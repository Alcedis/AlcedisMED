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
    'qs_18_1_o_id'             => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'qs_18_1_brust_id'         => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'parent_status' => 'qs_18_1_brust'),
    'qs_18_1_b_id'             => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'erkrankung_id'            => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'patient_id'               => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'lfdnreingriff'            => array('req' => 1, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'diagoffbiopsie'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'praeopmarkierung'         => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'praeopmammographiejl'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'intraoppraeparatroentgen' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'praeopsonographiejl'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'intraoppraeparatsono'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'praeopmrtjl'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'opdatum'                  => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
    'opschluessel_1'           => array('req' => 1, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true)),
    'opschluessel_1_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_1_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_1_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'opschluessel_2'           => array('req' => 0, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true) ),
    'opschluessel_2_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_2_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_2_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'opschluessel_3'           => array('req' => 0, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true) ),
    'opschluessel_3_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_3_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_3_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'opschluessel_4'           => array('req' => 0, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true)),
    'opschluessel_4_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_4_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_4_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'opschluessel_5'           => array('req' => 0, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true)),
    'opschluessel_5_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_5_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_5_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'opschluessel_6'           => array('req' => 0, 'size' => '' , 'maxlen' => '29' , 'type' => 'code_ops'   , 'ext' => array('showSide' => true) ),
    'opschluessel_6_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
    'opschluessel_6_text'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'opschluessel_6_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'sentinellkeingriff'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'antibioprph'              => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'freigabe'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'createuser'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'               => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
