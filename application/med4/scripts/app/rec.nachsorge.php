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

$table      = 'nachsorge';
$form_id    = isset( $_REQUEST['nachsorge_id'] ) ? $_REQUEST['nachsorge_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      switch($_REQUEST['show_dlist']){
         case 'erkrankung':
            $erkrankungFields = $widget->loadExtFields('fields/app/nachsorge_erkrankung.php');
            $query = "SELECT * FROM nachsorge_erkrankung WHERE nachsorge_id='$form_id' AND erkrankung_weitere_id != '$erkrankung_id'";

            echo create_json_string(load_pos_sess($db, 'nachsorge_erkrankung', $query, 'erkrankung', $erkrankungFields, $config));
            exit;

            break;
      }
   }
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );


function ext_warn($valid) {

    $smarty       = $valid->_smarty;
    $config       = $smarty->get_config_vars();
    $fields       = $valid->_fields;


    // eCheck 6
    if (isset($fields['psa_bestimmt']) === true && isset($fields['vorlage_labor_id']) === true) {
        if (reset($fields['psa_bestimmt']['value']) == '1' && reset($fields['vorlage_labor_id']['value']) == null) {
            $valid->set_warn(11, 'vorlage_labor_id', null, $config['msg_labor']);
        }
    }
}


function ext_err($valid) {
    //eCheck 4
    $valid->condition_and('$response_klinisch == ""', array('!response_klinisch_bestaetigt'));

    //eCheck 5
    $valid->condition_and('in_array($lymphoedem, array("", "0")) == true', array('!lymphoedem_seite'));

    //eChecks aus Anamnese
    //eCheck 13
    $valid->condition_and('in_array($sy_schmerzen, array("", "kein")) == true' , array('!sy_schmerzen_lokalisation && !sy_schmerzen_lokalisation_text && !sy_schmerzen_lokalisation_seite', ''));
    $valid->condition_and('in_array($sy_schmerzen, array("")) == true' , array('!sy_schmerzscore', ''));

    //eCheck 14
    $valid->condition_and('in_array($sy_husten, array("", "kein")) == true' , array('!sy_husten_dauer', ''));

    //eCheck 15
    $valid->condition_and('in_array($sy_miktion, array("", "kein")) == true' , array('!sy_restharn', ''));

    //eCheck 16
    $valid->condition_and('in_array($sy_harnstau, array("", "kein")) == true' , array('!sy_harnstau_lokalisation', ''));

    //eCheck 17
    $valid->condition_and('in_array($sy_para_syndrom, array("", "kein")) == true' , array('!sy_para_syndrom_symptom && !sy_para_syndrom_detail', ''));

    //eCheck 18
    $valid->condition_and('in_array($sy_gewichtsverlust, array("", "kein")) == true' , array('!sy_gewichtsverlust_2wo && !sy_gewichtsverlust_3mo', ''));

    //eCheck 28
    $valid->condition_and('$sy_dauer == ""' , array('!sy_dauer_einheit', ''));
}

?>
