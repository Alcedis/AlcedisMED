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

class statusRefresh
{
    /**
     * @access
     * @var array
     */
    protected $_statusIds = array();


    /**
     * @access
     * @var null
     */
    protected $_widget = null;


    /**
     * @access
     * @var null
     */
    protected $_db = null;


    /**
     * @access
     * @var null
     */
    protected $_smarty = null;


    /**
     * @access
     * @var array
     */
    protected $_buffer = array(
        'formName' => null,
        'datasets' => array(),
        'posForms' => array()
    );


    /**
     * @access
     * @var array
     */
    protected $_statusForms = array();


    /**
     * @access
     * @var bool
     */
    protected $_completeLoad = false;


    /**
     * @access
     * @var array
     */
    protected $_log = array();


    /**
     * @access
     * @var array
     */
    protected $_time = array(
        'start' => null,
        'end'   => null
    );


    /**
     *
     * @var formManager
     */
    protected $_formManager = null;


    /**
     * @access
     * @var null
     */
    protected $_validationQueue = array(
        'current'   => null,
        'next'      => null,
        'onlyOne'   => false
    );

    /**
     * @param $db
     * @param $smarty
     */
    public function __construct($db, $smarty)
    {
        $this->_widget = $smarty->widget;
        $this->_db     = $db;
        $this->_smarty = $smarty;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $smarty
     * @return statusRefresh
     */
    public static function create($db, $smarty) {
        return new self($db, $smarty);
    }


    /**
     *
     *
     * @access
     * @param $type
     * @param $form
     * @return $this
     */
    public function setValidationQueue($type, $form)
    {
        $this->_validationQueue[$type] = $form;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    protected function getStatusDatasets()
    {
        $this->_statusIds = array_unique($this->_statusIds);

        $where = "WHERE ";

        if (count($this->_statusIds) > 0) {
            $where .= "status_id IN (" . implode(',', $this->_statusIds) . ")";
        } else {
            $this->_completeLoad = true;

            $this
                ->_findValidationQueue()
            ;

            if ($this->_validationQueue['current'] !== null) {
                if ($this->_validationQueue['onlyOne'] === true) {
                    $this->resetLog($this->_validationQueue['current']);
                }

                $where .= "s.form = '{$this->_validationQueue['current']}'";
            } else {
                $where .= 0;
            }
        }

        $query = "
            SELECT
                s.status_id,
                s.form,
                s.form_id,
                s.form_param,
                s.form_status,
                IF(s.status_lock = 1, s.form_status, NULL) AS 'status_lock_param',
                e.erkrankung,
                e.erkrankung_id,
                e.patient_id
            FROM `status` s
                LEFT JOIN erkrankung e  ON e.erkrankung_id = IF(s.form = 'erkrankung', s.form_id, s.erkrankung_id)
            {$where}
            GROUP BY
                s.status_id
        ";

        foreach (sql_query_array($this->_db, $query) as $statusForm) {
            $form    = $statusForm['form'];
            $formId  = $statusForm['form_id'];

            $this->_statusForms[$form][$formId] = $statusForm;
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @return $this
     */
    private function _findValidationQueue()
    {
        $statusLog = sql_query_array($this->_db, "SELECT * FROM `status_log` ORDER BY form ASC");

        $queue = array();

        foreach ($statusLog as $i => $logEntry) {
            $state = $logEntry['state'];

            if (in_array($state, array(0, 1)) === true) {
                $queue[] = $logEntry['form'];
            }
        }

        if (count($queue) > 0) {
            if ($this->_validationQueue['current'] === null) {
                $this->_validationQueue['current'] = $queue[0];

                if (count($queue) > 1) {
                    $this->_validationQueue['next'] = $queue[1];
                }
            } else {
                $currentKey = array_search($this->_validationQueue['current'], $queue);

                if ($currentKey !== false && isset($queue[$currentKey + 1])) {
                    $this->_validationQueue['next'] = $queue[$currentKey + 1];
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param null $singleForm
     * @return $this
     */
    public function resetLog($singleForm = null)
    {
        if ($singleForm === null) {
            mysql_query("TRUNCATE TABLE status_log");
        }

        $where = $singleForm !== null ? "WHERE form = '{$singleForm}'" : null;

        $query = "
            SELECT
                form,
                COUNT(DISTINCT status_id) as 'status_count',
                SUM(status_lock) as 'locked'
            FROM `status`
            {$where}
            GROUP BY
                form
        ";

        foreach (sql_query_array($this->_db, $query) as $form) {
            $formName = $form['form'];

            $query = "
                SELECT
                    COUNT(s.status_id) as 'count'
                FROM `status` s
                    INNER JOIN `{$formName}` f ON f.{$formName}_id = s.form_id
                WHERE
                    s.form = '{$formName}'
            ";

            $resultStatusRelation = reset(sql_query_array($this->_db, $query));

            $query = "
                SELECT
                    COUNT(f.{$formName}_id) as 'count'
                FROM `{$formName}` f
                    INNER JOIN `status` s ON s.form = '{$formName}' AND s.form_id = f.{$formName}_id
            ";

            $resultFormRelation = reset(sql_query_array($this->_db, $query));

            $this->_log[$form['form']] = array(
                'form'           => "'{$formName}'",
                'status_count'   => $form['status_count'],
                'status_relation'=> $resultStatusRelation['count'],
                'form_count'     => dlookup($this->_db, "`{$form['form']}`", "COUNT(*)", '1'),
                'form_relation'  => $resultFormRelation['count'],
                'locked'         => $form['locked'],
                'validated'      => 0
            );
        }

        ksort($this->_log);

        if ($singleForm === null) {
            $this->_writeLog(false);
        } else {
            $this->_writeLog(true, $singleForm);
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param bool $update
     * @param null $formName
     * @return $this
     */
    private function _writeLog($update = true, $formName = null)
    {
        foreach ($this->_log as $form => $logData) {
            if ($formName !== null) {
                if ($form != $formName) {
                    continue;
                }

                if (isset($this->_log[$formName]) === false) {
                    continue;
                }
            }

            $where   = $update === true ? "WHERE form = '{$form}'" : null;
            $sel     = $update === true ? "UPDATE" : "INSERT INTO";

            $content = array();

            foreach ($logData as $key => $value) {
                $content[] = "{$key} = {$value}";
            }

            $content = implode(',', $content);

            $query = "
                {$sel} status_log
                SET {$content}
                {$where}
            ";

            mysql_query($query);
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $formManager
     * @return $this
     */
    public function setFormManager($formManager)
    {
        $this->_formManager = $formManager;

        return $this;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function getDiseaseIds()
    {
        $diseaseIds = array();

        $result = sql_query_array($this->_db,
            "SELECT
                DISTINCT
                status_id,
                form,
                form_id,
                erkrankung_id
            FROM `status`
            WHERE
                status_id IN ('" . implode("','", $this->_statusIds) . "')"
        );

        foreach ($result as $status) {
            $formId = $status['form_id'];

            switch ($status['form']) {
                case 'erkrankung':
                    $diseaseIds[] = $formId;
                break;

                case 'nachsorge':
                    $tmpDiseaseIds = dlookup($this->_db, 'nachsorge_erkrankung', "GROUP_CONCAT(erkrankung_weitere_id SEPARATOR ',')", "nachsorge_id = '{$formId}'");

                    $diseaseIds = array_merge($diseaseIds, explode(',', $tmpDiseaseIds));
                break;

                default:
                    $diseaseIds[] = $status['erkrankung_id'];

                break;
            }
        }

        return array_unique($diseaseIds);
    }


    /**
     *
     *
     * @access
     * @param bool $updateDisease
     * @param bool $updateLocked
     * @param bool $forceDiseaseUpdate
     * @return $this
     */
    public function refreshStatus($updateDisease = true, $updateLocked = false, $forceDiseaseUpdate = false)
    {
        $this->getStatusDatasets();

        $validator = pseudoValidator::create($this->_smarty, $this->_db);

        $dkgManager = dkgManager::getInstance();

        foreach ($this->_statusForms as $statusFormName => $statusForms) {
            $dkgManager->setParam('form', $statusFormName);

            $this->_log[$statusFormName]['state'] = 1;
            $this->_writeLog(true, $statusFormName);

            $ext = in_array($statusFormName, array('qs_18_1_b', 'qs_18_1_o', 'qs_18_1_brust')) ? 'err' : 'warn';

            $dlistNames = $dkgManager->getDlistNames();

            foreach ($statusForms as $statusFormId => $statusForm) {
                $formStatus = $statusForm['form_status'];

                $statusLockParam  = strlen($statusForm['status_lock_param']) > 0 ? $statusForm['status_lock_param'] : NULL;

                $formId  = $statusForm['form_id'];

                $dataset = $this->_loadFormDatasetFromStatus($statusForm);

                if ($dataset['dataset'] === false) {
                    continue;
                }

                $posData = array();

                foreach ($dlistNames as $formName) {
                    $posDatasets = $this->_loadFromBuffer($formName, $formId, $statusFormName);

                    if ($posDatasets !== false) {
                        $posData[$formName] = $posDatasets;
                    }
                }

                $fields = widget::getFieldsFromStatus($this->_db, $statusForm);
                $fields = dataArray2fields($dataset['dataset'], $fields, true);

                $validator->validate($statusFormName, $fields, $ext);

                if ($updateLocked === true || (int) $formStatus != 3) {
                    write_status($this->_smarty, $this->_db, $fields, $statusFormName, 'update', false, null, true, $posData, $validator->getValidator(), $statusLockParam);
                }

                if ($this->_completeLoad === true) {
                    $this->_log[$statusFormName]['validated']++;

                    unset($this->_statusForms[$statusFormName][$statusFormId]);
                    $this->_writeLog(true, $statusFormName);
                }
            }

            foreach ($dlistNames as $dlistName) {
                unset($this->_buffer['posforms'][$dlistName]);
            }

            $this->_log[$statusFormName]['state'] = 2;

            $this->_writeLog(true, $statusFormName);
        }

        $this->_syncDiseaseForms($forceDiseaseUpdate);

        if ($updateLocked === false && $updateDisease === true) {
            if (isset($this->_statusForms['erkrankung']) === true) {
                foreach ($this->_statusForms['erkrankung'] as $dataset) {
                    $this->_updateDisease($dataset['form_id']);
                }
            }
        }

        if ($this->_completeLoad === true) {
            if ($this->_validationQueue['next'] !== null && $this->_validationQueue['onlyOne'] === false) {
                // call next section
                $exec = "/usr/bin/php core/exec.php --feature=status --page=validate {$this->_validationQueue['next']} > /dev/null &";

                exec($exec);
            }
        }

        return $this;
    }

    /**
     *
     *
     * @access
     * @param bool $force
     * @return $this
     */
    private function _syncDiseaseForms($force = false)
    {
        if ($force === true) {
            if (isset($this->_statusForms['erkrankung']) === false) {
                $this->_statusForms['erkrankung'] = array();
            }

            foreach ($this->_statusForms as $formName => $forms) {
                if ($formName !== 'erkrankung') {
                    foreach ($forms as $form) {
                        if (isset($form['erkrankung_id']) === true && isset($this->_statusForms['erkrankung'][$form['erkrankung_id']]) === false) {
                            $this->_statusForms['erkrankung'][$form['erkrankung_id']] = array(
                                'form'       => 'erkrankung',
                                'form_id'    => $form['erkrankung_id'],
                                'patient_id' => $form['patient_id'],
                                'erkrankung' => $form['erkrankung']
                            );
                        }
                    }
                }
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param null $diseaseId
     * @return $this
     */
    public function refreshDisease($diseaseId = null)
    {
        $diseaseIds = $diseaseId !== null ? array($diseaseId) : $this->getDiseaseIds();

        foreach ($diseaseIds as $diseaseId) {
            $this->_updateDisease($diseaseId);
        }

        return $this;
    }


    /**
     * update disease
     *
     * @access
     * @param $diseaseId
     * @return $this
     */
    protected function _updateDisease($diseaseId)
    {
        $diseaseDataset = reset(sql_query_array($this->_db,
            "SELECT
                s.*,
                s.report_param AS 'erkrankung'
            FROM status s
            WHERE
                s.form = 'erkrankung'
                AND s.form_id = '{$diseaseId}'"
        ));

        $patientId        = $diseaseDataset['patient_id'];
        $disease          = $diseaseDataset['erkrankung'];
        $currentState     = $diseaseDataset['form_param'];
        $statusId         = $diseaseDataset['status_id'];

        $nachsorgeDatasets = sql_query_array($this->_db,
            "SELECT
                ne.nachsorge_erkrankung_id,
                s.*
            FROM nachsorge_erkrankung ne
                LEFT JOIN nachsorge n   ON n.nachsorge_id = ne.nachsorge_id
                LEFT JOIN status s      ON s.form = 'nachsorge' AND s.form_id = n.nachsorge_id
            WHERE
                ne.erkrankung_weitere_id = '{$diseaseId}'"
        );

        $abschlussDataset = sql_query_array($this->_db,
            "SELECT * FROM status WHERE form = 'abschluss' AND patient_id = '{$patientId}'"
        );

        $statusDatasets = sql_query_array($this->_db,
            "SELECT * FROM status WHERE erkrankung_id = '{$diseaseId}'"
        );

        $patientDataset = sql_query_array($this->_db,
            "SELECT *, 'patient' AS form, 4 AS form_status FROM patient WHERE patient_id = '{$patientId}'"
        );

        $datasets = array_merge(
            array($diseaseDataset),
            $nachsorgeDatasets,
            $abschlussDataset,
            $statusDatasets,
            $patientDataset
        );

        $dkgManager = dkgManager::getInstance()
            ->setParam('disease', $disease)
            ->setConditionData($datasets)
            ->checkConditions($this->_db)
        ;

        $state = $dkgManager->getConditionState();

        if ($state != $currentState) {
            $updateQuery = "UPDATE `status` SET form_param = '{$state}' WHERE status_id = '{$statusId}'";

            mysql_query($updateQuery, $this->_db);
        }

        return $this;
    }


    /**
     * loads form data and merge it with his status dataset
     *
     * @access protected
     * @param $statusForm
     * @return array
     */
    protected function _loadFormDatasetFromStatus($statusForm)
    {
        $formName   = $statusForm['form'];
        $formId     = $statusForm['form_id'];

        if ($this->_completeLoad === true) {
            $dataset = array(
                'status'    => $statusForm,
                'dataset'   => $this->_loadFromBuffer($formName, $formId)
            );
        } else {
            $dataset = array(
                'status'    => $statusForm,
                'dataset'   => reset(sql_query_array($this->_db, "SELECT * FROM `{$formName}` WHERE {$formName}_id = '{$formId}'"))
            );
        }

        return $dataset;
    }


    /**
     *
     *
     * @access
     * @param      $formName
     * @param      $formId
     * @param null $parentForm
     * @return array|bool
     */
    private function _loadFromBuffer($formName, $formId, $parentForm = null)
    {
        $dataset = array();

        if ($parentForm === null) {
            if ($this->_buffer['formName'] !== $formName) {
                $this->_buffer['datasets'] = array();
                $this->_buffer['formName'] = $formName;

                foreach (sql_query_array($this->_db, "SELECT * FROM `{$formName}`") as $formData) {
                    $this->_buffer['datasets'][$formData["{$formName}_id"]] = $formData;
                }
            }

            if (isset($this->_buffer['datasets'][$formId]) === true) {
                $dataset = $this->_buffer['datasets'][$formId];
            } else {
                $dataset = false;
            }
        } else {
            if ($this->_completeLoad === true) {
                if (isset($this->_buffer['posforms'][$formName]) === false) {
                    foreach (sql_query_array($this->_db, "SELECT * FROM `{$formName}`") as $formData) {
                        $parentFormId = $formData["{$parentForm}_id"];

                        $this->_buffer['posforms'][$formName][$parentFormId][] = $formData;
                    }
                }

                if (isset($this->_buffer['posforms'][$formName][$formId]) === true) {
                    $dataset = $this->_buffer['posforms'][$formName][$formId];
                } else {
                    $dataset = false;
                }
            } else {
                $dataset = sql_query_array($this->_db, "SELECT * FROM `{$formName}` WHERE {$parentForm}_id = '{$formId}'");
            }
        }

        return $dataset;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    public function getStatusIds() {
        return $this->_statusIds;
    }


    /**
     *
     *
     * @access
     * @param $ids
     * @return $this
     */
    public function setStatusIds($ids)
    {
        if (is_array($ids) === true) {
            foreach ($ids as $id) {
                $this->setStatusId($id);
            }
        }

        return $this;
    }


    /**
     *
     *
     * @access
     * @param $id
     * @return $this
     */
    public function setStatusId($id)
    {
        if ($id !== null && strlen($id) > 0) {
            $this->_statusIds[] = $id;
        }

        return $this;
    }
}

?>
