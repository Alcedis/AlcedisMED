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

/**
 * Class Concobox_prostata_e_5_3_1_View
 */
class Concobox_prostata_e_5_3_1_View extends CExportDefaultView
{
    /**
     * ReadConfigs
     *
     * @access  public
     * @return  void
     */
    public function ReadConfigs()
    {
        parent::ReadConfigs();

        $this->m_smarty->config_load('../feature/export/oncobox_prostata/oncobox_prostata_e_5_3_1.conf', 'export_oncobox_prostata');

        $this->m_configs = $this->m_smarty->get_config_vars();
    }


    /**
     * GetTemplateFilename
     *
     * @access  public
     * @return  string
     */
    public function GetTemplateFilename()
    {
        return "../feature/export/oncobox_prostata/oncobox_prostata_e_5_3_1_view.tpl";
    }


    /**
     * CreateParameterViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateParameterViewFields()
    {
        $this->m_fields = array();
    }


    /**
     * CreateBackLink
     *
     * @access  public
     * @return  void
     */
    public function CreateBackLink()
    {
        $this->m_smarty->assign('back_btn', 'page=auswertungen');
    }
}

?>
