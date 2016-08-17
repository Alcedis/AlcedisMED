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

function parseArgs($argv)
{
  array_shift($argv);

  $out = array();

  foreach ($argv as $arg) {
      if (substr($arg, 0, 2) == '--') {
          $eqPos = strpos($arg, '=');

          if ($eqPos === false){
              $key = substr($arg, 2);
              $out[$key] = isset($out[$key]) ? $out[$key] : true;
          } else {
              $key = substr($arg, 2, $eqPos - 2);
              $out[$key] = substr($arg, $eqPos + 1);
          }
      } elseif (substr($arg, 0, 1) == '-'){
          if (substr($arg, 2, 1) == '='){
              $key = substr($arg, 1, 1);
              $out[$key] = substr($arg, 3);
          } else {
              $chars = str_split(substr($arg, 1));

              foreach ($chars as $char){
                  $key = $char;
                  $out[$key] = isset($out[$key]) ? $out[$key] : true;
              }
          }
      } else {
          $out[] = $arg;
      }
  }

  return $out;
}

$arguments = parseArgs($argv);

foreach ($arguments as $key => $value) {
   $_REQUEST[$key] = $value;
}

require 'index.php';

?>