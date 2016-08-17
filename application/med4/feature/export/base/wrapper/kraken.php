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

require_once('kraken/model.php');

class exportWrapperKraken extends CExportBaseObject implements IExportWrapper
{
    /**
     * _models
     *
     * @access  protected
     * @var     exportWrapperKrakenModel
     */
    protected $_model;


    /**
     * _data
     *
     * @access  protected
     * @var     array
     */
    protected $_data;


    /**
     * @param string    $absolute_path
     * @param string    $export_name
     * @param Smarty    $smarty
     * @param resource  $db
     */
    public function __construct($absolute_path, $export_name, $smarty, $db)
    {
        parent::create($absolute_path, $export_name, $smarty, $db);
    }


    /**
     * getExportData
     *
     * @access  public
     * @param   array $parameters
     * @return  array
     * @throws  Exception
     */
    public function getExportData($parameters = array())
    {
        $data = $this->_data;

        if ($data === null) {
            if ($this->hasModel() === false) {
                throw new Exception('please set model to wrap for getting export data');
            }

            $this->_data = $data = $this->getModel()->getData();
        }

        return $data;
    }


    /**
     * setBaseModel
     *
     * @access  public
     * @param   exportWrapperKrakenModel $model
     * @return  exportWrapperKraken
     */
    public function setModel(exportWrapperkrakenModel $model)
    {
        $this->_model = $model->setDb($this->getDB());

        return $this;
    }


    /**
     * getModel
     *
     * @access  public
     * @return  exportWrapperKrakenModel
     */
    public function getModel()
    {
        return $this->_model;
    }


    /**
     * hasModel
     *
     * @access  public
     * @return  bool
     */
    public function hasModel()
    {
        return ($this->_model !== null);
    }
}
