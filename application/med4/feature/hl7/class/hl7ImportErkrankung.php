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

class hl7ImportErkrankung extends hl7ImportBehandler
{
    private $_writeBuffer = false;

    private $_writeDiagnosis = false;

    protected $_diseaseId = null;

    private $_timestampDefault = '19000101000000';

    protected function _importErkrankung($cachePatient)
    {
        if ($this->_patientId !== null) {
            $this->_writeBuffer    = false;
            $this->_writeDiagnosis = false;

            $diseaseId      = null;
            $customDisease  = $this->getParam('selectedDisease');

            if ($customDisease === false) {
                $this->_imported = true;

                return;
            }

            $datasets = $this->_getDiagnosisDatasets($cachePatient);

            //If a custom disease selected. CREATE IT!!!
            if ($customDisease !== null) {
                $this->_createDisease($customDisease);
            }

            $diseaseToCreate = $customDisease;

             //DiseaseToCreate is null if no param is given
            if ($diseaseToCreate === null) {
                // take first found disease dataset / was sorted by time if possible
                foreach ($datasets as $dataset) {
                    if ($this->_isFilled($dataset, 'erkrankung') === true) {
                        $diseaseToCreate = $dataset['erkrankung'];
                        break;
                    }
                }
            }

            //Check diseases from patient
            $dIn = reset(sql_query_array($this->_db, "
               SELECT
                  MAX(IF(e.erkrankung = '{$diseaseToCreate}', e.erkrankung_id, NULL)) AS 'diseaseId',
                  COUNT(e.erkrankung_id) AS 'erkCount'
               FROM patient p
                  LEFT JOIN erkrankung e ON e.patient_id = p.patient_id
               WHERE
                  p.patient_id = '{$this->_patientId}'
               GROUP BY
                  p.patient_id
            "));

            switch (true) {
                case ($diseaseToCreate !== null && $dIn['erkCount'] == 0): //target disease found, but no disease in patient exist
                case ($diseaseToCreate !== null && $dIn['erkCount'] == 1 && $dIn['diseaseId'] == ''): ////target disease exist and one disease exist but isn´t the target disease
                   $diseaseId = $this->_createDisease($diseaseToCreate);
                   $this->_writeDiagnosis = true;
                   $this->_imported = true;

                   break;

                //target disease exist and one disease exist with required disease type
                case ($diseaseToCreate !== null && $dIn['erkCount'] == 1 && $dIn['diseaseId'] != ''):
                   $diseaseId             = $dIn['diseaseId'];
                   $this->_writeDiagnosis = true;

                   break;

                //target disease doesn´t exist but one disease exist
                case ($diseaseToCreate === null && $dIn['erkCount'] == 1):
                   $diseaseId        = dlookup($this->_db, 'erkrankung', 'MAX(erkrankung_id)', "patient_id = '{$this->_patientId}'");
                   $this->_writeDiagnosis = true;

                   break;

                //target disease doesn´t exist and no disease exist
                case ($diseaseToCreate === null && $dIn['erkCount'] == 0):
                   //Currently do nothing

                   break;

                case ($diseaseToCreate !== null && $dIn['erkCount'] > 1): //target disease exist but there are more than one disease exist
                case ($diseaseToCreate === null && $dIn['erkCount'] > 1): //target disease doesn´t exist but more than one disease exist
                   $this->_writeBuffer = true;
                   break;
             }

             $this->_writeDiagnosisDatasets($datasets, $diseaseId);
        }
    }

    public function getDiseaseId()
    {
        return $this->_diseaseId;
    }


    private function _writeDiagnosisDatasets($datasets, $diseaseId)
    {
        if (count($datasets) > 0) {
            if ($this->_writeDiagnosis === true && $diseaseId !== null) {

                $fields = $this->_smarty->widget->loadExtFields('fields/app/diagnose.php');

                foreach ($datasets as $dataset) {
                   if ($this->_isFilled($dataset, 'diagnose') === true && $this->_isFilled($dataset, 'datum') === true) {
                      $tmpFields = $fields;

                      //Insert Dataset only if ukey not already exists
                      $diagnoseId = dlookup($this->_db, 'diagnose', 'MAX(diagnose_id)',
                         "erkrankung_id = '{$diseaseId}' AND datum = '{$dataset['datum']}' AND diagnose = '{$dataset['diagnose']}' AND lokalisation = '-' AND lokalisation_text = '-'"
                      );

                      if (strlen($diagnoseId) == 0) {
                         $dataset['erkrankung_id'] = $diseaseId;

                         array2fields($dataset, $tmpFields);

                         execute_insert($this->_smarty, $this->_db, $tmpFields, 'diagnose', 'insert', false, -90);

                         $this->_imported = true;
                      }
                   }
                }
            } elseif ($this->_writeBuffer === true) {
                $this->_writeDiagnosisBuffer($datasets);
            }
        }
    }


    private function _writeDiagnosisBuffer($datasets)
    {
        $fields = $this->_smarty->widget->loadExtFields('feature/hl7/fields/hl7_diagnose.php');

        foreach ($datasets as $dataset) {
            if ($this->_isFilled($dataset, 'diagnose') === true && $this->_isFilled($dataset, 'datum') === true) {
               $where = "patient_id = '{$dataset['patient_id']}' AND datum = '{$dataset['datum']}' AND diagnose = '{$dataset['diagnose']}'";

               $diagnoseId = dlookup($this->_db, 'hl7_diagnose', 'hl7_diagnose_id', $where);

               if (strlen($diagnoseId) == 0) {
                   $tmpFields = $fields;
                   array2fields($dataset, $tmpFields);
                   execute_insert($this->_smarty, $this->_db, $tmpFields, 'hl7_diagnose', 'insert', true, -90);

                   $this->_imported = true;
               }
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $cachePatient
     * @return array
     */
    private function _getDiagnosisDatasets($cachePatient)
    {
        $datasets = array();

        $importDiagnoseType = array();

        $filteredDatasets = array();

        $diseaseRestriction = $this->getSettings('diseaseRestriction');

        $importFilter = $this->getParam('importFilter') === true ? $this->getFilter('import') : array();

        if (in_array('diagnosetyp', $importFilter) === true) {
            $importDiagnoseType = explode($this->getDelimiter(), $this->getFieldValue("import.diagnosetyp"));
        }

        //Create DG1 Datasets if diagnose field settings detected
        foreach ($this->getFieldSettings('diagnose') as $field) {
            $value   = $this->getFieldValue($field['med_feld']);
            $matrix  = explode($this->getDelimiter(), $value);

            //instant receive possible disease
            if ($field['feld'] == 'diagnose') {
                foreach ($matrix as $index => $val) {
                    $diagnose   = reset(explode(' ', trim($val)));
                    $erkrankung = $this->getLookups('diagnose', $diagnose, $diseaseRestriction);

                    if ($erkrankung === null) {
                        $diagnoseSub   = substr($diagnose, 0, 3);
                        $erkrankung    = $this->getLookups('diagnose', $diagnoseSub, $diseaseRestriction);
                    }

                    $datasets[$index]['erkrankung']     = $erkrankung;
                    $datasets[$index][$field['feld']]   = $val;
                    $datasets[$index]['diagnose']       = $diagnose;
                    $datasets[$index]['patient_id']     = $this->_patientId;
                    $datasets[$index]['org_id']         = $cachePatient['org_id'];

                    $datasets[$index]['diagnosetyp']    = in_array('diagnosetyp', $importFilter) === true && isset($importDiagnoseType[$index]) === true
                        ? $importDiagnoseType[$index]
                        : ''
                    ;
                }
            } else {
                foreach ($matrix as $index => $val) {
                    $datasets[$index][$field['feld']] = $val;
                }
            }
        }

        //afterwork on diagnosis datasets
        foreach ($datasets as $i => $dataset) {
            if ($this->_isFilled($dataset, 'diagnose') === true) {

                // Importfilter diagnosetyp
                if (in_array('diagnosetyp', $importFilter) === true) {
                    $condition = $this->getSettings("import_diagnosetyp_filter");

                    if (preg_match($condition, $dataset['diagnosetyp']) !== 1) {
                        continue;
                    }
                }

                // Importfilter diagnose
                if (in_array('diagnose', $importFilter) === true) {
                    $condition = $this->getSettings("import_diagnose_filter");

                    if (preg_match($condition, $dataset['diagnose']) !== 1) {
                        continue;
                    }
                }

                //check diagnose site
                if ($this->_isFilled($dataset, 'diagnose_seite') === true) {
                    $loweredDiagnosis = strtolower($dataset['diagnose']);
                    $loweredSite      = strtolower($dataset['diagnose_seite']);

                    if (str_ends_with($loweredDiagnosis, $loweredSite) === true) {
                        $dataset['diagnose'] = substr($dataset['diagnose'], 0, -1);
                    }
                }

                $filteredDatasets[] = $dataset;
            }
        }

        return $this->_orderDiagnose($filteredDatasets);
    }


    /**
     *
     *
     * @access
     * @param $datasets
     * @return array
     */
    private function _orderDiagnose($datasets)
    {
        $orderedDatasets = array();

        if (count($datasets) > 0) {
            $tmp = array(
                $this->_timestampDefault => array()
            );

            foreach ($datasets as $dataset) {
                if ($this->_isFilled($dataset, 'timestamp') === true) {
                    $timestamp = str_pad($dataset['timestamp'], 14, '0', STR_PAD_RIGHT);

                    $tmp[$timestamp][] = $dataset;
                } else {
                    $tmp[$this->_timestampDefault][] = $dataset;
                }
            }

            krsort($tmp);

            foreach ($tmp as $diagnoseDs) {
                $orderedDatasets = array_merge($orderedDatasets, $diagnoseDs);
            }
        }

        return $orderedDatasets;
    }

    protected function _resetDiseaseId()
    {
        $this->_diseaseId = null;

        return $this;
    }

    private function _createDisease($disease)
    {
        $erkrankungId = dlookup($this->_db, 'erkrankung', 'erkrankung_id', "patient_id = '{$this->_patientId}' AND erkrankung = '{$disease}'");

        if (strlen($erkrankungId) > 0) {
            $erkrankungId = $erkrankungId;
        } else {
           $fields  = $this->_smarty->widget->loadExtFields('fields/app/erkrankung.php');

           $diseaseDataset = array(
              'erkrankung' => $disease,
              'patient_id' => $this->_patientId
           );

           array2fields($diseaseDataset, $fields);

           execute_insert($this->_smarty, $this->_db, $fields, 'erkrankung', 'insert', false, -90);

           $erkrankungId = dlookup($this->_db, 'erkrankung', 'erkrankung_id', "patient_id = '{$this->_patientId}' AND erkrankung = '{$disease}'");
        }

        $this->_diseaseId = $erkrankungId;

        $this->_updatePatientDisease();

        return $erkrankungId;
    }

    private function _updatePatientDisease() {
        if ($this->_patientId !== null) {
            mysql_query("
                UPDATE patient d
                   INNER JOIN (
                      SELECT
                         x.patient_id,
                         GROUP_CONCAT(DISTINCT l.bez SEPARATOR ', ') AS 'erk'
                      FROM patient x
                         INNER JOIN erkrankung e ON e.patient_id = x.patient_id
                            LEFT JOIN l_basic l ON l.klasse = 'erkrankung' AND l.code = e.erkrankung
                      WHERE x.patient_id = '{$this->_patientId}'
                      GROUP BY x.patient_id
                   ) u ON u.patient_id = d.patient_id
                SET
                   d.erkrankungen = u.erk
                WHERE
                   d.patient_id = '{$this->_patientId}'
            ", $this->_db);
        }
    }
}

?>
