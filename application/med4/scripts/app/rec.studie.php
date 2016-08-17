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

$table      = 'studie';
$form_id    = isset( $_REQUEST['studie_id'] ) ? $_REQUEST['studie_id'] : '';
$location   = get_url('page=view.erkrankung');

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

if (strlen($form_id)) {
    $studie_id = dlookup($db, $table, 'vorlage_studie_id', "studie_id = '$form_id'");
    if (strlen($studie_id)) {
        $fields['vorlage_studie_id']['ext'] = str_replace('aktiv IS NOT NULL', "(aktiv IS NOT NULL OR vorlage_studie_id = '$studie_id')", $fields['vorlage_studie_id']['ext']);
    }
}

show_record($smarty, $db, $fields, $table, $form_id);

$smarty->assign('button',  $button);

function ext_err($valid)
{
    $valid->condition_or(true, array('date||beginn'));
}

?>