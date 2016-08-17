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

require_once('feature/export/helper.dmp.php');

abstract class dmp2013PreallocateAbstract
{
    /**
     * @access
     * @var ressource
     */
    protected $_db = null;


    /**
     * @access
     * @var array
     */
    protected $_fields = array();


    /**
     * @access
     * @var array
     */
    private $_ignoredFields = array(
        'dmp_brustkrebs_ed_2013_id',
        'patient_id',
        'erkrankung_id',
        'org_id',
        'createuser',
        'createtime',
        'updateuser',
        'updatetime',
        'bem'
    );


    /**
     * @access
     * @var array
     */
    protected $_filter = array(
        'start' => '1900-01-01',
        'end'   => '2050-12-31',
        'diseaseId' => null
    );


    /**
     * @access
     * @var array
     */
    protected $_params = array();


    /**
     * @access
     * @var array
     */
    protected $_timeline = array();


    /**
     * @access
     * @var array
     */
    protected $_exceptions = array();


    /**
     * indicator of processed tumorstate
     *
     * @access
     * @var bool
     */
    protected  $_tumorstateProcessed = false;


    /**
     * @param $db
     * @param $params
     */
    public function __construct($db, $params)
    {
        $this->_db = $db;
        $this->_params = $params;
    }


    /**
     *
     *
     * @access
     * @param      $field
     * @param null $prefix
     * @param bool $noStartDate
     * @return string
     */
    protected function _getDateFilter($field, $prefix = null, $noStartDate = false)
    {
        $start      = $this->_filter['start'];
        $end        = $this->_filter['end'];
        $diseaseId  = $this->_filter['diseaseId'];

        $prefix     = $prefix !== null ? "{$prefix}." : null;

        $startDate = $noStartDate === false ? "{$prefix}{$field} > '{$start}' AND" : null;

        return "{$prefix}erkrankung_id = '{$diseaseId}' AND {$startDate} {$prefix}{$field} <= '{$end}'";
    }


    /**
     *
     *
     * @access
     * @param $name
     * @param $value
     * @return $this
     */
    public function setFilter($name, $value)
    {
        $this->_filter[$name] = $value;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @return mixed
     */
    public function getFilter($name)
    {
        return $this->_filter[$name];
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    public function preallocate()
    {
        $this
            ->_load()
            ->_process()
        ;

        if ($this->_getFormId() === null) {
            $this->_caseNumber();

            $this->setField('melde_user_id',
                HelperDmp::getDefaultDetectorId($this->_db, $this->getParam('org_id'), $this->getParam('patient_id'), null)
            );
        } else {
            $this
                ->ignoreField('melde_user_id')
                ->ignoreField('doku_datum')
                ->ignoreField('fall_nr')
            ;
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return mixed
     */
    abstract protected function _load();


    /**
     * convert data
     *
     * @access
     * @return mixed
     */
    abstract protected function _process();


    /**
     *
     * for pnp
     * @access
     * @return $this
     */
    protected  function _caseNumber()
    {
        $edId = $this->getParam('dmp_brustkrebs_ed_2013_id');

        $caseNumber = dlookup($this->_db, 'dmp_brustkrebs_ed_2013', 'fall_nr', "dmp_brustkrebs_ed_2013_id = '{$edId}'");

        $this->setField('fall_nr', $caseNumber);

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $ident
     * @param $date
     * @param $data
     * @return $this
     */
    protected function addTimelineEntry($ident, $date, $data)
    {
        $this->_timeline[$ident][$date] = $data;

        // sort timeline for later stepping through values
        krsort($this->_timeline[$ident]);

        return $this;
    }


    /**
     * get first found field from DESC sorted timeline
     *
     * if spinTimeline = true, timeline will be temporarily spined and first entry will be returned
     *
     * @access
     * @param      $ident
     * @param      $name
     * @param bool $spinTimeline
     * @return null
     */
    protected function getTimelineField($ident, $name, $spinTimeline = false)
    {
        $value = null;

        if (array_key_exists($ident, $this->_timeline) === true) {
            if ($spinTimeline === true) {
                ksort($this->_timeline[$ident]);
            }

            foreach ($this->_timeline[$ident] as $entry) {
                if (array_key_exists($name, $entry) === true && strlen($entry[$name]) > 0) {
                    $value = $entry[$name];

                    break;
                }
            }

            if ($spinTimeline === true) {
                krsort($this->_timeline[$ident]);
            }
        }

        return $value;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @param $value
     * @return $this
     */
    public function setField($name, $value)
    {
        $this->_fields[$name] = $value;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @return $this|null
     */
    public function getField($name)
    {
        return (array_key_exists($name, $this->_fields) === true ? $this->_fields[$name] : null);

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    public function getIgnoredFields()
    {
        return $this->_ignoredFields;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @return $this
     */
    public function ignoreField($name)
    {
        $this->_ignoredFields[] = $name;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @return null
     */
    public function getParam($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }


    /**
     *
     *
     * @access
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;

        return $this;
    }


    /**
     * getFormId
     *
     * @access
     * @return mixed
     */
    abstract protected function _getFormId();


    /**
     *
     *
     * @access
     * @return array|bool|null|string
     */
    public function getDocumentationDate()
    {
        $date = $this->getParam('doku_datum');

        if ($date !== null && strlen($date) > 0) {
            $date = todate($date, 'en');
        } else {
            $date = date('Y-m-d');
        }

        return $date;
    }


    /**
     *
     *
     * @access
     * @param $v
     * @return $this
     */
    protected function _addException($v)
    {
        $this->_exceptions[$v] = 1;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    public function getExceptions()
    {
        return array_keys($this->_exceptions);
    }


    /**
     * _prepareTreatmentRegime
     *
     * @access  protected
     * @param   bool    $checkIntention
     * @return  $this
     */
    protected function _prepareTreatmentRegime($checkIntention = false)
    {
        $where = $this->_getDateFilter('datum', 'th');

        $query = "
            SELECT
                th.datum,
                th.strahlen,
                th.chemo,
                th.ah                                        AS 'endo',
                COUNT(vtw.vorlage_therapie_wirkstoff_id) > 0 AS 'ah',
                th.strahlen_intention,
                th.chemo_intention,
                th.ah_intention                              AS 'endo_intention',
                th.immun_intention                           as 'ah_intention'
            FROM therapieplan th
              LEFT JOIN vorlage_therapie_wirkstoff vtw ON th.immun = '1' AND
                                                          th.immun_id = vtw.vorlage_therapie_id AND
                                                          vtw.wirkstoff = 'trastuzumab'
            WHERE
                {$where}
            GROUP BY
                th.therapieplan_id
            ORDER BY
                th.datum DESC
            LIMIT 1
        ";

        $forms = reset(sql_query_array($this->_db, $query));

        $sections = array('strahlen' => 'strahlen_intention',
                          'chemo' => 'chemo_intention',
                          'endo' => 'endo_intention',
                          'ah' => 'ah_intention');

        foreach ($sections as $sectionName => $intentionField) {
            if ($checkIntention == true) {
                $param = ($forms[$sectionName] === '1' && $forms[$intentionField]  === 'kura' ? 'g' : false);
            } else {
                $param = $forms[$sectionName] === '1' ? 'g': false;
            }

            if ($param !== false) {
                $this->setParam($sectionName, $param);
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _loadDiagnose()
    {
        $data = array(
            'diagnose' => dlookup($this->_db, 'diagnose', 'GROUP_CONCAT(DISTINCT diagnose)', $this->_getDateFilter('datum'))
        );

        return $data;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _loadOtherTherapy()
    {
        $where = $this->_getDateFilter('beginn');

        $data = array(
            'intention' => dlookup($this->_db, 'sonstige_therapie', 'GROUP_CONCAT(DISTINCT intention)', $where)
        );

        return $data;
    }


    /**
     *
     *
     * @access
     * @param null $whereAddition
     * @return array|bool
     */
    protected function _loadTumorstate($whereAddition = null)
    {
        $where = $this->_getDateFilter('datum_sicherung', 't');

        $query = "
            SELECT
                t.*,
                GROUP_CONCAT(DISTINCT tm.lokalisation SEPARATOR '#+#') AS 'metastasis'
            FROM tumorstatus t
                LEFT JOIN tumorstatus_metastasen tm ON tm.tumorstatus_id = t.tumorstatus_id
            WHERE
                {$where}{$whereAddition}
            GROUP BY
                t.tumorstatus_id
            ORDER BY
                t.datum_sicherung DESC
        ";

        return sql_query_array($this->_db, $query);
    }


    /**
     *
     *
     * @access
     * @param bool $noStartDate
     * @param null $where
     * @return array|bool
     */
    protected function _loadNonInterventionalTherapy($noStartDate = false, $where = null)
    {
        if ($where === null) {
            $where = $this->_getDateFilter('beginn', 'ts', $noStartDate);
        }

        $query1 = "
            SELECT
                ts.vorlage_therapie_id,
                ts.vorlage_therapie_art,
                ts.ende,
                ts.endstatus,
                GROUP_CONCAT(DISTINCT
                    IF (vtw.vorlage_therapie_wirkstoff_id IS NOT NULL,
                       vtw.wirkstoff,
                       NULL
                    ) SEPARATOR ','
                ) AS 'agent',
                ts.intention
            FROM therapie_systemisch ts
                LEFT JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = ts.vorlage_therapie_id
            WHERE
                {$where}
            GROUP BY
                ts.therapie_systemisch_id
        ";

        $query2 = "
            SELECT
                ts.vorlage_therapie_id,
                ts.vorlage_therapie_art,
                ts.ende,
                ts.endstatus,
                GROUP_CONCAT(DISTINCT
                    IF (vtw.vorlage_therapie_wirkstoff_id IS NOT NULL,
                       vtw.wirkstoff,
                       NULL
                    ) SEPARATOR ','
                ) AS 'agent',
                ts.intention
            FROM strahlentherapie ts
                LEFT JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = ts.vorlage_therapie_id
            WHERE
                {$where}
            GROUP BY
                ts.strahlentherapie_id
        ";

        return array_merge(
            sql_query_array($this->_db, $query1),
            sql_query_array($this->_db, $query2)
        );
    }


    /**
     *
     *
     * @access
     * @return array|bool
     */
    protected function _loadHistology()
    {
        $where = $this->_getDateFilter('datum');

        $data = sql_query_array($this->_db, "
            SELECT
                *
            FROM histologie
            WHERE
                {$where}
        ");

        return $data;
    }


    /**
     *
     *
     * @access
     * @param string $prefix
     * @param bool   $noStartDate
     * @return array|bool
     */
    protected function _processNonInterventionalTherapy($prefix = 'beh', $noStartDate = false)
    {
        $process = array(
            'chemo'     => array('endStatus' => '', 'noEnd' => false, 'count' => 0),
            'endo'      => array('endStatus' => '', 'noEnd' => false, 'count' => 0),
            'ah'        => array('endStatus' => '', 'noEnd' => false, 'count' => 0),
            'strahlen'  => array('endStatus' => '', 'noEnd' => false, 'count' => 0),
        );

        $data = $this->_loadNonInterventionalTherapy($noStartDate);

        foreach ($data as $form) {
            // Strahlen
            if (str_contains($form['vorlage_therapie_art'], 'st') === true) {
                $process['strahlen']['endStatus'] .= $form['endstatus'];
                $process['strahlen']['count']++;

                if (strlen($form['endstatus']) == 0 && strlen($form['ende']) == 0) {
                    $process['strahlen']['noEnd'] = true;
                }
            }

            // Chemo
            if (str_contains($form['vorlage_therapie_art'], 'c') === true) {
                $process['chemo']['endStatus'] .= $form['endstatus'];
                $process['chemo']['count']++;

                if (strlen($form['endstatus']) == 0 && strlen($form['ende']) == 0) {
                    $process['chemo']['noEnd'] = true;
                }
            }

            // Endo
            if (str_contains($form['vorlage_therapie_art'], 'ah') === true) {
                $process['endo']['endStatus'] .= $form['endstatus'];
                $process['endo']['count']++;

                if (strlen($form['endstatus']) == 0 && strlen($form['ende']) == 0) {
                    $process['endo']['noEnd'] = true;
                }
            }

            // Ah
            if (str_contains($form['vorlage_therapie_art'], 'i') === true &&
                str_contains($form['agent'], 'trastuzumab') === true
            ) {
                $process['ah']['endStatus'] .= $form['endstatus'];
                $process['ah']['count']++;

                if (strlen($form['endstatus']) == 0 && strlen($form['ende']) == 0) {
                    $process['ah']['noEnd'] = true;
                }
            }
        }

        foreach ($process as $name => $content) {
            $param = $this->getParam($name);

            $val = strlen($param) > 0 ? $param : 'k';

            if ($content['noEnd'] === true) {
                $val = 'a';
            } elseif (str_contains($content['endStatus'], 'plan') === true) {
                $val = 'ra';
            } elseif (str_contains($content['endStatus'], 'abbr') === true) {
                $val = 'vb';
            }

            $this->setField("{$prefix}_{$name}", $val);
        }

        return $data;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _processDiagnose()
    {
        $data = $this->_loadDiagnose();

        if (str_contains($data['diagnose'], array('I89.0', 'I97.2', 'Q82.0')) === true) {
            $this->setField('lymphoedem', true);
        }

        return $this;
    }
}
