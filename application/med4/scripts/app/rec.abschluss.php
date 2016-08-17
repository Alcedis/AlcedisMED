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

$table            = 'abschluss';
$abschluss_id     = dlookup($db, 'abschluss', "abschluss_id", "patient_id='$patient_id'");
$form_id          = strlen($abschluss_id) ? $abschluss_id : (isset( $_REQUEST['abschluss_id'] ) ? $_REQUEST['abschluss_id'] : '');
$location         = get_url('page=view.patient') . "&patient_id=$patient_id";

if ($permission->action($action) === true) {
   $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
   require($permission->getActionFilePath());
}

//DLIST
if ($ajax === true) {
    if (isset($_REQUEST['show_dlist']) === true) {
        switch($_REQUEST['show_dlist']){
            case 'ursache':
                $abschlussUrsacheFields = $widget->loadExtFields('fields/app/abschluss_ursache.php');
                $query = "SELECT * FROM abschluss_ursache WHERE abschluss_id='{$form_id}' ORDER BY krankheit";

                echo create_json_string(load_pos_sess($db, 'abschluss_ursache', $query, 'ursache', $abschlussUrsacheFields, $config));
                exit;

                break;
        }
    }
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty->assign('button', $button);

if (isset($backPage) == false) {
   $smarty->assign('back_btn', "page=view.patient&amp;patient_id=$patient_id");
}

function ext_err($valid) {

   //eCheck 2,3,4,5
   $valid->condition_and('$abschluss_grund != "tot"', array(
       '!todesdatum && !tod_ursache && !tod_tumorassoziation && !autopsie && !tod_ursache_dauer && !ursache_quelle'
   ));
   // eCheck 6
   $valid->condition_and('$abschluss_grund == "lost"', array( 'letzter_kontakt_datum' ));

   //eCheck 2b
   $valid->condition_and('$abschluss_grund == "tot"', array('todesdatum'));


}

?>
