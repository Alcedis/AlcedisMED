<?php
/**
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

/**
 * include all relevant fields
 */
$fields = '';
include "fields/app/dmp_brustkrebs_ed_2013.php";
$fieldsEd = $fields;

$fields = '';
include "fields/app/dmp_brustkrebs_ed_pnp_2013.php";
$fieldsPnp = $fields;

$fields = '';
include "fields/app/dmp_brustkrebs_fd_2013.php";
$fieldsFd = $fields;



// XML aktualisieren

require_once('feature/export/dmp/helper.dmp.php');
require_once('feature/export/base/helper.database.php');

$forms = array('ed', 'ed_pnp', 'fd');

$fieldSrc = array(
    'ed'     => $fieldsEd,
    'ed_pnp' => $fieldsPnp,
    'fd'     => $fieldsFd
);

$backup = $_REQUEST;

$parameters = array();

$parameters['user_id'] = isset($_SESSION['sess_user_id']) ? $_SESSION['sess_user_id'] : '';
$parameters['login_name'] = isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : '';

$parameters['export_id'] = isset($_REQUEST['export_id']) ? $_REQUEST['export_id'] : 0;

$parameters['von_datum'] = '0000-00-00';
$parameters['bis_datum'] = date("Y-m-d");

$parameters['melde_user_id'] = 1;
$parameters['empfaenger_aok'] = false;
$parameters['sw_version'] = appSettings::get("software_version");

foreach ($forms as $form) {
    $datasets = sql_query_array($db, "
        SELECT
            form.*,
            p.org_id
        FROM dmp_brustkrebs_{$form}_2013 form
            INNER JOIN patient p ON form.patient_id = p.patient_id
        WHERE
            form.dmp_brustkrebs_eb_id IS NOT NULL AND
            (
            form.createtime > form.updatetime OR
            form.updatetime IS NOT NULL
            )
        ");

    foreach ($datasets as $dataset) {
        $fields = $fieldSrc[$form];

        $tmpFields = $fields;

        array2fields($dataset, $tmpFields);

        fields2request($tmpFields);

        HDmp::initXml($fields, $_REQUEST);

        $parameters['org_id'] = $dataset['org_id'];

        // DMP-Formular mit Prüfmodul checken
        $result = HDmp::checkForm($parameters, $dataset["dmp_brustkrebs_{$form}_2013_id"], $form, $smarty, $db);

        // Ergebnis des Checks in den Request legen
        HDmp::setXml($result, $_REQUEST, $_SESSION);

        action_update($smarty, $db, $fields, "dmp_brustkrebs_{$form}_2013", $dataset["dmp_brustkrebs_{$form}_2013_id"], 'update');
    }
}
