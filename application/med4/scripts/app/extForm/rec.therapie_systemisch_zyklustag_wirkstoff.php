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

$math = array(
   'kreatinin_cockroft' => array(),
   'kreatinin_jelliffe' => array()
);

$zyklusData = $this->getParam('zyklusData');

foreach ($datasets as &$dataset) {

   //Collect Data
   $age        = calc_age_on_event($zyklusData['geburtsdatum'], $zyklusData['zyklustag_datum']);
   $sex        = strlen($zyklusData['geschlecht']) ? $zyklusData['geschlecht'] : 'w';
   $creatinin  = $dataset['kreatinin'];
   $weight     = $zyklusData['gewicht'];


   $dataset['aenderung_dosis'] = tofloat($dataset['aenderung_dosis'], 'de');
   $dataset['verabreicht_dosis'] = tofloat($dataset['verabreicht_dosis'], 'de');

   //Write Data
   $dataset['kreatinin_cockroft'] = creatinine_clearance_cockroft($age, $weight, $sex, $creatinin);
   $dataset['kreatinin_jelliffe'] = creatinine_clearance_jelliffe($age, $sex, $creatinin);
}

?>