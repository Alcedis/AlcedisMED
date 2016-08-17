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

require_once 'reports/scripts/reportPreSelects.php';


/**
 * Class reportHelper
 */
class reportHelper extends reportPreSelects
{
    /**
     *
     * @param type $value
     * @return boolean
     */
    protected function IsTrue( $value ) {
        if ( is_string( $value ) &&
             ( strlen( $value ) > 0 ) &&
             ( $value == '1' ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  $value == 1 ) {
            return true;
        }
        return false;
    }


    /**
     *
     * @param type $value
     * @return boolean
     */
    protected function IsFalse( $value ) {
        if ( is_null( $value ) ) {
            return true;
        }
        else if ( is_string( $value ) &&
             ( ( strlen( $value ) == 0 ) ||
               ( $value == '0' ) ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  $value == 0 ) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $row
     * @return boolean
     */
    protected function IsTNSet( $row ) {
        if ( ( strlen( $row[ 'ct' ] ) > 0 ) &&
             ( strlen( $row[ 'cn' ] ) > 0 ) ) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $row
     * @return boolean
     */
    protected function IsMSet( $row ) {
        if ( strlen( $row[ 'm' ] ) > 0 ) {
            return true;
        }
        return false;
    }

    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCnPlus($row)
    {
        if ((strlen($row['cn']) > 0) &&
            (str_starts_with($row['cn'], "cN1") ||
                str_starts_with($row['cn'], "cN2") ||
                str_starts_with($row['cn'], "cN3"))) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPnPlus($row)
    {
        if ((strlen($row['pn']) > 0) &&
            (str_starts_with($row['pn'], "pN1") ||
                str_starts_with($row['pn'], "pN2") ||
                str_starts_with($row['pn'], "pN3"))) {
            return true;
        }
        else if ((strlen($row['pn_sn']) > 0) &&
            (str_starts_with($row['pn_sn'], "pN1") ||
                str_starts_with($row['pn_sn'], "pN2") ||
                str_starts_with($row['pn_sn'], "pN3"))) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    public function hasNPlus($row)
    {
        if ($this->_hasCnPlus($row) === true || $this->_hasPnPlus($row) === true) {
            return true;
        }
        return false;
    }


    /**
     * checkValueRange
     *
     * @access  public
     * @param   string       $string
     * @param   array|string $values
     * @return  bool
     */
    public function checkValueRange($string, $values)
    {
        $values = is_array($values) === false ? array($values) : $values;

        $inArray = array();
        $startsWith = array();

        foreach ($values as $value) {
            if (str_ends_with($value, '*') === true) {
                $startsWith[] = substr($value, 0, -1);
            } else {
                $inArray[] = $value;
            }
        }

        return (in_array($string, $inArray) || str_starts_with($string, $startsWith));
    }


    /**
     * map
     *
     * @access  public
     * @param   string  $value
     * @param   array   $lookups
     * @param   string  $default
     * @return  string
     */
    public function map($value, array $lookups, $default = NULL)
    {
        return (array_key_exists($value, $lookups) === true ? $lookups[$value] : $default);
    }
}
?>
