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

//standard settings
$arr_menubar = array(
   'patient'                     => array('new'=>false ),
   'erkrankung'                  => array('new'=>true  ),
   'begleitmedikation'           => array('new'=>true  ),
   'org'                         => array('new'=>true  ),
   'user'                        => array('new'=>true  ),
   'recht'                       => array('new'=>true  ),
   'termin'                      => array('new'=>false ),
   'konferenz'                   => array('new'=>false ),
   'konferenz_teilnehmer'        => array('new'=>false ),
   'konferenz_dokument'          => array('new'=>false ),
   'auswertungen'                => array('new'=>false ),
   'settings_export'             => array('new'=>true  ),
   'settings_import'             => array('new'=>true  )
);

//special case for specific role
switch ($rolle_code)
{
   case 'moderator':
      $arr_menubar['konferenz']                    = array('new' => true);
      $arr_menubar['konferenz_dokument']           = array('new' => true);
      $arr_menubar['konferenz_teilnehmer_profil']  = array('new' => true);

      break;
}

//Init
menuManager::setConfig($smarty)
    ->setPage($page)
    ->setRole($rolle_code)
;


//Register Menu Items
menuManager::registerMenuItem('org')
    ->registerMenuItem('user')
    ->registerMenuItem('recht')
    ->registerMenuItem('tools', 'manager&feature=tools', null)
    ->registerMenuItem('hl7manager', 'manager&feature=hl7', null)
    ->registerMenuItem('settings', null, 'rec')
    ->registerMenuItem('patient')
    ->registerMenuItem('konferenz')
    ->registerMenuItem('termin_erinnerung')
    ->registerMenuItem('zweitmeinung')
    ->registerMenuItem('user_reg')
    ->registerMenuItem('teilnehmerprofil', 'konferenz_teilnehmer_profil')
    ->registerMenuItem('vorlagen')
    ->registerMenuItem('auswertungen', null, null)
    ->registerMenuItem('extras', null, null)
;

menuManager::registerMenuPage('rollenauswahl');

menuManager::mapRoleItems('admin', array('org', 'user', 'recht', 'vorlagen', 'tools', 'settings'), false)
    ->mapRoleItems('moderator', array('konferenz', 'zweitmeinung','teilnehmerprofil', 'vorlagen'))
    ->mapRoleItems('kooperationspartner', array('patient', 'konferenz', 'termin_erinnerung', 'auswertungen', 'vorlagen'))
    ->mapRoleItems('lesen', array('patient', 'konferenz', 'termin_erinnerung', 'auswertungen', 'vorlagen'))
    ->mapRoleItems('pathologe', array('patient', 'konferenz', 'termin_erinnerung', 'vorlagen'))
    ->mapRoleItems('radiologe', array('patient', 'konferenz', 'termin_erinnerung'))
    ->mapRoleItems('konferenzteilnehmer', array('patient', 'konferenz', 'vorlagen'))
    ->mapRoleItems('dateneingabe', array('patient', 'vorlagen'))
    ->mapRoleItems('facharzt', array('patient', 'konferenz', 'termin_erinnerung', 'vorlagen'))
    ->mapRoleItems('reg', array('user_reg', 'vorlagen'))
    ->mapRoleItems('strahlentherapeut', array('patient', 'konferenz', 'termin_erinnerung', 'vorlagen'))
    ->mapRoleItems('supervisor', array('patient', 'konferenz', 'user', 'recht', 'auswertungen', 'vorlagen', 'hl7manager', 'tools'))
    ->mapRoleItems('systemtherapeut', array('patient', 'konferenz', 'termin_erinnerung', 'vorlagen'))
    ->mapRoleItems('default', array('patient', 'konferenz', 'termin_erinnerung', 'auswertungen', 'vorlagen'))
;

menuManager::addMenuItemGroup('auswertungen', array('auswertungen'));


if ($rolle_code == 'supervisor' && appSettings::get('tools') !== true) {
    menuManager::hideMenuItem('tools');
}

if (appSettings::get('konferenz') !== true) {
    menuManager::hideMenuItem('konferenz');
}

if (appSettings::get('zweitmeinung') !== true) {
    menuManager::hideMenuItem('zweitmeinung');
}

if (appSettings::get('active', 'hl7') !== true) {
    menuManager::hideMenuItem('hl7manager');
}

if (formManager::getFormProperty($org_id, 'erkrankung', 'termin') === true) {
    menuManager::hideMenuItem('termin_erinnerung');
}

// Seitenspezifische Ausblendung

if ($pageName == 'user_setup' && isset($_SESSION['sess_rolle_code']) == false) {
    menuManager::hideMenu();
}

if ($pageName == 'rollenauswahl' && isset($_SESSION['sess_rolle_code']) === true) {
    menuManager::hideMenu();
}

if ($pageName == 'login') {
    menuManager::hideMenu();
}

?>
