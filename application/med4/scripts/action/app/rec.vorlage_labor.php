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

switch( $action )
{
   case 'active':

      if (isset($_REQUEST['inaktiv']) == false) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_labor WHERE vorlage_labor_id = '$form_id'"));
         unset($dataset['inaktiv']);

         $location   = get_url("page=rec.vorlage_labor&vorlage_labor_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_labor_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_labor WHERE vorlage_labor_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_labor&vorlage_labor_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_labor_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'verlaengern':

      $tmpFields = array(
         'vorlage_labor_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
         'gueltig_bis'      => array('req' => 2, 'size' => '',   'maxlen' => '',   'type' => 'date',      'ext' => '', 'range' => false),
         'gueltig_von'      => array('req' => 2, 'size' => '',   'maxlen' => '',   'type' => 'date',      'ext' => '', 'range' => false),
      );

      $_REQUEST['gueltig_von'] = dlookup($db, 'vorlage_labor', "DATE_FORMAT(gueltig_von, '%d.%m.%Y')", "vorlage_labor_id = '$form_id'");

      action_update( $smarty, $db, $tmpFields, $table, $form_id, 'update', $location, 'ext_err', '', true);

      break;


   case 'insert':

      if ($rolle_code == 'supervisor' || $freigabe == false) {
         $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err', '', true);

         if ($no_error) {
            $id = dlookup($db, $table, 'MAX(vorlage_labor_id)', "createuser = '$user_id'");

            $fields = $widget->loadExtFields('fields/app/vorlage_labor_wert.php');
            insert_sess_db($smarty, $db, $fields, 'vorlage_labor_wert', $id, 'wert', 'vorlage_labor_wert_id', 'vorlage_labor_id');

            action_cancel( $location );
         }
      }

      break;

   case 'update':

      if ($rolle_code == 'supervisor' || $freigabe == false) {
         $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err', '', true);

         if ($no_error) {
            $fields = $widget->loadExtFields('fields/app/vorlage_labor_wert.php');

            insert_sess_db($smarty, $db, $fields, 'vorlage_labor_wert', $form_id, 'wert', 'vorlage_labor_wert_id', 'vorlage_labor_id');
            action_cancel( $location );
         }
      }

      break;

   case 'delete':

      if ($rolle_code == 'supervisor' || $freigabe == false) {
         action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location );
      }

      break;

   case 'cancel': action_cancel( $location );                                                   break;
}

?>
