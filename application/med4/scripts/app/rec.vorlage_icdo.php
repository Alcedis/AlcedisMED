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

$table    = 'vorlage_icdo';
$form_id  = isset( $_REQUEST['vorlage_icdo_id'] ) ? $_REQUEST['vorlage_icdo_id'] : '';
$location = get_url('page=list.vorlage_icdo');

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button   = get_buttons ( $table, $form_id );

show_record( $smarty, $db, $fields, $table, $form_id);

$smarty
   ->assign( 'button',  $button )
   ->assign('back_btn',  'page=list.vorlage_icdo');


function ext_err($valid) {

   $smarty = $valid->_smarty;
   $fields = $valid->_fields;
   $config = $smarty->get_config_vars();

   $icdoCode = reset($fields['code']['value']);

   //Eingabeformat "nnnn/n"; n = Ziffer 0...9
   if (preg_match("/^\d{4}\/\d{1}/", $icdoCode) == 0) {
      $valid->set_err(12, 'code', null, $config['msg_code']);
   }
}


?>