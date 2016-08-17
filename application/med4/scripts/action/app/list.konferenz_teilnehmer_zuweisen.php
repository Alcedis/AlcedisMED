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
   case 'update':
      if (isset($_REQUEST['buffer-id']) === true) {

         $buffer = json_decode($_REQUEST['buffer-id'], true);

         if (isset($buffer['add']) === true && count($buffer['add']) > 0) {

             $fields = $widget->loadExtFieldsOnce('fields/app/konferenz_teilnehmer.php');

             foreach (array_keys($buffer['add']) as $add_id) {
                $tmpFields = $fields;

                $data = array(
                   'konferenz_id'   => $konferenz_id,
                   'user_id'        => $add_id
                );

                array2fields($data, $tmpFields);

                execute_insert($smarty, $db, $tmpFields, 'konferenz_teilnehmer', 'insert', true);
             }

             mysql_query("
                UPDATE
                   konferenz
                SET
                   teilnehmer = (SELECT COUNT(konferenz_teilnehmer_id) FROM konferenz_teilnehmer WHERE konferenz_id = '{$konferenz_id}')
                WHERE
                   konferenz_id = '{$konferenz_id}'
             ", $db);
         }
      }

      action_cancel( $location );

      break;

    case 'cancel':

        action_cancel( $location );

        break;
}

?>