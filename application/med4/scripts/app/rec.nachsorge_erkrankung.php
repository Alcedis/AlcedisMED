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

$table      = 'nachsorge_erkrankung';

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $form_id  = isset($arr_sess['nachsorge_erkrankung_id']) ? $arr_sess['nachsorge_erkrankung_id'] : '';
}else{
   $form_id  = isset($_REQUEST['nachsorge_erkrankung_id']) ? $_REQUEST['nachsorge_erkrankung_id'] : '';
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$fields['erkrankung_weitere_id']['ext'] .= " AND erkrankung_id != $erkrankung_id";

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );

$smarty->assign( 'button', get_ajax_buttons( $table) );

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