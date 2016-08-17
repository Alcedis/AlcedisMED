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
    'tumorstatus_id'                  => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'erkrankung_id'                   => array('req' => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'patient_id'                      => array('req' => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'datum_beurteilung'               => array('req' => 1, 'size' => '',  'maxlen' => '',    'type' => 'date',     'ext' => ''),
    'anlass'                          => array('req' => 1, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'tumorstatus_anlass')),
    'sicherungsgrad'                  => array('req' => 1, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'tumorstatus_sicherungsgrad')),
    'datum_sicherung'                 => array('req' => 1, 'size' => '',  'maxlen' => '',    'type' => 'date',     'ext' => ''),
    'diagnosesicherung'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'diagnosesicherung')),
    'tumorausbreitung_lokal'          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'tumorausbreitung_lk'             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'tumorausbreitung_konausdehnung'  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'tumorausbreitung_fernmetastasen' => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'rezidiv_lokal'                   => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'rezidiv_lk'                      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'rezidiv_metastasen'              => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'quelle_metastasen'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'quelle_metastase')),
    'rezidiv_psa'                     => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'mhrpc'                           => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'zweittumor'                      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'fall_vollstaendig'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'nur_zweitmeinung'                => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'kein_fall'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'nur_diagnosesicherung'           => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'zufall'                          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'diagnose'                        => array('req' => 1, 'size' => '',  'maxlen' => '28',  'type' => 'code_icd', 'ext' => ''),
    'diagnose_seite'                  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'seite_rl'), 'default' => '-', 'null' => '-'),
    'diagnose_text'                   => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'diagnose_version'                => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'diagnose_c19_zuordnung'          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'diagnose_c19_zuordnung')),
    'lokalisation'                    => array('req' => 0, 'size' => '',  'maxlen' => '30',  'type' => 'code_o3',  'ext' => array('type' => 't')),
    'lokalisation_seite'              => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
    'lokalisation_text'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'lokalisation_version'            => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'lokalisation_detail'             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'lokalisation_detail')),
    'hoehe'                           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'morphologie'                     => array('req' => 0, 'size' => '',  'maxlen' => '30',  'type' => 'code_o3',  'ext' => array('type' => 'm')),
    'morphologie_text'                => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'morphologie_version'             => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'groesse_x'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'groesse_y'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'groesse_z'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'multizentrisch'                  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'multifokal'                      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'mikrokalk'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'dcis_morphologie'                => array('req' => 0, 'size' => '',  'maxlen' => '30',  'type' => 'code_o3',  'ext' => array('type' => 'm')),
    'dcis_morphologie_text'           => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'dcis_morphologie_version'        => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'stadium_mason'                   => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'stadium_mason')),
    'gleason1'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'gleason2'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'gleason3'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'gleason4_anteil'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'eignung_nerverhalt'              => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'eignung_nerverhalt_seite'        => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'nerverhalt_seite')),
    'lk_entf'                         => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'lk_bef'                          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'lk_staging'                      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'lk_sentinel_entf'                => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'lk_sentinel_bef'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',      'ext' => ''),
    'regressionsgrad'                 => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'regressionsgrad')),
    'tnm_praefix'                     => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'tnm_praefix')),
    't'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query',    'ext' => $query_t),
    'n'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query',    'ext' => $query_n),
    'm'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query',    'ext' => $query_m),
    'g'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'g')),
    'l'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'l')),
    'v'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'v')),
    'r'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'r')),
    'r_lokal'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'r')),
    'ppn'                             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'ppn')),
    's'                               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 's')),
    'infiltration'                    => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'befallen_n'                      => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'befallen_m'                      => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'resektionsrand'                  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'invasionstiefe'                  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'uicc'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'uicc')),
    'lugano'                          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'lugano')),
    'ajcc'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'uicc')),
    'figo'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'uicc')),
    'nhl_who_b'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'nhl_who_b')),
    'nhl_who_t'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'nhl_who_t')),
    'hl_who'                          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'hl_who')),
    'ann_arbor_stadium'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'ann_arbor_stadium')),
    'ann_arbor_aktivitaetsgrad'       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'ann_arbor_aktivitaetsgrad')),
    'ann_arbor_extralymphatisch'      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'ann_arbor_extralymphatisch')),
    'nhl_ipi'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'nhl_ipi')),
    'flipi'                           => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'flipi')),
    'durie_salmon'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'durie_salmon')),
    'iss'                             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'iss')),
    'immun_phaenotyp'                 => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'immun_phaenotyp')),
    'cll_rai'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'cll_rai')),
    'cll_binet'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'cll_binet')),
    'aml_fab'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'aml_fab')),
    'aml_who'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'aml_who')),
    'all_egil'                        => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'all_egil')),
    'mds_fab'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'mds_fab')),
    'mds_who'                         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'mds_who')),
    'stadium_sclc'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'stadium_sclc')),
    'risiko'                          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'risiko')),
    'risiko_mediastinaltumor'         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'risiko_extranodalbefall'         => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'risiko_bks'                      => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'risiko_lk'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'check',    'ext' => ''),
    'estro'                           => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'estro_irs'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'irs')),
    'estro_urteil'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'posneg')),
    'prog'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'prog_irs'                        => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'irs')),
    'prog_urteil'                     => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'posneg')),
    'her2'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'her2')),
    'her2_methode'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'her2_methode')),
    'her2_fish'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'fish')),
    'her2_fish_methode'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'fish_methode')),
    'her2_urteil'                     => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'posnegu')),
    'psa'                             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'float',    'ext' => ''),
    'datum_psa'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'date',     'ext' => ''),
    'bem'                             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'createuser'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'createtime'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updateuser'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updatetime'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',   'ext' => '')
);

?>
