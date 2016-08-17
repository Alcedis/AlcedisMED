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

class CKrrp1_0_0 extends CMedBaseExport
{

   protected $m_public_key = "/feature/exports/scripts/krrp/krrp_td_a02_Krebsregister_RLP_(0x9DE5E14A)_pub.asc";

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/server.conf', 'pgp' );
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_krrp.conf', 'export_krrp' );
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
      $export_filter[ 'format_date' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $ext_dir = isset( $this->_config[ 'exp_krrp_dir' ] ) ? $this->_config[ 'exp_krrp_dir' ] : 'krrp/';
      $ext_log_subdir = isset( $this->_config[ 'exp_krrp_log_subdir' ] ) ? $this->_config[ 'exp_krrp_log_subdir' ] : 'log/';
      $ext_tmp_subdir = isset( $this->_config[ 'exp_krrp_tmp_subdir' ] ) ? $this->_config[ 'exp_krrp_tmp_subdir' ] : 'tmp/';
      $ext_xml_subdir = isset( $this->_config[ 'exp_krrp_xml_subdir' ] ) ? $this->_config[ 'exp_krrp_xml_subdir' ] : 'xml/';
      $ext_zip_subdir = isset( $this->_config[ 'exp_krrp_zip_subdir' ] ) ? $this->_config[ 'exp_krrp_zip_subdir' ] : 'zip/';
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
      $export_filter[ 'csv_quote' ] = isset( $this->_config[ 'exp_krrp_csv_quote' ] ) ? $this->_config[ 'exp_krrp_csv_quote' ] : '"';
      $export_filter[ 'csv_separator' ] = isset( $this->_config[ 'exp_krrp_csv_separator' ] ) ? $this->_config[ 'exp_krrp_csv_separator' ] : ';';
      $export_filter[ 'csv_file_suffix' ] = isset( $this->_config[ 'exp_krrp_csv_file_suffix' ] ) ? $this->_config[ 'exp_krrp_csv_file_suffix' ] : '.csv';
      $export_filter[ 'pgp_binary' ] = $this->_config[ 'pgp_binary' ];
      //$export_filter[ 'pgp_homedir' ] = $this->_config[ 'pgp_homedir' ]; Not needed...
      $export_filter[ 'org_id' ] = $session[ 'sess_org_id' ];
      // Formular Daten holen
      $export_filter[ 'von' ] = isset( $request[ 'sel_datum_von' ] ) ? todate( $request[ 'sel_datum_von' ], 'en' ) : '';
      $export_filter[ 'bis' ] = isset( $request[ 'sel_datum_bis' ] ) ? todate( $request[ 'sel_datum_bis' ], 'en' ) : '';
      // export_id bestimmen
      $export_filter[ 'export_id' ] = dlookup( $this->_db, 'exp_ekrrp_log', 'IFNULL( MAX( export_id ) + 1, 1 )', '1' );
      return $export_filter;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilename()
    */
   protected function CreateExportFilename( $export_filter )
   {
      $filename = $this->_zip_dir . 'export_krrp_' . date( 'YmdHis' );
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
      $fields_melder = array();
      $fields_daten = array();
      $result = array();

      $melder_file = $this->_tmp_dir . "Melder" . $export_filter[ 'csv_file_suffix' ];
      $daten_file = $this->_tmp_dir . "Daten" . $export_filter[ 'csv_file_suffix' ];
      $files[] = $melder_file;
      $files[] = $daten_file;
      $zip_file = $filename . ".zip";

      $melder_pgp_file = $melder_file . ".gpg";
      $daten_pgp_file = $daten_file . ".gpg";
      $gpg_files[] = $melder_pgp_file;
      $gpg_files[] = $daten_pgp_file;
      $zip_gpg_file = $filename . "_gpg.zip";

      if ( !is_array( $content ) ) {
         return false;
      }
      if ( count( $content[ 'Melder' ] ) > 0 ) {
         $tmp = array_keys( $content[ 'Melder' ][ 0 ] );
         foreach( $tmp as $count => $field ) {
            if ( ( $field != "patient_id" ) &&
                 ( $field != "ekr_id" ) ) {
               $fields_melder[] = array(
               	'Field' => $field,
   				'Type' => "text"
               );
            }
         }
      }
      if ( count( $content[ 'Daten' ] ) > 0 ) {
         $tmp = array_keys( $content[ 'Daten' ][ 0 ] );
         foreach( $tmp as $count => $field ) {
            if ( ( $field != "patient_id" ) &&
                 ( $field != "ekr_id" ) ) {
               $fields_daten[] = array(
               	'Field' => $field,
   				'Type' => "text"
               );
            }
         }
      }
      $this->WriteCsvFile( $melder_file, $fields_melder, $content[ 'Melder' ], $export_filter[ 'csv_separator' ] );
      $this->WriteCsvFile( $daten_file, $fields_daten, $content[ 'Daten' ], $export_filter[ 'csv_separator' ] );
      $this->FileEncryption( $melder_file, "Krebsregister RLP <krebsregister@uni-mainz.de>", $this->_tmp_dir,
                             $this->m_public_key, $export_filter[ 'pgp_binary' ] );
      $this->FileEncryption( $daten_file, "Krebsregister RLP <krebsregister@uni-mainz.de>", $this->_tmp_dir,
                             $this->m_public_key, $export_filter[ 'pgp_binary' ] );

      $zip = new PclZip( $zip_gpg_file );
      $zip_create = $zip->create( $gpg_files, PCLZIP_OPT_REMOVE_ALL_PATH );
      $zip_gpg_url = "index.php?page=export_krrp&action=download&type=zip&file=" . $zip_gpg_file;

      $zip = new PclZip( $zip_file );
      $zip_create = $zip->create( $files, PCLZIP_OPT_REMOVE_ALL_PATH );
      $zip_url = "index.php?page=export_krrp&action=download&type=zip&file=" . $zip_file;
      if ( $zip_create ) {
         unlink( $melder_file );
         unlink( $daten_file );
         unlink( $melder_pgp_file );
         unlink( $daten_pgp_file );
      }

      $result[ 'valid' ] = array();
      $i = 0;
      $bez_arr = array();
      foreach( $content[ 'Daten' ] AS $ekr ) {
         $bez = $this->GetBezeichnung( $ekr[ 'ekr_id' ], $export_filter );
         if ( !in_array( $bez, $bez_arr ) ) {
            $result[ 'valid' ][ $i ][ 'ekr_id' ] = $ekr[ 'ekr_id' ];
            $result[ 'valid' ][ $i ][ 'bez' ] = $bez;
            $bez_arr[] = $bez;
            $i++;
         }
      }
      $cnt_patient_valid = count( $result[ 'valid' ] );
      $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $this->_config[ 'info_patienten_valid' ] );
      $cnt_patient_invalid = 0;
      $info_patienten_invalid = str_replace( '#anzahl#', $cnt_patient_invalid, $this->_config[ 'info_patienten_invalid' ] );
      foreach( $content[ 'Daten' ] AS $ekr ) {
         // Log in DB schreiben
         $query = '
            INSERT INTO exp_ekrrp_log
               VALUES ( "",
                        "' . $export_filter[ 'export_id' ]   . '",
                        "' . $ekr[ 'ekr_id' ]                . '",
                        "0",
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
      // Template Variablen
      $this->_smarty->assign(
         array(
            'export_id'              => $export_filter[ 'export_id' ],
            'cnt_patient_valid'      => $cnt_patient_valid,
            'cnt_patient_invalid'    => $cnt_patient_invalid,
            'info_patienten_invalid' => $info_patienten_invalid,
            'info_patienten_valid'   => $info_patienten_valid,
            'result'                 => $result,
      	    'zip_filename'           => basename( $zip_file ),
            'zip_url'                => $zip_url,
            'zip_gpg_filename'       => basename( $zip_gpg_file ),
            'zip_gpg_url'            => $zip_gpg_url
         )
      );
      return true;
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
            e.ekr_id

         FROM
            ekr e
            INNER JOIN patient p ON e.patient_id=p.patient_id
                                    AND e.datum BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'

         WHERE
            p.org_id={$export_filter[ 'org_id' ]}
      ";
      $result = sql_query_array( $this->_db, $query );
      // Wenn keine Daten verfügbar sind, hier raus springen
      if ( !count( $result ) ) {
         return 'error';
      }
      $data[ 'Melder' ] = $this->GetMelderDaten( $export_filter );
      $data[ 'Daten' ] = $this->GetEkrDaten( $export_filter );
      return $data;
   }

   /**
    *
    * @param $ekr_id
    * @param $export_filter
    * @return unknown_type
    */
   protected function GetMelderDaten( $export_filter )
   {
      $data = array();
      // Daten
      $query = "
         SELECT
		    p.patient_id,
            e.ekr_id,
            'M282' 											AS 'Kennung',
            'K' 											AS 'Satzart',
            '0518' 											AS 'Satzlänge',
            u.kr_kennung									AS 'Melder-ID',
            u.kr_kuerzel									AS 'Melder/Daten',
            o.name											AS 'Krankenhaus/Praxis',
            NULL											AS 'Abteilung',
            NULL											AS 'Anrede',
            u.titel											AS 'Titel',
            u.nachname										AS 'Nachname',
            u.vorname										AS 'Vorname',
            fachabteilung.bez								AS 'Fachrichtung',
            CONCAT_WS( ' ', o.strasse, o.hausnr )			AS 'Straße und Hausnr.',
            o.plz											AS 'Postleitzahl',
            o.ort											AS 'Ort',
            u.telefon										AS 'Telefon',
            u.telefax										AS 'Fax',
            u.email											AS 'E-mail',
            u.bsnr											AS 'BSNR (Betriebsstätten-Nr.)',
            u.lanr											AS 'LANR (Lebenslange Arzt-Nr.)',
            u.bank_kontoinhaber								AS 'Kontoinhaber',
            u.bank_name										AS 'Bank',
            u.bank_blz										AS 'Bankleitzahl',
            u.bank_kontonummer								AS 'Kontonummer',
            u.bank_verwendungszweck							AS 'Verwendungszweck'

         FROM
            ekr e
            INNER JOIN patient p 							ON e.patient_id=p.patient_id
            LEFT JOIN user u							    ON u.user_id=e.user_id
            LEFT JOIN org o                                 ON o.org_id=p.org_id
            LEFT JOIN l_basic fachabteilung					ON fachabteilung.klasse='fachabteilung'
            												   AND fachabteilung.code=u.fachabteilung

         WHERE
			p.org_id={$export_filter[ 'org_id' ]}
            AND e.datum BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'

         GROUP BY
	    	p.patient_id

      ";
      $data = sql_query_array( $this->_db, $query );
      return $data;
   }

   /**
    *
    * @param $ekr_id
    * @param $export_filter
    * @return unknown_type
    */
   protected function GetEkrDaten( $export_filter )
   {
      $ekr_data = array();
      // Daten
      $query = "
         SELECT
         	e.erkrankung_id,
		    p.patient_id,
            e.ekr_id,
            'M282' 															AS 'Kennung',
            'D' 															AS 'Satzart',
            '1313' 															AS 'Satzlänge',
            p.patient_nr													AS 'Patienten-ID',
            NULL															AS 'Aufnahmenummer',
            p.nachname														AS 'Nachname und Zusatz',
            p.vorname														AS 'Vorname und Titel',
            p.geburtsname													AS 'Geburtsname',
            NULL															AS 'Sonstiger früherer Name',
            CONCAT( ' ', p.strasse, p.hausnr )								AS 'Straße und Hausnr.',
            p.plz															AS 'Postleitzahl',
            p.ort															AS 'Wohnort',
            DATE_FORMAT( p.geburtsdatum, '%d.%m.%Y' )						AS 'Geburtsdatum',
            IFNULL( geschlecht.code_ekrrp, '9' )							AS 'Geschlecht',
            IFNULL( p.staat, '9' )											AS 'Staatsangehörigkeit',
			NULL															AS 'zuletzt ausgeübter Beruf',
			an.beruf_letzter												AS 'letzter Beruf, Klartext',
			an.beruf_letzter_dauer											AS 'Dauer des zuletzt ausgeübten Berufs in Jahren',
			NULL															AS 'am längsten ausgeübter Beruf',
			an.beruf_laengster												AS 'längster Beruf, Klartext',
			an.beruf_laengster_dauer										AS 'Dauer des am längsten ausgeübten Berufs in Jahren',
			DATE_FORMAT( IFNULL( ts_e.datum_sicherung,
								 ts_n.datum_sicherung ),
								 '%d.%m.%Y' )								AS 'Diagnosedatum',
			'9'																AS 'Diagnosetag künstlich?',
			'9'																AS 'Diagnosemonat künstlich?',
			'0'																AS 'Wievielter Tumor',
			IFNULL( ts_e.diagnose,
					ts_n.diagnose )											AS 'Tumordiagnose codiert',
			IF( IFNULL( ts_e.diagnose,
					    ts_n.diagnose ) IS NOT NULL,
					    'ICD10',
					    NULL )												AS 'Codierungssystem_1',
			IFNULL( ts_e.diagnose_text,
					ts_n.diagnose_text )									AS 'Tumordiagnose Klartext',
			IFNULL( IFNULL( ts_e.lokalisation,
							ts_n.lokalisation ),
					d_vs_l.lokalisation_code )								AS 'Tumorlokalisation codiert',
			IF( IFNULL( IFNULL( ts_e.lokalisation,
								ts_n.lokalisation ),
					    d_vs_l.lokalisation_code ) IS NOT NULL,
					    'ICDO3',
					    NULL )												AS 'Codierungssystem_2',
			IFNULL( ts_e.lokalisation_text,
					ts_n.lokalisation_text )								AS 'Tumorlokal. Klartext',
			IFNULL( IFNULL( ts_e.diagnose_seite,
							ts_n.diagnose_seite ), '9' )					AS 'Seitenlokalisation',
			IF( COUNT( h.histologie_id ) > 0, '4', '1' )					AS 'Diagnosesicherung',
            IFNULL( ts_e.morphologie,
            		ts_n.morphologie )										AS 'Histologie codiert',
           	IF( IFNULL( ts_e.morphologie,
            			ts_n.morphologie ) IS NOT NULL,
            			'ICDO3',
            			NULL )												AS 'Codierungssystem_3',
            IFNULL( ts_e.morphologie_text,
            		ts_n.morphologie_text )                     			AS 'Histologie Klartext',
            IFNULL( RIGHT( IFNULL( ts_e.morphologie,
            					   ts_n.morphologie ), 1 ),
            				'9' )											AS 'Dignität',
			IFNULL( grading.code_ekrrp, '9' )								AS 'Grading',
			CASE
               WHEN IFNULL( ts_e.m, ts_n.m ) IS NOT NULL AND
               		IFNULL( ts_e.m, ts_n.m ) NOT LIKE '%M0%' AND
                    IFNULL( ts_e.m, ts_n.m ) NOT LIKE '%MX'    	THEN '3'
               WHEN IFNULL( ts_e.n, ts_n.n ) IS NOT NULL AND
               		IFNULL( ts_e.n, ts_n.n ) NOT LIKE '%N0%' AND
                    IFNULL( ts_e.n, ts_n.n ) NOT LIKE '%NX'    	THEN '2'
               ELSE                                                	 '1'
            END																AS 'Tumorausbreitung',
            UPPER( SUBSTRING( IFNULL( ts_e.t, ts_n.t ), 3 ) )				AS 'T (TNM-Klassifikation)',
            CASE
               WHEN LEFT( IFNULL( ts_e.t, ts_n.t ), 1 )='p'		THEN '4'
               WHEN LEFT( IFNULL( ts_e.t, ts_n.t ), 1 )='c' 	THEN '1'
               ELSE                                                	 '9'
            END																AS 'Ct',
            UPPER( SUBSTRING( IFNULL( ts_e.n, ts_n.n ), 3 ) )				AS 'N (TNM-Klassifikation)',
            CASE
               WHEN LEFT( IFNULL( ts_e.n, ts_n.n ), 1 )='p'		THEN '4'
               WHEN LEFT( IFNULL( ts_e.n, ts_n.n ), 1 )='c' 	THEN '1'
               ELSE                                                	 NULL
            END																AS 'Cn',
            UPPER( SUBSTRING( IFNULL( ts_e.m, ts_n.m ), 3 ) )				AS 'M (TNM-Klassifikation)',
            CASE
               WHEN LEFT( IFNULL( ts_e.m, ts_n.m ), 1 )='p'		THEN '4'
               WHEN LEFT( IFNULL( ts_e.m, ts_n.m ), 1 )='c' 	THEN '1'
               ELSE                                                	 NULL
            END																AS 'Cm',
            CASE
               WHEN IFNULL( ts_e.ann_arbor_stadium,
               				ts_n.ann_arbor_stadium ) IS NOT NULL THEN '1'
               WHEN IFNULL( ts_e.cll_rai,
               				ts_n.cll_rai ) IS NOT NULL   		 THEN '3'
               WHEN IFNULL( ts_e.cll_binet,
               				ts_n.cll_binet ) IS NOT NULL   		 THEN '4'
               WHEN IFNULL( ts_e.durie_salmon,
               				ts_n.durie_salmon ) IS NOT NULL   	 THEN '5'
               ELSE                                                   NULL
            END																AS 'Klassifikation',
            CASE
               WHEN IFNULL( ts_e.ann_arbor_stadium,
               				ts_n.ann_arbor_stadium ) IS NOT NULL   	THEN
                  IF( ts_e.ann_arbor_stadium IS NOT NULL,
                      CONCAT_WS( ' ', ts_e.ann_arbor_stadium,
                      				  ts_e.ann_arbor_aktivitaetsgrad,
                      				  ts_e.ann_arbor_extralymphatisch ),
                      CONCAT_WS( ' ', ts_n.ann_arbor_stadium,
                      				  ts_n.ann_arbor_aktivitaetsgrad,
                      				  ts_n.ann_arbor_extralymphatisch ) )
               WHEN IFNULL( ts_e.cll_rai,
               				ts_n.cll_rai ) IS NOT NULL   			THEN
                  IF( ts_e.cll_rai IS NOT NULL,
                      ts_e.cll_rai,
                      ts_n.cll_rai )
               WHEN IFNULL( ts_e.cll_binet,
               				ts_n.cll_binet ) IS NOT NULL   			THEN
               	  IF( ts_e.cll_binet IS NOT NULL,
                      ts_e.cll_binet,
                      ts_n.cll_binet )
               WHEN IFNULL( ts_e.durie_salmon,
               				ts_n.durie_salmon ) IS NOT NULL   		THEN
               	  IF( ts_e.durie_salmon IS NOT NULL,
                      ts_e.durie_salmon,
                      ts_n.durie_salmon )
               ELSE                                                	NULL
            END                                                             AS 'Stadium',
            NULL															AS 'Zelltyp',
            IFNULL( diagnose_anlass.code_ekrrp, '9' )						AS 'Anlaß der Diagnosestellung',
            IF( COUNT( eg.eingriff_id ) > 0, '1', '2' )						AS 'Operation',
            IF( COUNT( stp.strahlentherapie_id ) > 0, '1', '2' )			AS 'Strahlentherapie',
            IF( COUNT( th_sys_c.therapie_systemisch_id ) > 0, '1', '2' )	AS 'Chemotherapie',
            IF( COUNT( th_sys_ah.therapie_systemisch_id ) > 0, '1', '2' )	AS 'Hormontherapie',
            IF( COUNT( th_sys_i.therapie_systemisch_id ) > 0, '1', '2' )	AS 'Immuntherapie',
            NULL															AS 'Therapieintention',
            meldebegruendung.code_ekrrp										AS 'Patient über Aufnahme in das Krebsregister informiert?',
            DATE_FORMAT( e.createtime, '%d.%m.%Y' )							AS 'Datum Ersterfassung',
            DATE_FORMAT( IFNULL( e.updatetime,
            					 e.createtime ), '%d.%m.%Y' )				AS 'Datum letzte Korrektur',
			u.kr_kuerzel													AS 'Melder/Datenherkunft',
			ab.todesdatum													AS 'Sterbedatum',
			IF( ab.todesdatum IS NOT NULL, '9', NULL )						AS 'Sterbedatum künstlich?',
			ab.tod_ursache													AS 'Unmittelbare Todesursache codiert',
			IF( ab.tod_ursache IS NOT NULL, 'ICD10', NULL )				 	AS 'Codierungssystem_4',
            ab.tod_ursache_text												AS 'Unmittelbare Todesursache Klartext',
            NULL															AS 'Begleiterkrank. codiert',
            NULL															AS 'Codierungssystem_5',
            NULL															AS 'Begleiterkrank. Klartext',
            NULL															AS 'Grundleiden codiert',
            NULL															AS 'Codierungssystem_6',
            NULL															AS 'Grundleiden Klartext',
            IF( ab.abschluss_grund='tot',
            	tod_tumorbedingt.code_ekrrp,
            	IF( COUNT( ab.abschluss_id ) > 0,
            	    '9',
            	    NULL
            	)
            )							   									AS 'Tod tumorbedingt?',
            IF( ab.abschluss_grund='tot',
            	'9',
            	NULL )														AS 'Autopsie angestrebt?',
           	NULL															AS 'Bemerkung',
           	nachsorge.code_ekrrp											AS 'Schriftliche Einwilligung für Nachsorge liegt vor',
           	e.nachsorgepassnr												AS 'Nachsorgepaßnummer',
           	CASE
           		WHEN nach.malignom IS NULL
           			  AND ( nach.response_klinisch IS NULL
           			        OR nach.response_klinisch = 'CR' ) 	THEN '2'
           		WHEN nach.response_klinisch IS NOT NULL 		THEN '1'
           		ELSE												 NULL
           	END 															AS 'Tumor noch nachweisbar?',
           	NULL															AS 'Datum der Tumorfreiheit',
           	NULL															AS 'Tumortyp',
           	DATE_FORMAT( ts.datum_sicherung, '%d.%m.%Y' )					AS 'Datum der Diagnosestellung von Rezidiv/Metastase',
     		CONCAT_WS( ' ',
     				   IF( nu.titel='', NULL, nu.titel ),
     				   IF( nu.vorname='', NULL, nu.vorname ),
     				   IF( nu.nachname='', NULL, nu.nachname ) )			AS '__nsa',
     		CONCAT_WS( ' ',
     				   IF( nu.strasse='', NULL, nu.strasse ),
     				   IF( nu.hausnr='', NULL, nu.hausnr ) )				AS '__a',
     		CONCAT_WS( ' ',
     				   IF( nu.plz='', NULL, nu.plz ),
     				   IF( nu.ort='', NULL, nu.ort ) )						AS '__anr',
           	''																AS 'Nachsorgearzt Name, Anschrift, Arztnr.',
           	DATE_FORMAT( e.nachsorgetermin, '%d.%m.%Y' )					AS 'Nachsorgedatum',
           	ts_e.m AS e_m,
           	ts_n.m AS n_m,
           	ts_e.n AS e_n,
           	ts_n.n AS n_n

         FROM
            ekr e
            INNER JOIN patient p 							 				ON e.patient_id=p.patient_id
                                    										   AND e.datum BETWEEN '{$export_filter[ 'von' ]}' AND '{$export_filter[ 'bis' ]}'
            LEFT JOIN user u							     				ON u.user_id=e.user_id
            LEFT JOIN user nu												ON nu.user_id=e.nachsorge_user_id
            LEFT JOIN l_exp_ekrrp geschlecht                 				ON geschlecht.klasse='geschlecht'
                                                    						   AND geschlecht.code_med=p.geschlecht
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

            		  ) an                                 					ON an.erkrankung_id=e.erkrankung_id
            LEFT JOIN ( SELECT
            			   tumorstatus_id,
            			   erkrankung_id,
                           anlass,
                           datum_beurteilung,
                           datum_sicherung,
                           diagnose,
                           diagnose_text,
                           lokalisation,
                           lokalisation_text,
                           diagnose_seite,
                           morphologie,
                           morphologie_text,
                           g,
                           t,
                           n,
                           m,
                           ann_arbor_stadium,
                           ann_arbor_aktivitaetsgrad,
                           ann_arbor_extralymphatisch,
                           cll_rai,
                           cll_binet,
                           aml_fab,
                           durie_salmon,
                           gleason1,
                           gleason2

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           datum_sicherung DESC

                        ) ts_n                             					ON ts_n.erkrankung_id=e.erkrankung_id
            LEFT JOIN tumorstatus ts_e                     					ON ts_e.erkrankung_id=e.erkrankung_id
                                                              				   AND LEFT( ts_e.anlass, 1 )='p'
                                                    						   AND ts_e.sicherungsgrad='end'
			LEFT JOIN ( SELECT
            			   erkrankung_id,
                           anlass,
                           datum_beurteilung,
                           datum_sicherung

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='r'

                        ORDER BY
                           datum_sicherung ASC

                        ) ts                             					ON ts.erkrankung_id=e.erkrankung_id
			LEFT JOIN histologie h                         					ON h.erkrankung_id=e.erkrankung_id
            LEFT JOIN l_exp_diagnose_to_lokalisation d_vs_l 				ON d_vs_l.diagnose_code=IFNULL( ts_e.diagnose, ts_n.diagnose )
            LEFT JOIN l_exp_ekrrp grading                   				ON grading.klasse='grading'
                                                              				   AND grading.code_med=IFNULL( ts_e.g, ts_n.g )
            LEFT JOIN l_exp_ekrrp diagnose_anlass              				ON diagnose_anlass.klasse='diagnoseanlass'
                                                              				   AND diagnose_anlass.code_med=an.entdeckung
			LEFT JOIN eingriff eg											ON eg.erkrankung_id=e.erkrankung_id
																			   AND ( eg.art_primaertumor='1'
																			   		 OR eg.art_metastasen='1' )
			LEFT JOIN strahlentherapie stp									ON stp.erkrankung_id=e.erkrankung_id
			LEFT JOIN therapie_systemisch th_sys_c							ON th_sys_c.erkrankung_id=e.erkrankung_id
																			   AND POSITION( 'c' IN th_sys_c.vorlage_therapie_art ) > 0
			LEFT JOIN therapie_systemisch th_sys_ah							ON th_sys_ah.erkrankung_id=e.erkrankung_id
																			   AND POSITION( 'ah' IN th_sys_ah.vorlage_therapie_art ) > 0
			LEFT JOIN therapie_systemisch th_sys_i							ON th_sys_i.erkrankung_id=e.erkrankung_id
																			   AND POSITION( 'i' IN th_sys_i.vorlage_therapie_art ) > 0
			LEFT JOIN l_exp_ekrrp meldebegruendung            				ON meldebegruendung.klasse='meldebegruendung'
                                                              				   AND meldebegruendung.code_med=e.meldebegruendung
			LEFT JOIN abschluss ab                         					ON ab.patient_id=p.patient_id
			LEFT JOIN l_exp_ekrrp tod_tumorbedingt         					ON tod_tumorbedingt.klasse='turs'
                                                              				   AND tod_tumorbedingt.code_med=ab.tod_tumorassoziation
			LEFT JOIN l_exp_ekrrp nachsorge		         					ON nachsorge.klasse='jn'
                                                              				   AND nachsorge.code_med=e.nachsorgeprogramm
			LEFT JOIN ( SELECT
            			   patient_id,
            			   datum,
            			   malignom,
            			   response_klinisch

            			FROM
            			   nachsorge

            			ORDER BY
            			   datum DESC

            		  ) nach                                 				ON nach.patient_id=e.patient_id
            		  														   AND nach.datum <= e.datum

         WHERE
            p.org_id={$export_filter[ 'org_id' ]}

		 GROUP BY
			e.ekr_id

         ORDER BY
            p.patient_id,
            e.ekr_id
      ";
      $ekr_data = sql_query_array( $this->_db, $query );
      for( $i = 0; $i < count( $ekr_data ); $i++ ) {
         $query = "
            SELECT
      			IFNULL( intention.code_ekrrp, '9' )		AS intention,
      			tp.datum

      		 FROM ekr
      			INNER JOIN therapieplan	tp			ON ekr.erkrankung_id=tp.erkrankung_id
      			LEFT JOIN tumorstatus ts			ON ekr.erkrankung_id=ts.erkrankung_id
      											   	   AND LEFT( ts.anlass, 1 )='r'
      			LEFT JOIN l_exp_ekrrp intention		ON intention.klasse='intention'
                                                   	   AND intention.code_med=tp.intention

      		 WHERE
      			ekr_id={$ekr_data[ $i ][ 'ekr_id' ]}

      		 GROUP BY
      			tp.therapieplan_id

      		 HAVING
      			datum < IFNULL( MIN( ts.datum_beurteilung), '9999-12-31' )

      		 ORDER BY
      			tp.datum DESC

      		 LIMIT 0, 1
         ";
         $data = end( sql_query_array( $this->_db, $query ) );
         if ( is_array( $data ) && ( count( $data ) > 0 ) ) {
   		    $ekr_data[ $i ][ 'Therapieintention' ] = $data[ 'intention' ];
         }
         else {
         	$ekr_data[ $i ][ 'Therapieintention' ] = '9';
         }
         $query = "
            SELECT
      			MIN( ts.datum_beurteilung )								AS datum,
      			IF( ekr.erkrankung_id=ts.erkrankung_id, 1, 0 )			AS ist_aktuelle_erkrankung

      		 FROM
      		 	ekr
      			INNER JOIN tumorstatus ts								ON ekr.patient_id=ts.patient_id
      																	   AND LEFT( ts.anlass, 1 )='p'

      		 WHERE
      			ekr.ekr_id={$ekr_data[ $i ][ 'ekr_id' ]}

      		 GROUP BY
      			ts.erkrankung_id

      		 ORDER BY
      			datum
         ";
         $data = sql_query_array( $this->_db, $query );
         $ekr_data[ $i ][ 'Wievielter Tumor' ] = '0';
         if ( is_array( $data ) && ( count( $data ) > 0 ) ) {
         	 for( $c = 0; $c < count( $data ); $c++ ) {
         	 	if ( $data[ $c ][ 'ist_aktuelle_erkrankung' ] == 1 ) {
         	 	   $ekr_data[ $i ][ 'Wievielter Tumor' ] = ( $c + 1 );
         	 	}
      		 }
         }
         if ( strlen( $ekr_data[ $i ][ '__nsa' ] ) > 0 ||
              strlen( $ekr_data[ $i ][ '__a' ]   ) > 0 ||
              strlen( $ekr_data[ $i ][ '__anr' ] ) > 0 ) {
            $ekr_data[ $i ][ 'Nachsorgearzt Name, Anschrift, Arztnr.' ] = $ekr_data[ $i ][ '__nsa' ] . "," .
                                                                          $ekr_data[ $i ][ '__a' ] . "," .
                                                                          $ekr_data[ $i ][ '__anr' ];
         }
         unset( $ekr_data[ $i ][ '__nsa' ] );
         unset( $ekr_data[ $i ][ '__a' ] );
         unset( $ekr_data[ $i ][ '__anr' ] );
      }
      return $ekr_data;
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
         //$result[ 'faelle' ][] = $this->GetCase( $data, $export_filter );
      }
      $result[ 'patient_id' ] = $patient[ 0 ][ 'patient_id' ];
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
            IFNULL( geschlecht.code_ekrrp, 'x'              AS geschlecht,
            DATE_FORMAT( abschluss.todesdatum, '%Y-%m-%d' ) AS todesdatum

         FROM
            patient p
            LEFT JOIN l_exp_ekrrp geschlecht ON p.geschlecht=geschlecht.code_med
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
    * ...
    *
    * @param int $ekr_id
    * @param array $export_filter
    * @return array
    *
    */
   protected function GetBezeichnung( $ekr_id, $export_filter )
   {
      $query = "
         SELECT
	        p.vorname,
	        p.nachname,
	        DATE_FORMAT( p.geburtsdatum, '{$export_filter[ 'format_date' ]}' ) AS geburtsdatum,
	        DATE_FORMAT( ekr.datum, '{$export_filter[ 'format_date' ]}' )      AS datum

         FROM
            ekr
            INNER JOIN erkrankung e   ON e.erkrankung_id=ekr.erkrankung_id
            INNER jOIN patient p	  ON p.patient_id=e.patient_id

         WHERE
            ekr.ekr_id=$ekr_id

         GROUP BY
            ekr.ekr_id
      ";
      $result = end( sql_query_array( $this->_db, $query ) );
      $bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . ")"; //, Meldung vom " . $result[ 'datum' ];
      return $bez;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#WriteCsvFile()
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
	     $str = substr( $field[ 'Field' ], 0, 16 );
	     if ( $str == "Codierungssystem" ) {
            $head[] = '"' . $str . '"';
	     }
	     else {
	        $head[] = '"' . $field[ 'Field' ] . '"';
	     }
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

}

?>
