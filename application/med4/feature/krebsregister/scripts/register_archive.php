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

require_once 'feature/krebsregister/class/register.php';

$patientId   = isset($_GET['patient_id'])    ? $_GET['patient_id']    : null;

if ($action === 'archive') {
    $exportLogId = isset($_GET['export_log_id']) ? $_GET['export_log_id'] : null;

    // redirect to select page if parameter not filled
    if (strlen($exportLogId) === 0 || strlen($patientId) === 0) {
        redirectTo(get_url('page=select&feature=krebsregister'));
    }

    $type = substr(dlookup($db, 'export_log', 'export_name', "export_log_id = '{$exportLogId}'"), 3);

    if (strlen($type) === 0 || appSettings::get('interfaces', null, 'kr_' . $type) === false) {
        redirectTo(get_url('page=select&feature=krebsregister'));
    }

    $params = array(
        'org_id'    => $org_id,
        'user_id'   => $user_id,
        'type'      => $type,
        'login_name'=> (isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : 'dummy')
    );

    /* @var int $org_id */

    $register = register::create($db, $smarty, $type, $params);

    $registerState = $register->getRegisterState();

    $xml = $registerState
        ->loadArchive($exportLogId, $patientId)
        ->getXml()
    ;

    $exportDate = str_replace(array('.', ':', ' ', '-'), array('_', '_', '_', '_'), dlookup($db, 'export_log', 'updatetime', "export_log_id = '{$exportLogId}'"));

    $fileName = 'kr_' . $type . '_export_' . $exportDate;

    download::create(false, 'xml', $xml)
        ->output($fileName . '.xml')
    ;
} else if ($action === 'recreate') {
    // recreate complete export for patient
    $type = isset($_GET['type']) ? $_GET['type'] : null;

    // redirect to select page if parameter not filled
    if (strlen($type) === 0 || strlen($patientId) === 0) {
        redirectTo(get_url('page=select&feature=krebsregister'));
    }

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

    $xml = $registerState
        ->prepareInitialExport($patientId)
    ;

    redirectTo(get_url('page=list.register&feature=krebsregister&type=' . $type));
}
