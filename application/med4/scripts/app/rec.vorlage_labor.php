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

$table    = 'vorlage_labor';
$form_id  = isset( $_REQUEST['vorlage_labor_id'] ) ? $_REQUEST['vorlage_labor_id'] : '';
$location = get_url('page=list.vorlage_labor');


$freigabe = false;
$inaktiv  = false;
if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_labor', 'freigabe', "vorlage_labor_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_labor', 'inaktiv',  "vorlage_labor_id = '$form_id'") == 1 ? true : false;

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

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $vorlageLaborWertFields = $widget->loadExtFields('fields/app/vorlage_labor_wert.php');
      $query = "SELECT * FROM $tbl_vorlage_labor_wert WHERE vorlage_labor_id='$form_id' ORDER BY parameter";

      echo create_json_string(load_pos_sess($db, $tbl_vorlage_labor_wert, $query, 'wert', $vorlageLaborWertFields, $config));
      exit;
   }
}

$confirmDial = $rolle_code === 'supervisor' ? false : true;

if ($freigabe == true) {

   if ($rolle_code === 'supervisor' && $freigabe == true) {
      $button   = get_buttons ( $table, $form_id, null, $confirmDial );
   } else {

      $smarty->assign('status_locked', true);
      $button = 'cancel';
   }
} else {
   $button   = get_buttons ( $table, $form_id, null, $confirmDial);
}

show_record( $smarty, $db, $fields, $table, $form_id);

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('button',   $button )
   ->assign('back_btn', 'page=list.vorlage_labor');


function ext_err( $valid )
{
   $valid->condition_and( '$freigabe == 1' , array('gueltig_von && gueltig_bis') );
   $valid->start_end_date( array('gueltig_von'), array('gueltig_bis') );
}

?>