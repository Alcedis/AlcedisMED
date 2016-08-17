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
   WHERE freigabe IS NOT NULL AND inaktiv IS NULL AND art != 'st'
";

$fields = array(
    'therapie_systemisch_id'     => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'reference' => 'nebenwirkung'),
    'erkrankung_id'              => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'patient_id'                 => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'vorlage_therapie_id'        => array('req' => 1, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_schema, 'preselect' => 'vorlage_therapie_id'),
    'vorlage_therapie_art'       => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
    'org_id'                     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_org'], 'preselect' => 'org_id'),
    'user_id'                    => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'picker'     , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'), 'preselect' => 'user.user_id'),
    'therapieplan_id'            => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_therapieplan'], 'parent_status' => 'therapieplan'),
    'intention'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'intention')),
    'therapieform'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieform')),
    'therapielinie'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapielinie')),
    'metastasentherapie'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'studie'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'studie_id'                  => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_studie']),
    'beginn'                     => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
    'ende'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '', 'range' => false),
    'andauernd'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
    'zahnarzt'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'ort_therapiegabe'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ort_therapiegabe')),
    'tumorverhalten_platin'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tumorverhalten')),
    'endstatus'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieende')),
    'endstatus_grund'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'therapieplan_abweichung_grund')),
    'best_response'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'response')),
    'best_response_datum'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => ''),
    'best_response_bestimmung'   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'response_bestimmung')),
    'dosisaenderung'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'dosisaenderung_grund'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'dosisreduktion_grund')),
    'dosisaenderung_grund_sonst' => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
    'regelmaessig'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'regelmaessig_grund'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'regelmaessig_grund')),
    'unterbrechung'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'unterbrechung_grund'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'unterbrechung_grund')),
    'unterbrechung_grund_sonst'  => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => ''),
    'paravasat'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn')),
    'bem'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
    'createuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
