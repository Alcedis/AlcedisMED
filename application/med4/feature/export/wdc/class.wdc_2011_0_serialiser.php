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

class Cwdc_2011_0_Serialiser extends CExportXmlSerialiser
{

    public function __construct()
    {
        $this->m_xml_template_file = "wdc_2011_0.tpl";
        $this->m_xml_schema_file = "feature/export/wdc/wdc_2011_0.xsd";
    }

    public function Validate( $parameters )
    {
        $data = array();
        // Alle neuen Cases im alten suchen...
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $case_key => $case ) {
            $sections = $case->GetSections();
            // Check
            foreach( $sections as $section_key => $section ) {
                $data = array();
                switch( $section->GetBlock() ) {
                    case 'melder' :
                        $data = $this->CreateMelderDataArray( $section );
                        break;
                    case 'patient' :
                        $data = $this->CreatePatientDataArray( $section );
                        break;
                    case 'fall' :
                        $data = $this->CreateFallDataArray( $section );
                        break;
                    case 'study' :
                    case 'lab' :
                    case 'conference' :
                    case 'anamnesis' :
                    case 'diagnose' :
                    case 'histology' :
                    case 'operations' :
                    case 'systemicTherapy' :
                    case 'radioTherapy' :
                    case 'afterCare' :
                    case 'abschluss' :
                        $data = $this->CreateDataArray( $section );
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
            $cases[ $case_key ] = $case;
        }
        $this->m_export_record->SetCases( $cases );
    }

    public function Encrypt( $parameters )
    {
    }

    public function Write( $parameters )
    {
        $xml_file = parent::Write( $parameters );
        $patient_counter = -1;
        $last_patient_id = 0;
        $fall_counter = -1;
        $current_fall_counter = 0;
        $data = null;
        $cases = $this->m_export_record->GetCases();
        foreach( $cases as $cases_key => $case ) {
            if ( $case->IsValid() ) {
                if ( null == $data ) {
                    // Header erstellen
                    $data = $this->GetSectionData( $case, 'melder' );
                }
                if ( $case->GetPatientId() != $last_patient_id ) {
                    $last_patient_id = $case->GetPatientId();
                    $patient_counter++;
                    $fall_counter = -1;
                    $current_fall_counter = 0;
                }
                if ( !isset( $data[ 'patients' ][ $patient_counter ][ 'patient_id' ] ) ) {
                    $data[ 'patients' ][ $patient_counter ] = $this->GetSectionData( $case, 'patient' );
                }
                if ( $fall_counter != $current_fall_counter ) {
                    $fall_counter++;
                    $merge = array_merge( $this->GetSectionData( $case, 'fall' ),
                        array(
                             'anamnesis'       => array(),
                             'study'           => array(),
                             'conference'      => array(),
                             'diagnose'        => array(),
                             'histology'       => array(),
                             'operations'      => array(),
                             'systemicTherapy' => array(),
                             'radioTherapy'    => array(),
                             'lab'             => array(),
                             'afterCare'       => array(),
                             'abschluss'       => array()
                        ) );


                    $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ] = $merge;
                    $current_fall_counter                                                = $fall_counter;
                }
                $sections = $case->GetSections();
                foreach( $sections as $key_section => $section ) {
                    if ( ( 'melder' != $section->GetBlock() ) &&
                        ( 'patient' != $section->GetBlock() ) ) {
                        $tmp = $section->GetDaten();
                        $tmp[ 'meldungskennzeichen' ] = 'N';
                        switch( $section->GetBlock() ) {
                            case 'anamnesis' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'anamnesis' ][]       = $tmp;
                                break;
                            case 'study' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'study' ][]           = $tmp;
                                break;
                            case 'conference' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'conference' ][]      = $tmp;
                                break;
                            case 'diagnose' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'diagnose' ][]        = $tmp;
                                break;
                            case 'histology' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'histology' ][]       = $tmp;
                                break;
                            case 'operations' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'operations' ][]      = $tmp;
                                break;
                            case 'systemicTherapy' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'systemicTherapy' ][] = $tmp;
                                break;
                            case 'radioTherapy' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'radioTherapy' ][]    = $tmp;
                                break;
                            case 'lab' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'lab' ][]             = $tmp;
                                break;
                            case 'afterCare' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'afterCare' ][]       = $tmp;
                                break;
                            case 'abschluss' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'abschluss' ][]       = $tmp;
                                break;
                            default :
                                break;
                        }
                        $section->SetDaten( $tmp );
                    }
                    $sections[ $key_section ] = $section;
                }
                $cases[ $cases_key ] = $case;
                $fall_counter++;
            }
        }
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
            $history->addFilter('von', $parameters['datum_von']);
            $history->addFilter('bis', $parameters['datum_bis']);
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
        return 'wdc_export_' . date( 'YmdHis' ) . '.xml';
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
     * @param $block_name
     * @return array
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


    /**
     *
     *
     * @access
     * @return array
     */
    protected function CreateValidMelderBlock()
    {
        $melder = array();
        $melder[ 'schema_version' ] = array(
            'typ'  => '105',
            'jahr' => '2011'
        );
        $melder[ 'zentrum_id' ]                = '999999';
        $melder[ 'datum_datensatzerstellung' ] = '2012-01-01';
        $melder[ 'zeitraum_beginn' ]           = '2012-01-01';
        $melder[ 'zeitraum_ende' ]             = '2012-01-01';
        $melder[ 'sw_hersteller' ]             = 'NORAlcedis GmbH';
        $melder[ 'sw_name' ]                   = 'NORAlcedis MED';
        $melder[ 'sw_version' ]                = '5.0.27';
        $melder[ 'technische_ansprechpartner' ] = array(
            'tech_ansprechpartner_name' => 'Ansprechpartner',
            'email'                     => 'dummymail@mail.de'
        );

        return $melder;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function CreateValidDiagnosisBlock ()
    {
        $diagnose = array();
        $diagnose[0][ 'tumor' ]                        = '3';
        $diagnose[0][ 'anocutanlinie' ]                = '7';
        $diagnose[0][ 'rezediv' ]                      = '1';
        $diagnose[0][ 'datum_diagnose' ]               = '2012-01-01';
        $diagnose[0][ 'icd_code' ]                     = 'C20';
        $diagnose[0][ 'icd_text' ]                     = 'Test Validierung';
        $diagnose[0][ 'icd_version' ]                  =  '405';
        $diagnose[0][ 't' ]                            =  '1';
        $diagnose[0][ 'n' ]                            =  '1c';
        $diagnose[0][ 'm' ]                            =  '';
        $diagnose[0][ 'y' ]                            =  '1';
        $diagnose[0][ 'g' ]                            =  '4';
        $diagnose[0][ 'tnm_version' ]                  =  '503';
        $diagnose[0][ 'ges_koloskopie' ]               =  '5';
        $diagnose[0][ 'tot_koloskopie' ]               =  '3';
        $diagnose[0][ 'ther_koloskopie' ]              =  '1';
        $diagnose[0][ 'ther_koloskopie_kompl' ]        =  '1';
        $diagnose[0][ 'unv_stenosierende_koloskopie' ] =  '1';
        $diagnose[0][ 'polyp_nachweis' ]               =  '3';
        $diagnose[0][ 'polypektomie' ]                 =  '1';
        $diagnose[0][ 'polyp_op_gebiet' ]              =  '1';
        $diagnose[0][ 'polypektomie_polyp' ]           =  '2';

        return $diagnose;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function CreateValidHistologyBlock ()
    {
        $histologie = array();
        $histologie[0][ 'msi' ]                                                     = '1';
        $histologie[0][ 'k_ras_wildtyp' ]                                           = '2';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'morpho_code' ]             = '8400/3';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'morpho_text' ]             = 'Test Validierung';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'topologie_code' ]          = 'C20.9';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'topologie_text' ]          = 'Rektum o.n.A.';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'histo_datum' ]             = '2012-02-04';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'icdo_version' ]            = '301';
        $histologie[0][ 'patho_histo_klassifikation' ][ 't' ]                       = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'n' ]                       = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'm' ]                       = '1';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'y' ]                       = '1';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'g' ]                       = '4';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'r' ]                       = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'l' ]                       = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'v' ]                       = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'pn' ]                      = '';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'tnm_version' ]             = '503';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'metastasen_ort' ][0][ 'ort' ] = 'OTH';
        $histologie[0][ 'patho_histo_klassifikation' ][ 'stadiengruppierung_uicc' ] = '3';
        return $histologie;
    }


    /**
     *
     *
     * @access
     * @param null $section
     * @return array
     */
    protected function CreateValidFallBlock( $section = null )
    {
        $fall = array();
        $fall[ 'fall_id' ]        = '101';
        $fall[ 'kostentraeger' ]  = 'DAK';
        $fall[ 'fall_beginn' ]    = '1900-01-01';
        $fall[ 'koerpergroesse' ] = '180';
        $fall[ 'koerpergewicht' ] = '80';
        $fall[ 'fall_ende']       = '1900-01-01';
        $fall[ 'diagnose' ]       = $this->CreateValidDiagnosisBlock();
        $fall[ 'histology' ]      = $this->CreateValidHistologyBlock();

        if ( !is_null( $section ) && ( 'diagnose' == $section->GetBlock()) ) {
            $fall[ 'diagnose' ] = array();
            $fall[ 'diagnose' ][] = $section->GetDaten();
        }
        if ( !is_null( $section ) && ( 'histology' == $section->GetBlock() ) ) {
            $fall[ 'histology' ] = array();
            $fall[ 'histology' ][] = $section->GetDaten();
        }

        $fall[ 'study' ] = array();
        if ( !is_null( $section ) && ( 'study' == $section->GetBlock() ) ) {
            $fall[ 'study' ][] = $section->GetDaten();
        }

        $fall[ 'conference' ] = array();
        if ( !is_null( $section ) && ( 'conference' == $section->GetBlock() ) ) {
            $fall[ 'conference' ][] = $section->GetDaten();
        }

        $fall[ 'anamnesis' ] = array();
        if ( !is_null( $section ) && ( 'anamnesis' == $section->GetBlock() ) ) {
            $fall[ 'anamnesis' ][] = $section->GetDaten();
        }
        $fall[ 'operations' ] = array();
        if ( !is_null( $section ) && ( 'operations' == $section->GetBlock() ) ) {
            $fall[ 'operations' ][] = $section->GetDaten();
        }

        $fall[ 'systemicTherapy' ] = array();
        if ( !is_null( $section ) && ( 'systemicTherapy' == $section->GetBlock() ) ) {
            $fall[ 'systemicTherapy' ][] = $section->GetDaten();
        }

        $fall[ 'radioTherapy' ] = array();
        if ( !is_null( $section ) && ( 'radioTherapy' == $section->GetBlock() ) ) {
            $fall[ 'radioTherapy' ][] = $section->GetDaten();
        }

        $fall[ 'lab' ] = array();
        if ( !is_null( $section ) && ( 'lab' == $section->GetBlock() ) ) {
            $fall[ 'lab' ][] = $section->GetDaten();
        }

        $fall[ 'afterCare' ] = array();
        if ( !is_null( $section ) && ( 'afterCare' == $section->GetBlock() ) ) {
            $fall[ 'afterCare' ][] = $section->GetDaten();
        }

        $fall[ 'abschluss' ] = array();
        if ( !is_null( $section ) && ( 'abschluss' == $section->GetBlock() ) ) {
            $fall[ 'abschluss' ][] = $section->GetDaten();
        }
        return $fall;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function CreateValidPatientBlock()
    {
        $patient = array();
        $patient[ 'patient_id' ]    = 'PATIENT0001';
        $patient[ 'geburtstag' ]    = '1948-03-17';
        $patient[ 'geschlecht' ]    = 'w';
        $patient[ 'todesdatum' ]    = '2012-01-01';
        $patient[ 'todesursache' ]  = '1';

        return $patient;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return mixed
     */
    protected function CreateMelderDataArray( $section )
    {
        $data = $section->GetDaten();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $this->CreateValidFallBlock()
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
    protected function CreateFallDataArray( $section )
    {
        $fall = $section->GetDaten();
        $fall[ 'diagnose' ]       = $this->CreateValidDiagnosisBlock();
        $fall[ 'histology' ]      = $this->CreateValidHistologyBlock();
        $data = $this->CreateValidMelderBlock();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array($fall);

        return $data;
    }


    /**
     *
     *
     * @access
     * @param $section
     * @return array
     */
    protected function CreatePatientDataArray( $section )
    {
        $data = $this->CreateValidMelderBlock();
        $data[ 'patients' ][ 0 ] = $section->GetDaten();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $this->CreateValidFallBlock()
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
    protected function CreateDataArray( $section )
    {
        $data = $this->CreateValidMelderBlock();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $this->CreateValidFallBlock( $section )
        );
        return $data;
    }
}
?>
