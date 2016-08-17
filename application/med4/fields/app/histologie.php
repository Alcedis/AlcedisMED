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

$query_pathologe = "
  SELECT DISTINCT
     user.user_id,
     CONCAT_WS(', ', user.nachname, user.vorname)
  FROM user
     LEFT JOIN l_basic titel            ON titel.klasse = 'titel' AND titel.code = user.titel
  WHERE
     user.fachabteilung = 'Z4400' AND user.inaktiv IS NULL
  ORDER BY
   nachname ASC, vorname
";

$query_patho = query_investigator($db, $query_pathologe, 'user_id', 'histologie');

$query_t = "
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'ct')
      UNION
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'pt')
";

$query_n = "
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'cn')
      UNION
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'pn')
";

$query_m = "
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'cm')
      UNION
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'pm')
";


$fields = array(
    'histologie_id'                    => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'patient_id'                       => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'erkrankung_id'                    => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'datum'                            => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'art'                              => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'histologie_art') ),
	'org_id'                           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_org'], 'preselect' => 'org_id' ),
	'user_id'                          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'picker'     , 'ext' => array('query' => $query_patho, 'type' => 'arzt' ) ),
	'histologie_nr'                    => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'diagnose_seite'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
	'eingriff_id'                      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_eingriff'], 'parent_status' => 'eingriff' ),
    'untersuchung_id'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_untersuchung'], 'parent_status' => 'untersuchung' ),
	'referenzpathologie'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'referenzpathologie') ),
    'anzahl_praeparate'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'int'        , 'ext' => '' ),
	'groesse_x'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'groesse_y'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'groesse_z'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'prostatagewicht'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'multizentrisch'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'multifokal'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'morphologie'                      => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
	'morphologie_text'                 => array('req' => 0, 'size' => '' , 'maxlen' => '', 'type' => 'textarea'      , 'ext' => '' ),
	'morphologie_version'              => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'morphologie_erg1'                 => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
	'morphologie_erg1_text'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'morphologie_erg1_version'         => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'morphologie_erg2'                 => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
	'morphologie_erg2_text'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'morphologie_erg2_version'         => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'morphologie_erg3'                 => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
	'morphologie_erg3_text'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'morphologie_erg3_version'         => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'unauffaellig'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'ptnm_praefix'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tnm_praefix') ),
    'pt'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query'      , 'ext' => $query_t),
    'pn'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query'      , 'ext' => $query_n),
    'pm'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query'      , 'ext' => $query_m),
	'g'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'g') ),
	'l'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'l') ),
	'v'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'v') ),
	'r'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'r') ),
	'ppn'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ppn') ),
    'konisation_exzision'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'exzision') ),
    'konisation_x'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'konisation_y'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'konisation_z'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'invasionstiefe'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'invasionsbreite'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'resektionsrand'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'anz_rand_positiv'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'int'        , 'ext' => '' ),
    'status_resektionsrand_organ'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'status_resektionsrand') ),
    'status_resektionsrand_circumferentiell' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'status_resektionsrand') ),
    'resektionsrand_circumferentiell'  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'resektionsrand_oral'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'resektionsrand_aboral'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'resektionsrand_lateral'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'tumoranteil_turp'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'int'        , 'ext' => '' ),
	'mercury'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mercury') ),
	'msi'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'msi_mutation'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'msi_mutation') ),
	'msi_stabilitaet'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'msi_stabilitaet') ),
	'kapselueberschreitung'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'tubulusbildung'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tubulusbildung') ),
	'kernpolymorphie'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'kernpolymorphie') ),
	'mitoserate'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mitoserate') ),
	'ki67'                             => array('req' => 0, 'size' => '5' , 'maxlen' => '5'   , 'type' => 'string'     , 'ext' => '' ),
	'ki67_index'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ki67_index') ),
	'gleason1'                         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'gleason2'                         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'gleason3'                         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'gleason4_anteil'                  => array('req' => 0, 'size' => '' , 'maxlen' => '6' , 'type' => 'int'        , 'ext' => '' ),
	'parametrienbefall_r'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'parametrienbefall_r_infiltration' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'parametrienbefall_infiltration') ),
	'parametrienbefall_l'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'parametrienbefall_l_infiltration' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'parametrienbefall_infiltration') ),
    'blasteninfiltration'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'blasteninfiltration_prozent'      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_sentinel_entf'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_sentinel_bef'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_12_entf'                       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_12_bef_makro'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_12_bef_mikro'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_3_entf'                        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_3_bef_makro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_3_bef_mikro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ip_entf'                       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ip_bef_makro'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ip_bef_mikro'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_bef_makro'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_bef_mikro'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_hilus_entf'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_hilus_bef_mikro'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_hilus_bef_makro'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_interlobaer_entf'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_interlobaer_bef_mikro'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_interlobaer_bef_makro'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lobaer_entf'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lobaer_bef_mikro'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lobaer_bef_makro'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_segmental_entf'                => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_segmental_bef_mikro'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_segmental_bef_makro'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lig_pul_entf'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lig_pul_bef_mikro'             => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_lig_pul_bef_makro'             => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraoeso_entf'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraoeso_bef_mikro'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraoeso_bef_makro'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subcarinal_entf'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subcarinal_bef_mikro'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subcarinal_bef_makro'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraaortal_entf'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraaortal_bef_mikro'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_paraaortal_bef_makro'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subaortal_entf'                => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subaortal_bef_mikro'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_subaortal_bef_makro'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_unt_paratrach_entf'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_unt_paratrach_bef_mikro'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_unt_paratrach_bef_makro'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_prae_retro_trach_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_prae_retro_trach_bef_mikro'    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_prae_retro_trach_bef_makro'    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ob_paratrach_entf'             => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ob_paratrach_bef_mikro'        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_ob_paratrach_bef_makro'        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_mediastinum_entf'              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_mediastinum_bef_mikro'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_mediastinum_bef_makro'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_l_entf'                        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_l_bef_mikro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_l_bef_makro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_r_entf'                        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_r_bef_mikro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_r_bef_makro'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_entf'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_bef'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_entf'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_bef'                      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_inguinal_entf'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_inguinal_bef'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_inguinal_makro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'lk_inguinal_mikro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'lk_iliakal_entf'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_iliakal_bef'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_iliakal_makro'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'lk_iliakal_mikro'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'lk_axillaer_entf'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_axillaer_bef'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_axillaer_makro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'lk_axillaer_mikro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'lk_zervikal_entf'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_zervikal_bef'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'lk_zervikal_makro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'lk_zervikal_mikro'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'lk_inguinal_l_entf'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_inguinal_l_bef'                => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_inguinal_r_entf'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_inguinal_r_bef'                => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_andere1'                       => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'lk_andere1_entf'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_andere1_bef'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_andere2'                       => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'lk_andere2_entf'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_andere2_bef'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_externa_l_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_externa_l_bef'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_interna_l_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_interna_l_bef'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_fossa_l_entf'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_fossa_l_bef'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_communis_l_entf'        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_communis_l_bef'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_externa_r_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_externa_r_bef'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_interna_r_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_interna_r_bef'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_fossa_r_entf'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_fossa_r_bef'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_communis_r_entf'        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_pelvin_communis_r_bef'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_paracaval_entf'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_paracaval_bef'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_interaortocaval_entf'     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_interaortocaval_bef'      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_cranial_ami_entf'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_cranial_ami_bef'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_caudal_ami_entf'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_caudal_ami_bef'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_cranial_vr_entf'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_para_cranial_vr_bef'           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'hpv'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'lk_entf'                          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_bef'                           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'lk_mikrometastasen'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'groesste_ausdehnung'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'kapseldurchbruch'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'estro'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'estro_irs'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'irs') ),
	'estro_urteil'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'prog'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'prog_irs'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'irs') ),
	'prog_urteil'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'her2'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'her2') ),
	'her2_methode'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'her2_methode') ),
	'her2_fish'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'fish') ),
	'her2_fish_methode'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'fish_methode') ),
	'her2_urteil'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'pai1'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'upa'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'egf'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'vegf'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'chromogranin'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'kras'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'kras') ),
    'braf'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'braf') ),
    'egfr'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
    'egfr_mutation'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'egfr') ),
	'nse'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'ercc1'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'ttf1'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
    'alk'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
    'ros'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'psa'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'pcna'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'epca2'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'p53'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'ps2'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'kathepsin_d'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posnegu') ),
	'hmb45'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'melan_a'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	's100'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'dcis_grading'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'dcis_grading') ),
	'dcis_groesse'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'dcis_resektionsrand'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'dcis_van_nuys'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'van_nuys') ),
	'dcis_vnpi'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'vnpi') ),
	'dcis_morphologie'                 => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
	'dcis_morphologie_text'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
	'dcis_morphologie_version'         => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'dcis_kerngrading'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'dcis_kerngrading') ),
	'dcis_nekrosen'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'dcis_nekrosen') ),
	'bem'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'createuser'                       => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'                       => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'                       => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'                       => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

$stanzenFields = array(
   'l_beurteilung'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
   'l_anz'                            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'l_anz_positiv'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'l_laenge'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
   'l_tumoranteil'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
   'r_beurteilung'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
   'r_anz'                            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'r_anz_positiv'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'r_laenge'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
   'r_tumoranteil'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
   'stanzen_ges_anz'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'stanzen_ges_anz_positiv'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' )
   )
;

$sections = array('sbr', 'sbl', 'blr', 'bll', 'br', 'bl', 'tr', 'tl', 'mlr', 'mll', 'mr', 'ml', 'ar', 'al', 'alr', 'all');

$sectionTemplates = array(
    'beurteilung' => array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'lookup', 'ext' => array('l_basic' => 'posneg')),
    'anz_positiv' => array('req' => 0, 'size' => ''   , 'maxlen' => ''   , 'type' => 'int'     , 'ext' => '', 'range' => array('min' => '1', 'max' => '5')),
    'content' => array(
        'laenge'           => array('req' => 0, 'size' => '2', 'maxlen' => '3', 'type' => 'float', 'ext' => '' ),
        'tumoranteil'      => array('req' => 0, 'size' => '1', 'maxlen' => '3', 'type' => 'int', 'ext' => '' ),
        'gleason1'         => array('req' => 0, 'size' => '1', 'maxlen' => '1', 'type' => 'int', 'ext' => '', 'range' => array('min' => '1', 'max' => '5') ),
        'gleason2'         => array('req' => 0, 'size' => '1', 'maxlen' => '1', 'type' => 'int', 'ext' => '', 'range' => array('min' => '1', 'max' => '5') ),
        'gleason1_anteil'  => array('req' => 0, 'size' => '1', 'maxlen' => '3', 'type' => 'int', 'ext' => '' ),
        'gleason2_anteil'  => array('req' => 0, 'size' => '1', 'maxlen' => '3', 'type' => 'int', 'ext' => '' ),
        'gleason4'         => array('req' => 0, 'size' => '1', 'maxlen' => '6', 'type' => 'int', 'ext' => ''),
        'diff'             => array('req' => 0, 'size' => '1', 'maxlen' => '3', 'type' => 'int', 'ext' => '' ),
    )
);

foreach ($sections as $sectionName) {
    $stanzenFields["{$sectionName}_beurteilung"] = $sectionTemplates['beurteilung'];
    $stanzenFields["{$sectionName}_anz_positiv"] = $sectionTemplates['anz_positiv'];

    for($i = 1; $i <= 5; $i++) {
        foreach ($sectionTemplates['content'] as $contentName => $fieldDefinition) {
            $stanzenFields["{$sectionName}_{$i}_{$contentName}"] = $fieldDefinition;
        }
    }
}

$fields = array_merge($fields, $stanzenFields);

$hpvFields = array();

for ($ix = 1; $ix <= 9; $ix++) {
    $hpvFields["hpv_typ0{$ix}"]      = array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'lookup', 'ext' => array('l_basic' => 'hpv_typ'));
    $hpvFields["hpv_ergebnis0{$ix}"] = array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'lookup', 'ext' => array('l_basic' => 'posneg'));
}

$fields = array_merge($fields, $hpvFields);

?>
