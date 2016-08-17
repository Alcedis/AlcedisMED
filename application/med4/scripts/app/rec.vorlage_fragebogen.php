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

$table    = 'vorlage_fragebogen';
$form_id  = isset( $_REQUEST['vorlage_fragebogen_id'] ) ? $_REQUEST['vorlage_fragebogen_id'] : '';
$location = get_url('page=list.vorlage_fragebogen');

$freigabe = false;
$inaktiv  = false;
if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_fragebogen', 'freigabe', "vorlage_fragebogen_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_fragebogen', 'inaktiv',  "vorlage_fragebogen_id = '$form_id'") == 1 ? true : false;

   $inactivePermission  = $permission->action('inactive');
   $activePermission    = $permission->action('active');

   $statePermission = false;

   if ($inaktiv == true && $activePermission == true) {
      $statePermission = 'active';
   } elseif($freigabe == true && $inaktiv == false && $inactivePermission == true) {
      $statePermission = 'inactive';
   }

   $smarty
      ->assign('status_locked', $freigabe)
      ->assign('freigabe', $freigabe)
      ->assign('stateButton', $statePermission)
      ->assign('inaktiv', $inaktiv);
}

//TODO BOAAAHHHH
$err_msg  = array();
$errors   = false;

// A-Tabellen werden manuell gepflegt.
$tbl_a_vorlage_fragebogen = '_vorlage_fragebogen';
$tbl_a_vorlage_fragebogen_frage = '_vorlage_fragebogen_frage';

if($form_id != '' && !strlen($action)){
   $fragen = sql_query_array($db,"SELECT frage, val_min, val_max FROM vorlage_fragebogen_frage WHERE vorlage_fragebogen_id = $form_id ORDER BY vorlage_fragebogen_frage_id");
   $smarty->assign('fragen',$fragen);
}

if (strlen($action) && !strlen($_REQUEST['art'])) {
   $err_msg[] = $config['msg_art'];
   $errors = true;
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

show_record( $smarty, $db, $fields, 'vorlage_fragebogen', $form_id, '', '' );

if ($freigabe == true) {

   if ($rolle_code === 'supervisor' && $inaktiv == false) {
      $button   = get_buttons ( $table, $form_id, null);
   } else {
      $button = 'cancel';
   }
} else {
   $button   = get_buttons ( $table, $form_id, null);
}

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign( 'button', $button)
   ->assign('back_btn', 'page=list.vorlage_fragebogen');

?>