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
     CONCAT_WS(' ', titel.bez, user.vorname, user.nachname)
  FROM user
     LEFT JOIN l_basic titel            ON titel.klasse = 'titel' AND titel.code = user.titel
  WHERE
     user.fachabteilung = 'Z4400'
";

$query_patho = query_investigator($db, $query_pathologe, 'user_id', 'zytologie');

$fields = array(
   'zytologie_id'                 => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'patient_id'                  => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'erkrankung_id'               => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'datum'                        => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'histologie_nr'               => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'org_id'                      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_org'], 'preselect' => 'org_id' ),
	'user_id'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'picker'     , 'ext' => array('query' => $query_patho, 'type' => 'arzt' ) ),
	'eingriff_id'                 => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_eingriff'], 'parent_status' => 'eingriff' ),
	'untersuchungsmaterial'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'material_zytologie') ),
    'nhl_who_b'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nhl_who_b') ),
    'nhl_who_t'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nhl_who_t') ),
    'hl_who'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'hl_who') ),
    'ann_arbor_stadium'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ann_arbor_stadium') ),
    'ann_arbor_aktivitaetsgrad'   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ann_arbor_aktivitaetsgrad') ),
    'ann_arbor_extralymphatisch'  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ann_arbor_extralymphatisch') ),
    'nhl_ipi'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nhl_ipi') ),
    'flipi'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'flipi') ),
    'durie_salmon'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'durie_salmon') ),
    'cll_rai'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'cll_rai') ),
    'cll_binet'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'cll_binet') ),
    'aml_fab'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'aml_fab') ),
    'aml_who'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'aml_who') ),
    'all_egil'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'all_egil') ),
    'mds_fab'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mds_fab') ),
    'mds_who'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mds_who') ),
	'zytologie_normal'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'zelldichte'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'zelldichte') ),
	'erythropoese'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 've') ),
	'granulopoese'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 've') ),
	'megakaryopoese'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 've') ),
	'km_infiltration'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'km_infiltration_anteil'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
	'zyto_sonstiges_text'         => array('req' => 0, 'size' => '30' , 'maxlen' => '150', 'type' => 'string'     , 'ext' => '' ),
	'zyto_sonstiges'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 've') ),
	'zellveraenderung'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'erythrozyten'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'erythrozyten_text'           => array('req' => 0, 'size' => '30' , 'maxlen' => '150', 'type' => 'string'     , 'ext' => '' ),
	'granulozyten'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'granulozyten_text'           => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'megakaryozyten'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'megakaryozyten_text'         => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'lymphozyten_text'            => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'plasmazellen_text'           => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'zellen_sonstiges'            => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'zellen_sonstiges_text'       => array('req' => 0, 'size' => '30' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'myeloperoxidase_urteil'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'liquordiag_1_methode'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'liquordiag_methode') ),
   'liquordiag_1_zellzahl'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'liquordiag_1_beurteilung'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'untersuchung_beurteilung') ),
   'liquordiag_2_methode'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'liquordiag_methode') ),
   'liquordiag_2_zellzahl'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'liquordiag_2_beurteilung'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'untersuchung_beurteilung') ),
   'liquordiag_3_methode'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'liquordiag_methode') ),
   'liquordiag_3_zellzahl'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
   'liquordiag_3_beurteilung'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'untersuchung_beurteilung') ),
	'myeloperoxidase_anteil'      => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'monozytenesterase_urteil'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'monozytenesterase_anteil'    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'pas_reaktion_urteil'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'pas_reaktion_anteil'         => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'immunzytologie_pathologisch' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'immunzytologie_diagnose'     => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'zytogenetik_normal'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'mrd1_methode'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mrd_methode') ),
	'mrd1_ergebnis'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'mrd2_methode'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'mrd_methode') ),
	'mrd2_ergebnis'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'posneg') ),
	'bem'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
   'createuser'                   => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                   => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                   => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                   => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>