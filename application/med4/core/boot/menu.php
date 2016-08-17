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

$pagePrefix = reset(explode('.', $pageName));

//Menubar
if (in_array( substr($pageName, 0, strpos($pageName, '.')), array('list'))=== true) {
   if (isset($arr_menubar[$page]) === true) {
      $show = false;

      foreach ($arr_menubar[$page] as $setting) {
         if ($setting !== false) {
            $show = true;
            break;
         }
      }

      if ($show === true) {
         $smarty->assign('menubar', $arr_menubar[$page]);
      }
   }
}

$sidebar = false;

if (in_array( $pagePrefix, array('list', 'view')) === true || $pageName == 'rollenauswahl') {
   $searchbar = true;
   if ($pagePrefix == 'view') {
      $sidebar = true;
   }
} else {
   $searchbar = false;
}


if (in_array($pageName, array('rollenauswahl', 'login', 'user_setup'))) {
   $infobar = false;
} else {
   $infobar = true;

   $sessOrgName = isset($_SESSION['sess_org_name']) === true    ? $_SESSION['sess_org_name']    : null;
   $sessOrgOrt  = isset($_SESSION['sess_org_ort']) === true     ? $_SESSION['sess_org_ort']     : null;

   if ($sessOrgName !== null || $sessOrgOrt !== null) {
      $smarty->assign('infobar_org', concat(array($sessOrgName, $sessOrgOrt),', '));
   }
}

$bodyClass  = false;

//Body class
if ($infobar === false || (isset($overrideInfobar) === true && $overrideInfobar === true)) {
   $bodyClass  = 'body-wo-menubar';
   $infobar = false;
}

$smarty
   ->assign('bodyClass',    $bodyClass)
   ->assign('appMenuItems', menuManager::getMenuItems())
   ->assign('file_sidebar', "app/sidebar/sidebar.{$page}.tpl")
   ->assign('searchbar',    (isset($overrideSearchbar) === true ? $overrideSearchbar: $searchbar))
   ->assign('sidebar',      (isset($overrideSidebar) === true ? $overrideSidebar : $sidebar))
   ->assign('infobar',      $infobar)
;

?>