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

class upload
{
   protected $_http_upload       = null;
   protected $_validator         = null;
   protected $_smarty            = null;
   protected $_config            = null;

   protected $_upload_dir        = null;
   protected $_tmp_dir           = null;
   protected $_dirs              = array();

   protected $_max_filesize      = array(134217728); // 128MB
   protected $_mandatory         = array(true);
   protected $_destinations      = null;
   protected $_valid_extensions  = array();

   protected $_fields            = null;
   protected $_files             = array();
   protected $_filenames         = array();
   protected $_isValid           = array();
   protected $_performed         = array();

   public function __construct($smarty)
   {
      $smarty->config_load(FILE_CONFIG_DEFAULT,  'upload');

      $this->_config = $smarty->get_config_vars();
      $this->_smarty = $smarty;

      $this->_setUploadDir();
   }

   public static function create($smarty)
   {
       return new self($smarty);
   }

   protected function _setUploadDir()
   {
      $upload = getUploadDir($this->_smarty, 'upload', false);

      $this->_upload_dir   = reset($upload);
      $this->_tmp_dir      = getUploadDir($this->_smarty, 'tmp');
      $this->_config       = array_merge($this->_config, end($upload));

      return $this;
   }

   public function getUploadDir(){
      return $this->_upload_dir;
   }

   public function getUserTmpDir()
   {
      $doc_root   = dirname($_SERVER['SCRIPT_FILENAME']);
      $user       = $_SESSION['sess_loginname'];
      $tmp_upload = $doc_root . DIR_MATERIAL . $user . '/upload';

      if (realpath($tmp_upload) === false) {
         umask(0002);
         $dir_created = mkdir($tmp_upload, 0777 ,true);
      }

      return $tmp_upload;
   }

   protected function _defineValidExtensions()
   {
      foreach ($this->_fields as $index => $name) {
         if (isset($this->_files[$name]) === false) {
            continue;
         }
         if (count($this->_valid_extensions[$name]) > 0) {
            $this->_files[$name]->setValidExtensions($this->_valid_extensions[$name], 'accept');
         }
      }
   }

   protected function _preValidateFiles() {
      foreach ($this->_fields as $index => $name) {
         if (isset($this->_files[$name]) === false) {
            $this->_isValid[$name] = false;
            continue;
         }

         switch (true) {
            case $this->_files[$name]->isMissing() === true && $this->_mandatory[$name] === true:
               $this->_validator->set_err(11, array($name), null, $this->_config['no_file']);
               $this->_isValid[$name] = false;
               continue 2;// foreach eins weiter, da file nicht in Ordnung
               break;
            case is_array($this->_max_filesize) && isset($this->_max_filesize[$name]) && $this->_files[$name]->getProp('size') >= $this->_max_filesize[$name]:
               $this->_validator->set_err(11, array($name), null, sprintf($this->_config['max_filesize'], $this->_files[$name]->getProp('name'), $this->_max_filesize[$name]));
               $this->_isValid[$name] = false;
               continue 2;// foreach eins weiter, da file nicht in Ordnung
               break;
            case $this->_files[$name]->getProp('size') >= $this->_max_filesize[0]:
               $this->_validator->set_err(11, array($name), null, sprintf($this->_config['max_filesize'], $this->_files[$name]->getProp('name'), $this->_max_filesize));
               $this->_isValid[$name] = false;
               continue 2;// foreach eins weiter, da file nicht in Ordnung
               break;
         }

         $this->_isValid[$name] = true;
      }
   }

   protected function _validateFile($moved, $name) {
      if (is_string($moved) === true) {
         $this->_performed[] = $name;
         return true;
      }

      $message = $moved->getMessage();
      if ($moved->getCode() === 'NOT_ALLOWED_EXTENSION') {
         $message  = sprintf($this->_config['deny_file_ext'], substr($this->_filenames[$name], 14));
         $message .= '<br><ul><li>' . sprintf($this->_config['list_file_ext'], implode(', ', $this->_valid_extensions[$name])) . '</li></ul>';
         $this->_validator->set_err(11, array($name), null, $message);
         $this->_isValid[$name] = false;

         return;
      }

      $this->_isValid[$name] = true;
      $this->_performed[]    = $name;
   }

   public function getTmpDir()
   {
      return $this->_tmp_dir;
   }

   public function isValid($name = null)
   {
      return $name === null ? reset($this->_isValid): $this->_isValid[$name];
   }

   public function clearUserTMP()
   {
      foreach (scandir($this->getUserTmpDir()) as $file) {
         if (in_array($file, array('.', '..', '.htaccess')) === false && is_file($this->getUserTmpDir() . '/' . $file) === true) {
            unlink($this->getUserTmpDir() . '/' . $file);
         }
      }
   }

   public function setMandatory($mandatory)
   {
      if (is_array($mandatory) === true) {
         foreach ($this->_fields as $index => $name) {
            $this->_mandatory[$name] = $mandatory[$index] === 1 || $mandatory[$index] === 3;
         }

         return;
      }

      $this->_mandatory[$this->_fields[0]] = $mandatory === 1 || $mandatory === 3;
   }

   public function setMaxFilesize($size)
   {
      if (is_array($size) === true) {
         foreach ($this->_fields as $index => $name) {
            $this->_max_filesize[$name] = $size[$index];
         }

         return;
      }

      $this->_max_filesize[$this->_fields[0]] = $size;
   }

   public function setFields($fields)
   {
      // einzelne File
      if (is_string($fields) === true) {
         $this->_fields[] = $fields;
         $this->_isValid[$fields] = false;

         return;
      }

      // nur bei mehreren Files gleichzeitig
      $this->_fields = $fields;
      foreach ($fields as $index => $name) {
         $this->_isValid[$name] = false;
      }


   }

   public function setValidExtensions($valid_extensions)
   {
      if (count($this->_fields) !== count($valid_extensions)) {
         throw new Exception('Wrong amount of DATA in UPLOAD->setFields && UPLOAD->setValidExtensions');
      }

      if (is_array($valid_extensions) === true) {
         foreach ($this->_fields as $index => $name) {
            $this->_valid_extensions[$name] = explode(';', $valid_extensions[$index]);
         }

         return;
      }

      $this->_valid_extensions[$this->_fields[0]] = explode(';', $valid_extensions);
   }


   /**
    *
    * @param unknown_type $fileFolderDestination
    * @param unknown_type $append
    * @return upload
    */
   public function setDestinations($fileFolderDestination, $append = false)
   {
      foreach ($fileFolderDestination as $field => $destinationArray) {

         $path = $append === false ? $this->_upload_dir : $this->_destinations[$field];

         foreach ($destinationArray as $pathPart) {
            if (strlen($pathPart) > 0) {
               $path .= isset($this->_config["{$pathPart}_dir"]) ? $this->_config["{$pathPart}_dir"] : $pathPart . '/';
            }
         }

         if (is_dir($path) === false) {
            mkdir($path, 0777, true);
         }

         $this->_destinations[$field] =  $path;
      }

      return $this;
   }


   public function setDestinationExtension($extension) {
      return $this->setDestinations($extension, true);
   }


   public function getDestination($field)
   {
      return $this->_destinations[$field];
   }


   public function getFilename($name)
   {
      if ((isset($this->_files[$name]) === true && isset($this->_filenames[$name]) === true) || isset($_REQUEST[$name])) {
         return isset($this->_filenames[$name]) && strlen($this->_filenames[$name]) ? $this->_filenames[$name]: $_REQUEST[$name];
      }
   }

   public function upload2UserTmp($valid)
   {
      $this->_validator = $valid;

      if (count($_FILES) > 0) {
         $lang = 'de';
         require_once DIR_LIB . '/pear/http_upload.php';
         // Upload initialisieren
         $this->_http_upload = new http_upload($lang);
         // Datei holen
         $this->_files = $this->_http_upload->getFiles();

         $this->_preValidateFiles();
         $this->_defineValidExtensions();

         foreach ($this->_fields as $index => $name) {
            if (isset($_FILES[$name]['name']) === true && strlen($_FILES[$name]['name']) > 0) {
               $this->_filenames[$name] = uniqid() . '_' . str_replace(' ', '_',$_FILES[$name]['name']);
               $this->_files[$name]->setName($this->_filenames[$name]);

               $this->_validateFile($this->_files[$name]->moveTo($this->getUserTmpDir()), $name);
            }
         }
      }

      return $this;
   }

   public function assignVars($fields = array()) {
      foreach ($this->_fields as $index => $field) {
         if ((($this->_isValid[$field] === false || isset($this->_filenames[$field]) === false) && strlen($fields[$field]['value'][0]) === 0) || strlen($this->_smarty->get_template_vars('err_' . $field)) > 0) {
            $this->_smarty->assign($field, '')->assign($field . '_text', '');//muss blöderweise gemacht werden (sollte später vielleicht umgebaut werden)
            continue;
         }

         $fileName      = isset($this->_filenames[$field]) ? $this->_filenames[$field]: $fields[$field]['value'][0];
         $fileNameClean = substr($fileName, 14);

         $fieldText     = "<input type='submit' name='action[file][{$field}]' class='dont_prevent_double_save filepackage' value='{$fileNameClean}' alt=''/>";

         $this->_smarty
            ->assign($field, $fileName)
            ->assign($field . '_text', $fieldText)
            ->assign('btn_unset_file', '<img class="btn_unset_file" src="media/img/base/editdelete.png" alt="Unset File" />');

         $_REQUEST[$field] = $fileName;
      }

      return $this;
   }

   public function moveTmp2Folder($files, $clearDir = true)
   {
      $result = array();
      foreach ($files as $field => $fileName) {
         if(strlen($fileName)) {
            $result[] = copy($this->getUserTmpDir() . '/' . $fileName, $this->_destinations[$field] . $fileName);
         }
      }

      // wenn alle files kopiert werden konnten, kann "$user/upload/" geleert werden
      if (in_array(false, $result) === false && $clearDir === true) {
         self::clearUserTMP();
      }

      return $this;
   }

   public function removeFile($fileName, $destination)
   {
      if (array_key_exists($destination, $this->_destinations) === true) {
         $path       = $this->_destinations[$destination];
         $filePath   = $path . $fileName;

         if (is_file($filePath) === true) {
            unlink($filePath);
         }
     }

     return $this;
   }

   public function uploadPerformed()
   {
       return count($this->_performed) > 0 ? $this->_performed: false;
   }

   public function getDir() {
      return $this->_dirs;
   }

   public function getExt($field) {
      if(isset($this->_files[$field]) === true) {
         return $this->_files[$field]->getProp('ext');
      }
   }
}
