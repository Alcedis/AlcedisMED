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

if (isset($_SESSION['sess_error']) === true) {
   $smarty->assign( 'error', $_SESSION['sess_error']);

   unset( $_SESSION['sess_error'] );
}


if (isset($_SESSION['sess_warnung']) === true OR isset($_SESSION['sess_warn']) === true ) {

   $warn    = isset( $_SESSION['sess_warn'] )      ? $_SESSION['sess_warn']    : array();
   $warnung = isset( $_SESSION['sess_warnung'] )   ? $_SESSION['sess_warnung'] : array();

   $smarty->assign( 'warn', array_merge($warn, $warnung));

   unset( $_SESSION['sess_warnung'] );
   unset( $_SESSION['sess_warn'] );

}

if (isset($_SESSION['sess_info']) === true ) {

   $info = $_SESSION['sess_info'];

   if (is_array($info) === false) {
      $info = array($info);
   }

   $smarty->assign('message', $info );

   unset($_SESSION['sess_info']);
}


if (isset($_SESSION['sess_feature_warn']) == true) {
   $smarty->assign('featureWarn', $_SESSION['sess_feature_warn']);

   unset($_SESSION['sess_feature_warn']);
}

/** -------------------------------------------------------------------------------------------
 ** PHP File ermitteln
 **/

//Default page
$script = null;

foreach ($appOrder as $app) {
   $folder = $app['folder'];

   $scriptFilePath = "scripts/{$folder}/{$subdir}{$pageName}.php";

   if (file_exists($scriptFilePath) === true ){
      $script = $scriptFilePath;
   }
}

//Feature
if ($script == null && $featureLoad['scripts'] !== null) {
    $script = $featureLoad['scripts'];
}

// script file must exists
if ($script !== null && file_exists($script) === true) {
   include($script);
}

?>
