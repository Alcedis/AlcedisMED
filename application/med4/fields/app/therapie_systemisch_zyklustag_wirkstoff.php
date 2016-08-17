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
   'therapie_systemisch_zyklustag_wirkstoff_id' => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => '', 'name_mod' => 'wirkstoff_data[therapie_systemisch_zyklustag_wirkstoff_id][]' ),
   'therapie_systemisch_zyklustag_id'           => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => '' ),
   'therapie_systemisch_zyklus_id'              => array('req'  => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => '' ),
   'therapie_systemisch_id'                     => array('req'  => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'     , 'ext' => '' ),
	'erkrankung_id'                              => array('req'  => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'    , 'ext' => '' ),
	'patient_id'                                 => array('req'  => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'    , 'ext' => '' ),
	'vorlage_therapie_wirkstoff_id'              => array('req'  => 1, 'size' => '',  'maxlen' => '11',  'type' => 'hidden'    , 'ext' => '' ),
	'dosis'                                      => array('req'  => 0, 'size' => '',  'maxlen' => '',    'type' => 'hidden'     , 'ext' => '' ),
	'einheit'                                    => array('req'  => 0, 'size' => '',  'maxlen' => '',    'type' => 'hidden'    , 'ext' => '' ),
	'aenderung_dosis'                            => array('req'  => 0, 'size' => '4', 'maxlen' => '',    'type' => 'float'     , 'ext' => '', 'name_mod' => 'wirkstoff_data[aenderung_dosis][]' ),
	'aenderung_einheit'                          => array('req'  => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup'    , 'ext' => array('l_basic' => 'wirkstoff_einheit'), 'name_mod' => 'wirkstoff_data[aenderung_einheit][]' ),
	'verabreicht_dosis'                          => array('req'  => 0, 'size' => '4', 'maxlen' => '',    'type' => 'float'     , 'ext' => '', 'name_mod' => 'wirkstoff_data[verabreicht_dosis][]' ),
	'verabreicht_einheit'                        => array('req'  => 0, 'size' => '',  'maxlen' => '',    'type' => 'lookup'    , 'ext' => array('l_basic' => 'wirkstoff_einheit'), 'name_mod' => 'wirkstoff_data[verabreicht_einheit][]' ),
	'kreatinin'                                  => array('req'  => 0, 'size' => '4', 'maxlen' => '',    'type' => 'float'     , 'ext' => '', 'name_mod' => 'wirkstoff_data[kreatinin][]' ),
   'createuser'                                 => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'createtime'                                 => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updateuser'                                 => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => ''),
   'updatetime'                                 => array('req'  => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',      'ext' => '')
);

?>