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

unset($_SESSION['sess_patient_data']);

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$fields['vorname']       = array('type' => 'string');
$fields['nachname']      = array('type' => 'string');
$fields['patient_nr']    = array('type' => 'string');
$fields['geburtsdatum']  = array('type' => 'date');
$fields['erkrankung']    = array('type' => 'string');
$fields['datum']         = array('type' => 'date');
$fields['org']           = array('type' => 'string');

$cookie = cookie::create($user_id, $pageName);

$orderBy    = "z.datum DESC";
$groupBy    = "z.zweitmeinung_id";

$searchFields = array(
    'nachname'     => array('type' => 'string','field' => "z.nachname"),
    'vorname'      => array('type' => 'string','field' => "z.vorname"),
    'geburtsdatum' => array('type' => 'date',  'field' => "z.geburtsdatum"),
    'patient_nr'   => array('type' => 'string','field' => "z.patient_nr"),
    'erkrankung'   => array('type' => 'string','field' => "z.erkrankung"),
    'datum'        => array('type' => 'date',  'field' => 'z.datum'),
);

$queryMod = queryModifier::create($db, $smarty)
    ->setCookie($cookie)
    ->setTable('zweitmeinung')
    ->setQuery($querys['zweitmeinung'])
    ->setSearchFields($searchFields)
    ->setOrderBy($orderBy)
    ->setGroupBy($groupBy)
;

data2list($db, $fields, $queryMod->query());

$smarty
    ->assign('entryCount', $queryMod->getDatasetCount())
;

?>