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

$table       = 'strahlentherapie';
$form_id     = isset($_REQUEST['strahlentherapie_id']) ? $_REQUEST['strahlentherapie_id'] : '';
$location    = get_url('page=view.erkrankung');

$fields['vorlage_therapie_id']['ext'] .= "AND erkrankung IN('{$erkrankung}', 'all')";

if ($permission->action($action) === true) {
    $location = isset($_REQUEST['origin']) ? get_url($backPage) : $location;
    require($permission->getActionFilePath());
}

$button = get_buttons($table, $form_id, $statusLocked);

show_record($smarty, $db, $fields, $table, $form_id);

$smarty->assign('button',  $button);


/**
 * ext_err
 *
 * @param   validator   $valid
 * @return  void
 */
function ext_err($valid) {
    //eCheck 3
    $valid->condition_and('$ziel_sonst != "1"', array('!ziel_sonst_detail && !ziel_sonst_detail_seite && !ziel_sonst_detail_text'));

    //eCheck 4
    $valid->condition_and('in_array($boost, array("", "0")) == true', array('!boostdosis'));

    //eCheck 5
    $valid->condition_and('$seed_strahlung_90d == ""', array('!seed_strahlung_90d_datum'));

    //eCheck 6
    $valid->condition_and('in_array($endstatus, array("", "plan")) == true', array('!endstatus_grund'));

    //eCheck 7
    $valid->condition_and('$best_response == ""', array('!best_response_datum'));

    //eCheck 8
    $valid->condition_and('in_array($dosisreduktion, array("", "0")) == true', array('!dosisreduktion_grund && !dosisreduktion_grund_sonst'));

    //eCheck 9
    $valid->condition_and('$dosisreduktion_grund != "son"', array('!dosisreduktion_grund_sonst'));

    //eCheck 10
    $valid->condition_and('in_array($unterbrechung, array("", "0")) == true', array('!unterbrechung_grund && !unterbrechung_grund_sonst'));

    //eCheck 11
    $valid->condition_and('$unterbrechung_grund != "son"', array('!unterbrechung_grund_sonst'));

    //eCheck 12
    $valid->condition_and('strlen($ende) > 0', array('!andauernd'));
    $valid->condition_and('$andauernd == "1"', array('!ende'));
}

?>
