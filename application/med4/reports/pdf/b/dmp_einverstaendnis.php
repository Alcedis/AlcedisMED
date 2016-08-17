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

class reportContentDmp_einverstaendnis extends customReport
{
   public function header(){}

   public function generate()
   {
      $config           = $this->loadConfigs('dmp_einverstaendnis', false, true);
      $fields_patient   = $this->_smarty->widget->loadExtFields('fields/app/patient.php');
      $fields_org       = $this->_smarty->widget->loadExtFields('fields/base/org.php');
      $patient_id       = $this->_params['patient_id'];
      $org_id           = $this->_params['org_id'];
      $datum            = date('d.m.Y');

      $query_patient    = "SELECT * FROM patient WHERE patient_id = '{$patient_id}'";
      data2list($this->_db, $fields_patient, $query_patient);

      $query_org        = "SELECT * FROM org WHERE org_id = '{$org_id}'";
      data2list($this->_db, $fields_org, $query_org);

      // -------------------------------------------------------------------------------------------
      //  Datenaufbereitung
      //
      $pat_name_pat           = $fields_patient[ 'nachname' ][ 'value'][ 0 ] . ', ' . $fields_patient[ 'vorname' ][ 'value' ][ 0 ];
      $pat_strasse_pat        = $fields_patient[ 'strasse' ][ 'value'][ 0 ] . ' ' . $fields_patient['hausnr']['value'][0];
      $pat_ort_pat            = $fields_patient[ 'plz' ][ 'value' ][ 0 ]      . ' ' . $fields_patient[ 'ort' ][ 'value' ][ 0 ];
      $pat_geb_am             = $fields_patient[ 'geburtsdatum' ][ 'value' ][ 0 ];
      $pat_kassen_nr          = $fields_patient[ 'kv_iknr' ][ 'value' ][ 0 ];
      $pat_kostentraeger      = dlookup($this->_db, "l_ktst", "name", "iknr = '{$pat_kassen_nr}'");
      $pat_versicherungs_nr   = $fields_patient['kv_nr']['value'][0];
      $pat_status             = strlen( $fields_patient['kv_status']['bez'][0] ) ? (substr($fields_patient['kv_status']['bez'][0], 0,1) . '000') : '';
      $pat_vk_gueltig_bis     = $fields_patient['kv_gueltig_bis']['value'][0];
      $pat_datum              = $datum;

      //Telefon Privat (Handy)
      $pat_telefon_privat    = str_pad($fields_patient['telefon']['value'][0], 16);

      //Fax
      $pat_fax               = str_pad($fields_patient['telefax']['value'][0], 17);

      //Email
      $pat_email             = str_pad($fields_patient['email']['value'][0], 54);

      //Datum
      $datum_10_00_0000       = $datum[0];
      $datum_01_00_0000       = $datum[1];
      $datum_00_10_0000       = $datum[3];
      $datum_00_01_0000       = $datum[4];
      $datum_00_00_1000       = $datum[6];
      $datum_00_00_0100       = $datum[7];
      $datum_00_00_0010       = $datum[8];
      $datum_00_00_0001       = $datum[9];

      // PDF-Datei erstellen
      $ox = -3;
      $oy = -10;

      for ($page=1; $page < 4; $page++) {
         // Klinik
         $flds[] = array('page' => $page, 'x' =>  30 + $ox, 'y'   =>  36 + $oy, 'db_field'   => $pat_kostentraeger );
         $flds[] = array('page' => $page, 'x' =>  30 + $ox, 'y'   =>  62 + $oy, 'db_field'   => $pat_name_pat );
         $flds[] = array('page' => $page, 'x' =>  30 + $ox, 'y'   =>  78 + $oy, 'db_field'   => $pat_strasse_pat );
         $flds[] = array('page' => $page, 'x' =>  30 + $ox, 'y'   =>  90 + $oy, 'db_field'   => $pat_ort_pat );
         $flds[] = array('page' => $page, 'x' => 200 + $ox, 'y'   =>  77 + $oy, 'db_field'   => $pat_geb_am);
         $flds[] = array('page' => $page, 'x' =>  30 + $ox, 'y'   => 118 + $oy, 'db_field'   => $pat_kassen_nr);
         $flds[] = array('page' => $page, 'x' =>  92 + $ox, 'y'   => 118 + $oy, 'db_field'   => $pat_versicherungs_nr);
         $flds[] = array('page' => $page, 'x' => 188 + $ox, 'y'   => 118 + $oy, 'db_field'   => $pat_status);
         $flds[] = array('page' => $page, 'x' => 400 + $ox, 'y'   => 152 + $oy, 'db_field'   => reset($fields_org['ik_nr']['value']));
         $flds[] = array('page' => $page, 'x' => 188 + $ox, 'y'   => 142 + $oy, 'db_field'   => $pat_datum);

         //Telefon Privat (Handy)
         $space = "21";
         for( $i=0; $i<13; $i++ ) {
            $flds[] = array('page' => $page, 'x' => $space + $ox , 'y' => 193.5 + $oy, 'db_field' => $pat_telefon_privat[$i]);
            $space = $space + 14.1;
         }

         //Fax
         $space = "404.5";
         for( $i=0; $i<12; $i++ ) {
            $flds[] = array('page' => $page, 'x' => $space + $ox , 'y' => 193.5 + $oy, 'db_field' => $pat_fax[$i]);
            $space = $space + 14.1;
         }

         //Email
         $space = "21.5";
         for( $i=0; $i<39; $i++ ) {
            $flds[] = array('page' => $page, 'x' => $space + $ox , 'y' => 222.5 + $oy, 'db_field' => $pat_email[$i]);
            $space = $space + 14.1;
         }

          $y = 646.5;

         // Datum Unterschrift Patient
         $flds[] = array('page' => $page, 'x' =>  28 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_10_00_0000);
         $flds[] = array('page' => $page, 'x' =>  43 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_01_00_0000);
         $flds[] = array('page' => $page, 'x' =>  62 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_10_0000);
         $flds[] = array('page' => $page, 'x' =>  76 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_01_0000);
         $flds[] = array('page' => $page, 'x' =>  95 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_1000);
         $flds[] = array('page' => $page, 'x' => 110 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0100);
         $flds[] = array('page' => $page, 'x' => 125.5 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0010);
         $flds[] = array('page' => $page, 'x' => 140 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0001);

         $y = 816;

         // Datum Unterschrift Arzt
         $flds[] = array('page' => $page, 'x' =>  28 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_10_00_0000);
         $flds[] = array('page' => $page, 'x' =>  43 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_01_00_0000);
         $flds[] = array('page' => $page, 'x' =>  62 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_10_0000);
         $flds[] = array('page' => $page, 'x' =>  76 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_01_0000);
         $flds[] = array('page' => $page, 'x' =>  95 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_1000);
         $flds[] = array('page' => $page, 'x' => 110 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0100);
         $flds[] = array('page' => $page, 'x' => 125.5 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0010);
         $flds[] = array('page' => $page, 'x' => 140 + $ox, 'y' =>  $y + $oy, 'db_field' => $datum_00_00_0001);
      }
      // Dummies zur Erzeugung der statischen Seiten in der Ausgabe
      $flds[] = array( 'page' => 4, 'x' => 100 + $ox, 'y' => 100 + $oy, 'db_field' => '' );
      $flds[] = array( 'page' => 5, 'x' => 100 + $ox, 'y' => 100 + $oy, 'db_field' => '' );
      $flds[] = array( 'page' => 6, 'x' => 100 + $ox, 'y' => 100 + $oy, 'db_field' => '' );

      $pdfRessource = "reports/pdf/b/dmp_einverstaendnis/dmp_einverstaendnis.pdf";

      $this->_createFpdi($pdfRessource, $flds);
   }
}

?>
