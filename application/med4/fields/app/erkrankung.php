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

$yearsSource = range(date('Y'), date('Y') - 20);
$years       = array();

foreach ($yearsSource as $year) {
    $years[] = "SELECT {$year}, {$year}";
}

$relevantYears = implode(" UNION ", $years);


$fields = array(
   'erkrankung_id'               => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_id'                  => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung'                  => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'erkrankung')),
   'erkrankung_detail'           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'erkrankung_sst_detail')),
   'beschreibung'                => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'seite'                       => array('req' => 0, 'size' => '',   'maxlen' =>'',    'type' => 'lookup',    'ext' => array('l_basic' => 'seite')),
   'zweiterkrankung'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'fallkennzeichen'             => array('req' => 0, 'size' => 50,   'maxlen' =>'255', 'type' => 'query',     'ext' => $querys['query_fallkennzeichen']),
   'erkrankung_relevant'         => array('req' => 0, 'size' => '',   'maxlen' =>'',    'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'erkrankung_relevant_haut'    => array('req' => 0, 'size' => '',   'maxlen' =>'',    'type' => 'query',     'ext' => $relevantYears),
   'rezidiv_bei_erstvorstellung' => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'notfall'                     => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'einweiser_id'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'picker',    'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'),'preselect' => 'user.user_id' ),
   'nachsorgepassnummer'         => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'bem'                         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'createuser'                  => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'                  => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'                  => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'                  => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>
