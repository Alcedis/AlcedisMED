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

class reportContentGkr_begleitzettel extends customReport
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

        $config = $this->loadConfigs('gkr_begleitzettel', false, true);

        // Init - Daten kommen größtenteils aus den in die Session gelegten Arrays
        $gkr_begleitzettel = $this->_params['gkrBegleitzettel'];

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

        $pdf->Text($borderLeft, $y, $config['lbl_datum_des_exports']);
        $pdf->Text($secColumn,  $y, $gkr_begleitzettel['export_datum']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_dateiname']);
        $pdf->Text($secColumn,  $y, $gkr_begleitzettel['filename']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_export_user']);
        $pdf->Text($secColumn,  $y, utf8_encode(unescape($gkr_begleitzettel['username'])));

        $y += 3 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_meldetypen']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_erstmeldung_mit_ae']);
        $pdf->Text($secColumn,  $y, $gkr_begleitzettel['cE']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_erstmeldung_ohne_ae']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['ce']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_folgemeldung']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['cf']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_korrekturmeldung']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['ck']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_todesmeldung']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['ct']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_basaliom_mit_ae']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['cB']);

        $y += 2 * $rowHeight;

        $pdf->Text($borderLeft, $y, $config['lbl_basaliom_ohne_ae']);
        $pdf->Text($secColumn,  $y,  $gkr_begleitzettel['cb']);
    }
}

?>
