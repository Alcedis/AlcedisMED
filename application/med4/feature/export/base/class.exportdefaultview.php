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

require_once('interface.exportview.php');
require_once('class.exportbaseobject.php');
require_once('record.export.php');
require_once('record.exportcase.php');
require_once('record.exportsection.php');
require_once('helper.database.php');

/**
 * Class CExportDefaultView
 */
class CExportDefaultView extends CExportBaseObject implements IExportView
{
    /**
     * m_configs
     *
     * @access  protected
     * @var     array
     */
    protected $m_configs = array();


    /**
     * m_fields
     *
     * @access  protected
     * @var     array
     */
    protected $m_fields = array();


    /**
     * m_current_action
     *
     * @access  protected
     * @var     string
     */
    protected $m_current_action = '';


    /**
     * m_view_type
     *
     * @access  protected
     * @var     string
     */
    protected $m_view_type = '';


    /**
     * m_model
     *
     * @access  protected
     * @var     IExportModel
     */
    protected $m_model;


    /**
     * BuildView
     *
     * @access  public
     * @param   string  $action
     * @return  bool
     */
    public function buildView($action)
    {
        $result = false;

        $this->ReadConfigs();
        $this->SetVariables();

        $viewType = '';

        switch ($action) {
            case 'export_start':
                $this->CreateParameterViewFields();
                $viewType = 'parameter';
                $this->FillFields();

                if ($this->_validateFields($this->m_fields) === true) {
                    $this->CreateErrorListViewFields();
                    $viewType = 'errorlist';
                    $result = true;
                }

                break;

            case 'export_is_open':
                $this->CreateErrorListViewFields();
                $viewType = 'errorlist';
                $result = true;

                break;

            case 'export_create' :
                $this->CreateLogViewFields();
                $viewType = 'log';
                $result = true;

                break;

            case 'export_delete' :
                $this->CreateParameterViewFields();
                $viewType = 'parameter';
                $this->FillFields();
                $result = true;

                break;

            case 'view_errors' :
                $this->CreateErrorViewFields();
                $viewType = 'errors';
                $result = true;

                break;

            case 'view_warnings' :
                $this->CreateWarningViewFields();
                $viewType = 'warnings';
                $result = true;

                break;

            default :
                $this->CreateParameterViewFields();
                $viewType = 'parameter';
                $this->FillFields();
                $result = false;

                break;
        }

        $this->setViewType($viewType);

        $this->CreateBackLink();

        $this->getSmarty()
            ->assign('export_tpl', $this->GetTemplateFilename())
        ;

        return $result;
    }


    /**
     * ReadConfigs
     *
     * @access  public
     * @return  void
     */
    public function ReadConfigs()
    {
        $smarty = $this->getSmarty();

        $smarty->config_load('settings/interfaces.conf');
        $smarty->config_load(FILE_CONFIG_APP);

        $this->setConfig($smarty->get_config_vars());
    }


    /**
     * SetVariables
     *
     * @access  public
     * @return  void
     */
    public function SetVariables()
    { }


    /**
     * CreateParameterViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateParameterViewFields()
    {
        $this->m_fields = array();
    }


    /**
     * _hasWarnings
     *
     * @access  protected
     * @param   array   $list
     * @return  bool
     */
    protected function _hasWarnings($list)
    {
        foreach ($list as $item) {
            if (substr($item, 0, 10) == '[warning] ') {
                return true;
            }
        }

        return false;
    }


    /**
     * _hasOnlyWarnings
     *
     * @access  protected
     * @param   array   $list
     * @return  bool
     */
    protected function _hasOnlyWarnings($list)
    {
        if (0 == count($list)) {
            return false;
        }

        foreach ($list as $item) {
            if (substr($item, 0, 10) != '[warning] ') {
                return false;
            }
        }

        return true;
    }


    /**
     * _getErrors
     *
     * @access  protected
     * @param   array   $list
     * @return  array
     */
    protected function _getErrors($list)
    {
        $result = array();

        foreach ($list as $item) {
            if (substr($item, 0, 10) != '[warning] ') {
                $result[] = $item;
            }
        }

        return $result;
    }


    /**
     * _getWarnings
     *
     * @access  protected
     * @param   array   $list
     * @return  array
     */
    protected function _getWarnings($list)
    {
        $result = array();

        foreach ($list as $item) {
            if (substr($item, 0, 10) == '[warning] ') {
                $result[] = substr($item, 10);
            }
        }

        return $result;
    }


    /**
     * CreateErrorListViewFields
     *
     * @access  protected
     * @return  void
     */
    public function CreateErrorListViewFields()
    {
        $patients   = array();
        $error      = array();
        $warnings   = array();

        $this->setFields(array());

        $exportRecord        = $this->getModel()->GetData();
        $valid_cases_count   = $exportRecord->GetValidCasesCount();
        $invalid_cases_count = $exportRecord->GetInvalidCasesCount();
        $invalidSections     = $exportRecord->GetAllInvalidSections();

        if (count($invalidSections) > 0) {
            foreach ($invalidSections as $item) {
                if (!isset($patients[$item['patient_id']])) {
                    $patients[$item['patient_id']] = HDatabase::GetPatientData($this->getDB(), $item['patient_id']);
                }

                $patient = $patients[$item['patient_id']];

                $item['export_nr']    = $exportRecord->GetExportNr();
                $item['nachname']     = $patient['nachname'];
                $item['vorname']      = $patient['vorname'];
                $item['geschlecht']   = $patient['geschlecht'];
                $item['geburtsdatum'] = date("d.m.Y", strtotime($patient['geburtsdatum']));

                if (!isset($erkrankungen[$item['erkrankung_id']])) {
                    $erkrankungen[$item['erkrankung_id']] = HDatabase::GetErkrankungData($this->getDB(), $item['erkrankung_id']);
                }

                $erkrankung = $erkrankungen[$item['erkrankung_id']];

                $item['erkrankung'] = $erkrankung['erkrankung_bez'];
                $item['createtime'] = date("d.m.Y H:m:s", strtotime($item['createtime']));

                if ($this->_hasWarnings($item['errors'])) {
                    $warnings[] = $item;
                }
                if (!$this->_hasOnlyWarnings($item['errors'])) {
                    $error[] = $item;
                }
            }
        } else if (0 == $valid_cases_count) {
            // Es liegen keine Daten zum Export vor, also aktuellen Export automatisch löschen
            $this->getModel()->DeleteData();
        }

        $info_patienten_valid = $this->getConfigVar('info_patienten_valid');

        if ($valid_cases_count == 1) {
            $info_patienten_valid = $this->getConfigVar('info_patient_valid');
        }

        $info_patienten_valid = str_replace("#anzahl#", "" . $valid_cases_count, $info_patienten_valid);

        $info_patienten_invalid = $this->getConfigVar('info_patienten_invalid');

        if ($invalid_cases_count == 1) {
            $info_patienten_invalid = $this->getConfigVar('info_patient_invalid');
        }

        $info_patienten_invalid = str_replace("#anzahl#", "" . $invalid_cases_count, $info_patienten_invalid);

        $this->getSmarty()
            ->assign('info_patienten_valid', $info_patienten_valid)
            ->assign('info_patienten_invalid', $info_patienten_invalid)
            ->assign('invalid_cases', $invalid_cases_count)
            ->assign('valid_cases', $valid_cases_count)
            ->assign('warninglist_data', $warnings)
            ->assign('errorlist_data', $error)
        ;
    }


    /**
     * CreateLogViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateLogViewFields()
    {
        $this->setFields(array());
    }


    /**
     * CreateErrorViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateErrorViewFields()
    {
        $result = array();

        $this->setFields(array());

        $exportRecord    = $this->getModel()->GetData();
        $invalidSections = $exportRecord->GetAllInvalidSections();

        if (count($invalidSections) > 0) {
            foreach ($invalidSections as $item) {
                if ($item['export_section_log_id'] === $this->m_parameters['export_id']) {
                    if (isset($patients[$item['patient_id']]) === false) {
                        $patients[$item['patient_id']] = HDatabase::GetPatientData($this->getDB(), $item['patient_id']);
                    }

                    $patient = $patients[$item['patient_id']];

                    $result['export_nr']    = $exportRecord->GetExportNr();
                    $result['nachname']     = $patient['nachname'];
                    $result['vorname']      = $patient['vorname'];
                    $result['geburtsdatum'] = date("d.m.Y", strtotime($patient['geburtsdatum']));
                    $result['errors']       = $this->_getErrors($item['errors']);

                    if (!isset($erkrankungen[$item['erkrankung_id']])) {
                        $erkrankungen[$item['erkrankung_id']] = HDatabase::GetErkrankungData($this->getDB(), $item['erkrankung_id']);
                    }

                    $erkrankung = $erkrankungen[$item['erkrankung_id']];

                    $result['erkrankung'] = $erkrankung['erkrankung_bez'];
                    $result['createtime'] = date("d.m.Y H:m:s", strtotime($item['createtime']));

                    $this->getSmarty()
                        ->assign('erroritem_data', $result)
                    ;

                    break;
                }
            }
        }
    }


    /**
     * CreateWarningViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateWarningViewFields()
    {
        $result = array();

        $this->setFields(array());

        $exportRecord    = $this->getModel()->GetData();
        $invalidSections = $exportRecord->GetAllInvalidSections();

        if (count($invalidSections) > 0) {
            foreach ($invalidSections as $item) {
                if ($item['export_section_log_id'] === $this->m_parameters['export_id']) {
                    if (!isset($patients[$item['patient_id']])) {
                        $patients[$item['patient_id']] = HDatabase::GetPatientData($this->m_db, $item['patient_id']);
                    }

                    $patient = $patients[$item['patient_id']];

                    $result['export_nr']    = $exportRecord->GetExportNr();
                    $result['nachname']     = $patient['nachname'];
                    $result['vorname']      = $patient['vorname'];
                    $result['geburtsdatum'] = date("d.m.Y", strtotime($patient['geburtsdatum']));
                    $result['errors']       = $this->_getWarnings($item['errors']);

                    if (!isset($erkrankungen[$item['erkrankung_id']])) {
                        $erkrankungen[$item['erkrankung_id']] = HDatabase::GetErkrankungData($this->m_db, $item['erkrankung_id']);
                    }

                    $erkrankung = $erkrankungen[$item['erkrankung_id']];

                    $result['erkrankung'] = $erkrankung['erkrankung_bez'];
                    $result['createtime'] = date("d.m.Y H:m:s", strtotime($item['createtime']));

                    $this->getSmarty()
                        ->assign('warningitem_data', $result)
                    ;

                    break;
                }
            }
        }
    }


    /**
     * FillFields
     *
     * @access  public
     * @return  void
     */
    public function FillFields()
    {
        form2fields($this->m_fields);
    }


    /**
     * CreateFormular
     *
     * @access  public
     * @return  void
     */
    public function CreateFormular()
    {
        $item = new itemgenerator($this->getSmarty(), $this->getDB(), $this->getFields(), $this->getConfig());

        $item->preselected = false;
        $item->generate_elements();

        $this->getSmarty()
            ->assign('view_type', $this->GetViewType())
        ;
    }


    /**
     * CreateBackLink
     *
     * @access  public
     * @return  void
     */
    public function CreateBackLink()
    {
        $this->getSmarty()->assign('back_btn', 'page=extras');
    }


    /**
     * ShowView
     *
     * @access  public
     * @return  void
     */
    public function ShowView()
    {
        $this->CreateFormular();
    }


    /**
     * GetViewType
     *
     * @access  public
     * @return  string
     */
    public function GetViewType()
    {
        return $this->m_view_type;
    }


    /**
     * setViewType
     *
     * @access  public
     * @param   string  $type
     * @return  IExportView
     */
    public function setViewType($type)
    {
        $this->m_view_type = $type;

        return $this;
    }


    /**
     * GetTemplateFilename
     *
     * @access  public
     * @return  string
     */
    public function GetTemplateFilename()
    {
        return "";
    }


    /**
     * _validateFields
     *
     * @access  protected
     * @param   string  $fields
     * @return  bool
     */
    protected function _validateFields($fields)
    {
        $valid = false;

        if (is_array($fields) === true) {
            $valid = validate_dataset($this->getSmarty(), $this->getDB(), $fields, $this->getErrorFunction(), '');

            if ((!$valid) === false) {
                $valid = true;
            }
        }

        return $valid;
    }


    /**
     * SetModel
     *
     * @access  public
     * @param   IExportModel    $model
     * @return  IExportView
     */
    public function SetModel($model)
    {
        $this->m_model = $model;

        return $this;
    }


    /**
     * getModel
     *
     * @access  public
     * @return  IExportModel
     */
    public function getModel()
    {
        return $this->m_model;
    }


    /**
     * getFields
     *
     * @access  public
     * @return  array
     */
    public function getFields()
    {
        return $this->m_fields;
    }


    /**
     * setFields
     *
     * @access  public
     * @param   array $fields
     * @return  IExportView
     */
    public function setFields(array $fields)
    {
        $this->m_fields = $fields;

        return $this;
    }


    /**
     * getConfig
     *
     * @access  public
     * @return  array
     */
    public function getConfig()
    {
        return $this->m_configs;
    }


    /**
     * setConfig
     *
     * @access  public
     * @param   array   $config
     * @return  IExportView
     */
    public function setConfig($config)
    {
        $this->m_configs = $config;

        return $this;
    }


    /**
     * getConfigVar
     *
     * @access  public
     * @param   string  $var
     * @return  string
     */
    public function getConfigVar($var)
    {
        $config = $this->getConfig();

        return (array_key_exists($var, $config) === true ? $config[$var] : null);
    }
}

?>
