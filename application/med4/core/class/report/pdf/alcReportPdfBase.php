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

class alcReportPdfBase extends alcReportPdfAbstract
{
   protected function _init()
   {
      if (array_key_exists('footer', $this->_params) === true && $this->_params['footer'] == 'none') {
         $fpdi = new FPDI('P', 'pt', array($this->_pageHeight, $this->_pageWidth));
      } else {
         //Standardmaessig immmer footer zeigen
         $fpdi = new fpdiExtension('P', 'pt', array($this->_pageHeight, $this->_pageWidth));
      }

      $fpdi->SetAutoPageBreak(false);
      $fpdi->SetPrintHeader(false);

      $this->_fpdi = $fpdi;

      return $this;
   }

}

?>