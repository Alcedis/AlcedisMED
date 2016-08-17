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

require_once( 'interface.medexport.php' );
require_once( 'class.medexportexception.php' );

abstract class CMedBaseExport implements IMedExport
{

   protected $_smarty;
   protected $_db;
   protected $_config;
   protected $_internal_smarty;
   protected $_total_errors = 0;
   protected $_export_path = "";
   protected $_log_dir = "";
   protected $_tmp_dir = "";
   protected $_xml_dir = "";
   protected $_zip_dir = "";
   protected $_schema_file = "";
   protected $_xml_template = "";

   // Implementierungen

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/IMedExport#Create()
    */
   public function Create( $smarty, $db )
   {
      $this->_smarty = $smarty;
      $this->_db = $db;
      $this->_internal_smarty = new Smarty();
      $this->_internal_smarty->template_dir = $this->_smarty->template_dir;
      $this->_internal_smarty->compile_dir = $this->_smarty->compile_dir;
      $this->_internal_smarty->config_dir = $this->_smarty->config_dir;
      $this->_internal_smarty->cache_dir = $this->_smarty->cache_dir;
      $this->_internal_smarty->plugins_dir = $this->_smarty->plugins_dir;
      $this->_internal_smarty->force_compile = true;
      $this->_internal_smarty->caching = 0;
      $this->_internal_smarty->debugging = true;
      $this->_internal_smarty->error_reporting = E_ALL & ~E_NOTICE & ~'E_WARN';
      $this->_config = array();
      $this->Init();
   }

   /**
    *
    */
   protected function Init()
   {
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/IMedExport#Export()
    */
   public function Export( $session, $request )
   {
      $export_filter = $this->CreateExportFilter( $session, $request );
      $content = $this->CreateContent( $export_filter );
      if ( count( $content ) > 0 ) {
         $checked_content = $this->CheckContent( $content, $export_filter );
         $filename = $this->CreateExportFilename( $export_filter );
         return $this->WriteContent( $checked_content, $filename, $export_filter );
      }
      return false;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/IMedExport#GetVersion()
    */
   public function GetVersion()
   {
      return "1.0";
   }

   /**
    *
    * @param array $session
    * @param array $request
    * @return array Filter
    */
   protected function CreateExportFilter( $session, $request )
   {
      $export_filter = array();
      return $export_filter;
   }

   /**
    *
    * @param array $export_filter
    * @return string Filename
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = "export_file.xml";
      return $filename;
   }

   /**
    *
    * @param array $export_filter
    * @return string $content
    */
   protected function CreateContent( $export_filter )
   {
      $content = $this->ExtractData( $export_filter );
      return $content;
   }

   /**
    *
    * @param array $content
    * @param array $export_filter
    * @return array $result
    */
   protected function CheckContent( $content, $export_filter )
   {
      $result = array( 'valid' => array(),
                       'invalid' => array() );

      return $result;
   }

   /**
    *
    * @param array $content
    * @param string $filename
    * @param array $export_filter
    * @return boolean War erfogreich, Ja oder Nein
    */
   protected function WriteContent( $content, $filename, $export_filter )
   {
      return true;
   }

  /**
    *
    * @param array $export_filter
    * @return array $data
    */
   protected function ExtractData( $export_filter )
   {
      $data = array();

      return $data;
   }

   /**
    * XML-Validierung
    *
    * @param string $xml_file
    * @param string $xml_schema
    * @return string Fehlerzeilen
    */
   protected function XmlSchemaValidate( $xml_string, $xml_schema )
   {
      $errors = array();
      if ( !is_file( $xml_schema ) ) {
         throw new EMedExportException( 'XML-Schema [' . $xml_schema . '] nicht gefunden.' );
      }
      libxml_use_internal_errors( true );
      $xml = new DOMDocument();
      $xml->loadXML( $xml_string );
      $xml->schemaValidate( $xml_schema );
      return $this->XmlSchemaValidateErrors();
   }

   /**
    *
    * @param $xml_file
    * @param $xml_schema
    * @return unknown_type
    */
   protected function XmlFileSchemaValidate( $xml_file, $xml_schema )
   {
      $errors = array();
      if ( !is_file( $xml_file ) ) {
         throw new EMedExportException( 'XML-File [' . $xml_file . '] nicht gefunden.' );
      }
      if ( !is_file( $xml_schema ) ) {
         throw new EMedExportException( 'XML-Schema [' . $xml_schema . '] nicht gefunden.' );
      }
      libxml_use_internal_errors( true );
      $xml = new DOMDocument();
      $xml->load( $xml_file );
      $xml->schemaValidate( $xml_schema );
      return $this->XmlSchemaValidateErrors();
   }

   /**
    *
    * @return string Fehlerzeilen
    */
   protected function XmlSchemaValidateErrors()
   {
      $libxml_errors = libxml_get_errors();
      $return = array();
      foreach( $libxml_errors AS $error ) {
         $line = "\n";
         switch( $error->level ) {
             case LIBXML_ERR_WARNING:
                 $line .= 'Warning ' . $error->code. ': ';
                 break;
             case LIBXML_ERR_ERROR:
                 $line .= 'Error ' . $error->code. ': ';
                 break;
             case LIBXML_ERR_FATAL:
                 $line .= 'Fatal Error ' . $error->code. ': ';
                 break;
         }
         $line .= trim($error->message);
         if ( strlen( $error->file ) > 0 ) {
             $line .= ' in ' . basename( $error->file );
         }
         $line .= ' in Line: ' . $error->line;
         $return[] = $line;
      }
      $this->_total_errors += count( $return );
      libxml_clear_errors();
      return $return;
   }

   /**
    * Schreibt Datensätze $data die durch die Felder $fields beschrieben werden in eine Datei mit dem Namen $filename.
    *
    * @param string $filename Name der CSV-Datei
    * @param array $fields Liste der Felder und ihrer Typen
    * @param array $data Alle Datensätze die Exportiert werden sollen
    * @param string $separator Default ist ";", gibt das Trennzeichen zwischen den Spalten an
    * @param string $replacer Wenn gesetzt bzw. nicht FALSE und ein Zeichen gesetzt, dann werden alle $separator Zeichen
    * durch dieses Zeichen ersetzt. Wichtig, falls in den Daten ein $separator Zeichen vorkommt.
    * @param boolean $use_string_quota Wenn TRUE werden alle Texte, Datums und Zeiten in Hochkomma gesetzt
    * @return boolean Rückgabe TRUE die Funktion wurde erfolgreich ausgeführt, FALSE im Fehler fall
    */
   protected function WriteCsvFile( $filename, $fields, $data, $separator = ";", $replacer = false, $use_string_quota = true )
   {
      // Check
      if ( ( count( $data ) == 0 ) || ( count( $fields ) == 0 ) || ( strlen( $filename ) == 0 ) ) {
         return false;
      }
      // Definitionen
      $patterns = array( "/\r\n/", "/\n/", "/\r/" );
      $replacements = array( "", "", "" );
      $export_records = array();
      // Kopfzeile für CSV-Datei generieren
      $head = array();
	  foreach( $fields as $field ) {
         $head[] = "\"" . $field[ 'Field' ] . "\"";
	  }
      $export_records[] = implode( $separator, $head );
      // Zeilen für CSV-Datei erstellen
      foreach( $data as $record ) {
         $row_values = array();
         foreach( $fields as $field ) {
      		$value = $record[ $field[ 'Field' ] ];
      		if ( $replacer !== false ) {
               $patterns[] = "/" . $separator . "/";
               $replacements[] = $replacer;
      		}
      		$value = preg_replace( $patterns, $replacements, $value );
      		if ( ( strpos( $field[ 'Type' ], 'char' ) !== false ) ||
      			 ( strpos( $field[ 'Type' ], 'text' ) !== false ) ||
      			 ( strpos( $field[ 'Type' ], 'date' ) !== false ) ||
      			 ( strpos( $field[ 'Type' ], 'time' ) !== false ) ) {
               $value = '"' . str_replace( '"', '""', $value ) . '"';
      		}
      		else {
               if ( ( $separator != "," ) &&
                  ( ( strpos( $field[ 'Type' ], 'double' ) !== false ) || ( strpos( $field[ 'Type' ], 'float' ) !== false ) ) ) {
                  $value = str_replace( '.', ',', $value );
               }
      		}
      		$row_values[] = $value;
      	}
      	$export_records[] = implode( $separator, $row_values );
      }
      // CSV-Datei erzeugen
      $fp = fopen( $filename, 'w' );
      if ( !$fp ) {
         print( "Fehler: CSV-Datei $filename konnte nicht zum Schreiben geöffnet werden!" );
         return false;
      }
      foreach( $export_records as $line ) {
         fwrite( $fp, $line . "\r\n" );
      }
      fclose( $fp );
      return true;
   }

   // Helper functions

   /**
    * ...
    *
    * @param $msg
    * @return unknown_type
    *
    */
   protected function HandleError( $msg )
   {
      throw new Exception( $msg );
   }

   /**
    * ...
    *
    * @param $gekid_dir
    * @param $login_name
    * @return unknown_type
    *
    */
   public function GetExportPath( $export_sub_dir, $login_name )
   {
   	  $tmp = getUploadDir( $this->_smarty, 'tmp', false );
   	  $path = $tmp[ 'tmp' ] . $export_sub_dir . $login_name . '/';
      return $path;
   }


   /**
    * loads export settings and throw error if not defined
    * @param unknown_type $baseSettings
    * @param unknown_type $exportName
    */
   protected function _loadExportSettings(&$baseSettings, $exportName)
   {
       $exportSettings = json_decode(dlookup($this->_db, 'settings_export', 'settings', "name = '{$exportName}'"), true);

       $settingsFound = false;

       if ($exportSettings !== null) {
           foreach ($exportSettings as $settings) {
               if ($settings['org_id'] == $baseSettings['org_id']) {
                   $baseSettings = array_merge($baseSettings, $settings);

                   $settingsFound = true;

                   break;
               }
           }
       }

       if ($settingsFound === false) {
           $this->HandleError("no settings defined for org_id ({$baseSettings['org_id']})");
       }
   }

   /**
    * ...
    *
    * @param $path
    *
    */
   protected function CreatePath( $path, $umask = 0002 )
   {
      umask( $umask );
      if ( !file_exists( $path ) ) {
         if ( !mkdir( $path, 0777, true ) ) {
      		throw new EMedExportException( "Konnte Pfad [$path] nicht erstellen." );
         }
      }
   }

   /**
    * ...
    *
    * @param string $dir
    */
   protected function DeleteDirectory( $dir )
   {
      if ( !$dh = @opendir( $dir ) ) {
         return;
      }
      while( false !== ( $obj = readdir( $dh ) ) ) {
         if ( $obj == '.' || $obj=='..' ) {
            continue;
         }
         if ( !@unlink( $dir . '/' . $obj ) ) {
            $this->DeleteDirectory( $dir . '/' . $obj );
         }
      }
      closedir( $dh );
      @rmdir( $dir );
   }

   /**
    *
    * @param $file
    * @param $receiver_uid
    * @param $working_dir
    * @param $public_key
    * @param $pgp_binary
    * @return unknown_type
    */
   protected function FileEncryption( $file,
                                      $receiver_uid,
                                      $working_dir,
                                      $public_key,
                                      $pgp_binary ) {
      $this->CreatePath( $working_dir );
      $absolut_public_key = getcwd() . $public_key;
      $cmd = "$pgp_binary --homedir '$working_dir' --import '$absolut_public_key'";
      exec( $cmd );
      $cmd = "$pgp_binary --homedir '$working_dir' --trust-model always -r \"$receiver_uid\" -e '$file'";
      exec( $cmd );
   }

   protected function TrimTNM( $tnm )
   {
      $pos = strpos( $tnm, "(" );
      $tmp = $tnm;
      if ( $pos !== false ) {
         $tmp = substr( $tnm, 0, $pos );
      }
      return $tmp;
   }

   protected function TrimString( $string, $length, $points_at_end=true )
   {
      $tmp = $string;
      if ( strlen( $string ) > $length ) {
         if ( $points_at_end ) {
	        $tmp = substr( $string, 0, $length - 3 );
	        $tmp .= "...";
         }
         else {
            $tmp = substr( $string, 0, $length );
         }
      }
      return $tmp;
   }

}

?>
