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

//Color picker addon
$reportsSettings = dlookup($db, 'settings_report', 'settings', "erkrankung = '{$sub}'");

if (strlen($reportsSettings) > 0) {
    $reportsSettings = json_decode($reportsSettings, true);
    $reportSettings  = array_key_exists($name, $reportsSettings) === true ? $reportsSettings[$name] : null;

    if ($reportSettings !== null) {
        //Chart vorhanden
        if (array_key_exists('chart', $reportSettings) == true) {
            //Base chart
            $colors = array();

            foreach ($config as $configKey => $configValue) {
                if (str_starts_with($configKey, 'chart_default_')) {
                    $section = substr($configKey, 14);

                    foreach (explode('|', $configValue) as $index => $secColors) {
                        $tmp = explode('=', $secColors);
                        $colors[$section][reset($tmp)] = end($tmp);
                    }
                }
            }

            //Cookie Chart
            $cookieChart = cookie::create($user_id, $pageName)
                ->getValue($name)
            ;

            if ($cookieChart !== null) {
                foreach ($cookieChart as $cn => $cv) {
                    if (str_starts_with($cn, 'chart_') === true) {
                        $parts = explode('-', substr($cn, 6));
                        $colors[reset($parts)][end($parts)] = $cv;
                    }
                }
            }

            $chart = array();

            $chartSettings = $reportSettings['chart'];

            foreach ($chartSettings as $section => $content) {
                $chart[$section]['config'] = isset($config["chart_{$section}"]) === true? $config["chart_{$section}"] : null;

                foreach ($content as $sec) {
                    $chart[$section]['content'][] = array(
                            'config' => $config["chart_{$section}_{$sec}"],
                            'sec'    => $sec,
                            'color'  => $colors[$section][$sec]
                    );
                }
            }

            $smarty->assign('chart', $chart);
        }
    }
}

?>
