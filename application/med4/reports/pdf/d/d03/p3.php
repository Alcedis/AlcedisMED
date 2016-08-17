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

//D03.14 Darmoperateure
$renderer
   ->matrix
      ->create('operateure', $this->_values['operateure'], array('w' => 720, 'y' => $renderer->getY(), 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,      28,'op_operateur_name', array('lookup' => array('table' => 'user', 'src' => 'user_id', 'field' => "CONCAT_WS(', ', nachname, vorname)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,       6, 'op_gesamt',         array('align' => 'R'))
      ->addColumn('op_colon_gesamt',                     9, 'op_colon_gesamt',   array('align' => 'R'))
      ->addColumn('op_colon_operateur',                  11,'op_colon_operateur',array('align' => 'R'))
      ->addColumn('op_colon_assi',                       12,'op_colon_assi',     array('align' => 'R'))
      ->addColumn('op_rektum_gesamt',                    9, 'op_rektum_gesamt',  array('align' => 'R'))
      ->addColumn('op_rektum_operateur',                 12,'op_rektum_operateur', array('align' => 'R'))
      ->addColumn('op_rektum_assi',                      13,'op_rektum_assi',    array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//D03.16.1 Anzahl aller dokumentierten durchgeführten Chemotherapien
$renderer
   ->matrix
      ->create('chemo_d', $this->_values['chemo_d'], array('w' => 360, 'y' => $y, 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  72, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   28, 'anz_pat_chemo', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');


//D03.16.2 Anzahl durchgeführter Chemotherapien mit Indikation Darm
$renderer
   ->matrix
      ->create('chemo_d_indikation', $this->_values['chemo_d_indikation'], array('w' => 360, 'y' => $y, 'page' => 'L'))
      ->addColumn(alcReportPdfAddonMatrix::$lookup,  72, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn(alcReportPdfAddonMatrix::$value,   28, 'anz_pat_chemo', array('align' => 'R'))
      ->draw()
;

?>