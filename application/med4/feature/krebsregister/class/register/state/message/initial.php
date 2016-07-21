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
 * Class registerStateMessageInitial
 */
class registerStateMessageInitial extends registerStateMessageAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'initial';


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName = 'diagnose';


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

        // add mandatories for this message type
        $this
            ->addMandatory($sectionName, 'diagnosedatum diagnosesicherung')
            ->addMandatory(
                $sectionName,
                array(
                    // 'menge_tnm/*/tnm_version', is already filled if this block is created (always 7)
                    'menge_tnm'
                ),
                self::MANDATORY_ERROR,
                function (registerPatientMessage $message, $sectionData) {
                    $exportData = $message->getSection('export')->getData();

                    // if disease is not ly, leu and snst = one tnm state must be filled
                    $condition = in_array($exportData['erkrankung'], array('ly', 'leu', 'snst')) === false;

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                array(
                    // 'menge_tnm/*/tnm_version', is already filled if this block is created (always 7)
                    'menge_tnm/*/tnm_t',
                    'menge_tnm/*/tnm_n',
                    'menge_tnm/*/tnm_m'
                ),
                self::MANDATORY_ERROR,
                null,
                function (registerPatientMessage $message, $sectionData, $nextStep, $parentData, $field) {
                    $parentId   = $parentData['id'];
                    $section    = $message->getSection('diagnose');
                    $exportData = $message->getSection('export')->getData();
                    $condition  = false;

                    static $stack = array();

                    // we only want to throw this message block one for each section
                    if (isset($stack[$parentId][$field]) === false) {
                        // if disease is not ly, leu and snst = one tnm state must be filled
                        $diseaseCondition = in_array($exportData['erkrankung'], array('ly', 'leu', 'snst')) === false;

                        if ($diseaseCondition === true) {
                            $sectionData = $section->getData();

                            $filled = false;

                            // check if one element exists with all values filled
                            foreach ($sectionData['menge_tnm'] as $tnmData) {
                                if (strlen($tnmData['tnm_t']) > 0 &&
                                    strlen($tnmData['tnm_n']) > 0 &&
                                    strlen($tnmData['tnm_m']) > 0
                                ) {
                                    $filled = true;
                                    break;
                                }
                            }

                            $condition = $filled === false;

                            // add this field to condition stack
                            if ($condition === true) {
                                $stack[$parentId][$field] = true;
                            }
                        }
                    }

                    return $condition;
                }
            )
            ->addMandatory($sectionName,
                array(
                    'menge_histologie/*/morphologie_code',
                    'menge_histologie/*/morphologie_icd_o_version',
                    'menge_histologie/*/morphologie_freitext'
                ),
                self::MANDATORY_WARNING
            )
            ->addMandatory($sectionName,
                array(
                    'primaertumor_topographie_icd_o',
                    'primaertumor_topographie_icd_o_version',
                    'primaertumor_topographie_icd_o_freitext'
                ),
                self::MANDATORY_ERROR,
                function (registerPatientMessage $message) {
                    $exportData = $message->getSection('export')->getData();

                    // if disease is not ly, leu and snst = fields must be filled
                    $condition  = in_array($exportData['erkrankung'], array('ly', 'leu', 'snst')) === false;

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                'menge_histologie/*/lk_befallen',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['lk_untersucht']) > 0);
                }
            )
            ->addMandatory(
                $sectionName,
                'menge_histologie/*/sentinel_lk_befallen',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['sentinel_lk_untersucht']) > 0);
                }
            )
            ->addMandatory(
                $sectionName,
                'menge_weitere_klassifikation',
                self::MANDATORY_WARNING,
                null,
                function (registerPatientMessage $message) {
                    $exportData = $message->getSection('export')->getData();

                    return in_array($exportData['erkrankung'], array('leu', 'ly', 'snst'));
                }
            )
            ->addMandatory(
                $sectionName,
                array(
                    'menge_weitere_klassifikation/*/name',
                    'menge_weitere_klassifikation/*/stadium'
                ),
                self::MANDATORY_WARNING,
                null,
                function (registerPatientMessage $message) {
                    $exportData = $message->getSection('export')->getData();

                    return in_array($exportData['erkrankung'], array('leu', 'ly', 'snst'));
                }
            )
        ;
    }


    /**
     * build messages
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  void
     */
    public function buildMessages(registerPatient $patient, $withHistory = true)
    {
        $primaryCases = $patient->getPrimaryCases();

        foreach ($primaryCases as $case) {

            $diseaseId = $case->getData('erkrankung_id');
            $side      = $case->getData('diagnose_seite');

            // identification for this message
            $ident = $this->getMessageType() . '_' . $diseaseId . '_' . $side;

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

            $this->_buildDiagnoseSection($message, $case);

            // add message to patient of diagnose section could be built
            if ($message->hasSection($this->getSectionName()) === true) {
                $this->_buildExportSection($message, $case, $withHistory);
                $this->_buildMessageSection($message, $case, 'diagnose');

                $message->setMandatories($this->getMandatories());

                $patient->addMessage($message);
            }
        }
    }


    /**
     * _buildDiagnoseSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $primaryCase
     * @return  void
     */
    protected function _buildDiagnoseSection(registerPatientMessage $message, registerPatientCase $primaryCase)
    {
        $metastases = $this->_buildMetastases($message, $primaryCase, $this->getSectionName());

        $diagnose = $this->getState()->map('diagnosesicherung', $primaryCase->getData('diagnosesicherung'));

        $messagePart = array(
            'id'                                      => 'DIA_' . $this->_getUniqueMessageId($primaryCase),
            'primaertumor_icd_code'                   => $primaryCase->getData('diagnose'),
            'primaertumor_icd_version'                => $primaryCase->getData('diagnose_version'),
            'primaertumor_diagnosetext'               => $primaryCase->getData('diagnose_text'),
            'primaertumor_topographie_icd_o'          => $primaryCase->getData('lokalisation'),
            'primaertumor_topographie_icd_o_version'  => $primaryCase->getData('lokalisation_version'),
            'primaertumor_topographie_icd_o_freitext' => $primaryCase->getData('lokalisation_text'),
            'diagnosedatum'                           => $this->_buildDiagnosisDate($primaryCase),
            'diagnosesicherung'                       => $diagnose,
            'seitenlokalisation'                      => $this->_buildSide($primaryCase),
            'fruehere_tumorerkrankungen'              => $this->_buildFormerDiseases($primaryCase->getData('anamnese')),
            'menge_histologie'                        => $this->_buildHistologies($primaryCase->getData('histologie'), $primaryCase),
            'menge_fm_unique'                         => $metastases['unique'], // for diff
            'menge_fm'                                => $metastases['metastases'],
            'menge_tnm'                               => $this->_buildTnm($primaryCase->getData('tumorstatus')),
            'menge_weitere_klassifikation'            => $this->_buildAdditionalClassification($primaryCase->getData('tumorstatus')),
            'allgemeiner_leistungszustand'            => $this->_buildEcog($primaryCase->getData('anamnese')),
            'anmerkung'                               => $primaryCase->getData('bem')
        );

        $message->addSection($this->getSectionName(), $messagePart);
    }


    /**
     * _buildFormerDiseases
     *
     * @access  protected
     * @param   array   $anamnesis
     * @return  string
     */
    protected function _buildFormerDiseases(array $anamnesis)
    {
        $formerDiseases = array();

        // diseaseData
        if (count($anamnesis) > 0) {
            // newest anamnesis
            $relevantAnamnesis = reset($anamnesis);

            foreach ($relevantAnamnesis['anamnese_erkrankung'] as $record) {
                $disease = $record['erkrankung'];

                if ((str_starts_with($disease, 'C') && str_starts_with($disease, array('C98', 'C99') === false)) ||
                    (str_starts_with($disease, 'D0') === true)) {

                    $implode = array($disease);
                    $year    = $record['jahr'];

                    if (strlen($year) > 0) {
                        $implode[] = $year;
                    }

                    $formerDiseases[] = implode(', ', $implode);
                }
            }
        }

        return implode('; ', $formerDiseases);
    }
}
