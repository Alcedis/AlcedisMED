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

require_once 'element/group.php';
require_once 'interface.php';

/**
 * Class registerQueryAbstract
 */
abstract class registerQueryAbstract extends customReport implements registerQueryInterface
{
    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected $_cache = array();


    /**
     * _queryParts
     *
     * @access  protected
     * @var     array
     */
    protected $_queryParts = array(
        'preQuerySelect' => array(),
        'preQueryWhere'  => array(),
        'preQueryHaving' => array(),
        'select' => array(),
        'join'   => array(),
        'where'  => array(),
        'having' => array()
    );


    /**
     * $_groupSelects
     *
     * @access  protected
     * @var     registerQueryElementGroup[]
     */
    protected $_groupSelects = array();


    /**
     * _postProcess
     *
     * @access  protected
     * @var     array
     */
    protected $_postProcess = array(
        'groupSelect' => array(),
        'mapping' => array(),
        'relations' => array()
    );

    /**
     * @param resource  $db
     * @param Smarty    $smarty
     * @param array     $params
     */
    public function __construct($db, Smarty $smarty, $params = array())
    {
        parent::__construct(null, $db, $smarty, null, null, $params);

        // initialize with all diseases
        $this->_filterDisease(true);

        $this->_initialize();

        $this->_buildQuery();
    }


    /**
     * _initialize
     *
     * @access  protected
     * @return  void
     */
    protected function _initialize()
    { }


    /**
     * _buildQuery
     *
     * @access  protected
     * @return  void
     */
    abstract protected function _buildQuery();


    /**
     * execute
     *
     * @access  protected
     * @return  registerPatientCollection
     */
    public function execute()
    {
        $where  = $this->_queryParts['preQueryWhere'];
        $having = null;

        // append having statements to preQuery
        if (count($this->_queryParts['preQueryHaving']) > 0) {
            $having = implode(' AND ' , $this->_queryParts['preQueryHaving']);
        }

        $preQuery = $this->_getPreQuery($having, $this->_queryParts['preQuerySelect'], $where);

        // now wrap preQuery
        $query = "
            SELECT
                sit.*
        ";

        // append select statements
        if (count($this->_queryParts['select']) > 0) {
            $query .= ',' . implode(',' , $this->_queryParts['select']);
        }

        foreach ($this->_groupSelects as $groupSelect) {
            $query .= ',' . $groupSelect->getStatement() . ' AS ' . $groupSelect->getName();
        }

        $query .= " FROM ({$preQuery}) sit {$this->_innerStatus()} ";

        // append join statements
        $query .= implode(' ', $this->_queryParts['join']);

        // append where statements
        if (count($this->_queryParts['where']) > 0) {
            $query .= ' WHERE (' . implode(') AND (', $this->_queryParts['where']) . ')';
        }

        // default grouping
        $query .= "
            GROUP BY
                sit.patient_id,
                sit.erkrankung_id,
                sit.anlass,
                sit.diagnose_seite
        ";

        $cases = sql_query_array($this->getDB(), $query);

        foreach ($cases as &$case) {
            $this->_postProcessGroupSelects($case);
            $this->_postProcessMappings($case);
            $this->_postProcessRelations($case);
            $this->_postProcess($case);
        }

        return $cases;
    }


    /**
     * _postProcess
     *
     * @access  protected
     * @param   array $record
     * @return  void
     */
    protected function _postProcess(array &$record)
    { }


    /**
     * addSelect
     *
     * @access  public
     * @param   string  $select
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addSelect($select, $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQuerySelect' : 'select';

        $this->_queryParts[$loc][] = $select;

        return $this;
    }


    /**
     * addGroupSelect
     *
     * @access  public
     * @param   registerQueryElementGroup  $group
     * @param   string                     $orderField
     * @param   string                     $order
     * @return  registerQueryElementGroup
     * @throws  Exception
     */
    public function addGroupSelect(registerQueryElementGroup $group, $orderField = null, $order = 'DESC')
    {
        $groupName = $group->getName();

        if (array_key_exists($groupName, $this->_groupSelects) === true) {
            throw new Exception("group '{$groupName}' already exists");
        }

        $this->_groupSelects[$groupName] = $group;

        $this->addGroupSelectPostProcess($group, $orderField, $order);

        return $this;
    }


    /**
     * getGroupSelect
     *
     * @access  public
     * @param   string  $name
     * @return  registerQueryElementGroup
     * @throws  Exception
     */
    public function getGroupSelect($name)
    {
        if (array_key_exists($name, $this->_groupSelects) === false) {
            throw new Exception("group '{$name}' doesn't exists");
        }

        return $this->_groupSelects[$name];
    }


    /**
     * getGroupSelects
     *
     * @access  public
     * @return  registerQueryElementGroup[]
     */
    public function getGroupSelects()
    {
        return $this->_groupSelects;
    }


    /**
     * addMapping
     *
     * @access  public
     * @param   string          $field
     * @param   string|array    $map
     * @param   string          $mappingField
     * @return  registerQueryInterface
     */
    public function addMapping($field, $map, $mappingField = null)
    {
        $this->_postProcess['mapping'][] = array(
            'field' => $field,
            'map' => $map,
            'mappingField' => $mappingField,
        );

        return $this;
    }


    /**
     * setSelects
     *
     * @access  public
     * @param   array   $selects
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setSelects(array $selects = array(), $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQuerySelect' : 'select';

        $this->_queryParts[$loc] = $selects;

        return $this;
    }


    /**
     * getSelects
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getSelects($preQuery = false)
    {
        $loc = $preQuery === true ? 'preQuerySelect' : 'select';

        return array_unique($this->_queryParts[$loc]);
    }


    /**
     * addJoin
     *
     * @access  public
     * @param   string  $join
     * @return  registerQueryInterface
     */
    public function addJoin($join)
    {
        $this->_queryParts['join'][] = $join;

        return $this;
    }


    /**
     * setJoins
     *
     * @access  public
     * @param   array $joins
     * @return  registerQueryInterface
     */
    public function setJoins(array $joins = array())
    {
        $this->_queryParts['join'] = $joins;

        return $this;
    }


    /**
     * getJoins
     *
     * @access  public
     * @return  array
     */
    public function getJoins()
    {
        return array_unique($this->_queryParts['join']);
    }


    /**
     * addWhere
     *
     * @access  public
     * @param   string  $where
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addWhere($where, $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryWhere' : 'where';

        $this->_queryParts[$loc][] = $where;

        return $this;
    }


    /**
     * setWhere
     *
     * @access  public
     * @param   array   $where
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setWhere(array $where = array(), $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryWhere' : 'where';

        $this->_queryParts[$loc] = $where;

        return $this;
    }


    /**
     * getWhere
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getWhere($preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryWhere' : 'where';

        return array_unique($this->_queryParts[$loc]);
    }


    /**
     * addHaving
     *
     * @access  public
     * @param   string  $having
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function addHaving($having, $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryHaving' : 'having';

        $this->_queryParts[$loc][] = $having;

        return $this;
    }


    /**
     * setHaving
     *
     * @access  public
     * @param   array  $having
     * @param   bool    $preQuery
     * @return  registerQueryInterface
     */
    public function setHaving(array $having = array(), $preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryHaving' : 'having';

        $this->_queryParts[$loc] = $having;

        return $this;
    }


    /**
     * getHaving
     *
     * @access  public
     * @param   bool $preQuery
     * @return  array
     */
    public function getHaving($preQuery = false)
    {
        $loc = $preQuery === true ? 'preQueryHaving' : 'having';

        return array_unique($this->_queryParts[$loc]);
    }


    /**
     * _addGroupPostProcess
     *
     * @access  public
     * @param   registerQueryElementGroup  $groupSelect
     * @param   string  $orderField
     * @param   string  $order
     * @return  registerQueryInterface
     */
    public function addGroupSelectPostProcess(registerQueryElementGroup $groupSelect, $orderField = null, $order = 'DESC')
    {
        $this->_postProcess['groupSelect'][] = array(
            'groupSelect' => $groupSelect,
            'orderField'  => $orderField,
            'order'       => $order
        );

        return $this;
    }


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
    public function addRelation($local, $foreign, $field = null, $many = true, $remove = true)
    {
        $field = $field === null ? $foreign . '_id' : $field;

        $this->_postProcess['relations'][] = array(
            'local' => $local,
            'foreign' => $foreign,
            'field' => $field,
            'relation' => ($many === true ? 'many' : 'one'),
            'remove' => $remove
        );

        return $this;
    }


    /**
     * _postProcessGroups
     *
     * @access  protected
     * @param   array   $record
     * @return  void
     */
    protected function _postProcessGroupSelects(array &$record)
    {
        foreach ($this->_postProcess['groupSelect'] as $process) {
            $groupSelect = $process['groupSelect'];

            $fieldName = $groupSelect->getName();

            $data = $record[$fieldName];

            if (strlen($data) > 0) {
                $data = HDatabase::recordStringToArray($data, array_values($groupSelect->getFields()));

                // do oder if field is filled
                if ($process['orderField'] !== null) {
                    $data = HReports::OrderRecordsByField($data, $process['orderField'], $process['order']);
                }

                $record[$fieldName] = $data;
            } else {
                $record[$fieldName] = array();
            }
        }
    }


    /**
     * _postProcessMappings
     *
     * @access  protected
     * @param   array $record
     * @return  void
     * @throws  EExportException
     */
    protected function _postProcessMappings(array &$record)
    {
        foreach ($this->_postProcess['mapping'] as $mapping) {
            $field = $mapping['field'];
            $toMap = $record[$field];
            $map   = $mapping['map'];
            $mappingField = $mapping['mappingField'];

            // check is map is already assigned as array, else take from cache
            if (is_string($map) === true) {
                $map = $this->getCacheEntry($map);

                if ($map === null) {
                    throw new EExportException("mapping '{$mapping['map']}' doesn't exists");
                }
            }

            // field must be filled and map must be found
            if ($toMap !== null && $map !== null) {
                // if field value is a string
                if (is_string($toMap) === true) {
                    echo 'TODO';


                } else {
                    foreach ($toMap as $key => $values) {
                        $mapValue = $values[$mappingField];

                        // field value must be filled and mapping should be exists
                        if (strlen($mapValue) > 0 && isset($map[$mapValue]) === true) {
                            $record[$field][$key][$mappingField] = $map[$mapValue];
                        } else {
                            $record[$field][$key][$mappingField] = null;
                        }
                    }
                }
            }
        }
    }


    /**
     * _postProcessRelations
     *
     * @access  protected
     * @param   array $record
     * @return  void
     */
    protected function _postProcessRelations(array &$record)
    {
        $removeFromRecord = array();

        foreach ($this->_postProcess['relations'] as $part) {
            $local = $part['local'];
            $foreign = $part['foreign'];
            $relationField = $part['field'];
            $relation = $part['relation'];
            $foreignData = $record[$foreign];

            // check if target array is filled, else do nothing except of deleting map
            if (is_array($foreignData) === true) {
                $localData = $record[$local];

                // foreach target record
                foreach ($record[$foreign] as $key => $data) {
                    $tmp = array();

                    // if local data (src datasets) exists
                    if (is_array($localData) === true) {
                        foreach ($localData as $lKey => $lData) {
                            // check relational fields
                            if ($lData[$relationField] == $data[$relationField]) {
                                $tmp[] = $lData;

                                unset($localData[$lKey]); // remove entry for faster performance

                                if ($relation !== 'many') {
                                    break;
                                }
                            }
                        }
                    }

                    // create single array or null if relation is not many (so single)
                    if ($relation !== 'many') {
                        if (count($tmp) === 1) {
                            $tmp = reset($tmp);
                        } else {
                            $tmp = null;
                        }
                    }

                    $record[$foreign][$key][$local] = $tmp;
                }
            }

            if ($part['remove'] === true) {
                $removeFromRecord[] = $local;
            }
        }

        // remove all record entries which was marked as remove after post process
        foreach ($removeFromRecord as $key) {
            unset($record[$key]);
        }
    }


    /**
     * addCacheEntry
     *
     * @access  public
     * @param   string  $name
     * @param   mixed   $values
     * @return  registerQueryInterface
     */
    public function addCacheEntry($name, $values)
    {
        $this->_cache[$name] = $values;

        return $this;
    }


    /**
     * getCacheEntry
     *
     * @access  public
     * @param   string  $name
     * @return  mixed
     */
    public function getCacheEntry($name)
    {
        return (array_key_exists($name, $this->_cache) === true ? $this->_cache[$name] : null);
    }


    /**
     * createStatusJoin
     *
     * @access  public
     * @param   string  $tableAndAlias
     * @return  string
     */
    public function createStatusJoin($tableAndAlias, $considerSide = false)
    {
        return $this->_statusJoin($tableAndAlias, $considerSide);
    }
}
