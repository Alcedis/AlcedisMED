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

$table            = 'l_icd10';
$fields           = $widget->loadExtFields('fields/base/codepicker/l_icd10.php');
$suche            = isset($_REQUEST['suche']     ) ? trim($_REQUEST['suche']): '';
$art              = isset($_REQUEST['suchart']   ) ? $_REQUEST['suchart']    : '';
$searchStrings    = convertSearchString($suche);

if (strlen($suche) && count($searchStrings) > 0) {
    $search  = array();

    foreach ($searchStrings as $index => $string) {
        $search[] = "CONCAT(c.code, IFNULL(v.bez, c.description)) LIKE '%{$string}%'";
    }

    $where = implode(' AND ', $search);

    $groupCase = array();

    foreach (sql_query_array($db, "SELECT * FROM l_icd10 WHERE sub_level = 2") as $group) {
        $von     = substr($group['code'], 0, 3);
        $bis     = substr($group['code'], 4, 3) .'.99';

        $groupCase[] = "WHEN c.code BETWEEN '{$von}' AND '{$bis}' THEN '{$group['description']}'";
    }

    $groupCase = implode('', $groupCase);

    $query = "
        SELECT
            c.*,

            CONCAT_WS(': ',
                CASE
                {$groupCase}
                END,
                IFNULL(v.bez, c.description)
            ) AS 'description'
        FROM {$table} c
            LEFT JOIN vorlage_icd10 v ON v.code = c.code
        WHERE
            c.selectable = '1' AND ({$where})
        GROUP BY
            c.code
        ORDER BY
            c.code
        LIMIT
            150
    ";

    data2list($db, $fields, $query);
}

$result_count = isset($fields['code']['value']) ? count($fields['code']['value']) : 0;

if ($result_count == 0){
   $error_msg = $config['msg_noresult_1'] . ' - <strong>' . $suche . '</strong> - ' . $config['msg_noresult_2'];
   $smarty->assign('error_msg', $error_msg);
}

?>