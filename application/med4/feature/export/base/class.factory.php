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

require_once('interface.exportcontroller.php');
require_once('class.exportdefaultcontroller.php');
require_once('class.exportexception.php');

/**
 * Class CExportFactory
 */
class CExportFactory
{
    /**
     * paths
     *
     * @static
     * @access  public
     * @var     array
     */
    public static $paths = array();


    /**
     * addPath
     *
     * @static
     * @access  public
     * @param   string  $path
     * @return  void
     */
    public static function addPath($path)
    {
        self::$paths[] = $path;
    }


    /**
     * createObject
     *
     * @static
     * @access  public
     * @param   Smarty      $smarty
     * @param   resource    $db
     * @param   string      $export_name
     * @param   string      $export_version
     * @param   string      $error_function
     * @return  IExportController|CExportDefaultController
     * @throws  EExportException
     */
    static public function createObject($smarty, $db, $export_name, $export_version, $error_function = '')
    {
        if ($smarty === null) {
            throw new EExportException("ERROR: Smarty object is NULL.");
        } else if ($db === null) {
            throw new EExportException("ERROR: Database object is NULL.");
        } else if (strlen($export_name) === 0) {
            throw new EExportException("ERROR: Export name is NULL.");
        } else if (strlen($export_version) === 0) {
            throw new EExportException("ERROR: Export version is NULL.");
        }

        $name      = strtolower($export_name);
        $version   = str_replace(".", "_", $export_version);
        $className = "C{$name}_{$version}_Controller";

        $absolutePaths = array_merge(array(
            "feature/export/{$name}/"
        ), self::$paths);

        $foundFile = null;
        $absolutePath = null;

        foreach ($absolutePaths as $path) {
            $file = "{$path}class.{$name}_{$version}_controller.php";

            if (file_exists($file) === true) {
                $foundFile    = $file;
                $absolutePath = $path;

                break;
            }
        }

        if ($foundFile === null) {
            throw new EExportException("FATAL: Controller class file not found.");
        }

        require_once($foundFile);

        /* @var IExportController $export_controller */
        $export_controller = new $className;

        if ($export_controller instanceof IExportController) {
            $export_controller->create($absolutePath, $export_name, $smarty, $db, $error_function);
        } else {
            throw new EExportException("FATAL: Controller class implements not IExportController or extends not CExportDefaultController.");
        }

        return $export_controller;
    }
}
