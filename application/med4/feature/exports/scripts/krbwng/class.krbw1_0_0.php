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

require_once( getcwd() . '/feature/exports/scripts/class.medbaseexport.php' );
require_once( 'class.krbw_utils_1_0_0.php' );

class CKrbw1_0_0 extends CMedBaseExport
{

   protected $m_l_exp_oids = array();
   protected $m_l_exp_fields = array();
   protected $m_oids;

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_krbw.conf', 'export_krbw' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();

      $this->_xml_template = array(
		 'diagnose'  => 'app/xml.export_krbw_diagnose_1_0_0.tpl',
         'verlauf'   => 'app/xml.export_krbw_verlauf_1_0_0.tpl',
         'therapie'  => 'app/xml.export_krbw_therapie_1_0_0.tpl',
         'abschluss' => 'app/xml.export_krbw_abschluss_1_0_0.tpl'
      );
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#GetVersion()
    */
   public function GetVersion()
   {
      return "1.0.0";
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilter()
    */
   protected function CreateExportFilter( $session, $request )
   {
      $export_filter = array();
      $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
      $export_filter[ 'format_date' ] = '%Y%m%d';
      $export_filter[ 'format_date_app' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $ext_dir = isset( $this->_config[ 'exp_krbw_dir' ] ) ? $this->_config[ 'exp_krbw_dir' ] : 'krbw/';
      $ext_log_subdir = isset( $this->_config[ 'exp_krbw_log_subdir' ] ) ? $this->_config[ 'exp_krbw_log_subdir' ] : 'log/';
      $ext_tmp_subdir = isset( $this->_config[ 'exp_krbw_tmp_subdir' ] ) ? $this->_config[ 'exp_krbw_tmp_subdir' ] : 'tmp/';
      $ext_xml_subdir = isset( $this->_config[ 'exp_krbw_xml_subdir' ] ) ? $this->_config[ 'exp_krbw_xml_subdir' ] : 'xml/';
      $ext_zip_subdir = isset( $this->_config[ 'exp_krbw_zip_subdir' ] ) ? $this->_config[ 'exp_krbw_zip_subdir' ] : 'zip/';
      $this->_export_path = $this->GetExportPath( $ext_dir, $export_filter[ 'login_name' ] );
      if ( file_exists( $this->_export_path ) ) {
         $this->DeleteDirectory( $this->_export_path );
      }
      $this->_log_dir = $this->_export_path . $ext_log_subdir;
      $this->_tmp_dir = $this->_export_path . $ext_tmp_subdir;
      $this->_xml_dir = $this->_export_path . $ext_xml_subdir;
      $this->_zip_dir = $this->_export_path . $ext_zip_subdir;
      // Pfade anlegen
      $this->createPath( $this->_log_dir );
      $this->createPath( $this->_tmp_dir );
      $this->createPath( $this->_xml_dir );
      $this->createPath( $this->_zip_dir );
      $export_filter[ 'org_id' ] = $session[ 'sess_org_id' ];
      // Formular Daten holen
      $export_filter[ 'von' ] = isset( $request[ 'sel_datum_von' ] ) ? todate( $request[ 'sel_datum_von' ], 'en' ) : '';
      $export_filter[ 'bis' ] = isset( $request[ 'sel_datum_bis' ] ) ? todate( $request[ 'sel_datum_bis' ], 'en' ) : '';
      $query = "
         SELECT
           *

         FROM
           l_exp_krbw_oids
      ";
      $result = sql_query_array( $this->_db, $query );
      foreach( $result as $record ) {
         $arr  = array_keys( $record );
         $this->m_l_exp_oids[ $record[ $arr[ 0 ] ] ][ $record[ $arr[ 1 ] ] ][ $record[ $arr[ 2 ] ] ][ $record[ $arr[ 3 ] ] ] = array( "value_oid" => $record[ $arr[ 4 ] ], "value_code" => $record[ $arr[ 5 ] ] );
      }
      $query = "
         SELECT
           *

         FROM
           l_exp_krbw_fields
      ";
      $result = sql_query_array( $this->_db, $query );
      foreach( $result as $record ) {
         $arr  = array_keys( $record );
         $this->m_l_exp_fields[ $record[ $arr[ 0 ] ] ] = array( "key_oid" => $record[ $arr[ 1 ] ], "key_code" => $record[ $arr[ 2 ] ] );
      }
      return $export_filter;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilename()
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = $this->_xml_dir . 'krbw_export_' . date( 'YmdHis' );
      return $filename;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CheckContent()
    */
   protected function CheckContent( $content, $export_filter )
   {
      return $content;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#WriteContent()
    */
   protected function WriteContent( $content, $filename, $export_filter )
   {
      $patient_ids = array();
      $files = array();

      foreach( $content as $document ) {
         $patient_ids[ $document[ 'patient_id' ] ] = $document[ 'patient_id' ];
         $fn = $filename . "_" . $document[ 'ekr_id' ];
         if ( isset( $document[ 'diagnose_dokumente' ] ) ) {
            $files[] = $this->WriteSubContent( $document[ 'diagnose_dokumente' ], $fn, "diagnose", $export_filter );
         }
         if ( isset( $document[ 'verlauf_dokumente' ] ) ) {
            $files[] = $this->WriteSubContent( $document[ 'verlauf_dokumente' ], $fn, "verlauf", $export_filter );
         }
         if ( isset( $document[ 'therapie_dokumente' ] ) ) {
            $files[] = $this->WriteSubContent( $document[ 'therapie_dokumente' ], $fn, "therapie", $export_filter );
         }
         if ( isset( $document[ 'abschluss_dokumente' ] ) ) {
            $files[] = $this->WriteSubContent( $document[ 'abschluss_dokumente' ], $fn, "abschluss", $export_filter );
         }
      }

      $result = array( 'valid' => array(),
                       'invalid' => array() );
      $i = 0;
      foreach( $patient_ids as $id) {
         $result[ 'valid' ][ $i ][ 'patient_id' ] = $id;
         $result[ 'valid' ][ $i ][ 'bez' ] = $this->GetPatientBez( $id, $export_filter );
         $i++;
      }

      $cnt_patient_valid = $i;
      $cnt_patient_invalid = 0;

      $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $this->_config[ 'info_patienten_valid' ] );
      $info_patienten_invalid = str_replace( '#anzahl#', $cnt_patient_invalid, $this->_config[ 'info_patienten_invalid' ] );

      $xml_file = $filename;
      $zip_file = $this->_zip_dir . 'krbw_export_files_' . date( 'YmdHis' ) . ".zip";

      $zip = new PclZip( $zip_file );
      $zip_create = $zip->create( $files, PCLZIP_OPT_REMOVE_ALL_PATH );

      $zip_url = "index.php?page=export_krbw&action=download&type=zip&file=" . $zip_file;

      foreach( $content as $document ) {
         $export_id = $document[ 'diagnose_dokumente' ][ 0 ][ 'dokument' ][ 'versionNumber' ][ 'value' ];
         $this->WriteDbLog( $document[ 'ekr_id' ], $export_id, $export_filter );
      }

      // Template Variablen
      $this->_smarty->assign( array(
            'cnt_patient_valid'      => $cnt_patient_valid,
            'cnt_patient_invalid'    => $cnt_patient_invalid,
            'info_patienten_invalid' => $info_patienten_invalid,
            'info_patienten_valid'   => $info_patienten_valid,
            'result'                 => $result,
      	    'zip_filename'           => basename( $zip_file ),
            'zip_url'                => $zip_url
         )
      );
      return true;
   }

   protected function WriteDbLog( $ekr_id, $export_id, $export_filter ) {
      $query = '
         INSERT INTO exp_krbw_log
            VALUES ( "",
                     "' . $export_id                      . '",
                     "' . $ekr_id                         . '",
                     "1",
         			 "",
                     "' . $export_filter[ 'org_id' ]      . '",
                     "' . $export_filter[ 'von' ]         . '",
                     "' . $export_filter[ 'bis' ]         . '",
                     "' . $export_filter[ 'login_name' ]  . '",
                     "' . date('Y-m-d H:i:s')             . '"
            )
      ';
      $erg = mysql_query( $query, $this->_db );
   }

   /**
    * ...
    *
    * @param $patient_id
    * @param $format_date
    * @return unknown_type
    *
    */
   protected function GetPatientBez( $patient_id, $export_filter )
   {
      $query = "
         SELECT
	        p.vorname,
	        p.nachname,
	        DATE_FORMAT( p.geburtsdatum, '{$export_filter[ 'format_date_app' ]}' ) AS geburtsdatum

         FROM
            patient p

         WHERE
            p.patient_id=$patient_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . ")";
      return $bez;
   }

   protected function WriteSubContent( $content, $filename, $template )
   {
      foreach( $content as $document ) {
         $this->_internal_smarty->debugging = true;
         foreach( $document as $name => $data ) {
            $this->_internal_smarty->assign( $name, $data );
         }
         $xml = $this->_internal_smarty->fetch( $this->_xml_template[ "$template" ] );
         $export_id = $document[ 'dokument' ][ 'versionNumber' ][ 'value' ];
         $xml_file = $filename . '_' . $template . '_' . $export_id . '.xml';
         file_put_contents( $xml_file, $xml );
      }
      return $xml_file;
   }

   protected function CreateSymetricKey()
   {
      $key = md5( "Test" );

      return $key;
   }

   protected function EncryptData( $data, $export_filter )
   {
      $key_vs = $this->CreateSymetricKey();
      $key_klr = $this->CreateSymetricKey();

      return true;
   }

   protected function SetHeadData( $export_filter )
   {
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#ExtractData()
    */
   protected function ExtractData( $export_filter )
   {
      $erkrankungen = array();
      //       Alle Umstellungen von erkrankung_id auf ekr_id
      $query = "
         SELECT DISTINCT
            ek.ekr_id,
            p.patient_id

         FROM
         	ekr ek
         	INNER JOIN tumorstatus ts	ON ts.erkrankung_id=ek.erkrankung_id
           	 						   	   AND ts.datum_sicherung BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'
            INNER JOIN patient p    	ON p.patient_id=ts.patient_id
                                       	   AND p.org_id={$export_filter[ 'org_id' ]}

         WHERE
            ts.anlass='p'

         ORDER BY
            ts.sicherungsgrad,
            ts.datum_sicherung
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result !== false ) && ( count( $result ) > 0 ) ) {
         foreach( $result AS $row ) {
            $export_filter[ 'export_id' ] = $this->GetExportId( $row[ 'ekr_id' ] );
            $erkrankungen[] = $this->GetErkrankung( $row[ 'patient_id' ], $row[ 'ekr_id' ], $export_filter );
         }
      }
      // Debug
      // print_arr( $erkrankungen );
      // exit;
      return $erkrankungen;
   }

   protected function GetExportId( $ekr_id )
   {
      $query = "
   	     SELECT
   	   	    IFNULL( MAX( export_id ) + 1, 1 )	AS export_id

         FROM
            exp_krbw_log

         WHERE
            ekr_id=$ekr_id

         LIMIT 0, 1
      ";
      $db_result = sql_query_array( $this->_db, $query );
      return $db_result[ 0 ][ 'export_id' ];
   }

   /**
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetErkrankung( $patient_id, $ekr_id, $export_filter )
   {
      $result = array();
      $result[ 'patient_id' ] = $patient_id;
      $result[ 'ekr_id' ] = $ekr_id;
      $result[ 'diagnose_dokumente' ] = $this->GetDiagnoseDokumente( $ekr_id, $export_filter );
      $result[ 'verlauf_dokumente' ] = $this->GetVerlaufDokumente( $ekr_id, $export_filter );
      $result[ 'therapie_dokumente' ] = $this->GetTherapieDokumente( $ekr_id, $export_filter );
      $result[ 'abschluss_dokumente' ] = $this->GetAbschlussDokumente( $ekr_id, $export_filter );
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetDiagnoseDokumente( $ekr_id, $export_filter )
   {
      $erstdiagnosedatum = "";
      $result = array();
      $result[ 0 ][ 'dokument' ] = $this->GetDokumentDaten( $ekr_id, $export_filter, 'diagnose' );
      $result[ 0 ][ 'meldebegruendungsdaten' ] = $this->GetMeldebegruendungsdaten( $ekr_id, $export_filter );
      $result[ 0 ][ 'erkrankungsdaten' ] = $this->GetErkrankungsdaten( $ekr_id, $export_filter, $erstdiagnosedatum );
      $result[ 0 ][ 'diagnostik' ] = $this->GetDiagnostik( $ekr_id, $export_filter );
      $result[ 0 ][ 'ph_primaertumor' ] = $this->GetPhPrimaertumor( $ekr_id, $export_filter );
      $result[ 0 ][ 'ph_fernmetastasen' ] = $this->GetPhFernmetastasen( $ekr_id, $export_filter );
      if ( strlen( $erstdiagnosedatum ) > 0 ) {
         $result[ 0 ][ 'dokument' ][ 'author' ][ 'time' ] = CKrbwUtils::CreateValueArray( $erstdiagnosedatum );
      }
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapieDokumente( $ekr_id, $export_filter )
   {
      $erstdiagnosedatum = "";
      $result = array();
      $result[ 0 ][ 'dokument' ] = $this->GetDokumentDaten( $ekr_id, $export_filter, 'therapie' );
      $result[ 0 ][ 'meldebegruendungsdaten' ] = $this->GetMeldebegruendungsdaten( $ekr_id, $export_filter );
      $result[ 0 ][ 'erkrankungsdaten' ] = $this->GetErkrankungsdaten( $ekr_id, $export_filter, $erstdiagnosedatum );
      $result[ 0 ][ 'th_operationen' ] = $this->GetTherapieOperationen( $ekr_id, $export_filter );
      $result[ 0 ][ 'th_bestrahlungen' ] = $this->GetTherapieBestrahlungen( $ekr_id, $export_filter );
      $result[ 0 ][ 'th_systemische_therapien' ] = $this->GetTherapieSystemischeTherapien( $ekr_id, $export_filter );
      $result[ 0 ][ 'th_studiendaten' ] = $this->GetTherapieStudiendaten( $ekr_id, $export_filter );
      $result[ 0 ][ 'th_planungen' ] = $this->GetTherapiePlanung( $ekr_id, $export_filter );
      $result[ 0 ][ 'therapien' ] = $this->GetSonstigeTherapie( $ekr_id, $export_filter );
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetVerlaufDokumente( $ekr_id, $export_filter )
   {
      $erstdiagnosedatum = "";
      $result = array();
      $result[ 0 ][ 'dokument' ] = $this->GetDokumentDaten( $ekr_id, $export_filter, 'verlauf' );
      $result[ 0 ][ 'meldebegruendungsdaten' ] = $this->GetMeldebegruendungsdaten( $ekr_id, $export_filter );
      $result[ 0 ][ 'erkrankungsdaten' ] = $this->GetErkrankungsdaten( $ekr_id, $export_filter, $erstdiagnosedatum );
      $result[ 0 ][ 'ph_fernmetastasen' ] = $this->GetPhFernmetastasen( $ekr_id, $export_filter );
      $result[ 0 ][ 'verlauf' ] = $this->GetVerlauf( $ekr_id, $export_filter );
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetAbschlussDokumente( $ekr_id, $export_filter )
   {
      $erstdiagnosedatum = "";
      $result = array();
      $result[ 0 ][ 'dokument' ] = $this->GetDokumentDaten( $ekr_id, $export_filter, 'abschluss' );
      $result[ 0 ][ 'meldebegruendungsdaten' ] = $this->GetMeldebegruendungsdaten( $ekr_id, $export_filter );
      $result[ 0 ][ 'erkrankungsdaten' ] = $this->GetErkrankungsdaten( $ekr_id, $export_filter, $erstdiagnosedatum );
      $result[ 0 ][ 'abschlussdaten' ] = $this->GetAbschlussdaten( $ekr_id, $export_filter );
      return $result;
   }

   protected function GetDokumentDaten( $ekr_id, $export_filter, $dokument_type )
   {
      $result = array();
      $result[ 'realmCode' ] = CKrbwUtils::CreateCodeArray( "DE", "", "" );
      $result[ 'typeId' ] = CKrbwUtils::CreateIdArray( "2.16.840.1.113883.1.3", CKrbwUtils::ReplaceHashCode( "#DKG-Code-für-KRBW-Dokument" ) );
      $result[ 'code' ] = CKrbwUtils::CreateCodeArray( CKrbwUtils::ReplaceHashCode( "#DKG-Code-für-KRBW-Dokument" ), "2.16.840.1.113883.1.3", "" );
      $result[ 'title' ] = "Krebsregistermeldung";
      $result[ 'effectiveTime' ] = CKrbwUtils::CreateValueArray( date( 'Ymd' ) );
      $result[ 'time' ] = CKrbwUtils::CreateValueArray( "" );
      $result[ 'confidentialityCode' ] = CKrbwUtils::CreateCodeArray( "N", "2.16.840.1.113883.5.25", "" );
      $result[ 'languageCode' ] = CKrbwUtils::CreateCodeArray( "DE", "", "" );
      $result[ 'versionNumber' ] = CKrbwUtils::CreateValueArray( $export_filter[ 'export_id' ] );
      $key_set = $this->GetKeySet( "MELDUNG_REF_MELDER" );
      switch( $dokument_type )
      {
         case 'diagnose' :
            $ref_melder = $ekr_id . "_1";
            break;
         case 'therapie' :
            $ref_melder = $ekr_id . "_2";
            break;
         case 'verlauf' :
            $ref_melder = $ekr_id . "_3";
            break;
         case 'abschluss' :
            $ref_melder = $ekr_id . "_4";
            break;
         default:
            die( "Document type [" . $dokument_type . "] is wrong!" );
            break;
      }
      $result[ 'setId' ] = CKrbwUtils::CreateIdArray( $this->GetKeyOid( $key_set ), $ref_melder );
      $result[ 'id' ] = CKrbwUtils::CreateIdArray( "1.2.276.0.58", $ref_melder );
      if ( $dokument_type == "abschluss" ) {
         $result[ 'patient' ] = $this->GetPatientDaten( $ekr_id, $export_filter, true );
      }
      else {
         $result[ 'patient' ] = $this->GetPatientDaten( $ekr_id, $export_filter );
      }
      $result[ 'melder' ] = $this->GetMelderDaten( $ekr_id, $export_filter );
      $result[ 'author' ] = $this->GetAuthorDaten( $ekr_id, $export_filter );
      $tmp = $this->GetEmpfaengerDaten( $ekr_id, $export_filter );
      if ( count( $tmp ) > 0 ) {
         $result[ 'empfaenger' ] = $this->GetEmpfaengerDaten( $ekr_id, $export_filter );
      }
      return $result;
   }

   protected function GetAuthorDaten( $ekr_id, $export_filter )
   {
      $result = array();
      $result[ 'idents' ] = $this->GetAuthorIdentDaten( $ekr_id, $export_filter );
      $result[ 'time' ] = CKrbwUtils::CreateValueArray( null );
      $quell_system = isset( $this->_config[ 'exp_krbw_source_system' ] ) ? $this->_config[ 'exp_krbw_source_system' ] : '0';
      $key_set = $this->GetKeySet( "QUELLSYSTEM" );
      $result[ 'assignedAuthoringDevice' ][ 'code' ] = CKrbwUtils::CreateCodeArray( $quell_system, $this->GetKeyOid( $key_set ), "" );
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetPatientDaten( $ekr_id, $export_filter, $get_die_date = false )
   {
      $result = array();

      $query = "
   	     SELECT
   	   	    p.patient_nr,
   	   	    p.titel,
   	   	    p.nachname,
   	   	    p.vorname,
            p.geschlecht,
            p.geburtsname,
            DATE_FORMAT( p.geburtsdatum, '{$export_filter[ 'format_date' ]}' )	AS geburtstag,
            p.geburtsort,
            p.strasse,
            p.hausnr,
            p.plz,
            p.ort,
            ''                                        							AS postfach,
            p.staat

         FROM
         	ekr ek
            LEFT JOIN patient p													ON p.patient_id=ek.patient_id

         WHERE
         	ek.ekr_id=$ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $key_set = $this->GetKeySet( "REFERENZNR" );
         $result[ 'id' ] = CKrbwUtils::CreateIdArray( $this->GetKeyOid( $key_set ),
                                                      $db_result[ 0 ][ 'patient_nr' ] );
         $result[ 'name' ] = CKrbwUtils::CreateNameArray( $db_result[ 0 ][ 'titel' ],
                                                          "",
                                                          $db_result[ 0 ][ 'vorname' ],
                                                          $db_result[ 0 ][ 'nachname' ],
                                                          $db_result[ 0 ][ 'geburtsname' ],
                                                          "" );

         $key_set = $this->GetKeySet( "STAATSANGEHOERIGKEIT" );
         $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'staat', $db_result[ 0 ][ 'staat' ] );
         if ( $value_set !== false ) {
            $staat = $this->GetValueCode( $value_set );
         }
         else {
            $staat = null;
         }
         $result[ 'addr' ] = CKrbwUtils::CreateAddressArray( $db_result[ 0 ][ 'strasse' ],
                                                             $db_result[ 0 ][ 'hausnr' ],
                                                             $db_result[ 0 ][ 'plz' ],
                                                             $db_result[ 0 ][ 'ort' ],
                                                             $db_result[ 0 ][ 'postfach' ],
                                                             $staat );
         $result[ 'administrativeGenderCode' ] = CKrbwUtils::CreateCodeArray( "", "", "" );
		 $key_set = $this->GetKeySet( "GESCHLECHT" );

         $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'geschlecht', $db_result[ 0 ][ 'geschlecht' ] );
         if( $value_set !== false ) {
            $result[ 'administrativeGenderCode' ] = CKrbwUtils::CreateCodeArray( $this->GetValueCode( $value_set ),
                                                                                 $this->GetValueOid( $value_set ),
                                                                                 "" );
         }
         $result[ 'birthTime' ] = CKrbwUtils::CreateValueArray( $db_result[ 0 ][ 'geburtstag' ] );
      }
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetMelderDaten( $ekr_id, $export_filter )
   {
      $result = array();

      $query = "
   	     SELECT
            o.name						AS org_name

         FROM
            org o

         WHERE
         	o.org_id={$export_filter[ 'org_id' ]}
      ";
      $db_result = end( sql_query_array( $this->_db, $query ) );

      $melder_id = isset( $this->_config[ 'exp_krbw_reporter_id' ] ) ? $this->_config[ 'exp_krbw_reporter_id' ] : '0';
      $key_set = $this->GetKeySet( "MELDER_ID" );
      $result[ 'id' ] = CKrbwUtils::CreateIdArray( $this->GetKeyOid( $key_set ), $melder_id );

      $result[ 'name' ] = "";
      if ( is_array( $db_result ) && count( $db_result ) > 0 ) {
         $result[ 'name' ] = $db_result[ 'org_name' ];
      }
      return $result;
   }

  /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetAuthorIdentDaten( $ekr_id, $export_filter )
   {
      $result = array();

      $absender_melder_id = isset( $this->_config[ 'exp_krbw_despatch_reporter_id' ] ) ? $this->_config[ 'exp_krbw_despatch_reporter_id' ] : '0';
      $key_set = $this->GetKeySet( "ABSENDER_MELDER_ID" );
      $result[ 0 ][ 'id' ] = CKrbwUtils::CreateIdArray( $this->GetKeyOid( $key_set ), $absender_melder_id );
      return $result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetEmpfaengerDaten( $ekr_id, $export_filter )
   {
      //
      // TODO <------------------------------------------------------------
      //
      $result = array();
      /*

      $query = "
   	     SELECT
   	   	    *

         FROM
            erkrankung e

         WHERE
            e.erkrankung_id=$ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );

	  */
      $result[ 'name' ] = "";
      // $result[ 'telecom' ] = CKrbwUtils::CreateTelefonArray( "WP", "", "WP", "" );
      $result[ 'telecom' ] = CKrbwUtils::CreateTelefonArray( "", "", "", "" );
      $result[ 'addr' ] = CKrbwUtils::CreateAddressArray( "", "", "", "", "", "" );
      return array(); //$result;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetMeldebegruendungsdaten( $ekr_id, $export_filter )
   {
      $query = "
   	     SELECT
   	   	    ek.meldebegruendung,
   	   	    p.staat

         FROM
            ekr ek
            LEFT JOIN patient p ON p.patient_id=ek.patient_id

         WHERE
            ek.ekr_id=$ekr_id

         LIMIT 0, 1
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Meldebegründung" );

         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set = $this->GetKeySet( "UNTERRICHTUNG" );
         $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'meldebegruendung', $db_result[ 0 ][ 'meldebegruendung' ] );
         if ( $value_set !== false ) {
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
         }
         $entry_relation_ship = new CKrbwEntryrelationship;
         $entry_relation_ship->SetTypeCode( "SPRT" );
         $entry_relation_ship->CreateObservation();
         $key_set = $this->GetKeySet( "STAATSANGEHOERIGKEIT" );
         $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'staat', $db_result[ 0 ][ 'staat' ] );
         if ( $value_set !== false ) {
            $entry_relation_ship->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                              				        $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
         }
         else {
            $entry_relation_ship->GetObservation()->SetValueToNullFlavor( "CD",
                                			                              $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ) );
         }
         $entry->GetObservation()->AddEntryrelationship( "Meldebegruendung.2", $entry_relation_ship );
         $component->AddEntry( "Meldebegruendung.1", $entry );
         return $component->GetData();
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetErkrankungsdaten( $ekr_id, $export_filter, &$erstdiagnosedatum )
   {
      $query = "
   	     SELECT
   	   	    DATE_FORMAT( ts.datum_sicherung, '{$export_filter[ 'format_date' ]}' ) 	AS erstdiagnosedatum,
   	   	    ek.erkrankung_id													   	AS tumoridentifikator,
   	   	    ts.diagnose_version													   	AS diagnose_version,
            ts.diagnose															 	AS diagnose_icd,
   	   	    ts.diagnose_text													 	AS diagnose_text

         FROM
         	ekr ek
            LEFT JOIN ( SELECT
            			   erkrankung_id,
                           sicherungsgrad,
            			   datum_beurteilung,
            			   datum_sicherung,
                           anlass,
                           diagnose,
                           diagnose_version,
                           diagnose_text

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           sicherungsgrad,
                           datum_beurteilung DESC

               		  ) ts                       									ON ts.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            ek.ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );

      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Erkrankungsdaten" );
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();

         $entry->GetObservation()->SetAuthorTime( $db_result[ 0 ][ 'erstdiagnosedatum' ] );
         $key_set = $this->GetKeySet( "TUMORIDENTIFIKATOR_DIAGNOSE" );

         $entry->GetObservation()->SetId( $this->GetKeyOid( $key_set ), $db_result[ 0 ][ 'tumoridentifikator' ] );
         $value_oid = "";
         if ( $db_result[ 0 ][ 'diagnose_version' ] == "2010" )
         {
            $key_set = $this->GetKeySet( "DIAGNOSE_VERSION_2010_VALUE_OID" );
            $value_oid = $this->GetKeyOid( $key_set );
         }
         else if ( $db_result[ 0 ][ 'diagnose_version' ] == "2011" )
         {
            $key_set = $this->GetKeySet( "DIAGNOSE_VERSION_2011_VALUE_OID" );
            $value_oid = $this->GetKeyOid( $key_set );
         }
         else if ( $db_result[ 0 ][ 'diagnose_version' ] == "2012" )
         {
            $key_set = $this->GetKeySet( "DIAGNOSE_VERSION_2012_VALUE_OID" );
            $value_oid = $this->GetKeyOid( $key_set );
         }
         else if ( $db_result[ 0 ][ 'diagnose_version' ] == "2013" )
         {
            $key_set = $this->GetKeySet( "DIAGNOSE_VERSION_2013_VALUE_OID" );
            $value_oid = $this->GetKeyOid( $key_set );
         }
         $key_set = $this->GetKeySet( "DIAGNOSE_ICD" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $value_oid, $db_result[ 0 ][ 'diagnose_icd' ] );
         $entry->GetObservation()->GetValue()->SetOriginalText( $db_result[ 0 ][ 'diagnose_text' ] );
         $component->AddEntry( "Diagnose.1", $entry );

         return $component->GetData();
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetPhPrimaertumor( $ekr_id, $export_filter )
   {
      $query = "
   	     SELECT
   	     	IFNULL( ts.lokalisation, dtl.lokalisation_code )	AS lokalisation_icd_o,
   	     	ts.lokalisation_seite		 	    				AS seitenlokalisation

         FROM
            ekr ek
            LEFT JOIN ( SELECT
            			   erkrankung_id,
                           sicherungsgrad,
            			   datum_beurteilung,
                           anlass,
                           diagnose,
                           lokalisation,
                           lokalisation_seite

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           sicherungsgrad,
                           datum_beurteilung DESC

                        ) ts                       				ON ts.erkrankung_id=ek.erkrankung_id
            LEFT JOIN l_exp_diagnose_to_lokalisation dtl 		ON dtl.diagnose_code=ts.diagnose

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
         	ek.ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) &&
           ( strlen( $db_result[ 0 ][ 'lokalisation_icd_o' ] ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Phänomen Primärtumor" );

         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "LOKALISATION-ICD-O_VALUE_OID" );
         $key_set = $this->GetKeySet( "LOKALISATION-ICD-O" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $db_result[ 0 ][ 'lokalisation_icd_o' ] );
         if ( strlen( $db_result[ 0 ][ 'seitenlokalisation' ] ) > 0 ) {
            $qualifier = new CKrbwQualifier;
            $key_set_oid = $this->GetKeySet( "SEITENLOKALISATION_VALUE_OID" );
            $key_set = $this->GetKeySet( "SEITENLOKALISATION" );
            $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
            $qualifier->SetValue( $db_result[ 0 ][ 'seitenlokalisation' ], $this->GetKeyOid( $key_set_oid ) );
            $entry->GetObservation()->GetValue()->AddQualifier( "seitenlokalisation", $qualifier );
         }

         $component->AddEntry( "Diagnose.2", $entry );
         return $component->GetData();
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetPhFernmetastasen( $ekr_id, $export_filter )
   {
      $query = "
   	     SELECT
   	     	ts_m.lokalisation 													 	AS metastasen_lokalisation,
   	     	DATE_FORMAT( ts.datum_sicherung, '{$export_filter[ 'format_date' ]}' ) 	AS metastase_diagnosedatum

         FROM
            ekr ek
            LEFT JOIN ( SELECT
            			   erkrankung_id,
            			   tumorstatus_id,
                           sicherungsgrad,
            			   datum_beurteilung,
            			   datum_sicherung,
                           anlass

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           sicherungsgrad,
                           datum_beurteilung DESC

                        ) ts                       								 	ON ts.erkrankung_id=ek.erkrankung_id
            LEFT JOIN tumorstatus_metastasen ts_m 								 	ON ts_m.tumorstatus_id=ts.tumorstatus_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
         	ek.ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Phänomendaten: Fernmetastasen" );
         foreach( $db_result as $row ) {
            if ( strlen( $row[ 'metastasen_lokalisation' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $key_set_oid = $this->GetKeySet( "METASTASENLOKALISATION_VALUE_OID" );
               $key_set = $this->GetKeySet( "METASTASENLOKALISATION" );
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetKeyOid( $key_set_oid ), $row[ 'metastasen_lokalisation' ] );
               if ( strlen( $row[ 'metastase_diagnosedatum' ] ) > 0 ) {
                  $entry->GetObservation()->SetEffectiveTime( $row[ 'metastase_diagnosedatum' ], "", "" );
               }
               $component->AddEntry( "Diagnose.48", $entry );
            }
         }
         return $component->GetData();
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetDiagnostik( $ekr_id, $export_filter )
   {
      $t1 = false;
      $t2 = false;

      $query = "
   	     SELECT
   	     	ek.ekr_id,
   	   	    LEFT( ts_b1.morphologie, 4 ) 			AS histologie_icd_o,
   	   	    MID( ts_b1.morphologie, 6, 1 )			AS dignitaet,
   	   	    MID( ts_b2.t, 2 )						AS ct_stadium,
   	   	    LEFT( ts_b2.t, 1 )						AS ct_stadium_qualifier,
   	   	    MID( ts_b2.n, 2 )						AS cn_stadium,
   	   	    LEFT( ts_b2.n, 1 )						AS cn_stadium_qualifier,
   	   	    MID( ts_b2.m, 2 )						AS cm_stadium,
   	   	    LEFT( ts_b2.m, 1 )						AS cm_stadium_qualifier,
   	   	    MID( ts_b3.t, 2 )						AS t_stadium_postop,
   	   	    LEFT( ts_b3.t, 1 )						AS t_stadium_postop_qualifier,
   	   	    MID( ts_b3.n, 2 )						AS n_stadium_postop,
   	   	    LEFT( ts_b3.n, 1 )						AS n_stadium_postop_qualifier,
   	   	    MID( ts_b3.m, 2 )						AS m_stadium_postop,
   	   	    LEFT( ts_b3.m, 1 )						AS m_stadium_postop_qualifier,
   	   	    CONCAT( 'G', ts_b1.g )					AS grading,
   	   	    CONCAT( 'L', ts_b1.l )					AS l_kategorie,
   	   	    CONCAT( 'V', ts_b1.v )					AS v_kategorie,
   	   	    CONCAT( 'Pn', ts_b1.ppn )				AS pn_kategorie,
   	   	    h_e.clark								AS clark,
   	   	    ts_b1.figo								AS figo,
   	   	    ts_b1.gleason1 							AS gleason_grading,
   	   	    ts_b1.gleason2 							AS gleason_grading2,
   	   	    ts_b1.gleason1 + ts_b1.gleason2 		AS gleason_score,
   	   	    ts_b1.ann_arbor_stadium					AS ann_arbor_stadium,
   	   	    ts_b1.ann_arbor_aktivitaetsgrad 		AS ann_arbor_aktivitaetsgrad,
   	   	    ts_b1.ann_arbor_extralymphatisch 		AS ann_arbor_extralymph,
   	   	    ts_b1.cll_binet 						AS binet,
   	   	    ts_b1.durie_salmon 						AS durie_salmon,
   	   	    ts_b1.aml_fab 							AS fab,
   	   	    ts_b1.cll_rai 							AS rai,
   	   	    ts_b1.stadium_sclc 						AS valg,
   	   	    h.mercury 								AS mercury,
            ts_b1.psa   							AS gesamt_psa,
            ts_b1.lk_entf				   			AS lymphknoten_untersucht,
            ts_b1.lk_bef   							AS lymphknoten_befallen,
            ts_b1.estro_urteil			   			AS rezeptor_oestrogen,
            ts_b1.prog_urteil			   			AS rezeptor_progesteron,
            ts_b1.her2					   			AS rezeptor_her2,
            an_b4.menopausenstatus 					AS menopausenstatus,
            an_b4.entdeckung 						AS diagnoseanlass

		 FROM
            ekr ek
            LEFT JOIN ( SELECT
            			   *

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           sicherungsgrad,
                           datum_beurteilung DESC

                      ) ts_b1                    				ON ts_b1.erkrankung_id=ek.erkrankung_id
            LEFT JOIN ( SELECT
            			   *

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p' AND LEFT( t, 1 )='c'

                        ORDER BY
                           datum_beurteilung DESC

                      ) ts_b2                   				ON ts_b2.erkrankung_id=ek.erkrankung_id
            LEFT JOIN ( SELECT
            			   *

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p' AND LEFT( t, 1 )='p'

                        ORDER BY
                           datum_beurteilung DESC

                      ) ts_b3                   				ON ts_b3.erkrankung_id=ek.erkrankung_id
            LEFT JOIN histologie h								ON h.erkrankung_id=ek.erkrankung_id
            LEFT JOIN histologie_einzel h_e						ON h_e.erkrankung_id=ek.erkrankung_id
            LEFT JOIN ( SELECT
            			   *

                        FROM
                           anamnese

                        ORDER BY
                           datum DESC

                      ) an_b4                   				ON an_b4.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
         	ek.ekr_id

      ";
      $db_result_s1 = sql_query_array( $this->_db, $query );

      if ( ( $db_result_s1 !== false ) &&
           ( is_array( $db_result_s1 ) ) &&
           ( count( $db_result_s1 ) > 0 ) ) {
         $t1 = true;
      }

      $query = "
   	     SELECT
            an_erk.erkrankung,
            an_erk.erkrankung_version

		 FROM
            ekr ek
            LEFT JOIN ( SELECT
            			   anamnese_id,
            			   erkrankung_id,
            			   datum

                        FROM
                           anamnese

                        ORDER BY
                           datum DESC

                      ) an_b4                   				ON an_b4.erkrankung_id=ek.erkrankung_id
            LEFT JOIN anamnese_erkrankung an_erk				ON an_erk.anamnese_id=an_b4.anamnese_id
            													   AND LEFT( an_erk.erkrankung, 1 )='C'

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            ek.ekr_id
      ";
      $db_result_s2 = sql_query_array( $this->_db, $query );
      if ( ( $db_result_s2 !== false ) &&
           ( is_array( $db_result_s2 ) ) &&
           ( count( $db_result_s2 ) > 0 ) ) {
         $t2 = true;
      }

      if ( $t1  ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Diagnostik" );

         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "HISTOLOGIE-ICD-O_VALUE_OID" );
         $key_set = $this->GetKeySet( "HISTOLOGIE-ICD-O" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'histologie_icd_o' ] );
         if ( strlen( $db_result_s1[ 0 ][ 'dignitaet' ] ) > 0 ) {
            $qualifier = new CKrbwQualifier;
            $key_set_oid = $this->GetKeySet( "DIGNITAET_VALUE_OID" );
            $key_set = $this->GetKeySet( "DIGNITAET" );
            $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
            $qualifier->SetValue( $db_result_s1[ 0 ][ 'dignitaet' ], $this->GetKeyOid( $key_set_oid ) );
            $entry->GetObservation()->GetValue()->AddQualifier( "dignitaet", $qualifier );
         }
         $component->AddEntry( "Diagnose.3", $entry );

         if ( strlen( $db_result_s1[ 0 ][ 'ct_stadium' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "CT_STADIUM_VALUE_OID" );
            $key_set = $this->GetKeySet( "CT_STADIUM" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'ct_stadium' ] );
            if ( strlen( $db_result_s1[ 0 ][ 'ct_stadium_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "CT_STADIUM_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "CT_STADIUM_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 'ct_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "ct_stadium_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.1", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'cn_stadium' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "CN_STADIUM_VALUE_OID" );
            $key_set = $this->GetKeySet( "CN_STADIUM" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'cn_stadium' ] );
            if ( strlen( $db_result_s1[ 0 ][ 'cn_stadium_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "CN_STADIUM_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "CN_STADIUM_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 'cn_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "cn_stadium_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.2", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'cm_stadium' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "CM_STADIUM_VALUE_OID" );
            $key_set = $this->GetKeySet( "CM_STADIUM" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'cm_stadium' ] );
            if ( strlen( $db_result_s1[ 0 ][ 'cm_stadium_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "CM_STADIUM_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "CM_STADIUM_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 'cm_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "cm_stadium_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.3", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 't_stadium_postop' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "T_STADIUM_POSTOP_VALUE_OID" );
            $key_set = $this->GetKeySet( "T_STADIUM_POSTOP" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 't_stadium_postop' ] );
            if ( strlen( $db_result_s1[ 0 ][ 't_stadium_postop_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "T_STADIUM_POSTOP_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "T_STADIUM_POSTOP_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 't_stadium_postop_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "t_stadium_postop_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.4", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'n_stadium_postop' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "N_STADIUM_POSTOP_VALUE_OID" );
            $key_set = $this->GetKeySet( "N_STADIUM_POSTOP" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'n_stadium_postop' ] );
            if ( strlen( $db_result_s1[ 0 ][ 'n_stadium_postop_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "N_STADIUM_POSTOP_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "N_STADIUM_POSTOP_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 'n_stadium_postop_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "n_stadium_postop_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.5", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'm_stadium_postop' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "M_STADIUM_POSTOP_VALUE_OID" );
            $key_set = $this->GetKeySet( "M_STADIUM_POSTOP" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'm_stadium_postop' ] );
            if ( strlen( $db_result_s1[ 0 ][ 'm_stadium_postop_qualifier' ] ) > 0 ) {
               $qualifier = new CKrbwQualifier;
               $key_set_oid = $this->GetKeySet( "M_STADIUM_POSTOP_QUALIFIER_VALUE_OID" );
               $key_set = $this->GetKeySet( "M_STADIUM_POSTOP_QUALIFIER" );
               $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
               $qualifier->SetValue( $db_result_s1[ 0 ][ 'm_stadium_postop_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
               $ers->GetObservation()->GetValue()->AddQualifier( "m_stadium_postop_qualifier", $qualifier );
            }
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.6", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'grading' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "GRADING_VALUE_OID" );
            $key_set = $this->GetKeySet( "GRADING" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'grading' ] );
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.7", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'l_kategorie' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "L_KATEGORIE_VALUE_OID" );
            $key_set = $this->GetKeySet( "L_KATEGORIE" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'l_kategorie' ] );
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.8", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'v_kategorie' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "V_KATEGORIE_VALUE_OID" );
            $key_set = $this->GetKeySet( "V_KATEGORIE" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'v_kategorie' ] );
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.9", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'pn_kategorie' ] ) > 0 ) {
            $ers = new CKrbwEntryrelationship;
            $ers->SetTypeCode( "SPRT" );
            $ers->CreateObservation();
            $key_set_oid = $this->GetKeySet( "PN_KATEGORIE_VALUE_OID" );
            $key_set = $this->GetKeySet( "PN_KATEGORIE" );
            $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                    $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'pn_kategorie' ] );
            $entry->GetObservation()->AddEntryrelationship( "Diagnose3.10", $ers );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'clark' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "CLARK_VALUE_OID" );
            $key_set = $this->GetKeySet( "CLARK" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'clark' ] );
            $component->AddEntry( "Diagnose.7", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'figo' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "FIGO_VALUE_OID" );
            $key_set = $this->GetKeySet( "FIGO" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'figo' ] );
            $component->AddEntry( "Diagnose.9", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'gleason_grading' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "GLEASON_GRADING" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $db_result_s1[ 0 ][ 'gleason_grading' ] );
            $component->AddEntry( "Diagnose.10", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'gleason_grading2' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "GLEASON_GRADING2" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      		 $db_result_s1[ 0 ][ 'gleason_grading2' ] );
            $component->AddEntry( "Diagnose.11", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'gleason_score' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "GLEASON_SCORE" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $db_result_s1[ 0 ][ 'gleason_score' ] );
            $component->AddEntry( "Diagnose.12", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'ann_arbor_stadium' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "ANN_ARBOR_STADIUM_VALUE_OID" );
            $key_set = $this->GetKeySet( "ANN_ARBOR_STADIUM" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'ann_arbor_stadium' ] );
            $component->AddEntry( "Diagnose.20", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'ann_arbor_aktivitaetsgrad' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "ANN_ARBOR_AKTIVITAETSGRAD_VALUE_OID" );
            $key_set = $this->GetKeySet( "ANN_ARBOR_AKTIVITAETSGRAD" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'ann_arbor_aktivitaetsgrad' ] );
            $component->AddEntry( "Diagnose.21", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'ann_arbor_extralymph' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "ANN_ARBOR_EXTRALYMPHATISCH_VALUE_OID" );
            $key_set = $this->GetKeySet( "ANN_ARBOR_EXTRALYMPHATISCH" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'ann_arbor_extralymph' ] );
            $component->AddEntry( "Diagnose.22", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'binet' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "BINET_VALUE_OID" );
            $key_set = $this->GetKeySet( "BINET" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'binet' ] );
            $component->AddEntry( "Diagnose.23", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'durie_salmon' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "DURIE_SALMON_VALUE_OID" );
            $key_set = $this->GetKeySet( "DURIE_SALMON" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'durie_salmon' ] );
            $component->AddEntry( "Diagnose.24", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'fab' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "FAB_VALUE_OID" );
            $key_set = $this->GetKeySet( "FAB" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'fab' ] );
            $component->AddEntry( "Diagnose.25", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'rai' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "RAI_VALUE_OID" );
            $key_set = $this->GetKeySet( "RAI" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'rai' ] );
            $component->AddEntry( "Diagnose.30", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'valg' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "VALG_VALUE_OID" );
            $key_set = $this->GetKeySet( "VALG" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'valg' ] );
            $component->AddEntry( "Diagnose.36", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'mercury' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "MERCURY_VALUE_OID" );
            $key_set = $this->GetKeySet( "MERCURY" );
            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                      $this->GetKeyOid( $key_set_oid ), $db_result_s1[ 0 ][ 'mercury' ] );
            $component->AddEntry( "Diagnose.38", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'gesamt_psa' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "GESAMT_PSA" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $db_result_s1[ 0 ][ 'gesamt_psa' ] );
            $component->AddEntry( "Diagnose.39", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'lymphknoten_untersucht' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "LYMPHKNOTEN_UNTERSUCHT" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $db_result_s1[ 0 ][ 'lymphknoten_untersucht' ] );
            $component->AddEntry( "Diagnose.40", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'lymphknoten_befallen' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set = $this->GetKeySet( "LYMPHKNOTEN_BEFALLEN" );
            $entry->GetObservation()->SetValueIntegerHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $db_result_s1[ 0 ][ 'lymphknoten_befallen' ] );
            $component->AddEntry( "Diagnose.41", $entry );
         }

         if ( strlen( $db_result_s1[ 0 ][ 'rezeptor_oestrogen' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "REZEPTOR_OESTROGEN" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'estro_urteil', $db_result_s1[ 0 ][ 'rezeptor_oestrogen' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Diagnose.42", $entry );
            }
         }

         if ( strlen( $db_result_s1[ 0 ][ 'rezeptor_progesteron' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "REZEPTOR_PROGESTERON" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'prog_urteil', $db_result_s1[ 0 ][ 'rezeptor_progesteron' ] );
            if ( $value_set !== false ) {
	            $entry = new CKrbwEntry;
	            $entry->SetTypeCode( "DRIV" );
	            $entry->CreateObservation();
	            $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
	                                                      $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
	            $component->AddEntry( "Diagnose.43", $entry );
            }
         }

         if ( strlen( $db_result_s1[ 0 ][ 'rezeptor_her2' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "REZEPTOR_HER2" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'her2', $db_result_s1[ 0 ][ 'rezeptor_her2' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Diagnose.44", $entry );
            }
         }

         if ( strlen( $db_result_s1[ 0 ][ 'menopausenstatus' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "MENOPAUSENSTATUS" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'menopausenstatus', $db_result_s1[ 0 ][ 'menopausenstatus' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Diagnose.45", $entry );
            }
         }

         if ( strlen( $db_result_s1[ 0 ][ 'diagnoseanlass' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "DIAGNOSEANLASS" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ), 'entdeckung', $db_result_s1[ 0 ][ 'diagnoseanlass' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Diagnose.46", $entry );
            }
         }

         if ( $t2 ) {
            foreach( $db_result_s2 as $row ) {
               if ( ( strlen( $row[ 'erkrankung' ] ) > 0 ) &&
                    ( strlen( $row[ 'erkrankung_version' ] ) > 0 ) ) {
                  $value_oid = "";
                  if ( $row[ 'erkrankung_version' ] == "2010" )
                  {
                     $key_set_oid = $this->GetKeySet( "ERKRANKUNG_VERSION_2010_VALUE_OID" );
                     $value_oid = $this->GetKeyOid( $key_set_oid );
                  }
                  else if ( $row[ 'erkrankung_version' ] == "2011" )
                  {
                     $key_set_oid = $this->GetKeySet( "ERKRANKUNG_VERSION_2011_VALUE_OID" );
                     $value_oid = $this->GetKeyOid( $key_set_oid );
                  }
                  else if ( $row[ 'erkrankung_version' ] == "2012" )
                  {
                     $key_set_oid = $this->GetKeySet( "ERKRANKUNG_VERSION_2012_VALUE_OID" );
                     $value_oid = $this->GetKeyOid( $key_set_oid );
                  }
                  else if ( $row[ 'erkrankung_version' ] == "2013" )
                  {
                     $key_set_oid = $this->GetKeySet( "ERKRANKUNG_VERSION_2013_VALUE_OID" );
                     $value_oid = $this->GetKeyOid( $key_set_oid );
                  }
                  $entry = new CKrbwEntry;
                  $entry->SetTypeCode( "DRIV" );
                  $entry->CreateObservation();
                  $key_set = $this->GetKeySet( "FRUEHERE_TUMORDIAGNOSEN" );

                  $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                            $value_oid, $row[ 'erkrankung' ] );
                  $component->AddEntry( "Diagnose.47", $entry );
               }
            }
         }

         return $component->GetData();
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapieOperationen( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
   	     SELECT
   	     	e.eingriff_id,
   	   	    CONCAT( 'eingriff-', e.eingriff_id )								AS tumoridentifikator,
   	   	    IF( e.art_primaertumor='1'
            	OR e.art_lk='1'
            	OR e.art_metastasen='1',
            	'OP',
            	NULL )															AS therapieart,
           	DATE_FORMAT( e.datum, '{$export_filter[ 'format_date' ]}' )			AS therapie_start,
           	DATE_FORMAT( e.datum, '{$export_filter[ 'format_date' ]}' )			AS therapie_ende,
   	   	    IF( e.art_transplantation_autolog IS NOT NULL
   	   	        AND e.art_transplantation_autolog='1',
				'STAMM-L',
				IF( ( e.art_transplantation_allogen_v IS NOT NULL
   	   	        	  AND e.art_transplantation_allogen_v='1' )
   	   	        	OR
   	   	        	( e.art_transplantation_allogen_nv IS NOT NULL
   	   	        	  AND e.art_transplantation_allogen_nv='1' ),
					'STAMM-G',
   	   	        	IF( e.art_transplantation_syngen IS NOT NULL
   	   	        		AND e.art_transplantation_syngen='1',
						'STAMM-S',
						NULL ) ) )												AS stammzelltransplantation,
			IF( e.art_primaertumor IS NOT NULL
			    AND e.art_primaertumor='1',
			    CONCAT( 'R', h.r ),
			    NULL )															AS r_klassifikation,
			IF( e.art_metastasen IS NOT NULL
			    AND e.art_metastasen='1',
			    CONCAT( 'R', h.r ),
			    NULL )															AS r_klassifikation_fernmetastasen

         FROM
            ekr ek
         	INNER JOIN eingriff e												ON e.erkrankung_id=ek.erkrankung_id
			INNER JOIN ( SELECT
						    *
						 FROM
						    histologie

						 ORDER BY
						    datum DESC
					   ) h														ON h.eingriff_id=e.eingriff_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            e.eingriff_id
      ";
      $db_result = sql_query_array( $this->_db, $query );

      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $c = 0;
         foreach( $db_result as $row ) {
            $ops_codes = $this->GetTherapieOperationenOpsCodes( $row[ 'eingriff_id' ], $export_filter );
            if ( count( $ops_codes ) > 0 ) {
               foreach( $ops_codes as $ops_code ) {
                  $components[] = $this->CreateThOperationComponent( $row, $ops_code );
                  if ( ( strlen( $row[ 'therapieart' ] ) > 0 ) &&
                       ( strlen( $row[ 'stammzelltransplantation' ] ) > 0 ) ) {
                     $components[] = $this->CreateThOperationStammzellenComponent( $row, $ops_code );
                  }
               }
            }
            else {
               $components[] = $this->CreateThOperationComponent( $row, "" );
               if ( ( strlen( $row[ 'therapieart' ] ) > 0 ) &&
                    ( strlen( $row[ 'stammzelltransplantation' ] ) > 0 ) ) {
                  $components[] = $this->CreateThOperationStammzellenComponent( $row, $ops_code );
               }
            }
         }
         return $components;
      }
      return array();
   }

   protected function CreateThOperationComponent( $row, $ops_code )
   {
      $component = new CKrbwComponent;
      $component->SetTitle( "Therapie: Operative Therapie" );
      if ( strlen( $row[ 'therapieart' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateProcedure();
         if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
            $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
            $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
         }
         if ( strlen( $row[ 'therapieart' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "THERAPIEART" );
            $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $row[ 'therapieart' ] );
         }
         $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
         $component->AddEntry( "Therapie.1", $entry );
      }

      if ( strlen( $ops_code[ 'prozedur' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateProcedure();
         if ( $ops_code[ 'prozedur_version' ] == "2010" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2010_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2011" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2011_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2012" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2012_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2013" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2013_VALUE_OID" );
         }
         $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $ops_code[ 'prozedur' ] );
         $component->AddEntry( "Therapie.2", $entry );
      }

      if ( strlen( $row[ 'r_klassifikation' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "R_KLASSIFIKATION_VALUE_OID" );
         $key_set = $this->GetKeySet( "R_KLASSIFIKATION" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $row[ 'r_klassifikation' ] );
         $component->AddEntry( "Therapie.3", $entry );
      }

      if ( strlen( $row[ 'r_klassifikation_fernmetastasen' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "R_KLASSIFIKATION_FERNMETASTASEN_VALUE_OID" );
         $key_set = $this->GetKeySet( "R_KLASSIFIKATION_FERNMETASTASEN" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $row[ 'r_klassifikation_fernmetastasen' ] );
         $component->AddEntry( "Therapie.4", $entry );
      }

      return $component->GetData();
   }

   protected function CreateThOperationStammzellenComponent( $row, $ops_code )
   {
      $component = new CKrbwComponent;
      $component->SetTitle( "Therapie: Operative Therapie" );

      if ( strlen( $row[ 'stammzelltransplantation' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateProcedure();
         if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
            $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
            $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
         }
         if ( strlen( $row[ 'stammzelltransplantation' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "STAMMZELLTRANSPLANTATION" );
            $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $row[ 'stammzelltransplantation' ] );
         }
         $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
         $component->AddEntry( "Therapie.1", $entry );
      }

      if ( strlen( $ops_code[ 'prozedur' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateProcedure();
         if ( $ops_code[ 'prozedur_version' ] == "2010" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2010_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2011" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2011_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2012" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2012_VALUE_OID" );
         }
         else if ( $ops_code[ 'prozedur_version' ] == "2013" )
         {
            $key_set = $this->GetKeySet( "PROZEDUR_VERSION_2013_VALUE_OID" );
         }
         $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $ops_code[ 'prozedur' ] );
         $component->AddEntry( "Therapie.2", $entry );
      }

      if ( strlen( $row[ 'r_klassifikation' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "R_KLASSIFIKATION_VALUE_OID" );
         $key_set = $this->GetKeySet( "R_KLASSIFIKATION" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $row[ 'r_klassifikation' ] );
         $component->AddEntry( "Therapie.3", $entry );
      }

      if ( strlen( $row[ 'r_klassifikation_fernmetastasen' ] ) > 0 ) {
         $entry = new CKrbwEntry;
         $entry->SetTypeCode( "DRIV" );
         $entry->CreateObservation();
         $key_set_oid = $this->GetKeySet( "R_KLASSIFIKATION_FERNMETASTASEN_VALUE_OID" );
         $key_set = $this->GetKeySet( "R_KLASSIFIKATION_FERNMETASTASEN" );
         $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                   $this->GetKeyOid( $key_set_oid ), $row[ 'r_klassifikation_fernmetastasen' ] );
         $component->AddEntry( "Therapie.4", $entry );
      }

      return $component->GetData();
   }

   protected function GetTherapieOperationenOpsCodes( $eingriff_id, $export_filter )
   {
      $query = "
   	     SELECT
   	     	prozedur,
   	     	prozedur_version

   	     FROM
   	        eingriff_ops

   	     WHERE
   	        eingriff_id=$eingriff_id
   	  ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         return $db_result;
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapieBestrahlungen( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
      	 SELECT
   	   	    stp.strahlentherapie_id,
   	   	    CONCAT( 'strahlentherapie-', stp.strahlentherapie_id )			AS tumoridentifikator,
   	   	    stp.art															AS therapieart,
            DATE_FORMAT( stp.beginn, '{$export_filter[ 'format_date' ]}' )	AS therapie_start,
           	DATE_FORMAT( stp.ende, '{$export_filter[ 'format_date' ]}' )	AS therapie_ende,
           	CASE stp.endstatus
           		WHEN 'abbr' THEN 'aborted'
                ELSE 'completed'
           	END																AS therapieabbruch

         FROM
         	ekr ek
            INNER JOIN strahlentherapie stp									ON stp.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            stp.strahlentherapie_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Therapie: Bestrahlung" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateProcedure();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
               $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
               if ( strlen( $row[ 'therapieart' ] ) > 0 ) {
                  $key_set = $this->GetKeySet( "STRAHLENTHERAPIE" );
                  $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                  								   'strahlentherapie_art', $row[ 'therapieart' ] );
                  if ( $value_set !== false ) {
                     $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $this->GetValueCode( $value_set ) );

                  }
               }
               if ( strlen( $row[ 'therapieabbruch' ] ) > 0 ) {
                  $entry->GetProcedure()->SetStatusCode( $row[ 'therapieabbruch' ], "", "" );
               }
               $component->AddEntry( "Therapie.1", $entry );
            }

            $components[] = $component->GetData();
         }
         return $components;
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapieSystemischeTherapien( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
      	 SELECT
   	   	    tp_sys.therapie_systemisch_id,
   	   	    CONCAT( 'therapie_systemisch-', tp_sys.therapie_systemisch_id )		AS tumoridentifikator,
   	   	    tp_sys.vorlage_therapie_art											AS medikamentoese_therapie,
            DATE_FORMAT( tp_sys.beginn, '{$export_filter[ 'format_date' ]}' )	AS therapie_start,
           	DATE_FORMAT( tp_sys.ende, '{$export_filter[ 'format_date' ]}' )		AS therapie_ende,
           	CASE tp_sys.endstatus
           		WHEN 'abbr' THEN 'aborted'
                ELSE 'completed'
           	END																	AS therapieabbruch

         FROM
         	ekr ek
            INNER JOIN therapie_systemisch tp_sys								ON tp_sys.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            tp_sys.therapie_systemisch_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Therapie: Systemische Therapie" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateProcedure();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
               $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
               if ( strlen( $row[ 'therapieabbruch' ] ) > 0 ) {
                  $entry->GetProcedure()->SetStatusCode( $row[ 'therapieabbruch' ], "", "" );
               }
               if ( strlen( $row[ 'medikamentoese_therapie' ] ) > 0 ) {
                  $key_set = $this->GetKeySet( "MEDIKAMENTOESE_THERAPIE" );
                  $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                  								   'therapie_systemisch_art', $db_result[ 0 ][ 'medikamentoese_therapie' ] );
                  if ( $value_set !== false ) {
                     $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $this->GetValueCode( $value_set ) );
                  }
               }
               $component->AddEntry( "Therapie.1", $entry );
            }
            $components[] = $component->GetData();
         }
         return $components;
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapieStudiendaten( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
      	 SELECT
   	   	    IF( COUNT(*) > 0, 'J', 'N' )	AS studienteilnahme

         FROM
         	ekr ek
            INNER JOIN studie s				ON s.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

      ";
      $db_result = sql_query_array( $this->_db, $query );

      $component = new CKrbwComponent;
      $component->SetTitle( "Studiendaten" );

	  $entry = new CKrbwEntry;
	  $entry->SetTypeCode( "DRIV" );
	  $entry->CreateProcedure();
	  $key_set = $this->GetKeySet( "STUDIENTEILNAHME" );
	  $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $db_result[ 0 ][ 'studienteilnahme' ] );
	  $component->AddEntry( "Therapie.5", $entry );
	  $components[] = $component->GetData();
      return $components;
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetTherapiePlanung( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
      	 SELECT
   	   	    tp.therapieplan_id,
   	   	    CONCAT( 'therapieplan-', tp.therapieplan_id )					AS tumoridentifikator,
   	   	    IF( tp.watchful_waiting='1'
            	OR tp.active_surveillance='1',
            	'WS',
            	NULL )														AS therapieart,
            DATE_FORMAT( tp.datum, '{$export_filter[ 'format_date' ]}' )	AS therapie_start,
           	DATE_FORMAT( tp.datum, '{$export_filter[ 'format_date' ]}' )	AS therapie_ende

         FROM
         	ekr ek
            INNER JOIN therapieplan tp										ON tp.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            tp.therapieplan_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Therapie: Planung" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateProcedure();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
               $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
               if ( strlen( $row[ 'therapieart' ] ) > 0 ) {
                  $key_set = $this->GetKeySet( "THERAPIEART" );
                  $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $row[ 'therapieart' ] );
               }
               $component->AddEntry( "Therapie.1", $entry );
            }
            $components[] = $component->GetData();
         }
         return $components;
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetSonstigeTherapie( $ekr_id, $export_filter )
   {
      $components = array();
      $query = "
      	 SELECT
      	 	ek.ekr_id,
   	   	    s_tp.sonstige_therapie_id,
   	   	    CONCAT( 'sonstige_therapie-', s_tp.sonstige_therapie_id )			AS tumoridentifikator,
   	   	    'ATH'																AS therapieart,
            DATE_FORMAT( s_tp.beginn, '{$export_filter[ 'format_date' ]}' )		AS therapie_start,
           	DATE_FORMAT( s_tp.ende, '{$export_filter[ 'format_date' ]}' )		AS therapie_ende,
           	CASE s_tp.endstatus
           		WHEN 'abbr' THEN 'aborted'
                ELSE 'completed'
           	END																	AS therapieabbruch,
           	s_tp.bez															AS therapie_detail

         FROM
         	ekr ek
            INNER JOIN sonstige_therapie s_tp									ON s_tp.erkrankung_id=ek.erkrankung_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            s_tp.sonstige_therapie_id
      ";
      $db_result = sql_query_array( $this->_db, $query );

      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Therapie: Sonstige Therapie" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateProcedure();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_THERAPIE" );
               $entry->GetProcedure()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetProcedure()->SetEffectiveTime( "", $row[ 'therapie_start' ], $row[ 'therapie_ende' ] );
               if ( strlen( $row[ 'therapieabbruch' ] ) > 0 ) {
                  $entry->GetProcedure()->SetStatusCode( $row[ 'therapieabbruch' ], "", "" );
               }
               if ( strlen( $row[ 'therapieart' ] ) > 0 ) {
                  $key_set = $this->GetKeySet( "THERAPIEART" );
                  $entry->GetProcedure()->SetValueHelper( $this->GetKeyOid( $key_set ), "", "", $row[ 'therapieart' ] );
               }
               // Ticket #3228
               if ( strlen( $row[ 'therapie_detail' ] ) > 0 ) {
                  $entry->GetProcedure()->GetValue()->SetOriginalText( $row[ 'therapie_detail' ] );
               }
               $component->AddEntry( "Therapie.1", $entry );
            }
            $components[] = $component->GetData();
         }
         return $components;
      }
      return array();
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetVerlauf( $ekr_id, $export_filter )
   {
      $components = array();
      // Verlaeufe aus tumorstatus
      $query = "
      	 SELECT
   	   	    ts.tumorstatus_id														AS tumoridentifikator,
            DATE_FORMAT( ts.datum_sicherung, '{$export_filter[ 'format_date' ]}' )	AS untersuchungsdatum,
            'PD'																	AS tumorgeschehen,
            MID( ts.t, 2 ) 															AS t_stadium,
            LEFT( ts.t, 1 )															AS t_stadium_qualifier,
            MID( ts.n, 2 ) 															AS n_stadium,
            LEFT( ts.n, 1 )															AS n_stadium_qualifier,
            MID( ts.m, 2 ) 															AS m_stadium,
            LEFT( ts.m, 1 )															AS m_stadium_qualifier

         FROM
         	ekr ek
            INNER JOIN tumorstatus ts												ON ts.erkrankung_id=ek.erkrankung_id
            																		   AND LEFT( ts.anlass, 1 )='r'

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
         	ek.ekr_id
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Verlauf: Status" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_TUMORSTATUS" );
               $entry->GetObservation()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetObservation()->SetEffectiveTime( "", $row[ 'untersuchungsdatum' ], "" );
               if ( strlen( $row[ 'tumorgeschehen' ] ) > 0 ) {
                  $key_set_oid = $this->GetKeySet( "TUMORGESCHEHEN_VALUE_OID" );
                  $key_set = $this->GetKeySet( "TUMORGESCHEHEN" );
                  $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                            $this->GetKeyOid( $key_set_oid ), $row[ 'tumorgeschehen' ] );

                  if ( strlen( $row[ 't_stadium' ] ) > 0 ) {
                     $ers = new CKrbwEntryrelationship;
                     $ers->CreateObservation();
                     $key_set_oid = $this->GetKeySet( "T_STADIUM_VALUE_OID" );
                     $key_set = $this->GetKeySet( "T_STADIUM" );
                     $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $this->GetKeyOid( $key_set_oid ), $row[ 't_stadium' ] );
                     if ( strlen( $row[ 't_stadium_qualifier' ] ) > 0 ) {
                        $qualifier = new CKrbwQualifier;
                        $key_set_oid = $this->GetKeySet( "T_STADIUM_QUALIFIER_VALUE_OID" );
                        $key_set = $this->GetKeySet( "T_STADIUM_QUALIFIER" );
                        $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
                        $qualifier->SetValue( $row[ 't_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
                        $ers->GetObservation()->GetValue()->AddQualifier( "t_stadium_qualifier", $qualifier );
                     }
                     $entry->GetObservation()->AddEntryrelationship( "Verlauf.1.1", $ers );
                  }

                  if ( strlen( $row[ 'n_stadium' ] ) > 0 ) {
                     $ers = new CKrbwEntryrelationship;
                     $ers->CreateObservation();
                     $key_set_oid = $this->GetKeySet( "N_STADIUM_VALUE_OID" );
                     $key_set = $this->GetKeySet( "N_STADIUM" );
                     $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $this->GetKeyOid( $key_set_oid ), $row[ 'n_stadium' ] );
                     if ( strlen( $row[ 'n_stadium_qualifier' ] ) > 0 ) {
                        $qualifier = new CKrbwQualifier;
                        $key_set_oid = $this->GetKeySet( "N_STADIUM_QUALIFIER_VALUE_OID" );
                        $key_set = $this->GetKeySet( "N_STADIUM_QUALIFIER" );
                        $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
                        $qualifier->SetValue( $row[ 'n_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
                        $ers->GetObservation()->GetValue()->AddQualifier( "n_stadium_qualifier", $qualifier );
                     }
                     $entry->GetObservation()->AddEntryrelationship( "Verlauf.1.2", $ers );
                  }

                  if ( strlen( $row[ 'm_stadium' ] ) > 0 ) {
                     $ers = new CKrbwEntryrelationship;
                     $ers->CreateObservation();
                     $key_set_oid = $this->GetKeySet( "M_STADIUM_VALUE_OID" );
                     $key_set = $this->GetKeySet( "M_STADIUM" );
                     $ers->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                             $this->GetKeyOid( $key_set_oid ), $row[ 'm_stadium' ] );
                     if ( strlen( $row[ 'm_stadium_qualifier' ] ) > 0 ) {
                        $qualifier = new CKrbwQualifier;
                        $key_set_oid = $this->GetKeySet( "M_STADIUM_QUALIFIER_VALUE_OID" );
                        $key_set = $this->GetKeySet( "M_STADIUM_QUALIFIER" );
                        $qualifier->SetName( $this->GetKeyCode( $key_set ), $this->GetKeyOid( $key_set ) );
                        $qualifier->SetValue( $row[ 'm_stadium_qualifier' ], $this->GetKeyOid( $key_set_oid ) );
                        $ers->GetObservation()->GetValue()->AddQualifier( "m_stadium_qualifier", $qualifier );
                     }
                     $entry->GetObservation()->AddEntryrelationship( "Verlauf.1.3", $ers );
                  }
               }
               $component->AddEntry( "Verlauf.1", $entry );
            }
            $components[] = $component->GetData();
            $this->GetVerlaufMetastasenDaten( $components, $row, $export_filter );
         }
      }

      // Verlaeufe aus nachsorge
      $query = "
      	 SELECT
   	   	    n.nachsorge_id													AS tumoridentifikator,
            DATE_FORMAT( n.datum, '{$export_filter[ 'format_date' ]}' )		AS untersuchungsdatum,
            n.response_klinisch												AS tumorgeschehen

         FROM
         	ekr ek
            INNER JOIN nachsorge_erkrankung n_ekr							ON n_ekr.erkrankung_weitere_id=ek.erkrankung_id
            LEFT JOIN nachsorge n											ON n_ekr.nachsorge_id=n.nachsorge_id

         WHERE
            ek.ekr_id=$ekr_id

         GROUP BY
            ek.ekr_id

      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         foreach( $db_result as $row ) {
            $component = new CKrbwComponent;
            $component->SetTitle( "Verlauf: Nachsorge" );

            if ( strlen( $row[ 'tumoridentifikator' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_NACHSORGE" );
               $entry->GetObservation()->SetId( $this->GetKeyOid( $key_set_oid ), $row[ 'tumoridentifikator' ] );
               $entry->GetObservation()->SetEffectiveTime( "", $row[ 'untersuchungsdatum' ], "" );
               if ( strlen( $row[ 'tumorgeschehen' ] ) > 0 ) {
                  $key_set_oid = $this->GetKeySet( "TUMORGESCHEHEN_VALUE_OID" );
                  $key_set = $this->GetKeySet( "TUMORGESCHEHEN" );
                  $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                            $this->GetKeyOid( $key_set_oid ), $row[ 'tumorgeschehen' ] );
               }
               $component->AddEntry( "Verlauf.1", $entry );
            }
            $components[] = $component->GetData();
         }
      }
      return $components;
   }

   protected function GetVerlaufMetastasenDaten( &$components, $data, $export_filter )
   {
      $c = 0;
      $query = "
   	     SELECT
   	   	    ts_m.lokalisation

         FROM
            tumorstatus_metastasen ts_m

         WHERE
            ts_m.tumorstatus_id={$data[ 'tumoridentifikator' ]}
      ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Verlauf: Status" );
         $c = 0;
         foreach( $db_result as $row ) {
            if ( strlen( $row[ 'lokalisation' ] ) > 0 ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetEffectiveTime( $data[ 'untersuchungsdatum' ], "", "" );
               $key_set_oid = $this->GetKeySet( "METASTASENLOKALISATION_VALUE_OID" );
               $key_set = $this->GetKeySet( "METASTASENLOKALISATION" );
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetKeyOid( $key_set_oid ), $row[ 'lokalisation' ] );
               $component->AddEntry( "Verlauf.2." . $c, $entry );
               $c++;
            }
         }
         $components[] = $component->GetData();
      }
   }

   /**
    *
    * @param integer $ekr_id
    * @param array $export_filter
    * @return array $result
    */
   protected function GetAbschlussdaten( $ekr_id, $export_filter )
   {
      $query = "
   	     SELECT DISTINCT
   	     	a.abschluss_id														AS tumoridentifikator,
   	     	a.abschluss_grund													AS abschlussgrund,
   	     	IF( a.abschluss_grund='lost' ||
   	     	    a.abschluss_grund='abge',
   	     	    DATE_FORMAT( n.datum, '{$export_filter[ 'format_date' ]}' ),
   	     	    NULL )															AS letzte_patienteninformation,
   	     	a.tod_tumorassoziation												AS tod_tumorbedingt,
            DATE_FORMAT( a.todesdatum, '{$export_filter[ 'format_date' ]}' )    AS todesdatum

         FROM
         	ekr ek
         	LEFT JOIN patient p													ON p.patient_id=ek.patient_id
            LEFT JOIN abschluss a												ON a.patient_id=p.patient_id
            LEFT JOIN nachsorge n												ON n.patient_id=p.patient_id

         WHERE
            ek.ekr_id=$ekr_id

         ORDER BY
         	n.datum DESC
	  ";
      $db_result = sql_query_array( $this->_db, $query );
      if ( ( $db_result !== false ) &&
           ( is_array( $db_result ) ) &&
           ( count( $db_result ) > 0 ) ) {
         $component = new CKrbwComponent;
         $component->SetTitle( "Abschlussdaten" );

        if ( strlen( $db_result[ 0 ][ 'tumoridentifikator' ] ) > 0 ) {
            $entry = new CKrbwEntry;
            $entry->SetTypeCode( "DRIV" );
            $entry->CreateObservation();
            $key_set_oid = $this->GetKeySet( "TUMORIDENTIFIKATOR_ABSCHLUSS" );
            $entry->GetObservation()->SetId( $this->GetKeyOid( $key_set_oid ), $db_result[ 0 ][ 'tumoridentifikator' ] );
            if ( strlen( $db_result[ 0 ][ 'letzte_patienteninformation' ] ) > 0 ) {
               $entry->GetObservation()->SetEffectiveTime( "", "", $db_result[ 0 ][ 'letzte_patienteninformation' ] );
            }
            $component->AddEntry( "Verlauf", $entry );
         }

         if ( strlen( $db_result[ 0 ][ 'abschlussgrund' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "ABSCHLUSSGRUND" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
               								 'abschluss_grund', $db_result[ 0 ][ 'abschlussgrund' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetEffectiveTime( "", "", $db_result[ 0 ][ 'todesdatum' ] );
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Abschluss.1", $entry );
            }
         }

         if ( strlen( $db_result[ 0 ][ 'tod_tumorbedingt' ] ) > 0 ) {
            $key_set = $this->GetKeySet( "TOD_TUMORBEDINGT" );
            $value_set = $this->GetValueSet( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
               								 'tod_tumorassoziation', $db_result[ 0 ][ 'tod_tumorbedingt' ] );
            if ( $value_set !== false ) {
               $entry = new CKrbwEntry;
               $entry->SetTypeCode( "DRIV" );
               $entry->CreateObservation();
               $entry->GetObservation()->SetValueHelper( $this->GetKeyOid( $key_set ), $this->GetKeyCode( $key_set ),
                                                         $this->GetValueOid( $value_set ), $this->GetValueCode( $value_set ) );
               $component->AddEntry( "Abschluss.2", $entry );
            }
         }

         return $component->GetData();
      }
      return array();
   }

   // Helper

   protected function GetKeySet( $name )
   {
      if ( isset( $this->m_l_exp_fields[ $name ] ) ) {
         return $this->m_l_exp_fields[ $name ];
      }
      return false;
   }

   protected function GetKeyOid( $key_set )
   {
      if ( isset( $key_set[ 'key_oid' ] ) ) {
         return $key_set[ 'key_oid' ];
      }
      return "ERROR: No key OID defined.";
   }

   protected function GetKeyCode( $key_set )
   {
      if ( isset( $key_set[ 'key_code' ] ) ) {
         return $key_set[ 'key_code' ];
      }
      return "ERROR: No key code defined.";
   }

   protected function GetValueSet( $key_oid, $key_code, $class, $code_med )
   {
      if ( isset( $this->m_l_exp_oids[ $key_oid ][ $key_code ][ $class ][ $code_med ] ) ) {
         return $this->m_l_exp_oids[ $key_oid ][ $key_code ][ $class ][ $code_med ];
      }
      return false;
   }

   protected function GetValueOid( $value_set )
   {
      if ( isset( $value_set[ 'value_oid' ] ) ) {
         return $value_set[ 'value_oid' ];
      }
      return "ERROR: No value OID defined.";
   }

   protected function GetValueCode( $value_set )
   {
      if ( isset( $value_set[ 'value_code' ] ) ) {
         return $value_set[ 'value_code' ];
      }
      return "ERROR: No value code defined.";
   }

}

?>
