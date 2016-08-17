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

$table      = 'konferenz';
$form_id    = isset($_REQUEST['konferenz_id'] ) ? $_REQUEST['konferenz_id'] : '';
$location   = get_url('page=list.konferenz');

//Bei Insert und Rolle Moderator
if (strlen($form_id) == 0 && $rolle_code == 'moderator') {
    $_REQUEST['moderator_id'] = $user_id;
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

//Initial Name der Konferenz
if ($action === null && isset($_SESSION['sess_konferenz_name']) === false && strlen($form_id) > 0) {
   $_SESSION['sess_konferenz_name'] = dlookup($db, 'konferenz', 'bez', "konferenz_id = '{$form_id}'");
}

//Restrict Delete Button if min one konferenz_patient exists
$restrict = dlookup($db, 'konferenz_patient', 'COUNT(konferenz_patient_id)', "konferenz_id = '{$form_id}'") > 0
    ? 'D'
    : null
;

$button = get_buttons ( $table, $form_id, null, false, $restrict);

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn');

if (strlen($form_id)) {

   $final = dlookup($db, 'konferenz', 'final', "konferenz_id = '$form_id'");

   if ($final == 1) {
      if ($rolle_code == 'supervisor') {
         $button = 'reopen[btnconfirm].cancel';
      } else {
         $button = 'cancel';
      }

      $smarty->assign('final', true);
   }
}

$smarty
   ->assign('back_btn', 'page=list.konferenz')
   ->assign('button', $button);

function ext_err($valid)
{
   $fields = &$valid->_fields;
   $smarty = $valid->_smarty;
   $db     = $valid->_db;

   $beginn  = str_replace(':', '', reset($fields['uhrzeit_beginn']['value']));
   $ende    = str_replace(':', '', reset($fields['uhrzeit_ende']['value']));
   $final   = reset($fields['final']['value']);

   $konferenzId = reset($fields['konferenz_id']['value']);

   //eCheck 3
   if (strlen($beginn) && strlen($ende) && ($ende <= $beginn)) {
      $valid->set_err(12, 'uhrzeit_ende', null);
   }

   //eCheck 4
   if ($final == 1) {
      $config  = $smarty->get_config_vars();

      $setFinal = true;

      if (strlen($konferenzId) == 0) {
         $setFinal = false;
      } else {

         $patientCount     = dlookup($db, 'konferenz_patient', "COUNT(konferenz_patient_id)", "konferenz_id = '$konferenzId'");
         $teilnehmerCount  = dlookup($db, 'konferenz', "teilnehmer", "konferenz_id = '$konferenzId'");

         if ($patientCount == 0 || $teilnehmerCount == 0) {
            $setFinal = false;
         }
      }

      if ($setFinal == false) {
         $valid->set_err(12, 'final', null, $config['msg_final']);
      }
   }
}

/**
 *
 * @param validator $valid
 */
function ext_warn($valid)
{
    $fields = $valid->_fields;
    $smarty = $valid->_smarty;
    $db     = $valid->_db;

    $moderatorId = reset($fields['moderator_id']['value']);

    $config  = $smarty->get_config_vars();

    if (strlen($moderatorId) > 0 && strlen(dlookup($db, 'user', 'email', "user_id = '{$moderatorId}'")) == 0) {
        $valid->set_warn(10, 'moderator_id', null, $config['msg_no_moderator_email']);
    } elseif (strlen($moderatorId) == 0) {
        $valid->set_warn(10, 'moderator_id', null, $config['msg_no_moderator']);

    }
}


?>