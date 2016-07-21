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

/** -------------------------------------------------------------------------------------------
 ** Datenbank Verbindung Öffnen
 ** und DB zurück geben
 **/

function dbConnect( &$smarty, $database="database" )
{
    $smarty->config_load( FILE_CONFIG_SERVER, $database );    // laden des Config Files

    $db_con    = $smarty->get_config_vars( );                           // Config Variablen in variable $config
    $db_return = mysql_connect( $db_con["db_host"], $db_con["db_user"], $db_con["db_passwd"] ) or die( "Unable to connect to database" );

    mysql_set_charset('latin1');

    $db_selected  = mysql_select_db( $db_con["db_name"], $db_return ) or die( "Unable to select database" );

    return $db_return;
}


/** -------------------------------------------------------------------------------------------
 ** Datenbank Verbindung schließen
 **/
function dbDisconnect ( $db )
{
    if( is_resource( $db ) )
        mysql_close( $db ); // close db
    else
        die( "Unable to close database" );
}


/** -------------------------------------------------------------------------------------------
 ** Setzt eine Query gegen die Datenbank ab und liefert das Ergebis
 ** in einem Array zurück
 **/
function sql_query_array ( $db, $query, $ergebnistyp=MYSQL_ASSOC )
{
   //PERFORMANCE DEBUG
 //  $query = str_replace('SELECT', 'SELECT SQL_NO_CACHE', $query);

   //print_arr($query);

    $result = mysql_query( $query, $db );
    if( is_resource( $result ) )
    {
        $return_array = array( );
        while ( $row = mysql_fetch_array( $result, $ergebnistyp ) )
            array_push( $return_array, $row );

        mysql_free_result( $result );
        return $return_array;
    }
    elseif( $result===false )
    {
        $error = mysql_error( $db );
        if( strlen( $error ) )
            die( "MySQL Error: " . $error . "<br>" . $query );
    }
    else
         return FALSE;
}




/** -------------------------------------------------------------------------------------------
 ** Speichert alle Werte aus dem array in Fields ()
 **/
function sqlArrayToInsertQuery($array, $table)
{
    $query = null;

    $whitelist = array('NOW()');

    if (count($array) > 0){
        $query  = "INSERT INTO `{$table}` (`" . implode("`,`", array_keys($array)) . '`) VALUES (';
        $values = array();

        // Attribut $fields['FELD']['value'] löschen, wenn vorhanden
        foreach ($array AS $value) {

            if (in_array($value, $whitelist) === true) {
                $values[] = $value;
            } else {
                $values[] = strlen($value) > 0
                    ? "'" . mysql_real_escape_string(htmlspecialchars_decode($value)) . "'"
                    : 'NULL'
                ;
            }
        }

        $query .= implode(',', $values) . ');';
    }

    return $query;
}

/** -------------------------------------------------------------------------------------------
 ** Retrieve input vars, trim spaces and return as array
 **/
function get_input_vars( )
{
   $REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

   $vars = $REQUEST_METHOD == 'POST' ? $_POST : $_GET;
   foreach ( $vars as $k=>$v )
   {
       if( is_array($v) )
         continue;

       if( get_magic_quotes_gpc() )
         $v = stripslashes($v);

       $vars[$k] = trim($v);
   }

   return $vars;
}

/** -------------------------------------------------------------------------------------------
 ** Hilfsfunktion zum Holen von einzelnen Datensätzen aus beliebigen Tabellen
 ** ist eine abgewandelte Kopie der alten dlookup()-Abfrage
 **/
function dlookup( $db, $table_name, $field_name, $where_condition )
{
    if (is_resource($db) === true && strlen($table_name) && strlen($field_name) && strlen($where_condition)) {
        $query = "SELECT " . $field_name . " FROM " . $table_name . " WHERE " . $where_condition;

        $result = sql_query_array($db, $query);

        $returnstring = isset($result[0]) ? $result[0][$field_name] : '';
    } else {
        $returnstring = "Es fehlt ein dlookup-Parameter (4 Stück!)";
    }

    return $returnstring;
}



/********************************************************************************
 **
 ** Action Handling - insert, update, delete, cancel
 **
 ********************************************************************************/

/**
 * remove captcha from fields
 */
function removeCaptcha(&$fields, $onlyValue = false)
{
    foreach ($fields as $field => $attr) {
        if ($field === 'captcha') {

            if ($onlyValue === true) {
                if (isset($fields[$field]['value'][0]) === true) {
                    $fields[$field]['value'][0] = null;
                }
            } else {
                unset($fields[$field]);
            }
        }
    }
}

/**
 * extends codes information
 *
 * @param $smarty
 * @param $fields
 */
function addCodeInformation ($db, &$smarty, &$fields) {
   $config = null;

   foreach ($fields AS $field => $attr) {
      if (isset($attr['type']) === true && in_array($attr['type'], array('code_icd', 'code_o3', 'code_nci', 'code_ops')) === true && empty($attr['value'][0]) == false) {

         $config = $config !== null ? $config : $smarty->get_config_vars();

         if ($attr['type'] === 'code_nci')
            continue;


         $fieldValue = reset($attr['value']);

         //Wenn ein textfeld zum codepicker vorhanden ist
         if (array_key_exists($field . '_text', $fields) === true) {

            $text = trim(reset($fields[$field . '_text']['value']));

            //Wenn das textfeld leer ist, code aus dem system laden
            if (strlen($text) === 0) {

               $fieldValue = reset($attr['value']);

               switch ($attr['type']) {

                  case 'code_ops':

                     $code = reset(sql_query_array($db, "
                        SELECT
                           IFNULL(v_ops.bez, l_ops.description) AS 'text'
                        FROM l_ops l_ops
                           LEFT JOIN vorlage_ops v_ops ON l_ops.code = v_ops.code
                        WHERE l_ops.code = '{$fieldValue}'
                     "));

                     break;

                  case 'code_o3':

                      $code = reset(sql_query_array($db, "
                        SELECT
                           description AS 'text'
                        FROM l_icdo3
                        WHERE code = '{$fieldValue}' AND sub_level = 'v'
                        UNION
                        SELECT
                           bez AS 'text'
                        FROM vorlage_icdo
                        WHERE code = '{$fieldValue}'
                     "));

                     break;

                  case 'code_icd':

                     $code = reset(sql_query_array($db, "
                        SELECT
                           IFNULL(v_icd.bez, l_icd.description) AS 'text'
                        FROM l_icd10 l_icd
                           LEFT JOIN vorlage_icd10 v_icd ON l_icd.code = v_icd.code
                        WHERE l_icd.code = '{$fieldValue}'
                     "));

                     break;
               }

               $fields[$field . '_text']['value'] = array($code['text']);
            }
         }
      }
   }
}

/** -------------------------------------------------------------------------------------------
 ** Funktion führt die Action insert aus
 **/
function action_insert( $smarty, $db, &$fields, $table, $action, $location='', $ext_err='', $ext_warn='', $skipStatus = false )
{
   form2fields( $fields );

   // Validierung Starten
   $validate =  validate_dataset( $smarty, $db, $fields, $ext_err, $ext_warn );

   if ($validate == false){
      return;
   }

   // Formular Aktion auswerten
   todate( $fields, 'en' );
   tofloat( $fields, 'en' );
   totime( $fields, 'en' );

   execute_insert( $smarty, $db, $fields, $table, $action, false, null, $validate);

   // Falls keine Fehler (wie Duplicate entry, ...)
   $error = $smarty->get_template_vars( 'error' );
   if( !strlen( $error ) AND strlen( $location ) ) {
      header( "location: $location" );
      exit;
   } elseif( !strlen( $error ) ) {
      return true;
   }
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt die Action update aus
 **/
function action_update( $smarty, $db, &$fields, $table, $value, $action, $location='', $ext_err='', $ext_warn='', $skipStatus = false )
{
   form2fields( $fields );

   // Validierung Starten
   $validate = validate_dataset( $smarty, $db, $fields, $ext_err, $ext_warn );

   if ($validate == false){
      return;
   }

   // Formular Aktion auswerten
   todate( $fields, 'en' );
   tofloat( $fields, 'en' );
   totime( $fields, 'en' );

   // PRIMARY Key bestimmen
   $key = get_primaer_key( $table );
   execute_update( $smarty, $db, $fields, $table, "$key = '$value'", $action, "", false, null, $validate);

   // Falls keine Fehler (wie Duplicate entry, ...)
   $error = $smarty->get_template_vars( 'error' );
   if( !strlen($error) AND strlen($location) ) {
      header( "location: $location" );
      exit;
   } elseif( !strlen( $error ) ) {
      return true;
   }
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt die Action delete aus
 **/
function action_delete($smarty, $db, &$fields, $table, $value, $action, $location='', $ext_err='', $ext_warn='', $skipStatus = false)
{
   $key   = get_primaer_key( $table );
   $query = "SELECT * FROM $table WHERE $key= '$value'";
   data2rec( $db, $fields, $query );

   // Formular Aktion auswerten
   todate( $fields, 'en' );
   tofloat( $fields, 'en' );
   totime( $fields, 'en' );

   // PRIMARY Key bestimmen
   $key = get_primaer_key( $table );
   execute_delete( $smarty, $db, $fields, $table,  "$key = '$value'", $action, $skipStatus );

   // Falls keine Fehler (wie Duplicate entry, ...)
   $error = $smarty->get_template_vars( 'error' );
   if( !strlen($error) AND strlen($location) )
   {
      action_cancel($location);
      exit;
   }
   elseif( !strlen( $error ) )
      return true;
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt die Action cancel aus
 **/
function action_cancel( $location='' )
{
   // zurück zu der Listen Ansicht
   if( strlen( $location ) ) {
      header( "location: $location" );
      exit;
   }
}

/********************************************************************************
 **
 ** Execute Funtionen - INSERT, UPDATE, DELETE
 **
 ********************************************************************************/

/** -------------------------------------------------------------------------------------------
 ** Funktion führt einen INSERT in die Datenbank aus
 **/
function execute_insert( $smarty, $db, $fields, $table, $action, $skipStatus = false, $updateUser = null, $validator = null)
{
   $updateUser = $updateUser !== null ? $updateUser : $_SESSION['sess_user_id'];

   if (isset($fields['createuser']) === true) {
      $fields['createuser']['value'][0] = $updateUser;
   }

   if (isset($fields['createtime']) === true) {
      $fields['createtime']['value'][0] = date("Y-m-d G:i:s");
   }

   addCodeInformation($db, $smarty, $fields);
   removeCaptcha($fields);

   addGhostFields($smarty, $fields, $table);

   $query = "INSERT INTO $table (" .
            fields2fieldlist( $fields ) .
            ") VALUES (" .
            fields2datalist( $fields ) .
            ")";

   //Nach diesem Funktionaufruf ist im Normalfall die Funktion beendet
   $result = action_query( $smarty, $db, $fields, $table, $query, $action, $skipStatus, $updateUser, $validator);
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt ein UPDATE in die Datenbank aus
 **/
function execute_update( $smarty, $db, $fields, $table, $where, $action, $query="", $skipStatus = false, $updateUser = null, $validator = null)
{
   $updateUser = $updateUser !== null ? $updateUser : $_SESSION['sess_user_id'];

   if( isset( $fields['updateuser'] ) )
      $fields['updateuser']['value'][0] = $updateUser;

   if( isset( $fields['updatetime'] ) )
      $fields['updatetime']['value'][0] = date("Y-m-d G:i:s" );

   $key       = get_primaer_key( $table );
   $tmp_field = $fields[$key];
   unset( $fields[$key] );

   addCodeInformation($db, $smarty, $fields);

   removeCaptcha($fields);

   addGhostFields($smarty, $fields, $table);

   if( !strlen($query) )
       $query = "UPDATE $table SET " . fields2updatelist( $fields ) . " WHERE $where";

    $fields[$key] = $tmp_field;

   //Nach diesem Funktionaufruf ist im Normalfall die Funktion beendet
    $result = action_query( $smarty, $db, $fields, $table, $query, $action, $skipStatus, $updateUser, $validator);
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt ein DELETE in der Datenbank aus
 **/
function execute_delete( $smarty, $db, $fields, $table, $where, $action, $skipStatus = false )
{
   if( isset( $fields['updateuser'] ) )
      $fields['updateuser']['value'][0] = isset($_SESSION['sess_user_id']) == true ? $_SESSION['sess_user_id'] : 0;

   if( isset( $fields['updatetime'] ) )
      $fields['updatetime']['value'][0] = date("Y-m-d G:i:s" );

    $query   = "DELETE FROM $table WHERE $where LIMIT 1";

    removeCaptcha($fields);

    //Nach diesem Funktionaufruf ist im Normalfall die Funktion beendet
    $result  = action_query( $smarty, $db, $fields, $table, $query, $action, $skipStatus);

    //if deleted Reference will be removed if exists
    if ($result === true) {

       $statusRefresh = statusRefresh::create($db, $smarty);

      foreach ($fields as $fieldName => $field) {
         if (array_key_exists('reference', $field) === true) {

            $reference = is_array($field['reference']) === false ? array($field['reference']) : $field['reference'];

            foreach ($reference as $referenceTable) {
               $referencedForms = sql_query_array($db, "
                  SELECT
                     r.*,
                     s.status_id as 'valid_status_id'
                  FROM `{$referenceTable}` r
                     LEFT JOIN `status` s ON s.form_id = r.{$referenceTable}_id AND s.form = '{$referenceTable}'
                  WHERE r.{$where}
               ");

               foreach ($referencedForms as $form) {
                  $query = "UPDATE `{$referenceTable}` SET {$fieldName} = NULL WHERE {$referenceTable}_id = '{$form[$referenceTable . '_id']}'";

                  mysql_query($query);

                  if (strlen($form['valid_status_id']) > 0) {
                     $statusRefresh->setStatusId($form['valid_status_id']);
                  }
               }
            }
         }
      }

      if (count($statusRefresh->getStatusIds()) > 0) {
         //Aktualisierte Formulare status refreshen
         $statusRefresh->refreshStatus();
      }
    }
}



/********************************************************************************
Hilfsfunktionen - Querys ausführen, Backup
********************************************************************************/

/** -------------------------------------------------------------------------------------------
 ** Query ausführen - INSERT, UPDATE, DELETE
 **/
function action_query( $smarty, $db, $fields, $table, $query, $action, $skipStatus, $updateUser = null, $validator = null)
{
   $duplicate_entry = 'Duplicate entry';

   //$result kann drei Werte haben: Resource ID (bei Insert, Update); FALSE (Problem aufgetreten); TRUE (bei Delete)
    $result = mysql_query( $query, $db );

    if( $result ) {  // Kann nur TRUE oder Resource ID sein

       if ($skipStatus == false) {
         write_status( $smarty, $db, $fields, $table, $action, true, $updateUser, false, null, $validator);
       }

      write_backup( $smarty, $db, $fields, $table, $action, $updateUser);

      $return = true;
    } else {         // Wenn dieser Teil des Scripts ausgeführt wird, ist ein Fehler aufgetreten
      $error = get_sql_error( $db );
      $error['query'] = $query;

      // Bei Duplicate Entry Fehlermeldung
      $smarty->config_load( FILE_CONFIG_DEFAULT, 'validator');
      $config = $smarty->get_config_vars(); // Config Variablen in variable $config

      // Wenn Duplicate Entry dann eigene Fehlermeldung anzeigen
        if( stripos($error['message'], $duplicate_entry) !== false) {
            $start = strpos($error['message'], "key '") + 5;
            $rest  = substr($error['message'], $start);
            $nextTic = strpos($rest, "'");

            $ukeyName = substr($rest, 0, $nextTic);

            if (array_key_exists($ukeyName, $config) === true) {
                $err = "<ul><li>{$config[$ukeyName]}</li></ul>";
            } else {
                $query       = "SHOW KEYS FROM $table WHERE KEY_NAME LIKE 'ukey%'";
                $result      = sql_query_array($db, $query);

                $felder      = array();
                $ghostFields = $smarty->widget->getGhostFields($table);

                foreach ($result as $keys) {
                    $feld = $keys['Column_name'];

                    if (array_key_exists($feld, $ghostFields) === false) {
                       $fieldName = isset($config[$feld]) ? $config[$feld] : '';
                       $felder[$keys['Key_name']][] = isset($fields[$feld]['value'][0]) ? ('<span class="' . $feld . '-' . $fields[$feld]['value'][0] . '">' . $fieldName . '</span>') : $fieldName;
                    }
                }

                $implode = count($felder) > 1 ? $felder[$ukeyName] : reset($felder);

                $err = count($implode) > 1 ? sprintf($config['msg_duplicate_multi'], implode(', ', $implode)) : sprintf($config['msg_duplicate_single'], reset($implode));
            }

            $smarty->assign( 'error', $err);
      } else {
         $smarty->assign( 'error', $error['message'] );
      }

      // Nur wenn kein Duplicate entry
      if( stripos( $error['message'], $duplicate_entry ) === false )
      {
         // Email senden
         error2mail( $smarty, $error );
      }

      $return = false;
   }

   return $return;
}


/** -------------------------------------------------------------------------------------------
 ** Schreibt den Status anhand der Validierungen und sonstiges in die Datenbank
 *
 **/
function write_status($smarty, $db, $fields, $table, $action, $directCall = true, $user = null, $dontUpdateErkrankung = false, $posDataSrc = null, $validator = null, $lockStatus = null)
{
   if ((isset($fields['patient_id']['value']) === true && reset($fields['patient_id']['value']) != '')) {

      $query   = null;
      $posData = $posDataSrc !== null ? $posDataSrc : (isset($_SESSION['pos_table']) === true ? $_SESSION['pos_table'] : array());

      //Workaround um pos formulare für den status refresh zugänglich zu machen
      if ($posDataSrc === null && count($posData) > 0) {
         $tmp = array();
         foreach ($posData as $posName => $posDataContent) {
            $tmp["{$table}_{$posName}"] = $posDataContent;
         }

         $posData = $tmp;
      }

      $status  = status::create($smarty, $db, $fields, $table, $action, null, $posData, $validator)
         ->setSessionWarn($directCall)
      ;

      $statusDataset = $status->getStatus($lockStatus);

      switch ($action) {

         case 'insert':

            $primaryKey = get_primaer_key($table);
            $createUser = $user !== null ? $user : (isset($_SESSION['sess_user_id']) === true ? $_SESSION['sess_user_id'] : '');

            $statusDataset['form_id']['value'] = array(dlookup($db, $table, "MAX({$primaryKey})", "createuser = '{$createUser}'"));

            $query = "
               INSERT INTO `status` (" .
               fields2fieldlist($statusDataset) .
               ") VALUES (" .
               fields2datalist($statusDataset) .
            ")";

            break;

         case 'update':

            $statusId = reset($statusDataset['status_id']['value']);

            $query = "UPDATE `status` SET " . fields2updatelist($statusDataset) . " WHERE status_id = '{$statusId}'";

            break;

         case 'delete':

            $statusId = reset($statusDataset['status_id']['value']);

            $query = "DELETE FROM `status` WHERE status_id = '$statusId'";

            //Special Case Referenced parent form
            $parentQuery = "UPDATE `status` SET parent_status_id = NULL WHERE parent_status_id = '{$statusId}'";

            mysql_query($parentQuery, $db);

            break;
      }

      if ($query !== null) {
         $statusResult = mysql_query($query, $db);

         //TODO
         $qs181TakeParent = in_array($table, array('qs_18_1_brust', 'qs_18_1_o')) ? true : false;

         statusReportParam::fire($table, reset($statusDataset['form_id']['value']), $qs181TakeParent);

         if ($statusResult === false) {
            $error = get_sql_error( $db );
            $error['query'] = $query;

            // Email senden
            error2mail( $smarty, $error );
         }

         //globalen erkrankungsstatus updaten
         if ($dontUpdateErkrankung == false && (isset($statusDataset['erkrankung_id']['value'][0]) == true && strlen($statusDataset['erkrankung_id']['value'][0]) || $table == 'erkrankung')) {
            $erkId = $table == 'erkrankung' ? $statusDataset['form_id']['value'][0] : $statusDataset['erkrankung_id']['value'][0];

            $statusRefresh = statusRefresh::create($db, $smarty);

            $statusRefresh->refreshDisease($erkId);
         }
      }
   }
}


/** -------------------------------------------------------------------------------------------
 ** Backup-Datensatz in die a_ Tabellen einfügen
 **/
function write_backup( &$smarty, $db, &$fields, $table, $action, $updateUser = null)
{
   if( skip_write_backup($table) )
      return;

   $smarty->config_load( FILE_CONFIG_SERVER, 'database' );
   $smarty->config_load( FILE_CONFIG_SERVER, 'mail' );
   $config    = $smarty->get_config_vars( );

   $rows    = array();
   $result  = mysql_list_tables( $config['db_name'] );

   //alle Tabellen aus Datenbank in Array legen
   if( !$result )
      die ( 'DB Error, could not list tables<br> MySQL Error: ' . mysql_error( ) );

   while( $rows[] = mysql_fetch_row( $result ) )
      continue;
   mysql_free_result( $result );

   // gibt es die a_ Tabelle zu der aktuellen Tabelle?
   $gefunden = false;
   foreach( $rows AS $row_key => $row)
   {
      $a_table       = "_$table";
      $a_tablename   = "_$table";
      $arr_tmp = explode( '.', $table );
      if( count( $arr_tmp )>1 )
      {
         $a_tablename = '_' . $arr_tmp[1];
         $a_table     = $arr_tmp[0] . '._' . $arr_tmp[1];
      }

      if( $row[0] == $a_tablename )
      {
         // PRIMARY Key bestimmen
         $key   = get_primaer_key( $table );
         $value = isset( $fields[$key]['value'][0] ) ? $fields[$key]['value'][0] : '';

         if( $action == 'insert' ) // Es geht hier um einen Insert-Datensatz
         {

            $updateUser = $updateUser !== null ? $updateUser : $_SESSION["sess_user_id"];

            $id_new                   = dlookup( $db, "$table", "max($key)", "createuser = '$updateUser'" );
             $fields[$key]['value'][0] = strlen( $id_new ) ? $id_new : "-8888";
         }
         elseif( !strlen( $value ) )
         {
            // BEGIN dgr, dwi
            $fields[$key]['value'][0] = "-7777";

            if( $config['send_mail'] && !in_array($action, array( 'frage_senden', 'frage_erledigt', 'frage_nicht_erledigt', 'sign')) )
            {
               $mail_arr['_SERVER']  = $_SERVER;
               $mail_arr['_REQUEST'] = $_REQUEST;
               $mail_arr['_SESSION'] = $_SESSION;
               $mail_arr['fields']   = $fields;
               // $mail_arr['smarty']   = $smarty;

               ob_start();
                    print_r( $mail_arr );
                    $mail_arr = ob_get_contents();
                ob_end_clean();

               mail( 'error@alcedis.de', '-7777: Fehlende Form ID bei Insert in A Tabelle', $mail_arr, 'From: support@alcedis.de' );
            }
         }

         $query_backup  = "INSERT INTO $a_table (a_action, " . fields2fieldlist( $fields ) . ") VALUES ('$action', " . fields2datalist( $fields ) . ")";
         $result_backup = mysql_query( $query_backup, $db );

         // Archive Tabelle fehlerhaft
         if( !$result_backup )
         {
             $error = get_sql_error( $db );
             $error['query'] = $query_backup;

            // Email senden
            error2mail( $smarty, $error );
         }
         $gefunden = true;
         break;
      }
   }

   // Archive Tabelle nicht gefunden und Formular nicht in der Ignore Liste
   if( !$gefunden )
   {
      $error['date']    = date( 'Y-m-d H:i:s' );
      $error['creater'] = $_SESSION['sess_loginname'];
      $error['nr']      = '-9999';
      $error['message'] = $a_table . '--- keine Archiv Tabelle  gefunden';


      // Email senden
      error2mail( $smarty, $error );
   }
}

/** -------------------------------------------------------------------------------------------
 ** write_backup überspringen, wenn A-Tabellen über Datenbank-Trigger gehandhabt werden
 **/
function skip_write_backup($table)
{
   $skip = (strpos($table, 'hl7_') !== false);

   return $skip;
}


/********************************************************************************
 **
 ** Error Handling
 **
 ********************************************************************************/

/** -------------------------------------------------------------------------------------------
 ** MySQL Error aus dem DB Objekt holen
 **/
function get_sql_error( &$db )
{
   $return['date']    = date( 'Y-m-d H:i:s' );
   $return['creater'] = isset($_SESSION['sess_loginname']) === true ? $_SESSION['sess_loginname'] : null;
   $return['nr']      = mysql_errno( $db );
   $return['message'] = mysql_error( $db );

   return $return;
}


/** -------------------------------------------------------------------------------------------
 ** Den Error als E-Mail versenden
 **/
function error2mail( &$smarty, $error )
{
   $smarty->config_load( FILE_CONFIG_SERVER, 'database' );
   $smarty->config_load( FILE_CONFIG_SERVER, 'mail' );
   $config = $smarty->get_config_vars( );

   $subject   = "Fatal Error: " . $config['db_name'] . " at " . $config['db_host'] . ":" . $error['nr'];

   $modul   = isset($_SESSION['sess_modul'])  ? $_SESSION['sess_modul']  : '';
   $uri     = isset($_SERVER['REQUEST_URI'])  ? $_SERVER['REQUEST_URI']  : '';
   $server  = isset($_SERVER['REMOTE_ADDR'])  ? $_SERVER['REMOTE_ADDR']  : '';
   $host    = isset($_SERVER['HTTP_HOST'])    ? $_SERVER['HTTP_HOST']    : '';
   $host    = ($host == 'localhost' && isset($_ENV['COMPUTERNAME'])) ? $_ENV['COMPUTERNAME'] : $host;

   $message =
         "\nDatenbank Fehler\n"
      .  "\nZeit:      " . $error['date']
      .  "\nServer:    " . $server
      .  "\nLoginname: " . $error['creater']
      .  "\nHost:      " . $host
      .  "\nURI:       " . $uri
      .  "\nModul:     " . $modul
      .  "\nDatenbank: " . $config['db_name']
      .  "\nServer:    " . $config['db_host']
      .  "\nMessage:   " . $error['message'];

   if( isset( $error['query'] ) )
      $message .= "\n\n" . $error['query'];

   $send_mail  = isset( $config['send_mail'] )  ? $config['send_mail']    : false;
   $error_mail = isset( $config['error_mail'] ) ? $config['error_mail']   : '';

   if( $send_mail AND strlen( $error_mail ) )
      mail( $error_mail, $subject, $message, "From:" . $error_mail );
}

/********************************************************************************
 **
 ** Fields-Funktionen - FORM Werte in Fields, Datensatz in Fields
 **
 ********************************************************************************/

/** -------------------------------------------------------------------------------------------
 ** Speichert alle Werte aus dem Formular in Fields ($_REQUEST)
 **/
function form2fields( &$fields )
{
   // Attribut $fields['FELD']['value'] löschen, wenn vorhanden
    foreach( $fields AS $k => $f )
    {
       $value = isset( $_REQUEST[$k] ) ? $_REQUEST[$k] : '';

      if( isset( $fields[$k]['value'] ) )
            unset( $fields[$k]['value'] );

       $fields[$k]["value"][] = trim( $value );
    }
}


/** -------------------------------------------------------------------------------------------
 ** Speichert alle Werte aus dem array in Fields ()
 **/
function array2fields( $array, &$fields )
{
   // Attribut $fields['FELD']['value'] löschen, wenn vorhanden
   foreach( $fields AS $k => $f )
   {
      $value = isset( $array[$k] ) ? $array[$k] : '';

      if( isset( $fields[$k]['value'] ) )
         unset( $fields[$k]['value'] );

      $fields[$k]["value"][] = trim( $value );
   }
}

function dataArray2fields($array, $fields = null, $htbAvailable = false) {
   $fields  = is_array($fields) == true ? $fields : array();
   $array   = is_array($array) == true ? $array : array();

   foreach( $array AS $k => $f ) {
      if ($htbAvailable == true && isset($fields[$k]) == false) {
         continue;
      }

      $value = isset( $array[$k] ) ? $array[$k] : '';

      if( isset( $fields[$k]['value'] ) )
         unset( $fields[$k]['value'] );

      $fields[$k]["value"][] = trim( $value );
   }

   return $fields;
}



/** -------------------------------------------------------------------------------------------
 ** Legt Werte zu einer Query in Fields ($fields['FELD']['value'][0])
 ** - In Listen zu verwenden ($fields['FELD']['value'] = array(0 => Dataset1, 1 => Dataset2, ...))
 **/
function data2list($db, &$fields, $query, $auto_lookup=true)
{
    if (is_array($query) === true) {
        $result = $query;
    } else {
        $result = sql_query_array($db, $query);
    }

    //    alle Dropdowns aus den Fields holen
    $dropdowns = get_dropdowns_fields($fields);

    //    alle Dropdown Inhalte holen die von der Art Lookup sind
    if( isset( $dropdowns["lookup"] ) )
    {
        $_lookup = array();

        reset( $dropdowns["lookup"] );

        while( list( $tablename, $lookups ) = each( $dropdowns["lookup"] ) )
        {
            $l_query = "SELECT klasse, code, bez FROM $tablename WHERE klasse IN ( '" . implode( "', '", array_unique( $lookups ) )  . "' )";
            $data_lookup = sql_query_array ( $db,  $l_query );

            foreach( $data_lookup as $_cur_data ) {
               $_lookup[$tablename][$_cur_data["klasse"]][$_cur_data["code"]] = $_cur_data['bez'];
            }
        }
    }


   //    alle Datensätze holen die von der Art Code sind
   if( isset( $dropdowns["code"] ) )
   {
      $_code = array();
       reset( $dropdowns["code"] );

       foreach( $dropdowns["code"] AS $tablename )
       {
          $l_query = "SELECT * FROM $tablename WHERE 1";
           $data_code = sql_query_array ( $db,  $l_query );
           foreach( $data_code AS $_cur_data )
           {
              $bez  = isset( $_cur_data["description"] ) ? $_cur_data["description"] : '';
              $bez  = isset( $_cur_data["bez"] )         ? $_cur_data["bez"]         : $bez;
              $code = isset( $_cur_data["code"] )        ? $_cur_data["code"]        : '';

               $_code[$tablename][$code] = $bez;
          }
       }
   }

   //    alle Datensätze holen die von der Art Group sind
   if( isset( $dropdowns["group"] ) )
   {
      $_group = array();
       reset( $dropdowns["group"] );

       foreach( $dropdowns["group"] AS $tablename )
       {
          $l_query = "SELECT grp, code, bez FROM $tablename WHERE 1";
           $data_group = sql_query_array ( $db,  $l_query );
           foreach( $data_group as $_cur_data )
               $_group[$tablename][$_cur_data["code"]] = $_cur_data["bez"];
       }
   }

   //$result kann drei Werte haben: Resource ID (bei Insert, Update); FALSE (Problem aufgetreten); TRUE (bei Delete)
   foreach( $fields as $k => $v )
   {
       if( !is_array( $k ) )
           unset( $fields[$k]['value'] );
   }

   /**
       für jeden Daten Satz in Bez die Bezeichner setzten
   **/
   foreach ($result as $row) {
      foreach ($fields as $name => $field) {
         $fields[$name]["value"][] = isset($row[$name]) ? $row[$name] : NULL;

         if ($auto_lookup == false) {
            continue;
         }

         switch( $field["type"] ) {
             case "check":
                 $fields[$name]["bez"][] =( $row[$name]==1 ) ? "ja" : "nein";
                 break;
             case "radio":
                 // Wenn ext kein Array dann Fehlermeldung
                 if( !is_array( $field["ext"] ) )
                    echo "Warning: Das Feld '$name' ist vom Typ 'radio' trotzdem ist das Attribut 'ext' nicht belegt!<br><br>";

               if (count($field['ext']) == 1 && strlen(key($field['ext'])) > 2)
               {
                  $tablename = key($field['ext']);

                  $bez = dlookup($db, $tablename, 'bez', "klasse = '" . $field['ext'][$tablename] . "' AND code = '" . $row[$name] . "'");
                  $fields[$name]["bez"][] = $bez;
               }
               else
               {
                    if( isset( $field["ext"][$row[$name]] ) )
                       $fields[$name]["bez"][] = strtok( $field["ext"][$row[$name]], ',' );
                  else
                     $fields[$name]["bez"][] ='';
               }

                 break;
             case "lookup":
                if( strlen( $row[$name] ) )
                {
                   $bez = isset( $_lookup[key( $field["ext"] )][ pos( $field["ext"] ) ][ $row[$name] ] ) ? $_lookup[key( $field["ext"] )][ pos( $field["ext"] ) ][ $row[$name] ] : '';
                    $fields[$name]["bez"][] = $bez;
                 }
               else
                    $fields[$name]["bez"][] = '';

            break;

            case "code_icd":
                if( strlen( $row[$name] ) )
                {
                   $bez = dlookup( $db, 'l_icd10', 'CONCAT_WS(" - ", code, description)', 'code="' . $row[$name] . '"' );
                    $fields[$name]["bez"][] = $bez;
                 }
               else
                    $fields[$name]["bez"][] = '';
            break;

            case "code_o3":
                if( strlen( $row[$name] ) )
                {
                   $bez = dlookup( $db, 'l_icdo3', 'CONCAT_WS(" - ", code, description)', 'id="' . $row[$name] . '"' );
                    $fields[$name]["bez"][] = $bez;
                 }
               else
                    $fields[$name]["bez"][] = '';
            break;

            case "code_ops":
                if( strlen( $row[$name] ) )
                {
                   $code          = $row[$name];
                    $bez           = dlookup( $db, 'l_ops', 'description', "code='$code'" );

                    $fields[$name]["bez"][] = "$code - $bez";
                 }
               else
                    $fields[$name]["bez"][] = '';
            break;

            case "group":
                if( strlen( $row[$name] ) )
                {
                   $bez = isset( $_group[$field["ext"]][ $row[$name] ] ) ? $_group[$field["ext"]][ $row[$name] ] : '';
                    $fields[$name]["bez"][] = $bez;
                 }
               else
                    $fields[$name]["bez"][] = '';

            break;

            case "query":

                if( strlen( $row[$name] ) )
                {

                   $bez              = array( );
                   $query            = isset( $field["ext"] )     ? $field["ext"]     : '';
                   $query_arr[$name] = isset( $query_arr[$name] ) ? $query_arr[$name] : array( );

                   if( strlen( $query ) AND count( $query_arr[$name] )==0 ) {
                      $query_arr[$name] = sql_query_array( $db, $query, MYSQL_NUM);
                   }

                   if( count( $query_arr[$name] ) > 0 )
                   {
                     foreach( $query_arr[$name] AS $key => $value)
                     {
                        if( count ( $bez ) > 0 )
                           continue;

                        $bez = array( );

                        if( $value[0] == $row[$name] )
                        {
                           foreach( $value AS $key2 => $value2 )
                           {
                              if( $key2 != 0 )
                                 $bez[] = $value2;
                           }
                        }
                     }

                     $fields[$name]["bez"][] = implode( ', ', $bez );
                    }
                 }
               else
                    $fields[$name]["bez"][] = '';

            break;

            case "picker":

               if( strlen( $row[$name] ) )
               {
                  $bez              = array( );
                  $query            = isset( $field["ext"]['query'] )     ? $field["ext"]['query']     : '';
                  $query_arr[$name] = isset( $query_arr[$name] ) ? $query_arr[$name] : array( );

                  if( strlen( $query ) AND count( $query_arr[$name] )==0 )
                     $query_arr[$name] = sql_query_array( $db, $query, MYSQL_NUM);

                  if( count( $query_arr[$name] ) > 0 )
                  {
                     foreach( $query_arr[$name] AS $key => $value)
                     {
                        if( count ( $bez ) > 0 )
                           continue;

                        $bez = array( );

                        if( $value[0] == $row[$name] )
                        {
                           foreach( $value AS $key2 => $value2 )
                           {
                              if( $key2 != 0 )
                                 $bez[] = $value2;
                           }
                        }
                     }

                     $fields[$name]["bez"][] = implode( ', ', $bez );
                   }
                }
               else
                   $fields[$name]["bez"][] = '';

            break;


            case "query_ext":
               //MED spezifisch
               if( strlen( $row[$name] ) )
                {
                  $settings               = isset( $field["ext"] )     ? $field["ext"]     : array() ;

                  if( count( $settings ) > 0 ){
                     $typ  = isset( $settings['typ'] ) ? $settings['typ'] : false;

                     if( $typ == 0){
                        $tbl_l_vorlage          =  'vorlagen_studie';
                        $fields[$name]["bez"][] =  dlookup( $db, $tbl_l_vorlage, 'name', "studie_id = '$row[$name]'" );
                     }
                  }
               }
               else
                    $fields[$name]["bez"][] = '';

            break;
         }
      }
   }

   todate( $fields, "de" );
   tofloat( $fields, "de" );
   totime( $fields, 'de' );

   //html
   foreach ($fields as $index => $field) {
      if (array_key_exists('value', $field) === true && (isset($field['ext']['showHtml']) == false || $field['ext']['showHtml'] == false)) {

          foreach ($field['value'] as $i => $value) {
            $fields[$index]['value'][$i] = escape($value);
         }
      }
   }
}


/** -------------------------------------------------------------------------------------------
 ** Legt Werte zu einer Query in Fields ($fields['FELD']['value'][0])
 ** - In Rec Formularen  zu verwenden ($fields['FELD']['value'][0] = Dataset)
 **/
function data2rec ( $db, &$fields, $query )
{
    $result = mysql_query( $query, $db );
    if( !$result )
    {
        $error = mysql_error( $db );
        if( strlen( $error ) )
            die( "MySQL Error: " . $error );
    }

   //$result kann drei Werte haben: Resource ID (bei Insert, Update); FALSE (Problem aufgetreten); TRUE (bei Delete)
   foreach( $fields as $k => $v )
   {
       if( !is_array( $k ) )
           unset( $fields[$k]["value"] );
   }
   /**
       für jeden Daten Satz in Bez die Bezeichner setzten
   **/
   while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
   {
       foreach( $fields as $name=>$field )
       {
          $value = isset( $row[$name] ) ? $row[$name] : NULL;


           $fields[$name]["value"][] = $value;
       }
   }
   mysql_free_result( $result );
    todate( $fields, "de" );
    tofloat( $fields, "de" );
    totime( $fields, 'de' );
}


/** -------------------------------------------------------------------------------------------
 ** Aus den Feldnamen der Fields einen String für eine INSERT Query bauen
 **/
function fields2fieldlist( &$fields )
{
   $arr = array( );

    if( !is_array( $fields ) )
        user_error( "fieldlist not set",    E_USER_ERROR );

    foreach( $fields as $name=>$field )
      $arr[] = $name;

   $return = implode( ', ', $arr );
    return $return;
}

/**
 * rohdaten der fields werden ins Request gelegt
 *
 * @param $fields
 */
function fields2request( $fields )
{
   if( !is_array( $fields ) )
      user_error( "fieldlist not set", E_USER_ERROR );

   todate( $fields, "de" );
   tofloat( $fields, "de" );
   totime( $fields, 'de' );

   foreach ($fields as $fieldName => $field) {
      $_REQUEST[$fieldName] = reset($field['value']);
   }
}


function fields2dataArray( $fields )
{
    if( !is_array( $fields ) )
        user_error( "fieldlist not set", E_USER_ERROR );

    $return = array();

    todate( $fields, "en" );
    tofloat( $fields, "en" );
    totime( $fields, 'en' );

    foreach ($fields as $fieldName => $field) {
        $return[$fieldName] = isset($field['value'][0]) == true ? reset($field['value']) : null;
    }

    return $return;
}


/** -------------------------------------------------------------------------------------------
 ** Aus den Werten die in $fields['FELD']['value'][0] stehen einen String mit Daten für eine INSERT Query bauen
 **/
function fields2datalist( &$fields )
{
   $arr = array( );

    if( !is_array( $fields ) )
        user_error( "fieldlist not set",    E_USER_ERROR );

    foreach( $fields as $name => $field )
    {
        if (isset($field['value'][0]) === false) {
           $arr[] = "NULL";
        } else {
         $string  = strlen($field['value'][0]) > 0
            ? "'" . mysql_real_escape_string(htmlspecialchars_decode($field['value'][0])) . "'"
            : (isset($field['null']) ? "'" . $field['null'] . "'" : 'NULL');
         $arr[]   = $string;
        }
    }

    $return = implode( ', ', $arr );

   return $return;
}




/** -------------------------------------------------------------------------------------------
 ** Aus den Feldnamen und Feldwerten der Fields einen String bauen
 **/
function fields2updatelist( &$fields )
{
    $arr = array( );

    if( !is_array( $fields ) )
        user_error( "fieldlist not set",    E_USER_ERROR );

    foreach( $fields as $name => $field )
    {
       if (isset($field['value'][0]) === false) {
            $arr[] = "$name = NULL";
       } else {

          $string  = strlen($field['value'][0]) > 0
            ? "'" . mysql_real_escape_string(htmlspecialchars_decode($field['value'][0])) . "'"
            : (isset($field['null']) ? "'" . $field['null'] . "'" : 'NULL');

         $arr[] = "{$name} = {$string}";
       }
   }

   $return = implode( ', ', $arr );
    return $return;
}


/**
 * escape
 *
 * @param   mixed   $data
 * @param   mixed   $flags
 * @param   string  $charset
 * @return  mixed
 */
function escape($data, $flags = null, $charset = 'ISO-8859-1')
{
    if ($flags === null) {
        $flags = ENT_COMPAT | ENT_HTML401;
    }

    if (is_array($data) === true) {
        foreach ($data as $index => $value){
            $data[$index] = htmlentities($value, $flags, $charset);
        }
    } else {
        $data = htmlentities($data, $flags, $charset);
    }

    return $data;
}


/**
 * unescape
 *
 * @param   mixed $data
 * @param   mixed $flags
 * @param   string $charset
 * @return  mixed
 */
function unescape($data, $flags = null, $charset = 'ISO-8859-1')
{
    if ($flags === null) {
        $flags = ENT_COMPAT | ENT_HTML401;
    }

    if (is_array($data) === true) {
        foreach ($data as $index => $value){
            $data[$index] = html_entity_decode($value, $flags, $charset);
        }
    } else {
        $data = html_entity_decode($data, $flags, $charset);
    }

    return $data;
}


/**
 * convertHtmlspecialchars
 *
 * @param  mixed  $data
 * @param  mixed  $flags
 * @param  string $charset
 * @return mxied
 */
function convertHtmlspecialchars($data, $flags = null, $charset = 'ISO-8859-1')
{
    if ($flags === null) {
        $flags = ENT_COMPAT | ENT_HTML401;
    }

    if (is_array($data) === true) {
        foreach ($data as $index => $value){
            $data[$index] = htmlspecialchars($value, $flags, $charset);
        }
    } else {
        $data = htmlspecialchars($data, $flags, $charset);
    }

    return $data;
}


/********************************************************************************
 **
 ** Validierungsfunktionen die zur Validierung von Datensätzen nötig sind
 **
 ********************************************************************************/

/** -------------------------------------------------------------------------------------------
 ** Aus den Feldnamen und Feldwerten der Fields einen String bauen
 **/
function validate_dataset( &$smarty, &$db, &$fields, $ext_err='', $ext_warn='' )
{
   // Validator Instanziieren und DB Handle setzen

   $valid = new validator($smarty, $db, $fields);
   $valid->validate_fields ( $fields );

   if( strlen( $ext_err ) )
       $ext_err( $valid );
   // Fehlermeldungen erstellen so weit vorhanden

   $error_message = $valid->parse_block( 'err' );

   // Fehlermeldungen Anzeigen
   if (strlen($error_message)) {
      $smarty->assign( "error", $error_message );
      return false;
   } else {
       if (strlen($ext_warn)) {
          $ext_warn( $valid );
       }

       $warn_message = $valid->parse_block( 'warn' );
       // Fehlermeldungen Anzeigen

       if (strlen($warn_message)) {
         $_SESSION['sess_warn'][] = $warn_message;
       }
         return $valid;
   }
}


/** -------------------------------------------------------------------------------------------
 ** Zeigt das Record
 **/
function show_record( &$smarty, $db, &$fields, $table, $value, $query='', $ext_warn='', $preselected=false ) {
    $smarty->config_load(FILE_CONFIG_DEFAULT, 'validator'); // laden des Config Files

    $config  = $smarty->get_config_vars( );                   // Config Variablen in variable $config
    $error   = $smarty->get_template_vars( 'error' );         // gab es einen Error (Validierung, Datenbank)

    // Primärkey bestimmen
    $key = get_primaer_key( $table );

    $query = strlen($query) ? $query : "SELECT * FROM `{$table}` WHERE {$key} = '{$value}'";

    // Wenn ID mitgegeben wird und kein Error aufgetreten ist Werte aus der Datenbank holen
    if (strlen($value) AND !strlen($error)) {
       data2rec($db, $fields, $query);
    } else {
        form2fields($fields);
    }

    $disease = null;

    //check disease for itemgen
    if (array_key_exists('erkrankung_id', $fields) === true ) {
        $diseaseId = reset($fields['erkrankung_id']['value']);

        if (strlen($diseaseId) > 0) {
            $disease = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$diseaseId}'");
        }
    }

    removeCaptcha($fields, true);

   // zeige evtl die Warnungen
    if (!strlen($error) && strlen($ext_warn) && strlen($value)) {
        // Validator Instanziieren und DB Handle setzen
        $valid = new validator( $smarty, $db, $fields );
        $ext_warn($valid);

        $_warn_message = $valid->parse_block( 'warn' );

        // Warnmeldungen Anzeigen
        if (strlen($_warn_message)) {
            $smarty->assign( 'warn', array($_warn_message) );
        } else {
            unset( $_SESSION['sess_warnung'] );
        }

        todate( $fields,  'de' );
        tofloat( $fields, 'de' );
        totime( $fields, 'de' );
   }

   $item = new itemgenerator( $smarty, $db, $fields, $config );

   $item->setParam('disease', $disease);

   $item->preselected = $preselected;

   $item->generate_elements();
}


/** -------------------------------------------------------------------------------------------
 ** Hilfsfunktion, welche den Namen des Primary Keys anhand des Tebellennamens ausgibt
 ** Diese Funktion ist Privat
 **/
function get_primaer_key( $table )
{
    $query  = "SHOW FIELDS FROM $table";
    $result = mysql_query($query);

    while ($row = mysql_fetch_assoc($result))
    {
        if( $row['Key'] == 'PRI' )
          return $row['Field'];
    }

   return false;
}


/**
 ** Prüft die Fields auf enthaltene Dropdowns und
 ** gibt alle Dropdowns zurück
 **/
function get_dropdowns_fields( &$fields )
{
   $data = array( );

    if( is_array( $fields ) )
    {
        reset( $fields );
        while( list( $name, $obj ) = each( $fields ) )
        {
            switch( $obj["type"] )
            {
                case "lookup":
                    if( is_array( $obj["ext"] ) )
                        $data["lookup"][key( $obj["ext"] )][] = pos( $obj["ext"] );
                    else
                        echo "Warning: Fehlende Parameter bei Lookup Eingabe Element ( $name ).<br>";
               break;
               #case "code":
                #    if( is_array( $obj["ext"] ) )
                #        $data["code"][$obj["ext"]] = $obj["ext"];
                #    else
                #        echo "Warning: Fehlende Parameter bei Code Eingabe Element ( $name ).<br>";
                #break;
                case "group":
                    if( strlen( $obj["ext"] ) )
                        $data["group"][$obj["ext"]] = $obj["ext"];
                    else
                        echo "Warning: Fehlende Parameter bei Group Eingabe Element ( $name ).<br>";
                break;
                case "query":
                    if( strlen( $obj["ext"] ) )
                        $data["query"]["query"][] = $obj["ext"];
                    else
                        echo "Warning: Fehlende Parameter bei Query Eingabe Element ( $name ).<br>";
                    break;
            }
        }
    }
    return $data;
}


/** -------------------------------------------------------------------------------------------
 ** Hilfsfunktion, Erstelt SQL-Querys anhand des Formular Typs
 **/
function get_sql( $type='list', $query, $where='', $order='', $limit='' )
{
   $group_by = '';
   $arr_query = explode( 'GROUP BY', $query );
   if( isset( $arr_query[1] ) )
   {
      $query = $arr_query[0];
      $group_by = 'GROUP BY ' . $arr_query[1];
   }

   $sql = '';
   switch( $type )
   {
      case 'list':
         $sql = $query . ' ' . $where . ' ' . $group_by . ' ' . $order . ' ' . $limit;
      break;
      case 'rec':
         $sql = $query . ' ' . $where . ' ' . $group_by . ' ' . ' LIMIT 1';
      break;
      case 'pdf':
         $sql = $query . ' ' . $where . ' ' . $group_by . ' ' . $order;
      break;
      case 'menubar':
         $sql = $query;
      break;
      default:
         $sql = $query;
      break;
   }

   return $sql;
}

/**
 *
 * Alles Field, also auch die die eigentlich durch die Widgetfunktionalität entfernt werden würden
 * @param $smarty
 * @param $fields
 * @param $table
 */

function addGhostFields($smarty, &$fields, $table)
{
    $ghostFields = $smarty->widget->getGhostFields($table);

    foreach($ghostFields as &$ghostField) {
        $ghostField['value'] = array($ghostField['default']);
    }

    $fields = array_merge($fields, $ghostFields);
}
