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

$form_id  = 1;
$location = 'index.php?page=rec.settings';
$table    = 'settings';
$upload   = new upload($smarty);

$upload->setDestinations(array('logo' => array('image')));

$ext = dlookup($db, $table, "img_type", "settings_id = '$form_id'");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button = strpos(get_buttons( $page, $form_id ), 'update') !== false ? 'update' : 'cancel';

show_record( $smarty, $db, $fields, 'settings', $form_id, '');

//TODO img feature
if (isset($_REQUEST['type']) === true && $_REQUEST['type'] == 'thumbnail') {
   $uploadDir  = getUploadDir($smarty, 'upload', false);
   $path        = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . reset($fields['logo']['value']);

   if (is_file($path) === true) {
      createImage($path, reset($fields['img_type']['value']));
      exit;
   }
}

$statusLast = appSettings::get('status_lasttime');

$smarty
   ->assign('button', $button)
   ->assign('ext', $ext)
   ->assign('status_last', ($statusLast !== null ? date("d.m.Y - H:i", strtotime($statusLast)) : null))
   ->assign('status_count', dlookup($db, '`status`', 'COUNT(*)', 1))
;

$upload->setFields(array('logo'));
$upload->assignVars($fields);

/**
 *
 * @param validator $valid
 */
function upload ($valid) {
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('logo');
   $upload       = new upload($smarty);

   if (reset($fields['fastreg_role']['value']) == 'konferenzteilnehmer' && reset($fields['rolle_konferenzteilnehmer']['value']) != '1') {
       $valid->set_err(12, 'rolle_konferenzteilnehmer', null);
   }

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('jpg;jpeg;png;gif'));
   $upload->setMandatory(array($fields['logo']['req']));
   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['logo']['value'][0]      = $upload->getFilename('logo');
   $fields['img_type']['value'][0]  = strlen($upload->getExt('logo')) ? $upload->getExt('logo') : (isset($_REQUEST['ext']) ? $_REQUEST['ext'] : '');
}


?>
