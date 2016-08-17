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

class alcReportPdfAddonText extends alcReportPdfAbstract implements alcReportPdfInterface
{
   /**
    *
    * @see core/class/report/pdf/alcReportPdfInterface::init()
    */
   public function init(FPDI $fpdi)
   {
      $this->_fpdi = $fpdi;

      return $this;
   }


   /**
    * writes text
    *
    * @param $text
    * @param $y
    * @param $x
    */
   public function write($txt, $type, $ln = 0, $x = null, $y = null)
   {
      $pos = array(
         'y' => $this->_fpdi->GetY(),
         'x' => $this->_fpdi->GetX()
      );

      $y = $y !== null
         ? $y
         : ( $pos['y'] <= $this->_pageMarginTop
            ? $this->_pageMarginTop
            : $pos['y']
         )
      ;

      $x = $x !== null
         ? $x
         : ( $pos['x'] <= $this->_pageMarginLeft
            ? $this->_pageMarginLeft
            : $pos['x']
         )
      ;

      switch ($type) {
         case 'h3': $this->_fpdi->SetFont($this->_fontDefault, $this->_fontBold, $this->_fontSizeHeadline1); break;
         case 'h4': $this->_fpdi->SetFont($this->_fontDefault, $this->_fontBold, $this->_fontSizeHeadline2); break;

      }

      $this->_fpdi->Text($x, $y, $txt);
      $this->_fpdi->SetY($y + $ln);

      return $this;
   }
}
