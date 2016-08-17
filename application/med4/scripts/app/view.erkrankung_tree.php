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

$searchFields = array(
   'date'        => array('type' => 'date',     'field' => 'form_date'),
   'content'     => array('type' => 'string',   'field' => 'formcontent'),
   'status'      => array('type'  => 'lookup',
                          'class' => 'status',
                          'val'   => 'Status',
                          'field' => 'form_status'
   ),
   'forms'       => array('type' => 'filter', 'field' => 'relation')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setTable('status')
   ->setQuery($querys['erkrankung_view_tree'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
   ->setLimit(null, true)
;

$tree               = $queryMod->query();
$lStatus            = getLookup($db, 'status');
$referenceListKeys  = array_keys($referenceList);

foreach ($tree as $i => $element) {
    $tree[$i]['form_name']         = $config[$element['form']];
    $tree[$i]['index']             = $i;
    $tree[$i]['form_status_bez']   = $lStatus[$element['form_status']];
    $tree[$i]['form_situation']    = ''; //isset($element['form_situation']) ? $element['form_situation'] : '';

    if (in_array($element['form'], $referenceListKeys) === true) {
        $tree[$i]['children'] = $referenceList[$element['form']];
    }
}

//Hier sortieren.
foreach($tree as $key => &$value) {
  $parentId = $value['parent_status_id'];

  if(strlen($parentId) > 0) {

     foreach($tree as $k => $parentValue) {
        $statusId = $parentValue['status_id'];

        if($parentId == $statusId) {
           $tree[$k]['branches'][] = $value;
           unset($tree[$key]);

           break;
        }

        if(isset($parentValue['branches'])) {
           foreach ($parentValue['branches'] as $branchKey => $branchValue) {
              if($branchValue['status_id'] == $parentId) {
                 $tree[$k]['branches'][$branchKey]['branches'][] = $value;
                 unset($tree[$key]);

                 break;
              }
           }
        }
     }
  }
}

//sorting entrys
foreach ($tree as $tKey => $tElement) {
    $key           = strlen($tElement['form_date']) > 0 ? $tElement['form_date'] : '0000-00-00';
    $tree[$key][]  = $tElement;

    unset($tree[$tKey]);
}

$list = array();

foreach ($tree as $tKey => $tSet) {
    foreach ($tSet as $k => $tElement) {
        $list["{$tKey}-{$k}"] = $tElement;
    }
}

krsort($list);

$htmlTree = buildDiseaseTree($config, $referenceList, $restrictions, $list, $param);

$smarty
  ->assign('erkrankungTree', $htmlTree)
;

?>