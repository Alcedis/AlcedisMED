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

$orderBy    = "max(login_time)";
$groupBy    = "u.user_id";
$where      = "r.org_id = {$org_id}";

$searchFields = array(
   'vorname'      => array('type' => 'string','field' => 'u.vorname'),
   'nachname'     => array('type' => 'string','field' => 'u.nachname'),
   'loginname'    => array('type' => 'string','field' => 'h.loginname'),
   'logintime'    => array('type' => 'date',  'field' => 'h.login_time')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['user_log'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setTable('history')
   ->setGroupBy($groupBy)
   ->setWhere($where)
;

data2list($db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=extras');

?>