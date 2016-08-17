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

require_once( 'feature/export/base/class.exportdefaultview.php' );

class Conkeyline_1_0_View extends CExportDefaultView
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultView
    //

    public function BuildView($action)
    {
        if (isset($_REQUEST['melder_id']))
        {
            $this->m_smarty->assign('melder_id', $_REQUEST['melder_id']);
        }
        return parent::BuildView($action);
    }

    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load( '../feature/export/onkeyline/onkeyline_1_0.conf', 'export_onkeyline' );
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function GetTemplateFilename()
    {
        return "../feature/export/onkeyline/onkeyline_1_0_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $this->m_fields = array(
			'melder_id' => array( 'req' => 1, 'size' => 13, 'maxlen' => '13', 'type' => 'string', 'ext' => '' ),
        );
    }

    public function CreateErrorListViewFields()
    {
        $patients = array();
        $error = array();
        $this->m_fields = array();
        $export_record = $this->m_model->GetData();
        //$valid_cases_count = $export_record->GetValidCasesCount();
        //$invalid_cases_count = $export_record->GetInvalidCasesCount();

        $invalid_cases_count = 0;
        $vaild_patient_ids = $export_record->GetAllValidPatientIds( $invalid_cases_count );
        $valid_cases_count = count( $vaild_patient_ids );

        $invalid_sections = $export_record->GetAllInvalidSections();
        if ( count( $invalid_sections ) > 0 ) {
            foreach( $invalid_sections as $item ) {
                if ( !isset( $patients[ $item[ 'patient_id' ] ] ) ) {
                    $patients[ $item[ 'patient_id' ] ] = HDatabase::GetPatientData( $this->m_db, $item[ 'patient_id' ] );
                }
                $patient = $patients[ $item[ 'patient_id' ] ];
                $item[ 'export_nr' ] = $export_record->GetExportNr();
                $item[ 'nachname' ] = $patient[ 'nachname' ];
                $item[ 'vorname' ] = $patient[ 'vorname' ];
                $item[ 'geschlecht' ] = $patient[ 'geschlecht' ];
                $item[ 'geburtsdatum' ] = date( "d.m.Y", strtotime( $patient[ 'geburtsdatum' ] ) );
                if ( !isset( $erkrankungen[ $item[ 'erkrankung_id' ] ] ) ) {
                    $erkrankungen[ $item[ 'erkrankung_id' ] ] = HDatabase::GetErkrankungData( $this->m_db, $item[ 'erkrankung_id' ] );
                }
                $erkrankung = $erkrankungen[ $item[ 'erkrankung_id' ] ];
                $item[ 'erkrankung' ] = $erkrankung[ 'erkrankung_bez' ];
                $item[ 'createtime' ] = date( "d.m.Y H:m:s", strtotime( $item[ 'createtime' ] ) );
                $error[] = $item;
            }
        }
        else if ( 0 == $valid_cases_count ) {
            // Es liegen keine Daten zum Export vor, also aktuellen Export automatisch löschen
            $this->m_model->DeleteData();
        }
        if ( $valid_cases_count == 1 ) {
            $info_patienten_valid = $this->m_configs[ 'info_patient_valid' ];
        }
        else {
            $info_patienten_valid = $this->m_configs[ 'info_patienten_valid' ];
        }
        $info_patienten_valid = str_replace( "#anzahl#", "" . $valid_cases_count, $info_patienten_valid );

        if ( $invalid_cases_count == 1 ) {
            $info_patienten_invalid = $this->m_configs[ 'info_patient_invalid' ];
        }
        else {
            $info_patienten_invalid = $this->m_configs[ 'info_patienten_invalid' ];
        }
        $info_patienten_invalid = str_replace( "#anzahl#", "" . $invalid_cases_count, $info_patienten_invalid );
        $this->m_smarty->assign( 'info_patienten_valid', $info_patienten_valid );
        $this->m_smarty->assign( 'info_patienten_invalid', $info_patienten_invalid );
        $this->m_smarty->assign( 'invalid_cases', $invalid_cases_count );
        $this->m_smarty->assign( 'valid_cases', $valid_cases_count );
        $this->m_smarty->assign( 'errorlist_data', $error );
    }

}

?>
