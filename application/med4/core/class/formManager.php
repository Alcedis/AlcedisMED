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

class formManager
{
    /**
     * _instance
     *
     * @access  private
     * @var     formManager
     */
    private static $_instance;


    /**
     * _properties
     *
     * @access  private
     * @var     array
     */
    private $_properties = array();


    /**
     * getInstance
     *
     * @static
     * @access  public
     * @return  formManager
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public static function setFormProperties($orgId = null, $properties = array())
    {
        $fm = self::getInstance();

        if ($orgId !== null) {
           $fm->_properties[$orgId] = $properties;
        }
    }

    /**
     * get form manager
     */
    public static function get()
    {
        return self::getInstance();
    }

    /**
     * return 1 means hide form in app
     *
     * @param int $orgId
     * @param string $section
     * @param string $form
     */
    public static function getFormProperty($orgId = null, $section = null, $form = null)
    {
        $fm = self::getInstance();

        $property = null;

        if ($orgId !== null && $section !== null && $form !== null) {
            //Fallback to default if no setting exists
            $orgId = array_key_exists($orgId, $fm->_properties) === true ? $orgId : 0;

            if (array_key_exists($orgId, $fm->_properties) === true &&
                array_key_exists($section, $fm->_properties[$orgId]) === true &&
                array_key_exists($form, $fm->_properties[$orgId][$section]) === true
            ) {
                $property = $fm->_properties[$orgId][$section][$form] == 1 ? true : null;
            }
        }

        return $property;
    }

}

?>
