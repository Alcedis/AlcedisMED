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

$restrict = null;

$table   = 'dmp_nummern_2013';
$formId  = isset($_REQUEST['rec_dmp_nummern_2013_id']) ? $_REQUEST['rec_dmp_nummern_2013_id'] : '';

$location = get_url('page=list.dmp_nummern_2013');

// don't allow update if current_nr != start nr
if (strlen($formId) > 0 && strlen(dlookup($db, 'dmp_nummern_2013', 'dmp_nummern_2013_id', "dmp_nummern_2013_id = '{$formId}' AND dmp_nr_start != dmp_nr_current")) > 0) {
    $restrict = array('U', 'D');
}

if ($permission->action($action) === true) {
    require($permission->getActionFilePath());
}
/*
// get free dmp numbers
$query = "SELECT * FROM dmp_nummern_2013 WHERE org_id = '{$org_id}'";
$dmpNumbers = sql_query_array($db, $query);

foreach ($dmpNumbers as $circle) {
    $dmpFree[] = $circle['dmp_nr_end'] - $circle['dmp_nr_current'] + 1;
}

$info = isset($dmpFree) && count($dmpFree > 0) ?
    sprintf($config['msg_freie_nummern'], array_sum($dmpFree)) :
    sprintf($config['lbl_no_numbers'])
;
*/

$button = get_buttons ($table, $formId, $statusLocked, false, $restrict);
show_record($smarty, $db, $fields, $table, $formId);


$smarty
    ->assign('button', $button)
//    ->assign('info', $info)
    ->assign('back_btn', 'page=list.dmp_nummern_2013')
;

/**
 *
 *
 * @access
 * @param $valid
 * @return void
 */
function ext_err(validator $valid)
{
    $db     = $valid->_db;
    $config = $valid->_msg;
    $fields = &$valid->_fields;

    $orgId  = reset($fields['org_id']['value']);
    $id     = reset($fields['dmp_nummern_2013_id']['value']);

    $start  = reset($fields['dmp_nr_start']['value']);
    $end    = reset($fields['dmp_nr_end']['value']);

    if (($end - $start) > 1000) {
        $valid->set_err(12, array('dmp_nr_end', 'dmp_nr_start'), null, $config['msg_max']);
    }

    if ($end <= $start) {
        $valid->set_err(12, 'dmp_nr_end', null, $config['msg_too_small']);
    }

    $dmpNumbers = sql_query_array($db, "SELECT * FROM dmp_nummern_2013 WHERE org_id = '{$orgId}' AND dmp_nummern_2013_id != '{$id}'");

    $exist = null;

    foreach ($dmpNumbers as $record) {
        $cond1 = $start <= $record['dmp_nr_end'] && $start >= $record['dmp_nr_start'];
        $cond2 = $end <= $record['dmp_nr_end'] && $end >= $record['dmp_nr_start'];

        if ($cond1 === true || $cond2 === true) {
            $exist = array(
                'start' => $record['dmp_nr_start'],
                'end'   => $record['dmp_nr_end']
            );
        }
    }

    if ($exist !== null) {
        $msg = sprintf($config['msg_other'], $exist['start'] . ' - ' . $exist['end']);

        $valid->set_err(12, array('dmp_nr_end', 'dmp_nr_start'), null, $msg);
    }

    // create pool
    $errorsExist = (isset($valid->err->msg) === true && count($valid->err->msg) > 0);

    if ($errorsExist === false) {
        $pool =  range($start, $end);

        $used = dlookup($db, 'dmp_brustkrebs_ed_2013', "GROUP_CONCAT(DISTINCT fall_nr SEPARATOR '|')", "org_id = '{$orgId}' AND fall_nr BETWEEN {$start} AND {$end}");

        foreach (explode('|', $used) as $nr) {
            if (is_numeric($nr) === true) {
                unset($pool[array_search($nr, $pool)]);
            }
        }

        $fields['nr_count']['value'][0] = count($pool);
        $fields['pool']['value'][0] = implode('|', $pool);

        $fields['dmp_nr_current']['value'][0] = count($pool) > 0
            ? min($pool)
            : $end
        ;
    }
}
