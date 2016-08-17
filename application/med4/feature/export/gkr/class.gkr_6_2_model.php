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

require_once('feature/export/base/class.exportdefaultmodel.php');
require_once('class.gkr_6_2_serialiser.php');
require_once('core/class/report/helper.reports.php');
require_once('feature/export/base/helper.common.php');
require_once('class.gkr_6_2_modelHelper.php');
require_once('class.gkraddresses.php');

class Cgkr_6_2_Model extends CExportDefaultModel
{
    /**
     * @access
     * @var array
     */
    protected $_diagnoseFilter = array('C', 'D0', 'D32', 'D33', 'D35.2', 'D35.4', 'D37', 'D38', 'D39', 'D4');


    /**
     * @access
     * @var array
     */
    protected $_changedFields = array();


    protected $_diagnoseSicherungCodes = array();


    /**
     * @access
     * @var array
     */
    protected $_basaliomMorphologien =
        array(
            '80901',
            '80903',
            '80913',
            '80923',
            '80933',
            '80943',
            '80953',
            '80973'
        );


    /**
     * @access
     * @var array
     */
    protected $_mappings = array();


    /**
     * @access
     * @var array
     */
    protected $_user = array();


    /**
     * @access
     * @var null
     */
    protected $_org = null;


    /**
     *
     */
    public function __construct() {}


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $wrapper
     * @param $export_record
     * @return void
     */
    public function ExtractData($parameters, $wrapper, &$export_record)
    {
        $this->_readDiagnoseSicherungCodes();

        $wrapper->SetRangeDate($parameters['datum_von'], $parameters['datum_bis']);
        $wrapper->SetErkrankungen('all');
        $wrapper->SetDiagnosen($this->_buildDiagnoseFilter());

        $wrapper->addHaving("(t.anlass = 'b' OR t.anlass = 'p')");

        $stageCalc = stageCalc::create($this->m_db);

        $this
            ->_addPatientData($wrapper)
            ->_addAnamnesisData($wrapper)
            ->_addPrimaryTumorData($wrapper, $stageCalc)
            ->_addPrimaryTherapyData($wrapper)
            ->_addConclusionData($wrapper)
        ;

        $result = $wrapper->GetExportData($parameters);

        $this
            ->_setDiagnoseLocalistions()
            ->_setRequiredEkrUserData($wrapper)
            ->_setRequiredOrg($parameters['org_id'])
        ;

        $orgAddress = $this->_getOrgAddress();

        foreach ($result as $extract_data) {

            $extract_data = $this->_removeFutureTherapies($extract_data);

            if (count($orgAddress) > 0 && $this->_checkAdress($extract_data, $orgAddress) === true) {
                continue;
            }

            if ($this->_bundeslandCheck($extract_data)) {

                $extract_data['operationen'] = $this->_getPrimaryOperations($extract_data['operationen']);

                $stageCalc->setSub($extract_data['erkrankung']);

                // Für Ticket #13428
                //$extract_data['ct_uicc'] = $stageCalc->calc($extract_data['ct_uicc']);
                //$extract_data['pt_uicc'] = $stageCalc->calc($extract_data['pt_uicc']);

                // Create main case
                $case = $this->CreateCase($export_record->GetDbid(), $parameters, $extract_data);

                $this
                    ->_addCaseSection($case, $parameters, $extract_data, 'patient')
                    ->_addCaseSection($case, $parameters, $extract_data, 'anamnesis')
                    ->_addCaseSection($case, $parameters, $extract_data, 'primary')
                    ->_addCaseSection($case, $parameters, $extract_data, 'therapy')
                    ->_addCaseSection($case, $parameters, $extract_data, 'conclusion')
                    ->_addCaseSection($case, $parameters, $extract_data, 'general')
                ;

                // Add main case
                $export_record->AddCase($case);
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $operations array
     * @return array
     */
    protected function _getPrimaryOperations($operations)
    {
        $result = array();
        foreach ($operations as $operation) {
            if ('1' == $operation['art_primaertumor']) {
                $result[] = $operation;
            }
        }
        return $result;
    }


    /**
     * _addCaseSection
     *
     * @access  protected
     * @param   RExportCase $case
     * @param   array       $parameters
     * @param   array       $data
     * @param   string      $name
     * @return  Cgkr_6_2_Model
     */
    protected  function _addCaseSection(RExportCase $case, $parameters, $data, $name)
    {
        $method = '_create' . ucfirst($name) . 'Section';

        $sectionUID = null;

        $section = $this->{$method}($data, $sectionUID);

        $case->AddSection($this->CreateBlock($case->GetDbid(), $parameters, $name, $sectionUID, $section));

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function PreparingData($parameters, &$export_record)
    {
        $patientCases = array();
        $currentRefNr = null;
        $oldestBasaliomCase = null;

        $cases = $export_record->GetCases();
        foreach ($cases as $caseKey => &$case) {
            $patient = $this->_getSectionData($case, 'patient');
            if ((null != $currentRefNr) &&
                ($currentRefNr != $patient['REF_NR'])) {
                $this->_preparingMeldungskennzeichen($patientCases);
                $currentRefNr = $patient['REF_NR'];
                $patientCases = array();
                $patientCases[] = &$case;
            }
            else {
                $patientCases[] = &$case;
                $currentRefNr = $patient['REF_NR'];
            }
        }
        $this->_preparingMeldungskennzeichen($patientCases);
        $export_record->SetCases($cases);
    }


    /**
     *
     *
     * @access
     * @param $patientCases
     * @return void
     */
    protected function _preparingMeldungskennzeichen(&$patientCases)
    {
        $oldestBasaliomCase = null;

        foreach ($patientCases as &$patientCase) {

            if ($this->_isOlderAsFiveYears($patientCase) || $this->_checkEinzugsgebiet($patientCase) === false) {
                // Then: e
                $patientCase->setMeldungskennzeichen('e');
                $this->_setMeldetype($patientCase, 'e');
            } else {
                // Hier automatisch kleiner 5 Jahre und im Einzgsbereich des GKR
                if ($this->_isBasaliom($patientCase)) {
                    // Then: b
                    $patientCase->setMeldungskennzeichen('b');
                    $this->_setMeldetype($patientCase, 'b');
                    $oldestBasaliomCase = $this->_getOldestCast($oldestBasaliomCase, $patientCase);
                } else {
                    // Then: E
                    $patientCase->setMeldungskennzeichen('E');
                    $this->_setMeldetype($patientCase, 'E');
                }
            }

        }
        if (null !== $oldestBasaliomCase) {
            $oldestBasaliomCase->setMeldungskennzeichen('B');
            $this->_setMeldetype($oldestBasaliomCase, 'B');
        }
    }


    /**
     *
     *
     * @access
     * @param $oldestBasaliomCase
     * @param $case
     * @return mixed
     */
    protected function &_getOldestCast(&$oldestBasaliomCase, &$case)
    {
        if (null == $oldestBasaliomCase) {
            return $case;
        }
        $primaryOldest = $this->_getSectionData($oldestBasaliomCase, 'primary');
        $primaryCase = $this->_getSectionData($case, 'primary');
        $primaryOldestTime = strtotime($primaryOldest['DIDA']);
        if ($primaryOldestTime > strtotime($primaryCase['DIDA'])) {
            return $case;
        }
        return $oldestBasaliomCase;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section
     * @param $old_section
     * @return void
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        $patient = $this->_getSectionData($case, 'patient');
        $s_section_data = $section->GetDaten();
        $s_old_section_data = $old_section->GetDaten();
        if ('general' == $section->GetBlock()) {
            // Damit der MTYP und DMN nicht mit überprüft werden...
            $s_section_data['MTYP'] = "";
            $s_section_data['DMN'] = "";
            $s_section_data['ANGKR'] = "";
            $s_old_section_data['MTYP'] = "";
            $s_old_section_data['DMN'] = "";
            $s_old_section_data['ANGKR'] = "";
        }
        $id = $patient['_patientId'] . "-" . $patient['_seite'];
        $changedFields = array();
        $result = $this->DiffData($s_section_data, $s_old_section_data, $changedFields);
        if (!isset($this->_changedFields[$id])) {
            $this->_changedFields[$id] = $changedFields;
        }
        else {
            $this->_changedFields[$id] =
                array_merge($this->_changedFields[$id], $changedFields);
        }

        // Nochmal holen damit MTYP und DMN wieder beachtet werden...
        $section->SetDataChanged(0);
        if (1 == $result) {
            $section->SetMeldungskennzeichen("f");
            if ('conclusion' == $section->GetBlock()) {
                $conclusion = $section->GetDaten();
                if ('v' == $conclusion['BEF']) {
                    $section->SetMeldungskennzeichen("t");
                }
            }
            $section->SetDataChanged(1);
        } elseif (2 == $result) {
            $section->SetMeldungskennzeichen("k");
            if ('conclusion' == $section->GetBlock()) {
                $conclusion = $section->GetDaten();
                if ('v' == $conclusion['BEF']) {
                    $section->SetMeldungskennzeichen("t");
                }
            }
            $section->SetDataChanged(1);
        }
    }

    protected function _removeHelperFields($fields)
    {
        $result = array();
        foreach ($fields as $key => $field) {
            if (('_patientId' != $field) &&
                ('_seite' != $field) &&
                ('_GEDALong' != $field) &&
                ('_strasse' != $field) &&
                ('_hausnr' != $field) &&
                ('_DIDALong' != $field) &&
                ('_STDALong' != $field) &&
                ('_DMNLong' != $field) &&
                ('_ANGKRExt' != $field) &&
                ('_einzugsgebiet' != $field)) {
                $result[$key] = $field;
            }
        }
        return $result;
    }

    public function CheckData($parameters, &$export_record)
    {
        $cases = $export_record->GetCases();
        foreach ($cases as $caseKey => &$case) {
            $patientData = $this->_getSectionData($case, 'patient');
            $generalData = $this->_getSectionData($case, 'general');
            $id = $patientData['_patientId'] . "-" . $patientData['_seite'];
            $str = $generalData['_ANGKRExt'];
            if (isset($this->_changedFields[$id]) && (count($this->_changedFields[$id]) > 0)) {
                $cfStr = implode(',', $this->_removeHelperFields($this->_changedFields[$id]));
                if (strlen($cfStr) > 0) {
                    if (strlen($str) > 0) {
                        $str .= ',' . $cfStr;
                    }
                    else {
                        $str = $cfStr;
                    }
                }
            }
            $this->_setAnGkr($case, $str);
        }
        $export_record->SetCases($cases);

        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Cgkr_6_2_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(),
            $this->m_smarty, $this->m_db, $this->m_error_function
        );
        $serialiser->SetData($export_record);
        $serialiser->Validate($this->m_parameters);
    }

    public function WriteData()
    {
        $this->m_export_record->SetFinished(true);
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Cgkr_6_2_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(),
            $this->m_smarty, $this->m_db, $this->m_error_function
        );
        $serialiser->SetData($this->m_export_record);
        $this->m_export_filename = $serialiser->Write($this->m_parameters);
        $this->m_export_record->Write($this->m_db);
    }


    //*********************************************************************************************
    //
    // Append Data to wrapper
    //

    /**
     *
     *
     * @access
     * @param $extract_data
     * @return bool
     */
    protected function _bundeslandCheck($extract_data)
    {
        if ((('BB' == $this->_org['bundesland']) || ('ST' == $this->_org['bundesland'])) &&
            ('zW' == $extract_data['kr_meldung']['meldebegruendung']))
        {
            // Dann darf kein Export passieren!
        }
        return true;
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return bool
     */
    protected function _patientAddressCheck($case)
    {
        $patient = $this->_getSectionData($case, 'patient');
        $result = CGkrAddresses::getInstance()->checkAddress($patient['PLZN'], $patient['ORTG']);
        if (0 == $result) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access  protected
     * @param   $case
     * @return  bool
     */
    protected function _checkEinzugsgebiet($case)
    {
        $patientData = $this->_getSectionData($case, 'patient');

        return $patientData['_einzugsgebiet'] === '0' ? false : true;
    }


    protected function _removeFutureTherapies($data)
    {
        $now = date('Y-m-d');

        foreach (array('systemische_therapien', 'strahlen_therapien', 'sonstige_therapien') as $type) {
            if (count($data[$type]) > 0) {

                $tmp = array();

                foreach ($data[$type] as $therapy) {
                    if ($therapy['beginn'] <= $now) {
                        $tmp[] = $therapy;
                    }
                }

                $data[$type] = $tmp;
            }
        }

        return $data;
    }


    protected function _getOrgAddress()
    {
        $plz = Cgkr_6_2_ModelHelper::PLZ_E($this->_getOrg());
        $ort = Cgkr_6_2_ModelHelper::ORT_E($this->_getOrg());
        $str = Cgkr_6_2_ModelHelper::STR_E($this->_getOrg());

        $orgAddress = array();

        if ($plz !== null && $ort !== null && $str !== '') {
            $orgAddress = array(
                $plz,
                $ort,
                $str
            );
        }

        return $orgAddress;
    }

    /**
     * Ignore Patient if patients address is equal to org address
     *
     * @access  protected
     * @param   $data
     * @param   $orgAdress
     * @return  bool
     */
    protected function _checkAdress($data, $orgAdress)
    {
        $patAdress = array(
            Cgkr_6_2_ModelHelper::PLZN($data),
            Cgkr_6_2_ModelHelper::ORTG($data),
            Cgkr_6_2_ModelHelper::STR($data)
        );

        return $patAdress === $orgAdress ? true : false;
    }


    /**
     *
     *
     * @access
     * @param $date
     * @return bool|string
     */
    protected function getFormatedDate($date)
    {
        $result = date('d.m.Y', strtotime($date));
        if (strlen($date) == 6) {
            $day = substr($date, 0, 2);
            $month = substr($date, 2, 2);
            $year = substr($date, 4, 2);
            $result = "{$day}.{$month}.20{$year}";
        }
        else if (strlen($date) == 8) {
            $day = substr($date, 0, 2);
            $month = substr($date, 2, 2);
            $year = substr($date, 4, 4);
            $result = "{$day}.{$month}.{$year}";
        }
        return $result;
    }

    /**
     *
     *
     * @access
     * @param $case
     * @return bool
     */
    protected function _isBasaliom($case)
    {
        $primary = $this->_getSectionData($case, 'primary');
        if (in_array($primary['ICM'], $this->_basaliomMorphologien)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $case
     * @return bool
     */
    protected function _isOlderAsFiveYears($case)
    {
        $primary = $this->_getSectionData($case, 'primary');

        $diff = $this->_diffYear($this->getFormatedDate($primary['DIDA']), $this->getFormatedDate(date('Y-m-d')));

        if ($diff >= 5) {
            return true;
        }

        return false;
    }


    /**
     *
     *
     * @access
     * @param $from
     * @param $to
     * @return mixed
     */
    protected function _diffYear($from, $to)
    {
        $from = is_array($from) === true ? $from : explode('-', date('Y-m-d', strtotime($from)));
        $to = is_array($to) === true ? $to : explode('-', date('Y-m-d', strtotime($to)));
        $mod = $to[1] <= $from[1] ? ($to[1] < $from[1] || $to[2] < $from[2] ? 1 : 0) : 0;
        return $to[0] - $from[0] - $mod;
    }


    /**
     *
     *
     * @access
     * @param $case
     * @param $meldetype
     * @return void
     */
    protected function _setMeldetype(&$case, $meldetype)
    {
        $sections = $case->GetSections();
        foreach ($sections as $key => &$section) {
            if ('general' == $section->GetBlock()) {
                $data = $section->GetDaten();
                $data['MTYP'] = $meldetype;
                $section->SetDaten($data);
            }
        }
    }

    /**
     *
     *
     * @access
     * @param $case
     * @param $meldetype
     * @return void
     */
    protected function _setAnGkr(&$case, $angkr)
    {
        $sections = $case->GetSections();
        foreach ($sections as $key => &$section) {
            if ('general' == $section->GetBlock()) {
                $data = $section->GetDaten();
                if (strlen($data['ANGKR']) > 0) {
                    $data['ANGKR'] .= ',' . $angkr;
                }
                else {
                    $data['ANGKR'] .= $angkr;
                }
                $section->SetDaten($data);
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $case
     * @param $blockName
     * @return array
     */
    protected function _getSectionData($case, $blockName)
    {
        $result = array();
        foreach ($case->GetSections() as $section) {
            if ($blockName == $section->GetBlock()) {
                $result = $section->GetDaten();
            }
        }
        return $result;
    }


    /**
     * add patient data
     *
     * @access protected
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addPatientData(CExportWrapper $wrapper)
    {
        $wrapper
            ->addAdditionalSelect(array(
                'p.titel',
                'p.geburtsname',
                'p.staat',
                'p.plz',
                'p.ort',
                'p.strasse',
                'p.hausnr'
            ))
            ->addAdditionalFields(array(
                'sit.titel',
                'sit.geburtsname',
                'sit.staat',
                'sit.plz',
                'sit.ort',
                'sit.strasse',
                'sit.hausnr',
                'sit.patient_nr'
            ))
        ;
        return $this;
    }


    /**
     *
     *
     * @access  protected
     * @param   CExportWrapper $wrapper
     * @return  $this
     */
    protected function _addAnamnesisData(CExportWrapper $wrapper)
    {
        $this
            ->_addMapping('erkrankung_therapie')
        ;

        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;

        $wrapper
            ->addAdditionalJoin("LEFT JOIN anamnese_erkrankung ane ON ane.anamnese_id = an.anamnese_id AND ({$this->_buildDiagnoseFilter('ane.erkrankung')})")
            ->addAdditionalField("
                IF( MAX( ane.anamnese_erkrankung_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( ane.anamnese_erkrankung_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(ane.anamnese_id, ''),
                            IFNULL(ane.erkrankung_text, ''),
                            IFNULL(ane.jahr, ''),
                            IFNULL(ane.therapie1, ''),
                            IFNULL(ane.therapie2, ''),
                            IFNULL(ane.therapie3, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'anamnesis_disease'
            ")
            ->addAdditionalField("
                IF( MAX( an.anamnese_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( an.anamnese_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(an.anamnese_id, ''),
                            IFNULL(an.datum, ''),
                            IFNULL(an.mehrlingseigenschaften, ''),
                            IFNULL(an.beruf_laengster, ''),
                            IFNULL(an.beruf_laengster_dauer, ''),
                            IFNULL(an.beruf_letzter, ''),
                            IFNULL(an.beruf_letzter_dauer, ''),
                            IFNULL(an.geburten_lebend, ''),
                            IFNULL(an.geburten_tot, ''),
                            IFNULL(an.geburten_fehl, ''),
                            IFNULL(an.entdeckung, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'anamnesis'
            ")
        ;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addPrimaryTumorData(CExportWrapper $wrapper, stageCalc $stageCalc)
    {
        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;

        $basicOrder     = 'ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1';
        $basicCondition = "FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";

        $wrapper
            ->addAdditionalJoin("LEFT JOIN histologie_einzel he ON he.histologie_id = h_a.histologie_id")
            ->addAdditionalJoin("LEFT JOIN untersuchung u ON s.form = 'untersuchung' AND u.untersuchung_id = s.form_id")
        ;

        $this
            ->_addMapping('l')
            ->_addMapping('v')
            ->_addMapping('ppn')
        ;

        $wrapper
            // Für Ticket #13428
            //->addAdditionalSelect($stageCalc->select(null, 'uicc', true, "LEFT( ts.t, 1 )='c'") . " AS 'ct_uicc'")
            //->addAdditionalSelect($stageCalc->select(null, 'uicc', true, "LEFT( ts.t, 1 )='p'") . " AS 'pt_uicc'")
            ->addAdditionalSelect("(SELECT ts.tnm_praefix {$basicCondition} AND LEFT( ts.t, 1 )='c' $basicOrder) AS 'ct_praefix'")
            ->addAdditionalSelect("(SELECT ts.tnm_praefix {$basicCondition} AND LEFT( ts.t, 1 )='p' $basicOrder) AS 'pt_praefix'")
            ->addAdditionalSelect("(SELECT ts.l {$basicCondition} AND LEFT( ts.t, 1 )='c' $basicOrder) AS 'ct_l'")
            ->addAdditionalSelect("(SELECT ts.l {$basicCondition} AND LEFT( ts.t, 1 )='p' $basicOrder) AS 'pt_l'")
            ->addAdditionalSelect("(SELECT ts.v {$basicCondition} AND LEFT( ts.t, 1 )='c' $basicOrder) AS 'ct_v'")
            ->addAdditionalSelect("(SELECT ts.v {$basicCondition} AND LEFT( ts.t, 1 )='p' $basicOrder) AS 'pt_v'")
            ->addAdditionalSelect("(SELECT ts.ppn {$basicCondition} AND LEFT( ts.t, 1 )='c' $basicOrder) AS 'ct_ppn'")
            ->addAdditionalSelect("(SELECT ts.ppn {$basicCondition} AND LEFT( ts.t, 1 )='p' $basicOrder) AS 'pt_ppn'")
            // Für Ticket #13428
            ->addAdditionalSelect("(SELECT ts.uicc {$basicCondition} AND LEFT( ts.t, 1 )='c' $basicOrder) AS 'ct_uicc'")
            ->addAdditionalSelect("(SELECT ts.uicc {$basicCondition} AND LEFT( ts.t, 1 )='p' $basicOrder) AS 'pt_uicc'")
            //
            ->addAdditionalSelect("(SELECT ts.n {$basicCondition} AND ts.n IS NOT NULL $basicOrder) AS 'n'")
            ->addAdditionalSelect("(SELECT ts.t {$basicCondition} AND ts.t IS NOT NULL $basicOrder) AS 't'")
            ->addAdditionalSelect("(SELECT ts.ann_arbor_extralymphatisch {$basicCondition} AND ts.ann_arbor_extralymphatisch IS NOT NULL $basicOrder) AS 'ann_arbor_extralymphatisch'")
            ->addAdditionalSelect("(SELECT ts.diagnose_text {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder) AS 'diagnose_text'")
            ->addAdditionalSelect("(SELECT ts.lokalisation_text {$basicCondition} AND ts.lokalisation IS NOT NULL {$basicOrder}) AS lokalisation_text")
            ->addAdditionalSelect("(SELECT ts.lokalisation_seite {$basicCondition} AND ts.lokalisation_seite IS NOT NULL $basicOrder) AS 'lokalisation_seite'")
            ->addAdditionalSelect("(SELECT ts.morphologie_text {$basicCondition} AND ts.morphologie IS NOT NULL {$basicOrder}) AS morphologie_text")
            ->addAdditionalSelect("(SELECT ts.diagnosesicherung {$basicCondition} AND ts.diagnosesicherung IS NOT NULL {$basicOrder}) AS diagnosesicherung")
            ->addAdditionalSelect("(SELECT
                CONCAT_WS('|',
                    IFNULL(ts.tumorausbreitung_lokal,''),
                    IFNULL(ts.tumorausbreitung_konausdehnung,''),
                    IFNULL(ts.tumorausbreitung_lk,''),
                    IFNULL(ts.tumorausbreitung_fernmetastasen,'')
                )
                {$basicCondition} AND
                (ts.tumorausbreitung_lokal IS NOT NULL OR
                 ts.tumorausbreitung_konausdehnung IS NOT NULL OR
                 ts.tumorausbreitung_lk IS NOT NULL OR
                 ts.tumorausbreitung_fernmetastasen IS NOT NULL
                )
                {$basicOrder})
                AS tumorausbreitung
            ")
        ;

        $wrapper
            ->addAdditionalField('sit.datum_sicherung')
            ->addAdditionalField('sit.ct_praefix')
            ->addAdditionalField('sit.ct_l')
            ->addAdditionalField('sit.start_date_rezidiv as min_tumorstate')
            ->addAdditionalField('sit.ct_v')
            ->addAdditionalField('sit.ct_ppn')
            ->addAdditionalField('sit.pt_praefix')
            ->addAdditionalField('sit.pt_l')
            ->addAdditionalField('sit.pt_v')
            ->addAdditionalField('sit.pt_ppn')
            ->addAdditionalField('sit.cm as ct_cm') //Workaround
            ->addAdditionalField('sit.m  as pt_cm') //Workaround
            ->addAdditionalField("sit.n")
            ->addAdditionalField("sit.ct_uicc")
            ->addAdditionalField("sit.pt_uicc")
            ->addAdditionalField("sit.t")
            ->addAdditionalField("sit.ann_arbor_extralymphatisch")
            ->addAdditionalField("sit.ann_arbor_aktivitaetsgrad")
            ->addAdditionalField("sit.diagnose_text")
            ->addAdditionalField("sit.ann_arbor_stadium")
            ->addAdditionalField('sit.tumorausbreitung')
            ->addAdditionalField("sit.lokalisation_seite")
            ->addAdditionalField("sit.lokalisation_text")
            ->addAdditionalField("sit.morphologie_text")
            ->addAdditionalField("sit.diagnosesicherung")
            ->addAdditionalField("GROUP_CONCAT(DISTINCT he.clark) AS 'clark'")
            ->addAdditionalField("IF(MAX(krm.datum) IS NOT NULL, krm.einzugsgebiet, NULL) AS 'einzugsgebiet'")
            ->addAdditionalField("MAX(he.tumordicke) AS 'tumordicke'")
            ->addAdditionalField('COUNT(DISTINCT z.zytologie_id) as zyto_count')
            ->addAdditionalField("
                IF(MAX(z.zytologie_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(z.zytologie_id IS NOT NULL AND z.eingriff_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            z.zytologie_id,
                            'zyto',
                            z.datum,
                            z.eingriff_id
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'dida_zytologie'
            ")
            ->addAdditionalField("GROUP_CONCAT(DISTINCT IF(u.untersuchung_id IS NOT NULL, CONCAT_WS('|', u.untersuchung_id, u.datum), null)) as dida_untersuchung")
            ->addAdditionalField("GROUP_CONCAT(DISTINCT IF(op.eingriff_id IS NOT NULL, CONCAT_WS('|', op.eingriff_id, op.datum), null)) as dida_eingriff")
            ->addAdditionalField("
                IF(MAX(h_a.histologie_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(h_a.histologie_id IS NOT NULL AND (h_a.untersuchung_id IS NOT NULL OR h_a.eingriff_id IS NOT NULL),
                        CONCAT_WS( '{$separator_col}',
                            h_a.histologie_id,
                            'histo',
                            h_a.datum,
                            IFNULL(h_a.untersuchung_id, ''),
                            IFNULL(h_a.eingriff_id, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'dida_histologie'
            ")
          //  ->addAdditionalJoin("LEFT JOIN anamnese_erkrankung ane ON ane.anamnese_id = an.anamnese_id AND ({$this->_buildDiagnoseFilter('ane.erkrankung')})");
        ;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addPrimaryTherapyData(CExportWrapper $wrapper)
    {
        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;

        $wrapper->addAdditionalField("
            IF(MAX(tp.therapieplan_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(tp.therapieplan_id IS NOT NULL AND tp.intention IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            tp.therapieplan_id,
                            IFNULL(tp.datum, ''),
                            IFNULL(tp.intention, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'therapieplan'
        ");

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addConclusionData(CExportWrapper $wrapper)
    {
        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;

        $wrapper
            ->addAdditionalJoin('LEFT JOIN abschluss_ursache au ON au.abschluss_id = x.abschluss_id')
            ->addAdditionalField("MAX(x.autopsie) AS 'autopsie'")
            ->addAdditionalField("MAX(x.tod_ursache) AS 'tod_ursache'")
            ->addAdditionalField("MAX(x.tod_ursache_text) AS 'tod_ursache_text'")
            ->addAdditionalField("IF({$this->_buildDiagnoseFilter('MAX(x.tod_ursache)')}, MAX(x.tod_ursache_dauer), null) AS 'tod_ursache_dauer'")
            ->addAdditionalField("MAX(x.ursache_quelle) AS 'ursache_quelle'")
            ->addAdditionalField("
                IF(MAX(au.abschluss_ursache_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(au.abschluss_ursache_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(au.krankheit, ''),
                            IFNULL(au.krankheit_text, ''),
                            IFNULL(au.krankheit_dauer, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'conclusionCause'
            ")
        ;

        return $this;
    }


    /**
     * create patient section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createPatientSection($data, &$sectionUID)
    {
        $sectionUID = 'PAT_' . $data['patient_id'];

        $section = array(
            '_patientId' => $data['patient_id'],
            '_seite'     => $data['diagnose_seite'],
            'GEDA'       => Cgkr_6_2_ModelHelper::convertDate(Cgkr_6_2_ModelHelper::GEDA($data)),
            '_GEDALong'  => Cgkr_6_2_ModelHelper::GEDA($data),
            'NAMG'       => $data['nachname'],
            'VNAG'       => $data['vorname'],
            'GNAG'       => $data['geburtsname'],
            'FNAG'       => null,
            'TITEL'      => Cgkr_6_2_ModelHelper::TITEL($data),
            'SEXG'       => Cgkr_6_2_ModelHelper::SEXG($data),
            'SAN'        => Cgkr_6_2_ModelHelper::SAN($data),
            'STR'        => Cgkr_6_2_ModelHelper::STR($data),
            'PLZN'       => Cgkr_6_2_ModelHelper::PLZN($data),
            'ORTG'       => Cgkr_6_2_ModelHelper::ORTG($data),
            'REF_NR'     => Cgkr_6_2_ModelHelper::REFNR($data),
            '_strasse'   => $data['strasse'],
            '_hausnr'    => $data['hausnr'],
            '_einzugsgebiet' => $data['einzugsgebiet']
        );

        return $section;
    }


    /**
     *
     */
    protected function _getKrMeldungsText($krMeldungen)
    {
        if (is_array($krMeldungen)) {
            $krMeldung = HReports::GetMaxElementByDate($krMeldungen, 7, null);
            if (false !== $krMeldung) {
                return $krMeldung['mitteilung_krebsregister'];
            }
        }
        return "";
    }


    /**
     * create anamnesis section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createAnamnesisSection($data, &$sectionUID)
    {
        $sectionUID = 'ANAM_' . $data['patient_id'];

        $anamnesis = HReports::OrderRecordsByField(HReports::RecordStringToArray($data['anamnesis'], array(
            'anamnese_id',
            'datum',
            'mehrlingseigenschaften',
            'beruf_laengster',
            'beruf_laengster_dauer',
            'beruf_letzter',
            'beruf_letzter_dauer',
            'geburten_lebend',
            'geburten_tot',
            'geburten_fehl',
            'entdeckung'
        )), 'datum', 'DESC');

        $anamnesisDisease = HReports::RecordStringToArray($data['anamnesis_disease'], array(
           'anamnese_id',
           'erkrankung_text',
           'jahr',
           'therapie1',
           'therapie2',
           'therapie3'
        ));

        $section = array(
            'MEHRL'   => Cgkr_6_2_ModelHelper::MEHRL($anamnesis),
            'BF1N'    => Cgkr_6_2_ModelHelper::BF1N($anamnesis),
            'JALAEN'  => Cgkr_6_2_ModelHelper::JALAEN($anamnesis),
            'BF2'     => Cgkr_6_2_ModelHelper::BF2($anamnesis),
            'JALET'   => Cgkr_6_2_ModelHelper::JALET($anamnesis),
            'KINL'    => Cgkr_6_2_ModelHelper::KINL($anamnesis),
            'KINT'    => Cgkr_6_2_ModelHelper::KINT($anamnesis),
            'KINF'    => Cgkr_6_2_ModelHelper::KINF($anamnesis),
            'BKZG'    => null,
            'VORT'    => Cgkr_6_2_ModelHelper::VORT($anamnesisDisease, $this->_getMapping('erkrankung_therapie')),
            'ANLDIAG' => Cgkr_6_2_ModelHelper::ANLDIAG($anamnesis)
        );

        return $section;
    }


    /**
     * create primary section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createPrimarySection($data, &$sectionUID)
    {
        $sectionUID = 'PRIM_' . $data['patient_id'];

        $zyto = HReports::RecordStringToArray($data['dida_zytologie'], array(
            'zytologie_id',
            'type',
            'date',
            'eingriff_id'
        ));

        $histo = HReports::RecordStringToArray($data['dida_histologie'], array(
            'histologie_id',
            'type',
            'date',
            'untersuchung_id',
            'eingriff_id'
        ));

        $dida = array_merge($zyto, $histo);

        $datumDida = Cgkr_6_2_ModelHelper::DIDA($data, HReports::OrderRecordsByField($dida, 'date', 'ASC'));

        $section = array(
            'DIDA'      => Cgkr_6_2_ModelHelper::convertDate($datumDida, 'dmy'),
            '_DIDALong' => $datumDida,
            'DTEXT'     => Cgkr_6_2_ModelHelper::DTEXT($data),
            'ICDZ'      => Cgkr_6_2_ModelHelper::ICDZ($data),
            'LOKT'      => Cgkr_6_2_ModelHelper::LOKT($data, $this->_getMapping('l_exp_diagnose')),
            'ICT'       => Cgkr_6_2_ModelHelper::ICT($data, $this->_getMapping('l_exp_diagnose')),
            'LATN'      => Cgkr_6_2_ModelHelper::LATN($data),
            'HFK'       => Cgkr_6_2_ModelHelper::HFK($data),
            'ICM'       => Cgkr_6_2_ModelHelper::ICM($data),
            'ICM_VER'   => 'I3',
            'GRADING'   => Cgkr_6_2_ModelHelper::GRADING($data),
            'TAUSB'     => Cgkr_6_2_ModelHelper::TAUSB($data),
            'STA_VER1'  => Cgkr_6_2_ModelHelper::STA_VER1($data),
            'STADIUM1'  => Cgkr_6_2_ModelHelper::STADIUM1($data),
            'STA_VER2'  => Cgkr_6_2_ModelHelper::STA_VER2($data),
            'STADIUM2'  => Cgkr_6_2_ModelHelper::STADIUM2($data),
            'TNMprae'   => Cgkr_6_2_ModelHelper::TNMprae(
                $data, $this->_mappings, Cgkr_6_2_ModelHelper::convertDate($datumDida, 'Y-m-d')),
            'TNMpost'   => Cgkr_6_2_ModelHelper::TNMpost(
                $data, $this->_mappings, Cgkr_6_2_ModelHelper::convertDate($datumDida, 'Y-m-d')),
            'HDSICH'    => $this->_encodeHdsich(Cgkr_6_2_ModelHelper::HDSICH($data))
        );

        return $section;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _readDiagnoseSicherungCodes()
    {
        $query = "
            SELECT
                code,
                code_gkr

            FROM
                l_basic

            WHERE
                klasse = 'diagnosesicherung'
        ";
        $result = sql_query_array($this->m_db, $query);
        foreach ($result as $row) {
            $this->_diagnoseSicherungCodes[$row['code']] = $row['code_gkr'];
        }
    }


    /**
     *
     *
     * @access
     * @param $code
     * @return mixed
     */
    protected function _encodeHdsich($code)
    {
        if (isset($this->_diagnoseSicherungCodes[$code])) {
            return $this->_diagnoseSicherungCodes[$code];
        }
        return $code;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $sectionUID
     * @return array
     */
    protected function _createTherapySection($data, &$sectionUID)
    {
        $sectionUID = 'THERAPY_' . $data['patient_id'];

        $therapyplan = HReports::OrderRecordsByField(HReports::RecordStringToArray($data['therapieplan'], array(
            'therapieplan_id',
            'datum',
            'intention'
        )), 'datum', 'ASC');

        $operations = HReports::OrderRecordsByField($data['operationen'], 'beginn', 'ASC');

        $mergedTherapys = array_merge($data['strahlen_therapien'], $data['systemische_therapien']);

        $therapies = HReports::OrderRecordsByField($mergedTherapys, 'beginn', 'ASC');

        $section = array(
            'CHAT'  => Cgkr_6_2_ModelHelper::CHAT($therapyplan),
            'OPE'   => Cgkr_6_2_ModelHelper::OPE($operations),
            'DMOPE' => Cgkr_6_2_ModelHelper::DMOPE($operations),
            'STT'   => Cgkr_6_2_ModelHelper::STT($therapies),
            'DMSTT' => Cgkr_6_2_ModelHelper::DMSTT($therapies),
            'CHE'   => Cgkr_6_2_ModelHelper::CHE($therapies),
            'DMCHE' => Cgkr_6_2_ModelHelper::DMCHE($therapies),
            'HOR'   => Cgkr_6_2_ModelHelper::HOR($therapies),
            'DMHOR' => Cgkr_6_2_ModelHelper::DMHOR($therapies),
            'IMM'   => Cgkr_6_2_ModelHelper::IMM($therapies),
            'DMIMM' => Cgkr_6_2_ModelHelper::DMIMM($therapies),
            'AND'   => Cgkr_6_2_ModelHelper::AND_($data),
        );

        return $section;
    }



    /**
     * create conclusion section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createConclusionSection($data, &$sectionUID)
    {
        $sectionUID = 'ABSCH_' . $data['patient_id'];

        $conclusionCause = HReports::RecordStringToArray($data['conclusionCause'], array(
            'krankheit',
            'krankheit_text',
            'krankheit_dauer'
        ));

        $section = array(
            'BEF'  => Cgkr_6_2_ModelHelper::BEF($data),
            'STDA' => Cgkr_6_2_ModelHelper::convertDate(Cgkr_6_2_ModelHelper::STDA($data), 'dmy'),
            '_STDALong' => Cgkr_6_2_ModelHelper::STDA($data),
            'SEK'  => Cgkr_6_2_ModelHelper::SEK($data),
            'T1A'  => Cgkr_6_2_ModelHelper::T1A($data),
            'X1A'  => Cgkr_6_2_ModelHelper::X1A($data),
            'Z1A'  => Cgkr_6_2_ModelHelper::Z1A($data),
            'T1B'  => Cgkr_6_2_ModelHelper::T1B($conclusionCause),
            'X1B'  => Cgkr_6_2_ModelHelper::X1B($conclusionCause),
            'Z1B'  => Cgkr_6_2_ModelHelper::Z1B($conclusionCause),
            'T1C'  => Cgkr_6_2_ModelHelper::T1C($conclusionCause),
            'X1C'  => Cgkr_6_2_ModelHelper::X1C($conclusionCause),
            'Z1C'  => Cgkr_6_2_ModelHelper::Z1C($conclusionCause),
            'T2A'  => Cgkr_6_2_ModelHelper::T2A($conclusionCause),
            'X2A'  => Cgkr_6_2_ModelHelper::X2A($conclusionCause),
            'Z2A'  => Cgkr_6_2_ModelHelper::Z2A($conclusionCause),
            'T2B'  => Cgkr_6_2_ModelHelper::T2B($conclusionCause),
            'X2B'  => Cgkr_6_2_ModelHelper::X2B($conclusionCause),
            'Z2B'  => Cgkr_6_2_ModelHelper::Z2B($conclusionCause),
            'QTU'  => Cgkr_6_2_ModelHelper::QTU($data),
            'TURS' => Cgkr_6_2_ModelHelper::TURS($data)
        );

        return $section;
    }


    /**
     * create general section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createGeneralSection($data, &$sectionUID)
    {
        $sectionUID = 'GEN_' . $data['patient_id'];

        $org  = $this->_getOrg();
        $user = $this->_getUser($data['kr_meldung']['user_id']);

        $section = array(
            'MTYP'      => '',
            'UTR'       => Cgkr_6_2_ModelHelper::UTR($data),
            'ANGKR'     => '',
            '_ANGKRExt' => $this->_getKrMeldungsText($data['kr_meldungen']),
            'EINRICHT'  => Cgkr_6_2_ModelHelper::EINRICHT($org),
            'ABT'       => Cgkr_6_2_ModelHelper::ABT($user),
            'PLZ_E'     => Cgkr_6_2_ModelHelper::PLZ_E($org),
            'ORT_E'     => Cgkr_6_2_ModelHelper::ORT_E($org),
            'STR_E'     => Cgkr_6_2_ModelHelper::STR_E($org),
            'NAMN'      => Cgkr_6_2_ModelHelper::NAMN($user),
            'TELN'      => Cgkr_6_2_ModelHelper::TELN($user),
            'DMN'       => Cgkr_6_2_ModelHelper::convertDate(Cgkr_6_2_ModelHelper::DMN($data), 'dmy'),
            '_DMNLong'  => Cgkr_6_2_ModelHelper::DMN($data),
        );

        return $section;
    }


    /**
     *
     *
     * @access
     * @param $mapping
     * @return $this
     */
    protected function _addMapping($mapping)
    {
        $map = is_array($mapping) === true ? $mapping : getLookup($this->m_db, $mapping);

        $this->_mappings[$mapping] = $map;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $mapping
     * @return array
     */
    protected function _getMapping($mapping)
    {
        return (array_key_exists($mapping, $this->_mappings) === true ? $this->_mappings[$mapping] : array());
    }


    /**
     *
     *
     * @access
     * @param $orgId
     * @return $this
     */
    protected function _setRequiredOrg($orgId)
    {
        if (strlen($orgId) > 0) {
            $query = "
                SELECT
                    name,
                    plz,
                    ort,
                    CONCAT_WS(' ', strasse, hausnr) AS 'strasse',
                    bundesland
                FROM org
                WHERE
                    org_id = '{$orgId}'
            ";

            $result = sql_query_array($this->m_db, $query);

            if (count($result) === 1) {
                $this->_org = reset($result);
            }
        }

        return $this;
    }


    /**
     * returns export org
     *
     * @access protected
     * @return null|array
     */
    protected function _getOrg()
    {
        return $this->_org;
    }


    /**
     * find all required ekr user
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _setRequiredEkrUserData(CExportWrapper $wrapper)
    {
        $diseases = $wrapper->getFilteredDiseases();

        if ($diseases !== null) {
            $userIds = dlookup($this->m_db, 'ekr', 'GROUP_CONCAT(DISTINCT user_id)', "erkrankung_id IN ({$diseases})");

            if (strlen($userIds) > 0) {
                $fachabteilung = $this->_addMapping('fachabteilung')->_getMapping('fachabteilung');

                $query = "
                    SELECT
                        u.user_id,
                        CONCAT_WS(' ', anrede.bez, u.titel, u.vorname, u.nachname) as 'fullname',
                        u.telefon,
                        u.fachabteilung

                    FROM
                        user u
                        LEFT JOIN l_basic anrede ON anrede.code=u.anrede
                                                    AND anrede.klasse='anrede'

                    WHERE
                        user_id IN ({$userIds})
                ";

                foreach (sql_query_array($this->m_db, $query) as $user) {
                    $user['fachabteilung_description'] = '';
                    if (isset($fachabteilung[$user['fachabteilung']])) {
                        $user['fachabteilung_description'] = $fachabteilung[$user['fachabteilung']];
                    }
                    $this->_user[$user['user_id']] = $user;
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _setDiagnoseLocalistions()
    {
        $query = "SELECT * FROM l_exp_diagnose_to_lokalisation";

        $mapping = array();

        foreach (sql_query_array($this->m_db, $query) as $record) {
            $mapping[$record['diagnose_code']] = array(
                'code' => $record['lokalisation_code'],
                'text' => $record['lokalisation_text']
            );
        }

        $this->_mappings['l_exp_diagnose'] = $mapping;

        return $this;
    }



    /**
     *
     *
     * @access
     * @param $id
     * @return null
     */
    protected function _getUser($id)
    {
        return (array_key_exists($id, $this->_user) === true ? $this->_user[$id] : null);
    }


    /**
     * build diagnose filter for given field
     *
     * @access  private
     * @param   string $field
     * @return  void
     */
    private function _buildDiagnoseFilter($field = 'diagnose')
    {
        $filter = array();

        foreach ($this->_diagnoseFilter as $diagnose) {
            $filter[] = "{$field} LIKE '{$diagnose}%'";
        }

        return implode(' OR ', $filter);
    }

}

?>
