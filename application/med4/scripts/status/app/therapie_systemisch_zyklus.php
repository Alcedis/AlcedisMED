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

$therapie_sys_id = $this->getFieldValue('therapie_systemisch_id');

$result = reset(sql_query_array($this->_db, "
   SELECT
      v.bez
   FROM therapie_systemisch t
      LEFT JOIN vorlage_therapie v ON v.vorlage_therapie_id = t.vorlage_therapie_id
   WHERE
      t.therapie_systemisch_id = '$therapie_sys_id'
"));

$content = array(
   'therapieschema'  => $result['bez'],
   'zyklus_nr'       => $this->getFieldValue('zyklus_nr')
);

$data = array(
   $content['therapieschema'],
   array('lbl' => $this->getFieldLabel('zyklus_nr'), 'value' => $content['zyklus_nr'], 'connector' => ' ')
);

if (check_array_content($content) !== true) {
   $data = '-';
}

$this
   ->setStatus('form_date', $this->getFieldValue('beginn'))
   ->setStatus('erkrankung_id', $this->getFieldValue('erkrankung_id'))
   ->setStatus('form_data', $data)
;

?>