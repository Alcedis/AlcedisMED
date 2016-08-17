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

// ED
$query = "
    SELECT
        dmp_brustkrebs_ed_2013_id
    FROM
        dmp_brustkrebs_ed_2013
    WHERE
        dmp_brustkrebs_eb_id IS NOT NULL
";

$dmp_brustkrebs_ed_2013_ids = sql_query_array($db, $query);


// PNP
$query = "
    SELECT
        dmp_brustkrebs_ed_pnp_2013_id
    FROM
        dmp_brustkrebs_ed_pnp_2013
    WHERE
        dmp_brustkrebs_eb_id IS NOT NULL
";

$dmp_brustkrebs_ed_pnp_2013_ids = sql_query_array($db, $query);


// FD
$query = "
    SELECT
        dmp_brustkrebs_fd_2013_id
    FROM
        dmp_brustkrebs_fd_2013
    WHERE
        dmp_brustkrebs_eb_id IS NOT NULL
";

$dmp_brustkrebs_fd_2013_ids = sql_query_array($db, $query);




//STATUS TABLE
foreach (array('dmp_brustkrebs_ed_2013', 'dmp_brustkrebs_ed_pnp_2013', 'dmp_brustkrebs_fd_2013') as $form) {
    $ids = array();
    switch ($form) {
        case 'dmp_brustkrebs_ed_2013':
            $ids = $dmp_brustkrebs_ed_2013_ids;
            break;
        case 'dmp_brustkrebs_ed_pnp_2013':
            $ids = $dmp_brustkrebs_ed_pnp_2013_ids;
            break;
        case 'dmp_brustkrebs_fd_2013':
            $ids = $dmp_brustkrebs_fd_2013_ids;
            break;
    }
    foreach ($ids as $id) {
        $query =
            "DELETE FROM
                status
            WHERE
                form = '{$form}'
            AND
                form_id = '{$id[$form . '_id']}'
            ";

        mysql_query($query, $db);
    }
}

// TABLES

// ED
$query = "
    DELETE FROM
        dmp_brustkrebs_ed_2013
    WHERE
        dmp_brustkrebs_ed_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);

$smarty
    ->assign('count_deleted_ed', mysql_affected_rows($db))
;


// PNP
$query = "
    DELETE FROM
        dmp_brustkrebs_ed_pnp_2013
    WHERE
        dmp_brustkrebs_ed_pnp_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);

$smarty
    ->assign('count_deleted_pnp', mysql_affected_rows($db))
;


// FD
$query = "
    DELETE FROM
        dmp_brustkrebs_fd_2013
    WHERE
        dmp_brustkrebs_fd_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);

$smarty
    ->assign('count_deleted_fd', mysql_affected_rows($db))
;



// _TABLES

// ED
$query = "
    DELETE FROM
        _dmp_brustkrebs_ed_2013
    WHERE
        _dmp_brustkrebs_ed_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);

// PNP
$query = "
    DELETE FROM
        _dmp_brustkrebs_ed_pnp_2013
    WHERE
        _dmp_brustkrebs_ed_pnp_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);

// FD
$query = "
    DELETE FROM
        _dmp_brustkrebs_fd_2013
    WHERE
        _dmp_brustkrebs_fd_2013.dmp_brustkrebs_eb_id IS NOT NULL
    ";

mysql_query($query, $db);




