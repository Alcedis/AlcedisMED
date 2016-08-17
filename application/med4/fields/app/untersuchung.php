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
   'untersuchung_id'               => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '', 'reference' => array('diagnose', 'komplikation')),
   'patient_id'                    => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'                 => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'art'                           => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'code_ops',  'ext' => array('show_inputfield' => true, 'showSide' => true) ),
   'art_seite'                     => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'art_text'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'art_version'                   => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'koloskopie_vollstaendig'       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn_ni')),
   'ct_becken'                     => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'pe'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'kontrastmittel_iv'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'kontrastmittel_po'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'kontrastmittel_rektal'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'datum'                         => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'date',      'ext' => ''),
   'anlass'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'untersuchung_anlass')),
   'org_id'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'query',     'ext' => $querys['query_org'], 'preselect' => 'org_id'),
   'arzt_id'                       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'picker'   , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
   'beurteilung'                   => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'untersuchung_beurteilung')),
   'hno_untersuchung'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'lunge'                         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'birads'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'birads')),
   'ut'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'ut')),
   'un'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'un')),
   'cn'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'cn_plus')),
   'mesorektale_faszie'            => array('req' => 0, 'size' =>'',    'maxlen' =>'',    'type' => 'int',       'ext' => ''),
   'lavage_menge'                  => array('req' => 0, 'size' => '',   'maxlen' =>'11',  'type' => 'int',       'ext' => ''),
   'bulky'                         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'bulky_groesse'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'lk_a'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_b'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_c'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_d'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_e'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_f'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_g'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_h'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_i'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_k'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'lk_l'                          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'konsistenz'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'tumormarkerverlauf')),
   'rsh_verschieblich'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'abgrenzbarkeit'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'gesamtvolumen'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'kapselueberschreitung'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'invasion'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'invasion_detail'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'invasion_detail_prostata')),
   'bem'                           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'createuser'                    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'                    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'                    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'                    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>
