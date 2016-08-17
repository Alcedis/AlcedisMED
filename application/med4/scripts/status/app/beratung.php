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

if ($this->getFieldValue('fragebogen_ausgehaendigt') !== null) {
   $data[] = concat(array($this->getFieldLabel('fragebogen_ausgehaendigt'), $this->getFieldDescription('fragebogen_ausgehaendigt')), ': ');
}

if ($this->getFieldValue('psychoonkologie') !== null) {
   $data[] = concat(array($this->getFieldLabel('psychoonkologie'), $this->getFieldDescription('psychoonkologie')), ': ');
}

if ($this->getFieldValue('hads') !== null) {
   $data[] = concat(array($this->getFieldLabel('hads'), $this->getFieldDescription('hads')), ': ');
}

if ($this->getFieldValue('sozialdienst') !== null) {
   $data[] = concat(array($this->getFieldLabel('sozialdienst'), $this->getFieldDescription('sozialdienst')), ': ');
}

if ($this->getFieldValue('fam_risikosprechstunde') !== null) {
   $data[] = concat(array($this->getFieldLabel('fam_risikosprechstunde'), $this->getFieldDescription('fam_risikosprechstunde')), ': ');
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