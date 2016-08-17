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

$loginname = isset($_REQUEST['loginname']) === true
   ? $_REQUEST['loginname']
   : (isset($_SESSION['sess_user_log_detail']) === true
      ? $_SESSION['sess_user_log_detail']
      : null
   )
;

if ($loginname !== null) {

   $_SESSION['sess_user_log_detail'] = $loginname;

   $cookie = cookie::create($user_id, $pageName);

   $orderBy    = "history_id";
   $groupBy    = "history_id";
   $where      = "loginname = '{$loginname}'";

   $searchFields = array(
      'id'        => array('type' => 'string', 'field'  => 'history_id'),
      'datum'     => array('type' => 'date',   'field'  => "DATE_FORMAT(login_time, '%Y-%m-%d')"),
      'uhrzeit'   => array('type' => 'string', 'field'  => "DATE_FORMAT(login_time, '%H:%i:%s')"),
      'ip'        => array('type' => 'string', 'field'  => 'login_ip'),
      'status'    => array('type' => 'check',  'field'  => 'login_acc')
   );

   $queryMod = queryModifier::create($db, $smarty)
      ->setCookie($cookie)
      ->setQuery($querys['user_log_detail'])
      ->setSearchFields($searchFields)
      ->setOrderBy($orderBy, 'DESC')
      ->setTable('history')
      ->setWhere($where)
   ;

   data2list($db, $fields, $queryMod->query());

   $smarty
      ->assign('entryCount', $queryMod->getDatasetCount())
   ;
}

$smarty
   ->assign('back_btn', 'page=list.user_log');

?>