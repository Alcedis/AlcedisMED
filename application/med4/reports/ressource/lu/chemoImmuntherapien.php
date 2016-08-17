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

$data = array(
   'all'       => array(),
   'disease'   => array()
);

$query = "
   SELECT
      COUNT(DISTINCT th_sys.therapie_systemisch_id) AS 'count',
      IF(th_sys.org_id IS NULL, sit.org_id, th_sys.org_id) AS 'org_id',
      1 AS 'in'
   FROM ({$this->_getPreQuerySql()}) sit
      INNER JOIN therapie_systemisch th_sys ON th_sys.erkrankung_id = sit.erkrankung_id AND
                                               th_sys.vorlage_therapie_art IN ('ci', 'cst', 'c', 'ist', 'i') AND
                                               {$this->_buildHaving('th_sys.beginn')}
   GROUP BY
      org_id
   UNION
   SELECT
      COUNT(DISTINCT ts.therapie_systemisch_id) AS 'count',
      IF(ts.org_id IS NULL, p.org_id, ts.org_id) AS 'org_id',
      0 AS 'in'
   FROM patient p
      INNER JOIN therapie_systemisch ts ON ts.patient_id = p.patient_id AND ts.vorlage_therapie_art IN ('ci', 'cst', 'c', 'ist', 'i') AND {$this->_buildHaving('ts.beginn')}
   WHERE
      p.org_id = '{$this->getParam('org_id')}'
   GROUP BY
     org_id
   ORDER BY NULL
";

$result = sql_query_array($this->_db, $query);

$tmpData = array(
   'all' => array(
      'sort' => array(),
      'data' => array()
   ),
   'disease' => array(
      'sort' => array(),
      'data' => array()
   )
);

foreach ($result as $dataset) {
   $section = $dataset['in'] == '1' ? 'disease' : 'all';

   $tmpData[$section]['sort'][$dataset['org_id']] = 1;
   $tmpData[$section]['data'][$dataset['org_id']] = $dataset['count'];
}

foreach ($tmpData as $i => $dataset) {
   if (count($dataset['sort']) > 0) {
      $mappedLookups = getMappedLookup($this->_db, 'org', "CONCAT_WS(',', name, namenszusatz)", 'org_id', array_keys($dataset['sort']), true);

      asort($mappedLookups);

      foreach ($mappedLookups as $ie => $dummy) {
         $data[$i][$ie] = $dataset['data'][$ie];
      }
   }
}

?>