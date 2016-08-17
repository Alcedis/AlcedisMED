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

$code    = trim($code, '()');

$von     = substr($code, 0, 3);
$bis     = substr($code, 4, 3) .'.99';

$headline = dlookup($db, $table, 'description', "code LIKE '%{$code}%'");

$query = "
    SELECT
        {$table}.*,
        CONCAT_WS(': ', '{$headline}', IFNULL(v.bez, {$table}.description)) AS 'description'
    FROM {$table}
        LEFT JOIN vorlage_icd10 v ON v.code = {$table}.code
    WHERE
        {$table}.code BETWEEN '{$von}' AND '{$bis}'
    GROUP BY code
";

data2list($db, $fields, $query);

$headline = concat(array((strlen($headline) > 100 ? substr($headline, 0, 100) . '...' : $headline), "($code)"), ' ');
$smarty->assign('headline', $headline);

?>