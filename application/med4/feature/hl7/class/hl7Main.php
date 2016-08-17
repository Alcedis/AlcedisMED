<?php

/*
 * AlcedisMED
 * Copyright (C) 2010-2016  Alcedis GmbH
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */ 

require_once 'feature/hl7/class/hl7Base.php';

$hl7Path = 'feature/hl7/class/';

$hl7Classes = array_merge(
    array('base', 'parser', 'log'),
    hl7Base::$sections,
    array('writer', 'message')
);

foreach ($hl7Classes as $hl7Class) {
    require_once "{$hl7Path}/hl7" . ucfirst($hl7Class) . ".php";
}

/**
 * Class hl7Main
 * extends hl7Message
 */
class hl7Main extends hl7Message
{
   /**
    * @var hl7Main
    */
   private static $instance = null;


    /**
     * @param null $db
     * @param null $smarty
     */
    public function __construct($db = null, $smarty = null) {
       $this->_db = $db;
       $this->_smarty = $smarty;

       parent::__construct();
   }


   public static function create($db = null, $smarty = null) {
       return new self($db, $smarty);
   }


    /**
     *
     *
     * @static
     * @access
     * @param null $db
     * @param null $smarty
     * @return hl7Main
     */
    static public function getInstance($db = null, $smarty = null)
   {
       if (self::$instance === null) {
           self::$instance = hl7Main::create($db, $smarty);
       }

       return self::$instance;
   }


    /**
     * update given cache patients
     *
     * @access
     * @param      $cacheIds
     * @param bool $updateOnly
     * @return $this
     */
    public function processCache($cacheIds, $updateOnly = false)
    {
        $this
            ->setParam('updateOnly', $updateOnly)
            ->setParam('importFilter', true)
        ;

        foreach ($cacheIds as $cacheId) {
            $this->writeData($cacheId);
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    public function startImport()
    {
        $this
            ->setParam('updateOnly', false)
            ->setParam('importFilter', true)
        ;

        $cacheDatasets = sql_query_array($this->_db, "SELECT hl7_cache_id FROM hl7_cache WHERE hash = '{$this->getProcessHash()}'");

        foreach ($cacheDatasets as $dataset) {
           $this->writeData($dataset['hl7_cache_id']);
        }

        return $this;
    }


    /**
     * call manual import
     *
     * @access
     * @param $cacheId
     * @param $selectDisease
     * @return mixed
     */
    public function importCacheId($cacheId, $selectDisease)
    {
        return $this
            ->setParam('importFilter', true)
            ->setParam('selectedDisease', (strlen($selectDisease) > 0 ? $selectDisease : false))
            ->writeData($cacheId)
        ;
    }


    /**
     *
     *
     * @access
     * @return null
     */
    private function _getRandomCacheId()
    {
        $cacheId    = null;
        $offset     = dlookup($this->_db, 'hl7_cache', 'FLOOR(RAND() * COUNT(*))', 1);
        $count      = 0;

        if ($offset == 0) {
            $count = dlookup($this->_db, 'hl7_cache', 'COUNT(hl7_cache_id)', 1);
        }

        if ($offset > 0 || $count >= 1) {
            $dataset = reset(sql_query_array($this->_db, "SELECT * FROM hl7_cache LIMIT {$offset}, 1"));
            $cacheId = $dataset['hl7_cache_id'];
        }

        return $cacheId;
   }


   protected $_preview = false;


   public function getPreview()
   {
       return $this->_preview;
   }


   public function activatePreviewMode($cacheId = null)
   {
       $cacheId = $cacheId !== null ? $cacheId : $this->_getRandomCacheId();

       if ($cacheId !== null) {
           $msg = dlookup($this->_db, 'hl7_message', 'message', "hl7_cache_id = '{$cacheId}'");

           if (strlen($msg) > 0) {
                $this
                    ->parseMsg($msg)
                    ->setActiveMsg(0)
                ;

                $previewFields = $this->getFieldSettings();

                foreach ($previewFields as $name => $information) {
                    $previewFields[$name]['value'] = $this->getFieldValue($name, ' / ');
                }

                $previewMessage = end(reset($this->parseMsg($msg, true)));

                $output  = array();

                foreach ($previewMessage as $index => $part) {
                    $output[$index . '. ' . reset($part)] = print_r($part, true);
                }

                $this->_preview = array(
                    'msg' => $output,
                    'fields' => $previewFields
                );
           }

       }

       return $this;
   }


    /**
    * resets complete hl7 class
    */
    public function reset($section = 'complete')
    {
        //HL7 Message
        if (in_array($section, array('complete', 'message')) === true) {
            $this->_skipMessage = false;
        }

        //Hl7 parser
        if (in_array($section, array('complete', 'parser')) === true) {
          $this->_messages = array();
          $this->_raw_messages = array();
          $this->_activeMsg = 0;
        }

        //HL7 writer
        if (in_array($section, array('complete', 'writer')) === true) {

           $this->_infoMessages = array();
           $this->_patientId = null;
           $this->_diseaseId = null;
        }

        //HL7 log
        if (in_array($section, array('complete', 'log')) === true) {
           $this->_logData = array();
           $this->_logType = null;
           $this->_logStatus = null;
           $this->_logFilter = array();
        }

        return $this;
    }
}

?>
