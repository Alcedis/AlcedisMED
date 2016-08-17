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

$vorauswahl = isset($_REQUEST['vorauswahl']) ? $_REQUEST['vorauswahl']  : '';
$code       = isset($_REQUEST['code'])       ? $_REQUEST['code']        : '';

$fields = $widget->loadExtFields('fields/base/codepicker/l_ops.php');

$query = "
   SELECT
      ops.*,
      IFNULL(vops.bez, ops.description) AS description
   FROM $table ops
      LEFT JOIN vorlage_ops vops ON ops.code = vops.code
   WHERE
      ops.sub_level > 2 AND
      LEFT(ops.code,4) = '$code' AND
      ops.selectable = 1
";

data2list( $db, $fields, $query );

$headline = dlookup($db, $table, 'description', "code LIKE '%$code%'");

$smarty->assign( 'headline', (strlen($headline) > 100 ? substr($headline, 0, 100) . '...' : $headline) );
$smarty->assign( 'fields'  , $fields );
$smarty->assign( 'code'    , $code   );

?>