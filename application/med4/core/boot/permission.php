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

$permissionGranted = isset($permissionGranted) ? $permissionGranted : false;
$actionFile = null;

foreach ($appOrder as $app) {
   $folder = $app['folder'];

   $actionFilePath = "scripts/action/{$folder}/{$subdir}{$pageName}.php";

   if (file_exists($actionFilePath) === true ) {
      $actionFile = $actionFilePath;
   }
}

if ($actionFile === null) {
   $actionFile = "scripts/action/base/default.php";
}

$matrix     = isset($_SESSION['sess_permission_matrix']) ? $_SESSION['sess_permission_matrix'] : null;
$permission = permission::create($page, $matrix, $actionFile, $org_id);

$smarty->assign('alcPermission', $permission);

$patientRestriction = false;

//Patient ID Permission check
if ($patient_id !== null && isset($_SESSION['sess_recht_erkrankung']) && (in_array($page, $erkrankung_tables) === true || in_array($page, $patient_tables)) === true && $pageName !== 'list.patient') {

   $restriction = true;

   $patientDiseaseList = explode(',', dlookup($db, 'erkrankung', "GROUP_CONCAT(DISTINCT erkrankung)", "patient_id = '{$patient_id}'"));

   foreach ($_SESSION['sess_recht_erkrankung'] as $rechtErk) {
      if (in_array($rechtErk, $patientDiseaseList) === true) {
         $restriction = false;
         break;
      }
   }

   $patientRestriction = $restriction;
}

//Status - Locked
if (in_array($page, array_merge($erkrankung_tables, $patient_tables)) == true && $pageType == 'rec')
{
   $requestFormId = isset($_REQUEST[$page . '_id']) ? $_REQUEST[$page . '_id'] : null;

   if ($requestFormId !== null && $patient_id !== null) {
      $statusLockId = dlookup($db, 'status', 'status_id', "form = '{$page}' AND form_id = '{$requestFormId}' AND patient_id = '{$patient_id}' AND status_lock = 1");

      if (strlen($statusLockId) > 0) {
         $statusLocked = true;

         $permission->setForbidden();

         $parameter = in_array($page, $patient_tables) == true
            ? "&form={$page}&location=view.patient"
            : "&location=view.erkrankung";

         $smarty->assign('status_locked', get_url("page=lock{$parameter}&selected={$statusLockId}&return=1"));
      }
   }
}

$whitelist = array('login', 'rollenauswahl', 'user_setup');

// permission check for files
if ($permissionGranted === false && $permission->checkView() === false && in_array($permission->getPage(), $whitelist) === false) {
    redirectTo(get_url('page=rollenauswahl'), $config['msg_nopageright']);
}


?>
