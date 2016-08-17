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

unset($_SESSION['sess_konferenz_name']);

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$fields['konferenz_patienten']   = array('type' => 'int');
$fields['konferenz_dokumente']   = array('type' => 'int');
$fields['moderator_org']         = array('type' => 'string');
$fields['teilnehmer']            = array('type' => 'int');
$fields['teilnehmer_bes']        = array('type' => 'int');
$fields['teilnehmer_class']      = array('type' => 'string');
$fields['uhrzeit']               = array('type' => 'string');

$cookie = cookie::create($user_id, $pageName);

$orderBy    = "konferenz.datum DESC, konferenz.bez ASC";
$groupBy    = "konferenz.konferenz_id";

$searchFields = array(
   'bez'       => array('type' => 'string','field' => "konferenz.bez"),
   'datum'     => array('type' => 'date',  'field' => 'konferenz.datum'),
   'moderator' => array('type' => 'string','field' => "CONCAT_WS(', ', moderator.nachname, moderator.vorname)"),
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setTable('konferenz')
   ->setQuery($querys['konferenz'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy($groupBy)
;

if (in_array($rolle_code, array('moderator', 'supervisor')) === false) {
   $queryMod
      ->addJoin("INNER JOIN konferenz_teilnehmer teilnehmer ON teilnehmer.konferenz_id = konferenz.konferenz_id AND teilnehmer.user_id = {$user_id}")
   ;
}

data2list($db, $fields, $queryMod->query());

$smarty
    ->assign('permission', array(
        'konferenz_dokument'         => $permission->checkView('konferenz_dokument'),
        'konferenz_patient_zuweisen' => $permission->checkView('konferenz_patient_zuweisen'),
        'konferenz_teilnehmer'       => $permission->checkView('konferenz_teilnehmer')
    ))
    ->assign('entryCount', $queryMod->getDatasetCount())
    //Abschluss wird nur in Verbindung mit Anhaengen benoetigt, ansonsten ausblenden
    ->assign('showConclusion', appSettings::get('email_attachment'))
;

//Setzen der Links
$form_rec = 'index.php?page=rec.konferenz';

?>