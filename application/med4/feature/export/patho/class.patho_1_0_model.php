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

require_once( DIR_LIB . '/alcedis/excel/excelgen_pear.class.php' );
require_once( 'feature/export/base/class.exportdefaultmodel.php' );

class Cpatho_1_0_Model extends CExportDefaultModel
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultModel
    //

    public function ExtractData( $parameters, $wrapper, &$export_record )
    {
        $query = "
            SELECT
               *

            FROM
               patho_item pi

            WHERE
               pi.date BETWEEN '{$parameters[ 'von_datum' ]}' AND '{$parameters[ 'bis_datum' ]}'
        ";
        $result = sql_query_array( $this->m_db, $query );
        $renderer = new ExcelGenPear;
        $bold = $renderer->workbook->addFormat();
        $bold->setBold();
        $row = 0;
        $col = 0;
        // Titel
        $renderer->WriteText( $row, 0, "Pathologie-Export", $bold );
        $row += 2;
        // Erstellungsdatum
        $renderer->WriteText( $row, 0, concat( array( "Erstellungsdatum", date( 'd.m.Y H:i:s' ) ), ' ' ) );
        $row += 2;
        // Datum Von bis...
        $parameters[ 'von_datum' ] = convertDate( $parameters[ 'von_datum' ] );
        $parameters[ 'bis_datum' ] = convertDate( $parameters[ 'bis_datum' ] );
        $datumsbereich = array( "Datumsbereich" );
        $datumsbereich[] = "von Datum";
        $datumsbereich[] = $parameters[ 'von_datum' ];
        $datumsbereich[] = "bis Datum";
        $datumsbereich[] = $parameters[ 'bis_datum' ];
        $renderer->WriteText( $row, 0, implode( ' ', $datumsbereich ) );
        $row += 2;
        if ( count( $result ) > 0 ) {
            $data = array();
            foreach( $result as $record ) {
                $tmp = unserialize( base64_decode( $record[ 'data' ] ) );
                $tmp2 = array();
                $tmp2[ 'Patient Nr.' ] = $tmp[ 'patient_nr_int' ];
                $tmp2[ 'Nachname' ] = $tmp[ 'nachname' ];
                $tmp2[ 'Vorname' ] = $tmp[ 'vorname' ];
                $tmp2[ 'Geburtsdatum' ] = $tmp[ 'geburtsdatum' ];
                $tmp2[ 'Histologie Nr.' ] = $tmp[ 'histologie_nr' ];
                $tmp2[ 'Histologiedatum' ] = $tmp[ 'datum' ];
                $tmp2[ 'Diagnose' ] = $tmp[ 'diagnose' ];
                $tmp2[ 'Histologie' ] = $tmp[ 'histologie' ];
                $tmp2[ 'pT' ] = $tmp[ 'pt' ];
                $tmp2[ 'pN' ] = $tmp[ 'pn' ];
                $tmp2[ 'pM' ] = $tmp[ 'pm' ];
                $tmp2[ 'Grading' ] = $tmp[ 'grading' ];
                $tmp2[ 'op_res_stadium' ] = $tmp[ 'op_res_stadium' ];
                $tmp2[ 'invasionlymph' ] = $tmp[ 'invasionlymph' ];
                $tmp2[ 'invasionvene' ] = $tmp[ 'invasionvene' ];
                $data[] = $tmp2;
            }
            // Spaltenüberschriften
            foreach( array_keys( reset( $data ) ) as $head ) {
                $renderer->worksheet->setColumn( $col, $col, 20 );
                $renderer->WriteText( $row, $col++, ( isset( $this->_config[ $head ] ) ? $this->_config[ $head ] : $head ), $bold );
            }
            $row++;
            // Daten
            foreach( $data as $record ) {
                $col = 0;
                foreach( $record as $value ) {
                    if( preg_match( "/^[0-2][0-9]{3}-[0-1][0-9]-[0-3][0-9]$/", $value ) ) {
                        $renderer->WriteDate( $row, $col++, $value );
                    }
                    elseif( is_numeric( $value ) ) {
                        $renderer->WriteNumber( $row, $col++, $value );
                    }
                    else {
                        $renderer->WriteText( $row, $col++, $value );
                    }
                }
                $row++;
            }
        }
        $renderer->SendFile();
    }

    public function PreparingData( $parameters, &$export_record )
    {
    }

    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
    }

    public function CheckData( $parameters, &$export_record )
    {
    }

    public function WriteData()
    {
    }

    //*********************************************************************************************
    //
    // Helper functions
    //



}

?>
