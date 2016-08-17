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
require_once('class.historymanager.php');

class Chistory_1_0_View extends CExportDefaultView
{

    //*********************************************************************************************
    //
    // Overrides from class CExportDefaultView
    //

    public function ReadConfigs()
    {
        parent::ReadConfigs();
        $this->m_smarty->config_load('../feature/export/history/history_1_0.conf', 'export_history');
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
        return "../feature/export/history/history_1_0_view.tpl";
    }


    /**
     * historiesToArrays
     *
     * @access  public
     * @param   array $histories
     * @return  array
     */
    public function historiesToArrays($histories)
    {
        $result = array();

        foreach ($histories as $history) {
            $result[] = $history->toArray();
        }

        return $result;
    }


    /**
     * BuildView
     *
     * @access  public
     * @param   string $action
     * @return  bool
     */
    public function BuildView($action)
    {
        if ($action == "delete") {
            $historyManager = CHistoryManager::getInstance();
            $historyManager->initialise($this->m_db, $this->m_smarty);
            $historyManager->deleteHistoryById(
                $this->m_parameters['exportName'],
                $this->m_parameters['org_id'],
                $this->m_parameters['user_id'],
                $this->m_parameters['historyId']
            );

            return parent::BuildView("start_export");
        }

        return parent::BuildView($action);
    }


    /**
     * CreateParameterViewFields
     *
     * @access  public
     * @return  void
     */
    public function CreateParameterViewFields()
    {
        $historyManager = CHistoryManager::getInstance();
        $historyManager->initialise($this->m_db, $this->m_smarty);
        $histories = $historyManager->getHistories(
            $this->m_parameters['exportName'],
            $this->m_parameters['org_id'],
            $this->m_parameters['user_id']
        );

        $this->m_smarty->assign('histories', $this->historiesToArrays($histories));
    }


    /**
     * CreateBackLink
     *
     * @access  public
     * @return  void
     */
    public function CreateBackLink()
    {
        $smarty = $this->m_smarty;

        $button = $smarty->get_template_vars('back_btn');

        if ($button === null) {
            $link = "page={$this->m_parameters['exportName']}&feature=export";

            // Auch nicht schön!!! :-(
            if ('qsmed' == $this->m_parameters['exportName']) {
                $link = "page={$this->m_parameters['exportName']}&feature=exports";
            }

            $smarty->assign('back_btn', $link);
        }
    }

}

?>
