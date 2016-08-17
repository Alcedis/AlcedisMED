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

class odtArray extends alcOdtParserFunctions {

   protected $_array = array(
      'subhead' => array(),
      'content' => array()
   );

   protected $_mustHaveContent = false;

   protected $_head = null;


   public static function create(){
      return new self();
   }


   /**
    * adds subhead row
    * and add sepeator on subhead position to content side
    *
    * @param $subhead
    */
   public function addSubhead($subhead = '', $seperator = false)
   {
      if (strlen($subhead) > 0){
         $this->_array['subhead'][] = $subhead;
      }

      if ($seperator !== false) {
         $position = count($this->_array['subhead']) - 1;

         $this->_array['content'] = array_insert($this->_array['content'], $position, array($seperator));
      }

      return $this;
   }


   public function addHead($head, $seperator = false)
   {
      $this->_head = array(
         'head'      => $head,
         'seperator' => $seperator
      );

      return $this;
   }


   /**
    * adds content e.g array or string
    *
    * @param $content
    * @param $allowEmpty
    */
   public function addContent($content = '', $allowEmpty = false)
   {
      if (is_array($content) === true) {
         foreach ($content as $c) {
            $this->addContent($c, $allowEmpty);
         }
      } else {
         if ($allowEmpty === true && strlen($content) == 0) {
            $this->_array['content'][] = '';
      } elseif (strlen($content) > 0) {
            $this->_array['content'][] = $content;
         }
      }

      return $this;
   }

   /**
    * Add odt Array
    *
    * @param $odtArray
    */
   public function addOdtArray($odtArray, $seperator = false)
   {
      if ($seperator !== false &&
         check_array_content($this->_array['content']))
      {
         $this->_array['subhead'][] = $seperator;
         $this->_array['content'][] = $seperator;
      }

      $this->_array['subhead'] = array_merge($this->_array['subhead'], $odtArray['subhead']);
      $this->_array['content'] = array_merge($this->_array['content'], $odtArray['content']);

      return $this;
   }


   /**
    * converts array
    *
    */
   protected function _convertArray($fillToEqualLines = true)
   {
      if (is_array($this->_array['subhead']) === false) {
         $this->_array['subhead'] = array(
            $this->_array['subhead']
         );
      }

      if($fillToEqualLines) {
          $size = count($this->_array['content']) - count($this->_array['subhead']);

          //if content count is greater than subhead count
          if ($size > 0) {
             for($i = 1; $i <= $size; $i++) {
                $this->_array['subhead'][] = '';
             }
          } else if ($size < 0) {
             $size = $size * -1;

             for($i = 1; $i <= $size; $i++) {
                $this->_array['content'][] = '';
             }
          }
      }

      //Todo... produziert evtl Fehler wenn beide Seite gleich voll sind... wenn wirklich, dann bitte umbauen
      if ($this->_head !== null) {
         $this->_array['subhead'] = array_insert($this->_array['subhead'], 0, $this->_head['head']);

         if ($this->_head['seperator'] !== false) {
            $this->_array['content'] = array_insert($this->_array['content'], 0, $this->_head['seperator']);
         } else {
            array_pop($this->_array['subhead']);
         }
      }

      return $this;
   }

   public function checkContent()
   {
      $return = false;

      if (count($this->_array['content']) > 0) {
         $return = true;
      }

      return $return;
   }


   /**
    * render the array
    *
    * @param unknown_type $allowEmptyContent
    */
   public function render($allowEmptyContent = false, $fillToEqualLines = true)
   {
      if (count($this->_array['content']) > 0 || $allowEmptyContent === true) {
         $this->_convertArray($fillToEqualLines);
      }

      $return = $this->_array;

      if (check_array_content($this->_array) !== true) {
         $return = null;
      }

      return $return;
   }
}

?>
