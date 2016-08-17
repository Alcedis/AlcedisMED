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

class reportContentH01_2 extends reportExtensionH
{
   public function generate()
   {
      $config = $this->loadConfigs('h01_2');

      $this->_title = $config['head_report'];

      $data = $this->loadRessource('h01_2');
      $this->_data = $this->convertReportData($data);

      $this->writeXLS();
   }


    /**
     * convertReportData
     *
     * @access  public
     * @param   array $data
     * @return  array
     */
    public function convertReportData($data)
    {
        $lKomplikation = getLookup($this->_db, 'komplikation');

        foreach ($data as &$dataset) {
            unset($dataset['eingriff_id']);
            unset($dataset['erkrankung_id']);
            unset($dataset['sln_markierung']);
            unset($dataset['sln_anzahl']);
            unset($dataset['anlass']);

            //Komplikation
            $komplikationen = strlen($dataset['komplikation']) > 0 ? explode('|', $dataset['komplikation']) : null;

            if ($komplikationen !== null) {
                $tmp = array();

                foreach($komplikationen as $komplikation) {
                    if (isset($lKomplikation[$komplikation]) === true) {
                        $tmp[] = $lKomplikation[$komplikation];
                    }
                }

                asort($tmp);

                $dataset['komplikation'] = implode(', ', $tmp);
            }
        }

        return $data;
    }
}

?>
