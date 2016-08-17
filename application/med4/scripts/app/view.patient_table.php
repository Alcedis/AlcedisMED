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
   'status_id'  => array( 'type' => 'int' ),
   'form_id'    => array( 'type' => 'hidden' ),
   'form'       => array( 'type' => 'string' ),
   'info'       => array( 'type' => 'string' ),
   'class'      => array( 'type' => 'string' ),
   'config'     => array( 'type' => 'string' ),
   'form_name'  => array( 'type' => 'string' ),
   'status_lock'=> array( 'type' => 'int'),
   'patient_id' => array( 'type' => 'int'),
   'locked'     => array( 'type' => 'int'),
   'total'      => array( 'type' => 'int'),
   'kpdirty'    => array( 'type' => 'int'),
   'status'     => array('req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'lookup', 'ext' => array('l_basic' => 'status'))
);

data2list( $db, $fields, $querys['patient_view'] );

//Hole den Namen des Formulares aus "patient.conf" und baue die Referenz-Links der Unterformulare
if (isset($fields['config']['value']) === true && count($fields['config']['value']) > 0) {
   foreach ($fields['config']['value'] as $index => $formName) {
     $fields['form_name']['value'][$index] = $config[$formName];
   }
}

?>