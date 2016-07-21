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

function smarty_function_html_set_text_color($params, &$smarty)
{
   $text        = NULL;
   $compare	    = NULL;
   $sep         = '<br>';
   $color_true  = '#33CC33';
   $color_false = 'red';

   extract( $params );

   $arr_text = explode( $sep, $text );
   $arr_text = ( is_array( $arr_text ) ) ? $arr_text : array( $text );

   foreach( $arr_text AS $key => $value )
   {
      if( strpos( $arr_text[$key], $compare )!==false )
         $arr_text[$key] = "<font color=$color_true>" . $arr_text[$key] . '</font>';
      else
         $arr_text[$key] = "<font color=$color_false>" . $arr_text[$key] . '</font>';
   }

   return implode( $sep, $arr_text );
}

?>
