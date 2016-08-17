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

$pageName      = isset($_REQUEST['page_name']) ? $_REQUEST['page_name'] : null;
$form_id       = $_REQUEST['form_id'];

$isDForm       = isset($_REQUEST['d_form']) == true ? ($_REQUEST['d_form'] == 'true' ? true : false) : false;
$data          = isset($_REQUEST['data']) === true ? json_decode($_REQUEST['data'], true) : array();

$msg           = '';
$submitButton  = array();

$smarty->config_load('app/erkrankung.conf', 'view');
$config  = $smarty->get_config_vars();

if ($isDForm == false) {
    switch ($pageName)
    {
        case 'user':
            $role = dlookup($db, 'settings s LEFT JOIN l_basic l ON l.klasse="rolle" AND l.code = s.fastreg_role', 'bez', 's.settings_id = 1');
            $msg  = sprintf($config['msg_list_user'], $role);
        break;

        case 'user_reg':

        $dataset = reset(sql_query_array($db, "
            SELECT
                ur.org_name,
                ur.org_namenszusatz,
                CONCAT_WS(' ', ur.org_strasse, ur.org_hausnr) AS 'org_strasse',
                CONCAT_WS(' ', ur.org_plz, ur.org_ort) AS 'org_ort',
                ur.org_telefon,
                ur.org_telefax,
                ur.org_email,
                ur.org_website,
                st.bez as 'org_staat',
                bl.bez AS 'org_bundesland'
            FROM user_reg ur
                LEFT JOIN l_basic bl ON bl.klasse = 'bundesland' AND bl.code = ur.org_bundesland
                LEFT JOIN l_basic st ON st.klasse = 'staat' AND st.code = ur.org_staat
            WHERE ur.user_reg_id = '{$form_id}'
            GROUP BY
                ur.user_reg_id
        "));

        $msg = sprintf($config['msg_user_reg'],
            $dataset['org_name'],
            $dataset['org_namenszusatz'],
            $dataset['org_strasse'],
            $dataset['org_ort'],
            $dataset['org_telefon'],
            $dataset['org_telefax'],
            $dataset['org_email'],
            $dataset['org_website'],
            $dataset['org_staat'],
            $dataset['org_bundesland']
        );
        break;

        case 'vorlage_labor':
            if (isset($data['freigabe']) === true) {
                $msg = $config['msg_vorlage_dokument'];
        }
        break;

        case 'konferenz_patient':
        case 'brief':
        case 'konferenz':
        case 'settings':
        case 'tumorstatus':
        $msg = $config["msg_{$pageName}"];
        break;

        case 'vorlage_dokument':

        if (isset($data['final']) === true) {
            $msg = $config['msg_vorlage_dokument'];
        }
        break;

        case 'vorlage_studie':

        if (strlen($form_id) > 0 && isset($data['freigabe']) === false
            && strlen(dlookup($db, 'vorlage_studie', 'freigabe', "vorlage_studie_id = '$form_id'"))) {
            $msg = $config['msg_vorlage_studie'];
        } elseif (isset($data['inaktiv']) == true) {
            $msg = $config['msg_vorlage_studie'];
        }
        break;

        case 'vorlage_therapie':

        if (strlen($form_id)) {
            $freigabe = dlookup($db, 'vorlage_therapie', 'freigabe', "vorlage_therapie_id = '$form_id'");
            if ($freigabe == 1) {
                if (isset($data['inaktiv']) === true){
                    $msg = $config['msg_vorlage_therapie_inaktiv'];
                }
            } else {
                if (isset($data['freigabe']) === true) {
                    $msg = $config['msg_vorlage_therapie_freigabe'];
                }
            }
        } elseif (isset($data['freigabe']) === true) {
            $msg = $config['msg_vorlage_therapie_freigabe'];
        }
        break;

        case 'dmp_brustkrebs_ed_2013':
        case 'dmp_brustkrebs_ed_pnp_2013':
        case 'dmp_brustkrebs_fd_2013':
            $element = isset($_POST['element']) ? $_POST['element'] : null;
            switch ($element) {
                case 'action[get_dmp]':
                    $msg = $config['msg_dmp_brustkrebs_ed_2013'];
                    break;
                default;
                    $msg = $config['msg_dmp_signature'];
                    break;
            }
        break;
    }
}

if (strlen($msg) == 0) {
   $smarty->assign('passtrough', true);
}

$smarty
   ->assign('msg', $msg)
   ->assign('submitButton', $submitButton)

?>
