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
   'settings_hl7_id'                 => array( 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'hidden', 'ext' => '' ),
   'bem'                             => array('req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'textarea',  'ext' => ''),
   'active'                          => array( 'req' => 0, 'size' => '', 'maxlen' => ''    , 'type' => 'check'  , 'ext' => '' ),
   'import_mode'                     => array( 'req' => 1, 'size' => '', 'maxlen' => ''    , 'type' => 'lookup' , 'ext' => array('l_basic' => 'hl7_import_mode') ),
   'valid_event_types'               => array( 'req' => 1, 'size' => '', 'maxlen' => '' , 'type' => 'textarea' , 'ext' => '' ),
   'patient_ident'                   => array( 'req' => 1, 'size' => '', 'maxlen' => ''    , 'type' => 'lookup' , 'ext' => array('l_basic' => 'hl7_patient_ident') ),
   'user_ident'                      => array( 'req' => 1, 'size' => '', 'maxlen' => ''    , 'type' => 'lookup' , 'ext' => array('l_basic' => 'hl7_user_ident') ),
   'cache_dir'                       => array( 'req' => 1, 'size' => '', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'max_log_time'                    => array( 'req' => 0, 'size' => '3', 'maxlen' => ''   , 'type' => 'int'    , 'ext' => '' ),
   'max_usability_time'              => array( 'req' => 0, 'size' => '3', 'maxlen' => ''   , 'type' => 'int'    , 'ext' => '' ),
   'update_patient_due_caching'      => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'cache_diagnose_active'           => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'cache_diagnose_hl7'              => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'cache_diagnose_filter'           => array( 'req' => 0, 'size' => '1', 'maxlen' => '' , 'type' => 'textarea' , 'ext' => '' ),
   'cache_diagnosetyp_active'        => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'cache_diagnosetyp_hl7'           => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'cache_diagnosetyp_filter'        => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'cache_abteilung_active'          => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'cache_abteilung_hl7'             => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'cache_abteilung_filter'          => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'import_diagnose_active'          => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'import_diagnose_hl7'             => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'import_diagnose_filter'          => array( 'req' => 0, 'size' => '1', 'maxlen' => '' , 'type' => 'textarea' , 'ext' => '' ),
   'import_diagnosetyp_active'       => array( 'req' => 0, 'size' => '2', 'maxlen' => ''   , 'type' => 'check'  , 'ext' => '' ),
   'import_diagnosetyp_hl7'          => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'import_diagnosetyp_filter'       => array( 'req' => 0, 'size' => '50', 'maxlen' => '255' , 'type' => 'string' , 'ext' => '' ),
   'createuser'                      => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
   'createtime'                      => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
   'updateuser'                      => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
   'updatetime'                      => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
);

?>
