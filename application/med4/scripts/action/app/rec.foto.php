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

        $fileName      = dlookup($db, 'foto', $field, "foto_id = '$form_id'");
        $fileType      = dlookup($db, 'foto', 'img_type', "foto_id = '$form_id'");
        $destination   = $upload->getDestination($field);

        download::create($destination . $fileName, $fileType)
            ->output(substr($fileName,14))
        ;

        break;

   case 'insert':

      $no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'upload');

      if ($no_error) {
         $upload->moveTmp2Folder(array('foto' => $fields['foto']['value'][0]));

         action_cancel($location);
      }

      break;

   case 'update':

      $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, $location, 'upload');

      break;

   case 'delete':

      $dateiDB = dlookup($db, $table, "foto", "foto_id = '$form_id'");
      $upload->removeFile($dateiDB, 'foto');

      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location);

      break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel($location);

      break;
}

?>