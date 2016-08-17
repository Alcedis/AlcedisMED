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

class reportContentGt02 extends reportExtensionGt
{
    /**
     * _initDataArray
     *
     * @access  protected
     * @param   bool $showP
     * @return  array
     */
    protected function _initDataArray($showP = true)
    {
        $data = array();
        //normale zahlen (zähler, nenner, prozent)
        $n = array(1,2,3,4,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27);

        foreach ($n as $nDigit) {
            $nDigitS = strlen($nDigit) == 1 ? "0$nDigit" : $nDigit;
            $data["kz_{$nDigitS}_z"] = 0;
            $data["kz_{$nDigitS}_n"] = 0;

            if ($showP === true) {
                $data["kz_{$nDigitS}_p"] = 0;
            }
        }

        ksort($data);

        return $data;
    }


    /**
     * _initGt06DataArray
     *
     * @access  protected
     * @param   array   $datasets
     * @return  array
     */
    protected function _initGt06DataArray($datasets)
    {
        $data = array();

        $dataArray = $this->_initDataArray(false);

        foreach ($datasets as $i => $dataset) {

            $patientData = array(
                'nachname'      => $dataset['nachname'],
                'vorname'       => $dataset['vorname'],
                'geburtsdatum'  => $dataset['geburtsdatum'],
                'bezugsdatum'   => $dataset['bezugsdatum'],
            );

            $data[$i] = array_merge($patientData, $dataArray);
        }

        return $data;
    }


    /**
     * generate
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $this->setTemplate('gt02');

        $gt06 = array();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

        $data = $this->_initDataArray();

        $additionalContent['condition'] = "({$bezugsjahr} = YEAR(bezugsdatum) OR LOCATE('$bezugsjahr', datum_studie) != 0)";

        $additionalContent['fields'] = array(
            'COUNT(th_str.strahlentherapie_id) OR
             COUNT(th_sys.therapie_systemisch_id) OR
             COUNT(IF(op.art_rezidiv IS NOT NULL, op.eingriff_id, NULL))
             as kz25_n'
        );

        $datasets = $this->loadRessource('gt01_1', $additionalContent);

        //Init Gt06
        if (str_starts_with($this->_params['name'], 'gt06') === true && count($datasets) > 0) {
            $gt06 = $this->_initGt06DataArray($datasets);
        }

        foreach ($datasets as $i => $dataset) {
            extract($dataset);

            $jahr = date('Y', strtotime($dataset['bezugsdatum']));

            $figo = $dataset['figo_nach_neoadj_th'] === '1' ? $dataset['figo_prae'] : $dataset['figo'];

            $studyDate = $dataset['datum_studie'];

            if (strlen($studyDate) > 0) {
                foreach (explode(', ', $studyDate) as $studiendatum) {
                    $studienYear = date('Y', strtotime($studiendatum));

                    if ($studienYear == $bezugsjahr) {
                        $data['kz_04_z'] ++;
                        $gt06[$i]['kz_04_z'] = isset($gt06[$i]['kz_04_z']) === true ? $gt06[$i]['kz_04_z'] + 1 : 1;
                    }
                }
            }

            if ($jahr != $bezugsjahr) {
                continue;
            }

            // KZ01
            if ($this->checkKz01($dataset) === true) {
                $data['kz_01_n'] ++;
                $gt06[$i]['kz_01_n'] = 1;

                $condition = (int) ($dataset['praeop_konferenz'] == 1 || $dataset['postop_konferenz'] == 1);

                $data['kz_01_z'] += $condition;
                $gt06[$i]['kz_01_z'] = $condition;
            }

            // KZ02
            if ($this->checkKz02($dataset) === true) {
                $data['kz_02_n'] ++;
                $gt06[$i]['kz_02_n'] = 1;

                $psychoCare = (int) $dataset['psychoonk_betreuung'];

                $data['kz_02_z'] += $psychoCare;
                $gt06[$i]['kz_02_z'] = $psychoCare;
            }

            // KZ03
            if ($this->checkKz03($dataset) === true) {
                $data['kz_03_n'] ++;
                $gt06[$i]['kz_03_n'] = 1;

                $socialCare = (int) $dataset['betreuungsozialdienst'];

                $data['kz_03_z'] += $socialCare;
                $gt06[$i]['kz_03_z'] = $socialCare;
            }

            // KZ04
            if ($this->checkKz04($dataset) === true) {
                $data['kz_04_n'] ++;
                $gt06[$i]['kz_04_n'] = 1;
            }


            if ($this->cgz_ovca_op_prim_stag($dataset) === true || $this->cgz_ovca_op_prim_def($dataset) === true) {
                // KZ08
                if (in_array($figo, array('IA', 'IB', 'IC', 'IIA', 'IIB', 'IIIA')) === true) {
                    $data['kz_08_n'] ++;
                    $gt06[$i]['kz_08_n'] = 1;

                    $opStaging = $this->hasOpStaging($dataset);

                    $data['kz_08_z'] += $opStaging === true ? 1 : 0;
                    $gt06[$i]['kz_08_z'] = $opStaging === true ? 1 : 0;
                }

                // KZ09
                if (in_array($figo, array('IA', 'IB')) === true) {
                    $data['kz_09_n'] ++;
                    $gt06[$i]['kz_09_n'] = 1;
                    $data['kz_09_z'] += $dataset['tumorruptur'];
                    $gt06[$i]['kz_09_z'] = (int) $dataset['tumorruptur'];
                }
            }

            if ($this->cgz_ovca_op_prim_def($dataset) === true) {
                // KZ10
                if (in_array($figo, array('IIB', 'IIIA', 'IIIB', 'IIIC', 'IV')) === true) {
                    $data['kz_10_n'] ++;
                    $gt06[$i]['kz_10_n'] = 1;
                    $data['kz_10_z'] += $dataset['resektion'];
                    $gt06[$i]['kz_10_z'] = (int) $dataset['resektion'];
                }

                // KZ11
                if (in_array($figo, array('IIB', 'IIIA', 'IIIB', 'IIIC', 'IV')) === true) {
                    $data['kz_11_n'] ++;
                    $gt06[$i]['kz_11_n'] = 1;
                    $data['kz_11_z'] += $dataset['operative_therapie'];
                    $gt06[$i]['kz_11_z'] = (int) $dataset['operative_therapie'];
                }

                // KZ12
                if (in_array($figo, array('IIB', 'IIIA', 'IIIB', 'IIIC', 'IV')) === true &&
                    $dataset['durchgef_chemotherapie'] == 1
                ) {
                    $data['kz_12_n'] ++;
                    $gt06[$i]['kz_12_n'] = 1;
                    $data['kz_12_z'] += $dataset['postop_chemotherapie'];
                    $gt06[$i]['kz_12_z'] = (int) $dataset['postop_chemotherapie'];
                }
            }

            // KZ13
            if ($this->checkKz13($dataset) === true) {
                $condition = (int) $dataset['adj_chemoth_durchgef'];

                $data['kz_13_n'] ++;
                $gt06[$i]['kz_13_n'] = 1;

                $data['kz_13_z'] += $condition;
                $gt06[$i]['kz_13_z'] = $condition;
            }

            if ($this->cgz_ovca_op_prim_stag($dataset) === true ||
                $this->cgz_ovca_op_prim_def($dataset) === true ||
                $this->cgz_ovca_nop_prim($dataset) === true
            ) {
                // KZ14
                if ($figo === 'IC' || (in_array($figo, array('IA', 'IB')) === true && $dataset['g'] === 'G3')) {
                    $data['kz_14_n'] ++;
                    $gt06[$i]['kz_14_n'] = 1;
                    $data['kz_14_z'] += $dataset['platinhaltige_chemotherapie'];
                    $gt06[$i]['kz_14_z'] = (int) $dataset['platinhaltige_chemotherapie'];
                }

                //KZ 15
                if (in_array($figo, array('IIB', 'IIIA', 'IIIB', 'IIIC', 'IV')) === true) {
                    $data['kz_15_n'] ++;
                    $gt06[$i]['kz_15_n'] = 1;
                    $data['kz_15_z'] += $dataset['first_line_therapie'];
                    $gt06[$i]['kz_15_z'] = (int) $dataset['first_line_therapie'];
                }
            }

            if ($this->checkKz16($dataset) === true) {
                $data['kz_16_n'] ++;
                $gt06[$i]['kz_16_n'] = 1;

                $monotherapy = (int) $dataset['monotherapie'];

                $data['kz_16_z'] += $monotherapy;
                $gt06[$i]['kz_16_z'] = $monotherapy;
            }

            if ($this->checkKz17($dataset) === true) {
                $data['kz_17_n'] ++;
                $gt06[$i]['kz_17_n'] = 1;

                $combitherapy = (int) $dataset['combitherapie'];

                $data['kz_17_z'] += $combitherapy;
                $gt06[$i]['kz_17_z'] = $combitherapy;
            }

            // KZ 18
            if ($dataset['diagnose'] === 'D39.1' && $dataset['g'] === 'GB' && $this->isPrimary($dataset) === true) {
                $condition = ($dataset['adj_antihormonelleth_durchgef'] == 1 ||
                    $dataset['adj_chemoth_durchgef'] == 1 ||
                    $dataset['adj_immunth_durchgef'] == 1 ||
                    $dataset['adj_strahlenth_durchgef'] == 1)
                    ? 1
                    : 0
                ;

                $data['kz_18_n'] ++;
                $gt06[$i]['kz_18_n'] = 1;
                $data['kz_18_z'] += $condition;
                $gt06[$i]['kz_18_z'] = (int) $condition;
            }

            // KZ19
            if ($this->checkKz19($dataset) === true) {
                $condition = (int) ($dataset['praeop_konferenz'] == 1 || $dataset['postop_konferenz'] == 1);

                $data['kz_19_n'] ++;
                $gt06[$i]['kz_19_n'] = 1;

                $data['kz_19_z'] += $condition;
                $gt06[$i]['kz_19_z'] = (int) $condition;
            }

            // KZ20
            if ($this->checkKz20($dataset) === true) {
                $condition = (int) ($dataset['vollst_histo'] == 1);

                $data['kz_20_n'] ++;
                $gt06[$i]['kz_20_n'] = 1;

                $data['kz_20_z'] += $condition;
                $gt06[$i]['kz_20_z'] = $condition;
            }

            // KZ21
            if ($this->checkKz21($dataset) === true) {
                $condition = (int) ($dataset['vollst_lympho'] == 1);

                $data['kz_21_n'] ++;
                $gt06[$i]['kz_21_n'] = 1;

                $data['kz_21_z'] += $condition;
                $gt06[$i]['kz_21_z'] = $condition;
            }

            // KZ22
            if ($this->checkKz22($dataset) === true) {
                $condition = (int) ($dataset['lk_staging'] == 1);

                $data['kz_22_n'] ++;
                $gt06[$i]['kz_22_n'] = 1;

                $data['kz_22_z'] += $condition;
                $gt06[$i]['kz_22_z'] = $condition;
            }

            // KZ23
            if ($this->checkKz23($dataset) === true) {
                $condition = (int) ($dataset['durchgef_radio_chemo_cis'] == 1);

                $data['kz_23_n'] ++;
                $gt06[$i]['kz_23_n'] = 1;

                $data['kz_23_z'] += $condition;
                $gt06[$i]['kz_23_z'] = $condition;
            }

            // KZ24
            if ($this->checkKz24($dataset) === true) {
                $condition = (int) ($dataset['adj_radio_chemo'] == 1);

                $data['kz_24_n'] ++;
                $gt06[$i]['kz_24_n'] = 1;

                $data['kz_24_z'] += $condition;
                $gt06[$i]['kz_24_z'] = $condition;
            }

            // KZ25
            if ($this->checkKz25($dataset) === true) {
                $condition = (int) ($dataset['praeth_histo_sicherung'] == 1);

                $data['kz_25_n'] ++;
                $gt06[$i]['kz_25_n'] = 1;

                $data['kz_25_z'] += $condition;
                $gt06[$i]['kz_25_z'] = $condition;
            }

            // KZ26
            if ($this->checkKz26($dataset) === true) {
                $condition = (int) ($dataset['bildg_diagnostik'] == 1);

                $data['kz_26_n'] ++;
                $gt06[$i]['kz_26_n'] = 1;

                $data['kz_26_z'] += $condition;
                $gt06[$i]['kz_26_z'] = $condition;
            }

            // KZ27
            if ($this->checkKz27($dataset) === true) {
                $condition = (int) ($dataset['r_lokal'] == '0');

                $data['kz_27_n'] ++;
                $gt06[$i]['kz_27_n'] = 1;

                $data['kz_27_z'] += $condition;
                $gt06[$i]['kz_27_z'] = $condition;
            }
        }

        foreach ($data as $kzName => &$calcPr) {
            if (strpos($kzName, '_p') !== false) {
                $nenner  = $data[str_replace('_p', '_n', $kzName)];
                $zaehler = $data[str_replace('_p', '_z', $kzName)];
                $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
            }
        }

        if (str_starts_with($this->_params['name'], 'gt06') === true) {
            $config = $this->loadConfigs('gt06_1');
            $this->_title = $config['head_report'];

            $this->_data  = $gt06;
            $this->writeXLS();
        } else {

            $data['bezugsjahr'] = "(Bezugsjahr $bezugsjahr)";

            $this->_data = $data;
            $this->writePDF(true);
        }
    }


    /**
     * checkKz01
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz01($record)
    {
        return $this->cgz_ges($record);
    }


    /**
     * checkKz02
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz02($record)
    {
        return $this->cgz_ges($record);
    }


    /**
     * checkKz03
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz03($record)
    {
        return $this->cgz_ges($record);
    }


    /**
     * checkKz04
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz04($record)
    {
        return $this->cgz_prim_ges($record);
    }

    /**
     * checkKz13
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz13(array $record)
    {
        $condition = (
            ($this->cgz_ovca_op_prim_stag($record) === true || $this->cgz_ovca_op_prim_def($record) === true) &&
            $this->check_figo($record, array('IA')) === true &&
            $record['g'] === 'G1' &&
            $this->hasOpStaging($record) === true
        );

        return $condition;
    }

    /**
     * checkKz16
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz16($record)
    {
        $valid = false;

        $cond = (
            str_starts_with($record['diagnose'], array('C56', 'C48', 'C57.0')) === true &&
            $this->isPrimary($record) === false &&
            $record['anlass'] === 'r01' &&
            $record['tumorverhalten'] === 'plres' &&
            $record['durchgef_chemotherapie'] == 1 &&
            $record['chemo_excl_study'] == 1
        );

        if ($cond === true) {
            $valid = date('Y-m-d', strtotime($record['enddatum'] . ' + 6 month')) > $record['datum_sicherung'];
        }

        return $valid;
    }


    /**
     * checkKz17
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz17(array $record)
    {
        $condition = (
            str_starts_with($record['diagnose'], array('C56', 'C48', 'C57.0')) === true &&
            $this->isPrimary($record) === false &&
            $record['tumorverhalten'] === 'plsen' &&
            $record['chemo_excl_study'] == 1
        );

        return $condition;
    }


    /**
     * checkKz19
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz19(array $record)
    {
        $condition = (
            ($this->cgz_zeca_op_prim_stag($record) === true ||
             $this->cgz_zeca_op_prim_def($record) === true ||
             $this->cgz_zeca_nop_prim($record) === true
            ) && $this->check_figo($record, array('IA1', 'IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA', 'IVB')) === true
        ) || $this->cgz_zeca_nprim($record) === true;

        return $condition;
    }


    /**
     * checkKz20
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz20(array $record)
    {
        $condition = ($this->cgz_zeca_op_prim_def($record) === true &&
            $this->check_figo($record, array('IA1', 'IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA', 'IVB')) === true
        );

        return $condition;
    }


    /**
     * checkKz21
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz21(array $record)
    {
        return ($this->checkKz20($record) === true && $record['lymphono'] == 1);
    }


    /**
     * checkKz22
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz22(array $record)
    {
        $condition = (
            ($this->cgz_zeca_op_prim_stag($record) === true ||
             $this->cgz_zeca_op_prim_def($record) === true ||
             $this->cgz_zeca_nop_prim($record) === true ||
             $this->cgz_zeca_nprim($record) === true
            ) &&
            $this->check_figo($record, array('IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA')) === true
        );

        return $condition;
    }


    /**
     * checkKz23
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz23(array $record)
    {
        $condition = (
            ($this->cgz_zeca_op_prim_stag($record) === true ||
                $this->cgz_zeca_op_prim_def($record) === true ||
                $this->cgz_zeca_nop_prim($record) === true
            ) &&
            $this->check_figo($record, array('IA1', 'IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA', 'IVB')) === true &&
            $record['hysterektomie'] == null &&
            $record['durchgef_radio_chemo'] == 1
        );

        return $condition;
    }


    /**
     * checkKz24
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz24(array $record)
    {
        $condition = (
            $this->cgz_zeca_op_prim_def($record) === true &&
            $this->check_figo($record, array('IA1', 'IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA', 'IVB')) === true &&
            $record['radikale_hysterektomie'] == 1
        );

        return $condition;
    }


    /**
     * checkKz25
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz25(array $record)
    {
        $condition = (
            $this->cgz_zeca_nprim($record) === true &&
            $record['lokalrezidiv'] == 1 &&
            $record['kz25_n'] == 1
        );

        return $condition;
    }


    /**
     * checkKz26
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz26(array $record)
    {
        return ($this->cgz_zeca_nprim($record) === true && $record['lokalrezidiv'] == 1);
    }


    /**
     * checkKz27
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function checkKz27(array $record)
    {
        $condition = (
            $this->cgz_zeca_nprim($record) === true &&
            strlen($record['datumprimaer_rezidiv_op']) > 0 &&
            $record['exenteration'] == 1
        );

        return $condition;
    }
}

?>
