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
 * eDMP Erstdokumentation
 * Datengewinnung
 **************************************************/

function get_dmp_ed( $db, $patient_id, $erkrankung_id, $doku_datum, $pnp = false )
{
   // Checks
   if ( !strlen( $patient_id ) || !strlen( $doku_datum ) ) {
      return;
   }

   // Init
   $data = array();

   //Grund für die Einschreibung

   // *****************************************************************************************************************
   // Einschreibung
   // bleibt leer bei pnp-Einschreibung
   if ( !$pnp )
   {
      $query = "
         SELECT
		    DATE_FORMAT( MIN( ts_p.datum_beurteilung ), '%d.%m.%Y' )               AS mani_primaer,
		    DATE_FORMAT( MIN( ts_k.datum_beurteilung ), '%d.%m.%Y' )               AS mani_kontra,
		    DATE_FORMAT( MIN( ts_r.datum_beurteilung ), '%d.%m.%Y' )               AS mani_rezidiv,
		    DATE_FORMAT( MIN( ts_m.datum_beurteilung ), '%d.%m.%Y' )               AS mani_metast

		 FROM patient p
		    LEFT JOIN tumorstatus ts_p ON p.patient_id=ts_p.patient_id             AND
		                                  ts_p.erkrankung_id=$erkrankung_id        AND
		                                  ts_p.anlass='p'                          AND
                                          ts_p.datum_beurteilung<='$doku_datum'
		    LEFT JOIN tumorstatus ts_k ON p.patient_id=ts_k.patient_id             AND
		                                  ts_k.erkrankung_id=$erkrankung_id        AND
		                                  ts_k.anlass='p'                          AND
		                                  ts_p.diagnose_seite!=ts_k.diagnose_seite AND
                                          ts_k.datum_beurteilung<='$doku_datum'
		    LEFT JOIN tumorstatus ts_r ON p.patient_id=ts_r.patient_id             AND
		                                  ts_r.erkrankung_id=$erkrankung_id        AND
		                                  ts_r.anlass!='p'                         AND
		                                  ts_r.anlass!='b'                         AND
		                                  ts_r.rezidiv_lokal='1'                   AND
                                          ts_r.datum_beurteilung<='$doku_datum'
		    LEFT JOIN tumorstatus ts_m ON p.patient_id=ts_m.patient_id             AND
		                                  ts_m.erkrankung_id=$erkrankung_id        AND
		                                  ts_m.anlass!='p'                         AND
		                                  ts_m.anlass!='b'                         AND
		                                  ts_m.rezidiv_lokal='1'                   AND
                                          ts_m.datum_beurteilung<='$doku_datum'
		 WHERE
		    p.patient_id=$patient_id
      ";
      $result = sql_query_array( $db, $query );

      // Array umwandeln für Übernahme in DMP-Bogen
      if ( isset( $result[ 0 ] ) ) {
         foreach( $result[ 0 ] as $name => $value ) {
            $data[ $name ] = $value;
         }
      }
   }

   // *****************************************************************************************************************
   // Anamnese und Behandlungsstatus Primärtumor/kontralateraler Brustkrebs

   // -> Betroffene Brust
   $query = "
      SELECT
         IF( ( ts_l.diagnose_seite IS NOT NULL ) AND ( ts_l.diagnose_seite='L' ) AND
             ( ( ts_r.diagnose_seite IS NULL ) OR ( ts_r.diagnose_seite!='R' ) ), '1', NULL) AS anam_brust_links,
         IF( ( ts_r.diagnose_seite IS NOT NULL ) AND ( ts_r.diagnose_seite='R' ) AND
             ( ( ts_l.diagnose_seite IS NULL ) OR ( ts_l.diagnose_seite!='L' ) ), '1', NULL) AS anam_brust_rechts,
         IF( ( ts_l.diagnose_seite IS NOT NULL ) AND ( ts_l.diagnose_seite='L' ) AND
             ( ts_r.diagnose_seite IS NOT NULL ) AND ( ts_r.diagnose_seite='R' ), '1', NULL) AS anam_brust_beidseits
      FROM patient p
         LEFT JOIN tumorstatus ts_l ON ts_l.patient_id=p.patient_id                          AND
                                       ts_l.erkrankung_id=$erkrankung_id                     AND
                                       ts_l.anlass='p'                                       AND
                                       ts_l.diagnose_seite='L'                               AND
                                       ts_l.datum_beurteilung<='$doku_datum'
         LEFT JOIN tumorstatus ts_r ON ts_r.patient_id=p.patient_id                          AND
                                       ts_r.erkrankung_id=$erkrankung_id                     AND
                                       ts_r.anlass='p'                                       AND
                                       ts_r.diagnose_seite='R'                               AND
                                       ts_r.datum_beurteilung<='$doku_datum'
      WHERE
         p.patient_id=$patient_id
      ORDER BY
  		 ts_l.datum_beurteilung ASC,
  		 ts_r.datum_beurteilung ASC
      LIMIT 0, 1
   ";
   $result = sql_query_array( $db, $query );

   // Array umwandeln für Übernahme in DMP-Bogen
   if ( isset( $result[ 0 ] ) ) {
      foreach( $result[ 0 ] as $name => $value ) {
         $data[ $name ] = $value;
      }
   }

   // -> Welche Untersuchungen wurden zur Diagnostik durchgeführt?
   $query = "
      SELECT
         IF( ( e.art_diagnostik IS NOT NULL ) AND ( MAX( eo_s.prozedur ) > 0 ), '1', NULL )   AS anam_unt_stanz,
         IF( ( e.art_diagnostik IS NOT NULL ) AND ( MAX( eo_v.prozedur ) > 0 ), '1', NULL )   AS anam_unt_vakuum,
         IF( ( e.art_diagnostik IS NOT NULL ) AND ( MAX( eo_o.prozedur ) > 0 ), '1', NULL )   AS anam_unt_offen,
         IF( MAX( u_m.art ) IS NOT NULL,                                        '1', NULL )   AS anam_unt_mammo,
         IF( MAX( u_s.art ) IS NOT NULL,                                        '1', NULL )   AS anam_unt_sono,
         IF( IF( ( e.art_diagnostik IS NOT NULL ) AND
	             IF( ( MAX( eo_s.prozedur ) > 0 ), '1', NULL ) IS NULL AND
                 IF( ( MAX( eo_v.prozedur ) > 0 ), '1', NULL ) IS NULL AND
                 IF( ( MAX( eo_o.prozedur ) > 0 ), '1', NULL ) IS NULL, 1, 0 ) OR
	         IF( MAX( u_m.art ) IS NULL AND MAX( u_s.art ) IS NULL, 1, 0 ),     '1', NULL )   AS anam_unt_andere
      FROM
         patient p
         LEFT JOIN eingriff e        ON e.patient_id=p.patient_id                             AND
                                        e.erkrankung_id=$erkrankung_id                        AND
                                        e.datum<='$doku_datum'                                AND
                                        e.art_diagnostik='1'
         LEFT JOIN eingriff_ops eo_s ON eo_s.patient_id=p.patient_id                          AND
                                        eo_s.erkrankung_id=$erkrankung_id                     AND
                                        e.eingriff_id=eo_s.eingriff_id                        AND
                                        eo_s.prozedur='1-e02.x'
         LEFT JOIN eingriff_ops eo_v ON eo_v.patient_id=p.patient_id                          AND
                                        eo_v.erkrankung_id=$erkrankung_id                     AND
                                        e.eingriff_id=eo_v.eingriff_id                        AND
                                        eo_v.prozedur='1-e03.x'
         LEFT JOIN eingriff_ops eo_o ON eo_o.patient_id=p.patient_id                          AND
                                        eo_o.erkrankung_id=$erkrankung_id                     AND
                                        e.eingriff_id=eo_o.eingriff_id                        AND
                                        eo_o.prozedur='5-870.0'
         LEFT JOIN untersuchung u_m  ON u_m.patient_id=p.patient_id                           AND
                                        u_m.erkrankung_id=$erkrankung_id                      AND
                                        u_m.datum<='$doku_datum'                              AND
                                        LEFT(u_m.art,5)='3-100'
         LEFT JOIN untersuchung u_s  ON u_s.patient_id=p.patient_id                           AND
                                        u_s.erkrankung_id=$erkrankung_id                      AND
                                        u_s.datum<='$doku_datum'                              AND
                                        u_s.art='3-e26.y'
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

   // get bet and mastektomy codes dynamically
   $betCodes  = sql_query_array($db, "SELECT code FROM l_ops WHERE code BETWEEN '5-870' AND '5-871'");
   $mastCodes = sql_query_array($db, "SELECT code FROM l_ops WHERE code BETWEEN '5-872' AND '5-876'");

   // bring codes in implodable form
   foreach ($betCodes as &$code)
       $code = '%' . $code['code'] . '%';
   foreach ($mastCodes as &$code)
       $code = '%' . $code['code'] . '%';

   // build substrings
   $substr = "SUBSTRING(report_param, 5) LIKE '";
   $betCheck = $substr . implode("' OR $substr", $betCodes) . "'";
   $mastCheck = $substr . implode("' OR $substr", $mastCodes) . "'";

   // use status form for code sniffing
   $query = "
       SELECT
           IF(COUNT(IF($betCheck, form_id, NULL)) > 0 AND
              COUNT(IF($mastCheck, form_id, NULL)) = 0, 1, NULL)    AS anam_op_bet,
           IF(COUNT(IF($mastCheck, form_id, NULL)) > 0, 1, NULL)    AS anam_op_mast
        FROM status
        WHERE
           form = 'eingriff' AND
           form_date <= '$doku_datum' AND
           patient_id = '$patient_id' AND
           erkrankung_id = '$erkrankung_id'
   ";
   $result = sql_query_array( $db, $query );

   // Array umwandeln für Übernahme in DMP-Bogen
   if( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }

   $query = "
      SELECT
         IF( MAX( eo_s.prozedur ) IS NOT NULL,        '1', NULL )                        AS anam_op_sln,
         IF( MAX( eo_s.prozedur ) IS NOT NULL,        '1', NULL )                        AS bef_lk_entf_sln,
         IF( MAX( eo_a.prozedur ) IS NOT NULL,        '1', NULL )                        AS anam_op_axilla,
         IF( MAX( e_an.art_rekonstruktion ) IS NOT NULL, '1', NULL )                     AS anam_op_anderes

      FROM
         patient p
         LEFT JOIN eingriff e          ON e.patient_id=p.patient_id                      AND
                                          e.erkrankung_id=$erkrankung_id                 AND
                                          e.datum<='$doku_datum'                         AND
                                          e.art_lk='1'
         LEFT JOIN eingriff_ops eo_s   ON eo_s.patient_id=p.patient_id                   AND
                                          eo_s.erkrankung_id=$erkrankung_id              AND
                                          e.eingriff_id=eo_s.eingriff_id                 AND
                                          LEFT( eo_s.prozedur, 7 )='5-401.1'
         LEFT JOIN eingriff_ops eo_a   ON eo_a.patient_id=p.patient_id                   AND
                                          eo_a.erkrankung_id=$erkrankung_id              AND
                                          e.eingriff_id=eo_a.eingriff_id                 AND
                                          ( eo_a.prozedur LIKE '5-402.1%'                OR
					                        eo_a.prozedur LIKE '5-404.0%'                OR
                                            eo_a.prozedur LIKE '5-406.1%' )
         LEFT JOIN eingriff e_an       ON e_an.patient_id=p.patient_id                   AND
                                          e_an.erkrankung_id=$erkrankung_id              AND
                                          e_an.datum<='$doku_datum'                      AND
                                          e_an.art_rekonstruktion='1'

      WHERE
         p.patient_id=$patient_id

   ";
   $result = sql_query_array( $db, $query );

   // Array umwandeln für Übernahme in DMP-Bogen
   if( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }

   // *****************************************************************************************************************
   // Aktueller Befundstatus Primärtumor/kontralateraler Brustkrebs

   // -> pT
   $query = "
      SELECT
         IF( MAX( e.art_primaertumor )   IS NULL AND
             MAX( e.art_rezidiv )        IS NULL AND
             MAX( e.art_lk )             IS NULL AND
             MAX( e.art_rekonstruktion ) IS NULL,     '1', NULL )                        AS anam_op_keine,
         IF( MAX( e.art_primaertumor )   IS NULL AND
             MAX( e.art_rezidiv )        IS NULL AND
             MAX( e.art_lk )             IS NULL AND
             MAX( e.art_rekonstruktion ) IS NULL,     '1', NULL )                        AS bef_pt_keine,
         IF( MAX( e.art_primaertumor )   IS NULL AND
             MAX( e.art_rezidiv )        IS NULL AND
             MAX( e.art_lk )             IS NULL AND
             MAX( e.art_rekonstruktion ) IS NULL,     '1', NULL )                        AS bef_pn_keine,
         IF( MAX( e.art_primaertumor )   IS NULL AND
             MAX( e.art_rezidiv )        IS NULL AND
             MAX( e.art_lk )             IS NULL AND
             MAX( e.art_rekonstruktion ) IS NULL,     '1', NULL )                        AS bef_r_keine,
         IF( MAX( e_lk.art_lk )          IS NULL,     '1', NULL )                        AS bef_lk_entf_keine

      FROM
         patient p
         LEFT JOIN eingriff e          ON e.patient_id=p.patient_id                      AND
                                          e.erkrankung_id=$erkrankung_id                 AND
                                          e.datum<='$doku_datum'                         AND
                                          ( e.art_primaertumor='1'                       OR
                                            e.art_rezidiv='1'                            OR
                                            e.art_lk='1'                                 OR
                                            e.art_rekonstruktion='1' )
         LEFT JOIN eingriff e_lk       ON e_lk.patient_id=p.patient_id                   AND
                                          e_lk.erkrankung_id=$erkrankung_id              AND
                                          e_lk.datum<='$doku_datum'                      AND
                                          e_lk.art_lk='1'


      WHERE
         p.patient_id=$patient_id
   ";
   $result = sql_query_array( $db, $query );

   // Array umwandeln für Übernahme in DMP-Bogen
   if( isset( $result[ 0 ] ) )
   {
      foreach( $result[ 0 ] as $name => $value )
      {
         $data[ $name ] = $value;
      }
   }

   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pTis', 'tis', $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pT0',  '0',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pT1',  '1',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pT2',  '2',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pT3',  '3',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pT4',  '4',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 't', 'pTX',  'x',   $data );

   // -> pN
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 'n', 'pN0',  '0',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 'n', 'pN1',  '1',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 'n', 'pN2',  '2',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 'n', 'pN3',  '3',   $data );
   HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, 'n', 'pNX',  '4',  $data );

   // -> M
   $query = "
      SELECT
         IF( ts_e.datum_beurteilung IS NOT NULL,
             CASE
                WHEN ts_e.m IN ( 'cM0', 'pM0' )            THEN '0'
                WHEN LEFT( ts_e.m, 3 ) IN ( 'cM1', 'pM1' ) THEN '1'
                WHEN ts_e.m IN ( 'cMX', 'pMX' )            THEN 'X'
                ELSE                                            NULL
             END, NULL )                                                   AS bef_m

      FROM
         patient                                                    p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id            AND
                        ts.erkrankung_id=$erkrankung_id      AND
                        ts.datum_beurteilung<='$doku_datum'  AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                 ts_e
         ON ts_e.sicherungsgrad='end'

      WHERE
         p.patient_id=$patient_id

      UNION

      SELECT
         IF( ts_v.datum_beurteilung IS NOT NULL,
             CASE
                WHEN ts_v.m IN ( 'cM0', 'pM0' )            THEN '0'
                WHEN LEFT( ts_v.m, 3 ) IN ( 'cM1', 'pM1' ) THEN '1'
                WHEN ts_v.m IN ( 'cMX', 'pMX' )            THEN 'X'
                ELSE                                            NULL
             END, NULL )                                                   AS bef_m

      FROM
         patient                                                    p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id            AND
                        ts.erkrankung_id=$erkrankung_id      AND
                        ts.datum_beurteilung<='$doku_datum'  AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                 ts_v
         ON ts_v.sicherungsgrad='vor'

      WHERE
         p.patient_id=$patient_id

      ORDER BY
         bef_m DESC

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

   // -> Grading
   $query = "
      SELECT
         IF( ts_e.datum_beurteilung IS NOT NULL,
             CASE
                WHEN ts_e.g IN ( '1', 'L' )                THEN '1'
                WHEN ts_e.g='2'                            THEN '2'
                WHEN ts_e.g='M'                            THEN '23'
                WHEN ts_e.g IN ( '3', 'H', '4' )           THEN '3'
                WHEN ts_e.g IN ( 'X', 'B' )                THEN 'X'
                ELSE                                            NULL
             END, NULL )                                                   AS bef_g

      FROM
         patient                                                    p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id            AND
                        ts.erkrankung_id=$erkrankung_id      AND
                        ts.datum_beurteilung<='$doku_datum'  AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                 ts_e
         ON ts_e.sicherungsgrad='end'

      WHERE
         p.patient_id = {$patient_id}

      UNION

      SELECT
         IF( ts_v.datum_beurteilung IS NOT NULL,
             CASE
                WHEN ts_v.g IN ( '1', 'L' )                THEN '1'
                WHEN ts_v.g='2'                            THEN '2'
                WHEN ts_v.g='M'                            THEN '23'
                WHEN ts_v.g IN ( '3', 'H', '4' )           THEN '3'
                WHEN ts_v.g IN ( 'X', 'B' )                THEN 'X'
                ELSE                                            NULL
             END, NULL )                                                   AS bef_g

      FROM
         patient                                                    p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id            AND
                        ts.erkrankung_id=$erkrankung_id      AND
                        ts.datum_beurteilung<='$doku_datum'  AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                 ts_v
         ON ts_v.sicherungsgrad='vor'

      WHERE
         p.patient_id = $patient_id

      ORDER BY
         bef_g DESC

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

   // -> Resektionsstatus
   HoleBefundstatusB( $db, $patient_id, $erkrankung_id, $doku_datum, '0', &$data );
   HoleBefundstatusB( $db, $patient_id, $erkrankung_id, $doku_datum, '1', &$data );
   HoleBefundstatusB( $db, $patient_id, $erkrankung_id, $doku_datum, '2', &$data );
   HoleBefundstatusB( $db, $patient_id, $erkrankung_id, $doku_datum, 'X', &$data );

   // -> Rezeptorstatus (Östrogen und/oder Progesteron)
   $query = "
      SELECT
         IF( MAX( ts_e.datum_beurteilung ) IS NOT NULL,
             CASE
                WHEN ( ( ts_e.estro_urteil='p' ) OR ( ts_e.prog_urteil='p' ) )           THEN 'p'
                WHEN ( ( ts_e.estro_urteil='n' ) OR ( ts_e.prog_urteil='n' ) )           THEN 'n'
                ELSE                                                                          'u'
             END, NULL )                                                                              AS bef_rezeptorstatus

      FROM
         patient p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id                AND
                        ts.erkrankung_id=$erkrankung_id          AND
                        ts.datum_beurteilung<='$doku_datum'      AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                                      ts_e
         ON ts_e.sicherungsgrad='end'

      WHERE
         p.patient_id=$patient_id

      UNION

      SELECT
         IF( MAX( ts_v.datum_beurteilung ) IS NOT NULL,
             CASE
                WHEN ( ( ts_v.estro_urteil='p' ) OR ( ts_v.prog_urteil='p' ) )           THEN 'p'
                WHEN ( ( ts_v.estro_urteil='n' ) OR ( ts_v.prog_urteil='n' ) )           THEN 'n'
                ELSE                                                                          'u'
             END, NULL )                                                                              AS bef_rezeptorstatus

      FROM
         patient p
         LEFT JOIN ( SELECT
                        *
                     FROM
                        tumorstatus ts
                     WHERE
                        ts.patient_id=$patient_id                AND
                        ts.erkrankung_id=$erkrankung_id          AND
                        ts.datum_beurteilung<='$doku_datum'      AND
                        ts.anlass='p'
                     ORDER BY
                        ts.datum_beurteilung DESC )                                      ts_v
         ON ts_v.sicherungsgrad='vor'

      WHERE
         p.patient_id=$patient_id

      ORDER BY
         bef_rezeptorstatus DESC

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

   if ( $data[ 'anam_op_axilla' ] == '1' )
   {
      // -> Anzahl der entfernten Lymphknoten

       $query = "
           SELECT
               lk_bef,
               lk_entf
           FROM tumorstatus
           WHERE
               patient_id = '$patient_id' AND
               erkrankung_id = '$erkrankung_id' AND
               datum_sicherung <= '$doku_datum' AND
               anlass = 'p'
           ORDER BY sicherungsgrad ASC, datum_sicherung DESC
           LIMIT 1
       ";

       if (count($result = sql_query_array($db, $query)) > 0) {
           extract($result[0]);
           $data['bef_lk_entf_09'] = $lk_entf !== null && $lk_entf >= 0 && $lk_entf <= 9 ? 1 : null;
           $data['bef_lk_entf_10'] = $lk_entf !== null && $lk_entf >= 10 ? 1 : null;
           $data['bef_lk_bef_keine'] = $lk_bef !== null && $lk_bef == 0 ? 1 : null;
           $data['bef_lk_bef_13'] = $lk_bef !== null && $lk_bef >= 1 && $lk_bef <= 3 ? 1 : null;
           $data['bef_lk_bef_4'] = $lk_entf !== null && $lk_entf >= 4 ? 1 : null;
       }
   }

   if ( $data[ 'anam_op_sln' ] == '1' )
   {
       $query = "
           SELECT
               n
           FROM tumorstatus
           WHERE
               patient_id = '$patient_id' AND
               erkrankung_id = '$erkrankung_id' AND
               datum_sicherung <= '$doku_datum' AND
               anlass = 'p'
           ORDER BY sicherungsgrad ASC, datum_sicherung DESC
           LIMIT 1
       ";

       if (count($result = sql_query_array($db, $query)) > 0) {
            extract($result[0]);
            $data['bef_lk_bef_sln_neg'] = str_starts_with($n, 'pN0') && str_ends_with($n, '(sn)') ? 1 : null;
       }
   }

   $data[ 'bef_lk_bef_unbekannt' ] = '0';
   if ( ( !isset( $data[ 'bef_lk_entf_keine' ]  ) || ( $data[ 'bef_lk_entf_keine' ]  != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_entf_sln' ]    ) || ( $data[ 'bef_lk_entf_sln' ]    != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_entf_09' ]     ) || ( $data[ 'bef_lk_entf_09' ]     != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_entf_10' ]     ) || ( $data[ 'bef_lk_entf_10' ]     != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_bef_keine' ]   ) || ( $data[ 'bef_lk_bef_keine' ]   != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_bef_sln_neg' ] ) || ( $data[ 'bef_lk_bef_sln_neg' ] != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_bef_13' ]      ) || ( $data[ 'bef_lk_bef_13' ]      != '1' ) ) &&
        ( !isset( $data[ 'bef_lk_bef_4' ]       ) || ( $data[ 'bef_lk_bef_4' ]       != '1' ) ) )
   {
      $data[ 'bef_lk_bef_unbekannt' ] = '1';
   }

   // *****************************************************************************************************************
   // Behandlung Primärtumor/kontralateraler Brustkrebs

   // -> Strahlentherapie
   $query = "
       SELECT
          CASE
             WHEN MAX( st_a.beginn ) IS NOT NULL    THEN 'vb'
             WHEN MAX( st_b.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( st_c.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( st_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS beh_strahlen

       FROM
          patient p
          LEFT JOIN strahlentherapie st_a           ON st_a.patient_id=p.patient_id        AND
                                                       st_a.erkrankung_id=$erkrankung_id   AND
                                                       st_a.beginn<='$doku_datum'          AND
                                                       st_a.endstatus='abbr'
          LEFT JOIN strahlentherapie st_b           ON st_b.patient_id=p.patient_id        AND
                                                       st_b.erkrankung_id=$erkrankung_id   AND
                                                       st_b.beginn<='$doku_datum'          AND
                                                       st_b.ende IS NULL
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
             WHEN MAX( ts_b.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( ts_c.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( ts_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS beh_chemo

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
                                                       ts_b.ende IS NULL
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
             WHEN MAX( ts_b.beginn ) IS NOT NULL    THEN 'a'
             WHEN MAX( ts_c.beginn ) IS NOT NULL    THEN 'ra'
             WHEN MAX( ts_d.datum )  IS NOT NULL    THEN 'g'
             ELSE                                   'k'
          END                                                                                        AS beh_endo

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
                                                       ts_b.ende IS NULL
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
   // Befunde und Therapie lokoregionäres Rezidiv

   // -> Lokalisation
   $query = "
      SELECT
		 IF( MAX( ts_i.datum_beurteilung ) IS NOT NULL,     '1', NULL )    AS rez_lok_intra,
         IF( MAX( ts_t.datum_beurteilung ) IS NOT NULL AND
             MAX( eo_t.prozedur )          IS NOT NULL,     '1', NULL )    AS rez_lok_thorax,
         IF( MAX( ts_a.datum_beurteilung ) IS NOT NULL AND
             MAX( eo_a.prozedur )          IS NULL,         '1', NULL )    AS rez_lok_axilla

      FROM
         patient                                                           p
         LEFT JOIN ( SELECT
                        *

                     FROM
                        tumorstatus ts

                     WHERE
                        ts.patient_id=$patient_id                          AND
                        ts.erkrankung_id=$erkrankung_id                    AND
                        ts.datum_beurteilung<='$doku_datum'                AND
                        ts.anlass!='p'                                     AND
                        ts.anlass!='b'

                     ORDER BY
                        ts.datum_beurteilung DESC )                        ts_i        ON ts_i.rezidiv_lokal='1'

         LEFT JOIN ( SELECT
                        *

                     FROM
                        tumorstatus ts

                     WHERE
                        ts.patient_id=$patient_id                          AND
                        ts.erkrankung_id=$erkrankung_id                    AND
                        ts.datum_beurteilung<='$doku_datum'                AND
                        ts.anlass!='p'                                     AND
                        ts.anlass!='b'

                     ORDER BY
                        ts.datum_beurteilung DESC )                        ts_t   ON ts_t.rezidiv_lokal='1'

         LEFT JOIN eingriff e         ON e.patient_id=p.patient_id         AND
                                         e.erkrankung_id=$erkrankung_id    AND
                                         e.datum<='$doku_datum'

         LEFT JOIN eingriff_ops eo_t  ON eo_t.patient_id=p.patient_id      AND
                                         eo_t.erkrankung_id=$erkrankung_id AND
                                         eo_t.eingriff_id=e.eingriff_id    AND
                                         LEFT( eo_t.prozedur, 5 ) BETWEEN '5-872' AND '5-876'

         LEFT JOIN ( SELECT
                        *

                     FROM
                        tumorstatus ts

                     WHERE
                        ts.patient_id=$patient_id                          AND
                        ts.erkrankung_id=$erkrankung_id                    AND
                        ts.datum_beurteilung<='$doku_datum'                AND
                        ts.anlass!='p'                                     AND
                        ts.anlass!='b'

                     ORDER BY
                        ts.datum_beurteilung DESC )                        ts_a   ON ts_a.rezidiv_lk='1'

         LEFT JOIN eingriff_ops eo_a  ON eo_a.patient_id=p.patient_id      AND
                                         eo_a.erkrankung_id=$erkrankung_id AND
                                         eo_a.eingriff_id=e.eingriff_id    AND
                                         LEFT( eo_a.prozedur, 5 ) BETWEEN '5-872' AND '5-876'

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

   // *****************************************************************************************************************
   // Befunde und Therapie Fernmetastasen
   $codes = dlookup($db, 'status', 'SUBSTRING(report_param, 12)', "form = 'tumorstatus' AND patient_id = '$patient_id' AND erkrankung_id = '$erkrankung_id' AND form_date <= '$doku_datum' ORDER BY form_date DESC LIMIT 1");

   // Fix für Ticket #6723
   $data['metast_lok_andere'] = 0;
   foreach (strlen($codes) > 0 ? explode(' ', $codes) : array() as $code) {
       switch (true) {
           case str_starts_with($code, 'C22.0'): $data['metast_lok_leber'] = 1; break;
           case str_starts_with($code, 'C34'): $data['metast_lok_lunge'] = 1; break;
           case str_starts_with($code, array('C40', 'C41')): $data['metast_lok_knochen'] = 1; break;
           default : {
               // Fix für Ticket #6723
               if ( strlen( $code ) > 0 ) {
                   $data['metast_lok_andere'] = 1;
               }
               break;
           }
       }
   }

   // -> Bisphosphonat-Therapie bei Knochenmetastasen
   if ( isset($data[ 'metast_lok_knochen' ]) && $data[ 'metast_lok_knochen' ] == '1' )
   {
      $query = "
         SELECT
            IF( MAX( vtw.wirkstoff ) IS NOT NULL, '1', NULL )                                               AS metast_bip_ja,
            IF( MAX( vtw.wirkstoff ) IS NULL,     '1', NULL )                                               AS metast_bip_nein

         FROM
            therapie_systemisch                  ts
            LEFT JOIN vorlage_therapie           vt    ON ts.vorlage_therapie_id=vt.vorlage_therapie_id
            LEFT JOIN vorlage_therapie_wirkstoff vtw   ON vt.vorlage_therapie_id=vtw.vorlage_therapie_id AND
                                                                  vtw.wirkstoff='biphosphonate'

         WHERE
            ts.erkrankung_id=$erkrankung_id            AND
            ts.beginn<='$doku_datum'                   AND
            ts.patient_id=$patient_id
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

   $query = "
      SELECT
		 IF( MAX( d.diagnose ) IS NOT NULL, '1', NULL )                         AS lymphoedem,
         IF( sm.analgetika     IS NOT NULL, '1', NULL )                         AS sonst_schmerz_ja,
         IF( sm.analgetika     IS NULL,     '1', NULL )                         AS sonst_schmerz_nein

      FROM
         patient                                                                p
         LEFT JOIN diagnose d                ON d.patient_id=$patient_id        AND
                                                d.erkrankung_id=$erkrankung_id  AND
                                                d.datum<='$doku_datum'          AND
                                                d.diagnose IN ( 'I89.0', 'I97.2', 'Q82.0' )
         LEFT JOIN ( SELECT
                        n.analgetika

                     FROM
                        nachsorge_erkrankung ne
                        LEFT JOIN nachsorge n ON n.nachsorge_id=ne.nachsorge_id

                     WHERE
                        ne.patient_id=$patient_id                               AND
                        ne.erkrankung_weitere_id=$erkrankung_id

                     ORDER BY
                        n.datum DESC ) sm     ON sm.analgetika='1'

      WHERE
		 p.patient_id=$patient_id

      GROUP BY
         p.patient_id
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

function HoleBefundstatusA( $db, $patient_id, $erkrankung_id, $doku_datum, $column, $code, $prefix, &$data )
{
	$length = strlen( $code );
    $query = "
       SELECT
          IF( ts_e.$column           IS NOT NULL,      '1', NULL )                AS bef_p".$column."_".$prefix."

       FROM
          patient                                                    p
          LEFT JOIN ( SELECT
                         *
                      FROM
                         tumorstatus ts
                      WHERE
                         ts.patient_id=$patient_id             AND
                         ts.erkrankung_id=$erkrankung_id       AND
                         ts.datum_beurteilung<='$doku_datum'   AND
                         ts.anlass='p'
                      ORDER BY
                         ts.datum_beurteilung DESC )                 ts_e
         ON ts_e.sicherungsgrad='end'                          AND
            LEFT( ts_e.$column, $length )='$code'

       WHERE
          p.patient_id=$patient_id

       UNION

       SELECT
          IF( ts_v.$column           IS NOT NULL,       '1', NULL )               AS bef_p".$column."_".$prefix."

       FROM
          patient                                                    p
          LEFT JOIN ( SELECT
                         *
                      FROM
                         tumorstatus ts
                      WHERE
                         ts.patient_id=$patient_id             AND
                         ts.erkrankung_id=$erkrankung_id       AND
                         ts.datum_beurteilung<='$doku_datum'   AND
                         ts.anlass='p'
                      ORDER BY
                         ts.datum_beurteilung DESC )                 ts_v
          ON ts_v.sicherungsgrad='vor'                         AND
             LEFT( ts_v.$column, $length )='$code'

       WHERE
          p.patient_id=$patient_id

       ORDER BY
          bef_p".$column."_".$prefix." DESC

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

function HoleBefundstatusB( $db, $patient_id, $erkrankung_id, $doku_datum, $code, &$data )
{
	if ( $code == 'X' )
	{
		$bez = "bef_r_unbekannt";
	}
	else
	{
		$bez = "bef_r_" . $code;
	}
    $query = "
       SELECT
          IF( ts_e.r IS NOT NULL, '1', NULL )                                             AS $bez

       FROM
          patient                                                    p
          LEFT JOIN ( SELECT
                         *
                      FROM
                         tumorstatus ts
                      WHERE
                         ts.patient_id=$patient_id             AND
                         ts.erkrankung_id=$erkrankung_id       AND
                         ts.datum_beurteilung<='$doku_datum'   AND
                         ts.anlass='p'
                      ORDER BY
                         ts.datum_beurteilung DESC )                 ts_e
         ON ts_e.sicherungsgrad='end'                          AND
            ts_e.r='$code'

       WHERE
          p.patient_id=$patient_id

       UNION

       SELECT
          IF( ts_v.r IS NOT NULL, '1', NULL )                                            AS $bez

       FROM
          patient                                                    p
          LEFT JOIN ( SELECT
                         *
                      FROM
                         tumorstatus ts
                      WHERE
                         ts.patient_id=$patient_id             AND
                         ts.erkrankung_id=$erkrankung_id       AND
                         ts.datum_beurteilung<='$doku_datum'   AND
                         ts.anlass='p'
                      ORDER BY
                         ts.datum_beurteilung DESC )                 ts_v
          ON ts_v.sicherungsgrad='vor'                         AND
             ts_v.r='$code'

       WHERE
          p.patient_id=$patient_id

       ORDER BY
          $bez DESC

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

?>
