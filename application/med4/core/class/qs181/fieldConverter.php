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

class qs181FieldConverter
{
    protected $_db = null;

    protected $_fields = array();

    protected $_formId = null;

    protected $_table = null;

    protected $_year = null;

    protected $_version = null;

    protected $_tables = array();

    protected $_request = array();


    public function __construct($db, $table, $fields = array(), $formId = null, $request = null)
    {
        $this->_db     = $db;
        $this->_table  = $table;
        $this->_fields = $fields;
        $this->_formId = strlen($formId) > 0 ? $formId : null;
        $this->_request = $request;
    }


    public static function create($db, $table, $fields = array(), $formId = null, $request = null)
    {
        return new self($db, $table, $fields, $formId, $request);
    }


    protected function _getYear()
    {
        $v = null;

        //Formular ist vorhanden
        if ($this->_formId !== null) {
            switch ($this->_table) {
                case 'qs_18_1_b':
                    $v = dlookup($this->_db, $this->_table, 'YEAR(aufndatum)', "qs_18_1_b_id = '{$this->_formId}'");

                    break;

                default:
                    $query = "
                       SELECT
                           YEAR(b.aufndatum) AS v
                       FROM {$this->_table} src
                           INNER JOIN qs_18_1_b b ON src.qs_18_1_b_id = b.qs_18_1_b_id
                       WHERE
                          {$this->_table}_id = '{$this->_formId}'
                       GROUP BY {$this->_table}_id
                    ";

                    $result = sql_query_array($this->_db, $query);

                    if ($result !== false) {
                        $dataset = reset($result);
                        $v = $dataset['v'];
                    }

                    break;
            }
        } else {
            $v = date('Y');
        }

        $this->_year = $v;

        return $this;
    }


    protected function _getVersion()
    {
        $lQsTables = sql_query_array($this->_db, "SHOW TABLES LIKE 'l_qs_%'");

        foreach ($lQsTables as $lqTable) {
            $y = substr(reset($lqTable), -4);

            $this->_tables[$y] = $y;
        }

        ksort($this->_tables);

        $v = null;

        if (array_key_exists($this->_year, $this->_tables) === true) {
            $v = $this->_year;
        } else {
            //Nimm das letzte Jahr
            $v = $this->_formId !== null ? reset($this->_tables) : end($this->_tables);
        }

        $this->_version = $v;

        return $this;
    }


    protected function _init()
    {
        return $this
            ->_getYear()
            ->_getVersion()
        ;
    }


    public function getVersion()
    {
        return ($this->_version !== null ? $this->_version : $this
            ->_init()
            ->_version
        );
    }


    /**
     *
     *
     * @access
     * @param $field
     * @return void
     */
    protected function _isConvertable($field)
    {
        $conditions = array(
            isset($field['type']) === true && in_array($field['type'], array('lookup', 'code_qs')) === true,
            isset($field['ext']) === true,
        );

        return (in_array(false, $conditions) === false);
    }


    protected function _convertFields()
    {

        foreach ($this->_fields as $index => $field) {
            if ($this->_isConvertable($field) === true) {
                $class = $this->_fields[$index]['ext']['l_qs'];

                unset($this->_fields[$index]['ext']['l_qs']);

                $this->_fields[$index]['ext']['l_qs_' . $this->_version] = $class;
            }
        }

        return $this;
    }


    public function getFields()
    {
        $this
            ->_init()
            ->_convertFields()
        ;

        return $this->_fields;
    }
}

?>
