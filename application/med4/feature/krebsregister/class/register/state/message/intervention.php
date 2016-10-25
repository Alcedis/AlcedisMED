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
 * Class registerStateMessageIntervention
 */
class registerStateMessageIntervention extends registerStateMessageAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'intervention';


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName = 'menge_op';


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

        // add mandatories for this message type
        $this
            ->addMandatory($sectionName, '*/op_datum */op_intention */op_ops_version */menge_ops')
            ->addMandatory(
                $sectionName,
                '*/histologie/lk_befallen',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['lk_untersucht']) > 0);
                }
            )
            ->addMandatory(
                $sectionName,
                '*/histologie/sentinel_lk_befallen',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData) {
                    return (strlen($sectionData['sentinel_lk_untersucht']) > 0);
                }
            )
        ;
    }


    /**
     * build message
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  void
     */
    public function buildMessages(registerPatient $patient, $withHistory = true)
    {
        $state = $this->getState();
        $primaryCases = $patient->getPrimaryCases();

        foreach ($primaryCases as $case) {

            // identification for this message
            $ident = $this->getMessageType();

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

            $this->_buildInterventionSection($message, $case);

            // add message to patient if intervention section could be built
            if ($message->hasSection($this->getSectionName()) === true) {
                $this->_buildExportSection($message, $case, $withHistory);
                $this->_buildMessageSection($message, $case, 'behandlungsende');
                $this->_buildTumorSection($message, $case);

                $message->setMandatories($this->getMandatories());

                $patient->addMessage($message);
            }
        }
    }


    /**
     * _buildInterventionData
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase $primaryCase
     * @return  void
     */
    protected function _buildInterventionSection(registerPatientMessage $message, registerPatientCase $primaryCase)
    {
        $messagePart = array();

        foreach ($primaryCase->getData('eingriff') as $intervention) {
            $messagePart[] = $this->_buildIntervention($intervention, $primaryCase);
        }

        // only add section if message parts was built
        if (count($messagePart) > 0) {
            $message->addSection($this->getSectionName(), $messagePart);
        }
    }


    /**
     * _buildIntervention
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $primaryCase
     * @return  array
     */
    protected function _buildIntervention(array $record, registerPatientCase $primaryCase)
    {
        $histologies = $this->_buildHistologies($record['histologie'], $primaryCase);

        $residualStatus = null;

        if (strlen($record['art_primaertumor']) > 0) {
            $residualStatus = $this->_buildResidualStatus($primaryCase);
        }

        $opsVersion = null;
        $opsCodes   = array();
        $ops        = $record['eingriff_ops'];

        $side       = $this->_buildSide($primaryCase);
        $checkSide  = in_array($side, array('L', 'R')) ? true : false;

        foreach ($ops as $opRecord) {
            $opsVersion = $opRecord['prozedur_version'];

            // need to check side only if side is set (l or r) and 'prozedur_seite' is not 'B'
            if ($opRecord['prozedur_seite'] !== 'B') {
                if ($checkSide === true && $side !== $opRecord['prozedur_seite']) {
                    continue;
                }
            }

            $opsCodes[] = $opRecord['prozedur'];
        }

        $intervention = array(
            'id'                 => 'INT_' . $record['eingriff_id'],
            'op_intention'       => $this->_buildInterventionIntention($record),
            'op_datum'           => todate($record['datum'], 'de'),
            'menge_ops'          => $opsCodes,
            'op_ops_version'     => $opsVersion,
            'histologie'         => (count($histologies) > 0 ? reset($histologies) : null),
            'menge_tnm'          => $this->_buildTnm($record['histologie'], false),
            'residualstatus'     => $residualStatus,
            'menge_komplikation' => array(),
            'menge_operateur'    => $this->_buildInterventionPerson($record),
            'anmerkung'          => $this->_buildInterventionNotice($record)
        );

        return $intervention;
    }


    /**
     * _buildInterventionIntention
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildInterventionIntention(array $record)
    {
        $intention = null;
        $rIntention = $record['intention'];

        // Prio 1
        if (strlen($rIntention) > 0) {
            if ($rIntention === 'kur') {
                $intention = 'K';
            } else if ($rIntention === 'pal') {
                $intention = 'P';
            }
        }

        // Prio 2, 3, 4
        if ($intention === null) {
            if (strlen($record['art_diagnostik']) > 0) {
                $intention = 'D';
            } elseif (strlen($record['art_revision']) > 0) {
                $intention = 'R';
            } elseif ((strlen($record['art_sonstige']) > 0) ||
                in_array('1', array($record['art_metastasen'], $record['art_nachresektion'], $record['art_rekonstruktion'])) === true) {
                $intention = 'S';
            }
        }

        return registerHelper::ifNull($intention, 'X');
    }


    /**
     * _buildInterventionNotice
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _buildInterventionNotice(array $record)
    {
        $notice = array();
        $state  = $this->getState();

        foreach ($record['komplikation'] as $complication) {
            $code = $complication['komplikation'];

            if (array_key_exists($code, $notice) === false) {
                $notice[$code] = $state->map('complication', $code);
            }
        }

        return implode(', ', $notice);
    }


    /**
     * _buildInterventionPerson
     *
     * @access  protected
     * @param   array $record
     * @return  array
     */
    protected function _buildInterventionPerson(array $record)
    {
        $persons = array();

        $person1Id = $record['operateur1_id'];
        $person2Id = $record['operateur2_id'];

        if (strlen($person1Id) > 0) {
            $persons[] = array('name_operateur' => registerMap::create('person', $person1Id)->getLabel());
        }

        if (strlen($person2Id) > 0) {
            $persons[] = array('name_operateur' => registerMap::create('person', $person2Id)->getLabel());
        }

        return $persons;
    }
}
