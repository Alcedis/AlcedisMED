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
require_once('feature/export/helper.dmp.php');

class Cdmp_2013_0_View extends CExportDefaultView
{


    /**
     * @access public
     * @return void
     */
    public function __construct()
    {
    }

    // *****************************************************************************************************************
    // Overrides from class CExportDefaultView

    public function BuildView($action)
    {
        $result = false;
        $this->ReadConfigs();
        $this->SetVariables();
        switch( $action ) {
            case 'export_start' : {
                $this->CreateParameterViewFields();
                $this->m_view_type = 'parameter';
                $this->FillFields();
                if ( $this->_validateFields( $this->m_fields ) ) {
                    $this->CreateErrorListViewFields();
                    $this->m_view_type = 'errorlist';
                    $result = true;
                }
                break;
            }
            case 'export_is_open' : {
                $this->CreateErrorListViewFields();
                $this->m_view_type = 'errorlist';
                $result = true;
                break;
            }
            case 'export_create' : {
                $this->CreateLogViewFields();
                $this->m_view_type = 'log';
                $result = true;
                break;
            }
            case 'export_delete' : {
                $this->CreateParameterViewFields();
                $this->m_view_type = 'parameter';
                $this->FillFields();
                $result = true;
                break;
            }
            case 'view_errors' : {
                $this->CreateErrorViewFields();
                $this->m_view_type = 'errors';
                $result = true;
                break;
            }
            case 'view_all_errors' : {
                $this->_CreateAllErrorsViewFields();
                $this->m_view_type = 'warnings';
                $result = true;
                break;
            }
            case 'view_warnings' : {
                $this->CreateWarningViewFields();
                $this->m_view_type = 'warnings';
                $result = true;
                break;
            }
            default : {
                $this->CreateParameterViewFields();
                $this->m_view_type = 'parameter';
                $this->FillFields();
                $result = false;
                break;
            }
        }
        $this->CreateBackLink();
        $this->m_smarty->assign('export_tpl', $this->GetTemplateFilename());
        return $result;
    }

    /**
     * @see CExportDefaultView::ReadConfigs
     */
    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load('../feature/export/dmp/dmp_2013_0.conf', 'export_dmp');
        $this->m_configs = $this->m_smarty->get_config_vars();
    }


    /**
     * @see CExportDefaultView::GetTemplateFilename
     */
    public function GetTemplateFilename()
    {
        return "../feature/export/dmp/dmp_2013_0_view.tpl";
    }


    /**
     * @see CExportDefaultView::CreateParameterViewFields
     */
    public function CreateParameterViewFields()
    {
        $orgId = $_SESSION['sess_org_id'];
        $queryMeldeUser = "
            (
                SELECT
                    u.user_id,
                    CONCAT_WS(', ', u.nachname, u.vorname) AS arzt

                FROM
                    dmp_brustkrebs_ed_2013 ed
                    INNER JOIN patient p                     ON ed.patient_id=p.patient_id
                    INNER JOIN user u                        ON ed.melde_user_id=u.user_id
                    INNER JOIN erkrankung e                  ON ed.erkrankung_id=e.erkrankung_id
                                                                AND e.erkrankung='b'

                WHERE
                    p.org_id='$orgId'

           ) UNION (
                SELECT
                    u.user_id,
                    CONCAT_WS(', ', u.nachname, u.vorname) AS arzt

                FROM
                    dmp_brustkrebs_ed_pnp_2013 ed_pnp
                    INNER JOIN patient p                     ON ed_pnp.patient_id=p.patient_id
                    INNER JOIN user u                        ON ed_pnp.melde_user_id=u.user_id
                    INNER JOIN erkrankung e                  ON ed_pnp.erkrankung_id=e.erkrankung_id
                                                                AND e.erkrankung='b'

                WHERE
                    p.org_id='$orgId'

           ) UNION (
                SELECT
                    u.user_id,
                    CONCAT_WS(', ', u.nachname, u.vorname) AS arzt

                FROM
                    dmp_brustkrebs_fd_2013 fd
                    INNER JOIN patient p                     ON fd.patient_id=p.patient_id
                    INNER JOIN user u                        ON fd.melde_user_id=u.user_id
                    INNER JOIN erkrankung e                  ON fd.erkrankung_id=e.erkrankung_id
                                                                AND e.erkrankung='b'

                WHERE
                    p.org_id='$orgId'

           )
            ORDER BY
                arzt
        ";
        $this->m_fields = array(
            'sel_melde_user_id' => array(
                'req' => 1,
                'size' => '',
                'maxlen' => '11',
                'type' => 'query',
                'ext' => $queryMeldeUser
            ),
            'sel_von_date'      => array(
                'req' => 0,
                'size' => '',
                'type' => 'date'
            ),
            'sel_bis_date'      => array(
                'req' => 0,
                'size' => '',
                'type' => 'date'
            ),
            'sel_empfaenger2'   => array(
                'req' => 0,
                'size' => '',
                'type' => 'check'
            )
        );
    }


    protected function _CreateAllErrorsViewFields()
    {
        $query = "
            SELECT
                erkrankung_id,
                patient_id,
                doku_datum,
                xml_protokoll

            FROM
                dmp_brustkrebs_{$this->m_parameters['export_type']}_2013

            WHERE
                dmp_brustkrebs_{$this->m_parameters['export_type']}_2013_id='{$this->m_parameters['export_id']}'
        ";
        $result = end(sql_query_array($this->m_db, $query));
        if (($result !== false) && is_array($result)) {
            $patientData = HDatabase::GetPatientData($this->m_db, $result['patient_id']);
            $data['export_nr'] = '';
            $data['nachname'] = $patientData['nachname'];
            $data['vorname'] = $patientData['vorname'];
            $data['geburtsdatum'] = date("d.m.Y", strtotime($patientData['geburtsdatum']));
            $data['errors'] = HelperDmp::parseErrorsFromProtocol($result['xml_protokoll']);
            $erkrankungData = HDatabase::GetErkrankungData($this->m_db, $result['erkrankung_id']);
            $data['erkrankung'] = $erkrankungData['erkrankung_bez'];
            $data['createtime'] = date( "d.m.Y H:m:s", strtotime($result['doku_datum']));
            $this->m_smarty->assign('warningitem_data', $data);
        }
    }

}

?>
