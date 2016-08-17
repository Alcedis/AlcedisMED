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

function get_ajax_buttons ( $table, $statusLock = false )
{
   // Modi
   $access    = 'A';
   $forbidden = 'F';
   $insert    = 'I';
   $update    = 'U';
   $delete    = 'D';
   $allow_all = 'I|U|D';

   // Init
   $table       = explode( '.', $table );
   $table       = end( $table );
   $modus       = 'cancel';
   $zugriff     = $forbidden;
   $zugriff_arr = array();

   $_SESSION['sess_matrix'] = ( isset($_SESSION['sess_matrix']) ) ? $_SESSION['sess_matrix'] : array();

   foreach( $_SESSION['sess_matrix'] AS $key => $value)
      if( $value['tabelle'] == $table )
         $zugriff = $value['zugriff'];

   $zugriff = strtoupper( $zugriff );

   if (strpos($zugriff, '&') !== false) {
      $arr_zugriff = explode('&', $zugriff);
      reset($arr_zugriff);
      $zugriff = pos($arr_zugriff);
   }

   $hardForbidden = getDiseaseSaveRight();

   if ($statusLock == true) {
      $modus = 'img-lock_grey.' . $modus;
   }

   if ($zugriff == $forbidden || $hardForbidden === true || $statusLock) {
      return $modus;
   }

   if ($zugriff == $access) {
      $zugriff = $allow_all;
   }

   // Aus String Array bauen
   $zugriff_arr = explode('|', $zugriff);

   // Auf Modus abfragen
   if (in_array($insert, $zugriff_arr) === true && !isset($_REQUEST['sess_pos']))
      return 'insert.cancel';

   if (in_array($update, $zugriff_arr) && isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != '' )
      $modus = 'update.cancel';

   if (in_array($delete, $zugriff_arr) && isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != '' )
      $modus = 'delete.cancel';

   if (in_array($update, $zugriff_arr) && in_array($delete, $zugriff_arr) && isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != '' )
      $modus = 'update.delete.cancel';

   return $modus;
}

function get_delete_right ( &$content, $hard = false)
{
   foreach( $content AS $index => $list ){
      foreach( $list AS $head  => $volume ){
         if($head == 'table')
            $table = $volume;

         //Rechte holen
         if( $head == 'body_content'){
            foreach($_SESSION['sess_matrix'] AS $rindex){
              if($rindex['tabelle'] == $table){
                  $zugriff = $rindex['zugriff'];
                  if($zugriff == 'A')
                     $zugriff = 'DIU';

                  if( strlen(trim($zugriff)) == 0)
                     $zugriff = $rindex['standard'];
               }
            }

            foreach($volume AS $field => $field_content){
               if($field === 'BTN_DELETE'){
                  if(strpos($zugriff, 'D') === false || getDiseaseSaveRight() === true || $hard === true) {
                     // REVISIT: funktioniert nur, wenn der Löschen-Button das letzte Element ist!!!
                     unset($content[$index][$head][$field]);
                     array_pop($content[$index]['head_content']);
                  }
               }
            }

         }//if body...

      }  //ende der Listen
   }
   return $content;
}

function ajax_rawurldecode($fields)
{
   foreach($fields as $field => $field_content){
      if(isset($field_content['value'])){
         foreach($field_content['value'] as $field_content_index){
          $fields[$field]['value'][0] = strlen($field_content_index)
          ? rawurldecode($field_content_index)
          : '';
         }
      }
   }

   return $fields;
}


function ajax_poslist_insert ( &$smarty, &$db, &$fields, $action, $sess_typ)
{
   $fields   = ajax_rawurldecode($fields);

   if($action == 'insert'){
      $config      = $smarty->get_config_vars();
      insert_pos_sess( $db, $sess_typ, $action, $fields ,$config, true );
   }
}


function delete_pos_list ( $smarty, $db, $fields, $table, $sess_typ , $table_id )
{
//Gate 1: Löschen alle Einträge in der Datenbank
   $key       = get_primaer_key( $table );

   foreach( $_SESSION['pos_table'][$sess_typ] AS $index => $data ){
      //case 1: steckt in der Datenbank
      if(strlen($data[$key])){
         $value = $data[$key];
         action_delete( $smarty, $db, $fields, $table, $value, 'delete' );
         unset($_SESSION['pos_table'][$sess_typ][$index]);
      }else{
      //case 2: Flüchtige Daten (noch nicht im Hauptformular gespeichert)
         unset($_SESSION['pos_table'][$sess_typ][$index]);
      }
   }
}

?>
