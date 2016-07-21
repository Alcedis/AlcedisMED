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

class upload_file
{
   const    UPLOAD_CHMOD         = 0660;

   private  $_fields             = array();
   private  $_msg                = array();

   private  $_fieldname          = null;
   private  $_req                = 0;
   private  $_valid_extensions   = '';
   private  $_max_filesize       = null;
   private  $_name               = 'real';

   private  $_upload_dir         = null;

   /**
    * Konstruktor
    *
    * @param object $valid
    * @param array  $config
    * @param array  &$fields
    * @param string $fieldname
    * @param int    $req
    * @param string $valid_extensions
    * @param int    $max_filesize
    * @param string $name
    * @return void
    */
   public function __construct($upload_dir, $valid, $config, &$fields, $fieldname, $valid_extensions = '', $max_filesize = '', $name='real') {
      // Einstellungen und Strings aus der Config
      $this->_msg    = $config;
      $this->_fields = &$fields;

      try{
         $this->setFieldname($fieldname);
         $this->setRequired();
         $this->setValidExt($valid_extensions);
         $this->setMaxFilesize($max_filesize);
         $this->setFile();
         $this->setName($name);
         $this->isValid();
         $this->setUploadDir($upload_dir);
         $this->moveFile();
         $this->setChmod();
         $this->setFields();
      } catch(Exception $e) {
         $this->setErrors($valid, $e->getMessage());
      }
   }

   /**
    * Setzt den Feldnamen der Datei
    *
    * @access private
    * @param string $fieldname
    * @return void
    */
   private function setFieldname($fieldname) {
      $this->_fieldname    = $fieldname;
   }

   /**
    * Setzt das required-Flag entsprechend der Fields
    *
    * @access private
    * @return void
    */
   private function setRequired() {
      $this->_req          = $this->_fields[$this->_fieldname]['req'];
   }

   /**
    * Setzt die erlaubten Dateiendungen
    *
    * @access private
    * @param string $valid_extensions
    * @return void
    */
   private function setValidExt($valid_extensions) {
      $this->_valid_extensions = explode(';', strtolower($valid_extensions));
   }

   /**
    * Setzt die maximale Dateigröße
    * Wenn ein leerer String übergeben wird, wird
    * der Wert der php.ini-Variable "upload_max_filesize" genommen
    *
    * @access private
    * @param int $max_filesize
    * @return void
    */
   private function setMaxFilesize($max_filesize) {
      $ini_size   = preg_replace('/m/i', '000000', ini_get('upload_max_filesize'));

      $max_size   = strlen($max_filesize) ? $max_filesize : $ini_size;

      $this->_max_filesize  = $max_size;
   }

   /**
    * Setzt den Ziel-Dateinamen
    * Wenn 'real' übergeben wird, wird der Quell-Dateiname genommen
    *
    * @access private
    * @param string $name
    * @return void
    */
   private function setName($name) {
      $add_this            = date('YmdHis');
      $name                = ($name != 'real') ? $name : $this->file['name'];
      $this->_name         = $add_this . '_' . $name;
   }

   /**
    * Erstellt das _file Unterobjekt
    *
    * @access private
    * @return void
    */
   private function setFile() {
      if(isset($_FILES[$this->_fieldname])) {
         $this->file = $_FILES[$this->_fieldname];
         if (($pos = strrpos($this->file['name'], '.')) !== false) {
            $this->file['ext'] = strtolower(substr($this->file['name'], $pos + 1));
         } else {
            $this->file['ext'] = '';
         }
      }
      elseif($this->_req == 3 AND !strlen($this->_fields[$this->_fieldname]['value'][0]))
         throw new Exception($this->_msg['no_file']);
      else
         throw new Exception();
   }

   /**
    * Setzt den vollen Upload-Pfad zusammen und erstellt
    * diesen bei Bedarf
    *
    * @access private
    * @return void
    */
   private function setUploadDir($ud) {
      $this->_upload_dir = $ud;
   }

   /**
    * Setzt die Zugriffsrechte auf die hochgeladene Datei
    *
    * @access private
    * @param  string $chmod
    * @return void
    */
   private function setChmod($chmod = upload_file::UPLOAD_CHMOD) {
      chmod($this->getFullPath(), $chmod);
   }

   /**
    * Legt den Ziel-Dateinamen in die Fields
    * MUSS leider in die Fields UND ins REQUEST gelegt werden,
    * weil die Fields ja schon mit show_record gefüllt werden.
    * Das REQUEST wird aber erst in der Validator-Unterfunktion
    * gesetzt.
    * In das REQUEST muss der Dateiname gelegt werden, damit bei
    * noch fälligen MUSS-Validierungen nicht wieder das File-Feld
    * gezeigt wird. Der Arzt müsste sonst doppelt hochladen und wir
    * hätten die Datei zwei mal auf der Festplatte.
    *
    * @access private
    * @return void
    */
   private function setFields() {
      $this->_fields[$this->getFieldname()]['value'][0]  = $this->getName();
      $_REQUEST[$this->getFieldname()]                   = $this->getName();
   }

   /**
    * Setzt Fehler-Meldungen
    *
    * @access private
    * @param object $valid    / null
    * @param string $message
    * @return void
    */
   private function setErrors($valid, $message) {
      if($valid != null AND $message != null) {
         $valid->set_msg('err', 10, array($this->_fieldname), $message);
      }

      $this->errors = $message;
   }

   /**
    * Holt den Feldnamen
    *
    * @access public
    * @return string
    */
   public function getFieldname() {
      return $this->_fieldname;
   }

   /**
    * Holt das Required-Flag
    *
    * @access public
    * @return int
    */
   public function getRequired() {
      return $this->_req;
   }

   /**
    * Holt die zugelassenen Extensions
    *
    * @access public
    * @return array
    */
   public function getValidExt() {
      return $this->_valid_extensions;
   }

   /**
    * Holt die maximale Dateigröße
    *
    * @access public
    * @return int
    */
   public function getMaxFilesize() {
      return $this->_max_filesize;
   }

   /**
    * Holt den Ziel-Dateinamen
    *
    * @access public
    * @return string
    */
   public function getName() {
      return $this->_name;
   }

   /**
    * Holt den Upload-Pfad
    *
    * @access public
    * @return string
    */
   public function getUploadDir() {
      return $this->_upload_dir;
   }

   /**
    * Gibt den vollen Ziel-Pfad zur Datei aus
    *
    * @access public
    * @return string
    */
   public function getFullPath() {
      $full_path = $this->getUploadDir() . $this->getName();

      return $full_path;
   }

   /**
    * Liefert Fehlermeldungen zurück, sofern es welche gibt
    *
    * @access public
    * @return string
    */
   public function getErrors() {
      if(isset($this->errors) AND strlen($this->errors) !== 0)
         return $this->errors;
      else
         return 0;
   }

   /**
    * Validierungs-Funktion
    * (Ist eine Datei angegeben, richtige Erweiterung etc.)
    *
    * @access private
    * @return void
    */
   private function isValid() {
      switch($this->file['error']) {
         case 1:
         case 2:
            throw new Exception($this->_msg['max_file_size']);
            break;

         case 3:
            throw new Exception($this->_msg['file_not_found']);
            break;

         case 4:
            if($this->_req == 3)
               throw new Exception($this->_msg['no_file']);
            else
               throw new Exception();
            break;

         default:
            // Prüfungen auf Datei-Endung
            if(!in_array($this->file['ext'], $this->getValidExt())) {
               throw new Exception($this->_msg['denied_file_ext']);
            }
            break;
      }
   }

   /**
    * Verschiebt die Datei aus dem Temp-Ordner an ihre Zielposition
    *
    * @access private
    * @return void
    */
   private function moveFile() {
      $success = move_uploaded_file($this->file['tmp_name'], $this->getFullPath());
      if(!$success)
         throw new Exception($this->_msg['dir_not_found']);
   }
}

?>
