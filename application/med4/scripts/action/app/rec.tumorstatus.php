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
   case 'insert':
      $no_error = action_insert( $smarty, $db, $fields, $table, $action, '',  'ext_err', 'ext_warn');

      if ($no_error) {
         $id = dlookup($db, $table, 'MAX(tumorstatus_id)', "createuser = '$user_id'");

         $dlistFields = $widget->loadExtFields('fields/app/tumorstatus_metastasen.php');
         insert_sess_db($smarty, $db, $dlistFields, 'tumorstatus_metastasen', $id, 'metastasen', 'tumorstatus_metastasen_id', 'tumorstatus_id');

         //Regel 2
         if (reset($fields['sicherungsgrad']['value']) == 'end') {

            $erkrankungId   = reset($fields['erkrankung_id']['value']);
            $anlass         = reset($fields['anlass']['value']);

            //Speziell auch hier wieder für Brust angepasst
            $diagnoseSeite = isset($fields['diagnose_seite']['value']) == true ? reset($fields['diagnose_seite']['value']) : null;

            $side = $diagnoseSeite !== null && in_array(dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'"), array('b', 'lu'))
                ? "AND diagnose_seite = '{$diagnoseSeite}'"
                : null
            ;

            $query = "SELECT * FROM tumorstatus WHERE anlass = '{$anlass}' AND
                                                      erkrankung_id = '{$erkrankungId}' AND
                                                      sicherungsgrad = 'end' AND
                                                      tumorstatus_id != '{$id}' {$side}
            ";

            if (count($result = sql_query_array($db, $query)) > 0) {
               $result = reset($result);

               $tumorstatusId = $result['tumorstatus_id'];

               $result['sicherungsgrad'] = 'vor';

               $fields = $widget->loadExtFields('fields/app/tumorstatus.php');

               array2fields($result, $fields);

               $updateQuery = "UPDATE tumorstatus SET " . fields2updatelist( $fields ) . " WHERE tumorstatus_id = $tumorstatusId";

               action_query( $smarty, $db, $fields, 'tumorstatus', $updateQuery, 'update', false);

               $_SESSION['sess_info'][] = $config['msg_sicherungsgrad_change'];
            }
         }

         action_cancel( $location );
      }

      break;

   case 'update':

      $no_error = action_update( $smarty, $db, $fields, $table, $form_id, $action, '',  'ext_err', 'ext_warn');

      if ($no_error) {
         $dlistFields = $widget->loadExtFields('fields/app/tumorstatus_metastasen.php');
         insert_sess_db($smarty, $db, $dlistFields, 'tumorstatus_metastasen', $form_id, 'metastasen', 'tumorstatus_metastasen_id', 'tumorstatus_id');

         //Regel 2
         if (reset($fields['sicherungsgrad']['value']) == 'end') {

            $anlass         = reset($fields['anlass']['value']);
            $erkrankungId   = reset($fields['erkrankung_id']['value']);

            //Speziell auch hier wieder für Brust angepasst
            $diagnoseSeite = isset($fields['diagnose_seite']['value']) == true ? reset($fields['diagnose_seite']['value']) : null;

            $side = $diagnoseSeite !== null && in_array(dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'"), array('b', 'lu'))
                ? "AND diagnose_seite = '{$diagnoseSeite}'"
                : null
            ;

            $query = "SELECT * FROM tumorstatus WHERE anlass = '{$anlass}' AND
                                                      erkrankung_id = '{$erkrankungId}' AND
                                                      sicherungsgrad = 'end' AND
                                                      tumorstatus_id != '{$form_id}' {$side}
            ";


            $result = sql_query_array($db, $query);

            if (count($result = sql_query_array($db, $query)) > 0) {
               $result = reset($result);

               $tumorstatusId = $result['tumorstatus_id'];

               $result['sicherungsgrad'] = 'vor';

               $fields = $widget->loadExtFields('fields/app/tumorstatus.php');

               array2fields($result, $fields);

               $updateQuery = "UPDATE tumorstatus SET " . fields2updatelist( $fields ) . " WHERE tumorstatus_id = $tumorstatusId";

               action_query( $smarty, $db, $fields, 'tumorstatus', $updateQuery, 'update', false);
               $_SESSION['sess_info'][] = $config['msg_sicherungsgrad_change'];
            }
         }

         action_cancel( $location );
      }

      break;

   case 'delete':

      $no_error = action_delete( $smarty, $db, $fields, $table, $form_id, $action );

      if ($no_error) {
         deleteForm($db, $smarty, 'tumorstatus_metastasen', $table, $form_id);

         action_cancel( $location );
      }

      break;

   case 'preload_data':
      $preloadErkrankungId   = isset($_REQUEST['erkrankung_id']) === true ? $_REQUEST['erkrankung_id'] : null;

      if ($preloadErkrankungId !== null) {
         $preloadSeite = isset($_REQUEST['preload_seite']) === true && strlen($_REQUEST['preload_seite']) > 0 ? $_REQUEST['preload_seite'] : null;

         //Bei Brust MUSS eine Seite ausgewählt werden
         if (in_array($erkrankung, array('b', 'lu')) === true && $preloadSeite === null) {
             $smarty
               ->assign('warn', $config['msg_preload_seite'])
               ->assign('preload_seite_err', true)
            ;
         } else {
            require_once('core/class/form/preload.php');

            $initialize = formPreload::create($db, $widget)
                ->setRelation('erkrankung_id', $preloadErkrankungId)
                ->setRelationTable('tumorstatus', 1, array('datum_sicherung', 'DESC'), array(
                   'anlass','rezidiv_lokal', 'rezidiv_lk', 'rezidiv_metastasen',
                   'rezidiv_psa', 'sicherungsgrad', 'nur_zweitmeinung',
                   'nur_diagnosesicherung', 'kein_fall', 'diagnose', 'diagnose_seite',
                   'r_lokal', 'diagnose_text', 'diagnose_c19_zuordnung', 'lokalisation',
                   'lokalisation_seite', 'lokalisation_text', 'lokalisation_detail',
                   'hoehe', 'mikrokalk', 'regressionsgrad', 'stadium_mason',
                   'uicc', 'ajcc', 'figo', 'nhl_who_b', 'nhl_who_t',
                   'hl_who', 'risiko_lk', 'eignung_nerverhalt', 'eignung_nerverhalt_seite',
                   'ann_arbor_stadium', 'ann_arbor_aktivitaetsgrad', 'ann_arbor_extralymphatisch',
                   'nhl_ipi', 'flipi', 'durie_salmon', 'iss', 'immun_phaenotyp', 'cll_rai',
                   'cll_binet', 'aml_fab', 'aml_who', 'all_egil', 'mds_fab',
                   'mds_who', 'stadium_sclc', 'risiko', 'risiko_mediastinaltumor',
                   'risiko_extranodalbefall', 'risiko_bks', 'psa',
                   'tnm_praefix', 't', 'n', 'm'
                ), array('diagnose_seite' => ($preloadSeite !== null ? $preloadSeite : array('-', 'B'))))
                ->setRelationTable('histologie', 0, array('datum', 'DESC'), array(
                   'ptnm_praefix'  => 'tnm_praefix',
                   'pt'            => 't',
                   'pn'            => 'n',
                   'pm'            => 'm'
                ), array('diagnose_seite' => ($preloadSeite !== null ? $preloadSeite : array('-', 'B'))))
                ->initialize('DESC')
            ;

            $convertedFields = $initialize->getConvertedFields();

            if ($erkrankung === 'd') {
               unset($convertedFields['groesse_x']);
            }

            $_REQUEST = array_merge(
               $_REQUEST,
               $convertedFields
            );

            $_REQUEST['preloaded'] = 1;
         }
      }

      break;

   case 'cancel': action_cancel($location); break;
}

?>
