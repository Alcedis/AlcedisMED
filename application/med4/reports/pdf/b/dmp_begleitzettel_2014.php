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

class reportContentDmp_begleitzettel_2014 extends customReport
{
   public function header(){}

   public function init($renderer)
   {
      $pdf = $renderer->getFPDI();

      $pdf->AddPage('P');

      return $this;
   }


   public function generate(alcReportPdf $renderer)
   {
      $pdf = $renderer->getFPDI();

      $config = $this->loadConfigs('dmp_begleitzettel', false, true);

      // Init - Daten kommen größtenteils aus den in die Session gelegten Arrays
      $dmp_begleitzettel = $this->_params['dmpBegleitzettel'];

      $dmp_begleitzettel['dateien'] = is_array($dmp_begleitzettel['dateien']) === false
         ? array($dmp_begleitzettel['dateien'])
         : $dmp_begleitzettel['dateien']
      ;

      // Beginn
      $y             = $renderer->getProperty('pageMarginTop');
      $rowHeight     = $renderer->getProperty('rowHeight');
      $defaultFont   = $renderer->getProperty('fontDefault');
      $borderLeft    = $renderer->getProperty('pageMarginLeft');
      $secColumn     = $borderLeft + 200;

      // Titel
      $pdf->SetFont($defaultFont, 'B' , 15);
      $pdf->Text($borderLeft, $y, $config['lbl_begleitzettel']);

      $y += 4 * $rowHeight;

      // Text
      $pdf->SetFont($defaultFont, 'B', 10);
      $pdf->Text($borderLeft, $y, $config['lbl_datenuebermittlungsverfahren']);
      $pdf->Text($secColumn,  $y, $config['lbl_datenuebermittlungsverfahren_const']);

      $y += 2 * $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_absender']);
      $pdf->Text($secColumn,  $y, $dmp_begleitzettel['absender' ]);

      $y += 2 * $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_empfaenger']);
      $pdf->Text($secColumn,  $y, $dmp_begleitzettel['empfaenger']);

      $y += 2 * $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_anz_datentraeger']);
      $pdf->Text($secColumn,  $y, '1');

      $y += 2 * $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_dateien']);

      foreach($dmp_begleitzettel[ 'dateien' ] as $datei )
      {
         $pdf->Text($secColumn, $y, $datei);
         $y += $rowHeight;
      }

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_datum_datentraeger']);
      $pdf->Text($secColumn,  $y, date('d.m.Y'));

      $y += 7 * $rowHeight;

      $pdf->Text($borderLeft, $y, '__________________________________________________');

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_datum_unterschrift']);
   }
}

?>
