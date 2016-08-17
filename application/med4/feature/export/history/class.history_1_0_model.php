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

require_once('feature/export/base/class.exportdefaultmodel.php');
require_once('core/class/report/helper.reports.php');
require_once('feature/export/base/helper.common.php');

class Chistory_1_0_Model extends CExportDefaultModel
{

    protected $_historyManager = null;

    public function __construct()
    {
    }

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultModel
    //

    public function ExtractData($parameters, $wrapper, &$export_record)
    {
    }

    public function PreparingData($parameters, &$export_record)
    {
    }

    public function HandleDiff($parameters, $case, &$section, $old_section)
    {
    }

    public function CheckData($parameters, &$export_record)
    {
    }

    public function WriteData()
    {
    }

    //*********************************************************************************************
    //
    // Helper functions
    //

}

?>
