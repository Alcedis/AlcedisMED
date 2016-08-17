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

//loads default config
$smarty->config_load(FILE_CONFIG_DEFAULT);
$config  = $smarty->get_config_vars();

//loads app config
$smarty->config_load(FILE_CONFIG_APP);
$smarty->config_load(FILE_CONFIG_APP, 'organisation');
$smarty->config_load(FILE_CONFIG_APP, 'produkt');
$config  = $smarty->get_config_vars();

//retrieves config information
$configName    = $arr_section['file'];
$configSection = $arr_section['type'];
$configPath    = null;

foreach ($appOrder as $app) {
   $folder = $app['folder'];

   $configDir        = $smarty->config_dir . '/';
   $configFilePath   = "$folder/$subdir$configName.conf";

   if (file_exists($configDir . $configFilePath) === true ) {
      $configPath = $configFilePath;
      break;
   }
}

//loads page config, maybe with section
if ($configPath !== null) {
   $smarty->config_load($configPath, $configSection);
   $config  = $smarty->get_config_vars();
}

//Constants for later calling in functions (oop would be a better way to fly)
define('FILE_CONFIG',      $configPath);
define('SECTION_CONFIG',   $configSection);
?>