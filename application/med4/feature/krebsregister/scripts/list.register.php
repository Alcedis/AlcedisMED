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

/* @var int $org_id */

require_once 'feature/krebsregister/class/register.php';

$type = isset($_REQUEST['type']) === true ? $_REQUEST['type'] : null;

if (strlen($type) === 0 || appSettings::get('interfaces', null, 'kr_' . $type) === false) {
    redirectTo(get_url('page=select&feature=krebsregister'));
}

$params = array(
    'org_id'    => $org_id,
    'user_id'   => $user_id,
    'type'      => $type,
    'login_name'=> (isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : 'dummy')
);

$register = register::create($db, $smarty, $type, $params);

$registerState = $register->getRegisterState();

if ($action !== NULL) {
    require('feature/krebsregister/scripts/action/register.php');
}

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
    'status'        => array('type'  => 'lookup',
                             'class' => 'status_list',
                             'val'   => $config['status'],
                             'field' => 'status'
    ),
    'errors'        => array('type'  => 'lookup',
                             'class' => 'jn',
                             'val'   => $config['error'],
                             'field' => 'errors'
    ),
    'warnings'      => array('type'  => 'lookup',
                             'class' => 'jn',
                             'val'   => $config['warning'],
                             'field' => 'warnings'
    ),
    'nachname'      => array('type' => 'string',   'field'  => 'nachname'),
    'erkrankung'    => array('type' => 'string',   'field'  => 'erkrankung'),
    'vorname'       => array('type' => 'string',   'field'  => 'vorname'),
    'geburtsdatum'  => array('type' => 'date',     'field'  => "geburtsdatum"),
    'lexport'       => array('type' => 'date',     'field'  => "lexport"),
    'patient_nr'    => array('type' => 'string',   'field'  => 'patient_nr'),
);

$patientIds = [];

if ($registerState->isCached() === true) {
    $patientIds = $registerState->getCachePatientIds();
} else {
    $patients   = $registerState->getPatients();
    $patientIds = $patients->getIds(true);
}

$currentExportId = dlookup($db, 'export_log', 'export_log_id', "export_name = 'kr_{$type}' AND org_id = '{$org_id}' AND export_unique_id = '{$org_id}' AND finished = 0");

$impPatientIds = count($patientIds) === 0 ? '0' : implode(',', $patientIds);

// prepare diseases
$diseaseQuery = "
    SELECT
        ecl.patient_id,
        GROUP_CONCAT(DISTINCT l.bez ORDER BY l.bez ASC SEPARATOR ', ') as erkrankung
    FROM export_case_log ecl
        INNER JOIN erkrankung e ON ecl.erkrankung_id = e.erkrankung_id
        INNER JOIN l_basic l ON l.klasse = 'erkrankung' AND l.code = e.erkrankung
    WHERE
        ecl.export_log_id = '{$currentExportId}' AND
        ecl.patient_id IN({$impPatientIds})
    GROUP BY 
        ecl.patient_id
";

$patientDiseaseSwitch = '""';

$patientDiseases = sql_query_array($db, $diseaseQuery);

if (count($patientDiseases) > 0) {
    $patientDiseaseSwitch = 'CASE p.patient_id ';

    foreach ($patientDiseases as $pd) {
        $patientDiseaseSwitch .= "WHEN {$pd['patient_id']} THEN '{$pd['erkrankung']}' ";
    }

    $patientDiseaseSwitch .= ' END ';
}

$queryMod = queryModifier::create($db, $smarty)
    ->setOrderBy('patient_id')
    ->setCookie($cookie)
    ->setQuery(prepareKrQuery($queries['kr_list'], array(
        'exportLogId' => $currentExportId,
        'type' => $type,
        'patientIds' => $impPatientIds,
        'pds' => $patientDiseaseSwitch))
    )
    ->setSearchFields($searchFields)
;

$result = $queryMod->query();

data2list($db, $fields, $result);

$smarty
    ->assign('entryCount', $queryMod->getDatasetCount())
    ->assign('type', $type)
;

// only on page initialize
if ($bfl === null) {
    $lastExportFinished = dlookup($db, 'export_log', 'createtime', "export_name = 'kr_{$type}' AND org_id = '{$org_id}' AND export_unique_id = '{$org_id}' AND finished = 1");
    $currentExportState = dlookup($db, 'export_log', 'createtime', "export_log_id = '{$currentExportId}'");

    $smarty
        ->assign('lastExportFinished', (strlen($lastExportFinished) > 0 ? todate($lastExportFinished, 'de') : null))
        ->assign('currentExportState', (strlen($currentExportState) > 0 ? todate($currentExportState, 'de') : null))
        ->assign('patients',           count($patientIds))
        ->assign('caption',            $config['caption_kr_' . $type])
        ->assign('back_btn',           'page=select&feature=krebsregister')
    ;
}
