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

//System
require('core/boot/session.php');
require('core/boot/request.php');
require('core/boot/constants.php');
require('core/boot/smarty.php');
require('core/boot/lib.php');

//ext
require('core/class/_loader.php');
require('core/functions/_loader.php');

require('core/boot/app.php');
require('core/boot/db.php');

require('core/boot/codepicker.php');
require('core/boot/picker.php');

require('core/boot/config.php');

require('core/boot/settings.php');

//custom
require('core/boot/custom.php');
require('core/initial/_loader.php');

//Feature
require('core/boot/feature.php');

require('core/boot/verify.php');

//fields
require('core/boot/fields.php');

//ajax
require('core/boot/ajax.php');

//widget selector
require('core/boot/widget.php');

//Permission
require('core/boot/permission.php');

//script
require('core/boot/script.php');

//menu
require('core/boot/menu.php');

//template
require('core/boot/template.php');

dbDisconnect($db);   // Datenbankverbindung trennen

?>