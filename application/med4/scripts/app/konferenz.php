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

$konferenzId = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : null;

$smarty->assign('caption', $config['caption']);

if ($konferenzId !== null) {
   //DLIST
   if ($ajax === true) {
      if (isset($_REQUEST['show_dlist']) === true) {
         switch($_REQUEST['show_dlist']){
            case 'therapieplan':

               $fields = array(
                  'therapieplan_id'  => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
                  'erkrankung_id'    => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
                  'patient_id'       => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
                  'name'             => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
                  'geburtsdatum'     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'date',      'ext' => ''),
                  'erkrankung'       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'erkrankung')),
                  'art'              => array('req' => 0, 'size' => '' ,  'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'tumorkonferenz_art') ),
               );

               $query = "
                  SELECT
                     th.*,
                     CONCAT_WS(', ', p.nachname, p.vorname) AS name,
                     p.geburtsdatum,
                     e.erkrankung,
                     kp.art
                  FROM konferenz_patient kp
                     LEFT JOIN patient p ON p.patient_id = kp.patient_id
                     LEFT JOIN erkrankung e ON e.erkrankung_id = kp.erkrankung_id
                     INNER JOIN therapieplan th ON th.konferenz_patient_id = kp.konferenz_patient_id
                  WHERE konferenz_id = '$konferenzId'
                  ";

               echo create_json_string(load_pos_sess($db, 'therapieplan', $query, 'therapieplan', $fields, $config));

               exit;

               break;
         }
      }
   } else {
      require_once 'scripts/app/konferenz_therapieplan_check.php';
      $smarty
         ->assign('konferenz_id', $konferenzId);

      //Initial Name der Konferenz
      if ($action === null && isset($_SESSION['sess_konferenz_name']) === false) {
         $_SESSION['sess_konferenz_name'] = dlookup($db, 'konferenz', 'bez', "konferenz_id = '{$konferenzId}'");
      }
   }

   //Settings
   $smarty->config_load(FILE_CONFIG_SERVER,  'aevolver');
   $smarty->config_load('settings/konferenz.conf');
   $config = $smarty->get_config_vars();

   $ccoc = isset($arr_setup[0]['ccoc'])?$arr_setup[0]['ccoc']:'t';
	$ccoc = ($ccoc == 't')? 'off' : 'on' ;
	$ussl = isset($arr_setup[0]['ussl'])?$arr_setup[0]['ussl']:'t';
	$ussl = ($ussl == 't')? '1' : '0';
   $aevolver_policy_port = isset($arr_setup[0]['aevolver_policy_port'])?$arr_setup[0]['aevolver_policy_port']:'843';

	if ($ussl == '1') {
	   $spn = '10000';
	} else {
	   $spn = '10001';
	}

	$role = $_SESSION['sess_rolle_code'];
	$cct = 2;
	if ($role == 'moderator') {
		$cct = 1;
	}

	$proxy         = "";
   $rolle         = strtoupper( $_SESSION['sess_rolle_code'] );
   $loginname     = $_SESSION['sess_loginname'];
   $cityname      = isset( $_SESSION['sess_org_ort'] ) ? $_SESSION['sess_org_ort'] : '';
   $username      = $_SESSION['sess_user_name'];
   $sessionId     = session_id();

   $flashVars     = "uid=$sessionId&amp;module=td&amp;language=de&amp;rolle=$rolle&amp;loginname=$loginname&amp;username=$username&amp;cityname=$cityname&amp;konferenz_id=$konferenzId";

   $tuk_width = isset($config['tuk_swf_width'])?$config['tuk_swf_width']:'960';
   $tuk_height = isset($config['tuk_swf_height'])?$config['tuk_swf_height']:'683';

   $smarty
      ->assign('width', $tuk_width)
      ->assign('height', $tuk_height)
      ->assign('flashVars', $flashVars)
      ->assign('client', 'media/flash/CAlcMainApplication.swf')
      ->assign('back_btn', "page=list.konferenz");
}

?>
