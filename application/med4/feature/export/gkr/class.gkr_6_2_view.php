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

require_once('feature/export/base/class.exportdefaultview.php');

class Cgkr_6_2_View extends CExportDefaultView
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultView
    //

    public function BuildView($action)
    {
        if (isset($_REQUEST['erstmeldungen_erneut_melden']))
        {
            $this->m_smarty->assign('erstmeldungen_erneut_melden', $_REQUEST['erstmeldungen_erneut_melden']);
        }
        return parent::BuildView($action);
    }

    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load('../feature/export/gkr/gkr_6_2.conf', 'export_gkr');
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function GetTemplateFilename()
    {
        return "../feature/export/gkr/gkr_6_2_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $this->m_fields = array();
    }

}

?>
