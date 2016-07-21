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

function smarty_function_load_media($params, &$smarty)
{
   $file     = isset($params['file']) == true ? $params['file'] : null;
   $fullPath = isset($params['full']);

   if ($file === null) {
       return false;
   }

   $path = 'media/';

   $fileExtension = end(explode('.', $file));

   switch ($fileExtension) {
       case 'js':   $path .= 'js/';  break;
       case 'css':  $path .= 'css/'; break;
   }

   $pathToFile = $fullPath === false ? $path . $file : $file;

   if (file_exists($pathToFile) === false) {


       return false;
   }

   $fileTime = filemtime($pathToFile);

   $html = null;

   switch ($fileExtension) {
       case 'js':

           $html = "<script type='text/javascript' src='{$pathToFile}?time={$fileTime}'></script>";

           break;

       case 'css':

           $html = "<link rel='stylesheet' type='text/css' href='{$pathToFile}?time={$fileTime}'/>";

           break;
   }

   return $html;
}

?>
