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

$table    = 'termin';
$form_id  = isset($_REQUEST['termin_id']) === true ? $_REQUEST['termin_id'] : '';
$location = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {

   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;

   require($permission->getActionFilePath());
}

show_record($smarty, $db, $fields, $table, $form_id, '', '');

$patient_name  = dlookup($db, $tbl_patient, "CONCAT_WS(', ', nachname, vorname)", "patient_id = {$patient_id}");

$smarty
   ->assign('patient_name', $patient_name)
   ->assign('button', get_buttons($table, $form_id, $statusLocked));

function ext_err( $valid ) {

	$valid->condition_and('$erinnerung == "1"', array('erinnerung_datum', '!erinnerung_datum'));

}

?>