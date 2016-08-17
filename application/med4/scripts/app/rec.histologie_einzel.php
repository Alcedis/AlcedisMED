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

$table    = $tbl_histologie_einzel;

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $form_id  = isset($arr_sess['histologie_einzel_id']) ? $arr_sess['histologie_einzel_id'] : '';
}else{
   $form_id  = isset($_REQUEST['histologie_einzel_id']) ? $_REQUEST['histologie_einzel_id'] : '';
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );

$smarty->assign( 'button', get_ajax_buttons( $table) );

/**
 * Validator hier anpassen
 */
function ext_err( $valid )
{
   //eCheck 10
   $valid->condition_and('$unauffaellig != ""', array('!morphologie && !morphologie_text'));

}

function ext_warn( $valid )
{
}

?>