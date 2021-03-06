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

$table      = 'sonstige_therapie';
$form_id    = isset( $_REQUEST['sonstige_therapie_id'] ) ? $_REQUEST['sonstige_therapie_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign( 'button',  $button );

function ext_err($valid) {

   //eCheck 2
   $valid->condition_and('in_array($studie, array("", "0")) == true', array('!studie_id'));

   //eCheck 4
   $valid->condition_and('in_array($endstatus, array("", "plan")) == true', array('!endstatus_grund'));

   //eCheck 5
   $valid->condition_and('$best_response == ""', array('!best_response_datum'));

   //eCheck 6
   $valid->condition_and('in_array($unterbrechung, array("", "0")) == true', array('!unterbrechung_grund && !unterbrechung_grund_sonst'));

   //eCheck 7
   $valid->condition_and('$unterbrechung_grund != "son"', array('!unterbrechung_grund_sonst'));

   //eCheck 8
   $valid->condition_and('$endstatus_grund == "" || $endstatus_grund !== "son"', array('!endstatus_grund_sonst'));
}

?>