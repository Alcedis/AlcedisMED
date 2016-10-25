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

$register = array();

$interfaces = explode(',', appSettings::get('interfaces'));

// set origin for export features
$_SESSION['origin'] = array(
    'page'    => 'select',
    'feature' => 'krebsregister'
);

foreach ($interfaces as $interface) {
    if (str_starts_with($interface, 'kr_') === true) {
        $register[] = array(
            'reg'         => $interface,
            'image'       => '<a href="index.php?page=list.register&type=he&feature=krebsregister"><img src="media/img/app/krebsregister/state/kr_he_small.png" ></a>',
            'link'        => '<a href="index.php?page=list.register&type=he&feature=krebsregister">Krebsregister Hessen</a>',
            'historyLink' => '<a href="index.php?page=history&feature=export&type=krebsregister&exportname=kr_he&origin=true">Archiv</a>'
        );
    }
}

$smarty->assign('register', $register);
