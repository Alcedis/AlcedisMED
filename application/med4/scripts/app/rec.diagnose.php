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

$table    = 'diagnose';
$form_id  = isset( $_REQUEST['diagnose_id'] ) ? $_REQUEST['diagnose_id'] : '';
$location = get_url('page=view.erkrankung') . "&erkrankung_id=$erkrankung_id";

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

if (in_array($erkrankung, array('leu', 'ly', 'snst')) === false) {
   $fields['diagnose']['ext']['showSide'] = true;
}

$erkrankungSeite = dlookup($db, 'erkrankung', 'seite', "erkrankung_id = '$erkrankung_id'");
if ($form_id == '' && in_array($erkrankungSeite, array('R', 'L')) && $action == NULL) {
    $_REQUEST['diagnose_seite'] = $erkrankungSeite;
}

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign( 'button',  $button );

?>