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

class CKrbw2_1_0 extends CMedBaseExport
{

   protected $_current_uid;

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_current_uid = 0;
      $this->_schema_file = 'feature/exports/scripts/krbw/krbw_export_schema_2_1.xsd';
      $this->_xml_template = 'app/xml.export_krbw_2_1_0.tpl';
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_krbw.conf', 'export_krbw' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#GetVersion()
    */
   public function GetVersion()
   {
      return "2.1.0";
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilter()
    */
   protected function CreateExportFilter( $session, $request )
   {
      $last_id = 0;
      $next_id = 0;
      $export_id = 0;

      $export_filter = array();
      $export_filter[ 'user_id' ] = isset( $session[ 'sess_user_id' ] ) ? $session[ 'sess_user_id' ] : '-1';
      $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
      $export_filter[ 'format_date' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
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
      $export_filter[ 'melder_id' ] = isset( $request[ 'melder_id' ] ) ? $request[ 'melder_id' ] : '-1';
      $export_filter[ 'melder_pruefcode' ] = isset( $request[ 'pruefcode' ] ) ? $request[ 'pruefcode' ] : '-1';
      $export_filter[ 'melder_meldungskennzeichen' ] = isset( $request[ 'meldungskennzeichen' ] ) ? $request[ 'meldungskennzeichen' ] : '-1';
      $query = "
         SELECT
	       IFNULL( MAX( l.export_id ), 1 ) 	   AS last_id,
	       l.createtime						   AS createtime,
	       l.meldungskennzeichen			   AS meldungskennzeichen

         FROM
           exp_krbw_log l

         WHERE
           l.melder_id='{$export_filter[ 'melder_id' ]}'
           AND l.meldung_typ!='patient'

         GROUP BY
           l.createtime

         ORDER BY
           l.createtime DESC

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( ( $result === false ) || ( !is_array( $result ) ) ) {
         $this->_current_uid = 0;
      }
      else {
         $this->_current_uid = $result[ 'last_id' ];
      }
      return $export_filter;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilename()
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = $this->_xml_dir . 'krbw_export_' . date( 'YmdHis' ) . '.xml';
      return $filename;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CheckContent()
    */
   protected function CheckContent( $content, $export_filter )
   {
      $result = array( 'valid' => array(),
      				   'invalid' => array() );
      foreach( $content[ 'patients' ] AS $patient ) {
         //$data = array();
         //$data[ 'melder' ] = $content[ 'melder' ];
         //$data[ 'patients' ][] = $patient;
         //$this->_internal_smarty->assign( 'data', $data );
         //$xml = $this->_internal_smarty->fetch( $this->_xml_template );
         //$errors = $this->xmlSchemaValidate( $xml, $this->_schema_file );
         $errors = array();
         if ( count( $errors ) > 0 ) {
            $result[ 'invalid' ][] = $patient;
         }
         else {
            $result[ 'valid' ][] = $patient;
         }
      }
      $result[ 'melder' ] = $content[ 'melder' ];
      return $result;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#WriteContent()
    */
   protected function WriteContent( $content, $filename, $export_filter )
   {
      $tmp = array();
      if ( !is_array( $content ) || ( !isset( $content[ 'valid' ] ) && !isset( $content[ 'invalid' ] ) ) ) {
         return false;
      }
      $zip_url = "";
      $zip_file = "";
      $xml_file = "";
      $info_keine_daten_exportierbar = $this->_config[ 'info_keine_daten_exportierbar' ];
      if ( count( $content[ 'valid' ] ) > 0 ) {
         $data = array();
         $data[ 'melder' ] = $content[ 'melder' ];
         $data[ 'patients' ] = array();
         foreach( $content[ 'valid' ] AS $patient ) {
            if ( $this->WriteLog( $export_filter, $patient ) ) {
               $data[ 'patients' ][] = $patient;
               $tmp[] = $patient;
            }
         }
         if ( count( $data[ 'patients' ] ) > 0 ) {
             $content = array( 'valid' => $tmp,
                       		   'invalid' => array() );
	         $this->_internal_smarty->assign( 'data', $data );
	         $xml = $this->_internal_smarty->fetch( $this->_xml_template );
	         $xml_file = $filename;
	         file_put_contents( $xml_file, utf8_encode( $xml ) );
	         /*
	         $zip_file = $this->_zip_dir . str_replace( '.xml', '.zip', basename( $xml_file ) );
	         $zip = new PclZip( $zip_file );
	         $zip_create = $zip->create( $xml_file, PCLZIP_OPT_REMOVE_ALL_PATH );
	         */
	         $zip_url = "index.php?page=export_wbc&action=download&type=xml&file=" . $xml_file;
         }
         else {
            $content = array( 'valid' => array(),
                       		  'invalid' => array() );
         }
      }
      $cnt_patient_valid = 0;
      $info_patienten_valid = "";
      $cnt_patient_invalid = 0;
      $info_patienten_invalid = "";
      $result = array( 'valid' => array(),
                       'invalid' => array() );
      foreach( $content AS $type => $erkrankungen ) {
         $i = 0;
         switch( $type ) {
            case 'valid':
               foreach( $erkrankungen AS $erkrankung ) {
                  $result[ $type ][ $i ][ 'patient_id' ] = $erkrankung[ 'patient_id' ];
                  $result[ $type ][ $i ][ 'bez' ] = $this->GetPatientBez( $erkrankung[ 'patient_id' ], $export_filter );
                  $i++;
               }
               $cnt_patient_valid = count( $result[ 'valid' ] );
               $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $this->_config[ 'info_patienten_valid' ] );
               break;

            case 'invalid':
               foreach( $erkrankungen AS $erkrankung ) {
                  $result[ $type ][ $i ][ 'patient_id' ] = $erkrankung[ 'patient_id' ];
                  $result[ $type ][ $i ][ 'bez' ] = $this->GetPatientBez( $erkrankung[ 'patient_id' ], $export_filter );
                  $i++;
               }
               $cnt_patient_invalid = count( $result[ 'invalid' ] );
               $info_patienten_invalid = str_replace( '#anzahl#', $cnt_patient_invalid, $this->_config[ 'info_patienten_invalid' ] );
               break;
         }
      }
      // Template Variablen
      $this->_smarty->assign( array(
            'export_id'                       => 0,
            'cnt_patient_valid'               => $cnt_patient_valid,
            'cnt_patient_invalid'             => $cnt_patient_invalid,
            'info_patienten_invalid'          => $info_patienten_invalid,
            'info_patienten_valid'            => $info_patienten_valid,
            'info_keine_daten_exportierbar'   => $info_keine_daten_exportierbar,
            'result'                          => $result,
      	    'zip_filename'                    => basename( $xml_file ),
            'zip_url'                         => $zip_url
         )
      );
      return true;
   }

   protected function SetHeadData( $export_filter )
   {
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
	        DATE_FORMAT( p.geburtsdatum, '{$export_filter[ 'format_date' ]}' ) AS geburtsdatum

         FROM
            patient p

         WHERE
            p.patient_id=$patient_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . ")";
      return $bez;
   }

   protected function GetMelderData( $export_filter )
   {
      $ansprechpartner_name = isset( $this->_config[ 'exp_krbw_ansprechpartner_name' ] ) ? $this->_config[ 'exp_krbw_ansprechpartner_name' ] : 'Ansprechparner';
      $ansprechpartner_email = isset( $this->_config[ 'exp_krbw_ansprechpartner_email' ] ) ? $this->_config[ 'exp_krbw_ansprechpartner_email' ] : 'demoarzt@alcedis.de';
      $melder = array();
      $melder[ 'id' ] = $export_filter[ 'melder_id' ];
      $melder[ 'pruefcode' ] = $export_filter[ 'melder_pruefcode' ];
      $melder[ 'meldungskennzeichen' ] = $export_filter[ 'melder_meldungskennzeichen' ];
      $melder[ 'ansprechpartner' ] = $ansprechpartner_name;
      if ( strlen( $ansprechpartner_email ) > 0 ) {
         $melder[ 'ansprechpartner' ] .= " (" . $ansprechpartner_email . ")";
      }
      $melder[ 'quellsystem' ] = "Alcedis MED4";
      return $melder;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#ExtractData()
    */
   protected function ExtractData( $export_filter )
   {
      $data = array();
      $query = "
         SELECT
           p.patient_nr,
           ts.patient_id,
           ts.erkrankung_id,
           ts.tumorstatus_id,
           ts.diagnose_seite

         FROM
           tumorstatus ts
           INNER JOIN ekr m        ON ts.erkrankung_id=m.erkrankung_id
                                      AND NOT m.meldebegruendung='zW'
           INNER JOIN erkrankung e ON ts.erkrankung_id=e.erkrankung_id
           INNER JOIN patient p    ON p.patient_id=ts.patient_id
                                      AND p.org_id={$export_filter[ 'org_id' ]}

         WHERE
           ts.anlass='p'
           AND ts.diagnose LIKE 'C%' AND ts.diagnose NOT IN ( 'C97' )
           OR ts.diagnose LIKE 'D0%'
           OR ts.diagnose IN ( 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2', 'D33.3', 'D33.4', 'D33.7', 'D33.9' )
           OR ts.diagnose IN ( 'D35.2', 'D35.3', 'D35.4' )
           OR ts.diagnose LIKE 'D37%' OR ts.diagnose LIKE 'D38%' OR ts.diagnose LIKE 'D39%' OR ts.diagnose LIKE 'D4%'
           OR ts.diagnose IN ( 'D18.02', 'D18.02', 'D18.18', 'D19.7', 'D21.0' )

         ORDER BY
           ts.sicherungsgrad,
           ts.datum_sicherung DESC
      ";
      $result = sql_query_array( $this->_db, $query );
      $data[ 'melder' ] = $this->GetMelderData( $export_filter );
      if ( ( $result !== false ) && ( count( $result ) > 0 ) ) {
         $tumorstatus = array();
         foreach( $result as $record ) {
            $key = $record[ 'erkrankung_id' ];
            if ( !isset( $tumorstatus[ $key ][ $record[ 'diagnose_seite' ] ] ) ) {
               $tumorstatus[ $key ][ $record[ 'diagnose_seite' ] ] = $record;
            }
         }
         foreach( $tumorstatus as $ts ) {
            foreach( $ts as $patient ) {
               $data[ 'patients' ][] = $this->GetPatient( $patient, $export_filter );
            }
         }
      }
      else {
         $data[ 'patients' ] = array();
      }
      return $data;
   }

   /**
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetPatient( $patient, $export_filter )
   {
      $query = "
         SELECT
           p.kv_nr 										AS versichertennr,
           p.patient_nr									AS referenznr,
           e.meldebegruendung							AS unterrichtung,
           p.titel										AS titel,
           p.nachname 									AS nachname,
           p.vorname 									AS vorname,
           p.geburtsname								AS geburtsname,
           DATE_FORMAT( p.geburtsdatum, '%Y-%m-%d' ) 	AS geburtsdatum,
           IFNULL( geschlecht.code_krbw, 'x' )			AS geschlecht,
           p.staat										AS land,
           IF( p.staat IS NULL,
               'X',
               IF( p.staat LIKE 'D',
                   'D',
                   'N' ) )								AS staatsangehoerigkeit,
           p.plz 										AS plz,
           p.ort						 				AS wohnort,
           p.strasse 									AS strasse,
           p.hausnr 									AS hausnr,
           DATE_FORMAT( a.todesdatum, '%Y-%m-%d' ) 		AS sterbedatum

         FROM
           ekr e
           LEFT JOIN patient p                    ON e.patient_id=p.patient_id
           LEFT JOIN l_exp_krbw geschlecht        ON p.geschlecht=geschlecht.code_med
                                                     AND geschlecht.klasse='geschlecht'
           LEFT JOIN abschluss a				  ON p.patient_id=a.patient_id

         WHERE
           e.erkrankung_id={$patient[ 'erkrankung_id' ]}

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
	  $result[ 'diagnosen' ] = $this->GetDiagnosen( $patient, $export_filter );
	  $result[ 'therapien' ] = $this->GetTherapien( $patient, $export_filter );
	  $result[ 'verlaeufe' ] = $this->GetVerlaeufe( $patient, $export_filter );
	  $result[ 'abschluesse' ] = $this->GetAbschluesse( $patient, $export_filter );
      $result[ 'patient_id' ] = $patient[ 'patient_id' ];
      $result[ 'erkrankung_id' ] = $patient[ 'erkrankung_id' ];
      $result[ 'tumorstatus_id' ] = $patient[ 'tumorstatus_id' ];
      $result[ 'diagnose_seite' ] = $patient[ 'diagnose_seite' ];
      return $result;
   }

   /*
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetDiagnosen( $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'DIAG_', ts.tumorstatus_id )				AS dbid,
           DATE_FORMAT( ts_dd.datum_sicherung, '%Y-%m' )	AS erstdiagnosedatum,
           ts.tumorstatus_id								AS tumoridentifikator,
           ts.diagnose										AS diagnose,
           ts.lokalisation									AS lokalisation,
           '10'												AS revision_icd,
           ts.diagnose_seite								AS seitenlokalisation,
           '3'												AS icd_o_version,
           ts.morphologie									AS histologie

         FROM
           tumorstatus ts
           LEFT JOIN ( SELECT
           				   ts_i.erkrankung_id,
                           ts_i.datum_sicherung

                        FROM
                           tumorstatus ts_i

                        ORDER BY
                           ts_i.datum_sicherung ASC

                     ) ts_dd ON ts_dd.erkrankung_id=ts.erkrankung_id

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}

		 LIMIT 0, 1
      ";
      $result = sql_query_array( $this->_db, $query );
      return $result;
   }

   /*
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetTherapien( $patient, $export_filter )
   {
      $result = array();
      $this->GetSystemischeTherapien( $result, $patient, $export_filter );
      $this->GetStrahlentherapien( $result, $patient, $export_filter );
      $this->GetSonstigeTherapien( $result, $patient, $export_filter );
      $this->GetEingriffe( $result, $patient, $export_filter );
      return $result;
   }

   /*
    *
    * @param array &$result
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetSystemischeTherapien( &$result, $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'ME_', t_sys.therapie_systemisch_id )	AS dbid,
           ts.tumorstatus_id								AS tumoridentifikator,
           'ME'												AS therapieart,
           medikamentoese_therapie.code_krbw				AS medikamentoese_therapie,
           NULL												AS strahlentherapie,
           NULL												AS sonstige_therapie,
           DATE_FORMAT( t_sys.beginn, '%Y-%m-%d' )			AS therapie_start,
           DATE_FORMAT( t_sys.ende, '%Y-%m-%d' )			AS therapie_ende

         FROM
           tumorstatus ts
           INNER JOIN therapie_systemisch t_sys 		ON t_sys.erkrankung_id=ts.erkrankung_id
           LEFT JOIN l_exp_krbw medikamentoese_therapie ON t_sys.vorlage_therapie_art=medikamentoese_therapie.code_med
                                                     	   AND medikamentoese_therapie.klasse='medikamentoese_therapie'

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
      ";
      $rc = sql_query_array( $this->_db, $query );
      foreach( $rc as $row ) {
         $result[] = $row;
      }
   }

   /*
    *
    * @param array &$result
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetStrahlentherapien( &$result, $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'ST_', s.strahlentherapie_id )		AS dbid,
           ts.tumorstatus_id							AS tumoridentifikator,
           'ST'											AS therapieart,
           NULL											AS medikamentoese_therapie,
           strahlentherapie.code_krbw					AS strahlentherapie,
           NULL											AS sonstige_therapie,
           DATE_FORMAT( s.beginn, '%Y-%m-%d' )			AS therapie_start,
           DATE_FORMAT( s.ende, '%Y-%m-%d' )			AS therapie_ende

         FROM
           tumorstatus ts
           INNER JOIN strahlentherapie s 				ON s.erkrankung_id=ts.erkrankung_id
           LEFT JOIN l_exp_krbw strahlentherapie 		ON s.art=strahlentherapie.code_med
                                                     	   AND strahlentherapie.klasse='strahlentherapie'

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
      ";
      $rc = sql_query_array( $this->_db, $query );
      foreach( $rc as $row ) {
         $result[] = $row;
      }
   }

   /*
    *
    * @param array &$result
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetSonstigeTherapien( &$result, $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'SO', ts.tumorstatus_id )		AS tumoridentifikator,
           'SO'										AS therapieart,
           NULL										AS medikamentoese_therapie,
           NULL										AS strahlentherapie,
           'S'										AS sonstige_therapie,
           DATE_FORMAT( son.beginn, '%Y-%m-%d' )	AS therapie_start,
           DATE_FORMAT( son.ende, '%Y-%m-%d' )		AS therapie_ende

         FROM
           tumorstatus ts
           INNER JOIN sonstige_therapie son			ON son.erkrankung_id=ts.erkrankung_id

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
      ";
      $rc = sql_query_array( $this->_db, $query );
      foreach( $rc as $row ) {
         $result[] = $row;
      }
   }

/*
    *
    * @param array &$result
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetEingriffe( &$result, $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'OP_', ein.eingriff_id )		AS dbid,
           ts.tumorstatus_id					AS tumoridentifikator,
           'OP'									AS therapieart,
           NULL									AS medikamentoese_therapie,
           NULL									AS strahlentherapie,
           NULL									AS sonstige_therapie,
           DATE_FORMAT( ein.datum, '%Y-%m-%d' )	AS therapie_start,
           NULL									AS therapie_ende,
           ein.eingriff_id						AS eingriff_id

         FROM
           tumorstatus ts
           INNER JOIN eingriff ein				ON ein.erkrankung_id=ts.erkrankung_id

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
      ";
      $rc = sql_query_array( $this->_db, $query );
      foreach( $rc as $row ) {
          $row[ 'ops_codes' ] = $this->GetOpsCodes( $row[ 'eingriff_id' ], $export_filter );
	      $result[] = $row;
      }
   }

   /*
    *
    * @param array $eingriff_id
    * @param array $export_filter
    * @return array
    */
   protected function GetOpsCodes( $eingriff_id, $export_filter )
   {
      $query = "
         SELECT
           ein.prozedur 	AS schluessel

         FROM
           eingriff_ops ein

         WHERE
           ein.eingriff_id=$eingriff_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result === false ) || ( !is_array( $result ) ) ) {
         $result = array();
      }
      return $result;
   }

   /*
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetVerlaeufe( $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'VER_', n.nachsorge_id )		AS dbid,
           ts.tumorstatus_id					AS tumoridentifikator,
           DATE_FORMAT( n.datum, '%Y-%m-%d' )	AS untersuchungsdatum,
           n.response_klinisch					AS tumorgeschehen

         FROM
           tumorstatus ts
           INNER JOIN nachsorge_erkrankung n_e ON n_e.erkrankung_weitere_id=ts.erkrankung_id
           INNER JOIN nachsorge n ON n.nachsorge_id=n_e.nachsorge_id

         WHERE
           ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
      ";
      $result = sql_query_array( $this->_db, $query );
      return $result;
   }

   /*
    *
    * @param array $patient
    * @param array $export_filter
    * @return array
    */
   protected function GetAbschluesse( $patient, $export_filter )
   {
      $query = "
         SELECT
           CONCAT( 'AB_', a.abschluss_id )				AS dbid,
           ts.tumorstatus_id							AS tumoridentifikator,
           abschluss_grund.code_krbw					AS abschlussgrund,
           DATE_FORMAT( a.todesdatum, '%Y-%m-%d' )		AS sterbedatum,
           tod_tumorbedingt.code_krbw					AS tod_tumorbedingt,
           IF( a.abschluss_grund='lost',
           	   NULL,
           	   NULL ) 									AS letzte_patienteninformation

         FROM
           patient p
           INNER JOIN abschluss a 						ON a.patient_id=p.patient_id
           INNER JOIN tumorstatus ts					ON ts.tumorstatus_id={$patient[ 'tumorstatus_id' ]}
           LEFT JOIN l_exp_krbw abschluss_grund 		ON a.abschluss_grund=abschluss_grund.code_med
                                                     	   AND abschluss_grund.klasse='abschluss_grund'
           LEFT JOIN l_exp_krbw tod_tumorbedingt 		ON a.tod_tumorassoziation=tod_tumorbedingt.code_med
                                                     	   AND tod_tumorbedingt.klasse='tod_tumorbedingt'

         WHERE
           p.patient_id={$patient[ 'patient_id' ]}
      ";
      $result = sql_query_array( $this->_db, $query );
      return $result;
   }

   protected function WriteLog( $export_filter, &$patient )
   {
      $patienten_stammdaten_geaendert = false;

      $serial = serialize( $patient );
      $db_serial = $this->GetLogMeldungDaten( $export_filter[ 'melder_id' ],
			                                  $patient[ 'patient_id' ],
			                                  $patient[ 'erkrankung_id' ],
			                                  $patient[ 'diagnose_seite' ],
			                                  'patient', 'PAT_' . $patient[ 'patient_id' ] );
      if ( $db_serial === false ) {
         $this->PutLog( 0, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                        $export_filter[ 'melder_id' ], '', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                        'patient', 'PAT_' . $patient[ 'patient_id' ], $serial );
      }
      else {
         if ( $db_serial != $serial ) {
            $this->PutLog( 0, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], '', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'patient', 'PAT_' . $patient[ 'patient_id' ], $serial );
            $patienten_stammdaten_geaendert = true;
         }
      }

      // Alle Diagnosen bearbeiten...
      $tmp = array();
      foreach( $patient[ 'diagnosen' ] as $index => $diagnose ) {
         $serial = serialize( $diagnose );
         $db_serial = $this->GetLogMeldungDaten( $export_filter[ 'melder_id' ],
			                                     $patient[ 'patient_id' ],
			                                     $patient[ 'erkrankung_id' ],
			                                     $patient[ 'diagnose_seite' ],
			                                     'diagnose', $diagnose[ 'dbid' ] );
         if ( !$db_serial ) {
            // Hoch kein Eintrag, also noch nicht exportiert...
            $this->_current_uid++;
            $this->PutLog( $this->_current_uid, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'N', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'diagnose', $diagnose[ 'dbid' ], $serial );
            $patient[ 'diagnosen' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $this->_current_uid );
            $patient[ 'diagnosen' ][ $index ][ 'meldungskennzeichen' ] = 'N';
         }
         else if ( $db_serial != $serial ) {
            // Diagnose hat sich geändert...
            $log_date = $this->GetLogExportId( $export_filter[ 'melder_id' ],
			                                   $patient[ 'patient_id' ],
			                                   $patient[ 'erkrankung_id' ],
			                                   $patient[ 'diagnose_seite' ],
			                                   'diagnose', $diagnose[ 'dbid' ] );

            $this->_current_uid++;
            $this->PutLog( $this->_current_uid, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'W', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'diagnose', $diagnose[ 'dbid' ], $serial );
            $patient[ 'diagnosen' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $this->_current_uid );
            $patient[ 'diagnosen' ][ $index ][ 'meldungskennzeichen' ] = 'W';
         }
         else {
            // Diagnose ist gleich geblieben, nicht exportieren...
            $tmp[] = $index;
         }
      }
      foreach( $tmp as $index ) {
         unset( $patient[ 'diagnosen' ][ $index ] );
      }

      // Alle Therapien bearbeiten...
      $tmp = array();
      foreach( $patient[ 'therapien' ] as $index => $therapie ) {
         $serial = serialize( $therapie );
         $db_serial = $this->GetLogMeldungDaten( $export_filter[ 'melder_id' ],
			                                     $patient[ 'patient_id' ],
			                                     $patient[ 'erkrankung_id' ],
			                                     $patient[ 'diagnose_seite' ],
			                                     'therapie', $therapie[ 'dbid' ] );
         if ( !$db_serial ) {
            // Hoch kein Eintrag, also noch nicht exportiert...
            $this->_current_uid++;
            $this->PutLog( $this->_current_uid, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'N', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'therapie', $therapie[ 'dbid' ], $serial );
            $patient[ 'therapien' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $this->_current_uid );
            $patient[ 'therapien' ][ $index ][ 'meldungskennzeichen' ] = 'N';
         }
         else if ( $db_serial != $serial ) {
            // Thearpie hat sich geändert...
            $tan_id = $this->GetLogExportId( $export_filter[ 'melder_id' ],
			                                 $patient[ 'patient_id' ],
			                                 $patient[ 'erkrankung_id' ],
			                                 $patient[ 'diagnose_seite' ],
			                                 'therapie', $therapie[ 'dbid' ] );
            if ( !$tan_id ) {
               $tan_id = 0;
            }
            $this->PutLog( $tan_id, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'A', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'therapie', $therapie[ 'dbid' ], $serial );
            $patient[ 'therapien' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $tan_id );
            $patient[ 'therapien' ][ $index ][ 'meldungskennzeichen' ] = 'A';
         }
         else {
            // Diagnose ist gleich geblieben, nicht exportieren...
            $tmp[] = $index;
         }
      }
      foreach( $tmp as $index ) {
         unset( $patient[ 'therapien' ][ $index ] );
      }

      // Alle Verläufe bearbeiten...
      $tmp = array();
      foreach( $patient[ 'verlaeufe' ] as $index => $verlauf ) {
         $serial = serialize( $verlauf );
         $db_serial = $this->GetLogMeldungDaten( $export_filter[ 'melder_id' ],
			                                     $patient[ 'patient_id' ],
			                                     $patient[ 'erkrankung_id' ],
			                                     $patient[ 'diagnose_seite' ],
			                                     'verlauf', $verlauf[ 'dbid' ] );
         if ( !$db_serial ) {
            // Hoch kein Eintrag, also noch nicht exportiert...
            $this->_current_uid++;
            $this->PutLog( $this->_current_uid, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'N', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'verlauf', $verlauf[ 'dbid' ], $serial );
            $patient[ 'verlaeufe' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $this->_current_uid );
            $patient[ 'verlaeufe' ][ $index ][ 'meldungskennzeichen' ] = 'N';
         }
         else if ( $db_serial != $serial ) {
            // Verlauf hat sich geändert...
            $tan_id = $this->GetLogExportId( $export_filter[ 'melder_id' ],
			                                 $patient[ 'patient_id' ],
			                                 $patient[ 'erkrankung_id' ],
			                                 $patient[ 'diagnose_seite' ],
			                                 'verlauf', $verlauf[ 'dbid' ] );
            if ( !$tan_id ) {
               $tan_id = 0;
            }
            $this->PutLog( $tan_id, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'A', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'verlauf', $verlauf[ 'dbid' ], $serial );
            $patient[ 'verlaeufe' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $tan_id );
            $patient[ 'verlaeufe' ][ $index ][ 'meldungskennzeichen' ] = 'A';
         }
         else {
            // Diagnose ist gleich geblieben, nicht exportieren...
            $tmp[] = $index;
         }
      }
      foreach( $tmp as $index ) {
         unset( $patient[ 'verlaeufe' ][ $index ] );
      }

      // Alle Abschlüsse bearbeiten...
      $tmp = array();
      foreach( $patient[ 'abschluesse' ] as $index => $abschluss ) {
         $serial = serialize( $abschluss );
         $db_serial = $this->GetLogMeldungDaten( $export_filter[ 'melder_id' ],
			                                     $patient[ 'patient_id' ],
			                                     $patient[ 'erkrankung_id' ],
			                                     $patient[ 'diagnose_seite' ],
			                                     'abschluss', $abschluss[ 'dbid' ] );
         if ( !$db_serial ) {
            // Hoch kein Eintrag, also noch nicht exportiert...
            $this->_current_uid++;
            $this->PutLog( $this->_current_uid, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'N', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'abschluss', $abschluss[ 'dbid' ], $serial );
            $patient[ 'abschluesse' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $this->_current_uid );
            $patient[ 'abschluesse' ][ $index ][ 'meldungskennzeichen' ] = 'N';
         }
         else if ( $db_serial != $serial ) {
            // Thearpie hat sich geändert...
            $tan_id = $this->GetLogExportId( $export_filter[ 'melder_id' ],
			                                 $patient[ 'patient_id' ],
			                                 $patient[ 'erkrankung_id' ],
			                                 $patient[ 'diagnose_seite' ],
			                                 'abschluss', $abschluss[ 'dbid' ] );
            if ( !$tan_id ) {
               $tan_id = 0;
            }
            $this->PutLog( $tan_id, $export_filter[ 'org_id' ], $export_filter[ 'user_id' ],
                           $export_filter[ 'melder_id' ], 'A', $patient[ 'patient_id' ], $patient[ 'erkrankung_id' ], $patient[ 'diagnose_seite' ],
                           'abschluss', $abschluss[ 'dbid' ], $serial );
            $patient[ 'abschluesse' ][ $index ][ 'tan' ] = $export_filter[ 'melder_id' ] . sprintf( "%07d", $tan_id );
            $patient[ 'abschluesse' ][ $index ][ 'meldungskennzeichen' ] = 'A';
         }
         else {
            // Diagnose ist gleich geblieben, nicht exportieren...
            $tmp[] = $index;
         }
      }
      foreach( $tmp as $index ) {
         unset( $patient[ 'abschluesse' ][ $index ] );
      }
      if ( !$patienten_stammdaten_geaendert &&
           ( count( $patient[ 'diagnosen' ] ) == 0 ) &&
           ( count( $patient[ 'therapien' ] ) == 0 ) &&
           ( count( $patient[ 'verlaeufe' ] ) == 0 ) &&
           ( count( $patient[ 'abschluesse' ] ) == 0 ) ) {
         return false;
      }
      return true;
   }

   protected function PutLog( $export_id, $org_id, $user_id,
                              $melder_id, $melder_meldungskennzeichen, $patient_id, $erkrankung_id, $diagnose_seite,
                              $meldung_typ, $meldung_dbid, $meldung_daten ) {
      $query = "
      	 INSERT INTO exp_krbw_log
            VALUES ( '',
                     $export_id,
                     $patient_id,
                     $erkrankung_id,
      				 '$diagnose_seite',
      				 '$melder_id',
      				 '$melder_meldungskennzeichen',
      				 '$meldung_typ',
      				 '$meldung_dbid',
      				 '$meldung_daten',
                     1,
                     '',
                     $org_id,
                     '',
                     '',
                     '$user_id',
                     '" . date( 'Y-m-d H:i:s' ) . "'
                   )";
      $result = mysql_query( $query, $this->_db );
      if ( !$result ) {
         die('Ungültige Anfrage: ' . mysql_error());
      }
      return $result;
   }

   protected function GetLog( $melder_id, $patient_id, $erkrankung_id,
                              $diagnose_seite, $meldung_typ, $meldung_dbid )
   {
      $query = "
      	 SELECT
            *

         FROM
            exp_krbw_log

         WHERE
            patient_id=$patient_id
            AND erkrankung_id=$erkrankung_id
            AND diagnose_seite='$diagnose_seite'
            AND melder_id=$melder_id
            AND meldung_dbid='$meldung_dbid'
            AND meldung_typ='$meldung_typ'

         ORDER BY
            createtime DESC

         LIMIT 0, 1
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result !== false ) &&
           is_array( $result ) &&
           ( count( $result ) == 1 ) ) {
         return $result[ 0 ];
      }
      return false;
   }

   protected function GetLogMeldungDaten( $melder_id, $patient_id, $erkrankung_id,
                                          $diagnose_seite, $meldung_typ, $meldung_dbid )
   {
      $result = $this->GetLog( $melder_id, $patient_id, $erkrankung_id,
                               $diagnose_seite, $meldung_typ, $meldung_dbid );
      if ( $result !== false ) {
         return $result[ 'meldung_daten' ];
      }
      return false;
   }

   protected function GetLogExportId( $melder_id, $patient_id, $erkrankung_id,
                                      $diagnose_seite, $meldung_typ, $meldung_dbid )
   {
      $result = $this->GetLog( $melder_id, $patient_id, $erkrankung_id,
                               $diagnose_seite, $meldung_typ, $meldung_dbid );
      if ( $result !== false ) {
         return $result[ 'export_id' ];
      }
      return false;
   }

   protected function GetLogDate( $melder_id, $patient_id, $erkrankung_id,
                                  $diagnose_seite, $meldung_typ, $meldung_dbid )
   {
      $result = $this->GetLog( $melder_id, $patient_id, $erkrankung_id,
                               $diagnose_seite, $meldung_typ, $meldung_dbid );
      if ( $result !== false ) {
         return $result[ 'createtime' ];
      }
      return false;
   }

}

?>
