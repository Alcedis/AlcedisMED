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

//Where Bedingung wird im Skript noch durch die aktuelle Erkrankung erweitert
$query_schema = "
   SELECT
      vorlage_therapie_id,
      bez
   FROM vorlage_therapie
   WHERE freigabe IS NOT NULL AND inaktiv IS NULL AND art IN ('ahst', 'st', 'sonstr', 'cst', 'ist')
";

$query_strahlentherapie = "
   SELECT
      code,
      bez
   FROM l_basic
   WHERE klasse = 'wirkstoff' AND kennung = 'str'
";

$fields = array(
   'strahlentherapie_id'        => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'reference' => 'nebenwirkung'),
   'erkrankung_id'              => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'patient_id'                 => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'vorlage_therapie_id'        => array('req' => 1, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_schema, 'preselect' => 'vorlage_therapie_id'),
   'hyperthermie'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'vorlage_therapie_art'       => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'org_id'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_org'], 'preselect' => 'org_id'),
   'user_id'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id'),
   'therapieplan_id'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_therapieplan'], 'parent_status' => 'therapieplan'),
   'intention'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention')),
   'therapieform'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieform')),
   'studie'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'studie_id'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_studie']),
   'beginn'                     => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
   'ende'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
   'andauernd'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'zahnarzt'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'ziel_ganzkoerper'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_primaertumor'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_mamma_r'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_mamma_l'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_brustwand_r'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_brustwand_l'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_mammaria_interna'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_mediastinum'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_prostata'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_becken'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_abdomen'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_vulva'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_vulva_pelvin'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_vulva_inguinal'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_inguinal_einseitig'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_ingu_beidseitig'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_ingu_pelvin'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_vagina'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_lymph'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_paraaortal'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_axilla_r'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_axilla_l'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_lk'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_lk_supra'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_lk_para'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_lk_iliakal'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_zervikal_r'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_zervikal_l'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_hilaer'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_axillaer_r'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_axillaer_l'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_abdominell_o'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_abdominell_u'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_iliakal_r'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_iliakal_l'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_inguinal_r'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'ziel_lk_inguinal_l'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_knochen'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_gehirn'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_sonst'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'ziel_sonst_detail'          => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 't')),
   'ziel_sonst_detail_seite'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'ziel_sonst_detail_text'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
   'ziel_sonst_detail_version'  => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
   'art'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $query_strahlentherapie),
   'fraktionierungstyp'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'fraktionierungstyp')),
   'einzeldosis'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
   'gesamtdosis'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
   'boost'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'boostdosis'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
   'dosierung_icru'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'imrt'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'igrt'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'beschleunigerenergie'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
   'seed_strahlung_90d'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
   'seed_strahlung_90d_datum'   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => ''),
   'endstatus'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieende')),
   'endstatus_grund'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieplan_abweichung_grund')),
   'best_response'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'response')),
   'best_response_datum'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => ''),
   'dosisreduktion'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'dosisreduktion_grund'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'dosisreduktion_grund')),
   'dosisreduktion_grund_sonst' => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
   'unterbrechung'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
   'unterbrechung_grund'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'unterbrechung_grund')),
   'unterbrechung_grund_sonst'  => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
   'bem'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
   'createuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
