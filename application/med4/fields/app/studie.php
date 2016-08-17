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
    'studie_id'           => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'reference' => array('sonstige_therapie', 'strahlentherapie', 'therapie_systemisch')),
    'erkrankung_id'      => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'    , 'ext' => ''),
    'patient_id'         => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'    , 'ext' => ''),
    'vorlage_studie_id'  => array('req' => 1, 'size' => '500',  'maxlen' => ''   , 'type' => 'picker'    ,
                                  'ext' => array(
                                     'query' => $querys['query_vorlage_studie'],
                                     'type' => 'query',
                                     'table' => 'vorlage_studie'
                                  ),
                                  'preselect' => 'vorlage_studie_id'
     ),
    'org_id'             => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'query'     , 'ext' => $querys['query_org'], 'preselect' => 'org_id'),
    'user_id'            => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'picker'    , 'ext' => array('query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
    'date'               => array('req' => 2, 'size' => '',  'maxlen' => ''   , 'type' => 'date'      ,  'ext' => ''),
    'beginn'             => array('req' => 2, 'size' => '',  'maxlen' => ''   , 'type' => 'date'      ,  'ext' => ''),
    'ende'               => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'date'      ,  'ext' => '', 'range' => false),
    'patient_nr'         => array('req' => 0, 'size' =>'8',  'maxlen' => '255', 'type' => 'string'    ,  'ext' => ''),
    'arm'                => array('req' => 0, 'size' =>'8',  'maxlen' => '255', 'type' => 'string'    ,  'ext' => ''),
    'bem'                => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => ''),
    'createuser'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'          => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>