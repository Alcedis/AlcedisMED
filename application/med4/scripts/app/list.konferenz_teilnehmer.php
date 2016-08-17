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

$konferenz_id = isset($_REQUEST['konferenz_id']) ? $_REQUEST['konferenz_id'] : '';
$location     = get_url("page=list.konferenz_teilnehmer&konferenz_id=$konferenz_id");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//Teilnehmer Profile
if ($bfl === null) {
    //Initial Name der Konferenz
    if ($action === null && isset($_SESSION['sess_konferenz_name']) === false) {
       $_SESSION['sess_konferenz_name'] = dlookup($db, 'konferenz', 'bez', "konferenz_id = '{$konferenz_id}'");
    }

    $smarty
      ->assign('teilnehmer_profil', sql_query_array($db, "SELECT konferenz_teilnehmer_profil_id, bez FROM konferenz_teilnehmer_profil"))
    ;
}

$cookie = cookie::create($user_id, $pageName);

//Fields Extension
$fields['email_status']['ext']  = array('showHtml' => true);
$fields['user']                 = array('type' => 'string');
$fields['ort']                  = array('type' => 'string');
$fields['email']                = array('type' => 'string');
$fields['telefon']              = array('type' => 'string');
$fields['fachabteilung']        = array('type' => 'string');
$fields['teilnehmer']           = array('type' => 'string');

$searchFields = array(
   'teilnehmer'     => array('type' => 'string', 'field'   => "x.teilnehmer")
);

$orderBy = 'teilnehmer ASC';

$query = "
   SELECT
      x.konferenz_teilnehmer_id,
      x.konferenz_id,
      x.user_id,
      x.user,
      x.ort,
      x.telefon,
      x.email,
      x.fachabteilung,
      x.teilnehmer,
      x.teilgenommen,
      x.email_status
   FROM
      (SELECT
         t.user_id,
         t.teilgenommen,
         t.email_status,
         t.konferenz_id,
         t.konferenz_teilnehmer_id,
         CONCAT_WS(' ', CONCAT_WS(', ', user.nachname, user.vorname)) AS user,
         user.ort                                                 AS ort,
         user.telefon                                             AS telefon,
         user.email                                               AS email,
         fachabteilung.bez                                        AS fachabteilung,
         CONCAT_WS(' ',
            CONCAT_WS(' ', CONCAT_WS(', ', user.nachname, user.vorname)),
            user.ort,
            fachabteilung.bez,
            user.telefon,
            user.email
         )                                                        AS teilnehmer
      FROM konferenz_teilnehmer  t
         LEFT JOIN user    user           ON user.user_id = t.user_id
         LEFT JOIN l_basic fachabteilung  ON fachabteilung.klasse = 'fachabteilung' AND fachabteilung.code = user.fachabteilung
      WHERE
        t.konferenz_id = '{$konferenz_id}'
      GROUP BY
        t.user_id
      ORDER BY
        NULL
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

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('link_param',  "&amp;konferenz_id={$konferenz_id}");
;

if ($bfl === null) {
    $currentConference = '<div class="current-conference" style="width: 530px !important"><b>' . $config['current_conference'] . '</b><br/>'
            . dlookup($db, 'konferenz', 'CONCAT_WS(" - ", DATE_FORMAT(datum, "%d.%m.%Y"), bez)', "konferenz_id = '{$konferenz_id}'")
            . '</div>';
    $smarty
        ->assign('conference', $currentConference)
        ->assign('bflparam', '{"konferenz_id":' . $konferenz_id . '}')
        ->assign('back_btn', "page=list.konferenz")
    ;
}

?>