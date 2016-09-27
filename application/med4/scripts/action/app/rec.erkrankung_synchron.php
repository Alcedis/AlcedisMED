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

function deleteSynchronousDiseases($smarty, $db, $dataset)
{
    $diseaseId = $dataset['erkrankung_id'];
    $esId = $dataset['erkrankung_synchron'];

    $counterpartId = dlookup($db, 'erkrankung_synchron', 'erkrankung_synchron_id', "erkrankung_id = '{$esId}' AND erkrankung_synchron = '{$diseaseId}'");

    if (strlen($counterpartId) > 0) {
        $fields = $smarty->widget->loadExtFields('fields/app/erkrankung_synchron.php');

        array2fields($dataset, $fields);

        action_delete($smarty, $db, $fields, 'erkrankung_synchron', $counterpartId, 'delete', null, '', '', true);
    }
}

switch ($action) {
    case 'insert':
        ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', 'synchron', null);

        break;

    case 'update':
        ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', $arr_sess['sess_typ'], $arr_sess['sess_id']);

        break;

    case 'delete':
        ajax_action( $smarty, $db, $fields, $table, null, $action, 'ext_err', 'ext_warn', null, null, true, 'deleteSynchronousDiseases');

        break;
}

?>