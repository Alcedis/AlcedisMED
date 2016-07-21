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

function smarty_function_html_set_html($params, &$smarty)
{
   $htmlReturn = '';
   $visible    = false;

   extract( $params );

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
      $htmlReturn = $html;
   }

   return $htmlReturn;
}

?>
