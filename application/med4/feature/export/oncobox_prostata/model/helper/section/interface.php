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

interface Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
{
    /**
     * setParameter
     *
     * @access  public
     * @param   array   $parameters
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
     */
    public function setParameters($parameters);


    /**
     * getParameter
     *
     * @access  public
     * @param   string  $param
     * @return  array
     */
    public function getParameter($param);


    /**
     * returns cache
     *
     * @access  public
     * @param   string  $method
     * @return  array
     */
    public function getCache($method);


    /**
     * sets cache
     *
     * @access  public
     * @param   string  $method
     * @param   string  $value
     * @return  void
     */
    public function setCache($method, $value);


    /**
     * return first filled value in forms
     *
     * @access
     * @param   $records
     * @param   $form
     * @param   $value
     * @return  string
     */
    public function getFirstFilled($records, $form, $value);


    /**
     * returns field, when conditionalField has conditionalValue
     *
     * @access  public
     * @param   array  $records
     * @param   string $form
     * @param   string $field
     * @param   string $conditionField
     * @param   string $conditionValue
     * @return  string
     */
    public function getConditionalFirstFilled($records, $form, $field, $conditionField, $conditionValue = 'filled');


    /**
     * returns array, when conditionalField has conditionalValue
     *
     * @access  public
     * @param   array  $records
     * @param   string $form
     * @param   string $field
     * @param   string $conditionField
     * @param   string $conditionValue
     * @return  array
     */
    public function getConditionalAllFilled($records, $form, $field, $conditionField, $conditionValue = 'filled');


    /**
     * get field with content from tumorstate where t starts with p
     *
     * @access  public
     * @param   array   $records
     * @param   string  $field
     * @return  string
     */
    public function getFirstFilledFromTumorstateP($records, $field);


    /**
     * ifEmpty
     *
     * @access  public
     * @param   string  $string
     * @param   string  $string2
     * @return  string
     */
    public function ifEmpty($string, $string2);


    /**
     * resetCache
     *
     * @access  public
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
     */
    public function resetCache();


    /**
     * setTemplates
     *
     * @access  public
     * @param   array   $templates
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
     */
    public function setTemplates($templates);


    /**
     * getTemplates
     *
     * @access  public
     * @return  array
     */
    public function getTemplates();


    /**
     * getFromTemplate
     *
     * @access  public
     * @param   string  $name
     * @param   int $id
     * @return  array
     */
    public function getFromTemplate($name, $id);
}
