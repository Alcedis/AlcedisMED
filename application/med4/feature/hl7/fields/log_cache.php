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
   'hl7_log_id'  => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',  'ext' => ''),
   'org_id'      => array('req' => 0, 'size' => '',   'maxlen' => '11' , 'type' => 'hidden',  'ext' => ''),
   'logtime'     => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'date',  'ext' => ''),
   'nachname'    => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'string',  'ext' => ''),
   'vorname'     => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'string',  'ext' => ''),
   'patient_nr'  => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'string',  'ext' => ''),
   'geburtsdatum'=> array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'date',  'ext' => ''),
   'aufnahme_nr' => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'string',  'ext' => ''),
   'type'        => array('req' => 0, 'size' => '',   'maxlen' => ''   , 'type' => 'string',  'ext' => ''),
   'status'      => array('type' => 'lookup', 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'lookup', 'ext' => array('l_basic' => 'hl7_status'))
);

?>