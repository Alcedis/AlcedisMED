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
   'untersuchung_lokalisation_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'untersuchung_id'              => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'patient_id'                   => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'erkrankung_id'                => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'lokalisation'                 => array('req' => 1, 'size' => '',   'maxlen' => '',   'type' => 'code_o3',   'ext' => array('showSide' => true, 'type' => 't')),
   'lokalisation_seite'           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'seite'), 'default' => '-', 'null' => '-'),
   'lokalisation_text'            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'lokalisation_version'         => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
   'beurteilung'                  => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'untersuchung_beurteilung')),
   'hoehe'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'groesse_x'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'groesse_y'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'groesse_z'                    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'float',     'ext' => ''),
   'groesse_nicht_messbar'        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'multipel'                     => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'organuebergreifend'           => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'wachstumsform'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'wachstumsform')),
   'naessen'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'krusten'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'blutung'                      => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'jn')),
   'zellzahl'                     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'int',       'ext' => ''),
   'createuser'                   => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'                   => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'                   => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'                   => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>