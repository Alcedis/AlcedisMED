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
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_studie WHERE vorlage_studie_id = '$form_id'"));
         unset($dataset['inaktiv']);

         $location   = get_url("page=rec.vorlage_studie&vorlage_studie_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_studie_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_studie WHERE vorlage_studie_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_studie&vorlage_studie_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_studie_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'file':
      $field         = reset(array_keys($_REQUEST['action']['file']));
      $fileName      = dlookup($db, $table, $field, "{$table}_id = '$form_id'");
      $destination   = $upload->getDestination($field);

      download::create($destination . $fileName, 'pdf')
         ->output();

      break;

   case 'insert':
      $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err');

      if ($no_error) {
         $upload->moveTmp2Folder(
            array(
               'krz_protokoll'   => $fields['krz_protokoll']['value'][0],
               'protokoll'       => $fields['protokoll']['value'][0]
            )
         );

         statusReportParam::fire('studie');

         action_cancel( $location );
      }

   break;

   case 'update':

      $krzProtokollDB   = dlookup($db, $table, "krz_protokoll", "vorlage_studie_id = '$form_id'");
      $protokollDB      = dlookup($db, $table, "protokoll", "vorlage_studie_id = '$form_id'");

      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

      if ($no_error) {

         if ($krzProtokollDB !== $fields['krz_protokoll']['value'][0]) {
            $upload
               ->moveTmp2Folder(array('krz_protokoll' => $fields['krz_protokoll']['value'][0]), false)
               ->removeFile($krzProtokollDB, 'krz_protokoll');
         }

         if ($protokollDB !== $fields['protokoll']['value'][0]) {
            $upload
               ->moveTmp2Folder(array('protokoll' => $fields['protokoll']['value'][0]))
               ->removeFile($protokollDB, 'protokoll');
         }

         statusReportParam::fire('studie');

         action_cancel( $location );
      }

      break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel( $location );

      break;
}

?>