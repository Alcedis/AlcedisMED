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

$fields['created_by']   = array('type' => 'string');
$fields['loginname']    = array('type' => 'string');
$fields['rolle_bez']    = array('type' => 'string');

$where = '';

//Supervisor
if ($rolle_code != 'admin') {
   $where = "rolle.code != 'admin' AND org.org_id = '{$org_id}' AND recht.recht_id != '{$recht_id}'";
}

$orderBy = "user.nachname ASC, user.vorname ASC";
$groupBy = "recht.recht_id";

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
   'user'          => array('type' => 'string', 'field'  => "CONCAT_WS(', ', user.nachname, user.vorname)"),
   'loginname'     => array('type' => 'string', 'field'  => 'user.loginname'),
   'recht'         => array('type' => 'string', 'field'  => 'rolle.bez'),
   'org'           => array('type' => 'string', 'field'  => 'org.name'),
   'angelegt'      => array('type' => 'string', 'field'  => "CONCAT_WS(', ', created_by.nachname, created_by.vorname)"),
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['recht'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy($groupBy)
   ->setWhere($where)
;

data2list( $db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
;

?>