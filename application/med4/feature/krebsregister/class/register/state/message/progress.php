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

/**
 * Class registerStateMessageProgress
 */
class registerStateMessageProgress extends registerStateMessageAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'progress';


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName = 'menge_verlauf';


    /**
     * raw progress structure
     *
     * @access  protected
     * @var     array
     */
    protected $_rawStructure = array(
        'id'                                 => null,
        'histologie'                         => null,
        'menge_tnm'                          => array(),
        'menge_weitere_klassifikation'       => array(),
        'untersuchungsdatum_verlauf'         => null,
        'gesamtbeurteilung_tumorstatus'      => null,
        'verlauf_lokaler_tumorstatus'        => null,
        'verlauf_tumorstatus_lymphknoten'    => null,
        'verlauf_tumorstatus_fernmetastasen' => null,
        'menge_fm_unique'                    => array(),
        'menge_fm'                           => array(),
        'allgemeiner_leistungszustand'       => null,
        'tod'                                => null,
        'anmerkung'                          => null
    );


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    {
        parent::_initialize();

        $sectionName = $this->getSectionName();

        // ignore "menge_fm" on diff check; this will be manually done by unique field build
        $this->addIgnoreOnDiff($sectionName, 'menge_fm');

        $this
            ->addMandatory($sectionName, 'untersuchungsdatum_verlauf', self::MANDATORY_WARNING)
            ->addMandatory(
                $sectionName,
                'gesamtbeurteilung_tumorstatus',
                self::MANDATORY_WARNING,
                function (registerPatientMessage $message, $sectionData) {
                    $condition = (
                        strlen($sectionData['verlauf_lokaler_tumorstatus']) === 0 &&
                        strlen($sectionData['verlauf_tumorstatus_lymphknoten']) === 0 &&
                        strlen($sectionData['verlauf_tumorstatus_fernmetastasen']) === 0
                    );

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                array(
                    'verlauf_lokaler_tumorstatus',
                    'verlauf_tumorstatus_lymphknoten',
                    'verlauf_tumorstatus_fernmetastasen'
                ),
                self::MANDATORY_WARNING,
                function (registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['gesamtbeurteilung_tumorstatus']) > 0);
                }
            )
            ->addMandatory(
                $sectionName,
                'histologie/lk_befallen',
                self::MANDATORY_WARNING,
                function (registerPatientMessage $message, $sectionData) {
                    $condition = false;

                    if ($sectionData['histologie'] !== null) {
                        $condition = strlen($sectionData['histologie']['lk_untersucht']) > 0;
                    }

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                'histologie/sentinel_lk_befallen',
                self::MANDATORY_WARNING,
                function (registerPatientMessage $message, $sectionData) {
                    $condition = false;

                    if ($sectionData['histologie'] !== null) {
                        $condition = strlen($sectionData['histologie']['sentinel_lk_untersucht']) > 0;
                    }

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                'menge_weitere_klassifikation/*/stadium',
                self::MANDATORY_WARNING,
                null,
                function (registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['name']) > 0);
                }
            )
        ;
    }

    /**
     * build progress messages
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  void
     */
    public function buildMessages(registerPatient $patient, $withHistory = true)
    {
        $relapses = $this->_buildRelapseProgressMessages($patient, $withHistory);

        $this->_buildAfterTreatmentProgressMessages($patient, $relapses, $withHistory);
    }


    /**
     * build progress messages from relapse cases
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  registerPatientCase[]
     * @throws  Exception
     */
    protected function _buildRelapseProgressMessages(registerPatient $patient, $withHistory = true)
    {
        $cases = $patient->getCases();

        /* @var registerPatientCase[] $relapses */
        $relapses = array();

        // check each case if it is a relapse case and has a valid primary case
        foreach ($cases as $case) {
            if (str_starts_with($case->getData('anlass'), 'r') === true && $case->hasPrimaryCase() === true) {
                $relapses[] = $case;
            }
        }

        // process each relapse and generate message
        foreach ($relapses as $case) {
            // identification for this message
            $ident = implode('_', array(
                $this->getMessageType(),
                $case->getData('erkrankung_id'),
                'RL',
                $case->getData('anlass'),
                $case->getData('diagnose_seite')
            ));

            $message = registerPatientMessage::create()
                ->initExportCase(array(
                    'patient_id'     => $case->getData('patient_id'),
                    'erkrankung_id'  => $case->getData('erkrankung_id'),
                    'diagnose_seite' => $case->getData('diagnose_seite'),
                    'anlass'         => $ident
                ))
                ->setIgnoreOnDiff($this->getIgnoreOnDiff())
                ->setParams($patient->getParams())
            ;

            if ($withHistory === true) {
                $message->loadHistory($this->getDb(), $ident);
            }

            $this->_buildRelapseProgressSection($message, $case, $relapses);

            // add message to patient if progress section could be built
            if ($message->hasSection($this->getSectionName()) === true) {
                $this->_buildExportSection($message, $case, $withHistory);
                $this->_buildMessageSection($message, $case, 'statusaenderung', $ident);
                $this->_buildTumorSection($message, $case);

                $message->setMandatories($this->getMandatories());

                $patient->addMessage($message);
            }

        }

        return $relapses;
    }


    /**
     * build progress messages from after treatment forms
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   registerPatientCase[] $patientRelapses
     * @param   bool $withHistory
     * @return  void
     * @throws  Exception
     */
    protected function _buildAfterTreatmentProgressMessages(
        registerPatient $patient,
        array $patientRelapses,
        $withHistory = true
    ) {
        $cases = $patient->getCases();

        // process each primaryCase
        foreach ($cases as $case) {
            if ($case->isPrimaryCase() === true) {
                $afterTreatments = $case->getData('nachsorge');

                // if case inherits after treatments
                if (count($afterTreatments) > 0) {
                    $diseaseId = $case->getData('erkrankung_id');
                    $relapses  = array();

                    // find all patient relapses that are related to this disease
                    foreach ($patientRelapses as $relapse) {
                        if ($relapse->getData('erkrankung_id') === $diseaseId) {
                            $relapses[] = $relapse;
                        }
                    }

                    // AfterTreatments are stored in primary case
                    foreach ($afterTreatments as $afterTreatment) {

                        // identification for this message
                        $ident = implode('_', array(
                            $this->getMessageType(),
                            $case->getData('erkrankung_id'),
                            'AT',
                            $case->getData('diagnose_seite'),
                            $afterTreatment['nachsorge_id']
                        ));

                        $message = registerPatientMessage::create()
                            ->initExportCase(array(
                                'patient_id'     => $case->getData('patient_id'),
                                'erkrankung_id'  => $case->getData('erkrankung_id'),
                                'diagnose_seite' => $case->getData('diagnose_seite'),
                                'anlass'         => $ident
                            ))
                            ->setIgnoreOnDiff($this->getIgnoreOnDiff())
                            ->setParams($patient->getParams())
                        ;

                        if ($withHistory === true) {
                            $message->loadHistory($this->getDb(), $ident);
                        }

                        $this->_buildAfterTreatmentProgressSection($afterTreatment, $message, $case, $relapses);

                        // add message to patient if progress section could be built
                        if ($message->hasSection($this->getSectionName()) === true) {
                            $this->_buildExportSection($message, $case, $withHistory);
                            $this->_buildMessageSection($message, $case, 'statusaenderung', $ident);
                            $this->_buildTumorSection($message, $case);

                            $message->setMandatories($this->getMandatories());

                            $patient->addMessage($message);
                        }
                    }
                }
            }
        }
    }


    /**
     * _buildRelapseProgressSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @param   registerPatientCase[]  $relapses
     * @return  void
     */
    protected function _buildRelapseProgressSection(registerPatientMessage $message, registerPatientCase $case, array $relapses)
    {
        $progress      = $this->getRawStructure();
        $statusMaxDate = $case->getData('end_date');
        $metastases    = $this->_buildMetastases($message, $case, $this->getSectionName());

        $progress['id'] = 'PRO_REL_' . $this->_getUniqueMessageId($case) . '_' . strtoupper($case->getData('anlass'));
        $progress['gesamtbeurteilung_tumorstatus'] = 'P';

        $primaryCaseTs = $case->getPrimaryCase()->getData('tumorstatus');

        $progress['verlauf_lokaler_tumorstatus'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_lokal',
                'latestTsField'      => 'r_lokal',
                'latestTsValues'     => array('1', '2')
            ),
            $primaryCaseTs,
            false
        );

        $progress['verlauf_tumorstatus_lymphknoten'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_lk',
                'latestTsField'      => 'n',
                'latestTsValues'     => array('1', '2', '3')
            ),
            $primaryCaseTs,
            false
        );

        $progress['verlauf_tumorstatus_fernmetastasen'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_metastasen',
                'latestTsField'      => 'm',
                'latestTsValues'     => array('1')
            ),
            $primaryCaseTs,
            false
        );

        $progress['histologie']                    = $this->_buildProgressHistology($case->getData('histologie'), $case);
        $progress['menge_tnm']                     = $this->_buildTnm($case->getData('tumorstatus'));
        $progress['untersuchungsdatum_verlauf']    = todate($case->getData('start_date'), 'de');
        $progress['allgemeiner_leistungszustand']  = $this->_buildEcog($case->getData('anamnese'));
        $progress['menge_fm_unique']               = $metastases['unique']; // for diff
        $progress['menge_fm']                      = $metastases['metastases'];
        $progress['menge_weitere_klassifikation']  = $this->_buildAdditionalClassification($case->getData('tumorstatus'));
        $progress['anmerkung']                     = $case->getData('bem');

        $message->addSection($this->getSectionName(), $progress);
    }


    /**
     * _buildAfterTreatmentProgressSection
     *
     * @access  protected
     * @param   array                  $record
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @param   registerPatientCase[]  $relapses
     * @return  void
     * @throws Exception
     */
    protected function _buildAfterTreatmentProgressSection(array $record, registerPatientMessage $message, registerPatientCase $case, array $relapses)
    {
        $state         = $this->getState();
        $progress      = $this->getRawStructure();
        $statusMaxDate = $record['datum'];

        $primaryCaseTs = $case->getPrimaryCase()->getData('tumorstatus');

        $progress['id'] = 'PRO_TREAT_' . $record['nachsorge_id'];
        $progress['gesamtbeurteilung_tumorstatus'] = registerHelper::ifNull(
            $state->map('response', $record['response_klinisch']),
            'X'
        );

        $progress['verlauf_lokaler_tumorstatus'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_lokal',
                'latestTsField'      => 'r_lokal',
                'latestTsValues'     => array('1', '2')
            ),
            $primaryCaseTs
        );

        $progress['verlauf_tumorstatus_lymphknoten'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_lk',
                'latestTsField'      => 'n',
                'latestTsValues'     => array('1', '2', '3')
            ),
            $primaryCaseTs
        );

        $progress['verlauf_tumorstatus_fernmetastasen'] = $this->_buildProgressStatus(
            $statusMaxDate,
            $relapses,
            array(
                'prevRelapseTsField' => 'rezidiv_metastasen',
                'latestTsField'      => 'm',
                'latestTsValues'     => array('1')
            ),
            $primaryCaseTs
        );

        $progress['untersuchungsdatum_verlauf'] = todate($record['datum'], 'de');
        $progress['anmerkung'] = $record['bem'];

        $message->addSection($this->getSectionName(), $progress);
    }


    /**
     * _buildProgressStatus
     *
     * @access  protected
     * @param   string                $date
     * @param   registerPatientCase[] $relapses
     * @param   array                 $check
     * @param   array                 $primaryCaseTs
     * @param   bool                  $fromAfterTreatment
     * @return  string
     * @throws  Exception
     */
    protected function _buildProgressStatus($date, array $relapses, array $check, $primaryCaseTs, $fromAfterTreatment = true)
    {
        $status = null;

        $prevRelapseTsWithCondition = array();

        // filter all possible relapse ts for correct timeline
        foreach ($relapses as $case) {
            foreach ($case->getData('tumorstatus') as $record) {

                // must be relapse and date must be before current form date
                if (str_starts_with($record['anlass'], 'r') === true && $record['datum_sicherung'] <= $date) {

                    // field (currently a checkbox) must be checked
                    if ($record[$check['prevRelapseTsField']] !== null) {
                        $prevRelapseTsWithCondition[] = $record;
                    }
                }
            }
        }

        // check primary case ts. should be there
        if (count($primaryCaseTs) > 0) {
            $latestPrimaryTs = reset($primaryCaseTs);

            // check condition
            if (str_contains($latestPrimaryTs[$check['latestTsField']], $check['latestTsValues']) === true && count($prevRelapseTsWithCondition) === 0) {
                $status = 'T';
            }

            if ($status === null && str_contains($latestPrimaryTs[$check['latestTsField']], '0') === true && count($prevRelapseTsWithCondition) === 0) {
                $status = 'K';
            }
        }

        // come from ts only and prev relapse with condition exists (this excludes the possibility to create other values in $status)
        if ($fromAfterTreatment === false && count($prevRelapseTsWithCondition) > 0) {
            $status = 'R';
        }

        return registerHelper::ifNull($status, 'X');
    }


    /**
     * _buildProgressHistology
     *
     * @access  protected
     * @param   array $records
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildProgressHistology(array $records, registerPatientCase $case)
    {
        $histology = null;

        // if histology records exists
        if (count($records) > 0) {
            $firstHistology = end($records);

            $lkEntf = null;
            $lkBef  = null;

            foreach ($records as $record) {
                if (strlen($record['lk_sentinel_entf']) > 0) {
                    $lkEntf += $record['lk_sentinel_entf'];
                }

                if (strlen($record['lk_sentinel_bef']) > 0) {
                    $lkBef += $record['lk_sentinel_bef'];
                }
            }

            $histology = array(
                'id'                        => 'PRO_REL_HIS_' . $this->_getUniqueMessageId($case) . '_' . strtoupper($case->getData('anlass')),
                'tumor_histologiedatum'     => todate($firstHistology['datum'], 'de'),
                'histologie_einsendenr'     => $firstHistology['histologie_nr'],
                'morphologie_code'          => $case->getData('morphologie'),
                'morphologie_icd_o_version' => $case->getData('morphologie_version'),
                'morphologie_freitext'      => $case->getData('morphologie_text'),
                'grading'                   => $case->getData('grading'),
                'lk_untersucht'             => $case->getData('lk_entf'),
                'lk_befallen'               => $case->getData('lk_bef'),
                'sentinel_lk_untersucht'    => $lkEntf,
                'sentinel_lk_befallen'      => $lkBef
            );
        }

        return $histology;
    }


    /**
     * get raw structure for progress messages
     *
     * @access  protected
     * @return  array
     */
    protected function getRawStructure()
    {
        return $this->_rawStructure;
    }
}
