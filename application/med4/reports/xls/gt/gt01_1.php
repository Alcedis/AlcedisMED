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

class reportContentGt01_1 extends reportExtensionGt
{
    /**
     * generate
     *
     * @access  public
     * @return  void
     */
   public function generate()
   {
      $config = $this->loadConfigs('gt01_1');

      $data = $this->loadRessource('gt01_1');
      $data = $this->convertReportData($data);

      $this->_title = $config['head_report'];
      $this->_data  = $data;

      $this->writeXLS();
   }


    /**
     * convertReportData
     *
     * @access  public
     * @param   array   $data
     * @return  array
     */
    public function convertReportData($data)
    {
        $ll = getLookup($this->_db, 'l');
        $lv = getLookup($this->_db, 'v');
        $ljn = getLookup($this->_db, 'jn');
        $lppn = getLookup($this->_db, 'ppn');
        $exzision = getLookup($this->_db, 'exzision');

        foreach ($data as &$dataset) {
            $dataset['l']   = $this->map($dataset['l'], $ll);
            $dataset['v']   = $this->map($dataset['v'], $lv);
            $dataset['ppn'] = $this->map($dataset['ppn'], $lppn);
            $dataset['ex_konisation'] = $this->map($dataset['ex_konisation'], $exzision);
            $dataset['kapseldurchbruch'] = $this->map($dataset['kapseldurchbruch'], $ljn);

            unset($dataset['anlass']);
            unset($dataset['start_date']);
            unset($dataset['end_date']);
            unset($dataset['erkrankung_id']);
            unset($dataset['patient_id']);
            unset($dataset['max_figo']);
            unset($dataset['tumorverhalten']);
            unset($dataset['lk_pelvin']);
            unset($dataset['lk_para']);
            unset($dataset['e_histologien']);
            unset($dataset['primaerop_eingriff_id']);
            unset($dataset['lymphono_op']);
            unset($dataset['ts_vollst_histo']);
        }

        return $data;
    }
}

?>
