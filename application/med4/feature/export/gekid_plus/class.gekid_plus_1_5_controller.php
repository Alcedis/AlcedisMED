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
require_once('class.gekid_plus_1_5_view.php');
require_once('class.gekid_plus_1_5_model.php');

class Cgekid_plus_1_5_Controller extends CExportDefaultController
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultController
    //

    public function Create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        parent::Create($absolute_path, $this->GetExportName(), $smarty, $db, $error_function);
        $this->m_model = new Cgekid_plus_1_5_Model;
        $this->m_model->Create(
            $absolute_path, $this->GetExportName(),
            $smarty, $db, $this->m_error_function
        );
        $this->m_model->SetParameters($this->m_parameters);
    }

    public function BuildView($action)
    {
        $this->m_view = new Cgekid_plus_1_5_View;
        $this->m_view->Create(
            $this->m_absolute_path, $this->GetExportName(),
            $this->m_smarty, $this->m_db, $this->m_error_function
        );
        $this->m_view->SetParameters($this->m_parameters);
        $this->m_view->SetModel($this->m_model);
        return $this->m_view->BuildView($action);
    }

    public function GetExportName()
    {
        return 'gekid_plus';
    }

}

?>
