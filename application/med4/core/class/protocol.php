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

require_once 'dataCollector.php';
require_once 'alcOdt/alcXhtmlToPdf.php';

class protocol
{
   private $_db = null;

   private $_smarty = null;

   private $_type = null;

   private $_typeFormId = null;

   private $_param = array();

   private $_updateTime = true;

   private $_isConverted = false;

   private $_initiated = false;


   public static $kp = 'konferenz_patient';
   public static $br = 'brief';
   public static $zw = 'zweitmeinung';

   /**
    *
    * dataCollecotr
    * @var dataCollector
    */
   private $_dataCollector = null;

   /**
    *
    * alcOdtConverter
    * @var alcOdtConverter
    */
   private $_alcOdtConverter = null;


   public function __construct($db, $smarty, $init = true) {
      $this->_db              = $db;
      $this->_smarty          = $smarty;

      $this->_init($init);
   }

   public static function create($db, $smarty, $init = true){
      return new self($db, $smarty, $init);
   }


    /**
     * conferenceThreadConvert
     *
     * @access  public
     * @param   int $conferenceId
     * @return  void
     */
    public function conferenceDetachedConvert($conferenceId)
    {
        mysql_query("
            UPDATE
                konferenz_patient
            SET
                document_dirty  = 1,
                document_process = NULL,
                document_final = NULL
            WHERE konferenz_id = '{$conferenceId}'
        ");

        $query = "SELECT konferenz_patient_id as id FROM konferenz_patient WHERE konferenz_id = '{$conferenceId}'";

        foreach (sql_query_array($this->_db, $query) as $cpId) {
            $exec = "/usr/bin/php core/exec.php --feature=convert --page=convert_exec --type=kp --id={$cpId['id']} > /dev/null &";

            exec($exec);
        }
    }


    /**
     * _init
     *
     * @access  private
     * @param   bool    $init
     * @return  protocol
     */
    private function _init($init = true)
    {
        if ($init === true && $this->_initiated === false) {
            $this->_dataCollector   = dataCollector::create($this->_db, $this->_smarty);
            $this->_alcOdtConverter = alcOdtConverter::create($this->_db, $this->_smarty);

            $this->_initiated = true;
        }

        return $this;
    }


   /**
    * Dirty is currently only relevant for konferenz_patient
    *
    * @param $type
    * @param $typeFormId
    */
   public function dirtyCheck($type = null, $typeFormId = null)
   {
      if ($type !== null && $typeFormId !== null && in_array($type, array(self::$zw, self::$br, self::$kp)) === true) {
         $this->setType($type, $typeFormId);

         if ($this->_isDirty() === true) {
            $this
               ->_init()
               ->setConvertFilter('ergebnis')
               ->updateTime(false)
               ->generate(1)
               ->convertToPdf()
            ;
         }
      }

      return $this;
   }


    /**
     * waitForProtocol
     *
     * @access  public
     * @param   string  $type
     * @param   id      $id
     * @return  bool
     */
    public function waitForProtocol($type, $id)
    {
        if (in_array($type, array(self::$zw, self::$br, self::$kp)) === false) {
            return false;
        }

        $this->setType($type, $id);

        if ($this->_isInProcess() === true) {
            $max = 40;
            $cur = 1;

            do {
                if ($cur <= $max) {
                    sleep(1);
                } else {
                    return false;
                }

                $cur++;
            } while($this->_isInProcess());
        }

        if ($this->_isDirty() === true) {
            $this
                ->_init()
                ->setConvertFilter('ergebnis')
                ->updateTime(false)
                ->generate(1)
                ->convertToPdf()
            ;
        }

        return true;
    }



   /**
    * generates protocol
    */
   public function generate($process = 'NULL')
   {
      if ($this->_type === null || $this->_typeFormId === null) {
         echo 'No Protocol Type or Form Id given';
         exit;
      }

      $this
         ->_setProcess($process)
         ->_loadParameter()
         ->_loadData()
      ;

      $this->_alcOdtConverter
         ->convert()
      ;

      //Unset Param
      $this->_resetParam();

      return $this;
   }


   public function convertToPdf()
   {
      $alcConverter = alcXhtmlToPdf::create($this->_db, $this->_smarty)
         ->setContent('protokoll')
         ->setType($this->_type)
         ->setParam('id', $this->_typeFormId)
         ->setPdfName('protokoll')
      ;

      if (in_array($this->_type, array(self::$zw, self::$kp)) === true) {
         $alcConverter
            ->setExtension('ergebnis')
         ;
      }

      $alcConverter
         ->createPdf()
      ;

      if ($alcConverter->getStatus() == 'ok') {
         mysql_query("
            UPDATE
               {$this->_type}
            SET
               document_process = NULL,
               document_final = 1,
               document_dirty = NULL
            WHERE
               {$this->_type}_id = '{$this->_typeFormId}'
         ");

         $this->_isConverted = true;
      }

      return $this;
   }


   private function _loadData()
   {
      $this->_dataCollector
         ->param('filter', $this->param('filter'))
      ;

      $this->_dataCollector
         ->initPatient()
      ;

      $this->_alcOdtConverter
         ->addParseData('data', $this->_dataCollector->get('patient', 'DESC'))
      ;

      if ($this->_type === self::$kp) {
         $this->_alcOdtConverter
            ->addParseData('therapieplan', $this->_dataCollector->get('therapieplan'))
            ->addParseData('teilnehmer',   $this->_dataCollector->get('teilnehmer'))
         ;
      }

      if ($this->_type === self::$zw) {
          $this->_alcOdtConverter
              ->addParseData('therapieplan', $this->_dataCollector->get('therapieplan'))
          ;
      }

      return $this;
   }


   public function makeDirty($type = null, $typeFormId = null)
   {
      if ($type !== null && $typeFormId !== null) {
         $this->setType($type, $typeFormId);
      }

      mysql_query("
         UPDATE
            {$this->_type}
         SET
            document_dirty  = 1
         WHERE {$this->_type}_id = '{$this->_typeFormId}'
      ");

      return $this;
   }

   /**
    * check if protocol is dirty
    *
    */
   private function _isDirty()
   {
       return (strlen(dlookup($this->_db, $this->_type, 'document_dirty', "{$this->_type}_id = '{$this->_typeFormId}'")) > 0);
   }


   private function _isInProcess()
   {
       return (strlen(dlookup($this->_db, $this->_type, 'document_process', "{$this->_type}_id = '{$this->_typeFormId}'")) > 0);
   }


   /**
    * Set the process state of the protocol
    *
    */
   private function _setProcess($process = 'NULL')
   {
      $updateTime = $this->_updateTime === true ? 'datenstand_datum = NOW(),' : '';

      mysql_query("
         UPDATE
            {$this->_type}
         SET
            {$updateTime}
            document_process  = {$process},
            document_final    = NULL,
            document_dirty    = NULL
         WHERE {$this->_type}_id = '{$this->_typeFormId}'
      ");

      return $this;
   }


   /**
    * loads the protocol parameter like zip packet name ..
    *
    */
   private function _loadParameter()
   {
      //For konferenz patient, load only till date of conference
      $filter     = $this->_type === self::$kp ? "k.datum AS 'filter'," : null;
      $filterJoin = $this->_type === self::$kp ? "LEFT JOIN konferenz k ON k.konferenz_id = t.konferenz_id" : null;

      $result = reset(
         sql_query_array($this->_db, "
            SELECT
               t.*,
               v.package,
               v.doc_konferenz_immer,
               e.erkrankung,
               {$filter}
               p.org_id
            FROM {$this->_type} t
               LEFT JOIN erkrankung e ON e.erkrankung_id = t.erkrankung_id
               LEFT JOIN patient p ON p.patient_id = t.patient_id
               LEFT JOIN vorlage_dokument v ON t.vorlage_dokument_id = v.vorlage_dokument_id
               {$filterJoin}
            WHERE
               t.{$this->_type}_id = '{$this->_typeFormId}'
         ")
      );

      if (($this->param('filter') !== null && isset($result['filter']) === true) || (isset($result['filter']) === true && strlen($result['filter']) == 0)) {
         unset($result['filter']);
      }

      foreach ($result as $key => $value) {
         $this->_param[$key] = $value;
         $this->_dataCollector->param($key, $value);
      }

      $this->_alcOdtConverter
         ->setType($this->_type)
         ->setParam('id', $this->_typeFormId)
         ->registerFile('zip', 'tpl', $result['package'])
      ;

      //Special Case for konferenz_patient
      if (in_array($this->_type, array(self::$kp, self::$zw)) === true && strlen($result['doc_konferenz_immer']) === 0) {
         $this->_alcOdtConverter
            ->setRestriction('ergebnis', 'therapieplan')
         ;
      }

      return $this;
   }


   /**
    *
    * returns converted bool
    */
   public function isConverted()
   {
      return $this->_isConverted;
   }

   /**
    * Defines protocol type
    *
    * @param type
    */
   public function setType($type = null, $typeFormId = null)
   {
      if ($type !== null && is_string($type) === true && $typeFormId !== null){
         $this->_type         = $type;
         $this->_typeFormId   = $typeFormId;
      }

      return $this;
   }


   /**
    * Define which part will be converted
    * if nothing defined, all parts will be converted
    *
    * @param $filter
    */
   public function setConvertFilter($filter = null)
   {
      if ($filter !== null) {

         $filter = is_array($filter) === false ? array($filter) : $filter;

         foreach ($filter as $f) {
            $this
               ->_alcOdtConverter
               ->convertRestriction($f);
         }
      }

      return $this;
   }


   /**
    * defines if the protocol state set to NEW
    *
    * @param $reset
    * @return protocol
    */
   public function updateTime($update= true)
   {
      $this->_updateTime = $update;

      return $this;
   }

   /**
    * param
    *
    * @param $name
    * @param $value
    * @return protocol
    */
   public function param($name, $value = null)
   {
      if ($value === null) {
         return (array_key_exists($name, $this->_param) === true ? $this->_param[$name] : null);
      } else {
         $this->_param[$name] = $value;

         return $this;
      }
   }

   private function _resetParam()
   {
      $this->_param = array();

      return $this;
   }

}

?>
