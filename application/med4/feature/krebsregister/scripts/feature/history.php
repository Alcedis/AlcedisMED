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

require_once 'feature/krebsregister/class/register/export/record.krexport.php';

/* @var Chistory_1_0_Controller $export */

$historyManager = CHistoryManager::getInstance();

/* remove cache data if any history was deleted */
$historyManager->addCallback('afterDelete', function(CHistoryManager $historyManager, $params) {

    $db = $historyManager->getDb();

    /* @var CHistory $history */
    $history    = $params[0];
    $orgId      = $history->getOrgId();
    $exportName = $history->getExportName();

    $export = new RKrExport;

    $export->read($db, $exportName, $orgId, $orgId, 'not_finished', false);

    if ($export->getDbId() != 0) {
        $export->delete($db);
    }
});
