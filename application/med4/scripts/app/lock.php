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

require_once 'core/functions/status.php';

$selected   = isset($_REQUEST['selected'])   ? $_REQUEST['selected'] : null;
$location   = isset($_REQUEST['location'])   ? $_REQUEST['location'] : null;;
$form       = isset($_REQUEST['form'])       ? $_REQUEST['form']     : null;;
$return     = isset($_REQUEST['return'])     ? true                  : false;
$patientId  = dlookup($db, 'status', 'patient_id', "status_id = '$selected'");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if ($location !== null && $selected != null) {

   $recht_erkrankung = $_SESSION['sess_recht_erkrankung'];
   $passTrough = true;
   $formRights = true;

   $smarty->config_load('app/status.conf');
   $config = $smarty->get_config_vars();

   if ($permission->action('lock') === false) {
      $formRights = false;
   }

   if ($location == 'view.patient')
   {
      unset($_SESSION['origin']);

      if ($form == 'erkrankung') {
         //Erkrankung
         $erkrankungId = dlookup($db, 'status', 'form_id', "status_id =  '$selected'");
         $erkrankung   = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = $erkrankungId");

         $conferenceRestriction = '';

         if (appSettings::get('konferenz') === null) {
            $conferenceRestriction = "AND form != 'konferenz_patient'";
         }

         $query = "
            SELECT
               *,
               '-' AS 'form_date'
            FROM status
            WHERE
               status_id = $selected
         UNION
            SELECT
               *,
               DATE_FORMAT(form_date, '%d.%m.%Y') AS 'form_date'
            FROM status
            WHERE
               erkrankung_id = $erkrankungId $conferenceRestriction
         UNION
            SELECT
               status.*,
               DATE_FORMAT(status.form_date, '%d.%m.%Y') AS 'form_date'
            FROM nachsorge nachsorge
               INNER JOIN nachsorge_erkrankung er_na  ON er_na.nachsorge_id = nachsorge.nachsorge_id AND er_na.erkrankung_weitere_id = '$erkrankungId'
               INNER JOIN status status            ON status.patient_id = nachsorge.patient_id AND status.form = 'nachsorge' AND status.form_id = nachsorge.nachsorge_id
            WHERE nachsorge.patient_id='$patientId'
         ";

         $_SESSION['origin'] = array(
            'patient_id'      => $patientId,
            'erkrankung_id'   => $erkrankungId,
            'selected'        => $selected,
            'form'            => $form,
            'location'        => $location,
            'page'            => 'lock'
         );

         $smarty->assign('list', true);

         //Wenn auf ausgewählte Erkrankung kein Recht besteht
         if (in_array($erkrankung, $recht_erkrankung) == false || $formRights == false) {
            $passTrough = "page=$location&patient_id=$patientId";
         }
      } else {
         //Behandler, Abschluss, Aufenthalt
         $query = "
            SELECT
               *,
             '-' AS 'form_date',
             '-' AS 'form_data'
            FROM status
            WHERE
               status_id = $selected
         ";

         $_SESSION['origin'] = array(
            'patient_id'      => $patientId,
            'selected'        => $selected,
            'location'        => $location,
            'page'            => 'lock'
         );

         $smarty->assign('single', true);

         if ($formRights == false) {
            $passTrough = "page=$location&patient_id=$patientId";
         }
      }

      $smarty
         ->assign('back_btn', 'page=view.patient&amp;patient_id=' . $patientId);
   } else {

      //Location = view.erkrankung

      $erkrankung   = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankung_id}'");

      //view.erkrankung
      $query = "
         SELECT
            *,
            IF(form_date IS NULL, '-', DATE_FORMAT(form_date, '%d.%m.%Y')) AS form_date
         FROM status
         WHERE status_id = '$selected'
      ";

      //Historie laden
      $history = sql_query_array($db, "
          SELECT
            sl.*,
            CONCAT_WS(', ', u.nachname, u.vorname) AS 'user',
            DATE_FORMAT(sl.time, '%d.%m.%Y') AS 'date',
            CONCAT_WS(' ', DATE_FORMAT(sl.time, '%H:%i'), 'Uhr') AS 'time',
            REPLACE(REPLACE(REPLACE(bem.bem, '\r\n', '<br/>'), '\n', '<br/>'),'\r', '<br/>') AS 'bem'
          FROM status_lock sl
             LEFT JOIN status_lock_bem bem ON bem.status_lock_bem_id = sl.status_lock_bem_id
             LEFT JOIN user u ON u.user_id = sl.user_id
          WHERE
            sl.status_id = '{$selected}'
          ORDER by
            sl.status_lock_id ASC
      ");

      $smarty->assign('history', $history);

      $_SESSION['origin'] = array(
         'patient_id'      => $patientId,
         'erkrankung_id'   => $erkrankung_id,
         'selected'        => $selected,
         'location'        => $location,
         'page'            => 'lock'
      );

      $smarty
         ->assign('single', true)
         ->assign('back_btn', "page={$location}&amp;erkrankung_id={$erkrankung_id}");

      //Wenn auf ausgewählte Erkrankung kein Recht besteht
      if (in_array($erkrankung, $recht_erkrankung) == false || $formRights == false) {
         $passTrough = "page={$location}&amp;erkrankung_id={$erkrankung_id}";
      }
   }

   //Recht checken
   if ($passTrough !== true) {
      $location                  = get_url($passTrough);
      $_SESSION['sess_warn'][]   = $config['lbl_no_lock_rights'];

      action_cancel($location);
   }

   $result = sql_query_array($db, $query);

   $configBuffer = array();

   $configBackup = $smarty->get_config_vars();

   $smarty->clear_config();

    foreach ($result as &$form) {
       $formName = $form['form'];

       if (array_key_exists($formName, $configBuffer) === false) {
            $smarty->clear_config();
            $smarty->config_load("app/{$formName}.conf", 'rec');

            $tmpConfig = $smarty->get_config_vars();

            $configBuffer[$formName] = $tmpConfig['caption'];
       }

       $form['form_config'] = $configBuffer[$formName];
    }

   $smarty->clear_config();

   $smarty->set_config($configBackup);
   unset($configBackup);
   unset($configBuffer);

   $forms = sortLock($result);
   $forms = checkKonferenzLock($db, $config, $forms);

   $smarty
      ->assign('return', $return)
      ->assign('forms', $forms)
      ->assign('exclusiveArray', array('erkrankung', 'aufenthalt', 'abschluss', 'behandler'))
      ->assign('patient_id', $patientId)
      ->assign('caption', $config['caption_lock']);
}

?>