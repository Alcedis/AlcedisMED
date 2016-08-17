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

$table    = 'hl7_log_cache';
$form_id  = isset( $_REQUEST['hl7_log_id'] ) ? $_REQUEST['hl7_log_id'] : '';
//$location = get_url('page=view.erkrankung') . "&erkrankung_id=$erkrankung_id";

$button = get_buttons ($table, $form_id, $statusLocked);

show_record($smarty, $db, $fields, $table, $form_id);

/*
$hl7Parser = hl7Parser::create()
    ->parseMsg(dlookup($db, 'hl7_log_cache', 'msg', "hl7_log_id = '{$form_id}'"))
    ->setActiveMsg(0)
;

$hl7Fields = $hl7Base->getHl7FieldSettings();

$status = reset(json_decode(dlookup($db, 'hl7_log_cache', 'filter', "hl7_log_id = '{$form_id}'"), true));

print_arr($status);
 * */



$smarty->assign( 'button',  $button );

$smarty
   ->assign('back_btn', 'page=list.log_cache&feature=hl7')
;

?>
