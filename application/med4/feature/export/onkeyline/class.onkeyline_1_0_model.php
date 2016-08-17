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

require_once( 'core/class/report/helper.reports.php' );
require_once( 'feature/export/base/class.exportdefaultmodel.php' );
require_once( 'class.onkeyline_1_0_serialiser.php' );

class Conkeyline_1_0_Model extends CExportDefaultModel
{

    protected $m_pattern = array();

    public function __construct()
    {
    }

    public function Create( $absolute_path, $export_name, $smarty, $db, $error_function = '' )
    {
        parent::Create( $absolute_path, $export_name, $smarty, $db, $error_function );
        $this->m_smarty->config_load( 'app/tumorstatus.conf', 'rec' );
        $this->m_smarty->config_load( 'app/strahlentherapie.conf', 'rec' );
        $this->m_config = $this->m_smarty->get_config_vars();
        $this->m_pattern = HDatabase::readMedicationPatternTable( $this->m_db );
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultModel
    //

    public function ExtractData( $parameters, $wrapper, &$export_record )
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;
        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
        $patient_ids = $this->GetExportedPatientIds();
        $wrapper->IgnorePatientIds( $patient_ids );
        $wrapper->SetRangeDate( $parameters[ 'datum_von' ], $parameters[ 'datum_bis' ] );
        $wrapper->SetErkrankungen( 'all' );
        $wrapper->SetAdditionalJoins( array(
            "LEFT JOIN tumorstatus tumor ON s.form = 'tumorstatus' AND tumor.tumorstatus_id = s.form_id AND tumor.diagnose_seite IN ('B', sit.diagnose_seite)",

            "LEFT JOIN histologie hist ON s.form = 'histologie' AND hist.histologie_id  = s.form_id AND hist.diagnose_seite IN ('B', sit.diagnose_seite)",

            "LEFT JOIN histologie_einzel ehist ON ehist.histologie_id = hist.histologie_id",

            "LEFT JOIN nebenwirkung eff ON s.form = 'nebenwirkung' AND eff.nebenwirkung_id  = s.form_id",

            "LEFT JOIN zytologie zyto ON s.form = 'zytologie' AND zyto.zytologie_id  = s.form_id",
        ) );

        $wrapper->SetAdditionalSelects( array(
            "p.kv_nr                                                       AS    'versicherungsnummer'",
            "p.titel                                                       AS    'titel'",
            "p.geburtsname                                                 AS    'geburtsname'",
            "p.strasse                                                     AS    'strasse'",
            "p.hausnr                                                      AS    'hausnummer'",
            "p.staat                                                       AS    'land'",
            "p.plz                                                         AS    'plz'",
            "p.ort                                                         AS    'wohnort'",
            "p.staat                                                       AS    'staat'",
            "p.kv_iknr                                                     AS    'ikassenschluessel'",
            "(SELECT name FROM l_ktst WHERE iknr = p.kv_iknr LIMIT 1)      AS    'kassenname'",
            "(SELECT vknr FROM l_ktst WHERE iknr = p.kv_iknr LIMIT 1)      AS    'kassenschluessel'",
            "p.kv_status                                                   AS    'versichertengruppe'",
            "p.bem                                                         AS    'bemerkungen'",
            "(SELECT ts.estro_irs FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.estro_irs IS NOT NULL    ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1)           AS estro_irs",
            "(SELECT ts.prog_irs  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.prog_irs IS NOT NULL     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1)           AS prog_irs",
            "e.nachsorgepassnummer                                         AS    'nachsorgepassnummer'",
            "e.bem                                                         AS    'comment'",
            "(SELECT
                abschluss_grund
             FROM
                abschluss
             WHERE
                patient_id = p.patient_id
             LIMIT 1)                                                      AS    'abschluss'",

            "(SELECT
                user_id
             FROM
                behandler
             WHERE
                patient_id = p.patient_id
             AND
                funktion = 'haus'
             LIMIT 1)                                                      AS    'behandler_id'",
        ) );


        $wrapper->SetAdditionalFields( array(
            "sit.versicherungsnummer    AS 'versicherungsnummer'",
            "sit.patient_nr             AS 'referenznr'",
            "sit.titel                  AS 'titel'",
            "sit.geburtsname            AS 'geburtsname'",
            "sit.strasse                AS 'strasse'",
            "sit.hausnummer             AS 'hausnummer'",
            "sit.land                   AS 'land'",
            "sit.plz                    AS 'plz'",
            "sit.wohnort                AS 'wohnort'",
            "sit.staat                  AS 'staat'",
            "sit.ikassenschluessel      AS 'ikassenschluessel'",
            "sit.kassenschluessel       AS 'kassenschluessel'",
            "sit.kassenname             AS 'kassenname'",
            "sit.versichertengruppe     AS 'versichertengruppe'",
            "sit.bemerkungen            AS 'bemerkungen'",
            "sit.diagnose_seite         AS 'seitenlokalisation'",
            "sit.estro_irs              AS 'estrogen_irs'",
            "sit.prog_irs               AS 'progest_irs'",
        		"IF( MAX( zyto.zytologie_id ) IS NOT NULL,
                 1,
                 0 )                    AS 'zytology'",
            "IF( MIN( tumor.tumorstatus_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( tumor.tumorstatus_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL( tumor.tumorstatus_id, '' ),
                            IFNULL( tumor.datum_sicherung, '' ),
                            IFNULL( tumor.morphologie, '' ),
                            IFNULL( tumor.morphologie_text, '' ),
                            IFNULL( tumor.diagnose, '' ),
                            IFNULL( tumor.diagnose_text, '' ),
                            IFNULL( tumor.rezidiv_lokal, '' ),
                            IFNULL( tumor.rezidiv_lk, ''),
                            IFNULL( tumor.rezidiv_metastasen, ''),
                            IFNULL( tumor.bem, ''),
                            IFNULL( tumor.nhl_who_b, ''),
                            IFNULL( tumor.nhl_who_t, ''),
                            IFNULL( tumor.hl_who, ''),
                            IFNULL( tumor.aml_who, ''),
                            IFNULL( tumor.all_egil, ''),
                            IFNULL( tumor.mds_fab, ''),
                            IFNULL( tumor.mds_who, ''),
                            IFNULL( tumor.groesse_x, ''),
                            IFNULL( tumor.groesse_y, ''),
                            IFNULL( tumor.groesse_z, ''),
                            IFNULL( tumor.lokalisation, ''),
                            IFNULL( tumor.lokalisation_text, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                      	    AS 'tumorstats'",

            "IF( MIN( hist.histologie_id ) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF( hist.histologie_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL(hist.histologie_id, ''),
                                IFNULL(hist.datum, ''),
                                IFNULL(hist.kras, ''),
                                IFNULL(hist.user_id, ''),
                                IFNULL(hist.histologie_nr, '')
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                             AS 'histology'",

            "IF( MIN( ehist.histologie_einzel_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( ehist.histologie_einzel_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(ehist.histologie_einzel_id, ''),
                            IFNULL(ehist.histologie_id, ''),
                            IFNULL(ehist.regression, ''),
                            IFNULL(ehist.ulzeration, ''),
                            IFNULL(ehist.tumordicke, ''),
                            IFNULL(ehist.clark, ''),
                            IFNULL(ehist.resektionsrand, ''),
                            IFNULL(hist.datum, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                             AS 'single_histology'",

            "IF( MIN( eff.nebenwirkung_id ) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF( eff.nebenwirkung_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            IFNULL(eff.nci_code, ''),
                            IFNULL(eff.strahlentherapie_id, ''),
                            IFNULL(eff.therapie_systemisch_id, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
            )                             AS 'sideeffects'",

            "sit.ann_arbor_stadium            AS 'ann_arb'",
            "sit.ann_arbor_aktivitaetsgrad    AS 'ann_arb_aktivitaetsgrad'",
            "sit.nachsorgepassnummer          AS 'nachsorgepassnummer'",
            "sit.comment                      AS 'comment'",
            "sit.abschluss                    AS 'abschluss'",
            "sit.behandler_id                 AS 'behandler_id'",
        ) );
        $lieferung[ 'lieferant_id' ] = $parameters[ 'melder_id' ];
        $lieferung[ 'lieferung_id' ] = intval( $this->m_export_record->GetExportNr() );
        $lieferung[ 'liefer_datum' ] = date( "Y-m-d" );
        $lieferung[ 'liefer_id' ] = intval( $this->m_export_record->GetNextTan() );
        $lieferung[ 'ref_id' ] = 0;
        $result = $wrapper->GetExportData( $parameters );
        $ref_id = 0;
        $last_patient_id = 0;
        $set_case = false;

        foreach( $result as $extract_data ) {
            // Fix für Ticket #8828
            foreach( $extract_data[ 'sonstige_therapien' ] as $sonstige_therapie ) {
                $sonstige_therapie[ 'systemische_therapie_id' ] = $sonstige_therapie[ 'sonstige_therapie_id' ];
                $sonstige_therapie[ 'art' ] = "son";
                $sonstige_therapie[ 'vorlage_therapie_id' ] = "";
                $sonstige_therapie[ 'dosisaenderung_grund' ] = "";
                $extract_data[ 'systemische_therapien' ][] = $sonstige_therapie;
            }
            $tumorstats = array();
            if ( strlen( $extract_data[ 'tumorstats' ] ) > 0 ) {
                $extract_data[ 'lokalisation_txt' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 20, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 20, null );
                if ( $tmp !== false ) {
                    $extract_data[ 'lokalisation' ] = $tmp[ 20 ];
                    $extract_data[ 'lokalisation_txt' ] = $tmp[ 21 ];
                }
                // nhl_who_b_description
                $extract_data[ 'nhl_who_b_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 10, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 10, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 10 ] ) > 0 ) ) {
                    $extract_data[ 'nhl_who_b_description' ] = $this->GetLBasicBez( 'nhl_who_b', $tmp[ 10 ] );
                }
                // nhl_who_t_description
                $extract_data[ 'nhl_who_t_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 11, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 11, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 11 ] ) > 0 ) ) {
                    $extract_data[ 'nhl_who_t_description' ] = $this->GetLBasicBez( 'nhl_who_t', $tmp[ 11 ] );
                }
                // hl_who
                $extract_data[ 'hl_who_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 12, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 12, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 12 ] ) > 0 ) ) {
                    $extract_data[ 'hl_who_description' ] = $this->GetLBasicBez( 'hl_who', $tmp[ 12 ] );
                }
                // aml_who
                $extract_data[ 'aml_who_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 13, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 13, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 13 ] ) > 0 ) ) {
                    $extract_data[ 'aml_who_description' ] = $this->GetLBasicBez( 'aml_who', $tmp[ 13 ] );
                }
                // all_egil
                $extract_data[ 'all_egil_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 14, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 14, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 14 ] ) > 0 ) ) {
                    $extract_data[ 'all_egil_description' ] = $this->GetLBasicBez( 'all_egil', $tmp[ 14 ] );
                }
                // mds_fab
                $extract_data[ 'mds_fab_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 15, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 15, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 15 ] ) > 0 ) ) {
                    $extract_data[ 'mds_fab_description' ] = $this->GetLBasicBez( 'mds_fab', $tmp[ 15 ] );
                }
                // mds_who
                $extract_data[ 'mds_who_description' ] = "";
                //$tmp = $this->GetMaxElementWithValue( $extract_data[ 'tumorstats' ], 16, "-|-", "_|_" );
                $tmp = HReports::GetMaxElementByDate( $extract_data[ 'tumorstats' ], 16, null );
                if ( ( $tmp !== false ) &&
                     ( strlen( $tmp[ 16 ] ) > 0 ) ) {
                    $extract_data[ 'mds_who_description' ] = $this->GetLBasicBez( 'mds_who', $tmp[ 16 ] );
                }
                $tumorstats = HReports::RecordStringToArray( $extract_data[ 'tumorstats' ],
                                                             array(
                                                                 "tumorstatus_id",
                                                                 "datum_sicherung",
                                                                 "morphologie",
                                                                 "morphologie_text",
                                                                 "diagnose",
                                                                 "diagnose_text",
                                                                 "rezidiv_lokal",
                                                                 "rezidiv_lk",
                                                                 "rezidiv_metastasen",
                                                                 "bem",
                                                                 "nhl_who_b",
                                                                 "nhl_who_t",
                                                                 "hl_who",
                                                                 "aml_who",
                                                                 "all_egil",
                                                                 "mds_fab",
                                                                 "mds_who",
                                                                 "groesse_x",
                                                                 "groesse_y",
                                                                 "groesse_z",
                                                                 "lokalisation",
                                                                 "lokalisation_text"
                                                             ) );
                $extract_data[ 'max_groesse' ] = $this->GetMaxGroesse( $tumorstats );
            }
            $extract_data[ 'tumorstats' ] = $tumorstats;
            // get histologie
            $histology = array();
            if ( strlen( $extract_data[ 'histology' ] ) > 0 ) {
                $histology = HReports::RecordStringToArray( $extract_data[ 'histology' ],
                                                            array(
                                                                "histology_id",
                                                                "datum",
                                                                "kras",
                                                                "user_id",
                                                                "histologie_nr"
                                                            ) );
            }
            $extract_data[ 'histology' ] = $histology;
            // Get sideffects
            $sideeffects = array();
            if ( strlen($extract_data[ 'sideeffects'] ) > 0 ) {
                $sideeffects = HReports::RecordStringToArray( $extract_data[ 'sideeffects' ],
                                                              array(
                                                                  "nci_code",
                                                                  "strahlentherapie_id",
                                                                  "therapie_systemisch_id"
                                                              ) );
            }
            $extract_data['sideeffects'] = $sideeffects;
            // Get Single Histologien
            $single_histology = array();
            if ( strlen( $extract_data[ 'single_histology' ] ) > 0 ) {
                $single_histology = HReports::RecordStringToArray( $extract_data[ 'single_histology' ],
                                                                   array(
                                                                       "histologie_einzel_id",
                                                                       "histologie_id",
                                                                       "regression",
                                                                       "ulzeration",
                                                                       "tumordicke",
                                                                       "clark",
                                                                       "resektionsrand",
                                                                       "datum"
                                                                 ) );
            }
            $extract_data['single_histology'] = $single_histology;

            foreach ($extract_data['anamnesen'] as $anamnesis) {
                $extract_data['anamnesisDiseases'] = $this->getAnamnesisDiseases($anamnesis['anamnese_id']);
            }

            $extract_data['newestTumorstatus'] = $this->getNewestTumorstatus($extract_data['tumorstats']);

            if ($extract_data['erkrankung'] === 'b' || $extract_data['erkrankung'] === 'lu') {
                $extract_data['operationen'] = $this->_filterOpSide($extract_data['operationen'], $extract_data['diagnose_seite']);
            }


            if ( '1' == $extract_data[ 'kr_meldung' ][ 'export_for_onkeyline' ] ) {
                // Create main case
                $case = $this->CreateCase( $export_record->GetDbid(), $parameters, $extract_data );

                // Patienten wechsel
                if ( $last_patient_id != $extract_data['patient_id'] ) {
                    $lieferung[ 'ref_id' ] = 0;
                    $ref_id = 0;
                }

                // Erstvorstellung
                if ($extract_data['anlass'] === 'p') {
                    $lieferung[ 'liefer_id' ]++;
                    $ref_id = $lieferung[ 'liefer_id' ];
                    $section = $this->createFirstIntroductionSection( $parameters, $extract_data, $section_uid, $lieferung );
                    $firstIntroduction= $this->CreateBlock( $case->GetDbid(), $parameters, 'firstIntroduction', $section_uid, $section );
                    $case->AddSection( $firstIntroduction);
                    $set_case = true;
                }

                if ( 0 != $ref_id ) {
                    // Patient
                    if ( ( $extract_data['anlass'] === 'p') &&
                         ( $last_patient_id != $extract_data['patient_id'] ) ) {
                        $lieferung[ 'liefer_id' ]++;
                        $section = $this->createPatientSection( $parameters, $extract_data, $section_uid, $lieferung );
                        $patient = $this->CreateBlock( $case->GetDbid(), $parameters, 'patient', $section_uid, $section );
                        $case->AddSection( $patient );
                        $set_case = true;
                    }

                    // Sekundär
                    $secondary = '';
                    if ( substr($extract_data['anlass'], 0, 1 ) === 'r' ) {
                        $lieferung[ 'liefer_id' ]++;
                        $lieferung[ 'ref_id' ] = $ref_id;
                        $section = $this->createSecondarySection( $parameters, $extract_data, $section_uid, $lieferung );
                        $secondary[$extract_data['anlass']] = $this->CreateBlock( $case->GetDbid(), $parameters, 'secondary', $section_uid, $section );
                        $case->AddSection( $secondary[$extract_data['anlass']] );
                        $set_case = true;
                    }

                    // chirurgisch (eingriff)
                    if ((count($extract_data['operationen']) > 0) && ($extract_data['anlass'] === 'p')) {
                        $surgical = '';
                        $i = 0;
                        foreach ($extract_data['operationen'] as $operations) {
                            if (count($operations) === 0) {
                                $i++;
                                continue;
                            }
                            $lieferung[ 'liefer_id' ]++;
                            $lieferung[ 'ref_id' ] = $ref_id;
                            $section = $this->createSurgicalSection( $parameters, $extract_data, $section_uid, $i, $lieferung );
                            $surgical[$extract_data['anlass']][$i] = $this->CreateBlock( $case->GetDbid(), $parameters, 'surgical', $section_uid, $section );
                            $case->AddSection($surgical[$extract_data['anlass']][$i]);
                            $set_case = true;
                            $i++;
                        }
                    }

                    // radiotherapy
                    if ( ( count( $extract_data['strahlen_therapien'] ) > 0 ) &&
                         ( $extract_data['anlass'] === 'p' ) ) {
                        $radioTherapy = '';
                        $i = 0;
                        foreach ($extract_data['strahlen_therapien'] as $radioTherapys) {
                           $lieferung[ 'liefer_id' ]++;
                           $lieferung[ 'ref_id' ] = $ref_id;
                           $section = $this->createRadioTherapySection( $parameters, $extract_data, $section_uid, $i, $lieferung );
                           $radioTherapy[$extract_data['anlass']][$i] = $this->CreateBlock( $case->GetDbid(), $parameters, 'radioTherapy', $section_uid, $section );
                           $case->AddSection($radioTherapy[$extract_data['anlass']][$i]);
                           $set_case = true;
                           $i++;
                        }
                    }

                    // internistical
                    if ( ( count( $extract_data['systemische_therapien'] ) > 0 ) &&
                         ( $extract_data['anlass'] === 'p' ) ) {
                        $internistical = '';
                        $i = 0;
                        foreach ($extract_data['systemische_therapien'] as $systemicTherapys) {
                            $lieferung[ 'liefer_id' ]++;
                            $lieferung[ 'ref_id' ] = $ref_id;
                            $section = $this->createInternisticalSection( $parameters, $extract_data, $section_uid, $i, $lieferung );
                            $internistical[$extract_data['anlass']][$i] = $this->CreateBlock( $case->GetDbid(), $parameters, 'internistical', $section_uid, $section );
                            $case->AddSection($internistical[$extract_data['anlass']][$i]);
                            $set_case = true;
                            $i++;
                        }
                    }

                    // melanom
                    if ( ( substr($extract_data['diagnose'], 0, 3) === 'C43' ) &&
                         ( $extract_data['anlass'] === 'p' ) ) {
                        $melanom = '';
                        $lieferung[ 'liefer_id' ]++;
                        $lieferung[ 'ref_id' ] = $ref_id;
                        $section = $this->createMelanomSection( $parameters, $extract_data, $section_uid, $lieferung );
                        $melanom[$extract_data['anlass']] = $this->CreateBlock( $case->GetDbid(), $parameters, 'melanom', $section_uid, $section );
                        $case->AddSection($melanom[$extract_data['anlass']]);
                        $set_case = true;
                    }
                }

                if ( ( $last_patient_id != $extract_data['patient_id'] ) &&
                     ( 0 != $ref_id ) ) {
                    if ( 'tot' === $extract_data[ 'abschlussgrund' ] ) {
                        // closure
                        $closure = '';
                        if ( ( strlen( $extract_data['todesdatum'] ) > 0 ) ||
                             ( strlen( $extract_data['letzte_patienteninformation'] ) > 0 ) ||
                             ( strlen( $extract_data['tumorassoziation'] ) > 0 ) ) {
                            $lieferung[ 'liefer_id' ]++;
                            $lieferung[ 'ref_id' ] = $ref_id;
                            $section = $this->createClosureSection( $parameters, $extract_data, $section_uid, $lieferung );
                            $closure = $this->CreateBlock( $case->GetDbid(), $parameters, 'closure', $section_uid, $section );
                            $case->AddSection($closure);
                            $set_case = true;
                        }
                    }
                }

                if ( $last_patient_id != $extract_data['patient_id'] ) {
                    $last_patient_id = $extract_data['patient_id'];
                }

                if ( true === $set_case ) {
                    $export_record->AddCase( $case );
                    $set_case = false;
                }
            }
        }
    }

    public function PreparingData( $parameters, &$export_record )
    {
    }

    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
    }

    public function CheckData( $parameters, &$export_record )
    {
        // Hier jeden Abschnitt gegen XSD Pr?fen und Fehler in DB schreiben...
        $serialiser = new Conkeyline_1_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path, $this->GetExportName(),
                             $this->m_smarty, $this->m_db, $this->m_error_function );
        $serialiser->SetData( $export_record );
        $serialiser->Validate( $this->m_parameters );
    }

    public function WriteData()
    {
        $this->m_export_record->SetFinished( true );
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD pr?fen..
        $serialiser = new Conkeyline_1_0_Serialiser();
        $serialiser->Create( $this->m_absolute_path, $this->GetExportName(),
                             $this->m_smarty, $this->m_db, $this->m_error_function );
        $serialiser->SetData( $this->m_export_record );
        $this->m_export_filename = $serialiser->Write( $this->m_parameters );
        $this->m_export_record->Write( $this->m_db );
    }


    //*********************************************************************************************
    //
    // Helper functions
    //
    protected function createPatientSection( $parameters, $extract_data, &$section_uid, $lieferung )
    {
        $patient = array();
        $patient['lieferung']           = $lieferung;
        $patient['patient_id']          = $extract_data['referenznr'];
        $patient['titel']               = $extract_data['titel'];
        $patient['geburtsname']         = $extract_data['geburtsname'];
        $patient['geschlecht']          = strtoupper($extract_data[ 'geschlecht']);
        $patient['strasse']             = $extract_data['strasse'];
        $patient['hnr']                 = $extract_data['hausnummer'];
        $patient['ort']                 = $extract_data['wohnort'];
        $patient['plz']                 = $extract_data['plz'];

        $patient['ausland']             = '';
        if (strlen($extract_data['land']) > 0) {
            $patient['ausland'] = $extract_data['land'] === 'D' ? '' : 'on';
        }

        $patient['kassenschluessel']    = $extract_data['kassenschluessel'];
        $patient['kassenname']          = $extract_data['kassenname'];

        $patient['versichertengruppe'] = '';
        if ( strlen( $extract_data['versichertengruppe'] ) > 0 ) {
            switch ( $extract_data['versichertengruppe'] ) {
                case 1:
                    $patient['versichertengruppe'] = 'M';
                    break;
                case 3:
                    $patient['versichertengruppe'] = 'F';
                    break;
                case 5:
                    $patient['versichertengruppe'] = 'R';
                    break;
            }
        }
        $patient['nachsorgepassnummer'] = $extract_data['nachsorgepassnummer'];

        $patient['bemerkungen'] = $extract_data['bemerkungen'];
        $section_uid = 'PAT_' . $extract_data['referenznr'];

        return $patient;
    }


    protected function createFirstIntroductionSection( $parameters, $extract_data, &$section_uid, $lieferung )
    {
        $firstIntroduction = array();
        $ts_diag = $this->GetNewestTumorstatus( $extract_data[ 'tumorstats' ] );

        $section_uid = 'ERS_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'];

        $firstIntroduction['lieferung']        = $lieferung;
        $firstIntroduction['patient_id']       = $extract_data['referenznr'];
        $firstIntroduction['diagnose_icd']     = $ts_diag[ 'diagnose' ];
        $firstIntroduction['diagnose_text']    = $ts_diag[ 'diagnose_text' ];
        $firstIntroduction['diagnose_datum']   = date("d.m.Y", strtotime($ts_diag[ 'datum_sicherung' ]));
        $firstIntroduction['nicht_gesichert']  = '';
        $firstIntroduction['histologisch']     = $extract_data['histology'] === array() ? '' : 'on';
        $firstIntroduction['zytologisch']      = $extract_data['zytology'] == '1' ? 'on' : '';
        $firstIntroduction['nicht_gesichert']  = $firstIntroduction['histologisch'] === 'on' || $firstIntroduction['zytologisch'] === 'on' ? '' : 'on';
        $firstIntroduction['lokalisation']     = $extract_data['lokalisation'];
        $firstIntroduction['lokalisation_txt'] = $extract_data['lokalisation_txt'];
        $seitenlokalisation = '8';
        if (strlen($extract_data[ 'seitenlokalisation' ]) > 0 ) {
            switch ($extract_data[ 'seitenlokalisation'] ) {
                case 'R':
                    $seitenlokalisation = '1';
                    break;
                case 'L':
                    $seitenlokalisation = '2';
                    break;
                case 'B':
                    $seitenlokalisation = '3';
                    break;
            }
        }
        $firstIntroduction['seitenlokalisation']      = $seitenlokalisation;

        $ts_morph = $this->getNewestMorphology($extract_data['tumorstats']);
        $firstIntroduction['histologie_zytologie']      = $ts_morph['morphologie_text'];
        $firstIntroduction['histologie_zytologie_code'] = $ts_morph['morphologie'];

        $ts_histo = $this->getNewestPathologe($extract_data['histologien']);
        $user_name = "";
        $histo_no = "";
        if (false !== $ts_histo) {
            $_user = $this->GetUser($ts_histo['user_id']);
            if (false !== $_user) {
                $user_name = concat( array(
                    concat( array(
                        $_user[ 'vorname' ],
                        $_user[ 'nachname' ],
                     ), ' ' ),
                    concat( array(
                        $_user[ 'strasse' ],
                        $_user[ 'hausnr' ],
                    ), ' ' ),
                    concat( array(
                        $_user[ 'plz' ],
                        $_user[ 'ort' ],
                    ), ' ' ),
                ), ', ' );
            }
            $histo_no = $ts_histo['histologie_nr'];
        }
        $firstIntroduction['pathologe']                  = $user_name;
        $firstIntroduction['histologie_nummer']          = $histo_no;

        //Mamma
        $firstIntroduction['mamma_histologie_code']      = '';
        $firstIntroduction['mamma_histologie_txt']       = '';
        $firstIntroduction['m_menopausenstatus']         = '';
        $firstIntroduction['m_keine_tumgroesse_keineop'] = '';
        $firstIntroduction['m_groesse_cm']               = '';
        $firstIntroduction['m_hormonrezeptor_status']    = '';
        $firstIntroduction['m_her2_neu_status']          = '';
        $firstIntroduction['m_sln_biopsie']              = '';
        $firstIntroduction['m_axilladissektion']         = '';
        $firstIntroduction['m_gesamt_befallen']          = '';
        $firstIntroduction['m_gesamt_entnommen']         = '';
        $firstIntroduction[ 'm_lkop_nein' ]              = '';

        if ($extract_data['erkrankung'] === 'b') {
            $firstIntroduction['m_hormonrezeptor_status']    = '4';
            $tumor = $this->getNewestMorphology($extract_data['tumorstats']);
            $firstIntroduction['mamma_histologie_code']  = 'M' . $tumor['morphologie'];
            $firstIntroduction['mamma_histologie_txt']   = $tumor['morphologie_text'];

            // Menopausenstatus
            $firstIntroduction['m_menopausenstatus']     = $this->getMenopause($extract_data['anamnesen']);

            $tSize = $extract_data[ 'max_groesse' ];
            if ($tSize !== null) {
                $firstIntroduction['m_groesse_cm']       = number_format( floatval( $tSize ) / 10.0, 1);
            }
            else {
                $firstIntroduction['m_keine_tumgroesse_keineop'] = 'on';
            }
            $firstIntroduction['m_hormonrezeptor_status']    = '';
            $firstIntroduction['m_hormonrezeptor_status']    = $this->getHormoneRezeptor($extract_data['estrogen_irs'], $extract_data['progest_irs']);

            // Her2
            $firstIntroduction[ 'm_her2_neu_status' ] = $this->getHer2($extract_data['her2'], $extract_data['her2_fish_methode'], $extract_data['her2_urteil']);
            $firstIntroduction[ 'm_lkop_nein' ]                = 'on';

            foreach ($extract_data['operationen'] as $ops) {
                if (array_key_exists('ops_codes', $ops) === true) {
                    foreach ($ops['ops_codes'] as $op) {
                        if ($op['prozedur'] === '5-401.11' || $op['prozedur'] === '5-401.12' || $op['prozedur'] === '5-e21.y') {
                            $firstIntroduction[ 'm_sln_biopsie' ] = 'on';
                            $firstIntroduction['m_lkop_nein']   = '';
                            if (substr($op['prozedur'], 0, 7) === '5-402.1' ||
                                substr($op['prozedur'], 0, 7) === '5-404.0' ||
                                substr($op['prozedur'], 0, 7) === '5-406.1' ||
                                substr($op['prozedur'], 0, 5) === '5-871' ||
                                substr($op['prozedur'], 0, 5) === '5-873' ||
                                substr($op['prozedur'], 0, 9) === '5-875.0-2') {
                                $firstIntroduction[ 'm_axilladissektion' ] = 'on';
                            }
                        }
                    }
                }

                $firstIntroduction[ 'm_gesamt_befallen' ]          = $extract_data['lk_bef'];
                $firstIntroduction[ 'm_gesamt_entnommen' ]         = $extract_data['lk_entf'];
            }
        }
        // End Mamma
        $firstIntroduction['basaliom_histologie_code']  = '';
        $firstIntroduction['basaliom_histologie_txt']   = '';
        $firstIntroduction['nein']                      = 'on';
        $ts_diag = $this->getNewestMorphology($extract_data['tumorstats']);
        if ((substr($extract_data['lokalisation'], 0 ,4) === 'C44.' || substr($extract_data['lokalisation'], 0 ,4) === 'C53.') &&
            ($ts_diag['morphologie'] === '8090/3' || $ts_diag['morphologie'] === '8091/3' ||
             $ts_diag['morphologie'] === '8092/3' || $ts_diag['morphologie'] === '8093/3' || $ts_diag['morphologie'] === '8094/3' ||
             $ts_diag['morphologie'] === '8095/3' || $ts_diag['morphologie'] === '8097/3' || $ts_diag['morphologie'] === '8098/3')) {
            $firstIntroduction[ 'basaliom_histologie_code' ]  = 'M' . $ts_diag['morphologie'];
            $firstIntroduction[ 'basaliom_histologie_txt' ]   = $ts_diag['morphologie_text'];
            $firstIntroduction[ 'nein' ]                      = '';
         }
        $firstIntroduction[ 'kras_faktor' ] = '3';
        $kras = $this->getNewestKras($extract_data['histology']);
        switch ($kras) {
            case 'wild':
                $firstIntroduction[ 'kras_faktor' ] = '1';
                break;
            case 'mut':
                $firstIntroduction[ 'kras_faktor' ] = '2';
                break;
        }

        $firstIntroduction['infKeine'] = 'on';

        $firstIntroduction['tnm_klassifikation_keine'] = '';

        $firstIntroduction['pt_wert'] = '';
        $firstIntroduction['pn_wert'] = '';
        $firstIntroduction['pm_wert'] = '';
        $firstIntroduction['pm_c4_operation_histologie'] = '';
        $firstIntroduction['grading'] = '';
        $firstIntroduction[ 'residualtumor' ] = '';
        $firstIntroduction[ 'lymph_invasion' ] = '';
        $firstIntroduction[ 'venen_invasion' ] = '';

        $firstIntroduction['ct_wert'] = '';
        $firstIntroduction['cn_wert'] = '';
        $firstIntroduction['cm_wert'] = '';

        //vorsicht namen anders wie spez. da sonst überschrieben
        if (in_array($extract_data[ 'erkrankung'], array('ly', 'leu', 'snst')) === true) {
            $firstIntroduction['tnm_klassifikation_keine'] = 'on';
        } else {
            $finding = false;

            if (strlen($extract_data['t_postop']) > 0 && strlen($extract_data['n_postop']) > 0 && strlen($extract_data['m']) > 0) {
               $firstIntroduction['pt_wert']                    = substr($extract_data['t_postop'], 1);
               $firstIntroduction['pt_wert_txt']                = substr($extract_data['t_postop'], 1);
               $firstIntroduction['pt_c4_operation_histologie'] = strlen($extract_data['t_postop']) > 0 ? 'on' : '';
               $firstIntroduction['pn_wert']                    = substr($extract_data['n_postop'], 1);
               $firstIntroduction['pn_wert_txt']                = substr($extract_data['n_postop'], 1);
               $firstIntroduction['pn_c4_operation_histologie'] = strlen($extract_data['n_postop']) > 0 ? 'on' : '';
               $firstIntroduction['pm_wert']                    = substr($extract_data['m'], 1, 2);
               $firstIntroduction['pm_wert_txt']                = substr($extract_data['m'], 1);
               $firstIntroduction['pm_c4_operation_histologie'] = strlen($extract_data['m_postop']) > 0 ? 'on' : '';

               $g = '';

               switch ($extract_data['g']) {
                   case 'X': $g = 'GX';   break;
                   case 'B': $g = 'GB';   break;
                   case '1': $g = 'G1';   break;
                   case '2':
                   case 'L': $g = 'G2';   break;
                   case '3':
                   case 'M': $g = 'G3';   break;
                   case 'H': $g = 'G3-4'; break;
                   case '4': $g = 'G4';   break;
                }

                $firstIntroduction['grading'] = $g;

                $firstIntroduction['residualtumor']  = strlen($extract_data['r']) > 0 ? 'R' . $extract_data['r'] : '' ;
                $firstIntroduction['lymph_invasion'] = strlen($extract_data['l']) > 0 ? 'L' . $extract_data['l'] : '9' ;
                $firstIntroduction['venen_invasion'] = strlen($extract_data['v']) > 0 ? 'V' . $extract_data['v'] : '9' ;

                $finding = true;
            } elseif (strlen($extract_data['ct']) > 0 && strlen($extract_data['cn']) > 0 && strlen($extract_data['cm']) > 0) {
                //vorsicht namen anders wie spez. da sonst überschrieben
                $firstIntroduction['ct_wert']                       = substr($extract_data['ct'], 1);
                $firstIntroduction['ct_wert_txt']                   = substr($extract_data['ct'], 1);
                $firstIntroduction['ct_c2_diagnostische_verfahren' ] = strlen($extract_data['ct']) > 0 ? 'on' : '';
                $firstIntroduction['cn_wert']                       = substr($extract_data['cn'], 1);
                $firstIntroduction['cn_wert_txt']                   = substr($extract_data['cn'], 1);
                $firstIntroduction['cn_c2_diagnostische_verfahren' ] = strlen($extract_data['cn']) > 0 ? 'on' : '';
                $firstIntroduction['cm_wert']                       = substr($extract_data['cm'], 1, 2);
                $firstIntroduction['cm_wert_txt']                   = substr($extract_data['cm'], 1);
                $firstIntroduction['cm_c2_diagnostische_verfahren'] = strlen($extract_data['cm']) > 0 ? 'on' : '';

                $finding = true;
            }

            if ($finding === false) {
                $firstIntroduction['tnm_klassifikation_keine'] = 'on';
            }
        }

        $firstIntroduction['klassifikation'] = '';

        if ($extract_data['erkrankung'] === 'ly') {
            $firstIntroduction[ 'klassifikation' ] = concat(array(
                attach_label(
                    $this->m_config[ 'nhl_who_b' ],
                    $extract_data['nhl_who_b_description']
                ),
                attach_label(
                    $this->m_config[ 'nhl_who_t' ],
                    $extract_data['nhl_who_t_description']
                ),
                attach_label(
                    $this->m_config[ 'hl_who' ],
                    $extract_data['hl_who_description']
                ),
                attach_label(
                    $this->m_config[ 'ann_arbor_stadium' ],
                    $extract_data['ann_arb']
                ),
                attach_label(
                    $this->m_config[ 'ann_arbor_aktivitaetsgrad' ],
                    $extract_data['ann_arb_aktivitaetsgrad']
                ),
                attach_label(
                    $this->m_config[ 'durie_salmon' ],
                    $extract_data['durie_salmon']
                ),
            ), ', ');
        }


        if ($extract_data['erkrankung'] === 'leu') {
            $firstIntroduction[ 'klassifikation' ] = concat(array(
               attach_label(
                   $this->m_config[ 'cll_rai' ],
                   $extract_data['rai']
               ),
               attach_label(
                   $this->m_config[ 'cll_binet' ],
                   $extract_data['binet']
               ),
               attach_label(
                   $this->m_config[ 'aml_fab' ],
                   $extract_data['fab']
               ),
               attach_label(
                   $this->m_config[ 'aml_who' ],
                   $extract_data['aml_who_description']
               ),
               attach_label(
                   $this->m_config[ 'all_egil' ],
                   $extract_data['all_egil_description']
               ),
               attach_label(
                   $this->m_config[ 'mds_fab' ],
                   $extract_data['mds_fab_description']
               ),
               attach_label(
                   $this->m_config[ 'mds_who' ],
                   $extract_data['mds_who_description']
               ),
            ), ', ');
        }


        if ($extract_data['erkrankung'] === 'snst') {
        $firstIntroduction[ 'klassifikation' ] = concat(array(
               attach_label(
                   $this->m_config[ 'nhl_who_b' ],
                   $extract_data['nhl_who_b_description']
               ),
               attach_label(
                   $this->m_config[ 'nhl_who_t' ],
                   $extract_data['nhl_who_t_description']
               ),
               attach_label(
                   $this->m_config[ 'hl_who' ],
                   $extract_data['hl_who_description']
               ),
               attach_label(
                   $this->m_config[ 'nhl_who_b' ],
                   $extract_data['nhl_who_b_description']
               ),
               attach_label(
                   $this->m_config[ 'nhl_who_t' ],
                   $extract_data['nhl_who_t_description']
               ),
               attach_label(
                   $this->m_config[ 'hl_who' ],
                   $extract_data['hl_who_description']
               ),
               attach_label(
                   $this->m_config[ 'ann_arbor_stadium' ],
                   $extract_data['ann_arb']
               ),
               attach_label(
                   $this->m_config[ 'ann_arbor_aktivitaetsgrad' ],
                   $extract_data['ann_arb_aktivitaetsgrad']
               ),
               attach_label(
                   $this->m_config[ 'durie_salmon' ],
                   $extract_data['durie_salmon']
               ),
               attach_label(
                   $this->m_config[ 'cll_rai' ],
                   $extract_data['rai']
               ),
               attach_label(
                   $this->m_config[ 'cll_binet' ],
                   $extract_data['binet']
               ),
               attach_label(
                   $this->m_config[ 'aml_fab' ],
                   $extract_data['fab']
               ),
               attach_label(
                   $this->m_config[ 'aml_who' ],
                   $extract_data['aml_who_description']
               ),
               attach_label(
                   $this->m_config[ 'all_egil' ],
                   $extract_data['all_egil_description']
               ),
               attach_label(
                   $this->m_config[ 'mds_fab' ],
                   $extract_data['mds_fab_description']
               ),
               attach_label(
                   $this->m_config[ 'mds_who' ],
                   $extract_data['mds_who_description']
               ),
           ), ', ');
        }
        $firstIntroduction[ 'klassifikation' ] = substr($firstIntroduction[ 'klassifikation' ], 0, 249);
        $firstIntroduction[ 'regionaere_lymphknoten' ] = 'nicht beurteilbar';


        //maligne_vorerkrankungen
        //vorsicht namen im kompletten block anders wie spez. da sonst überschrieben
        $firstIntroduction['maligne_nein']       = 'on';
        $firstIntroduction['bereits_bekannt']    = '';
        $firstIntroduction['art_erkrankung1']    = '';
        $firstIntroduction['dia_datum1']         = '';
        $firstIntroduction['operation1']         = '';
        $firstIntroduction['hormontherapie1']    = '';
        $firstIntroduction['strahlentherapie1']  = '';
        $firstIntroduction['immuntherapie1']     = '';
        $firstIntroduction['chemotherapie1']     = '';
        $firstIntroduction['sonstige1']          = '';
        $firstIntroduction['art_erkrankung2']    = '';
        $firstIntroduction['dia_datum2']         = '';
        $firstIntroduction['operation2']         = '';
        $firstIntroduction['hormontherapie2']    = '';
        $firstIntroduction['strahlentherapie2']  = '';
        $firstIntroduction['immuntherapie2']     = '';
        $firstIntroduction['chemotherapie2']     = '';
        $firstIntroduction['sonstige2']          = '';
        $firstIntroduction['art_erkrankung3']    = '';
        $firstIntroduction['dia_datum3']         = '';
        $firstIntroduction['operation3']         = '';
        $firstIntroduction['hormontherapie3']    = '';
        $firstIntroduction['strahlentherapie3']  = '';
        $firstIntroduction['immuntherapie3']     = '';
        $firstIntroduction['chemotherapie3']     = '';
        $firstIntroduction['sonstige3']          = '';
        $i = 1;
        if (isset($extract_data['anamnesisDiseases'])) {
            /*

                FIX für Ticket #9794

            if ( count( $extract_data['anamnesisDiseases'] ) > 0 ) {
                $firstIntroduction['maligne_nein']    = '';
            }
            */
            foreach ($extract_data['anamnesisDiseases'] as $anamnes) {
                if ($i >= 4) {
                   break;
                }
                if (substr($anamnes['erkrankung'], 0, 1) === 'C') {
                    $firstIntroduction['maligne_nein']    = '';
                    //
                    // FIX für Ticket #9794
                    //
                    // $firstIntroduction['bereits_bekannt'] = 'on';
                    // break;
                    $firstIntroduction['art_erkrankung' . "$i"] = $anamnes['erkrankung_text'];
                    $firstIntroduction['dia_datum' . "$i"] = '';
                    if ( strlen( $anamnes['jahr'] ) > 0 ) {
                        $firstIntroduction['dia_datum' . "$i"] = '01.06.' . $anamnes['jahr'];
                    }
                    $firstIntroduction['operation' . "$i"] = '';
                    if ($anamnes['therapie1'] === 'o' || $anamnes['therapie2'] === 'o' || $anamnes['therapie3'] === 'o') {
                        $firstIntroduction['operation' . "$i"] = 'on';
                    }
                    $firstIntroduction['hormontherapie' . "$i"] = '';
                    if ($anamnes['therapie1'] === 'ho' || $anamnes['therapie2'] === 'ho' || $anamnes['therapie3'] === 'ho') {
                        $firstIntroduction['hormontherapie' . "$i"] = 'on';
                    }
                    $firstIntroduction['strahlentherapie' . "$i"] = '';
                    if ($anamnes['therapie1'] === 's' || $anamnes['therapie2'] === 's' || $anamnes['therapie3'] === 's' || $anamnes['therapie1'] === 'so' || $anamnes['therapie2'] === 'so' || $anamnes['therapie3'] === 'so') {
                        $firstIntroduction['strahlentherapie' . "$i"] = 'on';
                    }
                    $firstIntroduction['immuntherapie' . "$i"] = '';
                    if ($anamnes['therapie1'] === 'im' || $anamnes['therapie2'] === 'im' || $anamnes['therapie3'] === 'im') {
                        $firstIntroduction['immuntherapie' . "$i"] = 'on';
                    }
                    $firstIntroduction['chemotherapie' . "$i"] = '';
                    if ($anamnes['therapie1'] === 'c' || $anamnes['therapie2'] === 'c' || $anamnes['therapie3'] === 'c' || $anamnes['therapie1'] === 'cs' || $anamnes['therapie2'] === 'cs' || $anamnes['therapie3'] === 'cs') {
                        $firstIntroduction['chemotherapie' . "$i"] = 'on';
                    }
                    $firstIntroduction['sonstige' . "$i"] = '';
                    if ($anamnes['therapie1'] === 'sonst' || $anamnes['therapie2'] === 'sonst' || $anamnes['therapie3'] === 'sonst') {
                        $firstIntroduction['sonstige' . "$i"]         = 'on';
                    }
                    $i++;
                }
            }
        }

        $ts_meta = $this->getNewestMetastases($extract_data['metastasen_lokalisationen']);

        $firstIntroduction['fernmetastasen_nein']   = '';
        $firstIntroduction['meta_lokalisation']     = '';
        $firstIntroduction['meta_lokalisation_txt'] = '';
        $firstIntroduction['datum']                 = '';

        if ($ts_meta !== false) {
            $firstIntroduction['meta_lokalisation']       = $ts_meta['lokalisation'];
            $firstIntroduction['meta_lokalisation_txt']   = $ts_meta['lokalisation_text'];
            $firstIntroduction['datum']                   = date("d.m.Y", strtotime($ts_meta['datum_sicherung']));
        } else {
           $firstIntroduction['fernmetastasen_nein'] = 'on';
        }

        $firstIntroduction['behandlung_keine'] = 'on';
        $firstIntroduction['weitere_Boegen'] = '';
        $noTherapy = '0';
        if ( ( count( $extract_data['systemische_therapien'] ) == 0 ) ||
             ( count( $extract_data['strahlen_therapien'] ) == 0 ) ||
             ( count( $extract_data['sonstige_therapien'] ) == 0 ) ) {
           $noTherapy = '1';
        }
        foreach ($extract_data['operationen'] as $ops) {
            if (count($ops) > 0) {
                if ($ops['art_primaertumor'] === '1' || $ops['art_lk'] === '1' || $ops['art_metastasen'] === '1' || $ops['art_rezidiv'] === '1' || $ops['art_nachresektion'] === '1' || $ops['art_revision'] === '1' || $noTherapy === '0') {

                    $firstIntroduction['behandlung_keine'] = '';
                    $firstIntroduction['weitere_Boegen'] = 'on';
                }
            }
        }

        $firstIntroduction['bemerkungen'] = "";
       if ( strlen( $extract_data['kr_meldung']['bem'] ) > 0 ) {
           $firstIntroduction['bemerkungen']          = substr($extract_data['kr_meldung']['bem'], 0, 3999);
       }

        // Fix for #17334
        $firstIntroduction['patient_verstorben']   = '';
        $firstIntroduction['keine_therapie']       = '';
        $firstIntroduction['zytostatika_therapie'] = '';
        $firstIntroduction['hormon_therapie']      = '';
        $firstIntroduction['immun_therapie']       = '';

        if ($extract_data['abschluss'] === 'tot') {
            $firstIntroduction['patient_verstorben']   = 'tot' ;
            $firstIntroduction['patient_verstorben']   = '';
            $firstIntroduction['operative_therapie']   = '';
            $firstIntroduction['strahlen_therapie']    = '';
            $firstIntroduction['patient_nachsorge']    = '';
        } else {
            $firstIntroduction['operative_therapie']   = $extract_data['operationen'] !== array() ? 'on' : '';
            $firstIntroduction['strahlen_therapie']    = $extract_data['strahlen_therapien'] !== array() ? 'on' : '';
            foreach ($extract_data['systemische_therapien'] as $sysTherapy) {
                if ($sysTherapy['art'] === 'ci' || $sysTherapy['art'] === 'cst' || $sysTherapy['art'] === 'c') {
                    $firstIntroduction['zytostatika_therapie'] = 'on';
                }
                if ($sysTherapy['art'] === 'ah' || $sysTherapy['art'] === 'ahst') {
                    $firstIntroduction['hormon_therapie'] = 'on';
                }
                if ($sysTherapy['art'] === 'i' || $sysTherapy['art'] === 'ist') {
                    $firstIntroduction['immun_therapie'] = 'on';
                }
            }
            if ($firstIntroduction['operative_therapie'] !== 'on' &&  $firstIntroduction['strahlen_therapie'] !== 'on' &&
                $firstIntroduction['zytostatika_therapie'] !== 'on' && $firstIntroduction['hormon_therapie'] !== 'on' &&
                $firstIntroduction['immun_therapie'] !== 'on') {
                $firstIntroduction['keine_therapie']      = 'on';
            }
            $firstIntroduction['patient_nachsorge']    = $extract_data['nachsorgen'] !== array() ? 'on' : '';
        }

        $user_name = "";
        $_user = $this->GetUser( $extract_data[ 'behandler_id' ] );
        if ( $_user !== false ) {
            $user_name = concat( array(
                concat( array(
                    $_user[ 'vorname' ],
                    $_user[ 'nachname' ],
                 ), ' ' ),
                concat( array(
                    $_user[ 'strasse' ],
                    $_user[ 'hausnr' ],
                ), ' ' ),
                concat( array(
                    $_user[ 'plz' ],
                    $_user[ 'ort' ],
                ), ' ' ),
            ), ', ' );
        }
       $firstIntroduction['hausarzt']             = $user_name;
       $firstIntroduction['anlass_erfassung']     = '9';
       $discover = $this->getNewestDiscover($extract_data['anamnesen']);
       if (strlen($discover['entdeckung']) > 0) {
           switch ($discover['entdeckung']) {
               case 'su':
                   $firstIntroduction['anlass_erfassung']     = '3';
                   break;
               case 'gf':
               case 'nv':
                   $firstIntroduction['anlass_erfassung']     = '2';
                   break;
               case 'ts':
               case 'ze':
                   $firstIntroduction['anlass_erfassung']     = '6';
                   break;
               case 'ns':
                   $firstIntroduction['anlass_erfassung']     = '5';
                   break;
               case 'sc':
                   $firstIntroduction['anlass_erfassung']     = $extract_data['erkrankung'] === 'b' ? '7' : '9';
                   break;
            }
        }

        return $firstIntroduction;
    }


    protected function createSecondarySection( $parameters, $extract_data, &$section_uid, $lieferung )
    {

        $secondary = array();
        $secondary['lieferung']                      = $lieferung;
        $secondary['patient_id']                     = $extract_data['referenznr'];
        $secondary['datum']                          = date("d.m.Y", strtotime($extract_data['newestTumorstatus']['datum_sicherung']));
        $secondary['infiltration_metastase_rezidiv'] = '9';

        $section_uid = 'SEK_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'];

        $counter = 0;
        if ($extract_data['newestTumorstatus']['rezidiv_metastasen'] === '1') {
            $counter++;
            $secondary['infiltration_metastase_rezidiv'] = '4';
        }
        if ($extract_data['newestTumorstatus']['rezidiv_lk'] === '1') {
            $counter++;
            $secondary['infiltration_metastase_rezidiv'] = '3';
        }
        if ($extract_data['newestTumorstatus']['rezidiv_lokal'] === '1') {
            $counter++;
            $secondary['infiltration_metastase_rezidiv'] = '5';
        }
        $secondary['infiltration_metastase_rezidiv'] = $counter >= 2 ? '9' : $secondary['infiltration_metastase_rezidiv'];
        if (($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') &&
            ($extract_data['newestTumorstatus']['rezidiv_lokal'] === '1' || $extract_data['newestTumorstatus']['rezidiv_lk'] === '1' || $extract_data['newestTumorstatus']['rezidiv_metastasen'] === '1')) {
            $secondary['infiltration_metastase_rezidiv'] = '7';
        }

        $ts_meta = $this->getNewestMetastases($extract_data['metastasen_lokalisationen']);

        $secondary['lokalisation']     = $ts_meta['lokalisation'];
        $secondary['lokalisation_txt'] = $ts_meta['lokalisation_text'];

        // #16169
        if ($secondary['lokalisation'] === null && $extract_data['newestTumorstatus'] !== false) {
            $secondary['lokalisation']     = $extract_data['newestTumorstatus']['lokalisation'];
            $secondary['lokalisation_txt'] = $extract_data['newestTumorstatus']['lokalisation_text'];
        }

        $secondary['seitenlokalisation']             = '8';
        $side = '';
        if (strlen($extract_data['seitenlokalisation']) > 0) {
            switch ($extract_data['seitenlokalisation'] ) {
                case 'R':
                    $secondary['seitenlokalisation'] = '1';
                    break;
                case 'L':
                    $secondary['seitenlokalisation'] = '2';
                    break;
                case 'B':
                    $secondary['seitenlokalisation'] = '3';
                    break;
            }
        }
        $secondary['differenzierung']                = '5';
        $g = '';
        if (strlen($extract_data['g']) > 0 ) {
            switch ($extract_data['g']) {
                case '1':
                    $secondary['differenzierung'] = '1';
                    break;
                case '2':
                    $secondary['differenzierung'] = '2';
                    break;
                case '3':
                case 'H':
                    $secondary['differenzierung'] = '3';
                    break;
                case '4':
                    $secondary['differenzierung'] = '4';
                    break;
            }
        }

        //Mamma
        $secondary['mamma_histologie_code']      = '';
        $secondary['mamma_histologie_txt']       = '';
        $secondary['m_menopausenstatus']         = '';
        $secondary['m_keine_tumgroesse_keineop'] = '';
        $secondary['m_groesse_cm']               = '';
        $secondary['m_hormonrezeptor_status']    = '';
        $secondary['m_her2_neu_status']          = '';
        $secondary['m_lkop_nein']                = '';
        $secondary['m_sln_biopsie']              = '';
        $secondary['m_axilladissektion']         = '';
        $secondary['m_gesamt_befallen']          = '';
        $secondary['m_gesamt_entnommen']         = '';

        // BRUST
        if ($extract_data['erkrankung'] === 'b') {
            $tumor = $this->getNewestMorphology($extract_data['tumorstats']);
            $secondary['mamma_histologie_code']   = 'M' . $tumor['morphologie'];
            $secondary['mamma_histologie_txt']    = $tumor['morphologie_text'];

            // Menopausenstatus
            $secondary['m_menopausenstatus']      = $this->getMenopause($extract_data['anamnesen']);

            $tSize = $extract_data[ 'max_groesse' ];
            if ( strlen( $tSize ) > 0 ) {
                $firstIntroduction['m_groesse_cm']       = number_format( ( floatval( $tSize ) / 10.0 ), 1);
            }
            else {
                $firstIntroduction['m_keine_tumgroesse_keineop'] = 'on';
            }
            $firstIntroduction['m_hormonrezeptor_status']    = '4';
            $secondary['m_hormonrezeptor_status'] = $this->getHormoneRezeptor($extract_data['estrogen_irs'], $extract_data['progest_irs']);

            $secondary['m_her2_neu_status']       = $this->getHer2($extract_data['her2'], $extract_data['her2_fish_methode'], $extract_data['her2_urteil']);


            $secondary[ 'm_lkop_nein' ]                = 'on';
            foreach ($extract_data['operationen'] as $ops) {
                if (array_key_exists('ops_codes', $ops)) {
                    foreach ($ops['ops_codes'] as $op) {
                        if ($op['prozedur'] === '5-401.11' || $op['prozedur'] === '5-401.12' || $op['prozedur'] === '5-e21.y') {
                            $secondary[ 'm_sln_biopsie' ] = 'on';
                            $secondary[ 'm_lkop_nein' ]   = '';
                        }
                        if (substr($op['prozedur'], 0, 7) === '5-402.1' ||
                            substr($op['prozedur'], 0, 7) === '5-404.0' ||
                            substr($op['prozedur'], 0, 7) === '5-406.1' ||
                            substr($op['prozedur'], 0, 5) === '5-871' ||
                            substr($op['prozedur'], 0, 5) === '5-873' ||
                            substr($op['prozedur'], 0, 9) === '5-875.0-2') {
                            $secondary[ 'm_axilladissektion' ] = 'on';
                        }
                    }
                }
            }

            $secondary[ 'm_gesamt_befallen' ]          = $extract_data['lk_bef'];
            $secondary[ 'm_gesamt_entnommen' ]         = $extract_data['lk_entf'];
        }
        // ENDE BRUST

        $secondary['ct_wert']                       = substr($extract_data['ct'], 1);
        $secondary['ct_wert_txt']                   = substr($extract_data['ct'], 1);
        $secondary['ct_c2_diagnostische_verfahren'] = strlen($extract_data['ct']) > 0 ? 'on' : '';
        $secondary['cn_wert']                       = substr($extract_data['cn'], 1);
        $secondary['cn_wert_txt']                   = substr($extract_data['cn'], 1);
        $secondary['cn_c2_diagnostische_verfahren'] = strlen($extract_data['cn']) > 0 ? 'on' : '';
        $secondary['cm_wert']                       = substr($extract_data['cm'], 1, 2);
        $secondary['cm_wert_txt']                   = substr($extract_data['cm'], 1);
        $secondary['cm_c2_diagnostische_verfahren'] = strlen($extract_data['cm']) > 0 ? 'on' : '';
        $secondary['pt_wert']                    = substr($extract_data['t_postop'], 1);
        $secondary['pt_wert_txt']                = substr($extract_data['t_postop'], 1);
        $secondary['pn_wert']                    = substr($extract_data['n_postop'], 1);
        $secondary['pn_wert_txt']                = substr($extract_data['n_postop'], 1);
        $secondary['pm_wert']                    = substr($extract_data['m'], 1, 2);
        $secondary['pm_wert_txt']                = substr($extract_data['m'], 1);

        $secondary[ 'grading' ]         = '';
        switch ($extract_data['g']) {
            case 'X':
                $secondary['grading'] = 'GX';
                break;
            case 'B':
                $secondary['grading'] = 'GB';
                break;
            case '1':
                $secondary['grading'] = 'G1';
                break;
            case '2':
            case 'L':
                $secondary['grading'] = 'G2';
                break;
            case '3':
            case 'M':
                $secondary['grading'] = 'G3';
                break;
            case 'H':
                $secondary['grading'] = 'G3-4';
                break;
            case '4':
                $secondary['grading'] = 'G4';
                break;
        }

        $secondary['residualtumor']       = strlen($extract_data['r']) > 0 ? 'R' . $extract_data['r'] : '' ;

        $ts_histo = $this->getOldestHistology($extract_data['histologien']);
        $user_name = "";
        $histologie_nr = "";
        if (false !== $ts_histo) {
            $_user = $this->GetUser($ts_histo['user_id']);
            if (false !== $_user) {
                $user_name = concat( array(
                    concat( array(
                        $_user[ 'vorname' ],
                        $_user[ 'nachname' ],
                     ), ' ' ),
                    concat( array(
                        $_user[ 'strasse' ],
                        $_user[ 'hausnr' ],
                    ), ' ' ),
                    concat( array(
                        $_user[ 'plz' ],
                        $_user[ 'ort' ],
                    ), ' ' ),
                ), ', ' );
            }
            $histologie_nr = $ts_histo['histologie_nr'];
        }
        $secondary['pathologe']     = $user_name;
        $secondary['befundnummer']  = $histologie_nr;

        $secondary['bemerkung_lst'] = substr($this->getNewestTumorstatusComment($extract_data['tumorstats']), 0, 3999);

        return $secondary;
    }



    protected function createSurgicalSection( $parameters, $extract_data, &$section_uid, $i, $lieferung )
    {
        $surgical = array();
        $surgical['lieferung']                = $lieferung;
        $surgical['patient_id']               = $extract_data['referenznr'];
        $ts = $this->getNewestTumorstatus($extract_data['tumorstats']);
        $surgical['diagnose_icd']             = $ts['diagnose'];
        $surgical['diagnose_txt']             = $ts['diagnose_text'];
        $surgical['diagnose_datum']           = date("d.m.Y", strtotime($ts['datum_sicherung']));

        $section_uid = 'OPE_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'] . "_" . $i;

        $op = $extract_data['operationen'][$i];

        $operateur_name = "";
        $_operateur = $this->GetUser( $op[ 'operateur1_id' ] );
        if ( $_operateur !== false ) {
            if ( strlen( $_operateur[ 'titel' ] ) > 0 ) {
                $operateur_name = $_operateur[ 'titel' ]. " ";
            }
            $operateur_name .= $_operateur[ 'nachname' ] . ", " . $_operateur[ 'vorname' ];
        }
        $surgical['operateur']                = $operateur_name;
        $surgical['operation_datum']          = date("d.m.Y", strtotime($op['beginn']));
        $surgical['ops_code1']                = '';
        $surgical['ops_txt1']                 = '';
        $surgical['ops_code2']                = '';
        $surgical['ops_txt2']                 = '';
        $surgical['ops_code3']                = '';
        $surgical['ops_txt3']                 = '';
        $surgical['ops_code4']                = '';
        $surgical['ops_txt4']                 = '';
        $surgical['ops_code5']                = '';
        $surgical['ops_txt5']                 = '';
        $surgical['ops_code6']                = '';
        $surgical['ops_txt6']                 = '';
        $surgical['ops_code7']                = '';
        $surgical['ops_txt7']                 = '';
        $surgical['ops_code8']                = '';
        $surgical['ops_txt8']                 = '';
        $surgical['ops_code9']                = '';
        $surgical['ops_txt9']                 = '';
        $surgical['ops_code10']               = '';
        $surgical['ops_txt10']                = '';
        $i = 0;
        if (array_key_exists('ops_codes', $op)) {
            foreach ($op['ops_codes'] as $ops) {
                if (strpos($ops['prozedur'], ".") === false) {
                    continue;
                }
                $ops_code = $ops['prozedur'];
                if ( '-e' === substr( $ops_code, 1, 2 ) ) {
                    continue;
                }
                $i++;
                if ($i >= 11) {
                    break;
                }
                $surgical['ops_code' . $i]        = $ops_code;
                $surgical['ops_txt' . $i]         = $ops['prozedur_text'];
                if ( strlen( $ops['prozedur_text'] ) == 0 ) {
                    $surgical['ops_txt' . $i]     = $ops_code;
                }
            }
        }
        $surgical['op_ergebnis']              = '4';
        $surgical['organ_primaersitz']        = $op['art_primaertumor'] === '1' ? 'on' : '';
        $surgical['regionaere_lk']            = $op['art_lk'] === '1' ? 'on' : '';
        $surgical['lokalrezidiv']             = $op['art_rezidiv'] === '1' ? 'on' : '';
        $surgical['fernmetastasen']           = $op['art_metastasen'] === '1' ? 'on' : '';

        $surgical['systemerkrankung']         = '';
        $surgical['rezidiv_systemerkrankung'] = '';

        if ($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') {
           if ($extract_data['anlass'] === 'p') {
              $surgical['systemerkrankung'] = 'on';
           }
           if (substr($extract_data['anlass'], 0, 1) === 'r') {
            $surgical['rezidiv_systemerkrankung'] = 'on';
            }
        }
        $surgical['therapieziel']             = $op['art_diagnostik'] === '1' ? '3' : '';
        switch ($op['intention']) {
            case 'kur':
                $surgical['therapieziel']     = $op['art_diagnostik'] === '1' ? '3' : '1';
                break;
            case 'pal':
                $surgical['therapieziel']     = $op['art_diagnostik'] === '1' ? '3' : '2';
                break;
        }
        $surgical['nein']                     = 'on';
        $surgical['patient_nachsorge']        = 'on';
        $surgical['weiterbehandlung']         = 'siehe Behandlungsboegen';
        $surgical['bemerkungen_lst']          = $op['bem'];

        return $surgical;
    }


    protected function createRadioTherapySection( $parameters, $extract_data, &$section_uid, $i, $lieferung )
    {
        $section_uid = 'BES_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'] . "_" . $i;
        $radioTherapy = array();
        $radioTherapy['lieferung']                   = $lieferung;
        $radioTherapy['patient_id']                  = $extract_data['referenznr'];
        $ts = $this->getNewestTumorstatus($extract_data['tumorstats']);
        $radioTherapy['diagnose_icd']                = $ts['diagnose'];
        $radioTherapy['diagnose_txt']                = $ts['diagnose_text'];
        $radioTherapy['diagnose_datum']              = date("d.m.Y", strtotime($ts['datum_sicherung']));
        $radio = $extract_data['strahlen_therapien'][$i];

        $user_name = "";
        $_user = $this->GetUser( $radio[ 'user_id' ] );
        if ( $_user !== false ) {
            $user_name = concat( array(
                concat( array(
                    $_user[ 'vorname' ],
                    $_user[ 'nachname' ],
                 ), ' ' ),
                concat( array(
                    $_user[ 'strasse' ],
                    $_user[ 'hausnr' ],
                ), ' ' ),
                concat( array(
                    $_user[ 'plz' ],
                    $_user[ 'ort' ],
                ), ' ' ),
            ), ', ' );
        }
        $radioTherapy['behandelnder']                = $user_name;
        $radioTherapy['beginn_therapie']             = date("d.m.Y", strtotime($radio['beginn']));
        $radioTherapy['ende_therapie']               = $radio['ende'] !== '' ? date("d.m.Y", strtotime($radio['ende'])) : '01.01.1900';
        $radioTherapy['strahlenart'] = "Keine Angaben";
        if ( strlen( $radio['art'] ) > 0 ) {
            $radioTherapy['strahlenart']                 = $this->GetLBasicBez( 'wirkstoff', $radio['art'] );
        }

        $radioTherapy['gesamtdosis_gy']              = '';
        if ( strlen( $radio['gesamtdosis'] ) > 0 ) {
            $radioTherapy['gesamtdosis_gy']          = number_format($radio['gesamtdosis'], 1);
        }
        $radioTherapy['bestrahlungsregion']          = substr(concat(array(
            $radio['ziel_mamma_r']            === '1' ? $this->m_config['ziel_mamma_r'] : '',
            $radio['ziel_mamma_l']            === '1' ? $this->m_config['ziel_mamma_l'] : '',
            $radio['ziel_brustwand_r']        === '1' ? $this->m_config['ziel_brustwand_r'] : '',
            $radio['ziel_brustwand_l']        === '1' ? $this->m_config['ziel_brustwand_l'] : '',
            $radio['ziel_mammaria_interna']   === '1' ? $this->m_config['ziel_mammaria_interna'] : '',
            $radio['ziel_axilla_r']           === '1' ? $this->m_config['ziel_axilla_r'] : '',
            $radio['ziel_axilla_l']           === '1' ? $this->m_config['ziel_axilla_l'] : '',
            $radio['ziel_lk_supra']           === '1' ? $this->m_config['ziel_lk_supra'] : '',
            $radio['ziel_lk_para']            === '1' ? $this->m_config['ziel_lk_para'] : '',
            $radio['ziel_knochen']            === '1' ? $this->m_config['ziel_knochen'] : '',
            $radio['ziel_gehirn']             === '1' ? $this->m_config['ziel_gehirn'] : '',
            $radio['ziel_primaertumor']       === '1' ? $this->m_config['ziel_primaertumor'] : '',
            $radio['ziel_prostata']           === '1' ? $this->m_config['ziel_prostata'] : '',
            $radio['ziel_becken']             === '1' ? $this->m_config['ziel_becken'] : '',
            $radio['ziel_abdomen']            === '1' ? $this->m_config['ziel_abdomen'] : '',
            $radio['ziel_vulva']              === '1' ? $this->m_config['ziel_vulva'] : '',
            $radio['ziel_vulva_pelvin']       === '1' ? $this->m_config['ziel_vulva_pelvin'] : '',
            $radio['ziel_vulva_inguinal']     === '1' ? $this->m_config['ziel_vulva_inguinal'] : '',
            $radio['ziel_inguinal_einseitig'] === '1' ? $this->m_config['ziel_inguinal_einseitig'] : '',
            $radio['ziel_ingu_beidseitig']    === '1' ? $this->m_config['ziel_ingu_beidseitig'] : '',
            $radio['ziel_ingu_pelvin']        === '1' ? $this->m_config['ziel_ingu_pelvin'] : '',
            $radio['ziel_vagina']             === '1' ? $this->m_config['ziel_vagina'] : '',
            $radio['ziel_lymph']              === '1' ? $this->m_config['ziel_lymph'] : '',
            $radio['ziel_paraaortal']         === '1' ? $this->m_config['ziel_paraaortal'] : '',
            $radio['ziel_lk']                 === '1' ? $this->m_config['ziel_lk'] : '',
            $radio['ziel_lk_iliakal']         === '1' ? $this->m_config['ziel_lk_iliakal'] : '',
            $radio['ziel_ganzkoerper']        === '1' ? $this->m_config['ziel_ganzkoerper'] : '',
            $radio['ziel_mediastinum']        === '1' ? $this->m_config['ziel_mediastinum'] : '',
            $radio['ziel_lk_zervikal_r']      === '1' ? $this->m_config['ziel_lk_zervikal_r'] : '',
            $radio['ziel_lk_zervikal_l']      === '1' ? $this->m_config['ziel_lk_zervikal_l'] : '',
            $radio['ziel_lk_hilaer']          === '1' ? $this->m_config['ziel_lk_hilaer'] : '',
            $radio['ziel_lk_axillaer_r']      === '1' ? $this->m_config['ziel_lk_axillaer_r'] : '',
            $radio['ziel_lk_axillaer_l']      === '1' ? $this->m_config['ziel_lk_axillaer_l'] : '',
            $radio['ziel_lk_abdominell_o']    === '1' ? $this->m_config['ziel_lk_abdominell_o'] : '',
            $radio['ziel_lk_abdominell_u']    === '1' ? $this->m_config['ziel_lk_abdominell_u'] : '',
            $radio['ziel_lk_iliakal_r']       === '1' ? $this->m_config['ziel_lk_iliakal_r'] : '',
            $radio['ziel_lk_iliakal_l']       === '1' ? $this->m_config['ziel_lk_iliakal_l'] : '',
            $radio['ziel_lk_inguinal_r']      === '1' ? $this->m_config['ziel_lk_inguinal_r'] : '',
            $radio['ziel_lk_inguinal_l']      === '1' ? $this->m_config['ziel_lk_inguinal_l'] : '',
            $radio['ziel_sonst_detail_text'],
        ), ', '), 0, 3999);

        $radioTherapy['therapieergebnis'] = '';
        switch ($radio['best_response']) {
            case 'CR';
                $radioTherapy['therapieergebnis'] = '1';
                break;
            case 'PR';
                $radioTherapy['therapieergebnis'] = '2';
                break;
            case 'SD';
                $radioTherapy['therapieergebnis'] = '4';
                break;
            case 'PD';
                $radioTherapy['therapieergebnis'] = '5';
                break;
            case 'NED';
                $radioTherapy['therapieergebnis'] = '0';
                break;
        }

        $radioTherapy['nein']                        = '';
        $radioTherapy['haematologisch']              = '';
        $radioTherapy['renal']                       = '';
        $radioTherapy['allergie']                    = '';
        $radioTherapy['haarverlust']                 = '';
        $radioTherapy['haut']                        = '';
        $radioTherapy['gehoer']                      = '';
        $radioTherapy['fieber']                      = '';
        $radioTherapy['gastrointestinal']            = '';
        $radioTherapy['pulmonal']                    = '';
        $radioTherapy['kardial']                     = '';
        $radioTherapy['infektion']                   = '';
        $radioTherapy['nerven']                      = '';
        $radioTherapy['schmerzen']                   = '';

        $hn      = '0';
        $sideeff = '0';
        if ($radio['endstatus_grund']      === 'hn' || $radio['endstatus_grund']      === 'htox' ||
            $radio['dosisreduktion_grund'] === 'hn' || $radio['dosisreduktion_grund'] === 'htox' ||
            $radio['unterbrechung_grund']  === 'hn' || $radio['unterbrechung_grund']  === 'htox') {
            $hn = '1';
            $radioTherapy['haematologisch']              = 'on';
            if ($radio['endstatus_grund']      === 'nhn' || $radio['endstatus_grund']      === 'nhtox' ||
                $radio['dosisreduktion_grund'] === 'nhn' || $radio['dosisreduktion_grund'] === 'nhtox' ||
                $radio['unterbrechung_grund']  === 'nhn' || $radio['unterbrechung_grund']  === 'nhtox') {
                $hn = '2';
            }
        }
        foreach($extract_data['sideeffects'] as $se) {
            if ($se['strahlentherapie_id'] === $radio['strahlentherapie_id']) {
                $sideeff = '1';
                $radioTherapy['haematologisch']   = $radioTherapy['haematologisch']   === 'on' || ($se['nci_code'] >=  '5013' && $se['nci_code'] <= '5025') || $hn === '2' ? 'on' : '';
                $radioTherapy['renal']            = $radioTherapy['renal']            === 'on' || ($se['nci_code'] >=  '5812' && $se['nci_code'] <= '5871') ? 'on' : '';
                $radioTherapy['allergie']         = $radioTherapy['allergie']         === 'on' || ($se['nci_code'] >=  '5001' && $se['nci_code'] <= '5006') ? 'on' : '';
                $radioTherapy['haarverlust']      = $radioTherapy['haarverlust']      === 'on' ||  $se['nci_code'] === '5103' ? 'on' : '';
                $radioTherapy['haut']             = $radioTherapy['haut']             === 'on' || ($se['nci_code'] !== '5103' && $se['nci_code'] >= '5095' && $se['nci_code'] <= '5122') ? 'on' : '';
                $radioTherapy['gehoer']           = $radioTherapy['gehoer']           === 'on' || ($se['nci_code'] >=  '5007' && $se['nci_code'] <= '5012') ? 'on' : '';
                $radioTherapy['fieber']           = $radioTherapy['fieber']           === 'on' ||  $se['nci_code'] === '5001' || $se['nci_code'] === '5082' || $se['nci_code'] === '5344' ? 'on' : '';
                $radioTherapy['gastrointestinal'] = $radioTherapy['gastrointestinal'] === 'on' || ($se['nci_code'] >=  '5138' && $se['nci_code'] <= '5277') ? 'on' : '';
                $radioTherapy['pulmonal']         = $radioTherapy['pulmonal']         === 'on' || ($se['nci_code'] >=  '5779' && $se['nci_code'] <= '5811') ? 'on' : '';
                $radioTherapy['kardial']          = $radioTherapy['kardial']          === 'on' || ($se['nci_code'] >=  '5026' && $se['nci_code'] <= '5073') ? 'on' : '';
                $radioTherapy['infektion']        = $radioTherapy['infektion']        === 'on' || ($se['nci_code'] >=  '5343' && $se['nci_code'] <= '5578') ? 'on' : '';
                $radioTherapy['nerven']           = $radioTherapy['nerven']           === 'on' || ($se['nci_code'] >=  '5657' && $se['nci_code'] <= '5703') ? 'on' : '';
                $radioTherapy['schmerzen']        = $radioTherapy['schmerzen']        === 'on' || ($se['nci_code'] >=  '5725' && $se['nci_code'] <= '5778') ? 'on' : '';
            }
        }
        $radioTherapy['nein']              = $sideeff === '0' && $hn === '0' ? 'on' : '';

        $radioTherapy['organ_primaersitz']           = $extract_data['anlass'] === 'p' ? 'on' : '';
        $radioTherapy['lokalrezidiv']                = substr($extract_data['anlass'], 0, 1) === 'r' && $extract_data['newestTumorstatus']['rezidiv_lokal'] === '1' ? 'on' : '';
        $radioTherapy['fernmetastasen']              = substr($extract_data['anlass'], 0, 1) === 'r' && $extract_data['newestTumorstatus']['rezidiv_metastasen'] === '1' ? 'on' : '';

        $radioTherapy['systemerkrankung']            = ($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') && $extract_data['anlass'] === 'p' ? 'on' : '';
        $radioTherapy['rezidiv_systemerkrankung']    = ($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') && substr($extract_data['anlass'], 0, 1) === 'r' ? 'on' : '';;
        $radioTherapy['therapieziel'] = '';
        switch ($radio['intention']) {
            case 'kur':
            case 'kura':
                $radioTherapy['therapieziel'] = '1';
                break;
            case 'pal':
            case 'pala':
            case 'palna':
                $radioTherapy['therapieziel'] = '2';
                break;
            case 'kurna':
                $radioTherapy['therapieziel'] = '4';
                break;
        }
        //vorsicht Name anders wie in spez
        $radioTherapy['mani_nein']         = 'on';
        $radioTherapy['patient_nachsorge'] = 'on';
        $radioTherapy['weiterbehandlung']  = 'siehe Behandlungsbogen';


        return $radioTherapy;
    }



    protected function createInternisticalSection($parameters, $extract_data, &$section_uid, $i, $lieferung)
    {
        $section_uid = 'INT_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'] . "_" . $i;

        $internistical = array();
        $internistical['lieferung']                   = $lieferung;
        $internistical['patient_id']                  = $extract_data['referenznr'];
        $ts = $this->getNewestTumorstatus($extract_data['tumorstats']);
        $internistical['diagnose_icd']                = $ts['diagnose'];
        $internistical['diagnose_txt']                = $ts['diagnose_text'];
        $internistical['diagnose_datum']              = date("d.m.Y", strtotime($ts['datum_sicherung']));

        $systemic = $extract_data['systemische_therapien'][$i];
        $user_name = "";
        $_user = $this->GetUser( $systemic['user_id'] );
        if ( $_user !== false ) {
            $user_name = concat( array(
                concat( array(
                    $_user[ 'vorname' ],
                    $_user[ 'nachname' ],
                 ), ' ' ),
                concat( array(
                    $_user[ 'strasse' ],
                    $_user[ 'hausnr' ],
                ), ' ' ),
                concat( array(
                    $_user[ 'plz' ],
                    $_user[ 'ort' ],
                ), ' ' ),
            ), ', ' );
        }
        $internistical['behandelnder']               = $user_name;
        $internistical['beginn_therapie']            = date("d.m.Y", strtotime($systemic['beginn']));
        $internistical['ende_therapie']              = $systemic['ende'] !== '' ? date("d.m.Y", strtotime($systemic['ende'])) : '01.01.1900';
        $internistical['therapieschema_medikamente'] = '';

        $m = array( 'Keine Angaben' );
        if ( isset( $systemic['vorlage_therapie_id'] ) ) {
            if ( isset($this->m_pattern[$systemic['vorlage_therapie_id']]) &&
                 ( count( $this->m_pattern[$systemic['vorlage_therapie_id']] ) > 0 ) ) {
                $m = array();
                foreach ($this->m_pattern[$systemic['vorlage_therapie_id']] as $medi) {
                    $tmp_w = "Keine Angaben";
                    if ( strlen( $medi ) > 0 ) {
                        $tmp_w = $this->GetLBasicBez('wirkstoff', $medi);
                    }
                    $m[] = $tmp_w;
                }
            }
        }

        $internistical['therapieschema_medikamente'] = concat($m, ', ');

        $circle                                      = $this->getTherapyCircle($systemic['systemische_therapie_id']);
        $internistical['anzahl_kurse']               = isset($circle['0']['i']) ? $circle['0']['i']: '';
        $internistical['angaben_zur_therapie']       = $systemic['endstatus'] === 'abbr' ? $this->GetLBasicBez( 'therapieplan_abweichung_grund', $systemic[ 'endstatus_grund' ] ) : '';
        $internistical['therapieergebnis']           = '';
        switch ($systemic['best_response']) {
            case 'CR':
                $internistical['therapieergebnis'] = '1';
                break;
            case 'PR':
                $internistical['therapieergebnis'] = '2';
                break;
            case 'SD':
                $internistical['therapieergebnis'] = '4';
                break;
            case 'PD':
                $internistical['therapieergebnis'] = '5';
                break;
            case 'NED':
                $internistical['therapieergebnis'] = '0';
                break;
        }

        //Side effects
        $internistical['nein']                       = '';
        $internistical['haematologisch']              = '';
        $internistical['renal']                       = '';
        $internistical['allergie']                    = '';
        $internistical['haarverlust']                 = '';
        $internistical['haut']                        = '';
        $internistical['gehoer']                      = '';
        $internistical['fieber']                      = '';
        $internistical['gastrointestinal']            = '';
        $internistical['pulmonal']                    = '';
        $internistical['kardial']                     = '';
        $internistical['infektion']                   = '';
        $internistical['nerven']                      = '';
        $internistical['schmerzen']                   = '';

        $hn      = '0';
        $sideeff = '0';
        foreach($extract_data['systemische_therapien'] as $sys) {
            if ($sys['endstatus_grund']      === 'hn' || $sys['endstatus_grund']      === 'htox' ||
                $sys['dosisaenderung_grund'] === 'hn' || $sys['dosisaenderung_grund'] === 'htox' ||
                $sys['unterbrechung_grund']  === 'hn' || $sys['unterbrechung_grund']  === 'htox'){
                $hn = '1';
                $internistical['haematologisch']              = 'on';
                if ($sys['endstatus_grund']      === 'nhn' || $sys['endstatus_grund']      === 'nhtox' ||
                    $sys['dosisaenderung_grund'] === 'nhn' || $sys['dosisaenderung_grund'] === 'nhtox' ||
                    $sys['unterbrechung_grund']  === 'nhn' || $sys['unterbrechung_grund']  === 'nhtox') {
                    $hn = '2';
                }
            }
        }

        foreach($extract_data['sideeffects'] as $se) {
            if ($se['therapie_systemisch_id'] === $systemic['systemische_therapie_id']) {
                $sideeff = '1';
                $internistical['haematologisch']   = $internistical['haematologisch']   === 'on' || ($se['nci_code'] >=  '5013' && $se['nci_code'] <= '5025') || $hn === '2' ? 'on' : '';
                $internistical['renal']            = $internistical['renal']            === 'on' || ($se['nci_code'] >=  '5812' && $se['nci_code'] <= '5871') ? 'on' : '';
                $internistical['allergie']         = $internistical['allergie']         === 'on' || ($se['nci_code'] >=  '5001' && $se['nci_code'] <= '5006') ? 'on' : '';
                $internistical['haarverlust']      = $internistical['haarverlust']      === 'on' ||  $se['nci_code'] === '5103' ? 'on' : '';
                $internistical['haut']             = $internistical['haut']             === 'on' || ($se['nci_code'] !== '5103' && $se['nci_code'] >= '5095' && $se['nci_code'] <= '5122') ? 'on' : '';
                $internistical['gehoer']           = $internistical['gehoer']           === 'on' || ($se['nci_code'] >=  '5007' && $se['nci_code'] <= '5012') ? 'on' : '';
                $internistical['fieber']           = $internistical['fieber']           === 'on' ||  $se['nci_code'] === '5001' || $se['nci_code'] === '5082' || $se['nci_code'] === '5344' ? 'on' : '';
                $internistical['gastrointestinal'] = $internistical['gastrointestinal'] === 'on' || ($se['nci_code'] >=  '5138' && $se['nci_code'] <= '5277') ? 'on' : '';
                $internistical['pulmonal']         = $internistical['pulmonal']         === 'on' || ($se['nci_code'] >=  '5779' && $se['nci_code'] <= '5811') ? 'on' : '';
                $internistical['kardial']          = $internistical['kardial']          === 'on' || ($se['nci_code'] >=  '5026' && $se['nci_code'] <= '5073') ? 'on' : '';
                $internistical['infektion']        = $internistical['infektion']        === 'on' || ($se['nci_code'] >=  '5343' && $se['nci_code'] <= '5578') ? 'on' : '';
                $internistical['nerven']           = $internistical['nerven']           === 'on' || ($se['nci_code'] >=  '5657' && $se['nci_code'] <= '5703') ? 'on' : '';
                $internistical['schmerzen']        = $internistical['schmerzen']        === 'on' || ($se['nci_code'] >=  '5725' && $se['nci_code'] <= '5778') ? 'on' : '';
            }
        }

        $internistical['nein']                       = $sideeff === '0' && $hn === '0' ? 'on' : '';

        $internistical['organ_primaersitz']          = $extract_data['anlass'] === 'p' && $extract_data['systemische_therapien'] !== array() ? 'on' : '';
        $internistical['lokalrezidiv']               = substr($extract_data['anlass'], 0, 1) === 'r' && $extract_data['newestTumorstatus']['rezidiv_lokal'] === '1' ? 'on' : '';
        $internistical['fernmetastasen']             = substr($extract_data['anlass'], 0, 1) === 'r' && $extract_data['newestTumorstatus']['rezidiv_metastasen'] === '1' ? 'on' : '';

        $internistical['systemerkrankung']           = ($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') && $extract_data['anlass'] === 'p' ? 'on' : '';
        $internistical['rezidiv_systemerkrankung']   = ($extract_data['erkrankung'] === 'leu' || $extract_data['erkrankung'] === 'ly' || $extract_data['erkrankung'] === 'snst') && substr($extract_data['anlass'], 0, 1) === 'r' ? 'on' : '';

        $internistical['therapieziel']               = '';
        switch ($systemic['intention']) {
            case 'kur':
            case 'kura':
                $internistical['therapieziel'] = '1';
                break;
            case 'pal':
            case 'pala':
            case 'palna':
                $internistical['therapieziel'] = '2';
                break;
            case 'kurna':
                $internistical['therapieziel'] = '4';
                break;
        }

        $internistical['chemotherapie']              = $systemic['art'] === 'c' || $systemic['art'] === 'ci' || $systemic['art'] === 'cst' ? 'on' : '';

        $internistical['stammzelltransplantation']   = '';

        foreach ($extract_data['operationen'] as $ops) {
            if (array_key_exists('ops_codes', $ops)) {
                foreach ($ops['ops_codes'] as $op) {
                    if ($op['prozedur'] === '5-411.00' || $op['prozedur'] === '5-411.02' || $op['prozedur'] === '5-411.24' || $op['prozedur'] === '5-411.25' || $op['prozedur'] === '5-411.26' ||
                        $op['prozedur'] === '5-411.27' || $op['prozedur'] === '5-411.30' || $op['prozedur'] === '5-411.32' || $op['prozedur'] === '5-411.40' || $op['prozedur'] === '5-411.42' ||
                        $op['prozedur'] === '5-411.50' || $op['prozedur'] === '5-411.52' || substr($op['prozedur'], 0, 7) === '5-411.x' || substr($op['prozedur'], 0, 7) === '5-411.y') {
                        $internistical['stammzelltransplantation']   = 'on';
                    }
                }
            }
        }

        $internistical['hormontherapie']             = $systemic['art'] === 'ah' || $systemic['art'] === 'ahst' ? 'on' : '';
        $internistical['immuntherapie']              = $systemic['art'] === 'i' || $systemic['art'] === 'ist' ? 'on' : '';
        $internistical['andere_text']                = '';

        if ($systemic['art'] === 'son' || $systemic['art'] === 'sonstr') {

            if (strlen( $systemic['vorlage_therapie_id']) > 0) {
                $internistical['andere_text'] = $this->getTherapyDescription($systemic['vorlage_therapie_id'], 'vorlage_therapie');
            } else {
                $internistical['andere_text'] = $this->getTherapyDescription( $systemic[ 'sonstige_therapie_id'], 'sonstige_therapie');
            }

        }

        $internistical['neue_manifestationen']       = 'on';
        $internistical['patient_nachsorge']          = 'on';
        $internistical['weiterbehandlung']           = 'siehe Behandlungsbogen';

        return $internistical;
    }


    protected function createClosureSection($parameters, $extract_data, &$section_uid, $lieferung)
    {
        $section_uid = 'ABS_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['abschluss_id'];
        $closure = array();
        $closure['lieferung']           = $lieferung;
        $closure['patient_id']          = $extract_data['referenznr'];
        $closure['todesdatum']          = $this->FormatDate( $extract_data['todesdatum'] );
        $closure['letzter_kontakt'] = '';
        if ( strlen( $extract_data['letzte_patienteninformation'] ) > 0 ) {
            $closure['letzter_kontakt'] = $this->FormatDate( $extract_data['letzte_patienteninformation'] );
        }
        $closure['nicht_tumorbedingt']  = $extract_data['tumorassoziation'] === 'totnt' ? 'on' : '';
        $closure['tumorbedingt']        = $extract_data['tumorassoziation'] === 'tott' ? 'on' : '';
        $closure['therapiebedingt']     = $extract_data['tumorassoziation'] === 'totn' ? 'on' : '';
        $closure['nicht_entscheidbar']  = $extract_data['tumorassoziation'] === 'totnb' ? 'on' : '';

        return $closure;
    }


    protected function createMelanomSection($parameters, $extract_data, &$section_uid, $lieferung)
    {
       $section_uid = 'MAL_' . $extract_data['referenznr'] . "_" . $extract_data['erkrankung_id'] . "_" . $extract_data['diagnose_seite'];

        $melanom = array();
        $melanom['lieferung']               = $lieferung;
        $melanom['patient_id']              = $extract_data['referenznr'];
        $melanom['diagnose_datum']          = date("d.m.Y", strtotime($extract_data['newestTumorstatus']['datum_sicherung']));
        $melanom['lokalisation']            = $extract_data['lokalisation'];
        $melanom['lokalisation_txt']        = $extract_data['lokalisation_txt'];
        $melanom['seitenlokalisation']      = '8';
        switch ($extract_data['seitenlokalisation']) {
            case 'R':
               $melanom['seitenlokalisation']      = '1';
               break;
            case 'L':
               $melanom['seitenlokalisation']      = '2';
               break;
            case 'B':
               $melanom['seitenlokalisation']      = '3';
               break;
        }
        $melanom['melanom_histologie_code'] = ( strlen( $extract_data['newestTumorstatus']['morphologie'] ) > 0 ) ? 'M' . $extract_data['newestTumorstatus']['morphologie'] : '';
        $melanom['melanom_histologie_txt']  = $extract_data['newestTumorstatus']['morphologie_text'];
        $melanom['regressionszeichen']      = 'nicht beurteilbar';
        $melanom['ulzeration_erosion']      = 'nicht beurteilbar';
        foreach ($extract_data['single_histology'] as $single) {
           $melanom['regressionszeichen']   = $single['regression'] === '0' ? 'nein' : $melanom['regressionszeichen'];
           $melanom['regressionszeichen']   = $single['regression'] === '1' ? 'ja' : $melanom['regressionszeichen'];
           $melanom['ulzeration_erosion']   = $single['ulzeration'] === '0' ? 'nein' : $melanom['ulzeration_erosion'];
           $melanom['ulzeration_erosion']   = $single['ulzeration'] === '1' ? 'ja' : $melanom['ulzeration_erosion'];
        }

        $newestHistology = $this->getNewestHistology($extract_data['histologien']);
        $user_name = "";
        $histologie_nr = "";
        if (false !== $newestHistology) {
            $_user = $this->GetUser($newestHistology['user_id']);
            if (false !== $_user) {
                $user_name = concat( array(
                    concat( array(
                        $_user[ 'vorname' ],
                        $_user[ 'nachname' ],
                     ), ' ' ),
                    concat( array(
                        $_user[ 'strasse' ],
                        $_user[ 'hausnr' ],
                    ), ' ' ),
                    concat( array(
                        $_user[ 'plz' ],
                        $_user[ 'ort' ],
                    ), ' ' ),
                ), ', ' );
            }
            $histologie_nr = $newestHistology['histologie_nr'];
        }
        $melanom['pathologe']               = $user_name;
        $melanom['histologie_nummer']       = $histologie_nr;

        $melanom['primarius_gefunden']      = $extract_data['anlass'] === 'p' ? 'ja' : '';
        $meta = $this->getNewestMetastases($extract_data['metastasen_lokalisationen']);
        //Vorsicht Name anders wie spez
        $melanom['lokalisation2']           = $meta['lokalisation'];
        $melanom['lokalisation_txt2']       = $meta['lokalisation_text'];
        $melanom['datum']                   = '??.??.1900';
        $melanom['nein']                    = $extract_data['metastasen_lokalisationen'] === array() ? 'on' : '';
        //Vorsicht Name anders wie spez
        $melanom['pt_wert']                 = substr($extract_data['t_postop'], 1);
        $melanom['pt_txt']                  = substr($extract_data['t_postop'], 1);
        $melanom['pn_wert']                 = substr($extract_data['n_postop'], 1);
        $melanom['pn_txt']                  = substr($extract_data['n_postop'], 1);
        $melanom['pm_wert']                 = substr($extract_data['m'], 1, 2);
        $melanom['pm_txt']                  = substr($extract_data['m'], 1);
        $melanom['tumordicke_breslow_mm']   = '';
        $melanom['clark_level_I']           = '';
        $melanom['clark_level_II']          = '';
        $melanom['clark_level_III']         = '';
        $melanom['clark_level_IV']          = '';
        $melanom['clark_level_V']           = '';
        $thick = array();
        $clark = array();
        foreach ($extract_data['single_histology'] as $single) {
            $thick[] = $single['tumordicke'];
            switch ($single['clark']) {
                case 'I':
                   $clark[] = '1';
                   break;
                case 'II':
                   $clark[] = '2';
                   break;
                case 'III':
                   $clark[] = '3';
                   break;
                case 'IV':
                   $clark[] = '4';
                   break;
                case 'V':
                   $clark[] = '5';
                   break;
            }
        }
        if ($thick !== array()) {
            $thickiest = $thick === array() ? '' : MAX($thick);
            $melanom['tumordicke_breslow_mm']   = number_format($thickiest, 1);
        }
        if ($clark !== array()) {
           $melanom['clark_level_I']           = MAX($clark) === '1' ? 'on' : '';
           $melanom['clark_level_II']          = MAX($clark) === '2' ? 'on' : '';
           $melanom['clark_level_III']         = MAX($clark) === '3' ? 'on' : '';
           $melanom['clark_level_IV']          = MAX($clark) === '4' ? 'on' : '';
           $melanom['clark_level_V']           = MAX($clark) === '5' ? 'on' : '';
        }
        $melanom['vorerkrankungen_nein'] = 'on';
        $melanom['bereits_bekannt']      = '';
        $melanom['art_erkrankung1']      = '';
        $melanom['dia_datum1']           = '';
        $melanom['operation1']           = '';
        $melanom['hormontherapie1']      = '';
        $melanom['strahlentherapie1']    = '';
        $melanom['immuntherapie1']       = '';
        $melanom['chemotherapie1']       = '';
        $melanom['sonstige1']            = '';
        $melanom['art_erkrankung2']      = '';
        $melanom['dia_datum2']           = '';
        $melanom['operation2']           = '';
        $melanom['hormontherapie2']      = '';
        $melanom['strahlentherapie2']    = '';
        $melanom['immuntherapie2']       = '';
        $melanom['chemotherapie2']       = '';
        $melanom['sonstige2']            = '';
        $melanom['art_erkrankung3']      = '';
        $melanom['dia_datum3']           = '';
        $melanom['operation3']           = '';
        $melanom['hormontherapie3']      = '';
        $melanom['strahlentherapie3']    = '';
        $melanom['immuntherapie3']       = '';
        $melanom['chemotherapie3']       = '';
        $melanom['sonstige3']            = '';
        $i = 0;
        if (isset($extract_data['anamnesisDiseases'])) {
            if ( count( $extract_data['anamnesisDiseases'] ) > 0 ) {
                $melanom['vorerkrankungen_nein'] = '';
            }
            foreach ($extract_data['anamnesisDiseases'] as $anamnes) {
                $i++;
                if ( $i >= 4 ) {
                   break;
                }
                if (substr($anamnes['erkrankung'], 0, 1) === 'C') {
                     $melanom['bereits_bekannt']     = 'on';
                     break;
                }
                $melanom['art_erkrankung' . "$i"]    = $anamnes['erkrankung_text'];
                $melanom['dia_datum' . "$i"];
                if ( strlen($anamnes['jahr'] ) > 0 ) {
                    $melanom['dia_datum' . "$i"] = '01.06.' . $anamnes['jahr'];
                }
                if ($anamnes['therapie1'] === 'o' || $anamnes['therapie2'] === 'o' || $anamnes['therapie3'] === 'o') {
                    $melanom['operation' . "$i"] = 'on';
                }
                if ($anamnes['therapie1'] === 'ho' || $anamnes['therapie2'] === 'ho' || $anamnes['therapie3'] === 'ho') {
                    $melanom['hormontherapie' . "$i"] = 'on';
                }

                if ($anamnes['therapie1'] === 's' || $anamnes['therapie2'] === 's' || $anamnes['therapie3'] === 's' || $anamnes['therapie1'] === 'so' || $anamnes['therapie2'] === 'so' || $anamnes['therapie3'] === 'so') {
                    $melanom['strahlentherapie' . "$i"] = 'on';
                }
                if ($anamnes['therapie1'] === 'im' || $anamnes['therapie2'] === 'im' || $anamnes['therapie3'] === 'im') {
                    $melanom['immuntherapie' . "$i"]         = 'on';
                }
                if ($anamnes['therapie1'] === 'c' || $anamnes['therapie2'] === 'c' || $anamnes['therapie3'] === 'c' || $anamnes['therapie1'] === 'cs' || $anamnes['therapie2'] === 'cs' || $anamnes['therapie3'] === 'cs') {
                    $melanom['chemotherapie' . "$i"]         = 'on';
                }
                if ($anamnes['therapie1'] === 'sonst' || $anamnes['therapie2'] === 'sonst' || $anamnes['therapie3'] === 'sonst') {
                    $melanom['sonstige' . "$i"]         = 'on';
                }
            }
        }
        $melanom['op_nein1']                = 'on';
        $melanom['op_datum1']               = '';
        $melanom['operateur1']              = '';
        $melanom['nicht_beurteilbar']       = 'on';
        $melanom['sicherheitsabstand_cm']   = '';
        $melanom['op_nein2']                = 'on';
        $melanom['op_datum2']               = '';
        $melanom['operateur2']              = '';
        $melanom['resttumor']               = '';
        $melanom['sicherheitsabstand_cm2']  = '';
        $melanom['op_nein3']                = 'on';
        $melanom['op_datum3']               = '';
        $melanom['operateur3']              = '';
        $melanom['therapeutische_lkop']     = 'on';
        $melanom['sentinel_lkop']           = '';
        $sortedOperations = $this->sortOperations($extract_data['operationen']);
        if ($sortedOperations !== false) {
            foreach ($sortedOperations as $ops) {
                if ($ops['art_primaertumor'] === '1' || $ops['art_lk'] === '1' ||
                    $ops['art_metastasen'] === '1' || $ops['art_rezidiv'] === '1' ||
                    $ops['art_nachresektion'] === '1' || $ops['art_revision'] === '1') {
                    $melanom['op_nein1']                = '';
                    $melanom['op_datum1']               = date("d.m.Y", strtotime($ops['beginn']));

                    $user_name = "";
                    $_user = $this->GetUser( $ops['operateur1_id'] );
                    if ( $_user !== false ) {
                        $user_name = concat( array(
                            concat( array(
                                $_user[ 'vorname' ],
                                $_user[ 'nachname' ],
                             ), ' ' ),
                            concat( array(
                                $_user[ 'strasse' ],
                                $_user[ 'hausnr' ],
                            ), ' ' ),
                            concat( array(
                                $_user[ 'plz' ],
                                $_user[ 'ort' ],
                            ), ' ' ),
                        ), ', ' );
                    }
                    $melanom['operateur1']    = $user_name;
                    $melanom['nicht_beurteilbar']       = '';
                    $melanom['sicherheitsabstand_cm']   = number_format($extract_data['sicherabstand'], 1);
                }

                if ($ops['art_nachresektion'] === '1') {
                    $melanom['op_nein2']                = '';
                    $melanom['op_datum2']               = date("d.m.Y", strtotime($ops['beginn']));

                    $user_name = "";
                    $_user = $this->GetUser( $ops['operateur1_id'] );
                    if ( $_user !== false ) {
                        $user_name = concat( array(
                            concat( array(
                                $_user[ 'vorname' ],
                                $_user[ 'nachname' ],
                            ), ' ' ),
                            concat( array(
                                $_user[ 'strasse' ],
                                $_user[ 'hausnr' ],
                            ), ' ' ),
                            concat( array(
                                $_user[ 'plz' ],
                                $_user[ 'ort' ],
                            ), ' ' ),
                        ), ', ' );
                    }
                    $melanom['operateur2']    = $user_name;
                    $melanom['resttumor']     = 'on';
                    $melanom['sicherheitsabstand_cm2']   = number_format($extract_data['sicherabstand'], 1);
                }


                if ($ops['art_lk'] === '1') {
                    $melanom['op_nein3']                = '';
                    if (array_key_exists('ops_codes', $ops)) {
                        foreach ($ops['ops_codes'] as $op) {
                            if ($op['prozedur'] === '3-760' || $op['prozedur'] === '5-401.01' || $op['prozedur'] === '5-401.02' || $op['prozedur'] === '5-401.11' ||
                                $op['prozedur'] === '5-401.12' || $op['prozedur'] === '5-401.51' || $op['prozedur'] === '5-401.52') {
                                $melanom['sentinel_lkop']           = 'on';
                                $melanom['therapeutische_lkop']     = '';
                            }
                        }
                    }

                    $melanom['op_datum3']               = date("d.m.Y", strtotime($ops['beginn']));

                    $user_name = "";
                    $_user = $this->GetUser( $ops['operateur1_id'] );
                    if ( $_user !== false ) {
                        $user_name = concat( array(
                            concat( array(
                                $_user[ 'vorname' ],
                                $_user[ 'nachname' ],
                             ), ' ' ),
                            concat( array(
                                $_user[ 'strasse' ],
                                $_user[ 'hausnr' ],
                            ), ' ' ),
                            concat( array(
                                $_user[ 'plz' ],
                                $_user[ 'ort' ],
                            ), ' ' ),
                        ), ', ' );
                    }
                    $melanom['operateur3']    = $user_name;
                }
            }
        }
        $melanom['sicherheitsabstand_cm']   = number_format($extract_data['sicherabstand'], 1);
        $melanom['sicherheitsabstand_cm2']  = number_format($extract_data['sicherabstand'], 1);
        $melanom['op_ergebnis']             = '4';
        $melanom['bemerkungen']             = $extract_data['newestTumorstatus']['bem'];
        $melanom['patient_nachsorge']       = 'on';
        $melanom['weiterbehandlung']        = 'siehe Behandlungsbogen';

        $user_name = "";
        $_user = $this->GetUser( $extract_data[ 'behandler_id' ] );
        if ( $_user !== false ) {
            $user_name = concat( array(
                concat( array(
                    $_user[ 'vorname' ],
                    $_user[ 'nachname' ],
                 ), ' ' ),
                concat( array(
                    $_user[ 'strasse' ],
                    $_user[ 'hausnr' ],
                ), ' ' ),
                concat( array(
                    $_user[ 'plz' ],
                    $_user[ 'ort' ],
                ), ' ' ),
            ), ', ' );
        }
        $melanom['hausarzt']             = $user_name;
        $melanom['anlass_erfassung']        = '';
        $discover = $this->getNewestDiscover($extract_data['anamnesen']);
        if (strlen($discover['entdeckung']) > 0) {
            switch ($discover['entdeckung']) {
                case 'su':
                    $melanom['anlass_erfassung']     = '3';
                    break;
                case 'gf':
                case 'nv':
                    $melanom['anlass_erfassung']     = '2';
                    break;
                case 'ts':
                case 'ze':
                    $melanom['anlass_erfassung']     = '6';
                    break;
                case 'ns':
                    $melanom['anlass_erfassung']     = '5';
                    break;
                case 'sc':
                    $melanom['anlass_erfassung']     = $extract_data['erkrankung'] === 'b' ? '7' : '9';
                    break;
           }
       }

       return $melanom;
    }

    /**
     * @param  $operations
     * @return array
     */
    protected function sortOperations($operations)
    {
        if (!is_array($operations)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach( $operations as $e ) {
            if (strlen($e['eingriff_id']) > 0 ) {
                $result[strtotime($e['beginn'])] = $e;
            }
        }
        if ($result !== false) {
           krsort($result);
        }
        return $result;

    }

    /**
     *
     * @param $value
     * @return unknown_type
     */
    protected function KillSn( $value ) {
        return str_replace( '(sn)', '', $value );
    }

    /**
     * @param  $anamnese_id
     * @return array
     */
    protected function getAnamnesisDiseases( $anamnese_id )
    {
        $query = "
                SELECT
                    ae.erkrankung,
                    ae.erkrankung_seite,
                    ae.erkrankung_text,
                    ae.erkrankung_version,
                    ae.jahr,
                    ae.therapie1,
                    ae.therapie2,
                    ae.therapie3
                FROM
                    anamnese_erkrankung ae
                WHERE
                    ae.anamnese_id=$anamnese_id
                AND
                    ae.jahr IS NOT NULL
                ORDER BY
                    ae.jahr DESC
                LIMIT 3
            ";
        $result = sql_query_array( $this->m_db, $query );
        return $result;
    }


    /**
     * @param  $therapy_id
     * @return array
     */
    protected function getTherapyCircle($therapy_id) {
        $query = "
            SELECT
                COUNT(therapie_systemisch_id) AS i
            FROM
                therapie_systemisch_zyklus
            WHERE
                therapie_systemisch_id = $therapy_id
            GROUP BY
                therapie_systemisch_id
             LIMIT 1
        ";
        $result = sql_query_array( $this->m_db, $query );
        return $result;
    }


    /**
     *
     *
     * @access  protected
     * @param   int         $therapy_id
     * @param   string      $table
     * @return  string
     */
    protected function getTherapyDescription($therapy_id, $table)
    {
        $idField = $table . '_id';

        $query = "
            SELECT
                bez
            FROM
                {$table}
            WHERE
                {$idField} = $therapy_id
            LIMIT 1
        ";
        $result = end(sql_query_array( $this->m_db, $query ));

        if ($result !== false) {
            return $result['bez'];
        }
        return '';
    }


    /**
     * @param  $tumorstatus
     * @return array
     */
    protected function getNewestTumorstatus($tumorstatus) {
        if (!is_array($tumorstatus)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach( $tumorstatus as $e ) {
            if ((strlen($e['datum_sicherung']) > 0 ) && (strtotime($e['datum_sicherung']) > $max_datum )) {
                $result = $e;
                $max_datum = strtotime($e['datum_sicherung']);
            }
        }
        return $result;
    }


    /**
     * @param  $tumorstatus
     * @return array
     */
    protected function getNewestMorphology($tumorstatus) {
        if (!is_array($tumorstatus)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($tumorstatus as $e) {
            if ((strlen($e['morphologie']) > 0) && (strtotime($e['datum_sicherung']) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['datum_sicherung']);
            }
        }
        return $result;
    }


    /**
     * @param  $tumorstatus
     * @return string
     */
    protected function getNewestTumorstatusComment($tumorstatus) {
        if (!is_array($tumorstatus)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($tumorstatus as $e) {
            if ((strlen($e['bem']) > 0) && (strtotime($e['datum_sicherung']) > $max_datum)) {
                $result = $e['bem'];
                $max_datum = strtotime($e['datum_sicherung']);
            }
        }
        return $result;
    }


    /**
     * @param  histologie
     * @return array
     */
    protected function getNewestHistology($histology) {
        if ( !is_array( $histology ) ) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($histology as $e) {
            if ((strlen($e['datum']) > 0) && (strtotime($e['datum']) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $histology
     * @return bool
     */
    protected function getNewestHistologyWithHistoNo($histology) {
        if ( !is_array( $histology ) ) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($histology as $e) {
            if ((strlen($e['datum']) > 0) &&
                (strtotime($e['datum']) > $max_datum) &&
                (strlen($e['histologie_nr']) > 0)) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     * @param  histologie
     * @return array
     */
    protected function getNewestPathologe($histology) {
        if ( !is_array($histology)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($histology as $e) {
            if (strtotime($e['datum']) > $max_datum) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     * @param  tumorstatus
     * @return array
     */
    protected function getNewestMetastases($tumorstatus) {
        if (!is_array($tumorstatus)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($tumorstatus as $e) {
            if ((strlen($e['datum_sicherung']) > 0) && (strtotime($e['datum_sicherung'] ) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['datum_sicherung']);
            }
        }

        return $result;
    }


    /**
     * @param  anamnese
     * @return array
     */
    protected function getNewestAnamnesis($anamnesis) {
        if (!is_array($anamnesis)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($anamnesis as $e) {
            if ((strlen($e['datum']) > 0) && (strtotime($e['datum']) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     * @param  anamnese
     * @return array
     */
    protected function getNewestDiscover($anamnesis) {
        if (!is_array($anamnesis)) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($anamnesis as $e) {
            if ((strlen($e['entdeckung']) > 0) && (strtotime($e['datum']) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     * Get oldest histology
     * @param  histologie
     * @return array
     */
    protected function getOldestHistology($histology)
    {
        if (!is_array($histology)) {
            return false;
        }
        $result = false;
        $max_datum = 9999999999999;
        foreach($histology as $e) {
            if (strtotime($e['datum']) < $max_datum) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $histology
     * @return bool
     */
    protected function getOldestHistologyWithHistoNo($histology)
    {
        if (!is_array($histology)) {
            return false;
        }
        $result = false;
        $max_datum = 9999999999999;
        foreach($histology as $e) {
            if ((strtotime($e['datum']) < $max_datum) && (strlen($e['histologie_nr']) > 0)) {
                $result = $e;
                $max_datum = strtotime($e['datum']);
            }
        }
        return $result;
    }


    /**
     * Get newest therapys
     * @param  histologie
     * @return array
     */
    protected function getNewestTherapy($therapy)
    {
        if ( !is_array( $therapy ) ) {
            return false;
        }
        $result = false;
        $max_datum = 0;
        foreach($therapy as $e) {
            if ((strlen($e['beginn']) > 0 ) && (strtotime($e['beginn']) > $max_datum)) {
                $result = $e;
                $max_datum = strtotime($e['beginn']);
            }
        }
        return $result;
    }


    /**
     * get Menopause-Status
     * @param  array
     * @return integer
     */
    protected function getMenopause($anamnese)
    {
        if (!is_array($anamnese)) {
            return false;
        }
        $max_datum = 0;
        $menopausenstatus = '4';
        foreach($anamnese as $e) {
            if ((strlen($e['menopausenstatus']) > 0) && (strtotime($e['datum']) > $max_datum)) {
                $menopausenstatus = $e['menopausenstatus'];
                $max_datum = strtotime($e['datum']);
            }
        }
        switch ($menopausenstatus) {
            case 'pra':
                $menopausenstatus = '1';
                break;
            case 'per':
                $menopausenstatus = '2';
                break;
            case 'po':
                $menopausenstatus = '3';
                break;
        }
        return $menopausenstatus;
    }




    /**
     * get newest KRAS-Faktor
     * @param  array
     * @return integer
     */
    protected function getNewestKras($histology)
    {
        $kras = '';
        if (!is_array($histology)) {
            return false;
        }
        $max_datum = 0;
        foreach($histology as $e) {
            if ((strlen($e['kras']) > 0) && (strtotime($e['datum']) > $max_datum)) {
                $kras = $e['kras'];
                $max_datum = strtotime($e['datum']);
            }
        }
        return $kras;
    }


    /**
     * get hormone-rezeptor
     */
    protected function getHormoneRezeptor($estrogen, $progest)
    {
         $estro = '0';
         $prog  = '0';
         switch ($estrogen) {
             case '0':
             case '12':
                $estro = '1';
                break;
             case '34':
             case '6':
                $estro = '2';
                break;
             case '12max':
                $estro = '3';
                break;
             case '':
                $estro = '0';
                break;
         }
         switch ($progest) {
             case '0':
             case '12':
                $prog = '1';
                break;
             case '34':
             case '6':
                $prog = '2';
                break;
             case '12max':
                $prog = '3';
                break;
             case '':
                $prog = '0';
                break;
         }
         $result = MAX($estro, $prog) === '0' ? '4' : MAX($estro, $prog);
    return $result;
    }


    /**
     * get Her2
     */
    protected function getHer2($her2, $her2Fish, $her2judge)
    {
        $result = '6';
        switch (strtolower($her2)) {
            case 'n' :
                $result = '1';
                break;
            case '1' :
                if ($her2Fish === '') {
                    $result = '2';
                }
                break;
            case '2' :
                if (($her2Fish === 'fish' || $her2Fish === 'cish' ) && $her2judge) {
                    $result = '3';
                }
                break;
            case '2' :
                if (($her2Fish === 'fish' || $her2Fish === 'cish' ) && $her2judge) {
                    $result = '4';
                }
                break;
            case '3' :
                $result = '5';
                break;
         }
         return $result;
    }

    protected function GetMaxGroesse( $data )
    {
        foreach( $data as $d ) {
            if ( ( strlen( $d[ 'groesse_x' ] ) > 0 ) ||
                 ( strlen( $d[ 'groesse_y' ] ) > 0 ) ||
                 ( strlen( $d[ 'groesse_z' ] ) > 0 ) ) {
                return MAX( array( $d[ 'groesse_x' ], $d[ 'groesse_y' ], $d[ 'groesse_z' ] ) );
            }
        }
        return '';
    }


    /**
     *
     *
     * @access  protected
     * @param   array       $data
     * @param   string      $side
     * @return  array
     */
    protected function _filterOpSide($data, $side)
    {
        $result = array();

        $side = $this->_getRelevantSides($side);

        if ($side === null || is_array($side) === false) {
            $result = $data;
        } else {
            if (is_array($data) === true) {
                foreach ($data as $op) {
                    $tmp        = array();
                    $filteredOp = array();
                    $opsCodes   = $op['ops_codes'];

                    unset($op['ops_codes']);

                    if (in_array($op['diagnose_seite'], $side)) {
                        foreach ($opsCodes as $code) {
                            if (in_array($code['prozedur_seite'], $side)) {
                                $tmp[] = $code;
                            }
                        }

                        if (count($tmp) > 0) {
                            $filteredOp = $op;
                            $filteredOp['ops_codes'] = $tmp;
                        }
                    }

                    $result[] = $filteredOp;
                }
            }
        }

        return $result;
    }


    /**
     *
     *
     * @access  protected
     * @param   string      $side
     * @return array|null
     */
    protected function _getRelevantSides($side)
    {
        $result = null;

        switch($side) {
            case 'B' :
                $result = array('B','L','R');
                break;
            case 'L' :
                $result = array('B','L');
                break;
            case 'R' :
                $result = array('B','R');
                break;
        }

        return $result;
    }


    /**
     *
     *
     * @access
     * @param $date
     * @return bool|string
     */
    protected function FormatDate( $date )
    {
        if ( strlen( $date ) > 0 ) {
            $time = strtotime( $date );
            if ( false !== $time ) {
                return date( "d.m.Y", $time );
            }
        }
        return '';
    }

}

?>
