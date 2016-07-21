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


/**
 * smarty_function_xmlTag
 *
 * @param   array   $params
 * @param   Smarty  $smarty
 * @return  string
 */
function smarty_function_xmlTag($params, $smarty)
{
    $value = null;
    $name  = $params['name'];
    $field = strtolower($name);
    $data  = $params['value'];
    $render = array_key_exists('render', $params) === true ? $params['render'] : false;

    if (is_array($data) === true) {
        if (array_key_exists($field, $data) === true) {
            $value = $data[$field];
        }
    } else {
        $value = $data;
    }

    if ($value !== null || ($render === true && $value === null)) {
        // replace tags
        $value = str_replace(
            array("&",     "<",    ">",    '"',      "'"     ),
            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"),
            $value
        );

        $value = "<{$name}>{$value}</{$name}>";
    }

    return $value;
}

?>
