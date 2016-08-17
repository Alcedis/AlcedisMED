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

//If Codepicker is requested
if ($codepicker === true)
{
   header('Content-Type: text/html; charset=iso-8859-1');

   require_once('core/functions/codepicker.php');

   $smarty->config_load('base/codepicker.conf');
   $config  = $smarty->get_config_vars();

   if (isset($_REQUEST['txtfield']) === true) {
      $smarty->assign('txtfield', $_REQUEST['txtfield']);
   }

   if (isset($_REQUEST['parentform']) === true) {
      $smarty->assign('parentform', $_REQUEST['parentform']);
   }

   if (isset($_REQUEST['type']) === true) {
      $smarty->assign('codepickertype', $_REQUEST['type']);
   }

   $history = (isset($_REQUEST['history']) == true && $_REQUEST['history'] == 1);

   $smarty->assign('history', $history);

   $subdir = 'codepicker/';
   $codepicker = true;
    $permissionGranted = true;
}

?>
