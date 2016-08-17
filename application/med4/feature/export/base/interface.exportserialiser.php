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

interface IExportSerialiser
{
    /**
     * create CExportXmlSerialiser
     *
     * @access  public
     * @param   string   $absolute_path
     * @param   string   $export_name
     * @param   Smarty   $smarty
     * @param   resource $db
     * @param   string   $error_function
     * @return  void
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '');


    /**
     * SetData
     *
     * @access  public
     * @param   RExport $export_record
     * @return  void
     */
    public function setData(&$export_record);


    /**
     * validate
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     */
    public function validate($parameters);


    /**
     * encrypt
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     */
    public function encrypt($parameters);


    /**
     * write
     *
     * @access  public
     * @param   array $parameters
     * @return  string
     */
    public function write($parameters);


    /**
     * getInternalSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getInternalSmarty();


    /**
     * getXmlTemplateFileName
     *
     * @access  public
     * @return  string
     */
    public function getXmlTemplateFileName();


    /**
     * getXmlSchemaFileName
     *
     * @access  public
     * @return  string
     */
    public function getXmlSchemaFileName();
}

?>
