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

require_once 'core/class/qs181/fieldConverter.php';

$table      = 'qs_18_1_brust';
$form_id    = isset( $_REQUEST['qs_18_1_brust_id'] ) ? $_REQUEST['qs_18_1_brust_id'] : '';
$bId        = isset( $_REQUEST['qs_18_1_b_id'] ) ? $_REQUEST['qs_18_1_b_id'] : dlookup($db, 'qs_18_1_brust', 'qs_18_1_b_id', "qs_18_1_brust_id = '{$form_id}'");
$location   = get_url("page=view.qs_18_1&qs_18_1_b_id={$bId}");

$qs181Converter = qs181FieldConverter::create($db, 'qs_18_1_b', $fields, $bId);

$fields     = $qs181Converter->getFields();

//Nach 2013 Feldname geaendert
if ($qs181Converter->getVersion() > 2013) {
    $removeField = 'axilladissektion';
} else {
    $removeField = 'axlkentfomark';
}

unset($fields[$removeField]);
$smarty->widget->unsetField($removeField);

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_err');

$smarty
    ->assign('button', $button )
    ->assign('qs181Version', $qs181Converter->getVersion())
    ->assign('back_btn', "page=view.qs_18_1&qs_18_1_b_id={$bId}")
;

function ext_err($valid)
{
    require_once 'core/class/qs181/validationParser.php';

    $widgetManager = $valid->_smarty->widget;

    $fields        = $valid->_fields;
    $db            = $valid->_db;

    $errType       = reset($fields['freigabe']['value']) == 1 ? 'err' : 'warn';
    $qs181bId      = reset($fields['qs_18_1_b_id']['value']);

    $errors = qs181ValidationParser::create($db)
        ->setLayer('brust')
        ->setFields(array(
            'b'     => reset(sql_query_array($db, "SELECT * FROM qs_18_1_b WHERE qs_18_1_b_id = '{$qs181bId}'")),
            'brust' => fields2dataArray($fields)
        ))
        ->parse()
        ->getErrors()
    ;

    if ($errors !== false) {
        foreach ($errors as $error) {
            foreach ($error['fields'] as $errField) {
                $valid->set_msg($errType, 12, $errField, $error['msg']);
            }
        }
    }
}

?>
