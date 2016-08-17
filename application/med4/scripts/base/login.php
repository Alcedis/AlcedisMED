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

require_once('fields/base/user.php');

$allowRegistration = appSettings::get('allow_registration');
$logo              = appSettings::get('logo');

//login Logo
if ($logo !== null) {

   $uploadDir  = getUploadDir($smarty, 'upload', false);
   $path        = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . $logo;

   if (is_file($path) === true) {
      $imgType = appSettings::get('img_type');

      $smarty
         ->assign('app_login_logo', "data:image/{$imgType};base64," . base64_encode(file_get_contents($path)))
      ;
   }
} else {
    $smarty->assign('app_login_logo', "media/img/app/logo.png");
}

$state      = isset( $_REQUEST['state']             ) ? $_REQUEST['state']             : '';
$loginname  = isset( $_REQUEST['loginname']         ) ? $_REQUEST['loginname']         : '';
$pwd        = isset( $_REQUEST['pwd']               ) ? $_REQUEST['pwd']               : '';
$is_login   = isset( $_REQUEST['action']['login']   ) ? true                           : false;
$isRegistration = isset( $_REQUEST['action']['registration']   ) ? true                : false;
$date       = date( 'Y-m-d G:i:s' );
$ip         = getenv('REMOTE_HOST') ? getenv('REMOTE_HOST') : getenv('REMOTE_ADDR');

$smarty->assign( 'loginname', isset( $_REQUEST['loginname'] ) ? $_REQUEST['loginname'] : '');
$smarty->assign( 'message',   isset( $_REQUEST['message']   ) ? $_REQUEST['message']   : '' );

$customCaption = appSettings::get('software_custom_title');
$caption = $customCaption !== null ? $customCaption : appSettings::get('software_title');

$smarty
    ->assign('file_help', 'app/login/med4' . (appSettings::get('allow_password_reset') === true ? '_reset' : null) . '.pdf')
    ->assign('caption', concat(array($caption, $config['caption']),' - '))
    ->assign('modus', 'login')
    ->assign('allowRegistration', $allowRegistration)
    ->assign('allowPasswordReset', appSettings::get('allow_password_reset'))
;

if ($is_login === false) {
   if ($isRegistration === true && appSettings::get('allow_registration') === true){
       action_cancel("index.php?page=registration&feature=konferenz");
   }

   if (isset($_SESSION['sess_time_login']) === true) {
		$date 		= date("Y-m-d H:i:s");
		$time_login	= $_SESSION['sess_time_login'];
		$arr_dauer  = date_diff_raw($time_login, $date);
		$dauer = $arr_dauer['h'] . ':' . $arr_dauer['m'] . ':' . $arr_dauer['s'];
		$query = " UPDATE user_log SET time_logout='$date', dauer='$dauer' " .
					" WHERE time_login='$time_login' AND  loginname='" . $_SESSION['sess_loginname'] . "'";
		$result = mysql_query($query, $db);
		// Muss an dieser Stelle einen harten Fehler werfen
		$error = mysql_error();

		if( is_resource( $result ) )
         mysql_free_result( $result );

		if(strlen($error))
			die('Error: Log-Update: ' . $error);
	}

	if (isset($_SESSION)) {
        session_destroy();
    }


	// ABSICHT: Wichtig, da sonst der Info Block in der Loginseite noch angezeigt wird!
	session_name(md5(dirname($_SERVER['SCRIPT_NAME'])));

	session_start();
	session_destroy();

   $error   = '';
   $message = '';

   switch ($state)
   {
      case 'logout':

         $message       = $config['msg_logout'];
         $footbar_logo  = NULL;

         break;

      case 'nopassed':     $message = $config['msg_wrong_session'];                           break;
      case 'pwd_changed':  $message = $config['msg_pwd_changed'];                             break;
   }

	$smarty->assign('message', $message);
	$smarty->assign('error', $error);
	return;
}


if( !( strlen($loginname) AND strlen($pwd) ) )
{
   $fields['loginname']['value'][0] = $loginname;
	$error = $config['msg_login'];
	$smarty->assign('error', $error);
	return;
}

// DB-Abfrage starten
$pwd = md5($pwd);

// von $loginname das Zeichen ' und leerzeichen entfernen
// um SQL-Injection zu verhindern
$loginname  = preg_replace( "/[' ]/i","",$loginname );
$query = " SELECT
      *
   FROM user
   WHERE (loginname='$loginname') AND (pwd='$pwd') LIMIT 1";

$result     = sql_query_array($db, $query);

$pwd_change = isset( $result[0]['pwd_change'] ) ? $result[0]['pwd_change'] : '';

$session_id = session_id();

$maxLogin = appSettings::get('user_max_login_deactivated') === true
                ? false
                : (appSettings::get('user_max_login') !== null
                    ? appSettings::get('user_max_login')
                    : false
);

if($maxLogin){
   //Schauen ob Sperreintrag vorhanden ist
   $query = "
      SELECT
         COUNT(user_lock_id) AS locked
      FROM user_lock
      WHERE
         loginname = '$loginname'
   ";

   $locked = reset(sql_query_array($db, $query));

   $locked = $locked['locked'] == 0  ? false: true;
}else{
   $locked = false;
}

if ($locked == false){
   //Login ist falsch
   if( isset($result[0]) === false) {

      // Benutzer existiert nicht
   	$fields['loginname']['value'][0] = $loginname;

   	//Error werfen
   	if(isset($maxLogin) AND $maxLogin){

         $last_unlock = dlookup($db, '_user_lock', 'MAX(updatetime)', "loginname = '{$loginname}'" );
         $last_login  = dlookup($db, 'history', 'MAX(login_time)', "loginname = '{$loginname}' AND login_acc = 1" );

         if($last_unlock > $last_login)
            $last_login = $last_unlock;

         $fail_logins = dlookup($db, 'history', 'COUNT(history_id)', "loginname = '{$loginname}' AND login_acc IS NULL AND login_time > '{$last_login}'");
         $fail_logins = strlen($fail_logins) ? $fail_logins +1: false;

         //maximale Logins Anzeigen
         if($fail_logins > ($maxLogin -1)){

            $error      = $config['msg_locked_acc'];

            //Sperreintrag schreiben
            $query     = "INSERT INTO user_lock (loginname, last_login_acc, last_login_fail, login_ip) VALUES " .
		                " ('$loginname', '$last_login','$date', '$ip')";
            mysql_query($query, $db);

         }else{
            $error       = $config['msg_wrong_login'] . ' ' . str_replace( 'xx' , $maxLogin , $config['msg_allowed_logins']);
         }
   	}else{
   	   $error   = $config['msg_wrong_login'];
   	}

   	$login_acc = 'NULL';
   	$smarty->assign('error', $error);
   }else{
      $login_acc = 1;
   }

   //History speichern
   $query = " INSERT INTO history (loginname, login_ip, login_acc, session_id, login_time )".
         " VALUES ('$loginname','$ip', $login_acc, '$session_id', '$date')";

   mysql_query($query, $db);

   //Breche ab diesem Punkt ab, wenn Login falsch
   if(!isset($result[0])){
      return;
   }

   if (isset($result[0]['reset_cookie']) === true && $result[0]['reset_cookie'] == 1) {
      $cookie = cookie::create($result[0]['user_id'], 'login');

      $cookie
         ->reset()
      ;

      //Update cookie reset
      mysql_query("UPDATE user SET reset_cookie = NULL WHERE user_id = {$result[0]['user_id']}");
   }

   // Benutzer existiert
   // Allgemein und User-Tabelle
   $_SESSION['sess_verified'] 	= true;								// Session verifiziert
   $_SESSION['sess_remote_addr'] = $_SERVER['REMOTE_ADDR'];		// Client-IP speichern
   $_SESSION['sess_time_login']	= $date;
   $_SESSION['sess_user_id']		= $result[0]['user_id'];
   $_SESSION['sess_loginname']	= $result[0]['loginname'];
   $_SESSION['sess_user_name']	= escape($result[0]['nachname'] . ', '. $result[0]['vorname']);
   $_SESSION['sess_admin']       = dlookup($db, 'recht', 'GROUP_CONCAT(user_id)', "rolle = 'admin'");

   empty_user_dir($_SESSION['sess_loginname']);

   // In die Log Tabelle eintragen
   $query = " INSERT INTO user_log (ip, loginname, session_id, time_login) VALUES " .
   		   " ('$ip', '" . $_SESSION['sess_loginname'] . "', '$session_id', '$date') ";
   $log_result = mysql_query($query, $db);

   $error = mysql_error($db);

   // Muss an dieser Stelle einen harten Fehler werfen
   if(strlen($error))
   	die('Error: Log-Insert: ' . $error);

   // Anzahl Rollen ermitteln
   $rolle_anz  =  dlookup($db, "recht", "count(*)", "user_id=" . $_SESSION['sess_user_id']);
   $_SESSION['sess_rolle_anz']	= $rolle_anz;

   if ($rolle_anz <= 0)
   {
      $fields['loginname']['value'][0] = $loginname;

      $error = $config['msg_no_right'];

   	$smarty
   	  ->assign('message', $error)
   	  ->assign('dontshowlogin', true)
   	;

   	return;
   }

   // Rollen zu diesem User existieren
   $redirect = $pwd_change != '1' ? 'user_setup' : 'rollenauswahl';

   action_cancel("index.php?page={$redirect}");
}else{
   $error = $config['msg_locked_acc'];
   $smarty->assign('error', $error);

   //History speichern mit einem fehlgeschlagenem Login
   $query = " INSERT INTO history (loginname, login_ip, session_id, login_time )".
            " VALUES ('$loginname','$ip', '$session_id', '$date')";

   mysql_query($query, $db);
}
?>
