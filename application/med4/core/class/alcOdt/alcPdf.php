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

class alcPdf extends TCPDF
{
   protected $_alcedisHeader = false;

   protected $_alcedisFooter = false;

   protected $_alcedisY = 0;

   protected $_alcedisAutoPageBreak = PDF_MARGIN_BOTTOM;

   /**
    * sets the path for the alcedis header file
    *
    * @param $headerPath
    */
   public function setAlcedisHeader($headerPath = null)
   {
      if ($headerPath !== null) {
         $this->_alcedisHeader = $headerPath;
      }

      return $this;
   }

   /**
    * sets the path for the alcedis footer file
    *
    * @param $headerPath
    */
   public function setAlcedisFooter($footerPath = null)
   {
      if ($footerPath !== null) {
         $this->_alcedisFooter = $footerPath;
      }

      return $this;
   }


   public function Header()
   {
      $this->setCellHeightRatio(0.5);

      //Header Script Path
      if ($this->_alcedisHeader !== false) {
         require $this->_alcedisHeader . 'header.php';
      } else {
         //Default Top Margin
         $this->SetY(28);
      }

      $y = $this->GetY();

      $this->_alcedisY = $y;
      $this->SetTopMargin($y);

   }


   public function Footer()
   {
      //Footer Script
      if ($this->_alcedisFooter !== false) {
         require $this->_alcedisFooter . 'footer.php';
      } else {
         // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Seite '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
      }
      $this->setTopMargin(0);
   }


   protected function checkPageBreak($h=0, $y='', $addpage=true) {
      if (TCPDF_STATIC::empty_string($y)) {
         $y = $this->y;
      }
      $current_page = $this->page;
      if ((($y + $h) > $this->PageBreakTrigger) AND ($this->inPageBody()) AND ($this->AcceptPageBreak())) {
         if ($addpage) {
            //Automatic page break
            $x = $this->x;
            $this->AddPage($this->CurOrientation);

            $this->y = $this->_alcedisY != 0 ? $this->_alcedisY : $this->tMargin;

            $oldpage = $this->page - 1;
            if ($this->rtl) {
               if ($this->pagedim[$this->page]['orm'] != $this->pagedim[$oldpage]['orm']) {
                  $this->x = $x - ($this->pagedim[$this->page]['orm'] - $this->pagedim[$oldpage]['orm']);
               } else {
                  $this->x = $x;
               }
            } else {
               if ($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']) {
                  $this->x = $x + ($this->pagedim[$this->page]['olm'] - $this->pagedim[$oldpage]['olm']);
               } else {
                  $this->x = $x;
               }
            }
         }
         return true;
      }
      if ($current_page != $this->page) {
         // account for columns mode
         return true;
      }
      return false;
   }

}

?>
