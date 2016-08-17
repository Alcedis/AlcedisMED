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

class reportContentOz02_1 extends reportExtensionOz
{
   protected function initMatrix()
   {
      $data = array();

      $entities = array_keys($this->_diagnosen);

      foreach ($entities as $entity) {
         $data[$entity] = 0;
      }

      $data['gesamt'] = 0;

      ksort($data);

      return $data;
   }



   public function generate()
   {
      $this->setTemplate('oz02_1');

      $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

      $data = $this->initMatrix();

      $additionalContent['condition'] = "YEAR(bezugsdatum) = {$bezugsjahr}";

      $datasets = $this->loadRessource('oz01', $additionalContent);

      foreach ($datasets as $dataset) {
         $entitaet      = $dataset['diagnosetyp'];

         if (strlen($entitaet) > 0 && $dataset['primaerfall'] == 1) {
            $data[$entitaet]++;
            $data['gesamt']++;
         }
      }

       $data['bezugsjahr'] = "(Bezugsjahr $bezugsjahr)";

      $this->_data = $data;

      $this->writePDF(true);
   }
}

?>
