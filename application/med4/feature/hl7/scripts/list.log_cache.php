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

$cookie = cookie::create($user_id, $pageName);

$searchFields = array(
   'status'        => array('type'  => 'lookup',
                            'class' => 'hl7_status',
                            'val'   => 'Status',
                            'field' => 'status'
   ),
   'nachname'      => array('type' => 'string', 'field'  => 'nachname'),
   'vorname'       => array('type' => 'string', 'field'  => 'vorname'),
   'geburtsdatum'  => array('type' => 'date', 'field'    => "geburtsdatum"),
   'patient_nr'    => array('type' => 'string', 'field'  => 'patient_nr'),
   'aufnahme_nr'   => array('type' => 'string', 'field'  => 'aufnahme_nr')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setQuery($querys['hl7_log_cache'])
   ->setSearchFields($searchFields)
   ->setOrderBy('hl7_log_id')
   ->setWhere("org_id = '{$org_id}'")
;

data2list( $db, $fields, $queryMod->query());

if ($bfl === null) {

   $fullDatasetCount = dlookup($db, 'hl7_log_cache', "COUNT(hl7_log_id)", "org_id = '{$org_id}'");

   $arr_menubar['log_cache']['custom'][] = "
      <div class='bfl-count'>
      {$config['lbl_anz_cache']}<span class='count'>" . $fullDatasetCount . "</span>
            /
            {$config['lbl_anz_filter']}<span class='filter'>" . $queryMod->getDatasetCount() .  "</span>
            </div>
   ";
}

$smarty
   ->assign('entryCount', $queryMod->getDatasetCount())
   ->assign('back_btn', 'page=manager&feature=hl7')
;

?>
