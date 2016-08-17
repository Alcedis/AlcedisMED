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

class fpdiExtension extends FPDI
{
   public function __construct($orientation='P', $unit='mm', $format='A4')
   {
      parent::__construct($orientation, $unit, $format);
   }

   public function Footer()
   {
      $this->SetFont('helvetica', 'I', 8);

      $o = $this->CurOrientation;
      $y = $o == 'L' ? 570 : 820;

      $x1 = 30;
      $x2 = $o == 'L' ? 770 : 535;

      $this->Text($x1, $y, 'Stand: ' . date('d.m.Y H:i'));        // Datum
      $this->Text($x2, $y, 'Seite: ' . $this->PageNo() );    // Seitennummer

      return $this;
   }

   public function getCurrentPageOrientation() {
      return strtolower($this->CurOrientation);
   }
}

?>