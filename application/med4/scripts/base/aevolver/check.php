<?php
//check();
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


function check($login_name = 'aga', $web_session_id = ''){

   include 'db_connect.php';

   db_connect($db, $db_name);

   $query = " SELECT
	              session_id,
	              loginname
              FROM $db_name.user_log
              WHERE loginname='".$login_name."'
                 AND time_logout IS NULL

              ORDER BY time_login DESC
              LIMIT 1";

   // Perform Query
   $result = mysql_query($query);

   // Check result
   // This shows the actual query sent to MySQL, and the error. Useful for debugging.
   if (!$result) {
      $message  = 'Invalid query: ' . mysql_error() . "\n";
      $message .= 'Whole query: ' . $query;
      die($message);
   }

	$check_arr = array();

	while ($row = mysql_fetch_assoc($result)) {
		$check_arr['session_id'] = isset($row['session_id'])?$row['session_id']:'';
		$check_arr['loginname'] = isset($row['loginname'])?$row['loginname']:'';
	}

   mysql_close($db);

   if ($check_arr['session_id'] != $web_session_id || $check_arr['loginname'] != $login_name)
      return false;

	return ($check_arr);
}
?>