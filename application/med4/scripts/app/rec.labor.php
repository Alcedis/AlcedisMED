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

$table      = $tbl_labor;
$form_id    = isset( $_REQUEST['labor_id'] ) ? $_REQUEST['labor_id'] : '';
$location   = get_url('page=view.erkrankung');

$vorlage_labor_id = isset($_REQUEST['vorlage_labor_id']) === true
   ? $_REQUEST['vorlage_labor_id']
   : (strlen($form_id) > 0
      ? dlookup($db, $table, 'vorlage_labor_id', "labor_id='$form_id'")
      : ''
   )
;

$extForm = extForm::create($db, $smarty, 'labor')
      ->setSortField('parameter')
      ->setFields('labor_wert')
      ->setTemplate('vorlage_labor_wert', $vorlage_labor_id);

if ($ajax === true || $vorlage_labor_id != '') {

   if ($ajax === true) {
      $extForm->buildFormElements();
      exit;
   }

   $smarty
      ->assign('extForm' , $extForm->assignTemplate())
   ;

   if (isset($_REQUEST['labor_wert'])) {

      $data = $extForm->convertRequestValues($_REQUEST['labor_wert'], 'vorlage_labor_wert_id');

      $smarty->assign('labor_wert_data', create_json_string($data));

   } else {

      $data = $extForm->loadValues(
         array(
            array(
               'field' => 'labor_wert_id',
               'type'  => 'int'
            ),
            array(
               'field' => 'wert',
               'type'  => 'float'
            ),
            array(
               'field' => 'beurteilung',
               'type'  => 'string'
            )
         ),
         $form_id,
         'vorlage_labor_wert_id'
      );
      $smarty->assign('labor_wert_data', create_json_string($data));
   }
}

if ($permission->action($action) === true) {

   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

if ($form_id !== '') {
   $smarty->assign('labor_name', dlookup($db, $tbl_vorlage_labor, 'bez', "vorlage_labor_id= '$vorlage_labor_id'"));
}

$smarty->assign('button', $button);

function check_labor_werte($extValid) {

    $extValid->minField('wert', 1);

}

?>