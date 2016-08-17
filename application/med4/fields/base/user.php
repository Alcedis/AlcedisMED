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
   'user_id'               => array('req' => 0, 'size' => '',   'maxlen' => 11 ,  'type' => 'hidden'   , 'ext' => '' ),
   'anrede'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup'   , 'ext' => array('l_basic' => 'anrede') ),
   'titel'                 => array('req' => 0, 'size' => '30', 'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'adelstitel'            => array('req' => 0, 'size' => '30', 'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'nachname'              => array('req' => 1, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'vorname'               => array('req' => 1, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'fachabteilung'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup'   , 'ext' => array('l_basic' => 'fachabteilung') ),
   'teilnahme_dmp'         => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'teilnahme_netzwerk'    => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'kr_kennung'            => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'kr_kuerzel'            => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'vertragsarztnummer'    => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'lanr'                  => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'bsnr'                  => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'efn'                   => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'efn_nz'                => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'org'                   => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'strasse'               => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'hausnr'                => array('req' => 0, 'size' => '5',  'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'plz'                   => array('req' => 0, 'size' => '5',  'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'ort'                   => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'telefon'               => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'handy'                 => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'telefax'               => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'email'                 => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'email'    , 'ext' => ''),
   'staat'                 => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'lookup'   , 'ext' => array('l_basic' => 'staat') ),
   'loginname'             => array('req' => 1, 'size' => '20', 'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'pwd'                   => array('req' => 1, 'size' => '',   'maxlen' => '40', 'type' => 'password' , 'ext' => '' ),
   'pwd_change'            => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'captcha'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'captcha'  , 'ext' => array('length' => 5)),
   'inaktiv'               => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'bank_kontoinhaber'     => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'bank_name'             => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'bank_blz'              => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'bank_kontonummer'      => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'bank_verwendungszweck' => array('req' => 0, 'size' => '',   'maxlen' =>'255', 'type' => 'string'   , 'ext' => ''),
   'candidate'             => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'check'    , 'ext' => ''),
   'bem'                   => array('req' => 0, 'size' => '',   'maxlen' => '',   'type' => 'textarea' , 'ext' => '' ),
   'createuser'            => array('req' => 0, 'size' => '',   'maxlen' => 20,   'type' => 'hidden'   , 'ext' => '' ),
   'createtime'            => array('req' => 0, 'size' => '',   'maxlen' => 19,   'type' => 'hidden'   , 'ext' => '' ),
   'updateuser'            => array('req' => 0, 'size' => '',   'maxlen' => 20,   'type' => 'hidden'   , 'ext' => '' ),
   'updatetime'            => array('req' => 0, 'size' => '',   'maxlen' => 19,   'type' => 'hidden'   , 'ext' => '' )
);

?>
