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

function smarty_function_cda_tag_qualifiers( $params, &$smarty )
{
   $cda_tags = array(
      'name' => array( 'code', 'codeSystem' ),
   	  'value' => array( 'code', 'codeSystem' )
   );
   $xml = "";

   if ( ( count( $params ) > 0 ) && isset( $params[ 'tag_params' ] ) ) {
      $tag_name = "qualifier";
      $tag_params = isset( $params[ 'tag_params' ] ) ? $params[ 'tag_params' ] : array();
      // Wenn Tag in simple Tags ist...
      if ( count( $tag_params ) > 0 ) {
         foreach( $tag_params as $qualifier => $tag_q_params ) {
            $xml .= "<$tag_name>\n";
            foreach( $tag_q_params as $tag_q_name => $tag_q_value ) {
               $tmp = array( 'tag_params' => $tag_q_params[ $tag_q_name ] );
               $vaild_q_params = __GetValidCdaTags( $tag_q_name, $tmp, $cda_tags[ $tag_q_name ] );
               if ( count( $vaild_q_params ) > 0 ) {
                  $xml .= "    <$tag_q_name ";
                  foreach( $vaild_q_params as $attr_name => $attr_value ) {
                     $xml .= "$attr_name=\"$attr_value\" ";
                  }
                  $xml .= "/>\n";
               }
            }
            $xml .= "</$tag_name>\n";
         }
      }
   }
   return $xml;
}

?>
