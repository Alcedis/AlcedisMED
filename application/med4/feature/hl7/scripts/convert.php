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

$cacheTableExists = (count(sql_query_array($db, "show tables like 'hl7_cache_tmp'")) == 1);

if ($cacheTableExists === true) {
    $hl7Main  = hl7Main::getInstance();
    $messages = sql_query_array($db, "SELECT * FROM hl7_cache_tmp");

    $hl7CacheDir = $hl7Main->getSettings('cache_dir');

    foreach ($messages as $message) {
        $hl7CacheId = $message['hl7_cache_id'];
        $orgId      = $message['org_id'];
        $msg        = $message['msg'];

        $filename = $hl7CacheId . uniqid();

        $filePath = $hl7CacheDir . $orgId . '/' . $filename;
        file_put_contents($filePath, $msg);
    }
}

?>
