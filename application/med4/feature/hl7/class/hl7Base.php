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

class hl7Base
{
   public static $sections = array(
       'importPatient',
       'importVorlageKrankenversicherung',
       'importAufenthalt',
       'importBehandler',
       'importErkrankung',
   );

   /**
    * db
    * @var db_ressource
    */
   protected $_db = null;

   protected $_smarty = null;

   protected $_params = array(
       'updateOnly' => false,
       'importFilter' => false,
       'selectedDisease' => null
   );

   protected $_processHash = null;

   /**
    * HL7 Main Settings
    * @var array
    */
   private $_settings = array();

   /**
    * HL7 Automatic Import Sections
    * @var array
    */
   private $_autoSections = array();

   /**
    * HL7 Field Settings
    * @var array
    */
   private $_fieldSettings = array();

   /**
    * Hl7 Base Filter
    *
    * @var array
    */
   private $_filter = array();

   /**
    * HL7 Lookups
    * @var array
    */
   private $_lookups = array();

   /**
    * HL7 Ukeys
    * @var unknown_type
    */
   private $_ukeys = array();

   private $_delimiter = null;


   public function __construct() {
      $this->_delimiter = '#' . chr(134) . chr(135) . chr(134) . chr(135) . '#';
   }

   public function setProcessHash($hash) {
       $this->_processHash = $hash;

       return $this;
   }

   public function getProcessHash() {
       return $this->_processHash;
   }


   protected function _isFilled($dataset, $key) {
        return (array_key_exists($key, $dataset) === true && strlen($dataset[$key]) > 0);
   }


   /**
    * Patient Identifikation
    * standard ident from hl_7cache
    *
    * @return integer
    */
   protected function _identPatient($ukey, $dataset, $table = 'hl7_cache', $isolate = null, $returnDataset = false)
   {
       $whereCondition = $this->buildCondition($this->getUkey($ukey), $dataset);

       if ($isolate !== null) {
           $isolateIds = array();

           foreach ($isolate as $id) {
               $isolateIds[] = $id['id'];
           }

           $isolate = count($isolateIds) > 0 ? " AND {$table}_id IN (" . implode(',', $isolateIds) . ")" : null;
       }

       $select = $returnDataset === true ? '*' : "{$table}_id as 'id'";

       $cacheEntries = sql_query_array($this->_db, "SELECT {$select} FROM `{$table}` WHERE {$whereCondition} {$isolate} ORDER BY {$table}_id ASC");

       if ($ukey === 'ukey') {
           // if more then one or no name entry exists
           if (count($cacheEntries) != 1) {
               return $this->_identPatient('patientid', $dataset, $table, $cacheEntries, $returnDataset);
           }
       }

       if ($returnDataset === true) {
           $data = count($cacheEntries) > 0 ? reset($cacheEntries) : array();
       } else {
           $data = count($cacheEntries) > 0 ? reset(reset($cacheEntries)) : null;
       }

       return $data;
   }


    /**
     *
     *
     * @access
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value)
   {
       $this->_params[$name] = $value;

       return $this;
   }

   public function getParam($name)
   {
       return (array_key_exists($name, $this->_params) === true
          ? $this->_params[$name]
          : NULL
       );
   }


     /**
    * build condition
    *
    * @param unknown_type $ukey
    * @param unknown_type $dataset
    */
   public function buildCondition($ukey, $dataset)
   {
      $whereCondition = '';

      foreach ($ukey as $index => $field) {
         if ($index > 0) {
            $whereCondition .= ' AND ';
         }

         $whereCondition .= $field . " = '" . $this->_escape($dataset[$field]) . "'";
      }

      return $whereCondition;
   }

   protected function _escape($string)
   {
       $escapedString = '';

       if (strlen($string) > 0) {
           $escapedString = mysql_real_escape_string(htmlspecialchars_decode($string));
       }

       return $escapedString;
   }


   protected function _buildSql($type, $table, $dataset)
   {
       foreach ($dataset as $key => $value) {
          $dataset[$key] = strlen($value) > 0
             ? "'" . $this->_escape($value) . "'"
             : "NULL"
          ;
       }

       $sql = ($type === 'insert' ? 'INSERT INTO ' : 'UPDATE ') . $table;

       if ($type === 'insert') {
           $sql .= '(' . implode(',', array_keys($dataset)) . ') VALUES (' . implode(',', array_values($dataset)) . ')';
       } else {
           $tmp = array();

           $identifier = $dataset["{$table}_id"];
           unset($dataset["{$table}_id"]);

           foreach ($dataset as $key => $value) {
               $tmp[] = $key . ' = ' . $value;
           }

           $sql .= ' SET ' . implode(', ', $tmp) . " WHERE {$table}_id = {$identifier}";
       }

       return $sql;
   }

   /**
    * Merges values from second array with the first array if value exist
    *
    * @param $old
    * @param $new
    */
   public function mergeDataset($base, $ext)
   {
      $dataset = $base;

      foreach ($ext as $key => $value) {
         if (strlen(trim($value)) > 0) {
            $dataset[$key] = $value;
         }
      }

      return $dataset;
   }

   /**
    * returns hl7 delimiter
    *
    * @return type
    */
   public function getDelimiter() {
      return $this->_delimiter;
   }

   /**
    * set Hl7 Settings
    * @param $settings
    */
   public function setSettings(array $settings)
   {
      $this->_settings = $settings;

      return $this;
   }


   /**
    * get Hl7 Settings
    */
   public function getSettings($defined = null)
   {
      $return = $defined === null
         ? $this->_settings
         : (array_key_exists($defined, $this->_settings) === true
            ? $this->_settings[$defined]
            : null
      );

      return $return;
   }


   /**
    * Set Hl7 Lookups
    * @param $l
    * @return hl7Writer
    */
   public function setLookups(array $lookups)
   {
      $this->_lookups = $lookups;

      return $this;
   }


   /**
    * getHl7 Lookups
    *
    * @param $class
    */
   public function getLookups($class = null, $code = null, $restriction = null)
   {
      $return = null;

      //Beides Muss vorhanden sein
      if (strlen($class) > 0 && strlen($code) > 0 && isset($this->_lookups[$class][$code]) === true) {
          $return = $this->_lookups[$class][$code];

          //Wert muss sich innerhalb der Restrictions befinden
          if ($restriction !== null && isset($restriction[$return]) === false) {
              $return = null;
          }
      }

      return $return;
   }


   /**
    * Set selected HL7 Patient identification ukey (cache)
    * @param   array
    * @return  hl7Writer
    */
   public function setUkeys(array $ukeys)
   {
      $this->_ukeys = $ukeys;

      return $this;
   }

   /**
    * get Hl7 Ukeys
    */
   public function getUkeys()
   {
      return $this->_ukeys;
   }


   public function setFilter($filter = array())
   {
       $this->_filter = $filter;

       return $this;
   }


   /**
    * returns filter when in FieldSettings
    *
    * @param $type
    */
   public function getFilter($type)
   {
       $return = array();

       foreach ($this->_fieldSettings as $fieldSettingName => $fieldSetting) {
          if (strpos($fieldSettingName, "{$type}.") !== false) {
              $return[] = str_replace("{$type}.", '', $fieldSettingName);
          }
       }

       return $return;
   }


   /**
    *
    *
    * @param $ukey
    */
   public function getUkey($ukey)
   {
      return $this->_ukeys[$ukey];
   }


   public function setAutoSections($autoSections)
   {
      $this->_autoSections = $autoSections;

      return $this;
   }

   public function getAutoSections()
   {
      return $this->_autoSections;
   }


   /**
    * sets the field settings of hl7
    *
    * @param $settings
    */
   public function setFieldSettings(array $settings)
   {
      foreach ($settings as $setting) {
         $this->_fieldSettings[$setting['med_feld']] = $setting;
      }

      return $this;
   }


   public function addFieldSetting($name = null, $setting = null)
   {
      if ($name !== null && $setting !== null) {
         $this->_fieldSettings[$name] = $setting;
      }

      return $this;
   }


   /**
    * get hl7 field settings
    */
   public function getFieldSettings($section = null)
   {
      $return = array();

      if ($section === null) {
         $return = $this->_fieldSettings;
      } else {
         foreach ($this->_fieldSettings as $fieldSettingName => $fieldSettingInformation) {
            if (strpos($fieldSettingName, "$section.") !== false &&
               isset($fieldSettingInformation['import']) === true && $fieldSettingInformation['import'] == 1) {
                $fieldSettingInformation['feld'] = substr($fieldSettingInformation['med_feld'],strlen($section . '.'));
                $return[] = $fieldSettingInformation;
            }
         }
      }

      return $return;
   }

    /**
    * wandelt einen zeitstempel in ein richtiges format
    *
    * @param $ts
    */
   public function toDate($ts)
   {
      $ts = $this->findFirst($ts);

      if (strlen($ts) >= 8) {
         $y = substr($ts, 0, 4);
         $m = substr($ts, 4, 2);
         $d = substr($ts, 6, 2);

         $value = implode('-', array($y, $m, $d));
      } else {
         $value = $ts;
      }

      return trim($value);
   }

    /**
     * refreshes hl7 tables
     *
     */
    public function refresh($target, $timeField, $select)
    {
        $time = (int) $this->getSettings("max_{$timeField}_time");
        $target = explode('.', $target);

        if ($time !== null && $time > 0 ) {
            $result = sql_query_array($this->_db, "
                SELECT
                    hl7_{$select}_id as 'id'
                FROM `hl7_{$target[0]}`
                WHERE
                    {$target[1]} < DATE_SUB(NOW(), INTERVAL {$time} DAY)
            ");

            if (count($result) > 0) {
                $ids = array();

                foreach ($result as $id) {
                    $ids[] = $id['id'];
                }

                $ids = implode(',', $ids);

                $queries = array(
                    //Main
                    "DELETE FROM `hl7_{$target[0]}` WHERE {$target[1]} < DATE_SUB(NOW(), INTERVAL {$time} DAY)"
                );

                // Delete Message files
                if ($target[0] == 'cache') {
                    $queries[] = "DELETE FROM `hl7_message` WHERE hl7_cache_id IN ($ids)";
                }

                foreach ($queries as $query) {
                    mysql_query($query, $this->_db);

                }
            }
        }

        return $this;
    }
}

?>
