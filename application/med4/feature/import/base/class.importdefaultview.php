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

require_once( 'interface.importview.php' );
require_once( 'class.importbaseobject.php' );

class CImportDefaultView extends CImportBaseObject implements IImportView
{

    protected $m_configs = array();
    protected $m_fields = array();
    protected $m_current_action = '';
    protected $m_view_type = '';
    protected $m_model = null;

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Implementations from interface IExportView
    //

    public function SetModel( $model )
    {
        $this->m_model = $model;
    }

    public function BuildView( $action )
    {
        $result = false;
        $this->ReadConfigs();
        $this->SetVariables();
        switch( $action ) {
            case 'do_import' : {
                $this->CreateLogViewFields();
                $this->m_view_type = 'info';
                $this->FillFields();
                $result = true;
                break;
            }
        }
        $this->CreateBackLink();
        $this->m_smarty->assign( 'import_tpl', $this->GetTemplateFilename() );
        return $result;
    }

    public function ReadConfigs()
    {
        $this->m_smarty->config_load( 'settings/interfaces.conf' );
        $this->m_smarty->config_load( "../feature/import/{$this->GetImportName()}/{$this->GetImportName()}_{$this->GetImportVersion()}.conf", "import_{$this->GetImportName()}" );
        $this->m_smarty->config_load( FILE_CONFIG_APP );
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function SetVariables()
    {
    }

    public function CreateParameterViewFields()
    {
    }

    public function CreateLogViewFields()
    {
        //$this->m_smarty->assign( 'caption', "Pathologie-Import" );
        $this->m_smarty->assign( 'info_text', "Der Import der Pathologiebereichte war erfolgreich." );
    }

    public function CreateErrorViewFields()
    {
    }

    public function FillFields()
    {
        form2fields( $this->m_fields );
    }

    public function CreateFormular()
    {
        $item = new itemgenerator( $this->m_smarty, $this->m_db,
                                   $this->m_fields, $this->m_configs );
        $item->preselected = false;
        $item->generate_elements();
        $this->m_smarty->assign( 'view_type', $this->m_view_type );
    }

    public function CreateBackLink()
    {
        $this->m_smarty->assign( 'back_btn', 'page=extras' );
    }

    public function ShowView()
    {
        $this->CreateFormular();
    }

    public function GetViewType() {
        return $this->m_view_type;
    }

    public function GetTemplateFilename()
    {
        return "";
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

    protected function ValidateFields( $fields )
    {
        if ( !is_array( $fields ) ) {
            return false;
        }
        $validate = validate_dataset( $this->m_smarty, $this->m_db,
                                      $fields, $this->m_error_function, '' );
        if ( !$validate ) {
            return false;
        }
        return true;
    }

}

?>
