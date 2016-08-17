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

abstract class alcReportPdfAbstract
{
   protected $_db;

   protected $_smarty;

   /**
    * addon stack
    *
    * @var array
    */
   protected $_addons = array();

   protected $_user;

   protected $_fpdi;

   protected $_config = array();

   protected $_rowHeight   = 13;

   protected $_pageWidth   = array();
   protected $_pageHeight  = array();

   protected $_pageMarginTop      = 30;
   protected $_pageMarginBottom   = 50;
   protected $_pageMarginLeft     = 50;
   protected $_pageMarginRight    = 50;

   protected $_fontSizeHeadline1 = 10;
   protected $_fontSizeHeadline2 = 6;
   protected $_fontSizeBigger    = 8;
   protected $_fontSizeNormal    = 6;
   protected $_fontSizeTitle     = 12;
   protected $_fontDefault       = 'helvetica';
   protected $_fontBold          = 'B';

   protected $_params = array();

   public function __construct($db, $smarty, $user, $params)
   {
      $this->_db     = $db;
      $this->_smarty = $smarty;
      $this->_user   = $user;
      $this->_params = $params;

      //Init page sizes
      $this->_pageWidth = array(
         'l' => 841.89,
         'p' => 595.28
      );

      $this->_pageHeight = array(
         'l' => 595.28,
         'p' => 841.89
      );
   }


   public function setConfig($config)
   {
       $this->_config = $config;
       
       foreach ($this->_addons as $name => $object) {
           $object->setConfig($config);
       }

       return $this;
   }


   /**
    *
    * @return FPDF_TPL
    */
   public function getFPDI()
   {
      return $this->_fpdi;
   }

   public function getProperty($name)
   {
      return $this->{"_{$name}"};
   }

   public function setProperty($name, $value)
   {
      $this->{"_{$name}"} = $value;

      return $this;
   }

}

?>