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
require_once('class.gkraddresses.php');
require_once('reports/pdf/oz/gkr_begleitzettel.php');
require_once('core/class/report/alcReportPdf.php');

class Cgkr_6_2_Serialiser extends CExportXmlSerialiser
{

    protected $_lengthOfFields =
        array(
            'GEDA'      =>   8,
            'NAMG'      =>  30,
            'VNAG'      =>  30,
            'GNAG'      =>  30,
            'FNAG'      =>  30,
            'TITEL'     =>   8,
            'SEXG'      =>   1,
            'MEHRL'     =>   1,
            'SAN'       =>   3,
            'STR'       =>  55,
            'PLZN'      =>   5,
            'ORTG'      =>  30,
            'BF1N'      =>  30,
            'JALAEN'    =>   2,
            'BF2'       =>  30,
            'JALET'     =>   2,
            'KINL'      =>   1,
            'KINT'      =>   1,
            'KINF'      =>   1,
            'BKZG'      =>   1,
            'VORT'      => 120,
            'ANLDIAG'   =>   1,
            'DIDA'      =>   6,
            'DTEXT'     => 254,
            'ICDZ'      =>   4,
            'LOKT'      => 254,
            'ICT'       =>   4,
            'LATN'      =>   1,
            'HFK'       => 254,
            'ICM'       =>   5,
            'ICM_VER'   =>   2,
            'GRADING'   =>   1,
            'TAUSB'     =>   1,
            'STA_VER1'  =>   1,
            'STADIUM1'  =>   5,
            'STA_VER2'  =>   1,
            'STADIUM2'  =>   5,
            'TNMprae'   =>  60,
            'TNMpost'   =>  60,
            'HDSICH'    =>   1,
            'CHAT'      =>   1,
            'OPE'       =>   1,
            'DMOPE'     =>   6,
            'STT'       =>   1,
            'DMSTT'     =>   6,
            'CHE'       =>   1,
            'DMCHE'     =>   6,
            'HOR'       =>   1,
            'DMHOR'     =>   6,
            'IMM'       =>   1,
            'DMIMM'     =>   6,
            'AND'       =>   1,
            'BEF'       =>   1,
            'STDA'      =>   6,
            'SEK'       =>   1,
            'T1A'       =>   4,
            'X1A'       =>  50,
            'Z1A'       =>  10,
            'T1B'       =>   4,
            'X1B'       =>  50,
            'Z1B'       =>  10,
            'T1C'       =>   4,
            'X1C'       =>  50,
            'Z1C'       =>  10,
            'T2A'       =>   4,
            'X2A'       =>  50,
            'Z2A'       =>  10,
            'T2B'       =>   4,
            'X2B'       =>  50,
            'Z2B'       =>  10,
            'QTU'       =>   1,
            'TURS'      =>   1,
            'MTYP'      =>   1,
            'UTR'       =>   1,
            'ANGKR'     => 254,
            'EINRICHT'  =>  90,
            'ABT'       =>  90,
            'PLZ_E'     =>   5,
            'ORT_E'     =>  30,
            'STR_E'     =>  55,
            'NAMN'      =>  50,
            'TELN'      =>  20,
            'DMN'       =>   6,
            'REF_NR'    =>   7
        );

    protected $_model = null;

    public function __construct()
    {
        /*
            TODO: Wird für die XML-Version dann wieder gebraucht!

        */
        //$this->m_xml_template_file = "gkr_6_2.tpl";
        //$this->m_xml_schema_file = "feature/export/gkr/krbw_2_1.xsd";
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportXmlSerialiser
    //

    public function Validate($parameters)
    {
        $this->_validateFixedLength($parameters);
    }

    protected function _validateFixedLength($parameters)
    {
        $data = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            $sections = $case->GetSections();
            $dateErrors = $this->_checkDates($case);
            foreach ($sections as $sectionKey => $section) {
                $data = $this->_createValidCaseBlock($section);
                $errors = array();
                if ('patient' == $section->GetBlock()) {
                    if (isset($dateErrors['patient'])) {
                        $errors = $dateErrors['patient'];
                    }
                    // Nachname checks
                    if (strlen($data['NAMG']) == 0) {
                        $errors[] =
                            "Error [NAMG]: Der Nachname muss angegeben werden.";
                    }
                    else if (strlen($data['NAMG']) < 2) {
                        $errors[] =
                            "Error [NAMG]: Es muss ein vollständiger Nachname angegeben werden.";
                    }
                    // Vorname checks
                    if (strlen($data['VNAG']) == 0) {
                        $errors[] =
                            "Error [VNAG]: Der Vorname muss angegeben werden.";
                    }
                    else if (strlen($data['VNAG']) < 2) {
                        $errors[] =
                            "Error [VNAG]: Es muss ein vollständiger Vorname angegeben werden.";
                    }
                    // Geschlecht checks
                    if (strlen($data['SEXG']) != 1) {
                        $errors[] =
                            "Error [SEXG]: Das Geschlecht ist nicht angegeben.";
                    }
                    // Adress checks
                    if (strlen($data['_strasse']) == 0) {
                        $errors[] =
                            "Error [STR]: Die Straße des Patienten ist nicht dokumentiert.";
                    }
                    if (strlen($data['_hausnr']) == 0) {
                        $errors[] =
                            "[warning] Warning [STR]: Die Hausnummer des Patienten ist nicht dokumentiert.";
                    }
                    if (strlen($data['PLZN']) == 0) {
                        $errors[] =
                            "Error [PLZN]: Die PLZ ist nicht angegeben.";
                    }
                    else if (strlen($data['PLZN']) != 5) {
                        $errors[] =
                            "Error [PLZN]: Die PLZ ist nicht 5 stellig.";
                    }
                    else if (1 != preg_match('/[0-9]{5}$/', $data['PLZN'])) {
                        $errors[] =
                            "Error [PLZN]: Die PLZ darf nur aus Ziffern bestehen.";
                    }
                    if (strlen($data['ORTG']) == 0) {
                        $errors[] =
                            "Error [ORTG]: Der Ort ist nicht angegeben.";
                    }
                    if (strlen($data['REF_NR']) == 0) {
                        $errors[] =
                            "Error [REF_NR]: Die Referenznummer darf nicht leer sein.";
                    }
                    if ((strlen($data['PLZN']) > 0) && (strlen($data['ORTG']) > 0)) {
                        if (1 == CGkrAddresses::getInstance()->checkAddress($data['PLZN'], $data['ORTG'])) {
                            $errors[] = "[warning] Warnung: Überprüfen Sie PLZ und Ort des Patienten.";
                        }
                    }
                }
                if ('primary' == $section->GetBlock()) {
                    if (isset($dateErrors['primary'])) {
                        $errors = $dateErrors['primary'];
                    }
                    if (strlen($data['DTEXT']) == 0) {
                        $errors[] = "Error [DTEXT]: Freitext zur Tumordiagnose darf nicht leer sein.";
                    }
                    if (strlen($data['ICDZ']) == 0) {
                        $errors[] = "Error [ICDZ]: Tumordiagnose darf nicht leer sein.";
                    }
                    if (strlen($data['LOKT']) == 0) {
                        $errors[] = "Error [LOKT]: Freitext zur Lokalisation darf nicht leer sein.";
                    }
                    if (strlen($data['ICT']) == 0) {
                        $errors[] = "Error [ICT]: Lokalisation darf nicht leer sein.";
                    }

                    if (strlen($data['ICM']) == 0 && in_array($data['HDSICH'], array('a','h','m'))) {
                        $errors[] = "[warning] Warning [ICM]: Morphologie muss gefüllt sein, wenn bei Art der
                            Diagnosesicherung 'histologisch aus dem Primärtumor', 'histologisch aus der Metastase' oder
                            'Autopsie mit Histologie' dokumentiert wurde";
                    }
                    if (strlen($data['HDSICH']) == 0) {
                        $errors[] = "[warning] Warning [HDSICH]: Diagnosesicherung darf nicht leer sein.";
                    }
                }
                if ('conclusion' == $section->GetBlock()) {
                    if (isset($dateErrors['conclusion'])) {
                        $errors = $dateErrors['conclusion'];
                    }
                    if (('v' == $data['BEF']) && (strlen($data['STDA']) == 0)) {
                        $errors[] =
                            "[warning] Warning [STDA]: Wenn Patient verstorben ist, muss das Todesdatum angegeben " .
                            "werden. Falls das genaue Datum unbekannt ist, dokumentieren Sie bitte die bestmögliche " .
                            "Schätzung.";
                    }
                }
                if ('general' == $section->GetBlock()) {
                    if (isset($dateErrors['general'])) {
                        $errors = $dateErrors['general'];
                    }
                    if (strlen($data['MTYP']) == 0) {
                        $errors[] = "Error [MTYP]: Meldetyp darf nicht leer sein.";
                    }
                    if ((strlen($data['UTR']) == 0) ||
                        ('zW' == $data['UTR'])) {
                        $errors[] = "[warning] Warning [UTR]: Die Meldebegründung muss dokumentiert sein.";
                    }
                    if (strlen($data['EINRICHT']) == 0) {
                        $errors[] = "[warning] Warning [EINRICHT]: Die meldende Einrichtung muss dokumentiert sein.";
                    }
                    if (strlen($data['NAMN']) == 0) {
                        $errors[] = "[warning] Warning [NAMN]: Der meldende Arzt muss dokumentiert sein.";
                    }
                }
                if (count($errors) > 0) {
                    /*
                    print_arr($section->GetBlock());
                    print_arr($errors);
                    print_arr($data);
                    */
                    $case->SetSectionErrorsByUid($section->GetSectionUid(), $errors);
                }
            }
            $cases[$caseKey] = $case;
        }
        $this->m_export_record->SetCases($cases);
    }

    protected function _checkDates($case)
    {
        $errors = array(
            'patient' => array(),
            'primary' => array(),
            'conclusion' => array(),
            'general' => array()
        );

        $patientData = $this->_getSectionDataFromCase($case, 'patient');
        $primaryData = $this->_getSectionDataFromCase($case, 'primary');
        $conclusionSection = $this->_getSectionDataFromCase($case, 'conclusion');
        $generalSection = $this->_getSectionDataFromCase($case, 'general');

        $geburtsDatumTime = 0;
        $geburtsDatum = '';
        if (strlen($patientData['_GEDALong']) > 0) {
            $geburtsDatumTime = strtotime($patientData['_GEDALong']);
            $geburtsDatum = $this->getFormatedDate($patientData['_GEDALong']);
        }

        $diagnoseDatumTime = 0;
        $diagnoseDatum = '';
        if (strlen($primaryData['_DIDALong']) > 0) {
            $diagnoseDatumTime = strtotime($primaryData['_DIDALong']);
            $diagnoseDatum = $this->getFormatedDate($primaryData['_DIDALong']);
        }

        $todesDatumTime = 0;
        $todesDatum = '';
        if (strlen($conclusionSection['_STDALong']) > 0) {
            $todesDatumTime = strtotime($conclusionSection['_STDALong']);
            $todesDatum = $this->getFormatedDate($conclusionSection['_STDALong']);
        }

        $medlungDatumTime = 0;
        $medlungDatum = '';
        if (strlen($generalSection['_DMNLong']) > 0) {
            $medlungDatumTime = strtotime($generalSection['_DMNLong']);
        }

        $exportDatumTime = strtotime(date('Y-m-d'));
        $exportDatum = date('d.m.Y');

        if ($geburtsDatumTime == 0) {
            $errors['patient'][] = "Error: Geburtsdatum darf nicht leer sein.";
        }
        else {
            if ($geburtsDatumTime >= $diagnoseDatumTime) {
                $errors['patient'][] =
                    "Error [GEDA]: Geburtsdatum {$geburtsDatum} muss < Diagnosedatum {$diagnoseDatum} sein.";
            }
            if ($todesDatumTime != 0) {
                if ($geburtsDatumTime >= $todesDatumTime) {
                    $errors['patient'][] =
                        "Error [GEDA]: Geburtsdatum {$geburtsDatum} muss < Todesdatum {$todesDatum} sein.";
                }
            }
            if ($geburtsDatumTime >= $exportDatumTime) {
                $errors['patient'][] =
                    "Error [GEDA]: Geburtsdatum {$geburtsDatum} muss < Exportdatum {$exportDatum} sein.";
            }
        }

        if ($diagnoseDatumTime == 0) {
            $errors['primary'][] = "Error [DIDA]: Diagnosedatum darf nicht leer sein.";
        }
        else {
            if ($todesDatumTime != 0) {
                if ($diagnoseDatumTime > $todesDatumTime) {
                    $errors['primary'][] =
                        "Error [DIDA]: Diagnosedatum {$diagnoseDatum} muss <= Todesdatum {$todesDatum} sein.";
                }
            }
            if ($diagnoseDatumTime > $exportDatumTime) {
                $errors['primary'][] =
                    "Error [DIDA]: Diagnosedatum {$diagnoseDatum} muss <= Exportdatum {$exportDatum} sein.";
            }
        }

        if ($todesDatumTime != 0) {
            if ($todesDatumTime > $exportDatumTime) {
                $errors['conclusion'][] =
                    "Error [STDA]: Todesdatum {$todesDatum} muss <= Exportdatum {$exportDatum} sein.";
            }
        }

        if ($medlungDatumTime == 0) {
            $errors['general'][] = "Error [DMN]: Medlungdatum darf nicht leer sein.";
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
        return $this->WriteFixedLengthFormat($parameters);
    }

    protected function WriteFixedLengthFormat($parameters)
    {
        $xml_file = parent::Write($parameters);
        $xml_file .= $parameters['fileSuffix'];
        $export_path = $this->GetExportPath($parameters['main_dir'], $parameters['login_name']);
        $zip_dir = $export_path . $parameters['zip_dir'];
        HFileSystem::CreatePath($zip_dir);
        $data = array();
        $cases = $this->m_export_record->GetCases();
        foreach($cases as $caseKey => $case) {
            if ((1 == $case->HasDataChanged()) && $case->IsValid()) {
                $sections = $case->GetSections();
                $mtyp = $this->_getMeldetyp($sections);
                $record = array();
                foreach($sections as $keySection => $section) {
                    $sectionData = $section->GetDaten();
                    if (('general' == $section->GetBlock()) &&
                        (null != $mtyp)) {
                        $sectionData['MTYP'] = $mtyp;
                        $section->SetDaten($sectionData);
                    }
                    $record = array_merge($record, $sectionData);
                    $sections[$keySection] = $section;
                }
                $cases[$caseKey] = $case;
                $data[] = $record;
            }
        }
        $this->m_export_record->SetCases($cases);
        $this->m_export_record->Write($this->m_db);
        $_SESSION['sess_gkr_begleitzettel'] = array(
            'cE' => 0,
            'ce' => 0,
            'cf' => 0,
            'ck' => 0,
            'ct' => 0,
            'cB' => 0,
            'cb' => 0
        );
        if (null != $data) {
            umask(0002);
            $fp = fopen($xml_file, 'w');
            if ( !$fp ) {
                throw new EExportException("ERROR: Kann Export-Datei [{$xml_file}] nicht zum schreiben öffnen.");
            }
            $newline = "\r\n";
            foreach ($data as $record) {
                // Datenzeile mit fester Länge generieren
                $curLine = '';
                foreach ($this->_lengthOfFields as $name => $length ) {
                    if (isset($record[$name])) {
                        $value = $this->replaceSpecialChars($record[$name]);
                        switch ($name) {
                            case 'REF_NR' :
                                if (strlen($value) > 7) {
                                    $value = substr($value, -7);
                                }
                                break;
                            case 'ANGKR' :
                            case 'VORT' :
                            case 'DTEXT' :
                            case 'LOKT' :
                            case 'HFK' :
                            case 'X1A' :
                            case 'X1B' :
                            case 'X1C' :
                            case 'X2A' :
                            case 'X2B' :
                                $value = HCommon::TrimString($value, $length);
                                break;
                            default :
                                $value = HCommon::TrimString($value, $length, false);
                                break;
                        }
                    }
                    else {
                        $value = "";
                    }
                    $curLine .= str_pad($value, $length);
                }
                fwrite($fp, $curLine . $newline);
                switch ($record['MTYP']) {
                    case 'E' :
                        $_SESSION['sess_gkr_begleitzettel']['cE'] += 1;
                        break;
                    case 'e' :
                        $_SESSION['sess_gkr_begleitzettel']['ce'] += 1;
                        break;
                    case 'f' :
                        $_SESSION['sess_gkr_begleitzettel']['cf'] += 1;
                        break;
                    case 'k' :
                        $_SESSION['sess_gkr_begleitzettel']['ck'] += 1;
                        break;
                    case 't' :
                        $_SESSION['sess_gkr_begleitzettel']['ct'] += 1;
                        break;
                    case 'B' :
                        $_SESSION['sess_gkr_begleitzettel']['cB'] += 1;
                        break;
                    case 'b' :
                        $_SESSION['sess_gkr_begleitzettel']['cb'] += 1;
                        break;
                }
            }
            fclose($fp);
            $gkrName = $parameters['kennung'] . date('my');
            $dateTime = date('Ymdhms');
            $filename = $gkrName . $dateTime . ".zip";
            $zipFile = $zip_dir . $filename;
            $_SESSION['sess_gkr_begleitzettel']['export_datum'] = date('d.m.Y h:m:s', strtotime($dateTime));
            $_SESSION['sess_gkr_begleitzettel']['filename'] = $filename;
            $_SESSION['sess_gkr_begleitzettel']['username'] = $_SESSION['sess_user_name'];
            $zip = new PclZip($zipFile);
            $zip_create = $zip->create(array($xml_file), PCLZIP_OPT_REMOVE_ALL_PATH);
            $this->m_smarty->assign('zip_url', $zipFile);
            $this->m_smarty->assign('zip_filename', 'Export Datei');
        }

        // Begleitzettel nur für Histry erstellen
        $begleitzettelFilename = $zip_dir . 'begleitzettel.pdf';
        $params = array(
            'gkrBegleitzettel' => $_SESSION['sess_gkr_begleitzettel']
        );
        $renderer = alcReportPdf::create($this->m_db, $this->m_smarty, $parameters['user_id'], $params);
        $renderer->addPage();
        $begleitzettel = new reportContentGkr_begleitzettel(
            $renderer, $this->m_db, $this->m_smarty, 'oz', 'pdf', $params);
        $begleitzettel->generate($renderer);
        $renderer->output($begleitzettelFilename, 'F');

        // History erstellen
        $historyManager = CHistoryManager::getInstance();
        $historyManager->initialise($this->m_db, $this->m_smarty);
        $history = $historyManager->createHistory();
        $history->setExportLogId($this->m_export_record->GetDbId());
        $history->setExportName($this->m_export_record->GetExportName());
        $history->setOrgId($parameters['org_id']);
        $history->setUserId($parameters['user_id']);
        $history->setDate(date('Ymd', time()));
        $history->setFiles(
            array(
                 $zipFile,
                 $begleitzettelFilename
            )
        );
        $historyManager->insertHistory($history);
        return $xml_file;
    }

    protected function _getMeldetyp($sections)
    {
        $mtyp = null;
        foreach($sections as $section) {
            if ((in_array($section->GetMeldungskennzeichen(), array('E', 'e', 'B', 'b'))) && (null == $mtyp)) {
                $mtyp = $section->GetMeldungskennzeichen();
            }
            if (('k' == $section->GetMeldungskennzeichen()) &&
                ('f' != $mtyp) &&
                ('t' != $mtyp)) {
                $mtyp = 'k';
            }
            if (('f' == $section->GetMeldungskennzeichen()) &&
                ('t' != $mtyp)) {
                $mtyp = 'f';
            }
            if ('t' == $section->GetMeldungskennzeichen()) {
                $mtyp = 't';
            }
        }
        return $mtyp;
    }


    public function GetFilename()
    {
        return "gkr";
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

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

    protected function _createValidPatientBlock()
    {
        $patient = array();
        $patient['GEDA'] = '03121934';
        $patient['NAMG'] = 'Müller';
        $patient['VNAG'] = 'Anne';
        $patient['GNAG'] = 'Zimmermann';
        $patient['FNAG'] = '';
        $patient['TITEL'] = 'Prof. Dr.';
        $patient['SEXG'] = 'w';
        $patient['SAN'] = 'D';
        $patient['STR'] = 'Holzweg 23';
        $patient['PLZN'] = '35390';
        $patient['ORTG'] = 'Gießen';
        $patient['REF_NR'] = '1234567';
        return $patient;
    }

    protected function _createValidAnamnesisBlock()
    {
        $anamnesis = array();
        $anamnesis['MEHRL'] = 'x';
        $anamnesis['BF1N'] = 'Zimmermann';
        $anamnesis['JALAEN'] = '12';
        $anamnesis['BF2'] = 'Maurer';
        $anamnesis['JALET'] = '2';
        $anamnesis['KINL'] = '1';
        $anamnesis['KINT'] = 'x';
        $anamnesis['KINF'] = 'x';
        $anamnesis['BKZG'] = 'x';
        $anamnesis['VORT'] = '';
        $anamnesis['ANLDIAG'] = 's';
        return $anamnesis;
    }

    protected function _createValidPrimaryBlock()
    {
        $primary = array();
        $primary['DIDA'] = '061110';
        $primary['DTEXT'] = 'Karzinom des Colon sigmoideum';
        $primary['ICDZ'] = 'C187';
        $primary['LOKT'] = 'Colon sigmoideum';
        $primary['ICT'] = '4466';
        $primary['LATN'] = 'x';
        $primary['HFK'] = 'gut differenziertes, schleimbildendes Adenokarzinom';
        $primary['ICM'] = '84813';
        $primary['ICM_VER'] = '13';
        $primary['GRADING'] = '3';
        $primary['TAUSB'] = 'r';
        $primary['STA_VER1'] = 'b';
        $primary['STADIUM1'] = 'A';
        $primary['STA_VER2'] = '';
        $primary['STADIUM2'] = '';
        $primary['TNMprae'] = 'T2 N2a M0, UICC: IIIC, Aufl.: 7';
        $primary['TNMpost'] = 'pT4b N1 M0, UICC: IIIC, Aufl.: 7';
        $primary['HDSICH'] = 'a';
        return $primary;
    }

    protected function _createValidTherapyBlock()
    {
        $therapy = array();
        $therapy['CHAT'] = 'p';
        $therapy['OPE'] = 'j';
        $therapy['DMOPE'] = '091110';
        $therapy['STT'] = 'j';
        $therapy['DMSTT'] = '201210';
        $therapy['CHE'] = 'n';
        $therapy['DMCHE'] = '';
        $therapy['HOR'] = 'n';
        $therapy['DMHOR'] = '';
        $therapy['IMM'] = 'n';
        $therapy['DMIMM'] = '';
        $therapy['AND'] = 'x';
        return $therapy;
    }

    protected function _createVaildConclusionBlock()
    {
        $conclusion = array();
        $conclusion['BEF'] = 'l';
        $conclusion['STDA'] = '';
        $conclusion['SEK'] = 'x';
        $conclusion['T1A'] = 'I509';
        $conclusion['X1A'] = '';
        $conclusion['Z1A'] = '2 Jahre';
        $conclusion['T1B'] = '';
        $conclusion['X1B'] = '';
        $conclusion['Z1B'] = '';
        $conclusion['T1C'] = '';
        $conclusion['X1C'] = '';
        $conclusion['Z1C'] = '';
        $conclusion['T2A'] = '';
        $conclusion['X2A'] = '';
        $conclusion['Z2A'] = '';
        $conclusion['T2B'] = '';
        $conclusion['X2B'] = '';
        $conclusion['Z2B'] = '';
        $conclusion['QTU'] = 'x';
        $conclusion['TURS'] = 'x';
        return $conclusion;
    }

    protected function _createVaildGeneralBlock()
    {
        $general = array();
        $general['MTYP'] = 'E';
        $general['UTR'] = 'j';
        $general['ANGKR'] = '';
        $general['EINRICHT'] = 'Test Krankenheus';
        $general['ABT'] = 'Abteilung X';
        $general['PLZ_E'] = '60456';
        $general['ORT_E'] = 'Frankfurt am Main';
        $general['STR_E'] = 'Hohestr. 129';
        $general['NAMN'] = 'Herr Dr. Bernd Schulz';
        $general['TELN'] = '';
        $general['DMN'] = '270114';
        return $general;
    }

    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function _createValidCaseBlock($section = null)
    {
        $case = array();
        if (!is_null($section) && ('patient' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createValidPatientBlock());
        }
        if (!is_null($section) && ('anamnesis' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createValidAnamnesisBlock());
        }
        if (!is_null($section) && ('primary' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createValidPrimaryBlock());
        }
        if (!is_null($section) && ('therapy' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createValidTherapyBlock());
        }
        if (!is_null($section) && ('conclusion' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createVaildConclusionBlock());
        }
        if (!is_null($section) && ('general' == $section->GetBlock())) {
            $case = array_merge($case, $section->GetDaten());
        }
        else {
            $case = array_merge($case, $this->_createVaildGeneralBlock());
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
