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

require_once( 'feature/export/base/class.exportxmlserialiser.php' );
require_once( 'feature/export/history/class.historymanager.php' );
require_once( 'feature/export/history/class.history.php' );

class Ckrbw_2_1_Serialiser extends CExportXmlSerialiser
{

    public function __construct()
    {
        $this->m_xml_template_file = "krbw_2_1.tpl";
        $this->m_xml_schema_file = "feature/export/krbw/krbw_2_1.xsd";
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportXmlSerialiser
    //

    public function Validate( $parameters )
    {
        $c = 0;
        $data = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $case_key => $case ) {
            $sections = $case->GetSections();
            $data = $this->CreatePatientDataArray( $case );
            // Check Melder and Patient...
            $this->m_internal_smarty->assign( 'data', $data );
            $xml = utf8_encode( $this->m_internal_smarty->fetch( $this->m_xml_template_file ) );
            $errors = $this->ParseXmlForErrors( $xml );
            if ( false !== $errors ) {
                // Es sind "Undefined Index: xxx" Fehler aufgetreten
                $case->SetSectionErrorsByName( 'patient', $errors );
            }
            else {
                $errors = $this->XmlSchemaValidate( $xml, $this->m_xml_schema_file );
                $case->SetSectionErrorsByName( 'melder', array() );
                $case->SetSectionErrorsByName( 'patient', $errors );
            }
            foreach( $sections as $section_key => $section ) {
                $c++;
                $meldungskennzeichen = $section->GetMeldungskennzeichen();
                if ( '' == $meldungskennzeichen ) {
                    $meldungskennzeichen = 'N'; // Nur für den Check!!!
                }
                if ( ( 'melder' != $section->GetBlock() ) &&
                     ( 'patient' != $section->GetBlock() ) ) {
                    $data = $this->CreateMeldungDataArray();
                    switch( $section->GetBlock() ) {
                        case 'diagnose' :
                            $tmp = $section->GetDaten();
                            // Nur hier als temporärer, wird erst beim wirklichen schreiben
                            // erzeugt
                            $tmp[ 'tan' ] = $data[ 'melder' ][ 'id' ] . sprintf( "%07d", $c );
                            $tmp[ 'meldungskennzeichen' ] = $meldungskennzeichen;
                            $data[ 'patients' ][ 0 ][ 'diagnosen' ][] = $tmp;
                            break;
                        case 'therapie' :
                            $tmp = $section->GetDaten();
                            // Nur hier als temporärer, wird erst beim wirklichen schreiben
                            // erzeugt
                            $tmp[ 'tan' ] = $data[ 'melder' ][ 'id' ] . sprintf( "%07d", $c );
                            $tmp[ 'meldungskennzeichen' ] = $meldungskennzeichen;
                            $data[ 'patients' ][ 0 ][ 'therapien' ][] = $tmp;
                            break;
                        case 'nachsorge' :
                            $tmp = $section->GetDaten();
                            // Nur hier als temporärer, wird erst beim wirklichen schreiben
                            // erzeugt
                            $tmp[ 'tan' ] = $data[ 'melder' ][ 'id' ] . sprintf( "%07d", $c );
                            $tmp[ 'meldungskennzeichen' ] = $meldungskennzeichen;
                            $data[ 'patients' ][ 0 ][ 'verlaeufe' ][] = $tmp;
                            break;
                        case 'abschluss' :
                            $tmp = $section->GetDaten();
                            // Nur hier als temporärer, wird erst beim wirklichen schreiben
                            // erzeugt
                            $tmp[ 'tan' ] = $data[ 'melder' ][ 'id' ] . sprintf( "%07d", $c );
                            $tmp[ 'meldungskennzeichen' ] = $meldungskennzeichen;
                            $data[ 'patients' ][ 0 ][ 'abschluesse' ][] = $tmp;
                            break;
                        default :
                            break;
                    }
                    $data = $this->ReplaceAllXmlEntities( $data );
                    $this->m_internal_smarty->assign( 'data', $data );
                    $xml = $this->m_internal_smarty->fetch( $this->m_xml_template_file );
                    $errors = $this->ParseXmlForErrors( $xml );
                    if ( false !== $errors ) {
                        // Es sind "Undefined Index: xxx" Fehler aufgetreten
                        $case->SetSectionErrorsByUid( $section->GetSectionUid(), $errors );
                    }
                    else {
                        $errors = $this->XmlSchemaValidate( $xml, $this->m_xml_schema_file );
                        $case->SetSectionErrorsByUid( $section->GetSectionUid(), $errors );
                    }
                }
            }
            $cases[ $case_key ] = $case;
        }
        $this->m_export_record->SetCases( $cases );
    }

    public function Encrypt( $parameters )
    {
    }

    public function Write( $parameters )
    {
        $tan_count = $this->m_export_record->GetNextTan();
        $xml_file = parent::Write( $parameters );
        $patient_counter = -1;
        $last_patient_id = 0;
        $data = null;
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $cases_key => $case ) {
            if ( ( 1 == $case->HasDataChanged() ) &&
                 $case->IsValid() ) {
                if ( null == $data ) {
                    $data = $this->CreateDataArray( $case );
                }
                if ( $case->GetPatientId() != $last_patient_id ) {
                    $last_patient_id = $case->GetPatientId();
                    $patient_counter++;
                }
                if ( !isset( $data[ 'patients' ][ $patient_counter ][ 'nachname' ] ) ) {
                    $data[ 'patients' ][ $patient_counter ] = $this->GetSectionData( $case, 'patient' );
                    $data[ 'patients' ][ $patient_counter ][ 'diagnosen' ] = array();
                    $data[ 'patients' ][ $patient_counter ][ 'therapien' ] = array();
                    $data[ 'patients' ][ $patient_counter ][ 'verlaeufe' ] = array();
                    $data[ 'patients' ][ $patient_counter ][ 'abschluesse' ] = array();
                }
                $sections = $case->GetSections();
                foreach( $sections as $key_section => $section ) {
                    // Nur sections exportieren wenn sich etwas geändert hat.
                    if ( 1 == $section->HasDataChanged() ) {
                        $meldungskennzeichen = $section->GetMeldungskennzeichen();
                        if ( ( 'melder' != $section->GetBlock() ) &&
                             ( 'patient' != $section->GetBlock() ) ) {
                            $tmp = $section->GetDaten();
                            if ( ( 'N' == $meldungskennzeichen ) ||
                                   !isset( $tmp[ 'tan' ] ) ||
                                   ( strlen( $tmp[ 'tan' ] ) == 0 ) ) {
                                $tmp[ 'tan' ] = $data[ 'melder' ][ 'id' ] . sprintf( "%07d", $tan_count );
                                $case->SetSectionTanByUid( $section->GetSectionUid(), $tmp[ 'tan' ] );
                                $tan_count++;
                            }
                            $tmp[ 'meldungskennzeichen' ] = $meldungskennzeichen;
                            switch( $section->GetBlock() ) {
                                case 'diagnose' :
                                    $data[ 'patients' ][ $patient_counter ][ 'diagnosen' ][] = $tmp;
                                    break;
                                case 'therapie' :
                                    $data[ 'patients' ][ $patient_counter ][ 'therapien' ][] = $tmp;
                                    break;
                                case 'nachsorge' :
                                    $data[ 'patients' ][ $patient_counter ][ 'verlaeufe' ][] = $tmp;
                                    break;
                                case 'abschluss' :
                                    $data[ 'patients' ][ $patient_counter ][ 'abschluesse' ][] = $tmp;
                                    break;
                                default :
                                    break;
                            }
                            $section->SetDaten( $tmp );
                        }
                    }
                    $sections[ $key_section ] = $section;
                }
                $cases[ $cases_key ] = $case;
            }
        }
        $this->m_export_record->SetNextTan( $tan_count );
        $this->m_export_record->SetCases( $cases );
        $this->m_export_record->Write( $this->m_db );
        if ( null != $data ) {
            $data = $this->ReplaceAllXmlEntities( $data );
            $this->m_internal_smarty->assign( 'data', $data );
            $xml = $this->m_internal_smarty->fetch( $this->m_xml_template_file );
            file_put_contents( $xml_file, utf8_encode( $xml ) );
            $this->m_smarty->assign( 'zip_url', $xml_file );
            $this->m_smarty->assign( 'zip_filename', 'Export Datei' );

            // History erstellen
            $historyManager = CHistoryManager::getInstance();
            $historyManager->initialise($this->m_db, $this->m_smarty);
            $history = $historyManager->createHistory();
            $history->setExportLogId($this->m_export_record->GetDbId());
            $history->setExportName($this->m_export_record->GetExportName());
            $history->setOrgId($parameters['org_id']);
            $history->setUserId($parameters['user_id']);
            $history->setDate(date('Ymd', time()));
            $history->addFilter('Melder ID', $parameters['melder_id']);
            $history->addFilter('Prüfcode', $parameters['melder_pruefcode']);
            $history->setFiles(
                array(
                     $xml_file
                )
            );
            $historyManager->insertHistory($history);
        }
        return $xml_file;
    }

    public function GetFilename()
    {
        return 'krbw_export_' . date( 'YmdHis' ) . '.xml';
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

    /**
     *
     * @param $case
     * @param $block_name
     * @return unknown_type
     */
    protected function GetSectionData( $case, $block_name )
    {
        $result = array();
        foreach( $case->GetSections() as $section ) {
            if ( $block_name == $section->GetBlock() ) {
                $result = $section->GetDaten();
            }
        }
        return $result;
    }

    protected function CreateValideMelderBlock()
    {
        $melder = array();
        $melder[ 'id' ] = '123456';
        $melder[ 'pruefcode' ] = '654321';
        $melder[ 'ansprechpartner' ] = null;
        $melder[ 'quellsystem' ] = null;
        return $melder;
    }

    protected function CreateValidPatientBlock()
    {
        $patient = array();
        $patient[ 'versicherungsnummer' ] = null;
        $patient[ 'referenznr' ] = 'PATIENT-000.1';
        $patient[ 'unterrichtung' ] = 'J';
        $patient[ 'titel' ] = null;
        $patient[ 'nachname' ] = 'Demo';
        $patient[ 'vorname' ] = 'Dieter';
        $patient[ 'geburtsname' ] = null;
        $patient[ 'geburtsdatum' ] = '1968-01-01';
        $patient[ 'geschlecht' ] = 'W';
        $patient[ 'land' ] = 'D';
        $patient[ 'plz' ] = '35394';
        $patient[ 'wohnort' ] = 'Giessen';
        $patient[ 'strasse' ] = 'Holzweg';
        $patient[ 'hausnummer' ] = null;
        $patient[ 'staatsangehoerigkeit' ] = null;
        return $patient;
    }

    protected function CreateValidAbschlussBlock()
    {
        $abschluss = array();
        $abschluss[ 'tan' ] = '1234560000001';
        $abschluss[ 'meldungskennzeichen' ] = 'N';
        $abschluss[ 'tumoridentifikator' ] = '23';
        $abschluss[ 'abschlussgrund' ] = 'L';
        $abschluss[ 'letzte_patienteninformation' ] = '2012-03-16';
        return $abschluss;
    }

    /**
     *
     * @param $case
     * @return unknown_type
     */
    protected function CreatePatientDataArray( $case )
    {
        $data = array();
        $data[ 'melder' ] = $this->CreateValideMelderBlock();
        $data[ 'patients' ] = array();
        $data[ 'patients' ][ 0 ] = $this->GetSectionData( $case, 'patient' );
        $data[ 'patients' ][ 0 ][ 'diagnosen' ] = array();
        $data[ 'patients' ][ 0 ][ 'therapien' ] = array();
        $data[ 'patients' ][ 0 ][ 'verlaeufe' ] = array();
        $data[ 'patients' ][ 0 ][ 'abschluesse' ][ 0 ] = $this->CreateValidAbschlussBlock();
        return $data;
    }

/**
     *
     * @param $case
     * @return unknown_type
     */
    protected function CreateMeldungDataArray()
    {
        $data = array();
        $data[ 'melder' ] = $this->CreateValideMelderBlock();
        $data[ 'patients' ] = array();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'diagnosen' ] = array();
        $data[ 'patients' ][ 0 ][ 'therapien' ] = array();
        $data[ 'patients' ][ 0 ][ 'verlaeufe' ] = array();
        $data[ 'patients' ][ 0 ][ 'abschluesse' ] = array();
        return $data;
    }

    /**
     *
     * @param $case
     * @return unknown_type
     */
    protected function CreateDataArray( $case )
    {
        $data = array();
        $data[ 'melder' ] = $this->GetSectionData( $case, 'melder' );
        $data[ 'patients' ] = array();
        return $data;
    }

}

?>
