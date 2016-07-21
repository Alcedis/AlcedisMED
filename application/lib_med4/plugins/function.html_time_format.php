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

function smarty_function_html_time_format($params, &$smarty)
{
   $time		  = NULL;
   $transform = array(25=>':15', 5=>':30', 75=>':45');

   extract( $params );

   $time = explode('.', $time);

   while( strlen( $time[0] ) < 2 AND strlen( $time[0] ) )
      $time[0] = '0' . $time[0];

   if( !strlen( $transform[$time[1]] ) AND strlen( $time[0] ) )
      $transform[$time[1]] = ':00';

   $time = $time[0] . $transform[$time[1]];
   return ( strlen($time) ) ? $time : '&nbsp;';
}

?>
