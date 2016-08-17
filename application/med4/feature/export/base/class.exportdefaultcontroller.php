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

require_once('interface.exportcontroller.php');
require_once('class.exportbaseobject.php');
require_once('class.exportdefaultmodel.php');
require_once('class.exportdefaultview.php');
require_once('helper.database.php');

class CExportDefaultController extends CExportBaseObject implements IExportController
{
    /**
     * m_permission
     *
     * @access  protected
     * @var     permission
     */
    protected $m_permission;


    /**
     * m_action
     *
     * @access  protected
     * @var     string
     */
    protected $m_action = '';


    /**
     * m_model
     *
     * @access  protected
     * @var     IExportModel
     */
    protected $m_model;


    /**
     * m_view
     *
     * @access  protected
     * @var     IExportView
     */
    protected $m_view;


    /**
     * m_has_open_export
     *
     * @access  protected
     * @var     bool
     */
    protected $m_has_open_export = false;


    /**
     * m_load_export_parameters
     *
     * @access  protected
     * @var     bool
     */
    protected $m_load_export_parameters = true;


    /**
     * _ignoreParameters
     *
     * @access  protected
     * @var     array
     */
    protected $_ignoreParameters = array('export_id');


    /**
     * @see IExportObject::create
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        parent::create($absolute_path, $export_name, $smarty, $db, $error_function);

        $model = new CExportDefaultModel;

        $model->create($absolute_path, $this->GetExportName(), $smarty, $db, $error_function);
        $model->setParameters($this->GetParameters());

        $this->setModel($model);

        $this->resetView();
    }


    /**
     * getExportName
     *
     * @access  public
     * @return  string
     */
    public function getExportName()
    {
        return 'DefaultExport';
    }


    /**
     * LoadExportParameters
     *
     * @access  public
     * @param   array   $param
     * @return  void
     */
    public function loadExportParameters($param)
    {
        $this->m_load_export_parameters = $param;
    }


    /**
     * SetParameters
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     */
    public function setParameters(&$parameters)
    {
        if ($this->m_load_export_parameters) {
            HDatabase::LoadExportSettings($this->m_db, $parameters, $this->getExportName());
        }

        parent::setParameters($parameters);

        if ($this->hasModel() === true) {
            $this->getModel()->setParameters($parameters);
        }

        if ($this->hasView() === true) {
            $this->getView()->setParameters($parameters);
        }
    }


    /**
     * addIgnoreParameters
     *
     * @access  public
     * @param   array   $parameters
     * @return  IExportController
     */
    public function addIgnoreParameters($parameters)
    {
        $this->_ignoreParameters = array_merge($this->_ignoreParameters, $parameters);

        return $this;
    }


    /**
     * getIgnoredParamaters
     *
     * @access  public
     * @return  array
     */
    public function getIgnoredParamaters()
    {
        return $this->_ignoreParameters;
    }


    /**
     * @see IExportController::doStartup
     */
    public function doStartup($permission, $action = '')
    {
        if ($permission === null) {
            throw new EExportException("ERROR: Permission object is NULL.");
        }

        $this->m_permission = $permission;

        $this->setAction($action);

        if ('download' === $action) {
            if ($this->m_permission->action($action, "export_{$this->GetExportName()}") === true) {
                $this->HandleAction();
                exit;
            } else {
                die("Permission denied to download for export " . $this->GetExportName());
            }
        }

        $model = $this->getModel();

        // if export is open actually
        if ($model->createExport($this->GetParameters()) === false) {
            $this->setHasOpenExport($model->IsOpenExport());

            $action = $this->getAction();

            $openActions = array('export_create', 'export_delete', 'view_errors', 'view_warnings');

            if ($this->hasOpenExport() === true && in_array($action, $openActions) === false) {
                $this->setAction('export_is_open');
            }

            if ($model->GetExportRecord() !== null) {
                // Damit nach dem Durchlauf durch das Formular die alten Parameter wieder geladen werden!
                $savedParameters = $model->GetExportRecord()->GetParameters();
                $params = $this->GetParameters();

                $ignoredParameters = $this->getIgnoredParamaters();

                foreach ($savedParameters as $key => $value) {
                    if (in_array($key, $ignoredParameters) === false) {
                        $params[$key] = $value;
                    }
                }

                $this->setParameters($params);
            }
        }

        $r = $this->BuildView($this->m_action);
        $a = $this->m_permission->action($this->getAction(), "export_{$this->GetExportName()}");

        if ($r === true && $a === true) {
            $this->HandleAction();
        } else {

        }

        $this->ShowView();
    }


    /**
     * BuildView
     *
     * @access  public
     * @param   string  $action
     * @return  bool
     */
    public function buildView($action)
    {
        $view = new CExportDefaultView;

        $view->Create($this->getAbsolutePath(), $this->GetExportName(), $this->getSmarty(), $this->getDB(), $this->getErrorFunction());

        $view->SetParameters($this->GetParameters());
        $view->SetModel($this->getModel());

        $this->setView($view);

        return $view->BuildView($action);
    }


    /**
     * @see IExportController::HandleAction
     */
    public function handleAction()
    {
        switch ($this->getAction()) {
            case 'export_start' :
            case 'export_is_open' :
                $this->getModel()->getData();
                break;

            case 'export_create' :
                $this->getModel()->writeData();
                break;

            case 'export_delete' :
                $this->getModel()->deleteData();
                break;

            case 'download' :
                $historyId  = $this->getParameter('historyId');

                // new download param
                if (strlen($historyId) > 0) {
                    $exportName = $this->getParameter('exportName');
                    $type = $this->getParameter('type');

                    if (strlen($historyId) === 0 || strlen($type) === 0) {
                        exit;
                    }

                    $file = dlookup($this->getDB(), 'export_history', 'file', "export_history_id = '{$historyId}' AND export_name = '{$exportName}'");

                    download::create($file, $type)->output();
                } else {
                    if (!isset($_REQUEST['file']) || (strlen($_REQUEST['file']) == 0) ||
                        !isset($_REQUEST['type']) || (strlen($_REQUEST['type']) == 0)) {
                        exit();
                    }

                    download::create($_REQUEST['file'], $_REQUEST['type'])->output();
                }

                break;
        }
    }


    /**
     * @see IExportController::showView
     */
    public function showView()
    {
        $this->getView()->ShowView();
    }


    /**
     * hasModel
     *
     * @access  public
     * @return  bool
     */
    public function hasModel()
    {
        return ($this->m_model !== null);
    }


    /**
     * hasView
     *
     * @access  public
     * @return  bool
     */
    public function hasView()
    {
        return ($this->m_view !== null);
    }


    /**
     * getView
     *
     * @access  public
     * @return  IExportView
     */
    public function getView()
    {
        return $this->m_view;
    }


    /**
     * setView
     *
     * @access  public
     * @param   IExportView $view
     * @return  IExportController
     */
    public function setView(IExportView $view)
    {
        $this->m_view = $view;

        return $this;
    }


    /**
     * resetView
     *
     * @access  public
     * @return  IExportController
     */
    public function resetView()
    {
        $this->m_view = null;

        return $this;
    }


    /**
     * setAction
     *
     * @access  public
     * @param   string  $action
     * @return  IExportController
     */
    public function setAction($action)
    {
        $this->m_action = $action;

        return $this;
    }


    /**
     * setModel
     *
     * @access  public
     * @param   IExportModel $model
     * @return  IExportController
     */
    public function setModel(IExportModel $model)
    {
        $this->m_model = $model;

        return $this;
    }


    /**
     * getModel
     *
     * @access  public
     * @return  IExportModel
     */
    public function getModel()
    {
        return $this->m_model;
    }


    /**
     * setPermission
     *
     * @access  public
     * @param   permission $permission
     * @return  IExportController
     */
    public function setPermission($permission)
    {
        $this->m_permission = $permission;

        return $this;
    }


    /**
     * getPermission
     *
     * @access  public
     * @return  permission
     */
    public function getPermission()
    {
        return $this->m_permission;
    }


    /**
     * getAction
     *
     * @access  public
     * @return  string
     */
    public function getAction()
    {
        return $this->m_action;
    }


    /**
     * setOpenExport
     *
     * @access  public
     * @param   bool    $open
     * @return  IExportController
     */
    public function setHasOpenExport($open)
    {
        $this->m_has_open_export = $open;

        return $this;
    }


    /**
     * hasOpenExport
     *
     * @access  public
     * @return  bool
     */
    public function hasOpenExport()
    {
        return $this->m_has_open_export;
    }
}
