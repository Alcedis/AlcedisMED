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

//fields/base/user.php extension -> for configuring required fields
if (isset($fields) === true && count($fields) > 0) {
    $fields['email']['req'] = 1;
    $fields['loginname']['req'] = 0;
    $fields['captcha']['req'] = 1;
    $fields['telefon']['req'] = 1;
    $fields['fachabteilung']['req'] = 1;

    //Pseudo req
    $fields['efn']['req'] = 3;

    $query_org  = "
       SELECT
          x.id,
          x.name
       FROM(
           SELECT
              o.org_id as id,
              CONCAT_WS(' ', o.name, IF(o.ort IS NOT NULL, CONCAT_WS('','(', o.ort, ')'), NULL)) as name,
              CONCAT_WS(' ', o.name, IF(o.ort IS NOT NULL, CONCAT_WS('','(', o.ort, ')'), NULL)) as 'order'
           FROM org o
           UNION
           SELECT
                0 as id,
                'andere' as 'name',
                'zzzzz' as 'order'
        ) x
        ORDER BY x.order
    ";

    $fields['org_id']         = array( 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'query' , 'ext' => $query_org);
    $fields['org_ort']        = array( 'req' => 0, 'size' => '', 'maxlen' => '255', 'type' => 'string' );
    $fields['org_name']       = array( 'req' => 0, 'size' => '', 'maxlen' => '255', 'type' => 'string' );
    $fields['org_namenszusatz'] = array( 'req' => 0, 'size' => '', 'maxlen' => '255', 'type' => 'string' );
    $fields['org_plz']        = array( 'req' => 0, 'size' => '5', 'maxlen' => '10' , 'type' => 'string', 'ext' => '' );
    $fields['org_strasse']    = array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string', 'ext' => '' );
    $fields['org_hausnr']     = array( 'req' => 0, 'size' => '5', 'maxlen' => '5' , 'type' => 'string', 'ext' => '' );
    $fields['org_telefon']    = array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'string', 'ext' => '' );
    $fields['org_telefax']    = array( 'req' => 0, 'size' => '', 'maxlen' => '20' , 'type' => 'string', 'ext' => '' );
    $fields['org_email']      = array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string', 'ext' => '' );
    $fields['org_website']    = array( 'req' => 0, 'size' => '', 'maxlen' => '50' , 'type' => 'string', 'ext' => '' );
    $fields['org_staat']      = array( 'req' => 0, 'size' => '', 'maxlen' => '5'  , 'type' => 'lookup', 'ext' => array('l_basic' => 'staat' ) );
    $fields['org_bundesland'] = array( 'req' => 0, 'size' => '', 'maxlen' => '5'  , 'type' => 'lookup', 'ext' => array('l_basic' => 'bundesland' ) );
}

?>