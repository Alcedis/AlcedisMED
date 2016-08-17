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

// Current Date
$date = date('Y-m-d');

//needed ids
$org_id        = isset($_SESSION['sess_org_id'])       === true ? $_SESSION['sess_org_id']       : NULL;
$user_id       = isset($_SESSION['sess_user_id'])      === true ? $_SESSION['sess_user_id']      : NULL;
$patient_id    = isset($_REQUEST['patient_id']) ? $_REQUEST['patient_id'] : (isset($_SESSION['sess_patient_data']['patient_id'])  === true ? $_SESSION['sess_patient_data']['patient_id'] : NULL);
$rolle_code    = isset($_SESSION['sess_rolle_code'])   === true ? $_SESSION['sess_rolle_code']   : NULL;
$recht_id      = isset($_SESSION['sess_recht_id'])     === true ? $_SESSION['sess_recht_id']     : NULL;
$tuk_id        = isset($_SESSION['sess_tuk_id'])       === true ? $_SESSION['sess_tuk_id']       :
                     (isset($_REQUEST['tuk_id']) ? $_REQUEST['tuk_id'] : NULL);

$erkrankung_id = isset($_REQUEST['erkrankung_id']) ? $_REQUEST['erkrankung_id'] :
                     (isset($_SESSION['sess_erkrankung_data']['erkrankung_id'])  === true ? $_SESSION['sess_erkrankung_data']['erkrankung_id'] : NULL);

$admin        = isset($_SESSION['sess_admin']) === true ? $_SESSION['sess_admin'] : '0';

define('ADMIN', $admin);

$statusLocked = false;
$requestPage = isset($_REQUEST['page']) === true ? $_REQUEST['page'] : 'login';
$arr_section = get_arr_section($requestPage);

$featureRequest = false;

$page       = $arr_section['file'];
$pageType   = $arr_section['type'];
$pageName   = $requestPage;

$subdir     = isset($_REQUEST['subdir']) === true  && strlen($_REQUEST['subdir']) > 0
   ? $_REQUEST['subdir'] . '/'
   : '';

//BFL
$bfl = isset($_REQUEST['bfl']) === true  && strlen($_REQUEST['bfl']) > 0
   ? $_REQUEST['bfl'] . '/'
   : null;


//for disease extension
$bflSub = isset($_REQUEST['bflsub']) === true  && strlen($_REQUEST['bflsub']) > 0
? '_' . $_REQUEST['bflsub']
: null;

$ajax = isset($_REQUEST['ajax']);

$codepicker = isset($_REQUEST['codepicker']);

/**
 ** Action
 **/
$action = isset($_REQUEST['action']) === true
   ? is_array($_REQUEST['action']) === true
      ? key($_REQUEST['action'])
      : $_REQUEST['action']
   : null;

//Application Order for script and template files. Could be overrided
$appOrder = array(
   'app' => array(
      'folder'    => 'app'
   ),
   'base' => array(
      'folder'    => 'base'
   )
);

bflBuffer::init($_REQUEST);

?>