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
   'name'            => array('type' => 'string', 'field'   => "vf.bez"),
   'art'             => array('type' => 'string', 'field'   => "l.bez"),
   'freigabe'        => array('type' => 'check',  'field'   => "vf.freigabe"),
   'inaktiv'         => array('type' => 'check',  'field'   => 'vf.inaktiv')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['vorlage_fragebogen'])
   ->setSearchFields($searchFields)
   ->setOrderBy('vf.bez ASC')
   ->addJoin("LEFT JOIN l_basic l ON l.klasse = 'fragebogen_art' AND l.code = vf.art")
;

data2list($db, $fields, $queryMod->query());

$arr_menubar['vorlage_fragebogen'] = array('new' => ($permission->action('I') === true));

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=list.vorlagen')
;

?>