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

class CCsv1_0_0 extends CMedBaseExport
{

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_csv.conf', 'export_csv' );
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
    * @see feature/exports/scripts/CMedBaseExport#Export()
    */
   public function Export( $session, $request )
   {
      $export_filter = array();
      $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
      $export_filter[ 'format_date' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
      $export_filter[ 'newline' ] = "\r\n";
      $export_filter[ 'quote' ] = isset( $this->_config[ 'exp_csv_quote' ] ) ? $this->_config[ 'exp_csv_quote' ] : '"';
      $export_filter[ 'separator' ] = isset( $this->_config[ 'exp_csv_separator' ] ) ? $this->_config[ 'exp_csv_separator' ] : ';';
      $export_filter[ 'file_suffix' ] = isset( $this->_config[ 'exp_csv_file_suffix' ] ) ? $this->_config[ 'exp_csv_file_suffix' ] : '.csv';
      $ext_dir = isset( $this->_config[ 'exp_csv_dir' ] ) ? $this->_config[ 'exp_csv_dir' ] : 'csv/';
      $this->_export_path = $this->GetExportPath( $ext_dir, $export_filter[ 'login_name' ] );
      if ( file_exists( $this->_export_path ) ) {
         $this->DeleteDirectory( $this->_export_path );
      }
      $this->createPath( $this->_export_path );
      // Pfade anlegen
      $export_filter[ 'org_id' ] = $session[ 'sess_org_id' ];
      $export_filter[ 'tables' ] = array(
        "patient",
        "abschluss",
        "anamnese",
        "anamnese_erkrankung",
        "anamnese_familie",
        "aufenthalt",
        "begleitmedikation",
        "behandler",
        "beratung",
        "brief",
        "brief_empfaenger",
        "diagnose",
        "dmp_brustkrebs_eb",
        "dmp_brustkrebs_fb",
        "eingriff",
        "eingriff_ops",
        "ekr",
        "erkrankung",
        "foto",
        "histologie",
        "histologie_einzel",
        "komplikation",
        "konferenz",
        "konferenz_dokument",
        "konferenz_patient",
        "konferenz_teilnehmer",
        "konferenz_teilnehmer_profil",
        "labor",
        "labor_wert",
        "nachsorge",
        "nachsorge_erkrankung",
        "nebenwirkung",
        "qs_18_1_b",
        "qs_18_1_brust",
        "qs_18_1_o",
        "sonstige_therapie",
        "strahlentherapie",
        "studie",
        "termin",
        "therapieplan",
        "therapieplan_abweichung",
        "therapie_systemisch",
        "therapie_systemisch_zyklus",
        "therapie_systemisch_zyklustag",
        "therapie_systemisch_zyklustag_wirkstoff",
        "tumorstatus",
        "tumorstatus_metastasen",
        "untersuchung",
        "untersuchung_lokalisation",
        "user",
        "zytologie",
        "zytologie_aberration",
        "vorlage_studie",
        "vorlage_therapie",
        "vorlage_therapie_wirkstoff",
        "l_basic",
        "l_dmp"

      );
      return $this->ExportCsv( $this->_export_path, $export_filter );
   }

   /**
    *
    * @param $path
    * @param $export_filter
    * @return array $exports
    */
   protected function ExportCsv( $path, $export_filter )
   {
      $exports = array( 'valid' => array(),
      		  	        'invalid' => array() );
      $patients = array();
      $query = "SELECT patient_id FROM patient WHERE org_id = {$export_filter[ 'org_id' ]}";
      $result = sql_query_array( $this->_db, $query );
      foreach( $result AS $entry ) {
         $patients[] = $entry[ 'patient_id' ];
      }
      $patients[] = 0;
      $patient_ids = implode( ',', $patients );
      $where_patient1 = " AND patient_id IN ( $patient_ids ) ";
      $where_patient2 = " AND p.patient_id IN ( $patient_ids ) ";
      foreach( $export_filter[ 'tables' ] as $table ) {
         $query = "SHOW FIELDS FROM $table";
         $fields = sql_query_array( $this->_db, $query );
         $filename = $path . $table . $export_filter[ 'file_suffix' ];
         // Soll auf patient gejoint werden?
         // Gleichzeitig Benutzernamen und Passwort-Hashes entfernen
         $join_patient = false;
         if ( $table != 'patient' ) {
            foreach( $fields as $i => $field ) {
               if ( $field[ 'Field' ] == 'patient_id' ) {
                  $join_patient = true;
               }
               if ( in_array( $field[ 'Field' ], array( 'loginname', 'pwd' ) ) ) {
                  unset( $fields[ $i ] );
               }
            }
         }
         // ggf. Felder für Patientendaten hinzufügen
         if ( $join_patient ) {
            $fields = array_merge(
               array(
                  array( 'Field' => 'nachname'     , 'Type' => 'varchar' ),
                  array( 'Field' => 'vorname'      , 'Type' => 'varchar' ),
                  array( 'Field' => 'geburtsdatum' , 'Type' => 'date'    )
               ),
               $fields
         	);
         }
         // Daten aus der Tabelle holen
         $query	= "SELECT "
                .   ( $join_patient ? 'p.nachname, p.vorname, p.geburtsdatum, ' : '' )
                . " t.*"
                . "FROM $table t "
                .   ( $join_patient	? "INNER JOIN patient p ON t.patient_id=p.patient_id " : '' )
                . "WHERE 1 ";
         if ( $join_patient ) {
            $query .= $where_patient2;
         }
         elseif ( $table == 'patient' ) {
            $query .= $where_patient1;
         }
         $data = sql_query_array( $this->_db, $query );
         if ( !$this->WriteCsvFile( $filename, $fields, $data ) ) {
            $exports[ 'invalid' ][] = array( "count" => count( $data ), "file" => $table . $export_filter[ 'file_suffix' ], "url" => $filename );
         }
         else {
            $exports[ 'valid' ][] = array( "count" => count( $data ), "file" => $table . $export_filter[ 'file_suffix' ], "url" => $filename );
         }
      }
      return $exports;
   }

}

?>
