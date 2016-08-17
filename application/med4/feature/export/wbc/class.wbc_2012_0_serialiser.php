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

class Cwbc_2012_0_Serialiser extends CExportXmlSerialiser
{

    /**
     *
     */
    public function __construct()
    {
        $this->m_xml_template_file = "wbc_2012_0.tpl";
        $this->m_xml_schema_file = "feature/export/wbc/wbc_2012_0.xsd";
    }

    //*****************************************************************************************************************
    //
    // Overrides from class CExportXmlSerialiser
    //

    /**
     * @override
     */
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
                    case 'fall' :
                        $data = $this->CreateFallDataArray( $section );
                        break;
                    case 'patient' :
                        $data = $this->CreatePatientDataArray( $section );
                        break;
                    case 'aufenthalt' :
                    case 'studie' :
                    case 'tumorkonferenz' :
                    case 'diagnose' :
                    case 'therapie' :
                    case 'histologie' :
                    case 'labor' :
                        $data = $this->CreateDataArray( $section );
                        break;
                    case 'nachsorge' :
                        $data = $this->CreateNachsorgeDataArray( $section );
                        break;
                    default :
                        break;
                }
                $data = $this->ReplaceAllXmlEntities( $data );
                $this->m_internal_smarty->assign( 'data',
                                                  $data );
                $xml = $this->m_internal_smarty->fetch( $this->m_xml_template_file );
                $errors = $this->ParseXmlForErrors( $xml );
                if ( false !== $errors ) {
                    // Es sind "Undefined Index: xxx" Fehler aufgetreten
                    $case->SetSectionErrorsByUid( $section->GetSectionUid(),
                                                  $errors );
                }
                else {
                    $errors = $this->XmlSchemaValidate( $xml,
                                                        $this->m_xml_schema_file );
                    if ( count( $errors ) > 0 ) {
                        $case->SetSectionErrorsByUid( $section->GetSectionUid(),
                                                      $errors );
                    }
                }
            }
            $cases[ $case_key ] = $case;
        }
        $this->m_export_record->SetCases( $cases );
    }


    /**
     * @override
     */
    public function Encrypt( $parameters )
    {
    }


    /**
     * @override
     */
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
                    $data[ 'patients' ][ $patient_counter ][ 'nachsorgen' ] = array();
                }
                if ( $fall_counter != $current_fall_counter ) {
                    $fall_counter++;
                    $merge = array_merge( $this->GetSectionData( $case, 'fall' ),
                        array(
                             'aufenthalte_stationaer' => array(),
                             'studien' => array(),
                             'tumorkonferenzen' => array(),
                             'diagnosen' => array(),
                             'therapien' => array(),
                             'patho_histologien' => array(),
                             'labore' => array()
                        ) );
                    $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ] = $merge;
                    $current_fall_counter = $fall_counter;
                }
                $sections = $case->GetSections();
                foreach( $sections as $key_section => $section ) {
                    if ( ( 'melder' != $section->GetBlock() ) &&
                         ( 'patient' != $section->GetBlock() ) ) {
                        $tmp = $section->GetDaten();
                        $tmp[ 'meldungskennzeichen' ] = 'N';
                        switch( $section->GetBlock() ) {
                            case 'aufenthalt' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'aufenthalte_stationaer' ][] = $tmp;
                                break;
                            case 'studie' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'studien' ][] = $tmp;
                                break;
                            case 'tumorkonferenz' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'tumorkonferenzen' ][] = $tmp;
                                break;
                            case 'diagnose' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'diagnosen' ][] = $tmp;
                                break;
                            case 'therapie' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'therapien' ][] = $tmp;
                                break;
                            case 'histologie' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'patho_histologien' ][] = $tmp;
                                break;
                            case 'labor' :
                                $data[ 'patients' ][ $patient_counter ][ 'faelle' ][ $fall_counter ][ 'labore' ][] = $tmp;
                                break;
                            case 'nachsorge' :
                                if ( !$this->HasNachsorge( $data[ 'patients' ][ $patient_counter ][ 'nachsorgen' ],
                                                           $tmp ) ) {
                                    $data[ 'patients' ][ $patient_counter ][ 'nachsorgen' ][] = $tmp;
                                }
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
            $this->m_internal_smarty->assign( 'data',
                                              $data );
            $xml = $this->m_internal_smarty->fetch( $this->m_xml_template_file );
            file_put_contents( $xml_file,
                               utf8_encode( $xml ) );
            $this->m_smarty->assign( 'zip_url',
                                     $xml_file );
            $this->m_smarty->assign( 'zip_filename',
                                     'Export Datei' );

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


    /**
     * @override
     */
    public function GetFilename()
    {
        return 'wbc_export_' . date( 'YmdHis' ) . '.xml';
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
     * @return bool
     */
    protected function GetSectionData( $case, $block_name )
    {
        foreach( $case->GetSections() as $section ) {
            if ( $block_name == $section->GetBlock() ) {
                return $section->GetDaten();
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function CreateValideMelderBlock()
    {
        $melder = array();
        $melder[ 'schema_version' ] = array(
            'typ'  => '104',
            'jahr' => '2012'
        );
        $melder[ 'zentrum_id' ] = '999999';
        $melder[ 'datum_datensatzerstellung' ] = '2012-01-01';
        $melder[ 'zeitraum_beginn' ] = '2012-01-01';
        $melder[ 'zeitraum_ende' ] = '2012-01-01';
        $melder[ 'sw' ] = array(
            'sw_hersteller' => 'Alcedis GmbH',
            'sw_name'       => 'Alcedis MED',
            'sw_version'    => '4.0.27'
        );
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
    protected function CreateValidPatientBlock()
    {
        $patient = array();
        $patient[ 'patient_id' ] = 'CHECK0001';
        $patient[ 'pat_daten' ] = array(
            'geburtstag'   => '1948-03-17',
            'geschlecht'   => 'w',
            'verstorben'   => array(
                'todesdatum'   => '2012-01-01',
                'todesursache' => 1
            )
        );
        $patient[ 'nachsorgen' ] = array();
        return $patient;
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
        $fall[ 'fall_id' ] = '1356';
        $fall[ 'kostentraeger' ] = 'AOK Giessen';
        if ( !is_null( $section ) &&
             'menopause' == $section->GetBlock() ) {
            $fall[ 'menopause' ] = $section->GetDaten();
        }
        $fall[ 'koerpergroesse' ] = 184;
        $fall[ 'koerpergewicht' ] = 74;
        $fall[ 'seite' ] = '1';
        $fall[ 'fall_beginn' ] = '2012-01-01';
        $fall[ 'aufenthalte_stationaer' ] = array();
        if ( !is_null( $section ) &&
            ( 'aufenthalt' == $section->GetBlock() ) ) {
            $fall[ 'aufenthalte_stationaer' ][] = $section->GetDaten();
        }
        $fall[ 'studien' ] = array();
        if ( !is_null( $section ) &&
            ( 'studie' == $section->GetBlock() ) ) {
            $fall[ 'studien' ][] = $section->GetDaten();
        }
        $fall[ 'tumorkonferenzen' ] = array();
        if ( !is_null( $section ) &&
            ( 'tumorkonferenz' == $section->GetBlock() ) ) {
            $fall[ 'tumorkonferenzen' ][] = $section->GetDaten();
        }
        $fall[ 'diagnosen' ] = array();
        if ( !is_null( $section ) &&
            ( 'diagnose' == $section->GetBlock() ) ) {
            $fall[ 'diagnosen' ][] = $section->GetDaten();
        }
        $fall[ 'therapien' ] = array();
        if ( !is_null( $section ) &&
            ( 'therapie' == $section->GetBlock() ) ) {
            $fall[ 'therapien' ][] = $section->GetDaten();
        }
        $fall[ 'patho_histologien' ] = array();
        if ( !is_null( $section ) &&
            ( 'histologie' == $section->GetBlock() ) ) {
            $fall[ 'patho_histologien' ][] = $section->GetDaten();
        }
        $fall[ 'labore' ] = array();
        if ( !is_null( $section ) &&
            ( 'labor' == $section->GetBlock() ) ) {
            $fall[ 'labore' ][] = $section->GetDaten();
        }
        return $fall;
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
        $data = $this->CreateValideMelderBlock();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $section->GetDaten()
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
    protected function CreatePatientDataArray( $section )
    {
        $data = $this->CreateValideMelderBlock();
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
        $data = $this->CreateValideMelderBlock();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $this->CreateValidFallBlock( $section )
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
    protected function CreateNachsorgeDataArray( $section )
    {
        $data = $this->CreateValideMelderBlock();
        $data[ 'patients' ][ 0 ] = $this->CreateValidPatientBlock();
        $data[ 'patients' ][ 0 ][ 'faelle' ] = array(
            $this->CreateValidFallBlock()
        );
        $data[ 'patients' ][ 0 ][ 'nachsorgen' ] = array(
            $section->GetDaten()
        );
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgen
     * @param $nachsorge
     * @return bool
     */
    protected function HasNachsorge( $nachsorgen,
                                     $nachsorge )
    {
        foreach( $nachsorgen as $item ) {
            if ( $item[ 'nachsorge_datum' ] == $nachsorge[ 'nachsorge_datum' ] ) {
                return true;
            }
        }
        return false;
    }

}

?>
