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

$table      = 'l_icd10';
$fields     = $widget->loadExtFields('fields/base/codepicker/l_icd10.php');
$parentform = isset($_REQUEST['parentform']) ? $_REQUEST['parentform'] : '';
$field      = isset($_REQUEST['txtfield']) ? $_REQUEST['txtfield'] : '';

$query = "
   SELECT
      *
   FROM $table
   WHERE
      sub_level = '1'
   ORDER BY
      code
";

if ($history === false) {
   $smarty
      ->assign('top10', createrPickerTop10($db, $parentform, $field))
   ;
}

$version = isset($config['code_icd_version']) === true ? concat(array($config['lbl_version'], $config['code_icd_version']), ' ') : null;

$smarty
->assign('caption', concat(array($config['caption'], $version), ' - '))
;

data2list( $db, $fields, $query );

?>