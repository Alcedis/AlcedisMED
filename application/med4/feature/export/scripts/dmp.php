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

require_once 'feature/export/base/class.factory.php';
require_once 'feature/export/base/class.exportexception.php';

$parameter = array();
$parameter['org_id'] = isset($_SESSION['sess_org_id']) ? $_SESSION['sess_org_id'] : '';
$parameter['user_id'] = isset($_SESSION['sess_user_id']) ? $_SESSION['sess_user_id'] : '';
$parameter['login_name'] = isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : '';
$parameter['format_date'] = isset($_SESSION['sess_format_date']) ? $_SESSION['sess_format_date'] : 'd.%m.%Y';
$parameter['export_id'] = isset($_REQUEST['export_id']) ? $_REQUEST['export_id'] : 0;
$parameter['export_type'] = isset($_REQUEST['export_type']) ? $_REQUEST['export_type'] : '';
if (isset($_REQUEST['sel_von_date'])&& strlen($_REQUEST['sel_von_date']) > 0) {
    $_SESSION['startdate'] = $_REQUEST['sel_von_date'];
}
if (isset($_REQUEST['sel_bis_date'])&& strlen($_REQUEST['sel_bis_date']) > 0) {
    $_SESSION['enddate']   = $_REQUEST['sel_bis_date'];
}
$fromDate = '2013-07-01';

if ((isset($_SESSION['startdate'])) && (strlen($_SESSION['startdate']) > 0)) {
    $fromTime = strtotime($fromDate);
    $selectedFromTime = strtotime($_SESSION['startdate']);
    if ($selectedFromTime > $fromTime) {
        $fromDate = date("Y-m-d", $selectedFromTime);
    }
}
$parameter['von_datum'] = $fromDate;
$parameter['bis_datum'] =
    ((isset($_SESSION['enddate']) && (strlen($_SESSION['enddate']) > 0))) ?
        date("Y-m-d", strtotime($_SESSION['enddate'])) : '2053-12-31';
$parameter['melde_user_id'] = isset($_REQUEST['sel_melde_user_id']) ? $_REQUEST['sel_melde_user_id'] : 0;
$parameter['empfaenger_aok'] =
    ((isset($_REQUEST['sel_empfaenger2'])) && ($_REQUEST['sel_empfaenger2'] == '1')) ? true : false;
$parameter['sw_version'] = appSettings::get("software_version");
$parameter['exportUniqueId'] = $parameter['user_id'];

$javaIsInstalled = exec('command -v java >/dev/null && echo "yes" || echo "no"');

if ($javaIsInstalled === 'no') {
    throw new EExportException("FATAL: JAVA is not installed");
}

if ((strlen($parameter['org_id']) > 0) &&
    (strlen($parameter['user_id']) > 0) &&
    (strlen($parameter['login_name']) > 0)) {
    $export = CExportFactory::CreateObject($smarty, $db, 'dmp', '2013.0', 'ext_err');
    $export->SetParameters($parameter);
    $export->addIgnoreParameters(array('export_type'));
    $export->DoStartup($permission, $action);
}
else {
    throw new EExportException("FATAL: Export parameters not set.");
}

function ext_err($valid) {
}

?>
