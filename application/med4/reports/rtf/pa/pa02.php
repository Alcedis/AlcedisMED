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

/**
 * Class reportContentPa02
 */
class reportContentPa02 extends reportExtensionPa
{
    /**
     * _kzCache
     *
     * @access  protected
     * @var     array
     */
    protected $_kzCache = array(
        '04' => array(),
        '05' => array()
    );


    /**
     * initDataArray
     *
     * @access  protected
     * @param   bool $showP
     * @return  array
     */
    protected function initDataArray($showP = true)
    {
        $data = array();

        $n = array(2,3,4,5,6,10,11,12,13,14,15,16,17,18);

        // special
        $c = array('7a','7b');

        // counts
        $a = array(1,8,9);

        foreach ($n as $nDigit) {
            $nDigitS = strlen($nDigit) == 1 ? "0$nDigit" : $nDigit;

            foreach (array('n','z','p') as $e) {
                if ($e == 'p' && $showP === false) {
                    continue;
                }

                $data["kz_{$nDigitS}_{$e}"] = 0;
            }
        }

        foreach ($c as $nDigit) {
            $nDigitS = "0$nDigit";

            foreach (array('n','z','p') as $e) {
                if ($e == 'p' && $showP === false) {
                    continue;
                }

                $data["kz_{$nDigitS}_{$e}"] = 0;
            }
        }

        foreach ($a as $aDigit) {
            $aDigitS = strlen($aDigit) == 1 ? "0$aDigit" : $aDigit;
            $data["kz_{$aDigitS}_a"] = 0;
        }

        ksort($data);

        return $data;
    }


    /**
     * _initPa06DataArray
     *
     * @access  protected
     * @param   array   $datasets
     * @return  array
     */
    protected function _initPa06DataArray($datasets)
    {
        $data = array();

        $dataArray = $this->initDataArray(false);

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
     * generate pkz02
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $this->setTemplate('pa02');

        $pa06 = array();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

        $data = $this->initDataArray();

        $result = $this->loadRessource('pa01_1');

        //Init pa06
        if ($this->_params['name'] == 'pa06' && count($result) > 0) {
            $pa06 = $this->_initPa06DataArray($result);
        }

        $kz07Ukey = array(
            'kz_07a_n' => array(),
            'kz_07a_z' => array(),
            'kz_07b_n' => array(),
            'kz_07b_z' => array()
        );

        foreach ($result as $i => $record) {
            $year = date('Y', strtotime($record['bezugsdatum']));

            // Kennzahl 06
            //studien zählen (unabhängig vom patientenbezugsjahr)
            if (strlen($record['datum_studie']) > 0) {
                 foreach (explode(', ', $record['datum_studie']) as $studiendatum) {
                     $studienYear = date('Y', strtotime($studiendatum));
                     if ($studienYear == $bezugsjahr) {
                         $data['kz_06_z']++;
                         $pa06[$i]['kz_06_z'] = isset($pa06[$i]['kz_06_z']) === true ? $pa06[$i]['kz_06_z'] + 1 : 1;
                     }
                 }
            }

            if ($year == $bezugsjahr) {

                // Kennzahl 1
                if ($this->check_KZ01($record) === true) {
                    $pa06[$i]['kz_01_a'] = 1;
                    $data['kz_01_a'] ++;
                }

                // Kennzahl 2
                if ($this->check_KZ02($record) === true) {
                    $data['kz_02_n'] ++;
                    $pa06[$i]['kz_02_n'] = 1;

                    $tumorkonf_praeop = $record['tumorkonf_praeop'];

                    $data['kz_02_z'] += (int) $tumorkonf_praeop;
                    $pa06[$i]['kz_02_z'] = (int) $tumorkonf_praeop;
                }

                // Kennzahl 3
                if ($this->check_KZ03($record) === true) {
                    $data['kz_03_n'] ++;
                    $pa06[$i]['kz_03_n'] = 1;

                    $tumorkonfPostop = $record['tumorkonf_postop'];

                    $data['kz_03_z'] += (int) $tumorkonfPostop;
                    $pa06[$i]['kz_03_z'] = (int) $tumorkonfPostop;
                }

                // Kennzahl 4
                if ($this->check_KZ04($record) === true) {
                    $data['kz_04_n'] ++;
                    $pa06[$i]['kz_04_n'] = 1;

                    $psychoonk = $record['psychoonk'];

                    $data['kz_04_z'] += (int) $psychoonk;
                    $pa06[$i]['kz_04_z'] = (int) $psychoonk;
                }

                // Kennzahl 5
                if ($this->check_KZ05($record) === true) {
                    $data['kz_05_n'] ++;
                    $pa06[$i]['kz_05_n'] = 1;

                    $sozialdienst = $record['sozialdienst'];

                    $data['kz_05_z'] += (int) $sozialdienst;
                    $pa06[$i]['kz_05_z'] = (int) $sozialdienst;
                }

                // Kennzahl 6
                if ($this->check_KZ06($record) === true) {
                    $data['kz_06_n'] ++;
                    $pa06[$i]['kz_06_n'] = 1;
                }

                $patientId = $record['patient_id'];

                // Kennzahl 7a Nenner
                if ($this->check_KZ07a_N($record, $kz07Ukey) === true) {
                    $data['kz_07a_n'] ++;
                    $pa06[$i]['kz_07a_n'] = 1;

                    $kz07Ukey['kz_07a_n'][$patientId] = 1;
                }

                // Kennzahl 7a Zähler
                if ($this->check_KZ07a_Z($record, $kz07Ukey) === true) {
                    $data['kz_07a_z'] ++;
                    $pa06[$i]['kz_07a_z'] = 1;

                    $kz07Ukey['kz_07a_z'][$patientId] = 1;
                }

                // Kennzahl 7b Nenner
                if ($this->check_KZ07b_N($record, $kz07Ukey) === true) {
                   $data['kz_07b_n'] ++;
                   $pa06[$i]['kz_07b_n'] = 1;

                   $kz07Ukey['kz_07b_n'][$patientId] = 1;
                }

                // Kennzahl 7b Zähler
                if ($this->check_KZ07b_Z($record, $kz07Ukey) === true) {
                   $data['kz_07b_z'] ++;
                   $pa06[$i]['kz_07b_z'] = 1;

                   $kz07Ukey['kz_07b_z'][$patientId] = 1;
                }

                // Kennzahl 8 Anzahl
                $kz08 = $this->check_KZ08($record);

                $data['kz_08_a']    += (int) $kz08;
                $pa06[$i]['kz_08_a'] = (int) $kz08;

                // Kennzahl 13
                if ($this->check_KZ13($record) === true) {
                    $data['kz_13_n'] ++;
                    $pa06[$i]['kz_13_n'] = 1;

                    $rLokal = $record['r_lokal'];

                    $data['kz_13_z'] += (int) ($rLokal == '0');
                    $pa06[$i]['kz_13_z'] = (int) ($rLokal == '0');
                }

                // Kennzahl 14
                if ($this->check_KZ14($record) === true) {
                    $data['kz_14_n'] ++;
                    $pa06[$i]['kz_14_n'] = 1;

                    $lymphnodes = ((int) $record['lk_untersucht'] >= 10);

                    $data['kz_14_z'] += (int) $lymphnodes;
                    $pa06[$i]['kz_14_z'] = (int) $lymphnodes;
                }

                // Kennzahl 15
                if ($this->check_KZ15($record) === true) {
                    $data['kz_15_n'] ++;
                    $pa06[$i]['kz_15_n'] = 1;

                    $pathoAufarbeitung = ($record['patho_aufarbeitung'] == 1);

                    $data['kz_15_z'] += (int) $pathoAufarbeitung;
                    $pa06[$i]['kz_15_z'] = (int) $pathoAufarbeitung;
                }

                // Kennzahl 16
                if ($this->check_KZ16($record) === true) {
                    $data['kz_16_n'] += $record['patho_befund'];
                    $pa06[$i]['kz_16_n'] = $record['patho_befund'];

                    $pathoVollstaendig = $record['patho_befund_vollstaendig'];

                    $data['kz_16_z'] += (int) $pathoVollstaendig;
                    $pa06[$i]['kz_16_z'] = (int) $pathoVollstaendig;
                }

                // Kennzahl 17
                if ($this->check_KZ17($record) === true) {
                    $data['kz_17_n'] ++;
                    $pa06[$i]['kz_17_n'] = 1;

                    $adjChemoGem = ($record['adjuvante_chemo_gem'] == 1);

                    $data['kz_17_z'] += (int) $adjChemoGem;
                    $pa06[$i]['kz_17_z'] = (int) $adjChemoGem;
                }

                // Kennzahl 18
                if ($this->check_KZ18($record) === true) {
                    $data['kz_18_n'] ++;
                    $pa06[$i]['kz_18_n'] = 1;

                    $palliChemo = ($record['palli_chemo'] == 1);

                    $data['kz_18_z'] += (int) $palliChemo;
                    $pa06[$i]['kz_18_z'] = (int) $palliChemo;
                }
            }

            //pa06 nicht zaehlbar
            $pa06[$i]['kz_09_a'] = '-';
            $pa06[$i]['kz_10_n'] = '-';
            $pa06[$i]['kz_10_z'] = '-';
            $pa06[$i]['kz_11_n'] = '-';
            $pa06[$i]['kz_11_z'] = '-';
            $pa06[$i]['kz_12_n'] = '-';
            $pa06[$i]['kz_12_z'] = '-';
        }

        $result = $this->loadRessource('pa01_2');

        $kz12z = array();

        foreach ($result as $record) {
            $year = date('Y', strtotime($record['op_datum']));

            if ($year != $bezugsjahr || $record['org_id'] != $this->_params['org_id']){
                continue;
            }

            // Kennzahl 9
            $data['kz_09_a']++;

            // Kennzahl 10
            $data['kz_10_n']++;
            $data['kz_10_z'] += (int) $record['op_revision'];

            // Kennzahl 11
            $data['kz_11_n']++;
            $data['kz_11_z'] += (int) $record['wundinfektion30tage'];


            // Kennzahl 12
            $data['kz_12_n']++;

            $tod30tage = $record['tod30tage'];

            if (strlen($tod30tage) > 0) {
                $kz12z[$tod30tage] = 1;
            }
        }

        $data['kz_12_z'] += count($kz12z);

        foreach ($data as $kzName => &$calcPr) {
            if (strpos($kzName, '_p') !== false) {
                $nenner  = $data[str_replace('_p', '_n', $kzName)];
                $zaehler = $data[str_replace('_p', '_z', $kzName)];
                $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
            }
        }

        if ($this->_params['name'] == 'pa06') {
            $config = $this->loadConfigs('pa06');
            $this->_title = $config['head_report'];

            $this->_data  = $pa06;
            $this->writeXLS();
        } else {
            $data['bezugsjahr'] = "(Bezugsjahr $bezugsjahr)";
            $this->_data = $data;
            $this->writePDF(true);
        }
    }


    /**
     * check_KZ01
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ01($record)
    {
        return $this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record);
    }


    /**
     * check_KZ02
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ02($record)
    {
        return $this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record);
    }


    /**
     * check_KZ03
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ03($record)
    {
        return ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) && strlen($record['primaeroperation']) > 0);
    }


    /**
     * check_KZ04
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ04($record)
    {
        $condition = $this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record);

        if ($condition === false) {
            $condition = $this->_kz0405rezcheck($record, '04');
        }

        return $condition;
    }


    /**
     * _kz0405rezcheck
     *
     * @access  protected
     * @param   array $record
     * @param   string $type
     * @return  bool
     */
    protected function _kz0405rezcheck($record, $type)
    {
        $condition = false;
        $case      = substr($record['anlass'], 0, 1);
        $diseaseId = $record['erkrankung_id'];

        if ($case === 'r' && array_key_exists($diseaseId, $this->_kzCache[$type]) === false) {
            $this->_kzCache[$type][$diseaseId] = true;

            $condition = true;
        }

        return $condition;
    }


    /**
     * check_KZ05
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ05($record)
    {
        $condition = $this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record);

        if ($condition === false) {
            $condition = $this->_kz0405rezcheck($record, '05');
        }

        return $condition;
    }


    /**
     * check_KZ06
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ06($record)
    {
        return $this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record);
    }


    /**
     * check_KZ07a_N
     *
     * @access  public
     * @param   array   $record
     * @param   array   $kz07Ukey
     * @return  bool
     */
    public function check_KZ07a_N($record, $kz07Ukey)
    {
        $patientId = $record['patient_id'];

        return ((int) $record['ercp'] > 0 && array_key_exists($patientId, $kz07Ukey['kz_07a_n']) === false);
    }


    /**
     * check_KZ07a_Z
     *
     * @access  public
     * @param   array   $record
     * @param   array   $kz07Ukey
     * @return  bool
     */
    public function check_KZ07a_Z($record, $kz07Ukey)
    {
        $patientId = $record['patient_id'];

        $condition = (
            (int) $record['ercp'] > 0 &&
            $record['pankreatitis'] == 1 &&
            array_key_exists($patientId, $kz07Ukey['kz_07a_z']) === false
        );

        return $condition;
    }


    /**
     * check_KZ07b_N
     *
     * @access  public
     * @param   array   $record
     * @param   array   $kz07Ukey
     * @return  bool
     */
    public function check_KZ07b_N($record, $kz07Ukey)
    {
        $patientId = $record['patient_id'];

        return ((int) $record['ercp'] > 0 && array_key_exists($patientId, $kz07Ukey['kz_07b_n']) === false);
    }


    /**
     * check_KZ07b_Z
     *
     * @access  public
     * @param   array   $record
     * @param   array   $kz07Ukey
     * @return  bool
     */
    public function check_KZ07b_Z($record, $kz07Ukey)
    {
        $patientId = $record['patient_id'];

        $condition = (
            (int) $record['ercp'] > 0 &&
            $record['blutung'] == 1 &&
            array_key_exists($patientId, $kz07Ukey['kz_07b_z']) === false
        );

        return $condition;
    }


    /**
     * check_KZ08
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ08($record)
    {
        return ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) && strlen($record['primaeroperation']) > 0);
    }


    /**
     * check_KZ13
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ13($record)
    {
        return ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) && strlen($record['primaeroperation']) > 0);
    }


    /**
     * check_KZ14
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ14($record)
    {
        $condition = ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) &&
            strlen($record['primaeroperation']) > 0 &&
            $record['lymphadenektomie'] == 1
        );

        return $condition;
    }


    /**
     * check_KZ15
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ15($record)
    {
        $condition = ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) &&
            strlen($record['primaeroperation']) > 0
        );

        return $condition;
    }


    /**
     * check_KZ16
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ16($record)
    {
        $condition = ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record) && strlen($record['primaeroperation']) > 0);

        return $condition;
    }


    /**
     * check_KZ17
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ17($record)
    {
        $condition = ($this->check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III($record) &&
            strlen($record['primaeroperation']) > 0 &&
            $record['r'] == '0'
        );

        return $condition;
    }


    /**
     * check_KZ18
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_KZ18($record)
    {
        $cond1 = (str_starts_with($this->getUICC($record), 'III') === true && $record['palli_situation'] == '1');

        $condition = ($record['primaerfall'] == 1 &&
            ($cond1 === true || str_starts_with($this->getUICC($record), 'IV') === true) &&
            in_array($record['ecog'], array('0', '1', '2')) === true
        );

        return $condition;
    }


    /**
     * check if case is primary and uicc is IA, IB, IIB, III, IV
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_PrimaryCase_And_Uicc_IA_IB_IIB_III_IV($record)
    {
        return ($record['primaerfall'] == 1 && $this->check_UICC_IA_IB_IIB_III_IV($record) === true);
    }


    /**
     * check if case is primary and uicc is IA, IB, IIA, IIB, III, IV
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III_IV($record)
    {
        return ($record['primaerfall'] == 1 && $this->check_UICC_IA_IB_IIA_IIB_III_IV($record) === true);
    }


    /**
     * check if case is primary and uicc is IA, IB, IIB, III
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_PrimaryCase_And_Uicc_IA_IB_IIB_III($record)
    {
        return ($record['primaerfall'] == 1 && $this->check_UICC_IA_IB_IIB_III($record) === true);
    }


    /**
     * check if case is primary and uicc is IA, IB, IIA, IIB, III
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function check_PrimaryCase_And_Uicc_IA_IB_IIA_IIB_III($record)
    {
        return ($record['primaerfall'] == 1 && $this->check_UICC_IA_IB_IIA_IIB_III($record) === true);
    }


    /**
     * check if UICC is
     * IA*, IB*, IIB*, III*, IV*
     *
     * @access  public
     * @param   array  $record
     * @return  bool
     */
    public function check_UICC_IA_IB_IIB_III_IV($record)
    {
        return (str_starts_with($this->getUICC($record), array('IA', 'IB', 'IIB', 'III', 'IV')));
    }


    /**
     * check if UICC is
     * IA*, IB*, IIB*, III*, IV*
     *
     * @access  public
     * @param   array  $record
     * @return  bool
     */
    public function check_UICC_IA_IB_IIA_IIB_III_IV($record)
    {
        return (str_starts_with($this->getUICC($record), array('IA', 'IB', 'IIA', 'IIB', 'III', 'IV')));
    }


    /**
     * check if UICC is
     * IA*, IB*, IIB*, III*
     *
     * @access  public
     * @param  array  $record
     * @return  bool
     */
    public function check_UICC_IA_IB_IIB_III($record)
    {
        return (str_starts_with($this->getUICC($record), array('IA', 'IB', 'IIB', 'III')));
    }


    /**
     * check if UICC is
     * IA*, IB*, IIA*, IIB*, III*
     *
     * @access  public
     * @param  array  $record
     * @return  bool
     */
    public function check_UICC_IA_IB_IIA_IIB_III($record)
    {
        return (str_starts_with($this->getUICC($record), array('IA', 'IB', 'IIA', 'IIB', 'III')));
    }


    /**
     * getUICC
     *
     * @access  public
     * @param   array   $record
     * @return  string
     */
    public function getUICC($record)
    {
        return ((int) $record['uicc_nach_neoadj_th'] == 1 ? $record['uicc_prae'] : $record['uicc']);
    }
}

?>
