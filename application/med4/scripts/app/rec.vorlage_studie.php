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

$table    = 'vorlage_studie';
$form_id  = isset( $_REQUEST['vorlage_studie_id'] ) ? $_REQUEST['vorlage_studie_id'] : '';
$location = get_url('page=list.vorlage_studie');
$upload   = new upload($smarty);

$upload->setDestinations(
   array(
      'krz_protokoll'   => array('doc', 'doc'),
      'protokoll'       => array('doc', 'doc')
   )
);

$freigabe = false;
$inaktiv  = false;

if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_studie', 'freigabe', "vorlage_studie_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_studie', 'inaktiv',  "vorlage_studie_id = '$form_id'") == 1 ? true : false;

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

$button   = get_buttons ( $table, $form_id , null, true);

show_record( $smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('krz_protokoll', 'protokoll'));
$upload->assignVars($fields);

$confirmDial = $rolle_code === 'supervisor' ? false : true;

if ($freigabe == true) {

   if ($rolle_code === 'supervisor' && $inaktiv == false) {
      $button   = get_buttons ( $table, $form_id, null, $confirmDial );
   } else {
      $button = 'cancel';
   }
} else {
   $button   = get_buttons ( $table, $form_id, null, $confirmDial);
}

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('button',   $button)
   ->assign('back_btn', 'page=list.vorlage_studie');

function ext_err($valid) {

   $valid->start_end_date(array('beginn'), array('ende'));

   protokollUpload($valid);
}


function protokollUpload($valid)
{
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('krz_protokoll', 'protokoll');
   $upload       = new upload($smarty);

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('pdf', 'pdf'));

   $upload->setMandatory(array($fields['krz_protokoll']['req'], 0));
   $upload->setMandatory(array($fields['protokoll']['req'], 0));

   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['krz_protokoll']['value'][0]   = $upload->getFilename('krz_protokoll');
   $fields['protokoll']['value'][0]       = $upload->getFilename('protokoll');

   // wenn kein Upload durchgeführt wurde ist hier schluss
   if ($upload->uploadPerformed() === false) {
      return;
   }
}


?>