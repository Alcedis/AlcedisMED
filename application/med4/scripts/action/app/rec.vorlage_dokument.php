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
   case 'file':
      $field         = reset(array_keys($_REQUEST['action']['file']));
      $fileName      = dlookup($db, 'vorlage_dokument', $field, "vorlage_dokument_id = '$form_id'");
      $destination   = $upload->getDestination($field);

      download::create($destination . $fileName, 'zip')
         ->output();

      break;


   case 'active':

      if (isset($_REQUEST['inaktiv']) == false) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_dokument WHERE vorlage_dokument_id = '$form_id'"));
         unset($dataset['inaktiv']);

         $location   = get_url("page=rec.vorlage_dokument&vorlage_dokument_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_dokument_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_dokument WHERE vorlage_dokument_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_dokument&vorlage_dokument_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_dokument_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'insert':
   	$no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'upload');

   	if ($no_error) {
      	$upload
            ->moveTmp2Folder(array('package' => $fields['package']['value'][0]));

      	action_cancel( $location );
      }
   	break;

   case 'update':

      $package = dlookup($db, $table, "package", "vorlage_dokument_id = '$form_id'");

      $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'upload');

   	  if ($no_error) {

   	   if ($package !== $fields['package']['value'][0]) {
            $upload
               ->moveTmp2Folder(array('package' => $fields['package']['value'][0]), false)
   		       ->removeFile($package, 'package')
            ;
   		}

      	action_cancel( $location );
      }

      break;

   case 'delete':

      $package = dlookup($db, $table, "package", "vorlage_dokument_id = '$form_id'");
      $upload
         ->removeFile($package, 'package');

      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location);

      break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel( $location );

      break;
}

?>