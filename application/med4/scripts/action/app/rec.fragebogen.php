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

$ffFields = $widget->loadExtFields('fields/app/fragebogen_frage.php');

switch( $action )
{
   case 'insert':

      $no_error = action_insert($smarty, $db, $fields, $table, $action, '', 'validate_questions');

      if ($no_error) {
         $fragebogen_id = dlookup($db, $table, "MAX(fragebogen_id)", "erkrankung_id = '{$erkrankung_id}'");

         foreach ($_REQUEST['antwort'] as $key => $antwort) {

            $tmpFields = $ffFields;

            $dataset = array(
                'fragebogen_id'               => $fragebogen_id,
                'vorlage_fragebogen_frage_id' => $_REQUEST['vorlage_fragebogen_frage_id'][$key],
                'vorlage_fragebogen_id'       => $vorlage_fragebogen_id,
                'antwort'                     => $antwort,
                'patient_id'                  => $patient_id,
                'erkrankung_id'               => $erkrankung_id
            );

            array2fields($dataset, $tmpFields);

            execute_insert($smarty, $db, $tmpFields, 'fragebogen_frage', 'insert', true);
         }

         action_cancel($location);

      } else {
         $smarty->assign('antwort', isset($_REQUEST['antwort']) ? $_REQUEST['antwort'] : array());
      }

      break;

   case 'update':

      $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'validate_questions');

      if ($no_error) {

         $v_fb_id = $_REQUEST['vorlage_fragebogen_id'];

         foreach ($_REQUEST['antwort'] as $key => $antwort) {

            $v_fb_f_id = $_REQUEST['vorlage_fragebogen_frage_id'][$key];

            $tmpFields = $ffFields;

            $where = "fragebogen_id = '{$form_id}' AND
                      vorlage_fragebogen_id = '{$v_fb_id}' AND
                      vorlage_fragebogen_frage_id = '{$v_fb_f_id}'
            ";

            $dataset = reset(sql_query_array($db, "SELECT * FROM fragebogen_frage WHERE {$where}"));
            $dataset['antwort'] = $antwort;

            array2fields($dataset, $tmpFields);

            execute_update($smarty, $db, $tmpFields, 'fragebogen_frage', "fragebogen_frage_id = '{$dataset['fragebogen_frage_id']}'", 'update', '', true);
         }

         action_cancel($location);
      }

      break;

   case 'delete':

      $no_error = action_delete($smarty, $db, $fields, $table, $form_id, $action, '');

      if ($no_error) {

          $datasets = sql_query_array($db, "SELECT * FROM fragebogen_frage WHERE fragebogen_id = '{$form_id}'");

          foreach ($datasets as $dataset) {
              $tmpFields = $ffFields;
              array2fields($dataset, $tmpFields);

              action_delete($smarty, $db, $tmpFields, 'fragebogen_frage', $dataset['fragebogen_frage_id'], 'delete', '', '', '', true);
          }

          action_cancel($location);
      }

      break;

   case 'cancel': action_cancel( $location ); break;
}

?>