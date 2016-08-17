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

$data = array(
   $this->getFieldDescription('art_diagnostik'),
   $this->getFieldDescription('art_staging'),
   $this->getFieldDescription('art_primaertumor'),
   $this->getFieldDescription('art_lk'),
   $this->getFieldDescription('art_metastasen'),
   $this->getFieldDescription('art_rezidiv'),
   $this->getFieldDescription('art_nachresektion'),
   $this->getFieldDescription('art_revision'),
   $this->getFieldDescription('art_rekonstruktion'),
   $this->getFieldValue('art_sonstige')
);

$data[] = $this->getFieldDescription('diagnose_seite');
$data[] = $this->getFieldDescription('intention');

if (check_array_content($data) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_param', null)
   ->setStatus('form_data', $data)
;

?>
