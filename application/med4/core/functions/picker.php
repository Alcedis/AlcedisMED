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

function insertDData($db, $table, $foreignKey, $dataField, $values)
{
   $sep = "'";
   $fakeFields = array();
   $return = true;
   foreach($foreignKey as $key)
      $fakeFields[$key] = $sep . (isset($_REQUEST[$key]) ? $_REQUEST[$key] : '') . $sep;
   $fakeFields[$dataField] = '';
   $fakeFields['createuser'] = $sep . $_SESSION['sess_user_id'] . $sep;
   $fakeFields['createtime'] = 'NOW()';
   if (isset($_REQUEST[$values])) {
      foreach ($_REQUEST[$values] as $record) {
         $fakeFields[$dataField] = $sep . $record . $sep;
         $fieldFields = array_keys($fakeFields);
         $queryString = "INSERT INTO $table(" . implode(',', $fieldFields) . ") VALUES(" . implode(',', $fakeFields) . ")";
         mysql_query($queryString, $db);

         if (mysql_error())
            $return = false;
      }
   }
   return $return;
}

function updateDData($db, $table, $delKey, $delVal, $foreignKey, $dataField, $values)
{
   $delete = deleteDData($db, $table, $delKey, $delVal);
   if ($delete)
    return insertDData($db, $table, $foreignKey, $dataField, $values);
}

function deleteDData($db, $table, $field, $value)
{
   $query = "DELETE FROM $table WHERE $field = '$value'";
   mysql_query($query, $db);
   if (mysql_error())
      return false;
   else
      return true;
}

function loadDData($db, $table, $getField, $keyName, $keyValue, $altRequest)
{
   $return        = array();
   $implodeFields = array();

   if (!isset($_REQUEST[$altRequest]) && strlen($keyValue)) {
      foreach (sql_query_array($db, "SELECT $getField FROM $table WHERE $keyName='$keyValue'") as $record) {
         $implodeFields[] = $record[$getField];
      }
   } else {
      $implodeFields = isset($_REQUEST[$altRequest]) === true ? $_REQUEST[$altRequest] : array();
   }

   if (count($implodeFields) > 0){
      $data = sql_query_array($db, "SELECT
               user_id,
               CONCAT_WS(' ', t.bez, u.titel) AS 'prefix',
               u.nachname,
               u.vorname,
               a.bez       AS 'fachabteilung'
            FROM user u
               LEFT JOIN l_basic t ON t.klasse='anrede' AND t.code=u.anrede
               LEFT JOIN l_basic a ON a.klasse='fachabteilung' AND a.code=u.fachabteilung
            WHERE user_id IN ('" . implode("','", $implodeFields) . "')
      ");

      foreach ($data as $values){
         $return[] = $values;
      }
   }

   return $return;
}

?>