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

class reportContentD02 extends reportExtensionD
{

   protected function _initDataArray($showP = true)
   {
      $data = array();
      //normale zahlen (zähler, nenner, prozent)
      $n = array(1,2,3,4,5,6,7,8,9,10,11,12,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
      //anzahl
      $a = array(13,14);

      foreach ($n as $nDigit) {
         $nDigitS = strlen($nDigit) == 1 ? "0$nDigit" : $nDigit;
         $data["kz_{$nDigitS}_z"] = 0;
         $data["kz_{$nDigitS}_n"] = 0;

         if ($showP === true) {
            $data["kz_{$nDigitS}_p"] = 0;
         }
      }

      foreach ($a as $aDigit) {
         $aDigitS = strlen($aDigit) == 1 ? "0$aDigit" : $aDigit;
         $data["kz_{$aDigitS}_a"] = 0;
      }

      ksort($data);

      return $data;
   }


   protected function _initD06DataArray($datasets)
   {
      $data = array();

      $dataArray = $this->_initDataArray(false);

      foreach ($datasets as $i => $dataset) {

         $patientData = array(
            'nachname'      => $dataset['nachname'],
            'vorname'       => $dataset['vorname'],
            'geburtsdatum'  => $dataset['geburtsdatum'],
            'bezugsdatum'   => $dataset['bezugsdatum'],
         );

         $data[$i] = array_merge($patientData, $dataArray);
      }

      return $data;
   }


   public function generate()
   {
      $this->setTemplate('d02');

      $d06 = array();

      $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

      $actYear = date('Y');

      $start   = $bezugsjahr < $actYear ? "{$bezugsjahr}-01-01" : date('Y-m-d', time() - 31536000);
      $end     = $bezugsjahr < $actYear ? "{$bezugsjahr}-12-31" : date('Y-m-d');

      $additionalContent['fields'] = array(
         "CONCAT_WS('', '|', GROUP_CONCAT(DISTINCT k.komplikation SEPARATOR '|'),'|') AS 'komplikation_raw'",
         "COUNT(IF(
             k.komplikation = 'ani' AND (
                 k.antibiotikum = '1' OR k.revisionsoperation = '1' OR k.reintervention = '1'
             ), 1, NULL
         )) > 0 AS 'komplikation_gradb'",
         "GROUP_CONCAT(IF(h.eingriff_id IS NOT NULL AND h.r = 0, h.eingriff_id, NULL) SEPARATOR '|') AS 'r0_eingriff_histos'",

      );

      $data = $this->_initDataArray();

      $additionalContent['condition'] = "((YEAR(bezugsdatum) IS NOT NULL AND YEAR(bezugsdatum) <= '$bezugsjahr') OR
               (YEAR(datum_primaer_op_rezidiv_op) IS NOT NULL AND YEAR(datum_primaer_op_rezidiv_op) <= '$bezugsjahr') OR LOCATE('$bezugsjahr', datum_studie) != 0)";

      $datasets = $this->loadRessource('d01', $additionalContent);

      //Init D06
      if ($this->_params['name'] == 'd06' && count($datasets) > 0) {
         $d06 = $this->_initD06DataArray($datasets);
      }

        foreach ($datasets as $i => $dataset) {
            extract($dataset);

            $checkDiag = strlen($zugeordnet_zu) ? $zugeordnet_zu : $diagnose;
            $substDiag = substr($checkDiag, 0, 3);

            $jahr = date('Y', strtotime((strlen($datum_primaer_op_rezidiv_op) ? $datum_primaer_op_rezidiv_op : $bezugsdatum)));

            $fallKolon  = ($checkDiag == 'D01.0' || $substDiag == 'C18');
            $fallRektum = (in_array($checkDiag, array('D01.1', 'D01.2')) || in_array($substDiag, array('C19', 'C20')));

            $check_kolon  = ($operativer_fall_kolon == 1  && $fallKolon === true);
            $check_rektum = ($operativer_fall_rektum == 1 && $fallRektum === true);


         //studien zählen (unabhängig vom patientenbezugsjahr)
         if (strlen($datum_studie) > 0) {
             foreach (explode(', ', $datum_studie) as $studiendatum) {
                 $studienYear = date('Y', strtotime($studiendatum));
                 if ($studienYear == $bezugsjahr) {
                     $data['kz_06_z']++;
                     $d06[$i]['kz_06_z'] = isset($d06[$i]['kz_06_z']) === true ? $d06[$i]['kz_06_z'] + 1 : 1;
                 }
             }
         }

         if ($jahr == $bezugsjahr) {
            //1
            if ($primaerfall == 1 && ($elek_primaer_oprezidiv_op == 1 || strlen($datum_primaer_op_rezidiv_op) == 0) &&
               (
                  (in_array($substDiag, array('C19','C20')) === true || in_array($checkDiag,array('D01.1', 'D01.2')) === true) ||
                  (($substDiag == 'C18' || $checkDiag == 'D01.0') && substr($uicc_prae, 0,2) == 'IV')
               )
            ) {
               $data['kz_01_n'] ++;
               $d06[$i]['kz_01_n'] = 1;
               $data['kz_01_z'] += $tumorkonf_praeop;
               $d06[$i]['kz_01_z'] = (int) $tumorkonf_praeop;
            }

             //2
             if( $primaerfall == 0) {
                $data['kz_02_n'] ++;
                $d06[$i]['kz_02_n'] = 1;
                $data['kz_02_z'] += $tumorkonf_praeop;
                $d06[$i]['kz_02_z'] = (int) $tumorkonf_praeop;
             }

             //3
             if( $primaerfall == 1 && strlen($datum_primaer_op_rezidiv_op) > 0 ) {
                $data['kz_03_n'] ++;
                $d06[$i]['kz_03_n'] = 1;
                $data['kz_03_z'] += $tumorkonf_postop;
                $d06[$i]['kz_03_z'] = (int) $tumorkonf_postop;
             }

             //4
             if( $primaerfall == 1) {
                $data['kz_04_n'] ++;
                $d06[$i]['kz_04_n'] = 1;
                $data['kz_04_z'] += $psychoonk_betreuung;
                $d06[$i]['kz_04_z'] = (int) $psychoonk_betreuung;
             }

             //5
             if( $primaerfall == 1) {
                $data['kz_05_n'] ++;
                $d06[$i]['kz_05_n'] = 1;
                $data['kz_05_z'] += $beratung_sozialdienst;
                $d06[$i]['kz_05_z'] = (int) $beratung_sozialdienst;
             }

             //6
             if( $primaerfall == 1) {
                $data['kz_06_n'] ++;
                $d06[$i]['kz_06_n'] = 1;
             }

             //7
             if( $primaerfall == 1) {
                $data['kz_07_n'] ++;
                $d06[$i]['kz_07_n'] = 1;
                $data['kz_07_z'] += (int) ($positive_familienanamnese == 1);
                $d06[$i]['kz_07_z'] = (int) ($positive_familienanamnese == 1);
             }

             //8
             if( $primaerfall == 1) {
                $data['kz_08_n'] ++;
                $d06[$i]['kz_08_n'] = 1;
                $data['kz_08_z'] += $genetische_beratung;
                $d06[$i]['kz_08_z'] = (int) $genetische_beratung;
             }

             //9
             if( $primaerfall == 1 && date_diff_years($geburtsdatum,$bezugsdatum) < 50) {
                $data['kz_09_n'] ++;
                $d06[$i]['kz_09_n'] = 1;
                $data['kz_09_z'] += $msi_untersuchung;
                $d06[$i]['kz_09_z'] = (int) $msi_untersuchung;
             }

             //10
             if( $ther_koloskopie) {
                $data['kz_10_n'] ++;
                $d06[$i]['kz_10_n'] = 1;
                $data['kz_10_z'] += (int) (str_contains($komplikation_raw, array('|blut|', 'blutv')) == true || str_contains($komplikation_raw, '|per|') == true);
                $d06[$i]['kz_10_z'] = (int) (str_contains($komplikation_raw, array('|blut|', 'blutv')) == true || str_contains($komplikation_raw, '|per|') == true);
             }


             //11
             if( $elek_ther_koloskopie + $anz_elek_diagn_koloskopien > 0) {
                $data['kz_11_n']    += $elek_ther_koloskopie + $anz_elek_diagn_koloskopien;
                $d06[$i]['kz_11_n'] = (int) ($elek_ther_koloskopie + $anz_elek_diagn_koloskopien);
                $data['kz_11_z']    += $anz_vollst_th_elek_koloskopien + $anz_vollst_diag_elek_koloskopien;
                $d06[$i]['kz_11_z'] = (int) ($anz_vollst_th_elek_koloskopien + $anz_vollst_diag_elek_koloskopien);
             }

             //12
             if ($primaerfall == 1 && $fallRektum === true && $duennschischt_becken == 1) {
                 $data['kz_12_n'] ++;
                 $d06[$i]['kz_12_n'] = 1;

                 $data['kz_12_z'] += $faszie_abstand;
                 $d06[$i]['kz_12_z'] = (int) $faszie_abstand;
             }

             //13
             if( $primaerfall == 1 && $check_kolon === true) {
                 $data['kz_13_a']++;
                 $d06[$i]['kz_13_a'] = 1;
             }

             //14
             if( $primaerfall == 1 && $operativer_fall_rektum == 1 &&
                 (in_array($checkDiag, array('D01.1', 'D01.2')) || in_array($substDiag, array('C19', 'C20')))
             ) {
                 $data['kz_14_a']++;
                 $d06[$i]['kz_14_a'] = 1;
             }

             //15
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && $check_kolon === true) {
                $data['kz_15_n'] ++;
                $d06[$i]['kz_15_n'] = 1;
                $data['kz_15_z'] += $revisionsop_erforderlich;
                $d06[$i]['kz_15_z'] = (int) $revisionsop_erforderlich;
             }

             //16
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && $check_rektum === true) {
                $data['kz_16_n'] ++;
                $d06[$i]['kz_16_n'] = 1;
                $data['kz_16_z'] += $revisionsop_erforderlich;
                $d06[$i]['kz_16_z'] = (int) $revisionsop_erforderlich;
             }

             //17
             if( $primaerfall == 1 && ($operativer_fall_kolon == 1 || $operativer_fall_rektum == 1) && $elek_primaer_oprezidiv_op > 0) {
                $data['kz_17_n'] ++;
                $d06[$i]['kz_17_n'] = 1;
                $data['kz_17_z'] += (int) ($wund_30 == 1);
                $d06[$i]['kz_17_z'] = (int) ($wund_30 == 1);
             }

             //18
             if( $primaerfall == 1 && $check_kolon === true && $anastomose_durchgefuehrt == 1 && $elek_primaer_oprezidiv_op == 1) {
                $data['kz_18_n'] ++;
                $d06[$i]['kz_18_n'] = 1;

                $data['kz_18_z'] += (int) (str_contains($komplikation_raw, '|ani|') === true);
                $d06[$i]['kz_18_z'] = (int) (str_contains($komplikation_raw, '|ani|') === true);
             }

             //19
             if( $primaerfall == 1 &&  $elek_primaer_oprezidiv_op == 1 && $anastomose_durchgefuehrt == 1 && $check_rektum === true) {
                $data['kz_19_n'] ++;
                $d06[$i]['kz_19_n'] = 1;

                $data['kz_19_z'] += (int) ($komplikation_gradb == 1);
                $d06[$i]['kz_19_z'] = (int) ($komplikation_gradb == 1);
             }

             //20
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && ($operativer_fall_kolon == 1 || $operativer_fall_rektum == 1)) {
                $data['kz_20_n'] ++;
                $d06[$i]['kz_20_n'] = 1;

                $differenz = date_diff_raw($datum_primaer_op_rezidiv_op, $todesdatum);
                $data['kz_20_z'] += strlen($todesdatum) > 0 && $differenz['h'] <= 720;
                $d06[$i]['kz_20_z'] = (int) (strlen($todesdatum) > 0 && $differenz['h'] <= 720);
             }

             //21
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && $check_kolon === true) {
                $data['kz_21_n'] ++;
                $d06[$i]['kz_21_n'] = 1;
                $data['kz_21_z'] += (int) ($r_lokal == '0');
                $d06[$i]['kz_21_z'] = (int) ($r_lokal == '0');
             }

             //22
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && $check_rektum === true) {
                $data['kz_22_n'] ++;
                $d06[$i]['kz_22_n'] = 1;
                $data['kz_22_z'] += (int) ($r_lokal == '0');
                $d06[$i]['kz_22_z'] = (int) ($r_lokal == '0');
             }

             //23
             if( $primaerfall == 1 && $check_rektum === true && $stomaanlage == 1 && $elek_primaer_oprezidiv_op == 1) {
                 $data['kz_23_n'] ++;
                 $d06[$i]['kz_23_n'] = 1;
                 $data['kz_23_z'] += (int) ($stomaposition_anzeichnung == 1);
                 $d06[$i]['kz_23_z'] = (int) ($stomaposition_anzeichnung == 1);
             }

             //24
             if( $primaerfall == 1 && $elek_primaer_oprezidiv_op == 1 && ($operativer_fall_kolon == 1 || $operativer_fall_rektum == 1) && substr($uicc,0,2) == 'IV' && $lebermetastase_resektabel == 1) {
                 $data['kz_24_n'] ++;
                 $d06[$i]['kz_24_n'] = 1;
                 $data['kz_24_z'] += $lebermetastasenresektion;
                 $d06[$i]['kz_24_z'] = (int) $lebermetastasenresektion;
             }

             //25
             if ($primaerfall == 1 && ($operativer_fall_kolon == 1 || $operativer_fall_rektum == 1) && $elek_primaer_oprezidiv_op == 1 && substr($uicc,0,2) == 'IV' && $lebermetastase_nicht_resektabel == 1 && $chemotherapie == 1){
                $data['kz_25_n'] ++;
                $d06[$i]['kz_25_n'] = 1;
                $data['kz_25_z'] += (int) $sekundaere_lebermetastasenresektion;
                $d06[$i]['kz_25_z'] = (int) $sekundaere_lebermetastasenresektion;
             }

             //26
             if ($primaerfall == 1 && $check_kolon === true && str_starts_with($dataset['uicc'], 'III') === true &&
                strlen($r0_eingriff_histos) > 0 && strlen($primaerop_id) > 0 && in_array($primaerop_id, explode('|', $r0_eingriff_histos)) === true) {
                $data['kz_26_n'] ++;
                $d06[$i]['kz_26_n'] = 1;
                $data['kz_26_z'] += (int) $adj_chemotherapie;
                $d06[$i]['kz_26_z'] = (int) $adj_chemotherapie;
             }

             //27
             if ($primaerfall == 1 && $check_rektum === true && $elek_primaer_oprezidiv_op == 1 &&
                 // dies sind alle UICC IIer und IIIer
                 (substr($uicc_prae,0,2) == 'II' || ($cm == 'cM0' && (($ct == 'cT3' || $ct == 'cT4') || $cn == 'cN1'|| $cn == 'cN2')))
                 && (strlen($hoehe_ab_ano) > 0 && $hoehe_ab_ano <= '12')) {
                 $data['kz_27_n'] ++;
                 $d06[$i]['kz_27_n'] = 1;
                 $data['kz_27_z'] += (int) ($neoadj_alleinige_radiotherapie == 1 || $neoadj_sim_radiochemotherapie == 1);
                 $d06[$i]['kz_27_z'] = (int) ($neoadj_alleinige_radiotherapie == 1 || $neoadj_sim_radiochemotherapie == 1);
             }

             //28
             if ($primaerfall == 1 && $operativer_fall_rektum == 1 && $elek_primaer_oprezidiv_op == 1 && $tme == 1) {
                 $data['kz_28_n'] ++;
                 $d06[$i]['kz_28_n'] = 1;
                 $data['kz_28_z'] += (int) (in_array($op_qualitaet_mercury, array('1', '2')));
                 $d06[$i]['kz_28_z'] = (int) (in_array($op_qualitaet_mercury, array('1', '2')));
             }

             //29
             if ($primaerfall == 1 && $operativer_fall_rektum == 1 && $elek_primaer_oprezidiv_op == 1 && ($tme == 1 || $pme == 1)) {
                 $data['kz_29_n'] ++;
                 $d06[$i]['kz_29_n'] = 1;
                 $data['kz_29_z'] += (int) ($resektionsrand_dok == 1);
                 $d06[$i]['kz_29_z'] = (int) ($resektionsrand_dok == 1);
             }

             //30
             if (
                $primaerfall == 1 &&
                ($operativer_fall_kolon == 1 || $operativer_fall_rektum == 1) &&
                $elek_primaer_oprezidiv_op == 1 &&
                (strlen($dataset['lk_entfernt']) > 0 && $dataset['lk_entfernt'] != 0)
             ) {
                 $data['kz_30_n'] ++;
                 $d06[$i]['kz_30_n'] = 1;
                 $data['kz_30_z'] += (int) ($dataset['lk_entfernt'] >= 12);
                 $d06[$i]['kz_30_z'] = (int) ($dataset['lk_entfernt'] >= 12);
             }
         }
      }

      foreach ($data as $kzName => &$calcPr) {
         if( strpos($kzName, '_p') !== false) {
            $nenner  = $data[str_replace('_p', '_n', $kzName)];
            $zaehler = $data[str_replace('_p', '_z', $kzName)];
            $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
         }
      }

      $data['bzg'] = $bezugsjahr;

      if ($this->_params['name'] == 'd06') {
         $config = $this->loadConfigs('d06');
         $this->_title = $config['head_report'];

         $this->_data  = $d06;
         $this->writeXLS();
      } else {
         $this->_data = $data;
         $this->writePDF(true);
      }
   }
}

?>
