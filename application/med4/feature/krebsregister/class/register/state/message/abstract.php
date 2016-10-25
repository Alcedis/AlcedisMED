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

require_once 'interface.php';

/**
 * Class registerStateMessageAbstract
 */
abstract class registerStateMessageAbstract implements registerStateMessageInterface
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType;


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName;


    /**
     * _state
     *
     * @access  protected
     * @var     registerStateInterface
     */
    protected $_state;


    /**
     * cache
     *
     * @access  protected
     * @var     array
     */
    protected static $_cache = array(
        'items' => array(),
        'conference' => array()
    );


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _ignoreOnDiff
     *
     * @access  protected
     * @var     array
     */
    protected $_ignoreOnDiff = array();


    /**
     * _mandatories
     *
     * @access  protected
     * @var     array
     */
    protected $_mandatories = array();


    /**
     * registerStateMessageAbstract constructor.
     *
     * @param resource $db
     */
    public function __construct($db)
    {
        $this->setDb($db);

        $this->_initialize();
    }


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    {
        $this
            ->addIgnoreOnDiff('export', 'loadHistory')
            ->addIgnoreOnDiff('export', 'validatable')
            ->addIgnoreOnDiff('export', 'exportable')
            ->addIgnoreOnDiff('message', 'meldedatum')
            ->addIgnoreOnDiff('menge_zusatzitem')
            ->addIgnoreOnDiff('menge_tumorkonferenz')
        ;

        // all fields are mandatory error
        $this
            ->addMandatory('message', 'meldender_arzt meldedatum meldebegruendung meldeanlass')
            ->addMandatory('tumorzuordnung', 'diagnosedatum primaertumor_icd_code')
            ->addMandatory('patient', array(
                'krankenversichertennr',
                'nachname',
                'vorname',
                'geschlecht',
                'geburtsdatum'
            ))
            ->addMandatory('patient',
                array(
                    'strasse',
                    'hausnummer',
                    'land',
                    'plz',
                    'ort'
                ),
                self::MANDATORY_WARNING
            )
            ->addMandatory('menge_zusatzitem', '*/wert', self::MANDATORY_WARNING)
            ->addMandatory('menge_tumorkonferenz', array(
                '*/tumorkonferenz_datum',
                '*/tumorkonferenz_typ'
            ), self::MANDATORY_WARNING)
        ;
    }


    /**
     * addMandatory
     *
     * @access  public
     * @param   string       $section
     * @param   array|string $fieldNames
     * @param   int          $mandatoryType
     * @param   callable     $onCondition
     * @param   callable     $fieldCondition
     * @param   array        $mandatoryValues
     * @return  registerStateMessageInterface
     */
    public function addMandatory(
        $section,
        $fieldNames,
        $mandatoryType = self::MANDATORY_ERROR,
        callable $onCondition = null,
        callable $fieldCondition = null,
        array $mandatoryValues = array()
    ) {
        if (is_array($fieldNames) === false) {
            $fieldNames = explode(' ', $fieldNames);
            $fieldNames = array_filter($fieldNames);
        }

        foreach ($fieldNames as $fieldName) {
            $this->_mandatories[$mandatoryType][$section][] = array(
                'field'           => $fieldName,
                'condition'       => $onCondition,
                'fieldCondition'  => $fieldCondition,
                'mandatoryValues' => $mandatoryValues
            );
        }

        return $this;
    }


    /**
     * getMandatories
     *
     * @access  public
     * @return  array
     */
    public function getMandatories()
    {
        return $this->_mandatories;
    }


    /**
     * getSectionName
     *
     * @access  public
     * @return  string
     */
    public function getSectionName()
    {
        return $this->_sectionName;
    }


    /**
     * getMessageType
     *
     * @access  public
     * @return  string
     */
    public function getMessageType()
    {
        return $this->_messageType;
    }


    /**
     * setState
     *
     * @access  public
     * @param   registerStateInterface $state
     * @return  $this
     */
    public function setState(registerStateInterface $state)
    {
        $this->_state = $state;

        return $this;
    }


    /**
     * getState
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function getState()
    {
        return $this->_state;
    }


    /**
     * _getUniqueMessageId
     *
     * @access  protected
     * @param   registerPatientCase $patientCase
     * @return  string
     */
    protected function _getUniqueMessageId(registerPatientCase $patientCase)
    {
        $diseaseId = $patientCase->getData('erkrankung_id');
        $side      = $this->_buildSide($patientCase->getPrimaryCase());

        return $diseaseId . '-' . $side;
    }


    /**
     * _buildSide
     *
     * @access  protected
     * @param   registerPatientCase $case
     * @return  string
     */
    protected function _buildSide(registerPatientCase $case)
    {
        $disease = $case->getData('erkrankung');

        $side = in_array($disease, array('leu', 'ly', 'snst')) === true ? 'T' : (
            strlen($case->getData('diagnose_seite')) > 0
                ? $case->getData('diagnose_seite')
                : $case->getData('lokalisation_seite')
            )
        ;

        return (strlen($side) === 0 || $side === '-') ?  'U' : $side;
    }


    /**
     * _buildExportSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase $patientCase
     * @param   bool $loadHistory
     * @param   bool $validatable
     * @param   bool $exportable
     * @return  void
     * @throws  Exception
     */
    protected function _buildExportSection(
        registerPatientMessage $message,
        registerPatientCase $patientCase,
        $loadHistory = true,
        $validatable = true,
        $exportable = true
    )
    {
        $messagePart = array(
            'patient_id'     => $patientCase->getData('patient_id'),
            'erkrankung_id'  => $patientCase->getData('erkrankung_id'),
            'erkrankung'     => $patientCase->getData('erkrankung'),
            'diagnose_seite' => $patientCase->getData('diagnose_seite'),
            'meldender_arzt' => $patientCase->getData('meldender_arzt'),
            'loadHistory'    => $loadHistory,
            'validatable'    => $validatable,
            'exportable'     => $exportable
        );

        $message->addSection('export', $messagePart);
    }


    /**
     * _buildMessageSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase $patientCase
     * @param   string $type
     * @param   string $messageUniqueId
     * @return  void
     * @throws  Exception
     */
    protected function _buildMessageSection(
        registerPatientMessage $message,
        registerPatientCase $patientCase,
        $type,
        $messageUniqueId = null
    ) {
        $state       = $this->getState();
        $primaryCase = $patientCase->getPrimaryCase();
        $messengerId = $primaryCase->getData('meldender_arzt');

        // add a messenger for this message
        $state->getMessenger()->add($messengerId, $message);

        // build default message unique id
        if ($messageUniqueId === null) {
            $diseaseId = $primaryCase->getData('erkrankung_id');
            $side      = $primaryCase->getData('diagnose_seite');

            $messageUniqueId = $type . '_' . $diseaseId . '_' . $side;
        }

        // set default message vars
        $messagePart = array(
            'id'               => 'MSG_'. strtoupper($messageUniqueId),
            'meldender_arzt'   => $messengerId,
            'meldedatum'       => todate(date('Y-m-d'), 'de'),
            'meldebegruendung' => $primaryCase->getData('ekr_mbg'),
            'meldeanlass'      => $type
        );

        $message->addSection('message', $messagePart);
    }


    /**
     * _buildTumorSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase $patientCase
     * @return  void
     */
    protected function _buildTumorSection(registerPatientMessage $message, registerPatientCase $patientCase)
    {
        $primaryCase = $patientCase->getPrimaryCase();

        $messagePart = array(
            'id'                    => 'TUM_' . $this->_getUniqueMessageId($patientCase),
            'primaertumor_icd_code' => $primaryCase->getData('diagnose'),
            'diagnosedatum'         => $this->_buildDiagnosisDate($primaryCase),
            'seitenlokalisation'    => $this->_buildSide($primaryCase)
        );

        $message->addSection('tumorzuordnung', $messagePart);
    }


    /**
     * _buildDiagnosisDate
     *
     * @access  protected
     * @param   registerPatientCase $case
     * @return  string
     */
    protected function _buildDiagnosisDate(registerPatientCase $case)
    {
        $diagnosisDate = array(
            $case->getData('date_histology'),
            $case->getData('date_zytology'),
            $case->getData('date_tumorstate'))
        ;

        $diagnosisDate = array_filter($diagnosisDate);

        sort($diagnosisDate);

        return todate(array_shift($diagnosisDate), 'de');
    }


    /**
     * _buildInterventionResidualStatus
     *
     * @access  protected
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildResidualStatus(registerPatientCase $case)
    {
        $status  = null;
        $local   = null;
        $overall = null;

        $primaryCase = $case->getPrimaryCase();

        $tsRLocal = $primaryCase->getData('r_lokal');
        $tsR      = $primaryCase->getData('r');

        if (strlen($tsRLocal) > 0) {
            $local = 'R' . $tsRLocal;
        }

        if (strlen($tsR) > 0) {
            $overall = 'R' . $tsR;
        }

        if ($local !== null || $overall !== null) {
            $status = array(
                'lokale_beurteilung_residualstatus' => $local,
                'gesamtbeurteilung_residualstatus'  => $overall
            );
        }

        return $status;
    }


    /**
     * _buildHistologies
     *
     * @access  protected
     * @param   array $records
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildHistologies(array $records, registerPatientCase $case)
    {
        $histologies = array();

        foreach ($records as $record) {
            // take morpho code from histology, if empty from newest tumorstatus
            if (strlen($record['morphologie']) > 0) {
                $mCode    = $record['morphologie'];
                $mVersion = $record['morphologie_version'];
                $mText    = $record['morphologie_text'];
            } else {
                $mCode    = $case->getData('morphologie');
                $mVersion = $case->getData('morphologie_version');
                $mText    = $case->getData('morphologie_text');
            }

            $lymphInfest = null;

            if ($case->getData('erkrankung') === 'b' || $case->getData('erkrankung') === 'p') {

                // check if fields filled, cause null + null = 0
                if ($record['lk_bef_makro'] !== null || $record['lk_bef_mikro'] !== null) {
                    $lymphInfest = $record['lk_bef_makro'] + $record['lk_bef_mikro'];
                }
            } else {
                $lymphInfest = $record['lk_bef'];
            }

            $data = array(
                'id'                        => 'HIS_' . $record['histologie_id'],
                'tumor_histologiedatum'     => todate($record['datum'], 'de'),
                'histologie_einsendenr'     => $record['histologie_nr'],
                'morphologie_code'          => $mCode,
                'morphologie_icd_o_version' => $mVersion,
                'morphologie_freitext'      => $mText,
                'grading'                   => registerHelper::ifNull($record['g'], $case->getData('grading')),
                'lk_untersucht'             => registerHelper::ifNull($record['lk_entf'], $case->getData('lk_entf')),
                'lk_befallen'               => registerHelper::ifNull($lymphInfest, $case->getData('lk_bef')),
                'sentinel_lk_untersucht'    => $record['lk_sentinel_entf'],
                'sentinel_lk_befallen'      => $record['lk_sentinel_bef']
            );

            $histologies[] = $data;
        }

        return $histologies;
    }


    /**
     * _buildTnm
     *
     * @access  protected
     * @param   array   $records
     * @param   bool    $fromTs
     * @return  array
     */
    protected function _buildTnm(array $records, $fromTs = true)
    {
        $tnm = array();
        $prefix = null;

        if ($fromTs === false) {
            $prefix = 'p';
        }

        foreach ($records as $record) {
            $tnmPrefix = $record[$prefix . 'tnm_praefix'];
            $t      = $record[$prefix . 't'];
            $n      = $record[$prefix . 'n'];
            $m      = $record[$prefix . 'm'];
            $l      = $record['l'];
            $v      = $record['v'];
            $ppn    = $record['ppn'];
            $s      = ($fromTs === true ? $record['s'] : null); // field doesn't exists in histology

            $id = $fromTs === true ? $record['tumorstatus_id'] : $record['histologie_id'];

            $filled = array(
                'tnm_y_symbol'        => (in_array($tnmPrefix, array('y', 'yr')) === true ? 'y' : null),
                'tnm_r_symbol'        => (in_array($tnmPrefix, array('yr', 'r')) === true ? 'r' : null),
                'tnm_a_symbol'        => ($tnmPrefix === 'a' ? 'a' : null),
                'tnm_c_p_u_praefix_t' => (strlen($t) > 0 ? substr($t, 0, 1) : null),
                'tnm_t'               => (strlen($t) > 0 ? substr($t, 1) : null),
                'tnm_m_symbol'        => ($record['multizentrisch'] === '1' || $record['multifokal'] === '1') ? 'm' : null,
                'tnm_c_p_u_praefix_n' => (strlen($n) > 0 ? substr($n, 0, 1) : null),
                'tnm_n'               => (strlen($n) > 0 ? substr($n, 1) : null),
                'tnm_c_p_u_praefix_m' => (strlen($m) > 0 ? substr($m, 0, 1) : null),
                'tnm_m'               => (strlen($m) > 0 ? substr($m, 1) : null),
                'tnm_l'               => (strlen($l) > 0 ? 'L' . $l : null),
                'tnm_v'               => (strlen($v) > 0 ? 'V' . $v : null),
                'tnm_pn'              => (strlen($ppn) > 0 ? 'Pn' . $ppn : null),
                'tnm_s'               => (strlen($s) > 0 ? strtoupper($s) : null)
            );

            $date = ($fromTs === true ? $record['datum_sicherung'] : $record['datum']);

            $tnm[] = array_merge(
                array(
                    'id'          => 'TNM_' . ($fromTs === null ? 'TS' : 'H') . '_' . $id,
                    'tnm_datum'   => todate($date, 'de'),
                    'tnm_version' => '7',
                ),
                $filled
            );
        }

        return $tnm;
    }


    /**
     * _buildEcog
     *
     * @access  protected
     * @param   array $records
     * @return  string
     */
    protected function _buildEcog(array $records)
    {
        $ecog = 'U';

        if (count($records) > 0) {
            $firstAnamnesis = end($records);
            $ecogValue = $firstAnamnesis['ecog'];

            // only fill ecog if value is not empty
            if (strlen($ecogValue) > 0) {
                $ecog = $ecogValue;
            }
        }

        return $ecog;
    }


    /**
     * _buildMetastases
     *
     * @access  protected
     * @param   registerPatientMessage  $message
     * @param   registerPatientCase     $patientCase
     * @param   string                  $section
     * @return  array
     */
    protected function _buildMetastases(registerPatientMessage $message, registerPatientCase $patientCase, $section)
    {
        $unique = array();

        // fill unique array based on history message if exists
        if ($message->hasHistory() === true) {
            $history = $message->getHistory();

            $historySectionData = $history->getSection($section)->getData();

            $unique = $historySectionData['menge_fm_unique'];
        }

        $metastases  = array(
            'unique' => $unique,
            'metastases' => array()
        );

        foreach ($patientCase->getData('tumorstatus') as $record) {
            $date = todate($record['datum_sicherung'], 'de');

            foreach ($record['tumorstatus_metastasen'] as $tm) {
                $localization = $tm['lokalisation'];
                $ident        = strtolower(str_replace('.', '_', $localization));

                $mappedLocalization = $this->_mapLocalisation($localization);

                if ($mappedLocalization !== null && in_array($ident, $metastases['unique']) === false) {
                    $metastases['unique'][] = $ident;

                    $metastases['metastases'][$ident] = array(
                        'fm_diagnosedatum' => $date,
                        'fm_lokalisation'  => $mappedLocalization
                    );
                }
            }
        }

        return $metastases;
    }


    /**
     * _mapLocalisation
     *
     * @access  protected
     * @param   string  $localisation
     * @return  string
     */
    protected function _mapLocalisation($localisation)
    {
        $value = null;

        switch (true) {
            case str_starts_with($localisation, 'C34'):
                $value = 'PUL';
                break;
            case str_starts_with($localisation, array('C40', 'C41')):
                $value = 'OSS';
                break;
            case str_starts_with($localisation, 'C22'):
                $value = 'HEP';
                break;
            case str_starts_with($localisation, 'C71'):
                $value = 'BRA';
                break;
            case str_starts_with($localisation, 'C77'):
                $value = 'LYM';
                break;
            case $localisation === 'C42.1':
                $value = 'MAR';
                break;
            case $localisation === 'C38.4':
                $value = 'PLE';
                break;
            case in_array($localisation, array('C48.1', 'C48.2')):
                $value = 'PER';
                break;
            case str_starts_with($localisation, 'C74'):
                $value = 'ADR';
                break;
            case str_starts_with($localisation, 'C44'):
                $value = 'SKI';
                break;
            case (str_starts_with($localisation, 'C') === true && substr($localisation, 1) <= 80.9):
                $value = 'OTH';
                break;
        }

        return $value;
    }


    /**
     * _buildAdditionalClassification
     *
     * @access  protected
     * @param   array $records
     * @return  array
     */
    protected function _buildAdditionalClassification(array $records)
    {
        $ac       = array();
        $state    = $this->getState();
        $acFields = $state->getAdditionalClassificationFields();

        foreach ($records as $record) {
            foreach ($acFields as $field) {
                $value = $record[$field];

                if (strlen($value) > 0) {
                    $ac[] = array(
                        'datum'   => todate($record['datum_sicherung'], 'de'),
                        'name'    => $state->getConfig($field, 'tumorstatus'),
                        'stadium' => $state->map($field, $value)
                    );
                }
            }
        }

        return $ac;
    }


    /**
     * addIgnoreOnDiff
     *
     * @access  public
     * @param   string $section
     * @param   string $field
     * @return  registerStateMessageInterface
     */
    public function addIgnoreOnDiff($section, $field = null)
    {
        if ($field !== null) {
            $this->_ignoreOnDiff[$section][] = $field;
        } else {
            $this->_ignoreOnDiff[$section] = true;
        }

        return $this;
    }


    /**
     * getIgnoreOnDiff
     *
     * @access  public
     * @return  array
     */
    public function getIgnoreOnDiff()
    {
        return $this->_ignoreOnDiff;
    }


    /**
     * setDb
     *
     * @access  public
     * @param   resource $db
     * @return  registerStateMessageInterface
     */
    public function setDb($db)
    {
        $this->_db = $db;

        return $this;
    }


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb()
    {
        return $this->_db;
    }
}
