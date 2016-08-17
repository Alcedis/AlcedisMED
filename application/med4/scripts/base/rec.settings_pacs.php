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

$table    = 'settings_pacs';
$form_id  = isset($_REQUEST['settings_pacs_id']) ? $_REQUEST['settings_pacs_id'] : '';
$location = 'index.php?page=list.settings_pacs';

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button = get_buttons($page, $form_id);

show_record($smarty, $db, $fields, 'settings_pacs', $form_id, '');

$smarty
   ->assign('button', $button);


function ext_err( $valid )
{

}

?>