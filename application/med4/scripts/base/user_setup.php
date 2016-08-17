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


$fields_user         = $widget->loadExtFields('fields/base/user.php');
$fields_user_empty   = $fields_user;

$user_id     = isset( $_SESSION['sess_user_id'] ) ? $_SESSION['sess_user_id'] : '';
$location  	 = 'index.php?page=login&state=pwd_changed';
$button      = 'update';

$first_login = dlookup($db, 'user', 'pwd_change', "user_id='$user_id'");

if (isset($_SESSION['sess_recht_id']) === true) {
   $location   = 'index.php?page=extras';
   $button     = 'update.cancel';
}

switch ($action) {
   case 'update':
      // Alte Werte beibehalten und neue Werte überschreiben...
      $result = reset(sql_query_array($db, "SELECT * FROM user WHERE user_id = '$user_id'"));


      foreach( $result AS $field_name => $field_value )
      {
         if( !isset( $_REQUEST[$field_name] ) )
            $_REQUEST[$field_name] = $field_value;
      }

      $fields = array_merge( $fields, $fields_user_empty );

      form2fields( $fields );

      $fields['user_id']['value'][0]    = $user_id;
      $fields['pwd_change']['value'][0] = '1';

      // Validierung Starten
      $validate = validate_dataset( $smarty, $db, $fields, 'ext_err');

      if ($validate !== false) {
         unset($fields['pwd_old']);
         unset($fields['pwd_new1']);
         unset($fields['pwd_new2']);

         // Formular Aktion auswerten
         todate( $fields,  'en' );
         tofloat( $fields, 'en' );

         execute_update($smarty, $db, $fields, 'user', "user_id = " . $user_id, $action, null, true, null, $validate);

         // Falls keine Fehler (wie Duplicate entry, ...)
         $error = $smarty->get_template_vars('error');
         if ( !strlen($error) ){
            $_SESSION['sess_info'] = $config['lbl_kennwort_gea'];
            header ("location: $location");
         }
      }
      break;

   case 'cancel':
      header ("location: $location");

      break;
}

show_record( $smarty, $db, $fields, "user", $user_id );

$smarty
   ->assign("user_id", $user_id)
   ->assign("button",  $button)
   ->assign('first_login', $first_login);

if ($first_login == 1) {
   $smarty->assign("back_btn",  "page=extras");
}


function ext_err($valid)
{
   $smarty     = &$valid->_smarty;
   $db         = $valid->_db;
   $fields     = &$valid->_fields;
   $config     = $valid->_msg;

   $smarty->config_load( FILE_CONFIG_DEFAULT, 'user_setup' );
   $config += $smarty->get_config_vars();

   $check = intense_pwd_check($db, $config, $fields, 2, 'pwd_old', 'pwd_new1', 'pwd_new2');

   if(is_array($check)){
      $valid->set_err(10, $check[0], '', $check[1]);
   }else
      $fields['pwd']['value'][0] = $check;
}


function ext_warn($valid)
{
}

?>