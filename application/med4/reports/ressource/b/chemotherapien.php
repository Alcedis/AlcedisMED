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

//Chemotherapien
$query = "
    SELECT
        ts.therapie_systemisch_id,
        ts.erkrankung_id,
        IF(ts.org_id IS NULL, p.org_id, ts.org_id) AS 'org_id',
        IF(MAX(t.tumorstatus_id) IS NOT NULL, 1, 0) AS 'b'
    FROM patient p
        INNER JOIN therapie_systemisch ts ON ts.patient_id = p.patient_id AND
                                          ts.vorlage_therapie_art IN ('ci', 'cst', 'c') AND
                                          {$this->_buildHaving('ts.beginn')}

        LEFT JOIN tumorstatus t ON t.erkrankung_id = ts.erkrankung_id AND (t.diagnose LIKE 'C50%' OR t.diagnose IN ('D05.1','D05.7','D05.9'))
    WHERE
        p.org_id = '{$this->getParam('org_id')}'
    GROUP BY
        therapie_systemisch_id,
        org_id
";

$data = sql_query_array($this->_db, $query);

?>