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

$table  = 'l_nci';
$fields = $widget->loadExtFields('fields/base/codepicker/l_nci.php');
$gruppe = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';

$sql = "SELECT code, bez, grad1, grad2, grad3, grad4, grad5 FROM $table WHERE grp LIKE '$gruppe' ORDER BY bez";

data2list( $db, $fields, $sql );

$smarty->assign('grp', $gruppe);

?>