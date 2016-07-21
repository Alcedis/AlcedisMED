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

function __GetValidCdaTags( $tag_name, $tag_params, $param_list )
{
   $result_params = array();

   if ( is_array( $tag_params ) && isset( $tag_params[ 'tag_params' ] ) && is_array( $tag_params[ 'tag_params' ] ) ) {
      foreach( $tag_params[ 'tag_params' ] as $attr_name => $attr_value ) {
         if ( array_search( $attr_name, $param_list ) !== false ) {
            if ( is_array( $attr_value ) || ( strlen( $attr_value ) > 0 ) ) {
               $result_params[ $attr_name ] = $attr_value;
            }
            else if ( $attr_name == "country" ) {
               $result_params[ $attr_name ] = $attr_value;
            }
         }
         else {
            die( "FATAL: [$attr_name] is not a valid attribute from [$tag_name] cda tag." );
         }
      }
   }
   return $result_params;
}

function smarty_function_cda_tag( $params, &$smarty )
{
   $cda_simple_tags = array(
      'typeId' => array( 'root', 'extension' ),
      'templateId' => array( 'root', 'extension' ),
      'id' => array( 'root', 'extension' ),
      'setId' => array( 'root', 'extension' ),
      'assignedAuthoringDevice' => array( 'root', 'extension' ),
      'realmCode' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'code' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'confidentialityCode' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'languageCode' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'statusCode' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'administrativeGenderCode' => array( 'code', 'codeSystem', 'displayName', 'nullFlavor' ),
      'versionNumber' => array( 'value' ),
      'effectiveTime' => array( 'value' ),
      'birthTime' => array( 'value' ),
      'deceasedTime' => array( 'value', 'nullFlavor' ),
      'telecom' => array( 'use', 'value' ),
      'time' => array( 'value', 'nullFlavor' ),
      'high' => array( 'value' ),
      'low' => array( 'value' ),
   );
   $cda_extended_tags = array(
      'addr' => array( 'streetName', 'houseNumber', 'postalCode', 'city', 'postBox', 'country' ),
      'name' => array( 'prefix', 'prefix:qualifier="VV"', 'given', 'family', 'family:qualifier="BR"', 'family:validityRange' )
   );
   $xml = "";

   if ( ( count( $params ) > 0 ) && isset( $params[ 'tag_params' ] ) ) {
      // Tag Namen extrahieren...
      $tag_name = isset( $params[ 'tag_name' ] ) ? $params[ 'tag_name' ] : '';
      $tag_params = isset( $params[ 'tag_params' ] ) ? $params[ 'tag_params' ] : array();
      if ( strlen( $tag_name ) > 0 ) {
         // Wenn Tag gefüllt...
         if ( isset( $cda_simple_tags[ $tag_name ] ) ) {
            // Wenn Tag in simple Tags ist...
            $vaild_params = __GetValidCdaTags( $tag_name, $params, $cda_simple_tags[ $tag_name ] );
            if ( count( $vaild_params ) > 0 ) {
               $xml = "<$tag_name ";
               foreach( $vaild_params as $attr_name => $attr_value ) {
                  $xml .= "$attr_name=\"$attr_value\" ";
               }
               $xml .= "/>\n";
            }
         }
         else if ( isset( $cda_extended_tags[ $tag_name ] ) ) {
            // Wenn Tag in extended Tags ist...
            $vaild_params = __GetValidCdaTags( $tag_name, $params, $cda_extended_tags[ $tag_name ] );
            if ( count( $vaild_params ) > 0 ) {
               $xml = "<$tag_name>\n";
               foreach( $vaild_params as $attr_name => $attr_value ) {
                  $a = split( ":", $attr_name );
                  $attr_name = $a[ 0 ];
                  if ( ( $attr_name == "country" ) &&
                       ( ( $attr_value == NULL ) ||
                         ( strlen( $attr_value ) == 0 ) ) ) {
                     $xml .= "<$attr_name nullFlavor=\"UNK\" />\n";
                  }
                  else {
                     $qualifier = "";
                     // Auf qualifier prüfen und eintragen...
                     if ( count( $a ) == 2 ) {
                        $qualifier = " " . $a[ 1 ];
                     }
                     $xml .= "<$attr_name$qualifier>$attr_value</$attr_name>\n";
                  }
               }
               $xml .= "</$tag_name>\n";
            }
         }
         else {
            die( "FATAL: [$tag_name is not a valid cda tag." );
         }
      }
   }
   return $xml;
}

?>
