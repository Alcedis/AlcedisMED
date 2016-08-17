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

$table      = 'anamnese';
$form_id    = isset( $_REQUEST['anamnese_id'] ) ? $_REQUEST['anamnese_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      switch($_REQUEST['show_dlist']){
         case 'familie':
            $anamneseFamilieFields = $widget->loadExtFields('fields/app/anamnese_familie.php');
            $query = "SELECT * FROM $tbl_anamnese_familie WHERE anamnese_id='$form_id' ORDER BY karzinom";

            echo create_json_string(load_pos_sess($db, $tbl_anamnese_familie, $query, 'familie', $anamneseFamilieFields, $config));
            exit;

            break;

         case 'erkrankung':
            $anamneseErkrankungFields = $widget->loadExtFields('fields/app/anamnese_erkrankung.php');
            $query = "SELECT * FROM $tbl_anamnese_erkrankung WHERE anamnese_id='$form_id' ORDER BY jahr";

            echo create_json_string(load_pos_sess($db, $tbl_anamnese_erkrankung, $query, 'erkrankung', $anamneseErkrankungFields, $config));
            exit;

            break;
      }
   }
}

$button = get_buttons( $table, $form_id, $statusLocked );

//daten aus vorheriger anam vorbelegen!!!!
if (!strlen($form_id) && !strlen($action)) {

   $anamneseData = sql_query_array($db, "SELECT * FROM anamnese WHERE patient_id = '$patient_id' AND erkrankung_id = '$erkrankung_id' ORDER BY datum");

   if (count($anamneseData) > 0) {
      $lastDataset = end($anamneseData);
      $lastDataset['datum'] = '';
      $lastDataset['createuser'] = '';
      $lastDataset['createtime'] = '';
      $lastDataset['updateuser'] = '';
      $lastDataset['updatetime'] = '';

      $lastAnamId                   = $lastDataset['anamnese_id'];
      $lastDataset['anamnese_id']   = '';

      array2fields($lastDataset, $fields);
      fields2request($fields);

      //anamnese_familie
      $anamFamFields = $widget->loadExtFields('fields/app/anamnese_familie.php');
      $anamFamData = sql_query_array($db, "SELECT *, '' AS anamnese_familie_id, '' AS anamnese_id, '$user_id' AS createuser, NOW() AS createtime FROM anamnese_familie WHERE anamnese_id = '$lastAnamId'");

      foreach ($anamFamData as $anamFamEntry) {
         array2fields($anamFamEntry, $anamFamFields);
         insert_pos_sess($db, 'familie', 'insert', $anamFamFields, $config, true);
      }

      //anamnese_erkrankung
      $anamErkFields = $widget->loadExtFields('fields/app/anamnese_erkrankung.php');
      $anamErkData = sql_query_array($db, "SELECT *, '' AS anamnese_erkrankung_id, '' AS anamnese_id, '$user_id' AS createuser, NOW() AS createtime FROM anamnese_erkrankung WHERE anamnese_id = '$lastAnamId'");

      foreach ($anamErkData as $anamErkEntry) {
         array2fields($anamErkEntry, $anamErkFields);
         insert_pos_sess($db, 'erkrankung', 'insert', $anamErkFields, $config, true);
      }

      $smarty->assign( 'message',  $config['msg_vorblend']);
    }
}

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign('button', $button);

function ext_err($valid) {

   $smarty     = &$valid->_smarty;
   $fields     = &$valid->_fields;
   $config     = $valid->_msg;

   //eCheck 3
   $valid->condition_and('$vorsorge_regelmaessig == "1"' , array('', '!vorsorge_intervall && !vorsorge_datum_letzte'));

   //eCheck 4
   $valid->condition_and('$risiko_pille == "1"' , array('', '!risiko_pille_dauer'));

   //eCheck 5
   $valid->condition_and('$hormon_substitution == "1"' , array('', '!hormon_substitution_art && !hormon_substitution_dauer'));

   //eCheck 6
   $valid->condition_and('$testosteron_substitution == "1"' , array('', '!testosteron_substitution_dauer'));

   //eCheck 7
   $valid->condition_and('$darmerkrankung_jn != ""' , array('', '!darmerkrankung_morbus && !darmerkrankung_colitis && !darmerkrankung_sonstige'));

   if (isset($fields['darmerkrankung_jn']) == true && reset($fields['darmerkrankung_jn']['value']) == '0') {
       //plausi check
       if (isset($fields['darmerkrankung_morbus']) === true && reset($fields['darmerkrankung_morbus']['value']) == '1') {
           $valid->set_err(12, 'darmerkrankung_morbus', null);
       }

       if (isset($fields['darmerkrankung_colitis']) === true && reset($fields['darmerkrankung_colitis']['value']) == '1') {
           $valid->set_err(12, 'darmerkrankung_colitis', null);
       }

       if (isset($fields['darmerkrankung_sonstige']) === true && reset($fields['darmerkrankung_sonstige']['value']) == '1') {
           $valid->set_err(12, 'darmerkrankung_sonstige', null);
       }
   }

   //eCheck 8
   $valid->condition_and('$hpv == "1"' , array('', '!hpv_typ01 && !hpv_typ02 && !hpv_typ03 && !hpv_typ04 && !hpv_typ05 && !hpv_typ06 && !hpv_typ07 && !hpv_typ08 && !hpv_typ09 && !hpv_typ10'));

   //eCheck 9
   $valid->condition_and('$risiko_sonnenschutzmittel == "1"' , array('', '!risiko_sonnenschutzmittel_detail'));

   //eCheck 10
   $valid->condition_and('$risiko_noxen == "1"' , array('', '!risiko_noxen_detail'));

   //eCheck 11
   $valid->condition_and('$beruf_letzter == ""' , array('!beruf_letzter_dauer', ''));

   //eCheck 12
   $valid->condition_and('$beruf_laengster == ""' , array('!beruf_laengster_dauer', ''));

   //eCheck 13
   $valid->condition_and('in_array($sy_schmerzen, array("", "kein")) == true' , array('!sy_schmerzen_lokalisation && !sy_schmerzen_lokalisation_text && !sy_schmerzen_lokalisation_seite', ''));
   $valid->condition_and('in_array($sy_schmerzen, array("", "kein")) == true && in_array($sy_schmerzscore, array("", 0)) == false' , array('!sy_schmerzscore', ''));


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

   //eCheck 19
   if (isset($fields['familien_karzinom']) === true && reset($fields['familien_karzinom']['value']) != 1 && count($_SESSION['pos_table']['familie']) > 0) {
      $valid->set_err(12, 'familien_karzinom', null, $config['err_plausibly']);
   }

   $gens = array('gen_fap', 'gen_gardner', 'gen_peutz', 'gen_hnpcc', 'gen_turcot', 'gen_polyposis', 'gen_dcc', 'gen_baxgen', 'gen_smad2',
       'gen_smad4', 'gen_kras', 'gen_apc', 'gen_p53', 'gen_cmyc', 'gen_tgfb2', 'gen_hpc1', 'gen_pcap', 'gen_cabp',
       'gen_x27_28', 'gen_sonstige', 'gen_wiskott_aldrich', 'gen_cvi', 'gen_louis_bar', 'gen_brca1', 'gen_brca2', 'gen_sonstige'

   );

   //eCheck 22
   $valid->condition_and('$gen_jn == "1"' , array('', '!' . implode(' && !', $gens)));

   //eCheck 23
   $valid->condition_and('$menopause_iatrogen == "1"' , array('', '!menopause_iatrogen_ursache'));

   //eCheck 24
   $valid->condition_and('$vorop == "1"' , array('', '!vorop_lok1 && !vorop_lok2 && !vorop_lok3 && !vorop_lok4'));

   //eCheck 25
   $valid->condition_and('$vorbestrahlung == "1"' , array('', '!vorbestrahlung_diagnose'));

   //eCheck 26
   $valid->condition_and('$hormon_sterilitaetsbehandlung == "1"' , array('', '!hormon_sterilitaetsbehandlung_dauer'));

   //eCheck 27
   $valid->condition_and('$sonst == "1"' , array('', '!sonst_dauer'));

   //eCheck 28
   $valid->condition_and('$sy_dauer == ""' , array('!sy_dauer_einheit', ''));
}

?>
