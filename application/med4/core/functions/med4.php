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
 ** Funktion, die anhand der Matrix Settings Buttons setzt
 **/

function get_buttons ( $table, $id, $statusLock = null, $confirm=false, $restict = null)
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

   $_SESSION['sess_matrix'] = isset($_SESSION['sess_matrix']) === true ? $_SESSION['sess_matrix'] : array();

   foreach( $_SESSION['sess_matrix'] AS $key => $value)
      if( $value['tabelle'] == $table )
         $zugriff = $value['zugriff'];

   $zugriff = strtoupper( $zugriff );

   if( strpos( $zugriff, '&' ) !== false )
   {
      $arr_zugriff = explode( '&', $zugriff );
      reset( $arr_zugriff );
      $zugriff = pos( $arr_zugriff );
   }

   $hardForbidden = getDiseaseSaveRight();

   if ($statusLock == true) {
      $modus = 'img-lock_grey.' . $modus;
   }

   if( $zugriff == $forbidden || $hardForbidden === true || $statusLock == true) {
      return $modus;
   }

   if ($zugriff == $access) {
      $zugriff = $allow_all;
   }

   $zugriff_arr = explode('|', $zugriff);

   if ($restict !== null) {
      if (is_array($restict) == false) {
         $restict = array($restict);
      }

      foreach ($restict as $restrictRight) {
         if (($zaKey = array_search($restrictRight, $zugriff_arr)) !== false) {
            unset($zugriff_arr[$zaKey]);
         }
      }
   }

   // Auf Modus abfragen
   if( in_array( $insert, $zugriff_arr ) AND !strlen( $id ) )
      return 'insert.cancel';

   if( in_array( $update, $zugriff_arr ) AND strlen( $id ) )
      $modus = 'update.cancel';

   if( in_array( $delete, $zugriff_arr ) AND strlen( $id ) )
      $modus = 'delete.cancel';

   if( in_array( $update, $zugriff_arr ) AND in_array( $delete, $zugriff_arr ) AND strlen( $id ) )
      $modus = 'update.delete.cancel';

   $modus = str_replace('delete', 'delete[btndelete]', $modus);

   if ($confirm === true) {
      $replace = array('insert' => 'insert[btnconfirm]', 'update' => 'update[btnconfirm]');

      foreach ($replace as $string => $repl) {
         $modus = str_replace($string, $repl, $modus);
      }
   }

   return $modus;
}

//If right of user was deleted, user will still be shown in dd!
//note: where clause will be injected right after WHERE statement!!!
function query_investigator($db, $query, $field, $table)
{
   $pKey    = get_primaer_key( $table );
   $form_id =  isset( $_REQUEST[$pKey]) ? $_REQUEST[$pKey] : null;
   if ($form_id !== null) {
      $altIds  = sql_query_array($db, "SELECT DISTINCT $table.$field FROM $table WHERE $pKey='$form_id'");
      $orIds   = array();
      foreach ($altIds as $id) {
         $orIds[] = $id['user_id'];
      }
                                 //todo: check if user_is can be $field
      $replaceString = "WHERE user.user_id IN('" . implode("','", $orIds) . "') OR ";
      $query = str_replace('WHERE', $replaceString, $query);
   }
   return $query;
}

function redirectTo($to, $msg = null)
{
    if ($msg !== null) {
        $_SESSION['back_function_error'] = $msg;
    }

    header("Location: $to");
    exit;
}

?>