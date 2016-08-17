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

$table   = 'l_icd10';
$fields  = $widget->loadExtFields('fields/base/codepicker/l_icd10.php');
$code    = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

$von = substr($code,1,3);
$bis = substr($code,5,3) . 'zzz';

$query = "
   SELECT
      *
   FROM $table
   WHERE
      sub_level = '2' AND
      (code >= '$von%')
      AND (code <= '$bis')
";

data2list( $db, $fields, $query );

$headline = dlookup( $db, $table, "description", "code = '$code'" );
$smarty->assign('headline', concat(array($headline, $code), ' '));

?>
