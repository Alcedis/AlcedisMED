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
    'konferenz_patient_id'       => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'reference' => 'therapieplan' ),
	'erkrankung_id'              => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'patient_id'                 => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'konferenz_id'               => array('req' => 0, 'size' => '500',  'maxlen' => ''   , 'type' => 'picker'    ,
            'ext' => array(
                'query' => $querys['query_konferenz'],
                'table' => 'konferenz',
                'type' => 'query'
            ),
            'preselect' => 'konferenz_id'
    ),
    'art'                        => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tumorkonferenz_art') ),
	'vorlage_dokument_id'        => array('req' => 1, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_vorlage_dokument_kpr'], 'preselect' => 'vorlage_dokument_id'),
	'fragestellung'              => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'patientenwunsch'            => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
	'patientenwunsch_beo'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
	'patientenwunsch_nerverhalt' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'primaervorstellung'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'primaervorstellung_prostata') ),
    'primaervorstellung_sonst'   => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'biopsie_durch'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'biopsie_durch_prostata') ),
    'biopsie_durch_sonst'        => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'mskcc'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'mskcc_ic'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'mskcc_svi'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'mskcc_ocd'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'mskcc_lni'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'mskcc_ee'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'fotos'                      => array('req' => 0, 'size' => '' , 'maxlen' =>'255' , 'type' => 'hidden'     , 'ext' => ''),
	'datenstand_datum'           => array('req' => 0, 'size' => '' , 'maxlen' => '19' , 'type' => 'hidden'     , 'ext' => '' ),
	'bem'                        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
	'createuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'createtime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updateuser'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
    'updatetime'                 => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>