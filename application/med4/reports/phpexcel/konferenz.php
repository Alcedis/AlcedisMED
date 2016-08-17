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

class reportContentKonferenz extends customReport
{
   protected $_config = null;

   protected $_reportHead = '';

   public function header(PHPExcel $renderer)
   {
      $konferenzId          = $this->getParam('konferenzId');
      $date                 = dlookup($this->_db, 'konferenz', 'datum', "konferenz_id = '$konferenzId'");
      $this->_reportHead    = concat(array($this->_config['head_report'], todate($date, 'de')), ' ');
   }

   public function init(PHPExcel $renderer){
      $this->_config = $this->loadConfigs('konferenz', false, true);
   }

   public function generate()
   {
      $this->_data       = $this->loadRessource('konferenz');

      $this->_data['A1'] = $this->_reportHead;

      $this->_data['A3'] = $this->_config['nachname'];
      $this->_data['B3'] = $this->_config['vorname'];
      $this->_data['C3'] = $this->_config['geburtsdatum'];
      $this->_data['D3'] = $this->_config['erkrankung'];
      $this->_data['E3'] = $this->_config['status'];
      $this->_data['F3'] = $this->_config['entscheid'];

      $this->parseXLS(true);
   }
}

?>