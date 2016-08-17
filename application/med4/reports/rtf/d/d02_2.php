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

class reportContentD02_2 extends reportExtensionD
{
    /**
     * initialize the data array
     *
     * @access
     * @return array
     */
    protected function _initDataArray()
    {
        $data = array();

        foreach (array('02','04','05','06') as $digit) {
            $data["kz_{$digit}_z"] = 0;
            $data["kz_{$digit}_n"] = 0;
            $data["kz_{$digit}_p"] = 0;
        }

        ksort($data);

        return $data;
    }


    /**
     * process data and generate document
     *
     * @access
     * @return void
     */
    public function generate()
    {
        $this->setTemplate('d02_2');

        $data = $this->_initDataArray();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ?
            $this->_params['jahr'] :
            date('Y')
        ;

        $datasets = $this->loadRessource('d01');
        $erkrankungId = array();
        $i = 0;

        foreach ($this->_sortArray($datasets, 'datum_sicherung') as $dataset) {

            // studien zählen (unabhängig vom patientenbezugsjahr)
            if (strlen($dataset['datum_studie']) > 0) {
                foreach (explode(', ', $dataset['datum_studie']) as $studiendatum) {
                    $studienYear = date('Y', strtotime($studiendatum));
                    if ($studienYear == $bezugsjahr) {
                        $data['kz_06_z']++;
                        $d06[$i]['kz_06_z'] = isset($d06[$i]['kz_06_z']) === true ? $d06[$i]['kz_06_z'] + 1 : 1;
                    }
                }
            }

            if (in_array($dataset['erkrankung_id'], $erkrankungId) || $bezugsjahr != date('Y', strtotime($dataset['bezugsdatum']))) {
                continue;
            }

            $jahr = $dataset['bezugsdatum'];
            $jahr = date('Y', strtotime($jahr));

            if ($jahr == $bezugsjahr) {
                // KZ2
                if ($dataset['primaerfall'] == 0 && $dataset['adenokarzinom'] == 1) {
                    $data['kz_02_n'] ++;
                    $d06[$i]['kz_02_n'] = 1;
                    $data['kz_02_z'] += $dataset['tumorkonf_praeop'];
                    $d06[$i]['kz_02_z'] = (int) $dataset['tumorkonf_praeop'];

                // KZ4a
                    $data['kz_04_n'] ++;
                    $d06[$i]['kz_04_n'] = 1;
                    $data['kz_04_z'] += $dataset['psychoonk_betreuung'];
                    $d06[$i]['kz_04_z'] = (int) $dataset['psychoonk_betreuung'];

                // KZ5a
                    $data['kz_05_n'] ++;
                    $d06[$i]['kz_05_n'] = 1;
                    $data['kz_05_z'] += $dataset['beratung_sozialdienst'];
                    $d06[$i]['kz_05_z'] = (int) $dataset['beratung_sozialdienst'];
                }

                if ($dataset['primaerfall'] == 0) {
                    $erkrankungId[] = $dataset['erkrankung_id'];
                }

                $i++;
            }
        }

        foreach ($data as $kzName => &$calcPr) {
            if (strpos($kzName, '_p') !== false) {
                $nenner  = $data[str_replace('_p', '_n', $kzName)];
                $zaehler = $data[str_replace('_p', '_z', $kzName)];
                $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
            }
        }

        $data["kz_06_n"] = '--';

        $data['bzg'] = $bezugsjahr;

        $this->_data = $data;
        $this->writePDF(true);
    }


    /**
     * Sorts an array, by given field
     *
     *
     * @access      protected
     * @param       $array
     * @param       $sortField
     * @param       bool $asc
     * @return      array
     */
    protected function _sortArray($array, $sortField, $asc = true)
    {
        $tmp = array();
        $sortedArray = array();

        if (is_array($array) === true) {

            foreach ($array as $dataset) {
                $tmp[$dataset[$sortField]][] = $dataset;
            }
        }
        $asc === true ? ksort($tmp) : krsort($tmp);

        foreach ($tmp as $new) {
            if (count($new) > 1) {
                foreach ($new as $n) {
                    $sortedArray[] = $n;
                }
            } else {
                $sortedArray[] = $new[0];
            }
        }


        return $sortedArray;
    }
}
?>
