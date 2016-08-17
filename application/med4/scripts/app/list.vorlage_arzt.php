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

$fields = $widget->loadExtFields('fields/base/user.php');

$fields['editable']  = array('type' => 'int');
$fields['name']      = array('type' => 'string');

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
   'name'            => array('type' => 'string', 'field'  => "CONCAT_WS(', ', u.nachname, u.vorname)"),
   'adresse'         => array('type' => 'string', 'field'  => "CONCAT_WS(' ', u.strasse, u.hausnr, u.ort, u.plz)"),
   'fachabteilung'   => array('type' => 'string', 'field'  => "fa.bez"),
   'inaktiv'         => array('type' => 'check',  'field'  => 'u.inaktiv')
);

$orderBy = 'name ASC';

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['vorlage_arzt'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy('u.user_id')
   ->setHaving('admin = 0')
;

data2list($db, $fields, $queryMod->query());


$arr_menubar['vorlage_arzt'] = array('new' => ($permission->action('I') === true));

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=list.vorlagen')
;

?>