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
   'status_id'                  => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'patient_id'                 => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'erkrankung_id'              => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'parent_status_id'           => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'form_id'                    => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden'     , 'ext' => '' ),
   'form'                       => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'form_date'                  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'date'       , 'ext' => '' ),
   'form_status'                => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'int'        , 'ext' => ''  ),
   'form_data'                  => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'form_param'                 => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' ),
   'report_param'               => array('req' => 0, 'size' => '' , 'maxlen' => '255', 'type' => 'string'     , 'ext' => '' )
);

?>