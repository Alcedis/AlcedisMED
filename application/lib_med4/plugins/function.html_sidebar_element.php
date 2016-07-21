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

function smarty_function_html_sidebar_element($params, &$smarty)
{
   $href       = NULL;
   $patiet_id  = NULL;
   $ref        = NULL;
   $lbl        = NULL;
   $param      = NULL;
   $noNew      = false;
   $permission = NULL;
   $property   = NULL;

   extract( $params );

   if ($permission !== null && method_exists($permission, 'getFormProperty') === true) {
       $property = $permission->getFormProperty($ref);
   }

   $html = '';

   if ($property === null) {
       $newButton = "
           <a href='index.php?page={$href}&amp;{$param}'>
               <img class='filter-img-new' alt='new' src='media/img/base/add.png' title=''/>
           </a>
       ";

       if ($noNew === true || ($permission !== NULL && $permission->action('I', $ref) === false)) {
           $newButton = "<!-- -->";
       }

       $html = "
           <tr>
              <td class='lbl' style='width:15%;'>
                {$newButton}
              </td>
              <td class='edt' style=\"width:100%;\">
                {$lbl}
              </td>
              <td class='lbl' style='width:15%;'>
              <img class='filter-img' title='' id='filter-img-{$ref}' alt='filter' src='media/img/base/filter.png'/>
              </td>
          </tr>
       ";
   }

   return $html;
}

?>