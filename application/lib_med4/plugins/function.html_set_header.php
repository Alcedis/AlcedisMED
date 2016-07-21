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

function smarty_function_html_set_header($params, &$smarty)
{
   $caption	= '<font class="star">SET_HEADER: HEADER UNDEFINED IN CONFIG</font>';
   $class	= 'sub_head';
   $colspan = '2';
   $button  = '';
   $visible = false;

   extract($params);

   $config = $smarty->get_config_vars();

   $fields = $smarty->widget->getFields(true);

   if (isset($field)) {
      $field = explode(',', $field);

      if (count($field) > 0) {
         foreach ($field as $key => $fieldname) {
            if (isset($fieldname) && array_key_exists($fieldname, $fields) === true) {
               $visible = true;
            }
         }
      }
   } else {
      $visible = true;
   }

   if ($visible === true) {
      if (in_array($class, array('msgbox', 'warn', 'err'))) {
          return "<tr><td class='msg' colspan='2'><div class='$class'>$caption</div></td></tr>";
      } else if (strlen($button)) {
         $image  = isset($config['src_'     . $button]) ? $config['src_'     . $button] : '';
         $value  = isset($config['btn_lbl_' . $button]) ? $config['btn_lbl_' . $button] : '';
         $button = $value . ' <input type="image" name="action[' . $button . ']" src="' . $image . '" value="' . $value . '">';

         return '
            <tr>
               <td class="' . $class . '" colspan="' . $colspan . '">
                  <div style="float: left">' . $caption . '</div>
                  <div style="float: right">' . $button . '</div>
               </td>
            </tr>
         ';
      } else {
         return '<tr><td class="' . $class . '" colspan="' . $colspan . '">' . $caption . '</td></tr>';
      }
   }
}

?>
