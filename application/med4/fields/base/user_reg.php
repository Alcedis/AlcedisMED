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

$query_org  = "
    SELECT
        x.id,
        x.name
    FROM (
        SELECT
            o.org_id as id,
            CONCAT_WS(' ', o.name, IF(o.ort IS NOT NULL, CONCAT_WS('','(', o.ort, ')'), NULL)) as name,
            CONCAT_WS(' ', o.name, IF(o.ort IS NOT NULL, CONCAT_WS('','(', o.ort, ')'), NULL)) as 'order'
        FROM org o
    ) x
    ORDER BY
        x.order
";

$fields = array(
   'user_reg_id'           => array('req' => 0, 'size' => '',  'maxlen' => 11 ,  'type' => 'hidden', 'ext' => '' ),
   'user_id'               => array('req' => 0, 'size' => '',  'maxlen' => 11 ,  'type' => 'hidden', 'ext' => '' ),
   'org_id'                => array('req' => 0, 'size' => '',  'maxlen' => '11', 'type' => 'query',  'ext' => $query_org),
   'org_name'              => array('req' => 0, 'size' => '' , 'maxlen' =>'255', 'type' => 'string', 'ext' => ''),
   'org_namenszusatz'      => array('req' => 0, 'size' => '' , 'maxlen' =>'255', 'type' => 'string', 'ext' => ''),
   'org_ort'               => array('req' => 0, 'size' => '' , 'maxlen' =>'255', 'type' => 'string', 'ext' => ''),
   'org_strasse'           => array('req' => 0, 'size' => '', 'maxlen' => '50',  'type' => 'string', 'ext' => '' ),
   'org_hausnr'            => array('req' => 0, 'size' => '5', 'maxlen' => '5',  'type' => 'string', 'ext' => '' ),
   'org_plz'               => array('req' => 0, 'size' => '5', 'maxlen' => '10', 'type' => 'string', 'ext' => '' ),
   'org_telefon'           => array('req' => 0, 'size' => '', 'maxlen' => '20',  'type' => 'string', 'ext' => '' ),
   'org_telefax'           => array('req' => 0, 'size' => '', 'maxlen' => '20',  'type' => 'string', 'ext' => '' ),
   'org_email'             => array('req' => 0, 'size' => '', 'maxlen' => '50',  'type' => 'string', 'ext' => '' ),
   'org_website'           => array('req' => 0, 'size' => '', 'maxlen' => '50',  'type' => 'string', 'ext' => '' ),
   'org_staat'             => array('req' => 0, 'size' => '', 'maxlen' => '5',   'type' => 'lookup', 'ext' => array('l_basic' => 'staat')),
   'org_bundesland'        => array('req' => 0, 'size' => '', 'maxlen' => '5',   'type' => 'lookup', 'ext' => array('l_basic' => 'bundesland')),
   'registered'            => array('req' => 0, 'size' => '' , 'maxlen' =>'1',   'type' => 'check', 'ext' => ''),
   'createuser'            => array('req' => 0, 'size' => '',  'maxlen' => 20 ,  'type' => 'hidden', 'ext' => '' ),
   'createtime'            => array('req' => 0, 'size' => '',  'maxlen' => 19 ,  'type' => 'hidden', 'ext' => '' ),
   'updateuser'            => array('req' => 0, 'size' => '',  'maxlen' => 20 ,  'type' => 'hidden', 'ext' => '' ),
   'updatetime'            => array('req' => 0, 'size' => '',  'maxlen' => 19 ,  'type' => 'hidden', 'ext' => '' )
);

?>