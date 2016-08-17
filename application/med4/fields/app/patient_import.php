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
   'patient_id'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'org_id'              => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_nr'          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'string',    'ext' => ''),
   'aufnahme_nr'         => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'string',    'ext' => ''),
   'nachname'            => array('req' => 1, 'size' => '50', 'maxlen' => '255','type' => 'string',    'ext' => ''),
   'vorname'             => array('req' => 1, 'size' => '50', 'maxlen' => '255','type' => 'string',    'ext' => ''),
   'geburtsdatum'        => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'date',      'ext' => ''),
   'createtime'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'lookup',    'ext' => array('l_basic' => 'erkrankung')),
);

?>