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

if ($action == 'cancel') {
    action_cancel('index.php?page=view.erkrankung');
}

require_once 'core/class/qs181/fieldConverter.php';

//override flags. Search bar and sidebar not used for qs
$overrideSearchbar = false;
$overrideSidebar   = false;

$fields  = $widget->loadExtFields('fields/app/qs_18_1_b.php');

$qs181bId = isset($_REQUEST['qs_18_1_b_id']) === true ? $_REQUEST['qs_18_1_b_id'] : null;

//Basis und Brust Informationen
$query = "
    SELECT
        base.qs_18_1_b_id,
        base.freigabe,
        base.idnrpat,
        base.patient_id,
        base.erkrankung_id,
        DATE_FORMAT(base.aufndatum, '%d.%m.%Y') AS 'aufnahmedatum',
        YEAR(base.aufndatum) AS 'aufnahmedatum_year',
        DATE_FORMAT(base.entldatum, '%d.%m.%Y') AS 'entlassungsdatum',
        MAX(IF(b.zuopseite = 'R', b.qs_18_1_brust_id, NULL)) AS 'qs_18_1_brust_r_id',
        MAX(IF(b.zuopseite = 'R' AND b.arterkrank IS NOT NULL, b.arterkrank, NULL)) AS 'qs_18_1_brust_r_art',
        MAX(IF(b.zuopseite = 'R' AND b.freigabe IS NOT NULL, 1, NULL)) AS 'qs_18_1_brust_r_freigabe',
        MAX(IF(b.zuopseite = 'L', b.qs_18_1_brust_id, NULL)) AS 'qs_18_1_brust_l_id',
        MAX(IF(b.zuopseite = 'L' AND b.arterkrank IS NOT NULL, b.arterkrank, NULL)) AS 'qs_18_1_brust_l_art',
        MAX(IF(b.zuopseite = 'L' AND b.freigabe IS NOT NULL, 1, NULL)) AS 'qs_18_1_brust_l_freigabe'
    FROM qs_18_1_b base
        LEFT JOIN qs_18_1_brust b ON b.qs_18_1_b_id = base.qs_18_1_b_id
    WHERE
        base.qs_18_1_b_id = '{$qs181bId}'
    GROUP BY
        base.qs_18_1_b_id
";

$qs181 = reset(sql_query_array($db, $query));

$qs181Version = null;

if ($qs181 !== false) {
    $qs181['status'] = dlookup($db, '`status`', 'form_status', "form = 'qs_18_1_b' AND form_id = '{$qs181['qs_18_1_b_id']}'");

    foreach (array('r', 'l') as $side) {
        $qs181["qs_18_1_brust_{$side}_art"] = strlen($qs181["qs_18_1_brust_{$side}_art"])
            ? dlookup($db, 'l_qs', 'bez', "klasse='erkrankung' AND code = '{$qs181["qs_18_1_brust_{$side}_art"]}'")
            : '--'
        ;

        $qs181["qs_18_1_brust_{$side}_status"] = strlen($qs181["qs_18_1_brust_{$side}_id"])
            ? dlookup($db, '`status`', 'form_status', "form = 'qs_18_1_brust' AND form_id = '{$qs181["qs_18_1_brust_{$side}_id"]}'")
            : null
        ;
    }

    $qs181Version = qs181FieldConverter::create($db, 'qs_18_1_b', null, $qs181bId)->getVersion();
}

$smarty
    ->assign('qs181', $qs181)
    ->assign('qs181version', sprintf($config['qs181interface'], $qs181Version))
;

$ops  = array('r' => array(), 'l' => array());
$qs181o = array();

//OP Informationen
$query = "
    SELECT
        o.qs_18_1_o_id,
        o.lfdnreingriff,
        o.freigabe,
        DATE_FORMAT(o.opdatum, '%d.%m.%Y') AS 'opdatum',
        LOWER(b.zuopseite) AS 'zuopseite'
    FROM qs_18_1_o o
        INNER JOIN qs_18_1_brust b ON b.qs_18_1_brust_id = o.qs_18_1_brust_id
    WHERE
        o.qs_18_1_b_id = '{$qs181bId}'
    GROUP BY
        o.qs_18_1_o_id
    ORDER BY
        o.opdatum, lfdnreingriff
";

foreach(sql_query_array($db, $query) as $op) {
    $op['status'] = dlookup($db, '`status`', 'form_status', "form = 'qs_18_1_o' AND form_id = '{$op['qs_18_1_o_id']}'");

    $ops[$op['zuopseite']][] = $op;
}

$count = 0;

foreach ($ops as $opSite => $opForms) {
    $ops[$opSite][] = array(
        'qs_18_1_o_id' => null
    );

    $count = $count > count($ops[$opSite]) ? $count : count($ops[$opSite]);
}

//op array aufbauen
for ($i=0; $i<$count; $i++) {
    $qs181o[] = array(
        'qs_18_1_o_r_id' => (isset($ops['r'][$i]) === true && array_key_exists('qs_18_1_o_id', $ops['r'][$i]) ? ($ops['r'][$i]['qs_18_1_o_id'] == null ? 'new' : $ops['r'][$i]['qs_18_1_o_id']) : null),
        'qs_18_1_o_r_nr' => (isset($ops['r'][$i]) === true && array_key_exists('lfdnreingriff', $ops['r'][$i]) ? $ops['r'][$i]['lfdnreingriff'] : null),
        'qs_18_1_o_r_freigabe' => (isset($ops['r'][$i]) === true && array_key_exists('freigabe', $ops['r'][$i]) ? $ops['r'][$i]['freigabe'] : null),
        'qs_18_1_o_r_opdatum' => (isset($ops['r'][$i]) === true && array_key_exists('opdatum', $ops['r'][$i]) ? $ops['r'][$i]['opdatum'] : null),
        'qs_18_1_o_r_status' => (isset($ops['r'][$i]) === true && array_key_exists('status', $ops['r'][$i]) ? $ops['r'][$i]['status'] : null),
        'qs_18_1_o_l_id' => (isset($ops['l'][$i]) === true && array_key_exists('qs_18_1_o_id', $ops['l'][$i]) ? ($ops['l'][$i]['qs_18_1_o_id'] == null ? 'new' : $ops['l'][$i]['qs_18_1_o_id']) : null),
        'qs_18_1_o_l_nr' => (isset($ops['l'][$i]) === true && array_key_exists('lfdnreingriff', $ops['l'][$i]) ? $ops['l'][$i]['lfdnreingriff'] : null),
        'qs_18_1_o_l_freigabe' => (isset($ops['l'][$i]) === true && array_key_exists('freigabe', $ops['l'][$i]) ? $ops['l'][$i]['freigabe'] : null),
        'qs_18_1_o_l_opdatum' => (isset($ops['l'][$i]) === true && array_key_exists('opdatum', $ops['l'][$i]) ? $ops['l'][$i]['opdatum'] : null),
        'qs_18_1_o_l_status' => (isset($ops['l'][$i]) === true && array_key_exists('status', $ops['l'][$i]) ? $ops['l'][$i]['status'] : null),
    );
}

$smarty
    ->assign('qs181o', $qs181o)
    ->assign('showInfo', isset($_REQUEST['showinfo']))
;

?>
