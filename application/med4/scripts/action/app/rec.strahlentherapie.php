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
   case 'insert':
       $_REQUEST['vorlage_therapie_art'] = isset($_REQUEST['vorlage_therapie_id']) && strlen($_REQUEST['vorlage_therapie_id']) ?
            dlookup($db, 'vorlage_therapie', 'art', "vorlage_therapie_id = '$_REQUEST[vorlage_therapie_id]'") : '';

       action_insert( $smarty, $db, $fields, $table, $action, $location, 'ext_err');

       break;

   case 'update':

      $_REQUEST['vorlage_therapie_art'] = isset($_REQUEST['vorlage_therapie_id']) && strlen($_REQUEST['vorlage_therapie_id']) ?
            dlookup($db, 'vorlage_therapie', 'art', "vorlage_therapie_id = '$_REQUEST[vorlage_therapie_id]'") : '';

      action_update( $smarty, $db, $fields, $table, $form_id, $action, $location, 'ext_err');

      break;

   case 'delete':

      deleteReference($db, 'nebenwirkung', $page, $form_id);

      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location );

      break;

   case 'cancel': action_cancel( $location );                                                   break;
}

?>