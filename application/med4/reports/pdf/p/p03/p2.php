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

//Resektions-/Sicherheitsabstand
$renderer
   ->matrix
      ->create('resektion', $this->_values['resektion'], array('w' => 215, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 70, 'gruppe')
      ->addColumn(alcReportPdfAddonMatrix::$value,       15, 'anzahl', array('align' => 'R'))
      ->addColumn(alcReportPdfAddonMatrix::$percent,     15, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//Operateure
$renderer
   ->matrix
      ->create('operateure', $this->_values['operateure'], array('w' => 455, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,     40,'op_operateur_name', array('order' => 'ASC', 'lookup' => array('table' => 'user', 'src' => 'user_id', 'field' => "CONCAT_WS(', ', nachname, vorname)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,      10,'op_gesamt',         array('align' => 'R'))
      ->addColumn('op_operateur',                       16,'op_operateur',       array('align' => 'R'))
      ->addColumn('op_assi',                            16,'op_assi',            array('align' => 'R'))
      ->addColumn('op_assi_anteil',                     18,'op_assi_anteil',     array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

$renderer
   ->matrix
      ->create('chemo_p_indikation', $this->_values['chemo_p_indikation'], array('w' => 350, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  70, 'klinikum_name', array('order' => 'ASC', 'lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   30, 'anz_pat_chemo', array('align' => 'R'))
      ->draw()
;

?>