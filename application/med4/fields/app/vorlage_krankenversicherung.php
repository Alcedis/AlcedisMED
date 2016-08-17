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
   'vorlage_krankenversicherung_id' => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'name'                           => array('req' => 1, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'iknr'                           => array('req' => 1, 'size' =>'10',  'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'vknr'                           => array('req' => 0, 'size' =>'10',  'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'strasse'                        => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'plz'                            => array('req' => 0, 'size' =>'8',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'ort'                            => array('req' => 0, 'size' =>'34',  'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'land'                           => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'bundesland'                     => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup',    'ext' => array('l_basic' => 'bundesland')),
   'telefon'                        => array('req' => 0, 'size' =>'20',  'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'telefax'                        => array('req' => 0, 'size' =>'20',  'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'email'                          => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string',    'ext' => ''),
   'gkv'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'bem'                            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea',  'ext' => ''),
   'inaktiv'                        => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check',     'ext' => ''),
   'createuser'                     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'createtime'                     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updateuser'                     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
   'updatetime'                     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
);

?>