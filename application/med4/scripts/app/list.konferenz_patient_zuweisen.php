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

$table         = $tbl_konferenz_patient;
$konferenz_id  = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : '';

$location      = get_url("page=list.konferenz_patient&konferenz_id={$konferenz_id}");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
   'patient'        => array('type' => 'string', 'field'   => "x.patient"),
   'erkrankung'     => array('type' => 'string', 'field'   => "x.erkrankung"),
   'art'            => array('type' => 'string', 'field'   => "x.art")
);

$orderBy = 'patname ASC';

$fields = array(
    'konferenz_patient_id'  => array('type' => 'int'),
    'patient'               => array('type' => 'string'),
    'patname'               => array('type' => 'string'),
    'erkrankung'            => array('type' => 'string'),
    'art'                   => array('type' => 'string'),
    'datenstand_datum'      => array('type' => 'string'),
    'datenstand_uhrzeit'    => array('type' => 'string'),
    'org'                   => array('type' => 'string')
);

$query = "
    SELECT
        x.konferenz_patient_id,
        x.art,
        x.org,
        x.datenstand_datum,
        x.datenstand_uhrzeit,
        x.erkrankung,
        x.patient,
        x.patname
    FROM (
        SELECT
          kp.konferenz_patient_id,
          CONCAT_WS(', ', p.nachname, p.vorname)                      AS 'patname',
          art.bez                                                     AS 'art',
          CONCAT_WS(', ',o.name, o.ort)                               AS 'org',
          DATE_FORMAT(kp.datenstand_datum, '%d.%m.%Y')                AS 'datenstand_datum',
          CONCAT(DATE_FORMAT(kp.datenstand_datum, '%I.%i'), ' Uhr')   AS 'datenstand_uhrzeit',
          e_bez.bez                                                   AS 'erkrankung',
          CONCAT_WS(' ',
            CONCAT_WS(', ', p.nachname, p.vorname),
            CONCAT_WS(', ', o.name, o.ort)
          )                                                           AS 'patient'
        FROM konferenz_patient kp
            INNER JOIN `status` s ON s.form = 'konferenz_patient' AND s.form_id = kp.konferenz_patient_id AND s.status_lock != 1

            LEFT JOIN l_basic art             ON art.klasse = 'tumorkonferenz_art' AND art.code = kp.art
            LEFT JOIN patient p               ON p.patient_id = kp.patient_id
            LEFT JOIN org o                   ON o.org_id = p.org_id

            LEFT JOIN erkrankung e            ON kp.erkrankung_id = e.erkrankung_id
            LEFT JOIN l_basic e_bez           ON e_bez.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                                 e_bez.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
        WHERE kp.konferenz_id IS NULL
        GROUP BY
            kp.konferenz_patient_id
        ORDER BY NULL
    ) x
";

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($query)
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy('x.konferenz_patient_id')
;

data2list($db, $fields, $queryMod->query());

$smarty
    ->assign('entryCount', $queryMod->getDatasetCount())
    ->assign('bflparam', '{"konferenz_id":' . $konferenz_id . '}')
    ->assign('back_btn', "page=list.konferenz_patient&amp;konferenz_id={$konferenz_id}")
;

?>