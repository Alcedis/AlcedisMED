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

switch( $action )
{
   case 'create':
   case 'preload':

       if (isset($_REQUEST['aufenthalt_id']) == false || strlen($_REQUEST['aufenthalt_id']) == 0) {
           $smarty
               ->assign('error_aufenthalt_id', $config['err_req'])
               ->assign('error', $config['err_req'])
           ;

           $error = true;
       } else {
           $aufenthaltId = $_REQUEST['aufenthalt_id'];

           $alreadyExists = dlookup($db, 'qs_18_1_b', 'qs_18_1_b_id', "erkrankung_id='{$erkrankung_id}' AND aufenthalt_id = '{$aufenthaltId}'");

           if (strlen($alreadyExists) > 0) {
               $error = true;
               $smarty
                   ->assign('error_aufenthalt_id', $config['err_already_exists'])
                   ->assign('error', $config['err_already_exists']);
               ;
           } else {
               if ($action === 'preload') {
                   require_once 'core/class/qs181/preAllocate.php';

                   $preAllocate = qs181PreAllocate::create()
                       ->setParam('abodeId', $aufenthaltId)
                       ->setParam('diseaseId', $erkrankung_id)
                       ->init($smarty, $db)
                   ;

                   if ($preAllocate->checkPossibleMapping() === true) {
                       $bqs181bid = $preAllocate
                           ->allocate()
                           ->getParam('qs181bid')
                       ;
                       action_cancel(get_url("page=view.qs_18_1&qs_18_1_b_id={$bqs181bid}&showinfo=true"));
                   } else {
                       $smarty->assign('plNotPossible', true);
                   }
               }

               $aufenthalt = reset(sql_query_array($db, "SELECT aufnahmedatum, entlassungsdatum FROM aufenthalt WHERE aufenthalt_id = '{$aufenthaltId}'"));

               $_REQUEST['aufndatum'] = todate($aufenthalt['aufnahmedatum'], 'de');
               $_REQUEST['entldatum'] = todate($aufenthalt['entlassungsdatum'], 'de');
           }
       }

       break;

   case 'insert':
      if (appSettings::get('interfaces', null, 'qs181') === true) {
         $noError = action_insert($smarty, $db, $fields, $table, $action, '', 'ext_err');

         //Weiterleitung auf die QS Übersicht
         if ($noError) {
            $patientId    = reset($fields['patient_id']['value']);
            $erkrankungId = reset($fields['erkrankung_id']['value']);

            $qs_18_1_b_id = dlookup($db, 'qs_18_1_b', 'MAX(qs_18_1_b_id)', "patient_id = '{$patientId}' AND erkrankung_id = '{$erkrankungId}' AND createuser = '{$user_id}'");

            action_cancel(get_url("page=view.qs_18_1&qs_18_1_b_id={$qs_18_1_b_id}"));
         }
      } else {
         action_cancel($location);
      }

      break;

   case 'update':

      action_update( $smarty, $db, $fields, $table, $form_id, $action, $location, 'ext_err');

      break;

   case 'delete':

      //qs_18_1_brust
      deleteForm($db, $smarty, 'qs_18_1_brust', 'qs_18_1_b', $form_id);

      //qs_18_1_o
      deleteForm($db, $smarty, 'qs_18_1_o', 'qs_18_1_b', $form_id);

      $location = get_url("page=view.erkrankung");

      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location);

      break;

   case 'cancel':

      action_cancel( $location );

      break;
}

?>
