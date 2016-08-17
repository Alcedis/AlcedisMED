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

$table    = 'patient';
$form_id  = isset( $_REQUEST['patient_id'] ) ? $_REQUEST['patient_id'] : '';
$location = get_url('page=list.patient_import');

//Standard ist die Org Id des Patienten immer die Org Id des erstellers/updaters
if ($rolle_code !== 'dateneingabe') {
    //Nur beim Insert die Org id mit der Org_id des Nutzers vorbelegen
    if (strlen($form_id) == 0) {
        $_REQUEST['org_id'] = $org_id;
    }
} else {
    $smarty->assign('viewOrg', true);
}

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

$button = get_buttons($table, $form_id, $statusLocked);

show_record($smarty, $db, $fields, $table, $form_id);

// Auto Patient Id
if (appSettings::get('auto_patient_id') === true) {

    if (strlen($form_id) > 0) {
        $autoPatientId = reset($fields['patient_nr']['value']);
    } else {
        $autoPatientId = $config['msg_automatic'];
    }
    $smarty->assign('autoPatientId', $autoPatientId);
}

$back_btn = isset($backPage) === true
   ? $backPage
   : (
      strlen($form_id) > 0
         ? "page=view.patient&amp;patient_id=$patient_id"
         : (isset($_REQUEST['no_import']) ? "page=list.patient" : "page=list.patient_import")
   )
;

$smarty
   ->assign('button',  $button)
   ->assign('back_btn', $back_btn);

function ext_err($valid)
{
    $smarty     = &$valid->_smarty;
    $db         = $valid->_db;
    $fields     = &$valid->_fields;
    $config     = $valid->_msg;

    if (isset($fields['kv_iknr']) === true && reset($fields['kv_iknr']['value']) == '109999999') {
        $valid->set_err(12, 'kv_iknr', null, $config['err_iknr'] );
    }

    if (isset($fields['kv_iknr']) === true && strlen(reset($fields['kv_iknr']['value'])) > 0 &&
        isset($fields['kv_abrechnungsbereich']) === true && strlen(reset($fields['kv_abrechnungsbereich']['value'])) > 0
    ) {
        $iknr = mysql_real_escape_string(reset($fields['kv_iknr']['value']));

        if (strlen(dlookup($db, 'l_ktst', 'iknr', "iknr = '{$iknr}'")) > 0) {
            $abrechnungsbereich = reset($fields['kv_abrechnungsbereich']['value']);

            $possibleValues = array();

            foreach (sql_query_array($db, "SELECT * FROM l_ktst_abr WHERE iknr = '{$iknr}'") as $entry) {
                $possibleValues[$entry['abrechnungsbereich']] = $entry['name'];
            }

            if (array_key_exists($abrechnungsbereich, $possibleValues) === false) {
                $codes  = "'" . implode("','", array_keys($possibleValues)) . "'";

                $bereiche = array();

                foreach (sql_query_array($db, "SELECT bez FROM l_basic WHERE klasse = 'kv_abrechnungsbereich' AND code IN ({$codes}) ORDER BY bez ASC") as $entry) {
                    $bereiche[] = '<li>' . $entry['bez'] . '</li>';
                }

                $valid->set_err(12, 'kv_abrechnungsbereich', null, sprintf($config['err_abrechnungsbereich'], implode('', $bereiche)));
            }
        }
    }
}

?>
