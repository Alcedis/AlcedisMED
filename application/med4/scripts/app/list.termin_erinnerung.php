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

$fields = $widget->loadExtFields('fields/app/termin.php');

if ($permission->action($action) === true) {
   require('scripts/action/app/rec.termin.php');
}

$fields['patient_nr']['type']    = 'string';
$fields['patient_name']['type']  = 'string';

$cookie = cookie::create($user_id, $pageName);

$orderBy    = "t.datum DESC";
$groupBy    = "t.termin_id";

$searchFields = array(
   'datum'        => array('type' => 'date',  'field' => 't.datum'),
   'patient_nr'   => array('type' => 'string','field' => 'p.patient_nr'),
   'name'         => array('type' => 'string','field' => "CONCAT_WS(', ', p.nachname, p.vorname)"),
   'art'          => array('type' => 'string','field' => 'l.bez'),
   'gesendet'     => array('type' => 'check', 'field' => 't.brief_gesendet')
);

$where = "
   p.org_id = {$org_id} AND
   e.erkrankung IN ('{$queryRechtErkrankung}') AND
   t.erinnerung = 1 AND
   t.erinnerung_datum <= '{$date}' AND
   t.erledigt IS NULL
";

$_SESSION['origin'] = array(
   'page' => 'list.termin_erinnerung'
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setTable('termin')
   ->setQuery($querys['termin'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy($groupBy)
   ->setWhere($where)
;

data2list($db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
;

//Setzen der Links
$form_rec = get_url('page=rec.termin');

?>