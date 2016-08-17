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

$table      = 'begleitmedikation';
$form_id    = isset( $_REQUEST['begleitmedikation_id'] ) ? $_REQUEST['begleitmedikation_id'] : '';
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

   //eCheck 3
   $valid->condition_and('$dosis_wert != ""', array('dosis_einheit'),$config['msg_einheit'], true);

}


function ext_err($valid) {

   //eCheck 2
   $valid->condition_and('$dosis_wert == ""', array('!dosis_einheit'));

   //eCheck 4
   $valid->condition_or(true, array('beginn || beginn_nb'));
   $valid->condition_or('$beginn != "" && $beginn_nb != ""', array('beginn || beginn_nb'), -1);

}

?>