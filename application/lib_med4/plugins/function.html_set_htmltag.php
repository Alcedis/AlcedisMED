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

function smarty_function_html_set_htmltag( $params, &$smarty )
{
   $string         = '';
   $cmp1           = '';
   $cmp2           = '';
   $tag            = 'font color=red';

   foreach( $params AS $_key => $_val )
      $$_key = $_val;

   $return = $string;
   if( $cmp1 == $cmp2 )
      $return = '<' . $tag . '>' . $string. '</' . $tag . '>';

   return $return;
}

?>
