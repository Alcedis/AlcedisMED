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

$tmpY = $y;
//sln
$renderer
   ->matrix
      ->create('sln', $this->_values['sln'], array('w' => 255, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'stadium')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Nachweis Detektionsrate
$renderer
   ->matrix
      ->create('nachweis_detektionsrate', $this->_values['detektion1'], array('w' => 255, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nachweis_detektionsrate', $this->_values['detektion2'], array('w' => 255, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nachweis_detektionsrate', $this->_values['detektion3'], array('w' => 255, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;


//Second Row

//Nodalstatus
$renderer
   ->matrix
      ->create('nodal', $this->_values['nodal'], array('w' => 215, 'y' => $tmpY, 'x' => 320))
      ->addColumn(alcReportPdfAddonMatrix::$description, 80, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');


$renderer
   ->matrix
      ->create('resektionss', $this->_values['resektionss'], array('w' => 215, 'y' => $y, 'x' => 320))
      ->addColumn(alcReportPdfAddonMatrix::$description, 80, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('r0_resektion', $this->_values['r0_resektion'], array('w' => 215, 'y' => $y, 'x' => 320))
      ->addColumn(alcReportPdfAddonMatrix::$description, 80, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->draw()
;

?>