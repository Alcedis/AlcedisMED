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

$query = "SELECT * FROM dmp_nummern_2013 WHERE org_id = '{$org_id}' ORDER BY dmp_nr_current DESC";
$dmpNumbers = sql_query_array($db, $query);

$smarty->assign('dmpNumbers', $dmpNumbers);
$smarty->assign('back_btn', 'page=extras');
$smarty->assign('btn_lbl_insert', 'page=extras');
?>
