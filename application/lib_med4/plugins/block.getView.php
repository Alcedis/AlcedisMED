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

function smarty_block_getView($params, $content, &$smarty, &$repeat)
{
    $condition = true;

    if (array_key_exists('field', $params) === true) {
        $fields = explode(',', $params['field']);

        $tmpCondition = false;

        $wFields = $smarty->widget->getFields(true);

        foreach ($fields as $field) {
            if (strpos($field, 'dlist_') !== false) {
                if($smarty->widget->getView($field) != 'hide') {
                    $tmpCondition = true;
                }
            } else {
                if (array_key_exists($field, $wFields) === true) {
                    $tmpCondition = true;
                }
            }
        }

        $condition = $tmpCondition;
    }

    if (isset($content) === true && $condition === true) {
        return $content;
    }
}


?>