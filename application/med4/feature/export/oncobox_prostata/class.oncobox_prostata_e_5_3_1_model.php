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
require_once('class.oncobox_prostata_e_5_3_1_serialiser.php');
require_once('core/class/report/helper.reports.php');
require_once('feature/export/base/helper.common.php');

require_once('model/helper.php');

require_once('feature/export/base/wrapper/kraken.php');

/**
 * Class Concobox_prostata_e_5_3_1_Model
 */
class Concobox_prostata_e_5_3_1_Model extends CExportDefaultModel
{
    /**
     * take kraken export wrapper
     *
     * @access  protected
     * @var     string
     */
    protected $_wrapperName = 'exportWrapperKraken';


    /**
     * helper
     *
     * @access  protected
     * @var     Concobox_prostata_e_5_3_1_Model_Helper
     */
    protected $_helper;


    /**
     * ExtractData
     *
     * @access  public
     * @param   array           $parameters
     * @param   IExportWrapper  $wrapper
     * @param   RExport         $export_record
     * @return  void
     */
    public function extractData($parameters, $wrapper, &$export_record)
    {
        $this->appendWrapperData();

        $helper = $this->_helper = new Concobox_prostata_e_5_3_1_Model_Helper;

        $helper->setParameters($parameters);

        $this->loadTemplates($helper);

        $patients = $this->getWrapper()->getExportData();

        $sections = array('A', 'B', 'C', 'D', 'E');

        foreach ($patients as $patient) {
            $helper->resetSectionHelperCache();

            $disease = $patient['erkrankung'];

            if ($disease === null) {
                continue;
            }

            $patient['erkrankung_id'] = $disease['erkrankung_id'];

            $case = $this->createCase($export_record->getDbid(), $this->getParameters(), $patient);

            $uID = $patient['patient_id'] . '_' . $patient['erkrankung_id'] . '_';

            $sectionData = $this->_getInfoData($patient);
            $block = $this->createBlock($case->GetDbid(), $parameters, 'InfoXML', $uID . 'InfoXML', $sectionData);
            $case->addSection($block);

            foreach ($sections as $section) {
                $methodProcess = 'processSection' . $section;

                $sectionData = $this->{$methodProcess}($patient);

                $block = $this->createBlock($case->GetDbid(), $parameters, $section, $uID . $section, $sectionData);

                $case->addSection($block);
            }

            $export_record->AddCase($case);
        }
    }


    /**
     * _loadTemplates
     *
     * @access  public
     * @param   Concobox_prostata_e_5_3_1_Model_Helper $helper
     * @return  Concobox_prostata_e_5_3_1_Model
     */
    public function loadTemplates(Concobox_prostata_e_5_3_1_Model_Helper $helper)
    {
        $templates = array(
            'studie' => array()
        );

        foreach (sql_query_array($this->getDB(), 'SELECT * FROM vorlage_studie') as $record) {
            $templates['studie'][$record['vorlage_studie_id']] = $record;
        }

        $helper->setTemplates($templates);

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
    { }


    /**
     *
     *
     * @access
     * @param array  $parameters
     * @param string $case
     * @param array  $section
     * @param array  $old_section
     * @return void
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        $section->SetMeldungskennzeichen("N");
        $section->SetDataChanged(1);
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function CheckData($parameters, &$export_record)
    {
        $serialiser = new Concobox_prostata_e_5_3_1_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(), $this->m_smarty, $this->m_db, $this->m_error_function);
        $serialiser->SetData($export_record);
        $serialiser->Validate($this->m_parameters);
    }


    /**
     *
     *
     * @access
     * @param $patient
     * @return array
     */
    protected function _getInfoData($patient)
    {
        $section = array(
            "DatumXML" => date('Y-m-d'),
            "NameTudokusys" => "Alcedis MED",
            "VersionTudokusys" => "4.0"
        );

        return $section;
    }


    /**
     * processSectionA
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionA($patient)
    {
        $section = array(
            'PatientID',
            'Geschlecht',
            'GeburtsJahr',
            'GeburtsMonat',
            'GeburtsTag',
            'Organ',
            'RegNr',
            'HauptNebenStandort'
        );

        return $this->callMethods('A', $section, $patient, array());
    }


    /**
     * processSectionB
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionB($patient)
    {
        $section = array_merge(
            $this->processSectionB1_3($patient),
            $this->processSectionB4_5($patient),
            $this->processSectionB6($patient)
        );

        return $section;
    }


    /**
     * processSectionB1_3
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionB1_3($patient)
    {
        $dates   = array();
        $records = false;
        $disease = $patient['erkrankung'];

        foreach ($disease['konferenz_patient'] as $record) {
            if ($record['art'] === 'prae') {
                $dates[] = $record['konferenz']['datum'];
            }
        }

        if (count($dates) > 0) {
            // sort for latest date
            rsort($dates);

            $records = $this->getRecordsUntil($disease, reset($dates));
        }

        $section = array(
            'ErstdiagnostikPrimaertumor'       => $this->processSectionB1($patient, $records),
            'Familienanamnese'                 => $this->processSectionB2($patient, $records),
            'KrebserkrankungenVorErstdiagnose' => $this->processSectionB3($patient, $records)
        );

        return $section;
    }


    /**
     * processSectionB1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionB1($patient, $records)
    {
        $section = array(
            'DatumErstdiagnosePrimaertumor',
            'Diagnosesicherheit',
            'TumordiagnoseICD10',
            'HauptlokalisationICDO3',
            'praeTcp',
            'praeT',
            'praeNcp',
            'praeN',
            'praeMcp',
            'praeM',
            'PSADatum',
            'PSAWert',
            'BiopsieDatum',
            'BiopsiePerineuraleInvasion',
            'ICDOHistologie',
            'BiopsieAS',
            'GleasonScoreWert1',
            'GleasonScoreWert2',
            'Grading',
            'BefundPathologieVollstaendig',
            'Blasenkarzinom',
            'DKGPatientenfragebogenDatum',
            'Kontinenz',
            'Potenz',
            'Lebensqualitaet',
            'Gesundheitszustand'
        );

        return $this->callMethods('B1', $section, $patient, $records);
    }


    /**
     * processSectionB2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionB2($patient, $records)
    {
        $section = array(
            'FamilienangehoerigeGrad1PCa',
            'Grad1juengerals60',
            'FamilienangehoerigeGrad2PCa',
            'FamilienangehoerigeGrad3PCa'
        );

        return $this->callMethods('B2', $section, $patient, $records);
    }


    /**
     * processSectionB3
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionB3($patient, $records)
    {
        $section = array(
            'RelevanteKrebserkrankungen',
            'JahrRelevanteKrebserkrankungen',
            'NichtRelevanteKrebserkrankungen',
            'JahrNichtRelevanteKrebserkrankungen'
        );

        return $this->callMethods('B3', $section, $patient, $records);
    }


    /**
     * processSectionB4_5
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionB4_5($patient)
    {
        $records = $this->getNonInterventionalPrimaryDisease($patient);

        $section = array(
            'PatientUnterBeobachtung' => $this->processSectionB4($patient, $records),
            'Prozess'                 => $this->processSectionB5($patient, $records)
        );

        return $section;
    }


    /**
     * processSectionB4
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionB4($patient, $records)
    {
        $section = array(
            'Zentrumspatient',
            'VorstellungImZentrum',
            'DatumVorstellungImZentrum',
            'PatientInZentrumEingebracht',
            'Therapiestrategie',
            'EinwilligungDokumentationInTumordokumentation',
            'EinwilligungVersand',
            'EinwilligigungMeldungKKREKR',
            'FalldatensatzVollstaendig'
        );

        return $this->callMethods('B4', $section, $patient, $records);
    }


    /**
     * processSectionB5
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionB5($patient, $records)
    {
        $section = array(
            'DatumStudie',
            'StudienTyp',
            'PsychoonkologischeBetreuung',
            'BeratungSozialdienst',
            'PatientInMorbiditaetskonferenzVorgestellt'
        );

        return $this->callMethods('B5', $section, $patient, $records);
    }


    /**
     * processSectionB6
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionB6($patient)
    {
        $followUps = array();

        $template  = array(
            'Datum',
            'Quelle',
            'Vitalstatus',
            'KontrolluntersuchungTyp',
            'PSAWert',
            'Tumorstatus',
            'DKGFragebogenEingereicht',
            'Kontinenz',
            'Potenz',
            'Lebensqualitaet',
            'Gesundheitszustand',
            'DiagnoseFernmetastasierung',
            'DiagnoseZweittumor',
        );

        $primaryRecords = $this->getNonInterventionalPrimaryDisease($patient);

        if ($primaryRecords !== false) {
            $dates = array();

            foreach (array('strahlentherapie', 'therapie_systemisch') as $therapies) {
                foreach ($primaryRecords[$therapies] as $record) {
                    $dates[] = $record['datum'];
                }
            }

            foreach ($primaryRecords['eingriff'] as $record) {
                if ($record['art_primaertumor'] === '1') {
                    $dates[] = $record['datum'];
                }
            }

            sort($dates);

            $endDate = count($dates) > 0 ? reset($dates) : '2999-01-01';

            $records = $this->getRecordsUntil($patient['erkrankung'], $endDate);

            $closure = $patient['abschluss'];

            $fu = array();

            if ($closure !== null) {
                $date = $this->ifEmpty($closure['todesdatum'], $closure['letzter_kontakt_datum']);

                if (strlen($date) > 0 && $date < $endDate) {
                    $patient['abschluss']['datum'] = $date;

                    $fu[$date][] = array_merge(array('form' => 'abschluss'), $patient['abschluss']);
                }
            }

            foreach ($records['nachsorge'] as $record) {
                if ($record['nachsorge']['datum'] < $endDate) {
                    $fu[$record['nachsorge']['datum']][] = array_merge(array('form' => 'nachsorge'), $record['nachsorge']);
                }
            }

            $tumorstatus = $records['tumorstatus']->reverse();
            $rcLast = 0;

            foreach ($tumorstatus as $record) {
                if (str_starts_with($record['anlass'], 'r') === true) {
                    $rc = substr($record['anlass'], 1);

                    if ($rc !== $rcLast) {
                        $nextRecord = null;

                        foreach ($tumorstatus as $nRecord) {
                            if (str_starts_with($nRecord['anlass'], 'r') === true && substr($nRecord['anlass'], 1) > $rc) {
                                $nextRecord = $nRecord;
                                break;
                            }
                        }

                        $startDate = $record['datum'];
                        $endDate   = $nextRecord !== null ? $nextRecord['datum'] : "2999-01-01";

                        $rRecords = $this->getRecordsBetween($patient['erkrankung'], $startDate, $endDate);

                        $record['ops'] = $rRecords['eingriff'];

                        $fu[$record['datum']][] = array_merge(array('form' => 'tumorstatus'), $record);

                        $rcLast = $rc;
                    }
                }
            }

            sort($fu);

            foreach ($fu as $records) {
                foreach ($records as $record) {
                    $followUps[] = $this->callMethods('B6', $template, $patient, $record);
                }
            }
        }

        $section = array(
            'FollowUpPraetherapeutischerTumorkonferenz' => $followUps
        );

        return $section;
    }


    /**
     * processSectionC
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionC($patient)
    {
        $records = $this->_getPrimaryDiseaseRecords($patient);

        $section = array(
            'DiagnostikVorPrimaerintervention' => $this->processSectionC1($patient, $records),
            'PatientInPrimaertherapie'         => $this->processSectionC2($patient, $records),
            'Operation'                        => $this->processSectionC3($patient, $records),
            'PostoperativeHistologie'          => $this->processSectionC4($patient, $records),
            'PostoperativesStaging'            => $this->processSectionC4_2($patient, $records),
            'PostoperativeTumorkonferenz'      => $this->processSectionC5($patient, $records),
            'PerkutaneStrahlentherapie'        => $this->processSectionC6($patient, $records),
            'LDRBrachytherapie'                => $this->processSectionC7($patient, $records),
            'HDRBrachytherapie'                => $this->processSectionC8($patient, $records),
            'Chemotherapie'                    => $this->processSectionC9($patient, $records),
            'Hormontherapie'                   => $this->processSectionC10($patient, $records),
            'Immuntherapie'                    => $this->processSectionC11($patient, $records),
            'WeitereTherapien'                 => $this->processSectionC12($patient, $records),
            'PosttherapeutischeTumorkonferenz' => $this->processSectionC13($patient, $records),
            'AbschlussPrimaerintervention'     => $this->processSectionC14($patient, $records),
            'ProzessPrimaertherapie'           => $this->processSectionC15($patient, $records)
        );

        return $section;
    }


    /**
     * processSectionC1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC1($patient, $records)
    {
        $dates = array();

        foreach (array('strahlentherapie', 'therapie_systemisch') as $therapies) {
            foreach ($records[$therapies] as $therapy) {
                $dates[] = $therapy['datum'];
            }
        }

        foreach ($records['eingriff'] as $op) {
            if ($op['art_primaertumor'] == 1) {
                $dates[] = $op['datum'];
            }
        }

        if (count($dates) > 0) {
            // sort for earliest date
            sort($dates);
            $records = $this->getRecordsUntil($patient['erkrankung'], reset($dates));
        } else {
            $records = $this->getRecordsUntil($patient['erkrankung'], '2999-01-01');
        }

        $section = array(
            'praeTcp',
            'praeT',
            'praeNcp',
            'praeN',
            'praeMcp',
            'praeM',
            'PSAWert',
            'PSADatum',
            'BiopsieDatum',
            'BiopsiePerineuraleInvasion',
            'ICDOHistologie',
            'GleasonScoreWert1',
            'GleasonScoreWert2',
            'Grading',
            'BefundPathologieVollstaendig',
            'Blasenkarzinom',
            'DKGPatientenfragebogenDatum',
            'Kontinenz',
            'Potenz',
            'Lebensqualitaet',
            'Gesundheitszustand'
        );

        return $this->callMethods('C1', $section, $patient, $records);
    }


    /**
     * processSectionC2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC2($patient, $records)
    {
        $section = array(
            'Zentrumspatient',
            'PraetherapeutischeVorstellung',
            'DatumVorstellungImZentrum',
            'VorstellungUeberLeistungserbringer',
            'EinwilligungDokumentationInTumordokumentation',
            'EinwilligungVersand',
            'EinwilligigungMeldungKKREKR',
            'FalldatensatzVollstaendig'
        );

        return $this->callMethods('C2', $section, $patient, $records);
    }


    /**
     * processSectionC3
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC3($patient, $records)
    {
        $section = array(
            'DatumOperation',
            'OPSCode',
            'Verfahren',
            'Erstoperateur',
            'Zweitoperateur',
            'RevisionseingriffDatum',
            'Revisionseingriff',
            'PostoperativeWundinfektionDatum',
            'PostoperativeWundinfektion',
            'NervenerhaltendeOperation',
            'CalvienDindoGrad',
            'DatumKomplikation'
        );

        return $this->callMethods('C3', $section, $patient, $records);
    }


    /**
     * processSectionC4
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC4($patient, $records)
    {
        $section = array(
            'PraefixY',
            'pT',
            'pN',
            'pM',
            'GleasonScoreWert1',
            'GleasonScoreWert2',
            'Grading',
            'PerineuraleInvasion',
            'AnzahlUntersuchtenLymphknoten',
            'AnzahlMaligneBefallenenLymphknoten',
            'Lymphgefaessinvasion',
            'Veneninvasion',
            'ICDO3Histologie',
            'PSRLokaleRadikalitaet'
        );

        return $this->callMethods('C4', $section, $patient, $records);
    }


    /**
     * processSectionC5
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC4_2($patient, $records)
    {
        $section = array(
            'TumordiagnoseICD10',
            'cM'
        );

        return $this->callMethods('C4_2', $section, $patient, $records);
    }


    /**
     * processSectionC6
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC5($patient, $records)
    {
        $section = array(
            'Vorstellung',
            'Datum'
        );

        return $this->callMethods('C5', $section, $patient, $records);
    }


    /**
     * processSectionC6
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC6($patient, $records)
    {
        $section = array(
            'Therapiezeitpunkt',
            'Therapieintention',
            'BeginnDatum',
            'GesamtdosisInGray',
            'EndeDatum',
            'GrundBeendigungStrahlentherapie'
        );

        return $this->callMethods('C6', $section, $patient, $records);
    }


    /**
     * processSectionC7
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC7($patient, $records)
    {
        $section = array(
            'Datum',
            'GesamtdosisInGray',
            'GrayBeiD90'
        );

        return $this->callMethods('C7', $section, $patient, $records);
    }


    /**
     * processSectionC8
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC8($patient, $records)
    {
        $section = array(
            'BeginnDatum',
            'GesamtdosisInGray',
            'EndeDatum',
            'GrundBeendigungBrachytherapie'
        );

        return $this->callMethods('C8', $section, $patient, $records);
    }


    /**
     * processSectionC9
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC9($patient, $records)
    {
        $section = array(
            'BeginnDatum',
            'EndeDatum',
            'GrundBeendigungChemotherapie'
        );

        return $this->callMethods('C9', $section, $patient, $records);
    }


    /**
     * processSectionC10
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC10($patient, $records)
    {
        $section = array(
            'Therapiezeitpunkt',
            'Therapieintention',
            'TherapieArt',
            'BeginnDatum',
            'EndeDatum',
            'GrundBeendigungHormontherapie'
        );

        return $this->callMethods('C10', $section, $patient, $records);
    }


    /**
     * processSectionC11
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC11($patient, $records)
    {
        $section = array(
            'BeginnDatum',
            'EndeDatum',
            'GrundBeendigungImmuntherapie'
        );

        return $this->callMethods('C11', $section, $patient, $records);
    }


    /**
     * processSectionC12
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC12($patient, $records)
    {
        $section = array(
            'SupportiveTherapieDatum',
            'HIFUTherapieDatum',
            'KyrotherapieDatum',
            'HyperthermieDatum'
        );

        return $this->callMethods('C12', $section, $patient, $records);
    }


    /**
     * processSectionC13
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC13($patient, $records)
    {
        $section = array(
            'Vorstellung',
            'Datum'
        );

        return $this->callMethods('C13', $section, $patient, $records);;
    }


    /**
     * processSectionC14
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC14($patient, $records)
    {
        $section = array(
            'CalvienDindoGrad',
            'DatumKomplikation'
        );

        return $this->callMethods('C14', $section, $patient, $records);
    }


    /**
     * processSectionC15
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionC15($patient, $records)
    {
        $section = array(
            'DatumStudie',
            'Studientyp',
            'PsychoonkologischeBetreuung',
            'BeratungSozialdienst',
            'PatientInMorbiditaetskonferenzvorgestellt'
        );

        return $this->callMethods('C15', $section, $patient, $records);
    }


    /**
     * processSectionD
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionD($patient)
    {
        $interventions = array();
        $disease       = $patient['erkrankung'];
        $tumorstatus   = $disease['tumorstatus']->reverse();
        $rcLast        = 0;

        foreach ($tumorstatus as $i => $ts) {
            if (str_starts_with($ts['anlass'], 'r')) {
                $rc = substr($ts['anlass'], 1);
                if ($rc !== $rcLast) {
                    $startDate  = $ts['datum'];
                    $nextRecord = null;
                    foreach ($tumorstatus as $record) {
                        if (str_starts_with($ts['anlass'], 'r')) {
                            if (substr($record['anlass'], 1) > $rc) {
                                $nextRecord = $record;
                            }
                        }
                    }
                    $endDate = $nextRecord !== null ? $nextRecord['datum'] : "2999-01-01";
                    $records = $this->getRecordsBetween($disease, $startDate, $endDate);
                    $records = $this->_manualPreparingRanges($records, $startDate, $endDate);

                    if ($this->_hasProstatectomy($records)) {
                        $interventions[] = array(
                            'DiagnostikVorProgressintervention' => $this->processSectionD1($patient, $records),
                            'PatientInProgressintervention'     => $this->processSectionD2($patient, $records),
                            'Prostatektomie'                    => $this->processSectionD3($patient, $records),
                            'PostoperativeHistologie'           => $this->processSectionD4($patient, $records),
                            'PostoperativeTumorkonferenz'       => $this->processSectionD5($patient, $records),
                            'ProzessProgressintervention'       => $this->processSectionD6($patient, $records)
                        );
                    }
                    $rcLast = $rc;
                }
            }
        }

        return array(
            'progressintervention' => $interventions
        );
    }


    /**
     * processSectionD1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD1($patient, $records)
    {
        $section = array(
            'DatumDiagnoseProgress',
            'TumordiagnoseICD10',
            'HauptlokalisationICDO3',
            'PSAWert',
            'BiopsieDurchgefuehrt',
            'BiopsiePerineuraleInvasion',
            'ICDOHistologieMorphologie',
            'GleasonScoreWert1',
            'GleasonScoreWert2',
            'Grading',
            'DKGPatientenfragebogenDatum',
            'Kontinenz',
            'Potenz',
            'Lebensqualitaet',
            'Gesundheitszustand'
        );

        return $this->callMethods('D1', $section, $patient, $records);
    }


    /**
     * processSectionD2
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD2($patient, $records)
    {
        $section = array(
            'Zentrumspatient',
            'PraetherapeutischeVorstellung',
            'DatumVorstellungImZentrum',
            'VorstellungUeberLeistungserbringer',
            'FalldatensatzVollstaendig'
        );

        return $this->callMethods('D2', $section, $patient, $records);
    }


    /**
     * processSectionD3
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD3($patient, $records)
    {
        $section = array(
            'Datum',
            'OPSCode',
            'Verfahren',
            'Erstoperateur',
            'Zweitoperateur',
            'Revisionseingriff',
            'RevisionseingriffDatum',
            'PostoperativeWundinfektion',
            'PostoperativeWundinfektionDatum',
            'NervenerhaltendeOperation'
        );

        return $this->callMethods('D3', $section, $patient, $records);
    }


    /**
     * processSectionD4
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD4($patient, $records)
    {
        $section = array(
            'PostopResidualtumorLokale'
        );

        return $this->callMethods('D4', $section, $patient, $records);
    }


    /**
     * processSectionD5
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD5($patient, $records)
    {
        $section = array(
            'Vorstellung',
            'Datum'
        );

        return $this->callMethods('D5', $section, $patient, $records);
    }


    /**
     * processSectionD6
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionD6($patient, $records)
    {
        $section = array(
            'DatumStudie',
            'Studientyp',
            'PsychoonkologischeBetreuung',
            'BeratungSozialdienst',
            'PatientInMorbiditaetskonferenzvorgestellt'
        );

        return $this->callMethods('D6', $section, $patient, $records);
    }


    /**
     * processSectionE
     *
     * @access  public
     * @param   array   $patient
     * @return  array
     */
    public function processSectionE($patient)
    {
        $followups = array();

        if ($this->checkInterventionalPrimaryDisease($patient) === true) {
            $followups[] = $this->processSectionE1($patient, $patient['abschluss']);

            foreach ($patient['erkrankung']['nachsorge'] as $record) {
                $followups[] = $this->processSectionE1($patient, $record);
            }

            $disease     = $patient['erkrankung'];
            $tumorstatus = $disease['tumorstatus']->reverse();
            $rcLast      = 0;

            foreach ($tumorstatus as $i => $ts) {
                if (str_starts_with($ts['anlass'], 'r')) {
                    $rc = substr($ts['anlass'], 1);

                    if ($rc !== $rcLast) {
                        $startDate  = $ts['datum'];
                        $nextRecord = null;

                        foreach ($tumorstatus as $record) {
                            if (str_starts_with($ts['anlass'], 'r')) {
                                if (substr($record['anlass'], 1) > $rc) {
                                    $nextRecord = $record;
                                }
                            }
                        }

                        $endDate = $nextRecord !== null ? $nextRecord['datum'] : "2999-01-01";
                        $records = $this->getRecordsBetween($disease, $startDate, $endDate);

                        $followups[] = $this->processSectionE1($patient, $records);

                        $rcLast = $rc;
                    }
                }
            }
        }

        return array(
            'followup' => $followups
        );
    }


    /**
     * processSectionE1
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function processSectionE1($patient, $records)
    {
        $section = array(
            'Datum',
            'Quelle',
            'Vitalstatus',
            'Tumorstatus',
            'PSAWert',
            'DKGFragebogenEingereicht',
            'Kontinenz',
            'Potenz',
            'Lebensqualitaet',
            'Gesundheitszustand',
            'DiagnoseLokalrezidiv',
            'DiagnoseBiochemischenRezidiv',
            'DiagnoseFernmetastasierung',
            'Zweittumor'
        );

        return $this->callMethods('E1', $section, $patient, $records);
    }


    /**
     * appendWrapperData
     *
     * @access  public
     * @return  void
     */
    public function appendWrapperData()
    {
        $orgId = $this->getParameter('org_id');

        $this->getWrapper()
            ->setModel(exportWrapperKrakenModel::create('patient')
                ->setFields(array(
                    'patient_nr',
                    'geburtsdatum',
                    'nachname',
                    'geschlecht',
                    'datenaustausch',
                    'datenspeicherung',
                    'datenversand'
                ))
                ->addCondition("org_id = '{$orgId}'")
                ->addRelation(exportWrapperKrakenModel::create('erkrankung')
                    ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                    ->addField('erkrankung')
                    ->addField(exportWrapperKrakenModelField::create('erkrankung_weitere_id')
                        ->setStatement('erkrankung_id')
                    )
                    ->setRelationField('patient_id')
                    ->addCondition("erkrankung = 'p'")
                    ->addRelation(exportWrapperKrakenModel::create('histologie')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'art',
                            'eingriff_id',
                            'unauffaellig'
                        ))
                        ->setOrderBy('datum', 'DESC')
                        ->addRelation(exportWrapperKrakenModel::create('eingriff')
                            ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                            ->setRelationField('eingriff_id')
                            ->setFields(array('datum'))
                        )
                    )
                    ->addRelation(exportWrapperKrakenModel::create('anamnese')
                        ->setFields(array(
                            'datum',
                            'fb_dkg',
                            'gz_dkg',
                            'iciq_ui',
                            'iief5',
                            'lq_dkg'
                        ))
                        ->setRelationField('erkrankung_id')
                        ->addRelation(exportWrapperKrakenModel::create('anamnese_familie')
                            ->setFields(array(
                                'karzinom',
                                'verwandschaftsgrad',
                                'erkrankungsalter'
                            ))
                            ->setRelationField('anamnese_id')
                        )
                        ->addRelation(exportWrapperKrakenModel::create('anamnese_erkrankung')
                            ->setRelationField('anamnese_id')
                            ->setFields(array(
                                'erkrankung',
                                'jahr',
                                'morphologie'

                            ))
                        )
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('eingriff')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'art_primaertumor',
                            'op_verfahren',
                            'art_diagnostik',
                            'art_revision',
                            'art_rezidiv',
                            'nerverhalt_seite',
                            'operateur1_id',
                            'operateur2_id'
                        ))
                        ->addRelation(exportWrapperKrakenModel::create('eingriff_ops')
                            ->select('prozedur')
                            ->setRelationField('eingriff_id')
                        )
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('therapie_systemisch')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'ende',
                            'endstatus',
                            'endstatus_grund',
                            'intention',
                            'vorlage_therapie_art'
                        ))
                        ->addField(exportWrapperKrakenModelField::create('datum')
                            ->setStatement('beginn')
                        )
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('strahlentherapie')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'ende',
                            'best_response',
                            'endstatus',
                            'endstatus_grund',
                            'gesamtdosis',
                            'intention',
                            'art',
                            'seed_strahlung_90d'
                        ))
                        ->addField(exportWrapperKrakenModelField::create('datum')
                            ->setStatement('beginn')
                        )
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('tumorstatus')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'anlass',
                            'datum_sicherung',
                            'diagnosesicherung',
                            'diagnose',
                            'lokalisation',
                            'tnm_praefix',
                            't',
                            'n',
                            'm',
                            'g',
                            'l',
                            'v',
                            'r_lokal',
                            'datum_psa',
                            'psa',
                            'ppn',
                            'morphologie',
                            'gleason1',
                            'gleason2',
                            'fall_vollstaendig',
                            'quelle_metastasen',
                            'nur_zweitmeinung',
                            'nur_diagnosesicherung',
                            'kein_fall',
                            'zufall',
                            'lk_entf',
                            'lk_bef',
                            'rezidiv_lokal',
                            'rezidiv_lk',
                            'rezidiv_psa',
                            'rezidiv_metastasen',
                            'zweittumor'
                        ))
                        ->addField(exportWrapperKrakenModelField::create('datum')
                            ->setStatement("IFNULL(datum_sicherung, datum_beurteilung)")
                        )
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('therapieplan')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'grundlage',
                            'zeitpunkt',
                            'leistungserbringer',
                            'org_id',
                            'watchful_waiting',
                            'active_surveillance',
                            'studie'
                        ))
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('komplikation')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'revisionsoperation',
                            'komplikation'
                        ))
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('untersuchung')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'art'
                        ))
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('studie')
                        ->setRelationField('erkrankung_id')
                        ->addField(exportWrapperKrakenModelField::create('datum')
                            ->setStatement("IFNULL(beginn, date)")
                        )
                        ->addField('date')
                        ->addField('beginn')
                        ->addField('vorlage_studie_id')
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('ekr')
                        ->setRelationField('erkrankung_id')
                        ->select('datum_einverstaendnis')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('beratung')
                        ->setRelationField('erkrankung_id')
                        ->setFields(array(
                            'datum',
                            'psychoonkologie',
                            'psychoonkologie_dauer',
                            'sozialdienst'
                        ))
                        ->setOrderBy('datum', 'DESC')
                    )
                    ->addRelation(exportWrapperKrakenModel::create('konferenz_patient')
                        ->setRelationField('erkrankung_id')
                        ->setCondition('konferenz_id IS NOT NULL')
                        ->setFields(array(
                            'konferenz_id',
                            'art'
                        ))
                        ->addRelation(exportWrapperKrakenModel::create('konferenz')
                            ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                            ->select('datum')
                            ->setRelationField('konferenz_id')
                        )
                    )
                    ->addRelation(exportWrapperKrakenModel::create('nachsorge')
                        ->setTableName('nachsorge_erkrankung')
                        ->setRelationField('erkrankung_weitere_id')
                        ->addField('nachsorge_id')
                        ->addRelation(exportWrapperKrakenModel::create('nachsorge')
                            ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                            ->setRelationField('nachsorge_id')
                            ->setFields(array(
                                'datum',
                                'org_id',
                                'nachsorge_biopsie',
                                'psa_bestimmt',
                                'malignom',
                                'fb_dkg',
                                'gz_dkg',
                                'iciq_ui',
                                'iief5',
                                'lq_dkg',
                                'response_klinisch',
                                'labor_id'
                            ))
                            ->addRelation(exportWrapperKrakenModel::create('psa_labor_wert')
                                ->setTableName('labor_wert')
                                ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                                ->setRelationField('labor_id')
                                ->setCondition('parameter = "psa" AND wert IS NOT NULL')
                                ->addField('labor_id')
                                ->addField('wert')
                            )
                        )
                    )
                )
                ->addRelation(exportWrapperKrakenModel::create('abschluss')
                    ->setRelationField('patient_id')
                    ->setRelationType(exportWrapperKrakenModel::RELATION_ONE)
                    ->addField('abschluss_grund')
                    ->addField('todesdatum')
                    ->addField('letzter_kontakt_datum')
                    ->addField('tod_tumorassoziation')
                    ->addField('tod_ursache')
                    ->addRelation(exportWrapperKrakenModel::create('abschluss_ursache')
                        ->setRelationField('abschluss_id')
                        ->addField('krankheit')
                    )
                )

            )
        ;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function WriteData()
    {
        $this->m_export_record->SetFinished(true);
        $serialiser = new Concobox_prostata_e_5_3_1_Serialiser();
        $serialiser->Create($this->m_absolute_path, $this->GetExportName(), $this->m_smarty, $this->m_db,
            $this->m_error_function);
        $serialiser->SetData($this->m_export_record);
        $this->m_export_filename = $serialiser->Write($this->m_parameters);
        $this->m_export_record->Write($this->m_db);
    }


    /**
     * getWrapper
     *
     * @access  public
     * @return  exportWrapperKraken
     */
    public function getWrapper()
    {
        return parent::getWrapper();
    }


    /**
     * getRecordsUntil
     *
     * @access  public
     * @param   array   $records
     * @param   string  $end
     * @param   string  $field
     * @return  array
     */
    public function getRecordsUntil($records, $end, $field = 'datum')
    {
        return $this->getRecordsBetween($records, '0000-00-00', $end, $field);
    }


    /**
     * getRecordsBetween
     *
     * @access  public
     * @param   array   $records
     * @param   string  $start
     * @param   string  $end
     * @param   string  $field
     * @return  array
     */
    public function getRecordsBetween($records, $start, $end, $field = 'datum')
    {
        $filteredRecords = array();

        foreach ($records as $formName => $forms) {
            if ($forms instanceof exportWrapperKrakenModelIterator) {
                $iterator = new exportWrapperKrakenModelIterator;

                foreach ($forms as $form) {
                    if (array_key_exists($field, $form) === false) {
                        $iterator->addRecord($form);
                    } else {
                        if ($form[$field] >= $start && $form[$field] < $end) {
                            $iterator->addRecord($form);
                        }
                    }
                }

                $filteredRecords[$formName] = $iterator;
            } else {
                $filteredRecords[$formName] = $forms;
            }
        }

        return $filteredRecords;
    }


    /**
     * _getPrimaryDiseaseRecords
     *
     * @access  protected
     * @param   array   $patient
     * @return  array
     */
    protected function _getPrimaryDiseaseRecords($patient)
    {
        $dates      = array();
        $hasPrimary = false;
        $disease    = $patient['erkrankung'];
        $endDate    = '2999-01-01';

        foreach ($disease['tumorstatus'] as $record) {
            if (str_starts_with($record['anlass'], 'r') === true) {
                $dates[] = $record['datum'];
            } elseif ($record['anlass'] === 'p') {
                $hasPrimary = true;
            }
        }

        if (count($dates) > 0 && $hasPrimary === true) {
            // sort for earliest date
            sort($dates);

            $endDate = reset($dates);
        }

        $records = $this->getRecordsUntil($disease, $endDate);
        $records = $this->_manualPreparingRanges($records, '0000-00-00', $endDate);

        return $records;

    }


    /**
     * getNonInterventionalPrimaryDisease
     *
     * @access  public
     * @param   array   $patient
     * @return  array|bool
     */
    public function getNonInterventionalPrimaryDisease($patient)
    {
        if ($this->checkInterventionalPrimaryDisease($patient) === true) {
            return false;
        }

        return $this->_getPrimaryDiseaseRecords($patient);
    }


    /**
     * checkInterventionalPrimaryDisease
     *
     * @access  public
     * @param   array   $patient
     * @return  bool
     */
    public function checkInterventionalPrimaryDisease($patient)
    {
        $records = $this->_getPrimaryDiseaseRecords($patient);

        foreach ($records['therapieplan'] as $record) {
            if ($record['zeitpunkt'] === 'prae' && ($record['watchful_waiting'] === '1' || $record['active_surveillance'] === '1')) {
                return false;
                break;
            }
        }

        return true;
    }


    /**
     * getHelper
     *
     * @access  public
     * @return  Concobox_prostata_e_5_3_1_Model_Helper
     */
    public function getHelper()
    {
        return $this->_helper;
    }


    /**
     * createEmptySection
     *
     * @access  public
     * @param   array   $section
     * @return  array
     */
    public function createEmptySection($section)
    {
        return array_fill_keys(array_keys(array_flip($section)), null);
    }


    /**
     * call method by key name or create empty section if $records === false
     *
     * @access  public
     * @param   array   $sectionName
     * @param   array   $section
     * @param   array   $patient
     * @param   array   $records
     * @return  array
     */
    public function callMethods($sectionName, $section, $patient, $records)
    {
        $processedSection = array();

        if ($records === false) {
            $processedSection = $this->createEmptySection($section);
        } else {
            $helper = $this->getHelper()->getSectionHelper(substr($sectionName, 0, 1));

            foreach ($section as $sec) {
                $method = 'render' . $sectionName . $sec;

                $value = $helper->{$method}($patient, $records);

                $processedSection[$sec] = $value !== false ? $value : null;

                $helper->setCache($method, $processedSection[$sec]);
            }
        }

        return $processedSection;
    }


    /**
     * _manualPreparingRanges
     *
     * @access  protected
     * @param   array   $records
     * @param   string  $startDate
     * @param   string  $endDate
     * @return  array
     */
    protected function _manualPreparingRanges($records, $startDate, $endDate)
    {
        $konferenzPatient = array();

        foreach ($records['konferenz_patient'] as $record) {
            if (null !== $record['konferenz']) {

                if ($record['konferenz']['datum'] >= $startDate && $record['konferenz']['datum'] < $endDate) {
                    $konferenzPatient[] = $record;
                }
            }
        }
        $records['konferenz_patient'] = exportWrapperKrakenModelIterator::create()->setRecords($konferenzPatient);

        // TODO: Insert more

        return $records;
    }


    /**
     *
     *
     * @access
     * @param $disease
     * @return bool
     */
    protected function _hasProstatectomy($disease)
    {
        $prostatectomy = Concobox_prostata_e_5_3_1_Model_Helper::getProstatectomy($disease);

        if (false !== $prostatectomy) {
            return true;
        }

        return false;
    }


    /**
     * ifEmpty
     *
     * @access  public
     * @param   string  $string
     * @param   string  $string2
     * @return  string
     */
    public function ifEmpty($string, $string2)
    {
        return (($string === null || strlen($string) === 0) ? $string2 : $string);
    }
}

?>
