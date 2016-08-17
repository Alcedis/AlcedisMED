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

switch ($action) {
    case 'reset':

        $break = false;

        if ($values['username'] == null) {
            $smarty->assign('uFail', true);
            $break = true;
        }

        if  ($values['captcha'] == null || isset($_SESSION['captcha']) == false || $values['captcha'] !== $_SESSION['captcha']) {
            $smarty->assign('cFail', true);
            $break = true;
        }

        $dataset = reset(sql_query_array($db, "SELECT * FROM user WHERE loginname = '{$values['username']}'"));

        if ($dataset === false) {
            $smarty->assign('nFail', true);
            $break = true;
        } elseif (strlen($dataset['email']) == 0) {
            $smarty->assign('eFail', true);
            $break = true;
        }

        if ($break === true) {
            break;
        }

        $newPw = captcha::randomString(9);

        $userFields = $widget->loadExtFields('fields/base/user.php');

        $dataset['pwd_change'] = null;
        $dataset['pwd'] = md5($newPw);

        $userFields = dataArray2fields($dataset, $userFields);

        execute_update($smarty, $db, $userFields, 'user', "user_id = '{$dataset['user_id']}'", 'update', '', true, '-10');

        $dataset['newpw'] = $newPw;

        alcEmail::create($db, $smarty)
            ->setTemplate('password_reset')
            ->setRecipient($dataset)
            ->send()
        ;

        action_cancel('index.php?page=reset&feature=password&reset=true');

        break;

    case 'cancel':
        action_cancel('index.php?page=login');
        break;
}


?>