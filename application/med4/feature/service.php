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

require_once 'interface.php';
require_once 'abstract.php';

class featureService
{
    private static $instance = null;

    private $_features = array();

    private $_params = array();

    static public function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->resetParam();
    }

    public function register($class)
    {
        $this->_features[get_class($class)] = $class;

        return $this;
    }


    public function callService($class, $event)
    {
        $events = is_array($event) === false ? array($event) : $event;

        foreach ($this->_features as $feature) {
            foreach ($events as $event) {
                if ($feature->serviceExists($class, $event) === true) {
                    $paramBackup = $feature->getParams();

                    $feature
                        ->setParams($this->_params)
                        ->callService($class, $event)
                        ->setParams($paramBackup)
                    ;
                }
            }
        }

        return $this;
    }

    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;

        return $this;
    }

    public function getParam($name)
    {
        return (array_key_exists($param, $this->_params) === true ? $this->_params[$param] : null);
    }

    public function resetParam()
    {
        $this->_params = array();

        return $this;
    }
}

?>
