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

$getData = isset($_REQUEST['getData']) && $_REQUEST['getData'] == true ? true : false;

$whereUser = '';

if (isset($rolle_code) === true && $rolle_code !== 'admin') {
   $whereUser = "WHERE user.user_id NOT IN ($admin) AND user.inaktiv IS NULL";
}

$query = "
   SELECT
      DISTINCT user.user_id,
      user.loginname,
      user.nachname,
      user.vorname,
      a.bez       AS 'fachabteilung'
   FROM user user
      LEFT JOIN l_basic t ON t.klasse='anrede'        AND t.code=user.anrede
      LEFT JOIN l_basic a ON a.klasse='fachabteilung' AND a.code=user.fachabteilung
   $whereUser
";


$fieldFile = end(explode('.', isset($_REQUEST['parentPage'])  ? $_REQUEST['parentPage']  : ''));
$field     = isset($_REQUEST['targetField']) ? $_REQUEST['targetField'] : '';
$formId    = isset($_REQUEST["{$fieldFile}_id"]) == true && strlen($_REQUEST["{$fieldFile}_id"]) > 0 ? $_REQUEST["{$fieldFile}_id"] : '';

if (strlen($fieldFile) && strlen($formId)) {
   foreach ($appOrder as $app) {
      $folder = $app['folder'];

      $fieldFilePath = "fields/$folder/{$fieldFile}.php";

      if (file_exists($fieldFilePath) === true ){
         include $fieldFilePath;
         break;
      }
   }

   $preselected = isset($fields[$field]['preselect']) == true ? $fields[$field]['preselect'] : false;

   if (isset($fields[$field]) == true && $preselected !== false && strpos($query, 'WHERE') !== false) {
      $value       = dlookup($db, $fieldFile, $field, "{$fieldFile}_id = '{$formId}'");

      if (strlen($value) > 0) {
         $replace = "WHERE ({$preselected} = '{$value}') OR ";
         $query = str_replace('WHERE', $replace, $query);
      }
   }
}

if ($getData === true) {
   $query .= "AND user.user_id IN ('" . (isset($_REQUEST['values']) ? implode("','", $_REQUEST['values']) : '') . "')";
}

$query .= "ORDER BY user.nachname, user.vorname";

$data  = sql_query_array($db, $query);

if ($getData === true) {
   echo create_json_string($data);
   exit;
} else {

   $multi = isset($_REQUEST['multi']) ? (string) $_REQUEST['multi'] : 'false';

   $smarty
      ->assign('data', $data)
      ->assign('preSelection', isset($_REQUEST['preSelection']) ? $_REQUEST['preSelection'] : array())
      ->assign('multi', $multi);
}
?>