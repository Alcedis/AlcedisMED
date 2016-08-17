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

$queryAufenthalt = isset($querys['query_aufenthalt']) === true ? $querys['query_aufenthalt'] : null;

$fields = array(
   'qs_18_1_b_id'         => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'aufenthalt_id'        => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'query'      , 'ext' => $queryAufenthalt, 'preselect' => 'a.aufenthalt_id'),
   'erkrankung_id'        => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'patient_id'           => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'idnrpat'              => array('req' => 1, 'size' => '16' , 'maxlen' => '16' , 'type' => 'string'        , 'ext' => '' ),
   'aufndatum'            => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
   'aufndiag_1'           => array('req' => 1, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'aufndiag_1_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'aufndiag_1_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'aufndiag_1_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'aufndiag_2'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'aufndiag_2_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'aufndiag_2_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'aufndiag_2_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'aufndiag_3'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'aufndiag_3_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'aufndiag_3_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'aufndiag_3_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'aufndiag_4'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'aufndiag_4_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'aufndiag_4_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'aufndiag_4_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'aufndiag_5'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'aufndiag_5_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'aufndiag_5_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'aufndiag_5_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'asa'                  => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'asa') ),
   'adjutherapieplanung'  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
   'planbesprochen'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
   'planbesprochendatum'  => array('req' => 2, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
   'meldungkrebsregister' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
   'entldatum'            => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
   'entldiag_1'           => array('req' => 1, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'entldiag_1_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'entldiag_1_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'entldiag_1_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'entldiag_2'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'entldiag_2_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'entldiag_2_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'entldiag_2_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'entldiag_3'           => array('req' => 0, 'size' => '' , 'maxlen' => '28' , 'type' => 'code_icd'   , 'ext' => '' ),
   'entldiag_3_seite'     => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'lookup'    , 'ext' => array('l_qs' => 'zuopseite') ),
   'entldiag_3_text'      => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'textarea'  , 'ext' => '' ),
   'entldiag_3_version'   => array('req' => 0, 'size' => '' , 'maxlen' => '',     'type' => 'string'    , 'ext' => '' ),
   'entlgrund'            => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'entlgrund') ),
   'sektion'              => array('req' => 2, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
   'freigabe'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'createuser'           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'           => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
