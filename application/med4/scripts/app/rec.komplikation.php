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

$table      = 'komplikation';
$form_id    = isset( $_REQUEST['komplikation_id'] ) ? $_REQUEST['komplikation_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );


function ext_warn($valid) {

   $smarty        = $valid->_smarty;
   $config        = $smarty->get_config_vars();

   //eCheck 1
   $valid->condition_or(true, array('untersuchung_id || eingriff_id'), -1, $config['msg_reference'], true);
}


function ext_err($valid) {

   $smarty        = $valid->_smarty;
   $config        = $smarty->get_config_vars();

   //eCheck Basic Reference
   $valid->condition_or('$untersuchung_id != "" || $eingriff_id != ""', array( 'untersuchung_id || eingriff_id' ), -1, $config['msg_only_one']);

   //eCheck 2
   $valid->condition_and('in_array($antibiotikum, array("", "0")) == true' , array('!antibiotikum_dauer', ''));

   //eCheck 3
   $valid->condition_and('in_array($transfusion, array("", "0")) == true' , array('!transfusion_anzahl', ''));

   //eCheck 4
   $valid->condition_and('in_array($beatmung, array("", "0")) == true' , array('!beatmung_dauer', ''));

   //eCheck 5
   $valid->condition_and('in_array($intensivstation, array("", "0")) == true' , array('!intensivstation_dauer', ''));
}

?>