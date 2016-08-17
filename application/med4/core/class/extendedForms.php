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

class extForm
{
   private $_table       = null;

   private $_db          = null;

   private $_config      = null;

   private $_smarty      = null;

   private $_posTable    = null;
   private $_posFormId   = null;
   private $_posFields   = null;

   private $_param       = array();

   private $_data        = null;

   private $_tplTable    = null;
   private $_tplFields   = null;

   private $_finalFields = null;

   private $_valid       = null;

   private $_errors      = array();

   private $_sortField   = null;

   public static function create($db, $smarty, $mainTable)
   {
      return new self($db, $smarty, $mainTable);
   }

   function __construct($db, $smarty, $mainTable)
   {
      $this->_db     = $db;
      $this->_table  = $mainTable;
      $this->_smarty = $smarty;

      $this->_smarty->config_load("base/default.conf", 'extForm');
      $this->_config = $this->_smarty->get_config_vars();
   }

   public function setFields($table, $form_id = '')
   {
      $this->_posTable = $table;
      $this->__posFormId = $form_id;

      $fieldsFile = "fields/app/$table.php";

      if (file_exists($fieldsFile)) {
         include $fieldsFile;
         if (isset($fields)) {
            $this->_posFields = $fields;
         }
      }

      if ($this->_posFields === null) {
         echo 'Fehler beim Laden der posFields ' . $table;
      }

      return $this;
   }

   public function setSortField($field)
   {
       $this->_sortField = $field;

       return $this;
   }

   public function setTemplate($table, $id, $param = array())
   {
      $this->_tplTable = $table;

      $fieldsFile = "fields/app/$table.php";

      $papaTable = explode('_', $table);
      array_pop($papaTable);
      $papaTable = implode('_', $papaTable);

      if (file_exists($fieldsFile)) {

         include $fieldsFile;

         if (isset($fields)) {

            $params = '';

            foreach ($param as $paramKey => $paramValue) {
               $params .= "AND {$paramKey} = '{$paramValue}'";
            }

            $sort = $this->_sortField !== null ? $this->_sortField : "{$table}_id";

            $query = "SELECT * FROM $table WHERE {$papaTable}_id = '$id' $params ORDER BY $sort";
            data2list($this->_db, $fields, $query);
            $this->_tplFields = $fields;
         }
      }

      if ($this->_tplFields === null) {
         echo 'Fehler beim Laden der tplFields ' . $table;
      }

      return $this;
   }

   //läd fields und baut templates für ajax antwort
   public function buildFormElements($assign = false)
   {
      $table     = $this->_table;
      $posTable  = $this->_posTable;
      $posFields = $this->_posFields;
      $form_id   = $this->_posFormId;
      $template  = $this->_tplFields;

      $this->_smarty->config_load("app/$table.conf", 'extForm');
      $this->_config = $this->_smarty->get_config_vars();
      $tplFile    = "templates/app/extForm/rec.$posTable.tpl";

      $this->_smarty->assign('template', $template);

      show_record( $this->_smarty, $this->_db, $posFields, $posTable, $form_id);

      if ($assign) {
         if (file_exists($tplFile)) {
            return "../" . $tplFile;
         } else {
            echo 'Template nicht gefunden ' . $tplFile;
         }
      } else {
         if (file_exists($tplFile)) {
            $tpl = $this->_smarty->fetch("../$tplFile");
         } else {
            echo 'Fehler beim Laden des Templates ' . $tplFile;
         }

         echo $tpl;
      }
   }

   //läd die Formulardaten, wenn formular aufgerufen oder validiert wird
   public function assignTemplate()
   {
      return $this->buildFormElements(true);
   }



   public function validateExtFields($request, $err = null, $validErr = '')
   {
      $return = true;
      $idFields = reset($request);
      $tplFields = $this->_tplFields;
      foreach($idFields as $idField => $fieldId) {
         $tmpFields = $this->_posFields;
         foreach ($tmpFields as $tmpFieldName => &$tmpField) {
            $tmpField['value'][0] =
               in_array($tmpFieldName, array_keys($request)) ? $request[$tmpFieldName][$idField] :
                  (isset($tplFields[$tmpFieldName]['value'][$idField]) ? $tplFields[$tmpFieldName]['value'][$idField] :
                     (isset($_REQUEST[$tmpFieldName]) ? $_REQUEST[$tmpFieldName] : ''));
         }

         $this->_finalFields[] = $tmpFields;
         //normale validierung
         $this->_valid = validate_dataset_ajax ( $this->_smarty, $this->_db, $tmpFields, $validErr);

         $error = json_decode($this->_valid);
         if (count($error)) {
            foreach ($error as $f => $m) {
               $this->_errors[$f][$idField][] = htmlspecialchars_decode($m->msg);
            }
         }
      }

      //additional checks
      if ($err !== null)
            $err($this);

      if (count($this->_errors)) {
         $return =  false;
         $this->_smarty
            ->assign('error', '<ul class="pos_error_msg"><li style="display:none;"><!-- --></li></ul>')
            ->assign('pos_errors', create_json_string($this->_errors));
      }
      return $return;
   }

   public function setError($error)
   {
      $this->_errors[] = $error;

      return $this;
   }

   //macht keine FEHLER!!!!! -.-
   public function actionInsert($params)
   {
      foreach ($this->_finalFields as $fields) {
         foreach ($params as $param => $val) {
            $fields[$param]['value'][0] = $val;
         }

         todate( $fields, 'en' );
         tofloat( $fields, 'en' );
         totime( $fields, 'en' );

         execute_insert( $this->_smarty, $this->_db, $fields, $this->_posTable, 'insert', true );
      }
   }


   /**
    * Update funktion fügt im zuge der migration auch beim update fall noch inserts hinzu =)
    * Enter description here ...
    * @param $pKey
    */
   public function actionUpdate($pKey)
   {
      foreach ($this->_finalFields as $fields) {
         todate( $fields, 'en' );
         tofloat( $fields, 'en' );
         totime( $fields, 'en' );

         $val   = $fields[$pKey]['value'][0];

         if (strlen($val) > 0) {
            //Update
            $where = "$pKey = '$val'";
            execute_update( $this->_smarty, $this->_db, $fields, $this->_posTable, $where, 'update', null, true );
         } else {
            //Insert
            execute_insert( $this->_smarty, $this->_db, $fields, $this->_posTable, 'insert', true );
         }
      }
   }

   public function actionDelete($pKey, $pVal)
   {
      foreach ($this->_finalFields as $fields) {
         todate( $fields, 'en' );
         tofloat( $fields, 'en' );
         totime( $fields, 'en' );

         $key   = reset(array_keys($fields));
         $val   = $fields[$key]['value'][0];
         $where = "$pKey = '$pVal' AND $key = '$val'";

         execute_delete( $this->_smarty, $this->_db, $fields, $this->_posTable, $where, 'delete', true);
      }
   }

   /**
    * converts request values to
    *
    * @param $values
    * @param $identifier
    */
   public function convertRequestValues($values, $identifier) {
      $data = array();

      foreach ($values as $fieldName => $fieldValues) {
         foreach ($fieldValues as $dataIndex => $value) {
            $data[$dataIndex][$fieldName] = $value;
         }
      }

      $output = array(
         'identifier' => $identifier,
         'data' => $data
      );

      return $output;
   }


   public function loadValues($fields = array(), $papaId, $identifier )
   {
      $table      = $this->_posTable;
      $papaTable  = $this->_table;

      $reqFields = '';

      foreach ($fields as $field) {
         $reqFields .= strlen($reqFields) ? ', ' . $field['field'] : $field['field'];
      }

      //Identifier anhängen
      $reqFields .= strlen($reqFields) ? ",{$identifier}" : $identifier;

      $sort = $this->_sortField !== null ? $this->_sortField : $this->_tplTable . "_id";
      $datasets    = sql_query_array($this->_db, "SELECT $reqFields FROM $table WHERE {$papaTable}_id = '$papaId' ORDER BY $sort");

      //Additional data changes
      $scriptFile = "scripts/app/extForm/rec.$table.php";
      if (file_exists($scriptFile)) {
         require $scriptFile;
      }

      foreach ($datasets as &$dataset) {
         foreach ($dataset as $datasetFieldName => &$datasetFieldValue) {
            foreach ($fields as $field) {
               if ($field['field'] === $datasetFieldName) {

                  $val = &$datasetFieldValue;

                  if ($val !== null) {
                     switch ($field['type']) {
                        case 'float':
                        case 'int': $val = tofloat( $val, 'de' );  break;
                        case 'date': todate( $val, 'de' ); break;
                        case 'time': totime( $val, 'de' ); break;
                     }
                  }
               }
            }
         }
      }

      $jsonData = array(
         'identifier' => $identifier,
         'data' => $datasets
      );

      return $jsonData;
   }

   public function setParam($paramName, $param) {
      $this->_param[$paramName] = $param;

      return $this;
   }

   public function getParam($paramName) {
      $param = array();

      if (array_key_exists($paramName, $this->_param) == true) {
         $param = $this->_param[$paramName];
      }

      return $param;
   }

   public function getParams() {
      return $this->_params;
   }


   public function getFinalFields()
   {
      return $this->_finalFields;

   }

   //extValid funktion um mindestens x Felder über mehrere Datensätze zu validieren
   public function minField($field, $count)
   {
       $fieldCount = 0;
       $wholeCount = 0;

       foreach ($this->_finalFields as $fields) {
            if (isset($fields[$field]['value'][0]) && strlen($fields[$field]['value'][0])) {
                $fieldCount++;
            }
            $wholeCount++;
       }

       if ($fieldCount < $count) {
           foreach ($this->_finalFields as $c => $tmp) {
               if ($wholeCount == $count) {
                    $this->_errors[$field][$c][] = $this->_config['err_pos'];
               } else {
                    $this->_errors[$field][$c][] = str_replace('###', $this->_config['lbl_' . $count],$this->_config['err_fill_one_min']);
               }
           }
       }
   }
}

?>
