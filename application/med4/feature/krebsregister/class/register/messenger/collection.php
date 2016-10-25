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
 * Class registerMessengerCollection
 */
class registerMessengerCollection implements IteratorAggregate
{
    /**
     * _records
     *
     * @access  protected
     * @var     registerMessenger[]
     */
    protected $_registerMessengers = array();


    /**
     * getIterate
     *
     * @access  public
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_registerMessengers);
    }


    /**
     * create registerMessengerCollection
     *
     * @static
     * @access  public
     * @param   array   $registerMessengers
     * @return  registerMessengerCollection
     */
    public static function create($registerMessengers = array())
    {
        $self = new self;

        return $self->setregisterMessengers($registerMessengers);
    }


    /**
     * count records
     *
     * @access  public
     * @return  int
     */
    public function count()
    {
        return count($this->_registerMessengers);
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
     * addRegisterMessenger
     *
     * @access  public
     * @param   registerMessenger   $registerMessenger
     * @return  registerMessengerCollection
     */
    public function addRegisterMessenger(registerMessenger $registerMessenger)
    {
        $this->_registerMessengers[$registerMessenger->getId()] = $registerMessenger;

        return $this;
    }


    /**
     * adds a messenger
     *
     * @access  public
     * @param   int $id
     * @param   registerPatientMessage $message
     * @return  registerMessengerCollection
     */
    public function add($id, registerPatientMessage $message)
    {
        // only add if id is filled
        if (strlen($id) > 0) {
            // only add if not already exists and is filled
            if (array_key_exists($id, $this->_registerMessengers) === false) {
                $this->_registerMessengers[$id] = registerMessenger::create($id);
            }

            $this->_registerMessengers[$id]->addMessage($message);
        }

        return $this;
    }


    /**
     * get all related messages for this messenger
     *
     * @access  public
     * @param   int $id
     * @return  registerPatientMessage[]
     */
    public function getMessagesForMessenger($id)
    {
        $messages = array();

        // if id exists in this collection, return messages for this messenger
        if (array_key_exists($id, $this->_registerMessengers) === true) {
            $messages = $this->_registerMessengers[$id]->getMessages();
        }

        return $messages;
    }


    /**
     * load
     *
     * @access  public
     * @param   resource $db
     * @param   array    $params
     * @return  registerMessengerCollection
     */
    public function load($db, array $params)
    {
        foreach ($this->_registerMessengers as $messenger) {
            if ($messenger->hasData() === false) {
                $messenger->load($db, $params);
            }
        }

        return $this;
    }


    /**
     * setRegisterMessengers
     *
     * @access  public
     * @param   registerMessenger[]   $registerMessengers
     * @return  registerMessengerCollection
     */
    public function setRegisterMessengers(array $registerMessengers)
    {
        $this->_registerMessengers = array();

        foreach ($registerMessengers as $registerMessenger) {
            $this->addregisterMessenger($registerMessenger);
        }

        return $this;
    }


    /**
     * reverse registerMessenger
     *
     * @access  public
     * @return  array
     */
    public function reverse()
    {
        $reversedIterator = new self;

        return $reversedIterator->setregisterMessengers(array_reverse($this->_registerMessengers));
    }


    /**
     * getFirst
     *
     * @access  public
     * @return  registerMessenger
     */
    public function getFirst()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = reset($this->_registerMessengers);
        }

        return $record;
    }


    /**
     * getLast
     *
     * @access  public
     * @return  registerMessenger
     */
    public function getLast()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = end($this->_registerMessengers);
        }

        return $record;
    }


    /**
     * getRegisterMessenger
     *
     * @access  public
     * @param   int $id
     * @return  registerMessenger
     */
    public function getRegisterMessenger($id)
    {
        return (array_key_exists($id, $this->_registerMessengers) === true
            ? $this->_registerMessengers[$id]
            : null
        );
    }


    /**
     * toArray
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        $messenger = array();

        foreach ($this->_registerMessengers as $registerMessenger) {
            $messenger[] = $registerMessenger->getData();
        }

        return $messenger;
    }


    /**
     * getRegisterMessengers
     *
     * @access  public
     * @return  registerMessenger[]
     */
    public function getRegisterMessengers()
    {
        return $this->_registerMessengers;
    }
}
