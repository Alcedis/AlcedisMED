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

function smarty_function_html_delete_last_entry( $params, &$smarty )
{
	$page	          = NULL;
	$value          = NULL;
	$max            = NULL;
	$primary_field  = NULL;
	$arr_roles      = NULL;
	$monitor_signed = NULL;
	$arzt_signed    = NULL;
	$delete         = true;

   extract( $params );

	if( !strlen( $page ) )          return false;
	if( !strlen( $value ) )         return false;
	if( !strlen( $max ) )           return false;
	if( !strlen( $primary_field ) ) return false;

	$smarty->config_load( FILE_CONFIG_DEFAULT );
	$config = $smarty->get_config_vars();
	$class  = $smarty->get_template_vars( 'class' );

	if( is_array( $arr_roles ) )
	   if( !in_array( $_SESSION['sess_rolle_code'], $arr_roles ) )
	      $delete = false;

   // Wenn von MONITOR unterschrieben, nicht mehr löschbar
   if( strpos( $monitor_signed, 'uncheck' ) !== false )
      $delete = $delete;
   elseif( strpos( $monitor_signed, 'check' ) !== false )
      $delete = false;

   // Wenn von ARZT unterschrieben, nicht mehr löschbar
   if( strpos( $arzt_signed, 'uncheck' ) !== false )
      $delete = $delete;
   elseif( strpos( $arzt_signed, 'check' ) !== false )
      $delete = false;

	if( $value != $max OR $delete == false )
		return '<td class="' . $class . '" align="center"></td>';

   return '<td class="' . $class . '" align="center"><a href="' . $page . '&amp;' . $primary_field . '=' . $max . '&amp;action=delete"><img border="0" src="' . $config['src_ico_delete'] . '"></a></td>';
}

?>
