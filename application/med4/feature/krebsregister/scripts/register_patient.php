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

$patientId  = isset($_GET['patient_id'])    ? $_GET['patient_id']   : null;
$type       = isset($_GET['type'])          ? $_GET['type']         : null;
$case_id    = isset($_GET['case_id'])       ? $_GET['case_id']      : null;
$erkrankung = isset($_GET['erkrankung'])    ? $_GET['erkrankung']   : null;
$seite      = isset($_GET['seite'])         ? $_GET['seite']        : null;
$show       = isset($_GET['show'])          ? $_GET['show']         : 'history';
$cases      = null;
$targetCase = null;
$caption    = $config['lbl_title_' . $show];
$backbtn    = "page=list.register&type={$type}&feature=krebsregister";
$currentExportState = dlookup($db, 'export_log', 'createtime', "export_name = 'kr_{$type}' AND org_id = '{$org_id}' AND export_unique_id = '{$org_id}' AND finished = 0");

if ($type === null || appSettings::get('interfaces', null, 'kr_' . $type) === false) {
    redirectTo(get_url('page=select&feature=krebsregister'));
}

if (in_array($show, array('error', 'warning', 'history')) === false) {
    redirectTo(get_url('page=select&feature=krebsregister'));
}

$currentExportId = dlookup($db, 'export_log', 'export_log_id', "export_name = 'kr_{$type}' AND org_id = '{$org_id}' AND export_unique_id = '{$org_id}' AND finished = 0");

$params = array(
    'patient_id' => $patientId,
    'org_id' => $org_id,
    'export_unique_id' => $org_id,
    'type' => $type,
    'export_log_id' => $currentExportId,
    'sectionLogId' => null
);

if ($case_id !== null) {
    $params['sectionLogId'] = "AND esl.export_case_log_id = '{$case_id}'";
}

$query = prepareKrQuery($queries['kr_' . $show], $params);
data2list($db, $fields, $query);

if ($show !== 'history' && isset($fields['errors']['value']) > 0) {

    foreach ($fields['daten']['value'] as &$data) {
        $data = unserializeBase64($data);
    }

    foreach ($fields['errors']['value'] as &$errorCode) {
        parseXmlErrors($errorCode, $show);
    }

    //case -> sections -> errorMessages
    //sorting messages into sections and sections into cases
    $cases          = array();
    $ecl_ids        = $fields['export_case_log_id']['value'];
    $esl_ids        = $fields['export_section_log_id']['value'];
    $errorMessages  = $fields['errors']['value'];
    $erkrankung     = $fields['erkrankung']['value'];
    $anlass         = $fields['anlass']['value'];
    $seite          = $fields['diagnose_seite']['value'];
    $section_block  = $fields['block']['value'];

    foreach ($ecl_ids as $key => $ecl_id) {
        $eclAnlass = $anlass[$key];
        $eclAnlassShort = reset(explode('_', $eclAnlass));

        if (str_starts_with($eclAnlass, 'persistent') === true) {
            continue;
        }

        $cases[$ecl_id]['erkrankung'] = $erkrankung[$key];
        $cases[$ecl_id]['seite'] = isset($config['lbl_' . $seite[$key]]) ? $config['lbl_' . $seite[$key]] : $config['lbl_none'];
        $cases[$ecl_id]['anlass'] = isset($config['lbl_anlass_' . $eclAnlassShort]) ? $config['lbl_anlass_' . $eclAnlassShort] : $eclAnlass;
        $cases[$ecl_id]['section'][$esl_ids[$key]]['uid'] = $section_block[$key];
        $cases[$ecl_id]['section'][$esl_ids[$key]]['daten'] = array2ul($fields['daten']['value'][$key]);
        $cases[$ecl_id]['section'][$esl_ids[$key]]['errorMessages'] = $errorMessages[$key];
    }

    // count total error messages per case
    foreach ($cases as $case_key => $case) {
        $totalErrors = 0;
        foreach ($case['section'] as $section) {
            $totalErrors += count($section['errorMessages']);
        }

        $cases[$case_key]['total'] = $totalErrors;
    }

    // change backbutton if case id is available
    if (isset($case_id) && array_key_exists($case_id, $cases)) {
        $targetCase = $cases[$case_id];
        $backbtn    = "page=register_patient&type={$type}&feature=krebsregister&patient_id={$patientId}&show={$show}";
        if ('error' == $show) {
            $caption = 'Fehlerhafte Sektionen';
        }
        else if ('warning' == $show) {
            $caption = 'Sektionen mit Warnungen';
        }
    }
}

$smarty
    ->assign('rpShow', $show)
    ->assign('type', $type)
    ->assign('case_id', $case_id)
    ->assign('case', $targetCase)
    ->assign('cases', $cases)
    ->assign('caption', $caption)
    ->assign('back_btn', $backbtn)
    ->assign('patient', reset(sql_query_array($db, prepareKrQuery($queries['kr_register_patient'], $params))))
    ->assign('currentExportState', (strlen($currentExportState) > 0 ? todate($currentExportState, 'de') : null))
;

?>
