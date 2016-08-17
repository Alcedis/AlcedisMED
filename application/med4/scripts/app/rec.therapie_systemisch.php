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

$table        = 'therapie_systemisch';
$form_id     = isset( $_REQUEST['therapie_systemisch_id'] ) ? $_REQUEST['therapie_systemisch_id'] : '';
$location    = get_url('page=view.erkrankung');

$fields['vorlage_therapie_id']['ext'] .= "AND erkrankung IN( '$erkrankung', 'all') ORDER BY bez ASC";

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

$button = get_buttons ( $table, $form_id, $statusLocked );

show_record( $smarty, $db, $fields, $table, $form_id, null, 'ext_warn');

$smarty->assign( 'button',  $button );


/**
 * ext_warn
 *
 * @param   validator   $valid
 * @return  void
 */
function ext_warn($valid) {
    $smarty         = $valid->_smarty;
    $config         = $smarty->get_config_vars();
    $db              = $valid->_db;
    $fields         = $valid->_fields;
    $erkrankungId = reset($fields['erkrankung_id']['value']);
    $erkrankung    = dlookup($db, 'erkrankung', 'erkrankung', "erkrankung_id = '{$erkrankungId}'");

    //eCheck 2
    $valid->condition_and('$therapieplan_id == ""', array('therapieplan_id'), $config['msg_therapieplan_id'], true);

    //eCheck 11
     if ($erkrankung === 'd') {
          $valid->condition_and('$metastasentherapie == ""', array('metastasentherapie'), $config['warn_fill_one'], true);
     }
}


/**
 * ext_err
 *
 * @param   validator $valid
 * @return  void
 */
function ext_err($valid) {

    //eCheck 3
    $valid->condition_and('$studie != "1"', array('!studie_id'));

    //eCheck 4
    $valid->condition_and('in_array($endstatus, array("plan", "")) == true', array('!endstatus_grund'));

    //eCheck 5
    $valid->condition_and('$best_response == ""', array('!best_response_datum'));

    //eCheck 6
    $valid->condition_and('in_array($dosisaenderung, array("", "0")) == true', array('!dosisaenderung_grund && !dosisaenderung_grund_sonst'));

    //eCheck 7
    $valid->condition_and('$dosisaenderung_grund != "son"', array('!dosisaenderung_grund_sonst'));

    //eCheck 8
    $valid->condition_and('in_array($unterbrechung, array("", "0")) == true', array('!unterbrechung_grund && !unterbrechung_grund_sonst'));

    //eCheck 9
    $valid->condition_and('$unterbrechung_grund != "son"', array('!unterbrechung_grund_sonst'));

    //eCheck 10
    $valid->condition_and('in_array($regelmaessig, array("", "1")) == true', array('!regelmaessig_grund'));

    //eCheck 11
    $valid->condition_and('strlen($ende) > 0', array('!andauernd'));
    $valid->condition_and('$andauernd == "1"', array('!ende'));
}

?>
