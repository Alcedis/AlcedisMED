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
   'anamnese_erkrankung_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'anamnese_id'            => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_id'             => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung'             => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'code_icd',  'ext' => array('showSide' => true)),
   'erkrankung_seite'       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array( 'l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'erkrankung_text'        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'erkrankung_version'     => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'morphologie'            => array('req' => 0, 'size' => '',  'maxlen' => '30',  'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
   'morphologie_text'       => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'textarea'   , 'ext' => '' ),
   'morphologie_version'    => array('req' => 0, 'size' => '',  'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'jahr'                   => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'aktuell'                => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'lookup',    'ext' => array( 'l_basic' => 'jn')),
   'therapie1'              => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'lookup',    'ext' => array( 'l_basic' => 'erkrankung_therapie')),
   'therapie2'              => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'lookup',    'ext' => array( 'l_basic' => 'erkrankung_therapie')),
   'therapie3'              => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'lookup',    'ext' => array( 'l_basic' => 'erkrankung_therapie')),
   'bem'                    => array('req' => 0, 'size' => '' ,  'maxlen' => ''  , 'type' => 'textarea' , 'ext' => '' ),
   'createuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>
