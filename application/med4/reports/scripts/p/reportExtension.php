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

class reportExtensionP extends reportMath
{
    /**
     * row separator
     *
     * @var string
     */
    const SEPARATOR_ROWS = "\x01";


    /**
     * col separator
     *
     * @var string
     */
    const SEPARATOR_COLS = "\x02";


    /**
     * _codings
     *
     * @access  protected
     * @var     array
     */
    protected $_codings = array();


    /**
     * config
     *
     * @access  protected
     * @var     array
     */
    protected $_config;


    /**
     * get l_basic coding
     *
     * @access  protected
     * @param   string  $class
     * @param   string  $code
     * @return  string
     */
    protected function _getCoding($class, $code)
    {
        $coding = null;

        // check class in codings
        if (array_key_exists($class, $this->_codings) === false) {
            foreach (sql_query_array($this->_db, "SELECT code, bez FROM l_basic WHERE klasse = '{$class}'") as $row) {
                $this->_codings[$class][$row['code']] = $row['bez'];
            }
        }

        // get coding for code
        if (array_key_exists($code, $this->_codings[$class]) === true) {
            $coding = $this->_codings[$class][$code];
        }

        return $coding;
    }


    /**
     * _getConfig
     *
     * @access  protected
     * @param   string  $label
     * @return  string
     */
    protected function _getConfig($label)
    {
        if ($this->_config === null) {
            $this->_config = $this->loadConfigs('p01', false, true);
        }

        return $this->_config[$label];
    }


    /**
     * _getReferenceDateTherapyLabel
     *
     * @access  protected
     * @param   string $type
     * @return  string
     */
    protected function _getReferenceDateTherapyLabel($type)
    {
        if ($type !== null) {
            $lMap = array(
                'bezugsdatum_cpz_andere_lokale_therapie'     => 'bezug_cpz_lokale',
                'bezugsdatum_cpz_ausschliesslich_systemisch' => 'bezug_cpz_systemisch',
                'bezugsdatum_cpz_andere_behandlung'          => 'bezug_cpz_andere'
            );

            $coding = array_key_exists($type, $lMap) === true ? $lMap[$type] : $type;

            $type = $this->_getCoding('bezugsdatum_p', $coding);
        }

        return $type;
    }


    /**
     * cpz_1
     *
     * 'Primärfall' = 1 UND
     * ['cT' = cT1* ODER 'cT' = cT2a ODER ['Zufallsbefund' = 1 UND [pT' = pT1* ODER 'pT' = pT2a] ] ]
     * UND ['cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0] ] UND ['M' = cM0 ODER 'M' = pM0] UND 'Gesamt-PSA' ? 10ng/ml UND 'Gleason-Score' ? 6
     *
     * @access
     * @param $data
     * @return int
     */
    protected function _cpz_niedriges_risiko($data)
    {
        $result = 0;

        if ($this->_isPrimary($data) == true &&
            (str_starts_with($data['ct'], 'cT1') || $data['ct'] == 'cT2a'  || $this->_hasRandom($data, 'pt', 'pT1', true) || $this->_hasRandom($data, 'pt', 'pT2a')) &&
            $this->_checkN0($data) === true &&
            $this->_checkM0($data) === true &&
            $data['gesamt_psa'] <= 10 &&
            $data['gleason_score'] <= 6) {

            $result = 1;
        }

        return $result;
    }


    /**
     * cpz_2
     *
     * 'Primärfall' = 1 UND
     * [ ['cT' = cT2b ODER ['Zufallsbefund' = 1 UND 'pT' = pT2b] ] ODER 'Gesamt-PSA' ? 10-20ng/ml ODER 'Gleason-Score' = 7]
     * UND ['cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0] ] UND ['M' = cM0 ODER 'M' = pM0]
     *
     * @access
     * @param $data
     * @return int
     */
    protected function _cpz_mittleres_risiko($data)
    {
        $result = 0;

        if ($this->_cpz_lokal_fortgeschritten($data) == 0 && $this->_cpz_hohes_risiko($data) == 0) {
            if ($this->_isPrimary($data) == true &&
                (
                    ($data['ct'] == 'cT2b' || $this->_hasRandom($data, 'pt', 'pT2b') === true) ||
                    ($data['gesamt_psa'] > 10 && $data['gesamt_psa'] <= 20) ||
                    $data['gleason_score'] == 7
                ) && $this->_checkN0($data) && $this->_checkM0($data) == true) {

                $result = 1;
            }
        }

        return $result;
    }


    /**
     * cpz_3
     *
     * 'Primärfall' = 1 UND
     * [['cT' = cT2c ODER ['Zufallsbefund' = 1 UND 'pT' = pT2c] ] ODER 'Gesamt-PSA' ? 20ng/ml ODER 'Gleason-Score' ? 8]
     * UND ['cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0] ] UND ['M' = cM0 ODER 'M' = pM0]
     *
     * @access
     * @param $data
     * @return int
     */
    protected function _cpz_hohes_risiko($data)
    {
        $result = 0;

        if ($this->_cpz_lokal_fortgeschritten($data) == 0) {
            if ($this->_isPrimary($data) == true &&
                ($data['ct'] == 'cT2c' || $this->_hasRandom($data, 'pt', 'pT2c') ||
                $data['gesamt_psa'] > 20 || $data['gleason_score'] >= 8) &&
                $this->_checkN0andM0($data) == true) {

                $result = 1;
            }
        }

        return $result;
    }


    /**
     * cpz_4
     *
     * 'Primärfall' = 1 UND
     * ['cT' = cT3* ODER 'cT' = cT4* ODER ['Zufallsbefund' = 1 UND ['pT' = pT3* ODER 'pT' = pT4*] ] ]
     * UND ['cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0] ] UND ['M' = cM0 ODER 'M' = pM0]
     *
     * @access
     * @param $data
     * @return int
     */
    protected function _cpz_lokal_fortgeschritten($data)
    {
        $result = 0;

        if ($this->_isPrimary($data) == true &&
            (str_starts_with($data['ct'], 'cT3') || str_starts_with($data['ct'], 'cT4') || $this->_hasRandom($data, 'pt', array('pT3', 'pT4'), true)) &&
            $this->_checkN0andM0($data) == true) {

            $result = 1;
        }

        return $result;
    }


    /**
     * cpz_5
     *
     * 'Primärfall' = 1 UND ['cN' = cN1 ODER ['Zufallsbefund' = 1 UND 'pN' = pN1] ] UND ['M' = cM0 ODER 'M' = pM0]
     *
     * @access  protected
     * @param   array $data
     * @return  int
     */
    protected function _cpz_fortgeschritten(array $data)
    {
        $result = 0;

        if ($this->_isPrimary($data) == true && $this->_checkN1($data) == true && $this->_checkM0($data) == true) {
            $result = 1;
        }

        return $result;
    }


    /**
     * cpz_6
     *
     * 'Primärfall' = 1 UND ['M' = cM1* ODER 'M' = pM1*]
     *
     * @access  protected
     * @param   array $data
     * @return  int
     */
    protected function _cpz_metastasiert(array $data)
    {
        return $this->_isPrimary($data) == true && $this->_checkM1($data) == true ? 1 : 0;
    }


    /**
     * 'Primärfall' = 1 UND 'Therapie des Bezugsdatums' ist gefüllt
     *
     * @access  protected
     * @param   $data
     * @return  string
     */
    protected function _cpz_ges(array $data)
    {
        $result = '0';

        if ($data['primaerfall'] == '1' && $data['therapie_bezugsdatum'] !== null) {
            $result = '1';
        }

        return $result;
    }


    /**
     * cpz_7
     *
     * Mind. ein dokumentiertes rec.therapie_systemisch mit der Vorlage
     * [andere systemische Therapie || antihormonelle Therapie || Chemotherapie || Chemo-/Immuntherapie || Immuntherapie]
     *
     * @access  protected
     * @param   array   $record
     * @return  string
     */
    protected function _cpz_ausschliesslich_systemisch(array $record)
    {
        return $record['cpz_ausschliesslich_systemisch'] !== null ? '1' : '0';
    }


    /**
     * has_cpz_ausschliesslich_systemisch
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _has_cpz_ausschliesslich_systemisch(array $record)
    {
        return ($record['cpz_ausschliesslich_systemisch'] == '1');
    }


    /**
     * 'sonstige Therapie' = andere lokale Therapie || HIFU-Therapie (Hochintensiver fokussierter Ultraschall)
     *
     * @access      protected
     * @param       array $data
     * @return      string
     */
    protected function _cpz_andere_lokale_therapie(array $data)
    {
        return $data['cpz_andere_lokale_therapie'] !== null ? '1' : '0';
    }


    /**
     * _has_cpz_andere_lokale_therapie
     *
     * @access      protected
     * @param       array $data
     * @return      string
     */
    protected function _has_cpz_andere_lokale_therapie(array $data)
    {
        return ($data['cpz_andere_lokale_therapie'] == '1');
    }


    /**
     * 'sonstige Therapie' = ST ||  CYRO || HYPER || OT
     * ODER 'palliative Strahlentherapie' = 1
     * ODER 'palliative Versorgung' = 1
     * ODER mind. ein dokumentiertes rec.therapie_systemisch oder rec.strahlentherapie mit der Vorlage [andere systemische Therapie/Radiotherapie ODER Chemo-/Radiotherapie ODER Immun-/Radiotherapie ODER Strahlentherapie mit antihormoneller Therapie ODER Schmerztherapie]
     *
     * @access  protected
     * @param   array $data
     * @return  string
     */
    protected function _cpz_andere_behandlung(array $data)
    {
        return $data['cpz_andere_behandlung'] !== null ? '1' : '0';
    }


    /**
     * _has_cpz_andere_behandlung
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_cpz_andere_behandlung(array $data)
    {
        return ($data['cpz_andere_behandlung'] == '1');
    }



    /**
     * _isPrimary
     *
     * @access protected
     * @param  array $data
     * @return bool
     */
    protected function _isPrimary(array $data)
    {
        return ($data['primaerfall'] == '1');
    }


    /**
     * _isRelapse
     *
     * @access  protected
     * @param   array $record
     * @return  bool
     */
    protected function _isRelapse(array $record)
    {
        return str_starts_with($record['anlass_case'], 'r');
    }


    /**
     * _hasRandom
     *
     * @access  protected
     * @param   array        $data
     * @param   string       $field
     * @param   array|string $values
     * @param   bool         $starts
     * @return  bool
     */
    protected function _hasRandom($data, $field, $values, $starts = false)
    {
        $result = false;

        if (is_array($values) === false) {
            $values = array($values);
        }

        if ($data['zufallsbefund'] == '1') {
            foreach ($values as $val) {
                if ($starts === false) {
                    if ($data[$field] == $val) {
                        $result = true;
                        break;
                    }
                } else {
                    if (str_starts_with($data[$field], $val)) {
                        $result = true;
                        break;
                    }
                }
            }
        }

        return $result;
    }


    /**
     * _has_rpe_rze
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_rpe_rze(array $data)
    {
        return strlen($data['rpe_rze']) > 0;
    }


    /**
     * _has_radio
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_radio(array $data)
    {
        return $data['def_strahlentherapie'] == '1';
    }


    /**
     * _has_active_surveillance
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_active_surveillance(array $data)
    {
        return $data['active_surveillance'] == '1';
    }


    /**
     * _has_watchful_waiting
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_watchful_waiting(array $data)
    {
        return $data['watchful_waiting'] == '1';
    }


    /**
     * checks if any intervention (AS, WW, RPE, RZE, radio, other_interventions) was performed
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _has_any_intervention($data)
    {
        return ($this->_has_active_surveillance($data) == true ||
            $this->_has_watchful_waiting($data) == true ||
            $this->_has_rpe_rze($data) == true ||
            $this->_has_radio($data) == true ||
            $this->_cpz_ausschliesslich_systemisch($data) == '1'
        );
    }


    /**
     * 'cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0]
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkN0($data)
    {
        return $data['cn'] == 'cN0' || ($data['zufallsbefund'] == '1' && $data['pn'] == 'pN0');
    }


    /**
     * 'M' = cM0 ODER 'M' = pM0
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkM0($data)
    {
        return str_ends_with($data['m'], '0');
    }


    /**
     * praeop 'M' = 0
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkPraeopM0($data)
    {
        return str_ends_with($data['prae_m'], '0');
    }


    /**
     * ['cN' = cN0 ODER ['Zufallsbefund' = 1 UND 'pN' = pN0] ] UND ['M' = cM0 ODER 'M' = pM0]
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkN0andM0($data)
    {
        return $this->_checkM0($data) && $this->_checkN0($data);
    }


    /**
     * 'cN' = cN1 ODER ['Zufallsbefund' = 1 UND 'pN' = pN1
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkN1($data)
    {
        return $data['cn'] == 'cN1' || ($data['zufallsbefund'] == '1' && $data['pn'] == 'pN1');
    }


    /**
     * 'M' = cM1* ODER 'M' = pM1*
     *
     * @access
     * @param $data
     * @return bool
     */
    protected function _checkM1($data)
    {
        return substr($data['m'], 1, 2) == 'M1';
    }


    /**
     * 'M prätherapeutisch' = c/pM0
     * UND ['definitive perkutane Strahlentherapie' = 1 ODER 'Str. permanent seed' = 1 ODER 'HDR-Brachytherapie' = 1]
     * UND es ist kein 'Datum Rezidiv' innerhalb von 365 Tagen nach Beginn DIESER Therapie dokumentiert
     * UND es ist eine Nachsorge innerhalb von 365 Tagen nach Beginn DIESER Therapie dokumentiert
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _isOneYearTumorFree(array $data)
    {
        $tumorFree = false;

        if ($this->_checkPraeopM0($data) == true) {
            // get starting time of specific therapy types
            $therapyDates =  array_filter(array(
                $data['def_perkutane_str_beginn'],
                $data['str_permanent_seed'],
                $data['hdr_brachytherapie']
            ));

            $rezidivDates   = array_filter(explode('|', $data['rezidiv_datum']));
            $aftercareDates = array_filter(explode(self::SEP_ROWS, $data['aftercare_dates']));

            // check required therapy and aftercare dates
            if (count($therapyDates) > 0 && count($aftercareDates) > 0) {

                // iterate over all therapy dates
                foreach ($therapyDates as $therapyDate) {

                    // check rezidiv within 365 day, if none exists, it's ok
                    foreach ($rezidivDates as $rezidivDate) {
                        $rezidivDate = reset(explode(',', $rezidivDate));

                        $daysBetweenTherapyAndRezidiv = date_diff_days($therapyDate, $rezidivDate);

                        // if rezidiv within 365 day, return false
                        if ($daysBetweenTherapyAndRezidiv > 0 && $daysBetweenTherapyAndRezidiv <= 365) {
                            return false;
                        }
                    }

                    // now check after care dates against therapy dates, there must min one exists
                    foreach ($aftercareDates as $aftercareDate) {
                        // find an aftercaredate between starting date of therapy and rezidiv date

                        $daysBetweenTherapyAndAftercare = date_diff_days($therapyDate, $aftercareDate);

                        if ($daysBetweenTherapyAndAftercare > 0 && $daysBetweenTherapyAndAftercare <= 365) {

                            // after care exists so check next therapy, but mark as true
                            $tumorFree = true;
                            continue 2;
                        }
                    }
                }
            }
        }

        return $tumorFree;
    }


    /**
     * _filterRelapses
     * (filter all given records and check required fields)
     * (minimum one filled field must be set)
     * (data will filtered to: one relapse per disease and youngest 'datum_sicherung'
     *
     * @access  protected
     * @param   array  $records
     * @param   string $year
     * @param   array  $filled
     * @return  array
     */
    protected function _filterRelapses(array $records, $year, array $filled)
    {
        $filter = array();

        foreach ($records as $record) {
            $date = $record['bezugsdatum'];

            // check relapse and selected year
            if ($this->_isRelapse($record) === true && substr($date, 0, 4) === $year) {

                // check if min one field is filled
                foreach ($filled as $field) {
                    if (strlen($record[$field]) > 0) {
                        $sortDate = $record['datum_sicherung'];

                        $filter[$record['erkrankung_id']][$sortDate] = $record;

                        continue 2; // go to next record
                    }
                }
            }
        }

        // map to 1 -> 1
        foreach ($filter as $diseaseId => $relapses) {

            // order first
            ksort($relapses);

            $filter[$diseaseId] = reset($relapses);
        }

        return $filter;
    }


    /**
     * _is_bezug_as
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_as(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_as');
    }


    /**
     * _is_bezug_ww
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_ww(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_ww');
    }


    /**
     * _is_bezug_rpe
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_rpe(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_rpe');
    }


    /**
     * _is_bezug_rze
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_rze(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_rze' && $data['zufallsbefund'] != '1');
    }


    /**
     *_is_bezug_rze_with_random
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_rze_with_random(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_rze' && $data['zufallsbefund'] == '1');
    }


    /**
     * _is_bezug_perk
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_perk(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_perk');
    }


    /**
     * _is_bezug_seed
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_seed(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_seed');
    }


    /**
     * _is_bezug_hdr
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_hdr(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezug_hdr');
    }


    /**
     * _is_bezug_cpz_andere_lokale_therapie
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_cpz_andere_lokale_therapie(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezugsdatum_cpz_andere_lokale_therapie');
    }


    /**
     * _is_bezug_cpz_ausschliesslich_systemisch
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_cpz_ausschliesslich_systemisch(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezugsdatum_cpz_ausschliesslich_systemisch');
    }


    /**
     * _is_bezug_cpz_andere_behandlung
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _is_bezug_cpz_andere_behandlung(array $data)
    {
        return ($data['therapie_bezugsdatum'] === 'bezugsdatum_cpz_andere_behandlung');
    }


    /**
     * _has_rpe
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_rpe(array $data)
    {
        return ($data['rpe'] !== null);
    }


    /**
     * _has_rze
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_rze(array $data)
    {
        return ($data['rze'] !== null);
    }


    /**
     * _has_rze_with_incidental_finding
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_rze_with_incidental_finding(array $data)
    {
        return ($data['rze'] !== null && $data['zufallsbefund'] !== null);
    }


    /**
     * _has_rze_without_incidental_finding
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_rze_without_incidental_finding(array $data)
    {
        return ($data['rze'] !== null && $data['zufallsbefund'] == null);
    }


    /**
     * _has_perk
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_perk(array $data)
    {
        return ($data['def_perkutane_strahlentherapie'] !== null);
    }


    /**
     * _has_seed
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_seed(array $data)
    {
        return ($data['str_permanent_seed'] !== null);
    }


    /**
     * _has_hdr
     *
     * @access  protected
     * @param   array $data
     * @return  bool
     */
    protected function _has_hdr(array $data)
    {
        return ($data['hdr_brachytherapie'] !== null);
    }


    /**
     * _buildClavienDindo
     *
     * @access  protected
     * @param   array  $record
     * @param   string $field
     * @return  string
     */
    protected function _buildClavienDindo(array $record, $field = 'clavien_dindo')
    {
        $value = $record[$field];
        $dindo = array(
            'value' => null,
            'date'  => null,
            'raw'   => null
        );

        if ($value !== null) {
            $tmp = array();

            // build array for checking earliest date
            foreach (explode('|', $value) as $data) {
                $tmp[substr($data, 0, 10)][substr($data, 18)] = substr($data, 11);
            }

            // sort asc date
            ksort($tmp);

            $dates = array_keys($tmp);

            $dindo['date'] = reset($dates);

            // pick first date
            $tmp = reset($tmp);

            // if more than one complication, than highest dindo-degree
            krsort($tmp);

            $dindoValue = reset($tmp);

            $dindo['raw']   = $dindoValue;
            $dindo['value'] = $this->_getCoding('klassifikation_clavien_dindo', $dindoValue);
        }

        return $dindo;
    }



    /**
     * _buildCtcae
     *
     * @access  protected
     * @param   array  $record
     * @param   string $field
     * @return  string
     */
    protected function _buildCtcae(array $record, $field = 'ctcae')
    {
        $value = $record[$field];
        $ctcae = array(
            'value' => null,
            'date'  => null,
            'raw'   => null
        );

        if ($value !== null) {
            $tmp = array();

            foreach (explode('|', $value) as $data) {
                $tmp[substr($data, 0, 10)][substr($data, 16)] = substr($data, 11);
            }

            // sort asc date
            ksort($tmp);

            $dates = array_keys($tmp);

            $ctcae['date'] = reset($dates);

            // pick first date
            $tmp = reset($tmp);

            // if more than one complication, than highest ctcae
            krsort($tmp);

            $ctcaeValue = reset($tmp);

            $ctcae['raw']   = $ctcaeValue;
            $ctcae['value'] = $this->_getCoding('klassifikation_ctcae', $ctcaeValue);
        }

        return $ctcae;
    }



    /**
     * Sorts an array, by given field
     *
     * @access protected
     * @param  array  $array
     * @param  string $sortField
     * @param  bool   $asc
     * @return array
     */
    protected function _sortArray(array $array, $sortField, $asc = true)
    {
        $tmp = array();
        $sortedArray = array();

        foreach ($array as $dataset) {
            $tmp[$dataset[$sortField]][] = $dataset;
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


    /**
     * _getFirstRecord
     *
     * @access  protected
     * @param   string  $value
     * @param   array   $fields
     * @param   string  $orderField
     * @return  array
     */
    protected function _getFirstRecord($value, array $fields, $orderField)
    {
        $record = null;

        if (strlen($value) > 0) {
            $records = $this->recordStringToArray($value, $fields);

            $record = reset($this->_sortArray($records, $orderField));
        }

        return $record;
    }
}

?>
