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

require_once('feature/export/base/class.exportdefaultcontroller.php');
require_once('class.oncobox_prostata_e_5_3_1_view.php');
require_once('class.oncobox_prostata_e_5_3_1_model.php');

/**
 * Class Concobox_prostata_e_5_3_1_Controller
 */
class Concobox_prostata_e_5_3_1_Controller extends CExportDefaultController
{
    /**
     * GetExportName
     *
     * @access  public
     * @return  string
     */
    public function getExportName()
    {
        return 'oncobox_prostata';
    }


    /**
     * Create
     *
     * @access  public
     * @param   string  $absolute_path
     * @param   string  $export_name
     * @param   Smarty  $smarty
     * @param   Object  $db
     * @param   string  $error_function
     * @return  void
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        parent::create($absolute_path, $this->GetExportName(), $smarty, $db, $error_function);

        $model = new Concobox_prostata_e_5_3_1_Model;

        $model->create($absolute_path, $this->getExportName(), $smarty, $db, $this->m_error_function);
        $model->setParameters($this->m_parameters);

        $this->setModel($model);
    }


    /**
     * BuildView
     *
     * @access  public
     * @param   string  $action
     * @return  bool
     */
    public function BuildView($action)
    {
        $view = new Concobox_prostata_e_5_3_1_View;

        $view->create($this->getAbsolutePath(), $this->GetExportName(), $this->getSmarty(), $this->getDB(), $this->getErrorFunction());

        $view->setParameters($this->m_parameters);
        $view->setModel($this->getModel());

        $this->setView($view);

        return $view->buildView($action);
    }
}

?>
