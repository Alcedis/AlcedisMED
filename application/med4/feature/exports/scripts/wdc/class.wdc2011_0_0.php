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

class CWdc2011_0_0 extends CMedBaseExport
{
   var $_trim_length;

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_trim_length = 200;
      $this->_schema_file = 'feature/exports/scripts/wdc/ColonCa_2011.xsd';
      $this->_xml_template = 'app/xml.export_wdc_2011_0.tpl';
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
      return "2011.0";
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilter()
    */
   protected function CreateExportFilter( $session, $request )
   {
      $export_filter = array();
      $export_filter[ 'user_id' ] = isset( $session[ 'sess_user_id' ] ) ? $session[ 'sess_user_id' ] : '-1';
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
         $errors_sum = 0;
         foreach( $patient[ 'faelle' ] as $fall ) {
            $data = array();
            $data[ 0 ][ 'patient_id' ] = $patient[ 'patient_id' ];
            $data[ 0 ][ 'pat_daten' ] = $patient[ 'pat_daten' ];
            $data[ 0 ][ 'faelle' ][ 0 ] = $fall;
            $this->_internal_smarty->assign( 'patients', $data );
            $xml = $this->_internal_smarty->fetch( $this->_xml_template );
            $errors = $this->xmlSchemaValidate( $xml, $this->_schema_file ); // Fix für Ticket #5170: UPDATE: Wieder rückgängig gemacht!!!!
            $errors_sum += count( $errors );
            // Log in DB schreiben
            $query = '
               INSERT INTO exp_wdc_log
                  VALUES ( "",
                           "' . $export_filter[ 'export_id' ]   . '",
                           "' . $patient[ 'patient_id' ]        . '",
                           "' . $fall[ 'fall_id' ]              . '",
                           "' . ( int )!count( $errors )        . '",
                           "' . implode( '', $errors )          . '",
                           "' . $export_filter[ 'org_id' ]      . '",
                           "' . $export_filter[ 'von' ]         . '",
                           "' . $export_filter[ 'bis' ]         . '",
                            ' . $export_filter[ 'user_id' ]     . ',
                           "' . date('Y-m-d H:i:s')             . '"
                  )
            ';
            $erg = mysql_query( $query, $this->_db );
         }
         if ( $errors_sum > 0 ) {
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
         $this->_internal_smarty->assign( 'patients', $data );
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
      foreach( $content AS $type => $patients ) {
         $i = 0;
         switch( $type ) {
            case 'valid':
               foreach( $patients AS $patient ) {
                  $result[ $type ][ $i ][ 'patient_id' ] = $patient[ 'patient_id' ];
                  $result[ $type ][ $i ][ 'bez' ] = $this->GetPatientBez( $patient[ 'patient_id' ], $export_filter );
                  $i++;
               }
               $cnt_patient_valid = count( $result[ 'valid' ] );
               $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $this->_config[ 'info_patienten_valid' ] );
               break;

            case 'invalid':
               foreach( $patients AS $patient ) {
                  $result[ $type ][ $i ][ 'patient_id' ] = $patient[ 'patient_id' ];
                  $result[ $type ][ $i ][ 'bez' ] = $this->GetPatientBez( $patient[ 'patient_id' ], $export_filter );
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
      $darm = array();
      $darm[ 'schema_version' ][ 'typ' ] = "105";
      $darm[ 'schema_version' ][ 'jahr' ] = "2011";
      $darm[ 'zentrum_id' ] = isset( $this->_config[ 'exp_wdc_klinik_id' ] ) ? $this->_config[ 'exp_wdc_klinik_id' ] : '';
      $darm[ 'datum_datensatzerstellung' ] = date( 'Y-m-d' );
      $darm[ 'zeitraum_beginn' ] = $export_filter[ 'von' ];
      $darm[ 'zeitraum_ende' ] = $export_filter[ 'bis' ];
      $darm[ 'sw' ][ 'sw_hersteller' ] = $this->_config[ 'org_name' ];
      $darm[ 'sw' ][ 'sw_name' ] = dlookup($this->_db, 'settings', 'software_title', 'settings_id = 1');
      $darm[ 'sw' ][ 'sw_version' ] = dlookup($this->_db, 'settings', 'software_version', 'settings_id = 1');
      $darm[ 'technischer_ansprechpartner' ][ 'tech_ansprechpartner_name' ] = $this->_config[ 'exp_wdc_ansprechpartner_name' ];
      $darm[ 'technischer_ansprechpartner' ][ 'email' ] = $this->_config[ 'exp_wdc_ansprechpartner_email' ];
      $this->_internal_smarty->assign( 'darm', $darm );
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
           ts.erkrankung_id

         FROM
           tumorstatus ts
           	INNER JOIN erkrankung e ON ts.erkrankung_id=e.erkrankung_id
           							   AND e.erkrankung='d'
           							   AND ts.datum_sicherung BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'
           	INNER JOIN patient p    ON p.patient_id=ts.patient_id
                                       AND p.org_id={$export_filter[ 'org_id' ]}

         WHERE
           ts.anlass='p'

         GROUP BY
            erkrankung_id

         ORDER BY
           ts.sicherungsgrad,
           ts.datum_sicherung DESC
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result !== false ) && ( count( $result ) > 0 ) ) {
         $tumorstatus = array();
         foreach( $result as $record ) {
             $key = $record[ 'erkrankung_id' ];
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
      #debug
      #print_arr( $data );
      #exit();
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
      $result[ 'patient_id' ] = $patient[ 0 ][ 'patient_id' ];
      $result[ 'pat_daten' ] = $this->GetPatientData( $patient[ 0 ][ 'patient_id' ] );
      foreach( $patient as $fall ) {
         $result[ 'faelle' ][] = $this->GetCase( $fall, $export_filter );
      }
      return $result;
   }

   /**
    *
    * @param int $patient_id
    * @return array
    */
   protected function GetPatientData( $patient_id )
   {
      // $zk: Fix für #5330 todesursache auch 4 wenn KEIN abschlussformular!!!
      $query = "
         SELECT
            DATE_FORMAT( p.geburtsdatum, '%Y-%m-%d' )       	AS geburtstag,
            IFNULL( geschlecht.code_wdc, 'x' )              	AS geschlecht,
            IF( abschluss.abschluss_id IS NOT NULL,
                IF( abschluss.abschluss_grund='tot',
            		DATE_FORMAT(
            			IFNULL( abschluss.todesdatum,
            					'1900-01-01' ),
            			'%Y-%m-%d' ),
            		NULL ),
            	NULL )	     		 							AS todesdatum,
            IF( abschluss.abschluss_id IS NOT NULL,
                IF( abschluss.abschluss_grund='tot',
            	    IFNULL( tod_ursache.code_wdc, '4' ),
            	    NULL ),
            	NULL )											AS todesursache

         FROM
            patient p
            LEFT JOIN l_exp_wdc geschlecht  ON p.geschlecht=geschlecht.code_med
                                                AND geschlecht.klasse='geschlecht'
            LEFT JOIN abschluss abschluss   ON abschluss.patient_id=p.patient_id
            LEFT JOIN l_exp_wdc tod_ursache ON abschluss.tod_ursache=tod_ursache.code_med
                                                AND tod_ursache.klasse='todesursache'

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
      $case = $this->GetCaseData( $data[ 'erkrankung_id' ] );
      $case[ 'studien' ] = $this->GetStudien( $data[ 'erkrankung_id' ] );
      $case[ 'tumorkonferenzen' ] = $this->GetTumorkonferenzen( $data[ 'erkrankung_id' ] );
      $case[ 'anamnese' ] = $this->GetAnamnese( $data[ 'erkrankung_id' ] );
      $case[ 'diagnosen' ] = $this->GetDiagnosen( $data[ 'erkrankung_id' ] );
      $case[ 'histologien' ] = $this->GetHistologien( $data[ 'erkrankung_id' ] );
      $case[ 'therapien' ] = $this->GetTherapien( $data[ 'erkrankung_id' ] );
      $case[ 'labore' ] = $this->GetLabore( $data[ 'erkrankung_id' ] );
      $case[ 'nachsorgen' ] = $this->GetNachsorgen( $data[ 'erkrankung_id' ] );
      $case[ 'followups' ] = $this->GetFollowUps( $data[ 'erkrankung_id' ] );
      return $case;
   }

   /**
    *
    * @param int $tumorstatus_id
    * @return array
    */
   protected function GetCaseData( $erkrankung_id )
   {
      $query = "
         SELECT
           e.erkrankung_id                  	AS fall_id,
           LEFT(kostentraeger.name, 200)    	AS kostentraeger,
           MIN(s.form_date)                 	AS fall_beginn,
           a_gr.groesse                     	AS koerpergroesse,
           a_ge.gewicht                     	AS koerpergewicht,
           ''                               	AS fall_ende

         FROM
            erkrankung e
            INNER JOIN patient p                ON e.patient_id=p.patient_id
                LEFT JOIN l_ktst kostentraeger  ON p.kv_iknr=kostentraeger.iknr
            LEFT JOIN status s                  ON e.erkrankung_id=s.erkrankung_id
            LEFT JOIN (
                SELECT *
                FROM anamnese
                WHERE
                    groesse IS NOT NULL
                ORDER BY
                    datum
            ) a_gr                              ON e.erkrankung_id=a_gr.erkrankung_id
            LEFT JOIN (
                SELECT *
                FROM anamnese
                WHERE
                    gewicht IS NOT NULL
                ORDER BY
                    datum
            ) a_ge                              ON e.erkrankung_id=a_ge.erkrankung_id
         WHERE
            e.erkrankung_id=$erkrankung_id
         GROUP BY
            e.erkrankung_id
      ";
      $case_data = end( sql_query_array( $this->_db, $query ) );
      if ( $case_data === false ) {
         $case_data = array(
         	"fall_id"        => "",
         	"kostentraeger"  => "",
         	"fall_beginn"    => "",
         	"koerpergroesse" => "",
         	"koerpergewicht" => "",
         	"fall_ende"      => ""
         );
      }
      return $case_data;
   }

   /**
    *
    * @param int $erkrankung_id
    * @param int $patient_id
    * @return array
    */
   protected function GetStudien( $erkrankung_id )
   {
      $query = "
         SELECT
            1           AS studienteilnehmer,
	        vs.bez      AS studien_name,
	        ''          AS studien_nummer,
	        ''          AS studie_beendet,
            s.beginn    AS datum_einschluss,
            ''          AS datum_studienende,
            7           AS keine_studie

         FROM
            studie s
            INNER JOIN vorlage_studie vs ON s.vorlage_studie_id=vs.vorlage_studie_id

         WHERE
            s.erkrankung_id=$erkrankung_id

         GROUP BY
            s.studie_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result[ 0 ][ 'studienteilnehmer' ] = '0';
         $result[ 0 ][ 'studien_name' ] = '';
         $result[ 0 ][ 'studien_nummer' ] = '';
         $result[ 0 ][ 'studie_beendet' ] = '';
         $result[ 0 ][ 'datum_einschluss' ] = '';
         $result[ 0 ][ 'datum_studienende' ] = '';
         $result[ 0 ][ 'keine_studie' ] = '6';
      }
      $result[ 0 ][ 'studien_name' ] = $this->TrimString( $result[ 0 ][ 'studien_name' ], $this->_trim_length, false );
      return $result;
   }

   /**
    *
    * Enter description here ...
    * @param unknown_type $data
    * @param unknown_type $export_filter
    * @return array
    */
   protected function GetTumorkonferenzen( $erkrankung_id )
   {
      $query = "
         SELECT
            k.datum                                 AS datum,
	        IF(COUNT(tp.therapieplan_id)>0, 1, 0)   AS empfehlung,
	        IF(kp.art='post', 1, 0)                 AS postoperativ,
	        IF(kp.art='prae', 1, 0)                 AS praetherapeutisch

         FROM
                konferenz_patient kp
                LEFT JOIN konferenz k       ON kp.konferenz_id=k.konferenz_id
                LEFT JOIN therapieplan tp   ON kp.konferenz_patient_id=tp.konferenz_patient_id

         WHERE
            kp.erkrankung_id=$erkrankung_id

         GROUP BY
            kp.konferenz_patient_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result = array();
      }
      return array(); //$result; $zk: wieder rein???
   }

   /**
    *
    * Enter description here ...
    * @param unknown_type $data
    * @param unknown_type $export_filter
    * @return array
    */
   protected function GetAnamnese( $erkrankung_id )
   {
      $query = "
         SELECT
            0                                           AS rezidiv,
	        MIN(h.datum)                                AS erstdiag_datum,
	        IF(COUNT(af.anamnese_familie_id)>0, 1, 0)   AS familie_pos

         FROM
            erkrankung e
            LEFT JOIN histologie h          ON e.erkrankung_id=h.erkrankung_id
            LEFT JOIN anamnese_familie af   ON e.erkrankung_id=af.erkrankung_id
                                                AND af.karzinom='kore'

         WHERE
            e.erkrankung_id=$erkrankung_id

         GROUP BY
            e.erkrankung_id

      ";
      $result = end(sql_query_array( $this->_db, $query ));
      if ( ( $result === false ) || ( is_array( $result ) && ( count( $result ) == 0 ) ) ) {
         $result[ 'rezidiv' ] = '0';
         $result[ 'erstdiag_datum' ] = '';
         $result[ 'familie_pos' ] = '';
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @param array $export_filter
    * @return array
    */
   protected function GetDiagnosen( $erkrankung_id )
   {
      $diagnosen = array();
      $node = array();
      $query = "
         SELECT
            ts.tumorstatus_id,
            ts.anlass,
            CASE LEFT(ts.diagnose, 3)
                WHEN 'C18' THEN '1'
                WHEN 'C19' THEN '2'
                WHEN 'C20' THEN '3'
            END                                 AS tumor,
            ts.hoehe                            AS anocutanlinie,
            IF(LEFT(ts.anlass, 1)='p', 0, 1)    AS rezidiv,
            MIN(ts.datum_sicherung)             AS datum_diagnose

         FROM
            erkrankung e
            INNER JOIN (
                SELECT *
                FROM tumorstatus
                WHERE
                    LEFT(anlass, 1) IN ('p', 'r')
                ORDER BY
                    anlass,
                    datum_sicherung DESC
            ) ts            ON e.erkrankung_id=ts.erkrankung_id

         WHERE
            e.erkrankung_id=$erkrankung_id

         GROUP BY
            e.erkrankung_id,
            ts.anlass
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $diagnose ) {
            $node = array();
            foreach( $diagnose as $key => $value ) {
               $node[ $key ] = $value;
            }
            $node[ 'icds' ] = $this->GetIcds( $diagnose[ 'tumorstatus_id' ] );
            $node[ 'untersuchungsverfahren' ] = array();
            $node[ 'klinischer_tnm' ] = $this->GetKlinischerTNM( $erkrankung_id, $diagnose[ 'anlass' ] );
            $node[ 'koloskopie' ] = $this->GetKoloskopie( $erkrankung_id );
            $diagnosen[] = $node;
         }
      }
      return $diagnosen;
   }

   /**
    *
    * @param $erkrankung_id integer
    * @param $export_filter array
    * @return array
    */
   protected function GetIcds( $tumorstatus_id )
   {
      $query = "
         SELECT
            diagnose        AS icd_code,
            diagnose_text   AS icd_text,
            NULL            AS icd_version,
            NULL            AS diagnosesicherheit

         FROM
            tumorstatus

         WHERE
            tumorstatus_id=$tumorstatus_id
      ";
      $result = sql_query_array( $this->_db, $query );
      $i = 0;
      foreach( $result as $row ) {
         $result[ $i ][ 'icd_text' ] = $this->TrimString( $row[ 'icd_text' ], $this->_trim_length, false );
         $i++;
      }
      return $result;
   }

   /**
    *
    * @param $erkrankung_id integer
    * @param $export_filter array
    * @return array
    */
   protected function GetKlinischerTNM( $erkrankung_id, $anlass )
   {
      $query = "
         SELECT
            SUBSTRING( ts.t, 3)                 AS t,
            SUBSTRING( ts.n, 3)                 AS n,
            SUBSTRING( ts.m, 3)                 AS m,
            IF(ts.tnm_praefix LIKE '%y%', 1, 0) AS y,
            grading.code_wdc                    AS g,
            503                                 AS tnm_version

         FROM
            tumorstatus ts
            LEFT JOIN l_exp_wdc grading ON grading.klasse='grading'
                                            AND ts.g=grading.code_med
         WHERE
            erkrankung_id=$erkrankung_id
            AND anlass='$anlass'
            AND LEFT(t, 1)='c'

         ORDER BY
            datum_sicherung DESC

         LIMIT 0, 1
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( ( $result !== false ) && is_array( $result ) && count( $result ) > 0 ) {
	      $result[ 't' ] = $this->TrimTNM( $result[ 't' ] );
	      $result[ 'n' ] = $this->TrimTNM( $result[ 'n' ] );
	      $result[ 'm' ] = $this->TrimTNM( $result[ 'm' ] );
      }
      return $result;
   }

   /**
    *
    * @param $erkrankung_id integer
    * @param $export_filter array
    * @return array
    */
   protected function GetKoloskopie( $erkrankung_id )
   {
      $query = "
        SELECT
               SUM(ges_koloskopie)                  AS ges_koloskopie,
               SUM(tot_koloskopie)                  AS tot_koloskopie,
               MAX(ther_koloskopie)                 AS ther_koloskopie,
               MAX(ther_koloskopie_kompl)           AS ther_koloskopie_kompl,
               SUM(unv_stenosierende_koloskopie)    AS unv_stenosierende_koloskopie,
               SUM(polyp_nachweis)                  AS polyp_nachweis,
               SUM(polypektomie)                    AS polypektomie,
               SUM(polyp_op_gebiet)                 AS polyp_op_gebiet,
               SUM(polypektomie_polyp)              AS polypektomie_polyp

        FROM (
            SELECT
               COUNT(DISTINCT ei.eingriff_id)                                                      AS ges_koloskopie,
               COUNT(DISTINCT IF(ei.ther_koloskopie_vollstaendig='1', ei.eingriff_id, NULL))       AS tot_koloskopie,
               IF(COUNT(DISTINCT ei.eingriff_id)>0, 1, 0)                                          AS ther_koloskopie,
               IF(COUNT(DISTINCT k.komplikation_id)>0, 1, 0)                                       AS ther_koloskopie_kompl,
               COUNT(DISTINCT IF(k.komplikation='sten', k.eingriff_id, NULL))                      AS unv_stenosierende_koloskopie,
               COUNT(DISTINCT IF(ei.polypen='1', ei.eingriff_id, NULL))                            AS polyp_nachweis,
               COUNT(DISTINCT IF(eio.prozedur IN ('5-452.21', '5-452.22'), ei.eingriff_id, NULL))  AS polypektomie,
               COUNT(DISTINCT IF(
                   ei.polypen='1'
                   AND (ei.polypen_anz_entf=0 OR ei.polypen_anz_entf IS NULL)
                   AND ei.polypen_op_areal='1'
               , ei.eingriff_id, NULL))                                                            AS polyp_op_gebiet,
               COUNT(DISTINCT IF(
                   ei.polypen='1'
                   AND ei.polypen_op_areal='0', ei.eingriff_id, NULL)
               )                                                                                   AS polypektomie_polyp

            FROM
               eingriff ei
               INNER JOIN eingriff_ops eio ON eio.eingriff_id=ei.eingriff_id
                                               AND (
                                                   eio.prozedur LIKE '5-452.2%'
                                                   OR eio.prozedur LIKE '5-452.5%'
                                                   OR eio.prozedur LIKE '5-482._1%'
                                                   OR eio.prozedur LIKE '5-482._2%'
                                               )
               LEFT JOIN komplikation k    ON k.eingriff_id=ei.eingriff_id
            WHERE
               ei.erkrankung_id=$erkrankung_id

            GROUP BY
               ei.erkrankung_id

            UNION SELECT
               COUNT(DISTINCT u.untersuchung_id)                                           AS ges_koloskopie,
               COUNT(DISTINCT IF(u.koloskopie_vollstaendig='1', u.untersuchung_id, NULL))  AS tot_koloskopie,
               0                                                                           AS ther_koloskopie,
               0                                                                           AS ther_koloskopie_kompl,
               0                                                                           AS unv_stenosierende_koloskopie,
               COUNT(DISTINCT d.untersuchung_id)                                           AS polyp_nachweis,
               0                                                                           AS polypektomie,
               0                                                                           AS polyp_op_gebiet,
               0                                                                           AS polypektomie_polyp

            FROM
               untersuchung u
               LEFT JOIN diagnose d    ON u.untersuchung_id=d.untersuchung_id
                                           AND (d.diagnose IN ('K62.0', 'K62.1', 'K62.5'))
            WHERE
               u.erkrankung_id=$erkrankung_id
               AND (
                   u.art LIKE '1-650%'
                   OR u.art='1-652.1'
               )

            GROUP BY
               u.erkrankung_id
        ) x
      ";
      $result = end( sql_query_array( $this->_db, $query ) );

      /*
       * Das SQL liefert ein vollständiges Array, dessen Elemente alle leer sind, wenn das SQL kein Ergebnis liefert.
       * Daher reicht hier die Prüfung auf ein Element des Arrays.
       */
      if( ! strlen( $result['ges_koloskopie'] ) ) {
            $result['ges_koloskopie'] = 0;
            $result['tot_koloskopie'] = 0;
            $result['ther_koloskopie'] = 0;
            $result['ther_koloskopie_kompl'] = 0;
            $result['unv_stenosierende_koloskopie'] = 0;
            $result['polyp_nachweis'] = 0;
            $result['polypektomie'] = 0;
            $result['polyp_op_gebiet'] = 0;
            $result['polypektomie_polyp'] = 0;
      }
      return $result;
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetHistologien( $erkrankung_id )
   {
      $histologien = array();
      $node = array();
      $query = "
        SELECT
            histologie_id,
            h.msi                   AS msi,
            NULL                    AS immunhistochemie,
            CASE h.kras
                WHEN 'wild' THEN 1
                WHEN 'mut' THEN 0
                ELSE NULL
            END                     AS k_ras_wildtyp,
            NULL                    AS who_klassifikation,
            NULL                    AS multifokal,
            NULL                    AS wuchsform,
            NULL                    AS wuchsformtyp

        FROM
            histologie h

        WHERE
            h.erkrankung_id=$erkrankung_id

      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $histologie ) {
            $node = array();
            foreach( $histologie as $key => $value ) {
               $node[ $key ] = $value;
            }
            $node[ 'tumorhistologien' ] = $this->GetTumorhistologien( $histologie['histologie_id'] );
            if ( ( count( $node[ 'tumorhistologien' ] ) > 0 ) &&
                 ( strlen( $node[ 'tumorhistologien' ][ 0 ][ 'morpho_code' ] ) > 0 ) &&
                 ( strlen( $node[ 'tumorhistologien' ][ 0 ][ 'topologie_code' ] ) > 0 ) ) {
               $histologien[] = $node;
            }
         }
      }

      return $histologien;
   }

   protected function GetTumorhistologien( $histologie_id )
   {
      $tumorhistologien = array();
      $node = array();
      $query = "
         SELECT
            ts.tumorstatus_id,
            NULL                                            AS definitive_morphologie,
            h.morphologie                                   AS morpho_code,
            h.morphologie_text                              AS morpho_text,
            IFNULL(ts.lokalisation, ts.lokalisation_calc)   AS topologie_code,
            ts.lokalisation_text                            AS topologie_text,
            h.datum                                         AS histo_datum,
            '301'                                           AS icdo_version,
            SUBSTRING( h.pt, 3)                             AS t,
            SUBSTRING( h.pn, 3)                             AS n,
            SUBSTRING( h.pm, 3)                             AS m,
            IF(h.ptnm_praefix LIKE '%y%', 1, IF(h.ptnm_praefix IS NULL, NULL, 0))   AS y,
            grading.code_wdc                                AS g,
            h.r                                             AS r,
            h.l                                             AS l,
            h.v                                             AS v,
            h.ppn                                           AS pn,
            IF(h.pt IS NOT NULL OR h.pn IS NOT NULL OR h.pm IS NOT NULL, '503', NULL)  AS tnm_version,
            uicc.code_wdc                                   AS stadiengruppierung_uicc

        FROM
            histologie h
            LEFT JOIN (
                SELECT *,
                CASE diagnose
                    WHEN 'C19' THEN 'C19.9'
                    WHEN 'C20' THEN 'C20.9'
                    ELSE diagnose
                END AS lokalisation_calc
                FROM tumorstatus
                ORDER BY
                    datum_sicherung
            ) ts    ON h.erkrankung_id=ts.erkrankung_id

            LEFT JOIN l_exp_wdc grading ON grading.klasse='grading'
                                            AND h.g=grading.code_med
            LEFT JOIN l_exp_wdc uicc    ON uicc.klasse='uicc'
                                            AND ts.uicc=uicc.code_med

        WHERE
            h.histologie_id=$histologie_id

        GROUP BY
            h.histologie_id

        LIMIT 0, 1
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $tumorhistologie ) {
            $node = array();
            foreach( $tumorhistologie as $key => $value ) {
               if ( ( $key == 'morpho_text' ) ||
                    ( $key == 'topologie_text' ) ) {
                  $node[ $key ] = $this->TrimString( $value, $this->_trim_length );
               }
               else {
                  $node[ $key ] = $value;
               }
            }
            $node[ 't' ] = $this->TrimTNM( $node[ 't' ] );
            $node[ 'n' ] = $this->TrimTNM( $node[ 'n' ] );
            $node[ 'm' ] = $this->TrimTNM( $node[ 'm' ] );
            $node[ 'metastasen_orte' ] = $this->GetMetastasenOrte( $tumorhistologie['tumorstatus_id'] );
            $tumorhistologien[] = $node;
         }
      }
      return $tumorhistologien;
   }

   protected function GetMetastasenOrte( $tumorstatus_id )
   {
      if ( ! strlen( $tumorstatus_id ) ) {
          return array();
      }
      $query = "
        SELECT DISTINCT
            lok.code_wdc AS metastasen_ort

        FROM
            tumorstatus_metastasen tsm
            INNER JOIN l_exp_wdc lok    ON lok.klasse='metastasenort'
                                            AND tsm.lokalisation=lok.code_med

        WHERE
            tsm.tumorstatus_id=$tumorstatus_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
          return array();
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapien( $erkrankung_id )
   {
      $therapien = array();

      $therapien[0][ 'ops' ] = $this->GetTherapieOps( $erkrankung_id );
      $therapien[0][ 'operations' ] = $this->GetTherapieOperations( $erkrankung_id );
      $therapien[0][ 'systemtherapien' ] = $this->GetTherapieSystemtherapien( $erkrankung_id );
      $therapien[0][ 'strahlentherapien' ] = $this->GetTherapieStrahlentherapien( $erkrankung_id );
      return $therapien;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieOps( $erkrankung_id )
   {
      $query = "
         SELECT
            eops.prozedur                      AS ops_code,
            eops.prozedur_text                 AS ops_text,
            DATE_FORMAT( e.datum, '%Y-%m-%d' ) AS ops_datum,
            NULL                               AS ops_version

         FROM
            eingriff_ops eops
            INNER JOIN eingriff e ON e.eingriff_id=eops.eingriff_id

         WHERE
            eops.erkrankung_id=$erkrankung_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      $i = 0;
      foreach( $result as $row ) {
         $result[ $i ][ 'ops_text' ] = $this->TrimString( $row[ 'ops_text' ], $this->_trim_length );
         $i++;
      }
      return $result;
   }

   /**
    *
    * @param array $data
    * @return arrray
    */
   protected function GetTherapieOperations( $erkrankung_id )
   {
      $operations = array();
      $node = array();
      $query = "
         SELECT
            e.eingriff_id,
            IF(e.art_primaertumor='1', 1, 0)        AS ersteingriff,
            DATE_FORMAT( e.datum, '%Y-%m-%d' )      AS op_datum,
            CASE
                WHEN e.art_primaertumor='1' THEN 1
                WHEN e.art_revision='1'     THEN 2
                ELSE                             4
            END                                     AS op_typ,
            IF(e.notfall='1', 2, 1)                 AS op_notfalltyp,
            CASE
                WHEN e.intention='kur'  	THEN 1
                WHEN e.intention='pal'  	THEN 2
                WHEN e.art_diagnostik='1'   THEN 3
            END                                     AS op_intention,
            CASE
                WHEN a.todesdatum IS NULL                               THEN 1
                WHEN DATEDIFF(a.todesdatum, e.datum) BETWEEN 0 AND 30   THEN 2
                WHEN DATEDIFF(a.todesdatum, e.datum)>30                 THEN 3
                ELSE                                                         4
            END                                     AS op_letalitaet,
            lokal_r.code_wdc		                AS pathohistologisch_lokaler_r_status,
            e.asa                                   AS asa_score,
            IF(e.tme='1', 1, 0)                     AS mesorektumexstirpation,
            CASE
                WHEN GROUP_CONCAT(eio.prozedur) LIKE '%5-452.2%'    THEN 1
                WHEN GROUP_CONCAT(eio.prozedur) LIKE '%5-452.5%'    THEN 1
                WHEN GROUP_CONCAT(eio.prozedur) LIKE '%5-456.2%'    THEN 2
                ELSE                                                     4
            END                                     AS regionale_operationsverfahren,
            IFNULL(h.mercury, 0)                    AS mercury,
            IF(
                GROUP_CONCAT(eio.prozedur) LIKE '%5-501%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-502%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-509%'
            ,
                IF(
                    COUNT(ts.therapie_systemisch_id)>0
                ,
                    2
                ,
                    1
                )
            ,
                3
            )                                       AS leberresektion,
            IF(
                GROUP_CONCAT(eio.prozedur) LIKE '%5-455._1%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455._4%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455._5%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-456._1%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-456._4%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-456._5%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458._1%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458._4%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458._5%'
            ,
                0
            ,
                1
            )                                       AS ohne_anastomose

         FROM
            eingriff e
            LEFT JOIN histologie h             ON e.eingriff_id=h.eingriff_id
            LEFT JOIN abschluss a               ON e.patient_id=a.patient_id
            LEFT JOIN l_exp_wdc lokal_r     	ON lokal_r.klasse='lokal_r'
                                                    AND h.r=lokal_r.code_med
            LEFT JOIN eingriff_ops eio          ON e.eingriff_id=eio.eingriff_id
            LEFT JOIN therapie_systemisch ts    ON e.erkrankung_id=ts.erkrankung_id
                                                    AND ts.vorlage_therapie_art LIKE 'c%'
                                                    AND ts.beginn<e.datum
			LEFT JOIN patient p 			    ON p.patient_id=e.patient_id

         WHERE
            e.erkrankung_id=$erkrankung_id

         GROUP BY
            e.eingriff_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $operation ) {
            $node = array();
            foreach( $operation as $key => $value ) {
               $node[ $key ] = $value;
            }
            //$node[ 'operativer_sicherheitsabstand' ] = $this->GetOperativerSicherheitsabstand( $operation[ 'eingriff_id' ] );
            $tmp = $this->GetCircumferentiellerTumorrand( $operation[ 'eingriff_id' ] );
            if ( ( $tmp !== false ) &&
                 is_array( $tmp ) &&
                 ( count( $tmp ) > 0 ) &&
                 ( strlen( $tmp[ 'abstand_resektionsraender_distal' ] ) > 0 ) &&
                 ( strlen( $tmp[ 'abstand_resektionsraender_lateral' ] ) > 0 ) ) {
               $node[ 'circumferentieller_tumorrand' ] = $tmp;
            }
            else { // $zk: FIX für Ticket #5036
                $node[ 'circumferentieller_tumorrand' ][ 'abstand_resektionsraender_distal' ] = "";
                $node[ 'circumferentieller_tumorrand' ][ 'abstand_resektionsraender_lateral' ] = "";
            }
            $node[ 'op_komplikationen' ] = $this->GetOpKomplikationen( $operation[ 'eingriff_id' ] );
            $node[ 'stoma' ] = $this->GetStoma( $operation[ 'eingriff_id' ] );
            $node[ 'lymphknoten' ] = $this->GetLymphknoten( $operation[ 'eingriff_id' ] );
            $node[ 'transfusionen' ] = $this->GetTransfusionen( $operation[ 'eingriff_id' ] );
            $node[ 'op_personal' ] = $this->GetOpPersonal( $operation[ 'eingriff_id' ] );
            if ( strlen( $node[ 'pathohistologisch_lokaler_r_status' ] ) > 0 ) {
               $operations[] = $node;
            }
         }
      }
      return $operations;
   }

   /*
   protected function GetOperativerSicherheitsabstand( $eingriff_id )
   {
        // nicht relevant für Benchmarking
        $result = array();
        $result[ 'lateral' ] = '';
        $result[ 'distal' ] = '';
        return $result;
   }
   */

   protected function GetCircumferentiellerTumorrand( $eingriff_id )
   {
      $query = "
         SELECT
            h.resektionsrand_aboral     AS abstand_resektionsraender_distal,
            h.resektionsrand_lateral    AS abstand_resektionsraender_lateral

         FROM
            histologie h

         WHERE
            h.eingriff_id=$eingriff_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      return $result;
   }

   protected function GetOpKomplikationen( $eingriff_id )
   {
      $query = "
         SELECT
            CASE
                WHEN k.revisionsoperation='1'   THEN 2
                WHEN k.revisionsoperation='0'   THEN 1
                ELSE                                 4
            END                             AS op_komplikationsgrad,
            op_komplikationsart.code_wdc    AS op_komplikationsart

         FROM
            komplikation k
            INNER JOIN l_exp_wdc op_komplikationsart    ON op_komplikationsart.klasse='komplikation'
                                                            AND k.komplikation=op_komplikationsart.code_med

         WHERE
            k.eingriff_id=$eingriff_id

      ";


      $result = sql_query_array( $this->_db, $query );
      if ( $result === false || ( is_array( $result ) && count( $result ) == 0 ) ) {
         $result = array();
         $result[0][ 'op_komplikationsgrad' ] = '5';
         $result[0][ 'op_komplikationsart' ] = '0';
      }
      return $result;
   }

   protected function GetStoma( $eingriff_id )
   {
      $query = "
        SELECT
            IF(
                GROUP_CONCAT(eio.prozedur) LIKE '%5-455._2%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455._3%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455._6%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-456._0%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-456._7%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458._2%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-460%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-461%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-462%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-463.2%'
            ,
                1
            ,
                0
            )                                                   AS kuenstl_darmausgang,
            IF(GROUP_CONCAT(eio.prozedur) LIKE '%5-462%', 1, 0) AS stoma_protektiv

        FROM
            eingriff_ops eio

        WHERE
            eio.eingriff_id=$eingriff_id

        GROUP BY
            eio.eingriff_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( $result === false ) {
         $result = array();
         $result[ 'kuenstl_darmausgang' ] = '';
         $result[ 'stoma_protektiv' ] = '';
      }
      return $result;
   }

   protected function GetLymphknoten( $eingriff_id )
   {
      $query = "
         SELECT
            IF(
                GROUP_CONCAT(eio.prozedur) LIKE '%5-400%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-401%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-402%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-406%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-407.2%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-407.3%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-407.4%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-407.x%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-407.y%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-408.5%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455.4%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-455.6%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458.0%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458.1%'
                OR GROUP_CONCAT(eio.prozedur) LIKE '%5-458.5%'
            ,
                1
            ,
                0
            )                       AS lymphknoten_extirpation,
            IFNULL(h.lk_entf, 0)    AS anz_entf_lymphknoten_histo,
            h.lk_bef                AS anz_entf_lymphknoten_pos

         FROM
            eingriff e
            LEFT JOIN eingriff_ops eio  ON e.eingriff_id=eio.eingriff_id
            LEFT JOIN histologie h      ON e.eingriff_id=h.eingriff_id
            LEFT JOIN patient p 		ON p.patient_id=e.patient_id

         WHERE
            e.eingriff_id=$eingriff_id

         GROUP BY
            e.eingriff_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      if ( $result === false ) {
         $result = array();
         $result[ 'lymphknoten_extirpation' ] = '';
         $result[ 'anz_entf_lymphknoten_histo' ] = '';
         $result[ 'anz_entf_lymphknoten_pos' ] = '';
      }
      return $result;
   }

   protected function GetTransfusionen( $eingriff_id )
   {
        // Nicht relevant für Benchmarking
        $result = array();
        $result[ 'anz_transfusionen_intra' ] = '';
        $result[ 'anz_transfusionen_post' ] = '';
        return $result;
   }

   protected function GetOpPersonal( $eingriff_id )
   {
        // Nicht relevant für Benchmarking
        $data = array();
        $data[ 'operateur' ][ 'zentrumsoperateur' ] = '';
        $data[ 'operateur' ][ 'operateur' ] = '';
        $data[ 'erster_assistent' ][ 'zentrumsoperateur' ] = '';
        $data[ 'erster_assistent' ][ 'erster_assistent' ] = '';
        return $data;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieSystemtherapien( $erkrankung_id )
   {
      $systemtherapien = array();
      $node = array();
      $query = "
        SELECT
           systemtherapie.code_wdc              AS systemtherapie,
           vt.bez                               AS protokoll,
           systemtherapie_intention.code_wdc    AS systemtherapie_intention,
           t.beginn                             AS systemtherapie_beg_datum,
           t.ende                               AS systemtherapie_end_datum,
           NULL                                 AS systemtherapie_ergebnis,
           NULL                                 AS systemtherapie_erfolg,
           7                                    AS keine_systemtherapie,
           NULL                                 AS systemtherapie_ext_beg,
           NULL                                 AS systemtherapie_ext_weiter,
           NULL                                 AS chemo_ext,
           NULL                                 AS folfox_capecitabine

        FROM
            therapie_systemisch t
            LEFT JOIN l_exp_wdc systemtherapie              ON systemtherapie.klasse='systemtherapie'
                                                                AND t.vorlage_therapie_art=systemtherapie.code_med
            LEFT JOIN l_exp_wdc systemtherapie_intention    ON systemtherapie_intention.klasse='systemtherapie_intention'
                                                                AND t.intention=systemtherapie_intention.code_med
            LEFT JOIN vorlage_therapie vt                   ON t.vorlage_therapie_id=vt.vorlage_therapie_id

        WHERE
           t.erkrankung_id=$erkrankung_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $systemtherapie ) {
            $node = array();
            foreach( $systemtherapie as $key => $value ) {
               if ( $key == 'protokoll' ) {
                  $node[ $key ] = $this->TrimString( $value, $this->_trim_length );
               }
               else {
                  $node[ $key ] = $value;
               }
            }
            $systemtherapien[] = $node;
         }
      }
      return $systemtherapien;
   }

   /**
    *
    * @param array $data
    * @return array
    */
   protected function GetTherapieStrahlentherapien( $erkrankung_id )
   {
      $strahlentherapien = array();
      $node = array();
      $query = "
        SELECT
            1                                   AS strahlentherapie,
            strahlentherapie_intention.code_wdc AS strahlentherapie_intention,
            s.beginn                            AS strahlentherapie_beg_datum,
            s.ende                              AS strahlentherapie_ende_datum,
            NULL                                AS strahlentherapie_ergebnis,
            7                                   AS keine_strahlentherapie,
            s.gesamtdosis                       AS gesamtdosis,
            s.boostdosis                        AS boost,
            NULL                                AS art_bestrahlung

         FROM
            strahlentherapie s
            LEFT JOIN l_exp_wdc strahlentherapie_intention  ON strahlentherapie_intention.klasse='systemtherapie_intention'
                                                                AND s.intention=strahlentherapie_intention.code_med

         WHERE
            s.erkrankung_id=$erkrankung_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $strahlentherapie ) {
            $node = array();
            foreach( $strahlentherapie as $key => $value ) {
               $node[ $key ] = $value;
            }
            $strahlentherapien[] = $node;
         }
      }
      return $strahlentherapien;
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetLabore( $erkrankung_id )
   {
      $labore = array();

      $query = "
         SELECT
            lw.wert AS 'cea',
            l.datum AS 'cea_datum'

         FROM
            labor l
            INNER JOIN labor_wert lw    ON l.labor_id=lw.labor_id

         WHERE
            l.erkrankung_id=$erkrankung_id
            AND lw.parameter='cea'
            AND lw.wert IS NOT NULL
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false && is_array($result) && count($result)>0 ) {
         $labore[0]['ceas'] = $result;
      }
      return $labore;
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetCeas( $erkrankung_id )
   {
      $query = "
         SELECT
            '' AS cea,
            '' AS datum

         FROM
            eingriff_ops eops
            LEFT JOIN eingriff e ON e.eingriff_id=eops.eingriff_id

         LIMIT 0, 3
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
         $result[ 'cea' ] = '';
         $result[ 'datum' ] = '';
      }
      return $result;
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetNachsorgen( $erkrankung_id )
   {
      $nachsorgen = array();
      $node = array();
      $query = "
         SELECT
            n.datum     AS nachsorge_datum,
            NULL        AS nachsorge_befunde,
            NULL        AS letzter_kontakt

         FROM
            nachsorge_erkrankung ne
            INNER JOIN nachsorge n  ON ne.nachsorge_id=n.nachsorge_id

         WHERE
            ne.erkrankung_weitere_id=$erkrankung_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result !== false ) {
         foreach( $result as $nachsorge ) {
            $node = array();
            foreach( $nachsorge as $key => $value ) {
               $node[ $key ] = $value;
            }
            $node[ 'nachsorge_untersuchungen' ] = $this->GetNachsorgeUntersuchungen( NULL );
            $nachsorgen[] = $node;
         }
      }
      return $nachsorgen;
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetNachsorgeUntersuchungen( $erkrankung_id )
   {
        // nicht relevant für Benchmarking
        return array();
   }

   /**
    *
    * @param $data array
    * @param $export_filter array
    * @return array
    */
   protected function GetFollowUps( $erkrankung_id )
   {
      $query = "
         SELECT
            IF(a.abschluss_grund='lost', 1, 0)                                                  AS lost_follow_up,
            IF(a.abschluss_grund='tot' AND a.tod_tumorassoziation IN ('totn', 'tott'), 1, 0)    AS overall_survival,
            DATEDIFF(MIN(ts_r.datum_sicherung), MIN(ts.datum_sicherung))                        AS disease_free_survival,
            NULL                                                                                AS follow_up_datum

         FROM
            erkrankung e
            LEFT JOIN abschluss a       ON e.patient_id=a.patient_id
            LEFT JOIN tumorstatus ts    ON e.erkrankung_id=ts.erkrankung_id
                                            AND ts.anlass LIKE 'p%'
            LEFT JOIN tumorstatus ts_r    ON e.erkrankung_id=ts_r.erkrankung_id
                                            AND ts_r.anlass LIKE 'r%'

         WHERE
            e.erkrankung_id=$erkrankung_id

         GROUP BY
            e.erkrankung_id
      ";
      $result = sql_query_array( $this->_db, $query );
      if ( $result === false ) {
         $result = array();
      }
      return $result;
   }

}

?>
