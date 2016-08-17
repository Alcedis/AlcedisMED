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

abstract class dkgBaseValidations
{
    private $_name = null;

    private $_diseases = array();

    private $_validations = array();

    /**
     *
     * @param type $diseases
     * @return \dkgBaseValidations
     */
    protected function _activateForDisease($diseases)
    {
        $this->_diseases = is_array($diseases) === false ? array($diseases) : $diseases;

        return $this;
    }

    /**
     *
     * @param dkgValidation $validation
     * @return \dkgBaseValidations
     */
    protected function addValidation($validation)
    {
        $this->_validations[] = $validation;

        return $this;
    }

    /**
     * checks if the registered hub is active for this disease
     *
     * @param type $disease
     * @return boolean
     */
    public function checkForDisease($disease = null)
    {
        $valid = true;

        if ($disease !== null && in_array($disease, $this->_diseases) === false) {
            $valid = false;
        }

        return $valid;
    }


    /**
     * returns field conditions
     *
     * @param type $table
     * @param type $field
     * @param type $disease
     * @return type
     */
    public function getFieldCondition($table, $field, $disease = null)
    {
        $condition = null;

        foreach ($this->_validations as $validation) {
            if (in_array($table, $validation->getTables()) === true) {
                $condition = $validation->getCondition($field, $disease);
                break;
            }
        }

        return $condition;
    }

    /**
     * returns field check
     *
     * @param type $table
     * @param type $field
     * @param type $disease
     * @return type
     */
    public function getFieldCheck($table, $field, $disease = null)
    {
        $check = null;

        foreach ($this->_validations as $validation) {
            if (in_array($table, $validation->getTables()) === true) {
                $check = $validation->getCheck($field, $disease);
                break;
            }
        }

        return $check;
    }


    /**
     * checks if a required validation exists if form is given
     *
     * @param type $form
     */
    public function validationForFormExists($form = null)
    {
        $validationExists = false;

        if ($form === null) {
            $validationExists = true;
        } else {
            foreach ($this->_validations as $validation) {
                if (in_array($form, $validation->getTables()) === true) {
                    $validationExists = true;
                    break;
                }
            }
        }

        return $validationExists;
    }


    /**
     * add highlighting and interface name to fields
     *
     * @param type $fields
     * @param type $form
     * @param type $disease
     * @return type
     */
    public function applyToFields($fields, $form = null, $disease = null)
    {
        foreach ($this->_validations as $validation) {
            if (in_array($form, $validation->getTables()) === true) {
                $fields = $validation->applyToFields($this->getName(), $fields, $form, $disease);

                break;
            }
        }

        return $fields;
    }


    public function getDlists($form)
    {
        $dlists = array();

        foreach ($this->_validations as $validation) {
            if (in_array($form, $validation->getTables()) === true) {
                $dlists = $validation->getDlists();

                break;
            }
        }

        return $dlists;
    }


    public function getFormNames()
    {
        $forms = array();

        foreach ($this->_validations as $validation) {
            $forms[] = $validation->getTable();
        }

        return $forms;
    }

    protected function _setName($name)
    {
        $this->_name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->_name;
    }
}

?>
