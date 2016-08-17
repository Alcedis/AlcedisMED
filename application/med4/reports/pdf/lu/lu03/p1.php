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


//ct
$renderer
   ->matrix
      ->create('ct', $this->_values['ct'], array('w' => 120, 'y' => 100))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;


//pt
$renderer
   ->matrix
      ->create('pt', $this->_values['pt'], array('x' => '185', 'w' => 120, 'y' => 100))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;


$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//uicc
$renderer
   ->matrix
      ->create('uicc', $this->_values['uicc'], array('x' => 320,'w' => 120, 'y' => 100))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;


//cn
$renderer
   ->matrix
      ->create('cn', $this->_values['cn'], array('w' => 120, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;


//pn
$renderer
   ->matrix
      ->create('pn', $this->_values['pn'], array('x' => 185, 'w' => 120, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//m
$renderer
   ->matrix
      ->create('m', $this->_values['m'], array('w' => 120, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

//grading
$renderer
   ->matrix
      ->create('g', $this->_values['g'], array('x' => '185', 'w' => 120, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('L');

//Operateure
$renderer
   ->matrix
      ->create('operateure', $this->_values['operateure'], array('w' => 740, 'y' => $renderer->getY(), 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,     37,'operateur_name',  array('order' => 'ASC', 'lookup' => array('table' => 'user', 'src' => 'user_id', 'field' => "CONCAT_WS(', ', nachname, vorname)")))
      ->addColumn('operateure_lymph',                   25,'operateure_lymph',   array('align' => 'R'))
      ->addColumn('operateure_pneumo',                  16,'operateure_pneumo',  array('align' => 'R'))
      ->addColumn('operateure_broncho',                 22,'operateure_broncho', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('P');

//Lu03.9 Angabe pT, pN bei invasivem Karzinom
$renderer
   ->matrix
      ->create('invasiv', $this->_values['invasiv'], array('w' => 200, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('resektion', $this->_values['resektion'], array('w' => 200, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Lu03.11 Radiotherapie
$renderer
   ->matrix
      ->create('radio', $this->_values['radio'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  37, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn('radio_alle',                      37, 'radio_alle', array('align' => 'R'))
      ->addColumn('radio_primaer',                   26, 'radio_primaer', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//D03.16.1 Anzahl aller dokumentierten durchgeführten Chemotherapien
$renderer
   ->matrix
      ->create('chemoimmun', $this->_values['chemoimmun'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  55, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   45, 'anz_therapien_ges', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//D03.16.2 Anzahl durchgeführter Chemotherapien mit Indikation Darm
$renderer
   ->matrix
      ->create('chemoimmun_lunge', $this->_values['chemoimmun_lunge'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  55, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   45, 'anz_therapien', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('schnellschnitt', $this->_values['schnellschnitt'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 35, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       10, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     10, 'anteil', array('align' => 'R'))
      ->addColumn('min',     15, 'schnellschnitt_min',   array('align' => 'R'))
      ->addColumn('max',     15, 'schnellschnitt_max',   array('align' => 'R'))
      ->addColumn('range',   15, 'schnellschnitt_range', array('align' => 'R'))
      ->draw()
;

?>