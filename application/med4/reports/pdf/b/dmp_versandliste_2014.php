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

class reportContentDmp_versandliste extends customReport
{
   public function header(){}

   public function init($renderer)
   {
      $pdf = $renderer->getFPDI();

      $pdf->AddPage('P');

      return $this;
   }


   public function generate(alcReportPdfAbstract $renderer)
   {
      $config           = $this->loadConfigs('dmp_versandliste', false, true);
      $dmpEdPatdatOk    = $this->_params['dmpEdPatdatOk'];
      $dmpFdPatdatOk    = $this->_params['dmpFdPatdatOk'];
      $xkmfile          = $this->_params['xkmfile'];
      $meldeUserId      = $this->_params['meldeUserId'];
      $orgId            = $this->_params['orgId'];

      $renderer->setConfig($config);

      $query = "
         SELECT
            IFNULL( u.bsnr, o.ik_nr )                                                             AS bsnr,
            IF( u.bsnr IS NULL, o.name, NULL )                                                    AS org1,
            IF( u.bsnr IS NULL, o.namenszusatz, NULL )                                            AS org2,
            CONCAT_WS( ' ', u.titel, u.vorname, u.nachname )                                      AS arzt,
            IF( u.bsnr IS NULL, o.strasse, u.strasse )                                            AS strasse,
            IF( u.bsnr IS NULL, CONCAT_WS( ' ', o.plz, o.ort ), CONCAT_WS( ' ', u.plz, u.ort ) )  AS ort
         FROM
            org o,
            user u
         WHERE
            u.user_id = {$meldeUserId} AND o.org_id = {$orgId}
      ";

      $arzt    = reset(sql_query_array($this->_db, $query));

      $i       = 0;
      $values  = array();

      foreach ($dmpEdPatdatOk as $record) {
         foreach ($record as $name => $value) {
            $values[$name][$i] = utf8_encode($value);
         }

         $i++;
      }

      foreach ($dmpFdPatdatOk as $record) {
         foreach ($record as $name => $value) {
            // Besonderheit wegen Macke des Tabellengenerators
            if ($name == 'dmp_brustkrebs_fb_id') {
               $name = 'dmp_brustkrebs_eb_id';
            }
            $values[$name][$i] = utf8_encode($value);
         }

         $i++;
      }

      $pdf = $renderer->getFPDI();

      $y             = $renderer->getProperty('pageMarginTop');

      $rowHeight     = $renderer->getProperty('rowHeight');
      $defaultFont   = $renderer->getProperty('fontDefault');
      $borderLeft    = $renderer->getProperty('pageMarginLeft');

      // Listenerstellungsdatum
      $pdf->SetFont($defaultFont, 'B', 10);
      $pdf->Text(350, $y, $config['lbl_listenerstellungsdatum'] . ': ' . date('d.m.Y'));
      $y += 4 * $rowHeight;

      // Titel
      $pdf->SetFont($defaultFont, 'B', 15);
      $pdf->Text($borderLeft, $y, $config['lbl_versandliste']);
      $y += 2 * $rowHeight;


      // Arzt-Daten
      $pdf->SetFont($defaultFont, 'B', 10);
      $pdf->Text($borderLeft, $y, utf8_encode($arzt['bsnr']));

      $y += $rowHeight;

      if (strlen($arzt['org1']) > 0) {
         $pdf->Text($borderLeft, $y, utf8_encode($arzt['org1']));
         $y += $rowHeight;
      }

      if (strlen($arzt['org2']) > 0){
         $pdf->Text($borderLeft, $y, utf8_encode($arzt['org2']));
         $y += $rowHeight;
      }

      $pdf->Text($borderLeft, $y, utf8_encode($arzt['arzt']));

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, utf8_encode($arzt['strasse']));

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, utf8_encode($arzt['ort']));

      $y += 2 * $rowHeight;

      $pdf->Text($borderLeft, $y, $xkmfile);

      $y += 2 * $rowHeight;

      // Daten-Tabelle
      if (count($values) > 0) {

         $renderer
            ->matrix
               ->create('versandliste', $values, array('y' => $y))
               ->addColumn('kv_nr',                15, 'versich_nr')
               ->addColumn('patient',              33, 'patient')
               ->addColumn('kassen_nr',            10, 'kassen_nr')
               ->addColumn('fall_nr',              10, 'fall_nr')
               ->addColumn('dmp_dokument_id',      22, 'dmp_dokument_id')
               ->addColumn('unterschrift_datum',   10, 'unterschrift_datum')
               ->draw()
         ;

         $y = $renderer->getFPDI()->getY() + 20;
      }

      // Unterschriften
      if ($y >= end($renderer->getProperty('pageHeight')) - $renderer->getProperty('pageMarginBottom') - 10 * $rowHeight ) {
        $pdf->AddPage();
        $y = $renderer->getProperty('pageMarginTop');
      }

      $pdf->SetFont($defaultFont, 'B', 10);
      $pdf->Text($borderLeft, $y, $config['lbl_bestaetigung1']);

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_bestaetigung2']);

      $y += 5 * $rowHeight;

      $pdf->Text($borderLeft, $y, '__________________________________________________');

      $y += $rowHeight;

      $pdf->Text($borderLeft, $y, $config['lbl_arztunterschrift']);
   }
}

?>