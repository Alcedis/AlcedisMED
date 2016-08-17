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

function arr_reduce($src = array(), $reduce = array()) {
    foreach ($reduce as $r) {
        $key = array_search($r, $src);

        if (is_int($key) === true) {
            unset($src[$key]);
        }
    }

    return $src;
}

function check_array_content( $array )
{
   if(is_array($array)){
      foreach( $array AS $index => $sub_array){
         $status = check_array_content( $sub_array );
         if( $status === true ){
            return true;
         }
      }
   }else{
      $string = trim($array);
      if( strlen( $string) > 0 )
         return true;
   }
}

function array_insert($array, $index, $value)
{
   $value = is_array($value) === false ? array($value) : $value;

  return array_merge(array_slice($array, 0, $index), $value, array_slice($array, $index));
}

function mergeDataset($base, $ext)
{
   $dataset = $base;

   foreach ($ext as $key => $value) {
      if (strlen(trim($value)) > 0) {
         $dataset[$key] = $value;
      }
   }

   return $dataset;
}


function sort_by_key($array, $key, $last = '_') {

    $keySort = array();
    $newArray = array();
    $lastData = array();

    foreach ($array as $k => $data) {
        if (str_starts_with(strtolower($data[$key]), $last)) {
            $lastData[strtolower($data[$key])] = $k;
        } else {
            $keySort[strtolower($data[$key])] = $k;
        }
    }

    ksort($keySort);
    ksort($lastData);

    foreach ($keySort as $lbl => $x) {
        $newArray[] = $array[$x];
    }

    foreach ($lastData as $lbl => $x) {
        $newArray[] = $array[$x];
    }

    return $newArray;
}

?>