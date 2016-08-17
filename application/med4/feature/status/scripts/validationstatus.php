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

$validationStatus = sql_query_array($db, "SELECT * FROM status_log");

$configBackup = $smarty->get_config_vars();

foreach ($validationStatus as $i => $form) {
    if ($form['form'] == 'all') {
        $smarty->assign('finished', $form['validated']);
        unset($validationStatus[$i]);
        continue;
    }

    $smarty->clear_config();

    $smarty->config_load("app/{$form['form']}.conf", 'rec');

    $config = $smarty->get_config_vars();

    $validationStatus[$i]['lbl'] = $config['caption'];

    $validationStatus[$i]['formc']  = $form['form_count'] != $form['status_count']   ? 'valids-err' : 'valids-ok';
    $validationStatus[$i]['rels']   = $form['status_count'] != $form['status_relation']   ? 'valids-err' : 'valids-ok';
    $validationStatus[$i]['validc'] = $form['status_count'] > $form['validated']    ? 'valids-err' : 'valids-ok';
    $validationStatus[$i]['relf']   = $form['form_count'] != $form['form_relation']   ? 'valids-err' : 'valids-ok';
}

$smarty->set_config($configBackup);
$config = $smarty->get_config_vars();

$smarty->assign('validationStatus', array_values($validationStatus));

?>
