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

//Wir bedienen uns am Inhalt des "user" Formulares
$table  = 'user';
$fields = $widget->loadExtFields('fields/base/user.php');

$form_id  = isset($_REQUEST['arzt_id']) ? $_REQUEST['arzt_id'] : '';
$location = 'index.php?page=list.vorlage_arzt';

$inaktiv  = false;
if ($form_id) {
   $inaktiv    = dlookup($db, 'user', 'inaktiv',  "user_id = '$form_id'") == 1 ? true : false;

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

// email require check
if (appSettings::get('allow_registration') === true) {
    $fields['email']['req'] = 1;
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$smarty->config_load('base/user.conf', 'rec');
$config = $smarty->get_config_vars();

$button = get_buttons ( 'vorlage_arzt', $form_id);

show_record( $smarty, $db, $fields, 'user', $form_id, '');

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}


$smarty
   ->assign('button', $button)
   ->assign('caption', $config['caption_arzt'])
   ->assign('back_btn', 'page=list.vorlage_arzt');

?>