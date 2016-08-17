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

switch ($action) {
    case 'insert':

        $_REQUEST['org_id'] = $org_id;
        action_insert($smarty, $db, $fields, $table, $action, $location, 'ext_err', '', true);

        break;

    case 'update':

        if ($restrict === null) {
            action_update($smarty, $db, $fields, $table, $formId, $action, $location, 'ext_err', '', true);
        }

        break;

    case 'delete':

        if ($restrict === null) {
            action_delete($smarty, $db, $fields, $table, $formId, $action, $location);
        }

        break;

    case 'cancel':

        action_cancel($location);

        break;
}
?>
