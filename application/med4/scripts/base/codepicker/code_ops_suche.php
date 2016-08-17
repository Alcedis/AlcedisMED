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

$table = 'l_ops';
$fields  = $widget->loadExtFields('fields/base/codepicker/l_ops.php');
$suche   = isset($_REQUEST['suche']) ? trim($_REQUEST['suche']) : '';

$searchStrings = convertSearchString($suche);

if (strlen($suche) && count($searchStrings) > 0) {

    $search  = array();

    foreach ($searchStrings as $index => $string) {
        $search[] = "CONCAT(ops.code, IFNULL(vops.bez, ops.description)) LIKE '%{$string}%'";
    }

    $where = implode(' AND ', $search);

    $query = "
      SELECT
         ops.*,
         IFNULL(vops.bez, ops.description) AS description
      FROM $table ops
         LEFT JOIN vorlage_ops vops ON ops.code = vops.code
      WHERE ops.selectable = 1
      AND ($where)
      ORDER BY code
      LIMIT 200";

    data2list( $db, $fields, $query );
}

$result_count = isset($fields['code']['value']) ? count($fields['code']['value']) : 0;

if ($result_count == 0){
    $error_msg = $config['msg_noresult_1'] . ' - <strong>' . $suche . '</strong> - ' . $config['msg_noresult_2'];
    $smarty->assign('error_msg', $error_msg);
}

?>
