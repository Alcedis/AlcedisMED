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

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if (isset($_SESSION['sess_patient_data']) === true) {
   unset($_SESSION['sess_patient_data']);
}

//Overwrite fields
$fields['erkrankung']   = array('type' => 'string');
$fields['erklist']      = array('type' => 'string');

$fields['id']           = array('type' => 'string');
$fields['hl7']          = array('type' => 'string');

$cookie = cookie::create($user_id, $pageName);

$orderBy    = "p.nachname";
$groupBy    = isset($groupBy) === true ? $groupBy : "p.patient_id";
$where      = "p.org_id = {$org_id} ";

$searchFields = array(
   'vorname'       => array('type' => 'string','field'  => 'p.vorname'),
   'nachname'      => array('type' => 'string','field'  => 'p.nachname'),
   'geburtsdatum'  => array('type' => 'date',  'field'  => 'p.geburtsdatum'),
   'patient_nr'    => array('type' => 'string','field'  => 'p.patient_nr'),
   'aufnahme_nr'   => array('type' => 'string','field'  => 'p.aufnahme_nr'),
   'createtime'    => array('type' => 'date',  'field'  => 'p.createtime_en'),
   'erkrankung'    => array('type' => 'string', 'field' => 'p.erklist')
);

$hl7Active = appSettings::get('active', 'hl7');

if ($hl7Active !== true) {
   $querys['patient_import'] = "
      SELECT
         p.patient_id,
         p.org_id,
         p.patient_id AS id,
         p.nachname,
         p.vorname,
         p.geburtsdatum,
         p.createtime,
         p.patient_nr,
         p.aufnahme_nr,
         p.erk AS erkrankung,
         p.erklist,
         p.createtime_en
      FROM ({$querys['patient_import']} GROUP BY p.patient_id) p
   ";
}

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['patient_import'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setTable('patient')
   ->setWhere($where)
   ->setGroupBy($groupBy)
;

//Hl7 nicht Initialisiert
if ($hl7Active !== true) {
   $having = array(1);

   //Ausschlusskriterium
   foreach (explode("','", $queryRechtErkrankung) as $rechtErkrankung) {
      $having[] = "LOCATE('-{$rechtErkrankung}-', p.erk) = 0";
   }

   $queryMod
      ->setHaving(implode(' AND ', $having))
   ;
}

//Wenn keine Patienten vorhanden sind, auf patienten Registrierungsformular wechseln
if ($bfl === null) {
   $fullCount = "
      SELECT
         COUNT(DISTINCT p.patient_id) AS 'count'
      FROM patient p
         LEFT JOIN erkrankung e ON e.patient_id = p.patient_id AND e.erkrankung IN ('{$queryRechtErkrankung}')
      WHERE
         {$where} AND e.erkrankung_id IS NULL AND p.org_id = '{$org_id}'
   ";

   //Hl7 initialisiert
   if ($hl7Active === true) {
      $fullCount = "
         SELECT
            SUM(p.count) AS 'count'
         FROM (
            (
               {$fullCount}
            ) UNION (
               SELECT
                  COUNT(DISTINCT p.hl7_cache_id) AS 'count'
               FROM hl7_cache p
               WHERE {$where}
            )
         ) p
      ";
   }

   $result = $queryMod->getFullDatasetCount($fullCount);

   if ($result == 0 && $action === null) {
      action_cancel('index.php?page=rec.patient&no_import');
   }
}

data2list($db, $fields, $queryMod->query());

//HL7 ID verarbeiten
if (isset($fields['id']['value']) === true) {
   foreach ($fields['id']['value'] as &$hl7Id) {
      $id = explode('|', $hl7Id);

      if (count($id) > 1) {
         foreach ($id as $d) {
            if (strpos($d, 'hl7') !== false) {
               $hl7Id = $d;
               break;
            }
         }
      }
   }
}

//Neuer Hinzufügen Button

if ($bfl === null) {
    $arr_menubar['patient_import']['custom'][] = "<a href='index.php?page=rec.patient' class='button_large'>{$config['btn_lbl_create_patient']}</a>";

    $arr_menubar['patient_import']['custom'][] = "
        <div class='bfl-count'>
            {$config['lbl_anz_pat']}<span class='count'>" . $queryMod->getFullDatasetCount(null, 'patient_id') . "</span>
            /
            {$config['lbl_anz_filter']}<span class='filter'>" . $queryMod->getDatasetCount() .  "</span>
        </div>
   ";

   $smarty->assign('back_btn', 'page=list.patient');

}

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
;

?>
