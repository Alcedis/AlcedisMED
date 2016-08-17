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

$table      = 'qs_18_1_b';
$form_id    = isset( $_REQUEST['qs_18_1_b_id'] ) ? $_REQUEST['qs_18_1_b_id'] : '';
$location   = strlen($form_id) ? get_url("page=view.qs_18_1&qs_18_1_b_id={$form_id}") : get_url("page=view.erkrankung");

$error      = false;
$fields     = qs181FieldConverter::create($db, $table, $fields, $form_id, $_REQUEST)->getFields();

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons($table, $form_id, $statusLocked);

if (strlen($patient_id) > 0) {
    require_once('feature/export/base/helper.database.php');

    $qsmedSettings = array('org_id' => 3);

    HDatabase::LoadExportSettings($db, $qsmedSettings, 'qsmed');

    $iknr = isset($_SESSION['sess_patient_data']['org']['ik_nr']) === true && strlen($_SESSION['sess_patient_data']['org']['ik_nr'])
        ? $_SESSION['sess_patient_data']['org']['ik_nr']
        : null
    ;

    $birthdate = isset($_SESSION['sess_patient_data']['geburtsdatum']) === true && strlen($_SESSION['sess_patient_data']['geburtsdatum'])
        ? $_SESSION['sess_patient_data']['geburtsdatum']
        : null
    ;

    $gender = null;

    if (isset($_SESSION['sess_patient_data']['geschlecht']) === true && strlen($_SESSION['sess_patient_data']['geschlecht'])) {
        $geschl = $_SESSION['sess_patient_data']['geschlecht'] == 'w' ? 2 : 1;
        $gender = dlookup($db, 'l_qs', 'bez', "klasse = 'geschlecht' AND code = '{$geschl}'");
    }

    $info = array(
        'institutionsk' => $iknr,
        'entl_standort' => $qsmedSettings['standort'],
        'bsnr'          => $qsmedSettings['bsnr'],
        'fachabt'       => $qsmedSettings['fachabt'],
        'geb'           => $birthdate,
        'gender'        => $gender
    );

    $smarty->assign('info', $info);
}

//Wenn noch kein Formular angelegt, Insert button mit preload button belegen
if (strlen($form_id) == 0 && (isset($_REQUEST['aufenthalt_id']) == false || strlen($_REQUEST['aufenthalt_id']) == 0) || $error == true) {

    $fields['aufenthalt_id']['ext'] .= "
        WHERE
            a.patient_id = '{$patient_id}' AND
            a.aufnahmenr IS NOT NULL AND
            a.aufnahmedatum IS NOT NULL AND
            a.entlassungsdatum IS NOT NULL AND
            qs18b.qs_18_1_b_id IS NULL
    ";

    $button  = str_replace('insert.', '', $button);
}

show_record($smarty, $db, $fields, $table, $form_id, '', 'ext_err');

$backBtn = strlen($form_id) ? "page=view.qs_18_1&qs_18_1_b_id={$form_id}" : 'page=view.erkrankung';

$smarty
    ->assign('button', $button)
    ->assign('back_btn', $backBtn)
;

function ext_err($valid)
{
    require_once 'core/class/qs181/validationParser.php';

    $widgetManager = $valid->_smarty->widget;

    $fields        = $valid->_fields;
    $db            = $valid->_db;

    $errType       = reset($fields['freigabe']['value']) == 1 ? 'err' : 'warn';

    $errors = qs181ValidationParser::create($db)
        ->setLayer('b')
        ->setFields(array(
            'b' => fields2dataArray($fields)
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
