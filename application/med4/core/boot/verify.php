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

$verified = isset($verified) === true ? $verified : false;

if ($page !== 'login') {

    //verify browser-back function
    if (str_starts_with($pageName, 'rec.')) {   //rec only
        $formId = isset($_REQUEST[$page . '_id']) ? $_REQUEST[$page . '_id'] : '';

        //formId isset and no current form exists
        if (strlen($formId) > 0 && strlen(dlookup($db, $page, $page . '_id', $page . '_id' . " = '$formId'")) == 0) {

            $query = "SELECT * FROM _$page WHERE {$page}_id = '$formId' AND a_action = 'delete' ORDER BY a_{$page}_id DESC LIMIT 1";
            $data = sql_query_array($db, $query);

            //form existed
            if (count($data) === 1) {
                $params = '';
                foreach ($data[0] as $field => $value) {
                    if ($field === $page . '_id' || str_starts_with($field, 'a_')) {
                        continue;
                    } elseif (str_ends_with($field, '_id')) {
                        $tbl = str_replace('_id', '', $field);
                        $idCheck = dlookup($db, $tbl, $field, "$field = $value");

                        if (strlen($idCheck) == 0) {
                            redirectTo(get_url('page=rollenauswahl'), $config['msg_no_form_ever']);
                        }

                        $params .= "&{$field}={$value}";
                    } else {
                        break; //break on first non-id fields /to avoid mixup with regular id-fields
                    }
                }

                redirectTo(get_url("page=$pageName$params"), $config['msg_no_form']);
            //form never existed
            } else {
                redirectTo(get_url('page=rollenauswahl'), $config['msg_no_form_ever']);
            }
        }
    }

    //if id is in get-request check if corresponding form exists
    foreach ($_GET as $var => $val) {
        if (str_ends_with($var, '_id') && strlen($val) > 0) {
            $tbl = str_replace('_id', '', $var);
            if (relationManager::tableExists($tbl)) {
                $idCheck = dlookup($db, $tbl, $var, "{$var} = '{$val}'");

                if (strlen($idCheck) == 0) {
                    redirectTo(get_url('page=rollenauswahl'), $config['msg_no_form_ever']);
                }
            }
        }
    }

   //verified
   $verified = isset($_SESSION['sess_verified']) === true ? $_SESSION['sess_verified'] : $verified;


   $cancelLocation = null;

   if ($verified === false) {
      if ($page !== 'impressum') {
         if (isset($_SESSION) === true) {
            session_destroy();
         }

         $cancelLocation = "index.php?page=login&state=nopassed";
      }
   } else {
      //Special Case Konferenz Moderator
      if (appSettings::get('konferenz') !== true && isset($_SESSION['sess_rolle_code']) == true && $_SESSION['sess_rolle_code'] == 'moderator' &&
         $page != 'rollenauswahl'
      ) {
         $cancelLocation = "index.php?page=rollenauswahl";
      }

      if (in_array($page, array('rollenauswahl', 'user_setup')) === false && isset($_SESSION['sess_recht_id']) == false) {
         if (isset($permissionGranted) == false || $permissionGranted == false) {
            $cancelLocation = "index.php?page=rollenauswahl";
         }
      }
   }

   if ($cancelLocation !== null) {
      if ($ajax === true || $bfl !== null || $codepicker === true) {
         echo json_encode(array('session_expired' => 'true'));
         exit;
      } else {
         action_cancel($cancelLocation);
      }
   }

   if (isset($_REQUEST['nopageright']) === true) {
      $smarty->assign('message', $config['msg_nopageright']);
   }
} else {
   if (isset($_REQUEST['state']) === true && $_REQUEST['state'] == 'logout') {
      //Hier unbedingt alle relevanten parameter schon früher abschalten

      unset($_SESSION['sess_rolle_code']);
   }
}

?>