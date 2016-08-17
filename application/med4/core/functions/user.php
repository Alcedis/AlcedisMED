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

//Prüft nur gegen ob die Rolle nicht
function checkSessUserRole($forbiddenArray, $pageName)
{
   $return = true;
   $rolleCode = isset($_SESSION['sess_rolle_code']) === true ? $_SESSION['sess_rolle_code'] : null;

   if ($rolleCode !== null) {
      foreach ($forbiddenArray as $user => $sites) {
         if ($user === $rolleCode) {

            if (count($sites) === 0) {
               $return = false;
            } else {
               if (in_array($pageName, $sites) === true) {
                  $return = false;
               }
            }
         }
      }
   }

   return $return;
}


?>