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

require_once( 'interface.importcontroller.php' );
require_once( 'class.importbaseobject.php' );
require_once( 'class.importexception.php' );
require_once( 'class.importdefaultview.php' );
require_once( 'helper.database.php' );

class CImportDefaultController extends CImportBaseObject implements IImportController
{

    protected $m_permission = null;
    protected $m_action = '';
    protected $m_load_export_parameters = true;
    protected $m_view = null;

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CImportBaseObject
    //

    public function LoadExportParameters( $load_export_parameters )
    {
        $this->m_load_export_parameters = $load_export_parameters;
    }

    public function SetParameters( &$parameters )
    {
        if ( $this->m_load_export_parameters ) {
            HDatabase::LoadSettings( $this->m_db, $parameters, $this->GetImportName() );
        }
        parent::SetParameters( $parameters );
        if ( $this->m_view != null ) {
            $this->m_view->SetParameters( $parameters );
        }
    }

    //*********************************************************************************************
    //
    // Implementations from interface IImportController
    //

    public function DoStartup( $permission, $action = '' )
    {
        if ( $permission === null ) {
            throw new EExportException( "ERROR: Permission object is NULL." );
        }
        $this->m_permission = $permission;
        $this->m_action = 'do_import'; //$action;
        if ( ( $this->BuildView( $this->m_action ) ) &&
             ( $this->m_permission->action( $this->m_action,
             								                "import_{$this->GetImportName()}" ) === true ) ) {
            $this->HandleAction();
        }
        else {
            // TODO: ???
        }
        $this->ShowView();
    }

    public function BuildView( $action )
    {
        $this->m_view = new CImportDefaultView;
        $this->m_view->Create( $this->m_absolute_path, $this->GetImportName(), $this->GetImportVersion(),
                               $this->m_smarty, $this->m_db, $this->m_error_function );
        $this->m_view->SetParameters( $this->m_parameters );
        return $this->m_view->BuildView( $action );
    }

    public function HandleAction()
    {
        switch( $this->m_action ) {
            case 'do_import' :
                $this->DoImport();
                break;
            default :
                die( "Unknown action [{$this->m_action}]" );
                break;
        }
    }

    protected function DoImport() {
    }

    public function ShowView()
    {
        $this->m_view->ShowView();
    }

}

?>
