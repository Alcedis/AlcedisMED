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
   'settings_hl7field_id' => array( 'req' => 0, 'size' => '', 'maxlen' => '11',   'type' => 'hidden', 'ext' => '' ),
   'settings_hl7_id'      => array( 'req' => 0, 'size' => '', 'maxlen' => '11',   'type' => 'hidden', 'ext' => '' ),
   'med_feld'             => array( 'req' => 1, 'size' => '', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'hl7'                  => array( 'req' => 1, 'size' => '', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'hl7_bereich'          => array( 'req' => 0, 'size' => 4, 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'hl7_back'             => array( 'req' => 0, 'size' => '', 'maxlen' => '2' , 'type' => 'check' , 'ext' => '' ),
   'feld_typ'             => array( 'req' => 1, 'size' => '', 'maxlen' => ''    , 'type' => 'lookup' , 'ext' => array('l_basic' => 'hl7_feld_typ' )),
   'feld_trim_null'       => array( 'req' => 0, 'size' => '', 'maxlen' => ''    , 'type' => 'check' , 'ext' => ''),
   'multiple'             => array( 'req' => 1, 'size' => '', 'maxlen' => ''    , 'type' => 'lookup' , 'ext' => array('l_basic' => 'hl7_multiple' )),
   'import'               => array( 'req' => 0, 'size' => '', 'maxlen' => '2' , 'type' => 'check' , 'ext' => '' ),
   'multiple_segment'     => array( 'req' => 2, 'size' => '', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'multiple_filter'      => array( 'req' => 2, 'size' => '', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'ext'                  => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'createuser'           => array( 'req' => 0, 'size' => '', 'maxlen' => '20'  , 'type' => 'hidden', 'ext' => '' ),
   'createtime'           => array( 'req' => 0, 'size' => '', 'maxlen' => '19'  , 'type' => 'hidden', 'ext' => '' ),
   'updateuser'           => array( 'req' => 0, 'size' => '', 'maxlen' => '20'  , 'type' => 'hidden', 'ext' => '' ),
   'updatetime'           => array( 'req' => 0, 'size' => '', 'maxlen' => '19'  , 'type' => 'hidden', 'ext' => '' ),
);

?>