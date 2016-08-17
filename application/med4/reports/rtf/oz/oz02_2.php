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

class reportContentOz02_2 extends reportExtensionOz
{
   protected function initMatrix()
   {
      $data    = array();

      $config = $this->loadConfigs('oz01');

      $erkrankungen = array_keys($this->_diagnosen);

      foreach ($erkrankungen as $erkrankung) {

         $datablock = array();

         $datablock['kz_1_a'] = 0;
         $datablock['kz_2_a'] = 0;

         for ($i=2; $i <= 5; $i++) {
            $datablock["kz_{$i}_n"] = 0;
            $datablock["kz_{$i}_z"] = 0;
            $datablock["kz_{$i}_p"] = 0;
         }

         $datablock['used']      = false;
         $datablock['entitaet']  = $config['erkrankung_' . $erkrankung];

         $data[$erkrankung] = $datablock;
      }

      ksort($data);

      return $data;
   }

   public function generate()
   {
      $this->loadConfigs('oz02_2');

      $this->setTemplate('oz02_2');

      $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

      $matrix = $this->initMatrix();

      $additionalContent['condition'] = "YEAR(bezugsdatum) = {$bezugsjahr} OR LOCATE('$bezugsjahr', datum_studie) != 0";

      $datasets = $this->loadRessource('oz01', $additionalContent);

      foreach ($datasets as $dataset) {
         $entitaet = $dataset['diagnosetyp'];

         if (strlen($entitaet) == 0)
            continue;

         if (strlen($dataset['datum_studie']) > 0) {
            foreach (explode(', ', $dataset['datum_studie']) as $studiendatum) {
               $studienYear = date('Y', strtotime($studiendatum));
               if ($studienYear == $bezugsjahr) {
                  $matrix[$entitaet]['kz_5_z']++;
               }
            }
         }

         if ($bezugsjahr != date('Y', strtotime($dataset['bezugsdatum']))) {
            continue;
         }

         $primaerfall = $dataset['primaerfall'];

         //1
         $matrix[$entitaet]['kz_1_a'] += $primaerfall;

         //2
         if ($primaerfall == 1) {
            $matrix[$entitaet]['kz_2_n']++;

            if ($dataset['praeop_tumorkonf'] == 1 || $dataset['postop_tumorkonf'] == 1 ) {
               $matrix[$entitaet]['kz_2_z']++;
            }
         } else {
            if ($dataset['praeop_tumorkonf'] == 1 || $dataset['postop_tumorkonf'] == 1 ) {
               $matrix[$entitaet]['kz_2_a']++;
            }
         }

         //3
         if ($primaerfall == 1) {
            $matrix[$entitaet]['kz_3_n']++;
            $matrix[$entitaet]['kz_3_z'] += $dataset['psychoonk'];
         }

         //4
         if ($primaerfall == 1) {
            $matrix[$entitaet]['kz_4_n']++;
            $matrix[$entitaet]['kz_4_z'] += $dataset['sozialdienst'];
         }

         //5
         $matrix[$entitaet]['kz_5_n']+= (int) $primaerfall == 1;

         $matrix[$entitaet]['used'] = true;
      }

      $templateArray = array();

      $template = 'reports/rtf/oz/oz02_2.odt';

      $insertArrayArray = array();

      //Daten verarbeiten oder entfernen
      foreach ($matrix as $entity => &$content) {
         if ($content['used'] === true) {
            foreach ($content as $kzName => &$calcPr) {
               if (strpos($kzName, '_p') !== false) {
                  $nenner  = $content[str_replace('_p', '_n', $kzName)];
                  $zaehler = $content[str_replace('_p', '_z', $kzName)];
                  $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
               }
            }

            $content['bezugsjahr'] = $bezugsjahr;

            $templateArray[]  = $template;
            $insertArrayArray[] = $content;
         }
      }

      //Wenn Kein Template Array eingetragen wurde, leeres einfügen
      if (count($templateArray) == 0) {
         $templateArray[]  = $template;
      }

      //LBO... WTF!!!
      $this->_renderer->setLetterTemplateArray($templateArray);
      $this->_renderer->setInsertArrayArray($insertArrayArray);


      $this->writePDF(true, true);
   }
}

?>
