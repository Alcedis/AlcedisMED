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

$table         = 'konferenz_teilnehmer';
$konferenz_id  = isset($_REQUEST['konferenz_id']) ? $_REQUEST['konferenz_id'] : '';
$location      = get_url( "page=list.konferenz_teilnehmer&konferenz_id=$konferenz_id" );

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
   'teilnehmer'     => array('type' => 'string', 'field'   => "x.teilnehmer"),
   'telefon'        => array('type' => 'string', 'field'   => "x.telefon"),
   'email'          => array('type' => 'string', 'field'   => "x.email"),
);

$orderBy = 'nachname ASC';

$fields = array(
    'user_id'       => array('type' => 'int'),
    'teilnehmer'    => array('type' => 'string'),
    'telefon'       => array('type' => 'string'),
    'email'         => array('type' => 'string'),
    'user'          => array('type' => 'string'),
    'ort'           => array('type' => 'string'),
    'fachabteilung' => array('type' => 'string'),
);

$query = "
    SELECT
        x.user_id,
        x.teilnehmer,
        x.email,
        x.user,
        x.nachname,
        x.telefon,
        x.ort,
        x.fachabteilung
    FROM (
        SELECT
          u.user_id,
          u.nachname                                                            AS nachname,
          CONCAT_WS(' ', titel.bez, CONCAT_WS(', ', u.nachname, u.vorname))     AS user,
          u.ort                                                                 AS ort,
          u.telefon                                                             AS telefon,
          u.email                                                               AS email,
          fachabteilung.bez                                                     AS fachabteilung,
          CONCAT_WS(' ', CONCAT_WS(' ', titel.bez, CONCAT_WS(', ', u.nachname, u.vorname)), u.ort, fachabteilung.bez)  AS teilnehmer
        FROM user u
            LEFT JOIN l_basic titel          ON titel.klasse = 'titel' AND titel.code = u.titel
            LEFT JOIN l_basic fachabteilung  ON fachabteilung.klasse = 'fachabteilung' AND fachabteilung.code = u.fachabteilung
            LEFT JOIN konferenz_teilnehmer   teilnehmer  ON u.user_id = teilnehmer.user_id AND teilnehmer.konferenz_id = '{$konferenz_id}'
        WHERE
            teilnehmer.konferenz_id IS NULL AND u.user_id NOT IN ({$admin})
        GROUP BY
            u.user_id
        ORDER BY NULL
    ) x
";

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($query)
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy('x.user_id')
;

data2list($db, $fields, $queryMod->query());

$currentConference = '<div class="current-conference" style="width:950px !important"><b>' . $config['current_conference'] . '</b> '
        . dlookup($db, 'konferenz', 'CONCAT_WS(" - ", DATE_FORMAT(datum, "%d.%m.%Y"), bez)', "konferenz_id = '{$konferenz_id}'")
        . '</div>';

$smarty
   ->assign('conference', $currentConference)
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('bflparam', '{"konferenz_id":' . $konferenz_id . '}')
   ->assign('back_btn', "page=list.konferenz_teilnehmer&amp;konferenz_id=$konferenz_id")
;

?>