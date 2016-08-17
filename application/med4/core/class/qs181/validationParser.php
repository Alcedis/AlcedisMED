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

class qs181ValidationParser
{
    protected $_db = null;

    protected $_validations = array();

    protected $_activeValidations = array();

    protected $_fields = array();

    protected $_layer = null;

    protected $_errors = array();

    protected $_layers = array(
        'b'     => array('b'),
        'brust' => array('b', 'brust'),
        'o'     => array('b', 'brust', 'o')
    );

    public function __construct($db)
    {
        $this->_db = $db;

        $this->_init();
    }

    protected function _init()
    {
        $validations = sql_query_array($this->_db, "SELECT * FROM l_qs_valid ORDER BY jahr, nr");

        foreach ($validations as $validation) {
            $year  = $validation['jahr'];
            $nr    = $validation['nr'];
            $layer = $validation['layer'];
            $validation['fields'] = strlen(trim($validation['fields'])) > 0
                ? explode('|', trim($validation['fields']))
                : array('patient_id')
            ;

            $this->_validations[$year][$layer][$nr] = $validation;
        }
    }

    /**
     *
     */
    public function parse()
    {
        $year = array_key_exists('AUFNDATUM', $this->_fields) === false ? null : (
            strlen($this->_fields['AUFNDATUM']) ? substr($this->_fields['AUFNDATUM'], 0, 4) : null
        );

        if ($year !== null && array_key_exists($year, $this->_validations) === true) {
            $this->_buildValidations($this->_validations[$year]);
        }

        return $this;
    }


    protected function _buildSelectFrom()
    {
        $statement = "SELECT ";
        $parts     = array();

        foreach ($this->_fields as $fieldName => $fieldValue) {
            $parts[] = concat(array(
                (strlen($fieldValue) ? "'{$fieldValue}'" : "''"),
                "'{$fieldName}'"
            ), ' as ');
        }

        return $statement . implode(', ', $parts);
    }

    protected function _buildValidations($validations)
    {
        $statement = "SELECT ";
        $parts = array();

        foreach ($validations as $validationLayer => $validationConditions) {
            if (in_array($validationLayer, $this->_layers[$this->_layer]) === true) {
                foreach ($validationConditions as $condition) {

                    $this->_activeValidations[$condition['nr']] = $condition;

                    $parts[] = "IF({$condition['bedingung']}, 1, '') AS '{$condition['nr']}'";
                }
            }
        }

        $statement .= implode(', ', $parts) . "FROM ({$this->_buildSelectFrom()}) x";

        $result = reset(sql_query_array($this->_db, $statement));

        foreach ($result as $msgNr => $error) {
            if ($error == 1) {
                $this->_errors[] = array(
                    'nr'  => $msgNr,
                    'msg' => $this->_activeValidations[$msgNr]['meldung'],
                    'fields' => $this->_activeValidations[$msgNr]['fields']
                );
            }
        }
    }

    public function getErrors()
    {
        return (count($this->_errors) > 0 ? $this->_errors : false);
    }


    public function setFields($fields = array())
    {
        foreach ($fields as $layerName => $layerFields) {
            //GEBDATUM ADD
            if ($layerName == 'b') {
                $patientId = $layerFields['patient_id'];

                $layerFields['gebdatum'] = dlookup($this->_db, 'patient', 'geburtsdatum', "patient_id = '{$patientId}'");
            }

            foreach ($layerFields as $fieldName => $fieldContent) {
                $this->_fields[strtoupper($fieldName)] = $fieldContent;
            }
        }

        return $this;
    }

    public static function create($db)
    {
        return new self($db);
    }

    /**
     * set current layer depth
     *
     * @param string $layer
     * @return qs181ValidationParser
     */
    public function setLayer($layer)
    {
        $this->_layer = $layer;

        return $this;
    }
}

?>