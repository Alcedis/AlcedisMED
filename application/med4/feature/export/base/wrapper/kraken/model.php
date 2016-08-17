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

require_once('model/field.php');
require_once('model/iterator.php');

class exportWrapperKrakenModel
{
    const RELATION_MANY = 'many';
    const RELATION_ONE = 'one';

    /**
     * _name
     *
     * @access  protected
     * @var     string
     */
    protected $_name;


    /**
     * _tableName
     *
     * @access  protected
     * @var     string
     */
    protected $_tableName;


    /**
     * _fields
     *
     * @access  protected
     * @var     exportWrapperKrakenModelField[]
     */
    protected $_fields = array();


    /**
     * _conditions
     *
     * @access  protected
     * @var     array
     */
    protected $_conditions = array();


    /**
     * _relations
     *
     * @access  protected
     * @var     array
     */
    protected $_relations = array();


    /**
     * _db
     *
     * @access  protected
     * @var     Resource
     */
    protected $_db;


    /**
     * _relationField
     *
     * @access  protected
     * @var     string
     */
    protected $_relationField;


    /**
     * _relationFieldParentIds
     * (comment)
     *
     * @access
     * @var     array
     */
    protected $_relationFieldParentIds = array();


    /**
     * _relationType
     * (default many)
     *
     * @access  protected
     * @var     string
     */
    protected $_relationType = self::RELATION_MANY;


    /**
     * indicates if model is relation
     *
     * @access  protected
     * @var     bool
     */
    protected $_isRelation = false;


    /**
     * _orderBy
     *
     * @access  protected
     * @var     array
     */
    protected $_orderBy = array();


    /**
     * create exportWrapperKrakenModel
     *
     * @static
     * @access  public
     * @param   string  $name
     * @return  exportWrapperKrakenModel
     */
    public static function create($name)
    {
        return new self($name);
    }


    /**
     * @param string    $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
        $this->_tableName = $name;
    }


    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        $fields = '*';

        $tableName  = $this->getTableName();
        $primaryKey = get_primaer_key($tableName);

        if ($this->hasFields() === true) {
            $fields = array(
                $primaryKey
            );

            foreach ($this->getFields() as $field) {
                $fields[] = $field->getStatement();
            }

            $fields = implode(",\n", $fields);
        }

        $where = $this->_buildWhereCondition();

        $orderBy = $this->_buildOrderByCondition();

        $query = "
            SELECT
                {$fields}
            FROM `{$tableName}`
            {$where}
            {$orderBy}
        ";

        $data = sql_query_array($this->getDb(), $query);

        $data = $this->_buildRelations($data);

        if ($this->isRelation() === true) {
            $tmp = array();

            if ($this->getRelationType() === self::RELATION_MANY) {
                foreach ($data as $record) {
                    $tmp[$record[$this->getRelationField()]][] = $record;
                }
            } else {
                foreach ($data as $record) {
                    $tmp[$record[$this->getRelationField()]] = $record;
                }
            }

            $data = $tmp;
        } else {
            $data = exportWrapperKrakenModelIterator::create($data);
        }

        return $data;
    }


    /**
     * _buildWhereCondition
     *
     * @access  protected
     * @return  string
     */
    protected function _buildWhereCondition()
    {
        $where = array();

        if ($this->hasConditions() === true) {
            $where[] = '(' . implode(' AND ', $this->getConditions()) . ')';
        }

        if ($this->isRelation() === true) {
            $parentIds = $this->getRelationFieldParentIds();

            $where[] = $this->getRelationField() . ' IN (' . implode(',', $parentIds) . ')';
        }

        return count($where) > 0 ? 'WHERE (' . implode(' AND ', $where) . ')' : null;
    }


    /**
     * _buildOrderByCondition
     *
     * @access  protected
     * @return  string
     */
    protected function _buildOrderByCondition()
    {
        $condition = $this->hasOrderBy() === true ? 'ORDER BY ' . implode(' AND ', $this->getOrderBy()) : null;

        return $condition;
    }


    /**
     * getOrderBy
     *
     * @access  public
     * @return  array
     */
    public function getOrderBy()
    {
        return $this->_orderBy;
    }


    /**
     * setOrderBy
     *
     * @access  public
     * @param   string  $field
     * @param   string $direction
     * @return  exportWrapperKrakenModel
     */
    public function setOrderBy($field, $direction = 'ASC')
    {
        $this->_orderBy = array();

        $this->addOrderBy($field, $direction);

        return $this;
    }


    /**
     * addOrderBy
     *
     * @access  public
     * @param   string  $field
     * @param   string  $direction
     * @return  exportWrapperKrakenModel
     */
    public function addOrderBy($field, $direction = 'ASC')
    {
        $this->_orderBy[] = "{$field} {$direction}";

        return $this;
    }


    /**
     * hasOrderBy
     *
     * @access  public
     * @return  bool
     */
    public function hasOrderBy()
    {
        return (count($this->_orderBy) > 0);
    }


    /**
     * _buildRelations
     *
     * @access  protected
     * @param   array $data
     * @return  array
     */
    protected function _buildRelations(array $data)
    {
        $relations = $this->getRelations();
        $relationFieldIdCache = array();

        foreach ($relations as $relation) {
            $field = $relation->getRelationField();

            $relationType = $relation->getRelationType();

            // build id cache for faster record finding
            if (array_key_exists($field, $relationFieldIdCache) === false) {
                $relationFieldIdCache[$field] = array();

                foreach ($data as $record) {
                    $relationFieldIdCache[$field][] = $record[$field];
                }
            }

            $relation->setRelationFieldParentIds($relationFieldIdCache[$field]);

            $relationData = $relation->getData();

            foreach ($data as $i => $record) {
                $relationId = $record[$field];

                $rel = null;

                if (isset($relationData[$relationId]) === true) {
                    if ($relationType === exportWrapperKrakenModel::RELATION_ONE) {
                        $rel = $relationData[$relationId];
                    } else {
                        $rel = exportWrapperKrakenModelIterator::create($relationData[$relationId]);
                    }
                } else {
                    if ($relationType === exportWrapperKrakenModel::RELATION_ONE) {
                        $rel = null;
                    } else {
                        $rel = new exportWrapperKrakenModelIterator;
                    }
                }

                $data[$i][$relation->getName()] = $rel;
            }
        }

        return $data;
    }


    /**
     * addRelation
     *
     * @access  public
     * @param   exportWrapperKrakenModel $model
     * @return  exportWrapperKrakenModel
     */
    public function addRelation(exportWrapperKrakenModel $model)
    {
        $this->_relations[$model->getName()] = $model
            ->setDb($this->getDb())
            ->setIsRelation(true)
        ;

        return $this;
    }


    /**
     * setRelationType
     *
     * @access  public
     * @param   string  $type
     * @return  exportWrapperKrakenModel
     */
    public function setRelationType($type)
    {
        $this->_relationType = $type;

        return $this;
    }


    /**
     * hasRelationType
     *
     * @access  public
     * @return  bool
     */
    public function hasRelationType()
    {
        return ($this->_relationType !== null);
    }


    /**
     * getRelationType
     *
     * @access  public
     * @return  string
     */
    public function getRelationType()
    {
        return $this->_relationType;
    }


    /**
     * setRelationField
     *
     * @access  public
     * @param   string  $field
     * @return  exportWrapperKrakenModel
     */
    public function setRelationField($field)
    {
        $this->_relationField = $field;

        $this->select($field);

        return $this;
    }


    /**
     * setRelationFieldParentIds
     *
     * @access  public
     * @param   array   $ids
     * @return  exportWrapperKrakenModel
     */
    public function setRelationFieldParentIds($ids)
    {
        $this->_relationFieldParentIds = array_filter($ids);

        return $this;
    }


    /**
     * getRelationFieldParentIds
     * (get empty id if id count == 0)
     *
     * @access  public
     * @return  array
     */
    public function getRelationFieldParentIds()
    {
        $ids = $this->_relationFieldParentIds;

        return count($ids) > 0 ? $ids : array(0);
    }


    /**
     * hasRelationField
     *
     * @access  public
     * @return  bool
     */
    public function hasRelationField()
    {
        return ($this->_relationField !== null);
    }


    /**
     * getRelationField
     *
     * @access  public
     * @return  string
     */
    public function getRelationField()
    {
        return $this->_relationField;
    }


    /**
     * hasRelations
     *
     * @access  public
     * @return  bool
     */
    public function hasRelations()
    {
        return (count($this->_relations) > 0);
    }


    /**
     * getRelations
     *
     * @access  public
     * @return  exportWrapperKrakenModel[]
     */
    public function getRelations()
    {
        return $this->_relations;
    }


    /**
     * getName
     *
     * @access  public
     * @return  string
     */
    public function getName()
    {
        return $this->_name;
    }


    /**
     * setTableName
     *
     * @access  public
     * @param   string  $name
     * @return  exportWrapperKrakenModel
     */
    public function setTableName($name)
    {
        $this->_tableName = $name;

        return $this;
    }


    /**
     * getTableName
     *
     * @access  public
     * @return  string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }


    /**
     * addField
     *
     * @access  public
     * @param   exportWrapperKrakenModelField|string $field
     * @return  exportWrapperKrakenModel
     * @throws  Exception
     */
    public function addField($field)
    {
        if (is_string($field) === true) {
            $field = new exportWrapperKrakenModelField($field);
        }

        if ($this->hasField($field->getName()) === false) {
            if (($field instanceof exportWrapperKrakenModelField) === false) {
                throw new Exception('given field is not a kraken model field');
            }

            $this->_fields[$field->getName()] = $field;
        }

        return $this;
    }


    /**
     * select
     *
     * @access  public
     * @param   string  $field
     * @return  exportWrapperKrakenModel
     */
    public function select($field)
    {
        return $this->addField($field);
    }


    /**
     * setFields
     *
     * @access  public
     * @param   array   $fields
     * @return  exportWrapperKrakenModel
     */
    public function setFields($fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }

        return $this;
    }


    /**
     * getFields
     *
     * @access  public
     * @return  exportWrapperKrakenModelField[]
     */
    public function getFields()
    {
        return $this->_fields;
    }


    /**
     * hasField
     *
     * @access  public
     * @param   string  $name
     * @return  bool
     */
    public function hasField($name)
    {
        return (array_key_exists($name, $this->getFields()));
    }


    /**
     * hasFields
     *
     * @access  public
     * @return  bool
     */
    public function hasFields()
    {
        return (count($this->_fields) > 0);
    }


    /**
     * addCondition
     *
     * @access  public
     * @param   string  $condition
     * @return  exportWrapperKrakenModel
     */
    public function addCondition($condition)
    {
        $this->_conditions[] = $condition;

        return $this;
    }


    /**
     * setCondition
     *
     * @access  public
     * @param   string  $condition
     * @return  exportWrapperKrakenModel
     */
    public function setCondition($condition)
    {
        $this->_conditions = array($condition);

        return $this;
    }


    /**
     * getConditions
     *
     * @access  public
     * @return  array
     */
    public function getConditions()
    {
        return $this->_conditions;
    }


    /**
     * hasConditions
     *
     * @access  public
     * @return  bool
     */
    public function hasConditions()
    {
        return (count($this->_conditions) > 0);
    }


    /**
     * setDb
     *
     * @access  public
     * @param   resource    $db
     * @return  exportWrapperKrakenModel
     */
    public function setDb($db)
    {
        $this->_db = $db;

        foreach ($this->getRelations() as $relation) {
            $relation->setDb($db);
        }

        return $this;
    }


    /**
     * getDb
     *
     * @access  public
     * @return  Resource
     */
    public function getDb()
    {
        return $this->_db;
    }


    /**
     * setIsRelation
     *
     * @access  public
     * @param   bool    $bool
     * @return  exportWrapperKrakenModel
     */
    public function setIsRelation($bool)
    {
        $this->_isRelation = $bool;

        return $this;
    }


    /**
     * isRelation
     *
     * @access  public
     * @return  bool
     */
    public function isRelation()
    {
        return $this->_isRelation;
    }
}
