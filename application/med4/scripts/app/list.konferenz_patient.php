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

unset($_SESSION['sess_patient_data']);

$konferenzId = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : null;
$location    = get_url("page=list.konferenz_patient&konferenz_id=$konferenzId");

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if (strlen($konferenzId)) {
   //Initial Name der Konferenz
   if ($action === null && isset($_SESSION['sess_konferenz_name']) === false) {
      $_SESSION['sess_konferenz_name'] = dlookup($db, 'konferenz', 'bez', "konferenz_id = '{$konferenzId}'");
   }

   $fields['removelink']            = array( 'type' => 'string' );
   $fields['therapieplan_id']       = array( 'type' => 'string' );
   $fields['name']                  = array( 'type' => 'string' );
   $fields['geburtsdatum']          = array( 'type' => 'date' );
   $fields['org']                   = array( 'type' => 'string' );
   $fields['erkrankung']            = array( 'type' => 'string' );
   $fields['datenstand_datum']      = array( 'type' => 'string' );
   $fields['datenstand_uhrzeit']    = array( 'type' => 'string' );

   $where   = "WHERE k_pat.konferenz_id = {$konferenzId}";
   $sql     = get_sql('list', $querys['konferenz_patient'], $where, "ORDER BY patient.nachname ASC, patient.vorname ASC", $limit );

   $backupFields = $fields;

   data2list( $db, $fields, $sql);

   if (in_array($rolle_code, array('moderator', 'konferenzteilnehmer')) === true) {
      //Final
      if (dlookup($db, 'konferenz', 'final', "konferenz_id='{$konferenzId}'") == 1) {
         $smarty->assign('final', true);
      } elseif ($rolle_code == 'moderator') {
          $arr_menubar['konferenz_patient']['custom'][] = "<a href='index.php?page=list.konferenz_patient_zuweisen&amp;konferenz_id={$konferenzId}' class='button'>{$config['btn_lbl_insert']}</a>";
      }

      $link_param = "&amp;konferenz_id={$konferenzId}";

      require_once 'scripts/app/list.konferenz_patient_therapieplan_check.php';

      if (isset($fields['konferenz_patient_id']['value']) === true && count($fields['konferenz_patient_id']['value']) > 0) {
         foreach ($fields['konferenz_patient_id']['value'] as $index => $field) {
            $fields['removelink']['value'][$index] = "'index.php?page=list.konferenz_patient&amp;konferenz_patient_id={$field}{$link_param}&amp;action=remove'";
         }
      }

      $smarty
         ->assign('link_param', $link_param)
      ;
   }
}

$smarty
   ->assign('back_btn', "page=list.konferenz")
   ->assign('conference', '<br/>' . dlookup($db, 'konferenz', 'CONCAT_WS(" - ", DATE_FORMAT(datum, "%d.%m.%Y"), bez)', "konferenz_id = '{$konferenzId}'"))
   ->assign('caption', $config['caption_dokumentar'])
;

?>
