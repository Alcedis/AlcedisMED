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

class reportContentLu01_2 extends reportExtensionLu
{
   public function generate()
   {
      $config = $this->loadConfigs('lu01_2');
      $this->_title = $config['head_report'];
      $this->_data  = $this->convertReportData($this->loadRessource('lu01_2'));

      $this->writeXLS();
   }

   /**
    * Convert data for lu01_2 report
    *
    * @param $data
    */
   private function convertReportData($data)
   {
      foreach ($data as &$record) {
          unset($record['ops_codes']);
          unset($record['erkrankung_id']);
          unset($record['patient_id']);
          unset($record['eingriff_id']);
          unset($record['start_date']);
          unset($record['end_date']);
          unset(
             $record['t'],
             $record['n'],
             $record['m'],
             $record['uicc']
          );
      }

      return $data;
   }
}

?>