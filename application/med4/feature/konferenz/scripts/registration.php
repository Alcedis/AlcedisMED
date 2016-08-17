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

$caption = $config['caption_registration'];

$_SESSION['sess_user_id'] = -10;

$isRegistered = isset($_REQUEST['registered']) === true ? true : false;

$table  = 'user';
$fields = $widget->loadExtFieldsOnce('fields/base/user.php');

require 'feature/konferenz/fields/registration.php';

$smarty->config_load('base/user.conf', 'rec');
$config = $smarty->get_config_vars();

if ($isRegistered === false) {
    if ($action !== NULL) {
        require('feature/konferenz/scripts/action/registration.php');
    }

    show_record($smarty, $db, $fields, 'user', '');
} else {
    $caption = $config['caption_registration_successfull'];

    $logo = appSettings::get('logo');

    if ($logo !== null) {

        $uploadDir  = getUploadDir($smarty, 'upload', false);
        $path       = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . $logo;

        if (is_file($path) === true) {
            $smarty
                ->assign('app_login_logo', base64_encode(file_get_contents($path)))
                ->assign('app_login_logo_img_type',  appSettings::get('img_type'))
            ;
        }
    }
}

$overrideInfobar = true;

$smarty
    ->assign('registered', $isRegistered)
    ->assign('button', 'registration.cancel')
    ->assign('accessRegistration', true)
    ->assign('caption', $caption)
    ->assign('back_btn', 'page=login')
;

function ext_err( $valid )
{
    $smarty     = &$valid->_smarty;
    $fields     = &$valid->_fields;
    $db         = &$valid->_db;
    $config     = $valid->_msg;

    $valid->fields_req( array('loginname', 'pwd') );
    $smarty->config_load('base/user_setup.conf');

    $config += $smarty->get_config_vars();

    $check = intense_pwd_check($db, $config, $fields, 1, 'pwd');

    if(is_array($check)){
        $valid->set_err(10, $check[0], '', $check[1]);
    } else {
        $fields['pwd']['value'][0] = $check;
    }

    $username = reset($fields['email']['value']);

    if (strlen($username) > 0) {
        $exist = dlookup($db, 'user', 'user_id', "loginname = '{$username}'");

        if (strlen($exist) > 0) {
            $valid->set_err(12, 'email', '', $config['msg_already_exists']);
        }
    }

    $valid->condition_or(true, array('efn || efn_nz'));

    if (strlen(reset($fields['org_id']['value'])) == 0) {
        //Org ID has to be filled
        $valid->set_err(11, 'org_id', '');
    } elseif (reset($fields['org_id']['value']) != '0' && (strlen(reset($fields['org_name']['value'])) > 0 || strlen(reset($fields['org_ort']['value'])) > 0)) {
        if (strlen(reset($fields['org_name']['value'])) > 0) {
            $valid->set_err(12, 'org_name', '');
        }

        if (strlen(reset($fields['org_ort']['value'])) > 0) {
            $valid->set_err(12, 'org_ort', '');
        }
    } elseif (reset($fields['org_id']['value']) == '0' && (
        strlen(reset($fields['org_name']['value'])) == 0 || strlen(reset($fields['org_ort']['value'])) == 0 ||
        strlen(reset($fields['org_strasse']['value'])) == 0  || strlen(reset($fields['org_hausnr']['value'])) == 0 ||
        strlen(reset($fields['org_plz']['value'])) == 0  || strlen(reset($fields['org_staat']['value'])) == 0)
    ) {
        // ORG ID is filled but "other"
        if (strlen(reset($fields['org_name']['value'])) == 0) {
            $valid->set_err(11, 'org_name', '');
        }

        if (strlen(reset($fields['org_plz']['value'])) == 0) {
            $valid->set_err(11, 'org_plz', '');
        }

        if (strlen(reset($fields['org_strasse']['value'])) == 0) {
            $valid->set_err(11, 'org_strasse', '');
        }

        if (strlen(reset($fields['org_ort']['value'])) == 0) {
            $valid->set_err(11, 'org_ort', '');
        }

        if (strlen(reset($fields['org_hausnr']['value'])) == 0) {
            $valid->set_err(11, 'org_hausnr', '');
        }

        if (strlen(reset($fields['org_staat']['value'])) == 0) {
            $valid->set_err(11, 'org_staat', '');
        }
    } else {
        //all is ok, remove org_id from other
        foreach ($fields as $fieldName => $fieldData){
            if (str_starts_with($fieldName, 'org_') === true) {
                unset($fields[$fieldName]);
            }
        }
    }
}

?>