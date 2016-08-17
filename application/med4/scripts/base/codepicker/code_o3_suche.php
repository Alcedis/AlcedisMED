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

$table   = 'l_icdo3';
$fields  = $widget->loadExtFields('fields/base/codepicker/l_icdo3.php');
$suche   = isset($_REQUEST['suche']) ? trim($_REQUEST['suche']) : '';
$art     = isset($_REQUEST['suchart']) ? $_REQUEST['suchart'] : '';
$o3_type = isset($_REQUEST['o3_type']) ? $_REQUEST['o3_type'] : '';

$like = $o3_type == 't' ? 'NOT' : '';

$topoOnly = $o3_type == 't';

$searchStrings = convertSearchString($suche);

if (strlen($suche) && count($searchStrings) > 0) {

   $query = "
      SELECT
        code,
        description,
        sub_level
      FROM $table
      WHERE code $like LIKE '____/_'
   ";

   $vQuery = "
      SELECT
        code,
        bez AS 'description',
        'x' as 'sub_level'
      FROM vorlage_icdo
      WHERE
         1
   ";

   $description   = array();
   $bez           = array();

   foreach ($searchStrings as $index => $string) {
      $description[] = "CONCAT(description, code) LIKE '%{$string}%'";
      $bez[]         = "CONCAT(bez, code) LIKE '%{$string}%'";
   }

   $query   .= ' AND ' . implode(' AND ', $description);
   $vQuery  .= ' AND ' . implode(' AND ', $bez);

   $query = $topoOnly !== true ? "{$query} UNION {$vQuery} LIMIT 200" : "{$query} LIMIT 200";

   data2list( $db, $fields, $query );
}

$result_count = isset($fields['code']['value']) ? count($fields['code']['value']) : 0;

if ($result_count == 0){
   $error_msg = $config['msg_noresult_1'] . ' - <strong>' . $suche . '</strong> - ' . $config['msg_noresult_2'];
   $smarty->assign('error_msg', $error_msg);
}

?>