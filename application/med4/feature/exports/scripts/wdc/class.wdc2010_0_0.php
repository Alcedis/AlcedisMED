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

class CWdc2010_0_0 extends CMedBaseExport
{

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_schema_file = 'feature/exports/scripts/wdc/ColonCa_2010.xsd';
      $this->_xml_template = 'app/xml.export_wdc_2010_0.tpl';
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_wdc.conf', 'export_wdc' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#GetVersion()
    */
   public function GetVersion()
   {
      return "2010.0";
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilter()
    */
   protected function CreateExportFilter( $session, $request )
   {
      $export_filter = array();
      $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
      $export_filter[ 'format_date' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $ext_wdc_dir = isset( $this->_config[ 'exp_wdc_dir' ] ) ? $this->_config[ 'exp_wdc_dir' ] : 'wdc/';
      $ext_wdc_log_subdir = isset( $this->_config[ 'exp_wdc_log_subdir' ] ) ? $this->_config[ 'exp_wdc_log_subdir' ] : 'log/';
      $ext_wdc_tmp_subdir = isset( $this->_config[ 'exp_wdc_tmp_subdir' ] ) ? $this->_config[ 'exp_wdc_tmp_subdir' ] : 'tmp/';
      $ext_wdc_xml_subdir = isset( $this->_config[ 'exp_wdc_xml_subdir' ] ) ? $this->_config[ 'exp_wdc_xml_subdir' ] : 'xml/';
      $ext_wdc_zip_subdir = isset( $this->_config[ 'exp_wdc_zip_subdir' ] ) ? $this->_config[ 'exp_wdc_zip_subdir' ] : 'zip/';
      $this->_export_path = $this->GetExportPath( $ext_wdc_dir, $export_filter[ 'login_name' ] );
      if ( file_exists( $this->_export_path ) ) {
         $this->DeleteDirectory( $this->_export_path );
      }
      $this->_log_dir = $this->_export_path . $ext_wdc_log_subdir;
      $this->_tmp_dir = $this->_export_path . $ext_wdc_tmp_subdir;
      $this->_xml_dir = $this->_export_path . $ext_wdc_xml_subdir;
      $this->_zip_dir = $this->_export_path . $ext_wdc_zip_subdir;
      // Pfade anlegen
      $this->createPath( $this->_log_dir );
      $this->createPath( $this->_tmp_dir );
      $this->createPath( $this->_xml_dir );
      $this->createPath( $this->_zip_dir );
      $export_filter[ 'org_id' ] = $session[ 'sess_org_id' ];
      $export_filter[ 'von' ] = isset( $request[ 'sel_datum_von' ] ) ? todate( $request[ 'sel_datum_von' ], 'en' ) : '';
      $export_filter[ 'bis' ] = isset( $request[ 'sel_datum_bis' ] ) ? todate( $request[ 'sel_datum_bis' ], 'en' ) : '';
      // export_id bestimmen
      $export_filter[ 'export_id' ] = dlookup( $this->_db, 'exp_wdc_log', 'IFNULL( MAX( export_id ) + 1, 1 )', '1' );
      return $export_filter;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilename()
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = $this->_xml_dir . 'wdc_export_' . date( 'YmdHis' ) . '.xml';
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
      $this->SetHeadData( $export_filter );
      foreach( $content AS $patient ) {
         $data = array();
         $data[] = $patient;
         $this->_internal_smarty->assign( 'data', $data );
         $xml = $this->_internal_smarty->fetch( $this->_xml_template );
         $errors = $this->xmlSchemaValidate( $xml, $this->_schema_file );
         // Log in DB schreiben
         $query = '
            INSERT INTO exp_wdc_log
               VALUES ( "",
                        "' . $export_filter[ 'export_id' ]   . '",
                        "' . $patient[ 'patient_id' ]        . '",
                        "' . $patient[ 'erkrankung_id' ]     . '",
      					"' . $patient[ 'diagnose_seite' ]    . '",
                        "' . ( int )!count( $errors )        . '",
                        "' . implode( '', $errors )          . '",
                        "' . $export_filter[ 'org_id' ]      . '",
                        "' . $export_filter[ 'von' ]         . '",
                        "' . $export_filter[ 'bis' ]         . '",
                        "' . $export_filter[ 'login_name' ]  . '",
                        "' . date('Y-m-d H:i:s')             . '"
               )
         ';
         $erg = mysql_query( $query, $this->_db );
         if ( count( $errors ) > 0 ) {
            $result[ 'invalid' ][] = $patient;
         }
         else {
            $result[ 'valid' ][] = $patient;
         }
      }
      return $result;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#WriteContent()
    */
   protected function WriteContent( $content, $filename, $export_filter )
   {
      if ( !is_array( $content ) || ( !isset( $content[ 'valid' ] ) && !isset( $content[ 'invalid' ] ) ) ) {
         return false;
      }
      $zip_url = "";
      $zip_file = "";
      if ( count( $content[ 'valid' ] ) > 0 ) {
         $this->SetHeadData( $export_filter );
         foreach( $content[ 'valid' ] AS $patient ) {
            $data[] = $patient;
         }
         $this->_internal_smarty->assign( 'data', $data );
         $xml = $this->_internal_smarty->fetch( $this->_xml_template );
         $xml_file = $filename;
         file_put_contents( $xml_file, utf8_encode( $xml ) );
         $zip_file = $this->_zip_dir . str_replace( '.xml', '.zip', basename( $xml_file ) );
         $zip = new PclZip( $zip_file );
         $zip_create = $zip->create( $xml_file, PCLZIP_OPT_REMOVE_ALL_PATH );
         if ( $zip_create ) {
            unlink( $xml_file );
         }
         $zip_url = "index.php?page=export_wdc&action=download&type=zip&file=" . $zip_file;
      }
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
            'export_id'              => $export_filter[ 'export_id' ],
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

   protected function SetHeadData( $export_filter )
   {
      $datenexport = array();

      $datenexport[ 'klinik_id' ] = isset( $this->_config[ 'exp_wdc_klinik_id' ] ) ? $this->_config[ 'exp_wdc_klinik_id' ] : '';
      $datenexport[ 'exportdatum' ] = date( 'Y-m-d' );
      $datenexport[ 'exportzeitraum_beginn' ] = $export_filter[ 'von' ];
      $datenexport[ 'exportzeitraum_ende' ] = $export_filter[ 'bis' ];
      $this->_internal_smarty->assign( 'datenexport', $datenexport );
      $sw = array();
      $sw[ 'hersteller' ] = $this->_config[ 'org_name' ];
      $sw[ 'name' ] = dlookup($this->_db, 'settings', 'software_title', 'settings_id = 1');
      $sw[ 'version' ] = dlookup($this->_db, 'settings', 'software_version', 'settings_id = 1');
      $this->_internal_smarty->assign( 'sw', $sw );
      $ansprechpartner = array();
      $ansprechpartner[ 'name' ] = $this->_config[ 'exp_wdc_ansprechpartner_name' ];
      $ansprechpartner[ 'mail' ] = $this->_config[ 'exp_wdc_ansprechpartner_email' ];
      $this->_internal_smarty->assign( 'ansprechpartner', $ansprechpartner );
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

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#ExtractData()
    */
   protected function ExtractData( $export_filter )
   {
      $data = array();
      $query = "
         SELECT
           ts.patient_id,
           ts.tumorstatus_id,
           ts.erkrankung_id,
           ts.diagnose_seite

         FROM
           tumorstatus ts
           	INNER JOIN erkrankung e ON ts.erkrankung_id=e.erkrankung_id
           							   AND e.erkrankung='b'
           							   AND ts.datum_sicherung BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'
           	INNER JOIN patient p    ON p.patient_id=ts.patient_id
                                       AND p.org_id={$export_filter[ 'org_id' ]}

         WHERE
           ts.anlass='p'

         ORDER BY
           ts.sicherungsgrad,
           ts.datum_sicherung DESC
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result !== false ) && ( count( $result ) > 0 ) ) {
         $tumorstatus = array();
         foreach( $result as $record ) {
             $key = $record[ 'erkrankung_id' ] . $record[ 'diagnose_seite' ];
             if ( !isset( $tumorstatus[ $key ] ) ) {
                 $tumorstatus[ $key ] = $record;
             }
         }
         $patients = array();
         foreach( $tumorstatus as $ts ) {
            $key = $ts[ "patient_id" ];
            $patients[ $key ][] = $ts;
         }
         // Wenn keine Daten verfügbar sind, hier raus springen
         if ( !count( $patients ) ) {
            return array();
         }
         foreach( $patients AS $patient_id => $patient ) {
            $data[] = $this->GetPatient( $patient, $export_filter );
         }
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
      $result = array();
      $result[ 'patient_data' ] = $this->GetPatientData( $patient[ 0 ][ 'patient_id' ] );
      foreach( $patient as $data ) {
         $result[ 'faelle' ][] = $this->GetCase( $data, $export_filter );
      }
      $result[ 'patient_id' ] = $patient[ 0 ][ 'patient_id' ];
      $result[ 'erkrankung_id' ] = $patient[ 0 ][ 'erkrankung_id' ];
      $result[ 'diagnose_seite' ] = $patient[ 0 ][ 'diagnose_seite' ];
      return $result;
   }

   /**
    *
    * @param int $patient_id
    * @return array
    */
   protected function GetPatientData( $patient_id )
   {
      $query = "
         SELECT
            p.patient_nr                                    AS patient_id,
            DATE_FORMAT( p.geburtsdatum, '%Y-%m-%d' )       AS geburtstag,
            IFNULL( geschlecht.code_wdc, 'x' )              AS geschlecht,
            DATE_FORMAT( abschluss.todesdatum, '%Y-%m-%d' ) AS todesdatum

         FROM
            patient p
            LEFT JOIN l_exp_wdc geschlecht ON p.geschlecht=geschlecht.code_med
                                              AND geschlecht.klasse='geschlecht'
            LEFT JOIN abschluss abschluss  ON abschluss.patient_id=p.patient_id

         WHERE
            p.patient_id=$patient_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @param array $export_filter
    * @return array
    */
   protected function GetCase( $data, $export_filter )
   {
      $case = array();
      $case_data = $this->GetCaseData( $data[ 'tumorstatus_id' ], $data[ 'diagnose_seite' ] );
      foreach( $case_data as $key => $value ) {
         $case[ $key ] = $value;
      }
      $case[ 'studien' ] = $this->GetStudien( $data[ 'erkrankung_id' ], $data[ 'patient_id' ] );
      $case[ 'diagnosen' ] = $this->GetDiagnosen( $data, $export_filter );
      $case[ 'therapien' ] = $this->GetTherapien( $data );
      $case[ 'histologien' ] = $this->GetHistologien( $data );
      $case[ 'labore' ] = $this->GetLabore( $data );
      $case[ 'nachsorgen' ] = $this->GetNachsorgen( $data );
      return $case;
   }

   /**
    *
    * @param int $tumorstatus_id
    * @return array
    */
   protected function GetCaseData( $tumorstatus_id )
   {
      $query = "
         SELECT
            seite.code_wdc                                							AS seite,
			CONCAT( ts.erkrankung_id, ts.diagnose_seite ) 							AS fall_id,
			'2'                                           							AS therapieabbruch,
			DATE_FORMAT( IFNULL(MIN( h.datum ), ts.datum_sicherung), '%Y-%m-%d' )   AS fall_beginn

         FROM
            tumorstatus ts
            INNER JOIN l_exp_wdc seite ON ts.diagnose_seite=seite.code_med
                                          AND seite.klasse='seite'
            LEFT JOIN histologie h     ON h.erkrankung_id=ts.erkrankung_id
                                          AND h.diagnose_seite=ts.diagnose_seite

         WHERE
            ts.tumorstatus_id=$tumorstatus_id

         GROUP BY
            h.datum
      ";
      $case_data = end( sql_query_array( $this->_db, $query ) );
      if ( $case_data === false ) {
         $case_data = array( "seite" => "", "fall_id" => "", "therapieabbruch" => "" );
      }
      return $case_data;
   }

   /**
    *
    * @param int $erkrankung_id
    * @param int $patient_id
    * @return array
    */
   protected function GetStudien( $erkrankung_id, $patient_id )
   {
      $query = "
         SELECT
            IF( count( s.studie_id ) > 0, '1', '0' ) AS studienteilnehmer,
	        vs.bez                                   AS studien_name,
            DATE_FORMAT( s.beginn, '%Y-%m-%d' )      AS datum_einschluss,
            IF( count( s.studie_id ) > 0, '0', '6' ) AS keine_studie

         FROM
            studie s
            INNER JOIN vorlage_studie vs ON s.vorlage_studie_id=vs.vorlage_studie_id

         WHERE
            s.erkrankung_id=$erkrankung_id AND s.patient_id=$patient_id

         GROUP BY
            s.studie_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result[ 0 ][ 'studienteilnehmer' ] = '0';
         $result[ 0 ][ 'studien_name' ] = '';
         $result[ 0 ][ 'datum_einschluss' ] = '';
         $result[ 0 ][ 'keine_studie' ] = '6';
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @param array $export_filter
    * @return array
    */
   protected function GetDiagnosen( $data, $export_filter )
   {
      $diagnosen = array();
      $query = "
         SELECT
            '1' AS rezidiv

         FROM
            tumorstatus ts
            INNER JOIN tumorstatus tsp ON	tsp.tumorstatus_id={$data[ 'tumorstatus_id' ]}
            								AND tsp.erkrankung_id=ts.erkrankung_id
            								AND tsp.diagnose_seite=ts.diagnose_seite
         WHERE
            LEFT( ts.anlass, 1 )='r'
         GROUP BY
         	tsp.tumorstatus_id
      ";
      $result = end(sql_query_array( $this->_db, $query ));
      if ( $result !== false ) {
         foreach( $result as $key => $value ) {
            $diagnosen[ 0 ][ $key ] = $value;
         }
      }
      else {
         $diagnosen[ 0 ][ 'rezidiv' ] = '0';
      }

      $query = "
         SELECT
            DATE_FORMAT( ts.datum_sicherung, '%Y-%m-%d' ) AS diag_datum,
            IF( ts.mikrokalk='1', '1', '0' )              AS mikrokalk

         FROM
            tumorstatus ts

         WHERE
            ts.tumorstatus_id={$data[ 'tumorstatus_id' ]}
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      foreach( $result as $key => $value ) {
         $diagnosen[ 0 ][ $key ] = $value;
      }
      $diagnosen[ 0 ][ 'biopsie_bildgebende_kontrolle' ] = '6';
      if ( isset( $diagnosen[ 0 ][ 'biopsie_methode' ] ) && ( strlen( $diagnosen[ 0 ][ 'biopsie_methode' ] ) > 0 ) ) {
         $diagnosen[ 0 ][ 'biopsie_bildgebende_kontrolle' ] = '5';
      }

      $query = "
		 (
         	 SELECT
               CASE
                  WHEN MAX( eoa.prozedur ) IS NOT NULL THEN '1'
                  WHEN MAX( eob.prozedur ) IS NOT NULL THEN '3'
                  WHEN MAX( eoc.prozedur ) IS NOT NULL THEN '4'
                  WHEN MAX( eod.prozedur ) IS NOT NULL THEN '5'
                  ELSE '9'
               END                                                AS biopsie_methode,
               DATE_FORMAT( e.datum, '%Y-%m-%d' )                 AS biopsie_datum

            FROM
               eingriff e
               LEFT JOIN eingriff_ops eoa ON eoa.eingriff_id=e.eingriff_id
                                             AND eoa.prozedur='1-e03x'
               LEFT JOIN eingriff_ops eob ON eob.eingriff_id=e.eingriff_id
                                             AND eob.prozedur='1-e02x'
               LEFT JOIN eingriff_ops eoc ON eoc.eingriff_id=e.eingriff_id
                                             AND LEFT( eoc.prozedur, 5 )='1-493'
               LEFT JOIN eingriff_ops eod ON eod.eingriff_id=e.eingriff_id
                                             AND eod.prozedur='5-870.0'
            WHERE
               e.erkrankung_id={$data[ 'erkrankung_id' ]}
               AND e.diagnose_seite='{$data[ 'diagnose_seite' ]}'
               AND e.art_diagnostik='1'

            GROUP BY
               e.eingriff_id,
               eoa.prozedur,
               eob.prozedur,
               eoc.prozedur,
               eod.prozedur
		 ) UNION (
         	 SELECT
               '6'                                                AS biopsie_methode,
               DATE_FORMAT( h.datum, '%Y-%m-%d' )                 AS biopsie_datum

            FROM
               histologie h
            WHERE
               h.erkrankung_id={$data[ 'erkrankung_id' ]}
               AND h.diagnose_seite='{$data[ 'diagnose_seite' ]}'
               AND h.art='pr'
		 )
         ORDER BY
            biopsie_datum ASC

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( $result !== false ) {
         foreach( $result as $key => $value ) {
            $diagnosen[ 0 ][ $key ] = $value;
         }
      }
      else {
         $diagnosen[ 0 ][ 'biopsie_methode' ] = '7';
         $diagnosen[ 0 ][ 'biopsie_datum' ] = '';
      }

      // Get Klassifikation
      $query = "
         SELECT
            ts.morphologie                                                AS definitive_morphologie,
            DATE_FORMAT( ts.datum_sicherung, '%Y-%m-%d' )                 AS def_morph_datum,
            IF( ts.lokalisation IS NOT NULL,
                ts.lokalisation,
                IF( ts.diagnose IS NOT NULL AND LEFT( ts.diagnose, 3 )='C50',
                    ts.diagnose,
                    'C50.9' ) )                                           AS definitive_topologie,
            DATE_FORMAT( ts.datum_sicherung, '%Y-%m-%d' )                 AS def_topologie_datum

         FROM
            tumorstatus ts

         WHERE
            ts.tumorstatus_id={$data[ 'tumorstatus_id' ]}
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      foreach( $result as $key => $value ) {
         $diagnosen[ 0 ][ 'klassifikation' ][ $key ] = $value;
      }
      $diagnosen[ 0 ][ 'klassifikation' ][ 'icdo_version' ] = '';
      if ( ( isset( $diagnosen[ 0 ][ 'klassifikation' ][ 'definitive_morphologie' ] ) && strlen( $diagnosen[ 0 ][ 'klassifikation' ][ 'definitive_morphologie' ] ) > 0 ) &&
           ( isset( $diagnosen[ 0 ][ 'klassifikation' ][ 'definitive_topologie' ] ) && strlen( $diagnosen[ 0 ][ 'klassifikation' ][ 'definitive_topologie' ] ) > 0 ) ) {
         $diagnosen[ 0 ][ 'klassifikation' ][ 'icdo_version' ] = '301';
      }
      // Get Tumor
      $query = "
         SELECT
            IF( ts.multizentrisch='1', '1', '0' )                         AS multizentritaet,
            IF( ts.multifokal='1', '1', '0' )                             AS multifokalitaet

         FROM
            tumorstatus ts

         WHERE
            ts.tumorstatus_id={$data[ 'tumorstatus_id' ]}
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      foreach( $result as $key => $value ) {
         $diagnosen[ 0 ][ 'tumor' ][ $key ] = $value;
      }
      // Get Tumor.klinische_einteilung
      $query = "
         SELECT
            ct.code_wdc AS t,
            cn.code_wdc AS n,
            cm.code_wdc	AS m,
            '503'		AS tnm_version
         FROM
            tumorstatus ts
            LEFT JOIN l_exp_wdc ct ON ts.t=ct.code_med
                                       AND ct.klasse='ct'
			LEFT JOIN l_exp_wdc cn ON ts.n=cn.code_med
                                       AND cn.klasse='cn'
			LEFT JOIN l_exp_wdc cm ON ts.m=cm.code_med
                                       AND cm.klasse='cm'

		 WHERE
         	ts.erkrankung_id={$data[ 'erkrankung_id' ]}
         	AND ts.diagnose_seite='{$data[ 'diagnose_seite' ]}'
         	AND LEFT(ts.t, 1)='c'
         ORDER BY
        	ts.sicherungsgrad,
        	ts.datum_sicherung DESC
         LIMIT 0, 1
	  ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $diagnosen[ 0 ][ 'tumor' ][ 'klinische_einteilung' ] = array();
      if ( $result !== false ) {
         foreach( $result as $key => $value ) {
            $diagnosen[ 0 ][ 'tumor' ][ 'klinische_einteilung' ][ $key ] = $value;
         }
      }
      return $diagnosen;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapien( $data )
   {
      $result = array();
      $result[ 0 ][ 'ops' ] = $this->GetTherapieOps( $data );
      $result[ 0 ][ 'operation' ] = $this->GetTherapieOperations( $data );
      $result[ 0 ][ 'systemtherapie' ] = $this->GetTherapieSystemtherapien( $data );
      $result[ 0 ][ 'strahlentherapie' ] = $this->GetTherapieStrahlentherapien( $data );
      return $result;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieOps( $data )
   {
      $query = "
         SELECT
            eops.prozedur                                AS ops_code,
            e.datum                                      AS ops_datum,
            '104'									     AS ops_version

         FROM
            eingriff_ops eops
            LEFT JOIN eingriff e ON e.eingriff_id=eops.eingriff_id

         WHERE
            eops.erkrankung_id={$data[ 'erkrankung_id' ]}
            AND eops.prozedur_seite='{$data[ 'diagnose_seite' ]}'
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @return arrray
    */
   protected function GetTherapieOperations( $data )
   {
      $query = "
         SELECT
            IF( e.art_primaertumor='1', '1', '0' )         AS primaereingriff,
            DATE_FORMAT( e.datum, '%Y-%m-%d' )             AS op_datum,
            IFNULL(mark.code_wdc, '3')                     AS praeoperative_markierung,
            IFNULL(e.postop_roentgen, '2')                 AS praeparateroentgen,
            IFNULL(h.resektionsrand, -2)                   AS operativer_sicherheitsabstand,
            IFNULL(r.code_wdc, r_lokal.code_wdc)           AS lokale_pathohisto_radikalitaet,
            CASE
               WHEN kp.patientenwunsch_beo='1' THEN '0'
               WHEN kp.patientenwunsch_beo='0' THEN '1'
               ELSE                                 '2'
            END                                            AS ablatio_wunsch
         FROM
            eingriff e
            INNER JOIN tumorstatus tsp ON	tsp.tumorstatus_id={$data[ 'tumorstatus_id' ]}
            								AND tsp.erkrankung_id=e.erkrankung_id
            								AND tsp.diagnose_seite=e.diagnose_seite
            LEFT JOIN l_exp_wdc mark    ON e.mark=mark.code_med
                                           AND mark.klasse='praeoperative_markierung'
            LEFT JOIN l_exp_wdc r       ON tsp.r=r.code_med
                                           AND r.klasse='r'
            LEFT JOIN l_exp_wdc r_lokal ON tsp.r_lokal=r_lokal.code_med
                                           AND r_lokal.klasse='r'
			LEFT JOIN histologie h      ON h.eingriff_id=e.eingriff_id
            LEFT JOIN ( SELECT
                           k.datum,
                           k_p.erkrankung_id,
                           k_p.patient_id,
                           k_p.patientenwunsch_beo

                        FROM
                           konferenz_patient k_p
                           LEFT JOIN konferenz k ON k.konferenz_id=k_p.konferenz_id

                        ORDER BY
                           k.datum DESC

                        LIMIT 0, 1 ) kp ON kp.erkrankung_id=e.erkrankung_id
                                           AND kp.datum<=e.datum

         WHERE
            e.erkrankung_id={$data[ 'erkrankung_id' ]}
            AND e.diagnose_seite='{$data[ 'diagnose_seite' ]}'
            AND ( e.art_primaertumor='1'
                  OR e.art_lk='1'
                  OR e.art_metastasen='1'
                  OR e.art_rezidiv='1'
                  OR e.art_nachresektion='1'
                  OR e.art_revision='1'
                  OR e.art_rekonstruktion='1' )
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieSystemtherapien( $data )
   {
      $query = "
		(
    		SELECT
    			IFNULL(systemtherapie.code_wdc, '4')	AS systemtherapie,
                IF(t.ende IS NOT NULL, '3', '2')    	AS systemtherapie_ausfuehrung,
                systemtherapie_intention.code_wdc   	AS systemtherapie_intention,
    			t.beginn	                        	AS systemtherapie_beg_datum,
    			t.ende	                            	AS systemtherapie_ende_datum,
    			CASE
                    WHEN vt.art='cst'           THEN '106'
                    WHEN vt.art IN ('c', 'ci')  THEN IF(COUNT(vtw.vorlage_therapie_wirkstoff_id)>1, '102', '101')
                    WHEN vt.art IN ('i', 'ist') THEN '112'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('tamoxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('toremifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('fulvestran', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('raloxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '107'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('aminoglutethimid', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('anastrozol', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('exemestan', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('gemcitabin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('metenolon', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '108'
                    WHEN vt.art='ah'
                        AND LOCATE('mifepriston', GROUP_CONCAT(vtw.wirkstoff))>0
                                                THEN '109'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('goserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('leuprorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('triptorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('lhrhanaloga', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '110'
                    ELSE '113'
    			END			                        AS protokoll_art,
                IF(
                    LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('epirubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinnpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS anthrazyklin_gabe,
                IF(
                    LOCATE('docetaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('paclitaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS taxan_gabe,
                '0'                                 AS keine_systemtherapie
    		FROM
    			therapie_systemisch t
    			LEFT JOIN l_exp_wdc systemtherapie              ON systemtherapie.klasse='systemtherapie'
    			                                                    AND t.vorlage_therapie_art=systemtherapie.code_med
    			LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
    			                                                    AND t.intention=systemtherapie_intention.code_med
                LEFT JOIN vorlage_therapie vt                   ON t.vorlage_therapie_id=vt.vorlage_therapie_id
                    LEFT JOIN vorlage_therapie_wirkstoff vtw    ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id
    		WHERE
    			t.erkrankung_id={$data[ 'erkrankung_id' ]}
            GROUP BY
                t.therapie_systemisch_id
		) UNION (
    		SELECT
    			'1'            						AS systemtherapie,
                '1'                                 AS systemtherapie_ausfuehrung,
                systemtherapie_intention.code_wdc   AS systemtherapie_intention,
    			'1900-01-01'                     	AS systemtherapie_beg_datum,
                NULL	                            AS systemtherapie_ende_datum,
    			CASE
                    WHEN vt.art='cst'           THEN '106'
                    WHEN vt.art IN ('c', 'ci')  THEN IF(COUNT(vtw.vorlage_therapie_wirkstoff_id)>1, '102', '101')
                    WHEN vt.art IN ('i', 'ist') THEN '112'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('tamoxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('toremifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('fulvestran', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('raloxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '107'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('aminoglutethimid', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('anastrozol', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('exemestan', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('gemcitabin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('metenolon', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '108'
                    WHEN vt.art='ah'
                        AND LOCATE('mifepriston', GROUP_CONCAT(vtw.wirkstoff))>0
                                                THEN '109'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('goserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('leuprorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('triptorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('lhrhanaloga', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '110'
                    ELSE '113'
                END			                        AS protokoll_art,
                IF(
                    LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('epirubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinnpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS anthrazyklin_gabe,
                IF(
                    LOCATE('docetaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('paclitaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS taxan_gabe,
                '0'                                 AS keine_systemtherapie
    		FROM
    			therapieplan t
    			LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
    			                                                    AND t.chemo_intention=systemtherapie_intention.code_med
                LEFT JOIN vorlage_therapie vt                   ON t.chemo_id=vt.vorlage_therapie_id
                    LEFT JOIN vorlage_therapie_wirkstoff vtw    ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id
    			LEFT JOIN therapie_systemisch ts ON t.chemo_id=ts.vorlage_therapie_id
    		WHERE
		        t.chemo='1'
    			AND ts.therapie_systemisch_id IS NULL
    			AND t.erkrankung_id={$data[ 'erkrankung_id' ]}
    		GROUP BY
    		    t.therapieplan_id
    		ORDER BY
    			t.datum DESC
    		LIMIT 0, 1
		) UNION (
    		SELECT
    			'3'            						AS systemtherapie,
                '1'                                 AS systemtherapie_ausfuehrung,
                systemtherapie_intention.code_wdc   AS systemtherapie_intention,
    			'1900-01-01'                     	AS systemtherapie_beg_datum,
                NULL	                            AS systemtherapie_ende_datum,
    			CASE
                    WHEN vt.art='cst'           THEN '106'
                    WHEN vt.art IN ('c', 'ci')  THEN IF(COUNT(vtw.vorlage_therapie_wirkstoff_id)>1, '102', '101')
                    WHEN vt.art IN ('i', 'ist') THEN '112'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('tamoxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('toremifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('fulvestran', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('raloxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '107'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('aminoglutethimid', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('anastrozol', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('exemestan', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('gemcitabin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('metenolon', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '108'
                    WHEN vt.art='ah'
                        AND LOCATE('mifepriston', GROUP_CONCAT(vtw.wirkstoff))>0
                                                THEN '109'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('goserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('leuprorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('triptorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('lhrhanaloga', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '110'
                    ELSE '113'
                END			                        AS protokoll_art,
                IF(
                    LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('epirubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinnpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS anthrazyklin_gabe,
                IF(
                    LOCATE('docetaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('paclitaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS taxan_gabe,
                '0'                                 AS keine_systemtherapie
    		FROM
    			therapieplan t
    			LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
    			                                                    AND t.ah_intention=systemtherapie_intention.code_med
                LEFT JOIN vorlage_therapie vt                   ON t.ah_id=vt.vorlage_therapie_id
                    LEFT JOIN vorlage_therapie_wirkstoff vtw    ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id
    			LEFT JOIN therapie_systemisch ts ON t.ah_id=ts.vorlage_therapie_id
    		WHERE
		        t.ah='1'
    			AND ts.therapie_systemisch_id IS NULL
    			AND t.erkrankung_id={$data[ 'erkrankung_id' ]}
    		GROUP BY
    		    t.therapieplan_id
    		ORDER BY
    			t.datum DESC
    		LIMIT 0, 1
		) UNION (
    		SELECT
    			'2'             					AS systemtherapie,
                '1'                                 AS systemtherapie_ausfuehrung,
                systemtherapie_intention.code_wdc   AS systemtherapie_intention,
    			'1900-01-01'                     	AS systemtherapie_beg_datum,
    			NULL	                            AS systemtherapie_ende_datum,
    			CASE
                    WHEN vt.art='cst'           THEN '106'
                    WHEN vt.art IN ('c', 'ci')  THEN IF(COUNT(vtw.vorlage_therapie_wirkstoff_id)>1, '102', '101')
                    WHEN vt.art IN ('i', 'ist') THEN '112'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('tamoxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('toremifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('fulvestran', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('raloxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '107'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('aminoglutethimid', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('anastrozol', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('exemestan', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('gemcitabin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('metenolon', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '108'
                    WHEN vt.art='ah'
                        AND LOCATE('mifepriston', GROUP_CONCAT(vtw.wirkstoff))>0
                                                THEN '109'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('goserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('leuprorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('triptorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('lhrhanaloga', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '110'
                    ELSE '113'
    			END			                        AS protokoll_art,
                IF(
                    LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('epirubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinnpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS anthrazyklin_gabe,
                IF(
                    LOCATE('docetaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('paclitaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS taxan_gabe,
                '0'                                 AS keine_systemtherapie
    		FROM
    			therapieplan t
    			LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
    			                                                    AND t.immun_intention=systemtherapie_intention.code_med
                LEFT JOIN vorlage_therapie vt                   ON t.immun_id=vt.vorlage_therapie_id
                    LEFT JOIN vorlage_therapie_wirkstoff vtw    ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id
    			LEFT JOIN therapie_systemisch ts ON t.immun_id=ts.vorlage_therapie_id
    		WHERE
		        t.immun='1'
    			AND ts.therapie_systemisch_id IS NULL
    			AND t.erkrankung_id={$data[ 'erkrankung_id' ]}
    		GROUP BY
    		    t.therapieplan_id
    		ORDER BY
    			t.datum DESC
    		LIMIT 0, 1
		) UNION (
    		SELECT
    			'4'             					AS systemtherapie,
                '1'                                 AS systemtherapie_ausfuehrung,
                systemtherapie_intention.code_wdc   AS systemtherapie_intention,
    			'1900-01-01'                     	AS systemtherapie_beg_datum,
    			NULL	                            AS systemtherapie_ende_datum,
    			CASE
                    WHEN vt.art='cst'           THEN '106'
                    WHEN vt.art IN ('c', 'ci')  THEN IF(COUNT(vtw.vorlage_therapie_wirkstoff_id)>1, '102', '101')
                    WHEN vt.art IN ('i', 'ist') THEN '112'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('tamoxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('toremifen', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('fulvestran', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('raloxifen', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '107'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('aminoglutethimid', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('anastrozol', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('exemestan', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('gemcitabin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('metenolon', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '108'
                    WHEN vt.art='ah'
                        AND LOCATE('mifepriston', GROUP_CONCAT(vtw.wirkstoff))>0
                                                THEN '109'
                    WHEN vt.art='ah'
                        AND (
                            LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('goserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('leuprorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('triptorelin', GROUP_CONCAT(vtw.wirkstoff))>0
                            OR LOCATE('lhrhanaloga', GROUP_CONCAT(vtw.wirkstoff))>0
                        )                       THEN '110'
					ELSE '113'
    			END			                        AS protokoll_art,
                IF(
                    LOCATE('buserelin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('epirubicin', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('doxorubicinnpeg', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS anthrazyklin_gabe,
                IF(
                    LOCATE('docetaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                    OR LOCATE('paclitaxel', GROUP_CONCAT(vtw.wirkstoff))>0
                , 1, 0)                             AS taxan_gabe,
                '0'                                 AS keine_systemtherapie
    		FROM
    			therapieplan t
    			LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
    			                                                    AND t.andere_intention=systemtherapie_intention.code_med
                LEFT JOIN vorlage_therapie vt                   ON t.andere_id=vt.vorlage_therapie_id
                    LEFT JOIN vorlage_therapie_wirkstoff vtw    ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id
    			LEFT JOIN therapie_systemisch ts ON t.andere_id=ts.vorlage_therapie_id
    		WHERE
		        t.andere='1'
    			AND ts.therapie_systemisch_id IS NULL
    			AND t.erkrankung_id={$data[ 'erkrankung_id' ]}
    		GROUP BY
    		    t.therapieplan_id
    		ORDER BY
    			t.datum DESC
    		LIMIT 0, 1
		)
      ";
      $result = sql_query_array( $this->_db, $query );

      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result[ 0 ][ 'systemtherapie' ] = '0';
         $result[ 0 ][ 'systemtherapie_ausfuehrung' ] = '0';
         $result[ 0 ][ 'systemtherapie_intention' ] = '4';
         $result[ 0 ][ 'systemtherapie_beg_datum' ] = '1900-01-01';
         $result[ 0 ][ 'systemtherapie_ende_datum' ] = '';
         $result[ 0 ][ 'protokoll_art' ] = '113';
         $result[ 0 ][ 'anthrazyklin_gabe' ] = '0';
         $result[ 0 ][ 'taxan_gabe' ] = '0';
         $result[ 0 ][ 'keine_systemtherapie' ] = '5';
      }

      return $result;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieStrahlentherapien( $data )
   {
      $query = "
		SELECT
			'1'			AS strahlentherapie,
			t.beginn	AS strahlentherapie_beg_datum,
			'0'			AS keine_strahlentherapie,
			CASE
               WHEN '1' IN (ziel_mamma_r, ziel_mamma_l) AND '1' IN (ziel_axilla_r, ziel_axilla_l) THEN 203
               WHEN '1' IN (ziel_mamma_r, ziel_mamma_l) AND ziel_lk_supra='1'    THEN 202
               WHEN '1' IN (ziel_mamma_r, ziel_mamma_l) THEN 201
               WHEN '1' IN (ziel_brustwand_r, ziel_brustwand_l) THEN 301
               WHEN ziel_sonst_detail='C34.9' THEN 302
               WHEN ziel_sonst_detail='C71.9' THEN 100
               ELSE 701
			END			AS region
		FROM
			strahlentherapie t
		WHERE
			t.erkrankung_id={$data[ 'erkrankung_id' ]}
		UNION (SELECT
			'1'			AS strahlentherapie,
			NULL		AS strahlentherapie_beg_datum,
			'0'			AS keine_strahlentherapie,
			NULL		AS region
		FROM
			therapieplan t
			LEFT JOIN strahlentherapie st ON t.erkrankung_id=st.erkrankung_id
		WHERE
		    t.strahlen='1'
			AND st.strahlentherapie_id IS NULL
			AND t.erkrankung_id={$data[ 'erkrankung_id' ]}
		ORDER BY
			t.datum DESC
		LIMIT 0, 1
		)
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result[ 0 ][ 'strahlentherapie' ] = '0';
         $result[ 0 ][ 'strahlentherapie_beg_datum' ] = '';
         $result[ 0 ][ 'keine_strahlentherapie' ] = '5';
         $result[ 0 ][ 'region' ] = '';
      }

      return $result;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetHistologien( $data )
   {

      $histologien = array();
      $query = "
         SELECT
         	IFNULL(ts.lk_entf, '0')          	AS anz_entf_lymphknoten,
         	IFNULL(ts.lk_bef, '0')           	AS anz_entf_lymphknoten_pos,
         	IFNULL(h.lk_sentinel_entf, '0')  	AS anz_entf_sentinel,
         	IFNULL(h.lk_sentinel_bef, '0')		AS anz_entf_sentinel_pos
         FROM
         	tumorstatus ts
            LEFT JOIN (
                SELECT
                    erkrankung_id,
                    diagnose_seite,
                    lk_sentinel_entf,
                    lk_sentinel_bef
                FROM
                    histologie
                WHERE
                    erkrankung_id={$data[ 'erkrankung_id' ]}
                    AND diagnose_seite='{$data[ 'diagnose_seite' ]}'
                    AND lk_sentinel_entf IS NOT NULL
                ORDER BY
                    datum DESC
                LIMIT 0, 1
            ) h             ON ts.erkrankung_id=h.erkrankung_id
                                AND ts.diagnose_seite=h.diagnose_seite
         WHERE
         	ts.tumorstatus_id={$data[ 'tumorstatus_id' ]}
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( ( $result !== false ) && ( count( $result ) > 0 ) ) {
         foreach( $result as $key => $value ) {
            $histologien[ 0 ][ $key ] = $value;
         }
         $histologien[ 0 ][ 'patho_histo_klassifikationen' ] = $this->GetHistologienPhk( $data );
      }
      return $histologien;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetHistologienPhk( $data )
   {

      $query = "
         SELECT DISTINCT
         	ts.morphologie              AS morpho_code,
            IF(
                ts.lokalisation IS NOT NULL
            ,
                ts.lokalisation
            ,
                IF(
                    ts.diagnose IS NOT NULL AND LEFT( ts.diagnose, 3 )='C50'
                ,
                    ts.diagnose
                ,
                    'C50.9'
                )
            )                           AS topologie_code,
         	ts.datum_sicherung          AS histo_datum,
            IF(
                IF(
                    ts.lokalisation IS NOT NULL
                ,
                    ts.lokalisation
                ,
                    IF(
                        ts.diagnose IS NOT NULL AND LEFT( ts.diagnose, 3 )='C50'
                    ,
                        ts.diagnose
                    ,
                        'C50.9'
                    )
                ) IS NOT NULL
                OR ts.morphologie IS NOT NULL
         	, '301', NULL)              AS icdo_version,
         	t.code_wdc                  AS t,
         	n.code_wdc                  AS n,
         	m.code_wdc                  AS m,
         	IF(
         	    t.code_wdc IS NOT NULL
         	    OR n.code_wdc IS NOT NULL
         	    OR m.code_wdc IS NOT NULL
         	,
         	    IFNULL(y.code_wdc, '0')
         	,
         		NULL
         	)							AS y,
         	IF(
         	    t.code_wdc IS NOT NULL
         	    OR n.code_wdc IS NOT NULL
         	    OR m.code_wdc IS NOT NULL
         	, '503', NULL)              AS tnm_version
         FROM
         	tumorstatus ts
         	LEFT JOIN l_exp_wdc t   ON t.klasse='pt'
         	                            AND ts.t=t.code_med
         	LEFT JOIN l_exp_wdc n   ON n.klasse='pn'
         	                            AND ts.n=n.code_med
         	LEFT JOIN l_exp_wdc m   ON m.klasse IN ('cm', 'pm')
         	                            AND ts.m=m.code_med
         	LEFT JOIN l_exp_wdc y   ON y.klasse='y'
         	                            AND ts.tnm_praefix=y.code_med
		WHERE
         	ts.erkrankung_id={$data[ 'erkrankung_id' ]}
         	AND ts.diagnose_seite='{$data[ 'diagnose_seite' ]}'
         	AND LEFT(ts.t, 1)='p'
        ORDER BY
        	ts.sicherungsgrad,
        	ts.datum_sicherung DESC
        LIMIT 0, 1
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

   /**
    *
    * @param array $item
    * @return array
    */
   protected function GetLabore( $item )
   {
      $data = array();
      // er
      $query = "
         SELECT
           	IFNULL(er.code_wdc, er2.code_wdc)		AS er,
         	'2'                                     AS er_score

        FROM
            tumorstatus ts
			LEFT JOIN l_exp_wdc	er	ON er.klasse='irs'
										AND ts.estro_irs=er.code_med
			LEFT JOIN l_exp_wdc	er2	ON er2.klasse='rez_pn'
										AND ts.estro_urteil=er2.code_med
         WHERE
            ts.erkrankung_id={$item[ 'erkrankung_id' ]}
            AND ts.diagnose_seite='{$item[ 'diagnose_seite' ]}'
            AND ts.anlass='p'
            AND ts.estro_irs IS NOT NULL

         ORDER BY
            ts.datum_sicherung DESC

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $data[ 'er' ] = array();
      if ( $result !== false ) {
         $data[ 'er' ][] = $result;
      }
      // pr
      $query = "
         SELECT
           IFNULL(pr.code_wdc, pr2.code_wdc)		AS pr,
           '2'                                      AS pr_score

        FROM
            tumorstatus ts
			LEFT JOIN l_exp_wdc	pr	ON pr.klasse='irs'
										AND ts.prog_irs=pr.code_med
			LEFT JOIN l_exp_wdc	pr2	ON pr2.klasse='rez_pn'
										AND ts.prog_urteil=pr2.code_med
		WHERE
            ts.erkrankung_id={$item[ 'erkrankung_id' ]}
            AND ts.diagnose_seite='{$item[ 'diagnose_seite' ]}'
            AND ts.anlass='p'
            AND ts.prog_irs IS NOT NULL

        ORDER BY
            ts.datum_sicherung DESC

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $data[ 'pr' ] = array();
      if ( $result !== false ) {
         $data[ 'pr' ][] = $result;
      }
      return array($data);
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetNachsorgen( $data )
   {
      $query = "
         SELECT
           DATE_FORMAT( n.datum, '%Y-%m-%d' ) AS nachsorge_datum

         FROM
            nachsorge n
            INNER JOIN nachsorge_erkrankung ne ON ne.nachsorge_id=n.nachsorge_id
                                                  AND ne.erkrankung_weitere_id={$data[ 'erkrankung_id' ]}

         WHERE
            n.patient_id={$data[ 'patient_id' ]}
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

}

?>
