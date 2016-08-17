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

require_once('interface.exportwrapper.php');

require_once( 'core/class/report/helper.reports.php' );
require_once( 'reports/scripts/reportPreSelects.php' );
require_once( 'reports/scripts/reportMath.php' );
require_once( 'reports/scripts/oz/reportExtension.php' );


/**
 * Class CExportWrapper
 */
class CExportWrapper extends reportExtensionOz implements IExportWrapper
{
    protected $m_ignore_patient_ids = array();
    protected $m_absolute_path = '';
    protected $m_export_name = '';
    protected $m_diagnosen = '';
    protected $m_morphologien = '';
    protected $m_erkrankungen = array(  'b',
                                        'd',
                                        'gt',
                                        'h',
                                        'kh',
                                        'leu',
                                        'lg',
                                        'lu',
                                        'ly',
                                        'm',
                                        'nt',
                                        'oes',
                                        'p',
                                        'pa',
                                        'snst',
                                        'sst' );
    protected $m_alle_erkrankugen = true;
    protected $m_use_ekr_meldung_check = true;
    protected $m_additional_joins = '';
    protected $m_additional_selects = array();
    protected $m_additional_fields = '';
    protected $m_use_primary_cases_only = false;
    protected $m_von_date = '0000-00-00';
    protected $m_bis_date = '2999-12-31';
    protected $_relevantFieldForTimeRangeCheck = 'bezugsdatum';


    /**
     * having conditions
     *
     * @access  protected
     * @var     array
     */
    protected $_having = array();


    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected $_cache = array(
        'opscodes' => null
    );


    /**
     * constructor
     *
     * @param string    $absolute_path
     * @param string    $export_name
     * @param Smarty    $smarty
     * @param resource  $db
     */
    public function __construct($absolute_path, $export_name, $smarty, $db)
    {
        parent::__construct(null, $db, $smarty, '', '', array());

        $this->m_absolute_path = $absolute_path;
        $this->m_export_name = $export_name;
    }


    /**
     * ignorePatientIds
     *
     * @access  public
     * @param   array   $patientIds
     * @return  CExportWrapper
     */
    public function ignorePatientIds($patientIds)
    {
        $this->m_ignore_patient_ids = array();

        if (is_array($patientIds) === true) {
            $this->m_ignore_patient_ids = $patientIds;
        }

        return $this;
    }


    /**
     * SetRelevantFieldForTimeRangeCheck
     *
     * @access  public
     * @param   string  $field
     * @return  void
     */
    public function SetRelevantFieldForTimeRangeCheck($field)
    {
        $this->_relevantFieldForTimeRangeCheck = $field;
    }


    /**
     * must be overwritten due language convertion problems
     *
     * @access
     * @return string
     */
    protected function _getVonDate()
    {
        return $this->_params['datum_von'];
    }


    /**
     * must be overwritten due language convertion problems
     *
     * @access
     * @return string
     */
    protected function _getBisDate()
    {
        return $this->_params['datum_bis'];
    }


    /**
     * UsePrimaryCasesOnly
     *
     * @access  public
     * @return  CExportWrapper
     */
    public function usePrimaryCasesOnly()
    {
        $this->m_use_primary_cases_only = true;

        return $this;
    }


    /**
     * DoNotUseEkrMeldungsCheck
     *
     * @access  public
     * @return  CExportWrapper
     */
    public function doNotUseEkrMeldungsCheck()
    {
        $this->m_use_ekr_meldung_check = false;

        return $this;
    }


    /**
     * setRangeDate
     *
     * @access  public
     * @param   string  $von_date
     * @param   string  $bis_date
     * @return  CExportWrapper
     */
    public function setRangeDate($von_date, $bis_date)
    {
        $von_time = strtotime($von_date);

        if (false === $von_time) {
            $this->m_von_date = "1970-01-01";
        } else {
            $this->m_von_date = date("Y-m-d", $von_time);
        }

        $bis_time = strtotime($bis_date);

        if (false === $bis_time) {
            $this->m_bis_date = "2038-01-01";
        } else {
            $this->m_bis_date = date("Y-m-d", $bis_time);
        }

        return $this;
    }


    /**
     * SetParameters
     *
     * @access  public
     * @param   array   $parameters
     * @return  CExportWrapper
     */
    public function setParameters($parameters)
    {
        $this->_params = $parameters;

        return $this;
    }


    /**
     * getParameter
     *
     * @access  public
     * @param   string  $name
     * @return  string
     */
    public function getParameter($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }


    /**
     * SetErkrankungen
     * deprecated!!!! and not realy functional....
     *
     * bugfix for 4.0.33 BUT SHOULD NOT BE USED ANYMORE
     *
     * @access  public
     * @param   string $erkrankung
     * @return  this
     */
    public function SetErkrankungen($erkrankung = 'all')
    {
        if ($erkrankung !== 'all') {
            $this->m_erkrankungen = array(trim($erkrankung));
            $this->m_alle_erkrankugen = false;
        }

        return $this;
    }


    /**
     * setDiseaseFilter
     *
     * @access  public
     * @param   string  $disease
     * @return  CExportWrapper
     */
    public function setDiseaseFilter($disease)
    {
        $this->m_erkrankungen = array($disease);
        $this->m_alle_erkrankugen = false;

        return $this;
    }


    /**
     * SetDiagnosen
     *
     * @access  public
     * @param   string  $diagnosen
     * @return  CExportWrapper
     */
    public function SetDiagnosen($diagnosen)
    {
        $this->m_diagnosen = $diagnosen;

        return $this;
    }


    /**
     * SetMorphologien
     *
     * @access  public
     * @param   string  $morphologien
     * @return  CExportWrapper
     */
    public function SetMorphologien($morphologien)
    {
        $this->m_morphologien = $morphologien;

        return $this;
    }


    /**
     * setAdditionalJoins
     *
     * @access  public
     * @param   array   $additionalJoins
     * @return  CExportWrapper
     */
    public function setAdditionalJoins( $additionalJoins )
    {
        $this->m_additional_joins = '';

        if (is_array($additionalJoins) === true) {
            foreach ($additionalJoins as $statement) {
                if (strlen($this->m_additional_joins) == 0) {
                    $this->m_additional_joins .= $statement;
                } else {
                    $this->m_additional_joins .= " " . $statement;
                }
            }
        }

        return $this;
    }


    /**
     * setAdditionalSelects
     *
     * @access  public
     * @param   array   $additionalSelects
     * @return  CExportWrapper
     */
    public function setAdditionalSelects($additionalSelects)
    {
        $this->m_additional_selects = array();

        if (is_array($additionalSelects) === true) {
            $this->m_additional_selects = $additionalSelects;
        }

        return $this;
    }


    /**
     * setAdditionalFields
     *
     * @access  public
     * @param   array   $additionalFields
     * @return  CExportWrapper
     */
    public function setAdditionalFields($additionalFields)
    {
        $this->m_additional_fields = '';

        if (is_array($additionalFields) === true) {
            foreach ($additionalFields as $select) {
                $this->m_additional_fields .= $select . ", ";
            }
        }

        return $this;
    }


    /**
     * add fields to query
     *
     * @access  public
     * @param   string|array    $fields
     * @return  CExportWrapper
     */
    public function addAdditionalFields($fields)
    {
        $fields = is_array($fields) === false ? array($fields) : $fields;

        foreach ($fields as $field) {
            $this->m_additional_fields .= $field . ', ';
        }

        return $this;
    }


    /**
     * add a single field to query
     *
     * @access  public
     * @param   string  $field
     * @return  CExportWrapper
     */
    public function addAdditionalField($field)
    {
        $this->m_additional_fields .= $field . ', ';

        return $this;
    }


    /**
     * addAdditionalJoin
     *
     * @access  public
     * @param   string|array    $joins
     * @return  CExportWrapper
     */
    public function addAdditionalJoin($joins)
    {
        $joins = is_array($joins) === false ? array($joins) : $joins;

        foreach ($joins as $join) {
            if (strlen($this->m_additional_joins) === 0) {
                $this->m_additional_joins .= $join;
            } else {
                $this->m_additional_joins .= " " . $join;
            }
        }

        return $this;
    }


    /**
     * addAdditionalSelect
     *
     * @access  public
     * @param   array|string    $selects
     * @return  CExportWrapper
     */
    public function addAdditionalSelect($selects)
    {
        $selects = is_array($selects) === false ? array($selects) : $selects;

        foreach ($selects as $select) {
            $this->m_additional_selects[] = $select;
        }

        return $this;
    }


    /**
     * add having
     *
     * @access  public
     * @param   string  $having
     * @return  CExportWrapper
     */
    public function addHaving($having)
    {
        $this->_having[] = $having;

        return $this;
    }


    /**
     * getExportData
     *
     * @access  public
     * @param   array   $parameters
     * @return  array
     */
    public function getExportData($parameters = array())
    {
        $result = array();
        $having = $this->_having;
        $additionalTsSelects = array();

        if ($this->m_alle_erkrankugen === true) {
            $this->_filterDisease(true);
        } else {
            $this->_params['sub'] = reset($this->m_erkrankungen);
            $this->_filterDisease();
        }

        // Diagnosen
        if (strlen($this->m_diagnosen) > 0) {
            $having[] = "({$this->m_diagnosen})";
        }

        //Morphologien
        if (strlen($this->m_morphologien) > 0) {
            $having[] = "({$this->m_morphologien})";
        }

        // Primary Cases Only
        if ($this->m_use_primary_cases_only === true) {
            $having[] = "t.anlass = 'p'";
        }

        // Ignore Patient Ids
        if (count($this->m_ignore_patient_ids) > 0) {
            $ids = implode( ",", $this->m_ignore_patient_ids );

            $having[] = "p.patient_id NOT IN ({$ids})";
        }

        $relevantSelectWhere = "ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
        $rezidivOneStepCheck = $this->_rezidivOneStepCheck();

        $stageCalc = stageCalc::create($this->_db);

        $relevantSelects = array(
            $stageCalc->select( null, 'figo' ) . "AS 'figo'",
            $stageCalc->select( null, 'uicc', true) . "AS 'uicc'",
            "(SELECT ts.m                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL                                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS m",
            "(SELECT ts.g                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.g IS NOT NULL                                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS g",
            "(SELECT ts.diagnose_seite            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.diagnose IS NOT NULL                                                              ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS diagnose_seite",
            "(SELECT ts.resektionsrand            FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.resektionsrand IS NOT NULL                                                        ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS resektionsrand",
            "(SELECT ts.lk_entf                   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_entf IS NOT NULL                                                               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_entf",
            "(SELECT ts.lk_bef                    FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lk_bef IS NOT NULL                                                                ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lk_bef",
            "(SELECT ts.l                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.l IS NOT NULL                                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS l",
            "(SELECT ts.v                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.v IS NOT NULL                                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS v",
            "(SELECT ts.ppn                       FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ppn IS NOT NULL                                                                   ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ppn",
            "(SELECT ts.r_lokal                   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.r_lokal IS NOT NULL                                                               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS r_lokal",
            "(SELECT ts.datum_sicherung           FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.sicherungsgrad = 'end'                                                            ORDER BY ts.datum_sicherung DESC LIMIT 1)                                                   AS end_datum",
            "(SELECT ts.t                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.t IS NOT NULL AND LEFT( ts.t, 1 )='p'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS t_postop",
            "(SELECT ts.n                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.n IS NOT NULL AND LEFT( ts.n, 1 )='p'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS n_postop",
            "(SELECT ts.m                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL AND LEFT( ts.m, 1 )='p'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS m_postop",
            "(SELECT ts.t                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.t IS NOT NULL AND LEFT( ts.t, 1 )='c'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ct",
            "(SELECT ts.n                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.n IS NOT NULL AND LEFT( ts.n, 1 )='c'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS cn",
            "(SELECT ts.m                         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.m IS NOT NULL AND LEFT( ts.m, 1 )='c'                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS cm",
            "(SELECT ts.datum_sicherung           FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite ) AND ts.rezidiv_lokal IS NOT NULL       ORDER BY ts.datum_sicherung ASC LIMIT 1)                                                    AS rezidiv_lokal_datum",
            "(SELECT ts.datum_sicherung           FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite ) AND ts.rezidiv_lk IS NOT NULL          ORDER BY ts.datum_sicherung ASC LIMIT 1)                                                    AS rezidiv_lk_datum",
            "(SELECT ts.datum_sicherung           FROM tumorstatus ts WHERE {$rezidivOneStepCheck} AND ts.diagnose_seite IN ('B', t.diagnose_seite ) AND ts.rezidiv_metastasen IS NOT NULL  ORDER BY ts.datum_sicherung ASC LIMIT 1)                                                    AS rezidiv_metastasen_datum",
            "(SELECT MIN(ts.datum_sicherung)      FROM tumorstatus ts WHERE {$relevantSelectWhere})                                                                                                                                                                                     AS erstdiagnose_datum",
            "(SELECT ts.lokalisation              FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.lokalisation IS NOT NULL                                                          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS lokalisation",
            "(SELECT ts.gleason1                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.gleason1 IS NOT NULL                                                              ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS gleason1",
            "(SELECT ts.gleason2                  FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.gleason2 IS NOT NULL                                                              ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS gleason2",
            "(SELECT ts.ann_arbor_stadium         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ann_arbor_stadium IS NOT NULL                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ann_arbor_stadium",
            "(SELECT ts.ann_arbor_aktivitaetsgrad FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.ann_arbor_aktivitaetsgrad IS NOT NULL                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS ann_arbor_aktivitaetsgrad",
            "(SELECT ts.cll_binet                 FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.cll_binet IS NOT NULL                                                             ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS cll_binet",
            "(SELECT ts.durie_salmon              FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.durie_salmon IS NOT NULL                                                          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS durie_salmon",
            "(SELECT ts.aml_fab                   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.aml_fab IS NOT NULL                                                               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS aml_fab",
            "(SELECT ts.cll_rai                   FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.cll_rai IS NOT NULL                                                               ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS cll_rai",
            "(SELECT ts.estro_urteil              FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.estro_urteil IS NOT NULL                                                          ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS estro_urteil",
            "(SELECT ts.prog_urteil               FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.prog_urteil IS NOT NULL                                                           ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS prog_urteil",
            "(SELECT ts.her2                      FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.her2 IS NOT NULL                                                                  ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS her2",
            "(SELECT ts.her2_fish_methode         FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.her2_fish_methode IS NOT NULL                                                     ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS her2_fish_methode",
            "(SELECT ts.her2_urteil               FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.her2_urteil IS NOT NULL                                                           ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS her2_urteil",
            "(SELECT ts.psa                       FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.psa IS NOT NULL                                                                   ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS psa",
            "(SELECT ts.hoehe                     FROM tumorstatus ts WHERE {$relevantSelectWhere} AND ts.hoehe IS NOT NULL                                                                 ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS hoehe",
        );

        $relevantSelects = array_merge($relevantSelects, $this->m_additional_selects);

        $having = count($having) > 0 ? implode(' AND ', $having) : null;

        $preQuery = $this->_getPreQuery($having, array_merge($relevantSelects, $additionalTsSelects));

        $separator_row = HReports::SEPARATOR_ROWS;
        $separator_col = HReports::SEPARATOR_COLS;

        $query = "
            SELECT
                sit.erstdiagnose_datum                                             AS 'bezugsdatum',
                {$this->m_additional_fields}
                sit.tumorstatus_id                                                 AS 'tumoridentifikator',
                sit.nachname                                                       AS 'nachname',
                sit.vorname                                                        AS 'vorname',
                sit.geburtsdatum                                                   AS 'geburtsdatum',
                sit.geschlecht                                                     AS 'geschlecht',
                sit.erkrankung                                                     AS 'erkrankung',
                sit.diagnose                                                       AS 'diagnose',
                IFNULL( sit.diagnose_seite, '-' )                                  AS 'diagnose_seite',
                sit.t_postop                                                       AS 't_postop',
                sit.n_postop                                                       AS 'n_postop',
                sit.m_postop                                                       AS 'm_postop',
                sit.morphologie                                                    AS 'morphologie',
                sit.lokalisation                                                   AS 'lokalisation',
                sit.erstdiagnose_datum                                             AS 'erstdiagnose_datum',
                MAX( IF( op.art_primaertumor = '1', op.datum, NULL ) )             AS 'primaerop_datum',
                sit.figo                                                           AS 'figo',
                sit.uicc                                                           AS 'uicc',
                MAX(
                    IF(
                        op.art_primaertumor = 1,
                        ( SELECT
                              CONCAT_WS( ', ', u.nachname, u.vorname )
                          FROM
                              user u

                          WHERE
                              u.user_id = op.operateur1_id

                          LIMIT 1 ),
                        NULL
                    )
                )                                                                  AS 'operateur_1',
                GROUP_CONCAT(
                    DISTINCT (
                        IF(
                            s.form = 'eingriff' AND LEFT( s.report_param, 1) = 1 AND
                               SUBSTRING( s.report_param, 3, 1) IN ('B', sit.diagnose_seite) AND
                              ( SELECT
                                    art_primaertumor

                                FROM
                                    eingriff

                                WHERE
                                    eingriff_id = s.form_id
                               ) = 1,
                               SUBSTRING( s.report_param, 5 ),
                               NULL
                           )
                    )
                    SEPARATOR ' '
                )                                                                  AS 'primaer_ops',
                    COUNT(
                    IF(
                                    s.form = 'eingriff' AND
                                    LOCATE('5-', SUBSTRING(s.report_param, 5)) != 0 AND
                                    SUBSTRING( s.report_param, 3, 1 ) IN ( 'B', sit.diagnose_seite ),
                                    1,
                                    null
                              )
                        )                                                                  AS 'anz_ops',
                sit.ct                                                             AS 'ct',
                sit.cn                                                             AS 'cn',
                sit.cm                                                             AS 'cm',
                sit.pt                                                             AS 'pt',
                sit.pn                                                             AS 'pn',
                sit.m                                                              AS 'm',
                sit.g                                                              AS 'g',
                sit.l                                                              AS 'l',
                sit.v                                                              AS 'v',
                sit.ppn                                                            AS 'ppn',
                sit.r                                                              AS 'r',
                sit.r_lokal                                                        AS 'r_lokal',
                sit.resektionsrand                                                 AS 'sicherabstand',
                sit.lk_bef                                                         AS 'lk_bef',
                sit.lk_entf                                                        AS 'lk_entf',
                sit.gleason1                                                       AS 'gleason1',
                sit.gleason2                                                       AS 'gleason2',
                IF( sit.ann_arbor_stadium IS NOT NULL
                            AND sit.ann_arbor_aktivitaetsgrad IS NOT NULL,
                            CONCAT( sit.ann_arbor_stadium,
                                        sit.ann_arbor_aktivitaetsgrad ),
                            NULL
                        )                                                                                                  AS 'ann_arbor',
                sit.cll_binet                                                      AS 'binet',
                sit.durie_salmon                                                   AS 'durie_salmon',
                sit.aml_fab                                                        AS 'fab',
                sit.cll_rai                                                        AS 'rai',
                sit.estro_urteil                                                   AS 'estro_urteil',
                sit.prog_urteil                                                    AS 'prog_urteil',
                sit.her2                                                           AS 'her2',
                sit.her2_fish_methode                                              AS 'her2_fish_methode',
                sit.her2_urteil                                                    AS 'her2_urteil',
                sit.psa                                                            AS 'psa',
                sit.hoehe                                                          AS 'hoehe',
                IF( MIN( k.komplikation_id ) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                                  IFNULL( k.komplikation, '' )
                                  SEPARATOR '{$separator_row}'
                            ),
                            NULL
                        )                                                                  AS 'komplikation',
                        IF(
                    MAX( x.abschluss_grund ) IS NOT NULL AND
                    MAX( x.abschluss_grund ) = 'lost',
                    1,
                    IF(
                                    MAX( x.abschluss_grund ) IS NOT NULL AND
                                    MAX( x.abschluss_grund ) != 'lost',
                        0,
                        NULL
                    )
                )                                                                  AS 'losttofu',
                MAX( x.abschluss_id )                                              AS 'abschluss_id',
                MAX( x.todesdatum )                                                AS 'todesdatum',
                MAX( x.abschluss_grund )                                           AS 'abschlussgrund',
                MAX( x.tod_tumorassoziation )                                      AS 'tod_tumorbedingt',
                MAX( x.letzter_kontakt_datum )                                     AS 'letzte_patienteninformation',
                IF(
                    MAX( x.tod_tumorassoziation ) IS NOT NULL AND
                    MAX( x.tod_tumorassoziation ) IN ( 'tott', 'totn' ),
                    1,
                    IF(
                        MAX( x.tod_tumorassoziation ) IS NOT NULL AND
                        MAX( x.tod_tumorassoziation ) NOT IN ( 'tott', 'totn' ),
                        0,
                        NULL
                    )
                )                                                                  AS 'tod_tumorbedingt_jn',
                MAX( x.tod_tumorassoziation )                                      AS 'tumorassoziation',
                IF( MIN( krm.ekr_id ) IS NOT NULL,
                            GROUP_CONCAT(
                                DISTINCT
                                  IF( krm.ekr_id IS NOT NULL,
                                        CONCAT_WS(
                                            '{$separator_col}',
                                            IFNULL( krm.ekr_id, '' ),
                                        IFNULL( krm.datum, '' ),
                                            IFNULL( krm.wandlung_diagnose, '' ),
                                            IFNULL( krm.meldebegruendung, '' ),
                                            IFNULL( krm.export_for_onkeyline, '' ),
                                            IFNULL( krm.bem, '' ),
                                            IFNULL( krm.user_id, '' ),
                                            IFNULL( krm.mitteilung, '' )
                                    ),
                                    NULL
                                  )
                                  SEPARATOR '{$separator_row}'
                            ),
                            NULL
                )                                                                  AS 'kr_meldungen',
                sit.anlass,
                sit.start_date,
                sit.end_date,
                sit.erkrankung_id,
                sit.patient_id,
                IF( MIN( th_sys.therapie_systemisch_id ) IS NOT NULL,
                    GROUP_CONCAT(
                                DISTINCT
                                IF( th_sys.therapie_systemisch_id IS NOT NULL,
                                        CONCAT_WS(
                                            '{$separator_col}',
                                            IFNULL( th_sys.therapie_systemisch_id, '' ),
                                            IFNULL( th_sys.beginn, '' ),
                                            IFNULL( th_sys.ende, '' ),
                                            IFNULL( th_sys.endstatus, '' ),
                                            IFNULL( th_sys.endstatus_grund, '' ),
                                            IFNULL( th_sys.user_id, '' ),
                                            IFNULL( th_sys.vorlage_therapie_id, '' ),
                                            IFNULL( th_sys.best_response, '' ),
                                            IFNULL( th_sys.intention, '' ),
                                            IFNULL( th_sys.dosisaenderung_grund, '' ),
                                            IFNULL( th_sys.unterbrechung_grund, '' ),
                                            IFNULL( th_sys.vorlage_therapie_art, '' ),
                                            IFNULL( th_sys.vorlage_therapie_id, '' ),
                                            IFNULL( th_sys.therapieplan_id, '' ),
                                            IFNULL( th_sys.vorlage_therapie_art, '' ),
                                            IFNULL( th_sys.metastasentherapie, '' )
                                      ),
                                      NULL
                                  )
                                  SEPARATOR '{$separator_row}'
                            ),
                            NULL
                        )                                                                  AS 'systemische_therapien',
                IF( MIN( th_str.strahlentherapie_id ) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                            IF( th_str.strahlentherapie_id IS NOT NULL,
                                CONCAT_WS(
                                   '{$separator_col}',
                                    IFNULL( th_str.strahlentherapie_id, '' ),
                                    IFNULL( th_str.beginn, '' ),
                                    IFNULL( th_str.ende, '' ),
                                    IFNULL( th_str.endstatus, '' ),
                                    IFNULL( th_str.endstatus_grund, '' ),
                                    IFNULL( th_str.user_id, '' ),
                                    IFNULL( th_str.gesamtdosis, '' ),
                                    IFNULL( th_str.best_response, '' ),
                                    IFNULL( th_str.dosisreduktion_grund, '' ),
                                    IFNULL( th_str.unterbrechung_grund, '' ),
                                    IFNULL( th_str.intention, '' ),
                                    IFNULL( th_str.ziel_mamma_r, '' ),
                                    IFNULL( th_str.ziel_mamma_l, '' ),
                                    IFNULL( th_str.ziel_brustwand_r, '' ),
                                    IFNULL( th_str.ziel_brustwand_l, '' ),
                                    IFNULL( th_str.ziel_mammaria_interna, '' ),
                                    IFNULL( th_str.ziel_axilla_r, '' ),
                                    IFNULL( th_str.ziel_axilla_l, '' ),
                                    IFNULL( th_str.ziel_lk_supra, '' ),
                                    IFNULL( th_str.ziel_lk_para, '' ),
                                    IFNULL( th_str.ziel_knochen, '' ),
                                    IFNULL( th_str.ziel_gehirn, '' ),
                                IFNULL( th_str.ziel_primaertumor, '' ),
                                IFNULL( th_str.ziel_prostata, '' ),
                                IFNULL( th_str.ziel_becken, '' ),
                                IFNULL( th_str.ziel_abdomen, '' ),
                                IFNULL( th_str.ziel_vulva, '' ),
                                IFNULL( th_str.ziel_vulva_pelvin, '' ),
                                IFNULL( th_str.ziel_vulva_inguinal, '' ),
                                IFNULL( th_str.ziel_inguinal_einseitig, '' ),
                                IFNULL( th_str.ziel_ingu_beidseitig, '' ),
                                IFNULL( th_str.ziel_ingu_pelvin, '' ),
                                IFNULL( th_str.ziel_vagina, '' ),
                                IFNULL( th_str.ziel_lymph, '' ),
                                IFNULL( th_str.ziel_paraaortal, '' ),
                                IFNULL( th_str.ziel_lk, '' ),
                                IFNULL( th_str.ziel_lk_iliakal, '' ),
                                IFNULL( th_str.ziel_sonst_detail_text, '' ),
                                IFNULL( th_str.ziel_ganzkoerper, '' ),
                                IFNULL( th_str.ziel_mediastinum, '' ),
                                IFNULL( th_str.ziel_lk_zervikal_r, '' ),
                                IFNULL( th_str.ziel_lk_zervikal_l, '' ),
                                IFNULL( th_str.ziel_lk_hilaer, '' ),
                                IFNULL( th_str.ziel_lk_axillaer_r, '' ),
                                IFNULL( th_str.ziel_lk_axillaer_l, '' ),
                                IFNULL( th_str.ziel_lk_abdominell_o, '' ),
                                IFNULL( th_str.ziel_lk_abdominell_u, '' ),
                                IFNULL( th_str.ziel_lk_iliakal_r, '' ),
                                IFNULL( th_str.ziel_lk_iliakal_l, '' ),
                                IFNULL( th_str.ziel_lk_inguinal_r, '' ),
                                IFNULL( th_str.ziel_lk_inguinal_l, '' ),
                                IFNULL( th_str.boostdosis, '' ),
                                IFNULL( th_str.gesamtdosis, '' ),
                                IFNULL( th_str.vorlage_therapie_id, '' ),
                                IFNULL( th_str.art, '' ),
                                IFNULL( th_str.ziel_sonst_detail, '' ),
                                IFNULL( th_str.therapieplan_id, '' ),
                                IFNULL( th_str.vorlage_therapie_art, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                                                                  AS 'strahlen_therapien',
                    IF( MIN( th_son.sonstige_therapie_id ) IS NOT NULL,
                        GROUP_CONCAT(
                        DISTINCT
                              IF( th_son.sonstige_therapie_id IS NOT NULL,
                                    CONCAT_WS(
                                        '{$separator_col}',
                                        IFNULL(th_son.sonstige_therapie_id, ''),
                                IFNULL(th_son.beginn, ''),
                                IFNULL(th_son.ende, ''),
                                IFNULL(th_son.endstatus, ''),
                                IFNULL(th_son.endstatus_grund, ''),
                                IFNULL(th_son.user_id, ''),
                                IFNULL(th_son.best_response, ''),
                                IFNULL(th_son.intention, ''),
                                IFNULL(th_son.unterbrechung_grund, '')
                                    ),
                                    NULL
                              )
                              SEPARATOR '{$separator_row}'
                        ),
                        NULL
                    )                                                                  AS 'sonstige_therapien',
                    IF( MIN( op.eingriff_id ) IS NOT NULL,
                    GROUP_CONCAT(
                        DISTINCT
                              IF( op.eingriff_id IS NOT NULL,
                                      CONCAT_WS(
                                          '{$separator_col}',
                                    IFNULL( op.eingriff_id, '' ),
                                IFNULL( op.datum, '' ),
                                IFNULL( op.art_primaertumor, '0' ),
                                IFNULL( op.art_lk, '0' ),
                                IFNULL( op.art_metastasen, '0' ),
                                IFNULL( op.art_rezidiv, '0' ),
                                IFNULL( op.art_nachresektion, '0' ),
                                IFNULL( op.art_revision, '0' ),
                                IFNULL( op.art_diagnostik, '' ),
                                IFNULL( op.intention, '' ),
                                IFNULL( op.bem, '' ),
                                IFNULL( op.operateur1_id, ''),
                                IFNULL( op.asa, '' ),
                                IFNULL( op.notfall, '' ),
                                IFNULL( op.pme, '' ),
                                IFNULL( op.polypen, '' ),
                                IFNULL( op.polypen_op_areal, '' ),
                                IFNULL( op.ther_koloskopie_vollstaendig, '' ),
                                IFNULL( op.tme, '' ),
                                IFNULL( op.transfusion_anzahl_ek, '' ),
                                IFNULL( op.org_id, '' ),
                                IFNULL( op.mark, '' ),
                                IFNULL( op.mark_abstand, '' ),
                                IFNULL( op.postop_roentgen, '' ),
                                IFNULL( op.postop_sono, '' ),
                                IFNULL( op.sln_nein_grund, '' ),
                                IFNULL( op.axilla_nein_grund, '' ),
                                IFNULL( op.sentinel_nicht_detektierbar, '' ),
                                IFNULL( op.therapieplan_id, '' ),
                                IFNULL( op.art_rekonstruktion, '' ),
                                IFNULL( op.art_revision, '' ),
                                IFNULL( op.art_sonstige, '' ),
                                IFNULL( op.diagnose_seite, '' ),
                                IFNULL( op.intraop_sono, '' ),
                                IFNULL( op.intraop_roe, '' )
                              ),
                              NULL
                          )
                          SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                                                                  AS 'operationen',
                IF( MIN( n.nachsorge_id ) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF( n.nachsorge_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( n.nachsorge_id, '' ),
                                IFNULL( n.datum, '' ),
                                IFNULL( n.response_klinisch, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                          NULL
                    )                                                                AS 'nachsorgen',
                    IF( MAX( an.anamnese_id ) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF( an.anamnese_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( an.anamnese_id, '' ),
                                IFNULL( an.datum, '' ),
                                IFNULL( an.menopausenstatus, '' ),
                                IFNULL( an.entdeckung, '' ),
                                IFNULL( an.groesse, '' ),
                                IFNULL( an.gewicht, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                          ),
                          NULL
                    )                                                                  AS 'anamnesen',
                    IF( MAX( h.histologie_id ) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF( h.histologie_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( h.histologie_id, '' ),
                                IFNULL( h.datum, '' ),
                                IFNULL( h.user_id, '' ),
                                IFNULL( h.histologie_nr, '' ),
                                IFNULL( h.kras, '' ),
                                IFNULL( h.lk_bef, '' ),
                                IFNULL( h.lk_entf, '' ),
                                IFNULL( h.mercury, '' ),
                                IFNULL( h.msi, '' ),
                                IFNULL( h.r, '' ),
                                IFNULL( h.resektionsrand_aboral, '' ),
                                IFNULL( h.resektionsrand_lateral, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                                                                  AS 'histologien',
                IF( MAX( h_a.histologie_id ) IS NOT NULL,
                        GROUP_CONCAT( DISTINCT
                        IF( h_a.histologie_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                IFNULL( h_a.histologie_id, '' ),
                                IFNULL( h_a.datum, '' ),
                                IFNULL( h_a.user_id, '' ),
                                IFNULL( h_a.histologie_nr, '' ),
                                IFNULL( h_a.kras, '' ),
                                IFNULL( h_a.lk_bef, '' ),
                                IFNULL( h_a.lk_entf, '' ),
                                IFNULL( h_a.mercury, '' ),
                                IFNULL( h_a.msi, '' ),
                                IFNULL( h_a.r, '' ),
                                IFNULL( h_a.resektionsrand_aboral, '' ),
                                IFNULL( h_a.resektionsrand_lateral, '' ),
                                IFNULL( h_a.eingriff_id, '' ),
                                IFNULL( h_a.upa, '' ),
                                IFNULL( h_a.pai1, '' )
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                )                                                                  AS 'alle_histologien'

                  FROM
                      ({$preQuery}) sit
                {$this->_innerStatus()}

                    LEFT JOIN histologie h                                             ON s.form = 'histologie'
                                                                                          AND h.histologie_id = s.form_id
                                                                                          AND h.user_id IS NOT NULL
                                                                                          AND h.diagnose_seite IN ('B', sit.diagnose_seite )

                    LEFT JOIN histologie h_a                                           ON s.form = 'histologie'
                                                                                              AND h_a.histologie_id = s.form_id
                                                                                              AND h_a.diagnose_seite IN ('B', sit.diagnose_seite )


                        LEFT JOIN zytologie z                                              ON s.form = 'zytologie'
                                                                                              AND z.zytologie_id  = s.form_id
                        LEFT JOIN therapie_systemisch th_sys                               ON s.form = 'therapie_systemisch'
                                                                                              AND th_sys.therapie_systemisch_id = s.form_id
                LEFT JOIN strahlentherapie th_str                                  ON s.form = 'strahlentherapie'
                                                                                      AND th_str.strahlentherapie_id = s.form_id
                LEFT JOIN sonstige_therapie th_son                                 ON s.form = 'sonstige_therapie'
                                                                                      AND th_son.sonstige_therapie_id = s.form_id
                LEFT JOIN eingriff op                                              ON s.form = 'eingriff'
                                                                                      AND op.eingriff_id = s.form_id
                                                                                      AND op.diagnose_seite IN ('B', sit.diagnose_seite )
                LEFT JOIN therapieplan tp                                          ON s.form = 'therapieplan'
                                                                                      AND tp.therapieplan_id = s.form_id
                {$this->_statusJoin( 'komplikation k' )}
                LEFT JOIN nachsorge n                                              ON s.form = 'nachsorge'
                                                                                      AND LOCATE( CONCAT_WS( '', '-',sit.erkrankung_id,'-' ), s.report_param ) > 0
                                                                                      AND n.nachsorge_id = s.form_id
                        LEFT JOIN anamnese an                                              ON s.form = 'anamnese'
                                                                                              AND an.anamnese_id = s.form_id
                LEFT JOIN abschluss x                                              ON s.form = 'abschluss'
                                                                                      AND x.abschluss_id = s.form_id
                LEFT JOIN aufenthalt auf                                           ON s.form = 'aufenthalt'
                                                                                      AND auf.aufenthalt_id = s.form_id
                LEFT JOIN ekr krm                                                       ON krm.erkrankung_id=sit.erkrankung_id
                      LEFT JOIN sonstige_therapie th_sonst                               ON s.form = 'sonstige_therapie'
                                                                                            AND th_sonst.sonstige_therapie_id = s.form_id
                {$this->m_additional_joins}

                  GROUP BY
                sit.patient_id,
                sit.erkrankung_id,
                sit.anlass,
                sit.diagnose_seite

                  HAVING
                {$this->_buildHaving($this->_relevantFieldForTimeRangeCheck, false)}

                  ORDER BY
                nachname,
                vorname,
                erkrankung
        ";

        $data = sql_query_array($this->_db, $query);

        foreach ($data as $i => $dataset) {
            $kr_meldungen = array();

            if ( strlen( $data[ $i ][ 'kr_meldungen' ] ) > 0 ) {
                $kr_meldungen = HReports::RecordStringToArray( $data[ $i ][ 'kr_meldungen' ],
                                                               array(
                                                                   "ekr_id",
                                                                   "datum",
                                                                   "wandlung_diagnose",
                                                                   "meldebegruendung",
                                                                   "export_for_onkeyline",
                                                                   "bem",
                                                                   "user_id",
                                                                   "mitteilung_krebsregister"
                                                               ) );
            }
            $data[ $i ][ 'kr_meldung' ] = array();
            if ( $this->m_use_ekr_meldung_check ) {
                $ekr = HReports::GetMaxElementByDate( $kr_meldungen );
                if ( false === $ekr ) {
                    continue;
                }
                $data[ $i ][ 'kr_meldung' ] = $ekr;
            }
            $data[ $i ][ 'kr_meldungen' ] = $kr_meldungen;

            // UICC und FIGO Berechnungen
            if ( $dataset[ 'erkrankung' ] == 'gt' ) {
                $stageCalc->setSub( 'gt' );
                $data[ $i ][ 'figo' ] = $stageCalc->calc( $dataset[ 'figo' ] );
                // $data[ $i ][ 'figo_t' ] = $stageCalc->getCacheValue( 't' );
                $data[ $i ][ 'uicc' ] = null;
            } else {
                $stageCalc->setSub( $dataset[ 'erkrankung' ] );
                $data[ $i ][ 'uicc' ] = $stageCalc->calc( $dataset[ 'uicc' ] );
                $data[ $i ][ 'figo' ] = null;
            }

            // Gleason Score bestimmen
            if ( ( strlen( $data[ $i ][ 'gleason1' ] ) > 0 ) &&
                 ( strlen( $data[ $i ][ 'gleason2' ] ) > 0 ) ) {
                $data[ $i ][ 'gleason_score' ] = intval( $data[ $i ][ 'gleason1' ] ) + intval( $data[ $i ][ 'gleason2' ] );
            }
            else {
                $data[ $i ][ 'gleason_score' ] = null;
            }
            // Therapien holen
            // Systemisch
            $systemische_therapien = array();
            if ( strlen( $data[ $i ][ 'systemische_therapien' ] ) > 0 ) {
                $systemische_therapien = HReports::RecordStringToArray( $data[ $i ][ 'systemische_therapien' ],
                                                                        array(
                                                                            "systemische_therapie_id",
                                                                            "beginn",
                                                                            "ende",
                                                                            "endstatus",
                                                                            "endstatus_grund",
                                                                            "user_id",
                                                                            "vorlage_therapie_id",
                                                                            "best_response",
                                                                            "intention",
                                                                            "dosisaenderung_grund",
                                                                            "unterbrechung_grund",
                                                                            "art",
                                                                            "vorlage_therapie_id",
                                                                            "therapieplan_id",
                                                                            "vorlage_therapie_art",
                                                                            "metastasentherapie"
                                                                        ) );
            }
            $data[ $i ][ 'systemische_therapien' ] = $systemische_therapien;
            // Strahlen
            $strahlen_therapien = array();
            if ( strlen( $data[ $i ][ 'strahlen_therapien' ] ) > 0 ) {
                $strahlen_therapien = HReports::RecordStringToArray( $data[ $i ][ 'strahlen_therapien' ],
                                                                     array(
                                                                         "strahlentherapie_id",
                                                                         "beginn",
                                                                         "ende",
                                                                         "endstatus",
                                                                         "endstatus_grund",
                                                                         "user_id",
                                                                         "gesamtdosis",
                                                                         "best_response",
                                                                         "dosisreduktion_grund",
                                                                         "unterbrechung_grund",
                                                                         "intention",
                                                                         "ziel_mamma_r",
                                                                         "ziel_mamma_l",
                                                                         "ziel_brustwand_r",
                                                                         "ziel_brustwand_l",
                                                                         "ziel_mammaria_interna",
                                                                         "ziel_axilla_r",
                                                                         "ziel_axilla_l",
                                                                         "ziel_lk_supra",
                                                                         "ziel_lk_para",
                                                                         "ziel_knochen",
                                                                         "ziel_gehirn",
                                                                         "ziel_primaertumor",
                                                                         "ziel_prostata",
                                                                         "ziel_becken",
                                                                         "ziel_abdomen",
                                                                         "ziel_vulva",
                                                                         "ziel_vulva_pelvin",
                                                                         "ziel_vulva_inguinal",
                                                                         "ziel_inguinal_einseitig",
                                                                         "ziel_ingu_beidseitig",
                                                                         "ziel_ingu_pelvin",
                                                                         "ziel_vagina",
                                                                         "ziel_lymph",
                                                                         "ziel_paraaortal",
                                                                         "ziel_lk",
                                                                         "ziel_lk_iliakal",
                                                                         "ziel_sonst_detail_text",
                                                                         "ziel_ganzkoerper",
                                                                         "ziel_mediastinum",
                                                                         "ziel_lk_zervikal_r",
                                                                         "ziel_lk_zervikal_l",
                                                                         "ziel_lk_hilaer",
                                                                         "ziel_lk_axillaer_r",
                                                                         "ziel_lk_axillaer_l",
                                                                         "ziel_lk_abdominell_o",
                                                                         "ziel_lk_abdominell_u",
                                                                         "ziel_lk_iliakal_r",
                                                                         "ziel_lk_iliakal_l",
                                                                         "ziel_lk_inguinal_r",
                                                                         "ziel_lk_inguinal_l",
                                                                         "boostdosis",
                                                                         "gesamtdosis",
                                                                         "vorlage_therapie_id",
                                                                         "art",
                                                                         "ziel_sonst_detail",
                                                                         "therapieplan_id",
                                                                         "vorlage_therapie_art"
                                                                     ) );
            }
            $data[ $i ][ 'strahlen_therapien' ] = $strahlen_therapien;
            // Sonstige
            $sonstige_therapien = array();
            if ( strlen( $data[ $i ][ 'sonstige_therapien' ] ) > 0 ) {
                $sonstige_therapien = HReports::RecordStringToArray( $data[ $i ][ 'sonstige_therapien' ],
                                                                     array(
                                                                         "sonstige_therapie_id",
                                                                         "beginn",
                                                                         "ende",
                                                                         "endstatus",
                                                                         "endstatus_grund",
                                                                         "user_id",
                                                                         "best_response",
                                                                         "intention",
                                                                         "unterbrechung_grund"
                                                                     ) );
            }
            $data[ $i ][ 'sonstige_therapien' ] = $sonstige_therapien;
            // Eingriffe holen
            $operationen = array();
            if ( strlen( $data[ $i ][ 'operationen' ] ) > 0 ) {
                $operationen = HReports::RecordStringToArray( $data[ $i ][ 'operationen' ],
                                                              array(
                                                                  "eingriff_id",
                                                                  "beginn",
                                                                  "art_primaertumor",
                                                                  "art_lk",
                                                                  "art_metastasen",
                                                                  "art_rezidiv",
                                                                  "art_nachresektion",
                                                                  "art_revision",
                                                                  "art_diagnostik",
                                                                  "intention",
                                                                  "bem",
                                                                  "operateur1_id",
                                                                  "asa",
                                                                  "notfall",
                                                                  "pme",
                                                                  "polypen",
                                                                  "polypen_op_areal",
                                                                  "ther_koloskopie_vollstaendig",
                                                                  "tme",
                                                                  "transfusion_anzahl_ek",
                                                                  "org_id",
                                                                  "mark",
                                                                  "mark_abstand",
                                                                  "postop_roentgen",
                                                                  "postop_sono",
                                                                  "sln_nein_grund",
                                                                  "axilla_nein_grund",
                                                                  "sentinel_nicht_detectierbar",
                                                                  "therapieplan_id",
                                                                  "art_rekonstruktion",
                                                                  "art_revision",
                                                                  "art_sonstige",
                                                                  "diagnose_seite",
                                                                  "intraop_sono",
                                                                  "intraop_roe"
                                                              ) );
                foreach( $operationen as $key => $op ) {
                    $operationen[ $key ][ 'ops_codes' ] = $this->_getOpsCodes($op['eingriff_id']);
                }
            }
            $data[ $i ][ 'operationen' ] = $operationen;
            // Verläufe holen
            $nachsorgen = array();
            if ( strlen( $data[ $i ][ 'nachsorgen' ] ) > 0 ) {
                $nachsorgen = HReports::RecordStringToArray( $data[ $i ][ 'nachsorgen' ],
                                                             array(
                                                                 "nachsorge_id",
                                                                 "datum",
                                                                 "tumorgeschehen"
                                                             ) );
            }
            $data[ $i ][ 'nachsorgen' ] = $nachsorgen;

            $metastasisLocalization = $this->GetMetastasenLokalisationen(
                $data[$i]['erkrankung_id'],
                $data[$i]['diagnose_seite'],
                $data[$i]['start_date'],
                $data[$i]['end_date'])
            ;

            // Tumorstatus Metastasenlokalisationen (jüngster Tumorstatus)
            $data[$i]['metastasen_lokalisationen'] = count($metastasisLocalization) > 0
                ? reset($metastasisLocalization)
                : array()
            ;

            // Tumorstatus Metastasenlokalisationen (alle Tumorstatus)
            $data[$i]['metastasen_lokalisationen_alle'] = $metastasisLocalization;


            // Anamnesen holen
            $anamnesen = array();
            if ( strlen( $data[ $i ][ 'anamnesen' ] ) > 0 ) {
                $anamnesen = HReports::RecordStringToArray( $data[ $i ][ 'anamnesen' ],
                                                            array(
                                                                "anamnese_id",
                                                                "datum",
                                                                "menopausenstatus",
                                                                "entdeckung",
                                                                "groesse",
                                                                "gewicht"
                                                            ) );
            }
            $data[ $i ][ 'anamnesen' ] = $anamnesen;
            // Histologien die Pathologen und Nr gesetzt haben holen
            $histologien = array();
            if ( strlen( $data[ $i ][ 'histologien' ] ) > 0 ) {
                $histologien = HReports::RecordStringToArray( $data[ $i ][ 'histologien' ],
                                                              array(
                                                                  "histologie_id",
                                                                  "datum",
                                                                  "user_id",
                                                                  "histologie_nr",
                                                                  "kras",
                                                                  "lk_bef",
                                                                  "lk_entf",
                                                                  "mercury",
                                                                  "msi",
                                                                  "r",
                                                                  "resektionsrand_aboral",
                                                                  "resektionsrand_lateral"
                                                              ) );
            }
            $data[ $i ][ 'histologien' ] = $histologien;
            // Alle Histologien holen
            $alle_histologien = array();
            if ( strlen( $data[ $i ][ 'alle_histologien' ] ) > 0 ) {
                $alle_histologien = HReports::RecordStringToArray( $data[ $i ][ 'alle_histologien' ],
                                                                   array(
                                                                       "histologie_id",
                                                                       "datum",
                                                                       "user_id",
                                                                       "histologie_nr",
                                                                       "kras",
                                                                       "lk_bef",
                                                                       "lk_entf",
                                                                       "mercury",
                                                                       "msi",
                                                                       "r",
                                                                       "resektionsrand_aboral",
                                                                       "resektionsrand_lateral",
                                                                       "eingriff_id",
                                                                       "upa",
                                                                       "pai1"
                                                                   ) );
            }

            $data[$i]['alle_histologien'] = $alle_histologien;

            if ($this->m_use_ekr_meldung_check === true) {
                if ($ekr['datum'] >= $this->m_von_date && $ekr['datum'] <= $this->m_bis_date) {
                    $result[] = $data[$i];
                }
            } else {
                $result[] = $data[$i];
            }
        }

        return $result;
    }


    /**
     * GetOpsCodes from cache
     *
     * @access  protected
     * @param   int $opId
     * @return  array
     */
    protected function _getOpsCodes($opId)
    {
        $opsCodes = $this->_cache['opscodes'];

        // build cache when not already done
        if ($opsCodes === null) {
            $opsCodes   = array();
            $diseaseIds = $this->getFilteredDiseases();

            $query = "
                SELECT
                    eo.eingriff_id,
                    eo.prozedur,
                    eo.prozedur_seite,
                    eo.prozedur_text
                FROM
                    eingriff_ops eo
                WHERE
                    erkrankung_id IN ({$diseaseIds})
            ";

            foreach (sql_query_array($this->_db, $query) as $record) {
                $id = array_shift($record);

                $opsCodes[$id][] = $record;
            }

            $this->_cache['opscodes'] = $opsCodes;
        }

        return array_key_exists($opId, $opsCodes) === true ? $opsCodes[$opId] : array();
    }



    protected function GetMetastasenLokalisationen( $erkrankung_id,
                                                    $diagnose_seite,
                                                    $start_datum,
                                                    $ende_datum )
    {
        $query = "
            SELECT
                ts.anlass,
                ts.datum_sicherung,
                ts.sicherungsgrad,
                ts.datum_beurteilung,
                ts_m.lokalisation,
                ts_m.lokalisation_text,
                ts.tumorstatus_id

            FROM tumorstatus ts
                INNER JOIN tumorstatus_metastasen ts_m ON ts_m.tumorstatus_id = ts.tumorstatus_id

            WHERE
                ts.erkrankung_id = '{$erkrankung_id}'
                AND ts.diagnose_seite = '{$diagnose_seite}'
                AND ts.datum_sicherung >= '{$start_datum}'
                AND ts.datum_sicherung <= '{$ende_datum}'

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC
        ";

        $result = sql_query_array($this->_db, $query);

        if ($result === false) {
            return array();
        }

        $data = array();

        foreach ($result as $row) {
            $data[$row['tumorstatus_id']][] = $row;
        }

        return $data;
    }



    protected function GetNeuesteKrMeldung( $kr_meldungen )
    {
        $result = false;
        $max_datum = 0;
        if ( !is_array( $kr_meldungen ) ) {
            return false;
        }
        foreach( $kr_meldungen as $kr_meldung ) {
            if ( strtotime( $kr_meldung[ 'datum' ] ) > $max_datum ) {
                $result = $kr_meldung;
                $max_datum = strtotime( $kr_meldung[ 'datum' ] );
            }
        }
        return $result;
    }


    /**
     * getSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getSmarty()
    {
        return $this->_smarty;
    }


    /**
     * getDb
     *
     * @access  public
     * @return  Resource
     */
    public function getDb()
    {
        return $this->_db;
    }
}

?>
