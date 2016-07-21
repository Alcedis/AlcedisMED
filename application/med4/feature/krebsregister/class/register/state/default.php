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

require_once 'abstract.php';
require_once 'message/abstract.php';

/**
 * Class registerStateDefault
 */
abstract class registerStateDefault extends registerStateAbstract
{
    /**
     * names of export message types
     *
     * @access protected
     * @var    array
     */
    protected $_messageTypes = array(
        'initial',
        'progress',
        'intervention',
        'radiotherapy',
        'systemictherapy',
        'closure',
        'persistent'
    );


    /**
     * mapping table for values
     *
     * @access  protected
     * @var     array
     */
    protected $_map = array(
        'diagnosesicherung' => array(
            '8' => 7,
            '0' => 1
        ),
        'response' => array(
            'CR'  => 'V',
            'NED' => 'V',
            'PR'  => 'T',
            'SD'  => 'K',
            'PD'  => 'P'
        ),
        'geschlecht' => array(
            'm'  => 'M',
            'w'  => 'W',
            null => 'U'
        )
    );


    /**
     * _additionalClassificationFields
     *
     * @access  protected
     * @var     array
     */
    protected $_additionalClassificationFields = array(
        'nhl_who_b',
        'nhl_who_t',
        'hl_who',
        'ann_arbor_stadium',
        'durie_salmon',
        'iss',
        'cll_rai',
        'cll_binet',
        'aml_fab',
        'aml_who',
        'all_egil',
        'mds_fab',
        'mds_who'
    );


    /**
     * _initialize
     *
     * @access  protected
     * @param   string  $type
     * @return  void
     */
    protected function _initialize($type)
    {
        parent::_initialize($type);

        // require message type classes
        foreach ($this->_messageTypes as $type) {
            require_once "message/{$type}.php";
        }

        $db = $this->getDb();

        // add mapping for _additionalClassificationFields
        foreach ($this->getAdditionalClassificationFields() as $field) {
            $this->addToMap($field, getLookup($db, $field));
        }

        // add mapping for some default fields
        $this
            ->addToMap('seite', getLookup($db, 'seite'))
            ->addToMap('complication', getLookup($db, 'komplikation'))
            ->addToMap('wirkstoff', getLookup($db, 'wirkstoff'))
            ->addToMap('staat', getLookup($db, 'staat'))
        ;

        // set register map queries for cache
        registerMap::setQuery('krankenkasse', 'l_ktst', 'name', "iknr = '?'");
        registerMap::setQuery('vorlage_krankenversicherung', 'vorlage_krankenversicherung', 'name', "iknr = '?'");
        registerMap::setQuery('person', 'user', "CONCAT_WS(', ', nachname, vorname)", "user_id = '?'");
        registerMap::setQuery('nci', 'l_nci', 'bez', "code = '?'");
    }


    /**
     * build default messages
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @param   bool $withHistory
     * @return  void
     */
    protected function _buildMessages(registerPatientCollection $collection, $withHistory = true)
    {
        $this
            ->_loadConfig('tumorstatus', 'rec')
            ->_loadConfig('strahlentherapie', 'rec')
            ->_loadConfig('default', null, '../feature/krebsregister/configs/register/')
        ;

        /* @var registerPatient $patient */
        foreach ($collection as $patient) {

            // patient have at least one case that could be exported
            if ($patient->isValid() === true) {

                // convert patient data for correct format
                $this->_convertPatientData($patient);

                $patientData = $patient->getData();

                // build messages
                foreach ($this->_messageTypes as $type) {
                    $this->getMessageBuilder($type)
                        ->setState($this)
                        ->buildMessages($patient, $withHistory)
                    ;
                }

                $persistentMessages    = array();
                $nonPersistentMessages = array();

                // check difference and mandatories on each patient messages
                foreach ($patient->getMessages() as $patientMessage) {
                    $patientMessage->addSection('patient', $patientData);

                    if ($patientMessage->getType() !== 'persistent') {
                        $nonPersistentMessages[] = $patientMessage;
                    } else {
                        $persistentMessages[] = $patientMessage;
                    }

                    // check difference only when flag is set
                    if ($withHistory === true) {
                        $patientMessage->checkDifference();
                    }

                    $patientMessage->checkMandatories();
                }

                $this->_processPersistentMessages($persistentMessages, $nonPersistentMessages);
            }
        }
    }


    /**
     * _processPersistentMessages
     *
     * @access  protected
     * @param   registerPatientMessage[] $persistentMessages
     * @param   registerPatientMessage[] $nonPersistentMessages
     * @return  void
     */
    protected function _processPersistentMessages(array $persistentMessages, array $nonPersistentMessages)
    {
        foreach ($persistentMessages as $persistentMessage) {
            if ($persistentMessage->hasHistory() === false || $persistentMessage->hasDifference() === true) {
                $diseaseIdent = $persistentMessage->getDiseaseIdent();

                foreach ($nonPersistentMessages as $message) {
                    // check same disease ident
                    if ($message->getDiseaseIdent() === $diseaseIdent) {

                        // copy persistent message data to non persistent message
                        foreach (registerStateMessagePersistent::$uniqueMessageSections as $sectionName) {

                            // check persistent message on item
                            if ($persistentMessage->hasSection($sectionName) === true) {
                                $sectionData = $persistentMessage->getSection($sectionName)->getData();

                                $message->addSection($sectionName, $sectionData);
                            }
                        }

                        break;
                    }
                }
            }
        }
    }


    /**
     * _convertPatientData
     *
     * @access  protected
     * @param   registerPatient $patient
     * @return  void
     */
    protected function _convertPatientData(registerPatient $patient)
    {
        $notice = null;
        $data   = $patient->getData();

        if ($data['krankenkassennr'] !== null) {
            $notice = registerMap::create('krankenkasse', $data['krankenkassennr'])->getLabel();

            // fallback for 'vorlage_krankenkasse'
            if ($notice === '') {
                $notice = registerMap::create('vorlage_krankenversicherung', $data['krankenkassennr'])->getLabel();
            }
        }

        // overwrite or add patient data
        $patient->addData('anmerkung', $notice);
        $patient->addData('geburtsdatum', todate($data['geburtsdatum'], 'de'));
        $patient->addData('geschlecht', $this->map('geschlecht', $data['geschlecht']));
        $patient->addData('land', $this->map('staat', $data['land']));
    }


    /**
     * _detectPrimaryCases
     *
     * @access  protected
     * @param   registerPatientCollection $patientCollection
     * @return  void
     */
    protected function _detectPrimaryCases(registerPatientCollection $patientCollection)
    {
        /* @var registerPatient $patient */
        foreach ($patientCollection as $patient) {
            $cases = $patient->getCases();

            foreach ($cases as $case) {
                $ident   = $case->getIdent();
                $pcIdent = $case->getPrimaryCaseIdent();

                // current case is primary case
                if ($ident === $pcIdent) {
                    $case->setAsPrimaryCase();
                } else if ($ident !== $pcIdent && array_key_exists($pcIdent, $cases) === true) {
                    // if current case is not primary, try to set primary case for current case

                    $case->setPrimaryCase($cases[$pcIdent]);
                }
            }
        }
    }
}
