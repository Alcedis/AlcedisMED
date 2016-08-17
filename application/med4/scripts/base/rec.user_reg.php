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

$table    = 'user_reg';
$form_id  = isset($_REQUEST['user_reg_id']) ? $_REQUEST['user_reg_id'] : '';
$location = 'index.php?page=list.user_reg';

$selectedOrg = '';

$regRole  = appSettings::get('fastreg_role');

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//Schauen ob die Org vielleicht doch existiert
if (strlen($selectedOrg) == 0) {
    $dataset = reset(sql_query_array($db, "SELECT
        org_name as 'name',
        org_ort  as 'ort',
        org_id   as 'id'
    FROM user_reg
    WHERE
        user_reg_id = '{$form_id}'
    "));

    $selectedOrg = strlen($dataset['id']) > 0 && $dataset['id'] != 0
        ? $dataset['id']
        : dlookup($db, 'org', 'org_id', "name = '{$dataset['name']}' AND ort = '{$dataset['ort']}'")
    ;
}

show_record($smarty, $db, $fields, 'user_reg', $form_id, '');

$query = "
    SELECT
        CONCAT_WS(', ', u.nachname, u.vorname) as name,
        u.loginname,
        CONCAT_WS('<br/>', u.telefon, IFNULL(u.handy, '--')) AS telefon
    FROM user_reg ur
        INNER JOIN user u ON ur.user_id = u.user_id
        LEFT JOIN l_basic l ON l.klasse = 'anrede' AND l.code = u.anrede
    WHERE ur.user_reg_id = '{$form_id}'
";

$result = reset(sql_query_array($db, $query));

$smarty
   ->assign('selectedOrg', $selectedOrg)
   ->assign('orgDD', sql_query_array($db, $fields['org_id']['ext']))
   ->assign('regRole', dlookup($db, 'l_basic', 'bez', "klasse = 'rolle' AND code = '{$regRole}'"))
   ->assign('user', $result['name'])
   ->assign('loginname', $result['loginname'])
   ->assign('telefon', $result['telefon'])
   ->assign('button', 'approval.cancel')
   ->assign('back_btn', 'page=list.user_reg')
;

function ext_err($valid)
{
   $smarty     = &$valid->_smarty;
   $fields     = &$valid->_fields;
   $db         = &$valid->_db;
   $config     = $valid->_msg;

   if (strlen(reset($fields['org_id']['value'])) == 0) {
       $valid->set_err(11, 'org_id', '');
       $smarty->assign('fillOrg', true);
   }
}

?>