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

class alcCss
{
   private static $_instance = null;

   public $styles = array(
      'alcedisBold'      => '.alcedisBold {font-weight: bold;}',
      'alcedisItalic'    => '.alcedisItalic {font-style: italic;}',
      'alcedisUnderline' => '.alcedisUnderline {text-decoration: underline;}'
   );


   /**
    * returns instance of alcCss
    *
    * @static
    * @access  public
    * @return  alcCss|null
    */
   public static function getInstance()
   {
      if (self::$_instance == null) {
         self::$_instance = new self;
      }

      return self::$_instance;
   }


   /**
    * returns css styles
    *
    * @static
    * @access  public
    * @return  array
    */
   public static function getStyles()
   {
      return self::getInstance()->styles;
   }


   /**
    * adds given tag and style to styles array
    *
    * @static
    * @access  public
    * @param   string   $tag
    * @param   string   $style
    * @return  alcCss|null
    */
   public static function addStyle($tag, $style)
   {
      $instance = self::getInstance();

       if (str_starts_with($tag, 'alcedis') === true) {
           $instance->styles[$tag] = $style;
       }

      return $instance;
   }
}
