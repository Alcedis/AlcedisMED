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

function ajax_action ($smarty, $db, &$fields, $table, $value, $action, $ext_err='', $ext_warn='', $sess_typ, $sess_id, $skipStatus = true, $callback = null)
{
    if ($action == 'delete'){
        $sess_typ  = substr($_REQUEST['pos_delete'], 0                                         , strpos($_REQUEST['pos_delete'], '_'));
        $sess_id   = substr($_REQUEST['pos_delete'], (strpos($_REQUEST['pos_delete'], '_') + 1), strlen($_REQUEST['pos_delete']));
        $dataset = delete_pos($smarty, $db, $fields, $table, $sess_typ, $sess_id, $action );

        if ($callback !== null && function_exists($callback) === true) {
            $callback($smarty, $db, $dataset);
        }

        exit;
    }

   form2fields( $fields );

   $fields   = ajax_rawurldecode($fields);

   $validate =  validate_dataset_ajax($smarty, $db, $fields, $ext_err, $ext_warn, $table, $sess_typ, $sess_id);

   if($validate == '[]' && $action == 'insert'){
      $config      = $smarty->get_config_vars();

      $arr_success = array('success',$sess_typ);
      $validate    = json_encode( $arr_success );

      insert_pos_sess( $db, $sess_typ, $action, $fields ,$config );

      if ($callback !== null && function_exists($callback) === true) {
          $callback($smarty, $db, $fields);
      }
   }

   if($validate == '[]' && $action == 'update'){

      $arr_success = array('success',$sess_typ);
      $validate    = json_encode($arr_success);

      update_pos_sess( $fields, $sess_typ, $sess_id );

      todate(  $fields, 'en' );
      tofloat( $fields, 'en' );
      totime(  $fields, 'en' );

      //Update wenn schon in der Datenbank
      if ($value != ''){
         $key = get_primaer_key( $table );
         execute_update( $smarty, $db, $fields, $table, "$key = '$value'", $action, "", $skipStatus );

         statusReportParam::fire($table, $value, true);

         if ($callback !== null && function_exists($callback) === true) {
             $callback($smarty, $db, $fields);
         }
      }
   }

   echo $validate;

   exit;
}


/** -------------------------------------------------------------------------------------------
 ** Funktion führt die Ajax validierung usw aus
 **/
function validate_dataset_ajax ( $smarty, $db, &$fields, $ext_err='', $ext_warn='', $table = null, $sess_typ = null, $sess_id = null)
{
   $posTables = isset($_SESSION['pos_table'][$sess_typ]) ? $_SESSION['pos_table'][$sess_typ] : array();


   // Validator Instanziieren
   $valid = new validator_ajax($smarty, $db, $fields, $table, $posTables, $sess_id);
   $valid->validate_fields ( $fields );

   if (strlen($ext_err) > 0) {
       $ext_err($valid);
   }

   // Fehlermeldungen erstellen so weit vorhanden
   $error_message = $valid->parse_block( 'err' );

   //print_arr($error_message);
   //exit;

   // Fehlermeldungen Anzeigen
   if(  count($error_message) != 0 ){
      $error_message = json_encode( $error_message );
      return $error_message;
   }else{
      if (strlen($ext_warn) > 0) {
          $ext_warn($valid);
      }

      $warn_message = $valid->parse_block( 'warn' );
      $warn_message = json_encode( $warn_message );

      return $warn_message;
   }
}

?>
