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

$table    = 'settings_forms';
$form_id  = isset( $_REQUEST['settings_forms_id'] ) ? $_REQUEST['settings_forms_id'] : '';
$location = get_url('page=list.settings_forms&feature=tools');

$permission->setActionFilePath('feature/tools/scripts/action/rec.settings_forms.php');

$patientMainForms = relationManager::getPatientMainForms();

if ($permission->action($action) === true) {
    require($permission->getActionFilePath());
}

$button = get_buttons ($table, $form_id);

show_record($smarty, $db, $fields, $table, $form_id);

$formsArray = strlen($form_id) > 0
    ? json_decode(unescape(reset($fields['forms']['value'])), true)
    : $patientMainForms
;

//Nur bei Update
if (is_array($formsArray) === true) {
    $forms = array();

    $backupConfig = $smarty->get_config_vars();

    $smarty->clear_config();

    // Configs zuweisen
    foreach ($formsArray as $section => $sectionContent) {
        $smarty->config_load("app/{$section}.conf", 'rec');

        $config = $smarty->get_config_vars();

        $forms[$section] = array(
            'caption' => $config['caption'],
            'content' => array()
        );

        foreach ($sectionContent as $formName => $formValue) {
            $smarty->config_load("app/{$formName}.conf", 'rec');

            $config = $smarty->get_config_vars();

            $forms[$section]['content'][$formName] = array(
                'caption'   => $config['caption'],
                'value'     => $formValue
            );
        }

        ksort($forms[$section]['content']);
    }

    $smarty->set_config($backupConfig);
    $config = $backupConfig;
    $smarty->assign('forms', $forms);
}

$smarty
    ->assign('button', $button)
    ->assign('back_btn', "page=list.settings_forms&feature=tools")
;

function convertFormToolSettings($valid)
{
    $fields = $valid->_fields;
    $patientMainForms = relationManager::getPatientMainForms();

    $request = $_REQUEST;

    foreach ($request as $name => $var) {
        if (str_starts_with($name, 'form_') === true) {
            $rest = substr($name, 5);

            $firstUnderline = strpos($rest, '_');

            $section = substr($rest, 0, $firstUnderline);
            $form = substr($rest, $firstUnderline + 1);

            if ($section !== null && $form !== null) {
                $patientMainForms[$section][$form] = 1;
            }
        }
    }

    $valid->_fields['forms']['value'][0] = json_encode($patientMainForms);
}

?>
