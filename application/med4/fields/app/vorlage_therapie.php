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

$rechtErkrankungString = isset($_SESSION['sess_recht_erkrankung']) === true
   ? implode($_SESSION['sess_recht_erkrankung'], "','")
   : dlookup($db, 'l_basic', 'CONCAT_WS("' . "','" . '",code)', "klasse = 'erkrankung'");

$erkrankungWhere  = "code IN('" . $rechtErkrankungString. "')";
$erkrankung_query = "
   SELECT
      code,
      bez
   FROM l_basic
   WHERE
      klasse = 'erkrankung'
      AND (kennung = 'all' OR $erkrankungWhere)
";

$fields = array(
   'vorlage_therapie_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'bez'                 => array('req' => 1, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'art'                 => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'therapieart')),
   'erkrankung'          => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'query',     'ext' => $erkrankung_query),
   'datei'               => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'hidden',    'ext' => ''),
   'freigabe'            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'inaktiv'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'bem'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'createuser'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>