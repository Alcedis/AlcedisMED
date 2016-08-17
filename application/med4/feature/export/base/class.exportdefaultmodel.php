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

require_once('interface.exportmodel.php');
require_once('class.exportbaseobject.php');
require_once('class.exportwrapper.php');
require_once('class.exportexception.php');
require_once('record.export.php');
require_once('record.exportcase.php');
require_once('record.exportsection.php');

class CExportDefaultModel extends CExportBaseObject implements IExportModel
{
    /**
     * m_users
     *
     * @access  protected
     * @var     array
     */
    protected $m_users = array();


    /**
     * m_export_record
     *
     * @access  protected
     * @var     RExport
     */
    protected $m_export_record;


    /**
     * m_serialiser
     *
     * @access  protected
     * @var     IExportSerialiser
     */
    protected $m_serialiser;


    /**
     * m_create_new_export
     *
     * @access  protected
     * @var     bool
     */
    protected $m_create_new_export = false;


    /**
     * m_l_basic_bez
     *
     * @access  protected
     * @var     array
     */
    protected $m_l_basic_bez = array();


    /**
     * m_l_basic
     *
     * @access  protected
     * @var     array
     */
    protected $m_l_basic;


    /**
     * m_export_filename
     *
     * @access  protected
     * @var     string
     */
    protected $m_export_filename = '';


    /**
     * _wrapperName
     *
     * @access  protected
     * @var     string
     */
    protected $_wrapperName = 'CExportWrapper';


    /**
     * _wrapper
     *
     * @access  protected
     * @var     IExportWrapper
     */
    protected $_wrapper;


    /**
     * _checkSituation
     *
     * @access  protected
     * @var     bool
     */
    protected $_checkSituationOnDiff = true;


    /**
     * @see IExportModel::createExport
     */
    public function createExport($parameter)
    {
        $newExport = false;

        $this->m_l_basic_bez = HDatabase::ReadLBasicTable($this->getDB());

        $record = new RExport;

        $this->m_create_new_export = !$record->Read(
            $this->getDB(),
            $this->GetExportName(),
            $parameter['exportUniqueId'],
            $parameter['org_id'],
            'not_finished'
        );

        if ($this->isNewExport() === true) {
            $nr = RExport::getNextExportNr($this->getDB(), $this->GetExportName(), $parameter['exportUniqueId']);

            $record
                ->setExportName($this->GetExportName())
                ->setExportNr($nr)
                ->setExportUniqueId($parameter['exportUniqueId'])
                ->setOrgId($parameter['org_id'])
                ->setCreateUserId($parameter['user_id'])
                ->setFinished(0)
                ->setParameters($this->getParameters());
            ;

            $this->m_l_basic = HDatabase::ReadExportCodeTable($this->getDB(), $this->GetExportName());

            $newExport = true;
        }

        $this->setExportRecord($record);

        return $newExport;
    }


    /**
     * setCheckSituationOnDiff
     *
     * @access  public
     * @param   bool $bool
     * @return  IExportModel
     */
    public function setCheckSituationOnDiff($bool = true)
    {
        $this->_checkSituationOnDiff = $bool;

        return $this;
    }


    /**
     * hasCheckSituationOnDiff
     *
     * @access  public
     * @return  bool
     */
    public function hasCheckSituationOnDiff()
    {
        return $this->_checkSituationOnDiff;
    }


    /**
     * @see IExportModel::isNewExport
     */
    public function isNewExport()
    {
        return $this->m_create_new_export;
    }


    /**
     * @see IExportModel::setSerialiser
     */
    public function setSerialiser($serialiser)
    {
        if ($serialiser instanceof IExportSerialiser) {
            $this->m_serialiser = $serialiser;
        } else {
            throw new EExportException("ERROR: Object is not a instance of serialiser.");
        }
    }


    /**
     * @see IExportModel::isOpenExport
     */
    public function isOpenExport()
    {
        if ($this->hasExportRecord() === false) {
            throw new EExportException("ERROR: Record object is null.");
        }

        return !$this->getExportRecord()->GetFinished();
    }


    /**
     * @see IExportModel::getExportUniqueId
     */
    public function getExportUniqueId()
    {
        if ($this->hasExportRecord() === false) {
            throw new EExportException("ERROR: Record object is null.");
        }

        return $this->getExportRecord()->GetExportUniqueId();
    }


    /**
     * getWrapperName
     *
     * @access  public
     * @return  string
     */
    public function getWrapperName()
    {
        return $this->_wrapperName;
    }


    /**
     * setWrapperName
     *
     * @access  public
     * @param   string  $name
     * @return  CExportDefaultModel
     */
    public function setWrapperName($name)
    {
        $this->_wrapperName = $name;

        return $this;
    }


    /**
     * setWrapper
     *
     * @access  public
     * @param   IExportWrapper $wrapper
     * @return  IExportModel
     */
    public function setWrapper(IExportWrapper $wrapper)
    {
        $this->_wrapper = $wrapper;

        return $this;
    }


    /**
     * getWrapper
     *
     * @access  public
     * @return  IExportWrapper
     */
    public function getWrapper()
    {
        $wrapper = $this->_wrapper;

        if ($wrapper === null) {
            $wrapperName = $this->getWrapperName();

            /* @var IExportWrapper $wrapper */
            $wrapper = new $wrapperName($this->getAbsolutePath(), $this->GetExportName(), $this->getSmarty(), $this->getDB());
            $wrapper->setParameters($this->getParameters());

            $this->_wrapper = $wrapper;
        }

        return $wrapper;
    }


    /**
     * @see IExportModel::getData
     */
    public function getData()
    {
        if ($this->hasExportRecord() === false) {
            throw new EExportException("ERROR: Record object is null.");
        }

        if (true === $this->m_create_new_export) {
            $wrapper = $this->getWrapper();

            $this->extractData($this->getParameters(), $wrapper, $this->m_export_record);
            $this->preparingData($this->getParameters(), $this->m_export_record);

            $before_export_record = RExport::ReadLastFinished(
                $this->getDB(), $this->getExportName(), $this->m_parameters['exportUniqueId'],
                $this->m_parameters['org_id']);

            if (false !== $before_export_record) {
                $this->m_export_record->SetNextTan($before_export_record->GetNextTan());
                $this->CheckDiff($this->getParameters(), $this->m_export_record, $before_export_record);
            }

            $this->CheckData($this->m_parameters, $this->m_export_record);

            $this->m_export_record->Write($this->getDB());
            $this->m_create_new_export = false;
        }

        return $this->m_export_record;
    }


    /**
     * IExportModel::ExtractData
     */
    public function extractData($parameters, $wrapper, &$export_record)
    { }


    /**
     * IExportModel::preparingData
     */
    public function preparingData($parameters, &$export_record)
    { }


    /**
     * IExportModel::checkDiff
     */
    public function checkDiff($parameters, &$export_record, $before_export_record)
    {
        // Alle neuen Cases im alten suchen...
        $cases = $export_record->GetCases();

        foreach ($cases as $case_key => $case) {
            foreach ($before_export_record->GetCases() as $old_case) {
                if (($case->GetPatientId()     == $old_case->GetPatientId()) &&
                    ($case->GetErkrankungId()  == $old_case->GetErkrankungId()) &&
                    ($case->GetDiagnoseSeite() == $old_case->GetDiagnoseSeite())
                ) {
                    // if check situation flag is true and situation is another one, continue
                    if ($this->hasCheckSituationOnDiff() === true && $case->GetAnlass() !== $old_case->GetAnlass()) {
                        continue;
                    }

                    // Alle neuen Sections im alten suchen und vergleichen...
                    $sections = $case->GetSections();

                    foreach($sections as $section_key => $section) {
                        $found = false;

                        foreach($old_case->GetSections() as $old_section_key => $old_section) {
                            if ($section->GetSectionUid() == $old_section->GetSectionUid()) {
                                $found = true;
                                $section->SetDataChanged(0);

                                if ($this->IsUnequal($section, $old_section)) {
                                    $this->HandleDiff($parameters, $case, $section, $old_section);
                                } else {
                                    $old_section_data = $old_section->GetDaten();

                                    if (isset($old_section_data['tan'])) {
                                        $section_data = $section->GetDaten();
                                        $section_data['tan'] = $old_section_data['tan'];
                                        $section->SetDaten($section_data);
                                    }
                                }
                            }
                        }

                        if (!$found) {
                            $section->SetDataChanged(1);
                            // #7704
                            $section->SetMeldungskennzeichen("N");
                        }

                        $sections[$section_key] = $section;
                    }

                    $case->SetSections($sections);
                }
            }

            $cases[$case_key] = $case;
        }

        $export_record->SetCases($cases);
    }


    /**
     * @see IExportModel::HandleDiff
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    { }


    /**
     * @see IExportModel::CheckData
     */
    public function CheckData($parameters, &$export_record)
    { }


    /**
     * @see IExportModel::WriteData
     */
    public function WriteData()
    { }


    /**
     * @see IExportModel::DeleteData
     */
    public function DeleteData()
    {
        $record = $this->getExportRecord();

        if (!$record->GetFinished()) {
            $record->Delete($this->getDB());
        } else {
            throw new EExportException("ERROR: Can not delete finished exports [{$record->GetDbid()}]");
        }
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

    protected function IsUnequal($section, $old_section) {
        if ($section instanceof RExportSection) {
            $s_data = $section->GetDaten();
        }
        else if (is_array($section)) {
            $s_data = $section;
        }
        else {
            throw new EExportException("Section data is not a instance of RExportSection or an array.");
        }
        $s_data['tan'] = "";
        $s_data['meldungskennzeichen'] = "";
        if (isset($s_data['wandlung_diagnose'])) {
            $s_data['wandlung_diagnose'] = "";
        }
        if ($old_section instanceof RExportSection) {
            $os_data = $old_section->GetDaten();
        }
        else if (is_array($old_section)) {
            $os_data = $old_section;
        }
        else {
            throw new EExportException("Old section data is not a instance of RExportSection or an array.");
        }
        $os_tan = isset($os_data['tan']) ? $os_data['tan'] : '';
        $os_data['tan'] = "";
        $os_data['meldungskennzeichen'] = "";
        if (isset($os_data['wandlung_diagnose'])) {
            $os_data['wandlung_diagnose'] = "";
        }
        if (serialize($s_data) != serialize($os_data)) {
            $os_data['tan'] = $os_tan;
            return true;
        }
        return false;
    }


    /**
     * createCase
     *
     * @access  public
     * @param   int     $export_id
     * @param   array   $parameters
     * @param   array   $extract_data
     * @return  RExportCase
     */
    public static function createCase($export_id, &$parameters, &$extract_data)
    {
        $case = new RExportCase;

        $case->SetExportId($export_id);
        $case->SetPatientId($extract_data['patient_id']);
        $case->SetErkrankungId($extract_data['erkrankung_id']);

        if (array_key_exists('diagnose_seite', $extract_data) === true && strlen($extract_data['diagnose_seite']) > 0) {
            $case->SetDiagnoseSeite($extract_data['diagnose_seite']);
        } else {
            $case->SetDiagnoseSeite("-");
        }

        if (array_key_exists('anlass', $extract_data) === true && strlen($extract_data['anlass']) > 0) {
            $case->SetAnlass($extract_data['anlass']);
        } else {
            $case->SetAnlass("-");
        }


        $case->SetCreateUserId($parameters['user_id']);

        return $case;
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
     * @return  RExportSection
     */
    public static function createBlock($export_case_id, &$parameters, $block_name, $block_uid, $block_data)
    {
        $block = new RExportSection;

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
     * getExportCode
     *
     * @access  public
     * @param   string  $class
     * @param   string  $med_code
     * @param   string  $default_value
     * @return  string
     */
    public function getExportCode($class, $med_code, $default_value)
    {
        if (false === isset($this->m_l_basic[$class . "_" . $med_code])) {
            return $default_value;
        }

        return $this->m_l_basic[$class . "_" . $med_code];
    }


    /**
     *
     * @param $anamnese_id
     * @return unknown_type
     */
    protected function GetAnamneseErkrankungen($anamnese_id)
    {
        $query = "
    			SELECT
    			    ae.erkrankung,
    			    ae.erkrankung_seite,
    			    ae.erkrankung_text,
    			    ae.erkrankung_version,
    			    ae.jahr

    			FROM
    			    anamnese_erkrankung ae

    			WHERE
    			    ae.anamnese_id=$anamnese_id
    		";
        $result = sql_query_array($this->m_db, $query);
        return $result;
    }




    protected function GetExportedPatientIds()
    {
        $query = "
            SELECT DISTINCT
               epids.patient_id

            FROM
               export_patient_ids_log epids

            WHERE
               epids.export_name='{$this->GetExportName()}'
               AND epids.export_unique_id='{$this->GetExportUniqueId()}'
        ";
        $result = sql_query_array($this->m_db, $query);
        if ($result !== false) {
            $tmp = array();
            foreach($result as $row) {
                $tmp[] = $row['patient_id'];
            }
            return array_values(array_unique($tmp));
        }
        return array();
    }


    /**
     * DiffData
     *
     * @access  public
     * @param   array   $data_new
     * @param   array   $data_old
     * @param   array   $changedFields
     * @return  int
     */
    public function diffData($data_new, $data_old, &$changedFields)
    {
        $result = 0;
        // Finde alle Elemente aus $data_new die nicht in $data_old vorkommen,
        // also alle neu dazugekommenen Elemente.
        // Sollte eigentlich nicht vorkommen!!!
        $diff = array_diff_key($data_new, $data_old);
        if (count($diff) > 0) {
            foreach ($diff as $key => $value) {
                $changedFields[$key] = $key;
            }
            $result = 1;
        }
        // Finde alle Elemente aus $data_old die nicht in $data_new vorkommen,
        // also alle entfernten Elemente.
        // Sollte aber eigentlich nicht vorkommen!!!
        $diff = array_diff_key($data_old, $data_new);
        if (count($diff) > 0) {
            foreach ($diff as $key => $value) {
                $changedFields[$key] = $key;
            }
            $result = 2;
        }
        // Alle Werte überprüfen ob es eine Änderung gegeben hat.
        foreach($data_new as $key => $new_data_value) {
            if (array_key_exists($key, $data_old) && !is_array($data_old[$key])) {
                if ((strlen($data_old[$key]) == 0) && (strlen($new_data_value) > 0) && ($result != 2)) {
                    $changedFields[$key] = $key;
                    $result = 1;
                } elseif ($data_old[$key] != $new_data_value) {
                    $changedFields[$key] = $key;
                    $result = 2;
                }
            } else {
                if (isset($data_new[$key]) && isset($data_old[$key])) {
                    $sub_result = $this->DiffData($data_new[$key], $data_old[$key], $changedFields);
                    if ($sub_result != 0) {
                        if ($sub_result == 1) {
                            if ($result != 2) {
                                $result = 1;
                            }
                        } elseif ($sub_result == 2) {
                            $result = 2;
                        }
                    }
                }
            }
        }

        return $result;
    }


    /**
     * GetUser
     *
     * @access
     * @param $user_id
     * @return bool|mixed
     */
    protected function GetUser($user_id)
    {
        if (strlen($user_id) === 0) {
            return false;
        }
        if (isset($this->m_users[$user_id])) {
            return $this->m_users[$user_id];
        }
        else {
            $query = "SELECT
                        *

                      FROM
                        user u

                      WHERE
                        u.user_id={$user_id}

                      LIMIT 0, 1";
            $result = end(sql_query_array($this->m_db, $query));
            if ($result !== false) {
                $this->m_users[$user_id] = $result;
            }
        }
        return $result;
    }

    protected function GetLBasicBez($klasse, $code, $defaultValue = '') {
        if (isset($this->m_l_basic_bez[$klasse . "_" . $code])) {
            return $this->m_l_basic_bez[$klasse . "_" . $code];
        }
        return $defaultValue;
    }


    /**
     *
     *
     * @access
     * @param $iknr
     * @return mixed
     */
    protected function GetKrankenkassenDaten($iknr) {
        $query = "SELECT
                      *

                  FROM
                      l_ktst k

                  WHERE
                      k.iknr='{$iknr}'

                  LIMIT 0, 1";
        $result = end(sql_query_array($this->m_db, $query));
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $vorlage_studie_id
     * @return mixed
     */
    protected function GetStudienVorlage($vorlage_studie_id) {
        $query = "
            SELECT
                *

            FROM
                vorlage_studie vs

            WHERE
                vs.vorlage_studie_id={$vorlage_studie_id}

            LIMIT 0, 1
        ";
        $result = end(sql_query_array($this->m_db, $query));
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $vorlage_therapie_id
     * @return mixed|string
     */
    protected function GetTherapieVorlagenBezeichnung($vorlage_therapie_id) {
        $query = "
            SELECT
                vt.bez

            FROM
                vorlage_therapie vt

            WHERE
                vt.vorlage_therapie_id={$vorlage_therapie_id}

            LIMIT 0, 1
        ";
        $result = end(sql_query_array($this->m_db, $query));
        if (false === $result) {
            return '';
        }
        return $result['bez'];
    }


    /**
     *
     *
     * @access
     * @param $vorlage_therapie_id
     * @return mixed|string
     */
    protected function GetTherapieVorlagenArt($vorlage_therapie_id) {
        $query = "
            SELECT
                vt.art

            FROM
                vorlage_therapie vt

            WHERE
                vt.vorlage_therapie_id={$vorlage_therapie_id}

            LIMIT 0, 1
        ";
        $result = end(sql_query_array($this->m_db, $query));
        if (false === $result) {
            return '';
        }
        return $result['art'];
    }


    /**
     *
     *
     * @access
     * @param $vorlage_id
     * @return array
     */
    protected function GetWirkstoffe($vorlage_id)
    {
        $wirkstoffe = array();
        $query = "
            SELECT
                vtw.wirkstoff

            FROM
                vorlage_therapie_wirkstoff vtw

            WHERE
                vtw.vorlage_therapie_id={$vorlage_id}
        ";
        $result = sql_query_array($this->m_db, $query);
        if (false !== $result) {
            foreach($result as $row) {
                $wirkstoffe[] = $row['wirkstoff'];
            }
        }
        return $wirkstoffe;
    }



    /**
     * @see IExportModel::getExportRecord
     */
    public function getExportRecord()
    {
        return $this->m_export_record;
    }


    /**
     * @see IExportModel::hasExportRecord
     */
    public function hasExportRecord()
    {
        return ($this->m_export_record !== null);
    }


    /**
     * @see IExportModel::setExportRecord
     */
    public function setExportRecord($record)
    {
        $this->m_export_record = $record;

        return $this;
    }
}

?>
