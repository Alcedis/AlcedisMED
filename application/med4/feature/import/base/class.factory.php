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

require_once( 'class.importexception.php' );

class CImportFactory
{

    /**
     *
     * @param $smarty Object
     * @param $db Object
     * @param $import_name String
     * @param $version String
     * @param $error_function String
     * @return Object
     */
    static public function CreateObject( $smarty, $db, $import_name, $import_version, $error_function = '' )
    {
        if ( $smarty === null ) {
            throw new EImportException( "ERROR: Smarty object is NULL." );
        }
        else if ( $db === null ) {
            throw new EImportException( "ERROR: Database object is NULL." );
        }
        else if ( strlen( $import_name ) == 0 ) {
            throw new EImportException( "ERROR: Import name is NULL." );
        }
        else if ( strlen( $import_version ) == 0 ) {
            throw new EImportException( "ERROR: Import version is NULL." );
        }
        $name = strtolower( $import_name );
        $version = str_replace( ".", "_", $import_version );
        $class_name = "C{$name}_{$version}_Controller";
        $absolute_path = "feature/import/$name/";
        $class_file =  "{$absolute_path}class.{$name}_{$version}_controller.php";
        if ( !file_exists( $class_file ) ) {
            throw new EImportException( "FATAL: Controller class file not found." );
        }
        require_once( $class_file );
        $import_controller = new $class_name;
        if ( ( $import_controller instanceof IImportController ) ||
             ( $import_controller instanceof CImportDefaultController ) ) {
            $import_controller->create( $absolute_path, $name, $version, $smarty, $db, $error_function );
        }
        else {
            throw new EImportException( "FATAL: Controller class implements not IImportController or extends not CImportDefaultController." );
        }
        return $import_controller;
    }

}

?>
