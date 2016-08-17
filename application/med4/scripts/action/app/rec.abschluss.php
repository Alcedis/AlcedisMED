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

if (in_array($action, array('insert', 'update', 'delete')) === true) {
   $statusRefresh = statusRefresh::create($db, $smarty);
}

switch( $action )
{
    case 'insert':
        $no_error = action_insert( $smarty, $db, $fields, $table, $action, null, 'ext_err');

        if ($no_error) {
            foreach (sql_query_array($db, "SELECT erkrankung_id FROM erkrankung WHERE patient_id = '$patient_id'") as $erkrankung) {
                $erkrankungId = $erkrankung['erkrankung_id'];

                $statusRefresh->refreshDisease($erkrankungId);
            }

            $abschlussId = dlookup($db, $table, 'MAX(abschluss_id)', "patient_id = '{$patient_id}'");

            $fields = $widget->loadExtFields('fields/app/abschluss_ursache.php');
            insert_sess_db($smarty, $db, $fields, 'abschluss_ursache', $abschlussId, 'ursache', 'abschluss_ursache_id', 'abschluss_id');

            action_cancel($location);
        }

        break;

    case 'update':
        $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, null, 'ext_err');

        if ($no_error) {
            foreach (sql_query_array($db, "SELECT erkrankung_id FROM erkrankung WHERE patient_id = '$patient_id'") as $erkrankung) {
                $erkrankungId = $erkrankung['erkrankung_id'];

                $statusRefresh->refreshDisease($erkrankungId);
            }

            $fields = $widget->loadExtFields('fields/app/abschluss_ursache.php');
            insert_sess_db($smarty, $db, $fields, 'abschluss_ursache', $form_id, 'ursache', 'abschluss_ursache_id', 'abschluss_id');

            action_cancel($location);
        }

        break;

    case 'delete':
        $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action, null );

        if ($no_error) {
            foreach (sql_query_array($db, "SELECT erkrankung_id FROM erkrankung WHERE patient_id = '$patient_id'") as $erkrankung) {
                $erkrankungId = $erkrankung['erkrankung_id'];

                $statusRefresh->refreshDisease($erkrankungId);
            }

            deleteForm($db, $smarty, 'abschluss_ursache', $table, $form_id);

            action_cancel($location);
        }

        break;

   case 'cancel': action_cancel( $location ); break;
}

?>
