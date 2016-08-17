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

$validDiseases = array();

//alle Erkrankungen pro Org ohne Filter mit Diagnosetyp
$query = "
   SELECT
      x.*
   FROM  (
      SELECT
         sit.*,
         {$this->_buildDiagCase()}
      FROM ({$this->_getPreQuerySql()}) sit
      ORDER BY NULL
   ) x
   WHERE x.diagnosetyp IS NOT NULL
   ORDER BY NULL
";

foreach (sql_query_array($this->_db, $query) AS $dataset) {
   $validDiseases[$dataset['erkrankung_id']] = $dataset['diagnosetyp'];
}

$data = array();

//Alle gefundenen und valide angelegte Erkrankungen
if (count($validDiseases) > 0) {
    $erkrankungIds = ' AND t.erkrankung_id IN (' . implode(',', array_keys($validDiseases)) . ')';

    //Strahlentherapien dürfen nur innerhalb der gefundenen Erkrankungen liegen
    $query = "
        SELECT
            t.strahlentherapie_id   AS 'id',
            t.erkrankung_id,
            IF(t.org_id IS NULL, {$this->getParam('org_id')}, t.org_id) AS 'org_id',
            'strahlentherapie'    AS 'type',
            null                  AS 'byeffect'
       FROM strahlentherapie t
       WHERE 1
          {$erkrankungIds}
            AND {$this->_buildHaving('t.beginn')}
       UNION
       SELECT
            t.therapie_systemisch_id  AS id,
            t.erkrankung_id,
            IF(t.org_id IS NULL, {$this->getParam('org_id')}, t.org_id) AS 'org_id',
            'chemotherapie'                                             AS 'type',
            null                  AS 'byeffect'
       FROM therapie_systemisch t
       WHERE
          t.vorlage_therapie_art IN ('c','ci','cst')
          {$erkrankungIds}
            AND {$this->_buildHaving('t.beginn')}
       UNION
       /* Nur Chemoradio Therapie mit Nebenwirkungen */
       SELECT
            t.therapie_systemisch_id  AS id,
            t.erkrankung_id,
            IF(t.org_id IS NULL, {$this->getParam('org_id')}, t.org_id) AS 'org_id',
            'chemoradio'                                             AS 'type',
            GROUP_CONCAT(DISTINCT nw.nci_code)                       AS 'byeffect'
       FROM therapie_systemisch t
          INNER JOIN nebenwirkung nw ON nw.erkrankung_id = t.erkrankung_id AND nw.therapie_systemisch_id = t.therapie_systemisch_id
       WHERE
          t.vorlage_therapie_art = 'cst'
          {$erkrankungIds}
            AND {$this->_buildHaving('t.beginn')}
       GROUP BY id
       ORDER BY NULL
    ";

    $data = sql_query_array($this->_db, $query);

    foreach ($data AS $i => $dataset) {
       $data[$i]['diagnosetyp'] = $validDiseases[$dataset['erkrankung_id']];
    }
}

?>
