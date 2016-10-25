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

/**
 * Interface registerStateMessageInterface
 */
interface registerStateMessageInterface
{
    const MANDATORY_ERROR   = 2;
    const MANDATORY_WARNING = 1;


    /**
     * build messages types
     *
     * @access  public
     * @param   registerPatient $patient
     * @param   bool $withHistory
     * @return  void
     */
    public function buildMessages(registerPatient $patient, $withHistory = true);


    /**
     * setState
     *
     * @access  public
     * @param   registerStateInterface $state
     * @return  $this
     */
    public function setState(registerStateInterface $state);


    /**
     * getState
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function getState();


    /**
     * getMessageType
     *
     * @access  public
     * @return  string
     */
    public function getMessageType();


    /**
     * getSectionName
     *
     * @access  public
     * @return  string
     */
    public function getSectionName();


    /**
     * setDb
     *
     * @access  public
     * @param   resource $db
     * @return  registerStateMessageInterface
     */
    public function setDb($db);


    /**
     * addMandatory
     *
     * @access  public
     * @param   string       $section
     * @param   array|string $fieldNames
     * @param   int          $mandatoryType
     * @param   callable     $onCondition
     * @param   callable     $fieldCondition
     * @param   array        $mandatoryValues
     * @return  registerStateMessageInterface
     */
    public function addMandatory(
        $section,
        $fieldNames,
        $mandatoryType = self::MANDATORY_ERROR,
        callable $onCondition = null,
        callable $fieldCondition = null,
        array $mandatoryValues = array()
    );


    /**
     * getMandatories
     *
     * @access  public
     * @return  array
     */
    public function getMandatories();


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb();


    /**
     * addIgnoreOnDiff
     *
     * @access  public
     * @param   string $section
     * @param   string $field
     * @return  registerStateMessageInterface
     */
    public function addIgnoreOnDiff($section, $field = null);


    /**
     * getIgnoreOnDiff
     *
     * @access  public
     * @return  array
     */
    public function getIgnoreOnDiff();
}
