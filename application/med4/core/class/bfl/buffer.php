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

class bflBuffer
{
    /**
     * _buffer
     *
     * @access  protected
     * @var     array
     */
    protected static $_buffer = array();


    /**
     * _instance
     * (comment)
     *
     * @access  protected
     * @var     bflBuffer
     */
    protected static $_instance;


    /**
     * getInstance
     *
     * @static
     * @access  public
     * @return  bflBuffer
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     *
     * @param array $request
     */
    public static function init($request)
    {
        $b = self::getInstance();

        foreach ($request as $var => $value) {
            if (str_starts_with($var, 'buffer-') === true) {
                $name   = substr($var, 7);
                $rawBuffer = json_decode($request[$var], true);

                $buffer = array(
                    'add' => array(),
                    'remove' => array()
                );

                if (is_array($rawBuffer) === true) {
                    foreach ($rawBuffer as $section => $content) {
                        $buffer[$section] = array_keys($content);
                    }
                }

                $b->_buffer[$name] = $buffer;
            }
        }

        return $b;
    }

    /**
     * check if for asked buffer name is not empty
     * @param string $name
     * @return boolean
     */
    public static function notEmpty($name)
    {
        $b = self::getInstance();

        $empty = true;

        if (array_key_exists($name, $b->_buffer) === true) {
            foreach($b->_buffer[$name] as $section) {
                if (empty($section) === false) {
                    $empty = false;
                    break;
                }
            }
        }

        return ($empty === true ? false : true);
    }

    /**
     * get bfl buffer
     *
     * @param string $name
     * @param string $section
     * @return array
     */
    public static function get($name, $section = null)
    {
        $b = self::getInstance();

        $return = array();

        if (array_key_exists($name, $b->_buffer) === true) {
            $buffer = $b->_buffer[$name];

            if ($section === null) {
                $return = $buffer;
            } elseif (array_key_exists($section, $buffer) === true) {
                $return = $buffer[$section];
            }
        }

        return $return;
    }

}

?>
