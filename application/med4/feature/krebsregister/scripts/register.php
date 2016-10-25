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

$type = isset($_REQUEST['type']) === true ? $_REQUEST['type'] : null;

if (strlen($type) === 0 || appSettings::get('interfaces', null, 'kr_' . $type) === false) {
    redirectTo(get_url('page=select&feature=krebsregister'));
}

$smarty
    ->assign('action', $action)
    ->assign('type', $type)
;

if ($action === 'confirm') {
    $pids = isset($_GET['pids']) ? $_GET['pids'] : null;

    $smarty
        ->assign('pids', $pids)
        ->assign('back_btn', 'page=list.register&feature=krebsregister&type=' . $type)
        ->assign('caption', $config['caption_kr_' . $type] . $config['lbl_create'])
    ;
} elseif ($action === 'create') {
    $pids = isset($_REQUEST['pids']) ? $_REQUEST['pids'] : null;

    $params = array(
        'org_id'    => $org_id,
        'user_id'   => $user_id,
        'type'      => $type,
        'login_name'=> (isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] : 'dummy')
    );

    require_once 'feature/krebsregister/class/register.php';

    $register = register::create($db, $smarty, $type, $params);

    $registerState = $register->getRegisterState();

    if (strlen($pids) > 0) {
        $pids = unserialize(base64_decode(urldecode($pids)));

        $registerState->setPatientIdFilter($pids);
    }

    $historyId = $registerState->writeXml();

    redirectTo("index.php?page=register&type={$type}&feature=krebsregister&id={$historyId}&action=show");

    $smarty
        ->assign('back_btn', 'page=select&feature=krebsregister')
        ->assign('caption', $config['caption_kr_' . $type])
    ;
} elseif ($action === 'download' || $action === 'show') {
    $id = isset($_GET['id'])   ? $_GET['id']   : null;

    if (strlen($type) === 0 || strlen($id) === 0 || appSettings::get('interfaces', null, 'kr_' . $type) === false) {
        redirectTo(get_url('page=select&feature=krebsregister'));
    }

    require_once 'feature/export/history/class.history.php';

    $history = new CHistory;

    $history
        ->setExportHistoryId($id)
        ->read($db)
    ;

    // check if id matches to export type
    if ($history->getExportName() !== 'kr_' . $type) {
        redirectTo(get_url('page=select&feature=krebsregister'));
    }

    $history = $history->toArray();

    if ($action === 'download') {
        download::create($history['url'], 'zip')->output();
    }

    $smarty
        ->assign('history', $history)
        ->assign('back_btn', 'page=select&feature=krebsregister')
        ->assign('caption', $config['caption_kr_' . $type])
    ;
}
