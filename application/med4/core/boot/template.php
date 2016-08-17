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

$template = null;

if($template === null) {
   foreach ($appOrder as $app) {
      $folder = $app['folder'];

      $templateFilePath = "templates/{$folder}/{$bfl}{$subdir}{$pageName}{$bflSub}.tpl";

      if (file_exists($templateFilePath) === true) {
         $template = "{$folder}/{$bfl}{$subdir}{$pageName}{$bflSub}.tpl";
      }
   }
}

//Feature
if ($template == null && $featureLoad['templates'] !== null) {
   $template = "../{$featureLoad['templates']}";
}

if ($template === null) {
   $template = 'base/file_not_found.tpl';
}

/**
 ** Menu
 **/

//show content only
$noMenu = isset($_REQUEST['nomenu']) === true && (isset($noMenu) === true && $noMenu === true) ? true : false;

//um zu checken ob formular angelegt... ja ich weiss, unsauber und so, aber MED und so...
if (isset($form_id) === true && strlen($form_id) > 0) {
   $smarty->assign('form_id', $form_id);
}


/**
 * Patient Info Table
 */

//PatientData
if (isset($_SESSION['sess_patient_data']['patient_id']) == true) {
   $patientInfoData = $_SESSION['sess_patient_data'];

   $patientInfoData['name'] = concat(array($patientInfoData['nachname'], $patientInfoData['vorname']), ', ');

   $smarty
      ->assign('patientInfoData', $patientInfoData)
   ;
}

//Org Logo
$orgLogo  = isset($_SESSION['sess_org_logo']) === true ? (strlen($_SESSION['sess_org_logo']) > 0 ? $_SESSION['sess_org_logo'] : NULL) : NULL;

if ($orgLogo !== null) {

   $upload = getUploadDir($smarty, 'upload', false);

   $orgLogoPath = $upload['upload'] . $upload['config']['image_dir'] . $orgLogo;

   if (is_file($orgLogoPath) === true) {
      $orgLogo          = base64_encode(file_get_contents($orgLogoPath));
      $orgLogoImgType   = isset($_SESSION['sess_org_logo_img_type']) === true ? $_SESSION['sess_org_logo_img_type'] : null;

      $smarty
         ->assign('orgLogo', $orgLogo)
         ->assign('orgLogoImgType', $orgLogoImgType)
      ;
   }
}

//Conference
if (isset($_SESSION['sess_konferenz_name']) == true) {
   $smarty
      ->assign('konferenzName', $_SESSION['sess_konferenz_name'])
   ;
}

//site img
if (file_exists("media/img/app/pages/{$page}.png") === true) {
    $smarty->assign('site_img', "app/pages/{$page}.png");
}

/** -------------------------------------------------------------------------------------------
 ** Variablen ins Template legen
 **/
$form_rec = isset($form_rec) === true ? str_replace('&', '&amp;', $form_rec) : '';

if (isset($_SESSION['back_function_error'])) {
    $smarty->assign('message', $_SESSION['back_function_error']);
    unset($_SESSION['back_function_error']);
}

$smarty->assign('dontshowlogin',     $dontshowlogin); // renderLoginmenu
$smarty->assign( 'nomenu'            ,$noMenu );                                 //hide menu head
$smarty->assign( 'page'              ,$page );                                   //current page
$smarty->assign( 'pageName'          ,$pageName );                               //current page
$smarty->assign( 'subdir'            ,$subdir   );                               //aktueller Unterordner

$smarty->assign( 'file'              ,$script );                                 //php file
$smarty->assign( 'body'              ,$template );                               //tpl file
$smarty->assign( 'SESSION'           ,$_SESSION );                               //session to template
$smarty->assign( 'form_rec'          ,$form_rec );                               //Diese Variablen werden in der jeweiligen list.*.php belegt

$smarty->assign( 'fields'            ,$fields );                                 //fields

//config
$smarty->assign( 'file_config'       ,FILE_CONFIG );                            //Standard (Default) Config File


$viewTemplate = $ajax === true ? 'ajax' : ($codepicker === true ? 'codepicker' : ($picker === true ? 'picker' :'index'));

//Special Case Delete Dialog
if ($ajax === true && $deleteDialog === true) {
   $viewTemplate = 'delete';
}

//Special Case Confirm Dialog
if ($ajax === true && $confirmDialog === true) {
   $viewTemplate = 'confirm';
}

//List Template bei sehr sehr sehr großen Templates nachladen
if ($bfl !== null) {
   header('Content-Type: text/html; charset=iso-8859-1');
   $smarty->display('app/list/bfldata.tpl');

   $smarty->display($template);

   exit;
}

$smarty->display("base/$viewTemplate.tpl");
?>
