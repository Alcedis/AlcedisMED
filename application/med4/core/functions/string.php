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

/** -------------------------------------------------------------------------------------------
 ** Labels mit Trennzeichen verketten (wie bei MySQL)
 **/
function concat($arr_value, $seperator=', ')
{
   if( !is_array($arr_value) )
      return;

    $tmp_arr_value = array();
    foreach($arr_value AS $value)
        if(strlen($value))
            $tmp_arr_value[] = $value;

    $value = implode($seperator, $tmp_arr_value);

    return $value;
}

function attach_label($string1, $string2, $whitespace=true)
{
   $_ = $whitespace ? ' ' : '';

   $return = '';
   if(strlen($string1) AND strlen($string2))
      $return = $string1 . $_ . $string2;

   return $return;
}

function inject($pos, $string, $subject)
{
   return substr($subject, 0, $pos) . $string . substr($subject, $pos);
}

function str_starts_with($string, $args) {
   $args = is_array($args) ? $args : array($args);

    foreach ($args as $arg) {
        $arg = (string) $arg;

        if (substr($string, 0, strlen($arg)) == $arg) {
            return true;
        }
    }

   return false;
}

function str_ends_with($string, $args) {
    $args = is_array($args) ? $args : array($args);

    foreach ($args as $arg) {
        $arg = (string) $arg;

        if (strlen($string) && substr($string, -1 * strlen($arg)) == $arg) {
            return true;
        }
    }

    return false;
}

function str_contains($string, $args) {
    $args = is_array($args) ? $args : array($args);

    foreach ($args as $arg) {
        $arg = (string) $arg;

        if (strpos($string, $arg) !== false) {
            return true;
        }
    }

    return false;
}

function UTF8ToEntities ($string) {
      /* note: apply htmlspecialchars if desired /before/ applying this function
       /* Only do the slow convert if there are 8-bit characters */
      /* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
      if (! ereg("[\200-\237]", $string) and ! ereg("[\241-\377]", $string))
      return $string;

      // reject too-short sequences
      $string = preg_replace("/[\302-\375]([\001-\177])/", "&#65533;\\1", $string);
      $string = preg_replace("/[\340-\375].([\001-\177])/", "&#65533;\\1", $string);
      $string = preg_replace("/[\360-\375]..([\001-\177])/", "&#65533;\\1", $string);
      $string = preg_replace("/[\370-\375]...([\001-\177])/", "&#65533;\\1", $string);
      $string = preg_replace("/[\374-\375]....([\001-\177])/", "&#65533;\\1", $string);

      // reject illegal bytes & sequences
      // 2-byte characters in ASCII range
      $string = preg_replace("/[\300-\301]./", "&#65533;", $string);
      // 4-byte illegal codepoints (RFC 3629)
      $string = preg_replace("/\364[\220-\277]../", "&#65533;", $string);
      // 4-byte illegal codepoints (RFC 3629)
      $string = preg_replace("/[\365-\367].../", "&#65533;", $string);
      // 5-byte illegal codepoints (RFC 3629)
      $string = preg_replace("/[\370-\373]..../", "&#65533;", $string);
      // 6-byte illegal codepoints (RFC 3629)
      $string = preg_replace("/[\374-\375]...../", "&#65533;", $string);
      // undefined bytes
      $string = preg_replace("/[\376-\377]/", "&#65533;", $string);

      // reject consecutive start-bytes
      $string = preg_replace("/[\302-\364]{2,}/", "&#65533;", $string);

      // decode four byte unicode characters
      $string = preg_replace(
        "/([\360-\364])([\200-\277])([\200-\277])([\200-\277])/e",
        "'&#'.((ord('\\1')&7)<<18 | (ord('\\2')&63)<<12 |" .
        " (ord('\\3')&63)<<6 | (ord('\\4')&63)).';'",
      $string);

      // decode three byte unicode characters
      $string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
"'&#'.((ord('\\1')&15)<<12 | (ord('\\2')&63)<<6 | (ord('\\3')&63)).';'",
      $string);

      // decode two byte unicode characters
      $string = preg_replace("/([\300-\337])([\200-\277])/e",
    "'&#'.((ord('\\1')&31)<<6 | (ord('\\2')&63)).';'",
      $string);

      // reject leftover continuation bytes
      $string = preg_replace("/[\200-\277]/", "&#65533;", $string);

      return $string;
   }

function convertDate ($date) {
    if(strlen($date)) {
       if(preg_match('~[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{2,4}~', $date)) {
           $dateArr = explode('.', $date);

           $day     = str_pad($dateArr[0], 2, "0", STR_PAD_LEFT);
           $month   = str_pad($dateArr[1], 2, "0", STR_PAD_LEFT);
           $year    = $dateArr[2];

           if(strlen($year) == 2) {
              $currentDate = date('y', time());
              $definedDate = substr($date, -2);
              $datePrefix  = $definedDate > $currentDate ? '19' : '20';

              $year = $datePrefix . $year;
           }

           $date = $day . '.' . $month . '.' . $year;
       } else {
          preg_match_all('/\./', $date, $dots);
          if (count($dots[0]) === 1) {
             return $date;
          }

          $date = str_replace('.', '', $date);
          //Datum TTMMJJJJ/TTMMJJ -> this cofuses 04.2012 with 04-20-2012
          if (preg_match('~[0-9]{4}[1-2]{1}[0-9]{3}~', $date)) {
              $date = preg_replace('~([0-9]{2})([0-9]{2})([1-2]{1}[0-9]{3})~', '\1.\2.\3', $date);
          } else if (preg_match('~[0-9]{6}~',  $date)) {
              $currentDate = date('y', time());
              $definedDate = substr($date, -2);
              $datePrefix  = $definedDate > $currentDate ? '19' : '20';

              $date = preg_replace('~([0-9]{2})([0-9]{2})([0-9]{2})~', '\1.\2.' . $datePrefix . '\3', $date);
          }
       }

      return $date;
    }
}

?>
