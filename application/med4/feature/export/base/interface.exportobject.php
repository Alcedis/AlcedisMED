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

/**
 * Interface IExportObject
 */
interface IExportObject
{
    /**
     * create
     *
     * @access  public
     * @param   string      $absolute_path
     * @param   string      $export_name
     * @param   Smarty      $smarty
     * @param   resource    $db
     * @param   string      $error_function
     * @return  void
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '');


    /**
     * SetParameters
     *
     * @access  public
     * @param   array   $parameters
     * @return  IExportObject
     */
    public function setParameters(&$parameters);


    /**
     * GetParameters
     *
     * @access  public
     * @return  array
     */
    public function getParameters();


    /**
     * getParameter
     *
     * @access  public
     * @param   string  $name
     * @return  string
     */
    public function getParameter($name);


    /**
     * getSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getSmarty();


    /**
     * setSmarty
     *
     * @access  public
     * @param   Smarty  $smarty
     * @return  IExportObject
     */
    public function setSmarty($smarty);


    /**
     * getDB
     *
     * @access  public
     * @return  resource
     */
    public function getDB();


    /**
     * setDB
     *
     * @access  public
     * @param   resource    $db
     * @return  IExportObject
     */
    public function setDB($db);


    /**
     * getErrorFunction
     *
     * @access  public
     * @return  string
     */
    public function getErrorFunction();


    /**
     * setErrorFunction
     *
     * @access  public
     * @param   string  $name
     * @return  IExportObject
     */
    public function setErrorFunction($name);


    /**
     * GetExportName
     *
     * @access  public
     * @return  string
     */
    public function GetExportName();


    /**
     * setExportName
     *
     * @access  public
     * @param   string  $name
     * @return  IExportObject
     */
    public function setExportName($name);


    /**
     * getAbsolutePath
     *
     * @access  public
     * @return  string
     */
    public function getAbsolutePath();


    /**
     * setAbsolutePath
     *
     * @access  public
     * @param   string    $path
     * @return  IExportObject
     */
    public function setAbsolutePath($path);
}

?>
