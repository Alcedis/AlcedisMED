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

require_once('feature/krebsregister/class/register/export/record.krexportsection.php');

/**
 * Class registerPatientMessage
 */
class registerPatientMessage
{
    /**
     * export case of this message
     *
     * @access  protected
     * @var     RKrExportCase
     */
    protected $_exportCase;


    /**
     * _history
     *
     * @access protected
     * @var    registerPatientMessage
     */
    protected $_history;


    /**
     * _type
     *
     * @access  protected
     * @var     string
     */
    protected $_type;


    /**
     * _history
     *
     * @access  protected
     * @var     bool
     */
    protected $_isHistory = false;


    /**
     * _sections
     *
     * @access  protected
     * @var     RExportSection[]
     */
    protected $_sections = array();


    /**
     * these sections will be hidden on export or validate
     *
     * @access  protected
     * @var     array
     */
    protected $_hiddenSections = array();


    /**
     * contains export relevant information
     *
     * @access  protected
     * @var     array
     */
    protected $_params = array();


    /**
     * _isDifferent
     *
     * @access  protected
     * @var     bool
     */
    protected $_isDifferent = false;


    /**
     * _difference
     *
     * @access  protected
     * @var     array
     */
    protected $_difference = array();


    /**
     * _ignoreOnDiff
     *
     * @access  protected
     * @var     array
     */
    protected $_ignoreOnDiff = array();


    /**
     * _mandatories
     *
     * @access  protected
     * @var     array
     */
    protected $_mandatories = array();


    /**
     * _exportable
     *
     * @access  protected
     * @var     bool
     */
    protected $_exportable = true;


    /**
     * _validatable
     *
     * @access  protected
     * @var     bool
     */
    protected $_validatable = true;


    /**
     * create registerPatientMessage
     *
     * @static
     * @access  public
     * @return  registerPatientMessage
     */
    public static function create()
    {
        return new self();
    }


    /**
     * registerPatientMessage constructor.
     */
    public function __construct()
    {
        $this->_exportCase = new RKrExportCase;
    }


    /**
     * setExportable
     *
     * @access  public
     * @param   bool $bool
     * @return  registerPatientMessage
     */
    public function setExportable($bool = true)
    {
        $this->_exportable = $bool;

        return $this;
    }


    /**
     * isExportable
     *
     * @access  public
     * @return  bool
     */
    public function isExportable()
    {
        return $this->_exportable;
    }


    /**
     * setValidatable
     *
     * @access  public
     * @param   bool $bool
     * @return  registerPatientMessage
     */
    public function setValidatable($bool = true)
    {
        $this->_validatable = $bool;

        return $this;
    }


    /**
     * isValidatable
     *
     * @access  public
     * @return  bool
     */
    public function isValidatable()
    {
        return $this->_validatable;
    }


    /**
     * getType
     *
     * @access  public
     * @return  string
     */
    public function getType()
    {
        $type = null;
        $reason = $this->_exportCase->getAnlass();

        if (strlen($reason) > 0) {
            $reasonParts = explode('_', $reason);

            $type = reset($reasonParts);
        }

        return $type;
    }


    /**
     * initExportCase
     *
     * @access  public
     * @param   array $data
     * @return  registerPatientMessage
     */
    public function initExportCase(array $data)
    {
        $exportCase = $this->getExportCase();

        if (array_key_exists('patient_id', $data) === true) {
            $exportCase->setPatientId($data['patient_id']);
        }

        if (array_key_exists('erkrankung_id', $data) === true) {
            $exportCase->setErkrankungId($data['erkrankung_id']);
        }

        if (array_key_exists('diagnose_seite', $data) === true) {
            $exportCase->setDiagnoseSeite(strtolower($data['diagnose_seite']));
        }

        if (array_key_exists('anlass', $data) === true) {
            $exportCase->setAnlass(strtolower($data['anlass']));
        }

        return $this;
    }


    /**
     * checkConfiguration
     *
     * @access  public
     * @return  registerPatientMessage
     */
    public function checkConfiguration()
    {
        // only check configuration if message has export section
        if ($this->hasSection('export') === true) {
            $exportSectionData = $this->getSection('export')->getData();

            // check validatable
            if (strlen($exportSectionData['validatable']) === 0) {
                $this->setValidatable(false);
            }

            // check exportable
            if (strlen($exportSectionData['exportable']) === 0) {
                $this->setExportable(false);
            }
        }

        return $this;
    }



    /**
     * getIdent
     *
     * @access  public
     * @return  string
     */
    public function getIdent()
    {
        $messageSection = $this->getSection('message')->getData();

        $ident = substr($messageSection['id'], 4);

        return $ident;
    }


    /**
     * getDiseaseIdent
     *
     * @access  public
     * @return  string
     */
    public function getDiseaseIdent()
    {
        $exportSectionData = $this->getSection('export')->getData();

        return $exportSectionData['erkrankung_id'] . $exportSectionData['diagnose_seite'];
    }


    /**
     * getExportCase
     *
     * @access  public
     * @return  RKrExportCase
     */
    public function getExportCase()
    {
        $this->syncSections();

        return $this->_exportCase;
    }


    /**
     * setExportCase
     *
     * @access  public
     * @param   RKrExportCase $exportCase
     * @return  registerPatientMessage
     */
    public function setExportCase(RKrExportCase $exportCase)
    {
        $this->_exportCase = $exportCase;

        $this->setSections($exportCase->getSections());

        return $this;
    }


    /**
     * build hash from current message data
     *
     * @access  public
     * @return  registerPatientMessage
     */
    public function buildHash()
    {
        $data = $this->toArray();

        ksort($data);

        foreach ($this->getIgnoreOnDiff() as $sectionName => $section) {
            // if section has field definitions, remove only them, else remove complete section for diff
            if (is_array($section) === true) {
                foreach ($section as $field) {
                    unset($data[$sectionName][$field]);
                }
            } else {
                unset($data[$sectionName]);
            }
        }

        unset($data['patient']);

        // create sha1 hash from serialized data
        $this->getExportCase()->setHash(sha1(serialize($data)));

        return $this;
    }


    /**
     * setHistory
     *
     * @access  public
     * @param   registerPatientMessage $message
     * @return  registerPatientMessage
     */
    public function setHistory(registerPatientMessage $message)
    {
        $message->setIsHistory();

        $this->_history = $message;

        return $this;
    }


    /**
     * setIsHistory
     *
     * @access  public
     * @param   bool $bool
     * @return  registerPatientMessage
     */
    public function setIsHistory($bool = true)
    {
        $this->_isHistory = $bool;

        return $this;
    }


    /**
     * getHistory
     *
     * @access  public
     * @return  bool
     */
    public function isHistory()
    {
        return $this->_isHistory;
    }


    /**
     * getHistory
     *
     * @access  public
     * @return  registerPatientMessage
     */
    public function getHistory()
    {
        return $this->_history;
    }


    /**
     * toArray
     *
     * @access  public
     * @param   bool $checkHiddenSections
     * @return  array
     */
    public function toArray($checkHiddenSections = false)
    {
        $data = array();

        foreach ($this->getSections() as $sectionName => $section) {
            // check if section is hidden if flag is set
            if ($checkHiddenSections === true && $this->isSectionHidden($sectionName) === true) {
                continue;
            }

            $data[$sectionName] = $section->getData();
        }

        ksort($data);

        return $data;
    }


    /**
     * loadHistory
     *
     * @access  public
     * @param   resource $db
     * @param   string   $ident
     * @return  registerPatientMessage
     */
    public function loadHistory($db, $ident = null)
    {
        $exportCase = $this->getExportCase();

        $diseaseId = $exportCase->getErkrankungId();
        $side      = $exportCase->getDiagnoseSeite();

        $type  = $this->getParam('type');
        $orgId = $this->getParam('org_id');

        // if ident is not set, get it from exportCase
        if ($ident === null) {
            $ident = $exportCase->getAnlass();
        }

        // TODO
        // maybe optimize with all from last
        // check with productive data

        // get last exported message
        $query = "
            SELECT
                ecl.export_case_log_id
            FROM export_log el
                INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id AND
                                                  ecl.erkrankung_id = '{$diseaseId}' AND
                                                  ecl.diagnose_seite = '{$side}' AND
                                                  ecl.anlass = '{$ident}'
            WHERE
                el.export_name = 'kr_{$type}' AND
                el.org_id = '{$orgId}' AND
                el.export_unique_id = '{$orgId}' AND
                el.finished = 1
            GROUP BY
                el.export_log_id
            ORDER BY
                export_nr DESC
            LIMIT 1
        ";

        $lastExport = sql_query_array($db, $query);

        if (count($lastExport) > 0) {
            $lastCase   = reset($lastExport);
            $historyExportCase = new RKrExportCase;

            $historyExportCase->read($db, $lastCase['export_case_log_id'], true);

            $historyMessage = new registerPatientMessage;

            $this->setHistory($historyMessage
                ->setExportCase($historyExportCase)
            );
        }

        return $this;
    }


    /**
     * hasHistory
     *
     * @access  public
     * @return  bool
     */
    public function hasHistory()
    {
        return ($this->_history !== null);
    }


    /**
     * check difference on message
     *
     * @access  public
     * @return  void
     */
    public function checkDifference()
    {
        // only check difference if this message has a history
        if ($this->hasHistory() === true) {
            $this->buildHash();

            $history    = $this->getHistory();
            $exportCase = $this->getExportCase();

            // check is message is different
            if ($history->getExportCase()->getHash() !== $exportCase->getHash()) {
                $this->_isDifferent = true;
            }
        }
    }


    /**
     * checkSectionDifference
     *
     * @access  public
     * @param   string $sectionName
     * @return  bool
     */
    public function checkSectionDifference($sectionName)
    {
        $hasDifference = false;

        if ($this->hasHistory() === true) {
            $history = $this->getHistory();

            // in history these section never exists
            if ($history->hasSection($sectionName) === false) {
                $hasDifference = true;
            } else {
                $historySection = $history->getSection($sectionName);
                $currentSection = $this->getSection($sectionName);

                $hasDifference = sha1(serialize($historySection->getData())) !== sha1(serialize($currentSection->getData()));
            }
        }

        return $hasDifference;
    }


    /**
     * hasDifference
     *
     * @access  public
     * @return  bool
     */
    public function hasDifference()
    {
        return $this->_isDifferent;
    }


    /**
     * getDifference
     *
     * @access  public
     * @return  array
     */
    public function getDifference()
    {
        return $this->_difference;
    }


    /**
     * addSection
     *
     * @access  public
     * @param   string  $name
     * @param   array|RExportSection $section
     * @param   bool $init
     * @return  registerPatientMessage
     * @throws  Exception
     */
    public function addSection($name, $section, $init = true)
    {
        // create new section if not exists
        if ($this->hasSection($name) === false) {

            // convert array to RKrExportSection
            if (is_array($section) === true) {
                $section = self::createSection(null, $this->getParams(), $name, $name, $section);
            }

            if ($init === true) {
                $section->setValid(0);
            }

            $this->_sections[$name] = $section;
        } else {
            throw new Exception("section '{$name}' already exists");
        }

        return $this;
    }


    /**
     * createBlock
     *
     * @access  public
     * @param   int     $export_case_id
     * @param   array   $parameters
     * @param   string  $block_name
     * @param   string  $block_uid
     * @param   array   $block_data
     * @return  RKrExportSection
     */
    public static function createBlock($export_case_id, &$parameters, $block_name, $block_uid, $block_data)
    {
        $block = new RKrExportSection;

        $block->SetDataChanged(1);
        $block->SetSectionUid($block_uid);
        $block->SetExportCaseId($export_case_id);
        $block->SetBlock($block_name);
        $block->SetDaten($block_data);
        $block->SetValid(9);
        $block->SetMeldungskennzeichen('N');
        $block->SetCreateUserId($parameters['user_id']);

        return $block;
    }


    /**
     * createSection
     *
     * @static
     * @access  public
     * @param   int     $exportCaseId
     * @param   array   $param
     * @param   string  $sectionName
     * @param   string  $blockUId
     * @param   array   $data
     * @return  RExportSection
     */
    public static function createSection($exportCaseId, array $param = array(), $sectionName, $blockUId, array $data = array())
    {
        return self::createBlock($exportCaseId, $param, $sectionName, $blockUId, $data);
    }


    /**
     * removeSection
     *
     * @access  public
     * @param   string $name
     * @return  registerPatientMessage
     */
    public function removeSection($name)
    {
        unset($this->_sections[$name]);

        return $this;
    }


    /**
     * syncSections
     *
     * @access  public
     * @return  registerPatientMessage
     */
    public function syncSections()
    {
        $this->_exportCase->setSections($this->getSections());

        return $this;
    }


    /**
     * getSection
     *
     * @access  public
     * @param   string $name
     * @return  RExportSection
     */
    public function getSection($name)
    {
        return (array_key_exists($name, $this->_sections) === true ? $this->_sections[$name] : null);
    }


    /**
     * setSections
     *
     * @access  public
     * @param   RExportSection[] $sections
     * @return  registerPatientMessage
     * @throws  Exception
     */
    public function setSections(array $sections)
    {
        $this->_sections = array();

        foreach ($sections as $section) {
            $this->addSection($section->getSectionUid(), $section, false);
        }

        return $this;
    }


    /**
     * hasSection
     *
     * @access  public
     * @param   string $name
     * @return  bool
     */
    public function hasSection($name)
    {
        return (array_key_exists($name, $this->_sections));
    }


    /**
     * getSections
     *
     * @access  public
     * @return  RExportSection[]
     */
    public function getSections()
    {
        return $this->_sections;
    }


    /**
     * isSectionHidden
     *
     * @access  public
     * @param   string $sectionName
     * @return  bool
     */
    public function isSectionHidden($sectionName)
    {
        return array_key_exists($sectionName, $this->_hiddenSections);
    }


    /**
     * hideSection
     *
     * @access  public
     * @param   string $sectionName
     * @return  registerPatientMessage
     */
    public function hideSection($sectionName)
    {
        $this->_hiddenSections[$sectionName] = true;

        return $this;
    }


    /**
     * showSection
     *
     * @access  public
     * @param   string $sectionName
     * @return  registerPatientMessage
     */
    public function showSection($sectionName)
    {
        unset($this->_hiddenSections[$sectionName]);

        return $this;
    }


    /**
     * getSectionNames
     *
     * @access  public
     * @return  array
     */
    public function getSectionNames()
    {
        return array_keys($this->_sections);
    }


    /**
     * setParams
     *
     * @access  public
     * @param   array $params
     * @return  registerPatientMessage
     */
    public function setParams(array $params = array())
    {
        $this->_params = $params;

        return $this;
    }


    /**
     * getParams
     *
     * @access  public
     * @return  array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * getParam
     *
     * @access  public
     * @param   string  $name
     * @return  string
     */
    public function getParam($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }


    /**
     * getIgnoreOnDiff
     *
     * @access  public
     * @return  array
     */
    public function getIgnoreOnDiff()
    {
        return $this->_ignoreOnDiff;
    }


    /**
     * setIgnoreOnDiff
     *
     * @access  public
     * @param   array $ignoreFields
     * @return  registerPatientMessage
     */
    public function setIgnoreOnDiff(array $ignoreFields = array())
    {
        $this->_ignoreOnDiff = $ignoreFields;

        return $this;
    }


    /**
     * hasErrors
     *
     * @access  public
     * @return  bool
     */
    public function hasErrors()
    {
        $exportCase = $this->getExportCase();
        $hasErrors  = false;

        // find errors in section (level 2 and 3)
        foreach ($exportCase->getSections() as $section) {
            if (str_contains($section->getValid(), array('2', '3')) === true) {
                $hasErrors = true;
                break;
            }
        }

        return $hasErrors;
    }


    /**
     * setMandatories
     *
     * @access  public
     * @param   array $mandatories
     * @return  registerPatientMessage
     */
    public function setMandatories(array $mandatories = array())
    {
        $this->_mandatories = $mandatories;

        return $this;
    }


    /**
     * hasMandatories
     *
     * @access  public
     * @return  bool
     */
    public function hasMandatories()
    {
        return (count($this->_mandatories) > 0);
    }


    /**
     * parseMandatories
     *
     * @access  public
     * @return  registerPatientMessage
     */
    public function checkMandatories()
    {
        foreach ($this->_mandatories as $mandatoryType => $mandatories) {
            foreach ($mandatories as $sectionName => $sectionMandatories) {

                if ($this->hasSection($sectionName) === true) {
                    $section     = $this->getSection($sectionName);
                    $sectionData = $section->getData();

                    foreach ($sectionMandatories as $mandatory) {

                        $errors = array();

                        if (true === is_callable($mandatory['condition']) && false === is_callable($mandatory['fieldCondition'])) {
                            $errors = $this->_checkMandatoryCallable($sectionData, $mandatory['field'], $mandatory['mandatoryValues'], $mandatory['condition']);
                        }
                        elseif (false === is_callable($mandatory['condition']) && true === is_callable($mandatory['fieldCondition'])) {
                            $errors = $this->_checkFieldMandatoryCallable($sectionData, $mandatory['field'], $mandatory['mandatoryValues'], null, $mandatory['fieldCondition']);
                        }
                        elseif (true === is_callable($mandatory['condition']) && true === is_callable($mandatory['fieldCondition'])) {
                            $errors = $this->_checkFieldMandatoryCallable($sectionData, $mandatory['field'], $mandatory['mandatoryValues'], $mandatory['condition'], $mandatory['fieldCondition']);
                        }
                        else {
                            $errors = $this->_checkMandatoryField($sectionData, $mandatory['field'], $mandatory['mandatoryValues']);
                        }

                        // field mandatory not reached
                        foreach ($errors as $error) {
                            $section
                                ->addError(array(
                                    'level'   => $mandatoryType,
                                    'code'    => '-',
                                    'message' => $error,
                                    'line'    => '-',
                                    'section' => $sectionName
                                ))
                                ->setValid(registerExportSerializer::buildValid($mandatoryType, $section->getValid()))
                            ;
                        }
                    }
                }
            }
        }

        return $this;
    }


    /**
     * _checkMandatoryField
     *
     * @access  protected
     * @param   array    $sectionData
     * @param   string   $field
     * @param   array    $mandatoryValues
     * @param   callable $fieldCheck
     * @param   array    $parentData
     * @return  array
     */
    protected function _checkMandatoryField(array $sectionData, $field, array $mandatoryValues, callable $fieldCheck = null, array $parentData = null)
    {
        $errors = array();

        // TODO - refurbish. Fast code for test

        // this means we must go one step down
        if (str_contains($field, '/') === true) {
            $identParts   = explode('/', $field);
            $firstElement = reset($identParts);

            if ($firstElement === '*') {
                array_shift($identParts);

                $identParts = implode('/', $identParts);

                foreach ($sectionData as $data) {
                    $error = $this->_checkMandatoryField($data, $identParts, $mandatoryValues, $fieldCheck, $sectionData);

                    if (count($error) > 0) {
                        foreach ($error as $err) {
                            $errors[] = $err;
                        }
                    }
                }
            } else {
                $block = $sectionData[$firstElement];

                array_shift($identParts);

                $nextStep = reset($identParts);

                if ($nextStep === '*') {
                    array_shift($identParts);

                    $identParts = implode('/', $identParts);

                    foreach ($block as $data) {
                        $error = $this->_checkMandatoryField($data, $identParts, $mandatoryValues, $fieldCheck, $sectionData);

                        if (count($error) > 0) {
                            foreach ($error as $err) {
                                $errors[] = $err;
                            }
                        }
                    }
                } else {
                    $condition = true;
                    $errorMessage = "'{$nextStep}' ist nicht dokumentiert oder enthält keine Elemente";
                    if (null !== $fieldCheck) {
                        $result = $fieldCheck($this, $block, $nextStep, $parentData, $field);
                        if (true === is_string($result)) {
                            $errorMessage = $result;
                        }
                        else {
                            $condition = $result;
                        }
                    }
                    if (true === isset($block['id'])) {
                        $errorMessage .= " [ID = {$block['id']}]";
                    }
                    elseif (true === isset($parentData['id'])) {
                        $errorMessage .= " [ID = {$parentData['id']}]";
                    }
                    if (true === $condition && true === $this->_checkFieldForError($block[$nextStep], $mandatoryValues)) {
                        $errors[] = $errorMessage;
                    }
                }
            }
        } else {
            $condition = true;
            $errorMessage = "'{$field}' ist nicht dokumentiert oder enthält keine Elemente";
            if (null !== $fieldCheck) {
                $result = $fieldCheck($this, $sectionData, $field, $parentData, $field);
                if (true === is_string($result)) {
                    $errorMessage = $result;
                }
                else {
                    $condition = $result;
                }
            }
            if (true === isset($sectionData['id'])) {
                $errorMessage .= " [ID = {$sectionData['id']}]";
            }
            elseif (true === isset($parentData['id'])) {
                $errorMessage .= " [ID = {$parentData['id']}]";
            }
            if (true === $condition && true === $this->_checkFieldForError($sectionData[$field], $mandatoryValues)) {
                $errors[] = $errorMessage;
            }
        }

        return $errors;
    }


    /**
     * _checkFieldForError
     *
     * @access  protected
     * @param   array|string $field
     * @param   array $mandatoryValues
     * @return  bool
     */
    protected function _checkFieldForError($field, array $mandatoryValues = array())
    {
        $error = false;

        if (is_array($field) === true) {
            $error = (count($field) === 0);
        } else {
            if (count($mandatoryValues) > 0) {
                $error = (in_array($field, $mandatoryValues));
            }
            else {
                $error = (strlen($field) === 0);
            }
        }

        return $error;
    }


    /**
     * _checkMandatoryCallable
     *
     * @access  protected
     * @param   array    $sectionData
     * @param   string   $field
     * @param   array    $mandatoryValues
     * @param   callable $check
     * @return  array
     */
    protected function _checkMandatoryCallable(array $sectionData, $field, array $mandatoryValues, callable $check)
    {
        $errors = array();
        $condition = $check($this, $sectionData, $field);

        if ($condition === true) {
            $errors = $this->_checkMandatoryField($sectionData, $field, $mandatoryValues);
        }

        return $errors;
    }


    /**
     * _checkFieldMandatoryCallable
     *
     * @access  protected
     * @param   array    $sectionData
     * @param   string   $field
     * @param   array    $mandatoryValues
     * @param   callable $check
     * @param   callable $fieldCheck
     * @return  array
     */
    protected function _checkFieldMandatoryCallable(array $sectionData, $field, array $mandatoryValues, callable $check = null, callable $fieldCheck = null)
    {
        $errors = array();
        $condition = true;

        if (null !== $check) {
            $condition = $check($this, $sectionData, $field);
        }

        if ($condition === true) {
            $errors = $this->_checkMandatoryField($sectionData, $field, $mandatoryValues, $fieldCheck);
        }

        return $errors;
    }
}
