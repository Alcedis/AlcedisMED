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

$konferenz_id = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : null;

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//Initial Name der Konferenz
if ($action === null && isset($_SESSION['sess_konferenz_name']) === false) {
   $_SESSION['sess_konferenz_name'] = dlookup($db, 'konferenz', 'bez', "konferenz_id = '{$konferenz_id}'");
}

$cookie = cookie::create($user_id, $pageName);

$fields['id']         = array('type' => 'hidden');
$fields['iscodoc']    = array('type' => 'check');
$fields['ispatdoc']   = array('type' => 'check');
$fields['doc_type']   = array('type' => 'string');
$fields['type']       = array('type' => 'string');
$fields['name']       = array('type' => 'string');
$fields['geburtsdatum'] = array('type' => 'date');
$fields['org']        = array('type' => 'string');
$fields['erkrankung'] = array('type' => 'string');
$fields['dokid']      = array('type' => 'string');

$orderBy    = "kd.bez";
$groupBy    = "kd.id";

$searchFields = array(
    'bez'       => array('type' => 'string','field' => "kd.bez"),
    'zuordnung' => array('type'  => 'lookup',
        'content' => array(
            'pat'  => 'Patient',
            'konf' => 'Konferenz'
        ),
        'val'   => 'Zuordnung',
        'field' => 'kd.type'
    )
);

$modifiedQuery = sprintf($querys['konferenz_dokument'], $konferenz_id, $konferenz_id);

$queryMod = queryModifier::create($db, $smarty)
    ->setCookie($cookie)
    ->setTable('konferenz_dokument')
    ->setQuery($modifiedQuery)
    ->setSearchFields($searchFields)
    ->setOrderBy($orderBy)
    ->setGroupBy($groupBy)
;

data2list($db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('link_param', "&amp;konferenz_id={$konferenz_id}")
   ->assign('insertRight', ($permission->action('insert')))
   ->assign('bflparam', '{"konferenz_id":' . $konferenz_id . '}')
;

if ($bfl === null) {
    $smarty
        ->assign('conference', '<br/>' . dlookup($db, 'konferenz', 'CONCAT_WS(" - ", DATE_FORMAT(datum, "%d.%m.%Y"), bez)', "konferenz_id = '{$konferenz_id}'"))
        ->assign('back_btn', "page=list.konferenz")
        ->assign('dokumentExists', (isset($fields['ispatdoc']['value']) === true && array_sum($fields['ispatdoc']['value']) > 0))
    ;
}

?>