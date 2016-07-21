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

require_once 'core/class/report/helper.reports.php';

/**
 * Class registerQueryElementGroup
 */
class registerQueryElementGroup
{
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
     * _fields
     *
     * @access  protected
     * @var     array
     */
    protected $_fields = array();


    /**
     * _alias
     *
     * @access  protected
     * @var     string
     */
    protected $_alias;


    /**
     * _table
     *
     * @access  protected
     * @var     string
     */
    protected $_tableName;


    /**
     * _additionalConditions
     *
     * @access  protected
     * @var     array
     */
    protected $_additionalConditions = array();


    /**
     * create registerQueryElementGroup
     *
     * @static
     * @access  public
     * @param   string          $tableAndAlias
     * @param   array|string    $fields
     * @return  registerQueryElementGroup
     */
    public static function create($tableAndAlias = null, $fields = null)
    {
        return new self($tableAndAlias, $fields);
    }


    /**
     * @param string        $tableAndAlias
     * @param array|string  $fields
     */
    public function __construct($tableAndAlias = null, $fields = null)
    {
        if ($tableAndAlias !== null) {
            $this->setTable($tableAndAlias);
        }

        // set default name
        $this->setName($this->getTableName());

        if ($fields !== null) {
            $this->setFields($fields);
        }
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
     * setName
     *
     * @access  public
     * @param   string  $name
     * @return  registerQueryElementGroup
     */
    public function setName($name)
    {
        $this->_name = $name;

        return $this;
    }


    /**
     * addCondition
     *
     * @access  public
     * @param   string $condition
     * @return  registerQueryElementGroup
     */
    public function addCondition($condition)
    {
        $this->_additionalConditions[] = $condition;

        return $this;
    }


    /**
     * _buildStatement
     *
     * @access  protected
     * @return  string
     */
    protected function _buildStatement()
    {
        $rowSeparator = HReports::SEPARATOR_ROWS;
        $colSeparator = HReports::SEPARATOR_COLS;

        $alias     = $this->getAlias();
        $tableName = $this->getTableName();

        $statement = "GROUP_CONCAT(DISTINCT
            IF({$alias}.{$tableName}_id IS NOT NULL";
        if (count($this->_additionalConditions) > 0) {
            $statement .= " " . implode(" ", $this->_additionalConditions);
        }
        $statement .= ",";

        $fields = $this->getFields();

        if (count($fields) === 0) {
            $statement .= "{$alias}.*";
        } else {
            $statement .= "CONCAT_WS('{$colSeparator}', IFNULL({$alias}." .
                implode(", ''), IFNULL({$alias}.", $fields) . ', ""))';
        }

        $statement .= ", NULL
            )
            SEPARATOR '{$rowSeparator}'
        )";

        return $statement;
    }


    /**
     * setTable
     *
     * @access  public
     * @param   string  $tableAndAlias
     * @return  registerQueryElementGroup
     */
    public function setTable($tableAndAlias)
    {
        $table = $tableAndAlias;
        $alias = $tableAndAlias;

        if (str_contains($tableAndAlias, ' ') === true) {
            list($table, $alias) = explode(' ', $tableAndAlias);
        }

        $this->setTableName($table);
        $this->setAlias($alias);

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
            $statement = $this->_statement = $this->_buildStatement();
        }

        return $statement;
    }


    /**
     * setAlias
     *
     * @access  public
     * @param   string $alias
     * @return  registerQueryElementGroup
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
        return $this->_alias;
    }


    /**
     * hasAlias
     *
     * @access  public
     * @return  bool
     */
    public function hasAlias()
    {
        return ($this->_alias !== null);
    }


    /**
     * setTableName
     *
     * @access  public
     * @param   string $name
     * @return  registerQueryElementGroup
     */
    public function setTableName($name)
    {
        $this->_tableName = $name;

        return $this;
    }


    /**
     * getTableName
     *
     * @access  public
     * @return  string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }


    /**
     * setFields
     *
     * @access  public
     * @param   array|string $fields
     * @return  registerQueryElementGroup
     */
    public function setFields($fields)
    {
        if (is_string($fields) === true) {
            $fields = explode(' ', $fields);
        }

        $this->_fields = array();

        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }


    /**
     * addField
     *
     * @access  public
     * @param   string  $field
     * @return  registerQueryElementGroup
     */
    public function addField($field)
    {
        $field = strtolower($field);

        $this->_fields[$field] = $field;

        return $this;
    }


    /**
     * getFields
     *
     * @access  public
     * @return  array
     */
    public function getFields()
    {
        return $this->_fields;
    }


    /**
     * hasFields
     *
     * @access  public
     * @return  bool
     */
    public function hasFields()
    {
        return (count($this->_fields) > 0);
    }


    /**
     * removeField
     *
     * @access  public
     * @param   string  $name
     * @return  registerQueryElementGroup
     */
    public function removeField($name)
    {
        unset($this->_fields[$name]);

        return $this;
    }


    /**
     * reset group statement
     *
     * @access  public
     * @return  registerQueryElementGroup
     */
    public function reset()
    {
        $this->_statement = null;

        return $this;
    }
}
