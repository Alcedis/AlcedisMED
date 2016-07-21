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

require_once 'therapy/abstract.php';

/**
 * Class registerStateMessageSystemictherapy
 */
class registerStateMessageSystemictherapy extends registerStateMessageTherapyAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'systemicTherapy';


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName = 'menge_syst';


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    {
        parent::_initialize();

        $this->addMandatory($this->getSectionName(), '*/syst_intention');

        $this->addMandatory($this->getSectionName(), array(
            '*/syst_beginn_datum',
            '*/menge_therapieart/*/syst_therapieart'
        ), self::MANDATORY_WARNING);

        $this->addMandatory(
            $this->getSectionName(),
            '*/menge_substanz',
            self::MANDATORY_WARNING,
            function(registerPatientMessage $message, $data, $field) {
                // wenn im Feld SYST_Therapieart CH, HO, IM oder SO exportiert wird
                $condition = false;

                foreach ($data as $systemicTherapy) {
                    foreach ($systemicTherapy['menge_therapieart'] as $kind) {
                        if (in_array($kind['syst_therapieart'], array('CH', 'HO', 'IM', 'SO')) === true) {
                            $condition = true;

                            break;
                        }
                    }
                }

                return $condition;
            }
        );

        $this->addMandatory($this->getSectionName(), array(
                '*/menge_nebenwirkung/*/nebenwirkung_grad'
            ),
            self::MANDATORY_WARNING,
            null,
            function(registerPatientMessage $message, $sectionData, $field, $parentData) {

                $condition = false;

                if (true === isset($sectionData['nebenwirkung_art']) && strlen($sectionData['nebenwirkung_art']) > 0 &&
                    true === isset($sectionData['nebenwirkung_grad']) && 'U' === $sectionData['nebenwirkung_grad']) {
                    $condition = "'nebenwirkung_art' ist gefüllt, dann sollte '{$field}' auch dokumentiert sein (Wert ist, 'U' wenn Feld nicht dokumentiert ist)";
                }

                return $condition;
            },
            array(null, '', 'U')
        );

        $this->addMandatory($this->getSectionName(), array(
                '*/syst_ende_datum'
            ),
            self::MANDATORY_WARNING,
            null,
            function(registerPatientMessage $message, $sectionData, $field, $parentData) {

                $condition = false;

                if (true === isset($sectionData['syst_ende_grund']) && strlen($sectionData['syst_ende_grund']) > 0) {
                    $condition = "'syst_ende_grund' ist gefüllt, dann sollte '{$field}' auch dokumentiert sein";
                }

                return $condition;
            }
        );
    }


    /**
     * build systemic therapy messages
     *
     * @access  protected
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  void
     */
    public function buildMessages(registerPatient $patient, $withHistory = true)
    {
        // process each patient cases
        foreach ($patient->getCases() as $case) {

            // process all cases which has a valid primary case or are a valid primary case
            if ($case->hasPrimaryCase() === true && $case->getPrimaryCase()->isValid() === true) {

                // identification for this message
                $ident = implode('_', array(
                    $this->getMessageType(),
                    $case->getData('erkrankung_id'),
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

                $this->_buildSysTherapySection($message, $case);

                // add message to patient if radio therapy section could be built
                if ($message->hasSection($this->getSectionName()) === true) {
                    $this->_buildExportSection($message, $case, $withHistory);
                    $this->_buildMessageSection($message, $case, 'behandlungsende', $ident);
                    $this->_buildTumorSection($message, $case);

                    $message->setMandatories($this->getMandatories());

                    $patient->addMessage($message);
                }
            }
        }
    }


    /**
     * _buildSysTherapySection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @return  void
     */
    protected function _buildSysTherapySection(registerPatientMessage $message, registerPatientCase $case)
    {
        $messagePart = array();

        // default systemic therapy
        foreach ($case->getData('therapie_systemisch') as $therapy) {
            $messagePart[] = $this->_buildSysTherapy($therapy, $case);
        }

        // interventions with zytostatika or matching ops codes
        foreach ($case->getData('eingriff') as $intervention) {
            $match = strlen($intervention['intraop_zytostatika']) > 0;

            if ($match === false) {
                foreach ($intervention['eingriff_ops'] as $ops) {
                    if (str_starts_with($ops['prozedur'], array('5-410', '5-411')) === true) {
                        $match = true;
                        break;
                    }
                }
            }

            if ($match === true) {
                $messagePart[] = $this->_buildSysTherapyFromIntervention($intervention, $case);
            }
        }

        // therapyPlan
        foreach ($case->getData('therapieplan') as $plan) {
            if ($plan['active_surveillance'] === '1' || $plan['watchful_waiting'] === '1') {
                $messagePart[] = $this->_buildSysTherapyFromTherapyPlan($plan, $case);
            }
        }

        // if radio systemic therapy message parts exists, add to message
        if (count($messagePart) > 0) {
            $message->addSection($this->getSectionName(), $messagePart);
        }
    }


    /**
     * _buildSysTherapy
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $primaryCase
     * @return  array
     */
    protected function _buildSysTherapy(array $record, registerPatientCase $primaryCase)
    {
        $state       = $this->getState();
        $kind        = array();
        $substance   = array();
        $description = null;
        $notice      = null;

        switch ($record['vorlage_therapie_art']) {
            case ('c'):
            case ('cst'):
                $kind['c'] = array('syst_therapieart' => 'CH');
                break;
            case ('ci'):
                $kind['c'] = array('syst_therapieart' => 'CH');
                $kind['i'] = array('syst_therapieart' => 'IM');
                break;
            case ('ah'):
            case ('ahst'):
                $kind['ah'] = array('syst_therapieart' => 'HO');
                break;
            case ('i'):
            case ('ist'):
                $kind['i'] = array('syst_therapieart' => 'IM');
                break;
            case ('son'):
            case ('sonstr'):
            case ('schmerz'):
                $kind['son'] = array('syst_therapieart' => 'SO');
                break;
        }

        $template = $record['vorlage_therapie_id'];

        if ($template !== null) {
            $description = $template['bez'];
            $notice = $template['bem'];

            foreach ($template['wirkstoffe'] as $sub) {
                $substance[] = array('syst_substanz' => $state->map('wirkstoff', $sub['wirkstoff']));
            }
        }

        $therapy = array(
            'id'                         => 'SYS_' . $record['therapie_systemisch_id'],
            'syst_intention'             => $this->_buildIntention($record),
            'syst_stellung_op'           => $this->_buildOpRelation($record),
            'menge_therapieart'          => $kind,
            'syst_therapieart_anmerkung' => $notice,
            'syst_protokoll'             => $description,
            'syst_beginn_datum'          => todate($record['beginn'], 'de'),
            'menge_substanz'             => $substance,
            'syst_ende_grund'            => $this->_buildEndReason($record),
            'syst_ende_datum'            => todate($record['ende'], 'de'),
            'residualstatus'             => $this->_buildResidualStatus($primaryCase),
            'menge_nebenwirkung'         => $this->_buildByEffect($record),
            'anmerkung'                  => $record['bem']
        );

        return $therapy;
    }


    /**
     * _buildSysTherapyFromIntervention
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildSysTherapyFromIntervention(array $record, registerPatientCase $case)
    {
        $boneMarrowTrans = null;

        $kind = 'SO';

        foreach ($record['eingriff_ops'] as $ops) {
            if (str_starts_with($ops['prozedur'], array('5-410', '5-411')) === true) {
                $kind = 'KM';
                break;
            }
        }

        $therapy = array(
            'id'                         => 'SYS_INT_' . $record['eingriff_id'],
            'syst_intention'             => $this->_buildIntention($record),
            'syst_stellung_op'           => 'I',
            'menge_therapieart'          => array(array('syst_therapieart' => $kind)),
            'syst_therapieart_anmerkung' => null,
            'syst_protokoll'             => null,
            'syst_beginn_datum'          => todate($record['datum'], 'de'),
            'menge_substanz'             => array(),
            'syst_ende_grund'            => null,
            'syst_ende_datum'            => todate($record['datum'], 'de'),
            'residualstatus'             => $this->_buildResidualStatus($case),
            'menge_nebenwirkung'         => array(),
            'anmerkung'                  => null
        );


        return $therapy;
    }


    /**
     * _buildSysTherapyFromTherapyPlan
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildSysTherapyFromTherapyPlan(array $record, registerPatientCase $case)
    {
        $kind = array();

        if ($record['watchful_waiting'] === '1') {
            $kind[] = array('syst_therapieart' => 'WS');
        }

        if ($record['active_surveillance'] === '1') {
            $kind[] = array('syst_therapieart' => 'AS');
        }

        $therapy = array(
            'id'                         => 'SYS_THE_' . $record['therapieplan_id'],
            'syst_intention'             => $this->_buildIntention($record),
            'syst_stellung_op'           => 'O',
            'menge_therapieart'          => $kind,
            'syst_therapieart_anmerkung' => null,
            'syst_protokoll'             => null,
            'syst_beginn_datum'          => todate($record['datum'], 'de'),
            'menge_substanz'             => array(),
            'syst_ende_grund'            => null,
            'syst_ende_datum'            => null,
            'residualstatus'             => $this->_buildResidualStatus($case),
            'menge_nebenwirkung'         => array(),
            'anmerkung'                  => null
        );

        return $therapy;
    }
}
