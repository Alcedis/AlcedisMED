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

class dmp2013Preallocate
{
    /**
     * @access
     * @var dmp2013PreallocateAbstract
     */
    protected $_preallocator = null;


    /**
     * @access
     * @var array
     */
    protected $_fields = array();


    /**
     * @access
     * @var null
     */
    protected $_type = null;


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $type
     * @param $param
     * @return dmp2013Preallocate
     */
    public static function create($db, $type, $param)
    {
        return new self($db, $type, $param);
    }


    /**
     * @param $db
     * @param $type
     * @param $param
     */
    public function __construct($db, $type, $param)
    {
        $this->_init($db, $type, $param);
    }


    /**
     *
     *
     * @access
     * @param $db
     * @param $type
     * @param $param
     * @return $this
     */
    private function _init($db, $type, $param)
    {
        $class = get_class($this);

        require_once 'preallocate/abstract.php';
        require_once "preallocate/{$type}.php";

        $className = $class . ucfirst($type);

        $this->_preallocator = new $className($db, $param);

        $this->_fields = $this->_preallocator
            ->preallocate()
            ->getFields()
        ;

        $this->_type = $type;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param array $array
     * @param       $fields
     * @return $this
     */
    public function assignTo(&$array = array(), $fields)
    {
        $ignoreFields = $this->_preallocator->getIgnoredFields();

        $fields = array_diff(
            $fields,
            $ignoreFields
        );

        // reset
        foreach ($fields as $fielName) {
            unset($array[$fielName]);
        }

        foreach ($this->_fields as $name => $value) {
            $array[$name] = $value;
        }

        return $this;
    }
}
