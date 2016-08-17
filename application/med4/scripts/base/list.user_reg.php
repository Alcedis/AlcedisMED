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

$cookie = cookie::create($user_id, $pageName);

if ($permission->action($action) === true) {
    require($permission->getActionFilePath());
}

// add some field information
$fields['nachname']   = array('type' => 'string');
$fields['vorname']    = array('type' => 'string');
$fields['loginname']  = array('type' => 'string');
$fields['loginname']  = array('type' => 'string');
$fields['telefon']    = array('type' => 'string');

$searchFields = array(
   'nachname'      => array('type' => 'string', 'field'  => 'u.nachname'),
   'vorname'       => array('type' => 'string', 'field'  => 'u.vorname'),
   'loginname'     => array('type' => 'string', 'field'  => "u.loginname"),
   'telefon'       => array('type' => 'string', 'field'  => "u.telefon"),
   'org'           => array('type' => 'string', 'field'  => "u.org_name")
);

$orderBy = 'u.nachname ASC, u.vorname ASC';

$query = "
    SELECT
        u.user_reg_id,
        u.nachname,
        u.vorname,
        u.loginname,
        u.telefon,
        u.org_name,
        u.org_staat,
        u.org_bundesland,
        u.org_id,
        u.createtime,
        u.registered
    FROM (
        SELECT
            uo.user_reg_id,
            u.nachname,
            u.vorname,
            u.loginname,
            u.telefon,
            IFNULL(o.name, uo.org_name) AS 'org_name',
            org_staat,
            org_bundesland,
            null as org_id,
            DATE_FORMAT(uo.createtime, '%d.%m.%Y um %H:%i Uhr') AS createtime,
            uo.registered
        FROM user_reg uo
            INNER JOIN user u ON uo.user_id = u.user_id
            LEFT JOIN org o ON uo.org_id = o.org_id
    ) u
";

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($query)
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy('u.user_reg_id')
   ->setWhere('u.registered IS NULL')
;

data2list($db, $fields, $queryMod->query());

if (isset($fields['user_reg_id']['value']) == false) {
    $smarty->assign('message', $config['no_one_to_register']);
}

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
;

//Setzen der Links
$form_rec = get_url('page=rec.user_reg');

?>