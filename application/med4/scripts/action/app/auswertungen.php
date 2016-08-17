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
    case 'help':

        $sub  = isset($_REQUEST['sub'])  ? $_REQUEST['sub'] : null;
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

        $path = "media/help/reports/{$sub}/{$name}.pdf";

        $smarty->config_load("../reports/config/{$sub}/version.conf");

        $config = $smarty->get_config_vars();

        $name = str_replace($sub, $config['report_title'], $name);

        $fileName = concat(array($config['prefix_help'], $name), '_') . '.pdf';

        download::create($path, 'pdf')
            ->output($fileName)
        ;

        break;
}

?>
