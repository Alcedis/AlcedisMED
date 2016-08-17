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

$result = "";

if ( ( isset( $_GET[ 'uln' ] ) ) &&
( isset( $_GET[ 'dom' ] ) ) &&
( isset( $_GET[ 'app' ] ) ) &&
( isset( $_GET[ 'wsid' ] ) ) &&
( isset( $_GET[ 'wsn' ] ) ) &&
( isset( $_GET[ 'wauid' ] ) ) )
{
	$login_name = $_GET[ 'uln' ];
	$domain = $_GET[ 'dom' ];
	$application = $_GET[ 'app' ];
	$web_session_id = $_GET[ 'wsid' ];
	$web_session_name = $_GET[ 'wsn' ];
	$web_application_uid = $_GET[ 'wauid' ];

	// Session ID aus DB holen und Benutzernamen mit DB abgleichen

	include_once 'check.php';

	$check_arr = check($login_name,  $web_session_id);

	if ( $login_name == $check_arr['loginname'] )
	{
		$result = "<result mcs_error=\"0\" uln=\"" . $login_name . "\" />";
	}
	else
	{
		// Access denied
		$result = "<result mcs_error=\"200\" />";
	}
}
else
{
	// Parameter error
	$result = "<result mcs_error=\"100\" />";
}

echo $result;

?>
