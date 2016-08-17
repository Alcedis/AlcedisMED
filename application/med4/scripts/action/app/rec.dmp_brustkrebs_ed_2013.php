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

require_once('feature/export/helper.dmp.php');

switch ($action) {
    case 'insert':
        // Fields erweitern
        HelperDmp::initXml($fields, $_REQUEST);

        if (appSettings::get('interfaces', null, 'dmp') === true) {
            $_REQUEST['org_id'] = dlookup($db, 'patient', 'org_id', "patient_id = '{$patient_id}'");

            $noErrors = action_insert($smarty, $db, $fields, $table, $action);

            if ($noErrors) {
                $fallNr = reset($fields['fall_nr']['value']);
                $orgId  = reset($fields['org_id']['value']);

                $formId = dlookup($db, 'dmp_brustkrebs_ed_2013', 'dmp_brustkrebs_ed_2013_id', "org_id = '{$orgId}' AND fall_nr = '{$fallNr}'");

                HelperDmp::updateDmpNr($db, $org_id, $fallNr);

                $parameters = array(
                    'org_id'        => isset($_SESSION['sess_org_id'])    ? $_SESSION['sess_org_id']    : '',
                    'user_id'       => isset($_SESSION['sess_user_id'])   ? $_SESSION['sess_user_id']   : '',
                    'login_name'    => isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : '',
                    'export_id'     => 0,
                    'von_datum'     => '2013-07-01',
                    'bis_datum'     => date("Y-m-d"),
                    'melde_user_id' => 1,
                    'empfaenger_aok'=> false,
                    'sw_version'    => appSettings::get("software_version")
                );

                // DMP-Formular mit Prüfmodul checken
                $result = HelperDmp::checkForm($parameters, $formId, 'ed', $smarty, $db, $_REQUEST['doku_datum']);

                // Ergebnis des Checks in die DB schreiben
                HelperDmp::setXml($result, $_REQUEST, $_SESSION);

                $_REQUEST['dmp_brustkrebs_ed_2013_id'] = $formId;

                $noErrors = action_update($smarty, $db, $fields, $table, $formId, 'update', '', '', 'ext_warn');

                if ($noErrors) {
                    action_cancel($location);
                }
            }
        } else {
            action_cancel($location);
        }

        break;

    case 'update':
        // Fields erweitern
        HelperDmp::initXml($fields, $_REQUEST);

        if (appSettings::get('interfaces', null, 'dmp') === true) {

            $noErrors = action_update($smarty, $db, $fields, $table, $formId, $action);

            if ($noErrors) {
                $parameters = array(
                    'org_id'        => isset($_SESSION['sess_org_id'])    ? $_SESSION['sess_org_id']    : '',
                    'user_id'       => isset($_SESSION['sess_user_id'])   ? $_SESSION['sess_user_id']   : '',
                    'login_name'    => isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : '',
                    'export_id'     => 0,
                    'von_datum'     => '0000-00-00',
                    'bis_datum'     => date("Y-m-d"),
                    'melde_user_id' => 1,
                    'empfaenger_aok'=> false,
                    'sw_version'    => appSettings::get("software_version")
                );

                // DMP-Formular mit Prüfmodul checken
                $result = HelperDmp::checkForm($parameters, $formId, 'ed', $smarty, $db, $_REQUEST['doku_datum']);

                // Ergebnis des Checks in die DB schreiben
                HelperDmp::setXml($result, $_REQUEST, $_SESSION);

                $noErrors = action_update($smarty, $db, $fields, $table, $formId, 'update', '', '', 'ext_warn');

                if ($noErrors) {
                    action_cancel($location);
                }
            }
        } else {
            action_cancel($location);
        }
        break;

    case 'delete':
        action_delete($smarty, $db, $fields, $table, $formId, $action, $location);

        break;

    case 'get_dmp':

        require_once('feature/export/dmp/2013/preallocate.php');

        $param = array_merge(
            $_REQUEST,
            array(
                 'org_id' => (isset($_SESSION['sess_org_id']) ? $_SESSION['sess_org_id'] : null)
            )
        );

        dmp2013Preallocate::create($db, 'ed', $param)
            ->assignTo($_REQUEST, array_keys($fields))
        ;

        if (strlen($formId) == 0) {
            HelperDmp::getKvData($db, $patient_id);

            if (array_key_exists('dmp_nr_pool_empty', $_REQUEST) === true) {
                $smarty->assign('dmp_nr_pool_empty', true);
            }
        } else {
            action_update($smarty, $db, $fields, $table, $formId, $action, '', '', 'ext_warn', true);
        }

        break;

    case 'get_kv':
        HelperDmp::getKvData($db, $patient_id);

        if (strlen($formId) > 0) {
            action_update($smarty, $db, $fields, $table, $formId, $action, '', '', 'ext_warn', true);
        }

        break;

    case 'cancel':
        action_cancel($location);

        break;
}

?>
