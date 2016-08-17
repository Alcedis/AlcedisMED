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

$query_untersuchung = "
   SELECT
      untersuchung_id,
      CONCAT_WS(' - ', DATE_FORMAT(datum, '%d.%m.%Y'), l.bez, art_text)
   FROM untersuchung u
       LEFT JOIN l_basic l ON (u.art_seite IS NOT NULL OR u.art_seite != '-') AND l.klasse = 'seite' AND l.code = u.art_seite
   WHERE
      erkrankung_id='$erkrankung_id'
";

$fields = array(
   'diagnose_id'          => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',    'ext' => ''),
   'patient_id'           => array('req' => 1, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'        => array('req' => 1, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',    'ext' => ''),
   'datum'                => array('req' => 1, 'size' => '',   'maxlen' => ''   , 'type' => 'date',      'ext' => ''),
   'diagnose'             => array('req' => 1, 'size' => '',   'maxlen' => ''   , 'type' => 'code_icd',  'ext' => ''),
   'diagnose_seite'       => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'diagnose_text'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'diagnose_version'     => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'lokalisation'         => array('req' => 1, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't'), 'default' => '-', 'null' => '-'),
   'lokalisation_seite'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'lokalisation_text'    => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => '', 'default' => '-', 'null' => '-'),
   'lokalisation_version' => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'ct'                   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'ct')),
   'schleimhautmelanom'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'untersuchung_id'      => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'query',     'textLength' => 91, 'ext' => $query_untersuchung, 'parent_status' => 'untersuchung'),
   'rezidiv_von'          => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't')),
   'rezidiv_von_seite'    => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'rezidiv_von_text'     => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'rezidiv_von_version'  => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'lokoregionaer'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'haut_lokoregionaer')),
   'metast_visz'          => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'check',     'ext' => ''),
   'metast_visz_1'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't')),
   'metast_visz_1_seite'  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'metast_visz_1_text'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'metast_visz_1_version' => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'metast_visz_2'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't')),
   'metast_visz_2_seite'  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'metast_visz_2_text'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'metast_visz_2_version' => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'metast_visz_3'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't')),
   'metast_visz_3_seite'  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'metast_visz_3_text'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'metast_visz_3_version' => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'metast_visz_4'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'code_o3',   'ext' => array('type' => 't')),
   'metast_visz_4_seite'  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'metast_visz_4_text'   => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'metast_visz_4_version' => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',    'ext' => ''),
   'metast_haut'          => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'check',     'ext' => ''),
   'metast_lk'            => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'check',     'ext' => ''),
   'bem'                  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'textarea',  'ext' => ''),
   'createuser'           => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
   'createtime'           => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
   'updateuser'           => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => ''),
   'updatetime'           => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'hidden',    'ext' => '')
);

?>
