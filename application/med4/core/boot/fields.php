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

$fields     = array();
$fieldsPath = null;
$fieldsName = $arr_section['file'];

foreach ($appOrder as $app) {
   $folder = $app['folder'];

   $fieldsDir        = 'fields/';
   $fieldFilePath    = "$folder/$subdir$fieldsName.php";

   if (file_exists($fieldsDir . $fieldFilePath) === true ) {
      $fieldsPath = $fieldsDir . $fieldFilePath;
      break;
   }
}

//Feature
if ($fieldsPath == null && $featureLoad['fields'] !== null) {
   $fieldsPath = $featureLoad['fields'];
}

//load fields
if ($fieldsPath !== null) {
   require_once($fieldsPath);
}

?>