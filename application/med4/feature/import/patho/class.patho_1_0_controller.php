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

require_once( 'feature/import/base/class.importdefaultcontroller.php' );
require_once( 'feature/import/base/helper.filesystem.php' );

class Cpatho_1_0_Controller extends CImportDefaultController
{

    protected $m_hl7_fields = array();
    protected $m_log_time = null;
    protected $m_import_path = "";

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CImportDefaultController
    //

    public function GetImportName()
    {
        return 'patho';
    }

    protected function DoImport()
    {
        $this->m_hl7_fields = $this->GetHl7Fields();
        $this->m_log_time = date( 'Y-m-d_H-i-s' );
        $this->m_import_path = $this->m_parameters[ 'source_dir' ];
        $this->m_import_path = HFileSystem::CeckPath( $this->m_import_path );
        $filenames = HFileSystem::GetFilenames( $this->m_import_path, true );
        $messages = array();
        foreach( $filenames as $file ) {
            $messages[] = $this->GetFileData( $this->m_import_path . $file );
        }
        $data = $this->GetData( $messages );
        foreach( $data as $row ) {
            $this->WriteItem( $row );
        }
        foreach( $filenames as $file ) {
            $f = $this->m_import_path . $file;
            if ( file_exists( $f ) ) {
                unlink( $f );
            }
        }
    }

    protected function GetFileData( $file )
    {
        $message_strings = file( $file );
        $messages = array();
        if ( is_array( $message_strings ) &&
             count( $message_strings ) > 0 ) {
            foreach( $message_strings as $i => $cur_message_string )
            {
                $messages[ $i ] = trim( preg_replace( "/'/", '_', $cur_message_string ) );
            }
        }
        return implode( '###', $messages );
    }

    protected function GetData( $messages )
    {
        $patterns = array(
          'diagnose'        => '/.*([CD][0-9]{2}\.{0,1}[0-9]{0,1}[LR]{0,1}).*/',
          'histologie'      => '/.*ICD-O.*:.*M[\w\s]{0,1}([0-9]{4}\/[0-9]).*/',
//          'histologie_nr'   => '/.*###OBR\|{1}1\|([0-9]*)\|{1}.*/',
//          'patient_nr_int'  => '/.*###PID\|{1}[\w\s]*\|{1}[\w\s]*\|{1}([0-9]+)\|{1}.*/',
//          'datum'           => '/.*###OBR\|{1}1\|{1}[\w\s\^]*\|{1}[\w\s\^]*\|{1}[\w\s\^]*\|{1}[\w\s\^]*\|{1}[\w\s\^]*\|{1}[\w\s\^]*\|{1}([0-9]{8})[0-9]*\|{1}.*/',
          'pt'              => '/.*UICC.*p{0,1}T([01234X][a-o,q-z]*)\s*/',
          'pn'              => '/.*UICC.*p{0,1}N([01234X][a-o,q-z]*)\s*/',
          'pm'              => '/.*UICC.*p{0,1}M([01234X][a-o,q-z]*)\s*/',
          'grading'         => '/.*ICD-O.*G{1} {0,1}([01234X][a-o,q-z]*)\s*/',
          'op_res_stadium'  => '/.*UICC.*R([01234X][a-o,q-z]*)\s*/',
          'invasionlymph'   => '/.*UICC.*L([01234X][a-o,q-z]*)\s*/',
          'invasionvene'    => '/.*UICC.*V([01234X][a-o,q-z]*)\s*/'
        );
        $data = array();
        foreach( $messages as $raw_text) {
            $tmp = array();
            foreach( $patterns as $param => $pattern ) {
                $matches = array();
                preg_match( $pattern, $raw_text, $matches );
                if ( isset( $matches[ 1 ] ) ) {
                    $tmp[ $param ] = $matches[ 1 ];
                }
                else {
                    $tmp[ $param ] = "";
                }
            }
            $messages = explode( "###", $raw_text );
            $tmp[ 'histologie_nr' ] = $this->GetHistologieNr( $messages );
            $tmp[ 'patient_nr_int' ] = $this->GetPatientNr( $messages );
            $tmp[ 'datum' ] = $this->GetDatum( $messages );
            if ( strlen( $tmp[ 'datum' ] ) > 0 ) {
                $tmp[ 'datum' ] = date( "d.m.Y", strtotime( $tmp[ 'datum' ] ) );
            }
            // Bedingung: patient_nr_int muss vorhanden sein
            $d = strtotime( $tmp[ 'datum' ] );
            if ( ( isset( $tmp[ 'patient_nr_int' ] ) && ( strlen( $tmp[ 'patient_nr_int' ] ) > 0 ) ) &&
                 ( isset( $tmp[ 'datum' ] ) && ( strlen( $tmp[ 'datum' ] ) > 0 ) ) &&
                 ( isset( $tmp[ 'histologie_nr' ] ) && ( strlen( $tmp[ 'histologie_nr' ] ) > 0 ) ) ) {
                $patient = $this->GetPatient( $tmp[ 'patient_nr_int' ] );
                if ( $patient !== false ) {
                    foreach( $patient as $key => $value ) {
                        $tmp[ $key ] = $value;
                    }
                    $data[] = $tmp;
                }
            }
        }
        return $data;
    }

    protected function GetPatient( $patient_nr_int )
    {
        $query = "
            SELECT
               p.nachname,
               p.vorname,
               p.geburtsdatum

            FROM
               patient p

            WHERE
               p.patient_nr='{$patient_nr_int}'
        ";
        $result = end( sql_query_array( $this->m_db, $query ) );
        return $result;
    }

    protected function GetHl7Fields()
    {
        $query = "
            SELECT
               *

            FROM
               settings_hl7field
        ";
        $result = sql_query_array( $this->m_db, $query );
        $data = array();
        if ( $result !== false ) {
            foreach( $result as $row ) {
                $data[ $row[ 'med_feld' ] ] = $row;
            }
        }
        return $data;
    }

    protected function GetHistologieNr( $messages )
    {
        foreach( $messages as $line ) {
            if ( strtoupper( substr( $line, 0, 3 ) ) == "OBR" ) {
                $arr = explode( "|", $line );
                return $arr[ 2 ];
            }
        }
        return "";
    }

    protected function GetPatientNr( $messages )
    {
        $pid = explode( ".", $this->m_hl7_fields[ 'patient.patient_nr' ][ 'hl7' ] );
        foreach( $messages as $line ) {
            if ( strtoupper( substr( $line, 0, 3 ) ) == $pid[ 0 ] ) {
                $arr = explode( "|", $line );
                return $arr[ $pid[ 1 ] ];
            }
        }
        return "";
    }

    protected function GetDatum( $messages )
    {
        foreach( $messages as $line ) {
            if ( strtoupper( substr( $line, 0, 3 ) ) == "OBR" ) {
                $arr = explode( "|", $line );
                return $arr[ 8 ];
            }
        }
        return "";
    }

    protected function WriteItem( $row )
    {
        $data = base64_encode( serialize( $row ) );
        $date = strftime( "%F", strtotime( $row[ 'datum' ] ) );
        $query = "
            INSERT INTO patho_item (
                data,
                date )
            VALUES (
                '{$data}',
                '{$date}'
            )
        ";
        @mysql_query( $query, $this->m_db );
    }

}

?>
