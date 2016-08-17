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

$data = array();

if ($this->getFieldValue('op') == 1) {
   $data[] = $this->getFieldLabel('op');
}

if ($this->getFieldValue('strahlen') == 1) {
   $data[] = $this->getFieldLabel('strahlen');
}

if ($this->getFieldValue('chemo') == 1) {
   $data[] = $this->getFieldLabel('chemo');
}

if ($this->getFieldValue('immun') == 1) {
   $data[] = $this->getFieldLabel('immun');
}

if ($this->getFieldValue('ah') == 1) {
   $data[] = $this->getFieldLabel('ah');
}

if ($this->getFieldValue('andere') == 1) {
   $data[] = $this->getFieldLabel('andere');
}

if ($this->getFieldValue('sonstige') == 1) {
   $data[] = $this->getFieldLabel('sonstige');
}

if ($this->getFieldValue('watchful_waiting') == 1) {
   $data[] = $this->getFieldLabel('watchful_waiting');
}

if ($this->getFieldValue('active_surveillance') == 1) {
   $data[] = $this->getFieldLabel('active_surveillance');
}

if ($this->getFieldValue('nachsorge') == 1) {
   $data[] = $this->getFieldLabel('nachsorge');
}

if (check_array_content($data) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>