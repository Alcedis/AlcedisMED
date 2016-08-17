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

$location = get_url('page=list.patient');

if ($permission->action($action) === true) {
   $permission->setActionFilePath('feature/hl7/scripts/action/list.hl7_diagnose.php');
   require($permission->getActionFilePath());
}

if (isset($_SESSION['sess_patient_data']) === true) {
   unset($_SESSION['sess_patient_data']);
}

require_once 'feature/hl7/fields/list.hl7_diagnose.php';

$orderBy       = "nachname";
$where         = "p.org_id = '{$org_id}' ";
$rechtGlobal   = dlookup($db, 'recht', 'recht_global', "recht_id = '$_SESSION[sess_recht_id]'");

if (count($_SESSION['sess_recht_erkrankung_bez']) && ($rolle_code == 'supervisor' && $rechtGlobal == 1) == false) {
    $erkWhere = array();

    $rechtErk = $_SESSION['sess_recht_erkrankung_bez'];

    if (array_key_exists('sess_recht_erkrankung', $_SESSION) && in_array('sst', $_SESSION['sess_recht_erkrankung'])) {

       $sstDetail = dlookup($db, 'l_basic', 'GROUP_CONCAT(bez)', "klasse = 'erkrankung_sst_detail' GROUP BY klasse");

       $rechtErk = array_merge($rechtErk, explode(',', $sstDetail));
    }

    foreach ($rechtErk as $checkerk)
        $erkWhere[] = "p.erkrankungenbez LIKE '%$checkerk%'\n";

    $where .= 'AND (' . implode(' OR ', $erkWhere) . ')';
}

$groupBy       = "p.patient_id, p.datum";

$searchFields  = array(
   'vorname'       => array('type' => 'string', 'field' => 'vorname'),
   'nachname'      => array('type' => 'string', 'field' => 'nachname'),
   'geburtsdatum'  => array('type' => 'date',   'field' => 'geburtsdatum'),
   'patient_nr'    => array('type' => 'string', 'field' => 'patient_nr'),
   'diagnosen'     => array('type' => 'string', 'field' => 'diagnosen'),
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie(cookie::create($user_id, $pageName))
   ->setQuery($querys['hl7_diagnose'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setGroupBy($groupBy)
   ->setWhere($where)
;

data2list($db, $fields, $queryMod->query());

$erkList = '';

if (isset($fields['erkrankungen']['value']) === true) {

   foreach ($fields['erkrankungen']['value'] as $erkListAdd) {
      if (strlen($erkListAdd) > 0) {
         $erkList .= strlen($erkList) == 0 ? $erkListAdd : ',' . $erkListAdd;
      }
   }

   $erkList = implode(',', array_unique(explode(',', $erkList)));

   $rechtErkrankungSess = isset($_SESSION['sess_recht_erkrankung']) === true ? implode("','", $_SESSION['sess_recht_erkrankung']) : null;

   $erkrankungQuery = sql_query_array(
      $db,
      $querys['hl7_diagnose_erkrankung'] . "WHERE e.erkrankung_id IN ($erkList) AND e.erkrankung IN ('{$rechtErkrankungSess}')"
   );

   foreach ($fields['erkrankungen']['value'] as &$erkListAdd) {

      if (strlen($erkListAdd) > 0) {
         $values = explode(',', $erkListAdd);

         $tmpOptions = array();

         foreach ($values as $val) {
            foreach ($erkrankungQuery as $result) {
               if ($result['erkrankung_id'] == $val) {
                  $tmpOptions[$val] = $result['bez'];
               }
            }
         }

         natcasesort($tmpOptions);

         if (count($tmpOptions) > 0) {
            $erkListAdd = '';

            foreach ($tmpOptions as $val => $bez) {
               $erkListAdd .= "<option value='{$val}'>{$bez}</option>";
            }
         }
      }
   }
}

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=list.patient')
;

?>