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
$parameter[ 'org_id' ] = isset( $_SESSION[ 'sess_org_id' ] ) ? $_SESSION[ 'sess_org_id' ] : '';
$parameter[ 'user_id' ] = isset( $_SESSION[ 'sess_user_id' ] ) ? $_SESSION[ 'sess_user_id' ] : '';
$parameter[ 'login_name' ] = isset( $_SESSION[ 'sess_loginname' ] ) ? $_SESSION[ 'sess_loginname' ] : '';
$parameter[ 'format_date' ] = isset( $_SESSION[ 'sess_format_date' ] ) ? $_SESSION[ 'sess_format_date' ] : 'd.%m.%Y';
$parameter[ 'export_id' ] = isset( $_REQUEST[ 'export_id' ] ) ? $_REQUEST[ 'export_id' ] : 0;
$parameter[ 'wandlung_diagnose' ] = isset( $_REQUEST[ 'wandlung_diagnose' ] ) ? $_REQUEST[ 'wandlung_diagnose' ] : '0';
$parameter[ 'datum_von' ] = '0000-00-00';
$parameter[ 'datum_bis' ] = '2999-12-31';

$parameter['melder_id']        = isset($_REQUEST['melder_id']) === true && strlen($_REQUEST['melder_id']) > 0 ? $_REQUEST[ 'melder_id' ] : null;
$parameter['melder_pruefcode'] = isset($_REQUEST['pruefcode']) === true && strlen($_REQUEST['pruefcode']) > 0 ? $_REQUEST[ 'pruefcode' ] : null;

$exportUniqueId = null;

if ($parameter['melder_id'] === null || $parameter['melder_pruefcode'] === null) {
    $where = "export_name = 'krbw' AND finished = '0' AND export_unique_id LIKE '{$parameter['user_id']}-%'";

    if (strlen($dbExportUniqueId = dlookup($db, 'export_log', 'export_unique_id', $where)) > 0) {
        $exportUniqueId = $dbExportUniqueId;
        $exportParts    = explode('-', $dbExportUniqueId);

        $parameter['melder_id']        = $exportParts[1];
        $parameter['melder_pruefcode'] = $exportParts[2];
    }
} else {
    $exportUniqueId = "{$parameter['user_id']}-{$parameter['melder_id']}-{$parameter['melder_pruefcode']}";
}

$parameter['exportUniqueId'] = $exportUniqueId;

if ( ( strlen( $parameter[ 'org_id' ] ) > 0 ) &&
     ( strlen( $parameter[ 'user_id' ] ) > 0 ) &&
     ( strlen( $parameter[ 'login_name' ] ) > 0 ) ) {
    $export = CExportFactory::CreateObject( $smarty, $db, 'krbw', '2.1', 'ext_err' );
    $export->SetParameters( $parameter );
    $export->DoStartup( $permission, $action );
}
else {
    throw new EExportException( "FATAL: Export parameters not set." );
}

function ext_err( $valid ) {
}

?>
