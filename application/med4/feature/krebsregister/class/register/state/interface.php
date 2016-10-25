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
 * Interface registerStateInterface
 */
interface registerStateInterface
{
    /**
     * getPatients
     *
     * @access  public
     * @param   bool $export
     * @return  registerPatientCollection
     */
    public function getPatients($export = false);


    /**
     * get patient ids from cache if exists
     *
     * @access  public
     * @return  array
     */
    public function getCachePatientIds();


    /**
     * getConfig
     *
     * @access  public
     * @param   string  $var
     * @param   string  $section
     * @return  array|string
     */
    public function getConfig($var = null, $section = 'default');


    /**
     * getMap
     *
     * @access  public
     * @param   string  $name
     * @return  array
     */
    public function getMap($name);


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb();


    /**
     * getQuery
     *
     * @access  public
     * @return  registerQueryDefault
     */
    public function getQuery();


    /**
     * getSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getSmarty();


    /**
     * getType
     *
     * @access  public
     * @return  string
     */
    public function getType();


    /**
     * getXml
     *
     * @access  public
     * @return  string
     */
    public function getXml();


    /**
     * map
     *
     * @access  public
     * @param   string  $mapping
     * @param   string  $value
     * @param   bool $default
     * @return  mixed
     */
    public function map($mapping, $value, $default = false);


    /**
     * loadArchive
     *
     * @access  public
     * @param   int          $exportLogId
     * @param   array|string $patientIds
     * @return  registerStateInterface
     */
    public function loadArchive($exportLogId, $patientIds = null);


    /**
     * setPatientIdFilter
     *
     * @access  public
     * @param   array $patientIds
     * @return  registerStateInterface
     */
    public function setPatientIdFilter(array $patientIds = array());


    /**
     * getMessageBuilder
     *
     * @access  public
     * @param   string $type
     * @return  registerStateMessageInterface
     */
    public function getMessageBuilder($type);


    /**
     * getPatientIdFilter
     *
     * @access  public
     * @param   bool $concat
     * @return  array|string
     */
    public function getPatientIdFilter($concat = false);


    /**
     * resetPatients
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function resetPatients();


    /**
     * addToMap
     *
     * @access  public
     * @param   string  $name
     * @param   array   $values
     * @return  registerStateInterface
     */
    public function addToMap($name, array $values = array());


    /**
     * isCached
     *
     * @access  public
     * @return  bool
     */
    public function isCached();


    /**
     * getExportRecord
     *
     * @access  public
     * @return  RKrExport
     */
    public function getExportRecord();


    /**
     * _addAdditionalItems
     * (will be extended from state)
     *
     * @access  public
     * @param   registerPatientCase $patientCase
     * @return  array
     */
    public function addAdditionalItems(registerPatientCase $patientCase);


    /**
     * refreshCache
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function refreshCache();


    /**
     * prepare register for initialy export patient with id
     *
     * @access  public
     * @param   int $patientId
     * @return  registerStateInterface
     */
    public function prepareInitialExport($patientId);


    /**
     * getMessenger
     *
     * @access  public
     * @return  registerMessengerCollection
     */
    public function getMessenger();


    /**
     * getSerializer
     *
     * @access  public
     * @return  registerExportSerializer
     */
    public function getSerializer();


    /**
     * getAdditionalClassificationFields
     *
     * @access  public
     * @return  array
     */
    public function getAdditionalClassificationFields();


    /**
     * writeXml
     *
     * @access  public
     * @return  int
     */
    public function writeXml();
}
