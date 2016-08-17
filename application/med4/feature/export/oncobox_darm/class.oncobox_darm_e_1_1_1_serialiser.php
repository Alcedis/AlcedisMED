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

require_once('feature/export/base/class.exportxmlserialiser.php');
require_once('feature/export/history/class.historymanager.php');
require_once('feature/export/history/class.history.php');

class Concobox_darm_e_1_1_1_Serialiser extends CExportXmlSerialiser
{

    public function __construct()
    {
        $this->m_xml_template_file = "oncobox_darm_e_1_1_1.tpl";
        $this->m_xml_schema_file = "feature/export/oncobox_darm/oncobox_darm_e_1_1_1.xsd";
    }

    public function Validate($parameter)
    {
        $data = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            $sections = $case->GetSections();
            // Check
            foreach ($sections as $sectionKey => $section) {
                $data = array();
                switch ($section->GetBlock()) {
                    case 'infoXML' :
                        $data = $this->_CreateInfoXMLDataArray($section);
                        break;
                    case 'personalData' :
                        $data = $this->_CreatePersonalDataArray($section);
                        break;
                    case 'anamnesis' :
                    case 'similarity' :
                    case 'caseInfo' :
                    case 'diagnosis' :
                    case 'praeConference' :
                    case 'endo' :
                    case 'surgical' :
                    case 'histology' :
                    case 'postConference' :
                    case 'liver' :
                    case 'praeRadio' :
                    case 'postRadio' :
                    case 'praeChemo' :
                    case 'postChemo' :
                    case 'bestSupportiveCare' :
                    case 'study' :
                    case 'followUp' :
                        $data = $this->_CreateDataArray($section);
                        break;
                    default :
                        break;
                }
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
                    if (count($errors) > 0) {
                        $case->SetSectionErrorsByUid($section->GetSectionUid(), $errors);
                    }
                }
            }
            $cases[$caseKey] = $case;
        }
        $this->m_export_record->SetCases($cases);
    }

    public function Encrypt($parameters)
    {
    }

    public function Write($parameters)
    {
        $xml_file = parent::Write($parameters);
        $patient_counter = -1;
        $last_patient_id = 0;
        $fall_counter = -1;
        $current_fall_counter = 0;
        $data = null;
        $cases = $this->m_export_record->GetCases();

        foreach($cases as $cases_key => $case) {
            if ($case->IsValid()) {
                if (null == $data) {
                    $data['infoXml'] = $this->_GetSectionData($case, 'infoXML');
                }
                if ($case->GetPatientId() != $last_patient_id) {
                    $last_patient_id = $case->GetPatientId();
                    $patient_counter++;
                    $fall_counter = -1;
                    $current_fall_counter = 0;
                }
                if (!isset($data['patients'][$patient_counter]['stammdaten'])) {
                    $data['patients'][$patient_counter]['stammdaten'] = $this->_GetSectionData($case, 'personalData');
                }
                if ($fall_counter != $current_fall_counter) {
                    $fall_counter++;
                    /*
                    $merge = array_merge($this->GetSectionData($case, 'fall'),
                        array(
                        ));
                    $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ] = $merge;
                    */
                    $current_fall_counter = $fall_counter;
                }
                $sections = $case->GetSections();
                foreach($sections as $key_section => $section) {
                    if (('infoXml' != $section->GetBlock()) &&
                        ('personalData' != $section->GetBlock())) {
                        $tmp = $section->GetDaten();
                        $tmp['meldungskennzeichen'] = 'N';
                        if (!isset($data['patients'][$patient_counter]['cases'])) {
                            $data['patients'][$patient_counter]['cases'] = array();
                        }
                        if (!isset($data['patients'][$patient_counter]['cases'][$fall_counter]['followUps'])) {
                            $data['patients'][$patient_counter]['cases'][$fall_counter]['followUps'] = array();
                        }
                        switch($section->GetBlock()) {
                            case 'anamnesis' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['anamnese'] = $section->GetDaten();
                                break;
                            case 'similarity' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['grundgesamtheiten'] = $section->GetDaten();
                                break;
                            case 'caseInfo' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['fallinfos'] = $section->GetDaten();
                                break;
                            case 'diagnosis' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['diagnose'] = $section->GetDaten();
                                break;
                            case 'praeConference' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['praetherapeutischeTumorkonferenz'] = $section->GetDaten();
                                break;
                            case 'endo' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['endoskopischePrimaertherapie'] = $section->GetDaten();
                                break;
                            case 'surgical' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['chirurgischePrimaertherapie'] = $section->GetDaten();
                                break;
                            case 'histology' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['postoperativeHistologieStaging'] = $section->GetDaten();
                                break;
                            case 'postConference' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['postoperativeTumorkonferenz'] = $section->GetDaten();
                                break;
                            case 'liver' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['lebermetastasen'] = $section->GetDaten();
                                break;
                            case 'praeRadio' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['praeoperativeStrahlentherapie'] = $section->GetDaten();
                                break;
                            case 'postRadio' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['postoperativeStrahlentherapie'] = $section->GetDaten();
                                break;
                            case 'praeChemo' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['praeoperativeChemotherapie'] = $section->GetDaten();
                                break;
                            case 'postChemo' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['postoperativeChemotherapie'] = $section->GetDaten();
                                break;
                            case 'bestSupportiveCare' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['bestSupportiveCare'] = $section->GetDaten();
                                break;
                            case 'study' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['prozess'] = $section->GetDaten();
                                break;
                            case 'followUp' :
                                $data['patients'][$patient_counter]['cases'][$fall_counter]['followUps'][] = $section->GetDaten();
                                break;
                        }
                        $section->SetDaten($tmp);
                    }
                    $sections[$key_section] = $section;
                }
                $cases[$cases_key] = $case;
                $fall_counter++;
            }
        }
        $this->m_export_record->SetCases($cases);
        $this->m_export_record->Write($this->m_db);

        if (null != $data) {
            $data = $this->ReplaceAllXmlEntities($data);
            $this->m_internal_smarty->assign('data', $data);
            $xml = $this->m_internal_smarty->fetch($this->m_xml_template_file);
            file_put_contents($xml_file, utf8_encode($xml));
            $this->m_smarty->assign('zip_url', $xml_file);
            $this->m_smarty->assign('zip_filename', 'Export Datei');
        }

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
                 $xml_file
           )
       );
        $historyManager->insertHistory($history);

        return $xml_file;
    }

    public function GetFilename()
    {
        return 'oncobox_darm_export_' . date('YmdHis') . '.xml';
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
    protected function _GetSectionData($case, $blockName)
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
    protected function _CreateValidInfoXMLBlock()
    {
        $infoXML = array();
        $infoXML['DatumXML'] = '2012-01-01';
        $infoXML['NameTudokusys'] = 'Alcedis MED';
        $infoXML['VersionTudokusys'] = '4.0.0';
        return $infoXML;
    }

    /**
     *
     *
     * @access
     * @return array
     */
    protected function _CreateValidePersonalDataBlock()
    {
        $personalData = array();
        $personalData['PatientID'] = '2566';
        $personalData['GeburtsJahr'] = '1923';
        $personalData['GeburtsMonat'] = '05';
        $personalData['GeburtsTag'] = '25';
        $personalData['Geschlecht'] = 'w';
        $personalData['EinwilligungTumordoku'] = '1';
        $personalData['EinwilligungExterneStelle'] = '1';
        return $personalData;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _CreateValidAnamnesisBlock()
    {
        $anamnesis = array();
        $anamnesis['RelevanteKrebsvorerkrankungen'] = '1';
        $anamnesis['JahrRelevanteKrebsvorerkrankungen'] = '1986';
        $anamnesis['NichtRelevanteKrebsvorerkrankungen'] = '1';
        $anamnesis['JahrNichtRelevanteKrebsvorerkrankungen'] = '2001';
        $anamnesis['DKGPatientenfragebogen'] = '1';
        $anamnesis['PositiveFamilienanamnese'] = '1';
        return $anamnesis;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _CreateValidSimilarityBlock()
    {
        $similarity = array();
        $similarity['Grundgesamtheiten'] = '1';
        return $similarity;
    }


    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function _CreateValidCaseInfoBlock()
    {
        $caseInfo = array();
        $caseInfo['Zentrumsfall'] = '1';
        $caseInfo['Organ'] = 'DZ';
        $caseInfo['RegNr'] = '001';
        $caseInfo['HauptNebenStandort'] = '1';
        $caseInfo['FallNummer'] = '8599';
        $caseInfo['EingabeFalldaten'] = '1';
        return $caseInfo;
    }


    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function _CreateValidDiagnosisBlock()
    {
        $diagnosis = array();
        $diagnosis['DatumErstdiagnosePrimaertumor'] = '2011-02-28';
        $diagnosis['DatumHistologischeSicherung'] = '2011-02-28';
        $diagnosis['ICDOHistologieDiagnose'] = '8000/2';
        $diagnosis['Tumorauspraegung'] = '1';
        $diagnosis['ICDOLokalisation'] = 'C20';
        $diagnosis['KolonRektum'] = 'R';
        $diagnosis['TumorlokalisationRektum'] = '2';
        $diagnosis['praeT'] = 'T2';
        $diagnosis['praeN'] = 'N1';
        $diagnosis['praeM'] = 'M0';
        $diagnosis['UICCStadium'] = 'IIA';
        $diagnosis['SynchroneBehandlungKolorektalerPrimaertumoren'] = '0';
        $diagnosis['MRTBecken'] = '1';
        $diagnosis['CTBecken'] = '1';
        $diagnosis['AbstandFaszie'] = '';
        return $diagnosis;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPraeConferenceBlock()
    {
        $praeConference = array();
        $praeConference['VorstellungPraetherapeutischeTumorkonferenz'] = '0';
        $praeConference['EmpfehlungPraetherapeutischeTumorkonferenz'] = '1';
        return $praeConference;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildEndoBlock()
    {
        $endo = array();
        $endo['DatumTherapeutischeKoloskopie'] = '2010-01-23';
        $endo['OPSCodeEndoskopischePrimaertherapie'] = '5-234.34';
        return $endo;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildSurgicalBlock()
    {
        $surgical = array();
        $surgical['ASAKlassifikation'] = '9';
        $surgical['DatumOperativeTumorentfernung'] = '2011-03-10';
        $surgical['OPSCodesChirurgischePrimaertherapie'] = '5-482.82';
        $surgical['NotfallOderElektiveingriff'] = 'E';
        $surgical['Erstoperateur'] = '44';
        $surgical['Zweitoperateur'] = '0';
        $surgical['AnastomoseDurchgefuehrt'] = '0';
        $surgical['TMEDurchgefuehrt'] = '3';
        $surgical['PostoperativeWundinfektion'] = '0';
        $surgical['DatumPostoperativeWundinfektion'] = '';
        $surgical['AufgetretenAnastomoseninsuffizienz'] = '0';
        $surgical['AnastomoseninsuffizienzInterventionspflichtig'] = '1';
        $surgical['DatumInterventionspflichtigeAnastomoseninsuffizienz'] = '2001-02-01';
        $surgical['Revisionseingriff'] = '0';
        $surgical['DatumRevisionseingriff'] = '2010-01-01';
        $surgical['OPmitStoma'] = '1';
        $surgical['Stomaangezeichnet'] = '0';
        return $surgical;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildHistologyBlock()
    {
        $histology = array();
        $histology['pT'] = 'T2';
        $histology['pN'] = 'NX';
        $histology['postM'] = 'M0';
        $histology['Grading'] = 'G2';
        $histology['ICDOHistologiePostoperative'] = '8140/3';
        $histology['PSRLokalNachAllenOPs'] = 'R1';
        $histology['PSRGesamtNachPrimaertherapie'] = 'R1';
        $histology['GueteDerMesorektumresektion'] = '4';
        $histology['AnzahlDerUntersuchtenLymphknoten'] = '0';
        $histology['AbstandAboralerTumorrand'] = '0';
        $histology['AbstandZirkumferentiellerTumorrand'] = '1';
        return $histology;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPostConferenceBlock()
    {
        $postConference = array();
        $postConference['VorstellungPostoperativeTumorkonferenz'] = '1';
        $postConference['EmpfehlungPostoperativeTumorkonferenz'] = '0';
        return $postConference;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildLiverBlock()
    {
        $liver = array();
        $liver['LebermetastasenVorhanden'] = '0';
        $liver['LebermetastasenAusschliesslich'] = '1';
        $liver['PrimaereLebermetastasenresektion'] = '0';
        $liver['BedingungenSenkundaereLebermetastasenresektion'] = '1';
        $liver['SekundaereLebermetastasenresektion'] = '0';
        return $liver;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPraeRadioBlock()
    {
        $praeRadio = array();
        $praeRadio['EmpfehlungPraeoperativeStrahlentherapie'] = '0';
        $praeRadio['DatumEmpfehlungPraeoperativeStrahlentherapie'] = '2010-02-20';
        $praeRadio['TherapiezeitpunktPraeoperativeStrahlentherapie'] = 'N';
        $praeRadio['TherapieintentionPraeoperativeStrahlentherapie'] = 'K';
        $praeRadio['GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie'] = '1';
        $praeRadio['DatumBeginnPraeoperativeStrahlentherapie'] = '1999-12-20';
        $praeRadio['DatumEndePraeoperativeStrahlentherapie'] = '2000-01-01';
        $praeRadio['GrundDerBeendigungDerPraeoperativeStrahlentherapie'] = '3';
        return $praeRadio;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPostRadioBlock()
    {
        $postRadio = array();
        $postRadio['EmpfehlungPostoperativeStrahlentherapie'] = '0';
        $postRadio['DatumEmpfehlungPostoperativeStrahlentherapie'] = '2011-06-23';
        $postRadio['TherapiezeitpunktPostoperativeStrahlentherapie'] = 'N';
        $postRadio['TherapieintentionPostoperativeStrahlentherapie'] = 'P';
        $postRadio['GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie'] = '2';
        $postRadio['DatumBeginnPostoperativeStrahlentherapie'] = '2008-04-19';
        $postRadio['DatumEndePostoperativeStrahlentherapie'] = '2012-04-08';
        $postRadio['GrundDerBeendigungDerPostoperativeStrahlentherapie'] = '2';
        return $postRadio;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPraeChemoBlock()
    {
        $praeChemo = array();
        $praeChemo['EmpfehlungPraeoperativeChemotherapie'] = '1';
        $praeChemo['DatumEmpfehlungPraeoperativeChemotherapie'] = '2011-03-10';
        $praeChemo['TherapiezeitpunktPraeoperativeChemotherapie'] = 'N';
        $praeChemo['TherapieintentionPraeoperativeChemotherapie'] = 'P';
        $praeChemo['GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie'] = '1';
        $praeChemo['DatumBeginnPraeoperativeChemotherapie'] = '2006-11-10';
        $praeChemo['DatumEndePraeoperativeChemotherapie'] = '2008-10-10';
        $praeChemo['GrundDerBeendigungDerPraeoperativeChemotherapie'] = '1';
        return $praeChemo;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildPostChemoBlock()
    {
        $postChemo = array();
        $postChemo['EmpfehlungPostoperativeChemotherapie'] = '0';
        $postChemo['DatumEmpfehlungPostoperativeChemotherapie'] = '2013-02-03';
        $postChemo['TherapiezeitpunktPostoperativeChemotherapie'] = 'N';
        $postChemo['TherapieintentionPostoperativeChemotherapie'] = 'K';
        $postChemo['GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie'] = '2';
        $postChemo['DatumBeginnPostoperativeChemotherapie'] = '2011-03-16';
        $postChemo['DatumEndePostoperativeChemotherapie'] = '2001-01-01';
        $postChemo['GrundDerBeendigungDerPostoperativeChemotherapie'] = '1';
        return $postChemo;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildBestSupportiveCareBlock()
    {
        $bestSupportiveCare = array();
        $bestSupportiveCare['BestSupportiveCare'] = '0';
        return $bestSupportiveCare;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateVaildStudyBlock()
    {
        $study = array();
        $study['DatumStudie'] = '2010-02-18';
        $study['Studientyp'] = '2';
        $study['PsychoonkologischeBetreuung'] = '1';
        $study['BeratungSozialdienst'] = '1';
        $study['GenetischeBeratungEmpfohlen'] = '1';
        $study['GenetischeBeratungErhalten'] = '0';
        $study['ImmunhistochemischeUntersuchungAufMSI'] = '1';
        return $study;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _CreateValidFollowUpBlock()
    {
        $followUp = array();
        $followUp['DatumFollowUp'] = '2011-09-25';
        $followUp['LokoregionaeresRezidiv'] = '1';
        $followUp['LymphknotenRezidiv'] = '2';
        $followUp['Fernmetastasen'] = '1';
        $followUp['Zweittumor'] = '3';
        $followUp['Verstorben'] = '1';
        $followUp['QuelleFollowUp'] = '4';
        return $followUp;
    }


    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function _CreateValidCaseBlock($section = null)
    {
        $case = array();
        if (!is_null($section) && ('anamnesis' == $section->GetBlock())) {
            $case['anamnese'] = $section->GetDaten();
        }
        else {
            $case['anamnese'] = $this->_CreateValidAnamnesisBlock();
        }
        if (!is_null($section) && ('similarity' == $section->GetBlock())) {
            $case['grundgesamtheiten'] = $section->GetDaten();
        }
        else {
            $case['grundgesamtheiten'] = $this->_CreateValidSimilarityBlock();
        }
        if (!is_null($section) && ('caseInfo' == $section->GetBlock())) {
            $case['fallinfos'] = $section->GetDaten();
        }
        else {
            $case['fallinfos'] = $this->_CreateValidCaseInfoBlock();
        }
        if (!is_null($section) && ('diagnosis' == $section->GetBlock())) {
            $case['diagnose'] = $section->GetDaten();
        }
        else {
            $case['diagnose'] = $this->_CreateValidDiagnosisBlock();
        }
        if (!is_null($section) && ('praeConference' == $section->GetBlock())) {
            $case['praetherapeutischeTumorkonferenz'] = $section->GetDaten();
        }
        else {
            $case['praetherapeutischeTumorkonferenz'] = $this->_CreateVaildPraeConferenceBlock();
        }
        if (!is_null($section) && ('endo' == $section->GetBlock())) {
            $case['endoskopischePrimaertherapie'] = $section->GetDaten();
        }
        else {
            $case['endoskopischePrimaertherapie'] = $this->_CreateVaildEndoBlock();
        }
        if (!is_null($section) && ('surgical' == $section->GetBlock())) {
            $case['chirurgischePrimaertherapie'] = $section->GetDaten();
        }
        else {
            $case['chirurgischePrimaertherapie'] = $this->_CreateVaildSurgicalBlock();
        }
        if (!is_null($section) && ('histology' == $section->GetBlock())) {
            $case['postoperativeHistologieStaging'] = $section->GetDaten();
        }
        else {
            $case['postoperativeHistologieStaging'] = $this->_CreateVaildHistologyBlock();
        }
        if (!is_null($section) && ('postConference' == $section->GetBlock())) {
            $case['postoperativeTumorkonferenz'] = $section->GetDaten();
        }
        else {
            $case['postoperativeTumorkonferenz'] = $this->_CreateVaildPostConferenceBlock();
        }
        if (!is_null($section) && ('liver' == $section->GetBlock())) {
            $case['lebermetastasen'] = $section->GetDaten();
        }
        else {
            $case['lebermetastasen'] = $this->_CreateVaildLiverBlock();
        }
        if (!is_null($section) && ('praeRadio' == $section->GetBlock())) {
            $case['praeoperativeStrahlentherapie'] = $section->GetDaten();
        }
        else {
            $case['praeoperativeStrahlentherapie'] = $this->_CreateVaildPraeRadioBlock();
        }
        if (!is_null($section) && ('postRadio' == $section->GetBlock())) {
            $case['postoperativeStrahlentherapie'] = $section->GetDaten();
        }
        else {
            $case['postoperativeStrahlentherapie'] = $this->_CreateVaildPostRadioBlock();
        }
        if (!is_null($section) && ('praeChemo' == $section->GetBlock())) {
            $case['praeoperativeChemotherapie'] = $section->GetDaten();
        }
        else {
            $case['praeoperativeChemotherapie'] = $this->_CreateVaildPraeChemoBlock();
        }
        if (!is_null($section) && ('postChemo' == $section->GetBlock())) {
            $case['postoperativeChemotherapie'] = $section->GetDaten();
        }
        else {
            $case['postoperativeChemotherapie'] = $this->_CreateVaildPostChemoBlock();
        }
        if (!is_null($section) && ('bestSupportiveCare' == $section->GetBlock())) {
            $case['bestSupportiveCare'] = $section->GetDaten();
        }
        else {
            $case['bestSupportiveCare'] = $this->_CreateVaildBestSupportiveCareBlock();
        }
        if (!is_null($section) && ('study' == $section->GetBlock())) {
            $case['prozess'] = $section->GetDaten();
        }
        else {
            $case['prozess'] = $this->_CreateVaildStudyBlock();
        }
        if (!is_null($section) && ('followUp' == $section->GetBlock())) {
            $case['followUps'][] = $section->GetDaten();
        }
        else {
            $case['followUps'][] = $this->_CreateValidFollowUpBlock();
        }
        return $case;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return mixed
     */
    protected function _CreateInfoXMLDataArray($section)
    {
        $data['infoXml'] = $section->GetDaten();
        $data['patients'][ 0 ]['stammdaten'] = $this->_CreateValidePersonalDataBlock();
        $data['patients'][ 0 ]['cases'] = array(
            $this->_CreateValidCaseBlock()
        );
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return array
     */
    protected function _CreatePersonalDataArray($section)
    {
        $data['infoXml'] = $this->_CreateValidInfoXMLBlock();
        $data['patients'][ 0 ]['stammdaten'] = $section->GetDaten();
        $data['patients'][ 0 ]['cases'] = array(
            $this->_CreateValidCaseBlock()
        );
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return array
     */
    protected function _CreateDataArray($section)
    {
        $data['infoXml'] = $this->_CreateValidInfoXMLBlock();
        $data['patients'][ 0 ]['stammdaten'] = $this->_CreateValidePersonalDataBlock();
        $data['patients'][ 0 ]['cases'] = array(
            $this->_CreateValidCaseBlock($section)
        );
        return $data;
    }

}
?>
