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

class status
{
   protected $_smarty         = null;
   protected $_db             = null;
   protected $_fields         = array();
   protected $_table          = null;
   protected $_statusFields   = array();
   protected $_action         = null;
   protected $_sessionWarn    = true;
   protected $_headValidator  = null;

   protected $_configBuffer   = array();

   protected $_dListData = array();

   protected $_extensions = array(
      'patientOrgId'
   );


   /**
    * Constructor
    *
    * @param $db
    * @param $statusInformation
    * @param $fields
    */
   public function status($smarty, $db, $fields, $table, $action, $statusFields = null, $dlistData = array(), $headValidator = null)
   {
      $this
         ->_setSmarty($smarty)
         ->_setDb($db)
         ->_initConfig()
         ->_setFields($fields)
         ->_setTable($table)
         ->_setDlistData($dlistData)
      ;

      $this->_headValidator = $headValidator;

      $this->_action = $action;

      $this->_statusFields = $statusFields === null ? $this->_loadStatusFields() : $statusFields;

      return $this;
   }

   public static function create($smarty, $db, $fields, $table, $action, $statusFields = null, $dlistData = array(), validator $headValidator = null) {
      return new self($smarty, $db, $fields, $table, $action, $statusFields = null, $dlistData, $headValidator);
   }


   /**
    * return new status for inserting in status table
    *
    * @access  public
    * @return  array
    */
   public function getStatus($lockStatus = null)
   {
      $status = null;

      if ($this->_action !== 'insert') {
         //Status Datensatz laden
         $patientId     = $this->getFieldValue('patient_id');
         $table         = $this->_table;
         $formId        = $this->getFieldValue($table . '_id');

         $query = "
            SELECT
               *
            FROM status
            WHERE
               patient_id='$patientId' AND
               form = '$table' AND
               form_id = '$formId'
         ";

         $result = reset(sql_query_array($this->_db, $query));

         array2fields($result, $this->_statusFields);
      } else {
         //Bei Insert folgende Werte vorbelegen
         $this->_statusFields['patient_id']['value'][] = $this->getFieldValue('patient_id');
         $this->_statusFields['form']['value'][] = $this->_table;
      }

      //Status Script der aktuellen Seite laden
      $statusScriptPath = $this->_getStatusScriptPath();

      if ($statusScriptPath !== null) {
         if ($this->_action !== 'delete') {
            require($statusScriptPath);
            $this->_statusECheck($lockStatus);
         }
      } else {
         //Default
         $this->_statusFields['erkrankung_id']['value'][] = $this->getFieldValue('erkrankung_id');
      }

      return $this->_getParentStatusId();
   }


    protected function _statusECheck($lockStatus = null)
    {
        //Status green
        $status  = 4;
        $error   = array();

        //Wenn lock status per hand gesetzt wird, dann so lassen und kein validator nutzen
        if ($lockStatus !== null) {
           $this->setStatus('form_status', $lockStatus);
           return false;
        }

        $erkrankungIdVal = $this->getFieldValue('erkrankung_id');

        $disease = $erkrankungIdVal !== null
           ? dlookup($this->_db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungIdVal}'")
           : null
        ;


        $valid = new validator($this->_smarty, $this->_db, $this->_fields);

        $dkgManager = dkgManager::getInstance()
           ->setParam('form', $this->_table)
           ->setParam('disease', $disease)
        ;

        //1. Check Fields of Mainform
        foreach ($this->_fields as $fieldName => $field) {
           $fieldConditions = $dkgManager->getFieldConditions($fieldName);

           //Wenn er als X gelabelt oder y mit Condition
           if (array_key_exists('features', $field) === true || count($fieldConditions) > 0) {
              $isFilled = true;

              if (count($fieldConditions) > 0) {

                 foreach ($fieldConditions as $fieldInterface) {
                    foreach ($fieldInterface as $condition) {
                       $cond = $this->_extendCondition($condition);

                       $conditionIsTrue = $valid->condition($cond, '');
                       $fieldIsFilled = $valid->fields_req(array($fieldName), null, false);

                       if ($fieldIsFilled === false && $conditionIsTrue == true) {
                          $isFilled = false;
                       }
                    }
                 }

                 if ($this->_sessionWarn === true && isset($field['features']) === false) {
                    $field['features'] = array_keys($fieldConditions);
                 }
              } else {
                 $isFilled = $valid->fields_req(array($fieldName), null, false);
              }

              if ($isFilled === false) {
                 //Wenn Session Warn dann validator erweitern
                 if ($this->_sessionWarn === true) {
                    foreach ($field['features'] as $fieldInterface) {
                       $valid->register_type($fieldInterface, $fieldInterface);
                       $valid->enable_multimessage($fieldInterface, true);
                       $valid->enable_js($fieldInterface,    false);

                       $valid->set_msg($fieldInterface, 11, $fieldName, $this->getConfigLabel(array("status_{$fieldName}", $fieldName), $this->_table));
                       $error['interface'][$fieldInterface] = true;
                    }
                 }

                 $error['fields'][] = $fieldName;
              }
           }
        }

        $dkgConfig = $dkgManager->getLabels();

        //2. Check posdata of current pos form
        foreach ($dkgManager->getDlists() as $hubName => $dlists) {
            foreach ($dlists as $dlist) {
                //Alle Posformulare die zu diesem Formular gehören
                if (array_key_exists($dlist->getTable(), $this->_dListData) === true) {
                    foreach ($this->_dListData[$dlist->getTable()] as $data) {
                        $valid->setFields(dataArray2fields($data));

                        foreach ($dlist->getFields() as $dlistField) {
                            if ($dlistField->getCheck() === true) {
                               if ($valid->fields_req(array($dlistField->getName()), null, false) === false) {

                                  //Wenn Session Warn dann validator erweitern
                                  if ($this->_sessionWarn === true) {
                                     $valid->register_type($hubName, $hubName);
                                     $valid->enable_multimessage($hubName, true);
                                     $valid->enable_js($hubName,    false);

                                     $valid->set_msg($hubName, 11, $dlistField->getName(), $dkgConfig['pos']);
                                  }

                                  $error['interface'][$hubName] = true;
                               }
                            }
                        }
                    }
                }
            }
        }

        if (count($error) > 0) {
           //Session warn for interface error throw
           if ($this->_sessionWarn === true && array_key_exists('interface', $error) === true) {
              foreach (array_keys($error['interface']) as $interfaceError) {
                 $message = $valid->parse_block($interfaceError);

                 if (strlen($message) == true) {
                    $_SESSION['sess_feature_warn']['feature'][$dkgConfig['hub_' . $interfaceError]][] = $message;
                 }
              }
           }

           //Status yellow
           $status = 2;
        }
        //Sollte kein normaler Fehler aufgetreten sein, dann prüfe noch das Hauptformular
        elseif ($this->_headValidator !== null && strlen($this->_headValidator->parse_block( 'warn', true )) > 0) {
           $status = 2;
        }

        $this->setStatus('form_status', $status);
   }


    /**
     * very static condition extension
     *
     * @param $condition
     */
   private function _extendCondition($condition = '')
   {
       foreach ($this->_extensions as $extension) {
           if (strpos($condition, '$'.$extension) !== false) {
                switch ($extension) {
                    case 'patientOrgId':
                        if (array_key_exists('patient_id', $this->_fields) === true &&
                            (array_key_exists('value', $this->_fields['patient_id']) === true && is_array($this->_fields['patient_id']['value']) === true
                             && count($this->_fields['patient_id']['value']) == 1 && strlen(reset($this->_fields['patient_id']['value'])) > 0
                            )
                        ) {
                            $patient_id = reset($this->_fields['patient_id']['value']);
                            $orgId = dlookup($this->_db, 'patient', 'org_id', "patient_id = '$patient_id'");

                            $condition = str_replace('$'.$extension, $orgId, $condition);
                        }

                        break;
                }
           }
       }

       return $condition;
   }



   /**
    * Adds a parent status_id to status fields and returns whole status
    *
    */
   protected function _getParentStatusId()
   {
      $fields = $this->_getStatusFields();

      $parentStatus = array();

      foreach ($this->_fields as $field) {
         if (isset($field['parent_status']) === true) {

            $formId = trim($this->getFieldValue($field['parent_status'] . '_id'));

            if (strlen($formId)) {
               $parentStatus = array('form' => $field['parent_status'], 'form_id' => $formId);
            }
         }
      }

      $erkrankungId     = $this->getFieldValue('erkrankung_id');
      $patientId        = $this->getFieldValue('patient_id');
      $form             = isset($parentStatus['form']) === true      ? $parentStatus['form']    : null;
      $formId           = isset($parentStatus['form_id']) === true   ? $parentStatus['form_id'] : null;

      if ($erkrankungId !== null && $patientId !== null && $formId !== null && $form !== null) {
         $where_condition  = "erkrankung_id='$erkrankungId' AND patient_id='$patientId' AND form='$form' AND form_id='$formId'";

         $statusId = dlookup($this->_db, 'status', 'status_id', $where_condition);

         $fields['parent_status_id']['value'][0] = $statusId;
      } else {
         $fields['parent_status_id']['value'][0] = null;
      }

      return $fields;
   }


   protected function _getStatusFields()
   {
      return $this->_statusFields;
   }


   protected function _getStatusScriptPath()
   {
      $path = null;

      $scriptPath = 'scripts/status/app/' . $this->_table . '.php';

      if (file_exists($scriptPath) === true) {
         $path = $scriptPath;
      }

      return $path;
   }

   protected function _loadStatusFields()
   {
      return $this->_smarty->widget->loadExtFields('fields/app/status.php');
   }


   public function setSessionWarn($bool)
   {
      $this->_sessionWarn = $bool;

      return $this;
   }

   /**
    *
    * Set Status Field
    *
    * @param $field
    * @param $value
    */
   public function setStatus($field, $value)
   {
      if (isset($this->_statusFields[$field]) === true) {
         $value = $this->_attachStatusInformation($value);
         $this->_statusFields[$field]['value'][0] = $value;
      }

      return $this;
   }



   /**
    * return the value of the fieldname
    *
    * @param   $fieldName
    * @return  string
    */
   public function getFieldValue($fieldName)
   {
      $value = null;

      if (isset($this->_fields[$fieldName]['value'][0]) === true && strlen($this->_fields[$fieldName]['value'][0]) > 0) {
         $value = $this->_fields[$fieldName]['value'][0];
      }

      return $value;
   }


   /**
    * return the value of the fieldname
    *
    * @param   $fieldName
    * @param   $useLabel
    * @return  string
    *
    * useLabel = null
    * useLabel = array(1 = ..., 0 = ...)
    * useLabel = string <- if checkbox is checked
    *
    */
   public function getFieldDescription($fieldName, $useLabel = null)
   {
      $description = null;

      if (isset($this->_fields[$fieldName]['ext']) === true) {
         $ext     = $this->_fields[$fieldName]['ext'];
         $value   = $this->getFieldValue($fieldName);

         switch ($this->_fields[$fieldName]['type']) {
            case 'check':
               if ($value == 1) {
                  if ($useLabel === null) {
                     $description = $this->getConfigLabel($fieldName, $this->_table);
                  } else {
                     if (is_array($useLabel) === true) {
                        if (array_key_exists(1, $useLabel) === true) {
                           $description = $useLabel[1];
                        } else {
                           $description = $this->getConfigLabel($fieldName, $this->_table);
                        }
                     } else {
                        $description = $useLabel;
                     }
                  }
               } else {
                  if (is_array($useLabel) === true && array_key_exists(0, $useLabel) === true) {
                     $description = $useLabel[0];
                  }
               }

               break;

            case 'lookup':
               // Lookup Tabelle
               $table   = key($ext);
               // Lookup Klasse
               $klasse  = pos($ext);
               //Lookup Value

               $description = dlookup($this->_db, $table, 'bez', "klasse = '{$klasse}' AND code = '{$value}'");

               break;

            case 'query':
            case 'picker':
               if ($this->_fields[$fieldName]['type'] == 'picker') {
                   $ext = $ext['query'];
               }

               $result = sql_query_array($this->_db, $ext);

               foreach( $result AS $dataset) {
                  $count_elements = count($dataset);

                  //selected
                  if ($value == reset($dataset) AND strlen($value)) {
                     $indexedArray = array_values($dataset);

                     for ($nIndex=1; $nIndex < $count_elements; $nIndex++){
                        $output_arr[] = $indexedArray[$nIndex];
                     }

                     $description = implode(', ', $output_arr);

                     break;
                  }
               }

               break;
         }
      }

      return $description;
   }


    public function getFieldLabel($fieldNames, $table = null)
    {
        return $this->getConfigLabel($fieldNames, $table);
    }

    public function getConfigLabel($fieldNames, $table = null)
    {
        $label       = null;
        $fieldNames  = is_array($fieldNames) === false ? array($fieldNames) : $fieldNames;
        $table       = $table === null ? 'base' : $table;

        if (array_key_exists($table, $this->_configBuffer) === false) {
            $configBackup = $this->_smarty->get_config_vars();

            $this->_smarty->clear_config();
            $this->_smarty->config_load("app/{$table}.conf", 'rec');

            $this->_configBuffer[$table] = $this->_smarty->get_config_vars();
            $this->_smarty->clear_config();

            $this->_smarty->set_config($configBackup);
        }

        $config = $this->_configBuffer[$table];

        foreach ($fieldNames as $fieldName) {
            if (array_key_exists($fieldName, $config) === true) {
                $label = $config[$fieldName];
                break;
            }
        }

        return $label;
    }


  /**
    * setDb
    *
    * @param $db
    */
   protected function _setDb($db) {
      $this->_db = $db;

      return $this;
   }


   /**
    * setSmarty
    *
    * @param $smarty
    */
   protected function _setSmarty($smarty) {
      $this->_smarty = $smarty;

      return $this;
   }


   /**
    * set Fields
    *
    * @param $fields
    */
   protected function _setFields($fields) {
      $this->_fields = $fields;

      return $this;
   }


   /**
    * set Config
    *
    * @param $config
    */
   protected function _initConfig()
   {
      $this->_configBuffer['base'] = $this->_smarty->get_config_vars();

      return $this;
   }


   protected function _setDlistData($dlistData) {
      $this->_dListData = $dlistData;

      return $this;
   }


   /**
    * set Table
    *
    * @param $tableName
    */
   protected function _setTable($tableName) {
      $this->_table = $tableName;

      return $this;
   }

   protected function _attachStatusInformation($value) {

      if(is_array($value)) {
         foreach ($value as $k => $tmp) {
            //Wenn Wert ein Array ist, zu einem String zusammensetzen
            if (is_array($tmp)) {
               if (strlen($tmp['value'])) {
                  $add = '';

                  if (array_key_exists('add', $tmp)) {
                     $add = $tmp['add'];
                  }
                  $lbl       = isset($tmp['lbl']) === true ? $tmp['lbl'] : '';
                  $connector = isset($tmp['connector']) ? $tmp['connector'] : ' ';

                  $tmpArr = array($lbl, $tmp['value']);
                  $tmp = implode($connector, $tmpArr);

                  if (strlen($add)) {
                     $tmp .= ' ' . $add;
                  }
               } else {
                  $tmp = '';
               }
            }

            if (!strlen($tmp)) {
               unset($value[$k]);
            } else {
               $value[$k] = $tmp;
            }
         }

         $value = implode(' - ', $value);
      }

      return $value;
   }
}

?>