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

$query_konferenz_patient = "
   SELECT
      kp.konferenz_patient_id,
      CONCAT_WS(' - ',
         CONCAT_WS(' ',
            CONCAT_WS(', ', patient.nachname, patient.vorname),
            CONCAT('(', DATE_FORMAT(patient.geburtsdatum, '%d.%m.%Y'), ')')
         ),
         CONCAT_WS(' ', el.bez,
            CONCAT('(', art.bez, ')')
         )
      )
   FROM konferenz_patient kp
      LEFT JOIN l_basic art      ON art.klasse='tumorkonferenz_art' AND art.code=kp.art
      LEFT JOIN erkrankung e     ON e.erkrankung_id = kp.erkrankung_id

         LEFT JOIN l_basic el    ON el.klasse = IF(e.erkrankung_detail IS NOT NULL, 'erkrankung_sst_detail', 'erkrankung') AND
                                    el.code = IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung)


      LEFT JOIN patient patient  ON patient.patient_id = kp.patient_id
   WHERE
      konferenz_id = 'KONFERENZ_ID'
   GROUP BY
      patient.patient_id
";

$query_dokument = "
    SELECT
       dokument_id,
       SUBSTRING(dokument,15)
    FROM dokument
";

$fields = array(
   'konferenz_dokument_id' => array('req' => 0, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden',  'ext' => ''),
   'konferenz_id'          => array('req' => 1, 'size' => '',  'maxlen' => '11' , 'type' => 'hidden',  'ext' => ''),
   'bez'                   => array('req' => 1, 'size' => '',  'maxlen' =>'255' , 'type' => 'string',  'ext' => ''),
   'datei'                 => array('req' => 3, 'size' => '' , 'maxlen' => ''   , 'type' => 'file',    'ext' => ''),
   'konferenz_patient_id'  => array('req' => 0, 'size' => '' , 'maxlen' => ''   , 'type' => 'query',   'ext' => $query_konferenz_patient),
   'dokument_id'           => array('req' => 3, 'size' => '',  'maxlen' => '11' , 'type' => 'query',   'ext' => $query_dokument),
   'bem'                   => array('req' => 0, 'size' => '' , 'maxlen' => ''  , 'type' => 'textarea', 'ext' => ''),
   'createuser'            => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',  'ext' => ''),
   'createtime'            => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',  'ext' => ''),
   'updateuser'            => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',  'ext' => ''),
   'updatetime'            => array('req' => 0, 'size' => '',  'maxlen' => '11',  'type' => 'hidden',  'ext' => '')
);

?>