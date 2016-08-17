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

//Operateure
$query = "
    SELECT
        s.form_id   AS 'eingriff_id',
        s.form_date AS 'datum',
        IF(
            op.operateur1_id IS NOT NULL OR op.operateur2_id IS NOT NULL,
            CONCAT_WS('', IF(op.art_primaertumor = 1, 1, 0), '|' ,op.operateur1_id,'|', op.operateur2_id),
            NULL
        )   AS 'operateure'
    FROM patient p
        INNER JOIN erkrankung e ON e.patient_id = p.patient_id AND e.erkrankung = 'gt'
            INNER JOIN `status` s ON s.erkrankung_id = e.erkrankung_id AND s.form = 'eingriff'
                INNER JOIN eingriff op ON s.form_id = op.eingriff_id
    WHERE
        p.org_id = '{$this->getParam('org_id')}'
    GROUP BY
        s.form_id
    HAVING
        operateure IS NOT NULL AND {$this->_buildHaving('datum')}
";

$data = sql_query_array($this->_db, $query);

?>