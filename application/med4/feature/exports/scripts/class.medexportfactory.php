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

require_once( 'interface.medexport.php' );
require_once( 'class.medexportexception.php' );

class CMedExportFactory
{

   /**
    *
    * @param $smarty
    * @param $db
    * @param $export
    * @param $version
    * @param $sub_version
    * @return Object
    */
   static public function CreateObject( $smarty, $db, $export, $version, $sub_version = '0' )
   {
      $object = null;
      $version_str = str_replace( ".", "_", $version ) . "_" . $sub_version;
      $class_name = 'C' . $export . $version_str;
      $class_file =  getcwd() . '/feature/exports/scripts/' . strtolower( $export . '/class.' . $export . $version_str . '.php' );
      if ( file_exists( $class_file ) ) {
         require_once( $class_file );
         $object = new $class_name;
         $object->create( $smarty, $db );
      }
      else {
         throw new EMedExportException( "Klassen Datei $class_file nicht gefunden." );
      }
      return $object;
   }

}

?>
