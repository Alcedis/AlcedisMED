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

$lastArchivDatesetKonferenzPatientId = null;

//Ajax Aufruf des Moderators
if ($from == 'konferenz') {
   if ($action == 'update') {

      function updateTherapieplanFromKonferenz($smarty, $db, $fields)
      {
          if (array_key_exists('konferenz_patient_id', $fields) === true) {
            $konferenzPatientId = reset($fields['konferenz_patient_id']['value']);

            //Set Dirty State to Protocol
            protocol::create($db, $smarty, false)
               ->makeDirty(protocol::$kp, $konferenzPatientId)
            ;
          }
      }

      ajax_action($smarty, $db, $fields, $table, $form_id, $action, 'ext_err', '', $arr_sess['sess_typ'], $arr_sess['sess_id'], false, 'updateTherapieplanFromKonferenz');
   }

} else {
   switch( $action ) {

      case 'update':
      case 'insert':

         //Zwingend vorher ausführen, sonst ist der neue Therapieplan schon im Archiv
         if (strlen($form_id) > 0) {
            $lastArchivDatesetKonferenzPatientId = dlookup($db, '_therapieplan', 'konferenz_patient_id', "therapieplan_id = '$form_id' ORDER BY a_therapieplan_id DESC");
         }

         $no_error = $action == 'insert'
            ? $no_error = action_insert( $smarty, $db, $fields, $table, $action, '', 'ext_err', 'ext_warn')
            : $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err', 'ext_warn');

         if ($no_error) {

            //Therapieplan zur Konferenz aktualisieren
            if (array_key_exists('konferenz_patient_id', $fields) === true && strlen(reset($fields['konferenz_patient_id']['value'])) > 0 ) {

               $kpid = reset($fields['konferenz_patient_id']['value']);

               //Set Dirty State to Protocol
               protocol::create($db, $smarty, false)
                  ->makeDirty(protocol::$kp, $kpid)
               ;

               $location .= "&convertdoc=kp{$kpid}";
            } elseif ($action == 'update') {
               //In Archivtabelle prüfen ob im vorherigen Datensatz noch konferenz_patient gefüllt war

               if (strlen($lastArchivDatesetKonferenzPatientId) > 0) {
                  //Delete XHTML file of ergebnis
                  $xhtmlManager = xhtmlManager::create($smarty, 'konferenz_patient', $lastArchivDatesetKonferenzPatientId, 'ergebnis')
                     ->removeXMLDir()
                  ;

                  //Set Dirty State to Protocol
                  protocol::create($db, $smarty, false)
                     ->makeDirty(protocol::$kp, $lastArchivDatesetKonferenzPatientId)
                  ;

                  $location .= "&convertdoc=kp{$lastArchivDatesetKonferenzPatientId}";
               }
            }

            //Prüfen ob ein Konferenz Protokoll innerhalb dieser Erkrankung irgendein Therapieplan hat
            $query = "
               SELECT
                  status_id
               FROM status
               WHERE
                  form = 'konferenz_patient' AND
                  erkrankung_id = '$erkrankung_id'
            ";

            $result = sql_query_array($db, $query);

            if (count($result) > 0) {

               $statusRefresh = statusRefresh::create($db, $smarty);

               foreach ($result AS $statusId) {
                  $statusRefresh->setStatusId(reset($statusId));
               }

               $statusRefresh->refreshStatus();
            }

            action_cancel($location);
         }

         break;

      case 'delete':

         deleteReference($db, 'therapie_systemisch', $page, $form_id);
         deleteReference($db, 'strahlentherapie', $page, $form_id);
         deleteReference($db, 'eingriff', $page, $form_id);
         deleteReference($db, 'sonstige_therapie', $page, $form_id);
         deleteReference($db, 'therapieplan_abweichung', $page, $form_id);

         $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action);

         if ($no_error) {

            if (array_key_exists('konferenz_patient_id', $fields) === true && strlen(reset($fields['konferenz_patient_id']['value'])) > 0) {
               $kpid = reset($fields['konferenz_patient_id']['value']);

               //Delete XHTML file of ergebnis
               $xhtmlManager = xhtmlManager::create($smarty, 'konferenz_patient', $kpid, 'ergebnis')
                  ->removeXMLDir();

               mysql_query("UPDATE konferenz_patient SET document_process = NULL, document_final = NULL WHERE konferenz_patient_id = '$kpid'");

               $location .= "&convertdoc=kp{$kpid}";
            }

            //Prüfen ob ein Konferenz Protokoll innerhalb dieser Erkrankung irgendein Therapieplan hat
            $query = "
               SELECT
                  status_id
               FROM status
               WHERE
                  form = 'konferenz_patient' AND
                  erkrankung_id = '$erkrankung_id'
            ";

            $result = sql_query_array($db, $query);

            if (count($result) > 0) {

               $statusRefresh = statusRefresh::create($db, $smarty);

               foreach ($result AS $statusId) {
                  $statusRefresh->setStatusId(reset($statusId));
               }

               $statusRefresh->refreshStatus();
            }

            action_cancel($location);
         }

         break;

      case 'cancel': action_cancel( $location );                                                   break;
   }
}

?>