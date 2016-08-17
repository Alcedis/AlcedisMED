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

$table    = 'settings_export';
$form_id  = isset($_REQUEST['settings_export_id']) ? $_REQUEST['settings_export_id'] : '';
$location = 'index.php?page=list.settings_export';

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button = get_buttons($page, $form_id);

show_record($smarty, $db, $fields, 'settings_export', $form_id, '');

$smarty
   ->assign('button', $button)
   ->assign('back_btn', 'page=list.settings_export');
;


function ext_err( $valid )
{
    $fields = $valid->_fields;
    $smarty = $valid->_smarty;
    $config = $smarty->get_config_vars();

    $settingsJson = reset($fields['settings']['value']);

    if (strlen($settingsJson) > 0) {
        $validJson = json_decode($settingsJson, true);

        if ($validJson === null || (is_array($validJson) === false && str_starts_with($validJson, '{') === false)) {
            $valid->set_err(12, 'settings', null, $config['msg_settings']);
        }
    }
}

?>
