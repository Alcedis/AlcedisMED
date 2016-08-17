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

$erkrankungVal       = $this->getFieldValue('erkrankung');
$erkrankungDetailVal = $this->getFieldValue('erkrankung_detail');

$erkrankung = dlookup($this->_db, 'l_basic', "bez", "klasse = 'erkrankung' AND code = '{$erkrankungVal}'");

if (strlen($erkrankungDetailVal) > 0) {
    $erkrankungDetail = dlookup($this->_db, 'l_basic', "bez", "klasse = 'erkrankung_sst_detail' AND code = '{$erkrankungDetailVal}'");

    $erkrankung .= " ({$erkrankungDetail})";
}

$data = array(
   $erkrankung,
   $this->getFieldValue('beschreibung')
);

if ($this->getFieldValue('erkrankung_relevant') === '1') {
    /* @var status $this */
    $configLabel = $this->getConfigLabel("erkrankung_relevant_ext_{$erkrankungVal}");

    if ($configLabel === null) {
        $configLabel = $this->getFieldLabel('erkrankung_relevant');
    }

    $data[] = $configLabel;
}

if ($this->getFieldValue('erkrankung_relevant_haut') !== null) {
    $year = $this->getFieldValue('erkrankung_relevant_haut');
    $data[] = $this->getFieldLabel('erkrankung_relevant_haut') . ' ' . $year;
}

if (check_array_content($data) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('datum'))
   ->setStatus('form_data', $data)
;

?>
