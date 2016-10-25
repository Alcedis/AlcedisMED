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

/* @var string $type */
/* @var register $register */

switch ($action) {
    case 'export':
    case 'exportall':

        $pids = null;

        // if selection export for patients triggered, take only their patient ids
        if (bflBuffer::notEmpty('export') === true) {
            $pids = bflBuffer::get('export', 'add');
        } else if ($action === 'export' && bflBuffer::notEmpty('export') === false) {
            // no patients selected but button for selection was clicked
            $pids[] = 0;
        }

        if ($pids !== null) {
            $pids = urlencode(base64_encode(serialize($pids)));
        }

        redirectTo("index.php?page=register&type={$type}&feature=krebsregister&pids={$pids}&action=confirm");

        break;

    case 'refreshall':

        $register->getRegisterState()->refreshCache();

        break;
}
