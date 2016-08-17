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

$settingsErkrankungen = isset($_SESSION['settings']['erkrankungen'])
   ? $_SESSION['settings']['erkrankungen']
   : dlookup($db, 'l_basic', 'CONCAT_WAS(",", code)', "klasse = 'erkrankung'");

$systemErkrankungen = str_replace(',', "','", $settingsErkrankungen);

$query_erkrankung_recht = "
   SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'erkrankung' AND code IN ('$systemErkrankungen')
";

$fields = array(
   'vorlage_query_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'bez'              => array('req' => 1, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'erkrankung'       => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'query',     'ext' => $query_erkrankung_recht, 'preselect' => "klasse = 'erkrankung' AND code"),
   'typ'              => array('req' => 3, 'size' => '',   'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'rpt_typ')),
   'package'          => array('req' => 3, 'size' => '',   'maxlen' =>'255', 'type' => 'hidden',    'ext' => ''),
   'sqlstring'        => array('req' => 3, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'freigabe'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'inaktiv'          => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'bem'              => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'ident'            => array('req' => 0, 'size' => '9',  'maxlen' =>'10',  'type' => 'string',    'ext' => ''),
   'createuser'       => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'       => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'       => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'       => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>