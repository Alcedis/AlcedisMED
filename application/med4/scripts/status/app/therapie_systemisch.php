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

$therapie_id = $this->getFieldValue('vorlage_therapie_id');

$result = reset(sql_query_array($this->_db, "
   SELECT
      bez,
      art
   FROM vorlage_therapie
   WHERE
      vorlage_therapie_id = '$therapie_id'
"));

$data = array(
   $result['bez'],
   $this->getFieldDescription('intention')
);

//Nur wenn Therapie == Chemo/Immun (mit Kombis)
$stZyklusTagAllowed = 0;

if (in_array($result['art'], array('ist', 'c', 'ci', 'cst', 'i', 'son', 'sonstr'))) {
   $stZyklusTagAllowed = 1;
}

if (check_array_content($data) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('beginn'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
   ->setStatus('form_param', $stZyklusTagAllowed)
;

?>