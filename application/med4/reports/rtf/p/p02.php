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
 * Class reportContentP02
 */
class reportContentP02 extends reportExtensionP
{
    /**
     * _rawData
     *
     * @access  protected
     * @var     array
     */
    protected $_rawData = array();


    /**
     * initialize data array
     *
     * @access  public
     * @param   string $selectedYear
     * @param   string $previousYear
     * @param   bool   $showP
     * @return  array
     */
    public function initDataArray($selectedYear, $previousYear, $showP = true)
    {
        $data = $showP == true ? array('bezugsjahr' => $selectedYear, 'vorjahr' => $previousYear) : array();

        //normale zahlen (zähler, nenner, prozent)
        foreach (array('02a','02b','03a','03b','05','06','07','08','10','11','13','15','16','17','18','19','20','21','22') as $nDigit) {
            foreach (array('n','z','p') as $e) {

                if ($e == 'p' && $showP === false) {
                    continue;
                }

                $data["kz_{$nDigit}_{$e}"] = 0;
            }
        }

        ksort($data);

        return $data;
    }


    /**
     * initialize data array for pz06
     *
     * @access  protected
     * @param   array  $records
     * @param   string $selectedYear
     * @return  array
     */
    protected function _initRawDataArray($records, $selectedYear)
    {
        $data = array();

        foreach ($records as $record) {
            $patientData = array(
                'nachname'             => $record['nachname'],
                'vorname'              => $record['vorname'],
                'geburtsdatum'         => $record['geburtsdatum'],
                'bezugsdatum'          => $record['bezugsdatum']
            );

            $ident = $this->_buildIdent($record);

            $data[$ident] = array_merge($patientData, $this->initDataArray($selectedYear, $selectedYear - 1, false));
        }

        $this->_rawData = $data;
    }


    /**
     * _buildIdent
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildIdent(array $record)
    {
        return $record['erkrankung_id'] . '-' . $record['anlass_case'];
    }


    /**
     * generate
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $this->setTemplate('p02');

        $selectedYear = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');
        $previousYear = $selectedYear - 1;

        $data = $this->initDataArray($selectedYear, $previousYear);

        $rowSeparator = reportExtensionP::SEP_ROWS;

        $additionalContent = array(
            'fields' => array(
                "GROUP_CONCAT(DISTINCT IF(th_str.intention = 'kur', IFNULL(th_str.ende,th_str.beginn), NULL) SEPARATOR '{$rowSeparator}') AS 'def_strahlentherapie_datum'", // kz22
                "GROUP_CONCAT(DISTINCT IF(th_str.intention IN ('kura', 'pala'), IFNULL(th_str.ende, th_str.beginn), NULL) SEPARATOR '{$rowSeparator}') AS 'durchgef_adj_strahlenth_datum'", // kz22
                "GROUP_CONCAT(DISTINCT IF(allk.clavien_dindo IS NOT NULL, CONCAT_WS(',', allk.datum, allk.clavien_dindo), NULL) SEPARATOR '|') AS 'clavien_dindo_all'",
                "GROUP_CONCAT(DISTINCT IF(allk.ctcae IS NOT NULL, CONCAT_WS(',', allk.datum, allk.ctcae), NULL) SEPARATOR '|') AS 'ctcae_all'",
            ),
            'joins' => array(
                'LEFT JOIN komplikation allk ON allk.erkrankung_id = sit.erkrankung_id'
            )
        );

        $records = $this->loadRessource('p01', $additionalContent);

        $cache = array(
            'recordsInSelectedYear' => array(),
            'recordsInPreviousYear' => array(),
            'relapsesInSelectedYear' => $this->_filterRelapses($records, $selectedYear, array('rezidiv', 'psa_rezidiv', 'metastasen')),
            'cpzGesCases' => array()
        );

        // build cache
        foreach ($records as $record) {
            $referenceYear = substr($record['bezugsdatum'], 0, 4);

            if ($referenceYear == $selectedYear) {
                $cache['recordsInSelectedYear'][] = $record;
            } else if ($referenceYear == $previousYear) {
                $cache['recordsInPreviousYear'][] = $record;
            }
        }

        $this->_initRawDataArray(array_merge(
            $cache['recordsInSelectedYear'],
            $cache['recordsInPreviousYear'],
            $cache['relapsesInSelectedYear']
        ), $selectedYear);

        foreach ($records as $record) {
            $this->_checkKz08_z($data, $record, $selectedYear);
        }

        // process all records within selected year
        foreach ($cache['recordsInSelectedYear'] as $record) {
            $this->_checkKz02a($data, $record);
            $this->_checkKz02b($data, $record);
            $this->_checkKz03a($data, $record);
            $this->_checkKz05($data, $record);
            $this->_checkKz06_1($data, $record);
            $this->_checkKz07_1($data, $record);
            $this->_checkKz08_n($data, $record);

            // first block of kz10 - second is in relapse
            $this->_checkKz10_1($data, $record);
            $this->_checkKz11($data, $record);
            $this->_checkKz13($data, $record);
            $this->_checkKz15($data, $record);
            $this->_checkKz16($data, $record);
            $this->_checkKz17($data, $record);
            $this->_checkKz18($data, $record);
            $this->_checkKz19($data, $record);
            $this->_checkKz20($data, $record);

            // cache for kz 03b, 06, 07
            if ($this->_cpz_ges($record) == '1') {
                $cache['cpzGesCases'][] = $record['erkrankung_id'];
            }
        }

        // process relapses within selected year
        foreach ($cache['relapsesInSelectedYear'] as $record) {
            $this->_checkKz03b($data, $record, $cache['cpzGesCases']);
            $this->_checkKz06_2($data, $record, $cache['cpzGesCases']);
            $this->_checkKz07_2($data, $record, $cache['cpzGesCases']);

            $this->_checkKz10_2($data, $record);
        }

        // process all records within previous year
        foreach ($cache['recordsInPreviousYear'] as $record) {
            $this->_checkKz21($data, $record);
            $this->_checkKz22($data, $record);
        }

        // calc %
        foreach ($data as $kzName => &$calcPr) {
            if (strpos($kzName, '_p') !== false) {
                $nenner  = $data[str_replace('_p', '_n', $kzName)];
                $zaehler = $data[str_replace('_p', '_z', $kzName)];
                $calcPr  = $nenner > 0 ? round(($zaehler / $nenner * 100), 2) . '%' : '-';
            }
        }

        // write p06
        if ($this->_params['name'] == 'p06') {
            $config       = $this->loadConfigs('p06');
            $this->_title = $config['head_report'];

            $this->_data  = $this->_rawData;
            $this->writeXLS();
        } else {
            $this->_data = $data;
            $this->writePDF(true);
        }
    }


    /**
     * Check if checkDate is within endDate and endDate - 6 Months
     *
     * @access  protected
     * @param   string  $endDate
     * @param   string  $checkDate
     * @return  bool
     */
    protected function _checkWithin6Months($endDate, $checkDate)
    {
        $startDate = date('Y-m-d', strtotime($endDate . ' - 6 month'));

        return ($checkDate <= $endDate && $checkDate >= $startDate);
    }


    /**
     * _checkKz02a
     * (KZ 02a ['cpz_ges' = 1 UND 'Leistungserbringer' = Urologie] / 'präth. Konferenz' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record

     * @return  void
     */
    protected function _checkKz02a(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1' && $record['leistungserbringer_raw'] === 'luro') {
            $data['kz_02a_n']++;
            $this->_addRawDataFlag($record, 'kz_02a_n');

            $data['kz_02a_z'] += $record['prae_konferenz'];
            $this->_addRawDataFlag($record, 'kz_02a_z', $record['prae_konferenz']);
        }
    }


    /**
     * _checkKz02b
     * ([ 'cpz_ges' = 1 UND 'Leistungserbringer' = Strahlentherapie / 'präth. Konferenz' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record

     * @return  void
     */
    protected function _checkKz02b(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1' && $record['leistungserbringer_raw'] === 'lstrahl') {
            $data['kz_02b_n']++;
            $this->_addRawDataFlag($record, 'kz_02b_n');

            $data['kz_02b_z'] += $record['prae_konferenz'];
            $this->_addRawDataFlag($record, 'kz_02b_z', $record['prae_konferenz']);
        }
    }


    /**
     * _checkKz03a
     * ([ 'cpz_ges' = 1 UND ['pT' = pT3b || pT4 || 'R (lokal)' = R1 || 'pN' = pN1]] / 'posttherapeutische Konferenz' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record

     * @return  void
     */
    protected function _checkKz03a(array &$data, array $record)
    {
        $condition = $this->_cpz_ges($record) == '1' && (
            $record['pt'] == 'pT3b' ||
            substr($record['pt'], 2, 1) == '4' ||
            $record['r_lokal'] == '1' ||
            substr($record['pn'], 2, 1) == '1'
        );

        if ($condition === true) {
            $data['kz_03a_n']++;
            $this->_addRawDataFlag($record, 'kz_03a_n');

            $data['kz_03a_z'] += $record['post_konferenz'];
            $this->_addRawDataFlag($record, 'kz_03a_z', $record['post_konferenz']);
        }
    }


    /**
     * _checkKz03b
     *
     * attention: record is already filtered as earliest relapse
     *
     * Nenner:
     * 'cpz_erstmanifestation_rezidiv_fernmetastase' = 1
     *
     * Zähler:
     * 'posttherapeutische Konferenz' = 1
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @param   array $primaryCases
     * @return  void
     */
    protected function _checkKz03b(array &$data, array $record, array $primaryCases)
    {
        $diseaseId = $record['erkrankung_id'];

        if (in_array($diseaseId, $primaryCases) === false && $this->_check_cpz_relapse_first_metastasis_part($record) === true) {
            $data['kz_03b_n']++;
            $this->_addRawDataFlag($record, 'kz_03b_n');

            $data['kz_03b_z'] += $record['post_konferenz'];
            $this->_addRawDataFlag($record, 'kz_03b_n', $record['post_konferenz']);
        }
    }


    /**
     * _checkKz05
     * ('cpz_hohes_risiko' = 1  && 'Therapie des Bezugsdatums' = definitive perkutane Strahlentherapie / 'durchgef. (neo)adjuv. antih. Th.' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz05(array &$data, array $record)
    {
        if ($this->_cpz_hohes_risiko($record) == 1 && $this->_is_bezug_perk($record) === true) {
            $data['kz_05_n']++;
            $this->_addRawDataFlag($record, 'kz_05_n');

            $data['kz_05_z'] += $record['durchgef_adj_antihth'];
            $this->_addRawDataFlag($record, 'kz_05_z', $record['durchgef_adj_antihth']);
        }
    }


    /**
     * _checkKz06_1
     *
     * Nenner:
     * 'cpz_ges' = 1
     *
     * Zähler:
     * 'psychoonkologische Betreuung' = 1
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz06_1(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1') {
            $data['kz_06_n']++;
            $this->_addRawDataFlag($record, 'kz_06_n');

            $data['kz_06_z'] += $record['psychoonkologische_betreuung'];
            $this->_addRawDataFlag($record, 'kz_06_z', $record['psychoonkologische_betreuung']);
        }
    }


    /**
     * _checkKz06_2
     *
     * Nenner:
     * 'cpz_erstmanifestation_rezidiv_fernmetastase' = 1
     *
     * Zähler:
     * 'psychoonkologische Betreuung' = 1
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @param   array $primaryCases
     * @return  void
     */
    protected function _checkKz06_2(array &$data, array $record, array $primaryCases)
    {
        $diseaseId = $record['erkrankung_id'];

        if (in_array($diseaseId, $primaryCases) === false && $this->_check_cpz_relapse_first_metastasis_part($record) === true) {
            $data['kz_06_n']++;
            $this->_addRawDataFlag($record, 'kz_06_n');

            $data['kz_06_z'] += $record['psychoonkologische_betreuung'];
            $this->_addRawDataFlag($record, 'kz_06_z', $record['psychoonkologische_betreuung']);
        }
    }


    /**
     * _checkKz07_1
     *  'cpz_ges' = 1
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz07_1(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1') {
            $data['kz_07_n']++;
            $this->_addRawDataFlag($record, 'kz_07_n');

            $data['kz_07_z'] += $record['sozialdienst'];
            $this->_addRawDataFlag($record, 'kz_07_z', $record['sozialdienst']);
        }
    }


    /**
     * _checkKz07_2
     * 'cpz_erstmanifestation_rezidiv_fernmetastase' = 1
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @param   array $primaryCases
     * @return  void
     */
    protected function _checkKz07_2(array &$data, array $record, array $primaryCases)
    {
        $diseaseId = $record['erkrankung_id'];

        if (in_array($diseaseId, $primaryCases) === false && $this->_check_cpz_relapse_first_metastasis_part($record) === true) {
            $data['kz_07_n']++;
            $this->_addRawDataFlag($record, 'kz_07_n');

            $data['kz_07_z'] += $record['sozialdienst'];
            $this->_addRawDataFlag($record, 'kz_07_z', $record['sozialdienst']);
        }
    }


    /**
     * _checkKz08_n
     * (['cpz_ges' = 1] - Zähler wird seperat berechnet )
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz08_n(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1') {
            $data['kz_08_n']++;
            $this->_addRawDataFlag($record, 'kz_08_n');
        }
    }


    /**
     * _checkKz08_z
     * (Studienteilnahme im Kennzahlenjahr (Zähler ist unabhängig vom Nenner, d.h. eine Studie wird gezählt,)
     *
     * @access  protected
     * @param   array  $data
     * @param   array  $record
     * @param   string $year
     * @return  void
     */
    protected function _checkKz08_z(array &$data, array $record, $year)
    {
        if (strlen($record['datum_studie']) > 0) {
            foreach (explode(', ', $record['datum_studie']) as $date) {
                if (date('Y', strtotime($date)) === $year) {
                    $data['kz_08_z']++;
                    $this->_addRawDataFlag($record, 'kz_08_z');
                }
            }
        }
    }


    /**
     * _checkKz10_1
     * ('['Primärfall' = 1 UND 'Therapie des Bezugsdatums' = RPE])
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz10_1(array &$data, array $record)
    {
        if ($this->_isPrimary($record) && $this->_is_bezug_rpe($record) === true) {
            $data['kz_10_n']++;
            $this->_addRawDataFlag($record, 'kz_10_n');

            if ($record['revisions_op_90'] == '1') {
                $data['kz_10_z']++;
                $this->_addRawDataFlag($record, 'kz_10_z');
            }
        }
    }


    /**
     * _checkKz10_2
     * '[['Anlass'= Beurteilung des x. Rezidivs
     * && ['Rezidiv' = gefüllt ODER 'Metastasen' = gefüllt ODER 'PSA-Rezidiv' = gefüllt] &&
     * wenn mehr als 1 Rezidiv im ausgewählten Kalenderjahr, dann nur das mit dem frühesten Datum der Sicherung zählen]
     * &&  'RPE' = 1] / 'Anzahl Revisions-OPs innerhalb von 90 Tagen'
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz10_2(array &$data, array $record)
    {
        if ($this->_has_rpe($record) === true) {
            $data['kz_10_n']++;
            $this->_addRawDataFlag($record, 'kz_10_n');

            if ($record['revisions_op_90'] == '1') {
                $data['kz_10_z']++;
                $this->_addRawDataFlag($record, 'kz_10_z');
            }
        }
    }


    /**
     * _checkKz11
     * (cpz_ges = 1 && 'Datum Primär-OP' = gefüllt && ['pT' = pT2 ||  pT2a || pT2b || pT2c] UND ['cN' = cN0 || 'pN' = pN0] UND ['M' = cM0 ODER 'M' = pM0] / R (lokal) = R1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz11(array &$data, array $record)
    {
        $condition = $this->_cpz_ges($record) == '1' &&
            strlen($record['primaer_op']) > 0 &&
            in_array($record['pt'], array('pT2', 'pT2a', 'pT2b', 'pT2c')) &&
            ($record['cn'] == 'cN0' || $record['pn'] == 'pN0') &&
            substr($record['m'], 1) == 'M0'
        ;

        if ($condition === true) {
            $data['kz_11_n']++;
            $this->_addRawDataFlag($record, 'kz_11_n');

            if ($record['r_lokal'] == '1') {
                $data['kz_11_z']++;
                $this->_addRawDataFlag($record, 'kz_11_z');
            }
        }
    }


    /**
     * _checkKz13
     * ('Primärfall' = 1 UND 'Therapie des Bezugsdatums' = Str. permanent seed / 'Str. permanent seed, D90 > 130 Gy' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz13(array &$data, array $record)
    {
        if ($this->_isPrimary($record) === true && $this->_is_bezug_seed($record) === true) {
            $data['kz_13_n']++;
            $this->_addRawDataFlag($record, 'kz_13_n');

            if ($record['str_permanent_seed_d90_130_gy'] == '1') {
                $data['kz_13_z']++;
                $this->_addRawDataFlag($record, 'kz_13_z');
            }
        }
    }


    /**
     * _checkKz15
     * (cpz_ges = 1 && 'Biopsien: OPS-Codes' = gefüllt / 'Befundbericht Stanze' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz15(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1' && strlen($record['biopsien_ops_codes']) > 0) {
            $data['kz_15_n']++;
            $this->_addRawDataFlag($record, 'kz_15_n');

            if ($record['befundbericht_stanzen'] == '1') {
                $data['kz_15_z']++;
                $this->_addRawDataFlag($record, 'kz_15_z');
            }
        }
    }


    /**
     * _checkKz16
     * ('cpz_ges' = 1  && 'Lymphadenektomie' = 1 / ' 'Befundbericht LK' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz16(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1' && strlen($record['lymphadenektomie']) > 0) {
            $data['kz_16_n']++;
            $this->_addRawDataFlag($record, 'kz_16_n');

            if ($record['befund_lk'] == '1') {
                $data['kz_16_z']++;
                $this->_addRawDataFlag($record, 'kz_16_z');
            }
        }
    }


    /**
     * _checkKz17
     * ('cpz_lokal_fortgeschritten' = 1 && 'Therapie des Bezugsdatums' = definitive perkutane Strahlentherapie / 'durchgef. antih. Th.' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz17(array &$data, array $record)
    {
        if ($this->_cpz_lokal_fortgeschritten($record) == 1 && $this->_is_bezug_perk($record) === true) {
            $data['kz_17_n']++;
            $this->_addRawDataFlag($record, 'kz_17_n');

            if ($record['durchgef_antihth'] == '1') {
                $data['kz_17_z']++;
                $this->_addRawDataFlag($record, 'kz_17_z');
            }
        }
    }


    /**
     * _checkKz18
     * ('cpz_ges' = 1 && 'durchgeführte perkutane Str.' = 1 && 'pN' = pN1 / 'durchgef. antih. Th.' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz18(array &$data, array $record)
    {
        if ($this->_cpz_ges($record) == '1' && $record['perkutane_str'] == '1' && $record['pn'] == 'pN1') {
            $data['kz_18_n']++;
            $this->_addRawDataFlag($record, 'kz_18_n');

            if ($record['durchgef_antihth'] == '1') {
                $data['kz_18_z']++;
                $this->_addRawDataFlag($record, 'kz_18_z');
            }
        }
    }


    /**
     * _checkKz19
     * ('PSA-Rezidiv und SRT nach RPE' = 1 / 'Gesamt-PSA' < 0,5)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz19(array &$data, array $record)
    {
        if ($record['psa_rpe'] == '1') {
            $data['kz_19_n']++;
            $this->_addRawDataFlag($record, 'kz_19_n');

            if ($record['gesamt_psa'] !== null && $record['gesamt_psa'] < 0.5) {
                $data['kz_19_z']++;
                $this->_addRawDataFlag($record, 'kz_19_z');
            }
        }
    }


    /**
     * _checkKz20
     * ("['cpz_niedriges_risiko' =1 ODER 'cpz_mittleres_risiko' =1 ODER 'cpz_hohes_risiko' =1] && 'durchgeführte perkutane Str.' = 1" / 'durchgef. Strahlenth. mit 74 - <80 Gy innerhalb von 56d' = 1)
     *
     * @access  protected
     * @param   array $data
     * @param   array $record

     * @return  bool
     */
    protected function _checkKz20(array &$data, array $record)
    {
        $condition = (
            $this->_cpz_niedriges_risiko($record) == 1 ||
            $this->_cpz_mittleres_risiko($record) == 1 ||
            $this->_cpz_hohes_risiko($record) == 1
        ) && $record['perkutane_str'] == '1';

        if ($condition === true) {
            $data['kz_20_n']++;
            $this->_addRawDataFlag($record, 'kz_20_n');

            if ($record['durchgef_strahlenth_74_80'] == '1') {
                $data['kz_20_z']++;
                $this->_addRawDataFlag($record, 'kz_20_z');
            }
        }
    }


    /**
     * _checkKz21
     *
     * Nenner:
     * (['cpz_niedriges_risiko' = 1 ODER 'cpz_mittleres_risiko' = 1 ODER 'cpz_hohes_risiko' = 1] UND 'RPE' = 1)
     *
     * Zähler:
     * '['Klassifikation nach Clavien-Dindo' = Clavien-Dindo Grad III* ODER 'Klassifikation nach Clavien-Dindo' = Clavien-Dindo Grad IV*]
     * UND
     * Differenz zwischen dem "Datum des Auftretens" dieser Komplikation und dem vorangegangenen Eingriff mit ('RPE' = 1) ? 6 Monate
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  bool
     */
    protected function _checkKz21(array &$data, array $record)
    {
        $condition = (
            $this->_cpz_niedriges_risiko($record) == 1 ||
            $this->_cpz_mittleres_risiko($record) == 1 ||
            $this->_cpz_hohes_risiko($record) == 1
        ) && $this->_has_rpe($record) === true;

        if ($condition === true) {
            $data['kz_21_n']++;
            $this->_addRawDataFlag($record, 'kz_21_n');

            $dindo = $this->_buildClavienDindo($record, 'clavien_dindo_all');

            // check dindo value
            if (str_starts_with($dindo['raw'], array('clavien3', 'clavien4')) === true) {
                $rpeRecords = $this->recordStringToArray($record['bezug_rpe_kompl'], array('eingriff_id', 'datum'));

                // check all rpe records (normally the prostate should be only removed once...)
                foreach ($rpeRecords as $rpe) {
                    if ($this->_checkWithin6Months($dindo['date'], $rpe['datum']) === true) {
                        $data['kz_21_z']++;
                        $this->_addRawDataFlag($record, 'kz_21_z');

                        break;
                    }
                }
            }
        }
    }


    /**
     * _checkKz22
     *
     * Nenner:
     * ['cpz_niedriges_risiko' = 1 ODER 'cpz_mittleres_risiko' = 1 ODER 'cpz_hohes_risiko' = 1]
     * UND
     * ['definitive Strahlentherapie' = 1 ODER 'durchgeführte adjuvante Strahlentherapie' = 1]
     *
     * Zähler:
     * '['Klassifikation nach CTCAE' = CTCAE Grad 3 ODER 'Klassifikation nach CTCAE' = CTCAE Grad 4]
     * UND
     * Differenz zwischen dem "Datum des Auftretens" der Komplikation und der vorangegangenen ['definitive Strahlentherapie' = 1 ODER 'durchgeführte adjuvante Strahlentherapie' = 1] ? 6 Monate
     *
     * @access  protected
     * @param   array $data
     * @param   array $record
     * @return  void
     */
    protected function _checkKz22(array &$data, array $record)
    {
        $condition = (
            $this->_cpz_niedriges_risiko($record) == 1 ||
            $this->_cpz_mittleres_risiko($record) == 1 ||
            $this->_cpz_hohes_risiko($record) == 1
        ) && (
            $record['def_strahlentherapie'] == '1' ||
            $record['durchgef_adj_strahlenth'] == '1'
        );

        if ($condition === true) {
            $data['kz_22_n']++;
            $this->_addRawDataFlag($record, 'kz_22_n');

            $ctcae = $this->_buildCtcae($record, 'ctcae_all');

            // check ctcae value
            if (str_starts_with($ctcae['raw'], array('ctcae3', 'ctcae4')) === true) {
                $compareRecords = array_merge(
                    $this->recordStringToArray($record['def_strahlentherapie_datum'], array('datum')),
                    $this->recordStringToArray($record['durchgef_adj_strahlenth_datum'], array('datum'))
                );

                // check each therapy if within 6 months
                foreach ($compareRecords as $cRecord) {
                    if ($this->_checkWithin6Months($ctcae['date'], $cRecord['datum']) === true) {
                        $data['kz_22_z']++;
                        $this->_addRawDataFlag($record, 'kz_22_z');
                        break;
                    }
                }
            }
        }
    }


    /**
     * _check_cpz_relapse_first_metastasis_part
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _check_cpz_relapse_first_metastasis_part(array $record)
    {
        $condition = (
            $this->_has_active_surveillance($record) === true ||
            $this->_has_watchful_waiting($record) === true ||
            $this->_has_rpe($record) === true ||
            $this->_has_rze($record) === true ||
            $this->_has_perk($record) === true ||
            $this->_has_seed($record) === true ||
            $this->_has_hdr($record) === true ||
            $this->_has_cpz_andere_lokale_therapie($record) == '1' ||
            $this->_has_cpz_andere_behandlung($record) == '1' ||
            $this->_has_cpz_ausschliesslich_systemisch($record) == '1'
        );

        return $condition;
    }


    /**
     * _addRawDataFlag
     *
     * @access  protected
     * @param   array  $record
     * @param   string $field
     * @param   int    $value
     * @return  reportContentP02
     */
    protected function _addRawDataFlag(array $record, $field, $value = 1)
    {
        // only set if is filled
        if (strlen(trim($value)) > 0) {
            $ident = $this->_buildIdent($record);

            $this->_rawData[$ident][$field] += $value;
        }

        return $this;
    }
}

?>
