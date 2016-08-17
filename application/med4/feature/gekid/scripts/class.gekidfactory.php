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

class CGekidFactory
{

   static public function createObject( $type, $implementation_version, $smarty, $db )
   {
      $object = null;
      $version_str = str_replace( ".", "_", $implementation_version );
      $class = 'CGekid' . $type . $version_str;
      switch( $implementation_version ) {
         case '1.06' :
            require_once strtolower( 'class.gekid'. $type . $version_str . '.php' );
            $object = new $class;
            $object->create( $smarty, $db );
            break;
         default :
            throw new Exception( "Gekid Klasse $class nicht gefunden." );
            break;
      }
      return $object;
   }

}

?>