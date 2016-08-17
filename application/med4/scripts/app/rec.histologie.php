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

$table    = 'histologie';
$form_id  = isset( $_REQUEST['histologie_id'] ) ? $_REQUEST['histologie_id'] : '';
$location = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      switch($_REQUEST['show_dlist']){
         case 'einzelhistologie':
            $einzelhistologieFields = $widget->loadExtFields('fields/app/histologie_einzel.php');
            $query = "SELECT * FROM $tbl_histologie_einzel WHERE histologie_id='$form_id' ORDER BY diagnose_id";

            echo create_json_string(load_pos_sess($db, $tbl_histologie_einzel, $query, 'einzelhistologie', $einzelhistologieFields, $config));
            exit;

            break;
      }
   }
}

$erkrankungSeite = dlookup($db, 'erkrankung', 'seite', "erkrankung_id = '$erkrankung_id'");
if ($form_id == '' && in_array($erkrankungSeite, array('R', 'L')) && in_array($erkrankung, array('b', 'lu', 'sst')) == true  && $action == NULL) {
    $_REQUEST['diagnose_seite'] = $erkrankungSeite;
}

//Erweiterte Stanzen
$smarty->assign('show_extended_swage', appSettings::get('extended_swage'));

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );

function ext_warn($valid) {

   $fields        = $valid->_fields;

   $smarty        = $valid->_smarty;
   $config        = $smarty->get_config_vars();

   //eCheck 5
   $histologieDatum = reset($fields['datum']['value']);

   $msg  = sprintf($config['msg_eingriff'], $histologieDatum);
   $msg2 = sprintf($config['msg_untersuchung'], $histologieDatum);


   if (isset($fields['untersuchung_id']) === true && strlen(reset($fields['untersuchung_id']['value'])) == 0) {
      $valid->condition_and('$eingriff_id == ""' , array('eingriff_id'), $msg, true);
   }

   if (isset($fields['eingriff_id']) === true && strlen(reset($fields['eingriff_id']['value'])) == 0) {
      $valid->condition_and('$untersuchung_id == ""' , array('untersuchung_id'), $msg2, true);
   }

   //eCheck 27
   $valid->condition_and('$her2!= ""', array('her2_methode'), '', true);

   //eCheck 28
   $valid->condition_and('$her2_fish != ""', array('her2_fish_methode'), '', true);

   //eCheck 29
   $valid->condition_or('$her2_urteil != ""', array('her2_fish_methode || her2_methode'), '', true);
}

function ext_err($valid) {

   $fields = &$valid->_fields;

   //eCheck 6
   $valid->condition_and('$unauffaellig != ""' , array('!morphologie && !morphologie_text && !morphologie_erg1 && !morphologie_erg1_text && !morphologie_erg2 && !morphologie_erg2_text && !morphologie_erg3 && !morphologie_erg3_text', ''));

   //eCheck 7
   $valid->condition_and('in_array($msi, array("", 0)) == true' , array('!msi_mutation && !msi_stabilitaet'));

   //eCheck 8
   $valid->condition_and('$parametrienbefall_r == ""' , array('!parametrienbefall_r_infiltration'));

   //eCheck 9
   $valid->condition_and('$parametrienbefall_l == ""' , array('!parametrienbefall_l_infiltration'));

   //eCheck 11
   $valid->condition_and('$sbl_beurteilung == ""', array('!sbl_anz_positiv'));

   //eCheck 12
   $valid->condition_and('$sbr_beurteilung == ""', array('!sbr_anz_positiv'));

   //eCheck 13
   $valid->condition_and('$bll_beurteilung == ""', array('!bll_anz_positiv'));

   //eCheck 14
   $valid->condition_and('$blr_beurteilung == ""', array('!blr_anz_positiv'));

   //eCheck 15
   $valid->condition_and('$bl_beurteilung == ""', array('!bl_anz_positiv'));

   //eCheck 16
   $valid->condition_and('$br_beurteilung == ""', array('!br_anz_positiv'));

   //eCheck 17
   $valid->condition_and('$tl_beurteilung == ""', array('!tl_anz_positiv'));

   //eCheck 18
   $valid->condition_and('$tr_beurteilung == ""', array('!tr_anz_positiv'));

   //eCheck 19
   $valid->condition_and('$mll_beurteilung == ""', array('!mll_anz_positiv'));

   //eCheck 20
   $valid->condition_and('$mlr_beurteilung == ""', array('!mlr_anz_positiv'));

   //eCheck 21
   $valid->condition_and('$ml_beurteilung == ""', array('!ml_anz_positiv'));

   //eCheck 22
   $valid->condition_and('$mr_beurteilung == ""', array('!mr_anz_positiv'));

   //eCheck 23
   $valid->condition_and('$al_beurteilung == ""', array('!al_anz_positiv'));

   //eCheck 24
   $valid->condition_and('$ar_beurteilung == ""', array('!ar_anz_positiv'));

   //eCheck 25
   $valid->condition_and('$all_beurteilung == ""', array('!all_anz_positiv'));

   //eCheck 26
   $valid->condition_and('$alr_beurteilung == ""', array('!alr_anz_positiv'));
}

?>