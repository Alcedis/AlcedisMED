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

//WICHTIG
//Diese Query wird im Script nochmal erweitert da sie auch fuer einige andere Stellen benoetigt wird
$where = '';

if (isset($rolle_code) === true && $rolle_code !== 'admin') {
   $where      = "WHERE user.user_id NOT IN ($admin)";
}

$query_user = "
   SELECT
      DISTINCT user.user_id,
      CONCAT_WS(' ', CONCAT_WS(', ', user.nachname, user.vorname), CONCAT_WS('','(', user.loginname,')'))
   FROM user
   $where
   ORDER BY user.nachname, user.vorname
";


///////////////////////////////////////////////////////////////

$query_org  = "
   SELECT
      o.org_id,
      CONCAT_WS(', ', o.ort, o.name)
   FROM org o
   WHERE o.org_id > 0
   ORDER BY
      o.ort, o.name
";

$query_rolle = "
   SELECT
      code, bez
   FROM l_basic
   WHERE (";

if (isset($rolle_code) === true && $rolle_code !== 'admin') {
   $query_rolle .= "code != 'admin') AND (";
}

$query_rolle .= "(klasse = 'rolle' AND kennung IS NULL)";

//Extension 1: self register role activate
if (appSettings::get('allow_registration') === true) {
    $query_rolle .= " OR (klasse = 'rolle' AND kennung = 'reg')";
}

if (appSettings::get('rolle_konferenzteilnehmer') === true) {
    $query_rolle .= " OR (klasse = 'rolle' AND kennung = 'kt')";
}

if (appSettings::get('rolle_dateneingabe') === true) {
    $query_rolle .= " OR (klasse = 'rolle' AND kennung = 'dat')";
}

$query_rolle .= ')';

$fields = array(
	'recht_id'        => array( 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'hidden', 'ext' => '' ),
	'user_id'         => array( 'req' => 1, 'size' => '' , 'maxlen' => ''  , 'type' => 'picker', 'ext' => array( 'query' => $query_user, 'type' => 'user'), 'preselect' => 'user.user_id'  ),
	'org_id'          => array( 'req' => 1, 'size' => '', 'maxlen' => '11', 'type' => 'query' , 'ext' => $query_org, 'preselect' => 'o.org_id' ),
	'rolle'           => array( 'req' => 1, 'size' => '', 'maxlen' => '11', 'type' => 'query',  'ext' => $query_rolle),
    'behandler'       => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
	'recht_global'    => array( 'req' => 0, 'size' => '', 'maxlen' => ''  , 'type' => 'check' , 'ext' => '' ),
	'createuser'      => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
	'createtime'      => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
	'updateuser'      => array( 'req' => 0, 'size' => '', 'maxlen' => '20', 'type' => 'hidden', 'ext' => '' ),
	'updatetime'      => array( 'req' => 0, 'size' => '', 'maxlen' => '19', 'type' => 'hidden', 'ext' => '' ),
);

?>