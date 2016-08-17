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

$i = 0;
foreach ($this->_activeTypes as $name => $type) {
   if ($i > 0) {
      $renderer->addPage('L');
   }

   //04.1 Gesamtüberleben (OAS)
   $renderer
       ->chart
           ->kaplanMeierMulti($this->_values['041'][$name], concat(array($config['caption_041'], $config['lbl_' . $name]), ' '))
   ;

   $renderer->addPage('L');

   //04.2 Krankheitsfreies Überleben (DFS)
   $renderer
       ->chart
           ->kaplanMeierMulti($this->_values['042'][$name], concat(array($config['caption_042'], $config['lbl_' . $name]), ' '), null, 500)
   ;

   $renderer->addPage('L');

   //04.3 Lokalrezidivfreies Überleben
   $renderer
       ->chart
           ->kaplanMeierMulti($this->_values['043'][$name], concat(array($config['caption_043'], $config['lbl_' . $name]), ' '),null, 500)
   ;

   $renderer->addPage('L');

   //04.4 Fernmetastasenfreies Überleben
   $renderer
       ->chart
           ->kaplanMeierMulti($this->_values['044'][$name], concat(array($config['caption_044'], $config['lbl_' . $name]), ' '),null, 500)
   ;

   $renderer->addPage('L');

   //04.5 Überleben ab Progression/Rezidiv (PDS)
   $renderer
       ->chart
           ->kaplanMeier($this->_values['045'][$name], concat(array($config['caption_045'], $config['lbl_' . $name]), ' '), null, 500)
   ;

   $i++;
}

?>