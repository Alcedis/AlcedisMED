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

$table         = 'konferenz_teilnehmer_profil';
$form_id       = isset( $_REQUEST['konferenz_teilnehmer_profil_id'] ) ? $_REQUEST['konferenz_teilnehmer_profil_id'] : '';
$location      = get_url("page=list.konferenz_teilnehmer_profil");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button = get_buttons($table, $form_id);
show_record($smarty, $db, $fields, $table, $form_id);

if (strlen($form_id) > 0) {
    $userList = reset($fields['user_list']['value']);

    $selected   = strlen($userList) > 0 ? $userList : "'NULL'";
    $cookie     = cookie::create($user_id, $pageName);

    $searchFields = array(
       'teilnehmer'    => array('type' => 'string', 'field'  => "x.teilnehmer"),
       'telefon'       => array('type' => 'string', 'field'  => "x.telefon"),
       'email'         => array('type' => 'string', 'field'  => "x.email"),
    );

    $orderBy = 'x.nachname ASC';

    $query = "
       SELECT
        x.user_id,
        x.user,
        x.fachabteilung,
        x.ort,
        x.telefon,
        x.email,
        x.teilnehmer,
        x.checked,
        x.nachname
       FROM(
           SELECT
              u.user_id,
              CONCAT_WS(', ', u.nachname, u.vorname)                AS user,
              IF(u.user_id IN ({$selected}),1, 0)                    AS checked,
              u.nachname                                            AS nachname,
              u.ort                                                 AS ort,
              u.telefon                                             AS telefon,
              u.email                                               AS email,
              fachabteilung.bez                                     AS fachabteilung,
              CONCAT_WS(' ',
                CONCAT_WS(', ', u.nachname, u.vorname),
                u.ort,
                fachabteilung.bez
              )                                                     AS teilnehmer
           FROM user u
              LEFT JOIN l_basic fachabteilung  ON fachabteilung.klasse = 'fachabteilung' AND fachabteilung.code = u.fachabteilung
           WHERE
              u.user_id NOT IN ({$admin})
           GROUP BY
              u.user_id
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

    $fieldsProfil = $widget->loadExtFields('fields/app/konferenz_teilnehmer_zuweisen.php');

    $fieldsProfil['teilnehmer'] = array('type' => 'string');
    $fieldsProfil['checked']    = array('type' => 'checkbox');

    data2list($db, $fieldsProfil, $queryMod->query());

    $smarty
       ->assign('entryCount', $queryMod->getDatasetCount())
       ->assign('fieldsProfil', $fieldsProfil)
       ->assign('bflparam', '{"konferenz_teilnehmer_profil_id":' . $form_id . '}')
    ;
}

$smarty
   ->assign('button', $button)
   ->assign('back_btn', "page=list.konferenz_teilnehmer_profil")
;

?>