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

interface IImportObject
{

	/**
	 * Creates the import object
	 * @param $absolute_path Absolute path to the export.
	 * @param $smarty Object of the current smarty.
	 * @param $db Object to the current database.
	 */
    public function Create( $absolute_path, $import_name, $import_version, $smarty, $db, $error_function = '' );

    /**
     *
     * @param $parameters
     * @return unknown_type
     */
    public function SetParameters( &$parameters );

    /**
     *
     * @return unknown_type
     */
    public function GetParameters();

    /**
     *
     * @return unknown_type
     */
    public function GetImportName();

    /**
     *
     * @return unknown_type
     */
    public function GetImportVersion();

}

?>
