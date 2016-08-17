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

require_once( 'interface.importobject.php' );

abstract class CImportBaseObject implements IImportObject
{
    protected $m_absolute_path = '';
    protected $m_import_name = '';
    protected $m_import_version = '';
    protected $m_smarty = null;
    protected $m_db = null;
    protected $m_error_function = '';
    protected $m_parameters = array();

    public function __construct()
    {
    }

    public function Create( $absolute_path, $import_name, $import_version, $smarty, $db, $error_function = '' )
    {
        $this->m_absolute_path = $absolute_path;
        $this->m_import_name = $import_name;
        $this->m_import_version = $import_version;
        $this->m_smarty = $smarty;
        $this->m_db = $db;
        $this->m_error_function = $error_function;
    }

    public function SetParameters( &$parameters )
    {
        $this->m_parameters = $parameters;
    }

    public function GetParameters()
    {
        return $this->m_parameters;
    }

    public function GetImportName()
    {
        return $this->m_import_name;
    }

    public function GetImportVersion()
    {
        return $this->m_import_version;
    }

}

?>
