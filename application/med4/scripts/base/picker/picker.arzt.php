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

$fieldFile = end(explode('.', isset($_REQUEST['parentPage'])  ? $_REQUEST['parentPage']  : ''));
$field     = isset($_REQUEST['targetField']) ? $_REQUEST['targetField'] : '';
$select    = "DISTINCT user.user_id, CONCAT_WS(' ', t.bez, user.titel) AS 'prefix', user.nachname, user.vorname, a.bez AS 'fachabteilung'";
$joins     = "LEFT JOIN $tbl_l_basic t ON t.klasse='anrede' AND t.code=user.anrede LEFT JOIN $tbl_l_basic a ON a.klasse='fachabteilung' AND a.code=user.fachabteilung";

if (strlen($fieldFile) > 0) {

   include "fields/app/$fieldFile.php";

   if (isset($fields[$field]) == true) {
      $pickerQuery = $fields[$field]['ext']['query'];

      //replace select and insert join
      $pickerQueryParts = preg_split('~SELECT|FROM~', $pickerQuery);

      $pickerQuery = '';
      foreach($pickerQueryParts as $key => $pickerQueryPart) {
         if ($key == 0) continue;
         if ($key % 2 != 0) {
            $pickerQuery .= " SELECT $select ";
         }else{
            $pickerQuery .= " FROM $pickerQueryPart ";
         }
      }

      $pickerQuery = str_replace('WHERE', "$joins WHERE", $pickerQuery);

      $formId        = isset($_REQUEST["{$fieldFile}_id"]) == true && strlen($_REQUEST["{$fieldFile}_id"]) > 0 ? $_REQUEST["{$fieldFile}_id"] : '';
      $preselected   = isset($fields[$field]['preselect']) == true ? $fields[$field]['preselect'] : false;

      if (strlen($formId) > 0 && strpos($pickerQuery, 'WHERE') !== false && $preselected !== false) {
         $value       = dlookup($db, $fieldFile, $field, "{$fieldFile}_id = '{$formId}'");

         if (strlen($value) > 0) {
            $replace = "WHERE ({$preselected} = '{$value}') OR ";
            $pickerQuery = str_replace('WHERE', $replace, $pickerQuery);
         }
      }
   }
}

$pickerQuery = isset($pickerQuery) ? $pickerQuery : "SELECT
            $select
         FROM user
            INNER JOIN recht r       ON r.user_id=user.user_id
               INNER JOIN org o      ON o.org_id=r.org_id
                                       AND o.org_id='$org_id'
            $joins
         WHERE fachabteilung IS NOT NULL
         ";

if (isset($_REQUEST['getData']) && $_REQUEST['getData'] == true) {
   $pickerQuery = queryModifier::injectStatement($pickerQuery, 'WHERE',
    "user.user_id IN ('" . (isset($_REQUEST['values']) ? implode("','", $_REQUEST['values']) : '') . "') AND");
}

$data  = sql_query_array($db, $pickerQuery);

if (isset($_REQUEST['getData']) && $_REQUEST['getData'] == true) {
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
