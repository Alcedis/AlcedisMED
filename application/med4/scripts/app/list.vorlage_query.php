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

$isAdmin = ($rolle_code == 'admin');

$where = 'WHERE package ' . ($isAdmin === true ? 'IS NOT NULL' : 'IS NULL');

$sql = get_sql('list', $querys['vorlage_query'], $where, 'ORDER BY bez', $limit);

$fields['erkrankung']['ext'] = "SELECT code, bez FROM l_basic WHERE klasse = 'erkrankung'";

data2list($db, $fields, $sql);

$arr_menubar['vorlage_query'] = array('new' => ($permission->action('I') === true));

//Setzen der Links
$form_rec = get_url('page=rec.vorlage_query');

$smarty
    ->assign('back_btn', 'page=list.vorlagen')
;

?>