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

$patientId     = isset($_REQUEST['patient_id']) === true ? $_REQUEST['patient_id']  : null;
$location      = isset($_REQUEST['location'])   === true ? $_REQUEST['location']    : null;
$selected      = isset($_REQUEST['selected']) === true ? $_REQUEST['selected']    : null;

$recht_erkrankung = implode("','", $_SESSION['sess_recht_erkrankung']);

//query Status
$query = "
   SELECT
      s.*,
      CONCAT_WS(', ',
         l.bez,
         e.beschreibung
      ) AS 'form_data'
   FROM status s
      LEFT JOIN erkrankung e ON s.form_id = e.erkrankung_id
         LEFT JOIN l_basic l ON l.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                l.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
   WHERE
      s.patient_id = '$patientId' AND
      s.form = 'erkrankung' AND
      e.erkrankung IN ('$recht_erkrankung')
   ORDER BY erkrankung
";

$result = sql_query_array($db, $query);


$smarty
   ->assign('erkrankungen', $result)
   ->assign('patientId', $patientId)
   ->assign('caption',  $config['caption_status'])
   ->assign('selected', $selected)
   ->assign('location', $location);

$erkrankungId = null;

if ($location == 'erkrankung') {
   $erkrankungId = dlookup($db, 'status', 'form_id', "status_id = '$selected'");
}

$comeFrom = $location == 'patient' ? "view.patient&amp;patient_id={$patientId}" :
            ($location == 'erkrankung' ? "view.erkrankung&amp;erkrankung_id={$erkrankungId}" : 'list.patient');

$smarty->assign('back_btn', "page={$comeFrom}");

//Aktuelle seite in die session legen, wird benötigt um ohne große parameterübergabe wieder zu der aktuellen seite zu finden
$_SESSION['origin'] = array(
   'patient_id'      => $patientId,
   'erkrankung_id'   => $erkrankungId,
   'selected'        => $selected,
   'location'        => $location,
   'page'            => 'status'
);

if ($selected !== null) {

   $validErkrankungen = array();

   foreach ($result as $erkrankungen) {
      $validErkrankungen[] = $erkrankungen['status_id'];
   }

   //Wenn auf die gewählte erkrankung kein Recht besteht, kicken
   if (in_array($selected, $validErkrankungen) == false) {
      $location = get_url("page=view.patient&patient_id = {$patientId}");

      $_SESSION['sess_warn'][] = $config['lbl_no_rights'];
      action_cancel($location);
   }

   require_once('feature/status/class/view.php');

   $statusView = statusView::create($smarty, $db, $fields, null, $action)
      ->setLocation($location)
      ->ignoreForms($dlist_tables)
      ->loadStatusView($selected)
   ;

   $errors  = $statusView->getErrors();

   if (count($errors) == 0) {
      $hubs = $statusView->getHubList();

      $no_error = count($hubs) > 0
        ? sprintf($config['lbl_no_valid_errors'], implode('</li><li>', $hubs))
        : $config['lbl_no_valid_errors_no_int']
      ;

      $smarty->assign('no_errors', $no_error);
   }

   $query = "
      SELECT
         s.*,
         l.bez          AS erkrankung_bez,
         e.erkrankung   AS erkrankung_code
      FROM status s
         LEFT JOIN erkrankung e ON e.erkrankung_id = s.form_id
            LEFT JOIN l_basic l ON l.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                   l.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)
      WHERE s.status_id = '{$selected}'
   ";

   $result = reset(sql_query_array($db, $query));

   $erkrankungData = array(
      'code'   => $result['erkrankung_code'],
      'bez'    => $result['erkrankung_bez']
   );

   $smarty
      ->assign('interfaceErrors', $errors)
      ->assign('erkrankungData', $erkrankungData)
      ->assign('erkrankungId', $result['form_id'])
   ;
}

?>