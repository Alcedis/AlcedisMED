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

$table    = $tbl_vorlage_krankenversicherung;
$l_table  = $tbl_l_ktst;
$fields   = $widget->loadExtFields('fields/base/codepicker/l_ktst.php');

//suchfelder
$suche           = $_REQUEST['suche'];
$search['name']  = utf8_decode($suche[0]);
$search['iknr']  = utf8_decode($suche[1]);
$search['plz']   = utf8_decode($suche[2]);
$search['vknr']  = utf8_decode($suche[3]);
$where           = array();

foreach ($search as $name => $val) {
   if (strlen($val)) {
      $where[] = $name == 'plz' ? "(plz LIKE '%$val%' OR ort LIKE '%$val%')" : "$name LIKE '%$val%'";
   }
}

$where = implode(" AND ", $where);

$query = "
   SELECT * FROM (
      SELECT
         iknr, name, vknr, ort, strasse, plz
      FROM $table
      WHERE
         $where
   UNION
      SELECT
         iknr, name, vknr, ort, strasse, plz
      FROM $l_table
      WHERE
         $where
   ) k
   ORDER BY
      k.iknr
    LIMIT 500
";

data2list($db, $fields, $query);

$result_count = isset($fields['iknr']['value']) ? count($fields['iknr']['value']) : 0;

if ($result_count == 0){
   $smarty->assign('error_msg', $config['msg_noresult_1']);
}

?>