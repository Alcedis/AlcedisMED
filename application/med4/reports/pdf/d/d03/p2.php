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

$renderer->addPage();

$renderer
   ->matrix
      ->create('schnellschnitt', $this->_values['schnellschnitt'], array('w' => 490))
      ->addColumn(alcReportPdfAddonMatrix::$description, 35, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       10, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     10, 'anteil', array('align' => 'R'))
      ->addColumn('min',     15, 'schnellschnitt_min',   array('align' => 'R'))
      ->addColumn('max',     15, 'schnellschnitt_max',   array('align' => 'R'))
      ->addColumn('range',   15, 'schnellschnitt_range', array('align' => 'R'))
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

//Nebenwirkung Strahlentherapie
$renderer
   ->matrix
      ->create('nw_str', $this->_values['nw_str'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nw_chemo', $this->_values['nw_chemo'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nw_ah', $this->_values['nw_ah'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nw_i', $this->_values['nw_i'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nw_st', $this->_values['nw_st'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('nw_str_che', $this->_values['nw_str_che'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,   77, 'nw', array('lookup' => array('table' => 'l_nci', 'src' => "code", 'field' => 'bez')))
      ->addColumn(alcReportPdfAddonMatrix::$value,    5, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$count,    12, 'anzahl_therapien', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,  6, 'anteil', array('align' => 'R'))
      ->draw()
;

?>