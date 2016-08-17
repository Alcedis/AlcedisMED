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
      'brief_id'            => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'patient_id'          => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'erkrankung_id'       => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'vorlage_dokument_id' => array('req' => 1, 'size' => '',   'maxlen' => ''  , 'type' => 'query' ,    'ext' => $querys['query_vorlage_dokument_br'], 'preselect' => 'vorlage_dokument_id'),
      'datum'               => array('req' => 1, 'size' => '',   'maxlen' => ''  , 'type' => 'date'  ,    'ext' => ''),
      'zeichen_sender'      => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
      'zeichen_empfaenger'  => array('req' => 0, 'size' => '',   'maxlen' => '255','type' => 'string',    'ext' => ''),
      'nachricht'           => array('req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'date'  ,    'ext' => ''),
      'hauptempfaenger_id'  => array('req' => 1, 'size' => '',   'maxlen' => '11', 'type' => 'picker'   , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'), 'preselect' => 'user.user_id' ),
      'abs_oberarzt_id'     => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'picker'   , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'), 'preselect' => 'user.user_id' ),
      'abs_assistent_id'    => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'picker'   , 'ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt'), 'preselect' => 'user.user_id' ),
      'fotos'               => array('req' => 0, 'size' => '' ,  'maxlen' =>'255', 'type' => 'hidden'   , 'ext' => ''),
      'bem'                 => array('req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'textarea',  'ext' => ''),
      'datenstand_datum'    => array('req' => 0, 'size' => '' , 'maxlen' => '19' , 'type' => 'hidden'   , 'ext' => ''),
      'createuser'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'createtime'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'updateuser'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => ''),
      'updatetime'          => array('req' => 0, 'size' => '',   'maxlen' => '11', 'type' => 'hidden',    'ext' => '')
   );


   if (isset($picker) && $picker) {
      $fields['weitere_empfaenger']  = array('ext' => array( 'query' => $querys['query_user'], 'type' => 'arzt') );
   }

?>