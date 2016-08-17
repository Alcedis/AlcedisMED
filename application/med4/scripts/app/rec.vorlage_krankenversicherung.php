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

$table    = 'vorlage_krankenversicherung';
$form_id  = isset( $_REQUEST['vorlage_krankenversicherung_id'] ) ? $_REQUEST['vorlage_krankenversicherung_id'] : '';
$location = get_url('page=list.vorlage_krankenversicherung');

$inaktiv  = false;
if ($form_id) {
   $inaktiv    = dlookup($db, 'vorlage_krankenversicherung', 'inaktiv',  "vorlage_krankenversicherung_id = '$form_id'") == 1 ? true : false;

   $inactivePermission  = $permission->action('inactive');
   $activePermission    = $permission->action('active');

   $statePermission = false;

   if ($inaktiv == true && $activePermission == true) {
      $statePermission = 'active';
   } elseif($inaktiv == false && $inactivePermission == true) {
      $statePermission = 'inactive';
   }

   $smarty
      ->assign('status_locked', $inaktiv)
      ->assign('stateButton', $statePermission)
      ->assign('inaktiv', $inaktiv);
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button   = get_buttons ( $table, $form_id );

show_record( $smarty, $db, $fields, $table, $form_id);

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign( 'button',  $button )
   ->assign('back_btn',  'page=list.vorlage_krankenversicherung')

?>