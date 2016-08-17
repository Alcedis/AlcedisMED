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

require_once 'core/class/stageCalc/stageTemplates.php';

class stageCalc extends stageTemplates
{
    protected $_template = null;

    /**
     * saves last found stage dataset
     * @var unknown_type
     */
    protected $_cache = null;

    protected $_diaglok = null;

    protected $_db = null;

    protected $_stateUnique = array(
       'c' => '?*~',
       'p' => '!*~'
    );

    protected $_override = array();


    public function __construct($db, $sub = null)
    {
        $this->_db = $db;
        $this->_template = isset($this->_templates[$sub]) === true ? $this->_templates[$sub] : null;
        $this->_getDiagToLocalization();

        //label for ok
        $this->_override['ok'] = dlookup($db, 'l_basic', 'bez', "klasse = 'uicc' AND code = 'ok'");
    }

    public function setSub($sub)
    {
        $this->_template = isset($this->_templates[$sub]) === true ? $this->_templates[$sub] : null;

        return $this;
    }


    public static function create($db, $sub = null)
    {
        return new self($db, $sub);
    }


    public function calcFromMinDate($src, $date)
    {
        return $this->calc($src, $date, 'min');
    }


    /**
     * stage calc until maxDate
     *
     * @access
     * @param $src
     * @param $date
     * @return null
     */
    public function calcToMaxDate($src, $date)
    {
        return $this->calc($src, $date, 'max');
    }


    public function calc($src, $date = '9999-12-31', $calcType = 'max')
    {
        $val = null;

        $this->_cache = null;

        if (strlen($src) > 0) {
            $timeline = $this->_createTimeline($src, $date, $calcType);

            foreach ($timeline as $tl) {
                foreach ($tl as $sit) {
                    foreach ($sit as $ts) {
                        if (strlen($ts['result']) > 0) {
                            $this->_cache = $ts;
                            $val = $ts['result'];
                            break 3;
                        }
                    }
                }
            }
        }

        return $val;
    }


    /**
     * returns value of last dataset who had a valid stage value
     *
     * @param unknown_type $valuename
     */
    public function getCacheValue($valuename)
    {
        return ($this->_cache !== null && strlen($valuename) > 0 && isset($this->_cache["{$valuename}"]) === true ? $this->_cache["{$valuename}"] : null);
    }


    /**
     * _calcTNM
     *
     * @access  protected
     * @param   string $tnm
     * @param   string $localisation
     * @param   string $morphologie
     * @param   string  $diagnosis
     * @return  string
     */
    protected function _calcTNM($tnm, $localisation, $morphologie, $diagnosis)
    {
        $stage = null;

        if ($this->_template !== null) {
            $find    = array('[localisation]','[t]','[n]','[m]','[morphologie]', '[diagnosis]');
            $replace = array(
                "'{$localisation}'",
                "'{$tnm['t']}'",
                "'{$tnm['n']}'",
                "'{$tnm['m']}'",
                "'{$morphologie}'",
                "'{$diagnosis}'",
            );

            $query = 'SELECT ' . str_replace($find, $replace, $this->_template) . ' AS result';

            $stage = reset(reset(sql_query_array($this->_db, $query)));
        }

        return $stage;
    }


    /**
     *
     *
     * @access
     * @param null   $prefix
     * @param string $type
     * @param bool   $useSite
     * @param array  $additionalCondition
     * @return string
     */
    public function select($prefix = null, $type = 'uicc', $useSite = false, $additionalCondition = array())
    {
        $check = array(
                't' => ($prefix == 'c' ? "LEFT(ts.t, 1) = 'c'" : 'ts.t IS NOT NULL'),
                'n' => ($prefix == 'c' ? "LEFT(ts.n, 1) = 'c'" : 'ts.n IS NOT NULL'),
                'm' => ($prefix == 'c' ? "LEFT(ts.m, 1) = 'c'" : 'ts.m IS NOT NULL')
        );

        $where = array(
            'ts.erkrankung_id = t.erkrankung_id',
            'ts.anlass = t.anlass',
            "(ts.{$type} IS NOT NULL OR (" . implode(' AND ', $check) . '))'
        );

        if ($useSite !== false) {
            $where[] = "ts.diagnose_seite IN ('B', t.diagnose_seite)";
        }

        if ($prefix == 'c') {
            $where[] = "(ts.tnm_praefix NOT LIKE 'y%' OR ts.tnm_praefix IS NULL)";
        }

        $additionalCondition = is_array($additionalCondition) === false ? array($additionalCondition) : array();

        foreach ($additionalCondition as $condition) {
            $where[] = $condition;
        }

        $query = "
            (SELECT
                GROUP_CONCAT(DISTINCT
                    CONCAT_WS('{$this->_stateUnique['c']}',
                        IFNULL(ts.datum_sicherung, '-'),
                        IFNULL(ts.sicherungsgrad, 'vor'),
                        IFNULL(ts.datum_beurteilung, '-'),
                        IFNULL(ts.lokalisation, '-'),
                        IFNULL(ts.diagnose, '-'),
                        IFNULL(ts.morphologie, '-'),
                        IF(ts.tnm_praefix IS NOT NULL,
                            IF(LEFT(ts.tnm_praefix, 1) = 'y', 1, 0),
                            '-'
                        ),
                        IFNULL(ts.{$type}, '-'),
                        CONCAT_WS('~',
                            IFNULL(SUBSTRING(ts.t,3), '-'),
                            IFNULL(SUBSTRING(ts.n,3), '-'),
                            IFNULL(SUBSTRING(ts.m,3), '-')
                        )
                    )
                SEPARATOR '{$this->_stateUnique['p']}'
                )
            FROM tumorstatus ts
            WHERE " .
                implode(' AND ', $where) .
            " GROUP BY
                ts.erkrankung_id
            )
        ";

        return $query;
    }


    protected function _createTimeline($src, $date, $calcType)
    {
        $timeline = array();

        foreach (explode($this->_stateUnique['p'], $src) as $ts) {

            $ts  = explode($this->_stateUnique['c'], $ts);

            $tnm = explode('~', $ts[8]);

            //Date max or min calc
            if (($calcType == 'max' && $ts[0] >= $date) || ($calcType == 'min' && $ts[0] <= $date)) {
                continue;
            }

            $dataset = array(
                'lokalisation' => $ts[3],
                'diagnose' => $ts[4],
                'morphologie' => $ts[5],
                'tnm_praefix' => ($ts[6] !== '-' ? $ts[6] : null),
                'stage' => $ts[7],
                'tnm' => array(
                    't' => $tnm[0],
                    'n' => $tnm[1],
                    'm' => $tnm[2],
                ),
                'result' => ($ts[7] !== '-' ? (isset($this->_override["{$ts[7]}"]) === true ? $this->_override["{$ts[7]}"] : $ts[7] ): null)
            );

            if ($dataset['result'] === null) {
                if ($dataset['lokalisation'] == '-') {
                    $dataset['lokalisation'] = array_key_exists($dataset['diagnose'], $this->_diaglok) ? $this->_diaglok[$dataset['diagnose']] : null;
                }

                $dataset['result'] = $this->_calcTNM($dataset['tnm'], $dataset['lokalisation'], $dataset['morphologie'], $dataset['diagnose']);
            }

            $timeline["{$ts[0]}"]["{$ts[1]}"]["{$ts[2]}"] = $dataset;
        }

        //neustes Datum / datum sicherung
        krsort($timeline);

        foreach ($timeline as $i => $tl) {
            //endgueltig vor vorlaufig
            ksort($timeline[$i]);

            foreach ($tl as $k => $ts) {
                //neustes Datum / datum beurteilung
                krsort($timeline[$i][$k]);
            }
        }

        return $timeline;
    }


    protected function _getDiagToLocalization()
    {
       foreach (sql_query_array($this->_db, "SELECT diagnose_code, lokalisation_code FROM l_exp_diagnose_to_lokalisation") as $dataset) {
          $this->_diaglok["{$dataset['diagnose_code']}"] = $dataset['lokalisation_code'];
       }

       return $this;
    }
}

?>
