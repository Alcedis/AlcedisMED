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

require_once('interface.exportobject.php');

/**
 * Class CExportBaseObject
 */
abstract class CExportBaseObject implements IExportObject
{
    /**
     * m_absolute_path
     *
     * @access  protected
     * @var     string
     */
    protected $m_absolute_path = '';


    /**
     * m_export_name
     *
     * @access  protected
     * @var     string
     */
    protected $m_export_name = '';


    /**
     * m_smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $m_smarty;


    /**
     * m_db
     *
     * @access  protected
     * @var     resource
     */
    protected $m_db;


    /**
     * m_error_function
     *
     * @access  protected
     * @var     string
     */
    protected $m_error_function = '';


    /**
     * m_parameters
     *
     * @access  protected
     * @var     array
     */
    protected $m_parameters = array();


    /**
     * @see IExportObject::create
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        $this
            ->setAbsolutePath($absolute_path)
            ->setExportName($export_name)
            ->setSmarty($smarty)
            ->setDB($db)
            ->setErrorFunction($error_function)
        ;
    }


    /**
     * @see IExportObject::setParameters
     */
    public function setParameters(&$parameters)
    {
        $this->m_parameters = $parameters;

        return $this;
    }


    /**
     * @see IExportObject::getParameters
     */
    public function getParameters()
    {
        return $this->m_parameters;
    }


    /**
     * getParameter
     *
     * @access  public
     * @param   string  $name
     * @return  string
     */
    public function getParameter($name)
    {
        return (array_key_exists($name, $this->m_parameters) === true ? $this->m_parameters[$name] : null);
    }


    /**
     * @see IExportObject::getSmarty
     */
    public function getSmarty()
    {
        return $this->m_smarty;
    }


    /**
     * @see IExportObject::setSmarty
     */
    public function setSmarty($smarty)
    {
        $this->m_smarty = $smarty;

        return $this;
    }


    /**
     * @see IExportObject::getDB
     */
    public function getDB()
    {
        return $this->m_db;
    }


    /**
     * @see IExportObject::setDB
     */
    public function setDB($db)
    {
        $this->m_db = $db;

        return $this;
    }


    /**
     * @see IExportObject::getErrorFunction
     */
    public function getErrorFunction()
    {
        return $this->m_error_function;
    }


    /**
     * @see IExportObject::setErrorFunction
     */
    public function setErrorFunction($name)
    {
        $this->m_error_function = $name;

        return $this;
    }


    /**
     * @see IExportObject::GetExportName
     */
    public function getExportName()
    {
        return $this->m_export_name;
    }


    /**
     * @see IExportObject::setExportName
     */
    public function setExportName($name)
    {
        $this->m_export_name = $name;

        return $this;
    }


    /**
     * @see IExportObject::getAbsolutePath
     */
    public function getAbsolutePath()
    {
        return $this->m_absolute_path;
    }


    /**
     * @see IExportObject::setAbsolutePath
     */
    public function setAbsolutePath($path)
    {
        $this->m_absolute_path = $path;

        return $this;
    }
}

?>
