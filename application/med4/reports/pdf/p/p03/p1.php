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


//t
$renderer
   ->matrix
      ->create('pt', $this->_values['pt'], array('w' => 120, 'y' => 200))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//n
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

//Anzahl angebotener interdisziplinärer Gespräche
$renderer
   ->matrix
      ->create('interdis', $this->_values['interdis_ang'], array('w' => 215, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 85, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->draw()
;

//Stanzbiopsien
$renderer
   ->matrix
      ->create('stanzen', $this->_values['stanzen'], array('x' => 285,'w' => 215, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('biopsate', $this->_values['biopsate'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 35, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       10, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     10, 'anteil', array('align' => 'R'))
      ->addColumn('min',     15, 'biopsate_min',   array('align' => 'R'))
      ->addColumn('max',     15, 'biopsate_max',   array('align' => 'R'))
      ->addColumn('range',   15, 'biopsate_range', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('praeparate', $this->_values['praeparate'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 35, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       10, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     10, 'anteil', array('align' => 'R'))
      ->addColumn('min',     15, 'praeparate_min',   array('align' => 'R'))
      ->addColumn('max',     15, 'praeparate_max',   array('align' => 'R'))
      ->addColumn('range',   15, 'praeparate_range', array('align' => 'R'))
      ->draw()
;


$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Angabe pT, pN bei invasivem Karzinom
$renderer
   ->matrix
      ->create('ptpn_invasiv', $this->_values['ptpn_invasiv'], array('w' => 215, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

?>