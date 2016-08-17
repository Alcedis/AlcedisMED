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

class reportExtensionKh extends reportMath
{
    /**
     * _detectPrimaryCases
     *
     * @access  protected
     * @param   string  $preQuery
     * @return  array
     */
    protected function _detectPrimaryCases($preQuery)
    {
        $result = array();

        $primaryCaseQuery = "
            SELECT
                sit.*,
                MIN(h.datum) AS 'bezugsdatum'
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

        // first step - collect data
        foreach (sql_query_array($this->_db, $primaryCaseQuery) as $record) {
            // no cases without a morphologie
            if (strlen($record['morphologie']) > 0) {
                $primaryCases[$record['patient_id']][$record['morphologie']][$record['bezugsdatum']][$record['erkrankung_id']] = array(
                    'relevant'  => $record['erkrankung_relevant'],
                    'diseaseId' => $record['erkrankung_id']
                );
            }
        }

        // second step - sort entries
        foreach ($primaryCases as &$m) {
            foreach ($m as &$d) {
                ksort($d);
            }
        }

        // third step - detect primary cases
        foreach ($primaryCases as $morphos) {
            foreach ($morphos as $dates) {

                // only 1 date for morpho code exists
                if (count($dates) === 1) {
                    $diseases = reset($dates);

                    // only one disease for "befunddatum" date exists
                    if (count($diseases) === 1) {
                        $disease = reset($diseases);

                        $result[$disease['diseaseId'] . 'p'] = 1;
                    } else {
                        $primaryCase = 1;

                        foreach ($diseases as $disease) {
                            if ($disease['relevant'] == '1') {
                                $result[$disease['diseaseId'] . 'p'] = $primaryCase;

                                if ($primaryCase === 1) {
                                    $primaryCase = 2;
                                }
                            } else {
                                $result[$disease['diseaseId'] . 'p'] = 2;
                            }
                        }
                    }
                } else { // there are several deseases for one morpho code
                    $primaryCase = 1;

                    foreach ($dates as $diseases) {
                        // set first entry to 1 if only one disease for a date exists
                        if (count($diseases) === 1) {
                            $disease = reset($diseases);

                            $result[$disease['diseaseId'] . 'p'] = $primaryCase;

                            if ($primaryCase === 1) {
                                $primaryCase = 2;
                            }
                        } else {
                            foreach ($diseases as $disease) {
                                if ($disease['relevant'] == '1') {
                                    $result[$disease['diseaseId'] . 'p'] = $primaryCase;

                                    if ($primaryCase === 1) {
                                        $primaryCase = 2;
                                    }
                                } else {
                                    $result[$disease['diseaseId'] . 'p'] = 2;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }


    /**
     * checkMundhoehle
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkMundhoehle($record)
    {
        $diagnose = $record['diagnose'];

        return ($diagnose === 'C01' || str_starts_with($diagnose, array('C00', 'C02', 'C03', 'C04', 'C05', 'C06')) === true);
    }


    /**
     * ckhz_mundhoehle_op
     * ('['Diagnose' = C00* ODER 'Diagnose' = C01 ODER 'Diagnose' = C02* ODER 'Diagnose' = C03* ODER 'Diagnose' = C04*
     * ODER 'Diagnose' = C05* ODER 'Diagnose' = C06*] UND 'Primärfall' = 1 UND 'Datum Primär-/Rezidiv-OP' = gefüllt)
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function ckhz_mundhoehle_op($record)
    {
        return ($this->checkMundhoehle($record) && $record['primaerfall'] == '1' && strlen($record['datumprimaer_op']) > 0);
    }


    /**
     * ckhz_mundhoehle_nop
     * ('['Diagnose' = C00* ODER 'Diagnose' = C01 ODER 'Diagnose' = C02* ODER 'Diagnose' = C03* ODER 'Diagnose' = C04*
     * ODER 'Diagnose' = C05* ODER 'Diagnose' = C06*] UND 'Primärfall' = 1 UND 'Datum Primär-/Rezidiv-OP' = leer)
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function ckhz_mundhoehle_nop($record)
    {
        return ($this->checkMundhoehle($record) && $record['primaerfall'] == '1' && strlen($record['datumprimaer_op']) === 0);
    }


    /**
     * checkSonstige
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function checkSonstige($record)
    {
        $diagnose = $record['diagnose'];

        return (in_array($diagnose, array('C12', 'D00.0', 'D02.0')) === true || str_starts_with($diagnose, array('C09', 'C10', 'C11', 'C13', 'C14', 'C32')) === true);
    }


    /**
     * ckhz_sonst_op
     * ['Diagnose' = C09* ODER 'Diagnose' = C10* ODER 'Diagnose' = C11* ODER 'Diagnose' = C12 ODER 'Diagnose' = C13*
     * ODER 'Diagnose' = C14* ODER 'Diagnose' = C32* ODER 'Diagnose' = D00.0 ODER 'Diagnose' = D02.0] UND 'Primärfall' = 1
     * UND 'Datum Primär-/Rezidiv-OP' = gefüllt
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function ckhz_sonst_op($record)
    {
        return ($this->checkSonstige($record) === true && $record['primaerfall'] == '1' && strlen($record['datumprimaer_op']) > 0);
    }


    /**
     * ckhz_sonst_nop
     * ['Diagnose' = C09* ODER 'Diagnose' = C10* ODER 'Diagnose' = C11* ODER 'Diagnose' = C12 ODER 'Diagnose' = C13*
     * ODER 'Diagnose' = C14* ODER 'Diagnose' = C32* ODER 'Diagnose' = D00.0 ODER 'Diagnose' = D02.0] UND 'Primärfall' = 1
     * UND 'Datum Primär-/Rezidiv-OP' = leer
     *
     * @access  public
     * @param   array $record
     * @return  bool
     */
    public function ckhz_sonst_nop($record)
    {
        return ($this->checkSonstige($record) === true && $record['primaerfall'] == '1' && strlen($record['datumprimaer_op']) === 0);
    }


    /**
     * _convertKh041ReportData
     *
     * @access  protected
     * @param   array   $records
     * @return  array
     */
    protected function _convertKh041ReportData($records)
    {
        foreach ($records as &$record) {
            $addon = $record['addon'];

            unset(
                $record['pt_section'],
                $record['patient_id'],
                $record['erkrankung_id'],
                $record['anlass'],
                $record['h_beginn'],
                $record['041_ereignis'],
                $record['041_ende'],
                $record['042_ereignis'],
                $record['042_ende'],
                $record['043_ereignis'],
                $record['043_ende'],
                $record['044_ereignis'],
                $record['044_ende'],
                $record['045_ereignis'],
                $record['045_beginn'],
                $record['045_ende'],
                $record['addon'],
                $addon['uicc'],
                $addon['pt_section'],
                $addon['pt']
            );

            $record = array_merge($record, $addon);


        }

        return $records;
    }


    /**
     * in kh05 only special uicc cases allowed
     *
     * @access  protected
     * @param   string $uicc
     * @param   array  $with
     * @return  bool
     */
    protected function _checkUicc($uicc, $with = array('0', 'I', 'II', 'III', 'IVA', 'IVB', 'IVC'))
    {
        return in_array($uicc, $with);
    }
}

?>
