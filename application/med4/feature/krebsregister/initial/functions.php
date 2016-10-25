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
 * prepareKrQuery
 *
 * @param   string $query
 * @param   array  $params
 * @return  mixed
 */
function prepareKrQuery($query, array $params = array())
{
    foreach ($params as $key => $value) {
        $query = str_replace('{#' . $key . '#}', $value, $query);
    }

    return $query;
}

/**
 * parseXmlErrors
 *
 * @param   string  $encodedString
 * @return  array
 */
function parseXmlErrors(&$encodedString, $show)
{
    $sectionErrors = unserializeBase64($encodedString);

    $encodedString = array();

    foreach ($sectionErrors as $error) {
        if (('error' == $show && 2 == $error['level']) ||
            ('warning' == $show && 1 == $error['level'])) {
            $encodedString[] = $error['message'];
        }
    }
}


function unserializeBase64($string)
{
    return unserialize(base64_decode($string));
}

function array2ul($array, $level = 0) {

    $out = "<ul class='register-message-section level-{$level}'>";

    foreach($array as $key => $elem){
        if(!is_array($elem)){
            $out=$out."<li><span class='key'>{$key} : {$elem}</span></li>";
        }
        else $out=$out."<li>{$key}".array2ul($elem, $level + 1)."</li>";
    }

    return $out ."</ul>";
}
