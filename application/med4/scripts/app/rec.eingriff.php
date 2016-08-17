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

$table      = 'eingriff';
$form_id    = isset( $_REQUEST['eingriff_id'] ) ? $_REQUEST['eingriff_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      switch($_REQUEST['show_dlist']){
         case 'ops':
            $eingriffopsFields = $widget->loadExtFields('fields/app/eingriff_ops.php');
            $query = "SELECT * FROM $tbl_eingriff_ops WHERE eingriff_id='$form_id' ORDER BY prozedur";

            echo create_json_string(load_pos_sess($db, $tbl_eingriff_ops, $query, 'ops', $eingriffopsFields, $config));
            exit;

            break;
      }
   }
}

//R2
$erkrankungSeite = dlookup($db, 'erkrankung', 'seite', "erkrankung_id = '$erkrankung_id'");
if ($form_id == '' && in_array($erkrankungSeite, array('R', 'L')) && in_array($erkrankung, array('b', 'lu', 'sst')) === true && $action == NULL) {
    $_REQUEST['diagnose_seite'] = $erkrankungSeite;
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );


function ext_warn($valid) {

   $db         = $valid->_db;
   $config     = $valid->_msg;
   $fields     = $valid->_fields;

   $erkrankungId  = reset($fields['erkrankung_id']['value']);

   //eCheck 3
   $valid->condition_and('$diagnose_seite == ""' , array('diagnose_seite', ''), null, true);

   $checkPrim = dlookup($db, 'tumorstatus', 'datum_sicherung', "erkrankung_id = '{$erkrankungId}' AND anlass = 'p'");
   $checkRez  = dlookup($db, 'tumorstatus', 'MIN(datum_sicherung)', "erkrankung_id = '{$erkrankungId}' AND anlass LIKE 'r%'");

   $art_primaertumor = isset($fields['art_primaertumor']) && strlen(reset($fields['art_primaertumor']['value'])) ? true : false;

   //eCheck 31
   if (strlen($checkPrim) && strlen($checkRez) && $art_primaertumor === true) {
      $currentDate = todate(reset($fields['datum']['value']), 'en');

      if ($currentDate > $checkRez) {
          $valid->set_warn(12, 'art_primaertumor', null, $config['msg_zeitraum_prim']);
      }
   }

   $art_rezidiv = isset($fields['art_rezidiv']) && strlen(reset($fields['art_rezidiv']['value'])) ? true : false;

   //eCheck 32
   if (strlen($checkPrim) && strlen($checkRez) && $art_rezidiv === true) {
      $currentDate = todate(reset($fields['datum']['value']), 'en');

      if ($currentDate < $checkRez) {

          $valid->set_warn(12, 'art_rezidiv', null, $config['msg_zeitraum_rez']);
      }
   }
   //eCheck 33
   if ($art_primaertumor === true && (isset($fields['op_verfahren']) === true && strlen(reset($fields['op_verfahren']['value'])) == 0)) {
       $valid->set_warn(12, 'op_verfahren', null, $config['warn_fill_one']);
   }
}


function ext_err($valid) {

   $fields        = $valid->_fields;
   $config        = $valid->_msg;
   $db            = $valid->_db;
   $eingriffId    = (string) reset($fields['eingriff_id']['value']);

   $erkrankungId     = reset($fields['erkrankung_id']['value']);
   $art_primaertumor = isset($fields['art_primaertumor']['value']) ? reset($fields['art_primaertumor']['value']) : '';
   $art_rezidiv      = isset($fields['art_rezidiv']['value']) ? reset($fields['art_rezidiv']['value']) : '';

   $diagnoseSeite = isset($fields['diagnose_seite']['value']) ? reset($fields['diagnose_seite']['value']) : '';
   $erkrankung    = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = ' $erkrankungId'");

   //eCheck 4
   $valid->condition_or(true, array('art_diagnostik || art_staging || art_primaertumor || art_lk || art_metastasen || art_rezidiv || art_nachresektion ||
                                     art_transplantation_autolog || art_transplantation_allogen_v || art_transplantation_allogen_nv || art_transplantation_syngen
                                     || verwandschaftsgrad || art_revision || art_rekonstruktion || art_sonstige'));

   //eCheck 5
   $valid->condition_and('in_array($mark, array("", 0)) == true' , array('!mark_mammo && !mark_sono && !mark_mrt'));

   //eCheck 6
   $valid->condition_and('in_array($schnellschnitt, array("", 0)) == true' , array('!schnellschnitt_dauer'));

   //eCheck 7
   $valid->condition_and('in_array($intraop_roe, array("", 0)) == true' , array('!intraop_roe_ergebnis'));

   //eCheck 8
   $valid->condition_and('in_array($intraop_sono, array("", 0)) == true' , array('!intraop_sono_ergebnis'));

   //eCheck 9
   $valid->condition_and('in_array($intraop_mrt, array("", 0)) == true' , array('!intraop_mrt_ergebnis'));

   //eCheck 10
   $valid->condition_and('in_array($postop_roentgen, array("", 0)) == true' , array('!postop_roentgen_ergebnis'));

   //eCheck 11
   $valid->condition_and('in_array($postop_sono, array("", 0)) == true' , array('!postop_sono_ergebnis'));

   //eCheck 12
   $valid->condition_and('in_array($postop_mrt, array("", 0)) == true' , array('!postop_mrt_ergebnis'));

   //eCheck 13
   $valid->condition_and('in_array($stamm_purging, array("", 0)) == true' , array('!stamm_purging_text'));

   //eCheck 14
   if (isset($_SESSION['pos_table']['ops']) == false || count($_SESSION['pos_table']['ops']) == 0) {
      $valid->set_err(12, 'erkrankung_id', null, $config['msg_mind_ops']);
   }

   //eCheck 15
   $valid->condition_and('$intraop_bestrahlung == ""' , array('!intraop_bestrahlung_dosis'));

   //eCheck 16
   $valid->condition_and('$intraop_zytostatika == ""' , array('!intraop_zytostatika_art'));

   //eCheck 17
   $valid->condition_and('$sln_markierung == ""' , array('!sln_anzahl'));

   //eCheck 18
   $valid->condition_and('in_array($sln_schnellschnitt, array("", 0)) == true' , array('!sln_schnellschnitt_befall && !sln_schnellschnitt_dauer_versendung && !sln_schnellschnitt_dauer_eingang'));

   //eCheck 19
   $valid->condition_and('in_array($polypen, array("", 0)) == true' , array('!polypen_anz_gef && !polypen_anz_entf && !polypen_op_areal'));

   //eCheck 20
   $valid->condition_and('in_array($tumorrest, array("", 0)) == true' , array('!tumorrest_groesse'));

   //eCheck 21
   $valid->condition_and('$befall_ovar == ""' , array('!befall_ovar_rest'));

   //eCheck 22
   $valid->condition_and('$befall_tube == ""' , array('!befall_tube_seite'));

   //eCheck 23
   $valid->condition_and('$befall_sonst == ""' , array('!befall_sonst_text'));

   //eCheck 24
   $valid->condition_and('$beatmung == ""' , array('!beatmung_dauer'));

   //eCheck 25
   $valid->condition_and('$intensiv == ""' , array('!intensiv_dauer'));

   //eCheck 26
   $valid->condition_and('$antibiotika == ""' , array('!antibiotika_dauer'));

   //eCheck 27
   $valid->condition_and('$transfusion == ""' , array('!transfusion_anzahl_ek && !transfusion_anzahl_tk'));

   //eCheck 28
   $valid->condition_and('in_array($lymphocele, array("", 0)) == true' , array('!lymphocele_detail'));

   //eCheck 29
   $valid->condition_and('in_array($wundabstrich, array("", 0)) == true' , array('!wundabstrich_ergebnis'));

   //eCheck 30
   $valid->condition_and('$art_primaertumor == "1"' , array('!art_rezidiv'));

   //eCheck 33
   $where = "erkrankung_id = '$erkrankungId' AND art_primaertumor = 1";

   $whereSeite = '';

   if ($erkrankung == 'b' && $diagnoseSeite != 'B'){
      $whereSeite = " AND (diagnose_seite = '$diagnoseSeite' OR diagnose_seite = 'B')";
   }

   if ($erkrankung == 'b') {
      $where .= $whereSeite;
   }

   if (strlen($eingriffId) > 0) {
      $where .= " AND eingriff_id != '$eingriffId'";
   }

   if ($art_primaertumor == '1' && strlen(dlookup($db, 'eingriff', 'eingriff_id', $where))) {
      $valid->set_err(12, 'art_primaertumor', null, $config['msg_max_prim']);
   }

   //eCheck 34
   $where = "erkrankung_id = '{$erkrankungId}' AND art_rezidiv = 1";

   if ($erkrankung == 'b') {
      $where .= $whereSeite;
   }

   $valid->condition_and('$tme != ""' , array('!pme'));
   $valid->condition_and('$pme != ""' , array('!tme'));

   if (strlen($eingriffId) > 0) {
      $where .= " AND eingriff_id != '$eingriffId'";
   }

   if ($art_rezidiv == '1') {

      $datum      = todate(reset($fields['datum']['value']), 'en');

      if ($datum !== null) {
         $situation  = getSituations($db, $erkrankungId, $datum);

         if (count($situation) >= 1) {
            $sit = reset($situation);

            $where .= " AND datum BETWEEN '{$sit['start_date']}' AND '{$sit['end_date']}'";
         }

         if (strlen(dlookup($db, 'eingriff', 'eingriff_id', $where))) {
            $valid->set_err(12, 'art_rezidiv', null, $config['msg_max_rez']);
         }
      }
   }

   $valid->condition_and('$interdisziplinaer == "0"' , array('!urologie_bet && !chirurgie_bet'));
}


?>
