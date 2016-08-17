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

class reportExtensionH extends reportMath
{
    /**
     * _primaryCases
     *
     * @access  protected
     * @var     array
     */
    protected $_primaryCases;


    /**
     * _takeNewestLk
     *
     * @access  protected
     * @param   string  $lk
     * @return  string
     */
    protected function _takeNewestLk($lk)
    {
        $return = null;

        if (strlen($lk) > 0) {
            $lk = explode(',', $lk);

            $tmp = array();

            foreach ($lk as $entry) {
                $set = explode('|', $entry);
                $date = $set[0];
                $type = $set[1];
                $rm   = $set[2];

                $tmp[$date][$type] = $rm;
            }

            krsort($tmp);

            foreach ($tmp as &$tmpDataset) {
                ksort($tmpDataset);
            }

            $return = reset(reset($tmp));
        }

        return $return;
    }


    /**
     * _countLk
     *
     * @access  protected
     * @param   string  $lk
     * @return  string
     */
    protected function _countLk($lk)
    {
        return "GROUP_CONCAT(DISTINCT IF(h.{$lk} IS NOT NULL, CONCAT_WS('|', h.datum, h.art, h.{$lk}), NULL))";
    }


    /**
     * _eingriffCase
     *
     * @access  protected
     * @param   string  $codes
     * @return  string
     */
    protected function _eingriffCase($codes)
    {
        $cases = array();

        foreach ($codes as $case) {
            $cases[] = "LOCATE('{$case}', op.ops_codes) != 0";
        }

        $cases = implode(' OR ', $cases);

        return "IF({$cases}, 1, NULL)";
    }


    /**
     * _detectPrimaryCases
     *
     * @access  protected
     * @param   string  $preQuery
     * @return  array
     */
    protected function _detectPrimaryCases($preQuery)
    {
        $result = $this->_primaryCases;

        if ($result === null) {
            $result = array();

            $primaryCaseQuery = "
                SELECT
                    sit.*,
                    MIN(h.datum) AS 'bezugsdatum',

                    IF(sit.diagnose LIKE 'C43%' AND
                        ((sit.morphologie != '8247/3' AND
                        sit.morphologie != '9120/3' AND
                        sit.morphologie != '8832/3' AND
                        sit.morphologie != '8833/3') OR
                        sit.morphologie IS NULL),
                        1,
                        0
                     )                                      AS invasives_malignom,

                    IF(
                        sit.diagnose LIKE 'C44%' AND
                        (sit.morphologie LIKE '805_/3' OR
                         sit.morphologie LIKE '806_/3' OR
                         sit.morphologie LIKE '807_/3' OR
                         sit.morphologie LIKE '808_/3' OR
                         sit.morphologie LIKE '809_/3' OR
                         sit.morphologie LIKE '810_/3' OR
                         sit.morphologie LIKE '811_/3'
                        ),
                        1,
                        0
                    )                                        AS epithelialer_tumor,

                    -- wird unten nochmal nachvearbeitet
                    IF(
                        sit.morphologie = '8247/3' OR
                        sit.morphologie = '9120/3' OR
                        sit.morphologie = '8832/3' OR
                        sit.morphologie = '8833/3',
                        1,
                        0
                    )                                        AS seltene_tumore
                FROM ($preQuery) sit
                    {$this->_innerStatus()}
                    {$this->_statusJoin('histologie h')}
                WHERE
                    {$this->_getNcState()} AND sit.anlass = 'p'
                GROUP BY
                    sit.patient_id,
                    sit.erkrankung_id,
                    sit.anlass
            ";

            $primaryCases = array();

            $records = sql_query_array($this->_db, $primaryCaseQuery);

            $checks = array('invasives_malignom', 'epithelialer_tumor', 'seltene_tumore');

            // first step - check
            foreach ($records as $i => $record) {
                if ($record['seltene_tumore'] == '0') {
                    if (str_starts_with($record['diagnose'], 'C') === true && $record['invasives_malignom'] == '0' && $record['epithelialer_tumor'] == '0') {
                        $records[$i]['seltene_tumore'] = $record['seltene_tumore'] = '1';
                    }
                }

                $patientId = $record['patient_id'];
                $diseaseId = $record['erkrankung_id'];

                $year = substr($record['bezugsdatum'], 0, 4);
                $date = $record['bezugsdatum'];

                if (strlen($year) > 0) {
                    foreach ($checks as $check) {
                        if ($record[$check] == '1') {
                            $primaryCases[$patientId][$check][$year][$date][] = array(
                                'date'      => $date,
                                'relevant'  => $record['erkrankung_relevant_haut'],
                                'diseaseId' => $diseaseId
                            );
                        }
                    }
                }
            }

            foreach ($primaryCases as $types) {
                foreach ($types as $years) {
                    foreach ($years as $diseasesInYear) {
                        $relevantDiseases = array();

                        $diseaseCount = 0;

                        // first check and sort relevant diseases
                        foreach ($diseasesInYear as $diseasesOnSameDate) {
                            if (count($diseasesOnSameDate) > 1) {
                                foreach ($diseasesOnSameDate as $disease) {
                                    $diseaseCount++;

                                    if (strlen($disease['relevant']) > 0) {
                                        $relevantDiseases[$disease['date']][] = $disease['diseaseId'];
                                    }
                                }
                            } else {
                                $diseaseCount++;
                                $disease = reset($diseasesOnSameDate);

                                if (strlen($disease['relevant']) > 0) {
                                    $relevantDiseases[$disease['date']][] = $disease['diseaseId'];
                                }
                            }
                        }

                        // check if only one relevant disease exists
                        if (count($relevantDiseases) === 1) {
                            $rel = reset($relevantDiseases);

                            // and on this date only one disease is marked as relevant
                            if (count($rel) === 1) {
                                $diseaseId = reset($rel);

                                $result[$diseaseId . 'p'] = 1;
                            } else {
                                // when more then one disease is marked as relevant
                                foreach ($rel as $diseaseId) {
                                    $result[$diseaseId . 'p'] = 2;
                                }
                            }
                        } else {
                            // if more then one relevant disease in year exists = mark all as 2
                            foreach ($relevantDiseases as $diseaseDates) {
                                foreach ($diseaseDates as $diseaseId) {
                                    $result[$diseaseId . 'p'] = 2;
                                }
                            }
                        }

                        // mark only one disease as 1
                        if ($diseaseCount === 1 && count($relevantDiseases) === 0) {
                            $disease = reset(reset($diseasesInYear));

                            $result[$disease['diseaseId'] . 'p'] = 1;
                        } elseif ($diseaseCount > 0) {
                            foreach ($diseasesInYear as $diseasesOnSameDate) {
                                foreach ($diseasesOnSameDate as $disease) {
                                    $diseaseId = $disease['diseaseId'];

                                    if (array_key_exists($diseaseId . 'p', $result) === false) {
                                        $result[$diseaseId . 'p'] = 2;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->_primaryCases = $result;
        }

        return $result;
    }


    /**
    * Convert data for gt04.1 report
    *
    * @param $data
    */
   protected function _convertH041ReportData($data)
   {
       foreach ($data as &$dataset) {
          $addon = $dataset['addon'];

          unset(
              $dataset['patient_id'],
              $dataset['erkrankung_id'],
              $dataset['anlass'],
              $dataset['start_date'],
              $addon['section'],
              $dataset['max_ajcc'],
              $dataset['min_ajcc'],
              $dataset['pt_section'],
              $dataset['end_date'],
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
              $dataset['addon']
          );

          $dataset = array_merge($dataset, $addon);
       }

       return $data;
   }
}

?>
