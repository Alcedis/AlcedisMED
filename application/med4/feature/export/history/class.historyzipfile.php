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

require_once(DIR_LIB.'/zip/pclzip.lib.php');

class CHistoryZipFile {

    protected $_files = array();
    protected $_filename = "";
    protected $_path = "";

    public function __construct()
    {
    }

    public function clear()
    {
        $this->_files = array();
    }

    public function setPath($path)
    {
        $this->_path = CHistoryZipFile::checkPath($path);
    }

    public function getPath()
    {
        return $this->_path;
    }

    public function setFilename($filename)
    {
        $this->_filename = $filename;
    }

    public function getFilename()
    {
        return $this->_filename;
    }

    public function getFileUrl()
    {
        return $this->_path . $this->_filename;
    }

    public function addFile($filename, $filePath)
    {
        $this->_files[] =
            array(
                'filename' => $filename,
                'filePath' => $filePath
            );
    }

    public function addFiles($files)
    {
        $this->_files = $files;
    }

    public function create()
    {
        $zip = new PclZip($this->getFileUrl());
        $zip->create($this->_files, PCLZIP_OPT_REMOVE_ALL_PATH);
        return true;
    }

    public function delete()
    {
        if (file_exists($this->getFileUrl())) {
            unlink($this->getFileUrl());
        }
        return true;
    }

    public static function checkPath($path)
    {
        if (strlen($path) > 0) {
            if ($path[strlen($path) - 1] !== '/') {
                $path .= '/';
            }
        }
        return $path;
    }

}
