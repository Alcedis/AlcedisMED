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
    'sonstige_therapie_id'       => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => '', 'reference' => 'nebenwirkung'),
    'erkrankung_id'              => array('req' => 1, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'patient_id'                 => array('req' => 1, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'bez'                        => array('req' => 1, 'size' => '',   'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'sonstige_art'               => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'sonstige_art')),
    'org_id'                     => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'query',    'ext' => $querys['query_org'], 'preselect' => 'org_id'),
    'user_id'                    => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'picker',   'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id'),
    'therapieplan_id'            => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'query',    'ext' => $querys['query_therapieplan'], 'parent_status' => 'therapieplan'),
    'intention'                  => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'intention')),
    'studie'                     => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'studie_id'                  => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'query',    'ext' => $querys['query_studie']),
    'beginn'                     => array('req' => 1, 'size' => '',   'maxlen' => '',    'type' => 'date',     'ext' => '', 'range' => false),
    'ende'                       => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'date',     'ext' => '', 'range' => false),
    'endstatus'                  => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'therapieende')),
    'endstatus_grund'            => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'therapieplan_abweichung_grund')),
    'endstatus_grund_sonst'      => array('req' => 0, 'size' => '45', 'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'best_response'              => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'response')),
    'best_response_datum'        => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'date',     'ext' => ''),
    'unterbrechung'              => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'jn')),
    'unterbrechung_grund'        => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'unterbrechung_grund')),
    'unterbrechung_grund_sonst'  => array('req' => 0, 'size' => '45', 'maxlen' => '255', 'type' => 'string',   'ext' => ''),
    'bem'                        => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'textarea', 'ext' => ''),
    'createuser'                 => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'createtime'                 => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updateuser'                 => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
    'updatetime'                 => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => '')
);
?>
