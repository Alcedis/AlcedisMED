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

class exportWrapperKrakenModelField
{
    /**
     * _alias
     *
     * @access  protected
     * @var     string
     */
    protected $_alias;


    /**
     * _name
     *
     * @access  protected
     * @var     string
     */
    protected $_name;


    /**
     * _statement
     *
     * @access  protected
     * @var     string
     */
    protected $_statement;


    /**
     * create exportWrapperKrakenModelField
     *
     * @static
     * @access  public
     * @param   string  $name
     * @return  exportWrapperKrakenModelField
     */
    public static function create($name)
    {
        return new self($name);
    }


    /**
     * @param string    $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }


    /**
     * getName
     *
     * @access  public
     * @return  string
     */
    public function getName()
    {
        return $this->_name;
    }


    /**
     * setAlias
     *
     * @access  public
     * @param   string  $alias
     * @return  exportWrapperKrakenModelField
     */
    public function setAlias($alias)
    {
        $this->_alias = $alias;

        return $this;
    }


    /**
     * getAlias
     *
     * @access  public
     * @return  string
     */
    public function getAlias()
    {
        $alias = $this->_alias;

        if ($alias === null) {
            $this->_alias = $alias = $this->getName();
        }

        return $alias;
    }


    /**
     * setStatement
     *
     * @access  public
     * @param   string  $statement
     * @return  exportWrapperKrakenModelField
     */
    public function setStatement($statement)
    {
        $this->_statement = $statement;

        return $this;
    }


    /**
     * getStatement
     *
     * @access  public
     * @return  string
     */
    public function getStatement()
    {
        $statement = $this->_statement;

        if ($statement === null) {
            $this->_statement = $statement = $this->getName();
        }

        return $statement . " as '" . $this->getAlias() . "'";
    }
}
