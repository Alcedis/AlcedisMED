<?php/*
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

// report variables are checked and set in fieldsif ($permission->action($action) === true) {    require($permission->getActionFilePath());}if ($name == '' || $type == '') {    $smarty->assign('error', $config['msg_no_report']);} else {    // package report    if ($name == 'query') {        require_once 'core/class/report/package.php';        $queryId = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;        if ($queryId !== null) {            $smarty->assign('name', dlookup($db, 'vorlage_query', "bez", "vorlage_query_id = '{$queryId}'"));        }        $reportPackage = reportPackage::create()            ->setTarget(upload::create($smarty)->getUploadDir() . "doc/queries/{$queryId}", false)            ->loadPackage($smarty, $fields)        ;        $config = $reportPackage->getConfig();        $fields = $reportPackage->getFields();    } else {        $smarty->config_load("../reports/config/{$sub}/version.conf");        $smarty->config_load("../reports/config/{$sub}/{$name}.conf");        $config = $smarty->get_config_vars();        $title = str_replace('_', '.', strtoupper(str_replace($sub, $config['report_title'], $name)));        $smarty->assign('caption', $config['caption'] . ' ' . $title);    }    //Base filters    $filters = explode(',', $config['filter']);    foreach ($filters as $filter) {        $smarty->assign(trim($filter), true);    }    if (in_array('jahr', $filters) === false) {        unset($fields['jahr']);    }    require_once 'scripts/base/picker/picker.color.php';}show_record($smarty, $db, $fields, $table, '');$smarty    ->assign('back_btn', "page=auswertungen")    ->assign('info', (array_key_exists('info_msg', $config) === true ? $config['info_msg'] : null))    ->assign('type', $type)    ->assign('filter', $config['info_popup']);?>