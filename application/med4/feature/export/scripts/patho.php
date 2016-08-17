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
$parameter[ 'von_datum' ] = isset( $_REQUEST[ 'von_datum' ] ) && ( strlen( $_REQUEST[ 'von_datum' ] ) > 0 ) ? date( "Y-m-d", strtotime( $_REQUEST[ 'von_datum' ] ) ) : '0000-00-00';
$parameter[ 'bis_datum' ] = isset( $_REQUEST[ 'bis_datum' ] ) && ( strlen( $_REQUEST[ 'bis_datum' ] ) > 0 ) ? date( "Y-m-d", strtotime( $_REQUEST[ 'bis_datum' ] ) ) : '2999-12-31';

$parameter['exportUniqueId'] = $parameter['user_id'];

if ( ( strlen( $parameter[ 'org_id' ] ) > 0 ) &&
     ( strlen( $parameter[ 'user_id' ] ) > 0 ) &&
     ( strlen( $parameter[ 'login_name' ] ) > 0 ) ) {
    $export = CExportFactory::CreateObject( $smarty, $db, 'patho', '1.0', 'ext_err' );
    $export->SetParameters( $parameter );
    $export->DoStartup( $permission, $action );
}
else {
    throw new EExportException( "FATAL: Export parameters not set." );
}

function ext_err( $valid ) {
}

?>
