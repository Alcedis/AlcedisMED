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

$table      = 'therapie_systemisch_zyklustag';
$form_id    = isset( $_REQUEST['therapie_systemisch_zyklustag_id'] ) ? $_REQUEST['therapie_systemisch_zyklustag_id'] : '';
$location   = get_url('page=view.erkrankung');

$therapie_zyklus_id = isset($_REQUEST['therapie_systemisch_zyklus_id']) ? $_REQUEST['therapie_systemisch_zyklus_id'] :
    (strlen($form_id) ? dlookup($db, $table, 'therapie_systemisch_zyklus_id', "{$table}_id = '$form_id'") : '');

$therapie_sys_id = dlookup($db, 'therapie_systemisch_zyklus', 'therapie_systemisch_id', "therapie_systemisch_zyklus_id='$therapie_zyklus_id'");

$_REQUEST['therapie_systemisch_id'] = $therapie_sys_id;

//Asssign ans Template
$vorlage_therapie_id = dlookup($db, 'therapie_systemisch', 'vorlage_therapie_id', "therapie_systemisch_id='$therapie_sys_id'");

$zyklusTag = isset($_REQUEST['zyklustag']) === true ? $_REQUEST['zyklustag'] : (
   strlen($form_id) > 0
   ? dlookup($db, $table, 'zyklustag', "{$table}_id = '{$form_id}'")
   : ''
);


$query = "
   SELECT
      z.zyklus_nr,
      z.groesse,
      z.gewicht,
      tag.datum AS zyklustag_datum,
      p.geschlecht,
      p.geburtsdatum
   FROM therapie_systemisch_zyklus z
      LEFT JOIN patient p ON p.patient_id = z.patient_id
      LEFT JOIN therapie_systemisch_zyklustag tag ON tag.therapie_systemisch_zyklustag_id = '$form_id'
   WHERE z.therapie_systemisch_zyklus_id = '$therapie_zyklus_id'
";

$zyklusData = reset(sql_query_array($db, $query));

$extForm = extForm::create($db, $smarty, 'therapie_systemisch_zyklustag')
      ->setFields('therapie_systemisch_zyklustag_wirkstoff')
      ->setParam('zyklusData', $zyklusData)
      ->setTemplate('vorlage_therapie_wirkstoff', $vorlage_therapie_id);

if ($ajax === true || $zyklusTag != '') {

   if ($ajax === true) {
      $extForm->buildFormElements();
      exit;
   }

   $smarty
      ->assign('extForm' , $extForm->assignTemplate()
   );

   if (isset($_REQUEST['wirkstoff_data'])) {

      $data = $extForm->convertRequestValues($_REQUEST['wirkstoff_data'], 'vorlage_therapie_wirkstoff_id');

      $smarty
         ->assign('wirkstoff_data', create_json_string($data)
      );

   } else {
      $data = $extForm->loadValues(
         array(
            array(
               'field' => 'therapie_systemisch_zyklustag_wirkstoff_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'therapie_systemisch_zyklustag_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'therapie_systemisch_zyklus_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'therapie_systemisch_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'erkrankung_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'patient_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'vorlage_therapie_wirkstoff_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'dosis',
               'type'  => 'float'
            ),
            array(
               'field' => 'einheit',
               'type'  => 'string'
            ),
            array(
               'field' => 'aenderung_dosis',
               'type'  => 'float'
            ),
            array(
               'field' => 'aenderung_einheit',
               'type'  => 'string'
            ),
            array(
               'field' => 'verabreicht_dosis',
               'type'  => 'float'
            ),
            array(
               'field' => 'verabreicht_einheit',
               'type'  => 'string'
            ),
            array(
               'field' => 'kreatinin',
               'type'  => 'float'
            )
         ),
         $form_id,
         'vorlage_therapie_wirkstoff_id'
      );

      $smarty
         ->assign('wirkstoff_data', create_json_string($data));
   }
}


if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty
   ->assign('vorlage',     dlookup($db, 'vorlage_therapie', 'bez', "vorlage_therapie_id = '$vorlage_therapie_id'"))
   ->assign('zyklusData',  $zyklusData)
   ->assign('button',      $button);


function ext_err_wirkstoff(validator $valid) {
   $fields = $valid->_fields;

   //eCheck 3
   $valid->condition_and('$aenderung_dosis == ""', array('!aenderung_einheit', 'aenderung_einheit'));

   //eCheck 4
   $valid->condition_and('$verabreicht_dosis == ""', array('!verabreicht_einheit', 'verabreicht_einheit'));
}



function ext_err(&$valid) {

   $smarty  = $valid->_smarty;
   $config  = $smarty->get_config_vars();
   $fields  = $valid->_fields;

   if (isset($_REQUEST['wirkstoff_data']) == false && strlen(reset($fields['zyklustag']['value']))) {
      $valid->set_err(12, 'therapie_systemisch_zyklustag_id', null, $config['msg_insert']);
   }

}

?>