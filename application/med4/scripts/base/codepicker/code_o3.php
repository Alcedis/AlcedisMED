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

$table      = 'l_icdo3';
$fields     = $widget->loadExtFields('fields/base/codepicker/l_icdo3.php');
$type       = isset($_REQUEST['o3_type']) ? $_REQUEST['o3_type'] : '';
$parentform = isset($_REQUEST['parentform']) ? $_REQUEST['parentform'] : '';
$field      = isset($_REQUEST['txtfield']) ? $_REQUEST['txtfield'] : '';

$query = "
   SELECT
      DISTINCT LEFT(code, 2)                                AS code,
      CONCAT_WS(' - ', MIN(description), MAX(description))  AS description
   FROM {$table}
   WHERE
      code LIKE '____/_'
   GROUP BY
      LEFT(code, 2)
";


if ($type !== 'm') {
   $query .= "
      UNION
         SELECT
            'diag'               AS code,
            '{$config['lbl_diag']}'  AS description
         FROM {$table}
   ";
}

$range = implode("','",range(80,99));

$query .= "
   UNION
      SELECT
         LEFT(code, 2) AS code,
         CONCAT_WS('', 'Eigene Codes des Bereichs ', LEFT(code, 2), '...') AS descrption
    FROM vorlage_icdo
    WHERE
      LEFT(code,2) NOT IN ('$range')
    GROUP BY
      LEFT(code, 2)
";

if ($history === false) {
   $smarty
      ->assign('top10', createrPickerTop10($db, $parentform, $field))
   ;
}

data2list( $db, $fields, $query . 'ORDER BY code' );

$smarty->assign('o3_type', $type);

?>