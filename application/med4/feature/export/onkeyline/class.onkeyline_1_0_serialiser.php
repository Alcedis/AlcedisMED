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

require_once( DIR_LIB . '/zip/pclzip.lib.php' );
require_once( 'feature/export/base/class.exportxmlserialiser.php' );
require_once( 'feature/export/history/class.historymanager.php' );
require_once( 'feature/export/history/class.history.php' );

class Conkeyline_1_0_Serialiser extends CExportXmlSerialiser
{

    public function __construct()
    {
        $this->m_xml_template_file = "onkeyline_1_0.tpl";
        $this->m_xml_schema_file = "feature/export/onkeyline/onkeyline_1_0.xsd";
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportXmlSerialiser
    //

    public function Validate( $parameters )
    {
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $case_key => $case ) {
            // Eine Section wird eine Datei
            $sections = $case->GetSections();
            foreach( $sections as $section_key => $section ) {
                switch( $section->GetBlock() ) {
                    case "patient":
                        $this->m_xml_template_file = "onkeyline_1_0_pat.tpl";
                        break;
                    case "firstIntroduction":
                        $this->m_xml_template_file = "onkeyline_1_0_ers.tpl";
                        break;
                    case "secondary":
                        $this->m_xml_template_file = "onkeyline_1_0_sek.tpl";
                        break;
                    case "surgical":
                        $this->m_xml_template_file = "onkeyline_1_0_ope.tpl";
                        break;
                    case "radioTherapy":
                        $this->m_xml_template_file = "onkeyline_1_0_bes.tpl";
                        break;
                    case "internistical":
                        $this->m_xml_template_file = "onkeyline_1_0_int.tpl";
                        break;
                    case "closure":
                        $this->m_xml_template_file = "onkeyline_1_0_abs.tpl";
                        break;
                    case "melanom":
                        $this->m_xml_template_file = "onkeyline_1_0_mal.tpl";
                        break;
                }
                $data = $this->ReplaceAllXmlEntities( $section->GetDaten() );
                $this->m_internal_smarty->assign( $section->GetBlock(), $data );
                $xml = $this->m_internal_smarty->fetch( $this->m_xml_template_file );
                $errors = $this->ParseXmlForErrors( $xml );
                if ( false !== $errors ) {
                    // Es sind "Undefined Index: xxx" Fehler aufgetreten
                    $case->SetSectionErrorsByUid( $section->GetSectionUid(), $errors );
                }
                else {
                    $errors = $this->XmlSchemaValidate( $xml, $this->m_xml_schema_file );
                    if ( count( $errors ) > 0 ) {
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
        $files = array();
        $export_path = $this->GetExportPath( $parameters[ 'main_dir' ], $parameters[ 'login_name' ] );
        if ( file_exists( $export_path ) ) {
            HFileSystem::DeleteDirectory( $export_path );
        }
        $xml_dir = $export_path . $parameters[ 'xml_dir' ];
        HFileSystem::CreatePath( $xml_dir );
        $invaild_patients_count = 0;
        $vaild_patient_ids = $this->m_export_record->GetAllValidPatientIds( $invaild_patients_count );
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $case_key => $case ) {
            if ( in_array( $case->GetPatientId(), $vaild_patient_ids ) ) {
                // Eine Section wird eine Datei
                $sections = $case->GetSections();
                foreach( $sections as $section_key => $section ) {
                    switch( $section->GetBlock() ) {
                        case "patient":
                            $this->m_xml_template_file = "onkeyline_1_0_pat.tpl";
                            $post_fix = ".pat";
                            break;
                        case "firstIntroduction":
                            $this->m_xml_template_file = "onkeyline_1_0_ers.tpl";
                            $post_fix = ".ers";
                            break;
                        case "secondary":
                            $this->m_xml_template_file = "onkeyline_1_0_sek.tpl";
                            $post_fix = ".sek";
                            break;
                        case "surgical":
                            $this->m_xml_template_file = "onkeyline_1_0_ope.tpl";
                            $post_fix = ".ope";
                            break;
                        case "radioTherapy":
                            $this->m_xml_template_file = "onkeyline_1_0_bes.tpl";
                            $post_fix = ".bes";
                            break;
                        case "internistical":
                            $this->m_xml_template_file = "onkeyline_1_0_int.tpl";
                            $post_fix = ".int";
                            break;
                        case "closure":
                            $this->m_xml_template_file = "onkeyline_1_0_abs.tpl";
                            $post_fix = ".abs";
                            break;
                        case "melanom":
                            $this->m_xml_template_file = "onkeyline_1_0_mal.tpl";
                            $post_fix = ".mal";
                            break;
                    }
                    $data = $section->GetDaten();
                    $lieferant_id = $data[ 'lieferung' ][ 'lieferant_id' ];
                    $lieferung_id = $data[ 'lieferung' ][ 'lieferung_id' ];
                    $liefer_id = $data[ 'lieferung' ][ 'liefer_id' ];
                    $patient_id = $data[ 'patient_id' ];
                    $xml_file = $xml_dir . $lieferant_id . "_" . $patient_id . "_" . $liefer_id . $post_fix;
                    $files[] = $xml_file;
                    $data = $this->ReplaceAllXmlEntities( $data );
                    $this->m_internal_smarty->assign( $section->GetBlock(), $data );
                    $xml = utf8_encode( $this->m_internal_smarty->fetch( $this->m_xml_template_file ) );
                    file_put_contents( $xml_file, $xml );
                }
                $cases[ $case_key ] = $case;
            }
        }
        $this->m_export_record->SetNextTan( $this->m_export_record->GetNextTan() + 1 );
        $this->m_export_record->Write( $this->m_db );
        $this->m_export_record->WriteAllValidPatientIds( $this->m_db );
        $zip_filename = $lieferant_id . "_" . $lieferung_id . ".zip";
        $zip_file = $export_path . $zip_filename;
        $zip = new PclZip( $zip_file );
        $zip->create( $files, '', $xml_dir );
        $this->m_smarty->assign( 'zip_url', $zip_file );
        $this->m_smarty->assign( 'zip_filename', $zip_filename );

        // History erstellen
        $historyManager = CHistoryManager::getInstance();
        $historyManager->initialise($this->m_db, $this->m_smarty);
        $history = $historyManager->createHistory();
        $history->setExportLogId($this->m_export_record->GetDbId());
        $history->setExportName($this->m_export_record->GetExportName());
        $history->setOrgId($parameters['org_id']);
        $history->setUserId($parameters['user_id']);
        $history->setDate(date('Ymd', time()));
        $history->addFilter('Lieferant ID', $parameters['melder_id']);
        $history->setFiles($files);
        $historyManager->insertHistory($history);

        return $zip_file;
    }

    public function GetFilename()
    {
        return 'onkeyline_export_' . date( 'YmdHis' ) . '.xml';
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
        $melder[ 'lieferant_id' ] = '123456';
        $melder[ 'lieferung_id' ] = '789';
        $melder[ 'liefer_id' ]    = '12';
        $melder[ 'liefer_datum' ] = '25.12.1972';
        return $melder;
    }

    protected function CreateValidPatientBlock()
    {
        $patients = array();
        $patients[ 'lieferung' ][ 'lieferanten_id' ] = '234';
        $patients[ 'lieferung' ][ 'lieferung_id' ]   = '456';
        $patients[ 'lieferung' ][ 'liefer_datum' ]   = '25.12.1972';
        $patients[ 'lieferung' ][ 'liefer_id' ]      = '789';
        $patients[ 'patient_id' ]                    = '123456';
        $patients[ 'titel' ] = null;
        $patients[ 'geburtsname' ] = null;
        $patients[ 'geschlecht' ] = 'W';
        $patients[ 'strasse' ] = 'Holzweg';
        $patients[ 'hnr' ] = '21';
        $patients[ 'ort' ] = 'Giessen';
        $patients[ 'plz' ] = '35394';
        $patients[ 'ausland' ] = null;
        $patients[ 'kassenschluessel' ] = 'Psa789';
        $patients[ 'versichertengruppe' ] = 'M';
        $patients[ 'nachsorgepassnummer' ] = null;
        $patients[ 'bemerkungen' ] = null;
        return $patients;
    }

    protected function CreateValidFirstIntroductionBlock()
    {
        $firstIntroduction[ 'lieferung' ][ 'lieferanten_id' ] = '234';
        $firstIntroduction[ 'lieferung' ][ 'lieferung_id' ]   = '456';
        $firstIntroduction[ 'lieferung' ][ 'liefer_datum' ]   = '25.12.1972';
        $firstIntroduction[ 'lieferung' ][ 'liefer_id' ]      = '789';
        $firstIntroduction[ 'patient_id' ]                    = '123456';
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_text' ] = null;
        $firstIntroduction[ 'diagnose_datum' ] = '01.01.2013';
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;
        $firstIntroduction[ 'diagnose_icd' ] = null;


        return $firstIntroduction;


    }

    protected function CreateValidAbschlussBlock()
    {
        $abschluss = array();
        $abschluss[ 'lieferung' ][ 'lieferanten_id' ] = '234';
        $abschluss[ 'lieferung' ][ 'lieferung_id' ]   = '456';
        $abschluss[ 'lieferung' ][ 'liefer_datum' ]   = '25.12.1972';
        $abschluss[ 'lieferung' ][ 'liefer_id' ]      = '789';
        $abschluss[ 'patient_id' ]                    = '123456';
        $abschluss[ 'todesdatum' ]                    = null;
        $abschluss[ 'letzter_kontakt' ]               = null;
        $abschluss[ 'nicht_tumorbedingt' ]            = null;
        $abschluss[ 'tumorbedingt' ]                  = null;
        $abschluss[ 'letzte_patienteninformation' ] = '2012-03-16';
        return $abschluss;
    }

    /**
     *
     * @param $case
     * @return unknown_type
     */
    protected function CreateFileDataArray( $name, $daten, $ref_id )
    {
        $data = array();
        //$data[ 'patient_id' ] = $parameters[ '' ];
        $data[ $name ] = $daten;
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



    protected function CreatePatientDataArray()
    {
        $data = array();
        $data[ 'patients' ] = array();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
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
