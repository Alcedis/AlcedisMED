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

require_once('class.history.php');

class CHistoryManager
{
    /**
     * _instance
     *
     * @access  protected
     * @var     CHistoryManager
     */
    protected static $_instance;


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;


    /**
     * _historiesPath
     *
     * @access  protected
     * @var     string
     */
    protected $_historiesPath = "";


    /**
     * _callbacks
     *
     * @access  protected
     * @var     array
     */
    protected $_callbacks = array();


    /**
     * getInstance
     *
     * @static
     * @access  public
     * @return  CHistoryManager
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new CHistoryManager;
        }

        return self::$_instance;
    }


    /**
     * initialise
     *
     * @access  public
     * @param   resource $db
     * @param   Smarty   $smarty
     * @param   string   $historysPath
     * @return  void
     */
    public function initialise($db, $smarty, $historysPath = '')
    {
        $this->_db = $db;
        $this->_smarty = $smarty;

        if (strlen($historysPath) > 0) {
            $this->_historiesPath = $historysPath;
        } else {
            $this->_historiesPath = appSettings::get('historys_path');
        }
    }


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb()
    {
        return $this->_db;
    }


    /**
     * addCallback
     *
     * @access  public
     * @param   string   $onAction
     * @param   callable $method
     * @return  CHistoryManager
     */
    public function addCallback($onAction, callable $method)
    {
        $this->_callbacks[$onAction][] = $method;

        return $this;
    }


    /**
     * executeCallback
     *
     * @access  public
     * @param   string $event
     * @param   array  $params
     * @return  CHistoryManager
     */
    public function executeCallback($event, array $params = array())
    {
        if (array_key_exists($event, $this->_callbacks) === true) {
            foreach ($this->_callbacks[$event] as $callback) {
                $callback($this, $params);
            }
        }

        return $this;
    }


    /**
     * getHistories
     *
     * @access  public
     * @param   string  $exportName
     * @param   int     $orgId
     * @param   int     $userId
     * @return  array
     */
    public function getHistories($exportName, $orgId, $userId)
    {
        $histories = array();

        $conditions = [
            "org_id = '{$orgId}'"
        ];

        // TODO make it configurable
        if ($exportName !== 'kr_he') {
            $conditions[] = "user_id = '{$userId}'";
        }

        $conditions = implode(' AND ', $conditions);

        $query = "
            SELECT
                export_history_id,
                export_log_id,
                export_name,
                org_id,
                user_id,
                date,
                filter,
                file
            FROM
                export_history eh
            WHERE
                export_name = '{$exportName}' AND
                {$conditions}
            ORDER BY
                createtime DESC
        ";

        $result = sql_query_array($this->_db, $query);

        if ($result !== false) {
            foreach ($result as $row) {
                $history = new CHistory;
                $history->setFromDatabase($row);
                $history->setHistoriesPath($this->_historiesPath);
                $histories[] = $history;
            }
        }

        return $histories;
    }


    /**
     * createHistory
     *
     * @access  public
     * @return  CHistory
     */
    public function createHistory()
    {
        $history = new CHistory();
        $history->setHistoriesPath($this->_historiesPath);

        return $history;
    }


    /**
     * insertHistory
     *
     * @access  public
     * @param   CHistory $history
     * @return  void
     */
    public function insertHistory(CHistory $history)
    {
        $history->create();

        $tmpFields = dataArray2fields($history->toDatabaseArray());

        execute_insert($this->_smarty, $this->_db, $tmpFields, 'export_history', 'insert', false, $history->getUserId());

        $historyId = dlookup($this->_db, 'export_history', 'export_history_id', 'export_log_id = ' . $history->getExportLogId());

        $history->setExportHistoryId($historyId);
    }


    /**
     * deleteHistory
     *
     * @access  public
     * @param   CHistory $history
     * @return  void
     */
    public function deleteHistory(CHistory $history)
    {
        $this->executeCallback('beforeDelete', array($history));

        $tmpFields = dataArray2fields($history->toDatabaseArray());

        execute_delete(
            $this->_smarty,
            $this->_db,
            $tmpFields,
            'export_history',
            "export_history_id={$history->getExportHistoryId()}",
            'delete'
        );

        $history->delete($this->_db);

        $this->executeCallback('afterDelete', array($history));
    }


    /**
     * deleteHistoryById
     *
     * @access  p
     * @param $exportName
     * @param $orgId
     * @param $userId
     * @param $historyId
     * @return  void
     */
    public function deleteHistoryById($exportName, $orgId, $userId, $historyId)
    {
        $conditions = [
            "org_id = '{$orgId}'"
        ];

        // TODO make it configurable
        if ($exportName !== 'kr_he') {
            $conditions[] = "user_id = '{$userId}'";
        }

        $conditions = implode(' AND ', $conditions);

        $query = "
            SELECT
                export_history_id,
                export_log_id,
                export_name,
                org_id,
                user_id,
                date,
                filter,
                file
            FROM
                export_history
            WHERE
                export_history_id = '{$historyId}' AND
                export_name = '{$exportName}' AND
                {$conditions}
        ";

        $result = sql_query_array($this->_db, $query);

        if (($result !== false) && (count($result) == 1)) {
            $history = new CHistory();
            $history->setFromDatabase($result[0]);
            $this->deleteHistory($history);
        }
    }
}
