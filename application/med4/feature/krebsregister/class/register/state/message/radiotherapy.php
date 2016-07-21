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
 * Class registerStateMessageRadiotherapy
 */
class registerStateMessageRadiotherapy extends registerStateMessageTherapyAbstract
{
    /**
     * _messageType
     *
     * @access  protected
     * @var     string
     */
    protected $_messageType = 'radioTherapy';


    /**
     * _sectionName
     *
     * @access  protected
     * @var     string
     */
    protected $_sectionName = 'menge_st';


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
            ->addMandatory($sectionName, array(
                '*/st_intention',
                '*/menge_bestrahlung/*/st_beginn_datum'
            ))
            ->addMandatory(
                $sectionName,
                '*/anmerkung',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData) {
                    $id = $sectionData['id'];

                    // only for real radio therapies
                    $condition = str_starts_with($id, 'RAD') === true && str_starts_with($id, 'RAD_INT') === false;

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                '*/menge_bestrahlung/*/st_ende_datum',
                self::MANDATORY_WARNING,
                null,
                function(registerPatientMessage $message, $sectionData, $field, $parentData) {
                    $condition = false;

                    if (true === isset($parentData['st_ende_grund']) && strlen($parentData['st_ende_grund']) > 0) {
                        $condition = "'st_ende_grund' ist gefüllt, dann sollte '{$field}' auch dokumentiert sein";
                    }

                    return $condition;
                }
            )
            ->addMandatory(
                $sectionName,
                '*/menge_nebenwirkung/*/nebenwirkung_grad',
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
        ;
    }


    /**
     * build radio therapy messages
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

                $this->_buildRadioTherapySection($message, $case);

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
     * _buildRadioTherapySection
     *
     * @access  protected
     * @param   registerPatientMessage $message
     * @param   registerPatientCase    $case
     * @return  void
     * @throws  Exception
     */
    protected function _buildRadioTherapySection(registerPatientMessage $message, registerPatientCase $case)
    {
        $messagePart = array();

        // process each interventions
        foreach ($case->getData('eingriff') as $intervention) {
            if (strlen($intervention['intraop_bestrahlung']) > 0) {
                $messagePart[] = $this->_buildRadioTherapyFromIntervention($intervention, $case);
            }
        }

        // process each radio therapies
        foreach ($case->getData('strahlentherapie') as $therapy) {
            $messagePart[] = $this->_buildRadioTherapy($therapy, $case);
        }

        // process each systemic therapies
        foreach ($case->getData('therapie_systemisch') as $therapy) {
            if (in_array($therapy['vorlage_therapie_art'], array('ahst', 'st', 'cst', 'ist', 'sonstr')) === true) {
                $messagePart[] = $this->_buildRadioSysTherapy($therapy, $case);
            }
        }

        // if radio therapy message parts exists, add to message
        if (count($messagePart) > 0) {
            $message->addSection($this->getSectionName(), $messagePart);
        }
    }


    /**
     * _buildRadioTherapyFromIntervention
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildRadioTherapyFromIntervention(array $record, registerPatientCase $case)
    {
        $dose  = $record['intraop_bestrahlung_dosis'];

        // create only one location as 1..n
        $location = array(
            'st_zielgebiet'       => null, // default
            'st_seite_zielgebiet' => null, // default
            'st_beginn_datum'     => todate($record['datum'], 'de'),
            'st_ende_datum'       => todate($record['datum'], 'de'),
            'st_applikationsart'  => null,
            'st_gesamtdosis'      => (strlen($dose) > 0 ? $dose . ' Gy': null),
            'st_einzeldosis'      => null
        );

        $therapy = array(
            'id'                 => 'RAD_INT_' . $record['eingriff_id'],
            'st_intention'       => $this->_buildIntention($record),
            'st_stellung_op'     => 'I',
            'menge_bestrahlung'  => array($location),
            'st_ende_grund'      => null,
            'residualstatus'     => $this->_buildResidualStatus($case),
            'menge_nebenwirkung' => array(), // no byeffects on intervention
            'anmerkung'          => null
        );

        return $therapy;
    }


    /**
     * _buildRadioTherapy
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildRadioTherapy(array $record, registerPatientCase $case)
    {
        $kind  = $record['art'];
        $dose  = $record['gesamtdosis'];
        $sDose = $record['einzeldosis'];

        // create only one location as 1..n
        $location = array(
            'st_zielgebiet'       => null, // default
            'st_seite_zielgebiet' => null, // default
            'st_beginn_datum'     => todate($record['beginn'], 'de'),
            'st_ende_datum'       => todate($record['ende'], 'de'),
            'st_applikationsart'  => (strlen($kind) > 0
                ? ($kind === 'str_pk' ? 'P' : 'S')
                : null
            ),
            'st_gesamtdosis'      => (strlen($dose) > 0 ? $dose . ' Gy': null),
            'st_einzeldosis'      => (strlen($sDose) > 0 ? $sDose . ' Gy': null)
        );

        $therapy = array(
            'id'                 => 'RAD_' . $record['strahlentherapie_id'],
            'st_intention'       => $this->_buildIntention($record),
            'st_stellung_op'     => $this->_buildOpRelation($record),
            'menge_bestrahlung'  => array($location),
            'st_ende_grund'      => $this->_buildEndReason($record),
            'residualstatus'     => $this->_buildResidualStatus($case),
            'menge_nebenwirkung' => $this->_buildByEffect($record),
            'anmerkung'          => $this->_buildRadioTherapyNotice($record, $location)
        );

        return $therapy;
    }


    /**
     * _buildRadioSysTherapy
     *
     * @access  protected
     * @param   array $record
     * @param   registerPatientCase $case
     * @return  array
     */
    protected function _buildRadioSysTherapy(array $record, registerPatientCase $case)
    {
        $therapy = array(
            'id'                 => 'SYSRAD_' . $record['therapie_systemisch_id'],
            'st_intention'       => $this->_buildIntention($record),
            'st_stellung_op'     => $this->_buildOpRelation($record),
            'menge_bestrahlung'  => array(
                array(
                  'st_zielgebiet'       => null,
                  'st_seite_zielgebiet' => null,
                  'st_beginn_datum'     => todate($record['beginn'], 'de'),
                  'st_ende_datum'       => todate($record['ende'], 'de'),
                  'st_applikationsart'  => null,
                  'st_gesamtdosis'      => null,
                  'st_einzeldosis'      => null
                )
            ),
            'st_ende_grund'      => $this->_buildEndReason($record),
            'residualstatus'     => $this->_buildResidualStatus($case),
            'menge_nebenwirkung' => $this->_buildByEffect($record),
            'anmerkung'          => null
        );

        return $therapy;
    }


    /**
     * _buildRadioTherapyNotice
     *
     * @access  protected
     * @param   array $record
     * @param   array $thLocation
     * @return  string
     */
    protected function _buildRadioTherapyNotice(array $record, array $thLocation)
    {
        $state    = $this->getState();
        $notice   = array();
        $location = array();

        foreach ($record as $field => $value) {
            if (str_starts_with($field, 'ziel_') === true && str_starts_with($field, 'ziel_sonst_detail') === false) {
                if (strlen($value) > 0) {
                    $location[] = $state->getConfig($field, 'strahlentherapie');
                }
            }
        }

        $detail = $record['ziel_sonst_detail'];

        // ICD Code
        if (strlen($detail) > 0) {
            $location[] = implode(' ', array(
                $detail,
                $state->map('seite', $record['ziel_sonst_detail_seite']),
                $record['ziel_sonst_detail_text']
            ));
        }

        if (count($location) > 0) {
            $notice[] = implode(', ', $location);
        }

        if ($thLocation['st_applikationsart'] === 'S') {
            $notice[] = $state->map('wirkstoff', $record['art']);
        }

        $notice = implode('; ', $notice);

        return (strlen($notice) > 0 ? $notice : null);
    }
}
