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
   'komplikation_id'        => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'erkrankung_id'          => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'patient_id'             => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'datum'                  => array('req' => 1, 'size' => '',  'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
   'komplikation'           => array('req' => 1, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'komplikation') ),
   'eingriff_id'            => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_eingriff'], 'parent_status' => 'eingriff' ),
   'untersuchung_id'        => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'query'      , 'ext' => $querys['query_untersuchung'], 'parent_status' => 'untersuchung' ),
   'zeitpunkt'              => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'komplikation_zeitpunkt') ),
   'clavien_dindo'          => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'klassifikation_clavien_dindo') ),
   'ctcae'                  => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'klassifikation_ctcae') ),
   'reintervention'         => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'antibiotikum'           => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'antibiotikum_dauer'     => array('req' => 0, 'size' => '',  'maxlen' => '6'  , 'type' => 'float'      , 'ext' => '' ),
   'drainage_intervent'     => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'drainage_transanal'     => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'transfusion'            => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'transfusion_anzahl_ek'  => array('req' => 0, 'size' => '',  'maxlen' => '4'  , 'type' => 'int'        , 'ext' => '' ),
   'transfusion_anzahl_tk'  => array('req' => 0, 'size' => '',  'maxlen' => '4'  , 'type' => 'int'        , 'ext' => '' ),
   'transfusion_anzahl_ffp' => array('req' => 0, 'size' => '',  'maxlen' => '4'  , 'type' => 'int'        , 'ext' => '' ),
   'gerinnungshemmer'       => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'beatmung'               => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'beatmung_dauer'         => array('req' => 0, 'size' => '',  'maxlen' => '6'  , 'type' => 'float'      , 'ext' => '' ),
   'intensivstation'        => array('req' => 0, 'size' => '',  'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'intensivstation_dauer'  => array('req' => 0, 'size' => '',  'maxlen' => '6'  , 'type' => 'float'      , 'ext' => '' ),
   'sekundaerheilung'       => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'revisionsoperation'     => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
   'wund_spuelung'          => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'check'     , 'ext' => ''),
   'wund_spreizung'         => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'check'     , 'ext' => ''),
   'wund_vac'               => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'check'     , 'ext' => ''),
   'bem'                    => array('req' => 0, 'size' => '',  'maxlen' => ''    , 'type' => 'textarea'   , 'ext' => '' ),
   'createuser'             => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'             => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'             => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'             => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
