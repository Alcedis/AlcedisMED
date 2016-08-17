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

$unlock_id  = isset( $_REQUEST['user_unlock_id'] ) ? $_REQUEST['user_unlock_id'] : '';

if(strlen($unlock_id)){
   //Erst Daten holen, dann löschen (weiterverarbeitung für eintrag in a_table)
   $query = "SELECT * FROM user_lock WHERE user_lock_id = '$unlock_id'";
   $fields = reset(sql_query_array($db, $query));

   if ($fields !== false) {
      $query = "DELETE FROM user_lock WHERE user_lock_id = '$unlock_id'";
      mysql_query( $query, $db );

      //Backup write in a_tabelle (muss eigenes query da für function execute_delete und Co. keine fields zur Verfügung stehen)"
      $fields['updateuser'] = $_SESSION['sess_loginname'];
      $fields['updatetime'] = date("Y-m-d G:i:s" );
      $fields['action']     = 'delete';

      $query     = " INSERT INTO _user_lock (a_action, user_lock_id, loginname, last_login_acc, last_login_fail, login_ip, updateuser, updatetime) VALUES " .
                   " ('$fields[action]', '$fields[user_lock_id]', '$fields[loginname]', '$fields[last_login_acc]', '$fields[last_login_fail]', '$fields[login_ip]',".
                   " '$fields[updateuser]', '$fields[updatetime]')";
      mysql_query( $query, $db );
   }

}

$query = "
   SELECT
      ul.*,
      CONCAT_WS(', ',
            CONCAT_WS(' ', anrede.bez, u.titel, u.vorname, u.nachname),
            u.ort
         )                 AS 'info',
      DATE_FORMAT(last_login_acc, '%d.%m.%Y %H:%i:%s')   AS last_login_acc,
      DATE_FORMAT(last_login_fail, '%d.%m.%Y %H:%i:%s')  AS last_login_fail
   FROM user_lock ul
      LEFT JOIN user u  ON u.loginname = ul.loginname
         LEFT JOIN l_basic anrede   ON u.anrede=anrede.code AND anrede.klasse='anrede'
      WHERE 1
";

$locked = sql_query_array($db, $query);

$smarty->assign('user_locked', $locked);

//Setzen der Links
$form_rec = get_url('page=list.user_unlock');

$smarty
   ->assign('back_btn', 'page=extras');

?>