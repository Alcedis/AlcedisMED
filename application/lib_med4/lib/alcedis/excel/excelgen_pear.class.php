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

set_include_path( get_include_path() . PATH_SEPARATOR . DIR_LIB . '/pear' );

require_once 'Spreadsheet/Excel/Writer.php';

class ExcelGenPear
{
   var $workbook;
   var $worksheet;
   var $excel_filename;

	/**
	 * Setzt das Datumsformat.
	 *
	 * @access public
	 * @var string $date_format
	 */
   var $date_format = 'DD.MM.YYYY';

   /**
    * Konstruktor
    *
    * @access public
    * @param string $excel_filename
    * @param string $excel_wksheetname
    * @return void
    */
   function ExcelGenPear( $excel_filename = 'excelgen', $excel_wksheetname = 'alcedis' )
   {
      $this->excel_filename = $excel_filename;
      $this->workbook       = new Spreadsheet_Excel_Writer;
      $this->worksheet      = &$this->workbook->addWorksheet( $excel_wksheetname );
   }

   /**
    * Schlie�t die Excel-Datei
    *
    * @access public
    * @param void
    * @return void
    */
   function ExcelEnd($returnOutput = false)
   {
     if ($returnOutput === true) {
        return $this->workbook->close($returnOutput);
     } else {
       $this->workbook->close($returnOutput);
     }

   }

   /**
    * Schreibt eine Zahl.
    *
    * @access public
    * @param int $row
    * @param int $col
    * @param int $value
    * @param object $format
    * @return void
    */
   function WriteNumber( $row, $col, $value, $format = 0 )
   {
      if( !strlen( $value ) )
         return false;

      return $this->worksheet->writeNumber( $row, $col, $value, $format );
   }

   /**
    * Schreibt ein Datum.
    *
    * @access public
    * @param int $row
    * @param int $col
    * @param int $value Unix-Timestemp
    * @param string $date_format
    * @return void
    */
   function WriteDate( $row, $col, $value, $date_format = '' )
   {
      if( !strlen( $value ) )
         return false;

      if( !strlen( $date_format ) )
         $date_format = $this->date_format;

      if( !isset($this->format_date) )
      {
         $this->format_date = &$this->workbook->addFormat();
         $this->format_date->setNumFormat( $date_format );
      }

      return $this->Write( $row, $col, $this->unix2excel( $value ), $this->format_date );
   }

   /**
    * Schreibt einen Text.
    *
    * @access public
    * @param int $row
    * @param int $col
    * @param string $value
    * @param object $format
    * @return void
    */
   function WriteText( $row, $col, $value, $format = 0 )
   {
      if( !strlen( $value ) )
         return false;

      $first_sign = isset( $value[0] ) ? $value[0] : '';

      // Wenn Datum
      if( preg_match( '/^(\d{4})\-(\d{2})-(\d{2})$/', $value, $match ) )
         return $this->WriteDate( $row, $col, $value );
      // Wenn Zahl aber keine Null am Anfang
      elseif( is_numeric( $value ) AND ( ( $first_sign != '0' OR substr_count( $value, '.' ) ) OR $value == 0 ) )
         return $this->worksheet->writeNumber( $row, $col, $value, $format );
      else
         return $this->worksheet->writeString( $row, $col, $this->html2text( $value ), $format );
   }

   /**
    * Schreibt ein Wert. Erkennt automatisch den Typ.
    *
    * @access public
    * @param int $row
    * @param int $col
    * @param mixed $value
    * @param object $format
    * @return void
    */
   function Write( $row, $col, $value, $format = 0 )
   {
      if( !strlen( $value ) )
         return false;

      return $this->worksheet->write( $row, $col, $value, $format );
   }

   /**
    * Sendet die Datei.
    *
    * @access public
    * @param void
    * @return void
    */
   function SendFile( $filename = '', $returnOutput = false )
   {
      if( strlen( $filename ) )
         if ($returnOutput === true) {
            return $this->ExcelEnd($returnOutput);
         } else {
             $this->ExcelEnd();
         }
      else
      {
         $this->workbook->send( $this->excel_filename );
         $this->ExcelEnd();
      }
   }

   /**
    * Rechnet ein englisches Datum in Excel-Timestamp um.
    *
    * @access public
    * @param string $datetime
    * @return int
    */
   function unix2excel( $datetime )
   {
      $tmp  = explode( ' ', $datetime );
      $date = explode( '-', $tmp[0]   );
      $frac = 0;

      // Datum
      $date1 = GregorianToJD( $date[1], $date[2], $date[0] );
      $epoch = 2415021; // GregorianToJD( 1, 1, 1900 );

      // Zeit
      if( isset( $tmp[1] ) )
      {
         $time = explode( ':', $tmp[1] );
         $frac = ( ( $time[0] * 3600 ) + ( $time[1] * 60 ) + $time[2] ) / 86400;
      }

      return $date1 - $epoch + 2 + $frac;
   }

   /**
    * Entfernt HTML-Tags.
    *
    * @access public
    * @param string $string
    * @return string $string
    */
   function html2text( $string )
   {
      $html_encode = get_html_translation_table( HTML_ENTITIES );
      $html_decode = array();

      foreach( $html_encode as $k => $v )
         $html_decode[$v] = $k;

      $string = strtr( $string, $html_decode );

      // BUGFIX GKO, DWI, CRE 2008-08-20
      // strip_tags macht hier Probleme, wenn in Freitextfeldern
      // Gr��er- bzw. Kleinerzeichen eingegeben werden.
      // Einen Grund wof�r es gut sein soll konnte nicht ermittelt werden!?!
      #return strip_tags( $string );
      return $string;
   }
}

?>
