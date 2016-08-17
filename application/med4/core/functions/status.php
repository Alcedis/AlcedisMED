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

function sortLock($result)
{
   $lockUnlock = array(0 => 'unlocked', 1 => 'locked');
   $forms      = array(
      'unlocked'  => array(),
      'locked'    => array(),
   );

   foreach ($result as $dataset) {
      $statusLock = $dataset['status_lock'];

      $forms[$lockUnlock[$statusLock]][] = $dataset;
   }

   return $forms;
}

function checkKonferenzLock($db, $config, $forms) {

    foreach ($forms['unlocked'] as &$lForms) {

      $form    = $lForms['form'];
      $formId  = $lForms['form_id'];

      switch ($lForms['form']) {
         case 'dmp_brustkrebs_eb':
         case 'dmp_brustkrebs_fb':

            if ($lForms['form_status'] != '4') {
               $lForms['form_data'] = $config['msg_unlockable_dmp'];
               $lForms['lockable'] = 0;
            }

            break;
      }
   }

   foreach ($forms['locked'] as &$lForms) {

      $form    = $lForms['form'];
      $formId  = $lForms['form_id'];

      switch ($lForms['form']) {
         case 'konferenz_patient':

            switch ($lForms['form_param']) {
               case '1':

                  $lForms['unlockable']  = reset(end(sql_query_array($db, "
                     SELECT
                        IF(k.final = 1, 0, 1) AS unlockable
                     FROM $form f
                        LEFT JOIN konferenz k ON f.konferenz_id = k.konferenz_id
                     WHERE f.{$form}_id = '$formId'
                  ")));

                  if ($lForms['unlockable'] == 0) {
                     $lForms['form_data'] = $config['msg_unlockable_konferenz'];
                  }

                  break;
               //Archiv Migration
               case '2':
                  $lForms['unlockable'] = 0;
                  $lForms['form_data']  = $config['msg_unlockable_archiv'];

                  break;

               default:
                  $lForms['unlockable'] = 1;
               break;
            }

            break;


         case 'brief':

            switch ($lForms['form_param']) {
               //Archiv Migration
               case '2':
                  $lForms['unlockable'] = 0;
                  $lForms['form_data']  = $config['msg_unlockable_archiv'];

                  break;

               default:
                  $lForms['unlockable'] = 1;

            }

            break;

         case 'therapieplan':

            switch ($lForms['form_param']) {

               case '2':
                  $lForms['unlockable'] = 0;
                  $lForms['form_data']  = $config['msg_unlockable_archiv'];

                  break;

               default:

                  $lForms['unlockable']  = reset(end(sql_query_array($db, "
                     SELECT
                        IF(k.final = 1, 0, 1) AS unlockable
                     FROM $form f
                        LEFT JOIN konferenz_patient kp ON f.konferenz_patient_id = kp.konferenz_patient_id
                           LEFT JOIN konferenz k ON kp.konferenz_id = k.konferenz_id
                     WHERE f.{$form}_id = '$formId'
                  ")));


                  if ($lForms['unlockable'] == 0) {
                     $lForms['form_data'] = $config['msg_unlockable_konferenz'];
                  }

                  break;
            }

            break;

         default:
            $lForms['unlockable'] = 1;
            break;
      }
   }

   return $forms;
}


?>
