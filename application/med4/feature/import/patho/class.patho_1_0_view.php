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

require_once( 'feature/import/base/class.exportdefaultview.php' );

class Cpatho_1_0_View extends CImportDefaultView
{

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultView
    //

    public function GetTemplateFilename()
    {
        return "../feature/import/patho/patho_1_0_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $this->m_fields = array();
    }

}

?>
