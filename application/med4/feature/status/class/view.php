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

class statusView extends status
{
   protected $_location    = null;

   protected $_hubValidationErrors  = array();

   protected $_interfaceFormErrors        = array();

   protected $_formValidationErrors       = array();

   protected $_extensions = array(
      'patientOrgId'
   );

   protected $_eCheckErrors = array();

   protected $_patientId      = null;

   protected $_erkrankungCode = null;

   protected $_ignoreForms    = array();

   protected $_currentForm = null;

   public static function create($smarty, $db, $fields, $table, $action) {
      return new self($smarty, $db, $fields, $table, $action);
   }

   /**
    * loads the specifig view for the current location
    *
    * @param $patientId
    * @param $form
    * @param $formId
    */
   public function loadStatusView($statusId = null)
   {
      $result = $this->loadDateset($statusId);

      $disease          = $result['erkrankung'];
      $disease_id       = $result['erkrankung_id'];
      $patient_id       = $result['patient_id'];

      $this->_patientId = $patient_id;

      $statusForms  = $this->_getAllStatusForms($disease_id, $patient_id);

      $formApply  = $this->_getRequiredForms($disease);

      foreach ($statusForms as $formName => &$forms) {
         foreach ($forms as &$form) {
            $formName           = $form['form'];
            $formId             = isset($form['form_id'])     ? $form['form_id']      : null;
            $formStatus         = isset($form['form_status']) ? $form['form_status']  : null;
            $formStatusId       = isset($form['status_id'])   ? $form['status_id']    : null;
            $form['formData']   = array();
            $form['disease']    = $disease;
            $posformData        = array();

            //Wenn nicht grün oder blau
            if (in_array($formStatus, array(3,4)) === false) {
               $form['formData']   = reset(sql_query_array($this->_db, "SELECT * FROM {$formName} WHERE {$formName}_id = '{$formId}'"));

               $fields = widget::getFieldsFromStatus($this->_db, $formStatusId);

               $this
                    ->_validateHubFormErrors($form, $fields)
                    ->_validateHubPosFormErrors($form)
                    ->_validateForm($form, $fields)
               ;

               foreach ($formApply as $hubName => $hubForms) {
                  $formApply[$hubName][$formName] = true;
               }
            } elseif ($formName === 'patient') {
               if (strlen($form['geschlecht']) == 0 && array_key_exists('b', $formApply) === true) {
                  $formId = $form['patient_id'];

                  $this->_hubValidationErrors['b']['data']['patient']['data'][$formId] = array(
                      'data' => array(
                          'geschlecht' => $this->getConfigLabel('geschlecht', 'patient')
                      ),
                      'form_date' => '',
                      'form_status' => 2
                  );
               }
            } else {
               //Sollte das Formular nicht validiert werden, wenigstens in die Liste der vorhandenen Formulare eintragen
                foreach ($formApply as $hubname => $hubForms) {
                    if (array_key_exists($formName, $hubForms)) {
                        $formApply[$hubname][$formName] = true;
                    }
                }
            }
         }
      }

      $this->_validateComprehensiveEChecks($disease, $statusForms);

      foreach ($this->_hubValidationErrors as $hubName => $validations) {
         foreach ($validations['data'] as $formName => $form) {
            //Config load des Formular feldes
            $this->_hubValidationErrors[$hubName]['data'][$formName]['lbl'] = $this->getConfigLabel('caption', $formName);
         }
      }

      return $this;
   }

    protected function _validateComprehensiveEChecks($disease, $statusForms)
    {
        $dkgManager = dkgManager::getInstance()
           ->setParam('disease', $disease)
           ->setConditionData($statusForms, false)
           ->checkConditions($this->_db)
        ;

        $errors = $dkgManager->getConditionErrors();

        if (count($errors) > 0) {
            $this->_eCheckErrors = $errors;
        }

        return $this;
    }

    protected function _validateHubPosFormErrors($form)
    {
        $formName   = $form['form'];
        $formId     = $form['form_id'];
        $disease    = $form['disease'];
        $formDate   = $form['form_date'];
        $formStatus = $form['form_status'];

        $dkgManager = dkgManager::getInstance()
           ->setParam('form', $formName)
           ->setParam('disease', $disease)
        ;

        foreach ($dkgManager->getDlists() as $hubName => $dlists) {
            foreach ($dlists as $dlist) {
                $dlistName = $dlist->getTable();
                $dlistData = sql_query_array($this->_db, "SELECT * FROM {$dlistName} WHERE {$formName}_id = '{$formId}'");

                if (count($dlistData) == 0) {
                    $label = sprintf($this->getConfigLabel('lbl_min_one_form'), $this->getConfigLabel(array("head_{$dlistName}", 'caption'), $dlistName));

                    $this->_hubValidationErrors[$hubName]['data'][$formName]['data'][$formId]['data']['psformerror'] = $label;
                } else {

                    $requiredFields = $dlist->getRequiredFields();

                    foreach ($dlistData as $data) {
                        $unfilledFields = array();

                        foreach ($requiredFields as $fieldName) {
                             if (array_key_exists($fieldName, $data) === true && strlen($data[$fieldName]) == 0) {
                                 $unfilledFields[] = $fieldName;

                             }
                        }

                        if (count($unfilledFields) > 0) {
                            $formErrors = '';

                            foreach ($unfilledFields as $unfilledFieldName) {
                                $formErrors .= "<li>{$this->getFieldLabel($unfilledFieldName, $dlistName)}</li>";
                            }

                            $label = sprintf(
                                $this->getConfigLabel('lbl_min_one_form_error'),
                                $this->getConfigLabel(array("head_{$dlistName}", 'caption'), $dlistName)
                            ) . "<ul>{$formErrors}</ul>";

                            $this->_hubValidationErrors[$hubName]['data'][$formName]['data'][$formId] = array(
                                'data' => array(
                                    'psformerror' => $label
                                ),
                                'form_date'     => $formDate,
                                'form_status'   => $formStatus
                            );
                        }
                    }
                }
            }
        }

        return $this;
    }


   protected function _extendCondition($condition)
   {
       foreach ($this->_extensions as $extension) {
           if (strpos($condition, '$'.$extension) !== false) {
                switch ($extension) {
                    case 'patientOrgId':
                        $pId = $this->_patientId;
                        $patOrgId = dlookup($this->_db, 'patient', 'org_id', "patient_id = '$pId'");

                        $condition = str_replace('$'.$extension, $patOrgId, $condition);

                        break;
                }
           }
       }

       return $condition;
   }


    protected function _validateHubFormErrors($form, $fields)
    {
        $formName   = $form['form'];
        $formId     = $form['form_id'];
        $formDate   = $form['form_date'];
        $formStatus = $form['form_status'];
        $formData   = $form['formData'];
        $disease    = $form['disease'];

        $validator = new validator($this->_smarty, $this->_db, dataArray2fields($formData, $fields));

        $dkgManager = dkgManager::getInstance()
           ->setParam('form', $formName)
           ->setParam('disease', $disease)
        ;

        $errors = array();

        foreach ($fields as $fieldName => $fieldData) {
            $fieldError = array();

            if (count($fieldConditions = $dkgManager->getFieldConditions($fieldName)) > 0) {
                foreach ($fieldConditions as $hubName => $conditions) {
                     foreach ($conditions as $condition) {
                         if ($validator->condition($this->_extendCondition($condition)) === true && strlen($formData[$fieldName]) == 0) {
                             $fieldError[] = $hubName;
                         }
                     }
                }
            } elseif (count($fieldChecks = $dkgManager->getFieldCheck($fieldName)) > 0) {
                foreach ($fieldChecks as $hubName => $check) {
                    if (strlen($formData[$fieldName]) == 0) {
                        $fieldError[] = $hubName;
                    }
                }
            }

            $fieldError = array_unique($fieldError);

            if (count($fieldError) > 0) {
                foreach ($fieldError as $hubName) {
                    $errors[$hubName][] = $fieldName;
                }
            }
        }

        foreach ($errors as $hubName => $errorFields) {
            $data = array(
                'data' => array(),
                'form_date' => $formDate,
                'form_status' => $formStatus
            );

            foreach ($errorFields as $field) {
                 $data['data'][$field] = $this->getConfigLabel(array("status_{$field}", $field), $formName);
            }

            $this->_hubValidationErrors[$hubName]['data'][$formName]['data'][$formId] = $data;
        }

        return $this;
    }


   protected function _getAllStatusForms($diseaseId, $patientId)
   {
      $diseaseDataset = sql_query_array($this->_db, "
         SELECT
            s.*,
            DATE_FORMAT(form_date, '%d.%m.%Y') AS 'form_date'
         FROM status s
         WHERE
            s.form = 'erkrankung'
            AND s.form_id = '$diseaseId'
      ");

      $diseaseData      = reset($diseaseDataset);

      $nachsorgeDatasets = sql_query_array($this->_db, "
         SELECT
            s.*,
            DATE_FORMAT(s.form_date, '%d.%m.%Y') AS 'form_date'
         FROM nachsorge_erkrankung ne
            LEFT JOIN nachsorge n   ON n.nachsorge_id = ne.nachsorge_id
            LEFT JOIN status s      ON s.form = 'nachsorge' AND s.form_id = n.nachsorge_id
         WHERE ne.erkrankung_weitere_id = '$diseaseId'
      ");

      $abschlussDataset = sql_query_array($this->_db, "SELECT *, DATE_FORMAT(form_date, '%d.%m.%Y') AS 'form_date' FROM status WHERE form = 'abschluss' AND patient_id = '$patientId'");
      $statusDatasets   = sql_query_array($this->_db, "SELECT *, DATE_FORMAT(form_date, '%d.%m.%Y') AS 'form_date' FROM status WHERE erkrankung_id = '$diseaseId'");
      $patientDataset   = sql_query_array($this->_db, "SELECT *, 'patient' AS 'form', 4 AS 'form_status' FROM patient WHERE patient_id = '$patientId'");

      $datasets = array_merge($diseaseDataset, $nachsorgeDatasets, $abschlussDataset, $statusDatasets, $patientDataset);

      //Order forms for better performance
      $forms = array();

      foreach ($datasets as $dataset) {
         $forms[$dataset['form']][] = $dataset;
      }

      return $forms;
   }


   /**
    * Validates the given form with the pseudo Validator
    *
    * @param $formName
    * @param $fields
    * @param $formData
    */
   protected function _validateForm($form, $fields)
   {
      $formName  = $form['form'];
      $fields    = dataArray2fields($form['formData'], $fields, true);

      $warnFields = pseudoValidator::create($this->_smarty, $this->_db)
         ->validate($form['form'], $fields, 'warn')
         ->getFields('warn')
      ;

      if ($warnFields !== null) {
         $formId     = $form['form_id'];

         $this->_formValidationErrors['validation']['data'][$form['form']]['lbl'] = $this->getConfigLabel('caption', $formName);

         foreach ($warnFields as $field) {
            $this->_formValidationErrors['validation']['data'][$formName]['data'][$formId]['data'][$field] = $this->getConfigLabel(array("status_{$field}", $field), $formName);
            $this->_formValidationErrors['validation']['data'][$formName]['data'][$formId]['form_date']    = $form['form_date'];
            $this->_formValidationErrors['validation']['data'][$formName]['data'][$formId]['form_status']  = $form['form_status'];
         }
      }

      return $this;
   }


   public function getHubList()
   {
       return dkgManager::getInstance()
          ->setParam('disease', $this->_erkrankungCode)
          ->getHubNames(false, true)
       ;
   }


   public function getErrors()
   {
      $errors = array();

      if (count($this->_formValidationErrors) > 0) {
         $errors['base'] = $this->_formValidationErrors;
      }

      foreach ($this->_hubValidationErrors as $hubName => $dummy) {
         $errors[$hubName]['validation'] = $this->_hubValidationErrors[$hubName];
      }

      foreach ($this->_eCheckErrors as $hubName => $dummy) {
         $errors[$hubName]['eCheck'] = $this->_eCheckErrors[$hubName];
      }

      foreach ($this->_interfaceFormErrors as $hubName => $dummy) {
         $errors[$hubName]['apply'] = $this->_interfaceFormErrors[$hubName];
      }

      $dkgConfig = dkgManager::getInstance()->getLabels();

      foreach ($errors as $hubName => $dummy) {
         $errors[$hubName]['lbl'] = $dkgConfig["hub_{$hubName}"];
      }

      return $errors;
   }

   public function loadDateset($statusId)
   {
      $query = "
         SELECT
            s.form_id      AS 'erkrankung_id',
            s.patient_id   AS 'patient_id',
            e.erkrankung   AS 'erkrankung',
            p.org_id       AS 'org_id'
         FROM status s
            LEFT JOIN erkrankung e  ON e.erkrankung_id = s.form_id
            LEFT JOIN patient p ON p.patient_id = e.patient_id
         WHERE s.status_id = '$statusId'
      ";

      $result  = reset(sql_query_array($this->_db, $query));

      $this->_erkrankungCode = $result['erkrankung'];

      return $result;
   }

    /**
     * returns all registered forms forms
     *
     * @param array $interfaces
     */
    protected function _getRequiredForms($disease)
    {
        $formApply = array();

        $dkgManager = dkgManager::getInstance()
            ->setParam('disease', $disease)
        ;

        foreach ($dkgManager->getHubForms(false) as $hubName => $hubForms) {
            foreach ($hubForms as $formName) {
                $formApply[$hubName][$formName] = false;
            }

            if (in_array('tumorstatus', $hubForms) === false) {
                $formApply[$hubName]['tumorstatus'] = false;

            }
        }

        return $formApply;
    }

   /**
    * set location of the current status view
    *
    * @param $location
    */
   public function setLocation($location)
   {
      $this->_location = $location;

      return $this;
   }


   public function ignoreForms($forms) {
      $this->_ignoreForms = $forms;

      return $this;
   }

}

?>
