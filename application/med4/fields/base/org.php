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
   'org_id'        => array( 'req' => 0, 'size' => '', 'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'name'          => array( 'req' => 1, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'namenszusatz'  => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'strasse'       => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'hausnr'        => array( 'req' => 0, 'size' => '5', 'maxlen' => '5' , 'type' => 'string'     , 'ext' => '' ),
   'plz'           => array( 'req' => 0, 'size' => '5', 'maxlen' => '10' , 'type' => 'string'     , 'ext' => '' ),
   'ort'           => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'telefon'       => array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'string'     , 'ext' => '' ),
   'telefax'       => array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'string'     , 'ext' => '' ),
   'email'         => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'website'       => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'staat'         => array( 'req' => 0, 'size' => '', 'maxlen' => '5'  , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'staat' ) ),
   'bundesland'    => array( 'req' => 0, 'size' => '', 'maxlen' => '5'  , 'type' => 'lookup'     , 'ext' => array('l_basic' => 'bundesland' ) ),
   'ik_nr'         => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'kr_kennung'    => array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string'     , 'ext' => '' ),
   'mandant'       => array( 'req' => 0, 'size' => '', 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
   'inaktiv'       => array( 'req' => 0, 'size' => '', 'maxlen' => ''   , 'type' => 'check'      , 'ext' => '' ),
   'logo'          => array( 'req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'file',         'ext' => ''),
   'img_type'      => array( 'req' => 0, 'size' => '',   'maxlen' => ''  , 'type' => 'hidden'   ,    'ext' => ''),
   'bem'           => array( 'req' => 0, 'size' => '', 'maxlen' => ''   , 'type' => 'textarea'   , 'ext' => '' ),
   'createuser'    => array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'hidden'     , 'ext' => '' ),
   'createtime'    => array( 'req' => 0, 'size' => '', 'maxlen' => '19' , 'type' => 'hidden'     , 'ext' => '' ),
   'updateuser'    => array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'hidden'     , 'ext' => '' ),
   'updatetime'    => array( 'req' => 0, 'size' => '', 'maxlen' => '19' , 'type' => 'hidden'     , 'ext' => '' ),
);

?>