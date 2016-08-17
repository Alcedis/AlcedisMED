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
   'form'        => array('type' => 'string',   'field' => 'form'),
   'content'     => array('type' => 'string',   'field' => 'form_data'),
   'status'      => array('type'  => 'lookup',
                          'class' => 'status',
                          'ignore' => array('1'),
                          'val'   => 'Status',
                          'field' => 'form_status'
   ),
   'forms'       => array('type' => 'filter', 'field' => 'form_filter')
);

$queryMod = queryModifier::create($db, $smarty)
   ->setCookie($cookie)
   ->setTable('status')
   ->setQuery($querys['erkrankung_view_table'])
   ->setSearchFields($searchFields)
   ->setOrderBy($orderBy)
;

data2list($db, $fields, $queryMod->query());

if (isset($fields['form']['value']) === true && count($fields['form']['value']) > 0) {
    foreach ($fields['form']['value'] as $index => $formName) {
        $fields['form_name']['value'][$index] = $config[$formName];
        $fields['reference']['value'][$index] = buildReferenceList(
            $config,
            $fields['status_id']['value'][$index],
            $formName,
            $referenceList,
            $fields['form_id']['value'][$index],
            $param,
            $fields['form_param']['value'][$index],
            $restrictions
        );
    }
}

$smarty
    ->assign('entryCount', $queryMod->getDatasetCount())
;

?>