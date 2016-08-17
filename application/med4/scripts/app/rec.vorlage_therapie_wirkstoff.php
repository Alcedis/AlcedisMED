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

$buttons = 'cancel';
$table   = 'vorlage_therapie_wirkstoff';

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $art      = $arr_sess['art'];
   $form_id  = isset($arr_sess['vorlage_therapie_wirkstoff_id']) ? $arr_sess['vorlage_therapie_wirkstoff_id'] : '';
}else{
   $form_id  = isset($_REQUEST['vorlage_therapie_wirkstoff_id']) ? $_REQUEST['vorlage_therapie_wirkstoff_id'] : '';
   $art = isset($_REQUEST['preselected_value']) === true ? $_REQUEST['preselected_value'] :
            ( isset($_REQUEST['art']) === true ? $_REQUEST['art'] : '');
}

$_REQUEST['art'] = $art;

if ($art == 'str') {
    $tmpConfig = $smarty->get_config_vars();

    $tmpConfig['wirkstoff'] = $tmpConfig['lbl_wirkstoff_str'];

    $smarty->set_config($tmpConfig);
}


if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if ($form_id) {
   $query ="
      SELECT
         vt.freigabe
      FROM vorlage_therapie_wirkstoff vtw
         LEFT JOIN vorlage_therapie vt ON vt.vorlage_therapie_id = vtw.vorlage_therapie_id
      WHERE vtw.vorlage_therapie_wirkstoff_id = '$form_id'
   ";

   $result = reset(sql_query_array($db, $query));

   if ($result['freigabe'] != 1) {
      $buttons = get_ajax_buttons($table);
   }
} else {
   $buttons = get_ajax_buttons($table);
}

//Wirkstoff Dropdown
$kennung = $fields['wirkstoff']['ext'];
$kennung = $art === 'str' ? $kennung . " AND kennung = 'str'"  : $kennung . " AND (kennung != 'str' OR kennung IS NULL)";
$fields['wirkstoff']['ext'] = $kennung . ' ORDER BY pos';

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );

$smarty->assign( 'button', $buttons );
$smarty->assign('art', $art);

/**
 * Validator hier anpassen
 */
function ext_err( $valid )
{
   $fields = &$valid->_fields;

   //eCheck 4
   $valid->condition_and('$dosis !== ""', array('einheit', '!einheit'));

   //eCheck 5
   $valid->condition_and('$loesungsmittel == ""', array('!loesungsmittel_menge'));

   //eCheck 6 / 7
   $valid->condition_and('$infusionsdauer !== ""', array('infusionsdauer_einheit', '!infusionsdauer_einheit'));

   //eCheck 8 / 9
   $valid->condition_and('$applikationsfrequenz !== ""', array('applikationsfrequenz_einheit', '!applikationsfrequenz_einheit'));

   //eCheck 10 / 11
   $valid->condition_and('$therapiedauer !== ""', array('therapiedauer_einheit', '!therapiedauer_einheit'));

   //FULL ukey check
   $valid->fields_ukey($fields, true);
}

function ext_warn( $valid )
{
}

?>