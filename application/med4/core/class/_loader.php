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

require_once('core/class/app/settings.php');
require_once('core/class/app/secure.php');
require_once('core/class/bfl/buffer.php');

require_once('core/class/relationManager.php');
require_once('core/class/formManager.php');
require_once('core/class/menuManager.php');

require_once('core/class/cookie.php');
require_once('core/class/mimeType.php');
require_once('core/class/jodConverter.php');
require_once('core/class/queryModifier.php');
require_once('core/class/report.php');
require_once('core/class/download.php');
require_once('core/class/upload.php');
require_once('core/class/dataCollector.php');
require_once('core/class/customReport.php');
require_once('core/class/permission.php');
require_once('core/class/extendedForms.php');
require_once('core/class/pdf2swf.php');
require_once('core/class/concatPdf.php');
require_once('core/class/xhtmlManager.php');
require_once('core/class/alcOdt/_loader.php');
require_once('core/class/protocol.php');
require_once('core/class/email.php');
require_once('core/class/stageCalc/stageCalc.php');
?>