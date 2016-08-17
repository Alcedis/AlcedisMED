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

require_once 'class/status.php';
require_once 'class/reportParam.php';
require_once 'class/refresh.php';

//Initial
require_once 'initial/reportParam.php';


switch ($pageName) {

   case 'validate':
   case 'validationstatus':
         //Zugriff auf validate immer gestatten

         $verified = true;
         $permissionGranted = true;

         $featureRequest = true;

         break;
}

?>
