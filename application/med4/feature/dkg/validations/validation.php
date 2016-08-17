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

class dkgValidation
{
    private $_table = null;

    private $_fields = array();

    private $_dlists = array();

    public function __construct($table) {
        $this->_table = $table;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $table
     * @return dkgValidation
     */
    public static function create($table)
    {
        return new self($table);
    }


    /**
     * add validation field
     *
     * @param dkgValidationField $field
     * @return dkgValidation
     */
    public function addField($field)
    {
        $this->_fields[] = $field;

        return $this;
    }


    /**
     *
     * @param dkgValidation $dlist
     * @return dkgValidation
     */
    public function addDlist($dlist)
    {
        $this->_dlists[] = $dlist;

        return $this;
    }

    /**
     * returns the name of the dlist and main tables
     *
     * @return type
     */
    public function getTables()
    {
        $tables = array($this->_table);

        foreach ($this->_dlists as $dlist) {
            $tables[] = reset($dlist->getTables());
        }

        return $tables;
    }

    /**
     * return all registered Dlists
     * @return type
     */
    public function getDlists()
    {
        return $this->_dlists;
    }

    public function getFields()
    {
        return $this->_fields;
    }


    /**
     * returns condition for field if exist
     *
     * @param type $field
     * @param type $disease
     */
    public function getCondition($fieldName, $disease = null)
    {
        $condition = null;

        foreach ($this->_fields as $field) {
            if ($field->getName() === $fieldName && $field->checkExclude($disease) === false) {
                $condition = $field->getCondition();
                break;
            }
        }

        return $condition;
    }

    /**
     * returns condition for field if exist
     *
     * @param type $field
     * @param type $disease
     */
    public function getCheck($fieldName, $disease = null)
    {
        $check = null;

        foreach ($this->_fields as $field) {
            if ($field->getName() === $fieldName && $field->checkExclude($disease) === false) {
                $check = $field->getCheck();
                break;
            }
        }

        return $check;
    }


    public function applyToFields($hubName, $fields, $page, $disease = null)
    {
        if ($this->_table === $page) {
            foreach ($this->_fields as $field) {
                if (array_key_exists($field->getName(), $fields) === true && $field->checkExclude($disease) === false) {
                    if ($field->getCheck() === true) {
                        if (array_key_exists('interface', $fields[$field->getName()]) === false ||
                            in_array($hubName, $fields[$field->getName()]['interface']) === false
                        ) {
                            $fields[$field->getName()]['features'][] = $hubName;
                        }
                    }

                    $fields[$field->getName()]['highlight'] = 1;
                }
            }
        } else {
            foreach ($this->_dlists as $dlist) {
                $fields = $dlist->applyToFields($hubName, $fields, $page, $disease);
            }
        }

        return $fields;
    }


    /**
     *
     *
     * @return int
     */
    public function getRequiredFieldsCount()
    {
        $count = 0;

        foreach ($this->_fields as $field) {
            if ($field->getCheck() === true) {
                $count++;
            }
        }

        return $count;
    }

    public function getRequiredFields()
    {
        $fields = array();

        foreach ($this->_fields as $field) {
            if ($field->getCheck() === true) {
                $fields[] = $field->getName();
            }
        }

        return $fields;
    }


    public function getTable()
    {
        return $this->_table;
    }
}

?>
