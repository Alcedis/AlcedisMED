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

class cookie {

   protected $_cookieData = null;

   protected $_processData = null;

   protected $_cookie = null;

   protected $_userId = null;

   protected $_page = null;

   public function __construct($userId, $page){
      $this
         ->_init($userId, $page);

   }

   /**
    * Check Cookie
    *
    * @param $userId
    * @param $page
    */
   protected function _init($userId, $page)
   {
      $this->_page   = $page;
      $this->_userId = $userId;

      if (isset($_COOKIE['filter']) === true) {
         $this->_cookie = json_decode(stripslashes($_COOKIE['filter']), true);

         if (isset($this->_cookie[$this->_userId][$this->_page]) === true) {
            $this->_cookieData = $this->_cookie[$this->_userId][$this->_page];

            $this
               ->_process()
            ;
         }
      }

      return $this;
   }

   /*
    * reset cookie
    */
   public function reset()
   {
      if ($this->_cookie !== null) {

         if (isset($this->_cookie[$this->_userId]) === true) {
            unset($this->_cookie[$this->_userId]);

            $newCookie = count($this->_cookie) > 0 ? create_json_string($this->_cookie, true) : '{}';

            setcookie('filter', $newCookie);
         }
      }

      return $this;
   }

   private function _replaceSearch($txt) {
      $suchen   = array( 'ä', 'ö', 'ü', 'ß', "'", '"', "\\", "/");
      $ersetzen = array( 'a', 'o', 'u', 's', '', '', '', '');
      $txt     = str_replace($suchen, $ersetzen, $txt);

      return $txt;
   }


   protected function _process()
   {
      $processData = array(
         'orderBy'      => isset($this->_cookieData['sortbtn']) == true    ? $this->_cookieData['sortbtn']       : null,
         'orderType'    => isset($this->_cookieData['sorttype']) == true   ? $this->_cookieData['sorttype']      : null,
         'limit'        => isset($this->_cookieData['entries']) == true    ? $this->_cookieData['entries']       : null,
         'searchString' => isset($this->_cookieData['suche']) == true      ? $this->_cookieData['suche']         : null,
         'currentPage'  => isset($this->_cookieData['page']) == true       ? $this->_cookieData['page']          : null,
         'formFilter'   => isset($this->_cookieData['forms']) == true      ? $this->_cookieData['forms']         : null,
      );

      if (is_array($processData['searchString']) == true) {
         foreach ($processData['searchString'] as &$searchstring) {
            $searchstring = $this->_replaceSearch(utf8_decode($searchstring));
         }
      }

      $this->_processData = $processData;

      return $this;
   }



   public function getValue($key = '') {

      $value = isset($this->_cookieData[$key]) == true
         ? $this->_cookieData[$key]
         : null
      ;

      return $value;
   }

   public function getProcessData()
   {
      return $this->_processData;
   }

   public function setProcessData($processData)
   {
       $this->_processData = $processData;

       return $this;
   }

   public static function create($userId, $page) {
      return new self($userId, $page);
   }
}

?>