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
require_once('class.oncobox_darm_e_1_1_1_serialiser.php');
require_once('core/class/report/helper.reports.php');
require_once('feature/export/base/helper.common.php');
require_once('reports/scripts/d/reportExtension.php');

/**
 * Class Concobox_darm_e_1_1_1_Model
 */
class Concobox_darm_e_1_1_1_Model extends CExportDefaultModel
{
    /**
     * @access
     * @var array
     */
    protected $_diseaseSyncCounts = array();


    /**
     * @access
     * @var array
     */
    //protected $_diseases = array();


    /**
     * @access
     * @var array
     */
    protected $_gradingCodes = array();


    /**
     *
     */
    public function __construct()
    {
    }


    /**
     * Overrides from class CExportDefaultModel
     *
     * @access
     * @param $parameters
     * @param $wrapper
     * @param $export_record
     * @return void
     */
    public function ExtractData($parameters, $wrapper, &$export_record)
    {
        $stageCalc = stageCalc::create($this->m_db);

        $this->_readGradingCodes();

        $wrapper->SetRelevantFieldForTimeRangeCheck('d_bezugsdatum');
        $wrapper->SetRangeDate($parameters[ 'datum_von' ], $parameters[ 'datum_bis' ]);
        $wrapper->SetErkrankungen('d');
        //$wrapper->UsePrimaryCasesOnly();
        $wrapper->DoNotUseEkrMeldungsCheck();
        $wrapper->SetDiagnosen("diagnose LIKE 'C18%' " .
            "OR diagnose LIKE 'C19%' " .
            "OR diagnose LIKE 'C20%' " .
            "OR diagnose LIKE 'D01.0%' " .
            "OR diagnose LIKE 'D01.1%' " .
            "OR diagnose LIKE 'D01.2%' ");


        $wrapper->SetAdditionalJoins(array());

        $wrapper->SetAdditionalSelects(array(
            "p.datenspeicherung         AS 'datenspeicherung'",
            "p.datenversand             AS 'datenversand'",
            "(SELECT IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR
              MAX(ts.nur_diagnosesicherung) IS NOT NULL OR
              MAX(ts.kein_fall) IS NOT NULL, 1, NULL) FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass
            ) AS 'kein_zentrumsfall'",
            "(
                SELECT
                    MIN(ts.datum_sicherung)
                FROM
                    tumorstatus ts
                WHERE
                    ts.erkrankung_id = t.erkrankung_id AND
                    anlass = 'p'
                ORDER BY
                    ts.datum_sicherung DESC,
                    ts.sicherungsgrad ASC,
                    ts.datum_beurteilung DESC
                LIMIT 1
            )                          AS erstes_primaer"
        ));

        // DatumOperativeTumorentfernung (Grundgesamtheiten Prio 1. UND 'Resektion des Primärtumors'
        $datumOperativeTumorentfernungStmt = "
            IF (
                sit.anlass LIKE 'p',
                MIN(
                    IF (s.form = 'eingriff' AND LEFT(s.report_param, 1) = '1' AND (
                        LOCATE('5-455',   SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-456',   SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-458',   SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-484',   SUBSTRING(s.report_param, 5)) != 0 OR
                        LOCATE('5-485',   SUBSTRING(s.report_param, 5)) != 0),
                    s.form_date,
                    NULL)
                ),
                NULL
            )
        ";

        $earliestDiagnosticOpQuery = reportExtensionD::buildEarliestDiagnosticOpQuery($this->getDB(), $this->_getFilteredDiseases(), 'h_a');

        // DatumErstdiagnosePrimaertumor
        $datumErstdiagnosePrimaertumorStmt = "
            IF (sit.anlass LIKE 'r%',

                -- rezidiv
                IFNULL(
                    MIN(h_a.datum),
                    IF (sit.start_date = '0000-00-00',
                        sit.start_date_rezidiv,
                        sit.start_date
                    )
                ),

                -- primaerfall + benigne
                IFNULL(
                    IFNULL(
                        IFNULL(
                            -- Prio 1.
                            {$earliestDiagnosticOpQuery},

                            -- Prio 2.
                            MIN(
                                IF (h_a.art = 'pr' AND h_a.unauffaellig IS NULL,
                                    h_a.datum,
                                    NULL
                                )
                            )
                        ),

                        -- Prio 3.
                        (
                            SELECT
                                MIN(aufnahmedatum)

                            FROM
                                aufenthalt auf

                            WHERE
                                auf.patient_id = sit.patient_id AND auf.fachabteilung IN ('1500', '1550', '0700', '0107', 'visz')
                        )
                    ),
                    -- Prio 4.
                    sit.erstes_primaer
                )
            )
        ";

        $wrapper->SetAdditionalFields(array(
            "IFNULL(
                {$datumOperativeTumorentfernungStmt},
                {$datumErstdiagnosePrimaertumorStmt}
            )                                      AS d_bezugsdatum",
            $datumOperativeTumorentfernungStmt . " AS 'datumOperativeTumorentfernung'",
            $datumErstdiagnosePrimaertumorStmt . " AS 'datumErstdiagnosePrimaertumor'",
            "sit.patient_nr                        AS 'referenznr'",
            "sit.datenspeicherung                  AS 'datenspeicherung'",
            "sit.datenversand                      AS 'datenversand'",
            "sit.kein_zentrumsfall                 AS 'kein_zentrumsfall'"
        ));

        // add data
        $this
            ->_addSynchronDisease($wrapper)
            ->_addFamilyAnamnesisData($wrapper)
            ->_addDiagnoseData($wrapper, $stageCalc)
            ->_addAnamnesisData($wrapper)
            ->_addAnamnesisDiseaseData($wrapper)
            ->_addConference($wrapper)
            ->_addTherapyPlan($wrapper)
            ->_addTherapyPlanDeviance($wrapper)
            ->_addComplicationData($wrapper)
            ->_addTumorstatus($wrapper)
            ->_addAdvice($wrapper)
            ->_addStudy($wrapper)
            ->_addExamination($wrapper)
            ->_addAdditionalSurgicalFields($wrapper)
            ->_addAdditionalHistologyFields($wrapper)
            ->_addDepartment($wrapper)
            ->_addAfterCare($wrapper)
            ->_addClosure($wrapper)
        ;

        $result = $wrapper->GetExportData($parameters);

        $rezidiv = $this->_getRezidivTs($wrapper);

        $studyType = $this->_getStudyArtData();

        $this->_setSyncCounts($wrapper);

        foreach ($result as $extract_data) {

            $diseaseId = $extract_data['erkrankung_id'];

            if ($this->_hasSyncCount($diseaseId) === true && $extract_data['erkrankung_relevant'] !== '1') {
                continue;
            }

            $stageCalc->setSub($extract_data['erkrankung']);

            $extract_data['ct_uicc'] = $stageCalc->calc($extract_data['ct_uicc']);

            // studientyp aus vorlage_studie
            $extract_data['studientyp'] = $studyType;

            $extract_data['additional_anamnesis'] = HReports::RecordStringToArray($extract_data['additional_anamnesis'],
                array(
                    "anamnese_id",
                    "fb_dkg",
                    "fb_dkg_beurt"
                )
            );

            $extract_data['anamnesen'] = $this->_mergeData($extract_data['anamnesen'], $extract_data['additional_anamnesis'], 'anamnese_id');

            unset($extract_data['additional_anamnesis']);

            $extract_data['therapieplan'] = HReports::OrderRecordsByField(HReports::RecordStringToArray(
                $extract_data['therapieplan'],
                array(
                    "therapieplan_id",
                    "datum",
                    "grundlage",
                    "zeitpunkt",
                    "abweichung_leitlinie",
                    "palliative_versorgung",
                    "strahlen",
                    "strahlen_intention",
                    "chemo",
                    "chemo_intention",
                    "op"
                )),
                'datum',
                'DESC'
            );

            $extract_data['anamnesisDisease'] = HReports::RecordStringToArray($extract_data['anamnesisDisease'],
                array(
                    "anamnese_erkrankung_id",
                    "datum",
                    "erkrankung",
                    "morphologie",
                    "jahr"
                )
            );

            $extract_data['conference'] = HReports::RecordStringToArray($extract_data['conference'],
                array(
                    "conference"
                )
            );

            $extract_data['complication'] = HReports::RecordStringToArray($extract_data['complication'],
                array(
                    "komplikation_id",
                    "datum",
                    "eingriff_id",
                    "komplikation",
                    "reintervention",
                    "drainage_intervent",
                    "antibiotikum",
                    "drainage_transanal",
                    "revisionsoperation",
                )
            );

            $extract_data['beratung'] = HReports::RecordStringToArray($extract_data['beratung'],
                array(
                    "beratung_id",
                    "datum",
                    "psychoonkologie",
                    "psychoonkologie_dauer",
                    "sozialdienst",
                    "fam_risikosprechstunde",
                    "fam_risikosprechstunde_erfolgt",
                    "humangenet_beratung"
                )
            );

            $extract_data['diagnosis_data'] = HReports::OrderRecordsByField(
                HReports::RecordStringToArray($extract_data['diagnosis_data'],
                    array(
                        "datum",
                        "diagnose"
                    )
                ), 'datum', 'DESC'
            );


            $extract_data['studie'] = HReports::RecordStringToArray($extract_data['studie'],
                array(
                    "studie_id",
                    "beginn",
                    "vorlage_studie_id"
                )
            );
            $extract_data['studie'] = HReports::OrderRecordsByField($extract_data['studie'], 'beginn');

            $extract_data['examination'] = HReports::RecordStringToArray($extract_data['examination'],
                array(
                    "untersuchung_id",
                    "datum",
                    "art",
                    "ct_becken",
                    "mesorektale_faszie",
                    "art_text"
                )
            );
            $extract_data['examination'] = HReports::OrderRecordsByField($extract_data['examination'], 'datum');

            $extract_data['therapieplan_abweichung'] = HReports::RecordStringToArray($extract_data['therapieplan_abweichung'],
                array(
                    "therapieplan_abweichung_id",
                    "datum",
                    "therapieplan_id",
                    "bezug_strahlen",
                    "bezug_chemo",
                    "grund"
                )
            );


            $extract_data['eingriff'] = HReports::RecordStringToArray($extract_data['eingriff'],
                array(
                    "eingriff_id",
                    "operateur2_id",
                    "stomaposition",
                    "mesorektale_faszie"
                )
            );
            $extract_data['operationen'] = $this->_mergeData($extract_data['operationen'], $extract_data['eingriff'], 'eingriff_id');
            $extract_data['operationen'] = HReports::OrderRecordsByField($extract_data['operationen'], 'beginn', 'DESC');

            $extract_data['additional_histology'] = HReports::RecordStringToArray($extract_data['additional_histology'],
                array(
                    "histologie_id",
                    "art",
                    "resektionsrand_oral",
                    "unauffaellig",
                    "msi_mutation"
                )
            );
            $extract_data['alle_histologien'] = $this->_mergeData($extract_data['alle_histologien'], $extract_data['additional_histology'], 'histologie_id');
            $extract_data['alle_histologien'] = HReports::OrderRecordsByField($extract_data['alle_histologien'], 'datum', 'DESC');

            $extract_data['systemische_therapien'] = HReports::OrderRecordsByField($extract_data['systemische_therapien'], 'beginn');

            $extract_data['department'] = HReports::RecordStringToArray($extract_data['department'],
                array(
                    "aufenthalt_id",
                    "aufnahmedatum",
                    "fachabteilung",
                )
            );
            $extract_data['department'] = HReports::OrderRecordsByField($extract_data['department'], 'aufnahmedatum');


            $extract_data['nachsorge'] = HReports::RecordStringToArray($extract_data['nachsorge'],
                array(
                    "nachsorge_id",
                    "datum",
                    "malignom",
                    "org_id",
                )
            );

            $extract_data['strahlen_therapien'] = HReports::OrderRecordsByField($extract_data['strahlen_therapien'], 'beginn', 'ASC');

            $extract_data['nachsorge'] = HReports::OrderRecordsByField($extract_data['nachsorge'], 'datum');

            $extract_data['abschluss'] = HReports::RecordStringToArray($extract_data['abschluss'],
                array(
                    "abschluss_id",
                    "todesdatum",
                    "abschluss_grund",
                    "letzter_kontakt",
                    "tod_tumorassoziation",
                )
            );

            $extract_data['abschluss'] = count($extract_data['abschluss']) > 0 ? reset($extract_data['abschluss']) : array();

            $extract_data['similarity'] = $this->_getSimilaritySection($extract_data);

            // create main case
            $case = $this->CreateCase($export_record->GetDbid(), $parameters, $extract_data);

            $section = $this->createInfoXMLSection($parameters, $section_uid);
            $infoXML = $this->CreateBlock($case->GetDbid(), $parameters, 'infoXML', $section_uid, $section);
            $case->AddSection($infoXML);

            // personalData
            $section = $this->createPersonalDataSection($parameters, $extract_data, $section_uid);
            $personalData = $this->CreateBlock($case->GetDbid(), $parameters, 'personalData', $section_uid, $section);
            $case->AddSection($personalData);

            // anamnesis
            $section = $this->createAnamnesisSection($parameters, $extract_data, $section_uid);
            $anamnesis = $this->CreateBlock($case->GetDbid(), $parameters, 'anamnesis', $section_uid, $section);
            $case->AddSection($anamnesis);

            // similarity
            $section = $this->_createSimilaritySection($parameters, $extract_data, $section_uid);
            $similarity = $this->CreateBlock($case->GetDbid(), $parameters, 'similarity', $section_uid, $section);
            $case->AddSection($similarity);

            // caseInfo
            $section = $this->_createCaseInfoSection($parameters, $extract_data, $section_uid);
            $caseInfo = $this->CreateBlock($case->GetDbid(), $parameters, 'caseInfo', $section_uid, $section);
            $case->AddSection($caseInfo);

            // diagnosis
            $section = $this->createDiagnosisSection($parameters, $extract_data, $section_uid);
            $diagnosis = $this->CreateBlock($case->GetDbid(), $parameters, 'diagnosis', $section_uid, $section);
            $case->AddSection($diagnosis);

            // prae conference
            $section = $this->createPraeConferenceSection($parameters, $extract_data, $section_uid);
            $praeConference = $this->CreateBlock($case->GetDbid(), $parameters, 'praeConference', $section_uid, $section);
            $case->AddSection($praeConference);

            // endo
            $section = $this->createEndoSection($parameters, $extract_data, $section_uid);
            $endo = $this->CreateBlock($case->GetDbid(), $parameters, 'endo', $section_uid, $section);
            $case->AddSection($endo);

            // surgical
            $section = $this->_createSurgicalSection($parameters, $extract_data, $section_uid);
            $surgical = $this->CreateBlock($case->GetDbid(), $parameters, 'surgical', $section_uid, $section);
            $case->AddSection($surgical);

            // histology
            $section = $this->_createHistologySection($parameters, $extract_data, $section_uid);
            $histology = $this->CreateBlock($case->GetDbid(), $parameters, 'histology', $section_uid, $section);
            $case->AddSection($histology);

            // post conference
            $section = $this->_createPostConferenceSection($parameters, $extract_data, $section_uid);
            $postConference = $this->CreateBlock($case->GetDbid(), $parameters, 'postConference', $section_uid, $section);
            $case->AddSection($postConference);

            // liver metastasis
            $section = $this->createLiverSection($parameters, $extract_data, $section_uid);
            $liver = $this->CreateBlock($case->GetDbid(), $parameters, 'liver', $section_uid, $section);
            $case->AddSection($liver);

            // createPraeRadioTherapySection
            $section = $this->_createPraeRadioTherapySection($parameters, $extract_data, $section_uid);
            $praeRadio = $this->CreateBlock($case->GetDbid(), $parameters, 'praeRadio', $section_uid, $section);
            $case->AddSection($praeRadio);

            // createPostRadioTherapySection
            $section = $this->_createPostRadioTherapySection($parameters, $extract_data, $section_uid);
            $postRadio = $this->CreateBlock($case->GetDbid(), $parameters, 'postRadio', $section_uid, $section);
            $case->AddSection($postRadio);

            // createPraeChemoSection
            $section = $this->_createPraeChemoSection($parameters, $extract_data, $section_uid);
            $praeChemo = $this->CreateBlock($case->GetDbid(), $parameters, 'praeChemo', $section_uid, $section);
            $case->AddSection($praeChemo);

            // createPostChemoSection
            $section = $this->_createPostChemoSection($parameters, $extract_data, $section_uid);
            $postChemo = $this->CreateBlock($case->GetDbid(), $parameters, 'postChemo', $section_uid, $section);
            $case->AddSection($postChemo);

            // createBestSupportiveCareSection
            $section = $this->createBestSupportiveCareSection($parameters, $extract_data, $section_uid);
            $bestSupportiveCare = $this->CreateBlock(
                $case->GetDbid(), $parameters, 'bestSupportiveCare', $section_uid, $section
            );
            $case->AddSection($bestSupportiveCare);

            //createStudySection
            $section = $this->_createStudySection($parameters, $extract_data, $section_uid);
            $study = $this->CreateBlock($case->GetDbid(), $parameters, 'study', $section_uid, $section);
            $case->AddSection($study);

            //createFollowUpSection
            $sections = $this->createFollowUpSections($parameters, $extract_data, $rezidiv);

            foreach ($sections as $section) {
                $section_uid = $section['id'];
                $followUp = $this->CreateBlock($case->GetDbid(), $parameters, 'followUp', $section_uid, $section);
                $case->AddSection($followUp);
            }

            // Add main case
            $export_record->AddCase($case);
        }
    }


    /**
     * _getFilteredDiseases
     *
     * @access  protected
     * @return  string
     */
    protected function _getFilteredDiseases()
    {
        $result = dlookup($this->m_db,
            'erkrankung e INNER JOIN tumorstatus t ON t.erkrankung_id = e.erkrankung_id',
            "IFNULL(GROUP_CONCAT(DISTINCT e.erkrankung_id), 0)",
            "e.erkrankung = 'd'"
        );

        return $result;
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
        $section->SetMeldungskennzeichen("N");
        $section->SetDataChanged(1);
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
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Concobox_darm_e_1_1_1_Serialiser();
        $serialiser->Create($this->m_absolute_path, $this->GetExportName(), $this->m_smarty, $this->m_db,
            $this->m_error_function);
        $serialiser->SetData($this->m_export_record);
        $this->m_export_filename = $serialiser->Write($this->m_parameters);
        $this->m_export_record->Write($this->m_db);
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section_uid
     * @return array
     */
    protected function createInfoXMLSection($parameters, &$section_uid)
    {
        $infoXML = array();
        $infoXML['DatumXML']         = date("Y-m-d");
        $infoXML['NameTudokusys']    = HCommon::TrimString($parameters['sw_name'], 200, true);
        $infoXML['VersionTudokusys'] = HCommon::TrimString($parameters['sw_version'], 200, true);

        $section_uid = 'InfoXML_' . $parameters[ 'user_id' ] . '_' . $parameters[ 'org_id' ];

        return $infoXML;
    }



    protected function createPersonalDataSection($parameters, $extract_data, &$section_uid)
    {
        $personalData = array();

        $personalData['PatientID']                 = $extract_data['referenznr'];
        $personalData['GeburtsJahr']               = substr($extract_data['geburtsdatum'], 0, 4);
        $personalData['GeburtsMonat']              = substr($extract_data['geburtsdatum'], 5, 2);
        $personalData['GeburtsTag']                = substr($extract_data['geburtsdatum'], 8, 2);
        $personalData['Geschlecht']                = $extract_data['geschlecht'];
        $personalData['EinwilligungTumordoku']     = $extract_data['datenspeicherung'] === '1' ? '1' : '0';
        $personalData['EinwilligungExterneStelle'] = $extract_data['datenversand'] === '1' ? '1' : '0';

        $section_uid = 'PERSONAL_DATA_' . $this->_getUidFromData($extract_data);

        return $personalData;
    }


    /**
     * get ExtractData Section
     *
     * @access  protected
     * @param   array   $extractData
     * @param   string  $sectionName
     * @return  array
     */
    protected function _getExtSection($extractData, $sectionName)
    {
        $section = array();

        if (array_key_exists($sectionName, $extractData) === true && is_array($extractData[$sectionName]) === true) {
            $section = $extractData[$sectionName];
        }

        return $section;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function createAnamnesisSection($parameters, $extract_data, &$section_uid)
    {
        $anamnesis  = array(
            'RelevanteKrebsvorerkrankungen' => '',
            'JahrRelevanteKrebsvorerkrankungen' => '',
            'NichtRelevanteKrebsvorerkrankungen' => '',
            'JahrNichtRelevanteKrebsvorerkrankungen'=> '',
            'DKGPatientenfragebogen' => '0',
            'PositiveFamilienanamnese' => ''
        );

        $relevant = array();

        foreach ($this->_getExtSection($extract_data, 'anamnesisDisease') as $disease) {
            $diseaseVal = $disease['erkrankung'];
            $morphVal   = $disease['morphologie'];
            $mCodes     = array('809', '810', '811');

            $cond1 = str_starts_with($diseaseVal, 'C') === true && str_starts_with($diseaseVal, 'C44') === false;
            $cond2 = str_starts_with($diseaseVal, 'C44') === true && str_starts_with($morphVal, $mCodes) === false;

            if ($cond1 === true || $cond2 === true) {
                $relevant[] = $disease;
            }
        }

        $anamnesis['RelevanteKrebsvorerkrankungen'] = count($relevant) > 0 ? '1' : '0';

        $relevantYears = array();

        foreach ($relevant as $year) {
            if (strlen($year['jahr']) == 4) {
                $relevantYears[] = $year['jahr'];
            } elseif (strlen($year['jahr']) <= 2) {
                if ($year['jahr'] > date('y')) {
                    $relevantYears[] = '19' . $year['jahr'];
                } elseif (strlen($year['jahr']) == 2) {
                    $relevantYears[] = '20' . $year['jahr'];
                } elseif (strlen($year['jahr']) == 1) {
                    $relevantYears[] = '200' . $year['jahr'];
                }
            }
        }

        rsort($relevantYears);

        $anamnesis['JahrRelevanteKrebsvorerkrankungen'] = reset($relevantYears);

        $unrelevant = array();

        if (array_key_exists('anamnesisDisease', $extract_data) && count($extract_data['anamnesisDisease']) > 0) {
            $find = array('D37','D38','D39','D40','D41','D42','D43','D44','D45','D46','D47','D48');

            foreach ($extract_data['anamnesisDisease'] as $disease) {
                if (str_starts_with($disease['erkrankung'], $find) === true ||
                    (str_starts_with($disease['erkrankung'], 'C44') === true &&
                        str_starts_with($disease['morphologie'], array('809', '810', '811')) === true
                    )
                ) {
                    $unrelevant[] = $disease;
                }
            }
        }

        $anamnesis['NichtRelevanteKrebsvorerkrankungen'] = count($unrelevant) > 0 ? '1' : '0';

        $unRelevantYears = array();

        foreach ($unrelevant as $year) {
            if (strlen($year['jahr']) == 4) {
                $unRelevantYears[] = $year['jahr'];
            } elseif (strlen($year['jahr']) <= 2) {
                if ($year['jahr'] > date('y')) {
                    $unRelevantYears[] = '19' . $year['jahr'];
                } elseif (strlen($year['jahr']) == 2) {
                    $unRelevantYears[] = '20' . $year['jahr'];
                } elseif (strlen($year['jahr']) == 1) {
                    $unRelevantYears[] = '200' . $year['jahr'];
                }
            }
        }

        rsort($unRelevantYears);

        $anamnesis['JahrNichtRelevanteKrebsvorerkrankungen'] = reset($unRelevantYears);

        $questionaire = array();

        $anamnesen = $this->_getExtSection($extract_data, 'anamnesen');

        foreach ($anamnesen as $record) {
            $questionaire[] = strlen($record['fb_dkg']) > 0 ? $record['fb_dkg'] : '0';
        }

        if (count($questionaire) > 0) {
            $anamnesis['DKGPatientenfragebogen'] = max($questionaire);
        }

        $date = $extract_data['datumErstdiagnosePrimaertumor']; //$this->_getAppearanceDate($extract_data);

        $year = strlen($date) ? substr($date, 0, 4) : 2015;

        if ($year <= 2014) {
            $anamnesis['PositiveFamilienanamnese'] = $extract_data['anf'];
        } else {
            $questionaireResult = array();

            foreach ($anamnesen as $record) {
                switch ($record['fb_dkg_beurt']) {
                    case 'fbpos' : $questionaireResult[] = 1; break;
                    case 'fbneg' : $questionaireResult[] = 0; break;
                }
            }

            if (count($questionaireResult) > 0) {
                $anamnesis['PositiveFamilienanamnese'] = max($questionaireResult);
            }
        }

        $section_uid = 'ANAMNESIS_' . $this->_getUidFromData($extract_data);

        return $anamnesis;
    }


    /**
     * _createSimilaritySection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function _createSimilaritySection($parameters, $extract_data, &$section_uid)
    {
        $similarity = array();

        $similarity['Grundgesamtheiten'] = $extract_data['similarity'];

        $section_uid = 'GRUNDGESAMTHEITEN_' . $this->_getUidFromData($extract_data);

        return $similarity;
    }


    /**
     * _createCaseInfoSection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function _createCaseInfoSection($parameters, $extract_data, &$section_uid)
    {
        $caseInfo = array();

        $caseInfo['Zentrumsfall']       = strlen($extract_data['kein_zentrumsfall']) > 0 ? '0' : '1';
        $caseInfo['Organ']              = 'DZ';
        $caseInfo['RegNr']              = $parameters['zentrum_id'];
        $caseInfo['HauptNebenStandort'] = $parameters['HauptNebenStandort'];;
        $caseInfo['FallNummer']         = $extract_data['case_nr'];
        $caseInfo['EingabeFalldaten']   = $extract_data['fall_vollstaendig'] === null ? '0' : $extract_data['fall_vollstaendig'];

        $section_uid = 'CASE_INFO_' . $this->_getUidFromData($extract_data);

        return $caseInfo;
    }


    /**
     * _getAppearanceDate
     *
     * @access  protected
     * @param   array   $extractData
     * @return  mixed
     */
    /*
    protected function _getAppearanceDate($extractData)
    {
        $ops = array();

        $histologies = array_reverse($extractData['alle_histologien']);

        foreach ($extractData['operationen'] as $op) {
            if ($op['art_diagnostik'] === '1') {
                $ops[$op['eingriff_id']] = $op['beginn'];
            }
        }

        $prHistoDates = array();
        $poHistoDates = array();

        //Prio 1) Nimm das Datum des diagnostischen Eingriffs, der der frühesten Histologie mit Art
        //"Befundung von Biopsiegewebe" zugeordnet ist und in dem NICHT die Checkbox "pathologischer Befund unauffällig" ausgewählt wurde.
        foreach ($histologies as $histo) {
            if ($histo['art'] === 'pr') {
                if (strlen($histo['unauffaellig']) > 0) {
                    continue;
                }

                $prHistoDates[] = $histo['datum'];

                $opId = $histo['eingriff_id'];

                if (strlen($opId) > 0 && array_key_exists($opId, $ops) === true) {
                    return $ops[$opId];
                }
            } else {
                $poHistoDates[] = $histo['datum'];
            }
        }

        // Prio 2) Datum der frühesten Histologie mit Art "Befundung von Biopsiegewebe" in der NICHT die Checkbox "pathologischer Befund unauffällig" ausgewählt wurde.
        if (count($prHistoDates) > 0) {
            return reset($prHistoDates);
        }

        // Prio 3)  Datum des frühesten Aufenthalts mit Fachabteilung
        foreach ($extractData['department'] as $department) {
            if (in_array($department['fachabteilung'], array('1500', '1550', '0700', '0107', 'visz')) === true &&
                strlen($department['aufnahmedatum']) > 0
            ) {
                return $department['aufnahmedatum'];
            }
        }

        // Prio 4) Datum der Sicherung des frühesten Tumorstatus mit Anlass "Beurteilung Primürtumor"
        if (strlen($extractData['first_datum_sicherung']) > 0) {
            return $extractData['first_datum_sicherung'];
        }

        if (count($poHistoDates) > 0) {
            return reset($poHistoDates);
        }

        return $extractData['todesdatum'];
    }
    */


    protected function createDiagnosisSection($parameters, $extract_data, &$section_uid)
    {
        $diagnosis = array(
            'DatumHistologischeSicherung'   => '',
            'KolonRektum'                   => '',
            'TumorlokalisationRektum'       => '',
            'Tumorauspraegung'              => '',
            'UICCStadium'                   => '9',
            'MRTBecken'                     => '0',
            'CTBecken'                      => '0'
        );

        $diagnosis['DatumErstdiagnosePrimaertumor'] = $extract_data['datumErstdiagnosePrimaertumor']; //$this->_getAppearanceDate($extract_data);

        if (array_key_exists('alle_histologien', $extract_data) && count($extract_data['alle_histologien']) > 0) {
            foreach ($extract_data['alle_histologien'] as $histo) {
                if (strlen($histo['unauffaellig']) === 0) {
                    $diagnosis['DatumHistologischeSicherung'] = $histo['datum'];
                }
            }
        }

        $diagnosis['ICDOHistologieDiagnose'] = $extract_data['morphologie'];
        $diagnosis['ICDOLokalisation'] = str_replace('.', '', $extract_data['ICDOLokalisation']);

        switch (substr($extract_data['anlass'], 0, 1)) {
            case 'p':
                $diagnosis['Tumorauspraegung'] = '1';
                break;
            case 'r':
                $diagnosis['Tumorauspraegung'] = '2';
                break;
        }

        $diagnose   = null;
        $allocation = null;

        // wenn aus tumorstatus formular
        if (strlen($extract_data['diagnose']) > 0) {
            $diagnose   = $extract_data['diagnose'];
            $allocation = $extract_data['diagnose_c19_zuordnung'];
        } elseif (count($extract_data['diagnosis_data']) > 0) {
            $first = reset($extract_data['diagnosis_data']);

            $diagnose = $first['diagnose'];
        }

        $cond1 = str_starts_with($diagnose, array('C18', 'D01.0')) === true ||
            (str_starts_with($diagnose, array('C19', 'D01.1')) === true && $allocation === 'C18');
        $cond2 = $cond1 === false && (str_starts_with($diagnose, array('C20', 'D01.2')) === true ||
                (str_starts_with($diagnose, array('C19', 'D01.1')) === true && $allocation === 'C20'));

        if ($cond1 === true) {
            $diagnosis['KolonRektum'] = 'K';
        } elseif ($cond2 === true) {
            $diagnosis['KolonRektum'] = 'R';
        }


        if ($extract_data['hoehe'] >= 12 && $extract_data['hoehe'] <= 16) {
            $diagnosis['TumorlokalisationRektum'] = '1';
        } elseif ($extract_data['hoehe'] >= 6 && $extract_data['hoehe'] < 12) {
            $diagnosis['TumorlokalisationRektum'] = '2';
        } elseif ($extract_data['hoehe'] > 0 && $extract_data['hoehe'] < 6) {
            $diagnosis['TumorlokalisationRektum'] = '3';
        }

        $diagnosis['praeT'] = str_starts_with($extract_data['earliest_ct'], 'c') === true ? substr($extract_data['earliest_ct'], 1) : $extract_data['earliest_ct'];
        $diagnosis['praeN'] = str_starts_with($extract_data['earliest_cn'], 'c') === true ? substr($extract_data['earliest_cn'], 1) : $extract_data['earliest_cn'];
        $diagnosis['praeM'] = str_starts_with($extract_data['earliest_cm'], 'c') === true ? substr($extract_data['earliest_cm'], 1) : $extract_data['earliest_cm'];

        $ctUicc = $extract_data['ct_uicc'];

        switch (true) {
            case str_starts_with($ctUicc, 'IV'):    $diagnosis['UICCStadium'] = '4'; break;
            case str_starts_with($ctUicc, 'III'):   $diagnosis['UICCStadium'] = '3'; break;
            case str_starts_with($ctUicc, 'II'):    $diagnosis['UICCStadium'] = '2'; break;
            case str_starts_with($ctUicc, 'I'):     $diagnosis['UICCStadium'] = '1'; break;
        }

        $diagnosis['SynchroneBehandlungKolorektalerPrimaertumoren'] = $this->_hasSyncCount($extract_data['erkrankung_id']) === true ? '1' : '0';

        $faszie = array();

        if (count($extract_data['operationen']) > 0) {
            foreach ($extract_data['operationen'] as $op) {
                if (strlen($op['mesorektale_faszie']) > 0 ) {
                    $faszie[] = $op['mesorektale_faszie'];
                }
            }
        }

        $mrtDate = array();
        $ctDate  = array();

        if (count($extract_data['examination']) > 0) {
            foreach ($extract_data['examination'] as $exam) {
                if (str_starts_with($exam['art'], array('3-805', '3-82a', '3-e09.y')) === true) {
                    $mrtDate[] = $exam['datum'];
                }

                if ($exam['ct_becken'] === '1' && str_starts_with($exam['art'], array('3-206', '3-226', '3-e08.y', '3-e20.y')) === true) {
                    $ctDate[] = $exam['datum'];
                }

                if (strlen($exam['mesorektale_faszie']) > 0 ) {
                    $faszie[] = $exam['mesorektale_faszie'];
                }
            }
        }

        sort($mrtDate);
        sort($ctDate);
        sort($faszie);

        $therapies = array();

        foreach ($extract_data['operationen'] as $operation) {
            if ($operation['art_primaertumor'] === '1' ||
                $operation['art_lk'] === '1' ||
                $operation['art_metastasen'] === '1' ||
                $operation['art_rezidiv'] === '1' ||
                $operation['art_nachresektion'] === '1') {
                $therapies[] = $operation['beginn'];
            }
        }
        foreach (array('strahlen_therapien', 'systemische_therapien', 'sonstige_therapien') as $thType) {
            foreach ($extract_data[$thType] as $therapy) {
                $therapies[] = $therapy['beginn'];
            }
        }

        sort($therapies);

        if (count($mrtDate) > 0) {
            if (count($therapies) > 0 && reset($therapies) > reset($mrtDate)) {
                $diagnosis['MRTBecken'] = '1';
            }
        }

        if (count($ctDate) > 0) {
            if (count($therapies) > 0 && reset($therapies) > reset($ctDate)) {
                $diagnosis['CTBecken'] = '1';
            }
        }

        $diagnosis['AbstandFaszie'] = reset($faszie);

        $section_uid = 'DIAGNOSIS_' . $this->_getUidFromData($extract_data);

        return $diagnosis;
    }


    /**
     * createPraeConferenceSection
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function createPraeConferenceSection($parameters, $extract_data, &$section_uid)
    {
        $association    = '0';
        $recommendation = '';

        foreach ($extract_data['conference'] as $conference) {
            if (str_contains($conference['conference'], 'prae') === true) {
                $association = '1';
                break;
            }
        }

        foreach ($extract_data['therapieplan'] as $therapyplan) {
            if ($therapyplan['zeitpunkt'] === 'prae' && $therapyplan['grundlage'] === 'tk') {
                $association = '1';

                if ($therapyplan['abweichung_leitlinie'] === '1') {
                    $recommendation = '0';
                } elseif ($therapyplan['abweichung_leitlinie'] === '0') {
                    $recommendation = '1';
                }

                break;
            }
        }

        $praeConference = array(
            'VorstellungPraetherapeutischeTumorkonferenz' => $association,
            'EmpfehlungPraetherapeutischeTumorkonferenz'  => $recommendation
        );

        $section_uid = 'PRAE_CONFERENCE' . $this->_getUidFromData($extract_data);

        return $praeConference;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function createEndoSection($parameters, $extract_data, &$section_uid)
    {
        $endo = array();

        $endo['DatumTherapeutischeKoloskopie'] = '';
        $endo['OPSCodeEndoskopischePrimaertherapie'] = '';

        $code = false;

        $diagnose = $this->_getDiagnoseSection($extract_data);

        if ($extract_data['similarity'] === '2' && count($extract_data['operationen']) > 0) {
            foreach ($extract_data['operationen'] as $op) {
                if ($op['art_primaertumor'] === '1' || $op['art_rezidiv'] === '1') {
                    $endo['DatumTherapeutischeKoloskopie'] = $op['beginn'];
                    foreach ($op['ops_codes'] as $codes) {
                        $code1 = substr($codes['prozedur'], 0, 5) === '5-452' ? $codes['prozedur'] : false;
                        $code2 = substr($codes['prozedur'], 0, 5) === '5-482' ? $codes['prozedur'] : false;

                        if (strlen($code1) > 0 && strlen($code2) > 0){
                            switch ($diagnose) {
                                case 'C18':
                                    $code = $code1;
                                    break;
                                case 'C20':
                                    $code = $code2;
                                    break;
                            }
                        } elseif (strlen($code1) > 0 && strlen($code2) === 0){
                            $code = $code1;
                        } elseif (strlen($code2) > 0 && strlen($code1) === 0){
                            $code = $code2;
                        }
                    }
                }
            }

            $endo['OPSCodeEndoskopischePrimaertherapie'] = $code;
        }

        $section_uid = 'ENDO_' . $this->_getUidFromData($extract_data);

        return $endo;
    }


    /**
     * _createSurgicalSection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function _createSurgicalSection($parameters, $extract_data, &$section_uid)
    {
        $opsWithPrimaryOrRezidiv = array();
        $revisionOps             = array();

        $surgical = array(
            'ASAKlassifikation'                                   => '',
            'DatumOperativeTumorentfernung'                       => '',
            'OPSCodesChirurgischePrimaertherapie'                 => '',
            'NotfallOderElektiveingriff'                          => '',
            'Erstoperateur'                                       => '',
            'Zweitoperateur'                                      => '',
            'AnastomoseDurchgefuehrt'                             => '',
            'TMEDurchgefuehrt'                                    => '',
            'PostoperativeWundinfektion'                          => '',
            'AufgetretenAnastomoseninsuffizienz'                  => '',
            'AnastomoseninsuffizienzInterventionspflichtig'       => '',
            'Revisionseingriff'                                   => '',
            'DatumPostoperativeWundinfektion'                     => '',
            'DatumInterventionspflichtigeAnastomoseninsuffizienz' => '',
            'DatumRevisionseingriff'                              => '',
            'OPmitStoma'                                          => '',
            'Stomaangezeichnet'                                   => ''
        );

        if ($extract_data['similarity'] === '1') {
            $anastomosis    = false;
            $tmeProcessed   = '';
            $asa            = '9';
            $opDate         = '';
            $emergency      = '';
            $firstOperator  = '';
            $primaryOpsCode = '';
            $secondOperator = '';

            $tme        = false;
            $pme        = false;
            $op         = false;

            foreach ($extract_data['operationen'] as $operation) {

                // filter all op with art_primaertumor or art_rezidiv filled (needed for field "Revisionseingriff")
                if ($operation['art_primaertumor'] === '1' || $operation['art_rezidiv'] === '1') {
                    $opsWithPrimaryOrRezidiv[] = $operation;
                }
                // filter all op with art_revision (needed for field "Revisionseingriff")
                if ($operation['art_revision'] === '1') {
                    $revisionOps[] = $operation;
                }

                if ($operation['art_primaertumor'] === '1') {
                    $op = true;

                    $tmeProcessed = '3';
                    $opDate = $operation['beginn'];

                    if ($operation['tme'] === '1') {
                        $tme = true;
                    }

                    if ($operation['pme'] === '1') {
                        $pme = true;
                    }

                    switch ($operation['asa']) {
                        case '6': $asa = ''; break;
                        default :
                            if (strlen($operation['asa']) > 0) {
                                $asa = $operation['asa'];
                            }
                            break;
                    }

                    $emergency = 'E';

                    if ($operation['notfall'] === '1') {
                        $emergency = 'N';
                    }

                    $firstOperator  = $operation['operateur1_id'];
                    $secondOperator = $operation['operateur2_id'];

                    $procedure = '';
                    $prio1 = false;
                    $prio2 = false;

                    $diagnose = $this->_getDiagnoseSection($extract_data);

                    // only one ops code allowed
                    foreach ($operation['ops_codes'] as $code) {
                        if ($diagnose === 'C20' || $diagnose === 'D01.2') {
                            if (substr($code['prozedur'], 0, 7) === '5-456.1') {
                                $procedure = $code['prozedur'];
                                $prio1 = true;
                            } elseif (substr($code['prozedur'], 0, 5) === '5-485' && $prio1 === false) {
                                $procedure = $code['prozedur'];
                                $prio2 = true;
                            } elseif (substr($code['prozedur'], 0, 5) === '5-484' && $prio1 === false && $prio2 === false) {
                                $procedure = $code['prozedur'];
                            }
                        } elseif ($diagnose === 'C18' || $diagnose === 'D01.1') {
                            if (substr($code['prozedur'], 0, 5) === '5-458') {
                                $procedure = $code['prozedur'];
                                $prio1 = true;
                            } elseif (substr($code['prozedur'], 0, 5) === '5-456' && $prio1 === false) {
                                $procedure = $code['prozedur'];
                                $prio2 = true;
                            } elseif (substr($code['prozedur'], 0, 5) === '5-455' && $prio1 === false && $prio2 === false) {
                                $procedure = $code['prozedur'];
                            }
                        }

                        if ($procedure === '') {
                            $procedure = $code['prozedur'];
                        }

                        $primaryOpsCode = $procedure;

                        $anastomosis = $this->_checkAnastomosis($code['prozedur'], $anastomosis);
                    }
                    break; //only 1 primary allowed
                }
            }

            if ($op === true && $tme === true) { // includes condition 2
                $tmeProcessed = '1';
            } elseif ($op === true && $pme === true) {
                $tmeProcessed = '2';
            }

            $surgical['ASAKlassifikation']                              = $asa;
            $surgical['DatumOperativeTumorentfernung']                  = $extract_data['datumOperativeTumorentfernung']; //$opDate;
            $surgical['OPSCodesChirurgischePrimaertherapie']            = $primaryOpsCode;
            $surgical['NotfallOderElektiveingriff']                     = $emergency;
            $surgical['Erstoperateur']                                  = $firstOperator;
            $surgical['Zweitoperateur']                                 = $secondOperator;
            $surgical['AnastomoseDurchgefuehrt']                        = (string) ((int) $anastomosis);
            $surgical['TMEDurchgefuehrt']                               = $tmeProcessed;
            $surgical['PostoperativeWundinfektion']                     = '0';
            $surgical['AufgetretenAnastomoseninsuffizienz']             = '0';
            $surgical['AnastomoseninsuffizienzInterventionspflichtig']  = '';
            $surgical['Revisionseingriff']                              = '0';

            $revisionOpDates  = array();
            $insuOpDates      = array();
            $wuinOpDates      = array();

            $diagnose = $this->_getDiagnoseSection($extract_data);

            foreach ($extract_data['operationen'] as $operation) {

                $complicationWithRevisionOperation = null;
                foreach ($extract_data['complication'] as $complication) {
                    if ('1' == $complication['revisionsoperation']) {
                        $complicationWithRevisionOperation = $complication;
                    }

                    $dateDiff = date_diff_days($operation['beginn'], $complication['datum']);
                    if ($dateDiff >= 0 && $dateDiff <= 30) {
                        $compl          = $complication['komplikation'];
                        $reintervention = $complication['reintervention'];
                        $revisionOp     = null;

                        if ($operation['art_primaertumor'] === '1' || $operation['art_rezidiv'] === '1') {
                            if ($complication['revisionsoperation'] == 1) {
                                $revisionOp = 1;
                            }
                        }

                        if ($complication['eingriff_id'] === $operation['eingriff_id']) {
                            if (in_array($compl, array('wi', 'wa1', 'wa2', 'wa3', 'wctc2')) === true) {
                                if ($reintervention === '1') {
                                    $surgical['PostoperativeWundinfektion'] = '1';
                                    $wuinOpDates[] = $complication['datum'];
                                }
                            }

                            if (in_array($compl, array('ani', 'aninr')) === true) {
                                $surgical['AufgetretenAnastomoseninsuffizienz'] = '1';
                                if ($compl === 'aninr' || ($compl === 'ani' && $reintervention === '0') === true) {
                                    $surgical['AnastomoseninsuffizienzInterventionspflichtig'] = 'A';
                                } elseif ($diagnose === 'C20' || $diagnose === 'D01.2') {
                                    if ($revisionOp === 1) {
                                        $surgical['AnastomoseninsuffizienzInterventionspflichtig'] = 'C';
                                        $insuOpDates[] = $complication['datum'];
                                    } elseif (in_array('1', array($complication['antibiotikum'], $complication['drainage_intervent'], $complication['drainage_transanal'])) === true) {
                                        $surgical['AnastomoseninsuffizienzInterventionspflichtig'] = 'B';
                                        $insuOpDates[] = $complication['datum'];
                                    }
                                } elseif ((str_starts_with($diagnose, 'C18') === true || $diagnose === 'D01.0' || $diagnose === 'C19') &&  $reintervention === '1') {
                                    $surgical['AnastomoseninsuffizienzInterventionspflichtig'] = 'D';
                                    $insuOpDates[] = $complication['datum'];
                                }
                            }
                        }
                    }
                }
                if ($operation['art_primaertumor'] === '1' || $operation['art_rezidiv'] === '1') {
                    if (null !== $complicationWithRevisionOperation) {
                        $dateDiff = date_diff_days($operation['beginn'], $complicationWithRevisionOperation['datum']);
                        if ($dateDiff >= 0 && $dateDiff <= 30) {
                            $surgical['Revisionseingriff'] = '1';
                        }
                    }
                    foreach ($revisionOps as $revOp) {
                        $dateDiff = date_diff_days($operation['beginn'], $revOp['beginn']);
                        if ($dateDiff >= 0 && $dateDiff <= 30) {
                            $surgical['Revisionseingriff'] = '1';
                            $revisionOpDates[] = $revOp['beginn'];
                        }
                    }
                }
            }

            if (count($revisionOpDates) > 0) {
                sort($revisionOpDates);
                $surgical['DatumRevisionseingriff'] = array_shift($revisionOpDates);
            }

            sort($wuinOpDates);
            sort($insuOpDates);

            $surgical['DatumPostoperativeWundinfektion'] = count($wuinOpDates) > 0 ? reset($wuinOpDates) : '';
            $surgical['DatumInterventionspflichtigeAnastomoseninsuffizienz'] = count($insuOpDates) > 0 ? reset($insuOpDates) : '';

            $stoma      = false;
            $stomaDrawn = false;
            foreach ($extract_data['operationen'] as $operation) {
                if ($operation['stomaposition'] === '1') {
                    $stomaDrawn = true;
                }
                foreach ($operation['ops_codes'] as $code) {
                    $stoma = $this->_checkStoma($code['prozedur'], $stoma);
                }
            }
            $surgical['OPmitStoma']        = (string) ((int) $stoma);
            $surgical['Stomaangezeichnet'] = (string) ((int) $stomaDrawn);
        }

        $section_uid = 'SURGICAL_' . $this->_getUidFromData($extract_data);

        return $surgical;
    }


    /**
     *
     *
     * @access
     * @param $procedure
     * @param $anastomsis
     * @return bool
     */
    protected function _checkAnastomosis($procedure, $anastomsis)
    {
        if ($anastomsis === true) {
            return $anastomsis;
        }

        $cond1 = str_starts_with($procedure, '5-455') === true && str_ends_with($procedure, array('1','4','5')) === true;
        $cond2 = $cond1 === false && str_starts_with($procedure, '5-456') === true && str_ends_with($procedure, array('1','2','3','4','5', '6')) === true;
        $cond3 = $cond2 === false && str_starts_with($procedure, '5-458') === true && str_ends_with($procedure, array('1','4','5')) === true;
        $cond4 = $cond3 === false && str_starts_with($procedure, '5-484') === true && str_ends_with($procedure, array('1','5')) === true;
        $cond5 = $cond4 === false && str_starts_with($procedure, array('5-459.2', '5-459.3', '5-e00.y', '5-e01.y', '5-e02.y')) === true;

        return ($cond1 === true || $cond2 === true || $cond3 === true || $cond4 === true || $cond5 === true);
    }


    /**
     *
     *
     * @access
     * @param $procedure
     * @param $stoma
     * @return bool
     */
    protected function _checkStoma($procedure, $stoma)
    {
        if ($stoma === true) {
            return $stoma;
        }

        $cond1 = str_starts_with($procedure, '5-455') === true && str_ends_with($procedure, array('2','3','4','6')) === true;
        $cond2 = $cond1 === false && str_starts_with($procedure, '5-456') === true && str_ends_with($procedure, array('0','7')) === true;
        $cond3 = $cond2 === false && str_starts_with($procedure, '5-458') === true && str_ends_with($procedure, array('2','3','4')) === true;
        $cond4 = $cond3 === false && str_starts_with($procedure, array('5-460', '5-461', '5-462', '5-463')) === true;
        $cond5 = $cond4 === false && str_starts_with($procedure, array('5-e03.y', '5-e04.y', '5-e05.y', '5-e06.y', '5-e07.y')) === true;
        $cond6 = $cond5 === false && str_starts_with($procedure, '5-484') === true && str_ends_with($procedure, array('2','6')) === true;
        $cond7 = $cond6 === false && str_starts_with($procedure, '5-485') === true;

        return ($cond1 === true || $cond2 === true || $cond3 === true || $cond4 === true || $cond5 === true || $cond6 === true || $cond7 === true);
    }


    /**
     * _createHistologySection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function _createHistologySection($parameters, $extract_data, &$section_uid)
    {
        $histology = array();

        $similarity = $extract_data['similarity'];

        $m = '';

        if (str_starts_with($extract_data['pT_m'], 'p')) {
            $m = substr($extract_data['pT_m'], 1);
        } elseif (str_starts_with($extract_data['pT_m'], 'c') && $similarity === '1') {
            $m = substr($extract_data['pT_m'], 1);
        }

        $histology['pT']                                 = substr($extract_data['t_postop'], 1);
        $histology['pN']                                 = substr($extract_data['n_postop'], 1);
        $histology['postM']                              = $m;
        $histology['Grading']                            = $this->_getGradingBez($extract_data['pT_g']);
        $histology['ICDOHistologiePostoperative']        = $extract_data['pT_morphologie'];
        $histology['PSRLokalNachAllenOPs']               = ($similarity === '1' ? ('R' . $extract_data['pT_r_lokal']) : '');
        $histology['PSRGesamtNachPrimaertherapie']       = 'R' . $extract_data['pT_r'];
        $histology['AnzahlDerUntersuchtenLymphknoten']   = $extract_data['pT_lk_entf'];

        $aat = '0';
        $azt = '0';

        $mesorektum = null;

        foreach ($extract_data['alle_histologien'] as $histo) {
            if ($histo['art'] === 'po') {
                if (strlen($histo['mercury']) > 0 && $mesorektum === null) {
                    $mesorektum = $histo['mercury'];
                }

                if (strlen($histo['resektionsrand_aboral']) > 0) {
                    $aat = '1';
                }

                if (strlen($histo['resektionsrand_aboral']) > 0 &&
                    strlen($histo['resektionsrand_lateral']) > 0 &&
                    strlen($histo['resektionsrand_oral']) > 0
                ) {
                    $azt = '1';
                }
            }
        }

        $histology['AbstandAboralerTumorrand']           = ($similarity === '1' ? $aat : '');
        $histology['AbstandZirkumferentiellerTumorrand'] = ($similarity === '1' ? $azt : '');
        $histology['GueteDerMesorektumresektion'] = ($similarity === '1' ? ($mesorektum !== null ? $mesorektum : '4') : '');

        $section_uid = 'HISTOLOGY_' . $this->_getUidFromData($extract_data);

        return $histology;
    }


    /**
     * createPostConferenceSection
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function _createPostConferenceSection($parameters, $extract_data, &$section_uid)
    {
        $association    = '0';
        $recommendation = '';

        foreach ($extract_data['conference'] as $conference) {
            if (str_contains($conference['conference'], 'post') === true) {
                $association = '1';
                break;
            }
        }

        foreach ($extract_data['therapieplan'] as $therapyplan) {
            if ($therapyplan['zeitpunkt'] === 'post' && $therapyplan['grundlage'] === 'tk') {
                $association = '1';

                if ($therapyplan['abweichung_leitlinie'] === '1') {
                    $recommendation = '0';
                } elseif ($therapyplan['abweichung_leitlinie'] === '0') {
                    $recommendation = '1';
                }

                break;
            }
        }

        $postConference = array(
            'VorstellungPostoperativeTumorkonferenz' => $association,
            'EmpfehlungPostoperativeTumorkonferenz'  => $recommendation
        );

        $section_uid = 'POST_CONFERENCE_' . $this->_getUidFromData($extract_data);

        return $postConference;
    }


    /**
     * createLiverSection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function createLiverSection($parameters, $extract_data, &$section_uid)
    {
        $liver = array(
            'PrimaereLebermetastasenresektion'   => '',
            'BedingungenSenkundaereLebermetastasenresektion' => '',
            'SekundaereLebermetastasenresektion' => ''
        );

        $earliestTumorstatusId = $extract_data['earliest_tumorstatus_id'];
        $c22Exists             = false;
        $earliestTsC22Exists   = false;
        $earliestTsOtherExists = false;

        foreach ($extract_data['metastasen_lokalisationen_alle'] as $tsId => $tsMetastasis) {
            foreach ($tsMetastasis as $metastasis) {
                if (str_starts_with($metastasis['lokalisation'], 'C22.0') === true) {
                    $c22Exists = true;
                }

                if ($metastasis['tumorstatus_id'] == $earliestTumorstatusId) {
                    if (str_starts_with($metastasis['lokalisation'], 'C22.0') === true) {
                        $earliestTsC22Exists = true;
                    } else {
                        $earliestTsOtherExists = true;
                    }
                }
            }
        }

        $liver['LebermetastasenVorhanden']       = $c22Exists === true ? '1' : '0';
        $liver['LebermetastasenAusschliesslich'] = $earliestTsC22Exists === true && $earliestTsOtherExists === false
            ? '1'
            : '0'
        ;

        // Fall ist ein operativer Primärfall (Grundgesamtheit = Code 1):
        if ($extract_data['similarity'] === '1') {

            $latestLiverOpDates   = array();
            $latestLiverOpDate    = null;
            $conditionSecondary   = '0';
            $primaryLiver         = '0';
            $secondaryLiver       = '0';

            // iterate over each op and get date from ops with required codes
            foreach ($extract_data['operationen'] as $ops) {
                foreach($ops['ops_codes'] as $code) {
                    if (str_starts_with($code['prozedur'], array('5-501', '5-502', '5-509')) === true) {
                        $latestLiverOpDates[] = $ops['beginn'];

                        // only one date per op
                        continue 2;
                    }
                }
            }

            // sort liver op dates and get earliest
            if (count($latestLiverOpDates) > 0) {
                $latestLiverOpDates = array_unique($latestLiverOpDates);

                sort($latestLiverOpDates);

                $latestLiverOpDate = reset($latestLiverOpDates);
            }

            // go further only when op exists
            if ($latestLiverOpDate !== null) {

                $preChemoTherapyExists = false;

                // check pre chemo therapies
                foreach ($extract_data['systemische_therapien'] as $sysTherapy) {
                    if (in_array($sysTherapy['vorlage_therapie_art'], array('ci', 'cst', 'c')) === true &&
                        $sysTherapy['metastasentherapie'] === '1'
                    ) {

                        if ($c22Exists === true) {
                            $conditionSecondary = '1';
                        }

                        // therapy exists and is before latest liver op date
                        if ($sysTherapy['beginn'] < $latestLiverOpDate) {
                            $preChemoTherapyExists = true;
                            $secondaryLiver = '1';
                            break;
                        }
                    }
                }

                // if no praeop chemo exists, it's a primary liver resection
                if ($preChemoTherapyExists === false) {
                    $primaryLiver = '1';
                }
            }

            $liver['PrimaereLebermetastasenresektion']               = $primaryLiver;
            $liver['BedingungenSenkundaereLebermetastasenresektion'] = $conditionSecondary;
            $liver['SekundaereLebermetastasenresektion']             = $secondaryLiver;
        }

        $section_uid = 'LIVER_' . $this->_getUidFromData($extract_data);

        return $liver;
    }


    /**
     *
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function _createPraeRadioTherapySection($parameters, $extract_data, &$section_uid)
    {
        $praeRadio = array(
            'EmpfehlungPraeoperativeStrahlentherapie'                    => '',
            'DatumEmpfehlungPraeoperativeStrahlentherapie'               => '',
            'TherapiezeitpunktPraeoperativeStrahlentherapie'             => '',
            'TherapieintentionPraeoperativeStrahlentherapie'             => '',
            'GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie' => '',
            'DatumBeginnPraeoperativeStrahlentherapie'                   => '',
            'DatumEndePraeoperativeStrahlentherapie'                     => '',
            'GrundDerBeendigungDerPraeoperativeStrahlentherapie'         => ''
        );

        $sortedTherapyplan = $this->_sortArray($extract_data['therapieplan'], 'datum');

        $therapyRecommendation = $this->_getPraeTherapyRecommendation($extract_data['similarity'], $sortedTherapyplan, 'strahlen');

        $praeRadio['EmpfehlungPraeoperativeStrahlentherapie']      = $therapyRecommendation['recommendation'];
        $praeRadio['DatumEmpfehlungPraeoperativeStrahlentherapie'] = $therapyRecommendation['recommendation_date'];
        $tpId = $therapyRecommendation['therapieplan_id'];


        $praeRadio['GruendeFuerNichtdurchfuehrungPraeoperativeStrahlentherapie'] = $this->_getTherapyDeviationReason($extract_data, $tpId, 'strahlen');

        $radioTherapys   = array();
        $therapysWithTherapyplan = array();
        foreach ($extract_data['strahlen_therapien'] as $therapy) {
            if (in_array($therapy['intention'], array('kurna', 'palna')) === true) {
                $praeRadio['TherapiezeitpunktPraeoperativeStrahlentherapie'] = 'N';
                if (strlen($therapy['therapieplan_id']) > 0){
                    $therapysWithTherapyplan[] = $therapy;
                }
            }

            if (in_array($therapy['intention'], array('kur', 'kurna', 'palna', 'pal')) === true) {
                $radioTherapys[] = $therapy;
            }
        }

        foreach ($extract_data['systemische_therapien'] as $therapy) {
            if (in_array($therapy['vorlage_therapie_art'], array('st', 'sonstr', 'cst', 'ist', 'ahst')) === true) {
                if (in_array($therapy['intention'], array('kurna', 'palna')) === true) {
                    $praeRadio['TherapiezeitpunktPraeoperativeStrahlentherapie'] = 'N';
                    if (strlen($therapy['therapieplan_id']) > 0) {
                        $therapysWithTherapyplan[] = $therapy;
                    }
                }

                if (in_array($therapy['intention'], array('kur', 'kurna', 'palna', 'pal')) === true) {
                    $radioTherapys[] = $therapy;
                }
            }
        }

        if (count($radioTherapys) > 0) {
            $earliestRadioTherapy = reset($this->_sortArray($radioTherapys, 'beginn'));

            if (strlen($earliestRadioTherapy['beginn']) > 0 && count($sortedTherapyplan) > 0) {
                foreach ($sortedTherapyplan as $therapyplan) {
                    if ($earliestRadioTherapy['therapieplan_id'] === $therapyplan['therapieplan_id']) {
                        $praeRadio['TherapieintentionPraeoperativeStrahlentherapie'] = $this->_checkIntensionFromTherapyplan($therapyplan, 'strahlen');
                    }
                }

                if ($praeRadio['TherapieintentionPraeoperativeStrahlentherapie'] == '') {
                    $praeTherapy = $this->_getFirstFormWithFilledField($extract_data['therapieplan'], 'datum', 'zeitpunkt', 'prae');
                    $praeRadio['TherapieintentionPraeoperativeStrahlentherapie'] = $this->_checkIntensionFromTherapyplan($praeTherapy, 'strahlen');
                }
            }

            $praeRadio['DatumBeginnPraeoperativeStrahlentherapie'] = $earliestRadioTherapy['beginn'];
            $praeRadio['DatumEndePraeoperativeStrahlentherapie']   = $earliestRadioTherapy['ende'];

            $end       = $earliestRadioTherapy['endstatus'];
            $reason    = $earliestRadioTherapy['endstatus_grund'];
            $reasonEnd = '';

            switch (true) {
                case $end === 'plan':                       $reasonEnd = '1'; break;
                case $reason === 'patw':                    $reasonEnd = '0'; break;
                case in_array($reason, array('hn', 'nhn')): $reasonEnd = '2'; break;
                case strlen($reason) > 0:                   $reasonEnd = '3'; break;
            }

            $praeRadio['GrundDerBeendigungDerPraeoperativeStrahlentherapie'] = $reasonEnd;
        }

        $section_uid = 'PRAE_RADIO' . $this->_getUidFromData($extract_data);

        return $praeRadio;
    }


    protected function _getPraeTherapyRecommendation($similarity, $therapyplans, $type)
    {
        $result = array(
            'recommendation'      => '',
            'recommendation_date' => '',
            'therapieplan_id'      => ''
        );

        if ($similarity === '1') {
            foreach ($therapyplans as $therapyplan) {
                if ($therapyplan['zeitpunkt'] === 'prae') {
                    if ($therapyplan[$type] === '1') {

                        return array(
                            'recommendation'      => '1',
                            'recommendation_date' => $therapyplan['datum'],
                            'therapieplan_id'      => $therapyplan['therapieplan_id']
                        );

                    } else {
                        $result = array(
                            'recommendation'      => '0',
                            'recommendation_date' => '',
                            'therapieplan_id'      => ''
                        );
                    }
                }
            }
        } elseif ($similarity === '3' || $similarity === '4') {
            foreach ($therapyplans as $therapyplan) {
                if ($therapyplan['zeitpunkt'] === 'prae' ) {
                    if ($therapyplan[$type] === '1') {
                        if ($therapyplan['op'] !== '1') {

                            return array(
                                'recommendation'      => '1',
                                'recommendation_date' => $therapyplan['datum'],
                                'therapieplan_id'      => $therapyplan['therapieplan_id']
                            );
                        }
                    } else {
                        $result = array(
                            'recommendation'      => '0',
                            'recommendation_date' => '',
                            'therapieplan_id'      => ''
                        );

                    }
                }
            }
        }

        return $result;
    }



    /**
     * _getTherapyDeviationReason
     *
     * @access  protected
     * @param   array   $extractData
     * @param   string  $tpId
     * @param   string  $type
     * @return  string
     */
    protected function _getTherapyDeviationReason($extractData, $tpId, $type)
    {
        $reason = '';

        if (strlen($tpId) > 0) {
            $rawReasons = array();

            foreach ($extractData['therapieplan_abweichung'] as $deviance) {
                if ($deviance['therapieplan_id'] == $tpId && $deviance["bezug_{$type}"] === '1') {
                    $rawReasons[] = $deviance['grund'];
                }
            }

            if (in_array('tod', $rawReasons) === true) {
                $reason = '2';
            } else if (in_array('patw', $rawReasons) === true) {
                $reason = '1';
            } else if (count($rawReasons) > 0) {
                $reason = '3';
            }
        }

        return $reason;
    }


    /**
     *
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function _createPraeChemoSection($parameters, $extract_data, &$section_uid)
    {
        $praeChemo = array(
            'EmpfehlungPraeoperativeChemotherapie'                    => '',
            'DatumEmpfehlungPraeoperativeChemotherapie'               => '',
            'TherapiezeitpunktPraeoperativeChemotherapie'             => '',
            'TherapieintentionPraeoperativeChemotherapie'             => '',
            'GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie' => '',
            'DatumBeginnPraeoperativeChemotherapie'                   => '',
            'DatumEndePraeoperativeChemotherapie'                     => '',
            'GrundDerBeendigungDerPraeoperativeChemotherapie'         => ''
        );


        $sortedTherapyplan = $this->_sortArray($extract_data['therapieplan'], 'datum');

        $therapyRecommendation = $this->_getPraeTherapyRecommendation($extract_data['similarity'], $sortedTherapyplan, 'chemo');

        $praeChemo['EmpfehlungPraeoperativeChemotherapie']      = $therapyRecommendation['recommendation'];
        $praeChemo['DatumEmpfehlungPraeoperativeChemotherapie'] = $therapyRecommendation['recommendation_date'];
        $tpId                                                   = $therapyRecommendation['therapieplan_id'];

        $praeChemo['GruendeFuerNichtdurchfuehrungPraeoperativeChemotherapie'] = $this->_getTherapyDeviationReason($extract_data, $tpId, 'chemo');

        $chemoTherapys = array();

        foreach ($extract_data['systemische_therapien'] as $sysTherapy) {
            if (in_array($sysTherapy['vorlage_therapie_art'], array('ci', 'cst', 'c')) === true) {
                if (in_array($sysTherapy['intention'], array('kurna', 'palna')) === true) {
                    $praeChemo['TherapiezeitpunktPraeoperativeChemotherapie'] = 'N';
                }

                if (in_array($sysTherapy['intention'], array('kur', 'kurna', 'palna', 'pal')) === true) {
                    $chemoTherapys[] = $sysTherapy;
                }
            }
        }

        $earliestChemoTherapy = count($chemoTherapys) > 0 ? reset($chemoTherapys) : null;

        if ($earliestChemoTherapy !== null) {

            if (strlen($earliestChemoTherapy['beginn']) > 0 && count($sortedTherapyplan) > 0) {
                foreach ($sortedTherapyplan as $therapyplan) {
                    if ($earliestChemoTherapy['therapieplan_id'] === $therapyplan['therapieplan_id']) {
                        $praeChemo['TherapieintentionPraeoperativeChemotherapie'] = $this->_checkIntensionFromTherapyplan($therapyplan, 'chemo');
                    }
                }

                if ($praeChemo['TherapieintentionPraeoperativeChemotherapie'] == '') {
                    $praeTherapy = $this->_getFirstFormWithFilledField($extract_data['therapieplan'], 'datum', 'zeitpunkt', 'prae');
                    $praeChemo['TherapieintentionPraeoperativeChemotherapie'] = $this->_checkIntensionFromTherapyplan($praeTherapy, 'chemo');
                }
            }

            $praeChemo['DatumBeginnPraeoperativeChemotherapie'] = $earliestChemoTherapy['beginn'];
            $praeChemo['DatumEndePraeoperativeChemotherapie']   = $earliestChemoTherapy['ende'];

            $end       = $earliestChemoTherapy['endstatus'];
            $reason    = $earliestChemoTherapy['endstatus_grund'];
            $reasonEnd = '';

            switch (true) {
                case $end === 'plan':                       $reasonEnd = '1'; break;
                case $reason === 'patw':                    $reasonEnd = '0'; break;
                case in_array($reason, array('hn', 'nhn')): $reasonEnd = '2'; break;
                case strlen($reason) > 0:                   $reasonEnd = '3'; break;
            }

            $praeChemo['GrundDerBeendigungDerPraeoperativeChemotherapie'] = $reasonEnd;
        }

        $section_uid = 'PRAE_CHEMO' . $this->_getUidFromData($extract_data);

        return $praeChemo;
    }


    /**
     * createPostRadioTherapySection
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function _createPostRadioTherapySection($parameters, $extract_data, &$section_uid)
    {
        $postRadio = array(
            'EmpfehlungPostoperativeStrahlentherapie'                       => '',
            'DatumEmpfehlungPostoperativeStrahlentherapie'                  => '',
            'TherapiezeitpunktPostoperativeStrahlentherapie'                => '',
            'TherapieintentionPostoperativeStrahlentherapie'                => '',
            'GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie'    => '',
            'DatumBeginnPostoperativeStrahlentherapie'                      => '',
            'DatumEndePostoperativeStrahlentherapie'                        => '',
            'GrundDerBeendigungDerPostoperativeStrahlentherapie'            => ''
        );


        $therapysWithTherapyplan = array();

        $sortedRadioTherapys = $this->_sortArray($extract_data['strahlen_therapien'], 'beginn');

        foreach ($sortedRadioTherapys as $therapy) {
            if (in_array($therapy['intention'], array('kura', 'pala')) === true) {
                $postRadio['TherapiezeitpunktPostoperativeStrahlentherapie'] = 'A';

                if ($postRadio['DatumBeginnPostoperativeStrahlentherapie'] === '') {
                    $postRadio['DatumBeginnPostoperativeStrahlentherapie'] = $therapy['beginn'];
                    $postRadio['DatumEndePostoperativeStrahlentherapie'] = $therapy['ende'];

                    $reason    = $therapy['endstatus_grund'];
                    $reasonEnd = '';

                    switch (true) {
                        case $therapy['endstatus'] === 'plan':      $reasonEnd = '1'; break;
                        case $reason === 'patw':                    $reasonEnd = '0'; break;
                        case in_array($reason, array('hn', 'nhn')): $reasonEnd = '2'; break;
                        case strlen($reason) > 0:                   $reasonEnd = '3'; break;
                    }

                    $postRadio['GrundDerBeendigungDerPostoperativeStrahlentherapie'] = $reasonEnd;
                }

                if (strlen($therapy['therapieplan_id']) > 0){
                    $therapysWithTherapyplan[] = $therapy;
                }
            }
        }

        if (count($therapysWithTherapyplan) > 0) {
            $earliestTherapyWithTherapyplan = reset($this->_sortArray($therapysWithTherapyplan, 'beginn'));
            foreach ($extract_data['therapieplan'] as $therapyplan) {
                if ($therapyplan['therapieplan_id'] === $earliestTherapyWithTherapyplan['therapieplan_id']) {
                    $postRadio['TherapieintentionPostoperativeStrahlentherapie'] = $this->_checkIntensionFromTherapyplan($therapyplan, 'strahlen');
                }
            }
        }

        if ($postRadio['TherapieintentionPostoperativeStrahlentherapie'] == '' && 'A' == $postRadio['TherapiezeitpunktPostoperativeStrahlentherapie']) {
            $postTherapy = $this->_getFirstFormWithFilledField($extract_data['therapieplan'], 'datum', 'zeitpunkt', 'post');
            $postRadio['TherapieintentionPostoperativeStrahlentherapie'] = $this->_checkIntensionFromTherapyplan($postTherapy, 'strahlen');
        }

        if ($extract_data['similarity'] === '1'){
            foreach ($extract_data['therapieplan'] as $therapyplan) {
                if ($therapyplan['zeitpunkt'] === 'post') {
                    if ($therapyplan['strahlen'] === '1') {
                        $postRadio['EmpfehlungPostoperativeStrahlentherapie'] = '1';
                        $postRadio['DatumEmpfehlungPostoperativeStrahlentherapie'] = $therapyplan['datum'];

                        $postRadio['GruendeFuerNichtdurchfuehrungPostoperativeStrahlentherapie'] =
                            $this->_getTherapyDeviationReason($extract_data, $therapyplan['therapieplan_id'], 'strahlen')
                        ;

                        break;

                    } else {
                        $postRadio['EmpfehlungPostoperativeStrahlentherapie'] = '0';
                    }
                }
            }
        }

        $section_uid = 'POST_RADIO_' . $this->_getUidFromData($extract_data);

        return $postRadio;
    }


    /**
     * _createPostChemoSection
     *
     * @access  protected
     * @param   array   $parameters
     * @param   array   $extract_data
     * @param   string  $section_uid
     * @return  array
     */
    protected function _createPostChemoSection($parameters, $extract_data, &$section_uid)
    {
        $postChemo = array(
            'EmpfehlungPostoperativeChemotherapie'                    => '',
            'DatumEmpfehlungPostoperativeChemotherapie'               => '',
            'TherapiezeitpunktPostoperativeChemotherapie'             => '',
            'TherapieintentionPostoperativeChemotherapie'             => '',
            'GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie' => '',
            'DatumBeginnPostoperativeChemotherapie'                   => '',
            'DatumEndePostoperativeChemotherapie'                     => '',
            'GrundDerBeendigungDerPostoperativeChemotherapie'         => ''
        );

        $sortedTherapyplan = $this->_sortArray($extract_data['therapieplan'], 'datum');
        $sortedSysTherapys = $this->_sortArray($extract_data['systemische_therapien'], 'beginn');
        $chemoTherapys     = array();

        foreach ($sortedSysTherapys as $sysTherapy) {
            if (in_array($sysTherapy['vorlage_therapie_art'], array('ci', 'cst', 'c')) === true) {
                $chemoTherapys[] = $sysTherapy;
            }
        }

        $intention = '';
        $beginn    = '';
        $ende      = '';
        $reasonEnd = '';

        foreach ($chemoTherapys  as $chemo) {
            if (in_array($chemo['intention'], array('kura', 'pala')) === true) {
                $postChemo['TherapiezeitpunktPostoperativeChemotherapie'] = 'A';

                if (strlen($chemo['therapieplan_id']) > 0) {
                    foreach ($sortedTherapyplan as $therapyplan) {
                        if ($chemo['therapieplan_id'] === $therapyplan['therapieplan_id'] && $intention == '') {
                            $intention   = $this->_checkIntensionFromTherapyplan($therapyplan, 'chemo');
                        }
                    }
                }

                if ($intention === '') {
                    $postTherapy = $this->_getFirstFormWithFilledField($sortedTherapyplan, 'datum', 'zeitpunkt', 'post');
                    $intention   = $this->_checkIntensionFromTherapyplan($postTherapy, 'chemo');
                }

                if ($beginn === '') {
                    $beginn = $chemo['beginn'];
                    $ende   = $chemo['ende'];

                    $end       = $chemo['endstatus'];
                    $reason    = $chemo['endstatus_grund'];

                    switch (true) {
                        case $end === 'plan':                       $reasonEnd = '1'; break;
                        case $reason === 'patw':                    $reasonEnd = '0'; break;
                        case in_array($reason, array('hn', 'nhn')): $reasonEnd = '2'; break;
                        case strlen($reason) > 0:                   $reasonEnd = '3'; break;
                    }
                }
            }
        }

        $postChemo['TherapieintentionPostoperativeChemotherapie'] = $intention;

        $postChemo['DatumBeginnPostoperativeChemotherapie']           = $beginn;
        $postChemo['DatumEndePostoperativeChemotherapie']             = $ende;
        $postChemo['GrundDerBeendigungDerPostoperativeChemotherapie'] = $reasonEnd;


        foreach ($sortedTherapyplan as $therapyplan) {
            if ($therapyplan['zeitpunkt'] === 'post') {
                if ($extract_data['similarity'] === '1') {
                    if ($therapyplan['chemo'] === '1') {
                        $postChemo['EmpfehlungPostoperativeChemotherapie']      = '1';
                        $postChemo['DatumEmpfehlungPostoperativeChemotherapie'] = $therapyplan['datum'];

                        $postChemo['GruendeFuerNichtdurchfuehrungPostoperativeChemotherapie'] =
                            $this->_getTherapyDeviationReason($extract_data, $therapyplan['therapieplan_id'], 'chemo')
                        ;

                        break;

                    } elseif ($postChemo['EmpfehlungPostoperativeChemotherapie'] != '1') {
                        $postChemo['EmpfehlungPostoperativeChemotherapie']      = '0';
                    }
                }
            }
        }

        $section_uid = 'POST_CHEMO_' . $this->_getUidFromData($extract_data);

        return $postChemo;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $section_uid
     * @return array
     */
    protected function createBestSupportiveCareSection($parameters, $extract_data, &$section_uid)
    {
        $bestSupportiveCare = array(
            'BestSupportiveCare' => ''
        );

        $latestTherapyPlan = count($extract_data['therapieplan']) > 0 ? reset($extract_data['therapieplan']) : null;

        if ($latestTherapyPlan !== null) {
            $bestSupportiveCare['BestSupportiveCare'] = $latestTherapyPlan['palliative_versorgung'];
        }

        $section_uid = 'BEST_SUPPORT_' . $this->_getUidFromData($extract_data);

        return $bestSupportiveCare;
    }


    /**
     * createStudySection
     *
     * @access  protected
     * @param   $parameters
     * @param   $extract_data
     * @param   $section_uid
     * @return  array
     */
    protected function _createStudySection($parameters, $extract_data, &$section_uid)
    {
        $study = array(
            'DatumStudie' => '',
            'Studientyp'  => ''
        );

        $lastStudy = count($extract_data['studie']) > 0 ? end($extract_data['studie']) : null;

        if ($lastStudy !== null) {
            $study['DatumStudie'] = $lastStudy['beginn'];

            if (array_key_exists($lastStudy['vorlage_studie_id'], $extract_data['studientyp']) === true) {
                $template = $extract_data['studientyp'][$lastStudy['vorlage_studie_id']];

                switch ($template['studientyp']) {
                    case 'nichtinter':  $study['Studientyp']  = '1'; break;
                    case 'inter':       $study['Studientyp']  = '2'; break;
                    default:            $study['Studientyp']  = '9'; break;
                }
            }
        }

        $adviceSocial = array();
        $famRisk      = array();

        $study['PsychoonkologischeBetreuung'] = '0';
        $study['GenetischeBeratungEmpfohlen'] = '0';

        foreach ($extract_data['beratung'] as $advice) {
            if ($advice['psychoonkologie'] === '1' && $advice['psychoonkologie_dauer'] > 25) {
                $study['PsychoonkologischeBetreuung'] = '1';
            }

            if (strlen($advice['sozialdienst']) > 0) {
                $adviceSocial[] = $advice['sozialdienst'];
            }

            if ($advice['humangenet_beratung'] === '1' || $advice['fam_risikosprechstunde'] === '1') {
                $study['GenetischeBeratungEmpfohlen'] = '1';
            }

            if (strlen($advice['fam_risikosprechstunde_erfolgt']) > 0) {
                $famRisk[] = $advice['fam_risikosprechstunde_erfolgt'];
            }
        }

        $study['BeratungSozialdienst']       = count($adviceSocial) > 0 ? max($adviceSocial) : '9';
        $study['GenetischeBeratungErhalten'] = count($famRisk) > 0      ? max($famRisk)      : '9';

        $immun = array();

        $date = $extract_data['datumErstdiagnosePrimaertumor']; //$this->_getAppearanceDate($extract_data);

        $year = strlen($date) ? substr($date, 0, 4) : 2015;

        if ($year <= 2014) {
            foreach ($extract_data['alle_histologien'] as $histo) {
                if (strlen($histo['msi']) > 0) {
                    $immun[] = $histo['msi'];
                }
            }
        } else {
            foreach ($extract_data['alle_histologien'] as $histo) {
                if (strlen($histo['msi']) > 0) {
                    if ($histo['msi'] === '1') {
                        if (strlen($histo['msi_mutation']) > 0) {
                            $immun[] = $histo['msi'];
                        }
                    } else {
                        $immun[] = $histo['msi'];
                    }
                }
            }
        }

        $study['ImmunhistochemischeUntersuchungAufMSI'] = count($immun) > 0 ? max($immun) : '9';

        $section_uid = 'STUDY_' . $this->_getUidFromData($extract_data);

        return $study;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $extract_data
     * @param $rezidiv
     * @return array
     */
    protected function createFollowUpSections($parameters, $extract_data, $rezidiv)
    {
        $timeline = $this->_postProcessFollowUpTimeline(
            $this->_createFollowUpTimeline($extract_data, $rezidiv, $parameters)
        );

        return $timeline;
    }


    protected function _postProcessFollowUpTimeline($timeline)
    {
        $orderedTimeline = array();

        foreach ($timeline as $records) {
            foreach ($records as $record) {
                $orderedTimeline[] = $record;
            }
        }

        $checks = array(
            'LokoregionaeresRezidiv' => false,
            'LymphknotenRezidiv' => false,
            'Fernmetastasen' => false,
            'Zweittumor' => false,
        );

        foreach ($orderedTimeline as &$record) {
            foreach ($checks as $check => &$checkBool) {
                if ($checkBool === true) {
                    $record[$check] = '2';
                } elseif ($checkBool === false) {
                    if ($record[$check] === '1') {
                        $checkBool = true;
                    }
                }
            }
        }

        return $orderedTimeline;
    }



    /**
     *
     *
     * @access
     * @param $extractData
     * @param $rezidiv
     * @param $parameters
     * @return array
     */
    protected function _createFollowUpTimeline($extractData, $rezidiv, $parameters)
    {
        $timeline      = array();
        $diseaseId     = $extractData['erkrankung_id'];

        $template = array(
            'DatumFollowUp'          => '',
            'LokoregionaeresRezidiv' => '0',
            'LymphknotenRezidiv'     => '0',
            'Fernmetastasen'         => '0',
            'Zweittumor'             => '0',
            'Verstorben'             => '0',
            'QuelleFollowUp'         => '7'
        );

        // Abschluss
        if (count($extractData['abschluss']) > 0) {
            $closure = $extractData['abschluss'];

            $tmp  = $template;
            $date = null;

            if (strlen($closure['todesdatum']) > 0) {
                $tmp['DatumFollowUp'] = $date = $closure['todesdatum'];

                switch ($closure['tod_tumorassoziation']) {
                    case 'totn':
                    case 'tott':  $tmp['Verstorben'] = '1'; break;
                    case 'totnt': $tmp['Verstorben'] = '2'; break;
                    case '':
                    case 'totnb': $tmp['Verstorben'] = '3'; break;
                }
            } else {
                $tmp['DatumFollowUp'] = $date = $closure['letzter_kontakt'];
            }

            if (strlen($date) === 0) {
                $date = '2050-12-31';
            }

            if (in_array($closure['abschluss_grund'], array('lost', 'nnach')) === true) {
                $tmp['LokoregionaeresRezidiv']  = '3';
                $tmp['LymphknotenRezidiv']      = '3';
                $tmp['Fernmetastasen']          = '3';
                $tmp['Zweittumor']              = '3';
            }

            $tmp['id'] = 'FOLLOW_UP_A_' . $closure['abschluss_id'] . $this->_getUidFromData($extractData);

            $timeline[$date][3] = $tmp;
        }

        // Nachsorge
        foreach ($extractData['nachsorge'] as $nachsorge) {
            $tmp = $template;

            $tmp['DatumFollowUp'] = $date = $nachsorge['datum'];

            if ($parameters['org_id'] == $nachsorge['org_id']) {
                $tmp['QuelleFollowUp'] = '2';
            }
            if ($nachsorge['malignom'] == 1) {
                $tmp['Zweittumor'] = '1';
            }

            $tmp['id'] = 'FOLLOW_UP_N_' . $nachsorge['nachsorge_id'] . $this->_getUidFromData($extractData);

            $timeline[$date][1] = $tmp;
        }

        // Rezidiv
        if (array_key_exists($diseaseId, $rezidiv) === true) {
            foreach ($rezidiv[$diseaseId] as $rez) {
                $date = $rez['datum_sicherung'];

                $tmp = $template;

                $tmp['DatumFollowUp'] = $date;

                $tmp['LokoregionaeresRezidiv'] = $rez['rezidiv_lokal'] == 1 ? '1' : '0';
                $tmp['LymphknotenRezidiv']     = $rez['rezidiv_lk'] == 1 ? '1' : '0';
                $tmp['Fernmetastasen']         = $rez['rezidiv_metastasen'] == 1 ? '1' : '0';
                $tmp['Zweittumor']             = $rez['zweittumor'] == 1 ? '1' : '0';

                $tmp['id'] = 'FOLLOW_UP_T_' . $rez['tumorstatus_id'] . $this->_getUidFromData($extractData);

                $timeline[$date][2] = $tmp;
            }
        }

        ksort($timeline);

        foreach ($timeline as &$time) {
            ksort($time);
        }

        return $timeline;
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
        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Concobox_darm_e_1_1_1_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(), $this->m_smarty, $this->m_db, $this->m_error_function);
        $serialiser->SetData($export_record);
        $serialiser->Validate($this->m_parameters);
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function _getUidFromData($extract_data)
    {
        return $extract_data['patient_id'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['tumoridentifikator'];
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _setSyncCounts(CExportWrapper $wrapper)
    {
        $diseases = $wrapper->getFilteredDiseases();

        if ($diseases !== null) {
            $query = "
                SELECT
                    erkrankung_id
                FROM erkrankung_synchron
                WHERE
                    erkrankung_id IN ({$diseases})
                GROUP BY
                    erkrankung_id
            ";

            foreach (sql_query_array($this->m_db, $query) AS $record) {
                $this->_diseaseSyncCounts[] = $record['erkrankung_id'];
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $erkrankung_id
     * @return bool
     */
    protected function _hasSyncCount($erkrankung_id)
    {
        return in_array($erkrankung_id, $this->_diseaseSyncCounts);
    }


    /**
     *
     *
     * @access
     * @param $mainData
     * @param $additionalData
     * @param $idField
     * @return array
     */
    protected function _mergeData($mainData, $additionalData, $idField)
    {
        $data = array();

        if (strlen($idField) > 0) {
            foreach ($mainData as $main) {
                foreach ($additionalData as $add) {
                    if ($main[$idField] === $add[$idField]) {
                        foreach ($add as $key => $value) {
                            if ($key !== $idField) {
                                $main = array_insert($main, count($main), array($key => $value));
                            }
                        }
                        $data[] = $main;
                    }
                }
            }

        }
        return $data;
    }


    /**
     *
     *
     * @access  protected
     * @param   array       $form
     * @param   string      $field
     * @param   string      $dateField
     * @param   string|bool $dateField
     * @return  array
     */
    protected function _getFirstFormWithFilledField($form, $dateField, $field, $condition = false)
    {
        $form = $this->_sortArray($form, $dateField);

        foreach ($form as $record) {
            if ($condition !== false && $record[$field] === $condition) {
                return $record;
            }

            if ($condition === false && strlen($record[$field]) > 0) {
                return $record;
            }
        }

        return array();
    }


    /**
     *
     *
     * @access  protected
     * @param   array   $therapyplan
     * @return  string
     */
    protected function _checkIntensionFromTherapyplan(array $therapyplan, $type)
    {
        if (count($therapyplan) > 0) {
            if (str_starts_with($therapyplan[$type . '_intention'], 'kur') === true) {
                return 'K';
            } elseif (str_starts_with($therapyplan[$type . '_intention'], 'pal') === true) {
                return 'P';
            }
        }

        return '';
    }


    /**
     *
     *
     * @access
     * @param $datum
     * @return string
     */
    protected function CheckDatum($datum) {
        $min_datum = date('1900-01-01');
        $max_datum = date('2050-12-31');
        if (date($datum) < $min_datum) {
            return '1900-01-01';
        }
        else if (date($datum) > $max_datum) {
            return '2050-12-31';
        }

        return $datum;
    }


    /**
     *
     *
     * @access
     * @param $value
     * @return mixed
     */
    protected function killSn($value) {
        return str_replace('(sn)', '', $value);
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return string
     */
    protected function _getSimilaritySection($extract_data)
    {
        if ($extract_data['anlass'] !== 'p') {
            return '5';
        }

        // Prio 1
        foreach ($extract_data['operationen'] as $op) {
            if ($op['art_primaertumor'] === '1') {
                foreach ($op['ops_codes'] as $opCode) {
                    if (str_contains($opCode['prozedur'], array('5-455', '5-456', '5-458', '5-484', '5-485')) === true) {
                        return '1';
                    }
                }
            }
        }

        // Prio 2
        foreach ($extract_data['operationen'] as $op) {
            if ($op['art_primaertumor'] === '1') {
                foreach ($op['ops_codes'] as $opCode) {
                    if (str_contains($opCode['prozedur'], array('5-482', '5-452')) === true) {
                        return '2';
                    }
                }
            }
        }

        // Prio 3/4
        $primaryOp = false;

        foreach ($extract_data['operationen'] as $op) {
            if ($op['art_primaertumor'] === '1') {
                $primaryOp = true;
                break;
            }
        }

        // no primary op exists
        if ($primaryOp === false) {
            // Prio 3
            if (strlen($extract_data['ts_similarity']) > 0) {
                foreach (array('strahlen_therapien', 'systemische_therapien') as $therapys){
                    foreach ($extract_data[$therapys] as $therapy) {
                        if ($therapy['intention'] === 'kur') {
                            return '4';
                        }
                    }
                }
            }
            // Prio 4
            foreach (array('strahlen_therapien', 'systemische_therapien') as $therapys){
                foreach ($extract_data[$therapys] as $therapy) {
                    if ($therapy['intention'] === 'pal') {
                        return '3';
                    }
                }
            }
            foreach ($extract_data['therapieplan'] as $therapyplan) {
                if ($therapyplan['palliative_versorgung'] === '1') {
                    return '3';
                }
            }
        }

        return '5';
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addFamilyAnamnesisData(CExportWrapper $wrapper)
    {
        $where = implode(array(
            "anf.karzinom IN ('d', 'du', 'kore')",
            "anf.verwandschaftsgrad IN ('mu', 'va', 'sch', 'br', 'ze', 'zz', 'to', 'so')"
        ),
            ' AND '
        );

        $wrapper
            ->addAdditionalJoin("LEFT JOIN anamnese_familie anf ON an.anamnese_id = anf.anamnese_id AND {$where}")
            ->addAdditionalFields("COUNT(anf.anamnese_familie_id) > 0 AS 'anf'")
        ;

        return $this;
    }


    /**
     * add anamnesis data
     *
     * @access  protected
     * @param   CExportWrapper  $wrapper
     * @return  $this
     */
    protected function _addAnamnesisData(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
            IF(MIN(an.anamnese_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(an.anamnese_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(an.anamnese_id, ''),
                                    IFNULL(an.fb_dkg, ''),
                                    IFNULL(an.fb_dkg_beurt, '')
                                    ),
                                NULL
                                )
                            SEPARATOR '{$separator_row}'
                        ),
                    NULL
            )  AS 'additional_anamnesis'"
            );

        return $this;
    }


    /**
     * add anamnesis disease data
     *
     * @access  protected
     * @param   CExportWrapper  $wrapper
     * @return  $this
     */
    protected function _addAnamnesisDiseaseData(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalJoin("LEFT JOIN anamnese_erkrankung aerk ON an.anamnese_id = aerk.anamnese_id")
            ->addAdditionalFields("
            IF(MIN(aerk.anamnese_erkrankung_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(aerk.anamnese_erkrankung_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(aerk.anamnese_erkrankung_id, ''),
                                    IFNULL(an.datum, ''),
                                    IFNULL(aerk.erkrankung, ''),
                                    IFNULL(aerk.morphologie, ''),
                                    IFNULL(aerk.jahr, '')
                                    ),
                                NULL
                                )
                            SEPARATOR '{$separator_row}'
                        ),
                    NULL
            )  AS 'anamnesisDisease'"
            );

        return $this;
    }


    /**
     * add diagnose data
     *
     * @access  protected
     * @param   CExportWrapper $wrapper
     * @return  $this
     */
    protected function _addDiagnoseData(CExportWrapper $wrapper, stageCalc $stageCalc)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalSelect($stageCalc->select('c', 'uicc', true) . " AS 'ct_uicc'")
            ->addAdditionalJoin("LEFT JOIN diagnose dd ON s.form = 'diagnose' AND s.form_id = dd.diagnose_id")
            ->addAdditionalFields(array("
                IF(MIN(dd.diagnose_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(dd.diagnose_id IS NOT NULL AND dd.diagnose IS NOT NULL AND dd.datum IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    dd.datum,
                                    dd.diagnose
                                    ),
                                NULL
                                )
                            SEPARATOR '{$separator_row}'
                        ),
                    NULL
                )  AS 'diagnosis_data'",
                                        "sit.ct_uicc AS 'ct_uicc'"
                )
            );

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addConference(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;

        $wrapper->addAdditionalFields("GROUP_CONCAT(DISTINCT
            IF(s.form = 'konferenz_patient' AND SUBSTRING(s.report_param, 6) != '' AND SUBSTRING(s.report_param, 6) BETWEEN sit.start_date AND sit.end_date,
                s.report_param,
                null
            )
            SEPARATOR '{$separator_row}'
        ) AS 'conference'");

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addTherapyPlan(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper->addAdditionalFields("
            IF( MIN( tp.therapieplan_id ) IS NOT NULL,
                GROUP_CONCAT(
                    DISTINCT
                        IF( tp.therapieplan_id IS NOT NULL,
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(tp.therapieplan_id, ''),
                                IFNULL(tp.datum, ''),
                                IFNULL(tp.grundlage, ''),
                                IFNULL(tp.zeitpunkt, ''),
                                IFNULL(tp.abweichung_leitlinie, ''),
                                IFNULL(tp.palliative_versorgung, ''),
                                IFNULL(tp.strahlen, ''),
                                IFNULL(tp.strahlen_intention, ''),
                                IFNULL(tp.chemo, ''),
                                IFNULL(tp.chemo_intention, ''),
                                IFNULL(tp.op, '')
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
    protected function _addTherapyPlanDeviance(CExportWrapper $wrapper)
    {

        $wrapper->addAdditionalJoin(
            'LEFT JOIN therapieplan_abweichung tdev ON s.form = "therapieplan_abweichung" AND tdev.therapieplan_abweichung_id = s.form_id'
        );

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper->addAdditionalFields("
            IF( MIN( tdev.therapieplan_abweichung_id ) IS NOT NULL,
                GROUP_CONCAT(
                    DISTINCT
                        IF( tdev.therapieplan_abweichung_id IS NOT NULL,
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(tdev.therapieplan_abweichung_id, ''),
                                IFNULL(tdev.datum, ''),
                                IFNULL(tdev.therapieplan_id, ''),
                                IFNULL(tdev.bezug_strahlen, ''),
                                IFNULL(tdev.bezug_chemo, ''),
                                IFNULL(tdev.grund, '')
                            ),
                            NULL
                        )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            ) AS 'therapieplan_abweichung'
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
    protected function _addTumorstatus(CExportWrapper $wrapper)
    {
        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
        $basicOrder          = 'ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1';
        $reverseBasicOrder   = 'ORDER BY ts.datum_sicherung ASC, ts.sicherungsgrad DESC, ts.datum_beurteilung ASC LIMIT 1';

        $wrapper
            ->addAdditionalSelect("(SELECT ts.tumorstatus_id         FROM tumorstatus ts WHERE {$relevantSelectWhere} {$basicOrder})                                                                                                    AS 'case_nr'")
            ->addAdditionalSelect("(SELECT ts.tumorstatus_id         FROM tumorstatus ts WHERE {$relevantSelectWhere} {$reverseBasicOrder})                                                                                             AS 'earliest_tumorstatus_id'")
            ->addAdditionalSelect("(SELECT MAX(ts.fall_vollstaendig) FROM tumorstatus ts WHERE {$relevantSelectWhere})                                                                                                                  AS 'fall_vollstaendig'")
            ->addAdditionalSelect("(SELECT ts.lokalisation           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL {$basicOrder})                                                                    AS ICDOLokalisation")
            ->addAdditionalSelect("(SELECT ts.t                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'c' AND (ts.tnm_praefix NOT IN ('y', 'yr') OR ts.tnm_praefix IS NULL) {$reverseBasicOrder})   AS 'earliest_ct'")
            ->addAdditionalSelect("(SELECT ts.n                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.n, 1) = 'c' AND (ts.tnm_praefix NOT IN ('y', 'yr') OR ts.tnm_praefix IS NULL) {$reverseBasicOrder})   AS 'earliest_cn'")
            ->addAdditionalSelect("(SELECT ts.m                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.m, 1) = 'c' AND (ts.tnm_praefix NOT IN ('y', 'yr') OR ts.tnm_praefix IS NULL) {$reverseBasicOrder})   AS 'earliest_cm'")
            ->addAdditionalSelect("(SELECT ts.g                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT(ts.t, 1) = 'p' {$basicOrder})                                                                            AS 'pT_g'")
            ->addAdditionalSelect("(SELECT ts.morphologie            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.morphologie IS NOT NULL AND LEFT( ts.t, 1 )='p' {$basicOrder})                                             AS 'pT_morphologie'")
            ->addAdditionalSelect("(SELECT ts.r_lokal                FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL AND LEFT( ts.t, 1 ) = 'p' {$basicOrder})                                               AS 'pT_r_lokal'")
            ->addAdditionalSelect("(SELECT ts.r                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r IS NOT NULL AND LEFT( ts.t, 1 )='p'{$basicOrder})                                                        AS 'pT_r'")
            ->addAdditionalSelect("(SELECT ts.lk_entf                FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL AND LEFT( ts.t, 1 ) = 'p' {$basicOrder})                                               AS 'pT_lk_entf'")
            ->addAdditionalSelect("(SELECT ts.m                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND LEFT( ts.t, 1 ) = 'p' {$basicOrder})                                                                          AS 'pT_m'")
            ->addAdditionalSelect("(SELECT ts.diagnose_c19_zuordnung FROM tumorstatus ts WHERE {$relevantSelectWhere} AND (LEFT(ts.diagnose, 3) = 'C19' OR LEFT(ts.diagnose, 3) = 'C20' OR ts.diagnose ='D01.1') {$basicOrder})         AS 'diagnose_c19_zuordnung'")
            ->addAdditionalSelect("(SELECT ts.datum_sicherung        FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = 'p' {$reverseBasicOrder})                                                             AS 'first_datum_sicherung'")
            ->addAdditionalSelect("(SELECT ts.t                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.tnm_praefix IN ('y', 'yr') AND (ts.t = 'cT0' OR ts.t = 'pT0') {$basicOrder})                               AS 'ts_similarity'")
            ->addAdditionalSelect("(SELECT ts.rezidiv_lokal          FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.rezidiv_lokal IS NOT NULL {$basicOrder})                                                                   AS 'rezidiv_lokal'")
        ;

        $wrapper
            ->addAdditionalFields("sit.case_nr as case_nr")
            ->addAdditionalFields("sit.earliest_tumorstatus_id as earliest_tumorstatus_id")
            ->addAdditionalFields("sit.fall_vollstaendig as fall_vollstaendig")
            ->addAdditionalFields("sit.ICDOLokalisation as ICDOLokalisation")
            ->addAdditionalFields("sit.earliest_ct as earliest_ct")
            ->addAdditionalFields("sit.earliest_cn as earliest_cn")
            ->addAdditionalFields("sit.earliest_cm as earliest_cm")
            ->addAdditionalFields("sit.pT_g as pT_g")
            ->addAdditionalFields("sit.pT_morphologie as pT_morphologie")
            ->addAdditionalFields("sit.pT_r_lokal as pT_r_lokal")
            ->addAdditionalFields("sit.pT_r as pT_r")
            ->addAdditionalFields("sit.pT_m as pT_m")
            ->addAdditionalFields("sit.pT_lk_entf as pT_lk_entf")
            ->addAdditionalFields("sit.diagnose_c19_zuordnung as diagnose_c19_zuordnung")
            ->addAdditionalFields("sit.first_datum_sicherung as first_datum_sicherung")
            ->addAdditionalFields("sit.ts_similarity as ts_similarity")
            ->addAdditionalFields("sit.rezidiv_lokal as rezidiv_lokal")
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
    protected function _addComplicationData(CExportWrapper $wrapper)
    {

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper->addAdditionalFields("
            IF(MIN(k.komplikation_id) IS NOT NULL,
                GROUP_CONCAT(
                    DISTINCT
                        IF(k.komplikation_id IS NOT NULL,
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(k.komplikation_id, ''),
                                IFNULL(k.datum, ''),
                                IFNULL(k.eingriff_id, ''),
                                IFNULL(k.komplikation, ''),
                                IFNULL(k.reintervention, ''),
                                IFNULL(k.drainage_intervent, ''),
                                IFNULL(k.antibiotikum, ''),
                                IFNULL(k.drainage_transanal, ''),
                                IFNULL(k.revisionsoperation, '')
                            ),
                            NULL
                        )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            ) AS 'complication'
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
    protected function _addStudy(CExportWrapper $wrapper)
    {

        $wrapper->addAdditionalJoin(
            'LEFT JOIN studie stu ON s.form = "studie" AND stu.studie_id = s.form_id'
        );

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
            IF(MIN(stu.studie_id) IS NOT NULL,
                GROUP_CONCAT(
                    DISTINCT
                        IF(stu.studie_id IS NOT NULL,
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(stu.studie_id, ''),
                                IFNULL(stu.beginn, stu.date),
                                IFNULL(stu.vorlage_studie_id, '')
                            ),
                            NULL
                        )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            ) AS 'studie'
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
    protected function _addExamination(CExportWrapper $wrapper)
    {
        $wrapper->addAdditionalJoin(
            'LEFT JOIN untersuchung unt ON s.form = "untersuchung" AND unt.untersuchung_id = s.form_id'
        );

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
                IF(MIN(unt.untersuchung_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(unt.untersuchung_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(unt.untersuchung_id, ''),
                                    IFNULL(unt.datum, ''),
                                    IFNULL(unt.art, ''),
                                    IFNULL(unt.ct_becken, ''),
                                    IFNULL(unt.mesorektale_faszie, ''),
                                    IFNULL(unt.art_text, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'examination'
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
    protected function _addAdvice(CExportWrapper $wrapper)
    {
        $wrapper->addAdditionalJoin(
            'LEFT JOIN beratung b ON s.form = "beratung" AND b.beratung_id = s.form_id'
        );


        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper->addAdditionalFields("
            IF(MIN(b.beratung_id) IS NOT NULL,
                GROUP_CONCAT(
                    DISTINCT
                        IF(b.beratung_id IS NOT NULL,
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(b.beratung_id, ''),
                                IFNULL(b.datum, ''),
                                IFNULL(b.psychoonkologie, ''),
                                IFNULL(b.psychoonkologie_dauer, ''),
                                IFNULL(b.sozialdienst, ''),
                                IFNULL(b.fam_risikosprechstunde, ''),
                                IFNULL(b.fam_risikosprechstunde_erfolgt, ''),
                                IFNULL(b.humangenet_beratung, '')
                            ),
                            NULL
                        )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            ) AS 'beratung'
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
    protected function _addAdditionalSurgicalFields(CExportWrapper $wrapper)
    {

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("sit.start_date")
            ->addAdditionalFields("sit.end_date")
            ->addAdditionalFields("
                IF(MIN(op.eingriff_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(op.eingriff_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(op.eingriff_id, ''),
                                    IFNULL(op.operateur2_id, ''),
                                    IFNULL(op.stomaposition, ''),
                                    IFNULL(op.mesorektale_faszie, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'eingriff'
            ");

        return $this;
    }


    protected function _addSynchronDisease(CExportWrapper $wrapper)
    {
        $wrapper->addAdditionalField('sit.erkrankung_relevant');

        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addAdditionalHistologyFields(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("sit.start_date")
            ->addAdditionalFields("sit.end_date")
            ->addAdditionalFields("
                IF(MIN(h_a.histologie_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(h_a.histologie_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(h_a.histologie_id, ''),
                                    IFNULL(h_a.art, ''),
                                    IFNULL(h_a.resektionsrand_oral, ''),
                                    IFNULL(h_a.unauffaellig, ''),
                                    IFNULL(h_a.msi_mutation, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'additional_histology'
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
    protected function _addDepartment(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
                IF(MIN(auf.aufenthalt_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(auf.aufenthalt_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(auf.aufenthalt_id, ''),
                                    IFNULL(auf.aufnahmedatum, ''),
                                    IFNULL(auf.fachabteilung, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'department'
            ");

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array|bool
     */
    protected function _getStudyArtData()
    {
        $data  = array();
        $query = "SELECT * FROM vorlage_studie";

        foreach (sql_query_array($this->m_db, $query) as $result) {
            $data[$result['vorlage_studie_id']] = $result;
        }

        return $data;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _readGradingCodes()
    {
        $query  = "SELECT code, bez FROM l_basic WHERE klasse = 'g'";
        $result = sql_query_array($this->m_db, $query);

        foreach ($result as $row) {
            $this->_gradingCodes[$row['code']] = $row['bez'];
        }
    }


    /**
     * _getDiagnoseSection
     *
     * @access  protected
     * @param   array   $extractData
     * @return  string
     */
    protected function _getDiagnoseSection($extractData)
    {
        $diagnose  = $extractData['diagnose'];
        $diagnoseZ = $extractData['diagnose_c19_zuordnung'];

        if (str_starts_with($diagnose, 'D01') && strlen($diagnoseZ) === 0) {
            $diagnoseZ = $diagnose;
        }

        return strlen($diagnoseZ) ? $diagnoseZ : substr($diagnose, 0, 3);
    }


    /**
     *
     *
     * @access
     * @param $grading
     * @return string
     */
    protected function _getGradingBez($grading)
    {
        return array_key_exists($grading, $this->_gradingCodes) === true ? $this->_gradingCodes[$grading] : '';
    }


    /**
     * _findAllDiseases
     *
     * @access  protected
     * @param   CExportWrapper $wrapper
     * @return  $this

    protected function _findAllDiseases(CExportWrapper $wrapper)
    {
    $diseases = $wrapper->getFilteredDiseases();

    $query = "
    SELECT
    patient_id,
    erkrankung_id,
    datum_sicherung
    FROM tumorstatus
    WHERE
    erkrankung_id IN ({$diseases})
    GROUP BY
    erkrankung_id
    ORDER BY
    datum_sicherung ASC,
    anlass ASC,
    sicherungsgrad DESC
    ";

    foreach (sql_query_array($this->m_db, $query) as $result) {
    $patientId = $result['patient_id'];
    $diseaseId = $result['erkrankung_id'];

    $this->_diseases[$patientId][$diseaseId] = $result['datum_sicherung'];
    }

    return $this;
    } */


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return mixed
     */
    protected function _getRezidivTs(CExportWrapper $wrapper)
    {
        $diseases = $wrapper->getFilteredDiseases();

        $rezidiv = array();

        $query = "
            SELECT
                tumorstatus_id,
                erkrankung_id,
                datum_sicherung,
                anlass,
                sicherungsgrad,
                rezidiv_lokal,
                rezidiv_lk,
                rezidiv_metastasen,
                zweittumor
            FROM tumorstatus
            WHERE
                erkrankung_id IN ({$diseases}) AND
                LEFT(anlass, 1) = 'r'
            ORDER BY
                erkrankung_id ASC,
                datum_sicherung ASC,
                anlass ASC,
                sicherungsgrad DESC
        ";

        foreach (sql_query_array($this->m_db, $query) as $result) {
            $diseaseId  = $result['erkrankung_id'];
            $case       = $result['anlass'];

            if (isset($rezidiv[$diseaseId][$case]) === true) {
                continue;
            }

            $rezidiv[$result['erkrankung_id']][$case] = $result;
        }

        return $rezidiv;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addAfterCare(CExportWrapper $wrapper)
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
                IF(MIN(n.nachsorge_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(n.nachsorge_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(n.nachsorge_id, ''),
                                    IFNULL(n.datum, ''),
                                    IFNULL(n.malignom, ''),
                                    IFNULL(n.org_id, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'nachsorge'
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
    protected function _addClosure(CExportWrapper $wrapper)
    {

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $wrapper
            ->addAdditionalFields("
                IF(MIN(x.abschluss_id) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF(x.abschluss_id IS NOT NULL,
                                CONCAT_WS(
                                    '{$separator_col}',
                                    IFNULL(x.abschluss_id, ''),
                                    IFNULL(x.todesdatum, ''),
                                    IFNULL(x.abschluss_grund, ''),
                                    IFNULL(x.letzter_kontakt_datum, ''),
                                    IFNULL(x.tod_tumorassoziation, '')
                                ),
                                NULL
                            )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'abschluss'
            ");

        return $this;
    }


    /**
     * Sorts an array, by given field
     *
     *
     * @access      protected
     * @param       $array
     * @param       $sortField
     * @param       bool $asc
     * @return      array
     */
    protected function _sortArray($array, $sortField, $asc = true)
    {
        $tmp = array();
        $sortedArray = array();

        if (is_array($array) === true) {

            foreach ($array as $dataset) {
                $tmp[$dataset[$sortField]][] = $dataset;
            }
        }
        $asc === true ? ksort($tmp) : krsort($tmp);

        foreach ($tmp as $new) {
            if (count($new) > 1) {
                foreach ($new as $n) {
                    $sortedArray[] = $n;
                }
            } else {
                $sortedArray[] = $new[0];
            }
        }

        return $sortedArray;
    }
}
?>
