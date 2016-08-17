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
 * Class exportWrapperKrakenModelIterator
 */
class exportWrapperKrakenModelIterator implements IteratorAggregate
{
    /**
     * _records
     *
     * @access  protected
     * @var     array
     */
    protected $_records = array();


    /**
     * getIterate
     *
     * @access  public
     * @return  ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_records);

    }


    /**
     * create exportWrapperKrakenModelIterator
     *
     * @static
     * @access  public
     * @param   array $records
     * @return  exportWrapperKrakenModelIterator
     */
    public static function create($records = array())
    {
        $self = new self;

        return $self->setRecords($records);
    }


    /**
     * count records
     *
     * @access  public
     * @return  int
     */
    public function count()
    {
        return count($this->_records);
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
     * addRecord
     *
     * @access  public
     * @param   array   $record
     * @return  exportWrapperKrakenModelIterator
     */
    public function addRecord($record)
    {
        $this->_records[] = $record;

        return $this;
    }


    /**
     * setRecords
     *
     * @access  public
     * @param   array   $records
     * @return  exportWrapperKrakenModelIterator
     */
    public function setRecords($records)
    {
        $this->_records = $records;

        return $this;
    }


    /**
     * reverse records
     *
     * @access  public
     * @return  array
     */
    public function reverse()
    {
        $reversedIterator = new self;

        return $reversedIterator->setRecords(array_reverse($this->_records));
    }


    /**
     * getFirst
     *
     * @access  public
     * @return  exportWrapperKrakenModelIterator
     */
    public function getFirst()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = reset($this->_records);
        }

        return $record;
    }


    /**
     * getLast
     *
     * @access  public
     * @return  exportWrapperKrakenModelIterator
     */
    public function getLast()
    {
        $record = null;

        if ($this->count() > 0) {
            $record = end($this->_records);
        }

        return $record;
    }


    /**
     * getRecord
     *
     * @access  public
     * @param   int $index
     * @return  array
     */
    public function getRecord($index)
    {
        return (array_key_exists($index, $this->_records) === true ? $this->_records[$index] : null);
    }
}
