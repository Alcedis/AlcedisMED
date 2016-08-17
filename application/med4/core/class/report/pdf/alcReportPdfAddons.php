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

require_once 'core/class/report/pdf/alcReportPdfBase.php';

class alcReportPdfAddons extends alcReportPdfBase
{
   /**
    * addon prefix name
    *
    * @var string
    */
   private $_addonPrefix = 'alcReportPdfAddon';


   /**
    * loads Addon to pdf class
    *
    * @param $addon
    */
   protected function loadAddons()
   {
      $dir = "core/class/report/pdf/addons/";

      $addons = getDirContent($dir);

      foreach ($addons as $addonFile){

         $addonPath = "{$dir}{$addonFile}";

         $addonName = reset(explode('.', $addonFile));

         require_once $addonPath;

         $addonClassName = $this->buildAddonName($addonName);

         $addonClass = new $addonClassName($this->_db, $this->_smarty, $this->_user, $this->_params);

         $this->_addons[$addonName] = $addonClass->init($this->_fpdi);
      }

      return $this;
   }


   /**
    * magic addon getter
    *
    * @param $addon
    */
   public function __get($addon)
   {
      if (array_key_exists($addon, $this->_addons) === false) {
         echo "addon {$addon} not loaded";
         exit;
      }

      return $this->_addons[$addon];
   }


   /**
    * build addon name
    *
    * @param $addon
    */
   private function buildAddonName($addon) {
      return $this->_addonPrefix . ucfirst($addon);
   }
}


?>