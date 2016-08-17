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

require_once 'feature/exports/scripts/class.medexportfactory.php';

$exportDone = null;

$location = 'index.php?page=extras';

form2fields($fields);

if ($permission->action($action, 'export_qsmed') === true) {
    require('feature/exports/scripts/action/qsmed.php');
}

$smarty->config_load('settings/interfaces.conf');
$config  = $smarty->get_config_vars();

// Formularfelder generieren
$item = new itemgenerator($smarty, $db, $fields, $config);
$item->preselected = false;
$item->generate_elements();

$smarty
    ->assign('export_done', $exportDone)
    ->assign('back_btn', 'page=extras')
;

function ext_err($valid) {
    $valid->start_end_date( array( 'sel_datum_von' ), array( 'sel_datum_bis' ) );
}

?>