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

$maxLen = '255';
$size = '50';

if (appSettings::get('patient_initials_only') === true) {
    $maxLen = '1';
    $size = '1';
}

$query_org  = "
   SELECT
      o.org_id,
      CONCAT_WS(', ', o.ort, o.name)
   FROM org o
   WHERE o.org_id > 0
   ORDER BY
      o.ort, o.name
";

$fields = array(
    'patient_id'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'hidden',    'ext' => ''),
    'org_id'                        => array('req' => 1, 'size' => '',    'maxlen' => '11',    'type' => 'query',     'ext' => $query_org),
    'patient_nr'                    => array('req' => 1, 'size' => '50',  'maxlen' => '25',    'type' => 'string',    'ext' => ''),
    'titel'                         => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'adelstitel'                    => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'nachname'                      => array('req' => 1, 'size' => $size, 'maxlen' => $maxLen, 'type' => 'string',    'ext' => ''),
    'vorname'                       => array('req' => 1, 'size' => $size, 'maxlen' => $maxLen, 'type' => 'string',    'ext' => ''),
    'geschlecht'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'geschlecht') ),
    'geburtsdatum'                  => array('req' => 1, 'size' => '',    'maxlen' => '11',    'type' => 'date',      'ext' => ''),
    'geburtsname'                   => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'geburtsort'                    => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'datenaustausch'                => array('req' => 0, 'size' => '',    'maxlen' => '1',     'type' => 'check',     'ext' => ''),
    'datenspeicherung'              => array('req' => 0, 'size' => '',    'maxlen' => '1',     'type' => 'check',     'ext' => ''),
    'datenversand'                  => array('req' => 0, 'size' => '',    'maxlen' => '1',     'type' => 'check',     'ext' => ''),
    'krebsregister'                 => array('req' => 0, 'size' => '',    'maxlen' => '1',     'type' => 'check',     'ext' => ''),
    'strasse'                       => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'hausnr'                        => array('req' => 0, 'size' => '5',   'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'adrzusatz'                     => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'plz'                           => array('req' => 0, 'size' => '5',   'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'ort'                           => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'land'                          => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'staat')),
    'telefon'                       => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'telefax'                       => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'email'                         => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'staat'                         => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'staat')),
    'kv_iknr'                       => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'code_ktst', 'ext' => ''),
    'kv_abrechnungsbereich'         => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_abrechnungsbereich')),
    'kv_nr'                         => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'kv_fa'                         => array('req' => 0, 'size' => '',    'maxlen' => '255',   'type' => 'string',    'ext' => ''),
    'kv_status'                     => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_status')),
    'kv_statusergaenzung'           => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_statusergaenzung')),
    'kv_wop'                        => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_wop')),
    'kv_besondere_personengruppe'   => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_besondere_personengruppe')),
    'kv_dmp_kennzeichnung'          => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'lookup',    'ext' => array('l_basic' => 'kv_dmp_kennzeichnung')),
    'kv_versicherungsschutz_beginn' => array('req' => 0, 'size' => '',    'maxlen' => '',      'type' => 'date',      'ext' => '', 'range' => false),
    'kv_versicherungsschutz_ende'   => array('req' => 0, 'size' => '',    'maxlen' => '',      'type' => 'date',      'ext' => '', 'range' => false),
    'kv_gueltig_bis'                => array('req' => 0, 'size' => '',    'maxlen' => '',      'type' => 'date',      'ext' => '', 'range' => false),
    'kv_einlesedatum'               => array('req' => 0, 'size' => '',    'maxlen' => '',      'type' => 'date',      'ext' => ''),
    'bem'                           => array('req' => 0, 'size' => '',    'maxlen' => '',      'type' => 'textarea',  'ext' => ''),
    'createuser'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'hidden',    'ext' => ''),
    'createtime'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'hidden',    'ext' => ''),
    'updateuser'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'hidden',    'ext' => ''),
    'updatetime'                    => array('req' => 0, 'size' => '',    'maxlen' => '11',    'type' => 'hidden',    'ext' => '')
);

?>
