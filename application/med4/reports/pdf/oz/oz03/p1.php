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

//OZ03.1 Vorstellung Tumorkonferenz (Pat. mit Lokalrezidiv)
$renderer
   ->matrix
      ->create('vorstellung_konferenz', $this->_values['lokalrezidiv'], array('w' => 490, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 60, 'erkrankung')
      ->addColumn('lokalrezidiv',       16, 'fall_lokalrezidiv', array('align' => 'R'))
      ->addColumn('tumorkonferenz',     16, 'fall_lokalrezidiv_mtk', array('align' => 'R'))
      ->addColumn('anteil',             8, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//OZ03.2 Vorstellung Tumorkonferenz (Pat. mit Metastasen)
$renderer
   ->matrix
      ->create('vorstellung_konferenz_metast', $this->_values['fernmetast'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 60, 'erkrankung')
      ->addColumn('fernmetast',       16, 'fall_fernmetast', array('align' => 'R'))
      ->addColumn('tumorkonferenz',   16, 'fall_fernmetast_mtk', array('align' => 'R'))
      ->addColumn('anteil',             8, 'anteil', array('align' => 'R'))
      ->draw()
;


$renderer->addPage('P');

//OZ03.3 Behandlungsplan (Therapieplan)
$renderer
   ->matrix
      ->create('behandlungsplan', $this->_values['behandlungsplan'], array('w' => 490, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 60, 'erkrankung')
      ->addColumn('behandlungsplan_alle_faelle',       16, 'behandlungsplan_alle_faelle', array('align' => 'R'))
      ->addColumn('behandlungsplan',   16, 'behandlungsplan_vorhanden', array('align' => 'R'))
      ->addColumn('anteil',             8, 'anteil', array('align' => 'R'))
      ->draw()
;

$y = $renderer->getY() + $renderer->getProperty('rowHeight');

//OZ03.4 Rücklaufquote Befragungsbogen
$renderer
   ->matrix
      ->create('befragungsbogen', $this->_values['befragungsbogen'], array('w' => 490, 'y' => $y))
      ->addColumn(alcReportPdfAddonMatrix::$description, 60, 'erkrankung')
      ->addColumn('befragungsbogen_stationaere_faelle',  14, 'befragungsbogen_stationaere_faelle', array('align' => 'R'))
      ->addColumn('befragungsbogen_vorhanden',           19, 'befragungsbogen_vorhanden', array('align' => 'R'))
      ->addColumn('anteil',             7, 'anteil', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('P');

//OZ03.5 Fallzahl Chirurgie
$renderer
   ->matrix
      ->create('chirurgie', $this->_values['chirurgie'], array('w' => 450, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 75, 'erkrankung')
      ->addColumn('primaer_op',       25, 'primaer_op', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('L');

//OZ03.6 Strahlentherapie
$renderer
   ->matrix
      ->create('strahlentherapie', $this->_values['strahlentherapie'], array('w' => 740, 'page' => 'L', 'y' =>  $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$configDescription, 40, 'erkrankung')
      ->addColumn('strahlentherapie_einrichtung',  46, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn('strahlentherapie_durchgefuehrt',   14, 'anz_strahlentherapie', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('L');

//OZ03.7 Nebenwirkungen (Chemo/Radiotherapie)
$renderer
   ->matrix
      ->create('nebenwirkung_chemoradio', $this->_values['nebenwirkung_chemoradio'], array('w' => 740, 'page' => 'L', 'y' =>  $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$configDescription,  40, 'erkrankung')
      ->addColumn('nebenwirkung_chemoradio_name',  56, 'nebenwirkung_name', array('lookup' => array('table' => 'l_nci', 'src' => 'code', 'field' => "bez")))
      ->addColumn('nebenwirkung_chemoradio_anzahl',   4, 'nebenwirkung_anzahl', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('L');

//OZ03.8 Schnellschnitte
$renderer
   ->matrix
      ->create('schnellschnitt', $this->_values['schnellschnitt'], array('w' => 720, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 54, 'erkrankung')
      ->addColumn('schnellschnitt_alle_faelle',       7, 'schnellschnitt_alle_faelle', array('align' => 'R'))
      ->addColumn('schnellschnitt_kl30',        12, 'schnellschnitt_kl30', array('align' => 'R'))
      ->addColumn('schnellschnitt_gr30',        12, 'schnellschnitt_gr30', array('align' => 'R'))
      ->addColumn('schnellschnitt_min',         5, 'schnellschnitt_min', array('align' => 'R'))
      ->addColumn('schnellschnitt_max',         5, 'schnellschnitt_max', array('align' => 'R'))
      ->addColumn('schnellschnitt_range',       5, 'schnellschnitt_range', array('align' => 'R'))
      ->draw()
;


$renderer->addPage('L');

//OZ03.9 Angabe pT, pN bei invasivem Karzinom
$renderer
   ->matrix
      ->create('invasiv', $this->_values['invasiv'], array('w' => 600, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 58, 'erkrankung')
      ->addColumn('invasiv_alle_faelle',       18, 'invasiv_alle_faelle', array('align' => 'R'))
      ->addColumn('invasiv_pt',       12, 'invasiv_pt', array('align' => 'R'))
      ->addColumn('invasiv_pn',       12, 'invasiv_pn', array('align' => 'R'))
      ->draw()
;


$renderer->addPage('L');

//OZ03.10 Resektionsrand/Sicherheitsabstand
$renderer
   ->matrix
      ->create('resektion', $this->_values['resektion'], array('w' => 600, 'y' => $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$description, 58, 'erkrankung')
      ->addColumn('resektion_alle_faelle',       18, 'resektion_alle_faelle', array('align' => 'R'))
      ->addColumn('resektion_r',       12, 'resektion_r', array('align' => 'R'))
      ->addColumn('resektion_s',       12, 'resektion_s', array('align' => 'R'))
      ->draw()
;

$renderer->addPage('L');

//OZ03.11 Anzahl durchgeführte Chemotherapien pro Behandlungseinheit
$renderer
   ->matrix
      ->create('chemotherapie', $this->_values['chemotherapie'], array('w' => 740, 'page' => 'L', 'y' =>  $renderer->getY()))
      ->addColumn(alcReportPdfAddonMatrix::$configDescription, 40, 'erkrankung')
      ->addColumn('chemotherapie_einrichtung',  48, 'klinikum_name', array('lookup' => array('table' => 'org', 'src' => 'org_id', 'field' => "CONCAT_WS(',', name, namenszusatz)")))
      ->addColumn('chemotherapie_durchgefuehrt',   12, 'anz_chemotherapie', array('align' => 'R'))
      ->draw()
;

?>
