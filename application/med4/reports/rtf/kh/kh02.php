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

class reportContentKh02 extends reportExtensionKh
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
        $n = range(2, 15);
        //anzahl
        $a = array(1);

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

        ksort($data);

        return $data;
    }


    /**
     * _initKh06DataArray
     *
     * @access  protected
     * @param   array   $datasets
     * @return  array
     */
    protected function _initKh06DataArray($datasets)
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
        $this->setTemplate('kh02');

        $kh06 = array();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

        $data = $this->_initDataArray();

        $additionalContent['condition'] = "({$bezugsjahr} = YEAR(bezugsdatum) OR LOCATE('$bezugsjahr', datum_studie) != 0)";

        $additionalContent['fields'] = array("
            GREATEST(
                MAX(IF(sys.vorlage_therapie_art IN ('st', 'cst'), sys.beginn, 0)),
                MAX(IF(str.vorlage_therapie_art IN ('st', 'cst'), str.beginn, 0))
            ) AS greatest_st_cst_therapy_date"
        );

        $records = $this->loadRessource('kh01', $additionalContent);

        //Init kh06
        if (str_starts_with($this->_params['name'], 'kh06') === true && count($records) > 0) {
            $kh06 = $this->_initkh06DataArray($records);
        }

        $kzCache = array(
            'kz03'     => array('n' => array(), 'z' => array()),
            'kh06kz03' => array('n' => array(), 'z' => array()),
            'kz04'     => array('n' => array(), 'z' => array()),
            'kh06kz04' => array('n' => array(), 'z' => array())
        );

        foreach ($records as $i => $record) {
            $diseaseId = $record['erkrankung_id'];
            $date      = $record['bezugsdatum'];

            $jahr = date('Y', strtotime($date));

            $studyDate = $record['datum_studie'];

            if (strlen($studyDate) > 0) {
                foreach (explode(', ', $studyDate) as $studiendatum) {
                    $studienYear = date('Y', strtotime($studiendatum));

                    if ($studienYear == $bezugsjahr) {
                        $data['kz_05_z']++;
                        $kh06[$i]['kz_05_z'] = isset($kh06[$i]['kz_05_z']) === true ? $kh06[$i]['kz_05_z'] + 1 : 1;
                    }
                }
            }
//
            if ($jahr != $bezugsjahr){
                continue;
            }

            if ($this->checkKz01($record) === true) {
                $data['kz_01_a'] ++;
                $kh06[$i]['kz_01_a'] = 1;
            }

            // KZ02
            if ($this->checkKz02($record) === true) {
                $data['kz_02_n'] ++;
                $kh06[$i]['kz_02_n'] = 1;

                $praeopConference = (int) $record['praeop_tumorkonf'];

                $data['kz_02_z'] += $praeopConference;
                $kh06[$i]['kz_02_z'] = $praeopConference;
            }

            // KZ03 - preprocess
            if ($this->checkKz03($record) === true) {
                $case = substr($record['anlass'], 0, 1);

                if (in_array($case, array('p', 'r')) === true) {
                    $kzCache['kz03']['n'][$diseaseId][] = $case;

                    $kzCache['kh06kz03']['n'][$diseaseId][$case][$date] = $i;

                    $psycho = (int) $record['psychoonk_betreuung'];

                    if ($case === 'p') {
                        $data['kz_03_z'] += $psycho;
                        $kh06[$i]['kz_03_z'] = $psycho;
                    } else if ($case === 'r') {
                        $kzCache['kz03']['z'][$diseaseId][$date] = $psycho;
                        $kzCache['kh06kz03']['z'][$diseaseId][$case][$date] = array(
                            'index'  => $i,
                            'psycho' => $psycho
                        );
                    }
                }
            }

            // KZ04 - preprocess
            if ($this->checkKz04($record) === true) {
                $case = substr($record['anlass'], 0, 1);

                if (in_array($case, array('p', 'r')) === true) {
                    $kzCache['kz04']['n'][$diseaseId][] = $case;

                    $kzCache['kh06kz04']['n'][$diseaseId][$case][$date] = $i;

                    $social = (int) $record['betreuungsozialdienst'];

                    if ($case === 'p') {
                        $data['kz_04_z'] += $social;
                        $kh06[$i]['kz_04_z'] = $social;
                    } else if ($case === 'r') {
                        $kzCache['kz04']['z'][$diseaseId][$date] = $social;
                        $kzCache['kh06kz04']['z'][$diseaseId][$case][$date] = array(
                            'index'  => $i,
                            'social' => $social
                        );
                    }
                }
            }

            // KZ05
            if ($this->checkKz05($record) === true) {
                $data['kz_05_n'] ++;
                $kh06[$i]['kz_05_n'] = 1;
            }

            // KZ06
            if ($this->checkKz06($record) === true) {
                $data['kz_06_n'] ++;
                $kh06[$i]['kz_06_n'] = 1;

                $revisionOp = (int) $record['revisions_op'];

                if ($revisionOp === 1) {
                    $data['kz_06_z'] ++;
                    $kh06[$i]['kz_06_z'] = 1;
                }
            }

            // KZ07
            if ($this->checkKz07($record) === true) {
                $data['kz_07_n'] ++;
                $kh06[$i]['kz_07_n'] = 1;

                $hno = (int) $record['hno_untersuchung'];

                if ($hno === 1) {
                    $data['kz_07_z'] ++;
                    $kh06[$i]['kz_07_z'] = 1;
                }
            }

            // KZ08
            if ($this->checkKz08($record) === true) {
                $data['kz_08_n'] ++;
                $kh06[$i]['kz_08_n'] = 1;

                $nBest = (int) $record['n_bestimmung'];

                if ($nBest === 1) {
                    $data['kz_08_z'] ++;
                    $kh06[$i]['kz_08_z'] = 1;
                }
            }

            // KZ09
            if ($this->checkKz09($record) === true) {
                $data['kz_09_n'] ++;
                $kh06[$i]['kz_09_n'] = 1;

                $thorax = (int) $record['thorax_ct'];

                if ($thorax === 1) {
                    $data['kz_09_z'] ++;
                    $kh06[$i]['kz_09_z'] = 1;
                }
            }

            // KZ10
            if ($this->checkKz10($record) === true) {
                $data['kz_10_n'] ++;
                $kh06[$i]['kz_10_n'] = 1;

                $complete = (int) $record['histologisch_vollst'];

                if ($complete === 1) {
                    $data['kz_10_z'] ++;
                    $kh06[$i]['kz_10_z'] = 1;
                }
            }

            // KZ11
            if ($this->checkKz11($record) === true) {
                $data['kz_11_n'] ++;
                $kh06[$i]['kz_11_n'] = 1;

                $praeopConference = (int) $record['praeop_tumorkonf'];
                $postopConference = (int) $record['postop_tumorkonf'];

                if ($praeopConference === 1 || $postopConference === 1) {
                    $data['kz_11_z'] ++;
                    $kh06[$i]['kz_11_z'] = 1;
                }
            }

            // KZ12
            if ($this->checkKz12($record) === true) {
                $data['kz_12_n'] ++;
                $kh06[$i]['kz_12_n'] = 1;

                $neck = (int) $record['neck_dissection'];

                if ($neck === 1) {
                    $data['kz_12_z'] ++;
                    $kh06[$i]['kz_12_z'] = 1;
                }
            }

            // KZ13
            if ($this->checkKz13($record) === true) {
                $data['kz_13_n'] ++;
                $kh06[$i]['kz_13_n'] = 1;

                $aborted = (int) $record['strahlen_unterbrochen'];

                if ($aborted === 0) {
                    $data['kz_13_z'] ++;
                    $kh06[$i]['kz_13_z'] = 1;
                }
            }

            // KZ14
            if ($this->checkKz14($record) === true) {
                $data['kz_14_n'] ++;
                $kh06[$i]['kz_14_n'] = 1;

                $strChe = (int) $record['str_che_durchgef'];
                $str    = (int) $record['strahlen_durchgef'];

                $primaryOpDate = $record['datumprimaer_op'];
                $greatestTherapyDate = $record['greatest_st_cst_therapy_date'];

                if (($strChe === 1 || $str === 1) && $greatestTherapyDate > $primaryOpDate) {
                    $data['kz_14_z'] ++;
                    $kh06[$i]['kz_14_z'] = 1;
                }
            }

            // KZ15
            if ($this->checkKz15($record) === true) {
                $data['kz_15_n'] ++;
                $kh06[$i]['kz_15_n'] = 1;

                $tooth = (int) $record['zahnarzt'];

                if ($tooth === 1) {
                    $data['kz_15_z'] ++;
                    $kh06[$i]['kz_15_z'] = 1;
                }
            }
        }

        // Kz03 postprocess
        foreach ($kzCache['kz03']['n'] as $disease) {
            $data['kz_03_n'] += count(array_unique($disease));
        }

        foreach ($kzCache['kh06kz03']['n'] as $disease) {
            foreach ($disease as $case) {
                ksort($case);
                $kh06[reset($case)]['kz_03_n'] = 1;
            }
        }

        foreach ($kzCache['kz03']['z'] as $rez) {
            ksort($rez);
            $data['kz_03_z'] += reset($rez);
        }

        foreach ($kzCache['kh06kz03']['z'] as $disease) {
            foreach ($disease as $case) {
                ksort($case);

                $earliestTs = reset($case);

                if ($earliestTs['psycho'] === 1) {
                    $kh06[$earliestTs['index']]['kz_03_z'] = 1;
                }
            }
        }

        // Kz04 postprocess
        foreach ($kzCache['kz04']['n'] as $disease) {
            $data['kz_04_n'] += count(array_unique($disease));
        }

        foreach ($kzCache['kh06kz04']['n'] as $disease) {
            foreach ($disease as $case) {
                ksort($case);
                $kh06[reset($case)]['kz_04_n'] = 1;
            }
        }

        foreach ($kzCache['kz04']['z'] as $rez) {
            ksort($rez);
            $data['kz_04_z'] += reset($rez);
        }

        foreach ($kzCache['kh06kz04']['z'] as $disease) {
            foreach ($disease as $case) {
                ksort($case);

                $earliestTs = reset($case);

                if ($earliestTs['social'] === 1) {
                    $kh06[$earliestTs['index']]['kz_04_z'] = 1;
                }
            }
        }

        foreach ($data as $kzName => &$calcPr) {
            if (strpos($kzName, '_p') !== false) {
                $nenner  = $data[str_replace('_p', '_n', $kzName)];
                $zaehler = $data[str_replace('_p', '_z', $kzName)];
                $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
            }
        }

        if (str_starts_with($this->_params['name'], 'kh06') === true) {
            $config = $this->loadConfigs('kh06');
            $this->_title = $config['head_report'];

            $this->_data  = $kh06;
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
        $condition =
            ($this->ckhz_mundhoehle_op($record) === true ||
            $this->ckhz_mundhoehle_nop($record) === true ||
            $this->ckhz_sonst_op($record) === true ||
            $this->ckhz_sonst_nop($record) === true)
            &&
            $this->_checkUicc($record['uicc']) === true
        ;

        return $condition;
    }


    /**
     * checkUicc_III_IVA_IVB_IVC
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkUicc_III_IVA_IVB_IVC($record)
    {
        return in_array($record['uicc'], array('III', 'IVA', 'IVB', 'IVC'));
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
        return $this->checkKz01($record);
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
        return $this->checkKz01($record) === true || str_starts_with($record['anlass'], 'r');
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
        return $this->checkKz01($record) === true || str_starts_with($record['anlass'], 'r');
    }


    /**
     * checkKz05
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz05($record)
    {
        return $this->checkKz01($record) === true;
    }


    /**
     * checkKz06
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz06($record)
    {
        $condition = ($this->ckhz_mundhoehle_op($record) === true || $this->ckhz_sonst_op($record) === true)
            &&
            $this->_checkUicc($record['uicc']) === true
        ;

        return $condition;
    }


    /**
     * checkKz07
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz07($record)
    {
        $condition = ($this->ckhz_mundhoehle_op($record) === true || $this->ckhz_mundhoehle_nop($record) === true)
            &&
            $this->_checkUicc($record['uicc']) === true
        ;

        return $condition;
    }


    /**
     * checkKz08
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz08($record)
    {
        return $this->checkKz07($record);
    }


    /**
     * checkKz09
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz09($record)
    {
        $condition = ($this->ckhz_mundhoehle_op($record) === true || $this->ckhz_mundhoehle_nop($record) === true) &&
            $this->checkUicc_III_IVA_IVB_IVC($record) === true
        ;

        return $condition;
    }


    /**
     * checkKz10
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz10($record)
    {
        return $this->ckhz_mundhoehle_op($record) === true && $this->_checkUicc($record['uicc']) === true;
    }


    /**
     * checkKz11
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz11($record)
    {
        return $this->checkKz07($record);
    }


    /**
     * checkKz12
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz12($record)
    {
        return $this->checkKz07($record) === true && $record['cn'] === 'cN0';
    }


    /**
     * checkKz13
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz13($record)
    {
        return $this->checkKz07($record) === true && (int) $record['strahlen_durchgef'] === 1;
    }


    /**
     * checkKz14
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz14($record)
    {
        $pt = $record['pt'];
        $pn = $record['pn'];
        $rr = $record['resektionsrand'];

        $condition =
            $this->ckhz_mundhoehle_op($record) === true &&
            $this->_checkUicc($record['uicc']) === true &&
            ($pt === 'pT3' || str_starts_with($pt, 'pT4') === true) &&
            (strlen($rr) > 0 && (int) $rr <= 3) &&
            (
                (in_array($record['ppn'], array('1', '2')) === true) ||
                $record['l'] === '1' ||
                in_array($record['v'], array('1', '2')) === true ||
                (in_array($pn, array('pN1', 'pN3')) === true || str_starts_with($pn, 'pN2') === true)
            )
        ;

        return $condition;
    }


    /**
     * checkKz15
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkKz15($record)
    {
        return $this->checkKz07($record) === true && (int) $record['str_che_durchgef'] === 1;
    }
}

?>
