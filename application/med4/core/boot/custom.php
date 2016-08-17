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

/**
 * checkForBrowser
 *
 * @return  bool
 */
function checkForBrowser() {
    $valid = true;

    $userAgent = strtolower(getenv("HTTP_USER_AGENT"));

    if (str_contains($userAgent, 'trident/') === true) {
        $pos = strpos($userAgent, 'trident/');
        $rest = substr($userAgent, $pos + 8);

        $pointPos = strpos($rest, '.');

        $version = (int) substr($rest, 0, $pointPos);

        // IE 8 and lower
        if ($version < 5) {
            $valid = false;
        }
    } elseif (str_contains($userAgent, array('msie 7', 'msie 6')) === true) {
        $valid = false;
    }

    return $valid;
}

$dontshowlogin = false;

if (appSettings::get('check_ie') === true && checkForBrowser() === false) {
    $dontshowlogin = true;
}

$erkrankung_tables   = relationManager::get('erkrankung');
$dlist_tables        = relationManager::get('dlist');
$patient_tables      = relationManager::get('patient');
$picker_tables       = relationManager::get('picker');
$ext_tables          = relationManager::get('ext');

//Rollen die keine Informationen zur Erkrankung oder zum Patienten angezeigt bekommen dürfen
$forbidden = array(
   'dokumentar' => array('list.konferenz_patient', 'konferenz', 'rec.konferenz_archiv')
);

//Pages where the rec. lays in disease but the list is external..^^.. i know...
$extPages = array(
   'list.termin', 'auswertungen'
);

//Patient
if (in_array($page, array_merge($erkrankung_tables, $patient_tables, $picker_tables, $dlist_tables, array('report'))) === true
   && checkSessUserRole($forbidden, $pageName) === true
   && in_array($pageName, $extPages) === false) {

   if (
      (strlen($patient_id) > 0 && (isset($_SESSION['sess_patient_data']['patient_id']) === true && $_SESSION['sess_patient_data']['patient_id'] != $patient_id ))
      ||
      (strlen($patient_id) > 0 && isset($_SESSION['sess_patient_data']) === false)
   ){
      $patientData = escape(end(sql_query_array($db, "SELECT * FROM patient WHERE patient_id = '$patient_id'")));
      $patientData['org'] = escape(end(sql_query_array($db, "SELECT * FROM org WHERE org_id = '{$patientData['org_id']}'")));

      $_SESSION['sess_patient_data'] = $patientData;
   }
} else {
   if (isset($_SESSION['sess_patient_data']) === true && in_array($page, array( 'delete', 'confirm', 'convert', 'convert_exec', 'customcss', 'dmp_popups', 'dmp_2013_popups' )) === false) {
      unset($_SESSION['sess_patient_data']);
   }
}

//Erkrankung
$erkrankung = null;

if (in_array($page, array_merge($erkrankung_tables, array('erkrankung'), $picker_tables, $dlist_tables, array('report'))) === true
   && checkSessUserRole($forbidden, $pageName) === true
   && in_array($pageName, $extPages) === false) {

  if (
      (strlen($erkrankung_id) > 0 && (isset($_SESSION['sess_erkrankung_data']) === true && $_SESSION['sess_erkrankung_data']['erkrankung_id'] != $erkrankung_id))
      ||
      (strlen($erkrankung_id) > 0 && isset($_SESSION['sess_erkrankung_data']) === false)
  ){
      $query = "SELECT
         e.erkrankung_id   AS erkrankung_id,
         e.erkrankung      AS code,
         b.bez             AS bez
      FROM erkrankung e
         LEFT JOIN l_basic b ON b.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                b.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
      WHERE
         erkrankung_id = '{$erkrankung_id}'";

      $result = sql_query_array($db, $query);

      if (count($result) > 0) {
         $_SESSION['sess_erkrankung_data'] = reset($result);
      }
   }

   $erkrankung = isset($_SESSION['sess_erkrankung_data']['code']) === true ? $_SESSION['sess_erkrankung_data']['code'] : null;
} else {
   if (isset($_SESSION['sess_erkrankung_data']) === true && in_array($page, array('delete', 'confirm', 'convert', 'convert_exec', 'customcss', 'dmp_popups', 'dmp_2013_popups' )) === false) {
      unset($_SESSION['sess_erkrankung_data']);
   }
}

//pre define widget selector
$widgetSelector      = $erkrankung;

//Assign Erkrankung Data
if (isset($_SESSION['sess_erkrankung_data']) === true) {
   $sessErkrankungData = array(
      'code'   => $_SESSION['sess_erkrankung_data']['code'],
      'bez'    => $_SESSION['sess_erkrankung_data']['bez']
   );

   $smarty->assign('erkrankungData', $sessErkrankungData);
}

//Sonderfall: Formular innerhalb einer Erkrankung
if ($erkrankung !== null && in_array($page, $erkrankung_tables) === true) {
   $smarty->assign('back_btn', 'page=view.erkrankung');
}

//PatientOverview
if ($patient_id !== NULL && (in_array($page, $erkrankung_tables) === true || in_array($page, $patient_tables) === true) && $pageName !== 'list.patient') {
   $smarty->assign('patientoverview', $patient_id);
}


//Origin check
if (isset($_REQUEST['origin'])) {
   $backPage = '';

   foreach ($_SESSION['origin'] as $param => $value) {
      $backPage .= (strlen($backPage)) ? '&' . $param . '=' . $value : $param . '=' . $value;
   }

   $smarty->assign('back_btn', escape($backPage));
}

?>
