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
   'fragebogen_frage_id'    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'fragebogen_id'          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_id'             => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'vorlage_fragebogen_id'  => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'query' ,    'ext' => $querys['query_vorlage_fragebogen'], 'preselect' => 'v.vorlage_fragebogen_id'),
   'vorlage_fragebogen_frage_id'  => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'int' ,    'ext' => ''),
   'antwort'                => array('req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'int',  'ext' => ''),
   'createuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'             => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>