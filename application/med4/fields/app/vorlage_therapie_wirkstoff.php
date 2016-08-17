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

//Hinweis: Query wird im script fertig gebaut (Anhang des Art Feldes aus dem vorFormular
$query = "SELECT code, bez FROM l_basic WHERE klasse='wirkstoff'";

$fields = array(
   'vorlage_therapie_wirkstoff_id'  => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
   'vorlage_therapie_id'            => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
   'art'                            => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'hidden',   'ext' => ''),
   'wirkstoff'                      => array('req' => 1, 'size' => '',   'maxlen' => '',    'type' => 'query',    'ext' => $query),
   'radionukleid'                   => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'radionukleid')),
   'dosis'                          => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'float',    'ext' => ''),
   'einheit'                        => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'wirkstoff_einheit')),
   'applikation'                    => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'applikation')),
   'zyklus_beginn'                  => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'zyklus_anzahl'                  => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'zyklustag'                      => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag02'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag03'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag04'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag05'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag06'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag07'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag08'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag09'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklustag10'                    => array('req' => 0, 'size' => '2',   'maxlen' => '3',   'type' => 'int',      'ext' => ''),
   'zyklusdauer'                    => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'loesungsmittel'                 => array('req' => 0, 'size' => '',   'maxlen' => '255', 'type' => 'string',   'ext' => ''),
   'loesungsmittel_menge'           => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'infusionsdauer'                 => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'infusionsdauer_einheit'         => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'infusionsdauer_einheit')),
   'applikationsfrequenz'           => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'applikationsfrequenz_einheit'   => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'applikationsfrequenz_einheit')),
   'therapiedauer'                  => array('req' => 0, 'size' => '',   'maxlen' => '6',   'type' => 'int',      'ext' => ''),
   'therapiedauer_einheit'          => array('req' => 0, 'size' => '',   'maxlen' => '',    'type' => 'lookup',   'ext' => array('l_basic' => 'therapiedauer_einheit')),
   'createuser'                     => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
   'createtime'                     => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
   'updateuser'                     => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => ''),
   'updatetime'                     => array('req' => 0, 'size' => '',   'maxlen' => '11',  'type' => 'hidden',   'ext' => '')
);

?>