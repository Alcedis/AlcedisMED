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

//04.1 Gesamtüberleben (OAS)
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['041'], $config['caption_041'])
;

$renderer->addPage('L');

//04.2 Krankheitsfreies Überleben (DFS)
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['042'], $config['caption_042'], null, 500)
;

$renderer->addPage('L');

//04.3 Lokalrezidivfreies Überleben
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['043'], $config['caption_043'],null, 500)
;

$renderer->addPage('L');

//04.4 Fernmetastasenfreies Überleben
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['044'], $config['caption_044'],null, 500)
;

$renderer->addPage('L');

//04.5 Überleben ab Progression/Rezidiv (PDS)
$renderer
    ->chart
        ->kaplanMeier($this->_values['045'], $config['caption_045'], null, 500)
;

?>