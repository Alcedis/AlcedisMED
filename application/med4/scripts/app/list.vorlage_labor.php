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

$searchFields = array(
   'name'             => array('type' => 'string', 'field'   => "vl.bez"),
   'von'              => array('type' => 'date',   'field'   => "vl.gueltig_von"),
   'bis'              => array('type' => 'date',   'field'   => "vl.gueltig_bis"),
   'freigabe'         => array('type' => 'check',  'field'   => "vl.freigabe"),
   'inaktiv'          => array('type' => 'check',  'field'   => 'vl.inaktiv')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['vorlage_labor'])
   ->setSearchFields($searchFields)
   ->setOrderBy('vl.bez ASC')
;

data2list($db, $fields, $queryMod->query());

$arr_menubar['vorlage_labor'] = array('new' => ($permission->action('I') === true));

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=list.vorlagen')
;

?>