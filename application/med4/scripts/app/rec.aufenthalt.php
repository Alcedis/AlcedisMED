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

$table    = 'aufenthalt';
$form_id  = isset( $_REQUEST['aufenthalt_id'] ) ? $_REQUEST['aufenthalt_id'] : '';
$location = get_url('page=view.patient') . "&patient_id=$patient_id";

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign('button', $button);

if (isset($backPage) == false) {
   $smarty->assign('back_btn', "page=view.patient&amp;patient_id=$patient_id");
}


function ext_err($valid) {

   $smarty = $valid->_smarty;
   $config = $smarty->get_config_vars();

   //eCheck 1
   $valid->condition_or('$aufnahmenr == "" && $aufnahmedatum == ""', array('aufnahmenr || aufnahmedatum'));

   //eCheck 2
   $valid->start_end_date(array('aufnahmedatum'), array('entlassungsdatum'), $config['msg_date']);
}

?>