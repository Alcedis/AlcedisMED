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

$query = "
   SELECT
      th.erkrankung_id,
      th.patient_id,
      p.org_id,
      e.erkrankung,
      th.konferenz_patient_id,
      CONCAT_WS(', ', p.nachname, p.vorname) AS name,
      DATE_FORMAT(p.geburtsdatum, '%d.%m.%Y') as bday,
      l.bez AS 'erkrankung_bez',
      ll.bez AS 'type'
   FROM therapieplan th
      LEFT JOIN konferenz_patient kp ON kp.konferenz_patient_id = th.konferenz_patient_id
        LEFT JOIN l_basic ll ON ll.klasse = 'tumorkonferenz_art' AND ll.code = kp.art
      LEFT JOIN patient p ON th.patient_id = p.patient_id
      LEFT JOIN erkrankung e ON e.erkrankung_id = th.erkrankung_id
      LEFT JOIN l_basic l ON l.klasse = 'erkrankung' AND l.code = e.erkrankung
   WHERE
      th.therapieplan_id = '{$form_id}'
";

$result = reset(sql_query_array($db, $query));

$patient_id       = $result['patient_id'];
$erkrankung_id    = $result['erkrankung_id'];
$erkrankung       = $result['erkrankung'];
$org_id           = $result['org_id'];
$widgetSelector   = $result['erkrankung'];

require ('core/initial/queries/app.php');
require ('core/initial/queries/base.php');
require ('core/initial/queries/dropdown.php');

$widgetExtLoadParams = array(
   'smarty'          => $smarty,
   'patient_id'      => $patient_id,
   'org_id'          => $org_id,
   'querys'          => $querys,
   'erkrankung_id'   => $erkrankung_id,
   'erkrankung'      => $erkrankung
);

$widget = new widget($db, array(), $page, $widgetSelector, $widgetExtLoadParams);

$fields = $widget->loadExtFields('fields/app/therapieplan.php');

$widget->setFields($fields, $widgetSelector);

featureService::getInstance()
    ->setParam('form', $page)
    ->setParam('disease', $widgetSelector)
    ->setParam('pageType', 'rec')
    ->callService($widget, array('getFields', 'assign'))
;

$fields = $widget->getFields();

//Widget selector to smarty
$smarty->widget = $widget;

if ($from == 'konferenz_patient') {
   $konferenzId = $_REQUEST['konferenz_id'];

   $smarty
      ->assign('back_btn', "page=list.konferenz_patient&konferenz_id=$konferenzId");

   $location = get_url("page=list.konferenz_patient&konferenz_id=$konferenzId");
} else {
    $smarty->assign('patient', $result);
}

?>
