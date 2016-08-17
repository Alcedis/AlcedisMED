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

$data = array();

$query = "
   SELECT
      str.strahlentherapie_id,
      str.beginn,
      sit.patient_id,
      sit.erkrankung_id,
      IF(str.org_id IS NULL, sit.org_id, str.org_id)      AS 'org_id',
      IF(sit.anlass = 'p' AND (
            COUNT(th_sys.therapie_systemisch_id) > 0 OR
            COUNT(th_str.strahlentherapie_id) > 0 OR
            COUNT(th_son.sonstige_therapie_id) > 0
        ),
        1,
        0)                                                 AS primaerfall
   FROM ({$this->_getPreQuerySql()}) sit
      INNER JOIN strahlentherapie str ON str.erkrankung_id = sit.erkrankung_id AND
                                         {$this->_buildHaving('str.beginn')} AND
                                         str.beginn BETWEEN sit.start_date AND sit.end_date AND
                                         str.endstatus = 'plan'

      INNER JOIN status s                                               ON (s.erkrankung_id = sit.erkrankung_id AND
                                                                              (s.form_date BETWEEN sit.start_date AND sit.end_date OR s.form_date IS NULL)) OR
                                                                              (s.patient_id = sit.patient_id AND s.form IN ('nachsorge', 'abschluss'))

      LEFT JOIN therapie_systemisch th_sys                              ON s.form = 'therapie_systemisch' AND th_sys.therapie_systemisch_id = s.form_id
      LEFT JOIN strahlentherapie th_str                                 ON s.form = 'strahlentherapie' AND th_str.strahlentherapie_id = s.form_id
      LEFT JOIN sonstige_therapie th_son                                ON s.form = 'sonstige_therapie' AND th_son.sonstige_therapie_id = s.form_id

   GROUP BY
      str.strahlentherapie_id
";

$result = sql_query_array($this->_db, $query);

$tmpData = array(
   'sort' => array(),
   'data' => array()
);

foreach ($result as $dataset) {
   $tmpData['sort'][$dataset['org_id']] = 1;

   if (isset($tmpData['data'][$dataset['org_id']]) === false) {
      $tmpData['data'][$dataset['org_id']] = array(
         'all' => 0,
         'primaerfall' => 0
      );
   }

   $tmpData['data'][$dataset['org_id']]['primaerfall'] += (int) ($dataset['primaerfall'] == '1');
   $tmpData['data'][$dataset['org_id']]['all']++;
}

if (count($tmpData['sort']) > 0) {
   $mappedLookups = getMappedLookup($this->_db, 'org', "CONCAT_WS(',', name, namenszusatz)", 'org_id', array_keys($tmpData['sort']), true);

   asort($mappedLookups);

   foreach ($mappedLookups as $ie => $dummy) {
      $data[$ie] = $tmpData['data'][$ie];
   }
}

?>