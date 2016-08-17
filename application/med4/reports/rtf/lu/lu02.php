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

class reportContentLu02 extends reportExtensionLu
{

    protected $_uiccFilter = array('IA', 'IB', 'IIA', 'IIB', 'IIIA' => array('IIIA1', 'IIIA2', 'IIIA3', 'IIIA4'), 'IIIB', 'IV');


    /**
     * @access
     * @var array
     */
    protected $_patientUniqueKeys = array();


    /**
     *
     *
     * @access
     * @param bool $showP
     * @return array
     */
    protected function _initDataArray($showP = true)
    {
        $data = array();

        //normale zahlen (zähler, nenner, prozent)
        $n = array('02a','02b',3,4,5,6,10,11,12,13,14,15,16,19,20);
        //anzahl
        $a = array(1,7,8,9,17,18);

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

        $data['org']         = $this->_params['org_name'];
        $data['createtime']  = date("d.m.Y", time());

        //UICC
        foreach (array('lr', 'ges') as $type) {
            foreach ($this->_uiccFilter as $i => $uicc) {
                if (is_array($uicc) === true) {
                    $data["{$type}_{$i}"] = 0;
                } else {
                    $data["{$type}_{$uicc}"] = 0;
                }
            }

            $data["{$type}_ges"] = 0;
        }

        return $data;
    }


    /**
     * Init Lu06.1 Data Array
     *
     * @param array $datasets
     * @return array
     */
    protected function _initLu061DataArray($datasets)
    {
        $data = array();

        $dataArray = $this->_initDataArray(false);

        foreach ($dataArray as $fieldName => $dummy) {
            if (str_starts_with($fieldName, 'kz_') === false) {
                unset($dataArray[$fieldName]);
            }
        }

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


    public function generate()
    {
        $this->setTemplate('lu02');

        $data = $this->_initDataArray();

        $bezugsjahr = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

        $lu06_1 = array();

        $lu01_1AdditionalCondition = array(
            'fields' => array(
                "GROUP_CONCAT(DISTINCT IF(s.form = 'studie' AND YEAR(s.report_param) = '{$bezugsjahr}', s.form_id, NULL) SEPARATOR '|') AS 'studie_ids'"
            )
        );

        $lu01_1 = $this->loadRessource('lu01_1', $lu01_1AdditionalCondition);

        //Init Lu061
        if ($this->_params['name'] == 'lu06_1' && count($lu01_1) > 0) {
            $lu06_1 = $this->_initLu061DataArray($lu01_1);
        }

        $kzUkey = array();

        //For 2b, 4 and 5
        $this->_createPatientUniqueKeys($lu01_1);

        foreach ($lu01_1 as $i => $record) {
            $uicc = $record['uicc'];
            if ('1' == $record['uicc_nach_neoadj_th']) {
                $uicc = $record['uicc_praetherapeutisch'];
            }

            //6 z
            if (strlen($record['studie_ids']) > 0) {
                foreach (explode('|', $record['studie_ids']) as $studieId) {
                    $kzUkey['06']['z'][$record['erkrankung_id']][$studieId] = 1;
                }
            }

            $jahr = date('Y', strtotime($record['bezugsdatum']));

            if ($jahr != $bezugsjahr) {
                continue;
            }

            $primaryCase = strlen($record['primaerfall']) > 0 ? (int) $record['primaerfall'] : null;

            // 1 and 2a
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV'))) {
                // 1
                $data['kz_01_a']++;
                $lu06_1[$i]['kz_01_a'] = 1;
                // 2a
                $data['kz_02a_n']++;
                $lu06_1[$i]['kz_02a_n'] = 1;
                $data['kz_02a_z'] += (int)('1' == $record['praeth_konferenz']);
                $lu06_1[$i]['kz_02a_z'] = (int)('1' == $record['praeth_konferenz']);
            }

            // 2b
            if (true === $this->_isRelapse($record) &&
                true === $this->_hasPatientUniqueKey($record)) {
                $data['kz_02b_n']++;
                $lu06_1[$i]['kz_02b_n'] = 1;
                $data['kz_02b_z'] += (int)('1' == $record['postop_konferenz'] || '1' == $record['praeth_konferenz']);
                $lu06_1[$i]['kz_02b_z'] = (int)('1' == $record['postop_konferenz'] || '1' == $record['praeth_konferenz']);
            }

            // 3
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IB', 'IIA', 'IIB', 'IIIA*', 'IIIB')) &&
                '1' == $record['lungenresektion_durchgefuehrt']) {
                $data['kz_03_n']++;
                $lu06_1[$i]['kz_03_n'] = 1;
                $data['kz_03_z'] += (int)('1' == $record['postop_konferenz']);
                $lu06_1[$i]['kz_03_z'] = (int)('1' == $record['postop_konferenz']);
            }

            // 4
            if ((1 === $primaryCase &&
                 $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV'))) ||
                (true === $this->_isRelapse($record) &&
                 true === $this->_hasPatientUniqueKey($record))) {
                $data['kz_04_n']++;
                $lu06_1[$i]['kz_04_n'] = 1;
                $data['kz_04_z'] += (int)('1' == $record['psychoonk_betreuung']);
                $lu06_1[$i]['kz_04_z'] = (int)('1' == $record['psychoonk_betreuung']);
            }

            // 5
            if ((1 === $primaryCase &&
                 $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV'))) ||
                (true === $this->_isRelapse($record) &&
                 true === $this->_hasPatientUniqueKey($record))) {
                $data['kz_05_n']++;
                $lu06_1[$i]['kz_05_n'] = 1;
                $data['kz_05_z'] += (int)('1' == $record['beratung_sozialdienst']);
                $lu06_1[$i]['kz_05_z'] = (int)('1' == $record['beratung_sozialdienst']);
            }

            // 6 n
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV'))) {
                $data['kz_06_n']++;
                $lu06_1[$i]['kz_06_n'] = 1;
            }

            // 7
            $data['kz_07_a'] += (int)$record['anz_flexible_bronchoskopie'];
            $lu06_1[$i]['kz_07_a'] = (int)$record['anz_flexible_bronchoskopie'];

            // 8
            $data['kz_08_a'] += ((int)$record['anz_endoskopische_stenteinlagen'] + (int)$record['anz_thermisch_endoskopische_verfahren']);
            $lu06_1[$i]['kz_08_a'] = ((int)$record['anz_endoskopische_stenteinlagen'] + (int)$record['anz_thermisch_endoskopische_verfahren']);

            // 9 / 10 / 11 / 12 /13 /14
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV')) &&
                '1' == $record['lungenresektion_durchgefuehrt']) {

                // 9
                $data['kz_09_a'] += 1;
                $lu06_1[$i]['kz_09_a'] = 1;

                // 10 N
                $data['kz_10_n'] += 1;
                $lu06_1[$i]['kz_10_n'] = 1;
                if ('1' == $record['pneumektomie']) {
                    $data['kz_10_z'] += 1;
                    $lu06_1[$i]['kz_10_z'] = 1;
                }

                // 11 N
                $data['kz_11_n'] += 1;
                $lu06_1[$i]['kz_11_n'] = 1;
                if ('1' == $record['broncho_op']) {
                    $data['kz_11_z'] += 1;
                    $lu06_1[$i]['kz_11_z'] = 1;
                }

                // 12 N
                $data['kz_12_n'] += 1;
                $lu06_1[$i]['kz_12_n'] = 1;
                $deathDiff = date_diff_days($record['datum_primaer_op_oder_rezidiv_op'], $record['todesdatum']);
                if ($deathDiff >= 0 && $deathDiff <= 30) {
                    $data['kz_12_z'] += 1;
                    $lu06_1[$i]['kz_12_z'] = 1;
                }

                // 13 N
                $data['kz_13_n'] += 1;
                $lu06_1[$i]['kz_13_n'] = 1;
                if ('1' == $record['anastomoseinsuffizienz']) {
                    $data['kz_13_z'] += 1;
                    $lu06_1[$i]['kz_13_z'] = 1;
                }

                // 14 N
                $data['kz_14_n'] += 1;
                $lu06_1[$i]['kz_14_n'] = 1;
                if ('1' == $record['revisions_op']) {
                    $data['kz_14_z'] += 1;
                    $lu06_1[$i]['kz_14_z'] = 1;
                }
            }

            // 15
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IA', 'IB', 'IIA', 'IIB')) &&
                '1' == $record['lungenresektion_durchgefuehrt']) {
                $data['kz_15_n']++;
                $lu06_1[$i]['kz_15_n'] = 1;
                $data['kz_15_z'] += (int)('0' == $record['r_lokal']);
                $lu06_1[$i]['kz_15_z'] = (int)('0' == $record['r_lokal']);
            }

            // 16
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IIIA*', 'IIIB')) &&
                '1' == $record['lungenresektion_durchgefuehrt']) {
                $data['kz_16_n']++;
                $lu06_1[$i]['kz_16_n'] = 1;
                $data['kz_16_z'] += (int)('0' == $record['r_lokal']);
                $lu06_1[$i]['kz_16_z'] = (int)('0' == $record['r_lokal']);
            }

            // 17
            $data['kz_17_a'] += (int)('1' == $record['thorakale_bestrahlung']);
            $lu06_1[$i]['kz_17_a'] = (int)('1' == $record['thorakale_bestrahlung']);

            // 18
            $data['kz_18_a'] += (int)(strlen($record['datum_praeop_histologie']) > 0 || strlen($record['datum_postop_histologie']) > 0);
            $lu06_1[$i]['kz_18_a'] = (int)(strlen($record['datum_praeop_histologie']) > 0 || strlen($record['datum_postop_histologie']) > 0);

            // 19
            if (1 === $primaryCase &&
                $this->_checkUiccValues($uicc, array('IIA', 'IIB', 'IIIA1', 'IIIA2')) &&
                '1' == $record['nsclc_patient'] &&
                '0' == $record['r'] &&
                (int)$record['lk_entfernt'] > 0) {
                $data['kz_19_n']++;
                $lu06_1[$i]['kz_19_n'] = 1;
                $data['kz_19_z'] += (int)('1' == $record['adjuvante_cisplatinhaltige_chemotherapie'] && ('0' == $record['ecog'] || '1' == $record['ecog']));
                $lu06_1[$i]['kz_19_z'] = (int)('1' == $record['adjuvante_cisplatinhaltige_chemotherapie'] && ('0' == $record['ecog'] || '1' == $record['ecog']));
            }

            // 20
            if (1 === $primaryCase &&
                $this->_checkUiccValues($record['uicc_praetherapeutisch'], array('IIIA4', 'IIIB')) &&
                '1' == $record['nsclc_patient']) {
                $data['kz_20_n']++;
                $lu06_1[$i]['kz_20_n'] = 1;
                $data['kz_20_z'] += (int)('1' == $record['kombinierte_radiochemotherapie'] && ('0' == $record['ecog'] || '1' == $record['ecog']));
                $lu06_1[$i]['kz_20_z'] = (int)('1' == $record['kombinierte_radiochemotherapie'] && ('0' == $record['ecog'] || '1' == $record['ecog']));
            }

            if (0 === $primaryCase &&
                true === $this->_hasPatientUniqueKey($record)) {
                $this->_removePatientUniqueKey($record);
            }
        }

        //Verarbeitung der primaerfallEingriff
        foreach ($kzUkey as $kz => $kzData) {
            foreach ($kzData as $kzType => $kzContent) {
                if ($kz == '06') {
                    foreach ($kzContent as $erkId => $studieIds) {
                        $kzContent[$erkId] = array_sum($studieIds);
                    }
                }

                $data["kz_{$kz}_{$kzType}"] = array_sum($kzContent);
            }
        }


        if ($this->_params['name'] == 'lu06_1') {
            $config = $this->loadConfigs('lu06_1');
            $this->_title = $config['head_report'];

            $this->_data  = $lu06_1;
            $this->writeXLS();
        } else {
            foreach ($data as $kzName => $calcPr) {
                if (strpos($kzName, '_p') !== false) {
                    $nenner  = $data[str_replace('_p', '_n', $kzName)];
                    $zaehler = $data[str_replace('_p', '_z', $kzName)];

                    $data[$kzName] = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
                }
            }

            $data['bezugsjahr'] = "(Bezugsjahr $bezugsjahr)";

            $this->_data = $data;
            $this->writePDF(true);
        }
    }


    /**
     *
     *
     * @access
     * @param $checkUicc
     * @param $uiccList
     * @return bool
     */
    protected function _checkUiccValues($checkUicc, $uiccList) {
        if (false === is_array($uiccList)) {
            $uiccList = explode(",", $uiccList);
        }
        foreach ($uiccList as $uicc) {
            if (false !== ($pos = strpos($uicc, "*"))) {
                if (true === str_starts_with($checkUicc, substr($uicc, 0, $pos))) {
                    return true;
                }
            }
            else {
                if ($checkUicc == $uicc) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return void
     */
    protected function _createPatientUniqueKeys($data) {
        $this->_patientUniqueKeys = array();

        foreach ($data as $record) {
            if ($record['primaerfall'] == '1' && $record['kurativ_behandelt'] == '1') {
                $this->_patientUniqueKeys["{$record['erkrankung_id']}"]["{$record['seite']}"] = 1;
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _hasPatientUniqueKey($data) {
        if (isset($this->_patientUniqueKeys[$data['erkrankung_id']]) &&
            isset($this->_patientUniqueKeys[$data['erkrankung_id']][$data['seite']]) &&
            1 === $this->_patientUniqueKeys[$data['erkrankung_id']][$data['seite']]) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return void
     */
    protected function _removePatientUniqueKey($data) {
        if (isset($this->_patientUniqueKeys[$data['erkrankung_id']]) &&
            isset($this->_patientUniqueKeys[$data['erkrankung_id']][$data['seite']])) {
            unset($this->_patientUniqueKeys[$data['erkrankung_id']][$data['seite']]);
        }
    }


    /**
     *
     *
     * @access protected
     * @param $data
     * @return bool
     */
    protected function _isRelapse($data) {
        return str_starts_with($data['anlass'], 'r');
    }

}

?>
