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

function convertSearchString($string = '')
{
   $sString          = str_replace("'", "", stripslashes($string));
   $searchStrings    = explode(' ', $sString);

   foreach ($searchStrings as $index => &$string) {
      $string = trim($string);

      if (strlen($string) === 0) {
         unset($searchStrings[$index]);
      }
   }

   return $searchStrings;
}



function createrPickerTop10($db, $form, $field)
{
    $table = end(explode('.', $form));
    $fieldsFile = "fields/app/$table.php";

    if (file_exists($fieldsFile)) {

        //$org_id = isset($_SESSION['sess_org_id']) ? $_SESSION['sess_org_id'] : ''; //noch nicht!!!
        $erkrankung_id = isset($_SESSION['sess_erkrankung_data']['erkrankung_id']) ? $_SESSION['sess_erkrankung_data']['erkrankung_id'] : '';
        $erkrankung    = isset($_SESSION['sess_erkrankung_data']['code']) ? $_SESSION['sess_erkrankung_data']['code'] : '';

        //variablen, die in den queries gebraucht werden... Ò_ó
        $org_id = isset($_SESSION['sess_org_id']) ? $_SESSION['sess_org_id'] : '';
        $patient_id = isset($_SESSION['sess_patient_id']) ? $_SESSION['sess_patient_id'] : '';
        $date = '';
        $erkrankung_tables = array();

        include 'core/initial/queries.php';

        include $fieldsFile;
    } else {
        return false;
    }

    $type      = $fields[$field]['type'];
    $icdo3Type = isset($fields[$field]['ext']['type']) !== false        ? $fields[$field]['ext']['type']                : null;
    $limit     = isset($_SESSION['settings']['codepicker_top_limit'])   ? $_SESSION['settings']['codepicker_top_limit'] : 10;
    $lTable    = 'l_';
    $vorlage   = 'vorlage_';

    switch($type) {
        case 'code_icd':

            $lTable  .= 'icd10';
            $vorlage .= 'icd10';

            break;

        case 'code_ops':

            $lTable  .= 'ops';
            $vorlage .= 'ops';

            break;

        case 'code_o3' :

            $lTable  .= 'icdo3';
            $vorlage .= 'icdo';

            break;
        default : return false;              break;
    }

    $allFields = array($field);

    foreach ($fields as $singleField => $singleFieldData) {
      if ($singleFieldData['type'] == $type && $singleField !== $field) {

         if ($icdo3Type !== null) {
            if (isset($singleFieldData['ext']['type']) === true && $singleFieldData['ext']['type'] === $icdo3Type) {
               $allFields[] = $singleField;
            }
         } else {
            $allFields[] = $singleField;
         }
      }
    }

    if ($table == 'nachsorge') {
     $join = "INNER JOIN nachsorge_erkrankung ne ON ne.nachsorge_id = $table.nachsorge_id
                 INNER JOIN erkrankung e ON e.erkrankung_id = ne.erkrankung_weitere_id AND e.erkrankung = '$erkrankung'";
    } else {
     $join = "INNER JOIN erkrankung e ON $table.erkrankung_id = e.erkrankung_id AND e.erkrankung = '$erkrankung'";
    }

    $top10Query = array();

    $innerJoin = strlen($erkrankung) ? $join : null;

    foreach ($allFields as $codeField) {
        $query = "
            SELECT
                {$table}.{$codeField} AS code

            FROM {$table}
                {$innerJoin}
            WHERE
                {$table}.{$codeField} IS NOT NULL
        ";

        $top10Query[] = $query;
    }

    foreach ($top10Query as $top) {
        $data[] = sql_query_array($db, $top);
    }

    $countedData = array();

    if (count($data) > 0) {
        // flat multidimensional array
        $flatData = array();
        foreach ($data as $value) {
            foreach ($value as $key => $val) {
                $new_key    = array_keys($val);
                $flatData[] = $val[$new_key[0]];
            }
        }
        foreach (array_count_values($flatData) as $code => $count) {
            $countedData[$count][] = $code;
        }
    }

    $data = array();

    krsort($countedData);
    foreach ($countedData as $values) {
        foreach ($values as $value) {
            $data[] = array($value);
            $limit --;

            if ($limit === 0) {
                break 2;
            }
        }
    }

    foreach ($data as &$dataset) {
        $code =  $dataset[0];

        $codeVorlage  = dlookup($db, $vorlage, 'bez', "code ='{$code}'");

        $dataset['code'] = $code;
        $dataset['description'] = strlen($codeVorlage) > 0 ?
            $codeVorlage :
            dlookup($db, $lTable, 'description', "code = '{$code}'" . ($lTable == 'l_icdo3' ? " AND sub_level IN ('c', 'v')" : ''))
        ;
    }

    return $data;
}
?>
