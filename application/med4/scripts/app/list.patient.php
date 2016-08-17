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

if ($rolle_code == 'moderator') {
    action_cancel('index.php?page=list.zweitmeinung');
}

if (isset($_SESSION['sess_patient_data']) === true) {
   unset($_SESSION['sess_patient_data']);
}

//Set new fields for list
$fields = array(
   'patient_id'   => array('type' => 'hidden'),
   'nachname'     => array('type' => 'string'),
   'vorname'      => array('type' => 'string'),
   'patient_nr'   => array('type' => 'string'),
   'organisation' => array('type' => 'string'),
   'geburtsdatum' => array('type' => 'date'),
   'erkrankungen' => array('type' => 'string'),
   'aufnahme_nr'  => array('type' => 'string'),
   'createtime'   => array('type' => 'hidden'),
   'krebsregister'=> array('type' => 'string'),
   'status'       => array('type' => 'lookup', 'req' => 0, 'size' => '', 'maxlen' => '11', 'type' => 'lookup', 'ext' => array('l_basic' => 'status'))
);

$rechtGlobal = dlookup($db, 'recht', 'recht_global', "recht_id = '{$_SESSION['sess_recht_id']}'");

$where = array();

if (count($_SESSION['sess_recht_erkrankung_bez']) && ($rolle_code == 'supervisor' && $rechtGlobal == 1) == false) {
    $erkWhere = array();

    $rechtErk = $_SESSION['sess_recht_erkrankung_bez'];

    if (array_key_exists('sess_recht_erkrankung', $_SESSION) && in_array('sst', $_SESSION['sess_recht_erkrankung'])) {

       $sstDetail = dlookup($db, 'l_basic', 'GROUP_CONCAT(bez)', "klasse = 'erkrankung_sst_detail' GROUP BY klasse");

       $rechtErk = array_merge($rechtErk, explode(',', $sstDetail));
    }

    foreach ($rechtErk as $checkerk)
        $erkWhere[] = "p.erkrankungen LIKE '%$checkerk%'\n";

    $where[] = '(' . implode(' OR ', $erkWhere) . ')';
}

$querys['patient'] = str_replace("'BEHANDLER'", $user_id, $querys['patient']);

if (isset($_SESSION['sess_recht_behandler']) === true && $_SESSION['sess_recht_behandler'] == 1) {
    $where[] = 'behandler = 1';
}

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
    'krebsregister' => array('type'  => 'lookup',
                             'class' => 'jn',
                             'val'   => $config['krebsregister'],
                             'field' => 'p.krebsregister'
    ),
   'vorname'       => array('type' => 'string','field' => 'p.vorname'),
   'nachname'      => array('type' => 'string','field' => 'p.nachname'),
   'organisation'  => array('type' => 'string','field' => 'p.organisation'),
   'geburtsdatum'  => array('type' => 'date',  'field' => 'p.geburtsdatum'),
   'erkrankungen'  => array('type' => 'string','field' => 'p.erkrankungen'),
   'createtime'    => array('type' => 'date',  'field' => 'p.createtime'),
   'aufnahme_nr'   => array('type' => 'string','field' => 'p.aufnahme_nr'),
   'patient_nr'    => array('type' => 'string','field' => 'p.patient_nr'),
   'status'        => array('type'  => 'lookup',
                            'class' => 'status',
                            'val'   => 'Status',
                            'field' => 'p.status'
   )
);

//Rolle Dateneingabe hat ein Recht auf alle Organisationen
if ($rolle_code === 'dateneingabe') {
    $querys['patient'] = str_replace("AND pa.org_id = 'ORGID'", null, $querys['patient']);
    $smarty->assign('viewAlternative', true);
} else {
    $querys['patient'] = str_replace('ORGID', $org_id, $querys['patient']);
}

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setTable('patient')
   ->setQuery($querys['patient'])
   ->setSearchFields($searchFields)
   ->setOrderBy("nachname, vorname")
   ->setWhere(implode(' AND ', $where))
;

data2list($db, $fields, $queryMod->query());

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('patListKonfiguration', array(appSettings::get('pat_list_first'), appSettings::get('pat_list_second')))
;

//Setzen der Links
$form_rec = get_url('page=view.patient');

//Geänderter Hinzufügen Button
if ($bfl === null) {
   $arr_menubar['patient']['custom'][] = "
      <div class='bfl-count'>
      {$config['lbl_anz_pat']}<span class='count'>" . $queryMod->getFullDatasetCount(null, 'patient_id') . "</span>
            /
            {$config['lbl_anz_filter']}<span class='filter'>" . $queryMod->getDatasetCount() .  "</span>
            </div>
   ";

   if ($permission->checkView('patient_import')){
      $arr_menubar['patient']['custom'][] = "<a href='index.php?page=list.patient_import' class='button'>$config[btn_lbl_insert]</a>";
      $arr_menubar['patient']['custom'] = array_reverse($arr_menubar['patient']['custom']);
   }
}

?>
