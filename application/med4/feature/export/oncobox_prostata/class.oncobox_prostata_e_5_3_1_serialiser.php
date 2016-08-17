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


class Concobox_prostata_e_5_3_1_Serialiser extends CExportXmlSerialiser
{

    public function __construct()
    {
        $this->m_xml_template_file = "oncobox_prostata_e_5_3_1.tpl";
        $this->m_xml_schema_file = "feature/export/oncobox_prostata/oncobox_prostata_e_5_3_1.xsd";
    }

    public function Validate($parameter)
    {
        $cases = $this->m_export_record->GetCases();
        foreach ($cases as $caseKey => $case) {
            $sections = $case->GetSections();
            // Check
            foreach ($sections as $sectionKey => $section) {
                $data = array();
                switch ($section->GetBlock()) {
                    case 'InfoXML' :
                        $data = $this->_createInfoXMLDataArray($section);
                        break;
                    case 'A' :
                    case 'B' :
                    case 'C' :
                    case 'D' :
                    case 'E' :
                        $data = $this->_createDataArray($section);
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
        $xmlFile = parent::Write($parameters);
        $data = null;
        $patients = $this->m_export_record->GetCases();

        foreach ($patients as $patientKey => $patient) {
            if ($patient->IsValid()) {
                if (null == $data) {
                    $data['InfoXML'] = $this->_getSectionData($patient, 'InfoXML');
                    $data['patients'] = array();
                }
                $sections = $patient->GetSections();
                $patientData = array();
                foreach ($sections as $sectionKey => $section) {
                    if ('InfoXML' != $section->GetBlock()) {
                        $tmp = $section->GetDaten();
                        $tmp['meldungskennzeichen'] = 'N';
                        $section->SetDaten($tmp);
                        $sections[$sectionKey] = $section;
                        $patientData[$section->GetBlock()] = $section->GetDaten();
                    }
                }
                $data['patients'][] = $patientData;
                $patients[$patientKey] = $patient;
            }
        }
        $this->m_export_record->SetCases($patients);
        $this->m_export_record->Write($this->m_db);

        if (null != $data) {
            $data = $this->ReplaceAllXmlEntities($data);
            $this->m_internal_smarty->assign('data', $data);
            $xml = $this->m_internal_smarty->fetch($this->m_xml_template_file);
            file_put_contents($xmlFile, utf8_encode($xml));
            $this->m_smarty->assign('zip_url', $xmlFile);
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
                $xmlFile
            )
        );
        $historyManager->insertHistory($history);

        return $xmlFile;
    }


    /**
     * GetFilename
     *
     * @access  public
     * @return  string
     */
    public function GetFilename()
    {
        return 'oncobox_prostata_export_' . date('YmdHis') . '.xml';
    }

    /**
     *
     *
     * @access
     * @param $case
     * @param $blockName
     * @return array
     */

    /**
     * _GetSectionData
     *
     * @access  protected
     * @param   $case
     * @param   string  $blockName
     * @return  array
     */
    protected function _getSectionData($case, $blockName)
    {
        $result = array();
        foreach ($case->GetSections() as $section) {
            if ($blockName == $section->GetBlock()) {
                $result = $section->GetDaten();
                break;
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
    protected function _createValidInfoXMLBlock()
    {
        $blockData = array();
        $blockData['DatumXML'] = '2012-01-01';
        $blockData['NameTudokusys'] = 'Alcedis MED';
        $blockData['VersionTudokusys'] = '4.0.0';
        return $blockData;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _createValidABlock()
    {
        $blockData = array();
        $blockData['PatientID'] = 'PAT0001';
        $blockData['Geschlecht'] = 'W';
        $blockData['GeburtsJahr'] = '1948';
        $blockData['GeburtsMonat'] = '10';
        $blockData['GeburtsTag'] = '04';
        $blockData['Organ'] = 'PZ';
        $blockData['RegNr'] = '999';
        $blockData['HauptNebenStandort'] = 'Milchstrasse';

        return $blockData;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _createValidBBlock()
    {
        $blockData = array();

        $blockData['ErstdiagnostikPrimaertumor'] = array(
            "DatumErstdiagnosePrimaertumor" => '2013-01-01',
            "Diagnosesicherheit" => 'K',
            "TumordiagnoseICD10" => 'C61',
            "HauptlokalisationICDO3" => 'C61.91',
            "praeTcp" => 'cT1',
            "praeT" => 'pT1',
            "praeNcp" => 'cN2',
            "praeN" => 'pN2',
            "praeMcp" => 'cM0',
            "praeM" => 'pM0',
            "PSADatum" => '2013-01-01',
            "PSAWert" => '25,67',
            "BiopsieDatum" => '2013-01-01',
            "BiopsiePerineuraleInvasion" => 'Pn0',
            "ICDOHistologie" => '8020/3',
            "BiopsieAS" => 'N',
            "GleasonScoreWert1" => '2',
            "GleasonScoreWert2" => '5',
            "Grading" => 'G3',
            "BefundPathologieVollstaendig" => 'N',
            "Blasenkarzinom" => 'N',
            "DKGPatientenfragebogenDatum" => '2014-01-01',
            "Kontinenz" => '14',
            "Potenz" => '23',
            "Lebensqualitaet" => '7',
            "Gesundheitszustand" => '6'
        );
        $blockData['Familienanamnese'] = array(
            "FamilienangehoerigeGrad1PCa" => '1',
            "Grad1juengerals60" => '2',
            "FamilienangehoerigeGrad2PCa" => '1',
            "FamilienangehoerigeGrad3PCa" => '2'
        );
        $blockData['KrebserkrankungenVorErstdiagnose'] = array(
            "RelevanteKrebserkrankungen" => 'J',
            "JahrRelevanteKrebserkrankungen" => '1978',
            "NichtRelevanteKrebserkrankungen" => 'J',
            "JahrNichtRelevanteKrebserkrankungen" => '1990'
        );
        $blockData['PatientUnterBeobachtung'] = array(
            "Zentrumspatient" => 'ZF',
            "VorstellungImZentrum" => 'V',
            "DatumVorstellungImZentrum" => '2013-01-01',
            "PatientInZentrumEingebracht" => 'STR',
            "Therapiestrategie" => 'WW',
            "EinwilligungDokumentationInTumordokumentation" => 'LV',
            "EinwilligungVersand" => 'LV',
            "EinwilligigungMeldungKKREKR" => 'LV',
            "FalldatensatzVollstaendig" => 'J'
        );
        $blockData['Prozess'] = array(
            "DatumStudie" => '2013-01-01',
            "StudienTyp" => 'NIS',
            "PsychoonkologischeBetreuung" => 'J',
            "BeratungSozialdienst" => 'N',
            "PatientInMorbiditaetskonferenzVorgestellt" => 'X'
        );
        $blockData['FollowUpPraetherapeutischerTumorkonferenz'][] = array(
            "Datum" => '2013-01-01',
            "Quelle" => 'EZ-Nach',
            "Vitalstatus" => 'L',
            "KontrolluntersuchungTyp" => 'PSA',
            "PSAWert" => '45,02',
            "Tumorstatus" => 'P',
            "DKGFragebogenEingereicht" => 'J',
            "Kontinenz" => '9',
            "Potenz" => '24',
            "Lebensqualitaet" => '7',
            "Gesundheitszustand" => '6',
            "DiagnoseFernmetastasierung" => 'J-QFMU',
            "DiagnoseZweittumor" => 'X'
        );
        return $blockData;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _createValidCBlock()
    {
        $blockData = array();
        $blockData['DiagnostikVorPrimaerintervention'] = array(
            "praeTcp" => 'cT2',
            "praeT" => 'pT2',
            "praeNcp" => 'cN3',
            "praeN" => 'pN3',
            "praeMcp" => 'cM1',
            "praeM" => 'pM2',
            "PSADatum" => '2013-01-01',
            "PSAWert" => '34,56',
            "BiopsieDatum" => '2013-01-01',
            "BiopsiePerineuraleInvasion" => 'Pn1',
            "ICDOHistologie" => '8041/3',
            "GleasonScoreWert1" => '4',
            "GleasonScoreWert2" => '3',
            "Grading" => 'G4',
            "BefundPathologieVollstaendig" => 'N',
            "Blasenkarzinom" => 'N',
            "DKGPatientenfragebogenDatum" => '2014-01-01',
            "Kontinenz" => '2',
            "Potenz" => '25',
            "Lebensqualitaet" => '7',
            "Gesundheitszustand" => '7'
        );
        $blockData['PatientInPrimaertherapie'] = array(
            "Zentrumspatient" => 'ZF',
            "PraetherapeutischeVorstellung" => 'NV',
            "DatumVorstellungImZentrum" => '2013-01-01',
            "VorstellungUeberLeistungserbringer" => 'URO',
            "EinwilligungDokumentationInTumordokumentation" => 'LV',
            "EinwilligungVersand" => 'LNV',
            "EinwilligigungMeldungKKREKR" => 'A',
            "FalldatensatzVollstaendig" => 'LV'
        );
        $blockData['Operation'] = array(
            "DatumOperation" => '2013-01-01',
            "OPSCode" => '5-604.11',
            "Verfahren" => 'LT',
            "Erstoperateur" => '146',
            "Zweitoperateur" => '389',
            "Revisionseingriff" => 'J',
            "RevisionseingriffDatum" => '2013-01-01',
            "PostoperativeWundinfektion" => 'J',
            "PostoperativeWundinfektionDatum" => '2013-01-01',
            "NervenerhaltendeOperation" => 'J',
            "CalvienDindoGrad" => 'III',
            "DatumKomplikation" => '2013-01-01'
        );
        $blockData['PostoperativeHistologie'] = array(
            "PraefixY" => 'y',
            "pT" => 'T1',
            "pN" => 'N2a',
            "pM" => 'M1',
            "GleasonScoreWert1" => '3',
            "GleasonScoreWert2" => '1',
            "Grading" => 'G3',
            "PerineuraleInvasion" => 'Pn1',
            "AnzahlUntersuchtenLymphknoten" => '2',
            "AnzahlMaligneBefallenenLymphknoten" => '1',
            "Lymphgefaessinvasion" => 'L1',
            "Veneninvasion" => 'V2',
            "ICDO3Histologie" => '8000/3',
            "PSRLokaleRadikalitaet" => 'R2'
        );
        $blockData['PostoperativesStaging'] = array(
            "TumordiagnoseICD10" => 'D07.5',
            "cM" => 'M1a'
        );
        $blockData['PostoperativeTumorkonferenz'] = array(
            "Vorstellung" => 'V',
            "Datum" => '2013-01-01'
        );
        $blockData['PerkutaneStrahlentherapie'] = array(
            "Therapiezeitpunkt" => 'A',
            "Therapieintention" => 'K',
            "BeginnDatum" => '2013-01-01',
            "GesamtdosisInGray" => '10.4',
            "EndeDatum" => '2013-01-01',
            "GrundBeendigungStrahlentherapie" => 'AN'
        );
        $blockData['LDRBrachytherapie'] = array(
            "Datum" => '2013-01-01',
            "GesamtdosisInGray" => '8,56',
            "GrayBeiD90" => '13,46'
        );
        $blockData['HDRBrachytherapie'] = array(
            "BeginnDatum" => '2013-01-01',
            "GesamtdosisInGray" => '6.7',
            "EndeDatum" => '2013-01-01',
            "GrundBeendigungBrachytherapie" => 'D90'
        );
        $blockData['Chemotherapie'] = array(
            "BeginnDatum" => '2013-01-01',
            "EndeDatum" => '2013-01-01',
            "GrundBeendigungChemotherapie" => 'E'
        );
        $blockData['Hormontherapie'] = array(
            "Therapiezeitpunkt" => 'B',
            "Therapieintention" => 'P',
            "TherapieArt" => 'O',
            "BeginnDatum" => '2013-01-01',
            "EndeDatum" => '2013-01-01',
            "GrundBeendigungHormontherapie" => 'S'
        );
        $blockData['Immuntherapie'] = array(
            "BeginnDatum" => '2013-01-01',
            "EndeDatum" => '2013-01-01',
            "GrundBeendigungImmuntherapie" => 'VF'
        );
        $blockData['WeitereTherapien'] = array(
            "SupportiveTherapieDatum" => '2013-01-01',
            "HIFUTherapieDatum" => '2013-01-01',
            "KyrotherapieDatum" => '2013-01-01',
            "HyperthermieDatum" => '2013-01-01'
        );
        $blockData['PosttherapeutischeTumorkonferenz'] = array(
            "Vorstellung" => 'V',
            "Datum" => '2013-01-01'
        );
        $blockData['AbschlussPrimaerintervention'] = array(
            "CalvienDindoGrad" => 'II',
            "DatumKomplikation" => '2014-12-25'
        );
        $blockData['ProzessPrimaertherapie'] = array(
            "Studientyp" => 'SX',
            "DatumStudie" => '2013-01-01',
            "PsychoonkologischeBetreuung" => 'N',
            "BeratungSozialdienst" => 'J',
            "PatientInMorbiditaetskonferenzvorgestellt" => 'X'
        );

        return $blockData;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _createValidDBlock()
    {
        $blockData = array();
        $blockData['progressintervention'][] = array(
            "DiagnostikVorProgressintervention" => array(
                "DatumDiagnoseProgress" => '2013-02-01',
                "TumordiagnoseICD10" => 'C61',
                "HauptlokalisationICDO3" => 'C61.93',
                "PSAWert" => '39,56',
                "BiopsieDurchgefuehrt" => 'N',
                "BiopsiePerineuraleInvasion" => 'Pnx',
                "ICDOHistologieMorphologie" => '8010/2',
                "GleasonScoreWert1" => '3',
                "GleasonScoreWert2" => '4',
                "Grading" => 'G1',
                "DKGPatientenfragebogenDatum" => '2013-02-01',
                "Kontinenz" => '0',
                "Potenz" => '0',
                "Lebensqualitaet" => '3',
                "Gesundheitszustand" => '2'
            ),
            "PatientInProgressintervention" => array(
                "Zentrumspatient" => 'KZF',
                "PraetherapeutischeVorstellung" => 'NV',
                "DatumVorstellungImZentrum" => '2013-02-01',
                "VorstellungUeberLeistungserbringer" => 'STR',
                "FalldatensatzVollstaendig" => 'J'
            ),
            "Prostatektomie" => array(
                "Datum" => '2013-02-01',
                "OPSCode" => '5-604.12',
                "Verfahren" => 'OP',
                "Erstoperateur" => '456',
                "Zweitoperateur" => '3892',
                "Revisionseingriff" => 'J',
                "RevisionseingriffDatum" => '2013-02-01',
                "PostoperativeWundinfektion" => 'J',
                "PostoperativeWundinfektionDatum" => '2013-02-01',
                "NervenerhaltendeOperation" => '' // TODO: Wieder aktivieren wenn das Problem mit Onkozert geklärt ist!!!
            ),
            "PostoperativeHistologie" => array(
                "PostopResidualtumorLokale" => 'RX'
            ),
            "PostoperativeTumorkonferenz" => array(
                "Vorstellung" => 'V',
                "Datum" => '2013-02-01'
            ),
            "ProzessProgressintervention" => array(
                "Studientyp" => 'NIS',
                "DatumStudie" => '2013-02-01',
                "PsychoonkologischeBetreuung" => 'J',
                "BeratungSozialdienst" => 'J',
                "PatientInMorbiditaetskonferenzvorgestellt" => 'X'
            )
        );

        return $blockData;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _createValidEBlock()
    {
        $blockData = array();
        $blockData['followup'][] = array(
            "Datum" => '2013-10-04',
            "Quelle" => 'KKR',
            "Vitalstatus" => 'L',
            "Tumorstatus" => 'TF',
            "PSAWert" => '45,78',
            "DKGFragebogenEingereicht" => 'J',
            "Kontinenz" => '20',
            "Potenz" => '23',
            "Lebensqualitaet" => '7',
            "Gesundheitszustand" => '6',
            "DiagnoseLokalrezidiv" => 'N',
            "DiagnoseBiochemischenRezidiv" => 'N',
            "DiagnoseFernmetastasierung" => 'N',
            "Zweittumor" => 'N'
        );
        return $blockData;
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
        if (!is_null($section) && ('A' == $section->GetBlock())) {
            $case['A'] = $section->GetDaten();
        }
        else {
            $case['A'] = $this->_createValidABlock();
        }
        if (!is_null($section) && ('B' == $section->GetBlock())) {
            $case['B'] = $section->GetDaten();
        }
        else {
            $case['B'] = $this->_createValidBBlock();
        }
        if (!is_null($section) && ('C' == $section->GetBlock())) {
            $case['C'] = $section->GetDaten();
        }
        else {
            $case['C'] = $this->_createValidCBlock();
        }
        if (!is_null($section) && ('D' == $section->GetBlock())) {
            $case['D'] = $section->GetDaten();
        }
        else {
            $case['D'] = $this->_createValidDBlock();
        }
        if (!is_null($section) && ('E' == $section->GetBlock())) {
            $case['E'] = $section->GetDaten();
        }
        else {
            $case['E'] = $this->_createValidEBlock();
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
    protected function _createInfoXMLDataArray($section)
    {
        $data['InfoXML'] = $section->GetDaten();
        $data['patients'][0] = $this->_createValidCaseBlock();
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return array
     */
    protected function _createDataArray($section)
    {
        $data['InfoXML'] = $this->_createValidInfoXMLBlock();
        $data['patients'][0] = $this->_createValidCaseBlock($section);
        return $data;
    }

}

?>
