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

$parentId = $this->getFieldValue('dmp_brustkrebs_ed_2013_id');

$caseNr = dlookup($this->_db, 'dmp_brustkrebs_ed_2013', 'fall_nr', "dmp_brustkrebs_ed_2013_id = '{$parentId}'");

$data[] = concat(array($this->getFieldLabel('fall_nr', 'dmp_brustkrebs_ed_2013'), $caseNr), ': ');
$data[] = concat(array($this->getFieldLabel('einschreibung_grund', 'dmp_brustkrebs_fd_2013'), $this->getFieldDescription('einschreibung_grund')), ': ');

if (check_array_content($data) !== true) {
    $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('doku_datum'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>
