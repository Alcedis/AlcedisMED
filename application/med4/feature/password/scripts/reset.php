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

$isReseted = isset($_REQUEST['reset']) === true ? true : false;

$values = array(
    'username'  => isset($_REQUEST['username']) === true && strlen($_REQUEST['username']) > 0 ? $_REQUEST['username'] : null,
    'captcha'   => isset($_REQUEST['captcha']) === true && strlen($_REQUEST['captcha']) > 0 ? $_REQUEST['captcha'] : null
);

foreach ($values as $name => $value) {
    if ($value !== null) {
        $values[$name] = preg_replace("/[' ]/i","", $value);
    }
}

if ($action !== NULL) {
    require('feature/password/scripts/action/reset.php');
}

if ($isReseted === false) {
    $captcha = captcha::create()
        ->setLength(5)
        ->generate()
    ;

    $_SESSION['captcha'] = $captcha->getText();

    $smarty->assign('captcha', $captcha->render());
} else {
    $logo = appSettings::get('logo');

    if ($logo !== null) {

        $uploadDir  = getUploadDir($smarty, 'upload', false);
        $path       = $uploadDir['upload'] . $uploadDir['config']['image_dir'] . $logo;

        if (is_file($path) === true) {
            $smarty
                ->assign('app_login_logo', base64_encode(file_get_contents($path)))
                ->assign('app_login_logo_img_type', appSettings::get('img_type'))
            ;
        }
    }
}

$overrideInfobar = true;

$smarty
    ->assign('reseted', $isReseted)
    ->assign('username', $values['username'])
    ->assign('button', 'reset.cancel')
    ->assign('back_btn', 'page=login')
;

?>