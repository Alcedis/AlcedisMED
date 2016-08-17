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

abstract class qs181MappingAbstract
{
    /*
     *
     */
    protected $_db = null;


    /*
     *
     */
    protected $_smarty = null;


    /**
     * @access
     * @var array
     */
    protected $_params = array();


    /**
     * @access
     * @var array
     */
    protected $_situation = array();


    /**
     * @access
     * @var array
     */
    protected $_fields = array(
        'b' => null,
        'brust' => null,
        'o' => null
    );


    /**
     * a base (b) datasets is minimum
     *
     * @access
     * @var array
     */
    protected $_forms = array(
        'b' => null,
        'brust' => array(
            'R' => null,
            'L' => null
        ),
        'o' => array(
            'R' => null,
            'L' => null
        )
    );


    /**
     * @access
     * @var array
     */
    protected $_separator = array(
        'opSection' => '##~~##',
        'opParts'   => '++~~++'
    );


    /**
     * @access
     * @var array
     */
    protected $_inclusionOpsCodes = array();


    /**
     * @param $smarty
     * @param $db
     * @param $params
     */
    public function __construct($smarty, $db, $params)
    {
        $this->_smarty  = $smarty;
        $this->_db      = $db;
        $this->_params  = $params;

        $this->_init();
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    public function allocate()
    {
        return $this
            ->_processBaseForm()
            ->_processBreastForm()
            ->_processOpForm()
            ->_writeRecords()
        ;
    }


    /**
     *
     *
     * @access
     * @param      $section
     * @param      $form
     * @param null $side
     * @return $this
     */
    protected function _addForm($section, $form, $side = null)
    {
        switch ($section) {
            case 'b':
                $this->_forms[$section] = $form;

                break;

            case 'brust':
                $this->_forms[$section][$side] = $form;

                break;

            case 'o':
                $this->_forms[$section][$side][] = $form;

                break;
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $filter
     * @return void
     */
    protected function findRecord($data = array(), $filter)
    {
        $return = null;

        if (is_array($data) === false) {
            $data = array();
        }

        if (is_array(reset($data)) === false) {
            $data = array($data);
        }

        if (check_array_content($data) === true) {
            $filter = str_replace('record', '$tmpRecord', $filter);

            foreach ($data as $record) {
                $tmpRecord = json_decode(json_encode($record), FALSE);
                $condition = false;

                eval('$condition = (' . $filter . ');');

                if ($condition === true) {
                    $return = $record;

                    break;
                }
            }
        }

        return $return;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _init()
    {
        $abodeId   = $this->getParam('abodeId');
        $diseaseId = $this->getParam('diseaseId');

        $abode = reset(sql_query_array($this->_db, "
            SELECT
                aufnahmedatum,
                entlassungsdatum,
                aufnahmenr,
                patient_id
            FROM aufenthalt
            WHERE
                aufenthalt_id = '{$abodeId}'
        "));

        $this
            ->setParam('abodeStart', $abode['aufnahmedatum'])
            ->setParam('abodeNr', $abode['aufnahmenr'])
            ->setParam('abodeEnd', $abode['entlassungsdatum'])
            ->setParam('patientId', $abode['patient_id'])
        ;

        $basicOrder     = 'ORDER BY ts.datum_sicherung ASC, ts.sicherungsgrad DESC, ts.datum_beurteilung ASC LIMIT 1';
        $basicCondition = "FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";

        $query = "
            SELECT
                t.anlass,
                t.diagnose_seite,

                IF(x.first_date=MIN(IF(t2.anlass = t.anlass, t2.datum_sicherung, null)), '0000-00-00', MIN(t.datum_sicherung))                                       AS 'start_date',
                DATE_SUB(IFNULL(MIN(IF(t2.anlass != t.anlass AND t2.datum_sicherung > t.datum_sicherung, t2.datum_sicherung,null) ), '9999-12-31'), INTERVAL 1 DAY)  AS 'end_date',
                MAX(t.datum_sicherung)                                                                                                                               AS 'datum_sicherung',
                MIN(t.datum_sicherung)                                                                                                                               AS 'start_date_rezidiv',


              (SELECT ts.diagnose     {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder)          AS 'diagnose',
              (SELECT ts.diagnose_text     {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder)     AS 'diagnose_text',
              (SELECT ts.diagnose_version     {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder)  AS 'diagnose_version'

            FROM patient p
                INNER JOIN erkrankung e     ON e.patient_id = p.patient_id AND e.erkrankung_id = '{$diseaseId}'
                INNER JOIN tumorstatus t    ON t.erkrankung_id = e.erkrankung_id
                LEFT JOIN tumorstatus t2    ON t2.erkrankung_id = e.erkrankung_id

                INNER JOIN (
                    SELECT
                        erkrankung_id,
                        MIN(datum_sicherung) AS first_date
                    FROM tumorstatus
                    WHERE erkrankung_id = '{$diseaseId}'
                    GROUP BY
                        erkrankung_id
                ) x                          ON x.erkrankung_id = e.erkrankung_id
            WHERE p.patient_id
            GROUP BY
                p.patient_id,
                e.erkrankung_id,
                t.anlass,
                t.diagnose_seite
            HAVING diagnose_seite IN ('R', 'L')
            ORDER BY
                datum_sicherung ASC
        ";

        $situation = array(
            'raw' => array(),
            'R' => array(),
            'L' => array()
        );

        foreach (sql_query_array($this->_db, $query) as $record) {
            $situation['raw'][] = $record;

            $side = $record['diagnose_seite'];
            $case = $record['anlass'];
            $date = $record['datum_sicherung'];

            $situation[$side][$case][$date] = $record;
        }

        $this->_situation = $situation;

        foreach ($this->_fields as $fieldsName => $dummy) {
            $fields = null;

            require "fields/app/qs_18_1_{$fieldsName}.php";

            $this->_fields[$fieldsName] = $fields;
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _writeRecords()
    {
        require_once 'core/class/qs181/validationParser.php';

        $this
            ->_writeRecordBase()
            ->_writeRecordBreast()
            ->_writeRecordOp()
        ;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return this
     */
    protected function _writeRecordBase()
    {
        //write base record
        if ($this->_forms['b'] !== null) {
            $baseFields = $this->_fields['b'];

            array2fields($this->_forms['b'], $baseFields);

            $baseValidator = new validator($this->_smarty, $this->_db, $baseFields);

            $baseValidator->validate_fields($baseFields);

            if ($baseValidator->getValidationFields('err') !== null) {
                $baseValidator->set_msg('warn', 12, 'qs_18_1_b_id', 'req');
            }

            $baseErrors = qs181ValidationParser::create($this->_db)
                ->setLayer('b')
                ->setFields(array('b' => fields2dataArray($baseFields)))
                ->parse()
                ->getErrors()
            ;

            if ($baseErrors !== false) {
                foreach ($baseErrors as $error) {
                    foreach ($error['fields'] as $errField) {
                        $baseValidator->set_msg('warn', 12, $errField, $error['msg']);
                    }
                }
            }

            $abodeId   = $this->getParam('abodeId');
            $diseaseId = $this->getParam('diseaseId');

            execute_insert($this->_smarty, $this->_db, $baseFields, 'qs_18_1_b' ,'insert', false, null, $baseValidator);

            $this->setParam(
                'qs181bid',
                dlookup($this->_db, 'qs_18_1_b', 'qs_18_1_b_id', "erkrankung_id = '{$diseaseId}' AND aufenthalt_id = '{$abodeId}'")
            );
        }

        return $this;
    }


    /**
     *
     * @access
     * @return $this
     */
    protected function _writeRecordBreast()
    {
        $qs181bId = $this->getParam('qs181bid');

        foreach ($this->_forms['brust'] as $side => $form) {
            if ($form !== null && $qs181bId !== null) {
                $form['qs_18_1_b_id'] = $qs181bId;

                $baseFields   = $this->_fields['b'];
                $breastFields = $this->_fields['brust'];

                array2fields($this->_forms['b'], $baseFields);
                array2fields($form, $breastFields);

                $breastValidator = new validator($this->_smarty, $this->_db, $breastFields);

                $breastValidator->validate_fields($breastFields);

                if ($breastValidator->getValidationFields('err') !== null) {
                    $breastValidator->set_msg('warn', 12, 'qs_18_1_brust_id', 'req');
                }

                $breastErrors = qs181ValidationParser::create($this->_db)
                    ->setLayer('brust')
                    ->setFields(array(
                        'b'     => fields2dataArray($baseFields),
                        'brust' => fields2dataArray($breastFields)
                    ))
                    ->parse()
                    ->getErrors()
                ;

                if ($breastErrors !== false) {
                    foreach ($breastErrors as $error) {
                        foreach ($error['fields'] as $errField) {
                            $breastValidator->set_msg('warn', 12, $errField, $error['msg']);
                        }
                    }
                }

                execute_insert($this->_smarty, $this->_db, $breastFields, 'qs_18_1_brust', 'insert', false, null, $breastValidator);

                $this->setParam(
                    "qs181brustid{$side}",
                    dlookup($this->_db, 'qs_18_1_brust', 'qs_18_1_brust_id', "qs_18_1_b_id  = '{$qs181bId}' AND zuopseite = '{$side}'")
                );
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _writeRecordOp()
    {
        foreach ($this->_forms['o'] as $side => $forms) {
            $qs181bId = $this->getParam('qs181bid');
            $qs181breastId = $this->getParam("qs181brustid{$side}");

            if ($qs181bId !== null && $qs181breastId !== null && $forms !== null) {
                foreach ($forms as $form) {
                    $form['qs_18_1_b_id']     = $qs181bId;
                    $form['qs_18_1_brust_id'] = $qs181breastId;

                    $baseFields   = $this->_fields['b'];
                    $breastFields = $this->_fields['brust'];
                    $opFields     = $this->_fields['o'];

                    array2fields($this->_forms['b'], $baseFields);
                    array2fields($this->_forms['brust'][$side], $breastFields);
                    array2fields($form, $opFields);

                    $opValidator = new validator($this->_smarty, $this->_db, $opFields);

                    $opValidator->validate_fields($opFields);

                    if ($opValidator->getValidationFields('err') !== null) {
                        $opValidator->set_msg('warn', 12, 'qs_18_1_o_id', 'req');
                    }

                    $opErrors = qs181ValidationParser::create($this->_db)
                        ->setLayer('o')
                        ->setFields(array(
                             'b'     => fields2dataArray($baseFields),
                             'brust' => fields2dataArray($breastFields),
                             'o'     => fields2dataArray($opFields)
                        ))
                        ->parse()
                        ->getErrors()
                    ;

                    if ($opErrors !== false) {
                        foreach ($opErrors as $error) {
                            foreach ($error['fields'] as $errField) {
                                $opValidator->set_msg('warn', 12, $errField, $error['msg']);
                            }
                        }
                    }

                    execute_insert($this->_smarty, $this->_db, $opFields, 'qs_18_1_o' ,'insert', false, null, $opValidator);
                }
            }
        }

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
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @param        $side
     * @return array|bool|null
     */
    protected function _loadTumorstate($start = '1900-01-01', $end = '2050-12-31', $side = null)
    {
        $diseaseId = $this->getParam('diseaseId');

        $side = $side !== null ?  "AND diagnose_seite = '{$side}'" : null;

        $query = "
            SELECT
                *
            FROM tumorstatus
            WHERE
                erkrankung_id = '{$diseaseId}' AND
                datum_sicherung BETWEEN '{$start}' AND '{$end}' {$side}
            ORDER BY
                datum_sicherung ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @return array|bool|null
     */
    protected function _loadDiagnose($start = '1900-01-01', $end = '2050-12-31')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                *
            FROM diagnose
            WHERE
                erkrankung_id = '{$diseaseId}' AND
                datum BETWEEN '{$start}' AND '{$end}'
            ORDER BY
                datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @return array|bool|null
     */
    protected function _loadConference($start = '1900-01-01', $end = '2050-12-31')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                kp.art,
                kp.art as 'sit',
                IF(kp.art = 'prae', 1, 0) AS 'praethinterdisztherapieplan',
                k.datum
            FROM konferenz_patient kp
                INNER JOIN konferenz k ON k.konferenz_id = kp.konferenz_id AND
                                          k.datum BETWEEN '{$start}' AND '{$end}'
            WHERE
                kp.erkrankung_id = '{$diseaseId}'
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @param        $side
     * @return array|bool|null
     */
    protected function _loadTherapy($start = '1900-01-01', $end = '2050-12-31', $side = null)
    {
        $diseaseId = $this->getParam('diseaseId');

        $eSide   = $side !== null ? "AND e.diagnose_seite IN ('B', '{$side}')" : null;
        $opsSide = $side !== null ? "AND ops.prozedur_seite IN ('B', '{$side}')" : null;

        $query = "
            SELECT
                e.*,
                GROUP_CONCAT(DISTINCT CONCAT_WS('{$this->_separator['opParts']}',
                    ops.prozedur,
                    ops.prozedur_seite,
                    IFNULL(ops.prozedur_version, '--'),
                    IFNULL(ops.prozedur_text, '--')
                    )
                    SEPARATOR '{$this->_separator['opSection']}'
                ) AS 'ops',
                COUNT(DISTINCT IF(";

        $queryParts = array();

        foreach ($this->_inclusionOpsCodes as $opsCode) {
            $queryParts[] = "LOCATE('{$opsCode}', ops.prozedur) != 0";
        }

        $query .= implode(' OR ', $queryParts);

        $query .= ", ops.eingriff_id, NULL
                    )) > 0 AS 'inclusionCriteria'
            FROM eingriff e
                LEFT JOIN eingriff_ops ops ON ops.eingriff_id = e.eingriff_id {$opsSide}
            WHERE
                e.erkrankung_id = '{$diseaseId}' AND
                e.datum BETWEEN '{$start}' AND '{$end}' {$eSide}
            GROUP BY
                e.eingriff_id
            ORDER BY
                e.datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }



    /**
     *
     *
     * @access
     * @param        $side
     * @param string $start
     * @param string $end
     * @return array|bool|null
     */
    protected function _loadHistology($start = '1900-01-01', $end = '2050-12-31', $side = 'X')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                h.datum,
                h.art,
                h.diagnose_seite,
                h.morphologie,

                IF(
                    COUNT(DISTINCT IF(
                        LOCATE('1-e03.', ops.prozedur) != 0 OR
                        LOCATE('1-e02.', ops.prozedur) != 0 OR
                        LOCATE('1-494.31', ops.prozedur) != 0 OR
                        LOCATE('1-494.32', ops.prozedur) != 0 OR
                        LOCATE('1-493.31', ops.prozedur) != 0 OR
                        LOCATE('1-493.32', ops.prozedur) != 0,
                        ops.eingriff_id,
                        NULL
                    )) OR
                    COUNT(DISTINCT IF(
                        LOCATE('1-e03.', u.art) != 0 OR
                        LOCATE('1-e02.', u.art) != 0 OR
                        LOCATE('1-494.31', u.art) != 0 OR
                        LOCATE('1-494.32', u.art) != 0 OR
                        LOCATE('1-493.31', u.art) != 0 OR
                        LOCATE('1-493.32', u.art) != 0,
                        u.untersuchung_id,
                        NULL
                    )),
                   1,
                   NULL
                ) AS 'diagnosesicherung'
            FROM histologie h
                LEFT JOIN eingriff e ON e.eingriff_id = h.eingriff_id AND
                                       e.diagnose_seite IN ('B', '{$side}')

                    LEFT JOIN eingriff_ops ops  ON ops.eingriff_id = e.eingriff_id AND
                                                   ops.prozedur_seite IN ('B', '{$side}')


                LEFT JOIN untersuchung u    ON u.untersuchung_id = h.untersuchung_id AND
                                               u.art_seite IN ('B', '{$side}')

            WHERE
                h.diagnose_seite IN ('B', '{$side}') AND
                h.erkrankung_id = '{$diseaseId}' AND
                h.datum BETWEEN '{$start}' AND '{$end}'
            GROUP BY
                h.histologie_id
            ORDER BY
                h.datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @return array|bool|null
     */
    protected function _loadTherapyRegime($start = '1900-01-01', $end = '2050-12-31')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                *,
                zeitpunkt as 'sit',
                IF(zeitpunkt = 'prae' AND grundlage = 'tk', 1, 0) AS 'praethinterdisztherapieplan'
            FROM therapieplan
            WHERE
                erkrankung_id = '{$diseaseId}' AND
                datum BETWEEN '{$start}' AND '{$end}'
            ORDER BY
                datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @return array|bool|null
     */
    protected function _loadAnamnesis($start = '1900-01-01', $end = '2050-12-31')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                *
            FROM anamnese
            WHERE
                erkrankung_id = '{$diseaseId}' AND
                datum BETWEEN '{$start}' AND '{$end}'
            ORDER BY
                datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @param        $side
     * @return array|bool|null
     */
    protected function _loadComplications($start = '1900-01-01', $end = '2050-12-31', $side)
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                k.*,
                IF(
                    k.antibiotikum = '1' OR
                    k.sekundaerheilung = '1' OR
                    k.revisionsoperation = '1' OR
                    k.transfusion = '1' OR
                    k.gerinnungshemmer = '1' OR
                    k.beatmung = '1' OR
                    k.intensivstation = '1',
                    1,
                    null
                ) as 'pokomplikatspez'

            FROM komplikation k
                INNER JOIN eingriff e ON e.eingriff_id = k.eingriff_id AND
                                         e.datum BETWEEN '{$start}' AND '{$end}' AND
                                         e.diagnose_seite IN ('B', '{$side}')
            WHERE
                k.erkrankung_id = '{$diseaseId}'
            ORDER BY
                k.datum ASC
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @param string $start
     * @param string $end
     * @return array|null
     */
    protected function _loadNonInterventionalTherapy($start = '1900-01-01', $end = '2050-12-31')
    {
        $diseaseId = $this->getParam('diseaseId');

        $query1 = "
            SELECT
                'therapie_systemisch'  as 'type',
                ts.vorlage_therapie_id,
                ts.vorlage_therapie_art,
                ts.ende,
                ts.endstatus,
                ts.intention
            FROM therapie_systemisch ts
            WHERE
                ts.erkrankung_id = '{$diseaseId}' AND
                ts.beginn BETWEEN '{$start}' AND '{$end}'
        ";

        $query2 = "
            SELECT

                'strahlentherapie'  as 'type',
                ts.vorlage_therapie_id,
                ts.vorlage_therapie_art,
                ts.ende,
                ts.endstatus,
                ts.intention
            FROM strahlentherapie ts
            WHERE
                ts.erkrankung_id = '{$diseaseId}' AND
                ts.beginn BETWEEN '{$start}' AND '{$end}'
        ";

        $query3 = "
            SELECT
                'sonstige_therapie'  as 'type',
                null        as 'vorlage_therapie_art',
                ts.ende,
                ts.endstatus,
                ts.intention
            FROM sonstige_therapie ts
            WHERE
                ts.erkrankung_id = '{$diseaseId}' AND
                ts.beginn BETWEEN '{$start}' AND '{$end}'
        ";

        $data = array_merge(
            sql_query_array($this->_db, $query1),
            sql_query_array($this->_db, $query2),
            sql_query_array($this->_db, $query3)
        );

        return (count($data) ? $data : null);
    }


    /**
     *
     *
     * @access
     * @return array|null
     */
    protected function _loadConclusion()
    {
        $patientId = $this->getParam('patientId');

        $query = "
            SELECT
                *
            FROM abschluss
            WHERE
                patient_id = '{$patientId}'
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? reset($data) : null);
    }


    /**
     *
     *
     * @access
     * @return array|null
     */
    protected function _loadEkr()
    {
        $diseaseId = $this->getParam('diseaseId');

        $query = "
            SELECT
                *
            FROM ekr
            WHERE
                erkrankung_id = '{$diseaseId}'
        ";

        $data = sql_query_array($this->_db, $query);

        return (count($data) ? $data : null);
    }
}

?>
