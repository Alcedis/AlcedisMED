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

/**
 * Validate Password - erzeugt eine gewisse Komplexität im Password (in der settings.server.conf konfigurierbar)
 */

function intense_pwd_check($db, $config, $fields, $type, $old_pw, $new_pw1 = '', $new_pw2 = ''){
   //Default settings
   $min_char    = 6;
   $error       = '';
   $user_id     = $fields['user_id']['value'][0];

   //Config min characters
   if(isset($config['min_pwd_char']) AND $config['min_pwd_char'] != 0)
      $min_char = $config['min_pwd_char'];

   //Get labels
   $lbl_pwd_old_wrong = $config['lbl_pwd_old_wrong'];
   $lbl_pwd_new_wrong = $config['lbl_pwd_new_wrong'];
   $lbl_pwd_not_all   = $config['lbl_pwd_error'];
   $lbl_pwd_same      = $config['lbl_pwd_same'];

   //get current pwd (DB md5 sum)
   $current_pw = dlookup($db, 'user', 'pwd', "user_id = '$user_id'");

   //get current pwd (FORM)
   #if($type == 2){
   if($type == 2){
      $form_old_pw    = md5( $fields[$old_pw]['value'][0]);
      $form_new_pw_1  = md5( $fields[$new_pw1]['value'][0]);
      $form_new_pw_2  = md5( $fields[$new_pw2]['value'][0]);
      $form_raw_pw    = stripslashes(htmlspecialchars_decode($fields[$new_pw1]['value'][0]));
   }else{
      $form_raw_pw    = stripslashes(htmlspecialchars_decode($fields[$old_pw]['value'][0]));

      // Wenn das derzeitige Form passwort == dem Datenbankpasswort, dann nichts machen
      if($current_pw == $form_raw_pw){
         $check_req     = false;
         $form_old_pw   = $fields[$old_pw]['value'][0];
      }else{   //wenn nicht gleich, dann wurde geändert
         $check_req     = true;
         $form_old_pw   = md5($fields[$old_pw]['value'][0]);
      }
   }

   //Prüfung ob das eingegebene Passwort, dem in der Datenbank entspricht
   if($type == 2){
      if( $form_old_pw != $current_pw ){
         $error = array( array($old_pw), $lbl_pwd_old_wrong );
         return $error;
      }
   }

#######CASE 2#######
   if( $type == 2 ){
      if( $form_new_pw_1 != $form_new_pw_2 ){
         $error = array( array($new_pw1,$new_pw2), $lbl_pwd_new_wrong );
         return $error;
      }
   }

#######Check#########
   #######Length########
      if($type == 2){
         $laenge = strlen($form_raw_pw);
      }else{
         $laenge = strlen($form_raw_pw);
      }

      if($laenge < $min_char){
         $error .= sprintf($config['lbl_pwd_min_zeichen'], $min_char);
      }

   #######special chars########
   if(isset($config['min_pwd_spc_char']) AND $config['min_pwd_spc_char'] != 0){
      preg_match_all('/[^0-9a-zA-Z]/', $form_raw_pw, $zeichen);

      if(count($zeichen[0]) < $config['min_pwd_spc_char']){
         $error .= sprintf($config['lbl_pwd_min_sonderz'], $config['min_pwd_spc_char']);
      }
   }

   if(strlen($error)){
    if($type == 2){
            $error = array( array($new_pw1,$new_pw2), $lbl_pwd_not_all.$error );
            return $error;
    }elseif($check_req == true){
            $error = array( array($old_pw), $lbl_pwd_not_all.$error );
            return $error;
    }
   }

#######Alles OK########
   if( $type == 2 ){
      return $form_new_pw_1;
   }else
      return $form_old_pw;
}

?>