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

class hl7Message extends hl7Writer
{
    protected $_skipMessage = false;


    /**
     * check
     *
     * @access
     * @param      $type
     * @param      $check
     * @param null $arg
     * @return mixed
     */
    public function check($type, $check, $arg = null)
    {
        $method = "_{$type}Check" . ucfirst($check);

        return $this->{$method}($arg);
    }


    /**
     *
     *
     * @access
     * @param null $skip
     * @return $this|bool
     */
    public function skipMessage($skip = null)
    {
        if ($skip !== null) {
            $this->_skipMessage = $skip;

            return $this;
        }

        return $this->_skipMessage;
    }


    private function _preCheckDiagnose($validDiagnosisDatasets)
    {
        $filter = $this->getFilter('cache');

        $preCacheDiagnose = false;

        if (in_array('diagnose', $filter) === true && $this->skipMessage() === false) {

            $preCacheDiagnose = null;

            $diagnosisToCheck = array();

            if (in_array('diagnosetyp', $filter) === false) {
                $cacheDiagnose = $this->getFieldValue("cache.diagnose");

                if (strlen($cacheDiagnose) > 0) {
                    $diagnoseValues = explode($this->getDelimiter(), $cacheDiagnose);

                    foreach ($diagnoseValues as $value) {
                        $diagnosisToCheck[] = $value;
                    }
                }
            } else {
                $diagnosisToCheck = $validDiagnosisDatasets;
            }

            if (count($diagnosisToCheck) > 0) {
                $diagnoseCondition = $this->getSettings("cache_diagnose_filter");

                $match = false;

                // min one diagnose match must be found
                foreach ($diagnosisToCheck as $diagnose) {
                    $match = (preg_match($diagnoseCondition, $diagnose) == 1);

                    if ($match === true) {
                        $preCacheDiagnose = $diagnose;
                        break;
                    }
                }

                if ($match === false) {
                    $this->skipMessage(true);

                    $this
                        ->addLogFilter('message', 'pre_cache_condition_error')
                        ->addLogFilter('type', 'diagnose')
                        ->addLogFilter('condition', $diagnoseCondition)
                        ->addLogFilter('values' ,implode('--', $diagnosisToCheck))
                        ->setLogStatus('error')
                    ;
                }
            } else {
                $this->skipMessage(true);

                $this
                    ->addLogFilter('message', 'pre_cache_condition_error')
                    ->addLogFilter('type', 'diagnose')
                    ->addLogFilter('condition', 'no DG-1 Segment match')
                    ->addLogFilter('values', '')
                    ->setLogStatus('error')
                ;
            }
        }

        return $preCacheDiagnose;
    }


    /**
     * _preCheckDiagnoseTyp
     *
     * @access
     * @return array
     */
    private function _preCheckDiagnoseType()
    {
        $filter = $this->getFilter('cache');

        $validDiagnosisDatasets = array();

        if (in_array('diagnosetyp', $filter) === true && $this->skipMessage() === false) {
            $cacheDiagnoseTyp = $this->getFieldValue("cache.diagnosetyp");

            if (strlen($cacheDiagnoseTyp) > 0) {
                $match = false;

                $diagnoseValues       = explode($this->getDelimiter(), $this->getFieldValue("cache.diagnose"));
                $diagnoseTypValues    = explode($this->getDelimiter(), $cacheDiagnoseTyp);

                $diagnoseTypCondition = $this->getSettings("cache_diagnosetyp_filter");

                foreach ($diagnoseTypValues as $i => $diagnoseTyp) {
                    $regular = preg_match($diagnoseTypCondition, $diagnoseTyp);

                    if ($regular == 1) {
                        $match = true;

                        $validDiagnosisDatasets[] = $diagnoseValues[$i];
                    }
                }

                if ($match === false) {
                    $this->skipMessage(true);

                    $this
                        ->addLogFilter('message', 'pre_cache_condition_error')
                        ->addLogFilter('type', 'diagnoseType')
                        ->addLogFilter('condition', $diagnoseTypCondition)
                        ->addLogFilter('values', implode('--', $diagnoseTypValues))
                        ->setLogStatus('error')
                    ;
                }
            } else {
                $this->skipMessage(true);

                $this
                    ->addLogFilter('message', 'pre_cache_condition_error')
                    ->addLogFilter('type', 'diagnoseType')
                    ->addLogFilter('condition', 'DG-1 Segment not found')
                    ->addLogFilter('values', '')
                    ->setLogStatus('error')
                ;
            }
        }

        return $validDiagnosisDatasets;
    }


    /**
     * _preCheckDivision
     *
     * @access
     * @return $this
     */
    private function _preCheckDivision()
    {
        $filter = $this->getFilter('cache');

        // Step - Check devision
        if (in_array('abteilung', $filter) === true) {
            $cacheDivision = $this->getFieldValue("cache.abteilung");

            if (strlen($cacheDivision) > 0) {
                $divisionValues    = explode($this->getDelimiter(), $cacheDivision);

                $divisionCondition = $this->getSettings("cache_abteilung_filter");

                $match = false;

                foreach ($divisionValues as $division) {
                    $regular = preg_match($divisionCondition, $division);

                    if ($regular == 1) {
                        $match = true;
                        break;
                    }
                }

                if ($match === false) {
                    $this->skipMessage(true);

                    $this
                        ->addLogFilter('message', 'pre_cache_condition_error')
                        ->addLogFilter('type', 'divsion')
                        ->addLogFilter('condition', $divisionCondition)
                        ->addLogFilter('values', implode('--', $divisionValues))
                        ->setLogStatus('error')
                    ;
                }
            } else {
                $this->skipMessage(true);

                $this
                    ->addLogFilter('message', 'pre_cache_condition_error')
                    ->addLogFilter('type', 'divsion')
                    ->addLogFilter('condition', 'no division in message defined')
                    ->addLogFilter('values', '')
                    ->setLogStatus('error')
                ;
            }
        }

        return $this;
    }
}
