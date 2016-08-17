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
	if ( Authenticate( $login_name, $web_session_id ) )
	{
		if ( isset( $_GET[ 'action' ] ) )
		{
			$action = $_GET[ 'action' ];
			$_GET[ 'action' ] = "getall" ;
			switch( $action )
			{
				case "getall" :
					GetAll( $login_name, $result );
					break;
				case "add" :
					if ( ( isset( $_GET[ 'label' ] ) ) &&
					( isset( $_GET[ 'ae_title' ] ) ) &&
					( isset( $_GET[ 'hostname' ] ) ) &&
					( isset( $_GET[ 'port' ] ) ) )
					{
						$label = urldecode( $_GET[ 'label' ] );
						$ae_title = urldecode( $_GET[ 'ae_title' ] );
						$hostname = $_GET[ 'hostname' ];
						$port = $_GET[ 'port' ];
						$cipher = "";
						if ( isset( $_GET[ 'cipher' ] ) )
						{
							$cipher = $_GET[ 'cipher' ];
						}
						$description = "";
						if ( isset( $_GET[ 'description' ] ) )
						{
							$description = urldecode( $_GET[ 'description' ] );
						}
						Add( $login_name, $label, $ae_title, $hostname,
						$port, $cipher, $description, $result );
					}
					else
					{
						// Parameter error
						$result = "<result mcs_error=\"100\" />";
					}
					break;
				case "update" :
					if ( ( isset( $_GET[ 'psuid' ] ) ) &&
					( isset( $_GET[ 'label' ] ) ) &&
					( isset( $_GET[ 'ae_title' ] ) ) &&
					( isset( $_GET[ 'hostname' ] ) ) &&
					( isset( $_GET[ 'port' ] ) ) )
					{
						$psuid = $_GET[ 'psuid' ];
						$label = urldecode( $_GET[ 'label' ] );
						$ae_title = urldecode( $_GET[ 'ae_title' ] );
						$hostname = $_GET[ 'hostname' ];
						$port = $_GET[ 'port' ];
						$cipher = "";
						if ( isset( $_GET[ 'cipher' ] ) )
						{
							$cipher = $_GET[ 'cipher' ];
						}
						$description = "";
						if ( isset( $_GET[ 'description' ] ) )
						{
							$description = urldecode( $_GET[ 'description' ] );
						}
						Update( $login_name, $psuid, $label, $ae_title, $hostname,
						$port, $cipher, $description, $result );
					}
					else
					{
						// Parameter error
						$result = "<result mcs_error=\"100\" />";
					}
					break;
				case "delete" :
					if ( isset( $_GET[ 'psuid' ] ) )
					{
						$psuid = $_GET[ 'psuid' ];
						Delete( $login_name, $psuid, $result );
					}
					else
					{
						// Parameter error
						$result = "<result mcs_error=\"100\" />";
					}
					break;
				default :
					// unknown action command
					$result = "<result mcs_error=\"300\" />";
					break;
			}
		}
		else
		{
			// Parameter error
			$result = "<result mcs_error=\"100\" />";
		}
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

function Authenticate( $login_name, $web_session_id )
{
	include 'check.php';

   $check_arr = check($login_name,  $web_session_id);

   if ( $login_name == $check_arr['loginname'] )
	{
		return true;
	}
	return false;
}

function GetAll( $login_name, &$result )
{
   include_once 'db_connect.php';

   db_connect($db, $db_name);
   if (!$db) {
      die('Could not connect: ' . mysql_error());
   }

   $query = " SELECT
                 settings_pacs_id as psuid,
                 '' as org_id,
                 $db_name.user.user_id,
                 name,
                 ae_title,
                 hostname,
                 port,
                 cipher,
                 $db_name.settings_pacs.bem
              FROM $db_name.settings_pacs
              INNER JOIN $db_name.user ON ($db_name.settings_pacs.user_id = $db_name.user.user_id AND $db_name.user.loginname='".$login_name."')
              WHERE $db_name.user.loginname='".$login_name."'
          UNION ALL
             SELECT
                 settings_pacs_id as psuid,
                 $db_name.recht.org_id as org_id,
                 '' AS user_id,
                 name,
                 ae_title,
                 hostname,
                 port,
                 cipher,
                 $db_name.settings_pacs.bem
              FROM $db_name.user
              LEFT JOIN $db_name.recht ON ($db_name.recht.user_id = $db_name.user.user_id)
              LEFT JOIN $db_name.settings_pacs ON ($db_name.settings_pacs.org_id = $db_name.recht.org_id)
              WHERE $db_name.user.loginname='".$login_name."'
              GROUP BY $db_name.recht.org_id
";

   // Perform Query
   $query_result = mysql_query($query);

   // Check result
   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
   if (!$query_result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
   }

   $check_arr = array();
   $result = "<result mcs_error=\"0\" uln=\"" . $login_name . "\" action=\"getall\" >";

   while ($row = mysql_fetch_assoc($query_result)) {
   	$result .= "<pacsserver psuid=\"".$row['psuid']."\" ";
      $result .= "label=\"".$row['name']."\" ";
      $result .= "global=\"1\"";
      $result .= "ae_title=\"".$row['ae_title']."\" ";
      $result .= "hostname=\"".$row['hostname']."\" ";
      $result .= "port=\"".$row['port']."\" ";
      $result .= "cipher=\"".$row['cipher']."\" ";
      $result .= "description=\"".$row['bem']."\" ";
      $result .= "/>";
   }
	$result .= "</result>";

}

function Add( $login_name, $label, $ae_title, $hostname, $port, $cipher, $description, &$result )
{
   $user_id = getUserID($login_name);

   include_once 'db_connect.php';
   db_connect($db, $db_name);

   $query = "  INSERT
               INTO  $db_name.settings_pacs
               (settings_pacs_id, org_id, user_id, name, ae_title, hostname, port, cipher, bem, createuser, createtime, updateuser, updatetime)
               VALUES ( '', null , '".$user_id."', '".$label."', '".$ae_title."', '".$hostname."', '".$port."', '".$cipher."', '".$description."', '".$user_id."', '".date('Y-m-d H:i:s')."', '', '')
            ";

   // Perform Query
   $query_result = mysql_query($query);

   // Check result
   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
   if (!$query_result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
   }

   // $psuid = PACS Server UID
	$psuid = mysql_insert_id($db);
	$result = "<result mcs_error=\"0\" psuid=\"" . $psuid . "\" action=\"add\" />";
}

function Update( $login_name, $psuid, $label, $ae_title, $hostname, $port, $cipher, $description, &$result )
{
   if(getPermission($psuid))
   {
	   $user_id = getUserID($login_name);

	   include_once 'db_connect.php';
	   db_connect($db, $db_name);

	   $query = "
	                  UPDATE $db_name.settings_pacs
	                  SET
	                     name        = '".$label."',
	                     ae_title    = '".$ae_title."',
	                     hostname    = '".$hostname."',
	                     port        = '".$port."',
	                     cipher      = '".$cipher."',
	                     bem         = '".$description."',
	                     updateuser  = '".$user_id."',
	                     updatetime  = '".date('Y-m-d H:i:s')."'
	                  WHERE
	                     settings_pacs_id = '$psuid'
	               ";
	   // Perform Query
	   $query_result = mysql_query($query);

	   // Check result
	   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
	   if (!$query_result) {
	      $message  = 'Invalid query: ' . mysql_error() . "\n";
	      $message .= 'Whole query: ' . $query;
	      die($message);
	   }

		$result = "<result mcs_error=\"0\" psuid=\"" . $psuid . "\" action=\"update\" />";
   }
   else
   {
      $result = "<result mcs_error=\"200\" />";
   }
}

function Delete( $login_name, $psuid, &$result )
{
   if(getPermission($psuid))
   {
      include_once 'db_connect.php';
      db_connect($db, $db_name);

      $query = "
               DELETE
               FROM $db_name.settings_pacs
               WHERE $db_name.settings_pacs.settings_pacs_id='".$psuid."'
      ";
      // Perform Query
      $query_result = mysql_query($query);

      // Check result
      // This shows the actual query sent to MySQL, and the error. Useful for debugging.
      if (!$query_result) {
         $message  = 'Invalid query: ' . mysql_error() . "\n";
         $message .= 'Whole query: ' . $query;
         die($message);
      }

      $result = "<result mcs_error=\"0\" psuid=\"" . $psuid . "\" action=\"delete\" />";

   }
   else
   {
      $result = "<result mcs_error=\"200\"  />";
   }

}

function getUserID($login_name)
{
   include_once 'db_connect.php';
   db_connect($db, $db_name);

   $query = " SELECT
                 $db_name.user.user_id
              FROM $db_name.user
              WHERE $db_name.user.loginname='".$login_name."'
   ";
   // Perform Query
   $query_result = mysql_query($query);

   // Check result
   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
   if (!$query_result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
   }

   while ($row = mysql_fetch_assoc($query_result))
   {
      $user_id = isset($row['user_id'])?$row['user_id']:null;
   }

   mysql_close($db);

   return $user_id;
}


function getPermission($psuid)
{
   include_once 'db_connect.php';
   db_connect($db, $db_name);

   $query = " SELECT
                 $db_name.settings_pacs.org_id
              FROM $db_name.settings_pacs
              WHERE $db_name.settings_pacs.settings_pacs_id='".$psuid."'
   ";
   // Perform Query
   $query_result = mysql_query($query);

   // Check result
   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
   if (!$query_result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
   }
   $permission = true;
   while ($row = mysql_fetch_assoc($query_result))
   {
   	if (isset($row['org_id']))
         $permission = false;
   }

   mysql_close($db);

   return $permission;


}
echo $result;

?>
