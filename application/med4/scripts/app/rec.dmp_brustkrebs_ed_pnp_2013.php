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

require_once('feature/export/base/helper.database.php');

$table    = 'dmp_brustkrebs_ed_pnp_2013';
$formId   = isset($_REQUEST['dmp_brustkrebs_ed_pnp_2013_id']) ? $_REQUEST['dmp_brustkrebs_ed_pnp_2013_id'] : '';
$location = get_url('page=view.erkrankung');

if (strlen($formId) == 0 && $action === null) {
    $action = 'get_dmp';
}

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

unset($_SESSION['dmp_xml_protokoll']);

show_record($smarty, $db, $fields, $table, $formId, '', 'ext_warn');

$edId = array_key_exists('dmp_brustkrebs_ed_2013_id', $_REQUEST) === true ? $_REQUEST['dmp_brustkrebs_ed_2013_id'] : null;

if ($edId === null && strlen($formId) > 0) {
    $edId = reset($fields['dmp_brustkrebs_ed_2013_id']['value']);
}

$fallnr = dlookup($db, 'dmp_brustkrebs_ed_2013', 'fall_nr', "dmp_brustkrebs_ed_2013_id = '{$edId}'");

$button = get_buttons($table, $formId, $statusLocked, true);

$fakeSystemDate = appSettings::get('fake_system_date');

$compareDate = $fakeSystemDate !== null ? $fakeSystemDate : date('Y-m-d');

$smarty
    ->assign('button',  $button)
    ->assign('fallNr',  $fallnr)
    ->assign('showKvExtension', $compareDate >= '2014-10-01')
;

/**
 * ext_warn
 *
 * @access
 * @param $valid
 * @return void
 */
function ext_warn($valid)
{
    require_once('feature/export/helper.dmp.php');

    $dmpProtocol = '';
    if (isset($_SESSION['dmp_xml_protokoll'])) {
        $dmpProtocol = $_SESSION['dmp_xml_protokoll'];
        unset($_SESSION['dmp_xml_protokoll']);
    } elseif (isset($_REQUEST['dmp_brustkrebs_ed_pnp_2013_id']) &&
        (strlen($_REQUEST['dmp_brustkrebs_ed_pnp_2013_id']) > 0)) {
        $db = $valid->_db;
        $dmpId = $valid->_fields['dmp_brustkrebs_ed_pnp_2013_id']['value'][0];
        $dmpProtocol = dlookup(
            $db,
            'dmp_brustkrebs_ed_pnp_2013', 'xml_protokoll', "dmp_brustkrebs_ed_pnp_2013_id=$dmpId"
        );
    }
    HelperDmp::showDmpErrors($valid->_db, "ed_pnp", 0, $dmpProtocol, $valid);
}
?>
