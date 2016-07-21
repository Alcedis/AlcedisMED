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

/*

   ACHTUNG: Die Funktion __GetValidCdaTags wird schon in function.cda_tag_value_head.php deklariert und
            cda_tag wird vor cda_tag_value_head aufgerufen!!!

*/

function smarty_function_cda_tag_value_head( $params, &$smarty )
{
   $cda_tags = array(
   	  'value' => array( 'xsi_type', 'code', 'codeSystem', 'codeSystemName', 'displayName', 'originalText', 'qualifiers', 'nullFlavor', 'value' )
   );
   $xml = "";

   if ( ( count( $params ) > 0 ) && isset( $params[ 'tag_params' ] ) ) {
      $tag_name = "value";
      $tag_params = isset( $params[ 'tag_params' ] ) ? $params[ 'tag_params' ] : array();
      // Wenn Tag in simple Tags ist...
      $vaild_params = __GetValidCdaTags( $tag_name, $params, $cda_tags[ $tag_name ] );
      if ( count( $vaild_params ) > 0 ) {
         $xml = "<$tag_name ";
         foreach( $vaild_params as $attr_name => $attr_value ) {
            if ( ( $attr_name == "originalText" ) ||
                 ( $attr_name == "qualifiers" ) ) {
               continue;
            }
            if ( $attr_name == "xsi_type" ) {
               $attr_name = "xsi:type";
            }
            $xml .= "$attr_name=\"$attr_value\" ";
         }
         $xml .= ">";
      }
   }
   return $xml;
}

?>
