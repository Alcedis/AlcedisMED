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

$table      = 'untersuchung';
$form_id    = isset( $_REQUEST['untersuchung_id'] ) ? $_REQUEST['untersuchung_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;

   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $untersuchungLokalisationFields = $widget->loadExtFields('fields/app/untersuchung_lokalisation.php');
      $query = "SELECT * FROM $tbl_untersuchung_lokalisation WHERE untersuchung_id='$form_id' ORDER BY lokalisation";

      echo create_json_string(load_pos_sess($db, $tbl_untersuchung_lokalisation, $query, 'lokalisation', $untersuchungLokalisationFields, $config));
      exit;
   }
}

$erkrankungSeite = dlookup($db, 'erkrankung', 'seite', "erkrankung_id = '$erkrankung_id'");
if ($form_id == '' && in_array($erkrankungSeite, array('R', 'L')) && $action == NULL) {
    $_REQUEST['art_seite'] = $erkrankungSeite;
}

$button = get_buttons ( $table, $form_id, $statusLocked );
show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign( 'button',  $button );


function ext_err($valid) {

   //eCheck 4
   $valid->condition_and('$invasion == "1"' , array('', '!vorsorge_intervall && !invasion_detail'));
}

?>