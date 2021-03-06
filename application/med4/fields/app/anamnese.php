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

$fields = array(
   'anamnese_id'                         => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_id'                          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'                       => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'datum'                               => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'date',      'ext' => ''),
   'datum_nb'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'groesse'                             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'gewicht'                             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float' ,    'ext' => ''),
   'mehrlingseigenschaften'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'mehrlingseigenschaften')),
   'entdeckung'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'entdeckung')),
   'vorsorge_regelmaessig'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'vorsorge_intervall'                  => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'vorsorge_intervall')),
   'vorsorge_datum_letzte'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'date',      'ext' => ''),
   'screening'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_autoimmun'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',     'ext' => array('l_basic' => 'jn')),
   'risiko_autoimmun_sjoergren'          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'risiko_autoimmun_arthritis'          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'risiko_autoimmun_lupus_ery'          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'risiko_autoimmun_zoeliakie'          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'risiko_autoimmun_dermatitis'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',      'ext' => ''),
   'risiko_autoimmun_sonstige'           => array('req' => 0, 'size' => '',   'maxlen' => '255',   'type' => 'string',      'ext' => ''),
   'risiko_raucher'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'raucher')),
   'risiko_raucher_dauer'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',       'ext' => ''),
   'risiko_raucher_menge'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'int',         'ext' => ''),
   'risiko_exraucher'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',      'ext' => array('l_basic' => 'exraucher')),
   'risiko_exraucher_dauer'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',       'ext' => ''),
   'risiko_exraucher_menge'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'int',         'ext' => ''),
   'risiko_alkohol'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_medikamente'                  => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_drogen'                       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_pille'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_pille_dauer'                  => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'hormon_substitution'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'hormon_substitution_art'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hormon_substitution_art')),
   'testosteron_substitution'            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'testosteron_substitution_dauer'      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'int',       'ext' => ''),
   'darmerkrankung_jn'                   => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'darmerkrankung_morbus'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'darmerkrankung_colitis'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'darmerkrankung_sonstige'             => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'risiko_infekt'                       => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_infekt_ebv'                   => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_htlv1'                 => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_hiv'                   => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_hcv'                   => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_hp'                    => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_bb'                    => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'check',    'ext' => ''),
   'risiko_infekt_sonstige'              => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'hormon_substitution_dauer'           => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'hpv'                                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'hpv_typ01'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ02'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ03'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ04'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ05'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ06'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ07'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ08'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ09'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'hpv_typ10'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'hpv_typ')),
   'risiko_transplantation'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_transplantation_detail'       => array('req' => 0, 'size' => '',   'maxlen' => '255',   'type' => 'string',    'ext' => ''),
   'risiko_familie_melanom'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_sonnenbrand_kind'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_sonnenbankbesuch'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_sonnenschutzmittel'           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_sonnenschutzmittel_detail'    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'sonnenschutzmittel')),
   'risiko_noxen'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'risiko_noxen_detail'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'noxen')),
   'risiko_chronische_wunden'            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'beruf_letzter'                       => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'beruf_letzter_dauer'                 => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'beruf_laengster'                     => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'beruf_laengster_dauer'               => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'beruf_risiko'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'beruf_risiko_detail'                 => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'risiko_sonstige'                     => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'sy_schmerzen'                        => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_schmerzen_lokalisation'           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'code_o3',   'ext' => array('type' => 't') ),
   'sy_schmerzen_lokalisation_seite'     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'sy_schmerzen_lokalisation_text'      => array('req' => 0, 'size' => '' ,  'maxlen' =>''   , 'type' => 'textarea'   , 'ext' => '' ),
   'sy_schmerzen_lokalisation_version'   => array('req' => 0, 'size' => '' ,  'maxlen' =>''   , 'type' => 'string'   , 'ext' => '' ),
   'sy_schmerzscore'                     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'schmerzscore') ),
   'sy_dyspnoe'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_haemoptnoe'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_husten'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_husten_dauer'                     => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'sy_harndrang'                        => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_nykturie'                         => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_pollakisurie'                     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_miktion'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_restharn'                         => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'float'    , 'ext' => '' ),
   'sy_harnverhalt'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_harnstau'                         => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_harnstau_lokalisation'            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'seite') ),
   'sy_haematurie'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_para_syndrom'                     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_para_syndrom_symptom'             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'para_syndrom_symptom') ),
   'sy_para_syndrom_detail'              => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'string'   , 'ext' => '' ),
   'sy_gewichtsverlust'                  => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptom_schwere') ),
   'sy_gewichtsverlust_2wo'              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'float'    , 'ext' => '' ),
   'sy_gewichtsverlust_3mo'              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'float'    , 'ext' => '' ),
   'sy_fieber'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'sy_nachtschweiss'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'sy_sonstige'                         => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'string'   , 'ext' => '' ),
   'sy_dauer'                            => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'sy_dauer_einheit'                    => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'symptomatik_dauer_einheit') ),
   'euroqol'                             => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'lcss'                                => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'fb_dkg'                              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'fb_dkg_beurt'                        => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'fb_dkg') ),
   'iciq_ui'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'iciq_ui') ),
   'ics'                                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'ics') ),
   'iief5'                               => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'iief5') ),
   'ipss'                                => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'ipss') ),
   'lq_dkg'                              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'lq_dkg') ),
   'gz_dkg'                              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'gz_dkg') ),
   'ql'                                  => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'ql') ),
   'familien_karzinom'                   => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jnnb') ),
   'gen_jn'                              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'gen_fap'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_gardner'                         => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_peutz'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_hnpcc'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_turcot'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_polyposis'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_dcc'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_baxgen'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_smad2'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_smad4'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_kras'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_apc'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_p53'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_cmyc'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_tgfb2'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_wiskott_aldrich'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_cvi'                             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_louis_bar'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_hpc1'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_pcap'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_cabp'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_brca1'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_brca2'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_x27_28'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'    , 'ext' => '' ),
   'gen_sonstige'                        => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'string'   , 'ext' => '' ),
   'bethesda'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jnv') ),
   'beratung_genetik'                    => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'pot_pde5hemmer'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'pot_pde5hemmer_haeufigkeit'          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'pde5hemmer_haeufigkeit') ),
   'pot_vakuumpumpe'                     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'pot_skat'                            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'pot_penisprothese'                   => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'ecog'                                => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'ecog') ),
   'schwanger'                           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'menopausenstatus'                    => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'menopause') ),
   'alter_menarche'                      => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'alter_menopause'                     => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'menopause_iatrogen'                  => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'menopause_iatrogen_ursache'          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'menopause_iatrogen_ursache') ),
   'geburten_lebend'                     => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'geburten_tot'                        => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'geburten_fehl'                       => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'schwangerschaft_erste_alter'         => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'schwangerschaft_letzte_alter'        => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'zn_hysterektomie'                    => array('req' => 0, 'size' =>'2' ,  'maxlen' => '11', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn')),
   'vorop'                               => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_lok1'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'vorop_lok_prostata') ),
   'vorop_lok2'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'vorop_lok_prostata') ),
   'vorop_lok3'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'vorop_lok_prostata') ),
   'vorop_lok4'                          => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'vorop_lok_prostata') ),
   'vorbestrahlung'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorbestrahlung_diagnose'             => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'string'   , 'ext' => '' ),
   'platinresistenz'                     => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'platinresistenz') ),
   'vorop_uterus_zervix'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_uterus_zervix_jahr'            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_uterus_zervix_erhaltung'       => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_uterus_zervix_histologie'      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_uterus_corpus'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_uterus_corpus_jahr'            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_uterus_corpus_erhaltung'       => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_uterus_corpus_histologie'      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_ovar_r'                        => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_ovar_r_jahr'                   => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_ovar_r_erhaltung'              => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_ovar_r_histologie'             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_ovar_l'                        => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_ovar_l_jahr'                   => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_ovar_l_erhaltung'              => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_ovar_l_histologie'             => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_adnexe_r'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_adnexe_r_jahr'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_adnexe_r_erhaltung'            => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_adnexe_r_histologie'           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_adnexe_l'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_adnexe_l_jahr'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_adnexe_l_erhaltung'            => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_adnexe_l_histologie'           => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_vulva'                         => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_vulva_jahr'                    => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_vulva_erhaltung'               => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_vulva_histologie'              => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_mamma_r'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_mamma_r_jahr'                  => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_mamma_r_erhaltung'             => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_mamma_r_histologie'            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_mamma_l'                       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_mamma_l_jahr'                  => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_mamma_l_erhaltung'             => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'vorop_mamma_l_histologie'            => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'malignitaet') ),
   'vorop_sonstige'                      => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'check'   , 'ext' => ''),
   'vorop_sonstige_jahr'                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'int'   , 'ext' => '' ),
   'vorop_sonstige_bem'                  => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'string'   , 'ext' => '' ),
   'hormon_sterilitaetsbehandlung'       => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'hormon_sterilitaetsbehandlung_dauer' => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'sonst'                               => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'lookup'   , 'ext' => array('l_basic' => 'jn') ),
   'sonst_dauer'                         => array('req' => 0, 'size' => '' ,  'maxlen' => '11', 'type' => 'int'      , 'ext' => '' ),
   'bem'                                 => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'textarea' , 'ext' => '' ),
   'createuser'                          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'                          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'                          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'                          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>
