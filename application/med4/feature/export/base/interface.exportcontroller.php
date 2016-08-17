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

require_once('interface.exportobject.php');

/**
 * Interface IExportController
 */
interface IExportController extends IExportObject
{
    /**
     * doStartup
     *
     * @access  public
     * @param   permission $permission
     * @param   string $action
     * @return  void
     * @throws  EExportException
     */
    public function doStartup($permission, $action = '');


    /**
     * buildView
     *
     * @access  public
     * @param   string  $action
     * @return  bool
     */
    public function buildView($action);


    /**
     * handleAction
     *
     * @access  public
     * @return  void
     */
    public function handleAction();


    /**
     * ShowView
     *
     * @access  public
     * @return  void
     */
    public function showView();
}

?>
