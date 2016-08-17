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

$konferenz_id = isset($_REQUEST['konferenz_id']) === true ? $_REQUEST['konferenz_id'] : null;

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

if ($konferenz_id !== null) {
   $konferenzDatum = dlookup($db, 'konferenz', "DATE_FORMAT(datum,'%d.%m.%Y')" , "konferenz_id='{$konferenz_id}'");
   $konferenzTitel = dlookup($db, 'konferenz', 'bez' , "konferenz_id='{$konferenz_id}'");

   //Caption / Title
   $smarty
       ->assign('caption', concat(array($config['caption'], $konferenzDatum), $config['lbl_vom']))
       ->assign('titel', $konferenzTitel)
   ;

   //Patienten holen
   $fieldsKonferenzPatient = $widget->loadExtFields('fields/app/konferenz_patient.php');
   $fieldsKonferenzPatient = array_merge($fieldsKonferenzPatient, $widget->loadExtFields('fields/app/patient.php'));

   $q = "SELECT code,bez FROM l_basic WHERE klasse = 'erkrankung' OR klasse = 'erkrankung_sst_detail'";

   $fieldsKonferenzPatient['erkrankung']  = array('type' => 'query', 'ext' => $q);
   $fieldsKonferenzPatient['param']       = array('type' => 'string');

   $query = "
      SELECT
         kp.*,
         p.*,
         IF(e.erkrankung_detail IS NOT NULL, e.erkrankung_detail, e.erkrankung) AS erkrankung,
         CONCAT_WS('', '{\'type\':\'kp\',' , '\'id\':\'', kp.konferenz_patient_id , '\'}') AS param
      FROM konferenz_patient kp
         LEFT JOIN patient p     ON kp.patient_id = p.patient_id
         LEFT JOIN erkrankung e  ON kp.erkrankung_id = e.erkrankung_id
      WHERE
         kp.konferenz_id='$konferenz_id'
   ";

   data2list($db, $fieldsKonferenzPatient, $query);

   //Dokumente
   $fieldsKonferenzDokument = $widget->loadExtFields('fields/app/konferenz_dokument.php');
   $fieldsKonferenzDokument = array_merge($fieldsKonferenzDokument, $widget->loadExtFields('fields/app/patient.php'));

   $fieldsKonferenzDokument['type']         = array('type' => 'string');
   $fieldsKonferenzDokument['param']        = array('type' => 'string');
   $fieldsKonferenzDokument['patient']      = array('type' => 'string');


   $query = "
      SELECT
         kd.*,
         p.*,
         CONCAT_WS(', ', p.nachname, p.vorname) AS patient,
         IF (d.dokument_id IS NOT NULL,
             RIGHT(d.dokument, 3),
             RIGHT(kd.datei, 3)
         ) AS type,
         CONCAT_WS('', '{\'type\':\'kd\',' , '\'id\':\'', kd.konferenz_dokument_id , '\'}') AS param
      FROM konferenz_dokument kd
         LEFT JOIN konferenz_patient kp ON kp.konferenz_patient_id = kd.konferenz_patient_id
         LEFT JOIN dokument d ON d.dokument_id = kd.dokument_id
             LEFT JOIN erkrankung e ON kp.erkrankung_id = e.erkrankung_id OR d.erkrankung_id = e.erkrankung_id
                LEFT JOIN patient p ON p.patient_id = e.patient_id
      WHERE
         kd.konferenz_id = '{$konferenz_id}'
      GROUP BY
         kd.konferenz_dokument_id
   ";

   data2list($db, $fieldsKonferenzDokument, $query);

   //Teilnehmer
   $fieldsKonferenzTeilnehmer = $widget->loadExtFields('fields/app/konferenz_teilnehmer.php');
   $fieldsKonferenzTeilnehmer = array_merge($fieldsKonferenzTeilnehmer, $widget->loadExtFields('fields/base/user.php'));

   $fieldsKonferenzTeilnehmer['name'] = array('type' => 'string');

   $query = "
      SELECT
         kt.*,
         u.*,
         CONCAT_WS(', ',
            CONCAT_WS(' ', anrede.bez, u.titel, u.vorname, u.nachname)
         ) AS 'name'
      FROM konferenz_teilnehmer kt
         LEFT JOIN user u  ON u.user_id = kt.user_id
            LEFT JOIN l_basic anrede   ON u.anrede=anrede.code AND anrede.klasse='anrede'
      WHERE
         kt.konferenz_id = '$konferenz_id'
         AND kt.teilgenommen IS NOT NULL
      ORDER BY
         u.nachname,
         u.vorname
   ";

   data2list($db, $fieldsKonferenzTeilnehmer, $query);

   $smarty
      ->assign('fieldsKonferenzPatient'   , $fieldsKonferenzPatient)
      ->assign('fieldsKonferenzDokument'  , $fieldsKonferenzDokument)
      ->assign('fieldsKonferenzTeilnehmer', $fieldsKonferenzTeilnehmer);
}

$smarty
   ->assign('back_btn',    "page=list.konferenz");

?>