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

$table    = 'user';
$form_id  = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : '';
$location = 'index.php?page=list.user';

$inaktiv  = false;
if ($form_id) {
   $inaktiv    = dlookup($db, 'user', 'inaktiv',  "user_id = '$form_id'") == 1 ? true : false;

   $inactivePermission  = $permission->action('inactive');
   $activePermission    = $permission->action('active');

   $statePermission = false;

   if ($inaktiv == true && $activePermission == true) {
      $statePermission = 'active';
   } elseif($inaktiv == false && $inactivePermission == true) {
      $statePermission = 'inactive';
   }

   $smarty
      ->assign('status_locked', $inaktiv)
      ->assign('stateButton', $statePermission)
      ->assign('inaktiv', $inaktiv);
}

// email require check
if (appSettings::get('allow_registration') === true) {
    $fields['email']['req'] = 1;
    $fields['fachabteilung']['req'] = 1;
    $fields['efn']['req'] = 3;
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if (in_array($rolle_code, array('supervisor', 'admin'))) {
   $smarty->assign('accessData', true);
}

$button   = get_buttons( $page, $form_id );

show_record( $smarty, $db, $fields, 'user', $form_id, '');

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('user_id', $form_id)
   ->assign('button', $button)
   ->assign('back_btn', 'page=list.user');

function ext_err($valid) {
    $smarty     = &$valid->_smarty;
    $fields     = &$valid->_fields;
    $db         = &$valid->_db;
    $config     = $valid->_msg;

    if (appSettings::get('allow_registration') === true) {
        $valid->condition_or(true, array('efn || efn_nz'));
    }

    $valid->fields_req( array('loginname', 'pwd') );
    $smarty->config_load('base/user_setup.conf');

    $config += $smarty->get_config_vars();

    $check = intense_pwd_check($db, $config, $fields, 1, 'pwd');

    if (is_array($check) === true) {
        $valid->set_err(10, $check[0], '', $check[1]);
    } else {
        $fields['pwd']['value'][0] = $check;
    }

    $loginName = $fields['loginname']['value'][0];

    if (strlen($loginName) > 0) {
        preg_match('/^[A-Za-z0-9]+$/', $loginName, $validUsername);

        if (count($validUsername) === 0) {
            $valid->set_err(10, 'loginname', '', $config['msg_username']);
        }
    }
}

?>
