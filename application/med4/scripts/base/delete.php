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

function getDeleteForms($db, $config, $formName, $parentName, $parentId, $dateName = null)
{
   $datasets = array();

   $query = "SELECT * ";

   $query .= $dateName !== null ? ", IFNULL(DATE_FORMAT($dateName, '%d.%m.%Y'), '-') AS date" : ", '-' AS date";

   $query .= " FROM $formName
         WHERE $parentName"."_id = $parentId
      ";

      $result = sql_query_array($db, $query);

      foreach ($result as $dataset) {
         $datasets[] = array('date' => $dataset['date'], 'lbl' => isset($config[$formName])? $config[$formName] : '');
      }

   return $datasets;
}

$pageName      = isset($_REQUEST['page_name']) ? $_REQUEST['page_name'] : null;
$form_id       = $_REQUEST['form_id'];

$isDForm       = isset($_REQUEST['d_form']) == true ? ($_REQUEST['d_form'] == 'true' ? true : false) : false;

$msg           = '';
$submitButton  = array();
$extDelete     = array();
$extReference  = array();

$smarty->config_load('app/erkrankung.conf', 'view');
$config  = $smarty->get_config_vars();

if ($isDForm == false) {
   switch ($pageName)
   {
      case 'eingriff':

         $extReference = getDeleteForms($db, $config, 'histologie', $pageName, $form_id, 'datum');
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'zytologie', $pageName, $form_id, 'datum'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'komplikation', $pageName, $form_id, 'datum'));

         break;

      case 'konferenz':

          $extDelete = array(
             array('lbl' => $config['konferenz_dokument']),
             array('lbl' => $config['konferenz_teilnehmer'])
          );

          break;

      case 'konferenz_patient':
         //Im Falle das man den konferenz_patient Datensatz aus einer TUK entfernt
         if ($form_id == 'undefined') {
            $msg = $config['lbl_delete_patient_konferenz'];
         } else {
            $extReference = getDeleteForms($db, $config, 'therapieplan', $pageName, $form_id, 'datum');
         }

         break;

      case 'sonstige_therapie':

         $extReference = getDeleteForms($db, $config, 'nebenwirkung', $pageName, $form_id, 'beginn');

         break;

      case 'strahlentherapie':

         $extReference = getDeleteForms($db, $config, 'nebenwirkung', $pageName, $form_id, 'beginn');

         break;

      case 'dmp_brustkrebs_eb':

         $extReference = getDeleteForms($db, $config, 'dmp_brustkrebs_fb', $pageName, $form_id, 'doku_datum');

         break;


      case 'untersuchung':

         $extReference = getDeleteForms($db, $config, 'diagnose', $pageName, $form_id, 'datum');
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'komplikation', $pageName, $form_id, 'datum'));

         break;

      case 'erkrankung':
         $msg = $config['lbl_delete_erkrankung'];

         break;

      case 'therapieplan':

         $extReference = getDeleteForms($db, $config, 'therapie_systemisch', $pageName, $form_id, 'beginn');
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'strahlentherapie', $pageName, $form_id, 'beginn'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'eingriff', $pageName, $form_id, 'datum'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'sonstige_therapie', $pageName, $form_id, 'beginn'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'therapieplan_abweichung', $pageName, $form_id, 'datum'));

         break;

      case 'therapie_systemisch':

         $extDelete     = getDeleteForms($db, $config, 'therapie_systemisch_zyklus', $pageName, $form_id, 'beginn');
         $extDelete     = array_merge($extDelete, getDeleteForms($db, $config, 'therapie_systemisch_zyklustag', $pageName, $form_id, 'datum'));

         $extReference  = getDeleteForms($db, $config, 'nebenwirkung', $pageName, $form_id, 'beginn');

         break;

      case 'qs_18_1_b':

         $extDelete     = getDeleteForms($db, $config, 'qs_18_1_brust', $pageName, $form_id);
         $extDelete     = array_merge($extDelete, getDeleteForms($db, $config, 'qs_18_1_o', $pageName, $form_id, 'opdatum'));

         break;

      case 'qs_18_1_brust':

         $extDelete     = getDeleteForms($db, $config, 'qs_18_1_o', $pageName, $form_id, 'opdatum');

         break;

      case 'therapie_systemisch_zyklus':

         $extDelete     = getDeleteForms($db, $config, 'therapie_systemisch_zyklustag', $pageName, $form_id, 'datum');

         break;

      case 'nachsorge_erkrankung':

         $msg = "test";

         break;

      case 'nachsorge':

         $count = count(getDeleteForms($db, $config, 'nachsorge_erkrankung', $pageName, $form_id));

         if ($count > 1) {
            $msg = $config['lbl_delete_nachsorge'];
         }

         break;

      case 'studie':

         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'sonstige_therapie', $pageName, $form_id, 'beginn'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'strahlentherapie', $pageName, $form_id, 'beginn'));
         $extReference = array_merge($extReference, getDeleteForms($db, $config, 'therapie_systemisch', $pageName, $form_id, 'beginn'));

         break;

      case 'recht':

         $msg = $config['lbl_delete_right'];

         break;

      case 'patient':

         $msg = $config['lbl_delete_patient'];

         break;

        case 'history' :
            $msg = $config['lbl_delete_history'];
            break;
   }
}

$smarty
   ->assign('msg', $msg)
   ->assign('submitButton', $submitButton)
   ->assign('extDelete', $extDelete)
   ->assign('extReference', $extReference);

?>
