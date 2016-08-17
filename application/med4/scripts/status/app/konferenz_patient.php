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

$konferenzId         = $this->getFieldValue('konferenz_id');
$konferenzPatientId  = $this->getFieldValue('konferenz_patient_id');

$data = array(
   $this->getFieldDescription('art')
);

$date = '';

$reportParam = array(
   'art' => $this->getFieldValue('art')
);


if ($konferenzId !== null) {

   $konferenz = reset(sql_query_array($this->_db, "
      SELECT
         datum,
         bez
      FROM konferenz
      WHERE konferenz_id = '{$konferenzId}'
   "));

   $date = $konferenz['datum'];

   $reportParam['konferenzDatum'] = $date;

   $data[] = format_date($date, 'de');
   $data[] = $konferenz['bez'];
} else {
   $reportParam['konferenzDatum'] = '';
}


//Prüfen ob es einen Therapieplan gibt, der auf diesen Datensatz referenziert
if ($konferenzPatientId !== null) {
   $therapieplanId = dlookup($this->_db, 'therapieplan', 'therapieplan_id', "konferenz_patient_id = $konferenzPatientId");

   $this->setStatus('form_param', (strlen($therapieplanId) > 0 ? 1 : null));
}

if (check_array_content($data) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $date)
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('report_param', implode('|', $reportParam))
   ->setStatus('form_data', $data)
;

?>