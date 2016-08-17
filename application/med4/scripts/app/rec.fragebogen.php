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

$table      = 'fragebogen';
$form_id    = isset( $_REQUEST['fragebogen_id'] ) ? $_REQUEST['fragebogen_id'] : '';
$vorlage_fragebogen_id = isset($_REQUEST['vorlage_fragebogen_id']) ? $_REQUEST['vorlage_fragebogen_id'] : (strlen($form_id) ? dlookup($db, $table, 'vorlage_fragebogen_id', "fragebogen_id='$form_id'") : '');
$location   = get_url('page=view.erkrankung');

if ($ajax === true || $form_id != '' || $vorlage_fragebogen_id != '')
{
   $query = "
      SELECT
         vorlage_fragebogen_frage_id,
         frage,
         val_min,
         val_max
      FROM vorlage_fragebogen_frage
      WHERE vorlage_fragebogen_id = '{$vorlage_fragebogen_id}'
      ORDER BY
         vorlage_fragebogen_frage_id
   ";

   $fragen = sql_query_array($db, $query);

   if ($ajax === true) {
      echo create_json_string($fragen);
      exit;
   } else {
      foreach($fragen as &$frage) {
         $range = array('' =>"&nbsp;");
         foreach (range($frage['val_min'], $frage['val_max']) as $range_value)
            $range[$range_value] = $range_value;
         $frage['range'] = $range;
      }
      $smarty->assign('fragen', $fragen);

      if ($form_id != '') {
         $query = "SELECT antwort FROM fragebogen_frage WHERE fragebogen_id = '{$form_id}' ORDER BY vorlage_fragebogen_frage_id";
         $antworten = sql_query_array($db, $query);
         foreach ($antworten as &$antwort) {
            $antwort = reset($antwort);
         }
         $smarty->assign('antwort', $antworten);
      }
   }
}

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign( 'button',  $button );

function validate_questions($valid)
{
   $smarty = &$valid->_smarty;
   $config = $smarty->get_config_vars();

   $antworten = isset($_REQUEST['antwort']) ? $_REQUEST['antwort'] : array();

   //mindestens eine muss gefüllt sein
   if (count($antworten) > 0 && trim(implode($antworten)) == '') {
      $valid->set_err(12, array('fragebogen_id'), '', $config['msg_01']);
   }
}

?>