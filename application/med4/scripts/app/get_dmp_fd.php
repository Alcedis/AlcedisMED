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

/**************************************************
 * eDMP Mamma Folgedokumentation
 * Datengewinnung
 **************************************************/

function get_dmp_fd( $db, $patient_id, $erkrankung_id, $dmp_eb_id, $doku_datum )
{
   // Checks
   if ( !strlen( $patient_id ) || !strlen( $doku_datum ) ) 
   {
      return;
   }
   
   // Init
   $data = array();

   // *****************************************************************************************************************
   // Behandlungsstatus nach operativer Therapie Primärtumor/kontralateraler Brustkrebs
   
   // -> Strahlentherapie
   $query = "
       SELECT
          CASE
             WHEN MAX( st_a.beginn ) IS NOT NULL    THEN 'vb'
             WHEN MAX( st_b.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( st_c.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( st_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS primaer_strahlen

       FROM
          patient p
          LEFT JOIN strahlentherapie st_a           ON st_a.patient_id=p.patient_id        AND
                                                       st_a.erkrankung_id=$erkrankung_id   AND
                                                       st_a.beginn<='$doku_datum'          AND
                                                       st_a.endstatus='abbr'
          LEFT JOIN strahlentherapie st_b           ON st_b.patient_id=p.patient_id        AND
                                                       st_b.erkrankung_id=$erkrankung_id   AND
                                                       st_b.beginn<='$doku_datum'          AND
                                                       st_b.ende IS NOT NULL
          LEFT JOIN strahlentherapie st_c           ON st_c.patient_id=p.patient_id        AND
                                                       st_c.erkrankung_id=$erkrankung_id   AND
                                                       st_c.beginn<='$doku_datum'

          LEFT JOIN ( SELECT 
                         * 
                      FROM
                         therapieplan tp

                      WHERE 
                         tp.patient_id=$patient_id                                         AND
                         tp.erkrankung_id=$erkrankung_id                                   AND
                         tp.datum<='$doku_datum'
                  
                      ORDER BY
                         tp.datum DESC )           st_d
          ON st_d.strahlen='1'

       WHERE
          p.patient_id=$patient_id
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // -> Chemotherapie
   $query = "
       SELECT
          CASE
             WHEN MAX( ts_a.beginn ) IS NOT NULL    THEN 'vb'
             WHEN MAX( ts_b.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( ts_c.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( ts_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS primaer_chemo

       FROM
          patient p
          LEFT JOIN therapie_systemisch ts_a        ON ts_a.patient_id=p.patient_id             AND
                                                       ts_a.erkrankung_id=$erkrankung_id        AND
                                                       ts_a.beginn<='$doku_datum'               AND
                                                       LEFT( ts_a.vorlage_therapie_art, 1 )='c' AND
                                                       ts_a.endstatus='abbr'
          LEFT JOIN therapie_systemisch ts_b        ON ts_b.patient_id=p.patient_id             AND
                                                       ts_b.erkrankung_id=$erkrankung_id        AND
                                                       ts_b.beginn<='$doku_datum'               AND
                                                       LEFT( ts_b.vorlage_therapie_art, 1 )='c' AND
                                                       ts_b.ende IS NOT NULL
          LEFT JOIN therapie_systemisch ts_c        ON ts_c.patient_id=p.patient_id             AND
                                                       ts_c.erkrankung_id=$erkrankung_id        AND
                                                       ts_c.beginn<='$doku_datum'               AND
                                                       LEFT( ts_c.vorlage_therapie_art, 1 )='c'

          LEFT JOIN ( SELECT 
                         * 
                      FROM
                         therapieplan tp

                      WHERE 
                         tp.patient_id=$patient_id                                              AND
                         tp.erkrankung_id=$erkrankung_id                                        AND
                         tp.datum<='$doku_datum'
                  
                      ORDER BY
                         tp.datum DESC )            ts_d
          ON ts_d.chemo='1'

       WHERE
          p.patient_id=$patient_id
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // -> Endokrine Therapie
   $query = "
       SELECT
          CASE
             WHEN MAX( ts_a.beginn ) IS NOT NULL    THEN 'vb'
             WHEN MAX( ts_b.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( ts_c.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( ts_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS primaer_endo

       FROM
          patient p
          LEFT JOIN therapie_systemisch ts_a        ON ts_a.patient_id=p.patient_id              AND
                                                       ts_a.erkrankung_id=$erkrankung_id         AND
                                                       ts_a.beginn<='$doku_datum'                AND
                                                       LEFT( ts_a.vorlage_therapie_art, 2 )='ah' AND
                                                       ts_a.endstatus='abbr'
          LEFT JOIN therapie_systemisch ts_b        ON ts_b.patient_id=p.patient_id              AND
                                                       ts_b.erkrankung_id=$erkrankung_id         AND
                                                       ts_b.beginn<='$doku_datum'                AND
                                                       LEFT( ts_b.vorlage_therapie_art, 2 )='ah' AND
                                                       ts_b.ende IS NOT NULL
          LEFT JOIN therapie_systemisch ts_c        ON ts_c.patient_id=p.patient_id              AND
                                                       ts_c.erkrankung_id=$erkrankung_id         AND
                                                       ts_c.beginn<='$doku_datum'                AND
                                                       LEFT( ts_c.vorlage_therapie_art, 2 )='ah'

          LEFT JOIN ( SELECT 
                         * 
                      FROM
                         therapieplan tp

                      WHERE 
                         tp.patient_id=$patient_id                                              AND
                         tp.erkrankung_id=$erkrankung_id                                        AND
                         tp.datum<='$doku_datum'
                  
                      ORDER BY
                         tp.datum DESC )            ts_d
          ON ts_d.ah='1'

       WHERE
          p.patient_id=$patient_id
          
       LIMIT 0, 1
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   
   
   // -> Manifestation lokoregionäres Rezidiv
   for( $i=0; $i<4; $i++ )
   {
      switch( $i )
      {
         case 0 :
            $prefix = "nein";
            $check = "=";
            $bedingung = "";
            break;
         case 1 :
            $prefix = "intra";
            $check = ">";
            $bedingung = "ts.rezidiv_lokal='1' AND eb.anam_op_bet='1' AND";
            break;
         case 2 :
            $prefix = "thorax";
            $check = ">";
            $bedingung = "ts.rezidiv_lokal='1' AND eb.anam_op_mast='1' AND";
            break;
         case 3 :
            $prefix = "axilla";
            $check = ">";
            $bedingung = "ts.rezidiv_lk='1' AND";
            break;
      }
      $query = "
         SELECT 
            IF( COUNT( * )" . $check . "0,    '1', NULL )                            AS neu_rezidiv_$prefix
         
         FROM
            tumorstatus ts
            LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id              AND 
                                              eb.erkrankung_id=$erkrankung_id        AND 
                                              eb.doku_datum<='$doku_datum'           AND                                  
                                              eb.dmp_brustkrebs_eb_id=$dmp_eb_id
            LEFT JOIN ( SELECT 
                           fbi.patient_id,
                           fbi.doku_datum
                     
         	             FROM 
         	            	  dmp_brustkrebs_fb fbi
         	            
         	             WHERE 
         	                fbi.erkrankung_id=$erkrankung_id                          AND 
         	                fbi.doku_datum<='$doku_datum'                             AND 
         	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
         	               
         	             ORDER BY
         	                fbi.doku_datum DESC ) fb 
         	 ON fb.patient_id=$patient_id
         
         WHERE 
            ts.patient_id=$patient_id                                                AND
            ts.erkrankung_id=$erkrankung_id                                          AND
            ts.datum_beurteilung<='$doku_datum'                                      AND
            LEFT( ts.anlass, 1 )='r'                                                 AND
            $bedingung
            ( ts.datum_sicherung>IFNULL( fb.doku_datum, eb.doku_datum ) )
            
         LIMIT 0, 1
      ";
      $result = sql_query_array( $db, $query );
         
      // Array umwandeln für Übernahme in DMP-Bogen
      if ( isset( $result[ 0 ] ) )
      {
         foreach( $result[ 0 ] as $name => $value )
         {
            $data[ $name ] = $value;
         }
      }
   }

   // -> Datum lokoregionäres Rezidiv
   $query = "
      SELECT 
         DATE_FORMAT( MAX( ts.datum_sicherung ), '%d.%m.%Y' )                     AS neu_rezidiv_datum
      
      FROM
         tumorstatus ts
         LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id              AND 
                                           eb.erkrankung_id=$erkrankung_id        AND 
                                           eb.doku_datum<='$doku_datum'           AND                                  
                                           eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                          AND 
      	                fbi.doku_datum<='$doku_datum'                             AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb 
      	 ON fb.patient_id=$patient_id
      
      WHERE 
         ts.patient_id=$patient_id                                                AND
         ts.erkrankung_id=$erkrankung_id                                          AND
         ts.datum_beurteilung<='$doku_datum'                                      AND
         LEFT( ts.anlass, 1 )='r'                                                 AND
         ts.rezidiv_lokal='1'                                                     AND
         ( ts.datum_sicherung>IFNULL( fb.doku_datum, eb.doku_datum ) )
         
      ORDER BY
         ts.datum_sicherung DESC
         
      LIMIT 0, 1
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) ) {
      foreach( $result[ 0 ] as $name => $value ) {
         $data[ $name ] = $value;
      }
   }
   
   // *****************************************************************************************************************
   // Seit der letzten Dokumentation neu aufgetretene Ereignisse
   
   // -> Manifestation kontralateraler Brustkrebs
   $query = "
      SELECT 
         DATE_FORMAT( MIN( ts_e.datum_sicherung ), '%d.%m.%Y' )                                AS neu_kontra_datum
      
      FROM
         tumorstatus ts
         LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id                           AND
                                           eb.erkrankung_id=$erkrankung_id                     AND
                                           eb.doku_datum<='$doku_datum'                        AND
                                           eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                                       AND
      	                fbi.doku_datum<='$doku_datum'                                          AND
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb 
      	ON fb.patient_id=$patient_id

        LEFT JOIN tumorstatus ts_e	ON ts.erkrankung_id=ts_e.erkrankung_id                     AND
                                       ts_e.anlass='p'                                         AND
                                       ts_e.sicherungsgrad='end'                               AND
                                       ( ( ts_e.diagnose_seite<>ts.diagnose_seite )            OR
                                         ( ts_e.lokalisation_seite<>ts.lokalisation_seite ) )

      WHERE 
         ts.patient_id=$patient_id                                                             AND
         ts.erkrankung_id=$erkrankung_id                                                       AND
         ts.datum_beurteilung<='$doku_datum'                                                   AND
         LEFT( ts.anlass, 1 )='r'                                                              AND
         ( ts.datum_sicherung>IFNULL( fb.doku_datum, eb.doku_datum ) )

   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) ) {
      foreach( $result[ 0 ] as $name => $value ) {
         $data[ $name ] = $value;
      }
   }

   $data[ 'neu_kontra_nein' ] = '1';
   if ( isset( $data[ 'neu_kontra_datum' ] ) &&
        !is_null( $data[ 'neu_kontra_datum' ] ) ) {
      $data[ 'neu_kontra_nein' ] = '0';
   }
   
   // -> Manifestation von Fernmetastasen
   for( $i=0; $i<4; $i++ ) {
      switch( $i ) {
         case 0 :
            $prefix = "leber";
            $bedingung = "tm.lokalisation='C22.0'";
            break;
         case 1 :
            $prefix = "lunge";
            $bedingung = "LEFT( tm.lokalisation, 3 )='C34'";
            break;
         case 2 :
            $prefix = "knochen";
            $bedingung = "LEFT( tm.lokalisation, 3 ) IN ( 'C40', 'C41' )";
            break;
         case 3 :
            $prefix = "andere";
            $bedingung = "tm.lokalisation!='C22.0' AND 
                          LEFT( tm.lokalisation, 3 )!='C34' AND
                          LEFT( tm.lokalisation, 3 )!='C40' AND
                          LEFT( tm.lokalisation, 3 )!='C41'";
            break;
      }
      $query = "
         SELECT 
            IF( MAX( tm.lokalisation ) IS NOT NULL, '1', NULL )                         AS neu_metast_$prefix
         
         FROM
            tumorstatus ts
            LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id                 AND 
                                              eb.erkrankung_id=$erkrankung_id           AND 
                                              eb.doku_datum<='$doku_datum'              AND
                                              eb.dmp_brustkrebs_eb_id=$dmp_eb_id
            
            LEFT JOIN ( SELECT 
                           fbi.patient_id,
                           fbi.doku_datum
                     
         	             FROM 
         	            	  dmp_brustkrebs_fb fbi
         	            
         	             WHERE 
         	                fbi.erkrankung_id=$erkrankung_id                            AND 
         	                fbi.doku_datum<='$doku_datum'                               AND 
         	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
         	               
         	             ORDER BY
         	                fbi.doku_datum DESC ) fb 
         	 ON fb.patient_id=$patient_id
         	 
         	 LEFT JOIN tumorstatus_metastasen tm ON tm.patient_id=$patient_id           AND
                                                    tm.erkrankung_id=$erkrankung_id     AND   																			 
         	                                        tm.tumorstatus_id=ts.tumorstatus_id AND 
         	                                        $bedingung
         	                                       
         
         WHERE 
            ts.patient_id=$patient_id                                                   AND
            ts.erkrankung_id=$erkrankung_id                                             AND
            ts.datum_beurteilung<='$doku_datum'                                         AND
            LEFT( ts.anlass, 1 )='r'                                                    AND
            ( ts.datum_sicherung>IFNULL( fb.doku_datum, eb.doku_datum ) )
            
         LIMIT 0, 1
      ";      
      $result = sql_query_array( $db, $query );
         
      // Array umwandeln für Übernahme in DMP-Bogen
      if ( isset( $result[ 0 ] ) )
      {
         foreach( $result[ 0 ] as $name => $value )
         {
            $data[ $name ] = $value;
         }
      }
   }
   $data[ 'neu_metast_nein' ] = '0';
   if ( ( !isset( $data[ 'neu_metast_leber' ]    ) ||
          is_null( $data[ 'neu_metast_leber' ]   ) || 
          $data[ 'neu_metast_leber' ] != '1'          ) && 
        ( !isset( $data[ 'neu_metast_lunge' ]    ) ||
          is_null( $data[ 'neu_metast_lunge' ]   ) ||
          $data[ 'neu_metast_lunge' ] != '1'          ) &&
        ( !isset( $data[ 'neu_metast_knochen' ]  ) ||
          is_null( $data[ 'neu_metast_knochen' ] ) ||
          $data[ 'neu_metast_knochen' ] != '1'        ) &&
        ( !isset( $data[ 'neu_metast_andere' ]   ) ||
          is_null( $data[ 'neu_metast_andere' ]  ) ||
          $data[ 'neu_metast_andere' ] != '1'         ) )
   {
      $data[ 'neu_metast_nein' ] = '1';
   }

   // -> Datum Fernmetastasen
   $query = "
      SELECT 
          DATE_FORMAT( MAX( ts.datum_sicherung ), '%d.%m.%Y' )                      AS neu_metast_datum
      
      FROM
         tumorstatus ts
         LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id                AND 
                                           eb.erkrankung_id=$erkrankung_id          AND 
                                           eb.doku_datum<='$doku_datum'             AND
                                           eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                            AND 
      	                fbi.doku_datum<='$doku_datum'                               AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb 
      	 ON fb.patient_id=$patient_id
      	 
      	 LEFT JOIN tumorstatus_metastasen tm ON tm.patient_id=$patient_id           AND
                                                tm.erkrankung_id=$erkrankung_id     AND   																			 
      	                                        tm.tumorstatus_id=ts.tumorstatus_id
      
      WHERE 
         ts.patient_id=$patient_id                                                  AND
         ts.erkrankung_id=$erkrankung_id                                            AND
         ts.datum_beurteilung<='$doku_datum'                                        AND
         LEFT( ts.anlass, 1 )='r'                                                   AND
         ( ts.datum_sicherung>IFNULL( fb.doku_datum, eb.doku_datum ) )
         
      ORDER BY
         ts.datum_sicherung DESC
      
      LIMIT 0, 1
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // -> Lymphödem
   $query = "
      SELECT 
          IF( MAX( d.datum ) IS NOT NULL, '1', NULL )                              AS lymphoedem
      
      FROM
         diagnose d
         LEFT JOIN dmp_brustkrebs_eb eb ON eb.patient_id=$patient_id               AND 
                                           eb.erkrankung_id=$erkrankung_id         AND 
                                           eb.doku_datum<='$doku_datum'            AND
                                           eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                           AND 
      	                fbi.doku_datum<='$doku_datum'                              AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb 
      	 ON fb.patient_id=$patient_id
      
      WHERE 
         d.patient_id=$patient_id                                                  AND
         d.erkrankung_id=$erkrankung_id                                            AND
         d.datum<='$doku_datum'                                                    AND
         ( d.datum>IFNULL( fb.doku_datum, eb.doku_datum ) )                        AND
         d.diagnose IN ( 'I89.0', 'I97.2', 'Q82.0' )
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }

   // -> Bisphosphonat-Therapie bei Knochenmetastasen
   $query = "
      SELECT 
         IF( MAX( vtw.wirkstoff ) IS NOT NULL AND
             eb.metast_lok_knochen='1',           '1', NULL )                                          AS metast_bip_ja,
         IF( MAX( vtw.wirkstoff ) IS NULL AND 
             eb.metast_lok_knochen='1',           '1', NULL )                                          AS metast_bip_nein
      
      FROM
         therapie_systemisch tsys
         LEFT JOIN dmp_brustkrebs_eb eb             ON eb.patient_id=$patient_id                       AND 
                                                       eb.erkrankung_id=$erkrankung_id                 AND 
                                                       eb.doku_datum<='$doku_datum'                    AND
                                                       eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                                               AND 
      	                fbi.doku_datum<='$doku_datum'                                                  AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb    ON fb.patient_id=$patient_id
      	 
      	 LEFT JOIN vorlage_therapie           vt    ON tsys.vorlage_therapie_id=vt.vorlage_therapie_id
                  
         LEFT JOIN vorlage_therapie_wirkstoff vtw   ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id  AND
                                                       vtw.wirkstoff='biphosphonate'
      
      WHERE 
         tsys.patient_id=$patient_id                                                                   AND
         tsys.erkrankung_id=$erkrankung_id                                                             AND
         tsys.beginn<='$doku_datum'                                                                    AND
         ( tsys.ende IS NULL OR
           tsys.ende>fb.doku_datum OR tsys.ende>eb.doku_datum )                                        AND
         eb.metast_lok_knochen='1'
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // *****************************************************************************************************************
   // Sonstige Beratung und Behandlung
   
   // -> Systematische Tumorschmerztherapie
   $query = "
      SELECT 
         IF( MAX( tsys.vorlage_therapie_art ) IS NOT NULL, '1', NULL )                                 AS sonst_schmerz_ja,
         IF( MAX( tsys.vorlage_therapie_art ) IS NULL,     '1', NULL )                                 AS sonst_schmerz_nein
      
      FROM
         therapie_systemisch tsys
         LEFT JOIN dmp_brustkrebs_eb eb             ON eb.patient_id=$patient_id                       AND 
                                                       eb.erkrankung_id=$erkrankung_id                 AND 
                                                       eb.doku_datum<='$doku_datum'                    AND
                                                       eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                                               AND 
      	                fbi.doku_datum<='$doku_datum'                                                  AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb    ON fb.patient_id=$patient_id
      
      WHERE 
         tsys.patient_id=$patient_id                                                                   AND
         tsys.erkrankung_id=$erkrankung_id                                                             AND
         tsys.beginn<='$doku_datum'                                                                    AND
         ( tsys.ende IS NULL OR
           tsys.ende>fb.doku_datum OR tsys.ende>eb.doku_datum )                                        AND
         tsys.vorlage_therapie_art='schmerz'
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // -> Mammographie seit letzter Dokumentation durchgeführt
   $query = "
      SELECT 
         IF( MAX( u.art ) IS NOT NULL, '1', NULL )                                                     AS sonst_mammo_ja,
         IF( MAX( u.art ) IS NULL,     '1', NULL )                                                     AS sonst_mammo_nein
      
      FROM
         untersuchung u
         LEFT JOIN dmp_brustkrebs_eb eb             ON eb.patient_id=$patient_id                       AND 
                                                       eb.erkrankung_id=$erkrankung_id                 AND 
                                                       eb.doku_datum<='$doku_datum'                    AND
                                                       eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                                               AND 
      	                fbi.doku_datum<='$doku_datum'                                                  AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb    ON fb.patient_id=$patient_id
      
      WHERE 
         u.patient_id=$patient_id                                                                      AND
         u.erkrankung_id=$erkrankung_id                                                                AND
         u.datum<='$doku_datum'                                                                        AND
         ( u.datum>fb.doku_datum OR u.datum>eb.doku_datum )                                            AND
         LEFT( u.art, 5 )='3-100'
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }
   
   // -> Information über psychosoziales Versorgungsangebot erfolgt
   $query = "
      SELECT 
         IF( MAX( b.psychoonkologie ) IS NOT NULL, '1', NULL )                                         AS sonst_psycho_ja,
         IF( MAX( b.psychoonkologie ) IS NULL,     '1', NULL )                                         AS sonst_psycho_nein
      
      FROM
         beratung b
         LEFT JOIN dmp_brustkrebs_eb eb             ON eb.patient_id=$patient_id                       AND 
                                                       eb.erkrankung_id=$erkrankung_id                 AND 
                                                       eb.doku_datum<='$doku_datum'                    AND
                                                       eb.dmp_brustkrebs_eb_id=$dmp_eb_id
         
         LEFT JOIN ( SELECT 
                        fbi.patient_id,
                        fbi.doku_datum
                  
      	             FROM 
      	            	  dmp_brustkrebs_fb fbi
      	            
      	             WHERE 
      	                fbi.erkrankung_id=$erkrankung_id                                               AND 
      	                fbi.doku_datum<='$doku_datum'                                                  AND 
      	                fbi.dmp_brustkrebs_eb_id=$dmp_eb_id
      	               
      	             ORDER BY
      	                fbi.doku_datum DESC ) fb    ON fb.patient_id=$patient_id
      
      WHERE 
         b.patient_id=$patient_id                                                                      AND
         b.erkrankung_id=$erkrankung_id                                                                AND
         b.datum<='$doku_datum'                                                                        AND
         ( b.datum>fb.doku_datum OR b.datum>eb.doku_datum )                                            AND
         b.psychoonkologie='1'
   ";
   $result = sql_query_array( $db, $query );
      
   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }

   // *****************************************************************************************************************
   // Rückgabe der gewonnenen Daten
   return $data;
}

?>
