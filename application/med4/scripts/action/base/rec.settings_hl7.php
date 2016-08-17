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

switch( $action )
{
   case 'update':

      $no_error  = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', 'ext_err');

      if ($no_error) {
         $fields = $widget->loadExtFields('fields/base/settings_hl7field.php');

         insert_sess_db($smarty, $db, $fields, 'settings_hl7field', $form_id, 'hl7field', 'settings_hl7field_id', 'settings_hl7_id');

         action_cancel($location);
      }

      break;

   case 'show':   action_cancel('index.php?page=preview&feature=hl7'); break;
   case 'cancel': action_cancel('index.php?page=rec.settings'); break;
}

?>
