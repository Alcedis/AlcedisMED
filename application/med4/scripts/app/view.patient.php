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

if ($rolle_code == 'moderator') {
    action_cancel('index.php?page=list.zweitmeinung');
}

$smarty
   ->assign('back_btn', "page=list.patient")
;

$fotoCount     = dlookup($db, 'foto', 'COUNT(foto_id)', "patient_id = '{$patient_id}'");
$dokumentCount = dlookup($db, 'dokument', 'COUNT(dokument_id)', "patient_id = '{$patient_id}'");

if ($ajax === true) {
   $requestedPage = isset($_REQUEST['tpl_page']) == true && in_array($_REQUEST['tpl_page'], array('table', 'foto', 'dokument')) === true
        ? $_REQUEST['tpl_page']
        : 'table'
   ;

   switch ($requestedPage) {
       case 'table':

           $pageName = "view.patient_table";

           break;

       case 'foto' :

           $pageName = $fotoCount > 0 ? 'view.patient_foto' : "view.patient_table";

           break;

       case 'dokument' :

           $pageName = $dokumentCount > 0 ? 'view.patient_dokument' : "view.patient_table";

           break;
   }

   require_once("scripts/app/{$pageName}.php");

} else {
   if ($rolle_code === 'dateneingabe') {
       $smarty->assign('viewOrg', true);
   }

   $smarty
      ->assign('fotoExists', $fotoCount > 0)
   ;

   if (appSettings::get('dokument') === true) {
       $smarty
           ->assign('dokumentExists', $dokumentCount > 0)
       ;
   }
}

?>