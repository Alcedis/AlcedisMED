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

require_once( 'interface.krhe.php' );

class CKrhe1_0 implements IKrhe
{

   /**
    *
    * @var unknown_type
    */
   protected $_smarty;
   protected $_internal_smarty;
   protected $_db;
   protected $_schema_file = 'krhe_v1_0.xsd';
   protected $_total_errors = 0;
   protected $_export_path = "";
   protected $_log_dir = "";
   protected $_tmp_dir = "";
   protected $_xml_dir = "";
   protected $_zip_dir = "";

   /**
    * (non-PHPdoc)
    * @see feature/gekid/scripts/IGekid#create()
    */
   public function create( $smarty, $db )
   {
      $this->_smarty = $smarty;
      $this->_internal_smarty = new Smarty();
      $this->_internal_smarty->template_dir = $this->_smarty->template_dir;
      $this->_internal_smarty->compile_dir = $this->_smarty->compile_dir;
      $this->_internal_smarty->config_dir = $this->_smarty->config_dir;
      $this->_internal_smarty->cache_dir = $this->_smarty->cache_dir;
      $this->_internal_smarty->plugins_dir = $this->_smarty->plugins_dir;
      $this->_internal_smarty->force_compile = true;
      $this->_internal_smarty->caching = 0;
      $this->_internal_smarty->error_reporting = E_ALL & ~E_NOTICE & ~'E_WARN';
      $this->_db = $db;
   }

   /**
    *
    * (non-PHPdoc)
    * @see feature/gekid/scripts/IGekid#hasErrors()
    *
    */
   public function hasErrors()
   {
      return( $this->_total_errors != 0 );
   }

   /**
    * Hier wird für jeden Patient ein export durchgeführt um zu sehen, ob als Ergebnis ein
    * valides XML rauskommen würde. Es werden die patient_id in einem Array getrennt nach
    * 'valid' und 'invalid' zurückgegeben und das ganze in der Datenbank geloggt.
    *
    * @param $org_id
    * @param $login_name
    * @param $format_date
    * @param $export_id
    * @param $config
    * @param $arr_patient_id
    * @param $arr_export_filter
    * @return unknown_type
    *
    */
   protected function checkXml( $org_id, $user_id, $format_date, $export_id, $ekr_ids, $arr_export_filter )
   {
      // Checks
      if ( is_null( $export_id ) || ( strlen( $export_id ) == 0 ) ) {
         throw new Exception( "Export ID muss gesetzt sein." );
      }
      // Benötigte Verzeichnisse
      $xml_dir = $this->_xml_dir;
      // XML Schemadatei
      $xml_schema = getcwd() . "/feature/krhe/scripts/" . $this->_schema_file;
      // Es wird ein Array mit den patient_id getrennt
      // nach valid oder invalid zurückgegeben
      $return = array(
         'valid'   => array(),
         'invalid' => array()
      );
      $data = array();
      $data[ 'Melder' ] = $this->getMelderData( $org_id, $arr_export_filter[ 'melde_user_id' ], $format_date );
      foreach( $ekr_ids AS $ekr_id ) {
         // Daten für Export holen
         $data[ 'Daten' ] = array($this->getEkrData( $ekr_id ));
         $this->_internal_smarty->assign( 'data', $data );
         // XML generieren und speichern
         $xml_file = $this->_xml_dir . 'krhe_export_' . $export_id . '_' . $ekr_id . '.xml';
         $xml = $this->_internal_smarty->fetch( 'app/xml.export_krhe.tpl' );
         file_put_contents( $xml_file, utf8_encode( $xml ) );
         // XML Validieren
         $errors = $this->xmlSchemaValidate( $xml_file, $xml_schema );
         // XML wurde nur temporär für die Validierung gebraucht
         //unlink( $xml_file );
         // Log in DB schreiben
         $query = '
            INSERT INTO exp_krhe_log
               VALUES ( "",
                        "' . $export_id                  . '",
                        "' . $ekr_id                     . '",
                        "' . ( int )!count( $errors )    . '",
                        "' . implode( '', $errors )      . '",
                        "' . $org_id                     . '",
                        "' . $arr_export_filter[ 'von' ] . '",
                        "' . $arr_export_filter[ 'bis' ] . '",
                         ' . $user_id                    . ',
                        "' . date('Y-m-d H:i:s')         . '"
               )
         ';
         mysql_query( $query, $this->_db );
         if ( count( $errors ) > 0 ) {
            $return[ 'invalid' ][] = $ekr_id;
         }
         else {
            $return[ 'valid' ][] = $ekr_id;
         }
      }
      return $return;
   }

   /**
    * XML Generierung.
    *
    * @param $org_id
    * @param $config
    * @param $arr_patient_id
    * @param $arr_export_filter
    * @return unknown_type
    *
    */
   protected function generateXml( $org_id, $format_date, $valid_ekr_ids, $arr_export_filter )
   {
      if ( !count( $valid_ekr_ids ) ) {
         return;
      }
      $sel_datum_von_de = $arr_export_filter[ 'von' ];
      $sel_datum_bis_de = $arr_export_filter[ 'bis' ];
      todate( $sel_datum_von_de, 'de' );
      todate( $sel_datum_bis_de, 'de' );
      $data = array();
      $data[ 'Melder' ] = $this->getMelderData( $org_id, $arr_export_filter[ 'melde_user_id' ], $format_date );
      foreach( $valid_ekr_ids AS $ekr_id ) {
         $data[ 'Daten'][] = $this->getEkrData( $ekr_id );
      }
      $this->_internal_smarty->assign( 'data', $data );
      // XML generieren
      $xml = $this->_internal_smarty->fetch( 'app/xml.export_krhe.tpl' );
      $xml_file = $this->_xml_dir . 'krhe_export_' . date( 'YmdHis' ) . '.xml';
      file_put_contents( $xml_file, utf8_encode( $xml ) );
      // XML zippen
      $zip_file = $this->_zip_dir . str_replace( '.xml', '.zip', basename( $xml_file ) );
      $zip = new PclZip( $zip_file );
      $zip_create = $zip->create( $xml_file, PCLZIP_OPT_REMOVE_ALL_PATH );
      if ( $zip_create ) {
         unlink( $xml_file );
      }
      return $zip_file;
   }

   /**
    * Datengewinnung
    * @param $ekr_id
    * @return unknown_type
    */
   protected function getEkrData( $ekr_id )
   {
      // Daten
      $query = "
         SELECT
	        ekr_id,
            p.titel                                                                        AS 'Titel',
            p.vorname                                                                      AS 'Vornamen',
            p.nachname                                                                     AS 'Nachname',
            NULL                                                                           AS 'Namenszusatz',
            NULL                                                                           AS 'Fruehere_Namen',
            p.geburtsname                                                                  AS 'Geburtsname',
            IFNULL( geschlecht.code_krhe, 'U' )                                            AS 'Geschlecht',
            DATE_FORMAT( p.geburtsdatum, '%d.%m.%Y' )                                      AS 'Geburtsdatum',
            p.strasse                                                                      AS 'Strasse',
            p.hausnr                                                                       AS 'Hausnummer',
            NULL                                                                           AS 'Postfix',
            p.plz                                                                          AS 'Postleitzahl',
            p.ort                                                                          AS 'Ort',
            DATE_FORMAT( ab.todesdatum, '%d.%m.%Y' )                                       AS 'Todesdatum',
            ekr.grund			                                                           AS 'Meldebegruendung',
            p.patient_nr                                                                   AS 'Referenznummer',
            DAYOFMONTH( IFNULL( ts_e.datum_sicherung, ts_n.datum_sicherung ) )             AS 'Diagnosetag',
            '9'																			   AS 'Diagnosetag_kuenstlich',
            MONTH( IFNULL( ts_e.datum_sicherung, ts_n.datum_sicherung ) )                  AS 'Diagnosemonat',
            '9'                                                                            AS 'Diagnosemonat_kuenstlich',
            YEAR( IFNULL( ts_e.datum_sicherung, ts_n.datum_sicherung ) )                   AS 'Diagnosejahr',
            IFNULL( ts_e.diagnose, ts_n.diagnose )                                         AS 'ICD',
            IFNULL( ts_e.diagnose_text, ts_n.diagnose_text )                               AS 'Diagnose_Freitext',
            IFNULL( ts_e.morphologie, ts_n.morphologie )                                   AS 'Morphologie_Code',
            IFNULL( ts_e.morphologie_text, ts_n.morphologie_text )                         AS 'Morphologie_Freitext',
            RIGHT( IFNULL( ts_e.morphologie, ts_n.morphologie ), 1 )                       AS 'Dignitaet',
            IF( IFNULL( ts_e.diagnose,
                        ts_n.diagnose ) IS NOT NULL,
                        '10',
                        NULL )                                                             AS 'ICD_Auflage',
            IFNULL( IFNULL( ts_e.lokalisation,
            				ts_n.lokalisation ),
            				d_vs_l.lokalisation_code )	                                   AS 'Topographie_Code',
            IFNULL( ts_e.lokalisation_text,
            		ts_n.lokalisation_text )                                               AS 'Topographie_Freitext',
            IF( IFNULL( ts_e.morphologie,
            			ts_n.morphologie ) IS NOT NULL OR
                IFNULL( ts_e.lokalisation,
                		ts_n.lokalisation ) IS NOT NULL,
                		'3',
                		NULL )                                                             AS 'ICDO_Auflage',
            IFNULL( grading.code_krhe, 'U' )                                               AS 'Grading',
            NULL                                                                           AS 'Zelltyp',
            CASE
               WHEN h.histologie_id IS NOT NULL AND h.art='po' THEN 7
               WHEN h.histologie_id IS NOT NULL AND h.art='pr' THEN 5
               ELSE                                                 2
            END                                                                            AS 'Diagnosesicherung',
            diagnoseanlass.code_krhe                                                       AS 'Diagnoseanlass',
            IF(IFNULL( ts_e.diagnose_seite,
            		   ts_n.diagnose_seite )='-',
               NULL,
               IFNULL( ts_e.diagnose_seite,
               		   ts_n.diagnose_seite ) ) 											   AS 'Seitenlokalisation',
            NULL                                                                           AS 'Grobstadium',
            CASE
               WHEN IFNULL( ts_e.t, ts_n.t ) IS NOT NULL AND
                    LEFT( IFNULL( ts_e.t, ts_n.t ), 1 )='p'  THEN 'p'
               WHEN IFNULL( ts_e.t, ts_n.t ) IS NOT NULL AND
                    LEFT( IFNULL( ts_e.t, ts_n.t ), 1 )='c'  THEN 'c'
               ELSE                                               NULL
            END                                                                            AS 'Praefix_TNM',
            SUBSTRING( IFNULL( ts_e.t, ts_n.t ), 2 )                                       AS 'T',
            CASE
               WHEN IFNULL( ts_e.multizentrisch, ts_n.multizentrisch )='1' OR
                    IFNULL( ts_e.multifokal, ts_n.multifokal )='1'  			THEN 'm'
               ELSE                                                 				 NULL
            END                                                                            AS 'Multiplizitaet',
            SUBSTRING( IFNULL( ts_e.n, ts_n.n ), 2 )                                       AS 'N',
            SUBSTRING( IFNULL( ts_e.m, ts_n.m ), 2 )                                       AS 'M',
            IF( IFNULL( ts_e.t, ts_n.t ) IS NOT NULL OR
                IFNULL( ts_e.n, ts_n.n ) IS NOT NULL OR
                IFNULL( ts_e.m, ts_n.m ) IS NOT NULL, '7', NULL )                          AS 'TNM_Auflage',

            IF( COUNT( eg.eingriff_id ) > 0, 'J', 'N' )                                    AS 'Operation',
            IF( COUNT( st.strahlentherapie_id ) > 0, 'J', 'N' )                            AS 'Strahlentherapie',

            IF( COUNT(IF(tsys.vorlage_therapie_art LIKE '%c%',
            			 tsys.therapie_systemisch_id, NULL) ) > 0, 'J', 'N' )              AS 'Chemotherapie',


            IF( COUNT( tsys_chemo.therapie_systemisch_id ) > 0, 'J', 'N' )                 AS 'Chemotherapie',
            IF( COUNT( tsys_hormon.therapie_systemisch_id ) > 0, 'J', 'N' )                AS 'Hormontherapie',
            IF( COUNT( tsys_immun.therapie_systemisch_id ) > 0, 'J', 'N' )                 AS 'Immuntherapie',
            IF( COUNT( eg_knochen.eingriff_id ) > 0, 'J', 'N' )                            AS 'Knochenmarktransplantation',
            IF( COUNT( tsys.therapie_systemisch_id ) > 0 OR
                COUNT( sth.sonstige_therapie_id ) > 0, 'J', 'N' )                          AS 'Sonstige_Therapie',
            meldebegruendung.code_krhe                                                     AS 'Patient_informiert'

         FROM
            ekr
            INNER JOIN erkrankung e	                       ON e.erkrankung_id=ekr.erkrankung_id
            INNER jOIN patient p	                       ON p.patient_id=e.patient_id
            LEFT JOIN user u                               ON u.user_id=ekr.user_id
            LEFT JOIN org o                                ON p.org_id=o.org_id
            LEFT JOIN ( SELECT
                           *

                        FROM
                           tumorstatus

                        WHERE
                           LEFT( anlass, 1 )='p'

                        ORDER BY
                           datum_beurteilung DESC

                        ) ts_n                             ON ts_n.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN tumorstatus ts_e                     ON ts_e.erkrankung_id=ekr.erkrankung_id
                                                              AND LEFT( ts_e.anlass, 1 )='p'
                                                              AND ts_e.sicherungsgrad='end'
            LEFT JOIN l_exp_krhe geschlecht                ON geschlecht.klasse='geschlecht'
                                                              AND geschlecht.code_med=p.geschlecht
            LEFT JOIN l_exp_krhe grading                   ON grading.klasse='grading'
                                                              AND grading.code_med=IF( ts_e.g IS NOT NULL, ts_e.g, ts_n.g )
            LEFT JOIN anamnese an                          ON an.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN l_exp_krhe diagnoseanlass            ON diagnoseanlass.klasse='diagnoseanlass'
                                                              AND diagnoseanlass.code_med=an.entdeckung
			LEFT JOIN l_exp_krhe meldebegruendung          ON meldebegruendung.klasse='meldebegruendung'
                                                              AND meldebegruendung.code_med=ekr.meldebegruendung
            LEFT JOIN abschluss ab                         ON ab.patient_id=p.patient_id
            LEFT JOIN histologie h                         ON h.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN eingriff eg                          ON eg.erkrankung_id=ekr.erkrankung_id
                                                              AND ( eg.art_primaertumor='1'
                                                              		OR eg.art_metastasen='1' )
            LEFT JOIN strahlentherapie st                  ON st.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN therapie_systemisch tsys             ON tsys.erkrankung_id=ekr.erkrankung_id
            												  AND POSITION( 'son' IN tsys.vorlage_therapie_art ) > 0
            LEFT JOIN sonstige_therapie sth                ON sth.erkrankung_id=ekr.erkrankung_id
            LEFT JOIN therapie_systemisch tsys_chemo       ON tsys_chemo.erkrankung_id=ekr.erkrankung_id
                                                              AND POSITION( 'c' IN tsys_chemo.vorlage_therapie_art ) > 0
			LEFT JOIN therapie_systemisch tsys_hormon      ON tsys_hormon.erkrankung_id=ekr.erkrankung_id
                                                              AND POSITION( 'ah' IN tsys_hormon.vorlage_therapie_art ) > 0
            LEFT JOIN therapie_systemisch tsys_immun       ON tsys_immun.erkrankung_id=ekr.erkrankung_id
                                                              AND POSITION( 'i' IN tsys_immun.vorlage_therapie_art ) > 0
            LEFT JOIN eingriff eg_knochen                  ON eg_knochen.erkrankung_id=ekr.erkrankung_id
                                                              AND ( eg_knochen.art_transplantation_autolog='1'
                                                                    OR eg_knochen.art_transplantation_allogen_v='1'
                                                                    OR eg_knochen.art_transplantation_allogen_nv='1'
                                                                    OR eg_knochen.art_transplantation_syngen='1' )
            LEFT JOIN l_exp_diagnose_to_lokalisation d_vs_l ON d_vs_l.diagnose_code=IFNULL( ts_e.diagnose, ts_n.diagnose )

         WHERE
            ekr.ekr_id=$ekr_id

         GROUP BY
            ekr.ekr_id
      ";
      $ekr_data = end( sql_query_array( $this->_db, $query ) );
      $num = strtolower( $ekr_data[ 'Hausnummer' ] );
      $street = $ekr_data[ 'Strasse' ];
      if ( strlen( $ekr_data[ 'Hausnummer' ] ) == 0 ) {
         if ( preg_match( '/[0-9]{1,}/', $street, $match, PREG_OFFSET_CAPTURE ) ) {
            $pos = end( end( $match ) );
            $str = trim( substr( $street, 0, $pos ) );
            //$num = trim( substr( $street, $pos ) );
            $num = strtolower( trim( substr( $street, $pos ) ) );
         }
      }
      if ( preg_match( '/[^0-9]/', $num, $match, PREG_OFFSET_CAPTURE ) ) {
         $pos = end( end( $match ) );
         $nr  = trim( substr( $num, 0, $pos ) );
         //$fix = trim( substr( $num, $pos ) );
         $fix = strtolower( trim( substr( $num, $pos ) ) );
      }
      $ekr_data[ 'Strasse' ]    = isset( $str ) ? $str : $street;
      $ekr_data[ 'Hausnummer' ] = isset( $nr )  ? $nr  : $num;
      $ekr_data[ 'Postfix' ]    = isset( $fix ) ? $fix : '';
      $ekr_data[ 'T' ] = $this->CutTnm( $ekr_data[ 'T' ] );
      $ekr_data[ 'N' ] = $this->CutTnm( $ekr_data[ 'N' ] );
      $ekr_data[ 'M' ] = $this->CutTnm( $ekr_data[ 'M' ] );

      $query = "
         SELECT
            tp.therapieplan_id AS id,
			IFNULL( intention.code_krhe, '9' )		AS intention,
			tp.datum

		 FROM ekr
			INNER JOIN therapieplan	tp				ON ekr.erkrankung_id=tp.erkrankung_id
			LEFT JOIN tumorstatus ts				ON ekr.erkrankung_id=ts.erkrankung_id
											   		   AND LEFT( ts.anlass, 1 )='r'
			LEFT JOIN l_exp_krhe intention   		ON intention.klasse='intention'
                                                       AND intention.code_med=tp.intention

		 WHERE
			ekr_id=$ekr_id

		 GROUP BY
			tp.therapieplan_id

		 HAVING
			datum<IFNULL(MIN(ts.datum_beurteilung), '9999-12-31')

		 ORDER BY
			tp.datum DESC

		 LIMIT 0, 1
      ";
      $data = end( sql_query_array( $this->_db, $query ) );
      if ( is_array( $data ) && ( count( $data ) > 0 ) ) {
		 $ekr_data[ 'Therapieintention' ] = $data[ 'intention' ];
      }
      else {
      	 $ekr_data[ 'Therapieintention' ] = '9';
      }

   	  $query = "
         SELECT
			MIN(ts.datum_beurteilung)								AS datum,
			IF(ekr.erkrankung_id=ts.erkrankung_id, 1, 0)			AS ist_aktuelle_erkrankung

		 FROM ekr
			INNER JOIN tumorstatus ts								ON ekr.patient_id=ts.patient_id
																	   AND LEFT( ts.anlass, 1 )='p'

		 WHERE
			ekr.ekr_id=$ekr_id

		 GROUP BY
			ts.erkrankung_id

		 ORDER BY
			datum
      ";
      $data = sql_query_array( $this->_db, $query );
      $ekr_data[ 'Wievielter_Tumor' ] = '0';
      if ( is_array( $data ) && ( count( $data ) > 0 ) ) {
      	 for( $c = 0; $c < count( $data ); $c++ ) {
      	 	if ( $data[ $c ][ 'ist_aktuelle_erkrankung' ] == 1 ) {
      	 	   $ekr_data[ 'Wievielter_Tumor' ] = ( $c + 1 );
      	 	}
   		 }
      }
      return $ekr_data;
   }

   /**
    * ...
    *
    * @param $org_id
    * @param $melde_user_id
    * @param $format_date
    * @return unknown_type
    *
    */
   protected function getMelderData( $org_id, $melde_user_id, $format_date )
   {
      $query = "
         SELECT
            u.kr_kennung                                     AS 'Meldende_Stelle',
            o.name                                           AS 'KH_Abt_Station_Praxis',
            CONCAT_WS( ' ', u.titel, u.vorname, u.nachname ) AS 'Arztname',
            CONCAT_WS( ' ', o.strasse, o.hausnr )            AS 'Anschrift',
            o.plz                                            AS 'Postleitzahl',
            o.ort                                            AS 'Ort',
            DATE_FORMAT( NOW(), '$format_date' )             AS 'Meldedatum'

         FROM
         	org o,
         	user u

         WHERE
            u.user_id=$melde_user_id AND o.org_id=$org_id
      ";
      return end( sql_query_array( $this->_db, $query ) );
   }

   /**
    * ...
    *
    * @param $patient_id
    * @param $format_date
    * @return unknown_type
    *
    */
   protected function getEkrBez( $ekr_id, $format_date )
   {
      $query = "
         SELECT
	        p.vorname,
	        p.nachname,
	        DATE_FORMAT( p.geburtsdatum, '$format_date' ) AS geburtsdatum,
	        DATE_FORMAT( ekr.datum, '$format_date' )      AS datum

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
      $bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . "), Meldung vom " . $result[ 'datum' ];
      return $bez;
   }

   /**
    * XML-Validierung
    *
    * @param $xml_file
    * @param $xml_schema
    * @return unknown_type
    */
   protected function xmlSchemaValidate( $xml_file, $xml_schema )
   {
      if ( !is_file( $xml_file ) ) {
         echo 'Error: krhe_validate: XML-Datendatei nicht gefunden!';
         return;
      }
      if ( !is_file( $xml_schema ) ) {
         echo 'Error: krhe_validate: XML-Schema nicht gefunden!';
         return;
      }
      // Eigenes Error-Handlig aktivieren
      libxml_use_internal_errors( true );
      $xml = new DOMDocument();
      $xml->load( $xml_file );
      $xml->schemaValidate( $xml_schema );
      return $this->xmlSchemaValidateErrors();
   }

   /**
    *
    * @return unknown_type
    */
   function xmlSchemaValidateErrors()
   {
      /* WORKAROUND:
       * Das unten geladene xml_file wird jeweils an DOMDocument angehängt,
       * so dass nach dem ersten Auftreten eines XML-Fehlers alle (!) Pat.
       * als fehlerhaft erscheinen würden - Fehler-Array kumuliert also.
       * Aus Zeitgründen werden hier einfach die Fehler aus früheren Pat.-XMLs
       * entfernt.
       * Zähler ist $total_errors.
       * FIXME: Später mal untersuchen, wie das mit den XML-Funktionen sauber geht.
       */
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
         if ( $error->file ) {
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
    * (non-PHPdoc)
    * @see feature/gekid/scripts/IGekid#export()
    */
   public function export( $request, $session, $config )
   {
      $this->_total_errors = 0;
      $user_id = isset( $session[ 'sess_user_id' ] ) ? $session[ 'sess_user_id' ] : '-1';
      $login_name = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : 'unknown'; // Unknown sollte NIE vorkommen!!!
      $format_date = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $ext_krhe_dir = isset( $config[ 'exp_krhe_dir' ] ) ? $config[ 'exp_krhe_dir' ] : 'krhe/';
      $ext_krhe_log_subdir = isset( $config[ 'exp_krhe_log_subdir' ] ) ? $config[ 'exp_krhe_log_subdir' ] : 'log/';
      $ext_krhe_tmp_subdir = isset( $config[ 'exp_krhe_tmp_subdir' ] ) ? $config[ 'exp_krhe_tmp_subdir' ] : 'tmp/';
      $ext_krhe_xml_subdir = isset( $config[ 'exp_krhe_xml_subdir' ] ) ? $config[ 'exp_krhe_xml_subdir' ] : 'xml/';
      $ext_krhe_zip_subdir = isset( $config[ 'exp_krhe_zip_subdir' ] ) ? $config[ 'exp_krhe_zip_subdir' ] : 'zip/';
      $this->_export_path = $this->getExportPath( $ext_krhe_dir, $login_name );
      $this->_log_dir = $this->_export_path . $ext_krhe_log_subdir;
      $this->_tmp_dir = $this->_export_path . $ext_krhe_tmp_subdir;
      $this->_xml_dir = $this->_export_path . $ext_krhe_xml_subdir;
      $this->_zip_dir = $this->_export_path . $ext_krhe_zip_subdir;
      $result = '';
      $org_id = $session[ 'sess_org_id' ];
      // Pfade anlegen
      $this->createPath( $this->_log_dir );
      $this->createPath( $this->_tmp_dir );
      $this->createPath( $this->_xml_dir );
      $this->createPath( $this->_zip_dir );
      // Formular Daten holen
      $arr_export_filter[ 'melde_user_id' ] = isset( $request[ 'sel_melde_user_id' ] )  ? $request[ 'sel_melde_user_id' ]             : '';
      $arr_export_filter[ 'von' ]           = isset( $request[ 'sel_datum_von' ] )      ? todate( $request[ 'sel_datum_von' ], 'en' ) : '';
      $arr_export_filter[ 'bis' ]           = isset( $request[ 'sel_datum_bis' ] )      ? todate( $request[ 'sel_datum_bis' ], 'en' ) : '';
      // Erkrankungen holen
      $query = "
         SELECT
            e.ekr_id

         FROM
            ekr e
            INNER JOIN patient p ON e.patient_id=p.patient_id
                                    AND e.user_id={$arr_export_filter[ 'melde_user_id' ]}
                                    AND e.datum BETWEEN '{$arr_export_filter[ 'von' ]}' AND '{$arr_export_filter[ 'bis' ]}'

         WHERE
            p.org_id=$org_id
      ";
      $result_filter = sql_query_array( $this->_db, $query );
      // Wenn keine Daten verfügbar sind, hier raus springen
      if ( !count( $result_filter ) ) {
         return 'error';
      }
      // Erkrankung IDs sammeln
      $ekr_ids = array();
      foreach( $result_filter AS $value ) {
         $ekr_ids[] = $value[ 'ekr_id' ];
      }
      // export_id bestimmen
      $export_id = dlookup( $this->_db, 'exp_krhe_log', 'IFNULL( MAX( export_id ) + 1, 1 )', '1' );
      // XML validieren
      $ekr_ids_checked = $this->checkXml( $org_id, $user_id, $format_date, $export_id, $ekr_ids, $arr_export_filter );

      // XML generieren
      $zip_file = $this->generateXml( $org_id, $format_date, $ekr_ids_checked[ 'valid' ], $arr_export_filter );
      // Daten für Ergebnis im Template aufbereiten
      $result = array( 'valid' => array(), 'invalid' => array() );
      foreach( $ekr_ids_checked AS $type => $ekr_ids ) {
         $i = 0;
         switch( $type ) {
            case 'valid':
               foreach( $ekr_ids AS $ekr_id ) {
                  $result[ $type ][ $i ][ 'ekr_id' ] = $ekr_id;
                  $result[ $type ][ $i ][ 'bez' ]    = $this->getEkrBez( $ekr_id, $format_date );
                  $i++;
               }
               $cnt_patient_valid = count( $result[ 'valid' ] );
               $info_patienten_valid = str_replace( '#anzahl#', $cnt_patient_valid, $config[ 'info_patienten_valid' ] );
               break;

            case 'invalid':
               foreach( $ekr_ids AS $ekr_id ) {
                  $result[ $type ][ $i ][ 'ekr_id' ] = $ekr_id;
                  $result[ $type ][ $i ][ 'bez' ]    = $this->getEkrBez( $ekr_id, $format_date );
                  $i++;
               }
               $cnt_patient_invalid = count( $result[ 'invalid' ] );
               $info_patienten_invalid = str_replace( '#anzahl#', $cnt_patient_invalid, $config[ 'info_patienten_invalid' ] );
               break;
         }
      }
      $zip_url = "index.php?page=export_krhe&action=download&type=zip&file=" . $zip_file;
      // Template Variablen
      $this->_smarty->assign( array(
            'export_id'              => $export_id,
            'cnt_patient_valid'      => $cnt_patient_valid,
            'cnt_patient_invalid'    => $cnt_patient_invalid,
            'info_patienten_invalid' => $info_patienten_invalid,
            'info_patienten_valid'   => $info_patienten_valid,
            'result'                 => $result,
      	    'zip_filename'           => basename( $zip_file ),
            'zip_url'                => $zip_url
         )
      );
      return 'result';
   }

   /**
    * (non-PHPdoc)
    * @see feature/krhe/scripts/Ikrhe#getVersion()
    */
   public function getVersion()
   {
      return '1.0';
   }

   // Helper functions

   /**
    * ...
    *
    * @param $msg
    * @return unknown_type
    *
    */
   protected function handleError( $msg )
   {
      throw new Exception( $msg );
   }

   /**
    * ...
    *
    * @param $krhe_dir
    * @param $login_name
    * @return unknown_type
    *
    */
   protected function getExportPath( $krhe_dir, $login_name )
   {
   	  $tmp = getUploadDir( $this->_smarty, 'tmp', false );
   	  $path = $tmp[ 'tmp' ] . $krhe_dir . $login_name . '/';
      return $path;
   }

   /**
    * ...
    *
    * @param $path
    * @return unknown_type
    *
    */
   protected function createPath( $path )
   {
      umask( 0002 );
      if ( !file_exists( $path ) ) {
         if ( !mkdir( $path, 0777, true ) ) {
      		throw new Exception( "Konnte [$path] Pfad nicht erstellen." );
         }
      }
   }

   protected function CutTnm( $code )
   {
      $pos = strpos( $code, "(" );
      if ( $pos !== false ) {
          return substr( $code, 0, $pos );
      }
      return $code;
   }

}

?>
