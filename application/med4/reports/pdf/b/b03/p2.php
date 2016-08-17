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

//Qualifikation Behandlungseinheit/-partner
$renderer
   ->matrix
      ->create('chemo_b', $this->_values['chemo_b'], array('w' => 350))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  70, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   30, 'anz_pat_chemo', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('chemo_b_indikation', $this->_values['chemo_b_indikation'], array('w' => 350, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  70, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   30, 'anz_pat_chemo', array('align' => 'R'))
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


$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Pathobericht
$renderer
   ->matrix
      ->create('pathobericht', $this->_values['pathobericht'], array('w' => 215, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$tmpY = $renderer->getY();

//Pathobericht
$renderer
   ->matrix
      ->create('her2', $this->_values['her2'], array('w' => 215, 'y' => $y, 'x' => 280))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $tmpY + $renderer->getProperty('rowHeight');

//Strahlentherapien Gesamtdosis
$renderer
   ->matrix
      ->create('str_gesamt', $this->_values['str_50'], array('w' => 350, 'y' => $y))
      ->addColumn('str_k50', 20, 'str_k50')
      ->addColumn(alcReportPdfAddonMatrix::$value, 20, 'str_50')
      ->addColumn('str_g50', 20, 'str_g50')
      ->addColumn('str_ka50', 20, 'str_ka')
      ->addColumn(alcReportPdfAddonMatrix::$percent, 20, 'str_50_anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Strahlentherapien Boostdosis
$renderer
   ->matrix
      ->create('str_boost', $this->_values['str_10'], array('w' => 350, 'y' => $y))
      ->addColumn('str_k10', 20, 'str_k10')
      ->addColumn(alcReportPdfAddonMatrix::$value, 20, 'str_10')
      ->addColumn('str_g10', 20, 'str_g10')
      ->addColumn('str_ka10', 20, 'str_ka')
      ->addColumn(alcReportPdfAddonMatrix::$percent, 20, 'str_10_anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Strahlentherapien Einzeldosis
$renderer
   ->matrix
      ->create('str_einzel', $this->_values['str_18'], array('w' => 350, 'y' => $y))
      ->addColumn('str_k18', 20, 'str_k18')
      ->addColumn(alcReportPdfAddonMatrix::$value, 20, 'str_18')
      ->addColumn('str_g18', 20, 'str_g18')
      ->addColumn('str_ka18', 20, 'str_ka')
      ->addColumn(alcReportPdfAddonMatrix::$percent, 20, 'str_18_anteil', array('align' => 'R'))
      ->draw()
;

?>