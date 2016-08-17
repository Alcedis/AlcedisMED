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

class CGkr1_0_0 extends CMedBaseExport
{

   protected $m_field_len = array( 'GEDA'      => 10,
   								   'NAMG'      => 30,
   								   'VNAG'      => 30,
                                   'GNAG'      => 30,
                                   'FNAG'      => 30,
                                   'TITEL'     => 8,
                                   'SEXG'      => 1,
                                   'MEHRL'     => 1,
                                   'SAN'       => 3,
                                   'STR'       => 55,
                                   'PLZN'      => 5,
                                   'ORTG'      => 30,
                                   'BF1N'      => 30,
                                   'JALAEN'    => 2,
                                   'BF2'       => 30,
                                   'JALET'     => 2,
                                   'KINL'      => 1,
                                   'KINT'      => 1,
                                   'KINF'      => 1,
                                   'BKZG'      => 1,
                                   'VORT'      => 120,
                                   'ANLDIAG'   => 1,
                                   'DIDA'      => 10,
                                   'DTEXT'     => 120,
                                   'ICDZ'      => 5,
                                   'LOKT'      => 120,
                                   'ICT'       => 5,
                                   'LATN'      => 1,
                                   'HFK'       => 120,
                                   'ICM'       => 6,
                                   'ICM_VER'   => 10,
                                   'GRADING'   => 1,
                                   'TAUSB'     => 1,
                                   'STA_VER'   => 1,
                                   'STADIUM'   => 5,
                                   'TNMprae'   => 30,
                                   'TNMprae_a' => 4,
                                   'TNMpost'   => 30,
                                   'TNMpost_a' => 4,
                                   'HDSICH'    => 1,
                                   'CHAT'      => 1,
                                   'OPE'       => 1,
                                   'DMOPE'     => 10,
                                   'STT'       => 1,
                                   'DMSTT'     => 10,
                                   'CHE'       => 1,
                                   'DMCHE'     => 10,
                                   'HOR'       => 1,
                                   'DMHOR'     => 10,
                                   'IMM'       => 1,
                                   'DMIMM'     => 10,
                                   'AND'       => 1,
                                   'BEF'       => 1,
                                   'STDA'      => 10,
                                   'SEK'       => 1,
                                   'T1A'       => 5,
                                   'X1A'       => 50,
                                   'Z1A'       => 10,
                                   'T1B'       => 5,
                                   'X1B'       => 50,
                                   'Z1B'       => 10,
                                   'T1C'       => 5,
                                   'X1C'       => 50,
                                   'Z1C'       => 10,
                                   'T2A'       => 5,
                                   'X2A'       => 50,
                                   'Z2A'       => 10,
                                   'T2B'       => 5,
                                   'X2B'       => 50,
                                   'Z2B'       => 10,
                                   'TUE'       => 4,
                                   'XUE'       => 100,
                                   'QTU'       => 1,
                                   'NNT'       => 1,
                                   'TURS'      => 1,
                                   'MTYP'      => 1,
                                   'UTR'       => 1,
                                   'ANGKR'     => 255,
                                   'EINRICHT'  => 90,
                                   'ABT'       => 90,
                                   'PLZ_E'     => 5,
                                   'ORT_E'     => 30,
                                   'STR_E'     => 55,
                                   'NAMN'      => 50,
                                   'TELN'      => 20,
                                   'DMN'       => 10,
                                   'REFNR'     => 7 );

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_gkr.conf', 'export_gkr' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();
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
      $export_filter[ 'user_id' ] = isset( $session[ 'sess_user_id' ] ) ? $session[ 'sess_user_id' ] : '-1';
      $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
      $export_filter[ 'format_date' ] = '%Y%d%m';
      $export_filter[ 'format_date_app' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $export_filter[ 'gkr_file_suffix' ] = isset( $this->_config[ 'exp_gkr_file_suffix' ] ) ? $this->_config[ 'exp_gkr_file_suffix' ] : '.txt';
      $export_filter[ 'gkr_kennung' ] = isset( $this->_config[ 'exp_gkr_kennung' ] ) ? $this->_config[ 'exp_gkr_kennung' ] : 'ALCE';
      $ext_dir = isset( $this->_config[ 'exp_gkr_dir' ] ) ? $this->_config[ 'exp_gkr_dir' ] : 'gkr/';
      $ext_log_subdir = isset( $this->_config[ 'exp_gkr_log_subdir' ] ) ? $this->_config[ 'exp_gkr_log_subdir' ] : 'log/';
      $ext_tmp_subdir = isset( $this->_config[ 'exp_gkr_tmp_subdir' ] ) ? $this->_config[ 'exp_gkr_tmp_subdir' ] : 'tmp/';
      $ext_xml_subdir = isset( $this->_config[ 'exp_gkr_xml_subdir' ] ) ? $this->_config[ 'exp_gkr_xml_subdir' ] : 'xml/';
      $ext_zip_subdir = isset( $this->_config[ 'exp_gkr_zip_subdir' ] ) ? $this->_config[ 'exp_gkr_zip_subdir' ] : 'zip/';
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
      if ( isset( $request[ 'sel_erstmedlung_erneut' ] ) ) {
         $export_filter[ 'erstmedlung_erneut' ] = ( $request[ 'sel_erstmedlung_erneut' ] == '0' ) ? false : true;
      }
      else {
         $export_filter[ 'erstmedlung_erneut' ] = false;
      }
      // export_id bestimmen
      $export_filter[ 'export_id' ] = dlookup( $this->_db, 'exp_gkr_log', 'IFNULL( MAX( export_id ) + 1, 1 )', '1' );
      return $export_filter;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilename()
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = $this->_xml_dir . 'gkr_export_' . date( 'YmdHis' );
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
      $newline       = "\r\n"; // Wird von Smarty escaped und darf daher nicht in config stehen!

      $result = array( 'valid' => array(),
                       'invalid' => array() );
      $i = 0;
      $bez_arr = array();
      foreach( $content as $ekr ) {
         $bez = $this->GetPatientBez( $ekr[ 'patient_id' ], $export_filter );
         if ( !in_array( $bez, $bez_arr ) ) {
            $result[ 'valid' ][ $i ][ 'patient_id' ] = $ekr[ 'patient_id' ];
            $result[ 'valid' ][ $i ][ 'bez' ] = $bez;
            $bez_arr[] = $bez;
            $i++;
         }
         $this->WriteDbLog( $ekr[ 'ekr_id' ], $export_filter );
      }

      $lines = array();
      foreach( $content as $record ) {
         // Datenzeile mit fester Länge generieren
         $cur_line = '';
         foreach( $this->m_field_len as $name => $length ) {
            if ( isset( $record[ $name ] ) ) {
               $data = $record[ $name ];
            }
            else {
               $data = "";
            }
            $cur_line .= substr( str_pad( $data, $length ), 0, $length );
         }
         $lines[] = $cur_line;
      }

      umask( 0002 );
      $fixed_file = $this->_tmp_dir . "gkr" . $export_filter[ 'gkr_file_suffix' ];
      $fp = fopen( $fixed_file, 'w' );
      if ( !$fp ) {
         die( 'fixed-Datei konnte nicht zum Schreiben geöffnet werden!' );
      }
      foreach( $lines as $cur_line ) {
         fwrite( $fp, $cur_line . $newline );
      }
      fclose( $fp );

      // Nur zum Exportformat prüfen!!!
      if ( 0 ) {
         $this->CreateCvsFileCheck( $fixed_file );
      }

      $files[] = $fixed_file;

      $cnt_patient_valid = $i;
      $cnt_patient_invalid = 0;

      $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $this->_config[ 'info_patienten_valid' ] );
      $info_patienten_invalid = str_replace( '#anzahl#', $cnt_patient_invalid, $this->_config[ 'info_patienten_invalid' ] );

      $gkr_name = $export_filter[ 'gkr_kennung' ] . date( 'my' );
      $xml_file = $filename;
      $zip_file = $this->_zip_dir . $gkr_name . date( 'YmdHis' ) . ".zip";

      $zip = new PclZip( $zip_file );
      $zip_create = $zip->create( $files, PCLZIP_OPT_REMOVE_ALL_PATH );

      $zip_url = "index.php?page=export_gkr&action=download&type=zip&file=" . $zip_file;

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

   /**
    *
    * @param $ekr_id
    * @param $export_filter
    * @return unknown_type
    */
   protected function WriteDbLog( $ekr_id, $export_filter ) {
      $query = '
         INSERT INTO exp_gkr_log
            VALUES ( "",
                     "' . $export_filter[ 'export_id' ]   . '",
                     "' . $ekr_id                         . '",
                     "1",
         			 "",
                     "' . $export_filter[ 'org_id' ]      . '",
                     "' . $export_filter[ 'von' ]         . '",
                     "' . $export_filter[ 'bis' ]         . '",
                      ' . $export_filter[ 'user_id' ]     . ',
                     "' . date('Y-m-d H:i:s')             . '"
            )
      ';
      $erg = mysql_query( $query, $this->_db );
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#ExtractData()
    */
   protected function ExtractData( $export_filter )
   {
   	  $data = array();
   	  // Erkrankungen holen
      $query = "
         SELECT
            e.ekr_id,
            ts.tumorstatus_id

         FROM
            ekr e
            INNER JOIN patient p      ON e.patient_id=p.patient_id
            INNER JOIN ( SELECT
                            *

                         FROM
                            tumorstatus

                         WHERE
                            LEFT( anlass, 1 )='p'
                            AND ( diagnose LIKE 'C%'
                                  OR diagnose LIKE 'D0%'
                                  OR diagnose IN ( 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2',
                                                   'D33.3', 'D33.4', 'D33.5', 'D33.6', 'D33.7', 'D33.8', 'D33.9',
                                                   'D35.2', 'D35.4' )
                                  OR diagnose LIKE 'D37%'
                                  OR diagnose LIKE 'D38%'
                                  OR diagnose LIKE 'D39%'
                                  OR diagnose LIKE 'D4%' )
                            AND ( RIGHT( morphologie, 2 ) IN ( '/1', '/2', '/3', '/6', '/9' )
                                  OR ( RIGHT( morphologie, 2 ) = '/0'
                                       AND diagnose IN ( 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2',
                                                         'D33.3', 'D33.4', 'D33.5', 'D33.6', 'D33.7', 'D33.8', 'D33.9',
                                                         'D35.2', 'D35.4' ) ) )

                         ORDER BY
                            datum_beurteilung DESC

                         ) ts ON ts.erkrankung_id=e.erkrankung_id

         WHERE
            e.datum BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'
            AND p.org_id={$export_filter[ 'org_id' ]}

         GROUP BY
            e.ekr_id
      ";
      $result = sql_query_array( $this->_db, $query );
      // Wenn keine Daten verfügbar sind, hier raus springen
      if ( !count( $result ) ) {
         return array();
      }
      foreach( $result AS $row ) {
         $data[] = $this->getEkrData( $row[ 'ekr_id' ], $row[ 'tumorstatus_id' ], $export_filter );
      }
      return $data;
   }

   /**
    * Datengewinnung
    * @param $ekr_id
    * @return unknown_type
    */
   protected function getEkrData( $ekr_id, $tumorstatus_id, $export_filter )
   {
      // Daten
      $query = "
         SELECT
         	p.patient_id,
         	ekr.erkrankung_id,
	        ekr_id,
            DATE_FORMAT( p.geburtsdatum, '%d.%m.%Y' )                                      AS 'GEDA',
            p.nachname                                                                     AS 'NAMG',
            p.vorname                                                                      AS 'VNAG',
            p.geburtsname                                                                  AS 'GNAG',
            p.titel                                                                        AS 'TITEL',
            p.geschlecht						                                           AS 'SEXG',
            an.mehrlingseigenschaften													   AS 'MEHRL',
            IFNULL( p.staat, 'X' )                                                         AS 'SAN',
            CONCAT_WS( ' ', p.strasse, p.hausnr )                                          AS 'STR',
            p.plz                                                                          AS 'PLZN',
            p.ort                                                                          AS 'ORTG',
            an.beruf_laengster															   AS 'BF1N',
            an.beruf_laengster_dauer													   AS 'JALAEN',
            an.beruf_letzter															   AS 'BF2',
            an.beruf_letzter_dauer														   AS 'JALET',
            IF( an.geburten_lebend > 9, 9, IFNULL( an.geburten_lebend, 'x' ) )			   AS 'KINL',
            IF( an.geburten_tot > 9, 9, IFNULL( an.geburten_tot, 'x' ) )				   AS 'KINT',
            IF( an.geburten_fehl > 9, 9, IFNULL( an.geburten_fehl, 'x' ) )				   AS 'KINF',
            IFNULL( an.anamnese_id, '' )                                                   AS 'VORT',
   			IFNULL( diagnoseanlass.code_gkr, 'x' )                                         AS 'ANLDIAG',
   			DATE_FORMAT( ts_n.datum_sicherung, '%d.%m.%Y' ) 		   					   AS 'DIDA',
            ts_n.diagnose_text								                               AS 'DTEXT',
   			ts_n.diagnose																   AS 'ICDZ',
   			ts_n.lokalisation_text									                       AS 'LOKT',
   			IFNULL( ts_n.lokalisation, d_vs_l.lokalisation_code )                          AS 'ICT',
            IFNULL( diagnose_seite.code_gkr, 'x' )										   AS 'LATN',
            ts_n.morphologie_text			    										   AS 'HFK',
            IF( ts_n.diagnose IN ( 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2',
            					   'D33.3', 'D33.4', 'D33.5', 'D33.6', 'D33.7', 'D33.8',
            					   'D33.9', 'D35.2', 'D35.4' ),
                ts_n.morphologie,
                IF( RIGHT( ts_n.morphologie, 2 ) = '/0',
                    NULL,
                    ts_n.morphologie ) )												   AS 'ICM',
            'ICD-O-3'																	   AS 'ICM_VER',
   			IFNULL( grading.code_gkr, 'x' )												   AS 'GRADING',
   			CASE
               WHEN ts_n.m IS NOT NULL AND
               		ts_n.m NOT LIKE '%M0%' AND
                    ts_n.m NOT LIKE '%MX'  							THEN 'f'
               WHEN ts_n.n IS NOT NULL AND
               		ts_n.n NOT LIKE '%N0%' AND
                    ts_n.n NOT LIKE '%NX'  							THEN 'r'
               ELSE                                                 	 'l'
            END                                                                            AS 'TAUSB',
            CASE
               WHEN ts_n.ann_arbor_stadium IS NOT NULL   			THEN 'a'
               WHEN ts_n.cll_rai IS NOT NULL	   					THEN 'r'
               WHEN ts_n.cll_binet IS NOT NULL   					THEN 'b'
               WHEN ts_n.aml_fab IS NOT NULL	   					THEN 'f'
               WHEN ts_n.durie_salmon IS NOT NULL   				THEN 'm'
               WHEN ts_n.gleason1 IS NOT NULL	   					THEN 'g'
               WHEN ts_n.uicc IS NOT NULL							THEN 'u'
               ELSE                                                 	 NULL
            END																			   AS 'STA_VER',
            CASE
               WHEN ts_n.ann_arbor_stadium IS NOT NULL   			THEN
                  CONCAT_WS( '', IF( LOCATE( '/', ts_n.ann_arbor_stadium ) > 0,
                      				 LEFT( ts_n.ann_arbor_stadium,
                      				    LOCATE( '/', ts_n.ann_arbor_stadium ) - 1 ),
                      					ts_n.ann_arbor_stadium ),
                      				 ts_n.ann_arbor_aktivitaetsgrad,
                      				 ts_n.ann_arbor_extralymphatisch )
               WHEN ts_n.cll_rai IS NOT NULL 			  			THEN ts_n.cll_rai
               WHEN ts_n.cll_binet IS NOT NULL  		 			THEN ts_n.cll_binet
               WHEN ts_n.aml_fab IS NOT NULL   						THEN ts_n.aml_fab
               WHEN ts_n.durie_salmon IS NOT NULL   				THEN ts_n.durie_salmon
               WHEN ts_n.gleason1 IS NOT NULL			   			THEN
                  CONCAT_WS( '', ts_n.gleason1, '+',
                      			 ts_n.gleason2, '=',
                      			 ts_n.gleason1 + ts_n.gleason2 )
               WHEN ts_n.uicc IS NOT NULL							THEN ts_n.uicc
               ELSE                                                 	 NULL
            END                                                                            AS 'STADIUM',
            IF( ts_tnm_c.t IS NOT NULL,
                CONCAT_WS( '', ts_tnm_c.tnm_praefix,
                			   ts_tnm_c.t, ts_tnm_c.n, ts_tnm_c.m,
                			   l_c.bez, v_c.bez, ppn_c.bez ),
                NULL ) 																	   AS 'TNMprae',
            IF( ts_tnm_p.t IS NOT NULL,
                CONCAT_WS( '', ts_tnm_p.tnm_praefix,
                			   ts_tnm_p.t, ts_tnm_p.n, ts_tnm_p.m,
                			   l_p.bez, v_p.bez, ppn_p.bez ),
                NULL ) 																	   AS 'TNMpost',
			IF( COUNT( h.histologie_id ) > 0, 'h', 'k' )								   AS 'HDSICH',
			chat.code_gkr																   AS 'CHAT',
			IF( COUNT( eg.eingriff_id ) > 0, 'J', 'N' )  								   AS 'OPE',
			DATE_FORMAT( eg.datum, '%d.%m.%Y' )		                                       AS 'DMOPE',
            IF( COUNT( st.strahlentherapie_id ) > 0, 'J', 'N' )							   AS 'STT',
            DATE_FORMAT( st.beginn, '%d.%m.%Y' )		                                   AS 'DMSTT',
            if( COUNT( tsys_che.therapie_systemisch_id ) > 0, 'J', 'N' )				   AS 'CHE',
            DATE_FORMAT( tsys_che.beginn, '%d.%m.%Y' )		                               AS 'DMCHE',
            if( COUNT( tsys_hor.therapie_systemisch_id ) > 0, 'J', 'N' )				   AS 'HOR',
            DATE_FORMAT( tsys_hor.beginn, '%d.%m.%Y' )		                               AS 'DMHOR',
            if( COUNT( tsys_imm.therapie_systemisch_id ) > 0, 'J', 'N' )				   AS 'IMM',
            DATE_FORMAT( tsys_imm.beginn, '%d.%m.%Y' )		                               AS 'DMIMM',
            IF( COUNT( tsys_and.therapie_systemisch_id ) > 0 OR
                COUNT( sth.sonstige_therapie_id ) > 0, 'a', 'x' )						   AS 'AND',
            CASE
               WHEN ab.abschluss_grund='tot'   	THEN 'v'
               WHEN ab.abschluss_grund='lost'   THEN 'x'
               ELSE                                  'l'
            END                                                                            AS 'BEF',
            DATE_FORMAT( ab.todesdatum, '%d.%m.%Y' )									   AS 'STDA',
            IFNULL( autopsie.code_gkr, NULL )											   AS 'SEK',
            ab.tod_ursache																   AS 'T1A',
            ab.tod_ursache_text															   AS 'X1A',
            IF( ab.abschluss_grund='tot', 'x', NULL )									   AS 'QTU',
            IF( ab.abschluss_grund='tot', turs.code_gkr, NULL )							   AS 'TURS',
            meldetyp.code_gkr															   AS 'MTYP',
            ekr.meldebegruendung														   AS 'UTR',
            ekr.mitteilung																   AS 'ANGKR',
            o.name																		   AS 'EINRICHT',
            o.plz																		   AS 'PLZ_E',
            o.ort																		   AS 'ORT_E',
            CONCAT_WS( ' ', o.strasse, o.hausnr )										   AS 'STR_E',
            CONCAT_WS( ' ', u.titel, u.vorname, u.nachname )							   AS 'NAMN',
            u.telefon																	   AS 'TELN',
            DATE_FORMAT( ekr.datum, '%d.%m.%Y' )  										   AS 'DMN',
            RIGHT( p.patient_nr, 7 )													   AS 'REFNR'

         FROM
            ekr
            INNER JOIN erkrankung e	                       ON e.erkrankung_id=ekr.erkrankung_id
            INNER jOIN patient p	                       ON p.patient_id=e.patient_id
            LEFT JOIN user u                               ON u.user_id=ekr.user_id
            LEFT JOIN org o                                ON p.org_id=o.org_id
            LEFT JOIN tumorstatus ts_n                     ON ts_n.tumorstatus_id=$tumorstatus_id
            LEFT JOIN ( SELECT
                           erkrankung_id,
                           datum_sicherung,
            			   t,
                           n,
                           m,
                           l,
                           v,
                           ppn,
                           tnm_praefix

                        FROM
            			   tumorstatus

            			ORDER BY
            			   datum_sicherung ASC
            		  ) ts_tnm_c				   		   ON LEFT( ts_tnm_c.t, 1 )='c'
            		                                          AND ts_tnm_c.erkrankung_id=ekr.erkrankung_id
             LEFT JOIN ( SELECT
                           erkrankung_id,
                           datum_sicherung,
            			   t,
                           n,
                           m,
                           l,
                           v,
                           ppn,
                           tnm_praefix

                        FROM
            			   tumorstatus

            			ORDER BY
            			   datum_sicherung DESC
            		  ) ts_tnm_p				   		   ON LEFT( ts_tnm_p.t, 1 )='p'
            		  										  AND ts_tnm_p.erkrankung_id=ekr.erkrankung_id
			LEFT JOIN l_basic l_c						   ON l_c.klasse='l'
                                                              AND l_c.code=ts_tnm_c.l
			LEFT JOIN l_basic v_c						   ON v_c.klasse='v'
                                                              AND v_c.code=ts_tnm_c.v
            LEFT JOIN l_basic ppn_c						   ON ppn_c.klasse='ppn'
                                                              AND ppn_c.code=ts_tnm_c.ppn
			LEFT JOIN l_basic l_p						   ON l_p.klasse='l'
                                                              AND l_p.code=ts_tnm_p.l
			LEFT JOIN l_basic v_p						   ON v_p.klasse='v'
                                                              AND v_p.code=ts_tnm_p.v
            LEFT JOIN l_basic ppn_p						   ON ppn_p.klasse='ppn'
                                                              AND ppn_p.code=ts_tnm_p.ppn
            LEFT JOIN l_exp_gkr diagnose_seite             ON diagnose_seite.klasse='diagnose_seite'
                                                              AND diagnose_seite.code_med=ts_n.diagnose_seite
            LEFT JOIN l_exp_gkr grading                    ON grading.klasse='grading'
                                                              AND grading.code_med=ts_n.g
            LEFT JOIN ( SELECT
                     	   erkrankung_id,
                     	   anamnese_id,
                     	   mehrlingseigenschaften,
                     	   beruf_laengster,
                     	   beruf_laengster_dauer,
                     	   beruf_letzter,
                     	   beruf_letzter_dauer,
                     	   geburten_lebend,
                     	   geburten_tot,
                     	   geburten_fehl,
                     	   entdeckung,
                     	   datum

                     	FROM
                     	   anamnese

                     	ORDER BY
                     	   datum DESC
            		  ) an                                 ON an.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN l_exp_gkr diagnoseanlass             ON diagnoseanlass.klasse='diagnoseanlass'
                                                              AND diagnoseanlass.code_med=an.entdeckung
            LEFT JOIN l_exp_gkr meldetyp       		       ON meldetyp.klasse='meldetyp'
                                                              AND meldetyp.code_med=ekr.meldetyp
            LEFT JOIN abschluss ab                         ON ab.patient_id=p.patient_id
            LEFT JOIN l_exp_gkr autopsie                   ON autopsie.klasse='jn'
                                                              AND autopsie.code_med=ab.autopsie
            LEFT JOIN l_exp_gkr turs                       ON turs.klasse='turs'
                                                              AND turs.code_med=ab.tod_tumorassoziation
            LEFT JOIN histologie h                         ON h.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN ( SELECT
            			   eingriff_id,
            			   erkrankung_id,
            			   art_primaertumor,
            			   datum

            			FROM
            			   eingriff

            			WHERE
            			   art_primaertumor=1

            			ORDER BY
            			   datum ASC ) eg                  ON eg.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN ( SELECT
            			   strahlentherapie_id,
            			   erkrankung_id,
            			   beginn

            			FROM
            			   strahlentherapie

            			ORDER BY
            			   beginn ASC ) st				   ON st.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN ( SELECT
                           therapie_systemisch_id,
            			   erkrankung_id,
            			   beginn,
            			   vorlage_therapie_art

            			FROM
            			   therapie_systemisch

            			WHERE
            			   POSITION( 'c' IN vorlage_therapie_art ) > 0

            			ORDER BY
            			   beginn ASC ) tsys_che           ON tsys_che.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN ( SELECT
                           therapie_systemisch_id,
            			   erkrankung_id,
            			   beginn,
            			   vorlage_therapie_art

            			FROM
            			   therapie_systemisch

            			WHERE
            			   POSITION( 'ah' IN vorlage_therapie_art ) > 0

            			ORDER BY
            			   beginn ASC ) tsys_hor           ON tsys_hor.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN ( SELECT
                           therapie_systemisch_id,
            			   erkrankung_id,
            			   beginn,
            			   vorlage_therapie_art

            			FROM
            			   therapie_systemisch

            			WHERE
            			   POSITION( 'i' IN vorlage_therapie_art ) > 0

            			ORDER BY
            			   beginn ASC ) tsys_imm           	ON tsys_imm.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN therapie_systemisch tsys_and         	ON tsys_and.erkrankung_id=ekr.erkrankung_id
            			   									   AND POSITION( 'son' IN tsys_and.vorlage_therapie_art ) > 0
            LEFT JOIN sonstige_therapie sth                	ON sth.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN l_exp_diagnose_to_lokalisation d_vs_l ON d_vs_l.diagnose_code=ts_n.diagnose
            LEFT JOIN ( SELECT
                          *

                        FROM
            			  tumorstatus

            			ORDER BY
            			  datum_sicherung ASC ) ts_tp		ON ts_tp.erkrankung_id=ekr.erkrankung_id
                                                               AND LEFT( ts_tp.anlass, 1 )='r'
			LEFT JOIN ( SELECT
                          *

                        FROM
                          therapieplan

                        ORDER BY
                          datum DESC ) tp					ON tp.erkrankung_id=ekr.erkrankung_id
                          									   AND tp.datum<IFNULL( ts_tp.datum_sicherung, '2999-01-01' )
			LEFT JOIN l_exp_gkr chat	       				ON chat.klasse='chat'
                                                               AND chat.code_med=tp.intention

         WHERE
            ekr.ekr_id=$ekr_id

         GROUP BY
            ekr.ekr_id
      ";
      $ekr_data = end( sql_query_array( $this->_db, $query ) );

        if ( strlen( $ekr_data[ 'VORT' ] ) > 0 ) {
            $ekr_data[ 'VORT' ] = $this->GetVortData( $ekr_data[ 'erkrankung_id' ] );
        }

      if ( strlen( $ekr_data[ 'ICM' ] ) == 0 ) {
         $ekr_data[ 'HFK' ] = "";
      }
      $mtype = $ekr_data[ 'MTYP' ];
      $ekr_data[ 'TNMprae_a' ] = "";
      if ( strlen( $ekr_data[ 'TNMprae' ] ) ) {
         $ekr_data[ 'TNMprae_a' ] = "7";
      }
      $ekr_data[ 'TNMpost_a' ] = "";
      if ( strlen( $ekr_data[ 'TNMpost' ] ) ) {
         $ekr_data[ 'TNMpost_a' ] = "7";
      }
      if ( $export_filter[ 'export_id' ] > 1 ) {
         $mtype = "f";
      }
      if ( $export_filter[ 'erstmedlung_erneut' ] ) {
         $mtype = $ekr_data[ 'MTYP' ];
         if ( $mtype != "e" && $mtype != "E" ) {
            $mtype = "e"; // Wird hier einfach voraus gesetzt...
         }
      }
      $ekr_data[ 'MTYP' ] = $mtype;
      // Längen Prüfen...
      foreach( $ekr_data as $key => $value ) {
         if ( is_string( $value ) &&
              ( $key != "patient_id" ) &&
              ( $key != "erkrankung_id" ) &&
              ( $key != "ekr_id" ) ) {
            $ekr_data[ $key ] = $this->TrimString( $value, $this->m_field_len[ $key ], false );
         }
      }
       return $ekr_data;
   }


    /**
     *
     *
     * @access
     * @param $annamnese_id
     * @return void
     */
    protected function GetVortData( $erkrankung_id )
    {
        $vort = '';
        $query = "
            SELECT
                ane.jahr,
                ane.erkrankung_text

            FROM
                anamnese_erkrankung ane

            WHERE
                ane.erkrankung_id={$erkrankung_id}
                AND ( ane.erkrankung LIKE 'C%'
                      OR ane.erkrankung LIKE 'D0%'
                      OR ane.erkrankung IN ( 'D32.0', 'D32.1', 'D32.9', 'D33.0', 'D33.1', 'D33.2',
                                             'D33.3', 'D33.4', 'D33.5', 'D33.6', 'D33.7', 'D33.8', 'D33.9',
                                             'D35.2', 'D35.4' )
                      OR ane.erkrankung LIKE 'D37%'
                      OR ane.erkrankung LIKE 'D38%'
                      OR ane.erkrankung LIKE 'D39%'
                      OR ane.erkrankung LIKE 'D4%' )
        ";
        $result = sql_query_array( $this->_db, $query );
        if ( $result === false ) {
            return '';
        }
        foreach( $result as $row ) {
            if ( strlen( $vort ) == 0 ) {
                $vort .= $row[ 'jahr' ] . ', ' . $row[ 'erkrankung_text' ];
            }
            else {
                $vort .= '; ' . $row[ 'jahr' ] . ', ' . $row[ 'erkrankung_text' ];
            }
        }
        return $vort;
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

   /**
    *
    * @param $filename
    * @return unknown_type
    */
   protected function CreateCvsFileCheck( $filename ) {
      $check_filename = $filename . ".check.txt";

      $lines = file( $filename );
      if ( $lines === false ) {
         die( "Read export file to check faild." );
      }
      $fh = fopen( $check_filename, "w" );
      if ( !$fh ) {
         die( "Create check file faild." );
      }
      foreach( $lines as $line ) {
         $pos = 0;
         foreach( $this->m_field_len as $field => $len ) {
            $v = "[" . $field . "] = [" . substr( $line, $pos, $len ) . "](" . $len . ")\n";
            $pos += $len;
            fwrite( $fh, $v );
         }
         fwrite( $fh, "<== Next record =========================================================================================================================================>\n" );
      }
      fclose( $fh );
   }

}

?>
