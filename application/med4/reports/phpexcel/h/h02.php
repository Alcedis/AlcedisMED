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

class reportContentH02 extends reportExtensionH
{
    /**
     * _positions
     *
     * @access  protected
     * @var     array
     */
    protected $_positions = array(
        'H9'  => 'kz_1_1_a',
        'H10' => 'kz_1_2_a',
        'H11' => 'kz_1_3_a',
        'D6'  => 'bezugsjahr'
    );


    /**
     * _initDataArray
     * ()
     *
     * @access  p
     * @param bool $cPosition
     * @return  array
     */
    protected function _initDataArray($cPosition = true)
    {
        $data = array(
            'kz_1_1_a' => 0,
            'kz_1_2_a' => 0,
            'kz_1_3_a' => 0
        );

        //normale zahlen (zähler, nenner, prozent)
        $n = array(2,3,4,5,6,7,10,11,12,13,14,15,16,17,18);

        foreach ($n as $kz) {
            if ($cPosition === true) {
                $this->_positions['H' . ($kz + 10)] = "kz_{$kz}_z";

                if (in_array($kz, array(10,12)) === false) {
                    $this->_positions['I' . ($kz + 10)] = "kz_{$kz}_n";
                }
            }

            $data["kz_{$kz}_z"] = 0;
            $data["kz_{$kz}_n"] = 0;
        }

        $a = array(8,9);

        foreach ($a as $kz) {
            if ($cPosition === true) {
                $this->_positions['H' . ($kz + 10)] = "kz_{$kz}_a";
            }

            $data["kz_{$kz}_a"] = 0;
        }

        $data = $this->_sortInitDataArray($data);

        return $data;
   }


   /**
    * Init h06.1 Data Array
    *
    * @param array $datasets
    * @return array
    */
   protected function _initH061DataArray($datasets)
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
        $data = $this->_initDataArray();

        $bezugsjahr = $this->getParam('jahr', date('Y'));

        $h06_1 = array();

        $h011AdditionalCondition = array(
            'condition' => "({$bezugsjahr} = YEAR(bezugsdatum) OR LOCATE('{$bezugsjahr}', datum_studie) != 0)",
            'joins'     => array(
                $this->_statusJoin('aufenthalt af')
            ),
            'fields'    => array(
                "CONCAT_WS('-', sit.patient_id, sit.erkrankung_id, sit.anlass) AS 'ukey'",
                "COUNT(IF({$bezugsjahr} IN (YEAR(af.aufnahmedatum), YEAR(af.entlassungsdatum)),1,NULL)) > 0 AS 'aufenthaltbezugsjahr'",
                "COUNT(IF(op.art_primaertumor IS NOT NULL, 1, NULL)) > 0 AS 'op_primaertumor'",

                "IF(MAX(IF(th_str.ziel_lymph IS NOT NULL AND th_str.gesamtdosis BETWEEN 50 AND 60 AND th_str.fraktionierungstyp = 'konv', th_str.beginn, NULL)) > MAX(IF(op.art_primaertumor IS NOT NULL, op.datum, NULL)),
                    1,
                    NULL
                ) AS 'postop_strahlentherapie_5060gy_konvent'",
                "MIN(IF(s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0, s.form_date, NULL)) AS fruehste_nachsorge"
            )
        );

        $h012AdditionalCondition = array(
            'fields'    => array(
                "CONCAT_WS('-', sit.patient_id, sit.erkrankung_id, sit.anlass) AS 'ukey'"
            )
        );

        $h01_1 = $this->loadRessource('h01_1', $h011AdditionalCondition);
        $h01_2 = $this->loadRessource('h01_2', $h012AdditionalCondition);

        //Init h061
        if ($this->_params['name'] == 'h06_1' && count($h01_1) > 0) {
            $h06_1 = $this->_initH061DataArray($h01_1);
        }

        $kzCache = array(
            '02' => array(),
            '03' => array(),
            '04' => array(),
            '05' => array(),
            '07' => array(),
            '13_n' => array(),
            '13_z' => array(),
            '14_n' => array(),
            '14_z' => array(),
            '11_z' => array()
        );

        foreach ($h01_1 as $i => $record) {
            $studyDate = $record['datum_studie'];

            if (strlen($studyDate) > 0) {
                foreach (explode(', ', $studyDate) as $studiendatum) {
                    $studienYear = date('Y', strtotime($studiendatum));

                    if ($studienYear == $bezugsjahr) {
                        $data['kz_6_z']++;
                        $h06_1[$i]['kz_6_z'] = isset($h06_1[$i]['kz_6_z']) === true ? $h06_1[$i]['kz_6_z'] + 1 : 1;
                    }
                }
            }

            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr != $bezugsjahr) {
                continue;
            }

            $counted = array(
                'kz012' => 0,
                'kz02'  => 0
            );

            $diseaseSitIdent = $record['erkrankung_id'];

            // Kz01_1
            if ($this->_checkKz01_1($record) === true) {
                $data['kz_1_1_a']++;
                $h06_1[$i]['kz_1_1_a'] = 1;
            }

            // Kz01_2
            if ($this->_checkKz01_2($record) === true) {
                $data['kz_1_2_a']++;
                $h06_1[$i]['kz_1_2_a'] = 1;
                $counted['kz012'] = 1;
            }

            // Kz01_3
            if ($this->_checkKz01_3($record) === true) {
                $data['kz_1_3_a']++;
                $h06_1[$i]['kz_1_3_a'] = 1;
            }

            // Kz02
            if ($this->_checkKz02($record) === true) {
                if ($record['anlass'] === 'p' ||
                   (str_starts_with($record['anlass'], 'r') === true && in_array($diseaseSitIdent, $kzCache['02']) === false)
                ) {
                    if (str_starts_with($record['anlass'], 'r') === true) {
                        $kzCache['02'][] = $diseaseSitIdent;
                    }

                    $data['kz_2_n']++;
                    $h06_1[$i]['kz_2_n'] = 1;
                    $counted['kz02'] = 1;

                    $condition = (int) ((int) $record['tumorkonferenz'] === 1);

                    $data['kz_2_z']     += $condition;
                    $h06_1[$i]['kz_2_z'] = $condition;
                }
            }

            // Kz03
            if ($this->_checkKz03($record) === true) {
                if ($record['anlass'] === 'p' ||
                    (str_starts_with($record['anlass'], 'r') === true && in_array($diseaseSitIdent, $kzCache['03']) === false)
                ) {
                    if (str_starts_with($record['anlass'], 'r') === true) {
                        $kzCache['03'][] = $diseaseSitIdent;
                    }

                    if ((int) $record['tumorkonferenz'] === 1) {
                        $data['kz_3_n']++;
                        $h06_1[$i]['kz_3_n'] = 1;

                        $condition = (int) ((int) $record['therapieabweichung'] === 1);

                        $data['kz_3_z']     += $condition;
                        $h06_1[$i]['kz_3_z'] = $condition;
                    }
                }
            }

            // Kz04
            if ($counted['kz012'] !== 0 || $counted['kz02'] !== 0) {
                $data['kz_4_n'] += ($counted['kz012'] + $counted['kz02']);
                $h06_1[$i]['kz_4_n'] = ($counted['kz012'] + $counted['kz02']);

                $condition = (int) ((int) $record['psychoonk_betreuung'] === 1);

                $data['kz_4_z']     += $condition;
                $h06_1[$i]['kz_4_z'] = $condition;
            }

            // Kz05
            if ($counted['kz012'] !== 0 || $counted['kz02'] !== 0) {
                $data['kz_5_n'] += ($counted['kz012'] + $counted['kz02']);
                $h06_1[$i]['kz_5_n'] = ($counted['kz012'] + $counted['kz02']);

                $condition = (int) ((int) $record['sozialdienst'] === 1);

                $data['kz_5_z']     += $condition;
                $h06_1[$i]['kz_5_z'] = $condition;
            }

            // Kz06
            if ($this->_checkKz06($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_6_n']++;
                $h06_1[$i]['kz_6_n'] = 1;
            }

            // Kz13 1.
            if ($this->_checkKz13($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_13_n'] ++;
                $h06_1[$i]['kz_13_n'] = 1;

                $kzCache['13_n'][$record['erkrankung_id']] = 1;
            }

            // Kz14 1.
            if ($this->_checkKz14($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_14_n'] ++;
                $h06_1[$i]['kz_14_n'] = 1;

                $kzCache['14_n'][$record['erkrankung_id']] = 1;
            }

            // Kz15
            if ($this->_checkKz15($record) === true) {
                $data['kz_15_n']++;
                $h06_1[$i]['kz_15_n'] = 1;

                $condition = (int) ((int) $record['postop_strahlentherapie_5060gy_konvent'] === 1);

                $data['kz_15_z']     += $condition;
                $h06_1[$i]['kz_15_z'] = $condition;
            }

            // Kz16
            if ($this->_checkKz16($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_16_n']++;
                $h06_1[$i]['kz_16_n'] = 1;

                $condition = (int) ((int) $record['adj_sys_chemo_dacarbazin'] === 1);

                $data['kz_16_z']     += $condition;
                $h06_1[$i]['kz_16_z'] = $condition;
            }

            // Kz17
            if ($this->_checkKz17($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_17_n']++;
                $h06_1[$i]['kz_17_n'] = 1;

                $condition = (int) ((int) $record['adj_extperfusion'] === 1);

                $data['kz_17_z']     += $condition;
                $h06_1[$i]['kz_17_z'] = $condition;
            }

            // Kz18
            if ($this->_checkKz18($record) === true && $this->_hasNoUveaKonjunktivaMucosa($record) == true) {
                $data['kz_18_n']++;
                $h06_1[$i]['kz_18_n'] = 1;

                $condition = (int) ((int) $record['braf_therapie'] === 1);

                $data['kz_18_z']     += $condition;
                $h06_1[$i]['kz_18_z'] = $condition;
            }

            //h06_1 impossible
            $h06_1[$i]['kz_7_n'] = '-';
            $h06_1[$i]['kz_7_z'] = '-';
            $h06_1[$i]['kz_8_a'] = '-';
            $h06_1[$i]['kz_9_a'] = '-';
            $h06_1[$i]['kz_10_n'] = '-';
            $h06_1[$i]['kz_10_z'] = '-';
            $h06_1[$i]['kz_11_n'] = '-';
            $h06_1[$i]['kz_11_z'] = '-';
            $h06_1[$i]['kz_12_n'] = '-';
            $h06_1[$i]['kz_12_z'] = '-';
            $h06_1[$i]['kz_13_z'] = '-';
            $h06_1[$i]['kz_14_z'] = '-';
        }

        foreach ($h01_2 as $i => $record) {
            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr != $bezugsjahr) {
                continue;
            }

            $diseaseId = $record['erkrankung_id'];

            // Kz07 1.
            if ($this->_checkKz07($record) === true) {
                $kzCache['07'][$diseaseId][] = (int) $record['gammasonde_sentinel'];
            }

            // Kz13 2.
            if ((int) $record['sentinel_node_biopsie'] === 1 &&
                array_key_exists($diseaseId, $kzCache['13_n']) === true &&
                array_key_exists($diseaseId, $kzCache['13_z']) === false
            ) {
                $kzCache['13_z'][$diseaseId] = 1;

                $data['kz_13_z'] ++;
            }

            // Kz14 2.
            $kz14Condition =(
                (int) $record['sys_lymph_inguinal'] === 1 ||
                (int) $record['sys_lymph_iliakal_obtur'] === 1 ||
                (int) $record['sys_lymph_axillaer'] === 1 ||
                (int) $record['sys_lymph_zervikal'] === 1
            );

            if ($kz14Condition === true &&
                array_key_exists($diseaseId, $kzCache['14_n']) === true &&
                array_key_exists($diseaseId, $kzCache['14_z']) === false
            ) {
                $kzCache['14_z'][$diseaseId] = 1;
                $data['kz_11_n']++;

                $data['kz_14_z'] ++;
            }
        }

        // crazy and verrueckte kennzahl 11... das gibt sicherlich nochmal ein Ticket... Onknozert....
        foreach ($h01_2 as $i => $record) {
            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr != $bezugsjahr) {
                continue;
            }

            $diseaseId = $record['erkrankung_id'];

            $kz11zCondition = ((int) $record['revisions_op'] === 1 && str_contains($record['komplikation'], 'nbl') === true);

            $kz07Condition  = $this->_checkKz07($record);
            $kz14Condition  = (
                (int) $record['sys_lymph_inguinal'] === 1 ||
                (int) $record['sys_lymph_iliakal_obtur'] === 1 ||
                (int) $record['sys_lymph_axillaer'] === 1 ||
                (int) $record['sys_lymph_zervikal'] === 1
            );

            if ($kz11zCondition === true &&
                ($kz07Condition === true || $kz14Condition === true ) &&
                (array_key_exists($diseaseId, $kzCache['14_z']) === true ||
                  array_key_exists($diseaseId, $kzCache['07']) === true
                )
            ) {
                $tmp = array_key_exists($diseaseId, $kzCache['11_z']) === false
                    ? array('snb' => false, 'lad' => false)
                    : $kzCache['11_z'][$diseaseId]
                ;

                if ($kz07Condition === true && array_key_exists($diseaseId, $kzCache['07']) === true && $tmp['snb'] === false) {
                    $data['kz_11_z']++;
                    $tmp['snb'] = true;
                }

                if ($kz14Condition === true && array_key_exists($diseaseId, $kzCache['14_z']) === true && $tmp['lad'] === false) {
                    $data['kz_11_z']++;
                    $tmp['lad'] = true;
                }

                $kzCache['11_z'][$diseaseId] = $tmp;
            }
        }

        // Kz07 2.
        foreach ($kzCache['07'] as $disease) {
            $data['kz_7_n'] ++;
            $data['kz_11_n'] ++;

            if (array_sum($disease) > 0) {
                $data['kz_7_z'] ++;
            }
        }

        foreach ($h01_2 as $i => $record) {
            $jahr = date('Y', strtotime($record['op_datum']));

            if ($jahr != $bezugsjahr){
                continue;
            }

            // Kz08
            if ($this->_checkKz08($record) === true) {
                $data['kz_8_a']++;
            }

            // Kz09
            if ($this->_checkKz09($record) === true) {
                $data['kz_9_a']++;
            }

            // Kz10
            if (($this->_checkKz08($record) === true || $this->_checkKz09($record) === true) && $this->_checkKz10($record) === true) {
                $data['kz_10_z']++;
            }

            // Kz12
            if ($this->_checkKz12($record) === true) {
                $data['kz_12_n']++;

                $condition = (int) ((int) $record['wundinfektion'] === 1);

                $data['kz_12_z'] += $condition;
            }
        }

        if (str_starts_with($this->_params['name'], 'h06') === true) {
            $config = $this->loadConfigs($this->_params['name']);

            $this->_title = $config['head_report'];

            $rp = $this->_params['name'];

            $this->_data  = $$rp;
            $this->writeXLS();
        } else {
            $data['bezugsjahr'] = "(Bezugsjahr: $bezugsjahr)";
            $this->_data = $this->_convertPositionData($data);
            $this->parseXLS();
        }
    }


    /**
     * _convertPositionData
     *
     * @access  protected
     * @param   array $data
     * @return  array
     */
    protected function _convertPositionData($data)
    {
        foreach ($this->_positions as &$kz) {
            $kz = $data[$kz];
        }

        return $this->_positions;
    }


    /**
     * isPrimaryCase
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function isPrimaryCase(array $record)
    {
        return ((int) $record['primaerfall'] === 1);
    }


    /**
     * hasM1
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function hasM1(array $record)
    {
        return str_contains($record['m'], 'M1');
    }


    /**
     * _checkKz01_1
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz01_1(array $record)
    {
        return ($this->isPrimaryCase($record) === true && (int) $record['epithelialer_tumor'] === 1);
    }


    /**
     * _checkKz01_2
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz01_2(array $record)
    {
        return ($this->isPrimaryCase($record) === true && (int) $record['invasives_malignom'] === 1);
    }


    /**
     * _checkKz01_3
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz01_3(array $record)
    {
        return ($this->isPrimaryCase($record) === true && (int) $record['seltene_tumore'] === 1);
    }


    /**
     * _checkKz02
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz02(array $record)
    {
        return $this->hasM1($record);
    }


    /**
     * _checkKz03
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz03(array $record)
    {
        return ($this->hasM1($record) === true);
    }


    /**
     * _checkKz06
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz06(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            in_array($record['ajcc'], array('IA','IB','IIA','IIB','IIC','IIIA','IIIB','IIIC','IV')) === true
        );

        return $condition;
    }


    /**
     * _checkKz07
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz07(array $record)
    {
        return ((int) $record['sentinel_node_biopsie'] === 1);
    }


    /**
     * _checkKz08
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz08(array $record)
    {
        $condition = (
            ((int) $record['invasives_malignom'] === 1 || (int) $record['seltene_tumore'] === 1) &&
            strlen($record['mikrograph_chirurgie']) === 0 &&
            (int) $record['sicherheitsabstand'] === 1
        );

        return $condition;
    }


    /**
     * _checkKz08
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz09(array $record)
    {
        $condition = (
            (int) $record['epithelialer_tumor'] === 1 &&
            (int) $record['mikrograph_chirurgie'] === 1 &&
            ((int) $record['sicherheitsabstand'] === 0 || strlen($record['sicherheitsabstand']) === 0)
        );

        return $condition;
    }


    /**
     * _checkKz10
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz10(array $record)
    {
        $condition = (
            (int) $record['revisions_op'] === 1 &&
            str_contains($record['komplikation'], 'nbl') === true
        );

        return $condition;
    }


    /**
     * _checkKz12
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz12(array $record)
    {
        return ($this->_checkKz08($record) === true || $this->_checkKz09($record) === true);
    }


    /**
     * _checkKz13
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz13(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            (int) $record['tumordicke_gr1mm'] === 1 &&
            in_array($record['ajcc'], array('IA','IB','IIA','IIB','IIC')) === true
        );

        return $condition;
    }


    /**
     * _checkKz14
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz14(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            in_array($record['ajcc'], array('IIIB','IIIC')) === true
        );

        return $condition;
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
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            (int) $record['postop_strahlentherapie'] === 1
        );

        return $condition;
    }


    /**
     * _checkKz16
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz16(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            in_array($record['ajcc'], array('IA','IB','IIA','IIB','IIC','IIIA','IIIB','IIIC')) === true
        );

        return $condition;
    }


    /**
     * _checkKz17
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz17(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            in_array($record['ajcc'], array('IA','IB','IIA','IIB','IIC','IIIA','IIIB')) === true
        );

        return $condition;
    }


    /**
     * _checkKz18
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz18(array $record)
    {
        $condition = (
            $this->isPrimaryCase($record) === true &&
            (int) $record['invasives_malignom'] === 1 &&
            $record['ajcc'] === 'IV' &&
            (int) $record['braf'] === 1
        );

        return $condition;
    }


    /**
     *
     *
     * @access
     * @param $record
     * @return bool
     */
    protected function _hasNoUveaKonjunktivaMucosa($record)
    {
        return ($record['uvea'] === null && $record['konjunktiva'] === null && $record['schleimhaut'] === null);
    }
}
?>
