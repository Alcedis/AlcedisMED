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

$table    = 'vorlage_therapie';
$form_id  = isset( $_REQUEST['vorlage_therapie_id'] ) ? $_REQUEST['vorlage_therapie_id'] : '';
$location = get_url('page=list.vorlage_therapie');
$upload   = new upload($smarty);

$upload->setDestinations(array('datei' => array('doc', 'doc')));

$freigabe = false;
$inaktiv  = false;

if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_therapie', 'freigabe', "vorlage_therapie_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_therapie', 'inaktiv',  "vorlage_therapie_id = '$form_id'") == 1 ? true : false;

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

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $vorlageTherapieWirkstoffFields = $widget->loadExtFields('fields/app/vorlage_therapie_wirkstoff.php');
      $query = "SELECT * FROM $tbl_vorlage_therapie_wirkstoff WHERE vorlage_therapie_id='$form_id' ORDER BY wirkstoff";

      echo create_json_string(load_pos_sess($db, 'vorlage_therapie_wirkstoff', $query, 'wirkstoff', $vorlageTherapieWirkstoffFields, $config));
      exit;
   }
}

show_record( $smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('datei'));
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

//Dropdown für Wirkstoff Art
$dd_methode_tmp = sql_query_array($db, "SELECT code, bez FROM l_basic WHERE klasse='wirkstoff_art' ORDER BY pos");

foreach ($dd_methode_tmp AS $value ) {
   $dd_wirkstoff_art[$value['code']] = $value['bez'];
}

$smarty
   ->assign('dd_wirkstoff_art',  $dd_wirkstoff_art)
   ->assign('button',            $button)
   ->assign('back_btn',          'page=list.vorlage_therapie');


function upload($valid)
{
   $fields       = &$valid->_fields;
   $smarty       = &$valid->_smarty;
   $config       = $smarty->get_config_vars();
   $uploadFields = array('datei');
   $upload       = new upload($smarty);

   $upload->setFields($uploadFields);
   $upload->setValidExtensions(array('pdf'));
   $upload->setMandatory(array(0));
   $upload->upload2UserTmp($valid);
   $upload->assignVars($fields);

   $fields['datei']['value'][0] = $upload->getFilename('datei');

   // wenn kein Upload durchgeführt wurde ist hier schluss
   if ($upload->uploadPerformed() === false) {
      return;
   }
}

?>