
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


require_once('feature/export/base/class.exportdefaultmodel.php');
require_once('feature/export/base/helper.common.php');
require_once('class.gekid_plus_1_5_serialiser.php');
require_once('core/class/report/helper.reports.php');
require_once('feature/export/base/helper.common.php');

class Cgekid_plus_1_5_Model extends CExportDefaultModel
{

    protected $_lBasic = array();

    /**
     * @access
     * @var array
     */
    protected $_diagnoseFilter =
        array('C', 'D0', 'D32', 'D33', 'D37', 'D38', 'D39', 'D4', 'Q85.0', 'Z08', 'Z51.1', 'Z85');


    /**
     * @access
     * @var array
     */
    protected $_changedFields = array();


    protected $_diagnoseSicherungCodes = array();


    /**
     * @access
     * @var array
     */
    protected $_mappings = array();


    /**
     * @access
     * @var array
     */
    protected $_user = array();


    /**
     * @access
     * @var null
     */
    protected $_org = null;


    /**
     *
     */
    public function __construct() {}


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $wrapper
     * @param $export_record
     * @return void
     */
    public function ExtractData($parameters, $wrapper, &$export_record)
    {
        $this->_readLBasic();
        $this->_readDiagnoseSicherungCodes();

        $this->m_smarty->config_load('../feature/export/gekid_plus/gekid_plus_1_5.conf');
        $this->m_config = $this->m_smarty->get_config_vars();

        $wrapper->SetRangeDate($parameters['datum_von'], $parameters['datum_bis']);
        $wrapper->setParam('datum_von', '0000-00-00');
        $wrapper->setParam('datum_bis', '9999-12-31');
        $wrapper->SetErkrankungen('all');
        $wrapper->SetDiagnosen($this->_buildDiagnoseFilter());
        $wrapper->UsePrimaryCasesOnly();
        $stageCalc = stageCalc::create($this->m_db);
        $this
            ->_addPatientData($wrapper)
            ->_addPrimaryTumorData($wrapper, $stageCalc)
            ->_addConclusionData($wrapper)
        ;
        $result = $wrapper->GetExportData($parameters);
        $this
            ->_setDiagnoseLocalistions()
            ->_setRequiredEkrUserData($wrapper)
            ->_setRequiredOrg($parameters['org_id'])
        ;
        foreach ($result as $extract_data) {
            if ($this->_bundeslandCheck($extract_data) &&
                $this->_melderCheck($parameters['melder_id'], $extract_data)) {
                //$extract_data['operationen'] = $this->_getPrimaryOperations($extract_data['operationen']);
                $stageCalc->setSub($extract_data['erkrankung']);
                // Create main case
                $case = $this->CreateCase($export_record->GetDbid(), $parameters, $extract_data);
                $this
                    ->_addCaseSection($case, $parameters, $extract_data, 'melder')
                    ->_addCaseSection($case, $parameters, $extract_data, 'person')
                    ->_addCaseSection($case, $parameters, $extract_data, 'tumor')
                    ->_addCaseSection($case, $parameters, $extract_data, 'pathologe')
                ;
                // Add main case
                $export_record->AddCase($case);
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $extract_data
     * @return bool
     */
    protected function _bundeslandCheck($extract_data)
    {
        if (('BE' == $this->_org['bundesland']) ||
            ('BB' == $this->_org['bundesland']) ||
       //     ('MV' == $this->_org['bundesland']) ||
            ('ST' == $this->_org['bundesland']) ||
            ('SN' == $this->_org['bundesland']) ||
            ('TH' == $this->_org['bundesland'])) {
            // Dann darf kein Export passieren!
            return false;
        }
        return true;
    }


    /**
     *
     *
     * @access
     * @param $melderId
     * @param $extractData
     * @return bool
     */
    protected function _melderCheck($melderId, $extractData) {
        if ($melderId === $extractData['kr_meldung']['user_id']) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _readLBasic()
    {
        $query = "
            SELECT
                klasse,
                code            AS code_med,
                code_gekid_plus

            FROM
                l_basic
        ";
        $result = sql_query_array($this->m_db, $query);
        if ($result !== false) {
            foreach ($result as $row) {
                $this->_lBasic[$row['klasse'] . "_" . $row['code_med']] = $row[ 'code_gekid_plus' ];
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $klasse
     * @param $code
     * @return string
     */
    protected function _getLBasicCode($klasse, $code, $defaultValue = '')
    {
        if (isset($this->_lBasic[$klasse . "_" . $code])) {
            return $this->_lBasic[$klasse . "_" . $code];
        }
        return $defaultValue;
    }


    /**
     *
     *
     * @access
     * @param $med_code
     * @return string
     */
    protected function _getGenderCode($med_code)
    {
        $result = $this->_getLBasicCode('geschlecht', $med_code);
        if ('' === $result) {
            $result = 'U';
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $operations array
     * @return array
     */
    protected function _getPrimaryOperations($operations)
    {
        $result = array();
        foreach ($operations as $operation) {
            if ('1' == $operation['art_primaertumor']) {
                $result[] = $operation;
            }
        }
        return $result;
    }


    /**
     * add section to case
     *
     * @access  protected
     * @param   $case
     * @param   $data
     * @param   $name
     * @return  $this
     */
    protected  function _addCaseSection(RExportCase $case, $parameters, $data, $name)
    {
        $method = '_create' . ucfirst($name) . 'Section';
        $section = $this->{$method}($data, $sectionUID);
        $case->AddSection($this->CreateBlock($case->GetDbid(), $parameters, $name, $sectionUID, $section));
        return $this;
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function PreparingData($parameters, &$export_record)
    {
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $section
     * @param $old_section
     * @return void
     */
    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
        $section->SetMeldungskennzeichen("N");
        $section->SetDataChanged(1);
    }


    /**
     *
     *
     * @access
     * @param $parameters
     * @param $export_record
     * @return void
     */
    public function CheckData($parameters, &$export_record)
    {
        // Hier jeden Abschnitt gegen XSD Prüfen und Fehler in DB schreiben...
        $serialiser = new Cgekid_plus_1_5_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(),
            $this->m_smarty, $this->m_db, $this->m_error_function
        );
        $serialiser->SetData($export_record);
        $serialiser->Validate($this->m_parameters);
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function WriteData()
    {
        $this->m_export_record->SetFinished(true);
        // Hier gesammtes XML schreiben und nicht mehr gegen XSD prüfen..
        $serialiser = new Cgekid_plus_1_5_Serialiser();
        $serialiser->Create(
            $this->m_absolute_path, $this->GetExportName(),
            $this->m_smarty, $this->m_db, $this->m_error_function
        );
        $serialiser->SetData($this->m_export_record);
        $this->m_export_filename = $serialiser->Write($this->m_parameters);
        $this->m_export_record->Write($this->m_db);
    }


    //*********************************************************************************************
    //
    // Append Data to wrapper
    //

    /**
     *
     *
     * @access
     * @param $date
     * @return bool|string
     */
    protected function _getFormatedDate($date)
    {
        if (0 == strlen($date)) {
            return '';
        }
        return date('d.m.Y', strtotime($date));
    }


    /**
     *
     *
     * @access
     * @param $from
     * @param $to
     * @return mixed
     */
    protected function _diffYear($from, $to)
    {
        $from = is_array($from) === true ? $from : explode('-', date('Y-m-d', strtotime($from)));
        $to = is_array($to) === true ? $to : explode('-', date('Y-m-d', strtotime($to)));
        $mod = $to[1] <= $from[1] ? ($to[1] < $from[1] || $to[2] < $from[2] ? 1 : 0) : 0;
        return $to[0] - $from[0] - $mod;
    }


    /**
     *
     *
     * @access
     * @param $case
     * @param $blockName
     * @return array
     */
    protected function _getSectionData($case, $blockName)
    {
        $result = array();
        foreach ($case->GetSections() as $section) {
            if ($blockName == $section->GetBlock()) {
                $result = $section->GetDaten();
            }
        }
        return $result;
    }


    /**
     * add patient data
     *
     * @access protected
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addPatientData(CExportWrapper $wrapper)
    {
        $wrapper
            ->addAdditionalSelect(array(
                'p.titel',
                'p.geburtsname',
                'p.plz',
                'p.ort',
                'p.strasse',
                'p.hausnr'
            ))
            ->addAdditionalFields(array(
                'sit.titel',
                'sit.geburtsname',
                'sit.plz',
                'sit.ort',
                'sit.strasse',
                'sit.hausnr',
                'sit.patient_nr'
            ))
        ;
        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addPrimaryTumorData(CExportWrapper $wrapper, stageCalc $stageCalc)
    {
        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;
        $basicOrder     = 'ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1';
        $basicCondition = "FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
        $wrapper
            ->addAdditionalJoin("LEFT JOIN histologie_einzel he ON he.histologie_id = h_a.histologie_id")
            ->addAdditionalJoin("LEFT JOIN untersuchung u ON s.form = 'untersuchung' AND u.untersuchung_id = s.form_id")
            ->addAdditionalJoin("LEFT JOIN tumorstatus ats ON s.form = 'tumorstatus' AND ats.tumorstatus_id = s.form_id")
        ;
        $wrapper
            ->addAdditionalSelect("(SELECT ts.tnm_praefix {$basicCondition} AND ts.tnm_praefix IS NOT NULL $basicOrder) AS 'tnm_praefix'")
            ->addAdditionalSelect("(SELECT ts.n {$basicCondition} AND ts.n IS NOT NULL $basicOrder) AS 'n'")
            ->addAdditionalSelect("(SELECT ts.t {$basicCondition} AND ts.t IS NOT NULL $basicOrder) AS 't'")
            ->addAdditionalSelect("(SELECT ts.ann_arbor_extralymphatisch {$basicCondition} AND ts.ann_arbor_extralymphatisch IS NOT NULL $basicOrder) AS 'ann_arbor_extralymphatisch'")
            ->addAdditionalSelect("(SELECT ts.diagnose_text {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder) AS 'diagnose_text'")
            ->addAdditionalSelect("(SELECT ts.lokalisation_text {$basicCondition} AND ts.lokalisation IS NOT NULL {$basicOrder}) AS lokalisation_text")
            ->addAdditionalSelect("(SELECT ts.lokalisation_seite {$basicCondition} AND ts.lokalisation_seite IS NOT NULL $basicOrder) AS 'lokalisation_seite'")
            ->addAdditionalSelect("(SELECT ts.morphologie_text {$basicCondition} AND ts.morphologie IS NOT NULL {$basicOrder}) AS morphologie_text")
            ->addAdditionalSelect("(SELECT ts.diagnosesicherung {$basicCondition} AND ts.diagnosesicherung IS NOT NULL {$basicOrder}) AS diagnosesicherung")
            ->addAdditionalSelect("(SELECT ts.estro {$basicCondition} AND ts.estro IS NOT NULL {$basicOrder}) AS estro")
            ->addAdditionalSelect("(SELECT ts.prog {$basicCondition} AND ts.prog IS NOT NULL {$basicOrder}) AS prog")
            ->addAdditionalSelect("(SELECT ts.multifokal {$basicCondition} AND ts.multifokal IS NOT NULL {$basicOrder}) AS multifokal")
            ->addAdditionalSelect("(SELECT ts.immun_phaenotyp {$basicCondition} AND ts.immun_phaenotyp IS NOT NULL {$basicOrder}) AS immun_phaenotyp")
            ->addAdditionalSelect("(SELECT
                CONCAT_WS('|',
                    IFNULL(ts.tumorausbreitung_lokal,''),
                    IFNULL(ts.tumorausbreitung_konausdehnung,''),
                    IFNULL(ts.tumorausbreitung_lk,''),
                    IFNULL(ts.tumorausbreitung_fernmetastasen,'')
                )
                {$basicCondition} AND
                (ts.tumorausbreitung_lokal IS NOT NULL OR
                 ts.tumorausbreitung_konausdehnung IS NOT NULL OR
                 ts.tumorausbreitung_lk IS NOT NULL OR
                 ts.tumorausbreitung_fernmetastasen IS NOT NULL
                )
                {$basicOrder})
                AS tumorausbreitung
            ")
        ;
        $wrapper
            ->addAdditionalField('sit.tnm_praefix')
            ->addAdditionalField('sit.start_date_rezidiv as min_tumorstate')
            ->addAdditionalField("sit.n")
            ->addAdditionalField("sit.t")
            ->addAdditionalField("sit.ann_arbor_extralymphatisch")
            ->addAdditionalField("sit.ann_arbor_aktivitaetsgrad")
            ->addAdditionalField("sit.diagnose_text")
            ->addAdditionalField("sit.ann_arbor_stadium")
            ->addAdditionalField('sit.tumorausbreitung')
            ->addAdditionalField("sit.lokalisation_seite")
            ->addAdditionalField("sit.lokalisation_text")
            ->addAdditionalField("sit.morphologie_text")
            ->addAdditionalField("sit.diagnosesicherung")
            ->addAdditionalField("sit.estro")
            ->addAdditionalField("sit.prog")
            ->addAdditionalField("sit.multifokal")
            ->addAdditionalField("sit.immun_phaenotyp")
            ->addAdditionalField("sit.cll_rai")
            ->addAdditionalField("sit.cll_binet")
            ->addAdditionalField("sit.aml_fab")
            ->addAdditionalField("MAX(he.tumordicke) AS 'tumordicke'")
            ->addAdditionalField("COUNT(z.zytologie_id) AS 'zyto_count'")
            ->addAdditionalField("
                IF(MAX(z.zytologie_id) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF(z.zytologie_id IS NOT NULL AND z.eingriff_id IS NOT NULL,
                            CONCAT_WS( '{$separator_col}',
                                z.zytologie_id,
                                'zyto',
                                z.datum,
                                z.eingriff_id
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'dida_zytologie'
            ")
            ->addAdditionalField("
                IF(MAX(z.zytologie_id) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF(z.zytologie_id IS NOT NULL,
                            CONCAT_WS('{$separator_col}',
                                IFNULL(z.zytologie_id, '' ),
                                IFNULL(z.datum, '' ),
                                IFNULL(z.user_id, '' )
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'zytologien'
            ")
            ->addAdditionalField("GROUP_CONCAT(DISTINCT IF(u.untersuchung_id IS NOT NULL, CONCAT_WS('|', u.untersuchung_id, u.datum), null)) as dida_untersuchung")
            ->addAdditionalField("GROUP_CONCAT(DISTINCT IF(op.eingriff_id IS NOT NULL, CONCAT_WS('|', op.eingriff_id, op.datum), null)) as dida_eingriff")
            ->addAdditionalField("
                IF(MAX(h_a.histologie_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(h_a.histologie_id IS NOT NULL AND (h_a.untersuchung_id IS NOT NULL OR h_a.eingriff_id IS NOT NULL),
                        CONCAT_WS( '{$separator_col}',
                            h_a.histologie_id,
                            'histo',
                            h_a.datum,
                            IFNULL(h_a.untersuchung_id, ''),
                            IFNULL(h_a.eingriff_id, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'dida_histologie'
            ")
            ->addAdditionalField("
                IF(MAX(ats.tumorstatus_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(ats.tumorstatus_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            ats.tumorstatus_id,
                            ats.datum_sicherung,
                            IFNULL(ats.diagnose, ''),
                            IFNULL(ats.diagnose_seite, ''),
                            IFNULL(ats.lokalisation, ''),
                            IFNULL(ats.morphologie, ''),
                            IFNULL(ats.tnm_praefix, ''),
                            IFNULL(ats.t, ''),
                            IFNULL(ats.n, ''),
                            IFNULL(ats.m, ''),
                            IFNULL(ats.uicc, ''),
                            IFNULL(ats.groesse_x, ''),
                            IFNULL(ats.groesse_y, ''),
                            IFNULL(ats.groesse_z, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'tumorstati'
            ")
            ->addAdditionalField("
                IF(MAX(he.histologie_einzel_id) IS NOT NULL,
                GROUP_CONCAT( DISTINCT
                    IF(he.histologie_einzel_id IS NOT NULL,
                        CONCAT_WS( '{$separator_col}',
                            he.histologie_einzel_id,
                            IFNULL(he.createtime, he.updatetime),
                            IFNULL(he.tumordicke, '')
                        ),
                        NULL
                    )
                    SEPARATOR '{$separator_row}'
                ),
                NULL
                ) AS 'histologien_einzel'
            ")
            ->addAdditionalField("
                IF( MIN(op.eingriff_id) IS NOT NULL,
                    GROUP_CONCAT( DISTINCT
                        IF( op.eingriff_id IS NOT NULL AND
                            (
                                op.art_transplantation_autolog = '1' OR
                                op.art_transplantation_allogen_v = '1' OR
                                op.art_transplantation_allogen_nv = '1' OR
                                op.art_transplantation_syngen = '1'
                            ),
                            CONCAT_WS(
                                '{$separator_col}',
                                IFNULL(op.eingriff_id, ''),
                                IFNULL(op.datum, ''),
                                IFNULL(op.art_transplantation_autolog, '0'),
                                IFNULL(op.art_transplantation_allogen_v, '0'),
                                IFNULL(op.art_transplantation_allogen_nv, '0'),
                                IFNULL(op.art_transplantation_syngen, '0')
                            ),
                            NULL
                        )
                        SEPARATOR '{$separator_row}'
                    ),
                    NULL
                ) AS 'ops'
            ")
        ;
        return $this;
    }


    /**
     *
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _addConclusionData(CExportWrapper $wrapper)
    {
        $separator_col = HReports::SEPARATOR_COLS;
        $separator_row = HReports::SEPARATOR_ROWS;
        $wrapper
            ->addAdditionalJoin('LEFT JOIN abschluss_ursache au ON au.abschluss_id = x.abschluss_id')
            ->addAdditionalField("MAX(x.tod_ursache) AS 'tod_ursache'")
            ->addAdditionalField("MAX(x.tod_ursache_text) AS 'tod_ursache_text'")
        ;
        return $this;
    }


    /**
     * create patient section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createMelderSection($data, &$sectionUID)
    {
        $sectionUID = 'MELDER_' . $data['patient_id'] + $data['diagnose_seite'];
        $org  = $this->_getOrg();
        $user = $this->_getUser($data['kr_meldung']['user_id']);
        $section = array(
            'Meldende_Stelle'       => $user['kr_kennung'],
            'KH_Abt_Station_Praxis' => $org['name'],
            'Arztname'              => $user['fullname'],
            'Anschrift'             => $org['strasse'],
            'Postleitzahl'          => $org['plz'],
            'Ort'                   => $org['ort'],
            'Meldedatum'            => date('d.m.Y')
        );
        return $section;
    }


    /**
     * create person section
     *
     * @access
     * @param $data
     * @param $sectionUID
     * @return array
     */
    protected function _createPersonSection($data, &$sectionUID)
    {
        $sectionUID = 'PERSON_' . $data['patient_id'] + $data['diagnose_seite'];
        $street = $data['strasse'];
        $number = $data['hausnr'];
        $postfix = '';
        $this->_splitStreetAndNumber($street, $number, $postfix);
        $section = array(
            'Titel'            => $data['titel'],
            'Vornamen'         => $data['vorname'],
            'Nachname'         => $data['nachname'],
            'Geburtsname'      => $data['geburtsname'],
            'Geschlecht'       => $this->_getGenderCode($data['geschlecht']),
            'Geburtsdatum'     => $this->_getFormatedDate($data['geburtsdatum']),
            'Strasse'          => $street,
            'Hausnummer'       => $number,
            'Postfix'          => $postfix,
            'Postleitzahl'     => $data['plz'],
            'Ort'              => $data['ort'],
            'Todesdatum'       => $this->_getFormatedDate($data['todesdatum']),
            'Todesursache'     => $this->_getTodesursache($data),
            'Meldebegruendung' => $this->_getMeldebegruendung($data['kr_meldung'])
        );
        return $section;
    }


    /**
     *
     *
     * @access
     * @param $street
     * @param $number
     * @param $postfix
     * @return void
     */
    protected function _splitStreetAndNumber(&$street, &$number, &$postfix)
    {
        $pos = false;
        $no = trim($number);
        if (strlen($no) == 0) {
            return;
        }
        if (false === ($pos = strpos($no, '-'))) {
            if (preg_match('/([\D]+)/', $no, $match)) {
                $postfix = trim(reset($match));
                if (false !== ($postPos = strpos($no, $postfix))) {
                    $number = trim(substr($no, 0, $postPos));
                }
            }
        }
        else {
            $number = trim(substr($no, 0, $pos));
            $postfix = trim(substr($no, $pos));
        }
    }


    /**
     * create tumor section
     *
     * @access  protected
     * @param   $data
     * @param   $sectionUID
     * @return  array
     */
    protected function _createTumorSection($data, &$sectionUID)
    {
        $sectionUID = 'TUMOR_' . $data['patient_id'] + $data['diagnose_seite'];
        $zyto = HReports::RecordStringToArray(
            $data['dida_zytologie'],
            array(
                'zytologie_id',
                'type',
                'date',
                'eingriff_id'
            )
        );
        $histo = HReports::RecordStringToArray(
            $data['dida_histologie'],
            array(
                'histologie_id',
                'type',
                'date',
                'untersuchung_id',
                'eingriff_id'
            )
        );
        $dida = array_merge($zyto, $histo);
        $datumDida = $this->_getDiagnoseDatum($data, HReports::OrderRecordsByField($dida, 'date', 'ASC'));
        $tumorstati = HReports::RecordStringToArray(
            $data['tumorstati'],
            array(
                 'tumorstatus_id',
                 'datum',
                 'diagnose',
                 'seite',
                 'lokalisation',
                 'morphologie',
                 'praefix',
                 't',
                 'n',
                 'm',
                 'uicc',
                 'groesse_x',
                 'groesse_y',
                 'groesse_z'
            )
        );
        $tumorstati = HReports::OrderRecordsByField($tumorstati, 'datum', 'DESC');
        $this->_getTNMPraefix(
            $tumorstati, $data['diagnose_seite'], $tnmAuflage, $praefix, $t, $n, $m, $uicc, $tumorgroesse);
        $histologienEinzel = HReports::RecordStringToArray(
            $data['histologien_einzel'],
            array(
                 'histologie_einzel_id',
                 'datum',
                 'tumordicke'
            )
        );

        $section = array(
            'Referenznummer'             => $data['patient_nr'],
            'Diagnosetag'                => date('d', strtotime($datumDida)),
            'Diagnosemonat'              => date('m', strtotime($datumDida)),
            'Diagnosejahr'               => date('Y', strtotime($datumDida)),
            'ICD'                        => $data['diagnose'],
            'Diagnose_Freitext'          => HCommon::TrimString($data['diagnose_text'], 254),
            'Morphologie_Code'           => $data['morphologie'],
            'Morphologie_Freitext'       => $data['morphologie_text'],
            'Dignitaet'                  => $this->_getDignitaet($data['morphologie']),
            'ICD_Auflage'                => '10',
            'Topographie_Code'           => $this->_getLokalisation($data, $this->_getMapping('l_exp_diagnose')),
            'ICDO_Auflage'               => '3',
            'Grading'                    => $this->_getLBasicCode('g', $data['g'], 'U'),
            'Zelltyp'                    => (strlen($data['immun_phaenotyp']) > 0) ? $data['immun_phaenotyp'] : 'U',
            'Diagnosesicherung'          => $this->_getDiagnoseSicherung($data),
            'Diagnoseanlass'             => $this->_getDiagnoseanlass($data),
            'Seitenlokalisation'         => $this->_getSeite($data),
            'Grobstadium'                => $this->_getGrobstadium($data),
            'y'                          => $this->_getY($data),
            'r'                          => $this->_getR($data),
            'a'                          => $this->_getA($data),
            'Praefix_TNM'                => $praefix,
            'T'                          => $t,
            'Multi'                      => ('1' == $data['multifokal']) ? 'm' : '',
            'N'                          => $n,
            'M'                          => $m,
            'R'                          => $this->_getLBasicCode('r', $data['r']),
            'UICC_Stadium'               => $uicc,
            'TNM_Auflage'                => $tnmAuflage,
            'Tumorgroesse'               => $tumorgroesse,
            'Breslow'                    => $this->_getBreslow($histologienEinzel),
            'Gleason_Score'              => $this->_getGleasonScore($data),
            'Andere_Klassifikation'      => $this->_getOtherClassifications($data),
            'Oestrogen_Status'           => $this->GetLBasicBez('posneg', $data['estro_urteil'], 'nicht bestimmt'),
            'Oestrogen_pos_TZ'           => $data['estro'],
            'Progesteron_Status'         => $this->GetLBasicBez('posneg', $data['prog_urteil'], 'nicht bestimmt'),
            'Progesteron_pos_TZ'         => $data['prog'],
            'HER2_Status'                => $this->_getLBasicCode('her2', $data['her2'], 'NB'),
            'Operation'                  => $this->_hasOperation($data['operationen']),
            'Strahlentherapie'           =>
                $this->_hasStrahlenTherapie($data['strahlen_therapien'], $data['systemische_therapien']),
            'Chemotherapie'              =>
                $this->_hasSystemischeTherapie(
                    $data['strahlen_therapien'], $data['systemische_therapien'], array('c', 'ci', 'cst')) ? 'J' : 'N',
            'Hormontherapie'             =>
                $this->_hasSystemischeTherapie(
                    $data['strahlen_therapien'], $data['systemische_therapien'], array('ah', 'ahst')) ? 'J' : 'N',
            'Immuntherapie'              =>
                $this->_hasSystemischeTherapie(
                    $data['strahlen_therapien'], $data['systemische_therapien'], array('i', 'ci', 'ist')) ? 'J' : 'N',
            'Knochenmarktransplantation' => (strlen($data['ops']) > 0) ? 'J' : 'N',
            'Sonstige_Therapie'          =>
                $this->_hasSonstigeTherapie(
                    $data['sonstige_therapien'], $data['strahlen_therapien'], $data['systemische_therapien']),
            'Bemerkungen'                => $data['kr_meldung']['bem']
        );
        return $section;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $sectionUID
     * @return array
     */
    protected function _createPathologeSection($data, &$sectionUID)
    {
        $user = null;
        $section = array(
            'KH_Abt_Station_Praxis' => '',
            'Name_Pathologe'        => '',
            'Anschrift'             => '',
            'Postleitzahl'          => '',
            'Ort'                   => ''
        );
        $zytologien = HReports::RecordStringToArray(
            $data['zytologien'],
            array(
                'zytologie_id',
                'date',
                'user_id'
            )
        );
        $histologie = HReports::GetMaxElementByDate($data['histologien'], 2, null);
        if (false !== $histologie) {
            $user = $this->_getUser($histologie['user_id']);
        }
        else {
            $zytologie = HReports::GetMaxElementByDate($zytologien, 2, null);
            if (false !== $zytologie) {
                $user = $this->_getUser($zytologie['user_id']);
            }
        }
        if (null !== $user) {
            $section['KH_Abt_Station_Praxis'] = $user['org'];
            $section['Name_Pathologe']        = $user['fullname'];
            $section['Anschrift']             = $user['strasse'];
            $section['Postleitzahl']          = $user['plz'];
            $section['Ort']                   = $user['ort'];
        }
        $sectionUID = 'PATHOLOGE_' . $data['patient_id'] + $data['diagnose_seite'];
        return $section;
    }


    /**
     *
     *
     * @access
     * @param $strahlenTherapien
     * @param $systemischeTherapien
     * @return string
     */
    protected function _hasStrahlenTherapie($strahlenTherapien, $systemischeTherapien)
    {
        if (count($strahlenTherapien) > 0) {
            return 'J';
        }
        if ($this->_hasSystemischeTherapie(
            $strahlenTherapien, $systemischeTherapien, array('st', 'cst', 'ist', 'sonstst', 'ahst'))) {
            return 'J';
        }
        return 'N';
    }


    /**
     *
     *
     * @access
     * @param $strahlenTherapien
     * @param $systemischeTherapien
     * @return string
     */
    protected function _hasSonstigeTherapie($sonstigeTherapien, $strahlenTherapien, $systemischeTherapien)
    {
        if (count($sonstigeTherapien) > 0) {
            return 'J';
        }
        if ($this->_hasSystemischeTherapie($strahlenTherapien, $systemischeTherapien, array('son', 'sonstr'))) {
            return 'J';
        }
        return 'N';
    }


    /**
     *
     *
     * @access
     * @param $systemischeTherapien
     * @param $therapieArten
     * @return string
     */
    protected function _hasSystemischeTherapie($strahlenTherapien, $systemischeTherapien, $therapieArten)
    {
        foreach ($strahlenTherapien as $st) {
            if (in_array($st['vorlage_therapie_art'], $therapieArten)) {
                return true;
            }
        }
        foreach ($systemischeTherapien as $sysTh) {
            if (in_array($sysTh['vorlage_therapie_art'], $therapieArten)) {
                return true;
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $operationen
     * @return string
     */
    protected function _hasOperation($operationen)
    {
        if (count($operationen) > 0) {
            foreach ($operationen as $op) {
                if (('1' == $op['art_primaertumor']) ||
                    ('1' == $op['art_metastasen'])) {
                    return 'J';
                }
            }
        }
        return 'N';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getBreslow($histologienEinzel)
    {
        $maxTumorDicke = 0.0;
        $field = false;

        foreach ($histologienEinzel as $he) {
            if ((strlen($he['tumordicke']) > 0) &&
                (floatval($he['tumordicke']) > $maxTumorDicke)) {
                $maxTumorDicke = floatval($he['tumordicke']);
                $field = true;
            }
        }
        if (true == $field) {
            return $maxTumorDicke;
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getGleasonScore($data)
    {
        $g1_n = $this->_getNumericContent($data['gleason1']);
        $g1_c = $this->_getNonNumericContent($data['gleason1']);
        $g2_n = $this->_getNumericContent($data['gleason2']);
        $g2_c = $this->_getNonNumericContent($data['gleason2']);
        if ((strlen($g1_n) > 0) &&
            (strlen($g2_n) > 0)) {
            $gs = intval($g1_n) + intval($g2_n);
            return $gs . $g1_c . $g2_c;
        }
        else if ((strlen($g1_n) > 0) &&
                 (strlen($g2_n) == 0)) {
            return $data['gleason1'];
        }
        else if ((strlen($g1_n) == 0) &&
                 (strlen($g2_n) > 0)) {
            return $data['gleason2'];
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getOtherClassifications($data)
    {
        if (strlen($data['ann_arbor_stadium']) > 0) {

            $annArbor = $data['ann_arbor_stadium'];
            $pos = strpos($annArbor, '/');
            if (false !== $pos) {
                $annArbor = substr($annArbor, 0, $pos);
            }
            return attach_label($annArbor . $data['ann_arbor_aktivitaetsgrad'] . $data['ann_arbor_extralymphatisch'],
                $this->m_config['ann_arbor']
            );
        }
        else if (strlen($data['cll_rai']) > 0) {
            return attach_label($data['cll_rai'], $this->m_config['cll_rai']);
        }
        else if (strlen($data['cll_binet']) > 0) {
            return attach_label($data['cll_binet'], $this->m_config['cll_binet']);
        }
        else if (strlen($data['aml_fab']) > 0) {
            return attach_label($data['aml_fab'], $this->m_config['aml_fab']);
        }
        else if (strlen($data['durie_salmon']) > 0) {
            return attach_label($data['durie_salmon'], $this->m_config['durie_salmon']);
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getSeite($data)
    {
        $seite = $data['lokalisation_seite'];
        if (strlen($seite) == 0) {
            $seite = $data['diagnose_seite'];
        }
        $seite = str_replace('-', '', $seite);
        if ((strlen($data['lokalisation']) > 0) &&
            (strlen($seite) == 0)) {
            return 'T';
        }
        if ((strlen($data['lokalisation']) == 0) &&
            (strlen($seite) == 0)) {
            return 'U';
        }
        return $seite;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getGrobstadium($data)
    {
        if (strlen($data['tumorausbreitung']) > 0) {
            $tmp = explode('|', $data['tumorausbreitung']);
            if ('1' == $tmp[0]) {
                return 'L';
            }
            else if ('1' == $tmp[1]) {
                return 'S';
            }
            else if ('1' == $tmp[2]) {
                return 'R';
            }
            else if ('1' == $tmp[3]) {
                return 'F';
            }
        }
        return 'U';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getY($data)
    {
        if ((strlen($data['tnm_praefix']) > 0) &&
            (('y' == $data['tnm_praefix']) ||
             ('yr' == $data['tnm_praefix']))) {
            return 'y';
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getR($data)
    {
        if ((strlen($data['tnm_praefix']) > 0) &&
            (('r' == $data['tnm_praefix']) ||
             ('yr' == $data['tnm_praefix']))) {
            return 'r';
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getA($data)
    {
        if ((strlen($data['tnm_praefix']) > 0) &&
            ('a' == $data['tnm_praefix'])) {
            return 'a';
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $tumorstati
     * @param $seite
     * @param $praefix
     * @param $t
     * @param $n
     * @param $m
     * @param $uicc
     * @return void
     */
    protected function _getTNMPraefix(
        $tumorstati, $seite, &$tnmAuflage, &$praefix, &$t, &$n, &$m, &$uicc, &$tumorgroesse)
    {
        $dummy = 0;
        $t = $this->_getTumorstatusFilledValueBeforYDocumented($tumorstati, $seite, 't', $tnmAuflage, $praefix);
        $n = $this->_getTumorstatusFilledValueBeforYDocumented($tumorstati, $seite, 'n', $dummy, $dummy);
        $m = $this->_getTumorstatusFilledValueBeforYDocumented($tumorstati, $seite, 'm', $dummy, $dummy);
        $uicc = $this->_getTumorstatusFilledValueBeforYDocumented($tumorstati, $seite, 'uicc', $dummy, $dummy);
        $tumorgroesse = $this->_getTumorSize($tumorstati, $seite);
    }


    /**
     *
     *
     * @access
     * @param $tumorstati
     * @param $fieldName
     * @return string
     */
    protected function _getTumorstatusFilledValueBeforYDocumented(
        $tumorstati, $seite, $fieldName, &$tnmAuflage, &$praefix)
    {
        $result = '';
        foreach ($tumorstati as $ts) {
            if (!isset($ts[$fieldName])) {
                break;
            }
            if ((strlen($ts[$fieldName]) > 0) &&
                (!in_array($ts['praefix'], array('y', 'yr', 'r'))) &&
                ($seite == $ts['seite'])) {
                if ('t' == $fieldName) {
                    $tnmAuflage = '7';
                    $time = strtotime($ts['datum']);
                    if ($time <= strtotime('31.12.2009')) {
                        $tnmAuflage = '6';
                    }
                    $praefix = 'p';
                    $result = $this->_getLBasicCode('pt', $ts['t'], '');
                    if (str_starts_with($ts['t'], 'c')) {
                        $praefix = 'c';
                        $result = $this->_getLBasicCode('ct', $ts['t'], '');
                    }
                }
                else if ('n' == $fieldName) {
                    $result = $this->_getLBasicCode('pn', $ts['n'], '');
                    if (str_starts_with($ts['n'], 'c')) {
                        $result = $this->_getLBasicCode('cn', $ts['n'], '');
                    }
                }
                else if ('m' == $fieldName) {
                    $result = $this->_getLBasicCode('pm', $ts['m'], '');
                    if (str_starts_with($ts['m'], 'c')) {
                        $result = $this->_getLBasicCode('cm', $ts['m'], '');
                    }
                }
                else if ('uicc' == $fieldName) {
                    $result = $this->_getLBasicCode('uicc', $ts['uicc'], '');
                }
                else {
                    $result = $ts[$fieldName];
                }
                return $result;
            }
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $tumorstati
     * @param $seite
     * @return string
     */
    protected function _getTumorSize($tumorstati, $seite)
    {
        $maxSize = 0;
        $filled = false;

        foreach ($tumorstati as $ts) {
            if ((!in_array($ts['praefix'], array('y', 'yr', 'r'))) &&
                ($seite == $ts['seite'])) {
                if ((strlen($ts['groesse_x']) > 0) &&
                    (intval($ts['groesse_x']) > $maxSize)) {
                    $maxSize = intval($ts['groesse_x']);
                    $filled = true;
                }
                if ((strlen($ts['groesse_y']) > 0) &&
                    (intval($ts['groesse_y']) > $maxSize)) {
                    $maxSize = intval($ts['groesse_y']);
                    $filled = true;
                }
                if ((strlen($ts['groesse_z']) > 0) &&
                    (intval($ts['groesse_z']) > $maxSize)) {
                    $maxSize = intval($ts['groesse_z']);
                    $filled = true;
                }
            }
        }
        if (true === $filled) {
            return $maxSize;
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getDiagnoseSicherung($data)
    {
        if (strlen($data['diagnosesicherung']) > 0) {
            return $this->_getLBasicCode('diagnosesicherung', $data['diagnosesicherung'], '9');
        }
        else {
            if (strlen($data['morphologie']) > 0) {
                if (strlen($data['lokalisation']) > 0) {
                    if (('C80.9' != $data['lokalisation']) &&
                        (in_array(substr($data['morphologie'], -2), array('/0', '/1', '/2', '/3')))) {
                        return '7';
                    }
                    else if (('C80.9' == $data['lokalisation']) &&
                             ('/3' == substr($data['morphologie'], -2))) {
                        return '6';
                    }
                }
            }
            if (isset($data['zyto_count']) && ($data['zyto_count'] > 0)) {
                return '5';
            }
        }
        return '9';
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getDiagnoseanlass($data)
    {
        $result = '';
        $anamnese = HReports::GetMinElementByDate($data['anamnesen'], 3, null);
        if (false !== $anamnese) {
            $result = $this->_getLBasicCode('entdeckung', $anamnese['entdeckung']);
        }
        if (0 == strlen($result)) {
            return 'U';
        }
        return $result;
    }

    /**
     *
     *
     * @access
     * @param $morphologie
     * @return string
     */
    protected function _getDignitaet($morphologie)
    {
        if ((strlen($morphologie) > 0) &&
            (false !== strpos($morphologie, '/'))) {
            return substr($morphologie, -1);
        }
        return '';
    }


    /**
     * Get Diagnose-Datum
     *
     * @access
     * @param $record
     * @param $investigations
     * @return bool|null|string
     */
    public function _getDiagnoseDatum($record, $investigations)
    {
        $val = null;
        if (count($investigations) > 0) {
            $op = $this->_didaMapInvestigation($record['dida_eingriff']);
            $in = $this->_didaMapInvestigation($record['dida_untersuchung']);
            foreach ($investigations as $investigation) {
                $type = $investigation['type'];
                if ($type == 'zyto') {
                    if (array_key_exists($investigation['eingriff_id'], $op) === true) {
                        $val = $op[$investigation['eingriff_id']];
                        break;
                    }
                } else {
                    if (strlen($investigation['eingriff_id']) > 0 &&
                        array_key_exists($investigation['eingriff_id'], $op) === true) {
                        $val = $op[$investigation['eingriff_id']];
                        break;
                    } else if (array_key_exists($investigation['untersuchung_id'], $in) === true) {
                        $val = $in[$investigation['untersuchung_id']];
                        break;
                    }
                }
            }
        }
        if ($val === null) {
            $val = $record['min_tumorstate'];
        }
        return $val;
    }


    /**
     * map investigatiosn
     *
     * id|date,id|date,id|date
     *
     * @access
     * @param $field
     * @return void
     */
    public function _didaMapInvestigation($field)
    {
        $map = array();
        if (strlen($field) > 0) {
            $records = explode(',', $field);
            foreach ($records as $record) {
                $parts = explode('|', $record);
                $map[$parts[0]] = $parts[1];
            }
        }
        return $map;
    }

    /**
     * Get lokalisation text
     *
     * @access  protected
     * @param   $record
     * @param   $map
     * @return  void
     */
    protected function _getLokalisationText($record, $map)
    {
        $val = null;

        if (strlen($record['lokalisation']) > 0) {
            $val = $record['lokalisation_text'];
        } else {
            $localisation = self::map($record['diagnose'], $map);
            if ($localisation !== null) {
                $val = $localisation['text'];
            }
        }

        return $val;
    }


    /**
     * Get lokalisation
     *
     * @access  protected
     * @param   $record
     * @param   $map
     * @return  void
     */
    protected function _getLokalisation($record, $map)
    {
        $val = null;
        if (strlen($record['lokalisation']) > 0) {
            $val = $record['lokalisation'];
        } else {
            $localisation = self::map($record['diagnose'], $map);
            if ($localisation !== null) {
                $val = $localisation['code'];
            }
        }
        return $val;
    }


    /**
     *
     *
     * @access
     * @param      $value
     * @param      $map
     * @param null $default
     * @return mixed
     */
    protected function map($value, $map, $default = null)
    {
        return (array_key_exists($value, $map) === true ? $map[$value] : ($default !== null ? $default : $value));
    }


    /**
     *
     *
     * @access
     * @param $hausnr
     * @return string
     */
    protected function _getNumericContent($value)
    {
        if (preg_match('/[0-9]+/', $value, $result, PREG_OFFSET_CAPTURE)) {
            if (isset($result[0][0]) &&
                (strlen($result[0][0]) > 0)) {
                return $result[0][0];
            }
        }
        return '';
    }


    /**
     *
     *
     * @access
     * @param $hausnr
     * @return string
     */
    protected function _getNonNumericContent($value)
    {
        if (preg_match('/[a-zA-Z]+/', $value, $result, PREG_OFFSET_CAPTURE)) {
            if (isset($result[0][0]) &&
                (strlen($result[0][0]) > 0)) {
                return $result[0][0];
            }
        }
        return '';
    }


    /**
     *
     */
    protected function _getKrMeldungsText($krMeldungen)
    {
        if (is_array($krMeldungen)) {
            $krMeldung = HReports::GetMaxElementByDate($krMeldungen, 7, null);
            if (false !== $krMeldung) {
                return $krMeldung['bem'];
            }
        }
        return "";
    }


    /**
     *
     *
     * @access
     * @return void
     */
    protected function _readDiagnoseSicherungCodes()
    {
        $query = "
            SELECT
                code,
                code_gkr

            FROM
                l_basic

            WHERE
                klasse = 'diagnosesicherung'
        ";
        $result = sql_query_array($this->m_db, $query);
        foreach ($result as $row) {
            $this->_diagnoseSicherungCodes[$row['code']] = $row['code_gkr'];
        }
    }


    /**
     *
     *
     * @access
     * @param $code
     * @return mixed
     */
    protected function _encodeHdsich($code)
    {
        if (isset($this->_diagnoseSicherungCodes[$code])) {
            return $this->_diagnoseSicherungCodes[$code];
        }
        return $code;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return string
     */
    protected function _getTodesursache($data)
    {
        $result = '';
        if (strlen($data['tod_ursache']) > 0) {
            $result = $data['tod_ursache'];
        }
        if (strlen($data['tod_ursache_text']) > 0) {
            if (strlen($result) > 0) {
                $result .= ': ';
            }
            $result .= $data['tod_ursache_text'];
        }
        return $result;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @return void
     */
    protected function _getMeldebegruendung($krMeldung)
    {
        if (count($krMeldung) > 0) {
            return $krMeldung['meldebegruendung'];
        }
        return '';
    }

    /**
     *
     *
     * @access
     * @param $mapping
     * @return $this
     */
    protected function _addMapping($mapping)
    {
        $map = is_array($mapping) === true ? $mapping : getLookup($this->m_db, $mapping);
        $this->_mappings[$mapping] = $map;
        return $this;
    }


    /**
     *
     *
     * @access
     * @param $mapping
     * @return array
     */
    protected function _getMapping($mapping)
    {
        return (array_key_exists($mapping, $this->_mappings) === true ? $this->_mappings[$mapping] : array());
    }


    /**
     *
     *
     * @access
     * @param $orgId
     * @return $this
     */
    protected function _setRequiredOrg($orgId)
    {
        $this->_org = array(
            'name'    => '',
            'plz'     => '',
            'ort'     => '',
            'strasse' => ''
        );
        if (strlen($orgId) > 0) {
            $query = "
                SELECT
                    name,
                    plz,
                    ort,
                    CONCAT_WS(' ', strasse, hausnr) AS 'strasse',
                    bundesland

                FROM
                    org

                WHERE
                    org_id = '{$orgId}'
            ";
            $result = sql_query_array($this->m_db, $query);
            if (count($result) === 1) {
                $this->_org = reset($result);
            }
        }
        return $this;
    }


    /**
     * returns export org
     *
     * @access protected
     * @return null|array
     */
    protected function _getOrg()
    {
        return $this->_org;
    }


    /**
     * find all required ekr user
     *
     * @access
     * @param CExportWrapper $wrapper
     * @return $this
     */
    protected function _setRequiredEkrUserData(CExportWrapper $wrapper)
    {
        $query = "
            SELECT
                u.user_id,
                CONCAT_WS(' ', u.titel, u.vorname, u.nachname) as 'fullname',
                u.kr_kennung,
                u.org,
                CONCAT_WS(' ', u.strasse, u.hausnr) AS 'strasse',
                u.plz,
                u.ort

            FROM
                user u
        ";
        foreach (sql_query_array($this->m_db, $query) as $user) {
            $this->_user[$user['user_id']] = $user;
        }
        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function _setDiagnoseLocalistions()
    {
        $query = "SELECT * FROM l_exp_diagnose_to_lokalisation";
        $mapping = array();
        foreach (sql_query_array($this->m_db, $query) as $record) {
            $mapping[$record['diagnose_code']] = array(
                'code' => $record['lokalisation_code'],
                'text' => $record['lokalisation_text']
            );
        }
        $this->_mappings['l_exp_diagnose'] = $mapping;
        return $this;
    }


    /**
     *
     *
     * @access
     * @param $id
     * @return null
     */
    protected function _getUser($id)
    {
        $result = array(
            'user_id'    => 0,
            'fullname'   => '',
            'kr_kennung' => '',
            'org'        => '',
            'strasse'    => '',
            'plz'        => '',
            'ort'        => ''
        );
        if (array_key_exists($id, $this->_user) === true) {
            return $this->_user[$id];
        }
        return $result;
    }


    /**
     * build diagnose filter for given field
     *
     * @access  private
     * @param   string $field
     * @return  void
     */
    private function _buildDiagnoseFilter($field = 'diagnose')
    {
        $filter = array();
        foreach ($this->_diagnoseFilter as $diagnose) {
            $filter[] = "{$field} LIKE '{$diagnose}%'";
        }
        return implode(' OR ', $filter);
    }

}

?>
