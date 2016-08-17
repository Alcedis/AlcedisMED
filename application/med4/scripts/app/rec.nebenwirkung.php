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

$table      = 'nebenwirkung';
$form_id    = isset( $_REQUEST['nebenwirkung_id'] ) ? $_REQUEST['nebenwirkung_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn');

if (strlen($form_id) || $action == 'insert') {
   $code = $fields['nci_code']['value'][0];
   $nci_grade = sql_query_array($db, "SELECT grad1, grad2, grad3, grad4, grad5 FROM $tbl_l_nci WHERE code='$code'");
   if (isset($nci_grade[0]))
      $smarty->assign($nci_grade[0]);
}

$smarty->assign( 'button',  $button );

function ext_err($valid) {
   //eCheck 2
   $valid->condition_or(true, array('beginn || beginn_unbekannt'));
   $valid->condition_or('$beginn != "" && $beginn_unbekannt != ""', array('beginn || beginn_unbekannt'), -1);

   //eCheck 4
   $valid->condition_and('$ende != ""', array('!ende_unbekannt'));

   //eCheck 5
   $valid->condition_and('in_array($zusammenhang, array("","kein")) == true', array('!therapie_systemisch_id && !strahlentherapie_id && !sonstige_therapie_id'));

   //eCheck 7
   $valid->condition_or('$therapie_systemisch_id != "" || $strahlentherapie_id != "" || $sonstige_therapie_id != ""', array('therapie_systemisch_id || strahlentherapie_id || sonstige_therapie_id'), -1);

   //eCheck 8
   $valid->condition_and('in_array($therapie, array("", "0")) == true', array('!therapie_text'));

   //eCheck 9
   $valid->condition_and('$therapie_systemisch_id != "" || $strahlentherapie_id != "" || $sonstige_therapie_id != ""', array('zusammenhang'));

}

function ext_warn($valid) {
   $fields = $valid->_fields;

   $smarty = $valid->_smarty;
   $config = $smarty->get_config_vars();

   //eCheck 6
   if (isset($fields['zusammenhang']) === true && in_array(reset($fields['zusammenhang']['value']), array("moeg", "wahr")) === true) {
      $valid->condition_or('true', array('therapie_systemisch_id || strahlentherapie_id || sonstige_therapie_id'), -1, $config['warn_fill_one_min'], true);
   }
}

?>