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


//Gt03.6.1 Eingriffe bei Genitalmalignom (Ovar)
$renderer
   ->matrix
      ->create('eingriff_ovar', $this->_values['eingriff_ovar'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 75, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.6.2 Eingriffe bei Genitalmalignom (Zervix)
$renderer
   ->matrix
      ->create('eingriff_zervix', $this->_values['eingriff_zervix'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 75, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.6.3 Eingriffe bei Genitalmalignom (Endometrium)
$renderer
   ->matrix
      ->create('eingriff_endo', $this->_values['eingriff_endo'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 75, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.6.4 Eingriffe bei Genitalmalignom (Vulva)
$renderer
   ->matrix
      ->create('eingriff_vulva', $this->_values['eingriff_vulva'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 75, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       25, 'anzahl', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.7 Makroskopischer Tumorrest bei Ovarialkarzinomen
$renderer
   ->matrix
      ->create('makro_tumorrest', $this->_values['makro_tumorrest'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 66, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     14, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.8.1 Pathologischer Tumorrest (Zervix)
$renderer
   ->matrix
      ->create('patho_tumorrest_zervix', $this->_values['patho_tumorrest_zervix'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 66, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     14, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.8.2 Pathologischer Tumorrest (Vulva)
$renderer
   ->matrix
      ->create('patho_tumorrest_vulva', $this->_values['patho_tumorrest_vulva'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 66, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     14, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Gt03.9.3 Pathologischer Tumorrest (Endometrium)
$renderer
   ->matrix
      ->create('patho_tumorrest_endo', $this->_values['patho_tumorrest_endo'], array('w' => 300, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 66, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       20, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     14, 'anteil', array('align' => 'R'))
      ->draw()
;

?>