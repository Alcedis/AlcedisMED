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
 * Class registerStateMessageClosure
 */
class registerStateMessageClosure extends registerStateMessageProgress
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'closure';


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    {
        registerStateMessageAbstract::_initialize();

        // add mandatories for this message type
        $this
            ->addMandatory($this->getSectionName(), 'tod/sterbedatum tod/tod_tumorbedingt', self::MANDATORY_WARNING)
            ->addMandatory($this->getSectionName(), array(
                    'tod/menge_todesursache/*/todesursache_icd'
                ),
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData, $field, $parentData) {

                    $condition = false;

                    if (true === isset($sectionData['sterbedatum']) && true === strlen($sectionData['sterbedatum']) > 0 &&
                        (false === isset($sectionData['menge_todesursache']['todesursache_icd']) ||
                         (true === isset($sectionData['menge_todesursache']['todesursache_icd']) && 0 === strlen($sectionData['menge_todesursache']['todesursache_icd'])))) {
                        $condition = "'menge_todesursache -> todesursache_icd' ist nicht dokumentiert";
                    }

                    return $condition;
                }
            );
    }


    /**
     * build closure messages
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

            $this->_buildClosureSection($message, $case);

            // add message to patient of diagnose section could be built
            if ($message->hasSection($this->getSectionName()) === true) {
                $this->_buildExportSection($message, $case, $withHistory);
                $this->_buildMessageSection($message, $case, 'tod');

                $message->setMandatories($this->getMandatories());

                $patient->addMessage($message);

                // we only want one closure message on death per patient
//                break;
            }
        }
    }


    /**
     * _buildClosureSection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @return  void
     */
    protected function _buildClosureSection(registerPatientMessage $message, registerPatientCase $case)
    {
        $closure = $case->getData('abschluss');

        // if closure exists and patient has died
        if ($closure !== null && strlen($closure['todesdatum']) > 0) {
            $progress = $this->getRawStructure();

            $progress['id']                                 = 'PRO_CON_' . $closure['abschluss_id'];
            $progress['gesamtbeurteilung_tumorstatus']      = 'U';
            $progress['verlauf_lokaler_tumorstatus']        = 'U';
            $progress['verlauf_tumorstatus_lymphknoten']    = 'U';
            $progress['verlauf_tumorstatus_fernmetastasen'] = 'U';
            $progress['tod']                                = $this->_buildProgressDeath($closure);
            $progress['anmerkung']                          = $closure['bem'];

            $message->addSection($this->getSectionName(), $progress);
        }
    }


    /**
     * _buildProgressDeath
     *
     * @access  protected
     * @param   array $record
     * @return  array
     */
    protected function _buildProgressDeath(array $record)
    {
        $relation = $record['tod_tumorassoziation'];
        $related  = null;

        switch (true) {
            case (in_array($relation, array('tott', 'totn'))):
                $related = 'J';
                break;

            case ($relation === 'totnt'):
                $related = 'N';
                break;

            case (strlen($relation) === 0 || $relation === 'totnb'):
                $related = 'U';
                break;
        }

        $death = array(
            'sterbedatum'        => todate($record['todesdatum'], 'de'),
            'tod_tumorbedingt'   => $related,
            'menge_todesursache' => (strlen($record['tod_ursache']) > 0) ? array('todesursache_icd' => $record['tod_ursache']) : null
        );

        return $death;
    }
}
