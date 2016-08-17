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

if (appSettings::get('active', 'hl7') === true) {

   require_once 'feature/hl7/initial/queries.php';

   $loadHl7Main = false;

   switch (appSettings::get('patient_ident', 'hl7')) {
      case 'patientid':

         $groupBy    = 'org_id, patient_nr';
         $leftJoin   = 'p.patient_nr = c.patient_nr';

         break;

      case  'ukey':

         $groupBy    = 'org_id, nachname, vorname, geburtsdatum';
         $leftJoin   = 'p.nachname = c.nachname AND p.vorname = c.vorname AND p.geburtsdatum = c.geburtsdatum';

         break;

      default:

         $groupBy = 'org_id, nachname, vorname, geburtsdatum, patient_nr';

         break;
   }

   switch ($pageName) {

      case 'list.patient':

         //Checken ob es hl7_diagnose datensaetze gibt auf die man ein recht haette
         if ($recht_id !== null) {

            $hl7DiagnoseWhere       = "d.org_id = '{$org_id}'";
            $hl7DiagnoseRoleWhere   = null;

            $rechtGlobal = dlookup($db, 'recht', 'recht_global', "recht_id = '{$recht_id}'");

            $erkrankungRechtBez = isset($_SESSION['sess_recht_erkrankung_bez']) === true ? $_SESSION['sess_recht_erkrankung_bez'] : array();

            if (count($erkrankungRechtBez) && ($rolle_code == 'supervisor' && $rechtGlobal == 1) == false) {
                $erkWhere = array();

                $rechtErk = $erkrankungRechtBez;

                if (array_key_exists('sess_recht_erkrankung', $_SESSION) && in_array('sst', $_SESSION['sess_recht_erkrankung'])) {

                   $sstDetail = dlookup($db, 'l_basic', 'GROUP_CONCAT(bez)', "klasse = 'erkrankung_sst_detail' GROUP BY klasse");

                   $rechtErk = array_merge($rechtErk, explode(',', $sstDetail));
                }

                foreach ($rechtErk as $checkerk)
                    $erkWhere[] = "p.erkrankungen LIKE '%$checkerk%'\n";

                $hl7DiagnoseRoleWhere .= ' AND (' . implode(' OR ', $erkWhere) . ')';
            }

            $showHl7Diagnose = reset(sql_query_array($db, "
               SELECT
                  COUNT(d.hl7_diagnose_id) AS 'count'
               FROM hl7_diagnose d
                  INNER JOIN patient p ON p.patient_id = d.patient_id {$hl7DiagnoseRoleWhere}

               WHERE {$hl7DiagnoseWhere}
            "));

            //Hl7 Diagnose Import
            if ($showHl7Diagnose['count'] > 0) {
               $arr_menubar['patient']['custom'][] = "<a href='index.php?page=list.hl7_diagnose&amp;feature=hl7' class='button_large'>{$config['lbl_import_hl7_diagnosis']}</a>";
            }

         }

         $loadHl7Main = true;

         break;


      case 'preview':
      case 'rec.log_cache':

         $loadHl7Main = true;

        break;

      case 'list.patient_import':

         //HL7 Injection to patient_import

         $patImportHaving = '1';

         foreach (explode("','", $queryRechtErkrankung) as $rechtErkrankung) {
            $patImportHaving .= " AND LOCATE('-{$rechtErkrankung}-', erk) = 0";
         }

         $querys['patient_import'] = "
            SELECT
               p.patient_id,
               p.org_id,
               p.id,
               p.nachname,
               p.vorname,
               p.geburtsdatum,
               p.createtime,
               p.patient_nr,
               p.erkrankung_id,
               p.aufnahme_nr,
               p.erkrankung,
               p.erklist,
               p.createtime_en
            FROM (

                SELECT
                   x.patient_id,
                   x.org_id,
                   GROUP_CONCAT(x.id SEPARATOR '|') AS id,
                   x.nachname,
                   x.vorname,
                   x.geburtsdatum,
                   x.createtime,
                   x.patient_nr,
                   x.erkrankung_id,
                   x.aufnahme_nr,
                   MAX(x.erk) AS erkrankung,
                   GROUP_CONCAT(DISTINCT x.erklist SEPARATOR ' ') AS 'erklist',
                   x.createtime_en

               FROM (
                   (
                      {$querys['patient_import']}
                      GROUP BY p.patient_id
                      HAVING {$patImportHaving}
                   ) UNION (
                      SELECT
                         CONCAT_WS('_', 'hl7', MIN(hl7_cache_id))                    AS patient_id,
                         org_id                                                      AS org_id,
                         CONCAT_WS('_', 'hl7', MIN(hl7_cache_id))                    AS id,
                         nachname                                                    AS nachname,
                         vorname                                                     AS vorname,
                         geburtsdatum                                                AS geburtsdatum,
                         DATE_FORMAT(hl7_cache.createtime, '%d.%m.%Y')               AS createtime,
                         createtime                                                  AS createtime_en,
                         patient_nr                                                  AS patient_nr,
                         null                                                        AS erkrankung_id,
                         GROUP_CONCAT(DISTINCT hl7_cache.aufnahme_nr SEPARATOR ', ') AS aufnahme_nr,
                         IF(erkrankung IN('{$queryRechtErkrankung}'), MIN(erkrankung), '') AS erk,
                         IF(
                            erkrankung IN('{$queryRechtErkrankung}'),
                            (SELECT bez FROM l_basic WHERE klasse = 'erkrankung' AND code = MIN(erkrankung)),
                            NULL
                         ) AS erklist
                      FROM hl7_cache
                      GROUP BY {$groupBy}
                   )
               ) x

               GROUP BY {$groupBy}

            ) p
         ";

         $loadHl7Main = true;

         break;

      case 'cache':
      case 'convert':

         //Zugriff auf Cache immer gestatten
         $verified            = true;
         $permissionGranted   = true;
         $loadHl7Main = true;

         break;
   }


    switch ($page) {
        case 'preview':
        case 'log_cache':

            // permission check
            $tmpMatrix = isset($_SESSION['sess_permission_matrix']) ? $_SESSION['sess_permission_matrix'] : null;

            if ($tmpMatrix !== null && isset($tmpMatrix['hl7_' . $page]) === true && strlen(trim($tmpMatrix['hl7_' . $page])) > 0) {
                $permissionGranted = true;
            }

            unset($tmpMatrix);

            break;
    }


   if ($loadHl7Main === true) {
        require_once 'feature/hl7/class/hl7Main.php';

        $hl7FilterSections = array('diagnose', 'diagnosetyp', 'abteilung');

        hl7Main::getInstance($db, $smarty)
            ->setAutoSections(hl7Main::$sections)
            ->setFieldSettings(sql_query_array($db, "SELECT * FROM settings_hl7field"))
            ->setUkeys(array(
                'patientid' => array('org_id', 'patient_nr'),
                'ukey'      => array('org_id', 'nachname', 'vorname', 'geburtsdatum')
            ))
            ->setLookups(getLookup($db, null, 'l_imp_hl7'))
            ->setSettings(array_merge(
                    array('diseaseRestriction' => array_flip(explode(',', appSettings::get('erkrankungen')))),
                    appSettings::get(null, 'hl7')
            ))
            ->setFilter($hl7FilterSections)
        ;

        //Cache Filter & Import Filter
        foreach (array('cache', 'import') as $hl7FilterType) {
            foreach ($hl7FilterSections as $hl7FilterSection) {

                if ($hl7FilterType === 'import' && $hl7FilterSection === 'abteilung')
                    continue;

                if (hl7Main::getInstance()->getSettings(concat(array($hl7FilterType, $hl7FilterSection, 'active'), '_')) == 1) {

                    $hl7FieldSetting = array(
                       'med_feld' => "{$hl7FilterType}.{$hl7FilterSection}",
                       'hl7'      => hl7Main::getInstance()->getSettings("{$hl7FilterType}_{$hl7FilterSection}_hl7"),
                       'filter'   => hl7Main::getInstance()->getSettings("{$hl7FilterType}_{$hl7FilterSection}_filter"),
                       'feld_typ' => 'string',
                       'feld_trim_null' => null,
                       'multiple' => 'all'
                    );

                    if ($hl7FilterType === 'import') {
                        $hl7FieldSetting['import'] = 1;
                    }

                    hl7Main::getInstance()->addFieldSetting("{$hl7FilterType}.{$hl7FilterSection}", $hl7FieldSetting);
                }
            }
        }
   }

   unset($loadHl7Main);
}

?>
