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

$tmp_page = $page;
if (str_ends_with($page, array( '_i', '_e' )) === true) {
    $tmp_page = str_replace( array( '_i', '_e' ), '', $page);
}

$fTpl   = "feature/export/{$tmp_page}/{$tmp_page}.tpl";
$fSript = "feature/export/scripts/{$tmp_page}.php";

if (file_exists($fTpl) === true) {
    $featureLoad['templates'] = $fTpl;
}

if (file_exists($fSript) === true) {
    $featureLoad['scripts'] = $fSript;
}

unset($fTpl);
unset($fSript);
unset($tmp_page);


// permission check
$tmpMatrix = isset($_SESSION['sess_permission_matrix']) ? $_SESSION['sess_permission_matrix'] : null;

if ($tmpMatrix !== null && isset($tmpMatrix['export_' . $page]) === true && strlen(trim($tmpMatrix['export_' . $page])) > 0) {
    $permissionGranted = true;
}

unset($tmpMatrix);
?>
