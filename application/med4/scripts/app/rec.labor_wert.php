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

$table      = 'labor_wert';

if(isset($_REQUEST['sess_pos']) && $_REQUEST['sess_pos'] != ''){
   $arr_sess = edit_pos($smarty);
   $form_id  = isset($arr_sess['labor_wert_id']) ? $arr_sess['labor_wert_id'] : '';
}else{
   $form_id  = isset($_REQUEST['labor_wert_id']) ? $_REQUEST['labor_wert_id'] : '';
}

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

  //Fake Parameter zur Darstellung im Formular
$fields['parameter'] = array( 'req'    => 0,
                              'size'   => '',
                              'maxlen' => '',
                              'type'   => 'lookup',
                              'ext'    => array('l_basic' => 'laborparameter'));

$query = "SELECT
               lw.*,
               vlw.parameter as parameter
            FROM $tbl_labor_wert lw
               LEFT JOIN $tbl_vorlage_labor_wert vlw ON lw.vorlage_labor_wert_id = vlw.vorlage_labor_wert_id
            WHERE labor_wert_id='$form_id'
            ORDER BY parameter";

show_record( $smarty, $db, $fields, $table, $form_id, $query, '' );

$smarty->assign('button', get_ajax_buttons( $table) );

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