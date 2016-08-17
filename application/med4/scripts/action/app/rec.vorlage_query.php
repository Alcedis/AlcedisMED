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
      $fileName      = dlookup($db, 'vorlage_query', $field, "vorlage_query_id = '$form_id'");
      $destination   = $upload->getDestination($field);

      download::create($destination . $fileName, 'zip')
         ->output();

      break;

  case 'active':

      if (isset($_REQUEST['inaktiv']) == false) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_query WHERE vorlage_query_id = '$form_id'"));
         unset($dataset['inaktiv']);

         $location   = get_url("page=rec.vorlage_query&vorlage_query_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_query_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

   case 'inactive':

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_query WHERE vorlage_query_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_query&vorlage_query_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_query_id = '$form_id'", 'update');

         action_cancel($location);
      }

      break;

    case 'insert':
   	$no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'ext_err');

   	if ($no_error) {

            $packageName = reset($fields['package']['value']);

            $form_id = dlookup($db, 'vorlage_query', 'vorlage_query_id', "package = '{$packageName}'");

            if (strlen($packageName) > 0) {
                $upload
                    ->moveTmp2Folder(array('package' => $packageName))
                ;

                //Extract new Package
                reportPackage::create()
                   ->setFile($upload->getDestination('package') . $packageName)
                   ->setTarget($upload->getUploadDir() . "doc/queries/{$form_id}")
                   ->extract()
                ;
            }

            $fields = $widget->loadExtFields('fields/app/vorlage_query_org.php');
            insert_sess_db($smarty, $db, $fields, 'vorlage_query_org', $form_id, 'org', 'vorlage_query_org_id', 'vorlage_query_id');

            action_cancel( $location );
        }

   	break;

    case 'update':

        $oldPackage = dlookup($db, $table, "package", "vorlage_query_id = '{$form_id}'");

        $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

   	if ($no_error) {

            $vfields = $widget->loadExtFields('fields/app/vorlage_query_org.php');
            insert_sess_db($smarty, $db, $vfields, 'vorlage_query_org', $form_id, 'org', 'vorlage_query_org_id', 'vorlage_query_id');

            $packageName = reset($fields['package']['value']);

            if ((strlen($packageName) > 0) && ($oldPackage !== $packageName)) {
                $upload
                    ->moveTmp2Folder(array('package' => $packageName), false)
   		    ->removeFile($oldPackage, 'package')
                ;

                //Extract new Package
                reportPackage::create()
                   ->setFile($upload->getDestination('package') . $packageName)
                   ->setTarget($upload->getUploadDir() . "doc/queries/{$form_id}")
                   ->extract()
                ;
            }

            action_cancel( $location );
        }

        break;

   case 'delete':

        $package = dlookup($db, $table, "package", "vorlage_query_id = '$form_id'");

        $upload
            ->removeFile($package, 'package')
        ;

        reportPackage::create()
            ->deletePackage($upload->getUploadDir() . "doc/queries/{$form_id}")
        ;

        action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location);

        break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel( $location );

      break;
}

?>
