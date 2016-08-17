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

class permission
{
   protected $_matrix            = null;

   protected $_page              = null;

   protected $_actionFilePath    = null;

   protected $_forbidden         = false;

   protected $_orgId             = null;

   public function __construct($page, $matrix, $file = null, $orgId = null)
   {
      $this->_matrix          = $matrix;
      $this->_page            = $page;
      $this->_actionFilePath  = $file;
      $this->_orgId           = $orgId;
   }

   public static function create($page, $matrix, $file = null, $orgId = null)
   {
      return new self($page, $matrix, $file, $orgId);
   }

   public function getPage()
   {
       return $this->_page;
   }

   public function checkView ($page=null) {
      $check = false;
      $page  = $page === null ? $this->_page : $page;

      if (isset($this->_matrix[$page]) === true && strlen(trim($this->_matrix[$page])) > 0) {
         $check = true;
      }

      return $check;
   }


   public function action($action, $page = null)
   {
      $check = false;

      if ($action != '') {
         $page = $page === null ? $this->_page : $page;

         if (isset($this->_matrix[$page]) === true && strlen(trim($this->_matrix[$page])) > 0) {
            $allRights  = false;
            $rights     = array('cancel');

            $zugriff    = reset(explode('&', trim($this->_matrix[$page])));
            $zugriffArr = array(explode('|', $zugriff));

            foreach (reset($zugriffArr) as $restriction) {
               switch ($restriction) {
                  case 'A':
                        $allRights = true;
                        $rights = array_merge($rights, array('insert', 'update', 'delete', 'I', 'U', 'D'));
                     break;

                  case 'I':
                        $rights = array_merge($rights, array('insert', 'I'));
                     break;

                  case 'U':
                        $rights = array_merge($rights, array('update', 'U'));
                     break;

                  case 'D':
                        $rights = array_merge($rights, array('delete', 'D'));
                     break;

                  default:
                        $rights = array_merge($rights, array($restriction));
                     break;
               }
            }

            if (in_array($action, $rights) === true || $allRights === true) {
               $check = true;
            }
         } else {
             if ($action == 'cancel') {
                 $check = true;
             }
         }
      }

      if ($action !== 'cancel' && $this->_forbidden == true) {
         $check = false;
      }

      return $check;
   }

   public function getFormProperty($form = null)
   {
       return formManager::getFormProperty($this->_orgId, $this->_page, $form);
   }

   public function getActionFilePath() {
      return $this->_actionFilePath;
   }

   public function setActionFilePath($filePath) {
      $this->_actionFilePath = $filePath;

      return $this;
   }

   public function setForbidden($bool = true) {
      $this->_forbidden = $bool;

      return $this;
   }

}

?>