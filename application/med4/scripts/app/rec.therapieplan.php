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

$table      = 'therapieplan';
$location   = get_url('page=view.erkrankung');

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);

   $form_id  = isset($arr_sess['therapieplan_id']) ? $arr_sess['therapieplan_id'] : '';
   $from     = isset($_REQUEST['from']) ? $_REQUEST['from'] : 'konferenz';
}else{
   $from     = isset($_REQUEST['from']) ? $_REQUEST['from'] : 'erkrankung';
   $form_id  = isset($_REQUEST['therapieplan_id']) ? $_REQUEST['therapieplan_id'] : '';
}

if (in_array($from, array('konferenz', 'konferenz_patient')) == true) {
   //Da wir aus der Konferenz kommen, haben wir natürlich keinerlei erkrankung oder sonstiges vorbelegt, also muss alles user und formularspezifisch geladen werden..
   require 'scripts/app/rec.konferenz_therapieplan.php';
}

//Wenn Konferenzmodul deaktiviert
if (appSettings::get('konferenz') !== true) {
   if (strlen($form_id) > 0 && strlen(dlookup($db, 'therapieplan', 'konferenz_patient_id', "therapieplan_id = '{$form_id}'"))) {
       $fields['konferenz_patient_id']['ext'] .= "AND (tp.therapieplan_id = '{$form_id}')";
   } else {
       unset($fields['konferenz_patient_id']);

       $widget
          ->unsetField('konferenz_patient_id')
       ;
   }
} else {
   $fields['konferenz_patient_id']['ext'] .= strlen($form_id) ? "AND (tp.therapieplan_id IS NULL OR tp.therapieplan_id = $form_id)" : 'AND tp.therapieplan_id IS NULL';
}

// Wenn Zweitmeinung deaktiviert
if (appSettings::get('zweitmeinung') !== true) {
    unset($fields['zweitmeinung_id']);

    $widget
        ->unsetField('zweitmeinung_id')
    ;
}


if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

if ($from == 'konferenz') {
   $button = get_ajax_buttons($table);
} else {

   //only delete if no conenction to konferenz_patient exists
   if (strlen($form_id) > 0 && strlen(dlookup($db, 'therapieplan', 'konferenz_patient_id', "therapieplan_id = '{$form_id}'")) > 0) {
      $button = get_buttons($table, $form_id, $statusLocked, false, 'D');
   } else {
      $button = get_buttons($table, $form_id, $statusLocked);
   }
}

//Therapien auf die erkrankung beschränken
$restrict = "WHERE erkrankung IN ('all', '{$erkrankung}') AND ";

$fields['chemo_id']['ext']    = str_replace('WHERE', $restrict, $fields['chemo_id']['ext']);
$fields['immun_id']['ext']    = str_replace('WHERE', $restrict, $fields['immun_id']['ext']);
$fields['ah_id']['ext']       = str_replace('WHERE', $restrict, $fields['ah_id']['ext']);
$fields['andere_id']['ext']   = str_replace('WHERE', $restrict, $fields['andere_id']['ext']);

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty
   ->assign('button', $button)
   ->assign('from', $from);

function ext_warn($valid) {

    $smarty = $valid->_smarty;
    $config = $smarty->get_config_vars();
    $fields = $valid->_fields;

    if (isset($_SESSION['settings']['konferenz']) === true && $_SESSION['settings']['konferenz'] == 1) {
        //eCheck 3
        $valid->condition_and('$grundlage == "tk"', array('konferenz_patient_id'), $config['msg_konferenz_patient_id'], true);
    }

    if (isset($fields['zeitpunkt']) === true && strlen(reset($fields['zeitpunkt']['value'])) > 0 && (isset($fields['leistungserbringer']) === true && strlen(reset($fields['leistungserbringer']['value'])) == 0)) {
        $valid->set_warn(12, 'leistungserbringer', null, $config['warn_fill_one']);
    }

    // eCheck 19
    // Ist das Feld "watchful_waiting" mit "ja" dokumentiert darf keines der Felder "op", "strahlen", "chemo", "immun", "ah", "andere", oder "sonstige" gelcihzeitig dokumentiert sein
    if (isset($fields['watchful_waiting']) === true && strlen(reset($fields['watchful_waiting']['value'])) > 0) {
        if (reset($fields['watchful_waiting']['value']) == "1") {
            $checkfields = array('op', 'strahlen', 'chemo', 'immun', 'ah', 'andere', 'sonstige');
            foreach ($checkfields as $check) {
                if (isset($fields[$check]) === true && reset($fields[$check]['value']) == "1") {
                    $valid->set_warn(11, array('watchful_waiting', $check), null, $config['err_plausibly']);
                }
            }
        }
    }

    // echeck20
    // Ist das Feld "active_surveillance" mit "ja" dokumentiert darf keines der Felder "op", "strahlen", "chemo", "immun", "ah", "andere", oder "sonstige" gelcihzeitig dokumentiert sein
    if (isset($fields['active_surveillance']) === true && strlen(reset($fields['active_surveillance']['value'])) > 0) {
        if (reset($fields['active_surveillance']['value']) == "1") {
            $checkfields = array('op', 'strahlen', 'chemo', 'immun', 'ah', 'andere', 'sonstige');
            foreach ($checkfields as $check) {
                if (isset($fields[$check]) === true && reset($fields[$check]['value']) == "1") {
                    $valid->set_warn(11, array('active_surveillance', $check), null, $config['err_plausibly']);
                }
            }
        }
    }
}


function ext_err($valid) {

   $smarty        = $valid->_smarty;
   $config        = $smarty->get_config_vars();
   $fields        = $valid->_fields;

   //eCheck 4
   $valid->condition_and('$grund_keine_konferenz != "son"', array('!grund_keine_konferenz_sonstige'));

   //eCheck 5
   $valid->condition_and('$op != "1"', array('!op_art_prostata && !op_art_brusterhaltend && !op_art_mastektomie && !op_art_nerverhaltend && !op_art_transplantation_autolog &&
   !op_art_nachresektion && !op_art_sln && !op_art_axilla && !keine_axilla_grund && !op_art_transplantation_allogen_v && !op_art_transplantation_allogen_nv && !op_art_transplantation_syngen
   && !op_intention && !op_extern'));

   //eCheck 6/7
   $valid->condition_and('$op_art_sln != "" || $op_art_axilla != ""', array('!keine_axilla_grund'));

   //eCheck 8
   $valid->condition_and('$strahlen != "1"', array('!strahlen_intention && !strahlen_mamma && !strahlen_axilla && !strahlen_lk_supra && !strahlen_lk_para && !strahlen_thoraxwand
   && !strahlen_art && !strahlen_zielvolumen && !strahlen_gesamtdosis && !strahlen_einzeldosis && !strahlen_extern'));

   //eCheck 9
   $valid->condition_and('$chemo != 1', array('!chemo_intention && !chemo_id && !chemo_extern'));

   //eCheck 10
   $valid->condition_and('$immun != 1', array('!immun_intention && !immun_id && !immun_extern'));

   //eCheck 11
   $valid->condition_and('$ah != 1', array('!ah_intention && !ah_id && !ah_therapiedauer_prostata && !ah_therapiedauer_monate && !ah_extern'));

   //eCheck 12
   $valid->condition_and('$andere != 1', array('!andere_intention && !andere_id && !andere_extern'));

   //eCheck 13
   $valid->condition_and('$abweichung_leitlinie != 1', array('!abweichung_leitlinie_grund'));

   //eCheck 14
   $valid->condition_and('$studie != 1', array('!vorlage_studie_id'));

   //eCheck 15
   $valid->condition_and('$studie != "0"', array('!studie_abweichung'));

   //eCheck 16
   $valid->condition_and('$sonstige != 1', array('!sonstige_intention && !sonstige_extern'));

   //eCheck 17
   if (array_key_exists('konferenz_patient_id', $fields) === true) {
      if (reset($fields['konferenz_patient_id']['value']) != "" && reset($fields['grundlage']['value']) != "tk") {
         $valid->set_err(11, 'grundlage', null, $config['msg_17']);
      }
   }

    // eCheck 18
    // Es darf nur eines der Felder "watchful_waiting" und "active_surveillance" mit "ja" dokumentiert sein
    if (array_key_exists('watchful_waiting', $fields) === true && array_key_exists('active_surveillance', $fields) === true) {
        if (reset($fields['watchful_waiting']['value']) == "1" && reset($fields['active_surveillance']['value']) == "1") {
            $valid->set_err(11, array('watchful_waiting', 'active_surveillance'), null, $config['err_plausibly']);
        }
    }
}
?>
