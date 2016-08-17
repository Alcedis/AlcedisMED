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

require_once('interface.exportobject.php');

/**
 * Interface IExportModel
 */
interface IExportModel extends IExportObject
{
    /**
     * CreateExport
     *
     * @access  public
     * @param   array   $parameter
     * @return  bool
     */
    public function createExport($parameter);


    /**
     * setSerialiser
     *
     * @access  public
     * @param   IExportSerialiser   $serialiser
     * @return  void
     * @throws  EExportException
     */
    public function setSerialiser($serialiser);


    /**
     * HandleDiff
     *
     * @access  public
     * @param   array   $parameters
     * @param   string  $case
     * @param   array   $section
     * @param   array   $old_section
     * @return  void
     */
    public function handleDiff($parameters, $case, &$section, $old_section);


    /**
     * IsOpenExport
     *
     * @access  public
     * @return  bool
     * @throws  EExportException
     */
    public function isOpenExport();


    /**
     * getExportUniqueId
     *
     * @access  public
     * @return  string
     * @throws  EExportException
     */
    public function getExportUniqueId();


    /**
     * isNewExport
     *
     * @access  public
     * @return  bool
     */
    public function isNewExport();


    /**
     * getData
     *
     * @access  public
     * @return  RExport
     * @throws  EExportException
     */
    public function getData();


    /**
     * extractData
     *
     * @access  public
     * @param   array           $parameters
     * @param   IExportWrapper  $wrapper
     * @param   RExport         $export_record
     * @return  void
     */
    public function extractData($parameters, $wrapper, &$export_record);


    /**
     * PreparingData
     *
     * @access  public
     * @param   array   $parameters
     * @param   RExport$export_record
     * @return  void
     */
    public function preparingData($parameters, &$export_record);


    /**
     * CheckDiff
     *
     * @access  public
     * @param   array   $parameters
     * @param   RExport $export_record
     * @param   RExport $before_export_record
     * @return  void
     */
    public function checkDiff($parameters, &$export_record, $before_export_record);


    /**
     * CheckData
     *
     * @access  public
     * @param   array   $parameters
     * @param   RExport $export_record
     * @return  void
     */
    public function checkData($parameters, &$export_record);


    /**
     * WriteData
     *
     * @access  public
     * @return  void
     */
    public function writeData();


    /**
     * DeleteData
     *
     * @access  public
     * @return  void
     * @throws  EExportException
     */
    public function deleteData();



    /**
     * GetExportRecord
     *
     * @access  public
     * @return  RExport
     */
    public function getExportRecord();


    /**
     * hasExportRecord
     *
     * @access  public
     * @return  bool
     */
    public function hasExportRecord();


    /**
     * setExportRecord
     *
     * @access  public
     * @param   RExport $record
     * @return  IExportModel
     */
    public function setExportRecord($record);


    /**
     * getWrapper
     *
     * @access  public
     * @return  IExportWrapper
     */
    public function getWrapper();


    /**
     * setCheckSituationOnDiff
     *
     * @access  public
     * @param   bool $bool
     * @return  IExportModel
     */
    public function setCheckSituationOnDiff($bool = true);


    /**
     * hasCheckSituationOnDiff
     *
     * @access  public
     * @return  bool
     */
    public function hasCheckSituationOnDiff();
}
