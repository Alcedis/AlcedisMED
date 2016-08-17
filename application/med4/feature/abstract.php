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

abstract class featureAbstract
{
    private $_params = array();

    protected $_services = array();


    /**
     *
     * @param type $param
     * @param type $value
     * @return featureAbstract
     */
    public function setParam($param, $value)
    {
        $this->_params[$param] = $value;

        return $this;
    }

    /**
     *
     * @param type $param
     * @return type
     */
    public function getParam($param)
    {
        return (array_key_exists($param, $this->_params) === true ? $this->_params[$param] : null);
    }

    /**
     *
     * @return type
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     *
     * @param type $params
     * @return featureAbstract
     */
    public function setParams($params)
    {
        $this->_params = $params;

        return $this;
    }


    /**
     *
     * @return featureAbstract
     */
    public function resetParams()
    {
        $this->_params = array();

        return $this;
    }

    public function callService($class, $event)
    {
        $serviceRequest = get_class($class);

        if (array_key_exists($serviceRequest, $this->_services) === true && array_key_exists($event, $this->_services[$serviceRequest]) === true) {
            $methods = is_array($this->_services[$serviceRequest][$event]) === false
                ? array($this->_services[$serviceRequest][$event])
                : $this->_services[$serviceRequest][$event]
            ;

            foreach ($methods as $method) {
                $this->{$method}($class);
            }
        }

        return $this;
    }

    /**
     * check if service event exists
     *
     * @param type $class
     * @param type $event
     * @return type
     */
    public function serviceExists($class, $event)
    {
        return (array_key_exists(get_class($class), $this->_services) === true && array_key_exists($event, $this->_services[get_class($class)]));
    }
}

?>
