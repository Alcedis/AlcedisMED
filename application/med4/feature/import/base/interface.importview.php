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

interface IImportView
{

    /**
     *
     * @param $model
     * @return unknown_type
     */
    public function SetModel( $model );

    /**
     *
     * @return unknown_type
     */
    public function BuildView( $action );

    /**
     *
     * @return unknown_type
     */
    public function ReadConfigs();

    /**
     *
     * @return unknown_type
     */
    public function SetVariables();

    /**
     *
     * @return unknown_type
     */
    public function CreateParameterViewFields();

    /**
     *
     * @return unknown_type
     */
    public function CreateLogViewFields();

    /**
     *
     * @return unknown_type
     */
    public function CreateErrorViewFields();

    /**
     *
     * @return unknown_type
     */
    public function FillFields();

    /**
     *
     * @return unknown_type
     */
    public function CreateFormular();

    /**
     *
     * @return unknown_type
     */
    public function CreateBackLink();

    /**
     *
     * @param $action
     * @return unknown_type
     */
    public function ShowView();

    /**
     *
     * @return unknown_type
     */
    public function GetViewType();

    /**
     *
     * @return unknown_type
     */
    public function GetTemplateFilename();

}

?>
