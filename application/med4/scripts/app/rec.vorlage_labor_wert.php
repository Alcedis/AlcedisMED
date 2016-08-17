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
$table   = 'vorlage_labor_wert';

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $form_id  = isset($arr_sess['vorlage_labor_wert_id']) ? $arr_sess['vorlage_labor_wert_id'] : '';
}else{
   $form_id  = isset($_REQUEST['vorlage_labor_wert_id']) ? $_REQUEST['vorlage_labor_wert_id'] : '';
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if ($form_id) {
   $query ="
      SELECT
         vl.freigabe
      FROM vorlage_labor_wert vlw
         LEFT JOIN vorlage_labor vl ON vl.vorlage_labor_id = vlw.vorlage_labor_id
      WHERE vlw.vorlage_labor_wert_id = '$form_id'
   ";

   $result = reset(sql_query_array($db, $query));

   if ($result['freigabe'] != 1) {
      $buttons = get_ajax_buttons($table);
   }
} else {
   $buttons = get_ajax_buttons($table);
}

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );

$smarty->assign( 'button', $buttons);

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