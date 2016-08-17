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

$queryLock = "
   SELECT
      DATE_FORMAT(slock.time, '%d.%m.%Y')    AS time,
      IF(slock.lock = 1, 'Lock', 'Unlock')  AS lock_status,
      bem.bem,
      status.patient_id,
      status.form,
      slock.status_lock_id,
      status.form_data
   FROM status status
      LEFT JOIN status_lock      slock  ON status.status_id = slock.status_id
      LEFT JOIN status_lock_bem  bem    ON bem.status_lock_bem_id = slock.status_lock_bem_id
   WHERE status.patient_id = '$patient_id' AND slock.status_lock_id IS NOT NULL
   GROUP BY slock.status_lock_id
";

$resultLock = sql_query_array($db, $queryLock);

$fields['time']            = array('type' => 'date');
$fields['lock_status']     = array('type' => 'string');
$fields['form_data']       = array('type' => 'string');
$fields['status_lock_id']  = array('type' => 'int');
$fields['bem']             = array('type' => 'string');
$fields['patient_id']      = array('type' => 'int');
$fields['form']            = array('type' => 'string');
$fields['form_name']       = array('type' => 'string');

$smarty->assign('back_btn', "page=view.patient&amp;patient_id=$patient_id");

$sql = get_sql('list', $queryLock, $where, $order, $limit);
data2list($db, $fields, $sql);


if (isset($fields['form']['value']) === true && count($fields['form']['value']) > 0) {
   foreach ($fields['form']['value'] as $index => $formName) {
     $fields['form_name']['value'][$index] = $config[$formName];
   }
}

?>