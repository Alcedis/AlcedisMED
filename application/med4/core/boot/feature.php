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

$features         = array();
$featureLoaded    = isset($_REQUEST['feature']);

$featureLoad      = array(
   'feature'   => (isset($_REQUEST['feature']) === true ? $_REQUEST['feature'] : null),
   'configs'   => null,
   'scripts'   => null,
   'fields'    => null,
   'templates' => null
);

require "feature/service.php";
require "feature/status/loader.php";
require "feature/konferenz/loader.php";
require "feature/convert/loader.php";
require "feature/password/loader.php";
require "feature/dkg/loader.php";
require "feature/tools/loader.php";
require "feature/krebsregister/loader.php";

if ($featureLoad['feature'] == 'export' && ($page == "history" || appSettings::get('interfaces', null, $page)) === true) {
     require "feature/export/loader.php";
} else if ($featureLoad['feature'] == 'import' && appSettings::get('interfaces', null, $page)  === true) {
    require "feature/import/loader.php";
}

if ($featureLoad['feature'] == 'exports') {
    require "feature/exports/loader.php";
}

// gekid
if (appSettings::get('interfaces', null, 'gekid') === true) {
   require "feature/gekid/loader.php";
}

// ekrhe
if (appSettings::get('interfaces', null, 'ekr_h') === true) {
   require "feature/krhe/loader.php";
}

if (is_array(appSettings::get('feature')) === true) {
   foreach (appSettings::get('feature') as $featureName => $featureSettings) {
         $features[] = $featureName;

         require "feature/{$featureName}/loader.php";
   }
}

if ($featureLoaded === true) {

   $featureBasePath = "feature/{$featureLoad['feature']}/";

   $section = array('configs' => 'conf', 'scripts' => 'php', 'templates' => 'tpl', 'fields' => 'php');

   foreach ($section as $s => $t) {

      $tmpFileName = in_array($s, array('configs', 'fields')) ? $configName : $pageName;

      $ssubdir = $s == 'templates' ? $bfl : null;

      $tmpPath = "{$featureBasePath}{$s}/{$subdir}{$ssubdir}{$tmpFileName}.{$t}";

      if (file_exists($tmpPath) === true) {
         $featureLoad[$s] = $tmpPath;
      }
   }
}

//Config hier laden, da eigentlich schon gelaufen..
if ($featureLoad['configs'] !== null) {
    $smarty->config_load("../{$featureLoad['configs']}", $configSection);

    $config = $smarty->get_config_vars();
}

?>
