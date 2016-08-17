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

class reportExtensionLu extends reportMath
{

    protected $_severityCheck = array('uicc' => array(), 'm' => array(), 'n' => array(), 't' => array());


    public function __construct($renderer, $db, $smarty, $subdir, $type, $params = null)
    {
        $this->_prepareSeverityStaging($db);

        parent::__construct($renderer, $db, $smarty, $subdir, $type, $params);
    }

    protected function _uArtCount($codes)
    {
        $codes = is_array($codes) ? $codes : array($codes);
        $args = array();

        foreach ($codes as $code) {
            $args[] = "LOCATE('{$code}', u.art) != 0";
        }

        $statement = 'COUNT(DISTINCT IF(' . implode(' OR ', $args) . ', u.untersuchung_id, NULL))';

        return $statement;
    }


    protected function _eingriffCase($codes, $field = 'op.ops_codes')
    {
        $casesBase = array();

        foreach ($codes as $case) {
            $casesBase[] = "LOCATE('{$case}', $field) != 0";
        }

        $cases = implode(' OR ', $casesBase);

        return "IF({$cases}, 1, NULL)";
    }

    /**
     * Priorisierung für relevante faelle
     *
     * Priorität 1: Tumorstatus mit dem höchsten UICC-Stadium ("IVC" absteigend bis "0"; "okkultes Karzinom" zählt als leer)
     * Priorität 2: Tumorstatus mit dem höchsten M ("M1c" absteigend bis "M0"; hierbei ist das Präfix "c" oder "p" bedeutungslos; "MX" zählt als leer)
     * Priorität 3: Tumorstatus mit dem höchsten N ("N3c" absteigend bis "N0"; hierbei ist das Präfix "c" oder "p" bedeutungslos; "NX" zählt als leer)
     * Priorität 4: Tumorstatus mit dem höchsten T ("T4d" absteigend bis "T0", anschließend "Tis"; hierbei ist das Präfix "c" oder "p" bedeutungslos; "TX" zählt als leer)
     */

    /**
     *
     * @param type $db
     * @return void
     */
    protected function _prepareSeverityStaging($db)
    {
        foreach ($this->_severityCheck as $type => $dummy) {
            $i = $type;

            if ($type !== 'uicc') {
                $type = "c{$type}', 'p{$type}";
            }

            foreach (sql_query_array($db, "SELECT code, bez, kennung from l_basic WHERE klasse IN('{$type}')") as $l) {
                $l['code'] = $l['code'] == 'ok' ? $l['bez'] : $l['code']; //Okkultes Karzinom label
                $this->_severityCheck[$i][$l['code']] = $l['kennung'];
            }
        }
    }


    /**
     * @param array $firstCase
     * @param array $secondCase
     * @param null  $thirdCase
     * @return array
     */
    protected function _removeLowerSeverityPrimaryCase($firstCase, $secondCase, $thirdCase = null)
    {
        $unsetIndex = array();

        if ($thirdCase !== null) {
           $remIndex = reset($this->_removeLowerSeverityPrimaryCase($secondCase, $thirdCase));

           if ($secondCase['dataIndex'] == $remIndex) {
              $unsetIndex[] = $remIndex;
              $secondCase = $thirdCase;
           } else {
              $unsetIndex[] = $thirdCase['dataIndex'];
           }
        }

        $recordA = $firstCase['data'];
        $recordB = $secondCase['data'];

        foreach ($this->_severityCheck as $check => $group) {
            $valueA = strlen($recordA[$check]) > 0 ? $recordA[$check] : null;
            $valueB = strlen($recordB[$check]) > 0 ? $recordB[$check] : null;

            // Both values are empty, check next
            if ($valueA === null && $valueB === null) {
                continue;
            }

            // value of second record is empty
            if ($valueB === null) {
                $unsetIndex[] = $secondCase['dataIndex'];
                break;
            }

            //value of first record is empty
            if ($valueA === null) {
                $unsetIndex[] = $firstCase['dataIndex'];
                break;
            }

            $posA = $group[$valueA];
            $posB = $group[$valueB];

            if ($posA > $posB) {
                $unsetIndex[] = $secondCase['dataIndex'];
                break;
            }

            if ($posA < $posB) {
                $unsetIndex[] = $firstCase['dataIndex'];
                break;
            }
        }

        //Wenn alles leer, nimm den zweiten.... hier ist es zufaellig was links oder rechts ist
        if (count($unsetIndex) == 0) {
            $unsetIndex[] = $secondCase['dataIndex'];
        }

        return $unsetIndex;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $primaryCases
     * @return mixed
     */
    protected function _correctPrimaryCases($data, $primaryCases) {

        // Bilaterale Primaerfalle filtern (es darf nur einen Primaerfall geben)
        foreach ($primaryCases as $primaryCaseIndex) {
            if (count($primaryCaseIndex) > 1) {
                $indexFirst  = $primaryCaseIndex[0];
                $indexSecond = $primaryCaseIndex[1];
                $indexThird  = array_key_exists(2, $primaryCaseIndex) === true ? $primaryCaseIndex[2] : null;

                $sPrimaryCaseIndex = $this->_removeLowerSeverityPrimaryCase(
                    array(
                        'dataIndex' => $indexFirst,
                        'data' => $data[$indexFirst]
                    ),
                    array(
                        'dataIndex' => $indexSecond,
                        'data' => $data[$indexSecond]
                    ), ($indexThird !== null
                        ? array(
                            'dataIndex' => $indexThird,
                            'data' => $data[$indexThird]
                        )
                        : null
                    )
                );

                foreach ($sPrimaryCaseIndex as $index) {
                    $data[$index]['primaerfall'] = '2';
                }
            }
        }

        return $data;
    }


    /**
     *
     *  u = Untersuchungen (mit Datum)
     *  e = eingriff Ids
     *
     */
    protected function _calcThoraskopien($u, $e, $eIdent)
    {
        $eingriffe      = array();
        $count          = 0;

        if (strlen($eIdent) > 0) {
            foreach (explode(',', $eIdent) as $eingriff) {
                $tmp = explode('|', $eingriff);

                $eingriffe[reset($tmp)] = array(
                    'date' => end($tmp),
                    'codes' => array()
                );
            }
        }

        if (strlen($e) > 0) {
            foreach (explode('~#~', $e) as $eingriff) {
                $tmp = explode('#;#', $eingriff);
                $eingriffId = reset($tmp);

                if (array_key_exists($eingriffId, $eingriffe) === true) {
                    $eingriffe[$eingriffId]['codes'] = explode('+#+', end($tmp));
                }
            }
        }

        foreach ($eingriffe as $eingriffId => $content) {
            if (count($content['codes']) == 0) {
                unset($eingriffe[$eingriffId]);
            }
        }

        if (strlen($u) > 0) {
            foreach (explode('~#~', $u) as $untersuchung) {
                $tmp  = explode('#;#', $untersuchung);
                $date = reset($tmp);
                $code = end($tmp);

                //Wenn eine Untersuchung am gleichen Tag und mit dem gleichen OPS Code gemacht wurde
                foreach ($eingriffe as $eingriff) {
                    if ($date == $eingriff['date'] && in_array($code, $eingriff['codes']) === true) {
                        continue 2;
                    }
                }

                $count++;
            }
        }

        return ($count += count($eingriffe));
    }

    /**
      * Convert data for lu04.1 report
      *
      * @param $data
      */
    protected function _convertLu041ReportData($data)
    {
        foreach ($data as &$dataset) {
            $addon = $dataset['addon'];

            unset(
                $dataset['patient_id'],
                $dataset['addon'],
                $dataset['anlass'],
                $dataset['pt_section'],
                $dataset['h_beginn'],
                $dataset['041_ereignis'],
                $dataset['041_ende'],
                $dataset['042_ereignis'],
                $dataset['042_ende'],
                $dataset['043_ereignis'],
                $dataset['043_ende'],
                $dataset['044_ereignis'],
                $dataset['044_ende'],
                $dataset['045_ereignis'],
                $dataset['045_beginn'],
                $dataset['045_ende'],
                $dataset['endo_thora_untersuchungen'],
                $dataset['endo_thora_eingriffe'],
                $dataset['endo_thora_eingriff_identifier'],
                $dataset['primaerop_eingriff_id'],
                $dataset['erkrankung_id'],
                $dataset['max_uicc'],
                $dataset['start_date'],
                $dataset['end_date'],
                $addon['section']
             );

             $dataset = array_merge($dataset, $addon);
         }

         return $data;
     }


    /**
     *
     *
     * @access
     * @return string
     */
    protected function _getLungenresektionsCodes() {
        return $this->_eingriffCase(explode('|', '5-323|5-324|5-325|5-326|5-327|5-328'), 'eo.prozedur');
    }


    /**
     * _detectPrimaryCases
     *
     * @access  protected
     * @param   string  $preQuery
     * @param stageCalc $stageCalc
     * @return  array
     */
    protected function _detectPrimaryCases($preQuery, stageCalc $stageCalc)
    {
        $result = array();
        $primaryCaseQuery = "
            SELECT
                sit.*,
                MIN(h.datum) AS 'bezugsdatum',
                IF (
                    COUNT(th_sys.therapie_systemisch_id) > 0 OR
                    COUNT(th_str.strahlentherapie_id) > 0 OR
                    COUNT(th_son.sonstige_therapie_id) > 0 OR
                    COUNT(IF(tp.palliative_versorgung = '1', 1, NULL)) > 0
                , 1, NULL)                                                        AS 'primaerfall',

                COUNT(DISTINCT IF(
                  op.art_primaertumor IS NOT NULL,
                  (SELECT eo.eingriff_id FROM eingriff_ops eo
                   WHERE
                       eo.eingriff_id = op.eingriff_id AND eo.prozedur_seite IN ('B', sit.diagnose_seite) AND
                       ({$this->_getLungenresektionsCodes()})
                   GROUP BY
                       eo.eingriff_id
                  ),
                  NULL
                ))                                                                AS 'primaerfall_op',
                sit.t                                                             AS 't',
                sit.n                                                             AS 'n',
                sit.m                                                             AS 'm',
                sit.uicc                                                          AS 'uicc'

            FROM ($preQuery) sit
                {$this->_innerStatus()}
                LEFT JOIN eingriff op ON s.form = 'eingriff' AND op.eingriff_id = s.form_id
                                                             AND op.diagnose_seite IN ('B', sit.diagnose_seite)
                {$this->_statusJoin('histologie h')}
                {$this->_statusJoin('therapie_systemisch th_sys')}
                {$this->_statusJoin('strahlentherapie th_str')}
                {$this->_statusJoin('sonstige_therapie th_son')}
                {$this->_statusJoin('therapieplan tp')}
            WHERE
                {$this->_getNcState()} AND sit.anlass = 'p'
            GROUP BY
                sit.patient_id,
                sit.erkrankung_id,
                sit.anlass,
                sit.diagnose_seite
        ";

        // first step - collect erkrankung ids
        $erkrankungIds = array(0);
        foreach (sql_query_array($this->_db, $primaryCaseQuery) as $record) {
            if ('1' != $record['erkrankung_relevant']) {
                $erkrankungIds[$record['erkrankung_id']] = $record['erkrankung_id'];
            }

            $record['uicc'] = $stageCalc->calc($record['uicc']);

            if ($record['primaerfall'] === null && $record['primaerfall_op'] !== '0') {
                $record['primaerfall'] = '1';
            }

            $result[$record['erkrankung_id'] . $record['anlass'] . $record['diagnose_seite']] = $record;
        }

        // second step - get erkrankungen synchron
        $erkrankungIds = implode(',', $erkrankungIds);
        $ernkrankungSynchronQuery = "
            SELECT
                erkrankung_id

            FROM
                erkrankung_synchron es

            WHERE
                es.erkrankung_id IN ({$erkrankungIds})
        ";

        $erkrankungenSynchron = sql_query_array($this->_db, $ernkrankungSynchronQuery);

        $noPrimaryCases = array();

        foreach ($erkrankungenSynchron as $erkrankungSynchronId) {
            $noPrimaryCases[$erkrankungSynchronId['erkrankung_id']] = $erkrankungSynchronId['erkrankung_id'];
        }

        // third step - pre check synchron erkarnkungen
        $checkablePrimaryCases = array();
        foreach ($result as $key => $data) {
            if (in_array($data['erkrankung_id'], $noPrimaryCases)) {
                $result[$key]['primaerfall'] = '0';
            }
            $diseaseId = $data['erkrankung_id'];
            if ('1' == $data['primaerfall']) {
                if (false == array_key_exists($diseaseId, $checkablePrimaryCases)) {
                    $checkablePrimaryCases[$diseaseId] = array();
                }
                $checkablePrimaryCases[$diseaseId][] = $key;
            }
        }

        $result = $this->_correctPrimaryCases($result, $checkablePrimaryCases);

        return $result;
    }
}

?>
