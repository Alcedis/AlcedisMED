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

// render menubar if interface is active
if (appSettings::get('interfaces', null, 'kr_he') == true) {
    menuManager::registerMenuItem('krebsregister', 'select&feature=krebsregister', null);
    menuManager::addToMenuItemGroup('krebsregister', 'auswertungen');

    // if feature is selected
    if ($featureLoad['feature'] === 'krebsregister') {
        $permissionGranted = true;
        require_once 'feature/krebsregister/initial/queries.php';
        require_once 'feature/krebsregister/initial/functions.php';
    }
}
