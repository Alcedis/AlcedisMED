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

$table = 'recht_erkrankung';

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $form_id  = isset($arr_sess['recht_erkrankung_id']) ? $arr_sess['recht_erkrankung_id'] : '';
}else{
   $form_id  = isset($_REQUEST['recht_erkrankung_id']) ? $_REQUEST['recht_erkrankung_id'] : '';
}

//schnittmenge von erkrankungen des systems mit denen des aktuellen users (nur für form, liste braucht alle!!!)
$rechtErkrankung = explode(',', (appSettings::get('erkrankungen') !== null ? appSettings::get('erkrankungen') : array()));

$availableErkrankungen = $rolle_code == 'admin'
    ? $rechtErkrankung
    : (isset($_SESSION['sess_recht_erkrankung']) ? $_SESSION['sess_recht_erkrankung'] : array())
;

$systemErkrankungen = implode("','", array_intersect($rechtErkrankung, $availableErkrankungen));

$fields['erkrankung']['ext'] .= " AND code IN ('$systemErkrankungen')";

$dontSave = false;

if (strlen($form_id) > 0 && $rolle_code != 'admin') {
   //Prüfen ob user auf die Org id des ausgewählten Datensatzes ein Recht hat

   $result = reset(sql_query_array($db, "
      SELECT
         r.recht_erkrankung_id
      FROM recht_erkrankung r
         INNER JOIN recht re ON re.recht_id = r.recht_id
            INNER JOIN recht rorg ON rorg.org_id = re.org_id AND rorg.user_id = '{$user_id}' AND rorg.rolle = 'supervisor'
      WHERE
         r.recht_erkrankung_id = '{$form_id}'
   "));

   if ($result === false) {
      $dontSave = true;
   }
}

/**
 * Ab hier keine Änderung im Normalfall
 */
switch( $action )
{
  case 'insert':
   ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', 'erkrankung', null); break;
  case 'update':
   ajax_action( $smarty, $db, $fields, $table, $form_id, $action, 'ext_err', 'ext_warn', $arr_sess['sess_typ'], $arr_sess['sess_id']); break;
  case 'delete':
   ajax_action( $smarty, $db, $fields, $table, null, $action, 'ext_err', 'ext_warn', null, null); break;
}

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );

$smarty->assign( 'button', get_ajax_buttons( $table, $dontSave) );

/**
 * Validator hier anpassen
 */
function ext_err( $valid )
{
}

function ext_warn( $valid )
{
}

?>