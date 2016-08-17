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

require_once( 'feature/export/base/class.exportdefaultmodel.php' );
require_once( 'feature/export/base/helper.common.php' );
require_once( 'class.krbw_2_1_serialiser.php' );

class Ckrbw_2_1_Model extends CExportDefaultModel
{

    protected $m_ann_arbor_codings = array(
        "I"        => "I",
        "IA"       => "IA",
        "IB"       => "IB",
        "I/E"      => "IE",
        "I/EA"     => "IAE",
        "I/EB"     => "IBE",
        "II"       => "II",
        "IIA"      => "IIA",
        "IIB"      => "IIB",
        "II/E"     => "IIE",
        "II/EA"    => "IIAE",
        "II/EB"    => "IIBE",
        "III"      => "III",
        "IIIA"     => "IIIA",
        "IIIB"     => "IIIB",
        "III/E"    => "IIIE",
        "III/EA"   => "IIIAE",
        "III/EB"   => "IIIBE",
        "III/S"    => "IIIS",
        "III/SA"   => "IIIAS",
        "III/SB"   => "IIIBS",
        "III/E+S"  => "IIIES",
        "III/E+SA" => "IIIAES",
        "III/E+SB" => "IIIBES",
        "IV"       => "IV",
        "IVA"      => "IVA",
        "IVB"      => "IVB",
        "IV/N"     => "IV",
        "IV/NA"    => "IVA",
        "IV/NB"    => "IVB",
        "IV/H"     => "IV",
        "IV/HA"    => "IVA",
        "IV/HB"    => "IVB",
        "IV/S"     => "IV",
        "IV/SA"    => "IVA",
        "IV/SB"    => "IVB",
        "IV/L"     => "IV",
        "IV/LA"    => "IVA",
        "IV/LB"    => "IVB",
        "IV/M"     => "IV",
        "IV/MA"    => "IVA",
        "IV/MB"    => "IVB",
        "IV/O"     => "IV",
        "IV/OA"    => "IVA",
        "IV/OB"    => "IVB",
        "IV/D"     => "IV",
        "IV/DA"    => "IVA",
        "IV/DB"    => "IVB",
        "IV/P"     => "IV",
        "IV/PA"    => "IVA",
        "IV/PB"    => "IVB"
    );

    public function __construct()
    {
        // deactivate situation check on krbw
        $this->setCheckSituationOnDiff(false);
    }


    /**
     * ExtractData
     *
     * @access  public
     * @param   array          $parameters
     * @param   IExportWrapper $wrapper
     * @param   RExport        $export_record
     * @return  void
     */
    public function ExtractData( $parameters, $wrapper, &$export_record )
    {
        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;
        $wrapper->SetRangeDate( $parameters[ 'datum_von' ], $parameters[ 'datum_bis' ] );
        $wrapper->SetErkrankungen( 'all' );
        $wrapper->UsePrimaryCasesOnly();
        $wrapper->SetDiagnosen( "( diagnose LIKE 'C%' AND diagnose NOT IN ( 'C97' ) AND diagnose NOT LIKE 'C77%' AND diagnose NOT LIKE 'C78%' AND diagnose NOT LIKE 'C79%' ) " .
                                "OR diagnose LIKE 'D0%' " .
                                "OR diagnose LIKE 'D32%' OR diagnose LIKE 'D33%' " .
                                "OR diagnose IN ( 'D35.2', 'D35.3', 'D35.4' ) " .
                                "OR diagnose LIKE 'D37%' OR diagnose LIKE 'D38%' OR diagnose LIKE 'D39%' OR diagnose LIKE 'D4%' " .
                                "OR diagnose IN ( 'D18.02', 'D18.02', 'D18.18', 'D19.7', 'D21.0' )" );

        $wrapper->SetMorphologien( "( LEFT( morphologie, 4 ) LIKE '8%' OR LEFT( morphologie, 4 ) LIKE '9%' ) " .
                                   "AND RIGHT( morphologie, 2 ) IN ( '/0', '/1', '/2', '/3', '/9' ) " );

        $wrapper->SetAdditionalJoins( array(
            "LEFT JOIN nachsorge na ON s.form = 'nachsorge' AND na.nachsorge_id  = s.form_id",
            "LEFT JOIN tumorstatus tsa ON sit.erkrankung_id = tsa.erkrankung_id",
            "LEFT JOIN tumorstatus_metastasen ma ON sit.erkrankung_id = ma.erkrankung_id",
        ) );

        $wrapper->SetAdditionalSelects(
            array(
                "p.kv_nr        AS 'versicherungsnummer'",
                "p.kv_iknr      AS 'kv_iknr'",
                "p.kv_fa        AS 'kv_fa'",
                "p.titel        AS 'titel'",
                "p.geburtsname  AS 'geburtsname'",
                "p.strasse      AS 'strasse'",
                "p.hausnr       AS 'hausnummer'",
                "p.staat        AS 'land'",
                "p.plz          AS 'plz'",
                "p.ort          AS 'wohnort'"
            )
        );

        $wrapper->SetAdditionalFields(
            array(
                "sit.versicherungsnummer                                     AS 'versicherungsnummer'",
                "sit.kv_iknr                                                 AS 'kv_iknr'",
                "sit.kv_fa                                                   AS 'kv_fa'",
                "sit.patient_nr                                              AS 'referenznr'",
                "sit.titel                                                   AS 'titel'",
                "sit.geburtsname                                             AS 'geburtsname'",
                "sit.strasse                                                 AS 'strasse'",
                "sit.hausnummer                                              AS 'hausnummer'",
                "sit.land                                                    AS 'land'",
                "sit.plz                                                     AS 'plz'",
                "sit.wohnort                                                 AS 'wohnort'",
                "sit.org_id                                                  AS 'org_id'",
                "sit.start_date                                              AS 'startDate'",
                "sit.end_date                                                AS 'endDate'",
                "IF(MIN(na.nachsorge_id) IS NOT NULL,
                      GROUP_CONCAT( DISTINCT
                          IF(na.nachsorge_id IS NOT NULL,
                              CONCAT_WS('{$separator_col}',
                                  IFNULL(na.nachsorge_id, ''),
                                  IFNULL(na.datum, ''),
                                  IFNULL(na.response_klinisch, ''),
                                  IFNULL(na.org_id, '')
                              ),
                              NULL
                          )
                          SEPARATOR '{$separator_row}'
                      ),
                      NULL
                )                                                            AS 'verlauf'",
                "IF(MIN(tsa.tumorstatus_id) IS NOT NULL,
                      GROUP_CONCAT( DISTINCT
                          IF(tsa.tumorstatus_id IS NOT NULL,
                              CONCAT_WS('{$separator_col}',
                                  IFNULL(tsa.tumorstatus_id, ''),
                                  IFNULL(tsa.datum_sicherung, ''),
                                  IFNULL(tsa.anlass, ''),
                                  IFNULL(tsa.tnm_praefix, ''),
                                  IFNULL(tsa.t, ''),
                                  IFNULL(tsa.n, ''),
                                  IFNULL(tsa.m, ''),
                                  IFNULL(tsa.diagnose, ''),
                                  IFNULL(tsa.diagnose_seite, ''),
                                  IFNULL(tsa.lokalisation, ''),
                                  IFNULL(tsa.morphologie, ''),
                                  IFNULL(tsa.g, ''),
                                  IFNULL(tsa.l, ''),
                                  IFNULL(tsa.v, ''),
                                  IFNULL(tsa.ppn, ''),
                                  IFNULL(tsa.figo, ''),
                                  IFNULL(tsa.gleason1, ''),
                                  IFNULL(tsa.gleason2, ''),
                                  IFNULL(tsa.ann_arbor_stadium, ''),
                                  IFNULL(tsa.ann_arbor_aktivitaetsgrad, ''),
                                  IFNULL(tsa.cll_binet, ''),
                                  IFNULL(tsa.durie_salmon, ''),
                                  IFNULL(tsa.aml_fab, ''),
                                  IFNULL(tsa.cll_rai, ''),
                                  IFNULL(tsa.lk_entf, ''),
                                  IFNULL(tsa.lk_bef, ''),
                                  IFNULL(tsa.estro_urteil, ''),
                                  IFNULL(tsa.prog_urteil, ''),
                                  IFNULL(tsa.her2, ''),
                                  IFNULL(tsa.psa, ''),
                                  IFNULL(tsa.datum_beurteilung, '')
                              ),
                              NULL
                          )
                          SEPARATOR '{$separator_row}'
                      ),
                      NULL
                )                                                            AS 'tumorstatus_alle'",

                "IF(MIN(ma.tumorstatus_metastasen_id) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF(ma.tumorstatus_metastasen_id IS NOT NULL,
                            CONCAT_WS('{$separator_col}',
                                IFNULL(ma.tumorstatus_metastasen_id, ''),
                                IFNULL(ma.tumorstatus_id, ''),
                                IFNULL(ma.lokalisation, '')
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                                                            AS 'tumorstatus_metastasen_alle'"
        ));

        $result = $wrapper->GetExportData( $parameters );
        foreach( $result as $extract_data ) {
            if ('zW' != $extract_data['kr_meldung']['meldebegruendung']) {
                if ($extract_data['erkrankung'] === 'b' || $extract_data['erkrankung'] === 'lu') {
                    $extract_data['operationen'] = $this->_filterOpSide($extract_data['operationen'], $extract_data['diagnose_seite']);
                }
                // Get all tumorstatus
                $ts = array();
                if (strlen($extract_data[ 'tumorstatus_alle'] ) > 0) {
                    $fields = array(
                        'tumorstatus_id',
                        'datum_sicherung',
                        'anlass',
                        'tnm_praefix',
                        't',
                        'n',
                        'm',
                        'diagnose',
                        'diagnose_seite',
                        'lokalisation',
                        'morphologie',
                        'g',
                        'l',
                        'v',
                        'ppn',
                        'figo',
                        'gleason1',
                        'gleason2',
                        'ann_arbor_stadium',
                        'ann_arbor_aktivitaetsgrad',
                        'cll_binet',
                        'durie_salmon',
                        'aml_fab',
                        'cll_rai',
                        'lk_entf',
                        'lk_bef',
                        'estro_urteil',
                        'prog_urteil',
                        'her2',
                        'psa',
                        'datum_beurteilung'
                    );
                    $ts = HReports::RecordStringToArray($extract_data['tumorstatus_alle'], $fields);
                }
                $extract_data['tumorstatus_alle'] = $ts;
                $extract_data['tumorstatus'] =
                    $this->_getTumorstatusByTimeline(
                        $extract_data['tumorstatus_alle'],
                        $extract_data['anlass'],
                        $extract_data['diagnose_seite'],
                        $extract_data['startDate'],
                        $extract_data['endDate']
                    );
                // Get all tumorstatus
                $tsm = array();
                if (strlen($extract_data[ 'tumorstatus_metastasen_alle'] ) > 0) {
                    $fields = array('tumorstatus_metastasen_id', 'tumorstatus_id', 'lokalisation');
                    $tsm     = HReports::RecordStringToArray($extract_data['tumorstatus_metastasen_alle'], $fields);
                }
                $extract_data['tumorstatus_metastasen_alle'] = $tsm;
                $extract_data['mergedTumorstatus'] = $this->_mergeTumorstatusWithMorphologie(
                    $extract_data['tumorstatus_alle'], $extract_data['tumorstatus_metastasen_alle']
                );
                $extract_data['tumorstatus'] = $this->_mergeTumorstatusWithMorphologie(
                    $extract_data['tumorstatus'], $extract_data['tumorstatus_metastasen_alle']
                );

                // Get nachsorge with org_id
                $verlauf = array();
                if (strlen($extract_data['verlauf']) > 0) {
                    $fields  = array('nachsorge_id', 'datum', 'tumorgeschehen', 'org_id');
                    $verlauf = HReports::RecordStringToArray($extract_data['verlauf'], $fields);
                }
                $extract_data['verlauf'] = $verlauf;
                // Create main case
                $case = $this->CreateCase( $export_record->GetDbid(), $parameters, $extract_data );
                // Melder
                $section = $this->CreateMelderSection( $parameters, $section_uid );
                $melder = $this->CreateBlock( $case->GetDbid(), $parameters, 'melder', $section_uid, $section );
                $case->AddSection( $melder );
                // Patient
                $section = $this->CreatePatientSection( $parameters, $extract_data, $section_uid );
                $patient = $this->CreateBlock( $case->GetDbid(), $parameters, 'patient', $section_uid, $section );
                $case->AddSection( $patient );
                // Diagnose
                $section = $this->CreateDiagnoseSection( $parameters, $extract_data, $section_uid );
                $diagnose = $this->CreateBlock( $case->GetDbid(), $parameters, 'diagnose', $section_uid, $section );
                $case->AddSection( $diagnose );
                // Therapien
                $therapien = $this->GetTherapienSections( $parameters, $extract_data );
                foreach( $therapien as $row ) {
                    $section_uid = $row[ 'therapieart' ] . '_' . $row[ 'id' ];
                    $therapie = $this->CreateBlock( $case->GetDbid(), $parameters, 'therapie', $section_uid, $row );
                    $case->AddSection( $therapie );
                }
                // Nachsorgen
                $nachsorgen = $this->GetNachsorgenSections( $parameters, $extract_data );
                foreach( $nachsorgen as $row ) {
                    if (isset($row['nachsorge_id']) === true) {
                        $section_uid = 'NACH_' . $row['nachsorge_id'];
                    } else {
                        $section_uid = 'NACH_T_' . $row['tumorstatus_id'];
                    }
                    $nachsorge = $this->CreateBlock( $case->GetDbid(), $parameters, 'nachsorge', $section_uid, $row );
                    $case->AddSection( $nachsorge );
                }
                // Abschluss
                $section = $this->CreateAbschlussSection( $parameters, $extract_data, $section_uid );
                if ( count( $section ) > 0 ) {
                    $abschluss = $this->CreateBlock( $case->GetDbid(), $parameters, 'abschluss', $section_uid, $section );
                    $case->AddSection( $abschluss );
                }
                // Add main case
                $export_record->AddCase( $case );
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $allTumorstatus
     * @param $startDate
     * @param $endDate
     * @return array
     */
    protected function _getTumorstatusByTimeline($allTumorstatus, $situation, $side, $startDate, $endDate)
    {
        $result = array();
        if (null !== $allTumorstatus &&
            is_array($allTumorstatus) &&
            strlen($startDate) > 0 &&
            strlen($endDate) > 0) {
            foreach ($allTumorstatus as $ts) {
                $tsDate = strlen($ts['datum_sicherung']) > 0 ? $ts['datum_sicherung'] : $ts['datum_beurteilung'];
                if (strlen($tsDate) > 0 &&
                    $tsDate >= $startDate &&
                    $tsDate < $endDate &&
                    $side == $ts['diagnose_seite'] &&
                    $situation == $ts['anlass']) {
                    $result[] = $ts;
                }
            }
            usort($result, array("Ckrbw_2_1_Model", "_compareTumorstatusByDateDesc"));
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $tsA
     * @param $tsB
     * @return int
     */
    protected function _compareTumorstatusByDateAsc($tsA, $tsB) {
        $tsDateA = strlen($tsA['datum_sicherung']) > 0 ? $tsA['datum_sicherung'] : $tsA['datum_beurteilung'];
        $tsDateB = strlen($tsB['datum_sicherung']) > 0 ? $tsB['datum_sicherung'] : $tsB['datum_beurteilung'];
        return ($tsDateA < $tsDateB) ? -1 : 1;
    }

    /**
     *
     *
     * @access
     * @param $tsA
     * @param $tsB
     * @return int
     */
    protected function _compareTumorstatusByDateDesc($tsA, $tsB) {
        $tsDateA = strlen($tsA['datum_sicherung']) > 0 ? $tsA['datum_sicherung'] : $tsA['datum_beurteilung'];
        $tsDateB = strlen($tsB['datum_sicherung']) > 0 ? $tsB['datum_sicherung'] : $tsB['datum_beurteilung'];
        return ($tsDateA < $tsDateB) ? 1 : -1;
    }

    public function PreparingData( $parameters, &$export_record )
    {
    }

    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        // Fix f?r Ticket #7700
        if ( 'patient' == $section->GetBlock() ) {
            $s = $section->GetDaten();
            $s[ 'sterbedatum' ] = "";
            $so = $old_section->GetDaten();
            $so[ 'sterbedatum' ] = "";
            if ( !$this->IsUnequal( $s, $so ) ) {
                return;
            }
        }
        // Damit die TAN nicht mit ?berpr?ft wird...
        $s_section_data = $section->GetDaten();
        $s_section_data[ 'meldungskennzeichen' ] = "";
        $s_section_data[ 'tan' ] = "";
        $s_old_section_data = $old_section->GetDaten();
        $s_old_section_data[ 'meldungskennzeichen' ] = "";
        $s_old_section_data[ 'tan' ] = "";

        $changedFields = array();
        $result = $this->DiffData($s_section_data, $s_old_section_data, $changedFields);

        // Nochmal holen damit TAN wieder beachtet wird...
        $section_data = $section->GetDaten();
        $old_section_data = $old_section->GetDaten();
        if ( 1 == $result ) {
            $section->SetMeldungskennzeichen( "A" );
            if ( isset( $old_section_data[ 'tan' ] ) ) {
                $section_data[ 'tan' ] = $old_section_data[ 'tan' ];
            }
        }
        else if ( 2 == $result ) {
            $section->SetMeldungskennzeichen( "K" );
            if ( ( 'diagnose' == $section->GetBlock() ) &&
                 ( '1' == $section_data[ 'wandlung_diagnose' ] ) ) {
                $section->SetMeldungskennzeichen( "W" );
            }
            if ( isset( $old_section_data[ 'tan' ] ) ) {
                $section_data[ 'tan' ] = $old_section_data[ 'tan' ];
            }

        }
        else { // 0 == $result
            $section->SetMeldungskennzeichen( "K" );
            if ( isset( $old_section_data[ 'tan' ] ) ) {
                $section_data[ 'tan' ] = $old_section_data[ 'tan' ];
            }
        }
        $section->SetDataChanged( 1 );
        $section->SetDaten( $section_data );
    }

    public function CheckData( $parameters, &$export_record )
    {
        $this->_removeNotExportableCases($export_record);
        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Ckrbw_2_1_Serialiser();
        $serialiser->Create( $this->m_absolute_path, $this->GetExportName(),
                             $this->m_smarty, $this->m_db, $this->m_error_function );
        $serialiser->SetData( $export_record );
        $serialiser->Validate( $this->m_parameters );
    }



    public function WriteData()
    {
        $this->m_export_record->SetFinished( true );
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Ckrbw_2_1_Serialiser();
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


    /**
     *
     *
     * @access
     * @param RExport $export_record
     * @return bool
     */
    protected function _removeNotExportableCases(RExport &$export_record)
    {
        $cases = $export_record->GetCases();
        foreach ($cases as $case) {
            if (true === $case->IsValid()) {
                $sections = $case->GetSections();
                $otherDataChanged = false;
                foreach ($sections as $section) {
                    if (1 == $section->HasDataChanged()) {
                        if ('patient' !== $section->GetBlock() && 'melder' !== $section->GetBlock()) {
                            $otherDataChanged = true;
                            break;
                        }
                    }
                }
                if (false === $otherDataChanged) {
                    foreach ($sections as $section) {
                        if ('patient' === $section->GetBlock() || 'melder' === $section->GetBlock()) {
                            $section->SetDataChanged(0);
                        }
                    }
                }
            }
        }
        $export_record->SetCases($cases);
    }


    protected function CreateMelderSection( $parameters,
                                            &$section_uid )
    {
        $ansprechpartner_name = isset( $parameters[ 'ansprechpartner_name' ] ) ?
            $parameters[ 'ansprechpartner_name' ] : '';
        $ansprechpartner_email =  isset( $parameters[ 'ansprechpartner_email' ] ) ?
            $parameters[ 'ansprechpartner_email' ] : '';
        $melder = array();
        $melder[ 'id' ] = $parameters[ 'melder_id' ];
        $melder[ 'pruefcode' ] = $parameters[ 'melder_pruefcode' ];
        $melder[ 'ansprechpartner' ] = $ansprechpartner_name;
        if ( strlen( $ansprechpartner_email ) > 0 ) {
            $melder[ 'ansprechpartner' ] .= " (" . $ansprechpartner_email . ")";
        }
        $melder[ 'quellsystem' ] = isset( $parameters[ 'source_system' ] ) ?
            $parameters[ 'source_system' ] : 'Alcedis MED4';
        $section_uid = 'MELD_' . $parameters[ 'melder_id' ] . '_' . $parameters[ 'melder_pruefcode' ];
        return $melder;
    }

    protected function CreatePatientSection( $parameters,
                                             $extract_data,
                                             &$section_uid )
    {
        $patient = array();
        $patient[ 'id' ] = $extract_data[ 'patient_id' ];
        $patient[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
        $patient[ 'referenznr' ] = $extract_data[ 'referenznr' ];
        $patient[ 'versicherungsnummer' ] = $extract_data['versicherungsnummer'] . $extract_data['kv_iknr'] . substr($extract_data['kv_fa'], 0, 10);
        $patient[ 'unterrichtung' ] = $extract_data[ 'kr_meldung' ][ 'meldebegruendung' ];
        $patient[ 'titel' ] = $extract_data[ 'titel' ];
        $patient[ 'nachname' ] = $extract_data[ 'nachname' ];
        $patient[ 'vorname' ] = $extract_data[ 'vorname' ];
        $patient[ 'geburtsname' ] = $extract_data[ 'geburtsname' ];
        $patient[ 'geburtsdatum' ] = $extract_data[ 'geburtsdatum' ];
        $patient[ 'geschlecht' ] = $this->GetExportCode( 'geschlecht', $extract_data[ 'geschlecht' ], 'X' );
        $patient[ 'strasse' ] = $extract_data[ 'strasse' ];
        $patient[ 'hausnummer' ] = $extract_data[ 'hausnummer' ];
        $patient[ 'plz' ] = $extract_data[ 'plz' ];
        $patient[ 'wohnort' ] = $extract_data[ 'wohnort' ];
        $patient[ 'land' ] = $extract_data[ 'land' ];
        $patient[ 'sterbedatum' ] = $extract_data[ 'todesdatum' ];
        $staatsangehoerigkeit = 'X';
        if ( strlen( $extract_data[ 'land' ] ) > 0 ) {
            if ( 'D' == $extract_data[ 'land' ] ) {
                $staatsangehoerigkeit = 'D';
            }
            else {
                $staatsangehoerigkeit = 'N';
            }
        }
        $patient[ 'staatsangehoerigkeit' ] = $staatsangehoerigkeit;
        $section_uid = 'PAT_' . $extract_data[ 'patient_id' ];

        return $patient;
    }

    protected function CreateDiagnoseSection( $parameters, $extract_data, &$section_uid )
    {
        $diagnose = array();

        $diagnose['id']                 = $extract_data['tumoridentifikator'];
        $diagnose['tumoridentifikator'] = $extract_data['tumoridentifikator'];
        $diagnose['referenznr']         = $extract_data['referenznr'];
        $diagnose['erstdiagnosedatum']  = date('Y-m', strtotime($extract_data['erstdiagnose_datum']));
        $diagnose['diagnose_icd']       = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'diagnose'
        );
        $diagnose['icd_revision']       = '10';
        $diagnose['lokalisation_icd_o'] = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'lokalisation'
        );
        $side = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'diagnose_seite'
        );
        $diagnose['seitenlokalisation'] = $side == '-' ? 'X' : $side;
        $diagnose['icd_o_version']      = '3';
        $diagnose['histologie_icd_o']   = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'morphologie'
        );
        $diagnose['tnm_version']        =
            date('Y', strtotime($extract_data['erstdiagnose_datum'])) <= '2009' ? '6' : '7';
        if ($this->_hasOnlyTumorstatusWithY($extract_data['tumorstatus'], $extract_data['diagnose_seite'])) {
            $diagnose['ct_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 't', 'c'
            ));
            $diagnose['cn_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'n', 'c'
            ));
            $diagnose['cm_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'm', 'c'
            ));
            $diagnose['t_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 't', 'p'
            ));
            $diagnose['n_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'n', 'p'
            ));
            $diagnose['m_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTs(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'm'
            ));
        }
        else {
            $diagnose['ct_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 't', 'c'
            ));
            $diagnose['cn_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'n', 'c'
            ));
            $diagnose['cm_stadium']         = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'm', 'c'
            ));
            $diagnose['t_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 't', 'p'
            ));
            $diagnose['n_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                    $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'n', 'p'
            ));
            $diagnose['m_stadium_postop']   = HCommon::TrimTNM($this->_getFirstFilledTsWithoutY(
                $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'm'
            ));
        }
        $grading = $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'g');
        $diagnose['grading']            = $grading == 'B' ? 'G' :$grading;
        $diagnose['l_kategorie']        = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'l'
        );
        $diagnose['v_kategorie']        = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'v'
        );
        $diagnose['pn_kategorie']       = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'ppn'
        );
        $diagnose['figo']               = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'figo'
        );
        $diagnose['gleason_grading']    = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'gleason1'
        );
        $diagnose['gleason_grading2']   = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'gleason2'
        );
        $diagnose['gleason_score']      = $this->_calcGleasonScore(
            $diagnose['gleason_grading'],
            $diagnose['gleason_grading2']
        );
        $diagnose['ann_arbor']          = $this->GetAnnArbor(
            $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'ann_arbor_stadium')
        );
        $diagnose['binet']              = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'cll_binet'
        );
        $diagnose['durie_salmon']       = $this->_getFirstFilledTs(
            $extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'durie_salmon'
        );
        $fab = $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'aml_fab');
        if ($fab === 'M3var') {
            $fab = 'M3v';
        } elseif ($fab === 'M4 Eo') {
            $fab = 'M4Eo';
        }
        $diagnose['fab'] = $fab;
        $diagnose['rai'] =
            $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'cll_rai');
        $lk_entf = $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'lk_entf');
        $diagnose['lymphknoten_untersucht'] = intval($lk_entf) > 99 ? 99 : $lk_entf;
        $lk_bef = $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'lk_bef');
        $diagnose['lymphknoten_befallen'] = intval($lk_bef) > 99 ? 99 : $lk_bef;
        // Estro_urteil Kodierung
        $estroUrteil = strtolower(
            $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'estro_urteil')
        );
        switch ($estroUrteil) {
            case 'p' :
                $diagnose[ 'rezeptor_oestrogen' ] = '1';
                break;
            case 'n' :
                $diagnose[ 'rezeptor_oestrogen' ] = '0';
                break;
            default :
                $diagnose[ 'rezeptor_oestrogen' ] = '9';
                break;
        }
        // Prog_urteil Kodierung
        $progUrteil = strtolower(
            $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'prog_urteil')
        );
        switch ($progUrteil) {
            case 'p' :
                $diagnose[ 'rezeptor_progesteron' ] = '1';
                break;
            case 'n' :
                $diagnose[ 'rezeptor_progesteron' ] = '0';
                break;
            default :
                $diagnose[ 'rezeptor_progesteron' ] = '9';
                break;
        }
        // Her2 Kodierung
        $her2 = strtolower(
            $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'her2')
        );
        switch ($her2) {
            case 'n' :
                $diagnose[ 'rezeptor_her2' ] = '0';
                break;
            case '1' :
                $diagnose[ 'rezeptor_her2' ] = '1';
                break;
            case '2' :
                $diagnose[ 'rezeptor_her2' ] = '2';
                break;
            case '3' :
                $diagnose[ 'rezeptor_her2' ] = '3';
                break;
            default :
                $diagnose[ 'rezeptor_her2' ] = '9';
                break;
        }
        // Psa Bereichspr?fung
        $diagnose['gesamt_psa'] = null;

        $gesamtPsa = $this->_getFirstFilledTs($extract_data['tumorstatus'], $extract_data['diagnose_seite'], 'psa');
        if (strlen($gesamtPsa) > 0) {
            $value = floatval($gesamtPsa);
            if ($value < 0) {
                $diagnose['gesamt_psa'] = '0,0';
            } elseif ($value > 9.9) {
                $diagnose['gesamt_psa'] = '9,9';
            } else {
                // Fix f?r #7592
                $diagnose['gesamt_psa'] = substr(str_replace(".", ",", "" . $value), 0, 3);
            }
        }
        // Metastasenlokalisation (selbe TS wie TNM)
        $diagnose['metastasen'] = '';

        if ($this->_hasOnlyTumorstatusWithY($extract_data['tumorstatus'], $extract_data['diagnose_seite'])) {
            $diagnose['metastasen'] = $this->_getMetastases($extract_data['tumorstatus'], $extract_data['diagnose_seite']);
        } else {
            //without y
            $diagnose['metastasen'] = $this->_getMetastases($extract_data['tumorstatus'], $extract_data['diagnose_seite'], true);
        }

        // Menopausenstatus
        $diagnose['menopausenstatus'] = null;
        $anamnese = HReports::GetMaxElementByDate($extract_data['anamnesen']);
        if (false !== $anamnese) {
            $diagnose['menopausenstatus'] = $this->GetExportCode('menopause', $anamnese['menopausenstatus'], null);
        }
        // Diagnoseanlass und fr?here Tumordiagnosen
        $diagnose['diagnoseanlass'] = null;
        $diagnose['fruehere_tumordiagnosen'] = 'X';
        $anamnese = HReports::GetMinElementByDate($extract_data['anamnesen']);
        if ( false !== $anamnese ) {
            // Fix f?r Ticket #7593
            if (strlen($anamnese['entdeckung']) > 0) {
                $diagnose['diagnoseanlass'] = $this->GetExportCode('entdeckung', $anamnese['entdeckung'], null );
            } else {
                $diagnose['diagnoseanlass'] = 'X';
            }
            $diagnose[ 'fruehere_tumordiagnosen' ] = 'N';
            $anamnese_erkrankungen = $this->GetAnamneseErkrankungen($anamnese['anamnese_id']);
            if (false !== $anamnese_erkrankungen) {
                foreach($anamnese_erkrankungen as $item) {
                    if (true === $this->IsInTumordiagnosen($item['erkrankung'])) {
                        $diagnose['fruehere_tumordiagnosen'] = 'J';
                    }
                }
            }
        }
        $diagnose['wandlung_diagnose'] = $extract_data['kr_meldung']['wandlung_diagnose'];
        $diagnose[ 'tan' ] = "";
        $diagnose[ 'meldungskennzeichen' ] = "";
        $section_uid = 'DIAG_' . $extract_data[ 'tumoridentifikator' ];
        return $diagnose;
    }

    /**
     *
     *
     * @access
     * @param $tumorstatus
     * @return bool
     */
    protected function _hasOnlyTumorstatusWithY($tumorstatus, $side) {
        if (true === is_array($tumorstatus)) {
            foreach ($tumorstatus as $ts) {
                if (false === str_starts_with($ts['tnm_praefix'], array('y', 'yr')) &&
                    $side == $ts['diagnose_seite']) {
                    return false;
                }
            }
        }
        return true;
    }

    protected function IsInTumordiagnosen( $diagnose ) {
        $prefix = substr( $diagnose, 0, 1 );
        $value = floatval( substr( $diagnose, 1 ) );
        if ( ( ( 'C' == strtoupper( $prefix ) ) &&
               ( $value >= 00.0 ) && ( $value <= 96.9  ) ) ||
             ( ( 'D' == strtoupper( $prefix ) ) &&
               ( ( ( $value >= 00.0 ) && ( $value <= 09.9  ) ) ||
                 ( ( $value >= 32.0 ) && ( $value <= 33.9  ) ) ||
                 ( ( $value >= 35.2 ) && ( $value <= 35.4  ) ) ||
                 ( ( $value >= 37.0 ) && ( $value <= 48.9  ) ) ||
                 ( $value == 18.02 ) ||
                 ( $value == 18.18 ) ||
                 ( $value == 19.7 ) ||
                 ( $value == 21.0 ) ) ) ) {
            return true;
        }
        return false;
    }

    protected function GetTherapienSections( $parameters,
                                             $extract_data )
    {
        $therapien = array();
        // Systemische Therapien
        foreach( $extract_data[ 'systemische_therapien' ] as $therapie ) {
            $tmp = array();
            $tmp[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
            $tmp[ 'therapieart' ] = 'ME';
            $tmp[ 'id' ] = $therapie[ 'systemische_therapie_id' ];
            $tmp[ 'medikamentoese_therapie' ] = $this->GetExportCode( 'therapieart', $therapie[ 'art' ], null );
            $tmp[ 'beginn' ] = $therapie[ 'beginn' ];
            $tmp[ 'ende' ] = $therapie[ 'ende' ];
            $tmp[ 'abbruch' ] = ( 'abbr' == $therapie[ 'endstatus' ] ) ? 'A' : null;
            $tmp[ 'tan' ] = "";
            $tmp[ 'meldungskennzeichen' ] = "";
            $therapien[] = $tmp;
        }
        // Strahlen Therapien
        foreach( $extract_data[ 'strahlen_therapien' ] as $therapie ) {
            $tmp = array();
            $tmp[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
            $tmp[ 'therapieart' ] = 'ST';
            $tmp[ 'id' ] = $therapie[ 'strahlentherapie_id' ];
            $tmp[ 'strahlentherapie' ] = $this->GetExportCode( 'wirkstoff', $therapie[ 'art' ], null );
            $tmp[ 'beginn' ] = $therapie[ 'beginn' ];
            $tmp[ 'ende' ] = $therapie[ 'ende' ];
            $tmp[ 'abbruch' ] = ( 'abbr' == $therapie[ 'endstatus' ] ) ? 'A' : null;
            $tmp[ 'tan' ] = "";
            $tmp[ 'meldungskennzeichen' ] = "";
            $therapien[] = $tmp;
        }
        // Sonstige Therapien
        foreach( $extract_data[ 'sonstige_therapien' ] as $therapie ) {
            $tmp = array();
            $tmp[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
            $tmp[ 'therapieart' ] = 'SO';
            $tmp[ 'id' ] = $therapie[ 'sonstige_therapie_id' ];
            $tmp[ 'sonstige_therapie' ] = "S";
            $tmp[ 'therapie_detail' ] = "";
            $tmp[ 'beginn' ] = $therapie[ 'beginn' ];
            $tmp[ 'ende' ] = $therapie[ 'ende' ];
            $tmp[ 'abbruch' ] = ( 'abbr' == $therapie[ 'endstatus' ] ) ? 'A' : null;
            $tmp[ 'tan' ] = "";
            $tmp[ 'meldungskennzeichen' ] = "";
            $therapien[] = $tmp;
        }

        // Operationen
        foreach( $extract_data[ 'operationen' ] as $therapie ) {
            $conditionalCodes = array();
            $validCodes       = array();

            if (isset($therapie['ops_codes'])) {
                foreach ($therapie['ops_codes'] as $codes) {
                    if (str_starts_with($codes['prozedur'], "5-") === true &&
                        str_starts_with($codes['prozedur'], "5-411") === false &&
                        str_contains($codes['prozedur'], '-e') === false
                    ) {
                        if (str_starts_with($codes['prozedur'], '5-93') === true ||
                            str_starts_with($codes['prozedur'], '5-94') === true ||
                            str_starts_with($codes['prozedur'], '5-95') === true ||
                            str_starts_with($codes['prozedur'], '5-96') === true ||
                            str_starts_with($codes['prozedur'], '5-97') === true ||
                            str_starts_with($codes['prozedur'], '5-98') === true ||
                            str_starts_with($codes['prozedur'], '5-99') === true
                        ) {
                            $conditionalCodes[] = array(
                                'prozedur'       => $codes['prozedur'],
                                'prozedur_seite' => $codes['prozedur_seite'],
                                'prozedur_text'  => $codes['prozedur_text']
                            );
                        } else {
                            $validCodes[] = array(
                                'prozedur'       => $codes['prozedur'],
                                'prozedur_seite' => $codes['prozedur_seite'],
                                'prozedur_text'  => $codes['prozedur_text']
                            );
                        }
                    }
                }
            }

            if (count($validCodes) > 0 && count($conditionalCodes) > 0) {
                $validCodes = array_merge($validCodes, $conditionalCodes);
            }

            if (count($validCodes) > 0) {
                $tmp = array();
                $tmp[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
                $tmp[ 'therapieart' ] = 'OP';
                $tmp[ 'id' ] = $therapie[ 'eingriff_id' ];
                $tmp[ 'beginn' ] = $therapie[ 'beginn' ];
                $tmp[ 'ops_codes' ] = $validCodes;
                $tmp[ 'abbruch' ] = null;
                $tmp[ 'tan' ] = "";
                $tmp[ 'meldungskennzeichen' ] = "";
                $therapien[] = $tmp;
            }
        }
        return $therapien;
    }

    protected function GetNachsorgenSections($parameters, $extract_data)
    {
        $result = array();

        if (isset($extract_data['verlauf']) && is_array($extract_data['verlauf'])) {
            foreach ($extract_data['verlauf'] as $nachsorge) {
                if ($nachsorge['org_id'] === $extract_data['org_id']) {
                    // Nur Nachsorgen beachten die nach dem ersten Eingriff liegen.
                    // Fix f?r Ticket #7596
                    //if ( strtotime( $nachsorge[ 'datum' ] ) >= strtotime( $extract_data[ 'primaerop_datum' ] ) ) {
                    $nachsorge['tumoridentifikator' ]  = $extract_data['tumoridentifikator'];
                    $nachsorge['untersuchungsdatum']   = $nachsorge['datum'];
                    $nachsorge['tumorgeschehen' ]      =
                        $this->GetExportCode('response', $nachsorge['tumorgeschehen'], 'X');
                    $nachsorge['tnm_version']          = '';
                    $nachsorge['t']                    = '';
                    $nachsorge['n']                    = '';
                    $nachsorge['m']                    = '';
                    $nachsorge['tan' ]                 = "";
                    $nachsorge['meldungskennzeichen' ] = "";
                    $result[] = $nachsorge;
                    //}
                }
            }
        }
        if (isset($extract_data['tumorstatus']) &&
            is_array($extract_data['tumorstatus']) &&
            false === $this->_hasOnlyTumorstatusWithY($extract_data['tumorstatus'], $extract_data['diagnose_seite'])) {
            $nachsorge = array();
            $primaryTs = $this->_getLatestPrimaryTsWithY($extract_data['tumorstatus'], $extract_data['diagnose_seite']);
            if (false !== $primaryTs) {
                $nachsorge['tumorstatus_id']          = $primaryTs['tumorstatus_id'];
                $nachsorge['tumoridentifikator']      = $extract_data['tumoridentifikator'];
                $nachsorge['datum']                   = $primaryTs['datum_sicherung'];
                $nachsorge['tumorgeschehen']          = 'X';
                $nachsorge['tnm_version']             =
                    strtotime($primaryTs['datum_sicherung']) >= strtotime('2010-01-01') ? '7' : '6';
                $nachsorge['t']                       =
                    $primaryTs['tnm_praefix'] . HCommon::TrimTNM($primaryTs['t']);
                $nachsorge['n']                       = HCommon::TrimTNM($primaryTs['n']);
                $nachsorge['m']                       = HCommon::TrimTNM($primaryTs['m']);
                foreach ($extract_data['tumorstatus_metastasen_alle'] as $metastasis) {
                    if ($primaryTs['tumorstatus_id'] === $metastasis['tumorstatus_id']) {
                        $nachsorge['metastasen'][] =
                            array(
                                'metastasenlokalisation'  => $metastasis['lokalisation'],
                                'metastase_diagnosedatum' => substr($primaryTs['datum_sicherung'], 0, 7
                            )
                        );
                     }
                }
                $nachsorge[ 'tan' ]                   = "";
                $nachsorge[ 'meldungskennzeichen' ]   = "";
                $result[] = $nachsorge;
            }
        }
        if (isset($extract_data['tumorstatus_alle']) && is_array($extract_data['tumorstatus_alle'])) {
            // Alle Rezidive
            foreach ($extract_data['tumorstatus_alle'] as $tumorstatus) {
                if (str_starts_with($tumorstatus['anlass'], 'r') == true) {
                    $nachsorge = array();
                    $nachsorge['tumorstatus_id']        = $tumorstatus['tumorstatus_id'];
                    $nachsorge['tumoridentifikator']    = $extract_data['tumoridentifikator'];
                    $nachsorge['datum']                 = $tumorstatus['datum_sicherung'];
                    $nachsorge['tumorgeschehen']        = 'X';
                    $nachsorge['tnm_version']           =
                        strtotime($tumorstatus['datum_sicherung']) >= strtotime('2010-01-01') ? '7' : '6';
                    $nachsorge['t']                     =
                        $tumorstatus['tnm_praefix'] . HCommon::TrimTNM($tumorstatus['t']);
                    $nachsorge['n']                     = HCommon::TrimTNM($tumorstatus['n']);
                    $nachsorge['m']                     = HCommon::TrimTNM($tumorstatus['m']);
                    foreach ($extract_data['tumorstatus_metastasen_alle'] as $metastasis) {
                        if ($tumorstatus['tumorstatus_id'] === $metastasis['tumorstatus_id']) {
                            $nachsorge['metastasen'][] =
                                array(
                                    'metastasenlokalisation'  => $metastasis['lokalisation'],
                                    'metastase_diagnosedatum' => substr($tumorstatus['datum_sicherung'], 0, 7
                                )
                            );
                        }
                    }
                    $nachsorge['tan']                 = "";
                    $nachsorge['meldungskennzeichen'] = "";
                    $result[] = $nachsorge;
                }
            }
        }
        return $result;
    }

    protected function CreateAbschlussSection( $parameters,
                                               $extract_data,
                                               &$section_uid )
    {
        $abschluss = array();
        $abschlussgrund = $this->GetExportCode( 'abschluss_grund', $extract_data[ 'abschlussgrund' ], null );
        if ( strlen( $abschlussgrund ) > 0 ) {
            $abschluss[ 'id' ] = $extract_data[ 'abschluss_id' ];
            $abschluss[ 'tumoridentifikator' ] = $extract_data[ 'tumoridentifikator' ];
            $abschluss[ 'referenznr' ] = $extract_data[ 'referenznr' ];
            $abschluss[ 'abschlussgrund' ] = $abschlussgrund; // #7545
            $abschluss[ 'sterbedatum' ] = null;
            $abschluss[ 'tod_tumorbedingt' ] = null;
            $abschluss[ 'letzte_patienteninformation' ] = null;
            if ( 'T' == $abschluss[ 'abschlussgrund' ] ) {
                $abschluss[ 'sterbedatum' ] = $extract_data[ 'todesdatum' ];
                $abschluss[ 'tod_tumorbedingt' ] = $this->GetExportCode( 'tod_tumorassoziation', $extract_data[ 'tod_tumorbedingt' ], 'X' );
            }
            else if ( 'L' == $abschluss[ 'abschlussgrund' ] ) {
                $abschluss[ 'letzte_patienteninformation' ] = $extract_data[ 'letzte_patienteninformation' ];
            }
            $abschluss[ 'tan' ] = "";
            $abschluss[ 'meldungskennzeichen' ] = "";
            $section_uid = 'ABSCH_' . $extract_data[ 'abschluss_id' ];
        }
        return $abschluss;
    }


    /**
     *
     *
     * @access
     * @param $ann_arbor
     * @return null
     */
    protected function GetAnnArbor( $ann_arbor ) {
        if ( isset( $this->m_ann_arbor_codings[ $ann_arbor ] ) ) {
            return $this->m_ann_arbor_codings[ $ann_arbor ];
        }
        return null;
    }


    /**
     *
     *
     * @access
     * @param $value
     * @return mixed|string
     */
    protected function _killSn( $value ) {
        if (null === $value) {
            return "";
        }
        return str_replace('(sn)', '', $value);
    }


    /**
     *
     *
     * @access
     * @param   $tumorstatus
     * @return  mixed
     */
    protected function _getLatestPrimaryTsWithY($tumorstatus, $side)
    {
        if (null !== $tumorstatus && is_array($tumorstatus)) {
            foreach($tumorstatus as $ts) {
                if ('p' === $ts['anlass'] &&
                    true === str_starts_with($ts['tnm_praefix'], array('y', 'yr')) &&
                    $side == $ts['diagnose_seite']) {
                    return $ts;
                }
            }
        }
        return false;
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
     * Gibt den ersten Tumorstatus aus dem ?bergebenen Array zur?ck, der kein Pr?fix mit 'y' oder 'yr' enth?lt
     * und gef?llt ist. Falls als ?bergabe in 'fieldsValueStartsWith' ein Array mit Werten ?bergeben wurde, wird nicht
     * nur gepr?ft ob gef?llt, sondern ob es auch eine ?bereinstimmung gibt.
     * Es wird auch die Seite der Situation beachtet.
     *
     * @access  protected
     * @param   array             $tumorstatus
     * @param   string            $side
     * @param   string            $field
     * @param   string|array|bool $fieldsValueStartsWith
     * @return  string|null
     */
    protected function _getFirstFilledTsWithoutY($tumorstatus, $side, $field, $fieldsValueStartsWith = false)
    {
        if (false !== $fieldsValueStartsWith && false == is_array($fieldsValueStartsWith)) {
            $fieldsValueStartsWith = array($fieldsValueStartsWith);
        }
        // Gibt es Tumorstatus und
        if (count($tumorstatus) > 0) {
            // Gehe ?ber alle Tumorstatus
            foreach ($tumorstatus as $ts) {
                // Gibt es das Feld ?berhaupt
                if (true === isset($ts[$field])) {
                    // Ist es ein Tumorstatus ohne 'y' oder 'yr' als Pr?fix und stimmt die Seite mit der
                    // aktuellen Seite der Situation ?berein
                    if (false === str_starts_with($ts['tnm_praefix'], array('y', 'yr')) &&
                        $side == $ts['diagnose_seite']) {
                        // Gibt es spezielle Werte zum Pr?fen
                        if ($fieldsValueStartsWith !== false) {
                            // Gibt es eine ?bereinstimmung mit einem der speziellen Werte
                            if (str_starts_with($ts[$field], $fieldsValueStartsWith)) {
                                // wenn ja, das Ergebnis zur?ck geben.
                                return $ts[$field];
                            }
                        } elseif (strlen($ts[$field]) > 0) {
                            // Wenn keine speziellen Werte und das Feld gef?llt, dann  Ergebnis zur?ck geben.
                            return $ts[$field];
                        }
                    }
                }
                else {
                    // Wenn es das Feld nicht gbit, direkt raus ohne Ergebnis
                    break;
                }
            }
        }
        return "";
    }

    /**
     * Gibt den ersten Tumorstatus aus dem ?bergebenen Array zur?ck, der ein Pr?fix mit 'y' oder 'yr' oder 'r' enth?lt
     * und gef?llt ist. Falls als ?bergabe in 'fieldsValueStartsWith' ein Array mit Werten ?bergeben wurde, wird nicht
     * nur gepr?ft ob gef?llt, sondern ob es auch eine ?bereinstimmung gibt.
     * Es wird auch die Seite der Situation beachtet.
     *
     * @access  protected
     * @param   array             $tumorstatus
     * @param   string            $side
     * @param   string            $field
     * @param   string|array|bool $fieldsValueStartsWith
     * @return  string|null
     */
    protected function _getFirstFilledTsWithY($tumorstatus, $side, $field, $fieldsValueStartsWith = false)
    {
        if (false !== $fieldsValueStartsWith && false == is_array($fieldsValueStartsWith)) {
            $fieldsValueStartsWith = array($fieldsValueStartsWith);
        }
        // Gibt es Tumorstatus und
        if (count($tumorstatus) > 0) {
            // Gehe ?ber alle Tumorstatus
            foreach ($tumorstatus as $ts) {
                // Gibt es das Feld ?berhaupt
                if (true === isset($ts[$field])) {
                    // Ist es ein Tumorstatus mit 'y' oder 'yr' oder 'r' als Pr?fix und stimmt die Seite mit der
                    // aktuellen Seite der Situation ?berein
                    if (true === str_starts_with($ts['tnm_praefix'], array('y', 'yr')) &&
                        $side == $ts['diagnose_seite']) {
                        // Gibt es spezielle Werte zum Pr?fen
                        if ($fieldsValueStartsWith !== false) {
                            // Gibt es eine ?bereinstimmung mit einem der speziellen Werte
                            if (str_starts_with($ts[$field], $fieldsValueStartsWith)) {
                                // wenn ja, das Ergebnis zur?ck geben.
                                return $ts[$field];
                            }
                        } elseif (strlen($ts[$field]) > 0) {
                            // Wenn keine speziellen Werte und das Feld gef?llt, dann  Ergebnis zur?ck geben.
                            return $ts[$field];
                        }
                    }
                }
                else {
                    // Wenn es das Feld nicht gbit, direkt raus ohne Ergebnis
                    break;
                }
            }
        }
        return "";
    }

    /**
     * Gibt den ersten Tumorstatus aus dem ?bergebenen Array zur?ck bei dem das Feld gef?llt ist. Falls als ?bergabe
     * in 'fieldsValueStartsWith' ein Array mit Werten ?bergeben wurde, wird nicht nur gepr?ft ob gef?llt, sondern ob
     * es auch eine ?bereinstimmung gibt.
     * Es wird auch die Seite der Situation beachtet.
     *
     * @access  protected
     * @param   array             $tumorstatus
     * @param   string            $side
     * @param   string            $field
     * @param   string|array|bool $fieldsValueStartsWith
     * @return  string|null
     */
    protected function _getFirstFilledTs($tumorstatus, $side, $field, $fieldsValueStartsWith = false)
    {
        if (false !== $fieldsValueStartsWith && false == is_array($fieldsValueStartsWith)) {
            $fieldsValueStartsWith = array($fieldsValueStartsWith);
        }
        // Gibt es Tumorstatus und
        if (count($tumorstatus) > 0) {
            // Gehe ?ber alle Tumorstatus
            foreach ($tumorstatus as $ts) {
                // Gibt es das Feld ?berhaupt
                if (true === isset($ts[$field])) {
                    // Stiimt die Seite
                    if ($side == $ts['diagnose_seite']) {
                        // Gibt es spezielle Werte zum Pr?fen
                        if ($fieldsValueStartsWith !== false) {
                            // Gibt es eine ?bereinstimmung mit einem der speziellen Werte
                            if (!is_array($ts[$field]) && str_starts_with($ts[$field], $fieldsValueStartsWith)) {
                                // wenn ja, das Ergebnis zur?ck geben.
                                return $ts[$field];
                            }
                        }
                        else if ((is_array($ts[$field]) && count($ts[$field]) > 0) ||
                                 (!is_array($ts[$field]) && strlen($ts[$field]) > 0)) {
                            // Wenn keine speziellen Werte und das Feld gef?llt, dann  Ergebnis zur?ck geben.
                            return $ts[$field];
                        }
                    }
                }
                else {
                    // Wenn es das Feld nicht gbit, direkt raus ohne Ergebnis
                    break;
                }
            }
        }
        return "";
    }


    /**
     * returnes merged Tumorstate with posTable Morphology, sorted -> newest first
     *
     * @access   protected
     * @param $tumorstatus
     * @param $metastases
     * @internal param array $data
     * @return  array
     */
    protected function _mergeTumorstatusWithMorphologie($tumorstatus, $metastases)
    {
        $result =  array();
        if (is_array($tumorstatus) && count($tumorstatus) > 0) {
            $tmp = $tumorstatus;
            // first sort
            usort($tmp, array("Ckrbw_2_1_Model", "_compareTumorstatusByDateDesc"));
            foreach ($tumorstatus as $ts) {
                $id = $ts['tumorstatus_id'];
                $result[$id] = $ts;
                $result[$id]['tumorstatus_metastasen'] = array();
                foreach ($metastases as $metastasen) {
                    if ($id === $metastasen['tumorstatus_id']) {
                        $result[$id]['tumorstatus_metastasen'][] = $metastasen;
                    }
                }
            }
        }
        return $result;
    }


    /**
     * Gleason Score bestimmen
     *
     * @access  protected
     * @param   string      $gleason1
     * @param   string      $gleason2
     * @return  int|null
     */
    protected function _calcGleasonScore($gleason1, $gleason2)
    {
        if ((strlen($gleason1) > 0) && (strlen($gleason2) > 0)) {
            return intval($gleason1) + intval($gleason2);
        }
        return null;
    }


    /**
     *
     *
     * @access
     * @param      $tumorstatus
     * @param      $side
     * @param bool $withoutY
     * @return array
     */
    protected function _getMetastases($tumorstatus, $side, $withoutY = false)
    {
        if (count($tumorstatus) > 0) {
            foreach ($tumorstatus as $ts) {
                if ($withoutY === true && str_starts_with($ts['tnm_praefix'], 'y') === true) {
                    continue;
                } elseif ($side == $ts['diagnose_seite'] && count($ts['tumorstatus_metastasen']) > 0 ) {
                    foreach ($ts['tumorstatus_metastasen'] as $metastasis) {
                        $result[] = $metastasis['lokalisation'];
                    }

                    return $result;
                }
            }
        }

        return array();
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
}
?>
