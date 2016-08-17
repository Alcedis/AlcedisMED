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

$table    = 'org';
$form_id  = isset( $_REQUEST['org_id'] ) ? $_REQUEST['org_id'] : '';
$location = get_url('page=list.vorlage_organisation');

$inaktiv  = false;
if ($form_id) {
   $inaktiv    = dlookup($db, 'org', 'inaktiv',  "org_id = '$form_id'") == 1 ? true : false;

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

unset($fields['logo']);
unset($fields['img_type']);
$widget->unsetField('logo');
$widget->unsetField('img_type');

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button   = get_buttons ( 'vorlage_organisation', $form_id );
show_record( $smarty, $db, $fields, $table, $form_id);

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('org_id', $form_id)
   ->assign('button', $button)
   ->assign('back_btn', 'page=list.vorlage_organisation');

?>