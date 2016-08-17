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

switch ($action)
{
    case 'validatestatus':
        $form = null;

        if (is_array($_REQUEST['action'][$action]) === true) {
            $vform = reset(array_keys($_REQUEST['action'][$action]));

            if (in_array($vform, relationManager::get()) === true) {
                $form = "--onlyone=true --form=" . $vform;
            }
        } else {
            mysql_query('UPDATE settings SET status_lasttime = NOW()');
        }

        $exec = "/usr/bin/php core/exec.php --feature=status --page=validate {$form} > /dev/null &";

        exec($exec);

        break;

   case 'resetstatus':

       statusRefresh::create($db, $smarty)->resetLog();

       break;

   case 'reset_cookie':

      mysql_query("UPDATE user SET reset_cookie = 1");

      $smarty
         ->assign('warn', $config['warn_reset_cookie'])
      ;

      break;

   case 'update':

      $dateiDB  = dlookup($db, $table, "logo", "settings_id = '$form_id'");
      $no_error = action_update($smarty, $db, $fields, $table, $form_id, $action, '', 'upload');

      if ($no_error) {

         $dkg       = array('old' => '', 'new' => '');
         $konferenz = array('old' => appSettings::get('konferenz'), 'new' => null);

         foreach (appSettings::get() as $setting => $settingValue) {
             if (str_starts_with($setting, 'feature_dkg_') === true) {
                 $dkg['old'] .= $setting . appSettings::get($setting);
             }
         }

         appSettings::refresh();

         $konferenz['new']  = appSettings::get('konferenz');

         foreach (appSettings::get() as $setting => $settingValue) {
             if (str_starts_with($setting, 'feature_dkg_') === true) {
                 $dkg['new'] .= $setting . appSettings::get($setting);
             }
         }

         //Liste der Elemente die sich geändert haben müssen um ein Status Refresh zu erzeugen!
         //Oder Online Konferenz wurde aktiviert bzw deaktiviert
         if ($dkg['old'] !== $dkg['new'] || $konferenz['old'] !== $konferenz['new']) {
            $exec = "/usr/bin/php core/exec.php --feature=status --page=validate > /dev/null &";

            exec($exec);
         }

         if ($dateiDB !== $fields['logo']['value'][0]) {
            $upload
               ->moveTmp2Folder(array('logo' => $fields['logo']['value'][0]), false)
               ->removeFile($dateiDB, 'logo');
         }

         action_cancel($location);
      }

      break;

   case 'cancel':

      $upload->clearUserTMP();
      action_cancel($location);

      break;
}

?>
