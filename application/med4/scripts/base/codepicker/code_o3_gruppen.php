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

$table  = 'l_icdo3';
$fields = $widget->loadExtFields('fields/base/codepicker/l_icdo3.php');
$code   = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
$o3_type= isset($_REQUEST['o3_type']) ? $_REQUEST['o3_type'] : '';
$topo   = $o3_type == 't';

$parentform = isset($_REQUEST['parentform']) ? $_REQUEST['parentform'] : '';
$field      = isset($_REQUEST['txtfield']) ? $_REQUEST['txtfield'] : '';

if ($topo) {
   $smarty
      ->assign('caption', $config['caption_topo'])
      ->assign('o3_type', 't');
}

if ($code == 'diag') {
   $headline = $config['head_topo'];
   $query = "
      SELECT
         code,
         description,
         sub_level
      FROM $table
      WHERE code NOT LIKE '____/_'
   ";

} else {
   $headline = $config['caption'] . $code . $config['lbl_dots'];
   $query = "
      SELECT
         code,
         description
      FROM $table
      WHERE
         code LIKE '____/_' AND
         LEFT($table.code, 2) = '$code'
   UNION
     SELECT
        code,
        bez AS 'description'
     FROM vorlage_icdo
     WHERE
         LEFT(vorlage_icdo.code, 2) = '$code'
    ORDER BY code
   ";
}

if ($history === false) {
   $smarty
      ->assign('top10', createrPickerTop10($db, $parentform, $field))
   ;
}

data2list( $db, $fields, $query );

$smarty->assign('headline', $headline);

?>