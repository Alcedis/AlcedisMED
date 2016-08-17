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

$vorlage = $this->getFieldValue('vorlage_fragebogen_id');
$vorlage_art = dlookup($this->_db, 'vorlage_fragebogen', 'art', "vorlage_fragebogen_id='{$vorlage}'");

if ($vorlage_art == 'sonst') {
    $data = dlookup($this->_db, 'vorlage_fragebogen', 'bez', "vorlage_fragebogen_id='{$vorlage}'");
} else {
    $data = dlookup($this->_db, 'l_basic', 'bez',"klasse='fragebogen_art' AND code='{$vorlage_art}'");
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