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

class alcOdtConverter extends alcOdtParser
{
   protected $_db                   = null;

   protected $_smarty               = null;

   public function __construct($db, $smarty)
   {
      parent::__construct($db, $smarty);

      $this->_db     = $db;
      $this->_smarty = $smarty;
   }


   public static function create($db, $smarty)
   {
      return new self($db, $smarty);
   }


   /**
    * Parse zip template odt
    *
    */
   public function convert()
   {
      if (count($this->_paths) == 0) {
         $this->createPaths();
      }

      $this
         ->parse()
         ->_convertToXHTML()
         ->_resetOdtParser()
      ;

      return true;
   }

   /**
    * Converts the parsed odt to XHTML
    *
    * Enter description here ...
    */
   protected function _convertToXHTML()
   {
      if ($this->_breakParse === false) {
         require_once(DIR_LIB . '/odt2xhtml/index.php');

         if (ODT2XHTML_PHPCLI == 0) {
            //If xhtml file for converting exist
            if (array_key_exists('xhtml', $this->_paths['file']) === true) {
               foreach ($this->_paths['file']['xhtml'] as $file) {

                  //if convertRestriction setted and current file is not in list, continue
                  if (count($this->_convertRestriction) > 0 && in_array($file['file'], $this->_convertRestriction) === false) {
                     continue;
                  }

                  //Delete old xhtml dir
                  deltree($file['dir']);

                  //root Dir (odt), null, odt filename, where to extract...
                  $odt2Xhtml = new odt2xhtml($this->_paths['dir']['zipExtract'], null, $file['srcName'], $this->_paths['dir']['xhtml']);
                  $odt2Xhtml->convert2xhtml();
                  $odt2Xhtml->delete_tmp();

                   //Convert generated html
                  xhtmlManager::create($this->_smarty, $this->_type, $this->getParam('id'), $file['file'])
                     ->loadXhtml()
                     ->convertXhtmlForCkEditor()
                     ->saveCss()
                     ->saveXhtml();
               }
            }

            //Extract extension to xhtml folder if exist
            foreach ($this->_pdfExtensions as $extension) {
               if (array_key_exists($extension, $this->_paths['file']) === true) {

                  $extensionSrcPath = $this->_paths['file'][$extension]['dir'];

                  moveDir($extensionSrcPath, $this->_paths['dir']['xhtml'] . "{$extension}/");
               }
            }

            //Extract Settings to xhtml folder
            if (array_key_exists('settings', $this->_paths['file']) === true) {
               $target  = $this->_paths['dir']['xhtml'] . 'settings.php';
               $src     = $this->_paths['file']['settings']['src'];

               copy($src, $target);
               chmod($target, 0777);
            }

            //Extract OBX to xhtml folder
            if (array_key_exists('hl7', $this->_paths['file']) === true) {
                moveDir($this->_paths['dir']['zipExtract'] . 'hl7', $this->_paths['dir']['xhtml'] . 'hl7');
            }
         }

         //Delete Zip Ressource
         //DEBUG deactivate
         deltree($this->_paths['dir']['zipExtract']);
      }

      return $this;
   }
}

?>
