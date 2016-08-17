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

$query_systemisch = "
   SELECT
      ts.therapie_systemisch_id,
      DATE_FORMAT(ts.beginn, '%d.%m.%Y'),
      vth.bez
   FROM therapie_systemisch ts
      LEFT JOIN vorlage_therapie vth ON ts.vorlage_therapie_id = vth.vorlage_therapie_id
   WHERE
      ts.erkrankung_id = '$erkrankung_id'
";


$query_strahlen = "
   SELECT
      strahlentherapie_id,
      DATE_FORMAT(st.beginn, '%d.%m.%Y'),
      vth.bez
   FROM strahlentherapie st
      LEFT JOIN vorlage_therapie vth ON st.vorlage_therapie_id = vth.vorlage_therapie_id
   WHERE
      erkrankung_id = '$erkrankung_id'
";

$query_sonstige = "
   SELECT
      sonstige_therapie_id,
      DATE_FORMAT(beginn, '%d.%m.%Y'),
      bez
   FROM sonstige_therapie
   WHERE
      erkrankung_id = '$erkrankung_id'
";

$fields = array(
   'nebenwirkung_id'            => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'erkrankung_id'              => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
	'patient_id'                 => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'nci_code'                   => array('req' => 1, 'size' => '' , 'maxlen' => '27' , 'type' => 'code_nci'   , 'ext' => '' ),
	'nci_code_version'           => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'nci_text'                   => array('req' => 0, 'size' => '' , 'maxlen' => '',    'type' => 'textarea'   , 'ext' => '' ),
	'grad'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nci_grad') ),
	'ausgang'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nebenwirkung_ausgang') ),
	'beginn'                     => array('req' => 3, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'beginn_unbekannt'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'ende'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'ende_unbekannt'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'zusammenhang'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'nebenwirkung_zusammenhang') ),
	'therapie_systemisch_id'     => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_systemisch, 'parent_status' => 'therapie_systemisch'),
	'strahlentherapie_id'        => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_strahlen, 'parent_status' => 'strahlentherapie'),
	'sonstige_therapie_id'       => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_sonstige, 'parent_status' => 'sonstige_therapie'),
	'therapie'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'therapie_text'              => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'sae'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'bem'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
   'createuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>