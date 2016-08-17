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
$parameter['exportName'] = isset($_REQUEST['exportname']) ? $_REQUEST['exportname'] : '';
$parameter['historyId'] = isset($_REQUEST['history_id']) ? $_REQUEST['history_id'] : 0;
$parameter['type'] = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;
$parameter['exportUniqueId'] = $parameter['user_id'];

if ((strlen($parameter['org_id']) > 0) &&
    (strlen($parameter['user_id']) > 0) &&
    (strlen($parameter['login_name']) > 0) &&
    (strlen($parameter['exportName']) > 0)) {

    $export = CExportFactory::CreateObject($smarty, $db, 'history', '1.0', 'ext_err');
    $export->SetParameters($parameter);

    // if type is defined, check history relevant features
    if ($parameter['type'] !== null) {
        $featureExtPath = "feature/{$parameter['type']}/scripts/feature/history.php";

        if (file_exists($featureExtPath) === true) {
            require_once $featureExtPath;
        }
    }

    $export->DoStartup($permission, $action);
}
else {
    throw new EExportException("FATAL: Export parameters not set.");
}
function ext_err($valid) {
}

?>
