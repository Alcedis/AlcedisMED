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

$fields['created_by'] = array('type' => 'string');

$where = $rolle_code != 'admin' ? "u.user_id NOT IN ($admin)" : null;

$cookie = cookie::create($user_id, $pageName);

if ($permission->action($action) === true) {
    require($permission->getActionFilePath());
}

$searchFields = array(
   'nachname'      => array('type' => 'string', 'field'  => 'u.nachname'),
   'vorname'       => array('type' => 'string', 'field'  => 'u.vorname'),
   'loginname'     => array('type' => 'string', 'field'  => "IF(u.candidate IS NOT NULL, '', u.loginname)"),
   'angelegt'      => array('type' => 'string', 'field'  => "CONCAT_WS(', ', creator.nachname, creator.vorname)"),
   'inaktiv'       => array('type' => 'check',  'field'  => 'u.inaktiv')
);

$orderBy = 'u.nachname ASC, u.vorname ASC';

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['user'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy('u.user_id')
   ->setWhere($where)
;

data2list( $db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
;

//Setzen der Links
$form_rec = get_url( 'page=rec.user' );

?>
