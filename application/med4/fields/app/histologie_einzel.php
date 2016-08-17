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

$query_diagnose = "
   SELECT
      d.diagnose_id AS 'code',
      d.lokalisation,
      s.bez,
      d.lokalisation_text
   FROM diagnose d
      LEFT JOIN l_basic s ON s.klasse = 'seite' AND s.code = d.lokalisation_seite
   WHERE
        d.erkrankung_id = '{$erkrankung_id}'
   GROUP BY
        d.diagnose_id
   ORDER BY
        d.lokalisation
";

$query_t = "
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'ct')
      UNION
   (SELECT
      code, bez
   FROM l_basic
   WHERE klasse = 'pt')
";

$fields = array(
    'histologie_einzel_id'      => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'histologie_id'             => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'erkrankung_id'             => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'patient_id'                => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
    'materialgewinnung_methode' => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'materialgewinnung_methode') ),
    'materialgewinnung_anzahl'  => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'int'        , 'ext' => '' ),
    'diagnose_id'               => array('req' => 1, 'size' => '' , 'maxlen' => '11' , 'type' => 'query'      , 'ext' => $query_diagnose ),
    'schnittechnik'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'haut_schnittechnik') ),
    'clark'                     => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'clark_level') ),
    'mikroskopisch'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'groesse_x'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'groesse_y'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'groesse_z'                 => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'morphologie'               => array('req' => 0, 'size' => '' , 'maxlen' => '30' , 'type' => 'code_o3'    , 'ext' => array('type' => 'm') ),
    'morphologie_text'          => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'morphologie_version'       => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
    'unauffaellig'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
    'ptnm_praefix'              => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'tnm_praefix') ),
    'pt'                        => array('req' => 0, 'size' => '',  'maxlen' => '',    'type' => 'query'      , 'ext' => $query_t),
    'g'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'g') ),
    'l'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'l') ),
    'v'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'v') ),
    'r'                         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'r') ),
    'ppn'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'ppn') ),
    'uicc'                      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'uicc') ),
    'ulzeration'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'regression'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'perineurale_invasion'      => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'wachstumsphase'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'wachstumsphase') ),
    'melanom_muttermal'         => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'randkontrolle'             => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'jn') ),
    'resektionsrand'            => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'tumordicke'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'float'      , 'ext' => '' ),
    'bem'                       => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
    'createuser'                => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => ''),
    'createtime'                => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => ''),
    'updateuser'                => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => ''),
    'updatetime'                => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => '')
);

?>
