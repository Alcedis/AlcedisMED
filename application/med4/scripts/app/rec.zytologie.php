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

$table      = 'zytologie';
$form_id    = isset( $_REQUEST['zytologie_id'] ) ? $_REQUEST['zytologie_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $aberrationFields = $widget->loadExtFields('fields/app/zytologie_aberration.php');
      $query = "SELECT * FROM $tbl_zytologie_aberration WHERE zytologie_id='$form_id' ORDER BY aberration";

      echo create_json_string(load_pos_sess($db, $tbl_zytologie_aberration, $query, 'aberration', $aberrationFields, $config));
      exit;
   }
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );

function ext_warn($valid) {

   $smarty        = $valid->_smarty;
   $config        = $smarty->get_config_vars();

   $fields        = $valid->_fields;

   $erkrankungId  = reset($fields['erkrankung_id']['value']);
   $erkrankung    = dlookup($valid->_db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'");

   //eCheck 3
   if ($erkrankung !== 'leu') {
       $valid->condition_and('$eingriff_id == ""', array('eingriff_id'), $config['msg_eingriff'], true);
   }
}


function ext_err($valid) {

   $config        = $valid->_msg;
   $fields        = $valid->_fields;

   //eCheck 4
   $valid->condition_and('in_array($zytologie_normal, array("", 1)) == true' , array('!zelldichte && !erythropoese && !granulopoese && !megakaryopoese && !km_infiltration && !km_infiltration_anteil && !zyto_sonstiges_text && !zyto_sonstiges'));

   //eCheck 5
   $valid->condition_and('in_array($zellveraenderung, array("", 0)) == true' , array('!erythrozyten && !erythrozyten_text && !granulozyten && !granulozyten_text && !megakaryozyten && !megakaryozyten_text && !lymphozyten_text && !plasmazellen_text && !zellen_sonstiges && !zellen_sonstiges_text'));

   //eCheck 6
   if (in_array(reset($fields['zytogenetik_normal']['value']), array("", 1)) && count($_SESSION['pos_table']['aberration']) > 0) {
      $valid->set_err(12, 'zytogenetik_normal', null, $config['err_plausibly']);
   }

   //eCheck 7
   $valid->condition_and('$zyto_sonstiges != ""' , array('zyto_sonstiges_text'));

}

?>