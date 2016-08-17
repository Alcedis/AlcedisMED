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

//04.1.1 Gesamtüberleben (OAS) nach UICC-Stadium
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['041_uicc'], $config['caption_041_uicc'])
;

$renderer->addPage('L');

//04.1.2 Gesamtüberleben (OAS) nach pT-Stadium
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['041_pt'], $config['caption_041_pt'], null, 500)
;

$renderer->addPage('L');

//04.2.1 Rezidivfreies Überleben (DFS) nach UICC-Stadium
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['042_uicc'], $config['caption_042_uicc'],null, 500)
;

$renderer->addPage('L');

//04.2.2 Rezidivfreies Überleben (DFS) nach pT-Stadium
$renderer
    ->chart
        ->kaplanMeierMulti($this->_values['042_pt'], $config['caption_042_pt'],null, 500)
;

?>