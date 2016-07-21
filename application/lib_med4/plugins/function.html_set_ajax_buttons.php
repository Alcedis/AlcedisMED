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

function smarty_function_html_set_ajax_buttons($params, &$smarty)
{
	$smarty->config_load( FILE_CONFIG_DEFAULT, 'validator');
	$config  = $smarty->get_config_vars();

	$modus    = '';

	$align    = isset( $params['align']  ) ? $params['align']  : 'center';
	$height   = isset( $params['height'] ) ? $params['height'] : '20px';
	$icon     = '';
	$href     = '';
	$table    = true;


   extract( $params );

	$extern_begin_tag = '<table class="ajax-button-table" style="width: 100%; height: ' . $height . '; margin: -2px 0px 0px 0px; padding: 0px;" cellspacing="1" cellpadding="0"><tr>';
	$extern_end_tag   = '</tr></table>';

	$intern_begin_tag = '<td style="margin: 0px; padding: 0px;" align="' . $align . '">';
	$intern_end_tag   = '</td>';

	$arr_modus = explode( '.', $modus );

	$button    = array();

	if( $table )
		$return = $extern_begin_tag . $intern_begin_tag;
	else
		$return = '';

   foreach( $arr_modus AS $modus )
	{
      if($modus == 'delete')     //Es gibt bei den Posformularen kein delete Button, das übernimmt die Tabelle im Hauptformular
         continue;

	   if( strlen ($href) )
		{
			$config_lbl  = strlen($modus) ? $config[$modus] : $config['lbl_add'];

			$button[] = '<div style="padding: 0px; margin: 0px;"><a href="' . $href . '">' . $config_lbl . '</a></div>';
		}
		elseif( strlen ($icon) )
		{
			$config_lbl  = $config["btn_lbl_$modus"];
			$config_icon = $config[$icon];

			$button[] =
					'<button class="button" style="padding: 0px; margin: 0px;">'
   			.	'	<table style="width: 100%">'
   			.	'	<tr>'
   			.	'		<td style="0px; width: 20%; text-align: center;"><img src="' . $config_icon . '"></td>'
   			.	'		<td style="0px; width: 80%; text-align: center; font-size: 9pt; font-weight: bold;">' . $config_lbl . '</td>'
   			.	'	</tr>'
   			.	'	</table>'
				.	'</button>';
		}

		elseif( isset( $config["btn_lbl_$modus"] ) )
		{
			$config_lbl = $config["btn_lbl_$modus"];
			$button[] = '<input class="button" type="button"  name="' . $modus . '" value="' . $config_lbl . '" alt="' . $config_lbl . '">';
		}
		elseif( isset( $config[$modus] ) )
		{
		   $config_lbl = $config[$modus];
			$button[]   = '<input class="button" type="button"  name="'. $modus . '" value="' . $config_lbl . '" alt="' . $config_lbl . '">';
		}
	   elseif( strpos('img-', $modus) == 0) {
         $modus = substr($modus, 4);

         $button[]   = "<div style='margin-top:13px'><img src='media/img/base/$modus.png' alt='' /></div>";
      }
	}

	if( $table )
		$return .= implode('&nbsp;&nbsp;', $button) . $intern_end_tag . $extern_end_tag;
	else
		$return .= implode('&nbsp;&nbsp;', $button);

	return $return;
}

?>
