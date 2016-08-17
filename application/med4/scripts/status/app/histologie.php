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

$content = array(
   'histologie_nr'      => $this->getFieldValue('histologie_nr'),
   'morphologie'        => $this->getFieldValue('morphologie'),
   'morphologie_text'   => $this->getFieldValue('morphologie_text'),
   'diagnose_seite'     => $this->getFieldDescription('diagnose_seite'),
   'pt'                 => $this->getFieldDescription('pt'),
   'pn'                 => $this->getFieldDescription('pn'),
   'pm'                 => $this->getFieldDescription('pm'),
   'g'                  => $this->getFieldDescription('g'),
   'l'                  => $this->getFieldDescription('l'),
   'v'                  => $this->getFieldDescription('v'),
   'r'                  => $this->getFieldDescription('r')
);

$data = array(
   array('lbl' => $this->getFieldLabel('histologie_nr'), 'value' => $content['histologie_nr'], 'connector' => ' '),
   $content['morphologie'],
   $content['morphologie_text'],
   trim(implode(' ', array(
      $content['pt'],
      $content['pn'],
      $content['pm'],
      $content['g'],
      $content['l'],
      $content['v'],
      $content['r'],
   ))),
   $content['diagnose_seite'],
);

if (check_array_content($content) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>