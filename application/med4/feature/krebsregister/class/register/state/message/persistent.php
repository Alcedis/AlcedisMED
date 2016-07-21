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
 * Class registerStateMessagePersistent
 */
class registerStateMessagePersistent extends registerStateMessageAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'persistent';


    /**
     * these sections should only exists once in all messages
     *
     * @access  protected
     * @var     array
     */
    public static $uniqueMessageSections = array(
        'menge_tumorkonferenz',
        'menge_zusatzitem'
    );


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
                    'erkrankung_id'  => $diseaseId,
                    'diagnose_seite' => $side,
                    'anlass'         => $ident
                ))
                ->setIgnoreOnDiff($this->getIgnoreOnDiff())
                ->setParams($patient->getParams())
            ;

            if ($withHistory === true) {
                $message->loadHistory($this->getDb(), $ident);
            }

            $this->_buildExportSection($message, $case, $withHistory, false, false);
            $this->_buildMessageSection($message, $case, 'persistent', $ident);

            $this->_buildConferenceSection($message, $case);
            $this->_buildItemSection($message, $case);

            // especially for this kind of message
            $message->checkConfiguration();

            $patient->addMessage($message);
        }
    }


    /**
     * _buildConferenceSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @return  void
     */
    protected function _buildConferenceSection(registerPatientMessage $message, registerPatientCase $case)
    {
        $messagePart      = array();
        $primaryCase      = $case->getPrimaryCase();
        $primaryCaseIdent = $primaryCase->getIdent();

        // check cache for primary conference items
        if (array_key_exists($primaryCaseIdent, self::$_cache['conference']) === false) {
            $hasPrimaryOp = false;

            // check for primary op
            foreach ($primaryCase->getData('eingriff') as $intervention) {
                if ($intervention['art_primaertumor'] !== null) {
                    $hasPrimaryOp = true;
                    break;
                }
            }

            foreach ($case->getData('therapieplan') as $therapyPlan) {
                // only if based on conference
                if ($therapyPlan['grundlage'] === 'tk') {
                    $messagePart[] = array(
                        'id'                   => 'CON_' . $therapyPlan['therapieplan_id'],
                        'tumorkonferenz_datum' => todate($therapyPlan['datum'], 'de'),
                        'tumorkonferenz_typ'   => $this->_buildConferenceType($therapyPlan, $hasPrimaryOp),
                        'anmerkung'            => $therapyPlan['bem']
                    );
                }
            }

            self::$_cache['conference'][$primaryCaseIdent] = $messagePart;
        } else {
            $messagePart = self::$_cache['conference'][$primaryCaseIdent];
        }

        // only add if min one entry exists
        if (count($messagePart) > 0) {
            $message->addSection('menge_tumorkonferenz', $messagePart);
        }
    }


    /**
     * _buildConferenceType
     *
     * @access  protected
     * @param   array $record
     * @param   bool  $hasPrimaryOp
     * @return  string
     */
    protected function _buildConferenceType(array $record, $hasPrimaryOp)
    {
        $type = null;
        $moment = $record['zeitpunkt'];

        if ($moment === 'prae') {
            $type = 'praeth';
        } else if ($moment === 'post') {
            $type = $hasPrimaryOp === true ? 'postop' : 'postth';
        }

        return $type;
    }


    /**
     * _buildItemSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @return  void
     */
    protected function _buildItemSection(registerPatientMessage $message, registerPatientCase $case)
    {
        $primaryCase      = $case->getPrimaryCase();
        $primaryCaseIdent = $primaryCase->getIdent();

        // check cache for primary case items
        if (array_key_exists($primaryCaseIdent, self::$_cache['items']) === false) {
            $state = $this->getState();

            $messagePart = self::$_cache['items'][$primaryCaseIdent] = $state->addAdditionalItems($primaryCase);
        } else {
            $messagePart = self::$_cache['items'][$primaryCaseIdent];
        }

        // only add if min one entry exists
        if (count($messagePart) > 0) {
            $message->addSection('menge_zusatzitem', $messagePart);
        }
    }
}
