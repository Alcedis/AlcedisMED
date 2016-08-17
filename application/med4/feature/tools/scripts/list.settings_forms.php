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

if ($action == 'cancel') {
    action_cancel('index.php?page=manager&feature=tools');
}

$cookie = cookie::create($user_id, $pageName);

$fields['name'] = array('type' => 'string');

$searchFields = array(
    'name' => array('type' => 'string', 'field' => "name"),
);

$queryMod = queryModifier::create($db, $smarty)
    ->setCookie($cookie)
    ->setQuery($querys['tools_forms'])
    ->setSearchFields($searchFields)
    ->setOrderBy('o.name ASC')
    ->setGroupBy('sf.settings_forms_id')
;

if ($rolle_code != 'admin') {
    $orgRights = dlookup($db, 'recht', 'GROUP_CONCAT(DISTINCT org_id)', "user_id = '{$user_id}' AND rolle = 'supervisor' GROUP BY user_id");
    $orgRights = strlen($orgRights) ? $orgRights : '-999';

    $queryMod->setWhere("sf.org_id IN ({$orgRights})");
}


data2list($db, $fields, $queryMod->query());

//TODO role check
$arr_menubar['settings_forms']['custom'][] = "<a href='index.php?page=rec.settings_forms&amp;feature=tools' class='button'>{$config['btn_lbl_insert']}</a>";

$smarty
    ->assign('entryCount', $queryMod->getDatasetCount())
    ->assign('back_btn', 'page=manager&feature=tools')
;
?>