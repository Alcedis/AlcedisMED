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

class dkgValidationField
{
    private $_name  = null;

    private $_check = true;

    private $_condition = null;

    private $_excludeDiseases = array();

    public function __construct($name) {
        $this->_name = $name;
    }

    /**
     *
     * @param string $name
     * @return dkgValidationField
     */
    public static function create($name)
    {
        return new self($name);
    }


    /**
     *
     *
     * @access
     * @param bool $check
     * @return dkgValidationField
     */
    public function setCheck($check = true)
    {
        $this->_check = $check;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $condition
     * @return dkgValidationField
     */
    public function setCondition($condition)
    {
        $this->_condition = $condition;

        return $this;
    }

    public function getCondition()
    {
        return $this->_condition;
    }

    public function exclude($disease)
    {
        $this->_excludeDiseases = is_array($disease) === false ? array($disease) : $disease;

        return $this;
    }

    public function checkExclude($disease)
    {
        return ($disease !== null && in_array($disease, $this->_excludeDiseases) === true);
    }

    public function getCheck()
    {
        return $this->_check;
    }

    public function getName()
    {
        return $this->_name;
    }


}

?>
