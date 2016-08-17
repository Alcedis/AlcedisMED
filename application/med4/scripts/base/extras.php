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

/*
// nur Rollen Arzt und Supervisor dürfen Import/Export sehen
if (in_array($_SESSION['sess_rolle_code'], array('ARZT', 'SUPERVISOR')) === true) {
   $show_import_export = true;
} else {
   $show_import_export = false;
}

$smarty->assign( 'show_import_export', $show_import_export );

//Feststellen ob niedergelassener Arzt
if ($_SESSION['sess_rolle_code'] != "NI_ARZT") {
   $show_befragung_pdf = true;
} else {
   $show_befragung_pdf = false;
}


$smarty->assign( 'show_befragung_pdf', $show_befragung_pdf );

*/
//Kurzanleitung nur für die Demoversion anzeigen
$smarty->config_load( FILE_CONFIG_SERVER, 'demo');

$config = $smarty->get_config_vars();
$demo   = isset($config['demo']) ? $config['demo'] : false;

$smarty->assign('demo', $demo);

//Release Note check
$version            = str_replace('.', '_', appSettings::get('software_version'));
$releaseNotesPath   = "media/help/app/release_notes.pdf";

if (file_exists($releaseNotesPath) === true) {
    $smarty->assign('releaseNotes', $releaseNotesPath);
}

//User Log and User Lock
if (in_array($rolle_code, array('admin', 'supervisor')) === true && $_SESSION['settings']['user_max_login'] == true) {
   $smarty->assign('show_user_control', true);
}

?>
