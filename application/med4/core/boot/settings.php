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

appSettings::setDB($db)
    ->refresh()
;

$_SESSION['settings'] = appSettings::get();
$smarty->assign('appSettings', appSettings::get());

// In Funktionen verf�gbar
$_SESSION['sess_format_date']     = $format_date = $config['format_date'];
$_SESSION['sess_format_time']     = $format_time = $config['format_time'];
$_SESSION['sess_format_datetime'] = $format_datetime = $config['format_datetime'];

?>