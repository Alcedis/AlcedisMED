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

//Erkrankungsinformation
$fields  = $widget->loadExtFields('fields/app/status.php');

$query = "
   SELECT
      s.*,
      MAX(IF(bez.code = s.form_status, bez.bez, NULL)) AS 'form_status_bez',
      MAX(IF(bez.code = s.form_param,  bez.bez, NULL)) AS 'form_status_erkrankung_bez',
      e.beschreibung
   FROM `status` s
      LEFT JOIN l_basic bez ON bez.klasse = 'status' AND bez.code IN (s.form_status, s.form_param)
      LEFT JOIN erkrankung e ON e.erkrankung_id = s.form_id
   WHERE
      s.form = 'erkrankung' AND s.form_id = '{$erkrankung_id}'
   GROUP BY status_id
";

$result = reset(sql_query_array($db, $query));

$smarty
   ->assign('patient_id',                       $result['patient_id'])
   ->assign('erkrankung_id',                    $result['form_id'])
   ->assign('status_lock',                      $result['status_lock'])
   ->assign('status',                           $result['form_status'])
   ->assign('status_bez',                       $result['form_status_bez'])
   ->assign('status_erkrankung',                $result['form_param'])
   ->assign('status_erkrankung_bez',            $result['form_status_erkrankung_bez'])
   ->assign('status_id',                        $result['status_id'])
   ->assign('kurzbeschreibung_der_erkrankung',  $result['beschreibung'])
;

//Spezifizierte Liste der Formulare die über eine referenz verfügen
$referenceList = array(
    'untersuchung'                => array('diagnose', 'komplikation', 'histologie'),
    'therapie_systemisch'         => array('therapie_systemisch_zyklus', 'nebenwirkung'),
    'eingriff'                    => array('histologie', 'zytologie', 'komplikation'),
    'konferenz_patient'           => array('therapieplan'),
    'zweitmeinung'                => array('therapieplan'),
    'therapieplan'                => array('therapie_systemisch', 'strahlentherapie', 'eingriff', 'sonstige_therapie', 'therapieplan_abweichung'),
    'therapie_systemisch_zyklus'  => array('therapie_systemisch_zyklustag'),
    'sonstige_therapie'           => array('nebenwirkung'),
    'strahlentherapie'            => array('nebenwirkung')
);

if (appSettings::get('interfaces', null, 'dmp_2014') === true) {
    $referenceList['dmp_brustkrebs_ed_2013'] = array('dmp_brustkrebs_ed_pnp_2013', 'dmp_brustkrebs_fd_2013');
}

//Neues Setting Form Manager
foreach ($referenceList as $rfFormName => $rfList) {
    foreach ($rfList as $key => $rfForm) {
        if (formManager::getFormProperty($org_id, 'erkrankung', $rfForm) === true) {
            unset($referenceList[$rfFormName][$key]);
        }
    }
}

$param = "&amp;patient_id={$patient_id}&amp;erkrankung_id={$erkrankung_id}";

$restrictions = array(
    'therapie_systemisch' =>
        array('therapie_systemisch_zyklus' => 1),
    'konferenz_patient' =>
        array('therapieplan' => '')
);

//Cookie
$cookie = cookie::create($user_id, $pageName);

//Check für Brustformularfilter
if ($erkrankung !== 'b') {
    $cookieProcessData = $cookie->getProcessData();

    if (isset($cookieProcessData['formFilter']) === true && is_array($cookieProcessData['formFilter']) === true) {

        $cookieProcessData['formFilter'] = array_diff($cookieProcessData['formFilter'], array(
            'dmp_brustkrebs_eb',
            'dmp_brustkrebs_fb',
            'qs_18_1_b',
            'qs_18_1_brust',
            'qs_18_1_o'
        ));
    }

    $cookie->setProcessData($cookieProcessData);
}
$preSelectedList = $cookie->getValue('list');

$views = array('table', 'tree');

$erkrankungView = isset($_REQUEST['view']) === true && in_array($_REQUEST['view'], $views) === true
   ? $_REQUEST['view']
   : (isset($_REQUEST['bflsub']) === true && in_array($_REQUEST['bflsub'], $views)
    ? $_REQUEST['bflsub']
    : ($preSelectedList !== null && in_array($preSelectedList, $views) === true
        ? $preSelectedList
        : 'table'
      )
    )
;

//Disease List
$fields['form_name']    = array('type' => 'string');
$fields['form_date_de'] = array('type' => 'string');
$fields['reference']    = array('type' => 'string');
$fields['status_lock']  = array('type' => 'string');
$fields['form_status']  = array('req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'lookup', 'ext' => array('l_basic' => 'status'));

$orderBy    = "form_date DESC, form ASC";
$groupBy    = "p.patient_id";


require_once "scripts/app/view.erkrankung_{$erkrankungView}.php";

$smarty
    ->assign('erkrankungView', "app/view.erkrankung_{$erkrankungView}.tpl")
    ->assign('bfl-ext', $erkrankungView)
    ->assign('filter',
        "<td>
            <span class='filter-element'>
                <a href='index.php?page=view.erkrankung{$param}&amp;view=table' >
                    <img alt='list' class='filter-img' src='media/img/base/list-view.png' title=''/>
                </a>
                <a href='index.php?page=view.erkrankung{$param}&amp;view=tree' >
                    <img alt='tree' class='filter-img' src='media/img/base/tree-view.png' title=''/>
                </a>
             </span>
         </td>"
    )
    ->assign('back_btn', "page=view.patient&amp;patient_id={$patient_id}")
;


?>
