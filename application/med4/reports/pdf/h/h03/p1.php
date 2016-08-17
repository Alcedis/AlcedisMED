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

//lymph
$renderer
   ->matrix
      ->create('lymphsono', $this->_values['lymphsono'], array('w' => 200, 'y' => 460))
      ->addColumn(alcReportPdfAddonMatrix::$description, 45, 'fall')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     30, 'anteil', array('align' => 'R'))
      ->draw()
;

//sono
$renderer
   ->matrix
      ->create('lymphadenektomie', $this->_values['lymphadenektomie'], array('w' => 200, 'y' => 460, 'x' => '300'))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'fall')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

//invasiv
$renderer
   ->matrix
      ->create('invasiv', $this->_values['invasiv'], array('w' => 200, 'y' => 560))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'fall')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

//therapie
$renderer
   ->matrix
      ->create('sys_therapie', $this->_values['sys_therapie'], array('w' => 200, 'y' => 660))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'fall')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

?>