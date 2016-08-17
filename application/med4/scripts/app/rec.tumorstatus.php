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

$table          = 'tumorstatus';
$form_id        = isset( $_REQUEST['tumorstatus_id'] ) ? $_REQUEST['tumorstatus_id'] : '';
$location       = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $tumorstatusMetastasenFields = $widget->loadExtFields('fields/app/tumorstatus_metastasen.php');
      $query = "SELECT * FROM $tbl_tumorstatus_metastasen WHERE tumorstatus_id='$form_id' ORDER BY lokalisation";

      echo create_json_string(load_pos_sess($db, $tbl_tumorstatus_metastasen, $query, 'metastasen', $tumorstatusMetastasenFields, $config));
      exit;
   }
}

$erkrankungSeite = dlookup($db, 'erkrankung', 'seite', "erkrankung_id = '$erkrankung_id'");
if ($form_id == '' && in_array($erkrankungSeite, array('R', 'L')) && in_array($erkrankung, array('b', 'lu', 'sst')) === true && $action == NULL) {
    $_REQUEST['diagnose_seite']         = $erkrankungSeite;
    $_REQUEST['lokalisation_seite']     = $erkrankungSeite;
    $smarty->assign('selectedSide', $erkrankungSeite);
}

// R3
if (in_array($erkrankung, array('b', 'lu', 'sst')) === true) {
   $fields['diagnose']['ext']['showSide'] = true;
}

//R3/4
if (in_array($erkrankung, array('leu','ly', 'snst'))) {
   $smarty->set_config_var('rezidiv_lk',           $config['rezidiv_lk_leu_ly_snst']);
   $smarty->set_config_var('rezidiv_metastasen',   $config['rezidiv_metastasen_leu_ly_snst']);
}

if ($erkrankung === 'ly') {
   $smarty->set_config_var('rezidiv_lokal', $config['rezidiv_lokal_ly']);
}

$button = get_buttons ( $table, $form_id, $statusLocked);

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$preloadData = (array_key_exists('preloaded', $_REQUEST) === true && $_REQUEST['preloaded'] == 1) || strlen($form_id) > 0 ? false : $permission->action('preload_data');

if (in_array($erkrankung, array('b', 'lu', 'sst')) == true) {
   $options = array_merge(array('' => ''), getLookup($db, 'seite_rl'));

   $smarty->assign('side', $options);
}

$smarty
   ->assign('button',  $button )
   ->assign('preload_data', $preloadData)
   ->assign('preloaded', (array_key_exists('preloaded', $_REQUEST) === true && $_REQUEST['preloaded'] == 1))
;

/**
 *
 * @param validator $valid
 */
function ext_warn($valid) {

    /* @var validator $valid */
   $smarty       = $valid->_smarty;
   $db           = $valid->_db;
   $fields       = $valid->_fields;
   $config       = $smarty->get_config_vars();
   $erkrankungId = reset($fields['erkrankung_id']['value']);

   $erkrankung = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'");

   //eCheck 6
   $valid->condition_and('substr($anlass,0,1) != "r"', array('!rezidiv_lokal && !rezidiv_lk && !rezidiv_metastasen && !rezidiv_psa'), $config['msg_anlass_rezidiv'], true);

   //eCheck 10
   $valid->condition_and('$lokalisation_detail != "" && $lokalisation == ""', array('!msg_lokalisation_detail'), $config['msg_lokalisation_detail'], true);

   //eCheck 12
   $valid->condition_and('$her2 != ""', array('her2_methode'), '', true);

   //eCheck 13
   $valid->condition_and('$her2_fish != ""', array('her2_fish_methode'), '', true);

   //eCheck 14
   $valid->condition_or('$her2_urteil != ""', array('her2_fish_methode || her2_methode'), 1,'',  true);

   //eCheck 15
   $valid->condition_and('$morphologie == ""', array('morphologie'), $config['msg_morphologie'], true);

   //eCheck 16
   if (isset($fields['r']) === true && isset($fields['r_lokal']) === true) {
       if (reset($fields['r']['value']) == '0' && in_array(reset($fields['r_lokal']['value']) , array('1', '2')) === true) {
           $valid->set_warn(12, 'r_lokal', null, $config['msg_r0']);
       }
   }

   //eCheck 17
    if (isset($fields['t']) === true  && $erkrankung == 'p') {
        if (str_starts_with(reset($fields['t']['value']), 'pT1') === true) {
            $valid->set_warn(12, 't', null, $config['msg_pT1']);
        }
    }

    //eCheck 7 / 15
    if (isset($fields['diagnose']) === true && isset($fields['morphologie']) === true && strlen(reset($fields['morphologie']['value'])) > 0) {
        $diagnosis  = reset($fields['diagnose']['value']);
        $morphology = reset($fields['morphologie']['value']);

        if (in_array($erkrankung, array('leu', 'ly', 'snst')) === false) {
            if (str_starts_with($diagnosis, 'C') === true && str_ends_with($morphology, array('/3', '/6', '/9')) === false) {
                $valid->set_warn(12, array('diagnose', 'morphologie'), null, $config['msg_dignitaet']);
            } elseif (str_starts_with($diagnosis, 'D') && str_ends_with($morphology, array('/0', '/1', '/2')) === false) {
                $valid->set_warn(12, array('diagnose', 'morphologie'), null, $config['msg_dignitaet']);
            }
        }
    }

    //eCheck 7b
    if (isset($fields['diagnose']) && isset($fields['anlass'])) {
        $diagnose  = reset($fields['diagnose']['value']);
        $anlass    = reset($fields['anlass']['value']);

        if (str_starts_with($anlass, 'b')) {
            if (str_starts_with($diagnose, array('C', 'D0', 'D37', 'D38', 'D39', 'D4', 'D5', 'D6', 'D7', 'D8', 'D9'))) {
                $valid->set_warn(12, array('anlass', 'diagnose'), null, $config['msg_benigne']);
            }
        }
    }

    //eCheck 19
    if (in_array($erkrankung, array('p', 'd')) === true) {
        if (isset($fields['sicherungsgrad']['value']) === true && isset($fields['fall_vollstaendig']['value'])) {
            $sicherungsgrad   = reset($fields['sicherungsgrad']['value']);
            $fallVollstaendig = reset($fields['fall_vollstaendig']['value']);

            if ($sicherungsgrad == 'end' && strlen($fallVollstaendig) == 0) {
                $valid->set_warn(11, 'fall_vollstaendig', null, $config['msg_fill_field']);
            }
        }
    }

    if ($erkrankung === 'p') {
        //echeck 23
        $valid->condition_and('$datum_psa != ""', array('psa'), '', true);

        //eCheck 24
        $valid->condition_and('$psa != ""', array('datum_psa'), $config['msg_datum_psa'], true);

        //echeck 26
        $valid->condition_and('$rezidiv_metastasen != ""', array('quelle_metastasen'), $config['msg_quelle'], true);
    }

    if ($erkrankung === 'kh') {
        // 27
        $valid->condition_and('str_contains($t, "T4")', array('infiltration'), $config['msg_infiltration'], true);

        // v28
        $valid->condition_and('strlen($n) && str_ends_with($n, array("0", "X")) === false', array('befallen_n'), $config['msg_befallen_n'], true);

        // v29
        $valid->condition_and('strlen($m) && str_ends_with($m, array("0", "X")) === false', array('befallen_m'), $config['msg_befallen_m'], true);
    }
}


function ext_err($valid) {

   $smarty  = $valid->_smarty;
   $config  = $smarty->get_config_vars();
   $db      = $valid->_db;
   $fields  = $valid->_fields;

   toDate($fields, 'en');

   //eCheck 3
   $tumorstatusId    = reset($fields['tumorstatus_id']['value']);
   $anlass           = reset($fields['anlass']['value']);
   $datumSicherung   = reset($fields['datum_sicherung']['value']);
   $sicherungsgrad   = reset($fields['sicherungsgrad']['value']);
   $erkrankungId     = reset($fields['erkrankung_id']['value']);

   $erkrankung = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'");

   //Erkrankungsabhaengig da bei Brust auch noch nach links und rechts unterteilt werden muss
   $diagnoseSeite = isset($fields['diagnose_seite']['value']) == true ? reset($fields['diagnose_seite']['value']) : null;
   $sidePre      = null;
   $sidePost     = null;

   //Diagnoseseite wirklich nur bei Erkrankung Brust und Lunge
   if ($diagnoseSeite !== null && in_array($erkrankung, array('lu', 'b')) === true) {
      $sidePre  = "AND t.diagnose_seite = '{$diagnoseSeite}'";
      $sidePost = "AND u.diagnose_seite = '{$diagnoseSeite}'";
   }

   //check 1: zeitliche anordnung der anlaesse (p, r01, r02, rXX);
   $query = "
      SELECT
         IF(COUNT(DISTINCT t.tumorstatus_id) > 0 OR COUNT(DISTINCT u.tumorstatus_id) > 0, 1, 0) AS 'invalid'
      FROM l_basic x
         LEFT JOIN l_basic y          ON y.klasse = x.klasse AND y.pos > x.pos AND y.code != 'b'
            LEFT JOIN tumorstatus t   ON (t.anlass = y.code
                                          AND t.datum_sicherung <= '{$datumSicherung}'
                                          AND t.erkrankung_id = '{$erkrankungId}'
                                          AND t.tumorstatus_id != '{$tumorstatusId}'
                                          {$sidePre})
         LEFT JOIN l_basic z          ON z.klasse = x.klasse AND z.pos < x.pos AND z.code != 'b'
            LEFT JOIN tumorstatus u   ON (u.anlass = z.code
                                          AND u.datum_sicherung >= '{$datumSicherung}'
                                          AND u.erkrankung_id = '{$erkrankungId}'
                                          AND u.tumorstatus_id != '{$tumorstatusId}'
                                          {$sidePost})
      WHERE
         x.klasse = 'tumorstatus_anlass' AND
         x.code = '{$anlass}' AND
         x.code != 'b'
   ";

   $resultTime = reset(sql_query_array($db, $query));

   //check 2: vorlaeufig / endgueltig
   //wenn sicherungsgrad endgueltig ist und es einen endgueltigen vor dem aktuellen gibt, wird das vorige auf vorlaeufig gesetzt
   //Rueckfragen bitte an: mmi
   $query = "
      SELECT
         IF(COUNT(DISTINCT t.tumorstatus_id)>0,1,0)   AS 'invalid'
      FROM tumorstatus t
      WHERE
         IF(
            '{$sicherungsgrad}' = 'end',
            t.datum_sicherung > '{$datumSicherung}',
            t.sicherungsgrad = 'end' AND t.datum_sicherung < '{$datumSicherung}'
         ) AND
         t.anlass = '{$anlass}' AND
         t.erkrankung_id = '{$erkrankungId}' AND
         t.tumorstatus_id != '{$tumorstatusId}'
         {$sidePre}
   ";

   $resultPre = reset(sql_query_array($db, $query));
   if ($resultTime['invalid'] == 1 || $resultPre['invalid'] == 1) {
      $valid->set_err(12, 'datum_sicherung', '', $config['msg_anlass']);
   }

   //eCheck 11
   $valid->condition_and('in_array($eignung_nerverhalt, array("", 0)) == true' , array('!eignung_nerverhalt_seite'));

   //eCheck 9
   $valid->condition_and('$diagnose_c19_zuordnung != "" && in_array($diagnose, array("C19", "D01.1")) === false', array('!diagnose_c19_zuordnung'), $config['msg_diagnose_c19'], true);
   $valid->condition_and('in_array($diagnose, array("C19", "D01.1")) == false', array("!diagnose_c19_zuordnung"));


   //eCheck 8
   $diagnoseSeiteBedingung = 'in_array(substr($diagnose, 0, 3), array("C50", "D05", "D24", "N60", "N61", "N62", "N63", "N64", "C34")) === true || in_array($diagnose, array("C79.81", "D48.6")) == true';
   $valid->condition_and($diagnoseSeiteBedingung, array('diagnose_seite'));

   //eCheck 20
   $valid->condition_and('$tumorausbreitung_lokal == "1"', array(
      '!tumorausbreitung_lk && !tumorausbreitung_konausdehnung && !tumorausbreitung_fernmetastasen'
   ), $config['msg_primaer']);
    //eCheck 21
   $valid->condition_and('$tumorausbreitung_lk == "1"', array(
      '!tumorausbreitung_lokal && !tumorausbreitung_konausdehnung && !tumorausbreitung_fernmetastasen'
   ), $config['msg_primaer']);
    //eCheck 22
   $valid->condition_and('$tumorausbreitung_konausdehnung == "1"', array(
      '!tumorausbreitung_lokal && !tumorausbreitung_lk && !tumorausbreitung_fernmetastasen'
   ), $config['msg_primaer']);
    //eCheck 23
   $valid->condition_and('$tumorausbreitung_fernmetastasen == "1"', array(
      '!tumorausbreitung_lokal && !tumorausbreitung_lk && !tumorausbreitung_konausdehnung &&'
   ), $config['msg_primaer']);
}
?>
