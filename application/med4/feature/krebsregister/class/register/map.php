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

class registerMap
{
    /**
     * _cacheName
     *
     * @access  protected
     * @var     string
     */
    protected $_cacheName;


    /**
     * _ident
     *
     * @access  protected
     * @var     string
     */
    protected $_ident;


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected static $_db;


    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected static $_cache = array();


    /**
     * _queries
     *
     * @access  protected
     * @var     array
     */
    protected static $_queries = array();


    /**
     * _appending
     *
     * @access  protected
     * @var     string
     */
    protected $_appending;


    /**
     * _prepending
     *
     * @access  protected
     * @var     string
     */
    protected $_prepending;


    /**
     * create
     *
     * @static
     * @access  public
     * @param   string  $cacheName
     * @param   string  $ident
     * @return  registerMap
     */
    public static function create($cacheName = null, $ident = null)
    {
        return new self($cacheName, $ident);
    }


    /**
     * @param string $cacheName
     * @param string $ident
     */
    public function __construct($cacheName = null, $ident = null)
    {
        $this->_cacheName = $cacheName;
        $this->_ident = $ident;
    }


    /**
     * setDb
     *
     * @static
     * @access  public
     * @param   resource $db
     * @return  void
     */
    public static function setDb($db)
    {
        self::$_db = $db;
    }


    /**
     * setQuery
     *
     * @static
     * @access  public
     * @param   string  $cacheName
     * @param   string  $table
     * @param   string  $field
     * @param   string  $where
     * @return  void
     */
    public static function setQuery($cacheName, $table, $field, $where)
    {
        self::$_queries[$cacheName] = array(
            'table' => $table,
            'field' => $field,
            'where' => $where
        );
    }


    /**
     * getCache
     *
     * @static
     * @access  public
     * @return  array
     */
    public static function getCache()
    {
        return self::$_cache;
    }


    /**
     * getLabel
     *
     * @access  public
     * @return  string
     * @throws Exception
     */
    public function getLabel()
    {
        $cacheName = $this->_cacheName;

        // create cache on call
        if (array_key_exists($cacheName, self::$_cache) === false) {
            self::$_cache[$cacheName] = array();
        }

        $cache = self::$_cache[$cacheName];

        $ident = $this->_ident;
        $label = null;

        if (isset($cache[$ident]) === false) {
            $db = self::$_db;
            $query = self::$_queries[$cacheName];

            $label = dlookup($db, $query['table'], $query['field'], str_replace('?', $ident, $query['where']));

            self::$_cache[$cacheName][$ident] = $label;
        } else {
            $label = $cache[$ident];
        }

        $appending  = $this->_appending;
        $prepending = $this->_prepending;

        if ($prepending !== null) {
            $label = $prepending . $label;
        }

        if ($appending !== null) {
            $label .= $appending;
        }

        return $label;
    }


    /**
     * setAppending
     *
     * @access  public
     * @param   string  $appending
     * @param   string  $separator
     * @return  registerMap
     */
    public function setAppending($appending, $separator = ' ')
    {
        if (strlen($appending) > 0) {
            $appending = $separator . $appending;
        }

        $this->_appending = $appending;

        return $this;
    }


    /**
     * setPrepending
     *
     * @access  public
     * @param   string  $prepending
     * @param   string  $separator
     * @return  registerMap
     */
    public function setPrepending($prepending, $separator = ' ')
    {
        if (strlen($prepending) > 0) {
            $prepending .= $separator;
        }

        $this->_prepending = $prepending;

        return $this;
    }
}
