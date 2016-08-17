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

class reportContentD05_1 extends reportExtensionD
{
   protected $_cols = array('C','D','E','F','J','K','O','P','Q','R','S','T','U','W','X');

   /**
    * initialize matrix
    *
    * @param $auditjahr
    */
   protected function _initalizeMatrix($auditjahr)
   {
      $years = range($auditjahr - 8, $auditjahr - 1);

      $data = array(
         'X7'  => $auditjahr,
         'I2'  => utf8_encode($this->_params['org_name']),
         'I3'  => utf8_encode($this->_params['org_ort'])
      );

      $this
        ->addSaveCell('I', 2)
        ->addSaveCell('I', 3)
        ->addSaveCell('X', 7)
      ;

      foreach ($years as $k => $year)
         $data['A' . (13 + $k)] = $year . ($k == count($years)-1 ? '*' : '');

      foreach (range(13, 20) as $row) {
         foreach($this->_cols as $col) {
            if ($col == 'J' && $row == 20) {
               break 2;
            } else {
               $data[$col . $row] = 0;
            }
         }
      }

      $data['W22']   = 0;
      $data['X22']   = 0;

      return $data;
   }


   public function generate()
   {
      $bezugsjahr       = $this->getParam('jahr', date('Y'));
      $auswertungsdatum = date('Y-m-d');

      $auditjahr        = $bezugsjahr + 1;
      $firstJahr        = $auditjahr - 8;

      $oas = array('all' => array('range' => array(), 'event' => array()));
      $dfs = array('all' => array('range' => array(), 'event' => array()));

      foreach (range($firstJahr, $bezugsjahr - 1) as $j) {
        $oas[$j] = array('range' => array(), 'event' => array());
        $dfs[$j] = array('range' => array(), 'event' => array());
      }

      $matrix = $this->_initalizeMatrix($auditjahr);

      //kolonpatienten
      $additionalContent['condition'] = "LEFT(IFNULL(sit.diagnose_c19_zuordnung, sit.diagnose), 3) LIKE 'C18' AND YEAR(bezugsdatum) BETWEEN {$firstJahr} AND {$bezugsjahr}";

      $additionalContent['selects'] = array(
         "(SELECT ts.rezidiv_lokal        FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_lokal IS NOT NULL      ORDER BY NULL LIMIT 1)  AS rez_lokal",
         "(SELECT ts.rezidiv_lk           FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_lk IS NOT NULL         ORDER BY NULL LIMIT 1)  AS rez_loko",
         "(SELECT ts.rezidiv_metastasen   FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_metastasen IS NOT NULL ORDER BY NULL LIMIT 1)  AS rez_metast",
         "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31'                                       ORDER BY ts.datum_sicherung DESC LIMIT 1) AS lastRez",
         "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31'                                       ORDER BY ts.datum_sicherung ASC  LIMIT 1) AS firstRez",
      );

      $additionalContent['joins'] = array(
         $this->_diseaseExtension('joins', $bezugsjahr)
      );

      $additionalContent['fields'] = array(
         "sit.firstRez     AS 'firstRez'",
         "sit.lastRez      AS 'lastRez'",
         "sit.rez_lokal    AS 'rez_lokal'",
         "sit.rez_loko     AS 'rez_loko'",
         "sit.rez_metast   AS 'rez_metast'",

         "IF(sit.lastRez >= '{$bezugsjahr}-01-01', 1, NULL)                                              AS 'lastRezInNachsorgejahr'",
         "MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL))                                       AS 'lastNachsorge'",
         "IF(MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL)) >= '{$bezugsjahr}-01-01', 1, NULL) AS 'lastNachsorgeInNachsorgejahr'",
         "IF(MAX(x.todesdatum) <= '{$bezugsjahr}-12-31', MAX(x.todesdatum) , NULL)                       AS 'todesdatum_less_nachsorgejahr'",
         "CONCAT_WS(
            ',',

            /* spätere Darm Erkrankung speziallfall wegen zusätzlichem eingriff check*/
            IF(

               IFNULL(
                  IFNULL(
                     MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                     MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
                  ),
                  IF (
                     MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                     MIN(darm_erk_ts.datum_sicherung),
                     null
                  )
               ) > IFNULL(MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)), MIN(h.datum)),
               IFNULL(
                  IFNULL(
                     MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                     MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
                  ),
                  IF (
                     MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                     MIN(darm_erk_ts.datum_sicherung),
                     null
                  )
               ),
               null
            ),

            /* spätere sonstige Erkrankung */
            IF(
               MIN(sonstige_erk_status.form_date) > IFNULL(MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)), MIN(h.datum)),
               MIN(sonstige_erk_status.form_date),
               NULL
            ),

            /* malignom */
            IF(
               MAX(IF(n.datum <= '{$bezugsjahr}-12-31' AND n.malignom = '1', n.datum, NULL)) > IFNULL(MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)), MIN(h.datum)),
               MAX(IF(n.datum <= '{$bezugsjahr}-12-31' AND n.malignom = '1', n.datum, NULL)),
               NULL
            )
         ) AS 'zweitmalignom'",
         "
         IF(
            /* vorhergehende Darmerkrankung */
            /* Für Primärfälle bei Darm: Eingriff mit Resektion des Primärtumors , wenn dies nicht dokumentiert, dann Datum der frühesten dokumentierten Histologie */

            IFNULL(
               IFNULL(
                  MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                  MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
               ),
               IF (
                  MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                  MIN(darm_erk_ts.datum_sicherung),
                  '9999-12-12'
               )
            )

            < IFNULL(MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)), MIN(h.datum))

            OR

            /* vorhergehende sonstige Erkrankung */

            MIN(sonstige_erk_status.form_date) < IFNULL(MIN(IF(s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1', s.form_date, NULL)), MIN(h.datum))

            OR

            /* in der einer anamnese ist ein c dokumentiert */

            COUNT(vorerk1.anamnese_erkrankung_id) > 0,
            1,
            0
         ) AS 'vorerkrankung'"
      );

      $datasets = $this->loadRessource('d01', $additionalContent);

      foreach ($datasets as $dataset) {
         if ($dataset['primaerfall'] != 1) {
              continue;
         }

         if (strlen($dataset['zweitmalignom']) > 0) {
            $zweitmalignom = explode(',', $dataset['zweitmalignom']);

            sort($zweitmalignom);

            $dataset['zweitmalignom'] = end($zweitmalignom);
         }

         //UICC
         $uicc = $dataset['uicc_nach_neoadj_th'] == 1 ? $dataset['uicc_prae'] : $dataset['uicc'];

         if (strlen($uicc) == 0) {
            continue;
         }

         $continue      = false;

         $currentJahr   = date('Y', strtotime($dataset['bezugsdatum']));
         $row           = $currentJahr - $bezugsjahr + 20;

         if (substr($uicc, 0, 1) == 'I' && strpos($uicc, 'II') === false && strpos($uicc, 'IV') === false) {
            $matrix["C$row"]++;
            $continue = true;
         }

         if (substr($uicc, 0,2) == 'II' && strpos($uicc, 'III') === false) {
            $matrix["D$row"]++;
            $continue = true;
         }

         if (substr($uicc, 0,3) == 'III') {
            $matrix["E$row"]++;
            $continue = true;
         }

         if (substr($uicc, 0,2) == 'IV') {
            $matrix["F$row"]++;
         }

         if ($row == 20){
            continue;
         }

         if ($continue == true && $dataset['vorerkrankung'] == 0) {
            if ($dataset['lastNachsorgeInNachsorgejahr'] == 1 || $dataset['lastRezInNachsorgejahr'] == 1 || strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
               $matrix["J$row"]++;

               $caseRange = array(
                  'rez'   => strlen($dataset['lastRez']) > 0         ? $dataset['lastRez']         : false,
                  'zweit' => strlen($dataset['zweitmalignom']) > 0   ? $dataset['zweitmalignom']   : false,
                  'tod'   => strlen($dataset['todesdatum_less_nachsorgejahr']) > 0      ? $dataset['todesdatum_less_nachsorgejahr']      : false
               );

               $tmpDate = false;
               $xCase = false;

               foreach ($caseRange as $case => $checkDate) {
                  if ($tmpDate == false || $checkDate > $tmpDate) {
                      $tmpDate = $checkDate;
                      $xCase = $case;
                  }
               }

               switch ($xCase) {
                  case 'rez' :

                     if (strlen($dataset['firstRez']) > 0) {
                         $matrix["P{$row}"] += $dataset['rez_lokal']  == '1' ? 1 : 0;
                         $matrix["Q{$row}"] += $dataset['rez_loko']   == '1' ? 1 : 0;
                         $matrix["R{$row}"] += $dataset['rez_metast'] == '1' ? 1 : 0;

                         if ($dataset['rez_lokal'] == '1' || $dataset['rez_loko'] == '1' || $dataset['rez_metast'] == '1') {
                            $matrix["O{$row}"]++;
                         }
                     }

                     break;

                  case 'zweit' :

                     $matrix["S$row"]++;

                     break;

                  case 'tod' :

                     if (strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
                        $matrix["T$row"] += $dataset['tod_tumorbedingt'];
                        $matrix["U$row"] += 1 - (int) $dataset['tod_tumorbedingt'];
                     }

                  break;
               }
            } else {
               $matrix["K$row"]++;
            }

            $dfsDauer = strtotime($this->getFirstValue($dataset['firstRez'], $dataset['lastNachsorge'], $dataset['todesdatum_less_nachsorgejahr'], $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);

            $first      = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastRez']       : $dataset['lastNachsorge'];
            $second     = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastNachsorge'] : $dataset['lastRez'];

            $oasDauer = strtotime($this->getFirstValue($dataset['todesdatum_less_nachsorgejahr'], $first, $second, $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);
            $dfsEvent = strlen($dataset['firstRez']) ? 1 : 0;
            $oasEvent = strlen($dataset['todesdatum_less_nachsorgejahr']) ? 1 : 0;

            $dfs[$currentJahr]['range'][]    = $dfsDauer;
            $dfs[$currentJahr]['event'][]    = $dfsEvent;
            $dfs['all']['range'][]           = $dfsDauer;
            $dfs['all']['event'][]           = $dfsEvent;

            $oas[$currentJahr]['range'][]    = $oasDauer;
            $oas[$currentJahr]['event'][]    = $oasEvent;
            $oas['all']['range'][]           = $oasDauer;
            $oas['all']['event'][]           = $oasEvent;
         }
      }

      foreach ($dfs as $jahr => $dfsData) {
         $row = $jahr != 'all' ? ($jahr - $bezugsjahr + 20) : '22';

         $this->addSaveCell('W', $row, $dfsData);

         $oasValue = $this->_kaplanMeier($dfsData);
         $matrix["W$row"] = end($oasValue['y']) / 100;
      }

      foreach ($oas as $jahr => $oasData) {
         $row = $jahr != 'all' ? ($jahr - $bezugsjahr + 20) : '22';

         $this->addSaveCell('X', $row, $oasData);

         $oasValue = $this->_kaplanMeier($oasData);
         $matrix["X$row"] = end($oasValue['y'])/100;
      }

      $this->_data = $this->clearCells($matrix);

      $this->parseXLS();
   }
}

?>