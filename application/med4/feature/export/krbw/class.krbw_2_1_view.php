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

class Ckrbw_2_1_View extends CExportDefaultView
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
        if (isset($_REQUEST['melder_id']) && isset($_REQUEST['pruefcode']))
        {
            $this->m_smarty->assign('melder_id', $_REQUEST['melder_id']);
            $this->m_smarty->assign('pruefcode', $_REQUEST['pruefcode']);
        }
        return parent::BuildView($action);
    }

    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load( '../feature/export/krbw/krbw_2_1.conf', 'export_krbw' );
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function GetTemplateFilename()
    {
        return "../feature/export/krbw/krbw_2_1_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $this->m_fields = array(
            'melder_id' => array( 'req' => 1, 'size' => 13, 'maxlen' => '13', 'type' => 'string', 'ext' => '' ),
            'pruefcode' => array( 'req' => 1, 'size' => 50, 'maxlen' => '50', 'type' => 'string', 'ext' => '' )
        );
    }

}

?>
