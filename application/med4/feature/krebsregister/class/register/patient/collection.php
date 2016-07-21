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
 * Class registerPatientCollection
 */
class registerPatientCollection implements IteratorAggregate
{
    /**
     * _records
     *
     * @access  protected
     * @var     registerPatient[]
     */
    protected $_registerPatients = array();


    /**
     * getIterate
     *
     * @access  public
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_registerPatients);
    }


    /**
     * create registerPatientCollection
     *
     * @static
     * @access  public
     * @param   array   $registerPatients
     * @return  registerPatientCollection
     */
    public static function create($registerPatients = array())
    {
        $self = new self;

        return $self->setRegisterPatients($registerPatients);
    }


    /**
     * count records
     *
     * @access  public
     * @return  int
     */
    public function count()
    {
        return count($this->_registerPatients);
    }


    /**
     * hasData
     *
     * @access  public
     * @return  bool
     */
    public function hasData()
    {
        return ($this->count() > 0);
    }


    /**
     * addRegisterPatient
     *
     * @access  public
     * @param   registerPatient   $registerPatient
     * @return  registerPatientCollection
     */
    public function addRegisterPatient(registerPatient $registerPatient)
    {
        $this->_registerPatients[$registerPatient->getId()] = $registerPatient;

        return $this;
    }


    /**
     * setRegisterPatients
     *
     * @access  public
     * @param   registerPatient[]   $registerPatients
     * @return  registerPatientCollection
     */
    public function setRegisterPatients(array $registerPatients)
    {
        $this->_registerPatients = array();

        foreach ($registerPatients as $registerPatient) {
            $this->addRegisterPatient($registerPatient);
        }

        return $this;
    }


    /**
     * reverse registerPatient
     *
     * @access  public
     * @return  array
     */
    public function reverse()
    {
        $reversedIterator = new self;

        return $reversedIterator->setRegisterPatients(array_reverse($this->_registerPatients));
    }


    /**
     * getFirst
     *
     * @access  public
     * @return  registerPatient
     */
    public function getFirst()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = reset($this->_registerPatients);
        }

        return $record;
    }


    /**
     * getLast
     *
     * @access  public
     * @return  registerPatient
     */
    public function getLast()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = end($this->_registerPatients);
        }

        return $record;
    }


    /**
     * getRegisterPatient
     *
     * @access  public
     * @param   int $id
     * @return  registerPatient
     */
    public function getRegisterPatient($id)
    {
        return (array_key_exists($id, $this->_registerPatients) === true
            ? $this->_registerPatients[$id]
            : null
        );
    }


    /**
     * getIds
     *
     * @access  public
     * @param   bool    $onlyValid
     * @param   bool    $concat
     * @param   string  $separator
     * @return  array
     */
    public function getIds($onlyValid = true, $concat = false, $separator = ',')
    {
        $ids = array();

        foreach ($this->getRegisterPatients() as $patient) {
            if ($onlyValid === true) {
                if ($patient->isValid() === true) {
                    $ids[] = $patient->getId();
                }
            } else {
                $ids[] = $patient->getId();
            }
        }

        if ($concat === true) {
            $ids = count($ids) > 0 ? implode($separator,  $ids) : '0';
        }

        return $ids;
    }


    /**
     * getRegisterPatients
     *
     * @access  public
     * @return  registerPatient[]
     */
    public function getRegisterPatients()
    {
        return $this->_registerPatients;
    }


    /**
     * toArray
     *
     * @access  public
     * @param   bool $excludeNonValidatable
     * @param   bool $excludeNonExportable
     * @param   bool $checkHiddenSection
     * @return  array
     */
    public function toArray($excludeNonValidatable = false, $excludeNonExportable = false, $checkHiddenSection = false)
    {
        $patients = array();

        foreach ($this->getRegisterPatients() as $registerPatient) {
            $patient  = $registerPatient->getData();
            $messages = array();

            foreach ($registerPatient->getMessages() as $registerPatientMessage) {

                // check validatable
                if ($excludeNonValidatable === true && $registerPatientMessage->isValidatable() === false) {
                    continue;
                }

                // check exportable
                if ($excludeNonExportable === true && $registerPatientMessage->isExportable() === false) {
                    continue;
                }

                $message = $registerPatientMessage->toArray($checkHiddenSection);

                // always remove patient message section
                unset($message['patient']);

                $messages[] = $message;
            }

            // only export patient if min one message exists
            if (count($messages) > 0) {
                $patients[] = array(
                    'patient'  => $patient,
                    'messages' => $messages
                );
            }
        }

        return $patients;
    }


    /**
     * removeExceptOf
     *
     * @access  public
     * @param   array $patientIds
     * @return  registerPatientCollection
     */
    public function removeExceptOf(array $patientIds)
    {
        $this->_registerPatients = array_intersect_key($this->_registerPatients, array_flip($patientIds));

        return $this;
    }


    /**
     * remove
     *
     * @access  public
     * @param   int $id
     * @return  registerPatientCollection
     */
    public function remove($id)
    {
        unset($this->_registerPatients[$id]);

        return $this;
    }
}
