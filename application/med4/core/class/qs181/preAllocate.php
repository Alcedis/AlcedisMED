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

class qs181PreAllocate
{
    /**
     * @access
     * @var array
     */
    protected $_params = array();


    /**
     * @access
     * @var null
     */
    protected $_version = null;


    /**
     * @access
     * @var null
     */
    protected $_preallocator = null;


    /**
     *
     *
     * @static
     * @access
     * @return qs181PreAllocate
     */
    public static function create()
    {
        return new self();
    }


    /**
     *
     *
     * @access
     * @param $smarty
     * @param $db
     * @return $this
     */
    public function init($smarty, $db)
    {
        $version = $this->_getQS181Version($db);

        if ($version !== null) {
            $req = "core/class/qs181/mapping/{$version}.php";

            if (file_exists($req) === true) {
                $className = "qs181Mapping{$version}";

                require_once "core/class/qs181/mapping/abstract.php";
                require_once $req;

                $this->_preallocator = new $className($smarty, $db, $this->_params);
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    public function allocate()
    {
        $this->_preallocator->allocate();

        $this->setParam('qs181bid', 4);


        return $this;
    }


    /**
     *
     *
     * @access
     * @return bool
     */
    public function checkPossibleMapping()
    {
        return ($this->_preallocator !== null);
    }


    /**
     *
     *
     * @access
     * @param $db
     * @return null|string
     */
    private function _getQS181Version($db)
    {
        $version = $this->_version;

        if ($version === null && $this->getParam('abodeId') !== null) {
            $year = dlookup($db, 'aufenthalt', 'YEAR(aufnahmedatum)', "aufenthalt_id = '{$this->getParam('abodeId')}'");

            $version = strlen($year) ? $year : null;
        }

        return $version;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @param $value
     * @return $this
     */
    public function setParam($name, $value) {
        $this->_params[$name] = $value;

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $name
     * @return null
     */
    public function getParam($name)
    {
        $var = null;

        if ($this->_preallocator === null) {
            $var = (array_key_exists($name, $this->_params) ? $this->_params[$name] : null);
        } else {
            $var = $this->_preallocator->getParam($name);
        }

        return $var;
    }
}

?>
