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

class Cgekid_plus_1_5_View extends CExportDefaultView
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
        $this->m_smarty->config_load('../feature/export/gekid_plus/gekid_plus_1_5.conf', 'export_gekid_plus');
        $this->m_configs = $this->m_smarty->get_config_vars();
    }

    public function GetTemplateFilename()
    {
        return "../feature/export/gekid_plus/gekid_plus_1_5_view.tpl";
    }

    public function CreateParameterViewFields()
    {
        $queryMeldeUser = "
            SELECT DISTINCT
                u.user_id,
                CONCAT_WS( ', ', u.nachname, u.vorname ) AS arzt

            FROM patient p
                INNER JOIN ekr e  ON e.patient_id=p.patient_id
                INNER JOIN user u ON e.user_id=u.user_id

            WHERE
                p.org_id='{$this->m_parameters['org_id']}'

            ORDER BY
                arzt
        ";
        $this->m_fields = array(
            'sel_melde_user_id' => array(
                'req' => 1,
                'size' => '',
                'maxlen' => '11' ,
                'type' => 'query',
                'ext' => $queryMeldeUser
            ),
            'sel_von_date'      => array(
                'req' => 1,
                'size' => '',
                'type' => 'date'
            ),
            'sel_bis_date'      => array(
                'req' => 1,
                'size' => '',
                'type' => 'date'
            )
        );
    }

}

?>
