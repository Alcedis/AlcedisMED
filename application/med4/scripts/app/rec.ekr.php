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

$table      = 'ekr';
$form_id    = isset( $_REQUEST['ekr_id'] ) ? $_REQUEST['ekr_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty
   ->assign('button', $button)
   ->assign('bundesland', $bundesland)
;

function ext_warn($valid) {

   //eCheck 8
   $valid->condition_and('in_array($nachsorgeprogramm, array("", "0")) == true', array('!nachsorgepassnr && !nachsorge_user_id && !nachsorgetermin'), '', true);

}

function ext_err($valid) {

   //eCheck 4
   $valid->condition_and('in_array($sh_wohnort, array("", "1")) == true', array('!weiterleitung'));

   //eCheck 5
   $valid->condition_and('$weiterleitung == ""', array('!weiterleitung_datum'));

   //eCheck 7
   $valid->condition_and('$forschungsvorhaben == ""', array('!forschungsvorhaben_datum'));

}

?>