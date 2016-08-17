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

$form_id  = 1;
$location = 'index.php?page=rec.settings_hl7';
$table    = 'settings_hl7';

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      switch ($_REQUEST['show_dlist']) {
         case 'hl7field':

            $hl7FieldFields = $widget->loadExtFields('fields/base/settings_hl7field.php');
            $query = "SELECT * FROM settings_hl7field WHERE 1 ORDER BY med_feld";

            echo create_json_string(load_pos_sess($db, 'settings_hl7field', $query, 'hl7field', $hl7FieldFields, $config));
            exit;

            break;

         case 'hl7filter':
            $hl7FilterFields = $widget->loadExtFields('fields/base/settings_hl7filter.php');
            $query = "SELECT * FROM settings_hl7filter WHERE 1 ORDER BY med_feld";

            echo create_json_string(load_pos_sess($db, 'settings_hl7filter', $query, 'hl7filter', $hl7FilterFields, $config));
            exit;

            break;
      }
   }
}

$button = get_buttons( $page, $form_id );

show_record( $smarty, $db, $fields, 'settings_hl7', $form_id, '');

$smarty
   ->assign('button', $button)
   ->assign('hl7Active', appSettings::get('active', 'hl7'))
   ->assign('back_btn', 'page=rec.settings');

function ext_err( $valid )
{
    $valid->condition_and('$cache_diagnosetyp_active == 1' ,  array('cache_diagnose_active'));

    $fields = &$valid->_fields;

    if (strlen(reset($fields['cache_dir']['value'])) > 0 && substr(reset($fields['cache_dir']['value']), -1) !== '/') {
        $fields['cache_dir']['value'][0] .= '/';
    }
}

?>
