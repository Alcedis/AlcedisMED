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

//Wichtig wird neben ajax request auch während des Speicherns eines Hauptformulars benötigt!!!!
require_once('core/ajax/_loader.php');

//If Ajax is activated
if ($ajax === true) {

   header('Content-Type: text/html; charset=iso-8859-1');

   $deleteDialog = false;
   $confirmDialog = false;

   if (isset($_REQUEST['list_template']) === true) {
      $smarty->config_load($configPath, 'dlist');
      $config = $smarty->get_config_vars();

      $requestFormId = isset($_REQUEST[$page . '_id']) ? $_REQUEST[$page . '_id'] : null;
      $statusLock    = dlookup($db, 'status', 'status_lock', "form = '$page' AND form_id = '$requestFormId' AND patient_id = '$patient_id'") == 1 ? true : false;

      load_template($db, $appOrder, $_REQUEST['page'], $config, $statusLock);
      exit;
   }

   if (isset($_REQUEST['deletedialog']) === true) {
      $deleteDialog = true;
       $permissionGranted = true;
   }

   if (isset($_REQUEST['confirmdialog']) === true) {
       $confirmDialog = true;
       $permissionGranted = true;
   }

   //Status des Hauptformulars
   if (in_array($page, $dlist_tables) === true) {
      $parentFormId     = null;

      $firstField = reset($fields);

      if (array_key_exists('dlistParent', $firstField) == true) {
          $parentFormName = $firstField['dlistParent'];
      } else {
          $parentFormName = reset(explode('_', reset(array_keys($fields))));
      }

      if (isset($_REQUEST[$parentFormName . '_id']) === true) {
         $parentFormId = $_REQUEST[$parentFormName . '_id'];
      } elseif (isset($_REQUEST['sess_pos']) === true) {
         $ajaxDataset = edit_pos($smarty);
         $parentFormId = $ajaxDataset[$parentFormName . '_id'];
      }

      if ($parentFormId !== null) {
         $statusLocked = dlookup($db, 'status', 'status_lock', "form = '$parentFormName' AND form_id = '$parentFormId' AND patient_id = '$patient_id'");
      }
   }

} else {
   //Wenn kein Ajax und keine Action (Validate, insert oder update)
   if (isset($_REQUEST['action']) === false && isset($_SESSION['pos_table']) === true && in_array($page, $picker_tables) === false && $featureLoaded === false) {
      //AJAX POS Table löschen
      unset($_SESSION['pos_table']);
   }
}

?>
