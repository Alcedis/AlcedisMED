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

function getDbTables($smarty)
{
   $smarty->config_load( FILE_CONFIG_SERVER, 'database');
   $config = $smarty->get_config_vars();

   $tables = array();

   $result = mysql_list_tables($config['db_name']);

   while ($row = mysql_fetch_row($result)) {
      //Archiv Tabellen
      if(strpos(end($row), '_') === 0) {
         continue;
      }

      $tables['tbl_' . end($row)] = end($row);
   }

   return $tables;
}


function getMappedLookup($db, $table = '', $targetField = null, $srcField = '', $lookup = null, $lower = false)
{
   $output = array();

   if (strlen($table) > 0) {

      $mapIt = false;
      $where = 'WHERE ';
      $targetField = strlen($targetField) > 0 ? "{$targetField} AS 'field'" : '*';

      if (is_array($lookup) === true && strlen($srcField) > 0 && strlen($targetField) > 0) {
         //zu mappendes Lookup entweder voll oder leer
         $mapIt = true;

         $where         .= count($lookup) > 0 ? "{$srcField} IN ('" . implode("','", $lookup) . "')"   : "{$srcField} IS NOT NULL";
         $targetField   .= ", {$srcField} AS 'src'";

      } elseif (is_string($lookup) === true && strlen($srcField) > 0) {

      //TODO


      } elseif ($lookup === null && strlen($targetField) > 0) {

         //TODO
      }

      $query   = "SELECT {$targetField} FROM `{$table}` {$where}";
      $result  = sql_query_array($db, $query);

      if ($mapIt === true && count($result) > 0) {
         foreach ($result AS $map) {
            $output[$map['src']] = $lower === true ? strtolower($map['field']) : $map['field'];
         }
      }
   }

   return $output;
}





function getLookup($db, $class = null, $table = 'l_basic')
{
   $return = array();

   if ($class !== null) {
      $data = sql_query_array($db, "SELECT code, bez FROM {$table} WHERE klasse = '{$class}'");

      foreach  ($data as $dataset) {
         $return[$dataset['code']] = $dataset['bez'];
      }
   } else {
      $data = sql_query_array($db, "SELECT klasse, code, bez FROM {$table}");

      foreach  ($data as $dataset) {
         $return[$dataset['klasse']][$dataset['code']] = $dataset['bez'];
      }
   }

   return $return;
}

function getData($data, $takeDefault = false, $default = '-') {
   $string = strlen(trim($data)) ? "'" . unescape(mysql_real_escape_string(htmlspecialchars_decode($data))) . "'"
   : (
      $takeDefault == true
      ? "'{$default}'"
      : 'NULL'
   );

   return $string;
}


?>
