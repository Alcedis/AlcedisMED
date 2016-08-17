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

require_once('core/class/report/helper.reports.php');
require_once('class.historyzipfile.php');

class CHistory
{

    protected $_exportHistoryId = 0;
    protected $_exportLogId = 0;
    protected $_exportName = "";
    protected $_orgId = 0;
    protected $_userId = 0;
    protected $_date = "1900-01-01";
    protected $_filter = array();
    protected $_zipFile = null;
    protected $_historiesPath = "";


    public function __construct()
    {
        $this->clear();
    }

    public function setFromDatabase($row)
    {
        $this->clear();
        if (isset($row['export_history_id']))
        {
            $this->_exportHistoryId = $row['export_history_id'];
        }
        if (isset($row['export_log_id']))
        {
            $this->_exportLogId = $row['export_log_id'];
        }
        if (isset($row['export_name']))
        {
            $this->_exportName = $row['export_name'];
        }
        if (isset($row['org_id']))
        {
            $this->_orgId = $row['org_id'];
        }
        if (isset($row['user_id']))
        {
            $this->_userId = $row['user_id'];
        }
        if (isset($row['date']))
        {
            $this->_date = $row['date'];
        }
        if (isset($row['filter']))
        {
            $this->_filter = $this->filterToArray($row['filter']);
        }
        if (isset($row['file']))
        {
            $this->setZipFileUrl($row['file']);
        }
    }


    /**
     * clear
     *
     * @access  public
     * @return  CHistory
     */
    public function clear()
    {
        $this->_exportHistoryId = 0;
        $this->_exportLogId = 0;
        $this->_exportName = "";
        $this->_orgId = 0;
        $this->_userId = 0;
        $this->_date = "1900-01-01";
        $this->_filter = array();
        $this->_zipFile = new CHistoryZipFile;

        return $this;
    }


    /**
     * setHistoriesPath
     *
     * @access  public
     * @param   string  $historiesPath
     * @return  CHistory
     */
    public function setHistoriesPath($historiesPath)
    {
        $this->_historiesPath = $historiesPath;

        return $this;
    }


    /**
     * setExportHistoryId
     *
     * @access  public
     * @param   int $exportHistoryId
     * @return  CHistory
     */
    public function setExportHistoryId($exportHistoryId)
    {
        $this->_exportHistoryId = $exportHistoryId;

        return $this;
    }

    public function getExportHistoryId()
    {
        return $this->_exportHistoryId;
    }

    public function setExportLogId($exportLogId)
    {
        $this->_exportLogId = $exportLogId;
    }

    public function getExportLogId()
    {
        return $this->_exportLogId;
    }

    public function setExportName($exportName)
    {
        $this->_exportName = $exportName;
    }

    public function getExportName()
    {
        return $this->_exportName;
    }

    public function setOrgId($orgId)
    {
        $this->_orgId = $orgId;
    }

    public function getOrgId()
    {
        return $this->_orgId;
    }

    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function setDate($date)
    {
        $this->_date = $date;
    }

    public function getDate()
    {
        return $this->_date;
    }

    public function setFilter($filter)
    {
        $this->_filter = $filter;
    }

    public function addFilter($key, $value)
    {
        $this->_filter[$key] = $value;
    }

    public function getFilter()
    {
        return $this->_filter;
    }

    public function setZipFileUrl($zipFileUrl)
    {
        $pathParts = pathinfo($zipFileUrl);
        $this->_zipFile->setPath($pathParts['dirname']);
        $this->_zipFile->setFilename($pathParts['basename']);
    }

    public function setFile($file)
    {
        $this->_zipFile->addFile($file);
    }

    public function setFiles($files)
    {
        $this->_zipFile->addFiles($files);
    }

    public function getZipFileUrl()
    {
        return $this->_zipFile->getFileUrl();
    }

    protected function createZipFileUrl()
    {
        $url = CHistoryZipFile::checkPath($this->_historiesPath);
        $url .= $this->_exportName . '/';
        $url .= $this->_orgId . '/';
        $url .= $this->_userId . '/';
        $url .= $this->_exportName . '_' . date('Y-m-d_H-i-s') . '.zip';
        return $url;
    }

    public function create()
    {
        if (0 === $this->_exportHistoryId) {
            $this->setZipFileUrl($this->createZipFileUrl());
            if (!file_exists($this->_zipFile->getPath())) {
                mkdir($this->_zipFile->getPath(), 0777, true);
            }
            $this->_zipFile->create();
        }
    }


    /**
     * read
     *
     * @access  public
     * @param   resource $db
     * @return  CHistory
     */
    public function read($db)
    {
        $id = $this->getExportHistoryId();

        $result = sql_query_array($db, "SELECT * FROM export_history WHERE export_history_id = '{$id}'");

        if (count($result) > 0){
            $this->setFromDatabase(reset($result));
        }

        return $this;
    }


    /**
     * delete
     *
     * @access  public
     * @param   resource $db
     * @return  void
     */
    public function delete($db)
    {
        if (0 !== $this->_exportHistoryId) {
            $this->_zipFile->delete();
            $this->_removeAllCases($db, $this->_exportLogId);

            $query = "DELETE FROM export_log WHERE export_log_id={$this->_exportLogId}";
            sql_query_array($db, $query);

            // Nicht schön! :-(
            if ('onkeyline' === $this->_exportName) {
                $query = "
                    DELETE FROM
                        export_patient_ids_log

                    WHERE
                        export_name='{$this->_exportName}'
                        AND export_log_id={$this->_exportLogId}
                ";
                sql_query_array($db, $query);
            }
        }
    }


    /**
     * filterToString
     *
     * @access  protected
     * @param   array   $filter
     * @return  string
     */
    protected function filterToString(array $filter)
    {
        $str = "";

        foreach ($filter as $key => $value) {
            if (strlen($str) == 0) {
                $str = $key . HReports::SEPARATOR_COLS . $value;
            } else {
                $str .= HReports::SEPARATOR_ROWS . $key . HReports::SEPARATOR_COLS . $value;
            }
        }

        return $str;
    }


    /**
     * filterToArray
     *
     * @access  protected
     * @param   string  $filter
     * @return  array
     */
    protected function filterToArray($filter)
    {
        $result = array();
        $rows = explode(HReports::SEPARATOR_ROWS, $filter);

        if (is_array($rows) === true) {
            foreach ($rows as $row) {
                $cols = explode(HReports::SEPARATOR_COLS, $row);
                $result[$cols[0]] = $cols[1];
            }
        }

        return $result;
    }


    /**
     * toArray
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        return array(
            'export_history_id' => $this->_exportHistoryId,
            'export_log_id'     => $this->_exportLogId,
            'export_name'       => $this->_exportName,
            'org_id'            => $this->_orgId,
            'user_id'           => $this->_userId,
            'date'              => date('d.m.Y', strtotime($this->_date)),
            'filter'            => $this->_filter,
            'file'              => $this->_zipFile->getFilename(),
            'url'               => $this->_zipFile->getFileUrl()
        );
    }

    public function toDatabaseArray()
    {
        return array(
            'export_history_id' => $this->_exportHistoryId,
            'export_log_id'     => $this->_exportLogId,
            'export_name'       => $this->_exportName,
            'org_id'            => $this->_orgId,
            'user_id'           => $this->_userId,
            'date'              => $this->_date,
            'filter'            => $this->filterToString($this->_filter),
            'file'              => $this->_zipFile->getFileUrl(),
            'createuser'        => null,
            'createtime'        => null,
            'updateuser'        => null,
            'updatetime'        => null
        );
    }

    protected function _removeAllCases($db, $exportLogId)
    {
        if ($exportLogId > 0) {
            $caseIds = dlookup($db, 'export_case_log', "GROUP_CONCAT(DISTINCT export_case_log_id)", "export_log_id={$exportLogId} GROUP BY export_log_id");

            if (strlen($caseIds) > 0) {
                $queries = array(
                    "DELETE FROM export_case_log WHERE export_case_log_id IN ({$caseIds})",
                    "DELETE FROM export_section_log WHERE export_case_log_id IN ({$caseIds})"
                );

                foreach ($queries as $query) {
                    @mysql_query($query, $db);
                }
            }
        }
    }

    protected function _removeAllSections($db, $exportCaseLogId)
    {
        $query = "
            SELECT
                *

            FROM
                export_section_log esl

            WHERE
                esl.export_case_log_id={$exportCaseLogId}

            ORDER BY
                esl.createtime
        ";
        $result = sql_query_array($db, $query);
        if ($result !== false) {
            foreach ($result as $row) {
                $query = "
                    DELETE FROM
                        export_section_log

                    WHERE
                        export_section_log_id={$row['export_section_log_id']}
                ";
                sql_query_array($db, $query);
            }
        }
    }

}
