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

$table      = 'erkrankung';
$form_id    = isset( $_REQUEST['erkrankung_id'] ) ? $_REQUEST['erkrankung_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
    if (isset($_REQUEST['show_dlist']) === true) {
        switch($_REQUEST['show_dlist']){
            case 'synchron':
                $erkrankungSynchronFields = $widget->loadExtFields('fields/app/erkrankung_synchron.php');
                $query = "SELECT * FROM erkrankung_synchron WHERE erkrankung_id='{$form_id}' ORDER BY erkrankung_synchron";

                echo create_json_string(load_pos_sess($db, 'erkrankung_synchron', $query, 'synchron', $erkrankungSynchronFields, $config));
                exit;

                break;
        }
    }
}


$button = get_buttons ( $table, $form_id, $statusLocked );
$buttonParams = array('pre' => '', 'css' => '');

if ($permission->getFormProperty('ekr') !== true) {
    if ($form_id == 0 && strpos($button, 'insert') !== false) {
        $button = $button . '.ekr';
        $buttonParams = array('pre' => "||<br/>", 'css' => "no_margin|no_margin|button_xtra_large");
    }
}

//Erkrankung auf Rolle anpassen
if (strlen($form_id) == 0) {

   $erkrankungWhere  = "code IN('" . implode($_SESSION['sess_recht_erkrankung'],"','") . "')";
   $erkrankung_query = "
      SELECT
         code,
         bez
      FROM l_basic
      WHERE
         $erkrankungWhere AND klasse = 'erkrankung' AND kennung IS NULL
   ";

   $fields['erkrankung']['type'] = 'query';
   $fields['erkrankung']['ext']  = $erkrankung_query;
}

show_record( $smarty, $db, $fields, $table, $form_id);

if (isset($backPage) == false) {
   $smarty->assign('back_btn', !strlen($form_id) ? "page=view.patient" : "page=view.erkrankung&amp;erkrankung_id=$form_id");
}

$smarty
   ->assign('button',  $button)
   ->assign('button_params',  $buttonParams);

function ext_err($valid) {
    $fields = $valid->_fields;
    $db     = $valid->_db;
    $config = $valid->_msg;

    $patientId = reset($fields['patient_id']['value']);
    $diseaseId = reset($fields['erkrankung_id']['value']);

    //eCheck 2
    $valid->condition_and('$erkrankung != "sst"', array('!erkrankung_detail'));
    $valid->condition_and('in_array($erkrankung, array("b", "gt", "h", "lu", "ly", "kh", "sst")) === false', array('!seite'));

    // check skin diseases
    $skinDiseaseRelevantYear = isset($fields['erkrankung_relevant_haut']) === true ? reset($fields['erkrankung_relevant_haut']['value']) : null;

    if (strlen($skinDiseaseRelevantYear) > 0) {
        $skinDiseaseBasicYear = substr(dlookup($db, 'histologie', 'datum', "patient_id = '{$patientId}' AND erkrankung_id = '{$diseaseId}' ORDER BY datum ASC LIMIT 1"), 0, 4);

        if ($skinDiseaseBasicYear !== $skinDiseaseRelevantYear) {
            if (strlen($skinDiseaseBasicYear) === 0) {
                $skinDiseaseBasicYear = $config['msg_no_erkrankung_relevant_date'];
            }

            $msg = str_replace('##year##', $skinDiseaseBasicYear, $config['msg_erkrankung_relevant_plausibel']);

            $valid->set_err(12, 'erkrankung_relevant_haut', null, $msg);
        }
    }

    if (isset($fields['erkrankung_relevant']) === true) {
        $diseaseRelevant = reset($fields['erkrankung_relevant']['value']);
        $disease = reset($fields['erkrankung']['value']);

        if ($diseaseRelevant === "1") {
            $where = "patient_id = '{$patientId}' AND erkrankung = '{$disease}' AND erkrankung_relevant = '1'";

            if (strlen($diseaseId) > 0) {
                $where .= "AND erkrankung_id != '{$diseaseId}'";
            }

            $anotherRelevantDisease = dlookup($db, 'erkrankung', 'erkrankung_id', $where);

            if (strlen($anotherRelevantDisease) > 0) {
                $valid->set_err(12, 'erkrankung_relevant', null, $config['validation_erkrankung_relevant']);
            }
        }
    }
}

?>
