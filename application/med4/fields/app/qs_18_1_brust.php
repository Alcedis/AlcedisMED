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
   'qs_18_1_brust_id'            => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'qs_18_1_b_id'                => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '', 'parent_status' => 'qs_18_1_b'),
   'erkrankung_id'               => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
	'patient_id'                  => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => ''),
   'zuopseite'                   => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'zuopseite') ),
	'arterkrank'                  => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'erkrankung') ),
	'erstoffeingriff'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'tastbarmammabefund'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'primaertumor'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'primaertumor') ),
	'regiolymphknoten'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'regiolymphknoten') ),
	'anlasstumordiag'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'anlasstumordiageigen'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'anlasstumordiagfrueh'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'mammographiescreening'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'anlasstumordiagsympt'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'anlasstumordiagnachsorge'    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'anlasstumordiagsonst'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'praehistdiagsicherung'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'praehistbefund'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'praehistbefund') ),
    'praeicdo3'                   => array('req' => 0, 'size' => '',  'maxlen' => '30' , 'type' => 'code_qs'    , 'ext' => array('l_qs' => 'icdo3mamma') ),
	'ausganghistbefund'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'praethinterdisztherapieplan' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'datumtherapieplan'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
	'praeoptumorth'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'systchemoth'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'endokrinth'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'spezifantiktherapie'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'strahlenth'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'sonstth'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'pokomplikatspez'             => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
    'pokowundinfektion'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'nachblutung'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'serom'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'pokosonst'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'posthistbefund'              => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'posthistbefund') ),
    'posticdo3'                   => array('req' => 0, 'size' => '',  'maxlen' => '30' , 'type' => 'code_qs'    , 'ext' => array('l_qs' => 'icdo3mamma') ),
    'optherapieende'              => array('req' => 1, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'tumortherapieempf'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'tumortherapieempf') ),
	'tnmptmamma'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'tnmptmamma') ),
	'tnmpnmamma'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'tnmpnmamma') ),
	'anzahllypmphknoten'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'anzahllypmphknotenunb'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'graddcis'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'grading') ),
	'gesamttumorgroesse'          => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'tnmgmamma'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'grading_who') ),
	'rezeptorstatus'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'npsu') ),
	'her2neustatus'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'npu') ),
	'multizentrizitaet'           => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'angabensicherabstand'        => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'sicherheitsabstand') ),
	'sicherabstand'               => array('req' => 0, 'size' => '' , 'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
	'mnachstaging'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'mnachstaging') ),
	'bet'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'bet') ),
    'axlkentfomark'               => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj_axilla') ),
	'axilladissektion'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj_axilla') ),
	'slkbiopsie'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_qs' => 'nj') ),
	'radionuklidmarkierung'       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
	'farbmarkierung'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'freigabe'                    => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => ''),
   'createuser'                  => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                  => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                  => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                  => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>
