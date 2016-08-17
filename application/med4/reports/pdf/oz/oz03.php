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

class reportContentOz03 extends reportExtensionOz
{
   protected $_values = array();

   public function init(alcReportPdf $renderer){
      $renderer->addPage();
   }

   private function _initValues()
   {
      $erkrankungen = array_keys($this->_diagnosen);

      asort($erkrankungen);

      $erkrankungen = array_merge($erkrankungen, array('alle_erkrankungen'));

      $erkMinus = array();

      foreach ($erkrankungen as $erkrankung) {
         $erkMinus[$erkrankung] = '-';
      }

      $percentArray = array(
         'lokalrezidiv'      => array(
            'val'             => $erkrankungen,
            'lokalrezidiv'    => $erkrankungen,
            'tumorkonferenz'  => $erkrankungen,
            'anteil'          => $erkrankungen
         ),
         'fernmetast'      => array(
            'val'             => $erkrankungen,
            'fernmetast'      => $erkrankungen,
            'tumorkonferenz'  => $erkrankungen,
            'anteil'          => $erkrankungen
         ),
         'behandlungsplan'      => array(
            'val'                            => $erkrankungen,
            'behandlungsplan_alle_faelle'    => $erkrankungen,
            'behandlungsplan'                => $erkrankungen,
            'anteil'                         => $erkrankungen
         ),
         'befragungsbogen'      => array(
            'val'                                  => $erkrankungen,
            'befragungsbogen_stationaere_faelle'   => $erkrankungen,
            'befragungsbogen_vorhanden'            => $erkrankungen,
            'anteil'                               => $erkrankungen
         ),
         'chirurgie'      => array(
            'val'          => $erkrankungen,
            'primaer_op'   => $erkrankungen,
         ),
         'strahlentherapie' => array(
            'val'                            => array(),
            'strahlentherapie_einrichtung'   => array(),
            'strahlentherapie_durchgefuehrt' => array()
         ),
         'nebenwirkung_chemoradio' => array(
            'val' => array(),
            'nebenwirkung_chemoradio_name' => array(),
            'nebenwirkung_chemoradio_anzahl' => array()
         ),
         'schnellschnitt' => array(
            'val'                         => $erkrankungen,
            'schnellschnitt_alle_faelle'  => $erkrankungen,
            'schnellschnitt_kl30'         => $erkrankungen,
            'schnellschnitt_gr30'         => $erkrankungen,
            'schnellschnitt_min'          => $erkMinus,
            'schnellschnitt_max'          => $erkMinus,
            'schnellschnitt_range'        => $erkMinus
         ),
         'invasiv' => array(
            'val'                   => $erkrankungen,
            'invasiv_alle_faelle'   => $erkrankungen,
            'invasiv_pt'            => $erkrankungen,
            'invasiv_pn'            => $erkrankungen
         ),
         'resektion' => array(
            'val'                   => $erkrankungen,
            'resektion_alle_faelle'   => $erkrankungen,
            'resektion_r'            => $erkrankungen,
            'resektion_s'            => $erkrankungen
         ),
         'chemotherapie' => array(
            'val'                         => array(),
            'chemotherapie_einrichtung'   => array(),
            'chemotherapie_durchgefuehrt' => array()
         ),
      );


      $this
         ->_initPercentArray($percentArray)
      ;

      return $this;
   }

   public function generate($renderer)
   {
      $renderer->setConfig($this->loadConfigs('oz03', false, true));

      $data = $this->loadRessource('oz01');

      $this->_count = count($data);
      $this->_initValues();

      foreach ($data as $dataset) {
         $disease = $dataset['erkrankung'];

         if (strlen($disease) > 0) {

            //OZ03.1 Vorstellung Tumorkonferenz (Pat. mit Lokalrezidiv)
            if (strlen($dataset['lokalrezidiv']) > 0) {
               $this->_values['lokalrezidiv']['lokalrezidiv'][$disease]++;
               $this->_values['lokalrezidiv']['lokalrezidiv']['alle_erkrankungen']++;

               if (strlen($dataset['postop_tumorkonf']) > 0 || strlen($dataset['praeop_tumorkonf']) > 0) {
                  $this->_values['lokalrezidiv']['tumorkonferenz'][$disease]++;
                  $this->_values['lokalrezidiv']['tumorkonferenz']['alle_erkrankungen']++;
               }
            }

            //OZ03.2 Vorstellung Tumorkonferenz (Pat. mit Metastasen)
            if (strlen($dataset['fernmetast']) > 0) {
               $this->_values['fernmetast']['fernmetast'][$disease]++;
               $this->_values['fernmetast']['fernmetast']['alle_erkrankungen']++;

               if (strlen($dataset['postop_tumorkonf']) > 0 || strlen($dataset['praeop_tumorkonf']) > 0) {
                  $this->_values['fernmetast']['tumorkonferenz'][$disease]++;
                  $this->_values['fernmetast']['tumorkonferenz']['alle_erkrankungen']++;
               }
            }

            //OZ03.3 Behandlungsplan (Therapieplan)
            $this->_values['behandlungsplan']['behandlungsplan_alle_faelle'][$disease]++;
            $this->_values['behandlungsplan']['behandlungsplan_alle_faelle']['alle_erkrankungen']++;

            if ($dataset['therapieplan'] == '1') {
               $this->_values['behandlungsplan']['behandlungsplan'][$disease]++;
               $this->_values['behandlungsplan']['behandlungsplan']['alle_erkrankungen']++;
            }

            //OZ03.4 Rücklaufquote Befragungsbogen
            if ($dataset['stationaer'] == '1') {
               $this->_values['befragungsbogen']['befragungsbogen_stationaere_faelle'][$disease]++;
               $this->_values['befragungsbogen']['befragungsbogen_stationaere_faelle']['alle_erkrankungen']++;

               if ($dataset['befragungsbogen'] == '1') {
                  $this->_values['befragungsbogen']['befragungsbogen_vorhanden'][$disease]++;
                  $this->_values['befragungsbogen']['befragungsbogen_vorhanden']['alle_erkrankungen']++;
               }
            }

            //OZ03.5 Fallzahl Chirurgie
            if ($dataset['primaerfall'] == '1' && strlen($dataset['primaerop']) > 0) {
               $this->_values['chirurgie']['primaer_op'][$disease]++;
               $this->_values['chirurgie']['primaer_op']['alle_erkrankungen']++;
            }

            //OZ03.9 Angabe pT, pN bei invasivem Karzinom
            if ($dataset['primaerfall'] == '1' && substr($dataset['icd03'], -2) == '/3') {
               $this->_values['invasiv']['invasiv_alle_faelle'][$disease]++;
               $this->_values['invasiv']['invasiv_alle_faelle']['alle_erkrankungen']++;

               //pT dokumentiert
               if (strlen($dataset['pt']) > 0) {
                  $this->_values['invasiv']['invasiv_pt'][$disease]++;
                  $this->_values['invasiv']['invasiv_pt']['alle_erkrankungen']++;
               }

               //pN dokumentiert
               if (strlen($dataset['pn']) > 0) {
                  $this->_values['invasiv']['invasiv_pn'][$disease]++;
                  $this->_values['invasiv']['invasiv_pn']['alle_erkrankungen']++;
               }
            }

            //OZ03.8 Schnellschnitte
            if (strlen($dataset['primaerop']) > 0) {
               $this->_values['schnellschnitt']['schnellschnitt_alle_faelle'][$disease]++;
               $this->_values['schnellschnitt']['schnellschnitt_alle_faelle']['alle_erkrankungen']++;

               if (strlen($dataset['resektion_ergebnisdauer']) > 0 && ((int) $dataset['resektion_ergebnisdauer'] <= 30) === true) {
                  $this->_values['schnellschnitt']['schnellschnitt_kl30'][$disease]++;
                  $this->_values['schnellschnitt']['schnellschnitt_kl30']['alle_erkrankungen']++;
               }

               if (strlen($dataset['resektion_ergebnisdauer']) > 0 && ((int) $dataset['resektion_ergebnisdauer'] > 30) === true) {
                  $this->_values['schnellschnitt']['schnellschnitt_gr30'][$disease]++;
                  $this->_values['schnellschnitt']['schnellschnitt_gr30']['alle_erkrankungen']++;
               }

               if (strlen($dataset['resektion_ergebnisdauer']) > 0) {
                  $this->_values['schnellschnitt']['schnellschnitt_min'][$disease]     = $this->_insert('min', $this->_values['schnellschnitt']['schnellschnitt_min'][$disease], $dataset['resektion_ergebnisdauer']);
                  $this->_values['schnellschnitt']['schnellschnitt_max'][$disease]     = $this->_insert('max', $this->_values['schnellschnitt']['schnellschnitt_max'][$disease], $dataset['resektion_ergebnisdauer']);
                  $this->_values['schnellschnitt']['schnellschnitt_range'][$disease]   = $this->_insert('range', $this->_values['schnellschnitt']['schnellschnitt_max'][$disease], $this->_values['schnellschnitt']['schnellschnitt_min'][$disease]);

                  $this->_values['schnellschnitt']['schnellschnitt_min']['alle_erkrankungen']     = $this->_insert('min', $this->_values['schnellschnitt']['schnellschnitt_min']['alle_erkrankungen'], $dataset['resektion_ergebnisdauer']);
                  $this->_values['schnellschnitt']['schnellschnitt_max']['alle_erkrankungen']     = $this->_insert('max', $this->_values['schnellschnitt']['schnellschnitt_max']['alle_erkrankungen'], $dataset['resektion_ergebnisdauer']);
                  $this->_values['schnellschnitt']['schnellschnitt_range']['alle_erkrankungen']   = $this->_insert('range', $this->_values['schnellschnitt']['schnellschnitt_max']['alle_erkrankungen'], $this->_values['schnellschnitt']['schnellschnitt_min']['alle_erkrankungen']);
               }
            }

            //OZ03.10 Resektionsrand/Sicherheitsabstand 8.13
            if (strlen($dataset['primaerop']) > 0) {
               $this->_values['resektion']['resektion_alle_faelle'][$disease]++;
               $this->_values['resektion']['resektion_alle_faelle']['alle_erkrankungen']++;

               if (strlen($dataset['r_lokal']) > 0) {
                  $this->_values['resektion']['resektion_r'][$disease]++;
                  $this->_values['resektion']['resektion_r']['alle_erkrankungen']++;
               }

               if (strlen($dataset['sicherabstand']) > 0) {
                  $this->_values['resektion']['resektion_s'][$disease]++;
                  $this->_values['resektion']['resektion_s']['alle_erkrankungen']++;
               }
            }
         }
      }

      //Strahlen und Chemotherapien verarbeiten
      $therapys = array(
         'strahlentherapie' => array('values' => array(), 'orgValues' => array()),
         'chemotherapie'    => array('values' => array(), 'orgValues' => array()),
         'orgs'             => array(),
      );

      $sysTherapyWithByEffect = array(
         'disease'   => array(),
         'byeffect'  => array(),
         'byeffectValues' => array()
      );

      //Strahlen -und Chemotherapien
      $strahlenUndChemoTherapien = $this->loadRessource('strahlen_chemo_therapien');

      foreach ($strahlenUndChemoTherapien AS $therapy) {
         $type       = $therapy['type'];
         $org        = $therapy['org_id'];
         $disease    = $therapy['diagnosetyp'];
         $type       = $therapy['type'];
         $byEffects  = explode(',', $therapy['byeffect']);

         if (in_array($type, array_keys($therapys)) === true) {
            //Org füllen
            $therapys['orgs'][$org] = 1;

            //Einzel
            $therapys[$type]['values'][$disease][$org] = isset($therapys[$type]['values'][$disease][$org]) === true
               ? $therapys[$type]['values'][$disease][$org] + 1
               : 1
            ;

            //Orggesamt
            $therapys[$type]['orgValues'][$org] = isset($therapys[$type]['orgValues'][$org]) === true
               ? $therapys[$type]['orgValues'][$org] + 1
               : 1
            ;
         } else {
            foreach ($byEffects as $byEffect) {
               $sysTherapyWithByEffect['byeffect'][$byEffect] = 1;
               $sysTherapyWithByEffect['disease'][$disease][$byEffect] = isset($sysTherapyWithByEffect['disease'][$disease][$byEffect]) === true
                  ? $sysTherapyWithByEffect['disease'][$disease][$byEffect] + 1
                  : 1
               ;

               $sysTherapyWithByEffect['byeffectValues'][$byEffect] = isset($sysTherapyWithByEffect['byeffectValues'][$byEffect]) === true
                  ? $sysTherapyWithByEffect['byeffectValues'][$byEffect] + 1
                  : 1
               ;
            }
         }
      }

      ksort($sysTherapyWithByEffect['disease']);
      ksort($therapys['strahlentherapie']['values']);
      ksort($therapys['chemotherapie']['values']);

      if (count($sysTherapyWithByEffect['byeffect']) > 0) {
         $sysTherapyWithByEffect['byeffect'] = getMappedLookup($this->_db, 'l_nci', 'bez', 'code', array_keys($sysTherapyWithByEffect['byeffect']), true);
         asort($sysTherapyWithByEffect['byeffect']);
      }

      foreach ($sysTherapyWithByEffect['disease'] AS $disease => $byEffects) {
         foreach (array_keys($sysTherapyWithByEffect['byeffect']) as $byEffect) {
            if (isset($byEffects[$byEffect]) === true) {
               $this->_values['nebenwirkung_chemoradio']['val'][] = $disease;
               $this->_values['nebenwirkung_chemoradio']['nebenwirkung_chemoradio_name'][]   = $byEffect;
               $this->_values['nebenwirkung_chemoradio']['nebenwirkung_chemoradio_anzahl'][] = $byEffects[$byEffect];
            }
         }
      }

      foreach ($sysTherapyWithByEffect['byeffect'] as $byeffect => $dummy) {
         $this->_values['nebenwirkung_chemoradio']['val'][] = 'alle_erkrankungen';
         $this->_values['nebenwirkung_chemoradio']['nebenwirkung_chemoradio_name'][] = $byeffect;
         $this->_values['nebenwirkung_chemoradio']['nebenwirkung_chemoradio_anzahl'][] = $sysTherapyWithByEffect['byeffectValues'][$byeffect];
      }


      //Strahlen und Chemotherapie Orgs sortieren
      if (count($therapys['orgs']) > 0) {
         $therapys['orgs'] = getMappedLookup($this->_db, 'org', "CONCAT_WS(',', name, namenszusatz)", 'org_id', array_keys($therapys['orgs']), true);
         asort($therapys['orgs']);
      }

      //Therapien nach Name und Erkrankung vorsortieren
      foreach (array('strahlentherapie' => $therapys['strahlentherapie'], 'chemotherapie' => $therapys['chemotherapie']) as $type => $content) {
         foreach ($content['values'] as $disease => $orgs) {
            foreach ($therapys['orgs'] as $therapyOrgId => $therapyOrgName) {
               if (isset($orgs[$therapyOrgId]) === true) {
                  $this->_values[$type]['val'][] = $disease;
                  $this->_values[$type][$type . '_einrichtung'][] = $therapyOrgId;
                  $this->_values[$type][$type . '_durchgefuehrt'][] = $orgs[$therapyOrgId];
               }
            }
         }

         foreach ($therapys['orgs'] as $therapyOrgId => $therapyOrgName) {
            if (isset($content['orgValues'][$therapyOrgId]) === true) {
               $this->_values[$type]['val'][] = 'alle_erkrankungen';
               $this->_values[$type][$type . '_einrichtung'][]    = $therapyOrgId;
               $this->_values[$type][$type . '_durchgefuehrt'][]  = $content['orgValues'][$therapyOrgId];
            }
         }
      }

      $this->_calculateAmount();

      require_once 'reports/pdf/oz/oz03/p1.php';
   }

   private function _insert($type, $src, $value, $default = '-')
   {
      $val = $src !== $default ? $src : $default;

      switch ($type) {
         case 'range':

            if (is_int($src) == true && is_int($value) == true) {
               $val = $src - $value;
            }

            break;

         case 'max':
         case 'min':

            if (strlen($value) > 0) {
               $value   = (int) $value;
               $src     = $src !== $default ? $src : (
                  $type == 'min' ? $value : 0
               );

               eval('$compare = $type == "max" ? ($value >= $src) : ($value <= $src);');

               if ($compare == true) {
                  $val = $value;
               }

            } else {
               $val = $src;
            }

            break;
      }

      return $val;
   }


   private function _calculateAmount()
   {
       foreach ($this->_values as $section => $possibleAmount) {
           if (isset($possibleAmount['anteil']) === true) {

               $pos = 0;
               $src = array();
               $trg = array();

               foreach($possibleAmount as $key => $values) {
                   if ($pos == 2) {
                       $src = $values;
                   } elseif ($pos == 3) {
                      $trg = $values;
                   }
                   $pos++;
               }

               foreach ($src as $key => $value) {
                  $op = $trg[$key];

                  $amount = '-';

                  if ($value != 0) {
                      $amount = 0;

                      if ($value != 0 && $op != 0) {
                          $amount = ($op / $value) * 100;
                      }

                      $amount = round($amount);

                      $amount .= ' %';
                  }

                  $this->_values[$section]['anteil'][$key] = $amount;
               }
           }
       }
   }


}

?>