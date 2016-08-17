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

$doAction = false;

if ($form_id) {
   $query ="
      SELECT
         vt.freigabe
      FROM vorlage_therapie_wirkstoff vtw
         LEFT JOIN vorlage_therapie vt ON vt.vorlage_therapie_id = vtw.vorlage_therapie_id
      WHERE vtw.vorlage_therapie_wirkstoff_id = '$form_id'
   ";

   $result = reset(sql_query_array($db, $query));

   if ($result['freigabe'] != 1) {
      $doAction = true;
   }
} else {
   $doAction = true;
}

if ($doAction) {
   switch( $action )
   {
     case 'insert':
        ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', 'wirkstoff', null);

        break;

     case 'update':
         ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', $arr_sess['sess_typ'], $arr_sess['sess_id']);

         break;

     case 'delete':
         ajax_action( $smarty, $db, $fields, $table, null, $action, 'ext_err', 'ext_warn', null, null);

      break;
   }
}

?>