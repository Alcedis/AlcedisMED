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

$fields = $widget->loadExtFields('fields/app/termin.php');

$smarty->config_load('app/termin.conf', 'rec');
$config = $smarty->get_config_vars();

//Termin Patient kann nur ueber die Menubar aufgerufen werden und enthaelt keinen Insert / Button
//also kann die Patient Id ueber die Termin Id ausgelesen werden

$form_id  = isset($_REQUEST['termin_id']) === true ? $_REQUEST['termin_id'] : '';

$patient_id = dlookup($db, 'termin', 'patient_id', "termin_id = {$form_id}");

require 'scripts/app/rec.termin.php';

?>