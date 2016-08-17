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

$location = get_url('page=list.org');
$upload   = new upload($smarty);

$upload->setDestinations(array('logo' => array('image')));

$ext = dlookup($db, $table, "img_type", "org_id = '$form_id'");

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

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id );
show_record( $smarty, $db, $fields, $table, $form_id);


//TODO img feature
if (isset($_REQUEST['type']) === true && $_REQUEST['type'] == 'thumbnail') {
   $uploadDir  = getUploadDir($smarty, 'upload', false);
   $path        = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . reset($fields['logo']['value']);

   if (is_file($path) === true) {
      createImage($path, reset($fields['img_type']['value']));
      exit;
   }
}

$upload->setFields(array('logo'));
$upload->assignVars($fields);

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('org_id', $form_id)
   ->assign('ext', $ext)
   ->assign('button', $button)
   ->assign('back_btn', 'page=list.org');

function upload ($valid) {
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('logo');
   $upload       = new upload($smarty);

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('jpg;jpeg;png;gif'));
   $upload->setMandatory(array($fields['logo']['req']));
   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['logo']['value'][0]      = $upload->getFilename('logo');
   $fields['img_type']['value'][0]  = strlen($upload->getExt('logo')) ? $upload->getExt('logo') : (isset($_REQUEST['ext']) ? $_REQUEST['ext'] : '');
}


?>