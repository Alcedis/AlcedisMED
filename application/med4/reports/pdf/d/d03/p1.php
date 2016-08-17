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

//Dignitaet
$renderer
   ->matrix
      ->create('dignitaet', $this->_values['dignitaet'], array('w' => 255, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

//pt
$renderer
   ->matrix
      ->create('pt', $this->_values['pt'], array('w' => 120, 'y' => 200))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//pn
$renderer
   ->matrix
      ->create('pn', $this->_values['pn'], array('x' => 185,'w' => 120, 'y' => 200))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

//m
$renderer
   ->matrix
      ->create('m', $this->_values['m'], array('x' => 320,'w' => 100, 'y' => 200))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

//grading
$renderer
   ->matrix
      ->create('g', $this->_values['g'], array('x' => 435,'w' => 100, 'y' => 200))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

//uicc
$renderer
   ->matrix
      ->create('uicc', $this->_values['uicc'], array('w' => 120, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 50, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$yTmp = $renderer->getY() + $renderer->getProperty('rowHeight');

//therapeutische Koloskopien
$renderer
   ->matrix
      ->create('th_kolpo', $this->_values['krthk'], array('x' => 185, 'w' => 300, 'y' => $y))
      ->addColumn('thkol', 35, 'anz_th_kolo')
      ->addColumn(alcReportPdfAddonMatrix::$value,    35, 'anz_th_komp', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//diagnostische Koloskopien
$renderer
   ->matrix
      ->create('diag_kolpo', $this->_values['krdiagk'], array('x' => 185, 'w' => 300, 'y' => $y))
      ->addColumn('diagkol', 35, 'anz_diag_kolo')
      ->addColumn(alcReportPdfAddonMatrix::$value,    35, 'anz_diag_komp', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Lymphknoten
$renderer
   ->matrix
      ->create('lymph', $this->_values['lymph'], array('x' => 185, 'w' => 210, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 50, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,    25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  25, 'anteil', array('align' => 'R'))
      ->draw()
;

$renderer
   ->matrix
      ->create('praeop_dignitaet', $this->_values['praeop_dignitaet'], array('w' => 346, 'y' => $yTmp))
      ->addColumn(alcReportPdfAddonMatrix::$description, 50, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     25, 'anteil', array('align' => 'R'))
      ->draw()
;


?>