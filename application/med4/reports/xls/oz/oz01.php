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

class reportContentOz01 extends reportExtensionOz
{
   public function generate()
   {
        $config = $this->loadConfigs('oz01');

        $data = $this->loadRessource('oz01');
        $data = $this->convertReportData($data);

        $this->_title = $config['head_report'];
        $this->_data  = $data;

      $this->writeXLS();
   }

   /**
    * Convert data for oz01 report
    *
    * @param $data
    */
   private function convertReportData($data)
   {
      $config = $this->loadConfigs('oz01');

      foreach ($data as &$dataset) {
         unset($dataset['diagnosetyp']);
         unset($dataset['erkrankung_id']);
         unset($dataset['patient_id']);
         unset($dataset['anlass']);
         unset($dataset['start_date']);
         unset($dataset['end_date']);
         unset($dataset['zugeordnet_zu']);
         unset($dataset['prostata_nz']);

         $dataset['erkrankung'] = $config['erkrankung_' . $dataset['erkrankung']];
      }

      return $data;
   }
}

?>