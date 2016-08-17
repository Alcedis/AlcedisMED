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

if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$auswertungen = array();

//Eigene Auswertungen
$query = "
   SELECT
      vq.vorlage_query_id                          AS id,
      'self'                                       AS report_type,
      IF(MAX(vqo.vorlage_query_org_id) IS NOT NULL,
         CONCAT_WS('', '-', GROUP_CONCAT(DISTINCT vqo.org_id SEPARATOR '-'), '-'),
         NULL
      )                                            AS orgcheck,
      CONCAT('query&amp;id=', vq.vorlage_query_id) AS name,
      vq.bez                                       AS caption,
      vq.bez                                       AS caption_name,
      vq.erkrankung,
      IF(vq.typ IS NULL, 'xls', typ)               AS type,
      IF(vq.typ IS NULL, 'xls', typ)               AS img,
      IF(vq.package IS NULL, true, NULL)           AS direct
   FROM vorlage_query vq
      LEFT JOIN vorlage_query_org vqo ON vqo.vorlage_query_id = vq.vorlage_query_id
   WHERE
      vq.freigabe IS NOT NULL AND vq.inaktiv IS NULL
   GROUP BY
      vq.vorlage_query_id
";

$resultEigeneAuswertungen = sql_query_array($db, $query);

$eigeneAuswertungen = array();

foreach ($resultEigeneAuswertungen AS  $i => $auswertung) {
   if (strlen($auswertung['orgcheck']) > 0) {
       if (str_contains($auswertung['orgcheck'], "-{$org_id}-") == false) {
           unset($resultEigeneAuswertungen[$i]);
           continue;
       }
   }

   if (strlen($auswertung['erkrankung']) == 0) {
      $eigeneAuswertungen[] = $auswertung;
      unset($resultEigeneAuswertungen[$i]);
   }
}

if (count($eigeneAuswertungen) > 0) {
   $auswertungen[] = array(
      'label' => $config['lbl_eigene_auswertungen'],
      'type'  => 'self',
      'auswertungen' => array(
          'self' => $eigeneAuswertungen
      )
   );
}

$eigeneAuswertungErkrankung = array();

foreach ($resultEigeneAuswertungen AS $auswertung) {
   $eigeneAuswertungErkrankung[$auswertung['erkrankung']][] = $auswertung;
}

//Rechte auf Erkrankung

$appErkrankung       = explode(',', appSettings::get('erkrankungen'));
$sessRechtErkrankung = $_SESSION['sess_recht_erkrankung'];

$allowedReports = array();

foreach ($appErkrankung as $erk) {
   if (in_array($erk, $sessRechtErkrankung) === true) {
      $allowedReports[] = $erk;
   }
}

$erkrankungen = implode("', '", $allowedReports);

$rechtId        = isset($_SESSION['sess_recht_id']) === true ? $_SESSION['sess_recht_id'] : NULL;
$rechtGlobal    = strlen($rechtId) > 0 ? dlookup($db, 'recht', 'recht_global', "recht_id = '$rechtId'") : NULL;

$query = "
   SELECT
      code,
      bez
   FROM l_basic
   WHERE
      klasse = 'erkrankung' AND code IN ('$erkrankungen')
";

if (strlen($rechtGlobal) > 0) {
    $query .= "UNION
        SELECT
            'oz'                            AS 'code',
            '{$config['lbl_onko_zentrum']}' AS 'bez'
        ORDER BY
        bez"
    ;
}

$result = sql_query_array($db, $query);

$configBackup = $smarty->get_config_vars();

$smarty->clear_config();

foreach ($result AS $erkrankungen) {
    $code = $erkrankungen['code'];

    $reports = array();

    $reportSettings = dlookup($db, 'settings_report', 'settings', "erkrankung = '{$code}'");

    if (strlen($reportSettings) === 0) {
        continue;
    }

    $reportSettings = json_decode($reportSettings, true);

    $smarty->config_load("../reports/config/{$code}/version.conf");

    $config = $smarty->get_config_vars();

    $ver   = $config['report_version'];
    $title = $config['report_title'];

    $smarty->clear_config();

    //Prüfen, ob Feature für Auswertungen aktiviert ist
    if (array_key_exists('feature', $reportSettings) === true) {
        $feature = $reportSettings['feature'];

        $multipleFeature = false;

        if (is_array($feature) === true) {
            foreach ($feature as $curFeature) {
                if (appSettings::get("feature_{$curFeature}") === true) {
                    $multipleFeature = true;
                }
            }
        } else {
            if (appSettings::get("feature_{$feature}") === null && $multipleFeature === false) {
                continue;
            }
        }
    }

    unset($reportSettings['feature']);

    foreach ($reportSettings as $report) {
        $active = $report['active'];

        if ($active === false) {
            continue;
        }

        $report['sub'] = $code;

        if (array_key_exists('img', $report) === false) {
            $report['img'] = $report['type'];
        }

        $type = $report['type'];

        $report['help'] = file_exists("media/help/reports/{$code}/{$report['name']}.pdf");

        if ($type !== 'feature') {
            $report['caption_name'] = str_replace($code, $title, str_replace('_', '.', $report['name']));
        } else {
            if (str_contains(appSettings::get('interfaces'), $report['name']) === false) {
                continue;
            }

            $report['caption_name'] = '';

            $params = array();

            foreach ($report['param'] as $paramName => $paramValue) {
                $params[] = "{$paramName}={$paramValue}";
            }

            $report['link'] = implode('&', $params);
        }

        $smarty->config_load("../reports/config/{$code}/{$report['name']}.conf");

        $config = $smarty->get_config_vars();

        $smarty->clear_config();

        $report['caption'] = $config['head_report'];

        $reports[] = $report;
    }

    $auswertungen[] = array(
        'label' => $erkrankungen['bez'],
        'version' => $ver,
        'type' => 'report',
        'auswertungen' => array(
            'dkg'  => $reports,
            'self' => (isset($eigeneAuswertungErkrankung[$code]) ? $eigeneAuswertungErkrankung[$code] : array())
        )
    );
}

foreach ($auswertungen as $i => $block) {
    foreach ($block['auswertungen'] as $type => $reports) {
        $auswertungen[$i]['auswertungen'][$type] = sort_by_key($reports, 'caption_name');
    }
}

$smarty->set_config($configBackup);

$smarty
   ->assign('auswertungen', sort_by_key($auswertungen, 'label', 'eigen'))
;

?>
