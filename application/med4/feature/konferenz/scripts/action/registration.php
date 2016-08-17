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
    case 'registration':

        //Passwort immer direkt angepasst / User sofort inaktiv
        $_REQUEST['pwd_change'] = 1;
        $_REQUEST['inaktiv']    = 1;
        $_REQUEST['loginname']  = $_REQUEST['email'];

        $noError = action_insert($smarty, $db, $fields, $table, 'insert', '', 'ext_err', '', true);

        //Send registration Mail
        if ($noError === true) {
            $userReg = array();
            $userRegFields = $widget->loadExtFieldsOnce('fields/base/user_reg.php');

            foreach ($userRegFields as $fieldName => $fieldData) {
                if (array_key_exists($fieldName, $_REQUEST) === true) {
                    $userReg[$fieldName] = $_REQUEST[$fieldName];
                }
            }
            /*
            $userReg = array(
                'org_id'     => preg_replace("/[' ]/i","", $_REQUEST['org_id']),
                'org_name'   => preg_replace("/[']/i","", $_REQUEST['org_name']),
                'org_ort'    => preg_replace("/[']/i","", $_REQUEST['org_ort']),
            );*/

            $userReg['user_id'] = dlookup($db, 'user', 'user_id', "loginname = '{$fields['loginname']['value'][0]}'");

            $userRegFields = dataArray2fields($userReg, $userRegFields);

            execute_insert( $smarty, $db, $userRegFields, 'user_reg', 'insert', true, $userReg['user_id']);

            $userLoginname = reset($fields['loginname']['value']);

            // Email for registered User
            alcEmail::create($db, $smarty)
                ->setTemplate('registration_success')
                ->assignToTemplate('user_loginname', $userLoginname)
                ->setRecipient(fields2dataArray($fields))
                ->send()
            ;

            // Email to Reg Manager
            $ml = alcEmail::create($db, $smarty)
                ->setTemplate('registration_confirm')
                ->assignToTemplate('user_loginname', $userLoginname)
            ;

            $reg = sql_query_array($db, "
               SELECT
                  u.*
               FROM user u
                   INNER JOIN recht r ON u.user_id = r.user_id AND rolle = 'reg'
               GROUP BY
                   u.user_id
            ");

            foreach ($reg as $user) {
                $ml->setRecipient($user);
            }

            $ml->send();

            action_cancel('index.php?page=registration&feature=konferenz&registered=true');
        } else {
            //org_id field wurde bei "ext_err" eigentlich entfernt (weil nicht in der user tabelle)
            //sollte trotzdem noch ein anderer Fehler aufgetaucht sein wird die org_id hier wieder initialisiert
            //der Inhalt wird dann wieder von show record eingepflegt
            if (isset($fields['org_id']) === false) {
                $fields['org_id'] = array('req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'query' , 'ext' => $query_org);
            }

            if (isset($fields['org_staat']) === false) {
                $fields['org_staat'] = array('req' => 0, 'size' => '', 'maxlen' => '5',   'type' => 'lookup', 'ext' => array('l_basic' => 'staat'));
            }

            if (isset($fields['org_bundesland']) === false) {
                $fields['org_bundesland'] = array('req' => 0, 'size' => '', 'maxlen' => '5',   'type' => 'lookup', 'ext' => array('l_basic' => 'bundesland'));
            }

            $orgFields = array(
                'ort' => '',
                'plz' => 5,
                'strasse' => '',
                'hausnr' => 5,
                'name' => '',
                'namenszusatz' => '',
                'telefon' => '',
                'telefax' => '',
                'email' => '',
                'website' => ''
            );

            foreach ($orgFields as $f => $size) {
                if (isset($fields["org_{$f}"]) === false) {
                    $fields["org_{$f}"] = array('req' => 0, 'size' => $size, 'maxlen' => '255', 'type' => 'string');
                }
            }
        }

        break;

    case 'cancel':
        action_cancel('index.php?page=login');

        break;
}

?>