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

require_once( 'feature/export/base/class.exportdefaultview.php' );

class Cwbc_2012_0_View extends CExportDefaultView
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultView
    //

    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load( '../feature/export/wbc/wbc_2012_0.conf', 'export_wbc' );
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function GetTemplateFilename()
    {
        return "../feature/export/wbc/wbc_2012_0_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $this->m_fields = array(
            'sel_von_date' => array( 'req' => 0, 'size' => '', 'type' => 'date' ),
            'sel_bis_date' => array( 'req' => 0, 'size' => '', 'type' => 'date' )
        );
    }

}

?>
