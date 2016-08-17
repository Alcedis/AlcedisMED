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

require_once 'reports/scripts/reportHelper.php';

/**
 * Class reportContentB02
 */
class reportContentB02 extends reportExtensionB
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
        $n = array(1,2,3,11,12,13,14,15,17,18,19,20,21,22,23,24,25,26);
        //anzahl
        $a = array(16);
        //normale zahlen mit .1, .2 Unterteilung
        $s = array(4,5,6,7,8,9,10);

        foreach ($n as $nDigit) {
            $nDigitS = strlen($nDigit) == 1 ? "0$nDigit" : $nDigit;
            $data["kz_{$nDigitS}_z"] = 0;
            $data["kz_{$nDigitS}_n"] = 0;

            if ($showP === true) {
                $data["kz_{$nDigitS}_p"] = 0;
            }
        }

        foreach ($a as $aDigit) {
            $aDigitS = strlen($aDigit) == 1 ? "0$aDigit" : $aDigit;
            $data["kz_{$aDigitS}_a"] = 0;
        }

        foreach ($s as $sDigit) {
            $sDigitS = strlen($sDigit) == 1 ? "0$sDigit" : $sDigit;
            foreach (range(1,2) as $subDigit) {
                $data["kz_{$sDigitS}_{$subDigit}_z"] = 0;
                $data["kz_{$sDigitS}_{$subDigit}_n"] = 0;

                if ($showP === true) {
                    $data["kz_{$sDigitS}_{$subDigit}_p"] = 0;
                }
            }
        }

        ksort($data);

        return $data;
    }


    /**
     * init
     *
     * @access  public
     * @param   mixed $renderer
     * @return  void
     */
    public function init($renderer) {
        if ($this->_type == 'pdf') {
            $renderer->addPage();
        }
    }


    /**
     * header
     *
     * @access  public
     * @return  void
     */
    public function header()
    { }


    /**
     * generate
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $this->setTemplate('b02');

        $b06 = array();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');
        $vorjahr    = $bezugsjahr - 1;

        $data = $this->_initDataArray();

        $additionalContent['fields'] = array(
            "sit.patient_id AS 'patient_id'",
            "IF(
                 LEFT(sit.cn,1) = 'c' AND RIGHT(sit.cn, 4) = '(sn)',
                 sit.cn,
                 NULL
             ) AS 'cn_sn'"
        );

        $result = $this->loadRessource('b01', $additionalContent);

        //Init BZ06
        if ($this->_params['name'] == 'b06' && count($result) > 0) {
            $b06 = $this->_initB06DataArray($result);
        }

        $kzCache = array(
            '03' => array(),
            '11' => array(),
            '12' => array(),
            '13' => array()
        );

        // build cache
        foreach ($result as $i => $record) {
            if ($record['bezugsdatum'] === null) {
                continue;
            }

            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr > $bezugsjahr) {
                continue;
            }

            if (strlen($record['datum_studie']) > 0) {
                foreach (explode(', ', $record['datum_studie']) as $studiendatum) {
                    $studienYear = date('Y', strtotime($studiendatum));
                    if ($studienYear == $bezugsjahr) {
                        $data['kz_14_z'] ++;
                        $b06[$i]['kz_14_z'] = isset($b06[$i]['kz_14_z']) === true ? $b06[$i]['kz_14_z'] + 1 : 1;
                    }
                }
            }

            // kz11 cache
            if ($this->_checkVarM1($record) === true && array_key_exists($record['erkrankungId'], $kzCache['11']) === false) {
                $kzCache['11'][$record['erkrankungId']] = $record['anlass_raw'];
            }
        }

        foreach ($result as $i => $record) {
            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr != $bezugsjahr && $jahr != $vorjahr) {
                continue;
            }

            $diseaseSitIdent = $record['erkrankungId'] . $record['seite'];
            $diseaseId = $record['erkrankungId'];

            $primaryCase = strlen($record['primaerfall']) > 0 ? (int) $record['primaerfall'] : null;

            //kz_01
            if ($record['primaerfall'] == 1 &&
                $this->_hasPrimaryOp($record) === true &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_01_n'] ++;
                $b06[$i]['kz_01_n'] = 1;

                $data['kz_01_z']   += (int) $record['postop_tumorkonf'];
                $b06[$i]['kz_01_z'] = (int) $record['postop_tumorkonf'];
            }

            //kz_02
            if ($record['primaerfall'] == 1 &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_02_n'] ++;
                $b06[$i]['kz_02_n'] = 1;

                $data['kz_02_z']   += (int) $record['praeop_tumorkonf'];
                $b06[$i]['kz_02_z'] = (int) $record['praeop_tumorkonf'];
            }

            //kz_03
            if ($record['primaerfall'] == 0 && $jahr == $bezugsjahr && in_array($diseaseSitIdent, $kzCache['03']) === false) {
                $kzCache['03'][] = $diseaseSitIdent;

                $m = $record['primary_m'];

                if (str_contains($m, '1') === false) {
                    $data['kz_03_n'] ++;
                    $b06[$i]['kz_03_n'] = 1;

                    $condition = (int) ($record['praeop_tumorkonf'] == 1 || $record['postop_tumorkonf'] == 1);

                    $data['kz_03_z']   += $condition;
                    $b06[$i]['kz_03_z'] = $condition;
                }
            }

            //kz_04_1
            if ($record['primaerfall'] == 1 &&
                $this->_hasBetAndNoMastektomie($record) === true &&
                $this->_checkAllOpVars($record, false) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_04_1_n'] ++;
                $b06[$i]['kz_04_1_n'] = 1;

                $data['kz_04_1_z']   += (int) $record['gepl_adj_strahlenth'];
                $b06[$i]['kz_04_1_z'] = (int) $record['gepl_adj_strahlenth'];
            }

            //kz_04_2
            if ($record['primaerfall'] == 1 &&
                $record['gepl_adj_strahlenth'] == 1 &&
                $this->_hasBetAndNoMastektomie($record) === true &&
                $this->_checkAllOpVars($record, false) &&
                $jahr == $vorjahr
            ) {
                $data['kz_04_2_n'] ++;
                $b06[$i]['kz_04_2_n'] = 1;

                $data['kz_04_2_z']   += (int) $record['durchgef_adj_strahlenth'];
                $b06[$i]['kz_04_2_z'] = (int) $record['durchgef_adj_strahlenth'];
            }

            //kz_05_1
            if ($record['primaerfall'] == 1 &&
                $this->_hasBetAndNoMastektomie($record) === true &&
                ($this->_checkOpVarA($record, 'Tis') || $this->_checkOpVarB($record, 'Tis')) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_05_1_n'] ++;
                $b06[$i]['kz_05_1_n'] = 1;

                $data['kz_05_1_z']   += (int) $record['gepl_adj_strahlenth'];
                $b06[$i]['kz_05_1_z'] = (int) $record['gepl_adj_strahlenth'];
            }

            //kz_05_2
            if ($record['primaerfall'] == 1 &&
                $record['gepl_adj_strahlenth'] == 1 &&
                $this->_hasBetAndNoMastektomie($record) === true &&
                ($this->_checkOpVarA($record, 'Tis') || $this->_checkOpVarB($record, 'Tis')) &&
                $jahr == $vorjahr
            ) {
                $data['kz_05_2_n'] ++;
                $b06[$i]['kz_05_2_n'] = 1;

                $data['kz_05_2_z']   += (int) $record['durchgef_adj_strahlenth'];
                $b06[$i]['kz_05_2_z'] = (int) $record['durchgef_adj_strahlenth'];
            }

            //kz_06_1
            if ($jahr == $bezugsjahr && $this->_checkKz06_1($record) === true) {
                $data['kz_06_1_n'] ++;
                $b06[$i]['kz_06_1_n'] = 1;

                $data['kz_06_1_z'] += $record['gepl_adj_strahlenth'];
                $b06[$i]['kz_06_1_z'] = (int) $record['gepl_adj_strahlenth'];
            }

            //kz_06_2
            if ($jahr == $vorjahr && $this->_checkKz06_2($record) === true) {
                $data['kz_06_2_n'] ++;
                $b06[$i]['kz_06_2_n'] = 1;

                $condition = (int) $record['durchgef_adj_strahlenth'];

                $data['kz_06_2_z']   += $condition;
                $b06[$i]['kz_06_2_z'] = $condition;
            }

            //kz_07_1
            if ($jahr == $bezugsjahr && $this->_checkKz07_1($record) === true) {
                $data['kz_07_1_n'] ++;
                $b06[$i]['kz_07_1_n'] = 1;

                $condition = ($record['gepl_neoadj_chemoth'] == '1' || $record['gepl_adj_chemoth'] == '1');

                $data['kz_07_1_z']   += (int) $condition;
                $b06[$i]['kz_07_1_z'] = (int) $condition;
            }

            //kz_07_2
            if ($jahr == $vorjahr && $this->_checkKz07_2($record) === true) {
                $data['kz_07_2_n'] ++;
                $b06[$i]['kz_07_2_n'] = 1;

                $condition = (int) $record['durchgef_chemoth'];

                $data['kz_07_2_z']   += $condition;
                $b06[$i]['kz_07_2_z'] = $condition;
            }

            //kz_08_1
            if ($record['primaerfall'] == 1 &&
                $record['rezeptorbefund'] == 'p' &&
                $this->_hasPnPlus($record) &&
                $this->_checkAllVars($record, false) === true &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_08_1_n'] ++;
                $b06[$i]['kz_08_1_n'] = 1;

                $condition = ($record['gepl_neoadj_chemoth'] == '1' || $record['gepl_adj_chemoth'] == '1');

                $data['kz_08_1_z']   += (int) $condition;
                $b06[$i]['kz_08_1_z'] = (int) $condition;
            }

            //kz_08_2
            if ($jahr == $vorjahr &&
                $record['primaerfall'] == 1 &&
                $record['rezeptorbefund'] == 'p' &&
                $this->_hasPnPlus($record) &&
                $this->_checkAllVars($record, false) === true &&
                ($record['gepl_adj_chemoth'] == 1 || $record['gepl_neoadj_chemoth'] == 1)
            ) {
                $data['kz_08_2_n'] ++;
                $b06[$i]['kz_08_2_n'] = 1;

                $data['kz_08_2_z']   += (int) $record['durchgef_chemoth'];
                $b06[$i]['kz_08_2_z'] = (int) $record['durchgef_chemoth'];
            }

            //kz_09_1
            if ($jahr == $bezugsjahr && $this->_checkKz09_1($record) === true) {
                $data['kz_09_1_n'] ++;
                $b06[$i]['kz_09_1_n'] = 1;

                $condition = (int) $record['gepl_antih_th'];

                $data['kz_09_1_z']   += $condition;
                $b06[$i]['kz_09_1_z'] = $condition;
            }

            //kz_09_2
            if ($jahr == $vorjahr && $this->_checkKz09_2($record) === true) {
                $data['kz_09_2_n'] ++;
                $b06[$i]['kz_09_2_n'] = 1;

                $condition = (int) $record['durchgef_antih_th'];

                $data['kz_09_2_z']   += $condition;
                $b06[$i]['kz_09_2_z'] = $condition;
            }

            //kz_10_1
            if ($jahr == $bezugsjahr && $this->_checkKz10_1($record) === true) {
                $data['kz_10_1_n'] ++;
                $b06[$i]['kz_10_1_n'] = 1;

                $condition = (int) $record['gepl_immun_th_trastuzumab'];

                $data['kz_10_1_z']   += $condition;
                $b06[$i]['kz_10_1_z'] = $condition;
            }

            //kz_10_2
            if ($jahr == $vorjahr && $this->_checkKz10_2($record) === true) {
                $data['kz_10_2_n'] ++;
                $b06[$i]['kz_10_2_n'] = 1;

                $condition = (int) $record['durchgef_immunth_trastuzumab'];

                $data['kz_10_2_z']   += $condition;
                $b06[$i]['kz_10_2_z'] = $condition;
            }

            //kz_11
            if ((array_key_exists($diseaseId, $kzCache['11']) === true && $kzCache['11'][$diseaseId] === $record['anlass_raw']) &&
                $jahr == $bezugsjahr &&
                $record['rezeptorbefund'] === 'p'
            ) {
                $data['kz_11_n'] ++;
                $b06[$i]['kz_11_n'] = 1;

                $condition = (int) $record['therapielinie_anti_th'];

                $data['kz_11_z']   += $condition;
                $b06[$i]['kz_11_z'] = $condition;
            }

            //kz_12
            if ($jahr == $bezugsjahr && ($primaryCase === 1 ||
                ($primaryCase === 0 && (array_key_exists($diseaseSitIdent, $kzCache['12']) === false || in_array($jahr, $kzCache['12'][$diseaseSitIdent]) === false))
            )) {
                $count = false;

                if ($primaryCase === 0) {
                    $kzCache['12'][$diseaseSitIdent][] = $jahr;

                    if (str_contains($record['primary_m'], '1') === false) {
                        $count = true;
                    }
                } else {
                    $count = true;
                }

                if ($count === true) {
                    $data['kz_12_n'] ++;
                    $b06[$i]['kz_12_n'] = 1;

                    $condition = (int) ((int) $record['psychoonk_betreuung'] > 25);

                    $data['kz_12_z']   += $condition;
                    $b06[$i]['kz_12_z'] = $condition;
                }
            }

            //kz_13
            if ($jahr == $bezugsjahr && ($primaryCase === 1 ||
                    ($primaryCase === 0 && (array_key_exists($diseaseSitIdent, $kzCache['13']) === false || in_array($jahr, $kzCache['13'][$diseaseSitIdent]) === false))
                )) {
                $count = false;

                if ($primaryCase === 0) {
                    $kzCache['13'][$diseaseSitIdent][] = $jahr;

                    if (str_contains($record['primary_m'], '1') === false) {
                        $count = true;
                    }
                } else {
                    $count = true;
                }

                if ($count === true) {
                    $data['kz_13_n'] ++;
                    $b06[$i]['kz_13_n'] = 1;

                    $condition = (int) ((int) $record['betreuungsozialdienst'] === 1);

                    $data['kz_13_z']   += $condition;
                    $b06[$i]['kz_13_z'] = $condition;
                }
            }

            //kz_14
            if ($record['primaerfall'] == 1 && $jahr == $bezugsjahr) {
                $data['kz_14_n'] ++;
                $b06[$i]['kz_14_n'] = 1;
            }

            //kz_15
            if ($jahr == $bezugsjahr && $this->_checkKz15($record) === true) {
                $data['kz_15_n'] ++;
                $b06[$i]['kz_15_n'] = 1;

                $condition = (int) (strlen($record['datumpraeop_hist']) > 0 && $record['stanz_vaku'] == 1);

                $data['kz_15_z']   += $condition;
                $b06[$i]['kz_15_z'] = $condition;
            }

            //kz_16
            $data['kz_16_a']   += $jahr == $bezugsjahr && $record['primaerfall'] == 1 ? 1 : 0;
            $b06[$i]['kz_16_a'] = $jahr == $bezugsjahr && $record['primaerfall'] == 1 ? 1 : 0;

            //kz_17
            if ($record['primaerfall'] == 1 &&
                $this->_hasPrimaryOp($record) === true &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                str_contains($record['pt'], 'T1') === true &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_17_n'] ++;
                $b06[$i]['kz_17_n'] = 1;

                $data['kz_17_z']   += (int) $record['bet'];
                $b06[$i]['kz_17_z'] = (int) $record['bet'];
            }

            //kz_18
            if ($record['primaerfall'] == 1 &&
                $this->_hasPrimaryOp($record) === true &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_18_n'] ++;
                $b06[$i]['kz_18_n'] = 1;

                $data['kz_18_z']   += (int) $record['mastektomie'];
                $b06[$i]['kz_18_z'] = (int) $record['mastektomie'];
            }

            //kz_19
            if ($record['primaerfall'] == 1 &&
                ($this->_checkOpVarA($record, 'Tis')  || $this->_checkOpVarB($record, 'Tis')) &&
                $this->_hasBetAndNoMastektomie($record) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_19_n'] ++;
                $b06[$i]['kz_19_n'] = 1;

                $condition = ($record['axilla_diss'] == 1 || $record['sln_biopsie'] == 1);

                $data['kz_19_z']   += (int) $condition;
                $b06[$i]['kz_19_z'] = (int) $condition;
            }

            //kz_20
            if ($record['primaerfall'] == 1 &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                $this->_checkAllOpVars($record) === true &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_20_n'] ++;
                $b06[$i]['kz_20_n'] = 1;

                $condition = ($this->_hasPnPlus($record) || $this->_hasPn0OrPnsn0($record));

                $data['kz_20_z']   += (int) $condition;
                $b06[$i]['kz_20_z'] = (int) $condition;
            }

            //kz_21
            if ($jahr == $bezugsjahr && $this->_checkKz21($record) === true) {
                $data['kz_21_n'] ++;
                $b06[$i]['kz_21_n'] = 1;

                $condition = (int) ($record['sln_biopsie'] == 1 && strlen($record['axilla_diss']) == 0);

                $data['kz_21_z']   += $condition;
                $b06[$i]['kz_21_z'] = $condition;
            }

            //kz_22
            if ($record['drahtmarkierung_ges'] == 1 &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_22_n'] ++;
                $b06[$i]['kz_22_n'] = 1;

                $condition = ($record['intraop_roentgen'] == 1 || $record['intraop_sono'] == 1);

                $data['kz_22_z']   += (int) $condition;
                $b06[$i]['kz_22_z'] = (int) $condition;
            }

            //kz_23
            if ($record['primaerfall'] == 1 &&
                $this->_hasPrimaryOp($record) === true &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_23_n'] ++;
                $b06[$i]['kz_23_n'] = 1;

                $condition = (int) $record['revisions_op'];

                $data['kz_23_z']   += $condition;
                $b06[$i]['kz_23_z'] = $condition;
            }

            //kz_24
            if ($jahr == $vorjahr && $this->_checkKz24($record) === true) {
                $data['kz_24_n'] ++;
                $b06[$i]['kz_24_n'] = 1;

                $condition = (int) $record['rekonstruktion'];

                $data['kz_24_z']   += $condition;
                $b06[$i]['kz_24_z'] = $condition;
            }


            //kz_25
            if ($record['primaerfall'] == 1 &&
                $this->_hasPrimaryOp($record) === true &&
                ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true) &&
                $jahr == $bezugsjahr
            ) {
                $data['kz_25_n'] ++;
                $b06[$i]['kz_25_n'] = 1;

                $condition = strlen($record['sicherheitsabstand']) > 0;

                $data['kz_25_z']   += (int) $condition;
                $b06[$i]['kz_25_z'] = (int) $condition;
            }

            //kz_26
            if ($jahr == $bezugsjahr && $this->_isPrimaryCase($record) === true) {
                $data['kz_26_n'] ++;
                $b06[$i]['kz_26_n'] = 1;

                $condition = (int) $record['meldung_kr'];

                $data['kz_26_z']   += $condition;
                $b06[$i]['kz_26_z'] = $condition;
            }
        }

        if ($this->_params['name'] == 'b06') {
            $config = $this->loadConfigs('b06');
            $this->_title = $config['head_report'];

            $this->_data  = $b06;
            $this->writeXLS();
        } else {
            foreach ($data as $kzName => &$calcPr) {
                if (strpos($kzName, '_p') !== false) {
                    $nenner  = $data[str_replace('_p', '_n', $kzName)];
                    $zaehler = $data[str_replace('_p', '_z', $kzName)];
                    $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
                }
            }

            $data['bezugsjahr'] = "Bezugsjahr: $bezugsjahr";

            $this->_data = $data;
            $this->writePDF(true);
        }
    }


    /**
     *
     *
     * @access
     * @param $datasets
     * @return array
     */
    protected function _initB06DataArray($datasets)
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
     * _isPrimaryCase
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _isPrimaryCase(array $record)
    {
        return ((int) $record['primaerfall'] === 1);
    }


    /**
     * _hasPrimaryOp
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasPrimaryOp(array $record)
    {
        return (strlen($record['datumprimaer_op']) > 0);
    }


    /**
     * _hasNeoadjuvantTherapy
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasNeoadjuvantTherapy($record)
    {
        return ($record['durchgef_neoadj_therapie'] == '1');
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasNoNeoadjuvantTherapy($row)
    {
        return !$this->_hasNeoadjuvantTherapy($row);
    }


    /**
     * M0 check
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasM0(array $record)
    {
        return str_contains($record['m'], 'M0');
    }


    /**
     * M1 check
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasCm1(array $record)
    {
        return str_starts_with($record['m'], "cM1");
    }


    /**
     * M1 check
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasM1(array $record)
    {
        return str_contains($record['m'], 'M1');
    }


    /**
     * Not M1 check
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasNotM1($row)
    {
        return !$this->_hasM1($row);
    }


    /**
     * _hasBet
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _hasBet(array $record)
    {
        return ($record['bet'] == '1');
    }


    /**
     * _hasMastektomie
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasMastektomie(array $record)
    {
        return ($record['mastektomie'] == '1');
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasBetAndNoMastektomie($row)
    {
        if ($this->_hasBet($row) === true && $this->_hasMastektomie($row) === false) {
            return true;
        }
        return false;
    }


    /**
     * cM0 check
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _hasCm0(array $record)
    {
        return str_contains($record['m'], 'cM0');
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPnPlus($row)
    {
        if ((strlen($row['pn']) > 0) &&
            (str_starts_with($row['pn'], "pN1") ||
                str_starts_with($row['pn'], "pN2") ||
                str_starts_with($row['pn'], "pN3"))) {
            return true;
        }
        else if ((strlen($row['pn_sn']) > 0) &&
            (str_starts_with($row['pn_sn'], "pN1") ||
                str_starts_with($row['pn_sn'], "pN2") ||
                str_starts_with($row['pn_sn'], "pN3"))) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCnPlus($row)
    {
        if ((strlen($row['cn']) > 0) &&
            (str_starts_with($row['cn'], "cN1") ||
                str_starts_with($row['cn'], "cN2") ||
                str_starts_with($row['cn'], "cN3"))) {
            return true;
        }
        return false;
    }


    /**
     * _hasCn13
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasCn13(array $record)
    {
        return str_contains($record['cn'], array('N1','N2','N3'));
    }


    /**
     * Mx check
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasMx($row)
    {
        return str_contains($row['m'], 'MX');
    }


    /**
     * NX check
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasNx($row)
    {
        return str_contains($row['n'], 'NX');
    }


    /**
     * _hasCn0
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _hasCn0(array $record)
    {
        return str_contains($record['cn'], 'N0');
    }


    /**
     * _hasCnsn0
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasCnsn0($row)
    {
        return str_contains($row['cn_sn'], "N0");
    }


    /**
     * pN0 or pN0(sn) check
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasPn0OrPnsn0(array $record)
    {
        if ((strlen($record['pn']) > 0) &&
            str_starts_with($record['pn'], "pN0")) {
            return true;
        } else if ((strlen($record['pn_sn']) > 0) &&
            str_starts_with($record['pn_sn'], "pN0")) {
            return true;
        }

        return false;
    }


    /**
     * pT0 check
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasPt0($row)
    {
        return str_contains($row['pt'], 'T0');
    }


    /**
     * _hasPnsn0 check
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _hasPnsn0($row)
    {
        return str_contains($row['pn_sn'], 'N0');
    }


    /**
     * _hasCnPlus13
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _hasCnPlus13(array $record)
    {
        return str_contains($record['cn'], array('N1', 'N2', 'N3'));
    }


    /**
     * _hasR12
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasR12(array $record)
    {
        return str_contains($record['r'], array('1', '2'));
    }


    /**
     * _hasPn13Psn13
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _hasPn13Psn13(array $record)
    {
        return (str_contains($record['pn'], array('N1', 'N2', 'N3')) === true || str_contains($record['pn_sn'], array('N1', 'N2', 'N3')) === true);
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ptVal
     * @return bool
     */
    protected function _hasN0($row, $ptVal)
    {
        if (str_starts_with($ptVal, "Tis")) {
            if (($this->_hasPn0OrPnsn0($row) === true) ||
                ($this->_hasCn0($row) === true)) {
                return true;
            }
        }
        else {
            if ($this->_hasPn0OrPnsn0($row) === true) {
                return true;
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ptVal
     * @return bool
     */
    protected function _checkOpVarA($row, $ptVal)
    {
        return $this->_checkVarA($row, $ptVal);
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ptVal
     * @return bool
     */
    protected function _checkVarA($row, $ptVal)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNoNeoadjuvantTherapy($row) === true) &&
            (str_starts_with($row['pt'], "p{$ptVal}") === true) &&
            ($this->_hasN0($row, $ptVal) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ctVal
     * @return bool
     */
    protected function _checkVarB($row, $ctVal)
    {
        if (((($this->_hasPrimaryOp($row) === true) && ($this->_hasNeoadjuvantTherapy($row) === true)) ||
                ($this->_hasPrimaryOp($row) === false) ||
                ($this->_hasPt0($row) === true)) &&
            (str_starts_with($row['ct'], "c{$ctVal}") === true) &&
            ($this->_hasCn0($row) === true) &&
            ($this->_hasCm0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ctVal
     * @return bool
     */
    protected function _checkOpVarB($row, $ctVal)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNeoadjuvantTherapy($row) === true) &&
            (str_starts_with($row['ct'], "c{$ctVal}") === true) &&
            ($this->_hasCn0($row) === true) &&
            ($this->_hasCm0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarNA($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNoNeoadjuvantTherapy($row) === true) &&
            ($this->_hasPnPlus($row) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkOpVarNA($row)
    {
        return $this->_checkVarNA($row);
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarNB($row)
    {
        if (((($this->_hasPrimaryOp($row) === true) && ($this->_hasNeoadjuvantTherapy($row) === true)) ||
                ($this->_hasPrimaryOp($row) === false) ||
                ($this->_hasPt0($row) === true)) &&
            ($this->_hasCnPlus($row) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkOpVarNB($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNeoadjuvantTherapy($row) === true) &&
            ($this->_hasCnPlus($row) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarM1($row)
    {
        if ($this->_hasM1($row) === true) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkOpVarM1($row)
    {
        return $this->_checkVarM1($row);
    }


    /**
     * _hasLk4
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasLk4(array $record)
    {
        return ($record['lkbefallen'] >= 4);
    }


    /**
     * _hasPt34
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasPt34(array $record)
    {
        return str_contains($record['pt'], array('T3','T4'));
    }


    /**
     * _hasPt14
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasPt14(array $record)
    {
        return str_contains($record['pt'], array('T1','T2','T3','T4'));
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkAllOpVars($row, $checkM = true)
    {
        if ($this->_checkOpVarA($row, 'T1')  || $this->_checkOpVarB($row, 'T1') ||
            $this->_checkOpVarA($row, 'T2')  || $this->_checkOpVarB($row, 'T2') ||
            $this->_checkOpVarA($row, 'T3')  || $this->_checkOpVarB($row, 'T3') ||
            $this->_checkOpVarA($row, 'T4')  || $this->_checkOpVarB($row, 'T4') ||
            $this->_checkOpVarNA($row) || $this->_checkOpVarNB($row) ||
            ($this->_hasM1($row) && $checkM === true && $this->_hasPrimaryOp($row))) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param      $row
     * @param bool $checkM
     * @return bool
     */
    protected function _checkAllVars($row, $checkM = true)
    {
        if ($this->_checkVarA($row, 'T1')  || $this->_checkVarB($row, 'T1') ||
            $this->_checkVarA($row, 'T2')  || $this->_checkVarB($row, 'T2') ||
            $this->_checkVarA($row, 'T3')  || $this->_checkVarB($row, 'T3') ||
            $this->_checkVarA($row, 'T4')  || $this->_checkVarB($row, 'T4') ||
            $this->_checkVarNA($row) || $this->_checkVarNB($row) ||
            ($this->_hasM1($row) && $checkM === true)) {
            return true;
        }
        return false;
    }


    /**
     * _checkKz06_1
     *
     * @access  protected
     * @param   array   $record
     * @return  bool
     */
    protected function _checkKz06_1(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            $this->_hasPrimaryOp($record) === true &&
            $this->_hasMastektomie($record) === true &&
            (
                $this->_hasNoNeoadjuvantTherapy_And_Pt14_And_Pn0pnsn0_And_M0($record) === true ||
                $this->_hasNeoadjuvantTherapy_And_Ct14_And_Cn0_And_Cm0($record) === true ||
                $this->_hasNoNeoadjuvantTherapy_And_Pn13pnsn13_And_M0($record) === true ||
                $this->_hasNeoadjuvantTherapy_And_Cn13_And_M0($record) === true
            ) &&
            (
                $this->_hasR12($record) === true ||
                $this->_hasLk4($record) === true ||
                $this->_hasPt34($record) === true
            )
        );

        return $condition;
    }


    /**
     * _checkKz06_2
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz06_2(array $record)
    {
        return ($this->_checkKz06_1($record) === true && $record['gepl_adj_strahlenth'] == 1);
    }


    /**
     * _checkKz07_1
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz07_1(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            (
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pt14_And_Pn0Pnsn0_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_Or_NoPrimaryOp_Or_Pt0_With_Ct14_And_Cn0_And_Cm0($record) === true ||
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pn13Pnsn13_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_OR_NoPrimarOp_Or_Pt0_With_Cn13_And_M0($record) === true
            ) &&
            $record['rezeptorbefund'] === 'n'
        );

        return $condition;
    }


    /**
     * _checkKz07_2
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz07_2(array $record)
    {
        return ($this->_checkKz07_1($record) === true && ($record['gepl_neoadj_chemoth'] == '1' || $record['gepl_adj_chemoth'] == '1'));
    }


    /**
     * _checkKz09_1
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz09_1(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            (
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pt14_And_Pn0Pnsn0_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_Or_NoPrimaryOp_Or_Pt0_With_Ct14_And_Cn0_And_Cm0($record) === true ||
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pn13Pnsn13_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_OR_NoPrimarOp_Or_Pt0_With_Cn13_And_M0($record) === true
            ) &&
            $record['rezeptorbefund'] === 'p'
        );

        return $condition;
    }


    /**
     * _checkKz09_2
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz09_2(array $record)
    {
        return ($this->_checkKz09_1($record) === true && $record['gepl_antih_th'] == '1');
    }


    /**
     * _checkKz10_1
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz10_1(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            (
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pt14_And_Pn0Pnsn0_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_Or_NoPrimaryOp_Or_Pt0_With_Ct14_And_Cn0_And_Cm0($record) === true ||
                $this->_checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pn13Pnsn13_And_M0($record) === true ||
                $this->_checkPrimaryOp_And_NeoadjuvantTherapy_OR_NoPrimarOp_Or_Pt0_With_Cn13_And_M0($record) === true
            ) &&
            $record['her_2neu'] === 'p'
        );

        return $condition;
    }


    /**
     * _checkKz10_2
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz10_2(array $record)
    {
        return ($this->_checkKz10_1($record) === true && $record['gepl_immun_th_trastuzumab'] == '1');
    }


    /**
     * _checkKz15
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz15(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            $this->_hasPrimaryOp($record) === true &&
            ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true)
        );

        return $condition;
    }


    /**
     * _checkKz21
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz21(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            $this->_hasNeoadjuvantTherapy($record) === false &&
            (
                $this->_checkPrimaryOp_And_Pt14_And_Pn0Pnsn0_And_M0($record) === true ||
                $this->_checkNoPrimaryOp_OR_Pt0_With_Ct14_And_Cn0_And_Cm0($record) === true ||
                $this->_checkNoPrimaryOp_OR_Pt0_With_Cn13_And_M0($record) === true ||
                $this->_hasM1($record) === true
            ) &&
            $this->_hasPn0OrPnsn0($record) === true
        );

        return $condition;
    }


    /**
     * _checkKz24
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz24(array $record)
    {
        $condition = (
            $this->_isPrimaryCase($record) === true &&
            $this->_hasPrimaryOp($record) === true &&
            ($this->_hasBet($record) === true || $this->_hasMastektomie($record) === true)
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_Pt14_And_Pn0Pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkPrimaryOp_And_Pt14_And_Pn0Pnsn0_And_M0(array $record)
    {
        $condition = (
            $this->_hasPrimaryOp($record) === true &&
            $this->_hasPt14($record) === true &&
            $this->_hasPn0OrPnsn0($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _checkNoPrimaryOp_OR_Pt0_With_Ct14_And_Cn0_And_Cm0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkNoPrimaryOp_OR_Pt0_With_Ct14_And_Cn0_And_Cm0(array $record)
    {
        $condition = (
            ($this->_hasPrimaryOp($record) === false || $this->_hasPt0($record) === true) &&
            str_contains($record['ct'], array('T1','T2','T3','T4')) === true &&
            $this->_hasCn0($record) === true &&
            $this->_hasCm0($record) === true
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_Pt14_And_Pn0Pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkNoPrimaryOp_OR_Pt0_With_Cn13_And_M0(array $record)
    {
        $condition = (
            ($this->_hasPrimaryOp($record) === false || $this->_hasPt0($record) === true) &&
            $this->_hasCn13($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pt14_And_Pn0Pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pt14_And_Pn0Pnsn0_And_M0(array $record)
    {
        $condition = (
            $this->_hasPrimaryOp($record) === true &&
            $this->_hasNeoadjuvantTherapy($record) === false &&
            $this->_hasPt14($record) === true &&
            $this->_hasPn0OrPnsn0($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_NeoadjuvantTherapy_Or_NoPrimaryOp_Or_Pt0_With_Ct14_And_Cn0_And_Cm0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkPrimaryOp_And_NeoadjuvantTherapy_Or_NoPrimaryOp_Or_Pt0_With_Ct14_And_Cn0_And_Cm0(array $record)
    {
        $condition = (
            (
                ($this->_hasPrimaryOp($record) === true && $this->_hasNeoadjuvantTherapy($record) === true) ||
                $this->_hasPrimaryOp($record) === false ||
                $this->_hasPt0($record) === true
            ) &&
            str_contains($record['ct'], array('T1','T2','T3','T4')) === true &&
            $this->_hasCn0($record) === true &&
            $this->_hasCm0($record) === true
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pn13Pnsn13_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkPrimaryOp_And_NoNeoadjuvantTherapy_And_Pn13Pnsn13_And_M0(array $record)
    {
        $condition = (
            $this->_hasPrimaryOp($record) === true &&
            $this->_hasNeoadjuvantTherapy($record) === false &&
            $this->_hasPn13Psn13($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _checkPrimaryOp_And_NeoadjuvantTherapy_OR_NoPrimarOp_Or_Pt0_With_Cn13_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkPrimaryOp_And_NeoadjuvantTherapy_OR_NoPrimarOp_Or_Pt0_With_Cn13_And_M0(array $record)
    {
        $condition = (
            (
                ($this->_hasPrimaryOp($record) === true && $this->_hasNeoadjuvantTherapy($record) === true) ||
                $this->_hasPrimaryOp($record) === false ||
                $this->_hasPt0($record) === true
            ) &&
            $this->_hasCn13($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _hasNoNeoadjuvantTherapy_And_Pt14_And_Pn0pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasNoNeoadjuvantTherapy_And_Pt14_And_Pn0pnsn0_And_M0(array $record)
    {
        $condition = (
            $this->_hasNeoadjuvantTherapy($record) === false &&
            str_contains($record['pt'], array('T1', 'T2', 'T3', 'T4')) === true &&
            $this->_hasPn0OrPnsn0($record) === true &&
            $this->_hasM0($record)
        );

        return $condition;
    }


    /**
     * _hasNoNeoadjuvantTherapy_And_Pt14_And_Pn0pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasNeoadjuvantTherapy_And_Ct14_And_Cn0_And_Cm0(array $record)
    {
        $condition = (
            $this->_hasNeoadjuvantTherapy($record) === true &&
            str_contains($record['ct'], array('T1', 'T2', 'T3', 'T4')) === true &&
            $this->_hasCn0($record) === true &&
            $this->_hasCm0($record) === true
        );

        return $condition;
    }


    /**
     * _hasNoNeoadjuvantTherapy_And_Pn13pnsn13_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasNoNeoadjuvantTherapy_And_Pn13pnsn13_And_M0(array $record)
    {
        $condition = (
            $this->_hasNeoadjuvantTherapy($record) === false &&
            $this->_hasPn13Psn13($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }


    /**
     * _hasNoNeoadjuvantTherapy_And_Pt14_And_Pn0pnsn0_And_M0
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _hasNeoadjuvantTherapy_And_Cn13_And_M0(array $record)
    {
        $condition = (
            $this->_hasNeoadjuvantTherapy($record) === true &&
            $this->_hasCnPlus13($record) === true &&
            $this->_hasM0($record) === true
        );

        return $condition;
    }

}

?>
