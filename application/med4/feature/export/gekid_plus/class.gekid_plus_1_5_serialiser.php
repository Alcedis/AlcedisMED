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

require_once('feature/export/base/class.exportexception.php');
require_once('feature/export/base/class.exportxmlserialiser.php');
require_once('feature/export/base/helper.common.php');
require_once('feature/export/history/class.historymanager.php');
require_once('feature/export/history/class.history.php');
require_once('core/class/report/alcReportPdf.php');

class Cgekid_plus_1_5_Serialiser extends CExportXmlSerialiser
{

    protected $_model = null;

    public function __construct()
    {
        $this->m_xml_template_file = "gekid_plus_1_5.tpl";
        $this->m_xml_schema_file = "feature/export/gekid_plus/gekid_plus_2_1.xsd";
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportXmlSerialiser
    //

    public function Validate($parameters)
    {
        $data = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            $sections = $case->GetSections();
            $dateErrors = $this->_checkDates($case);
            // Check
            foreach ($sections as $sectionKey => $section) {
                $data = $this->_createValidDatenBlock($section);
                $data = $this->ReplaceAllXmlEntities($data);
                $this->m_internal_smarty->assign('data', $data);
                $xml = $this->m_internal_smarty->fetch($this->m_xml_template_file);
                $errors = $this->ParseXmlForErrors($xml);
                if (false !== $errors) {
                    // Es sind "Undefined Index: xxx" Fehler aufgetreten
                    $case->SetSectionErrorsByUid($section->GetSectionUid(), $errors);
                }
                else {
                    $errors = $this->XmlSchemaValidate($xml, $this->m_xml_schema_file);
                    if (isset($dateErrors[$section->GetBlock()]) &&
                        count($dateErrors[$section->GetBlock()]) > 0) {
                        $errors = array_merge($dateErrors[$section->GetBlock()], $errors);
                    }
                    if (count($errors) > 0) {
                        $case->SetSectionErrorsByUid($section->GetSectionUid(), $errors);
                    }
                }
            }
            $cases[$caseKey] = $case;
        }
        $this->m_export_record->SetCases($cases);
    }

    protected function _checkDates($case)
    {
        $errors = array(
            'melder' => array(),
            'person' => array(),
            'tumor' => array()
        );
        $melderData = $this->_getSectionDataFromCase($case, 'melder');
        $personData = $this->_getSectionDataFromCase($case, 'person');
        $tumorSection = $this->_getSectionDataFromCase($case, 'tumor');
        $geburtsDatumTime = 0;
        $geburtsDatum = '';
        if (strlen($personData['Geburtsdatum']) > 0) {
            $geburtsDatumTime = strtotime($personData['Geburtsdatum']);
            $geburtsDatum = $this->getFormatedDate($personData['Geburtsdatum']);
        }
        $diagnoseDatumTime = 0;
        $diagnoseDatum =
            $tumorSection['Diagnosetag'] . "." . $tumorSection['Diagnosemonat'] . "." . $tumorSection['Diagnosejahr'];
        if (strlen($diagnoseDatum) > 0) {
            $diagnoseDatumTime = strtotime($diagnoseDatum);
            $diagnoseDatum = $this->getFormatedDate($diagnoseDatum);
        }
        $todesDatumTime = 0;
        $todesDatum = '';
        if (strlen($personData['Todesdatum']) > 0) {
            $todesDatumTime = strtotime($personData['Todesdatum']);
            $todesDatum = $this->getFormatedDate($personData['Todesdatum']);
        }
        $medlungDatumTime = 0;
        $medlungDatum = '';
        if (strlen($melderData['Meldedatum']) > 0) {
            $medlungDatumTime = strtotime($melderData['Meldedatum']);
            $medlungDatum = $this->getFormatedDate($melderData['Meldedatum']);
        }
        if ($geburtsDatumTime == 0) {
            $errors['person'][] = "Error: Geburtsdatum darf nicht leer sein.";
        }
        else {
            if ($geburtsDatumTime > $diagnoseDatumTime) {
                $errors['person'][] =
                    "Error: Geburtsdatum {$geburtsDatum} muss <= Diagnosedatum {$diagnoseDatum} sein.";
            }
            if (0 != $todesDatumTime) {
                if ($geburtsDatumTime > $todesDatumTime) {
                    $errors['person'][] =
                        "Error: Geburtsdatum {$geburtsDatum} muss <= Todesdatum {$todesDatum} sein.";
                }
            }
            if ($geburtsDatumTime > $medlungDatumTime) {
                $errors['person'][] =
                    "Error: Geburtsdatum {$geburtsDatum} muss <= Meldedatum {$medlungDatum} sein.";
            }
            if ($geburtsDatumTime < strtotime('01.01.1900')) {
                $errors['person'][] =
                    "Error: Geburtsdatum {$geburtsDatum} muss >= 01.01.1900 sein.";
            }
        }
        if ($diagnoseDatumTime == 0) {
            $errors['tumor'][] = "Error: Diagnosedatum darf nicht leer sein.";
        }
        else {
            if ($todesDatumTime != 0) {
                if ($diagnoseDatumTime > $todesDatumTime) {
                    $errors['tumor'][] =
                        "Error: Diagnosedatum {$diagnoseDatum} muss <= Todesdatum {$todesDatum} sein.";
                }
            }
            if ($diagnoseDatumTime > $medlungDatumTime) {
                $errors['tumor'][] =
                    "Error: Diagnosedatum {$diagnoseDatum} muss <= Meldedatum {$medlungDatum} sein.";
            }
            if ($diagnoseDatumTime < strtotime('01.01.1990')) {
                $errors['tumor'][] =
                    "Error: Diagnosedatum {$diagnoseDatum} muss >= 01.01.1990 sein.";
            }
        }
        if ($todesDatumTime != 0) {
            if ($todesDatumTime > $medlungDatumTime) {
                $errors['person'][] =
                    "Error: Todesdatum {$todesDatum} muss <= Meldedatum {$medlungDatum} sein.";
            }
            if ($todesDatumTime < strtotime('01.01.1985')) {
                $errors['person'][] =
                    "Error: Todesdatum {$todesDatum} muss >= 01.01.1985 sein.";
            }
        }
        if ($medlungDatumTime == 0) {
            $errors['melder'][] = "Error: Meldedatum darf nicht leer sein.";
        }
        if (0 == strlen($tumorSection['ICD'])) {
            $errors['tumor'][] = "Error: ICD (Diagnose) darf nicht leer sein.";
        }
        if ((strlen($tumorSection['Oestrogen_pos_TZ']) > 0) &&
            (intval($tumorSection['Oestrogen_pos_TZ']) > 100)) {
            $errors['tumor'][] = "Error: Oestrogen_pos_TZ darf nicht > 100 sein.";
        }
        if ((strlen($tumorSection['Progesteron_pos_TZ']) > 0) &&
            (intval($tumorSection['Progesteron_pos_TZ']) > 100)) {
            $errors['tumor'][] = "Error: Progesteron_pos_TZ darf nicht > 100 sein.";
        }
        return $errors;
    }


    /**
     *
     *
     * @access
     * @param $case
     * @param $sectionName
     * @return bool|array
     */
    protected function _getSectionDataFromCase($case, $sectionName)
    {
        $sections = $case->GetSections();
        foreach ($sections as $sectionKey => $section) {
            if ($sectionName == $section->GetBlock()) {
                return $section->GetDaten();
            }
        }
        return false;
    }

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

    public function Encrypt($parameters)
    {
    }

    public function Write($parameters)
    {
        $xmlFile = parent::Write($parameters);
        $patientCounter = 0;
        $data = null;
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $casesKey => $case) {
            if ($case->IsValid()) {
                if (null == $data) {
                    // Header erstellen
                    $data['melder'] = $this->_getSectionData($case, 'melder');
                }
                $sections = $case->GetSections();
                foreach ($sections as $keySection => $section) {
                    if ('melder' != $section->GetBlock()) {
                        $tmp = $section->GetDaten();
                        switch ($section->GetBlock()) {
                            case 'person' :
                                $data['patienten'][$patientCounter]['person'] = $tmp;
                                break;
                            case 'tumor' :
                                $data['patienten'][$patientCounter]['tumor'] = $tmp;
                                break;
                            case 'pathologe' :
                                $data['patienten'][$patientCounter]['pathologe'] = $tmp;
                                break;
                            default :
                                break;
                        }
                        $section->SetDaten($tmp);
                    }
                    $sections[$keySection] = $section;
                }
                $cases[$casesKey] = $case;
                $patientCounter++;
            }
        }
        $this->m_export_record->SetCases($cases);
        $this->m_export_record->Write($this->m_db);
        if (null != $data) {
            $data = $this->ReplaceAllXmlEntities($data);
            $this->m_internal_smarty->assign('data', $data);
            $xml = $this->m_internal_smarty->fetch($this->m_xml_template_file);
            file_put_contents($xmlFile, utf8_encode($xml));
            $this->m_smarty->assign('zip_url', $xmlFile);
            $this->m_smarty->assign('zip_filename', 'Export Datei');
            // History erstellen
            $historyManager = CHistoryManager::getInstance();
            $historyManager->initialise($this->m_db, $this->m_smarty);
            $history = $historyManager->createHistory();
            $history->setExportLogId($this->m_export_record->GetDbId());
            $history->setExportName($this->m_export_record->GetExportName());
            $history->setOrgId($parameters['org_id']);
            $history->setUserId($parameters['user_id']);
            $history->setDate(date('Ymd', time()));
            $history->addFilter('von', $parameters['datum_von']);
            $history->addFilter('bis', $parameters['datum_bis']);
            $history->setFiles(
                array(
                     $xmlFile
                )
            );
            $historyManager->insertHistory($history);
        }
        return $xmlFile;
    }


    public function GetFilename()
    {
        return 'gekid_export_' . date('YmdHis') . '.xml';
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

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
     *
     *
     * @access
     * @return array
     */
    protected function _createValidMelderBlock()
    {
        $melder = array(
            'Meldende_Stelle'       => 'St. Josephs Krankenhaus',
            'KH_Abt_Station_Praxis' => 'Chirugie',
            'Arztname'              => 'Dr. Karl Müller',
            'Anschrift'             => 'Holzweg 32',
            'Postleitzahl'          => '60456',
            'Ort'                   => 'Frankfurt am Main',
            'Meldedatum'            => date('d.m.Y')
        );
        return $melder;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _createValidPersonBlock()
    {
        $person = array(
            'Titel'            => 'Prof. Dr.',
            'Vornamen'         => 'Tanja',
            'Nachname'         => 'Schmidt',
            'Geburtsname'      => 'Rohloff',
            'Geschlecht'       => 'W',
            'Geburtsdatum'     => '17.06.1952',
            'Strasse'          => 'Rosenstr.',
            'Hausnummer'       => '128',
            'Postfix'          => 'a',
            'Postleitzahl'     => '35390',
            'Ort'              => 'Giessen',
            'Todesdatum'       => '19.10.2013',
            'Todesursache'     => 'C18.2: Bösartige Neubildungen: Colon transversum',
            'Meldebegruendung' => 'E'
        );
        return $person;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _createValidTumorBlock()
    {
        $tumor = array(
            'Referenznummer'             => 'PN1234567890',
            'Diagnosetag'                => '19',
            'Diagnosemonat'              => '04',
            'Diagnosejahr'               => '2013',
            'ICD'                        => 'C18.2',
            'Diagnose_Freitext'          => 'Bösartige Neubildungen: Colon transversum',
            'Morphologie_Code'           => '8090/1',
            'Morphologie_Freitext'       => 'Basalzelltumor',
            'Dignitaet'                  => '1',
            'ICD_Auflage'                => '10',
            'Topographie_Code'           => '',
            'ICDO_Auflage'               => '3',
            'Grading'                    => 'M',
            'Zelltyp'                    => 'B',
            'Diagnosesicherung'          => '6',
            'Diagnoseanlass'             => 'A',
            'Seitenlokalisation'         => 'T',
            'Grobstadium'                => 'F',
            'y'                          => 'y',
            'r'                          => '',
            'a'                          => 'a',
            'Praefix_TNM'                => 'p',
            'T'                          => 'pT2',
            'Multi'                      => '2',
            'N'                          => 'pN1',
            'M'                          => 'pM3',
            'R'                          => 'RX',
            'UICC_Stadium'               => 'IIIC',
            'TNM_Auflage'                => '7',
            'Tumorgroesse'               => '8',
            'Breslow'                    => '9',
            'Gleason_Score'              => '7b',
            'Andere_Klassifikation'      => 'IV',
            'Oestrogen_Status'           => 'positiv',
            'Oestrogen_pos_TZ'           => '74',
            'Progesteron_Status'         => 'negativ',
            'Progesteron_pos_TZ'         => '70',
            'HER2_Status'                => '2+',
            'Operation'                  => 'J',
            'Strahlentherapie'           => 'J',
            'Chemotherapie'              => 'J',
            'Hormontherapie'             => 'N',
            'Immuntherapie'              => 'N',
            'Knochenmarktransplantation' => 'J',
            'Sonstige_Therapie'          => 'N',
            'Bemerkungen'                => 'Bemerkung aus Ekr-Formular!!!'
        );
        return $tumor;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _createValidPathologeBlock()
    {
        $pathologe = array(
            'KH_Abt_Station_Praxis' => 'Pathologie Essen',
            'Name_Pathologe'        => 'Dr. Hans Zimmermann',
            'Anschrift'             => 'Gassenweg 10',
            'Postleitzahl'          => '50256',
            'Ort'                   => 'München'
        );
        return $pathologe;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return array
     */
    protected function _createValidDatenBlock($section)
    {
        $data = array();
        if ('melder' == $section->GetBlock()) {
            $data['melder'] = $section->GetDaten();
            $data['patienten'][] = $this->_createValidPatientBlock();
        }
        else {
            $data['melder'] = $this->_createValidMelderBlock();
            $data['patienten'][] = $this->_createValidPatientBlock($section);
        }
        return $data;
    }


    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function _createValidPatientBlock($section = null)
    {
        $case = array();
        if (!is_null($section) && ('person' == $section->GetBlock())) {
            $case['person'] = $section->GetDaten();
        }
        else {
            $case['person'] = $this->_createValidPersonBlock();
        }
        if (!is_null($section) && ('tumor' == $section->GetBlock())) {
            $case['tumor'] = $section->GetDaten();
        }
        else {
            $case['tumor'] = $this->_createValidTumorBlock();
        }
        if (!is_null($section) && ('pathologe' == $section->GetBlock())) {
            $case['pathologe'] = $section->GetDaten();
        }
        else {
            $case['pathologe'] = $this->_createValidPathologeBlock();
        }
        return $case;
    }


    /**
     *
     *
     * @access
     * @param $str
     * @return mixed
     */
    protected function replaceSpecialChars($str)
    {
        return str_replace(array("\t", "\n", "\r"), ' ', $str);
    }

}

?>
