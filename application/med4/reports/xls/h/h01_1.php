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

class reportContentH01_1 extends reportExtensionH
{
   public function generate()
   {
      $config = $this->loadConfigs('h01_1');

      $data = $this->loadRessource('h01_1');

      $this->_title = $config['head_report'];
      $this->_data  = $this->convertReportData($data);

      $this->writeXLS();
   }

   /**
    * Convert data for h01_1 report
    *
    * @param $data
    */
   private function convertReportData($data)
   {
      foreach ($data as &$record) {
         unset($record['max_ajcc']);
         unset($record['min_ajcc']);
         unset($record['anlass']);
         unset($record['start_date']);
         unset($record['end_date']);
         unset($record['erkrankung_id']);
         unset($record['patient_id']);
      }

      return $data;
   }
}

?>