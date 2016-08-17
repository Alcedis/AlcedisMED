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

$abschnitte   = array();

//Die User Liste der Vorlagen hat eine rel. komplizierte Abfrage, deshalb dieser weg...
$querys['vorlage_arzt'] .= "
   GROUP BY u.user_id
   HAVING admin = 0
";


$isAdmin    = ($rolle_code == 'admin');

$userCount  = count(sql_query_array($db, $querys['vorlage_arzt']));
$orgCount   = count(sql_query_array($db, $querys['vorlage_org']));
$queryWhere = 'package ' . ($isAdmin === true ? 'IS NOT NULL' : 'IS NULL');

$abschnitte[] = array( 'show' => $permission->checkView('vorlage_arzt'),                  'table' => 'user',                        'caption' => $config['arzt'],                'type' => 'manuel', 'location' => 'list.vorlage_arzt', 'where' => "user_id >= 1", 'box' => $userCount );
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_organisation'),          'table' => 'org',                         'caption' => $config['organisation'],        'type' => 'manuel', 'location' => 'list.vorlage_organisation', 'where' => "org_id >= 1", 'box' => $orgCount );
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_therapie'),              'table' => 'vorlage_therapie',            'caption' => $config['therapie'],            'type' => 'count',  'location' => 'list.vorlage_therapie');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_studie'),                'table' => 'vorlage_studie',              'caption' => $config['studie'],              'type' => 'count',  'location' => 'list.vorlage_studie');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_labor'),                 'table' => 'vorlage_labor',               'caption' => $config['labor'],               'type' => 'count',  'location' => 'list.vorlage_labor');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_fragebogen'),            'table' => 'vorlage_fragebogen',          'caption' => $config['fragebogen'],          'type' => 'count',  'location' => 'list.vorlage_fragebogen');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_krankenversicherung'),   'table' => 'vorlage_krankenversicherung', 'caption' => $config['krankenversicherung'], 'type' => 'count',  'location' => 'list.vorlage_krankenversicherung');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_icd10'),                 'table' => 'vorlage_icd10',               'caption' => $config['icd10'],               'type' => 'count',  'location' => 'list.vorlage_icd10');
//$abschnitte[] = array( 'show' => $permission->checkView('vorlage_icd10_katalog'),         'table' => 'vorlage_icd10_katalog',       'caption' => $config['icd10_katalog'],       'type' => 'count',  'location' => 'list.vorlage_icd10_katalog');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_ops'),                   'table' => 'vorlage_ops',                 'caption' => $config['ops'],                 'type' => 'count',  'location' => 'list.vorlage_ops');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_icdo'),                  'table' => 'vorlage_icdo',                'caption' => $config['icdo'],                'type' => 'count',  'location' => 'list.vorlage_icdo');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_dokument'),              'table' => 'vorlage_dokument',            'caption' => $config['dokument'],            'type' => 'count',  'location' => 'list.vorlage_dokument');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_fallkennzeichen'),       'table' => 'vorlage_fallkennzeichen',     'caption' => $config['fallkennzeichen'],     'type' => 'count',  'location' => 'list.vorlage_fallkennzeichen');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_konferenztitel'),        'table' => 'vorlage_konferenztitel',      'caption' => $config['konferenztitel'],      'type' => 'count',  'location' => 'list.vorlage_konferenztitel');
$abschnitte[] = array( 'show' => $permission->checkView('vorlage_query'),                 'table' => 'vorlage_query',               'caption' => $config['query'],               'type' => 'count',  'location' => 'list.vorlage_query');

make_abschnitt($smarty, $db, $abschnitte);

?>