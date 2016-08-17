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

$query_lab = "
   SELECT
      l.labor_id,
      DATE_FORMAT(l.datum, '%d.%m.%Y'),
      vl.bez
   FROM labor l
       INNER JOIN labor_wert lw ON l.labor_id = lw.labor_id AND lw.parameter IN('psa', 'freiespsa') AND lw.wert IS NOT NULL
       INNER JOIN vorlage_labor vl ON vl.vorlage_labor_id = l.vorlage_labor_id
   WHERE l.erkrankung_id = '{$erkrankung_id}'
   GROUP BY l.labor_id
   ORDER BY l.datum DESC
";

$fields = array(
    'nachsorge_id'                      => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'patient_id'                        => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'datum'                             => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => ''),
    'org_id'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $querys['query_org'], 'preselect' => 'org_id'),
    'user_id'                           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id'),
    'ecog'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ecog')),
    'gewicht'                           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'malignom'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'nachsorge_biopsie'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'empfehlung_befolgt'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jnnb')),
    'tumormarkerverlauf'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tumormarkerverlauf')),
    'psa_bestimmt'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'labor_id'                          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query'      , 'ext' => $query_lab, 'preselect' => 'l.labor_id'),
    'response_klinisch'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'response')),
    'response_klinisch_bestaetigt'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'euroqol'                           => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => ''),
    'lcss'                              => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => ''),
    'iciq_ui'                           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'iciq_ui')),
    'ics'                               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ics')),
    'fb_dkg'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'iief5'                             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'iief5')),
    'ipss'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ipss')),
    'lq_dkg'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'lq_dkg')),
    'gz_dkg'                            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'gz_dkg')),
    'ql'                                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ql')),
    'armbeweglichkeit'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'armbeweglichkeit')),
    'umfang_oberarm'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'umfang_unterarm'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'hads_d_depression'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'hads_d_score')),
    'hads_d_angst'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'hads_d_score')),
    'pde5hemmer'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'pde5hemmer_haeufigkeit'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'pde5hemmer_haeufigkeitq')),
    'vakuumpumpe'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'skat'                              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'penisprothese'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'sy_schmerzen'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_schmerzen_lokalisation'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'code_o3'    , 'ext' => array('showSide' => true, 'type' => 't')),
    'sy_schmerzen_lokalisation_seite'   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic'=>'seite'), 'default' => '-', 'null' => '-'),
    'sy_schmerzen_lokalisation_text'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
    'sy_schmerzen_lokalisation_version' => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
    'sy_schmerzscore'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'schmerzscore')),
    'sy_dyspnoe'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_haemoptnoe'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_husten'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_husten_dauer'                   => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => ''),
    'sy_harndrang'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_nykturie'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_pollakisurie'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_miktion'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_restharn'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'sy_harnverhalt'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_harnstau'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_harnstau_lokalisation'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'seite')),
    'sy_haematurie'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_para_syndrom'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_para_syndrom_symptom'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'para_syndrom_symptom')),
    'sy_para_syndrom_detail'            => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
    'sy_gewichtsverlust'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptom_schwere')),
    'sy_gewichtsverlust_2wo'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'sy_gewichtsverlust_3mo'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => ''),
    'sy_fieber'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'sy_nachtschweiss'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'sy_sonstige'                       => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
    'sy_dauer'                          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => ''),
    'sy_dauer_einheit'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'symptomatik_dauer_einheit')),
    'analgetika'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'schmerzmedikation_stufe'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'schmerzmedikation_stufe')),
    'response_schmerztherapie'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'response')),
    'scapula_alata'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'lymphoedem'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'lymphoedem_seite'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'seite')),
    'lymphdrainage'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'sensibilitaet'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'kontinenz'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'kontinenz')),
    'vorlagenverbrauch'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'vorlagenverbrauch')),
    'spaetschaden_blase'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'spaetschaden_blase_grad'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'spaetschaden_grad')),
    'spaetschaden_rektum'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'spaetschaden_rektum_grad'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'spaetschaden_grad')),
    'bem'                               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
    'createuser'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'                        => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);
?>
