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

class reportTime
{
    /**
     * _params
     *
     * @access  protected
     * @var     array
     */
    protected $_params = array();


    /**
     * _columns
     *
     * @access  protected
     * @var     array
     */
    protected $_columns = array();


    /**
     * _time
     *
     * @access  protected
     * @var     string
     */
    protected $_time;


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * create reportTime
     *
     * @static
     * @access  public
     * @param   resource $db
     * @return  reportTime
     */
    public static function create($db)
    {
        return new self($db);
    }


    /**
     * reportTime constructor.
     *
     * @param $db
     */
    public function __construct($db)
    {
        $this->_db = $db;

        $this->_initialize();
    }


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    {
        $columns = sql_query_array($this->_db, 'SHOW COLUMNS FROM `report_time`');

        foreach ($columns as $column) {
            $type = null;
            $cType = $column['Type'];

            switch (true) {
                case str_starts_with($cType, 'varchar'): $type = 'text'; break;
                case str_starts_with($cType, 'char'): $type = 'check'; break;
            }

            if ($type !== null) {
                $this->_columns[$column['Field']] = $type;
            }
        }
    }


    /**
     * setParams
     *
     * @access  public
     * @param   array $params
     * @return  reportTime
     */
    public function setParams(array $params)
    {
        $columns = $this->getColumns();

        foreach ($params as $param => $value) {
            if (array_key_exists($param, $columns) === true) {
                $this->_params[$param] = $this->_cast($param, $value);
            }
        }

        return $this;
    }


    /**
     * getParams
     *
     * @access  public
     * @return  array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * hasParams
     *
     * @access  public
     * @return  bool
     */
    public function hasParams()
    {
        return count($this->_params) > 0;
    }


    /**
     * _cast
     *
     * @access  protected
     * @param   string $name
     * @param   string $value
     * @return  string
     */
    protected function _cast($name, $value)
    {
        $type = $this->_columns[$name];

        switch ($type) {
            case 'text':
                if (strlen($value) === 0) {
                    $value = '-';
                }

                break;

            case 'check':
                $value = strlen($value) > 0 ? 1 : 0;

                break;
        }

        return $value;
    }


    /**
     * getColumns
     *
     * @access  public
     * @return  array
     */
    public function getColumns()
    {
        return $this->_columns;
    }


    /**
     * getTime
     *
     * @access  public
     * @return  string
     */
    public function getTime()
    {
        return $this->_time;
    }


    /**
     * setTime
     *
     * @access  public
     * @param   string $time
     * @return  reportTime
     */
    public function setTime($time)
    {
        $this->_time = $time;

        return $this;
    }


    /**
     * read
     *
     * @access  public
     * @return  reportTime
     */
    public function read()
    {
        if ($this->hasParams() === true) {
            $where = array();

            foreach ($this->getParams() as $param => $value) {
                $where[] = "$param = '{$value}'";
            }

            $time = dlookup($this->_db, "report_time", "time", implode(" AND ", $where));

            $this->setTime($time);
        }

        return $this;
    }


    /**
     * write
     *
     * @access  public
     * @return  reportTime
     */
    public function write()
    {
        if ($this->hasParams() === true) {
            $params = $this->getParams();
            $where  = array();

            foreach ($params as $param => $value) {
                $where[] = "$param = '{$value}'";
            }

            $id = dlookup($this->_db, "report_time", "report_time_id", implode(" AND ", $where));

            if (strlen($id) == 0) {
                $fields = array();

                foreach ($this->getColumns() as $column => $cType) {
                    if (array_key_exists($column, $params) === true) {
                        $fields[] = "{$column} = '{$params[$column]}'";
                    } else {
                        $val = $this->_cast($column, null);

                        $fields[] = "{$column} = '{$val}'";
                    }
                }

                $fields[] = "time = '{$this->getTime()}'";

                $query = "INSERT INTO report_time SET " . implode(',', $fields);
            } else {
                $query = "UPDATE report_time SET time = '{$this->getTime()}' WHERE report_time_id = '{$id}'";
            }

            mysql_query($query, $this->_db);
        }

        return $this;
    }

}

