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
   'beratung_id'                     => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'erkrankung_id'                  => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'patient_id'                     => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'datum'                          => array('req' => 1, 'size' => '',  'maxlen' => '' ,   'type' => 'date'       , 'ext' => '' ),
   'fragebogen_ausgehaendigt'        => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'psychoonkologie'                 => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'psychoonkologie_dauer'           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'int',         'ext' => ''),
   'hads'                            => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'hads_d_depression'               => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'hads_d_score') ),
   'hads_d_angst'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'hads_d_score') ),
   'bc_pass_a'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'bc_pass_a') ),
   'bc_pass_b'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'bc_pass_bc') ),
   'bc_pass_c'                       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'bc_pass_bc') ),
   'sozialdienst'                    => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'fam_risikosprechstunde'          => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'fam_risikosprechstunde_erfolgt'  => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'humangenet_beratung'             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'interdisziplinaer_angeboten'     => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'interdisziplinaer_durchgefuehrt' => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'ernaehrungsberatung'             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup',      'ext' => array('l_basic' => 'jn') ),
   'bem'                             => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea',    'ext' => ''),
   'createuser'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                      => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
