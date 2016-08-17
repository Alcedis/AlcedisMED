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

class reportContentGt01_2 extends reportExtensionGt
{
   public function generate()
   {
      $config = $this->loadConfigs('gt01_2');

      $data = $this->loadRessource('gt01_2');
      $data = $this->convertReportData($data);

      $this->_title = $config['head_report'];
      $this->_data  = $data;

      $this->writeXLS();
   }


   /**
    * Convert data for gt01_2 report
    *
    * @param $data
    */
    private function convertReportData($data)
    {
        foreach ($data as &$dataset) {
            unset(
                $dataset['erkrankung_id'],
                $dataset['patient_id'],
                $dataset['eingriff_id']
            );
        }

        return $data;
    }
}

?>