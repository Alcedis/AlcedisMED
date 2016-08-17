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

require_once 'feature/dkg/class/abstract.php';

class dkgConditions extends dkgAbstract
{
    private $_data      = array();
    private $_errors    = array();

    private $_states    = array();

    public static $stateRed     = 1;
    public static $stateYellow  = 2;
    public static $stateBlue    = 3;
    public static $stateGreen   = 4;

    /**
     *
     * @return dkgConditions
     */
    public function checkConditions($db)
    {
        foreach (get_class_methods(get_class($this)) as $methodName) {
            if (str_starts_with($methodName, '__v') === true){
                $this->_states[] = $this->{$methodName}($db);
            }
        }

        return $this;
    }

    /**
     * return current condition state
     *
     * @return type
     */
    public function getConditionState()
    {
        return MIN(array_unique($this->_states));
    }

    /**
     * returns all condition errors
     *
     * @return type
     */
    public function getConditionErrors()
    {
        return $this->_errors;
    }

    /**
     *
     * @param type $data
     * @return dkgConditions
     */
    public function setConditionData($data, $convert = true)
    {
        $this->_states = array(self::$stateGreen);

        if ($convert === true) {
            $forms = array();

            foreach ($data as $dataset) {
                $forms[$dataset['form']][] = $dataset;
                $this->_states[] = trim($dataset['form_status']);
            }

            $this->_data = $forms;
        } else {
            $this->_data = $data;

            foreach ($this->_data as $formName => $entries) {
                foreach ($entries as $entry) {
                    $this->_states[] = trim($entry['form_status']);
                }
            }
        }

        $this->_errors = array();

        return $this;
    }

    /**
     * checks if form exists in disease data
     *
     * @param type $formName
     * @return type
     */
    private function _formExists($formName)
    {
        return array_key_exists($formName, $this->_data);
    }

    protected function __v1($db)
    {
        $state = self::$stateGreen;

        if ($this->getParam('disease') == 'p' && in_array('p', $this->getHubNames()) === true) {
            $minOneTherapieplan = false;

            if (array_key_exists('therapieplan', $this->_data) === true) {
                foreach ($this->_data['therapieplan'] as $dataset) {
                    if (strlen(dlookup($db, 'therapieplan', 'IF(watchful_waiting = 1 OR active_surveillance = 1, 1, NULL)', "therapieplan_id = '{$dataset['form_id']}'"))) {
                       $minOneTherapieplan = true;
                       break;
                    }
                }
            }

            if ($this->_formExists('eingriff') === false && $this->_formExists('therapie_systemisch') === false &&
                $this->_formExists('strahlentherapie') === false && $this->_formExists('sonstige_therapie') === false &&
                $minOneTherapieplan === false
            ){
                $this->_errors['p'][] = array(
                    'forms' => array(
                        'eingriff'              => $this->getLabel('caption', 'eingriff'),
                        'therapie_systemisch'   => $this->getLabel('caption', 'therapie_systemisch'),
                        'strahlentherapie'      => $this->getLabel('caption', 'strahlentherapie'),
                        'sonstige_therapie'     => $this->getLabel('caption', 'sonstige_therapie'),
                        'therapieplan'          => $this->getLabel('caption', 'therapieplan')
                     ),
                     'msg'  => $this->getLabel('v1')
                );

                $state = self::$stateYellow;
            }
        }

        return $state;
    }

    /**
     * form histologie must exist
     *
     * @return type
     */
    protected function __v2()
    {
        $state = self::$stateGreen;
        $hubs  = array('oz', 'b', 'gt', 'h', 'lu');

        if ($this->_formExists('histologie') === false && $this->getParam('disease') != 'd') {
            foreach ($this->getHubNames() as $hubName) {
                if (in_array($hubName, $hubs) === true ) {
                    $this->_errors[$hubName][] = array(
                        'forms' => array(
                            'histologie' => $this->getLabel('caption', 'histologie')
                        ),
                        'msg'   => $this->getLabel('v2')
                    );
                }
            }

            $state = self::$stateYellow;
        }

        return $state;
    }



    protected function __v3()
    {
        $state = self::$stateGreen;

        if ($this->getParam('disease') == 'd' &&
            $this->_formExists('histologie') === false &&
            $this->_formExists('eingriff') === false
        ){
            if (in_array('d', $this->getHubNames()) === true) {
                $this->_errors['d'][] = array(
                    'forms' => array(
                        'histologie' => $this->getLabel('caption', 'histologie'),
                        'eingriff'   => $this->getLabel('caption', 'eingriff')
                    ),
                    'msg' => $this->getLabel('v3')
                );
            }

            $state = self::$stateYellow;
        }

        return $state;
    }



    protected function __v4()
    {
        $state = self::$stateGreen;

        if ($this->_formExists('tumorstatus') === false) {
            foreach ($this->getHubNames() as $hubName) {
                $this->_errors[$hubName][] = array(
                    'forms' => array(
                        'tumorstatus' => $this->getLabel('caption', 'tumorstatus')
                    ),
                    'msg' => $this->getLabel('v4')
                );
            }

            $state = self::$stateRed;
        }

        return $state;
    }


    protected function __v5()
    {
        $state = self::$stateGreen;

        if ($this->getParam('disease') == 'b' &&
            in_array('b', $this->getHubNames()) === true &&
            array_key_exists('patient', $this->_data) === true
        ){
            foreach ($this->_data['patient'] as $dataset) {
                if (strlen($dataset['geschlecht']) == 0) {
                    $state = self::$stateYellow;
                    break;
                }
            }
        }

        return $state;
    }

    protected function __v6_v7()
    {
        $state      = self::$stateGreen;
        $errorForms = array();

        $forms = array('therapieplan', 'beratung');
        foreach ($this->getHubNames() as $hubName) {
            if (in_array($hubName, array('oz', 'd', 'b', 'gt', 'h', 'lu', 'p')) === true) {
                foreach ($forms as $formName) {
                    if ($this->_formExists($formName) === false){
                        $errorForms[$hubName][$formName] = $this->getLabel('caption', $formName);
                    }
                }
            }
        }

        $forms = array('anamnese');
        foreach ($this->getHubNames() as $hubName) {
            if (in_array($hubName, array('d', 'p')) === true) {
                foreach ($forms as $formName) {
                    if ($this->_formExists($formName) === false){
                        $errorForms[$hubName][$formName] = $this->getLabel('caption', $formName);
                    }
                }
            }
        }

        if (count($errorForms) > 0) {
            $state = self::$stateYellow;

            foreach ($errorForms as $hubName => $eForms) {
                $this->_errors[$hubName][] = array(
                    'forms' => $eForms,
                    'msg'   => $this->getLabel('v6_7')
                );

            }
        }

        return $state;
    }


    /**
     * returns field condition
     *
     * @param type $field
     * @return array
     */
    public function getFieldConditions($field)
    {
        $conditions = array();

        foreach ($this->getHubs() as $hub) {
            $fieldCondition = $hub->getFieldCondition($this->getParam('form'), $field, $this->getParam('disease'));

            if ($fieldCondition !== null) {
                $conditions[$hub->getname()][] = $fieldCondition;
            }
        }

        return $conditions;
    }

    /**
     * returns field check
     *
     * @param type $field
     * @return array
     */
    public function getFieldCheck($field)
    {
        $checks = array();

        foreach ($this->getHubs() as $hub) {
            $fieldCheck = $hub->getFieldCheck($this->getParam('form'), $field, $this->getParam('disease'));

            if ($fieldCheck === true) {
                $checks[$hub->getname()][$field] = true;
            }
        }

        return $checks;
    }
}

?>