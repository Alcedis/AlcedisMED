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

$table      = 'recht';
$form_id    = isset($_REQUEST["recht_id"]) === true ? $_REQUEST["recht_id"] : "";
$location   = get_url("page=list.recht");
$restrict   = null;

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $rechtErkrankungFields = $widget->loadExtFields('fields/base/recht_erkrankung.php');

      $query = "SELECT * FROM $tbl_recht_erkrankung WHERE recht_id='$form_id' ORDER BY erkrankung";

      echo create_json_string(load_pos_sess($db, $tbl_recht_erkrankung, $query, 'erkrankung', $rechtErkrankungFields, $config));
      exit;
   }
}

$button = get_buttons($table, $form_id, null, false, $restrict);

//Inaktive User entfernen... Spezialfall der nur ein enziges mal im System vorkommt
$fields['user_id']['ext'] = str_replace('WHERE', 'WHERE user.inaktiv IS NULL AND', $fields['user_id']['ext']);

show_record($smarty, $db, $fields, "recht", $form_id);

$smarty
   ->assign('role_recht_global', (isset($_SESSION['sess_recht_global']) === true ? $_SESSION['sess_recht_global'] : (in_array($user_id, explode(',', ADMIN)))))
   ->assign('recht_recht_global', dlookup($db, 'recht', 'recht_global', "recht_id = '{$form_id}'"))
   ->assign("recht_id", $form_id)
   ->assign("button",   $button)
   ->assign('restrictOrg', ($rolle_code != 'admin' ? $org_id : NULL))
   ->assign('back_btn', 'page=list.recht');


function ext_err($valid) {

   $smarty     = &$valid->_smarty;
   $fields     = &$valid->_fields;
   $config     = $valid->_msg;

   $role = reset($fields['rolle']['value']);

   //eCheck 1
   if (reset($fields['recht_global']['value']) != 1 && $role != 'admin') {
      if (isset($_SESSION['pos_table']['erkrankung']) == false || count($_SESSION['pos_table']['erkrankung']) == 0) {
         $valid->set_err(12, 'recht_global', null, $config['msg_mind']);
      }
   }

   //eCheck 2
   if (reset($fields['behandler']['value']) == 1 && $role == 'moderator') {
        $valid->set_err(12, 'behandler', null, $config['msg_behandler']);
   }
}

?>