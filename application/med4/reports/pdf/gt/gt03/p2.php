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

$renderer->addPage('L');

//Gt03.10 Anzahl durchgeführte Chemo- und Immuntherapien pro Behandlungseinheit (gynäkologische Tumore)
$renderer
   ->matrix
      ->create('sys_g_indikation', $this->_values['sys_g_indikation'], array('w' => 500, 'y' => $renderer->getY(), 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  72, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   28, 'anz_therapie', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');


//Gt03.11 Anzahl durchgeführte Chemo- und Immuntherapien pro Behandlungseinheit (alle Tumore)
$renderer
   ->matrix
      ->create('sys_therapie', $this->_values['sys_therapie'], array('w' => 500, 'y' => $y, 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  72, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   28, 'anz_therapie', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('operateure', $this->_values['operateure'], array('w' => 600, 'y' => $y, 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,     28, 'op_operateur_name', array('lookup' => array('table' => 'user', 'src' => 'user_id', 'field' => "CONCAT_WS(', ', nachname, vorname)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,      12,  'op_gesamt',         array('align' => 'R'))
      ->addColumn('op_operateur',                       12,  'op_operateur',   array('align' => 'R'))
      ->addColumn('op_assi',                            12,  'op_assi',        array('align' => 'R'))
      ->addColumn('op_prim_gesamt',                     12,  'op_prim_gesamt', array('align' => 'R'))
      ->addColumn('op_prim_operateur',                  12,  'op_operateur',   array('align' => 'R'))
      ->addColumn('op_prim_assi',                       12,  'op_assi',        array('align' => 'R'))
      ->draw()
;
?>