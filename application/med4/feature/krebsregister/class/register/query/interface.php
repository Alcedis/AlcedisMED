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

/**
 * Interface registerQueryInterface
 */
interface registerQueryInterface
{
    /**
     * execute
     *
     * @access  protected
     * @return  registerPatientCollection
     */
    public function execute();


    /**
     * addSelect
     *
     * @access  public
     * @param   string  $select
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addSelect($select, $preQuery = false);


    /**
     * addGroupSelect
     *
     * @access  public
     * @param   registerQueryElementGroup  $group
     * @param   string              $orderField
     * @param   string              $order
     * @return  registerQueryElementGroup
     * @throws  Exception
     */
    public function addGroupSelect(registerQueryElementGroup $group, $orderField = null, $order = 'DESC');


    /**
     * getGroupSelect
     *
     * @access  public
     * @param   string  $name
     * @return  registerQueryElementGroup
     * @throws  Exception
     */
    public function getGroupSelect($name);


    /**
     * getGroupSelects
     *
     * @access  public
     * @return  registerQueryElementGroup[]
     */
    public function getGroupSelects();


    /**
     * addMapping
     *
     * @access  public
     * @param   string          $field
     * @param   string|array    $map
     * @param   string          $mappingField
     * @return  registerQueryInterface
     */
    public function addMapping($field, $map, $mappingField = null);


    /**
     * setSelects
     *
     * @access  public
     * @param   array   $selects
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setSelects(array $selects = array(), $preQuery = false);


    /**
     * getSelects
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getSelects($preQuery = false);


    /**
     * addJoin
     *
     * @access  public
     * @param   string  $join
     * @return  registerQueryInterface
     */
    public function addJoin($join);


    /**
     * setJoins
     *
     * @access  public
     * @param   array $joins
     * @return  registerQueryInterface
     */
    public function setJoins(array $joins = array());


    /**
     * getJoins
     *
     * @access  public
     * @return  array
     */
    public function getJoins();


    /**
     * addWhere
     *
     * @access  public
     * @param   string  $where
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addWhere($where, $preQuery = false);


    /**
     * setWhere
     *
     * @access  public
     * @param   array   $where
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setWhere(array $where = array(), $preQuery = false);


    /**
     * getWhere
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getWhere($preQuery = false);


    /**
     * addHaving
     *
     * @access  public
     * @param   string  $having
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addHaving($having, $preQuery = false);


    /**
     * setHaving
     *
     * @access  public
     * @param   array  $having
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setHaving(array $having = array(), $preQuery = false);


    /**
     * getHaving
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getHaving($preQuery = false);


    /**
     * _addGroupPostProcess
     *
     * @access  public
     * @param   registerQueryElementGroup  $groupSelect
     * @param   string  $orderField
     * @param   string  $order
     * @return  registerQueryInterface
     */
    public function addGroupSelectPostProcess(registerQueryElementGroup $groupSelect, $orderField = null, $order = 'DESC');


    /**
     * addRelation
     *
     * @access  public
     * @param   string  $local
     * @param   string  $foreign
     * @param   string  $field
     * @param   bool $many
     * @param   bool $remove
     * @return  registerQueryInterface
     */
    public function addRelation($local, $foreign, $field = null, $many = true, $remove = true);


    /**
     * addCacheEntry
     *
     * @access  public
     * @param   string  $name
     * @param   mixed   $values
     * @return  registerQueryInterface
     */
    public function addCacheEntry($name, $values);


    /**
     * getCacheEntry
     *
     * @access  public
     * @param   string  $name
     * @return  mixed
     */
    public function getCacheEntry($name);


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb();


    /**
     * createStatusJoin
     *
     * @access  public
     * @param   string  $tableAndAlias
     * @return  string
     */
    public function createStatusJoin($tableAndAlias);
}
