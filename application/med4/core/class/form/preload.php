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

class formPreload
{
   protected $_db             = null;

   protected $_widget         = null;

   protected $_relations      = array();

   protected $_relationTables = array();

   protected $_relationWhere  = array();

   protected $_extFields      = array();

   protected $_relationOrder  = array();

   protected $_relationData   = array();

   protected $_initializeFields = array();

   public function __construct($db, widget $widget)
   {
        $this->_db      = $db;

        $this->_widget  = $widget;
   }

   public static function create($db, $widget) {
      return new self($db, $widget);
   }

   /**
    * set relation between
    *
    * @param unknown_type $relation
    * @param unknown_type $relationId
    */
   public function setRelation($relation = null, $relationId = null)
   {
      if ($relation !== null && $relationId !== null) {
         $this->_relations[$relation] = $relationId;
      }

      return $this;
   }


   /**
    *
    * priority is for finding the right value if two forms are on the same date
    *
    * @param type $table
    * @param type $priority
    * @param type $order
    * @param type $extFields
    * @param type $where
    * @return \preloadData
    */
   public function setRelationTable($table, $priority, $order, $extFields = array(), $where = null)
   {
      $this->_relationTables[$table] = 1;

      if (is_array($order) === false) {
         $this->_relationOrder[$table] = array(
            'order'    => $order,
            'sort'     => 'DESC',
            'priority' => $priority
         );
      } else {
         $this->_relationOrder[$table] = array(
            'order' => reset($order),
            'sort'  => (count($order) == 1 ? 'DESC' : end($order)),
            'priority' => $priority
         );
      }

      foreach ($extFields as $extField => $as) {
         if (is_numeric($extField) === true) {
            $this->_extFields[$table][$as] = $as;
         } else {
            $this->_extFields[$table][$extField] = $as;
         }
      }

      if ($where !== null && is_array($where) === true) {
         foreach ($where as $field => $cond) {
            $this->_relationWhere[$table][$field] = $cond;
         }
      }

      return $this;
   }


   public function initialize($order)
   {
      return $this
         ->_getFields()
         ->_intersect()
         ->_addExtFields()
         ->_getData()
         ->_sortData($order);
   }


   private function _sortData($order)
   {
      foreach ($this->_initializeFields as $fieldName => &$field) {

         if ($order == 'ASC') {
            ksort($field);
         } else {
            krsort($field);
         }

         $field = reset($field);
      }

      return $this;
   }


   public function getFields()
   {
      return $this->_initializeFields;
   }

   public function getConvertedFields()
   {
      return $this->_convertFields();
   }

   private function _convertFields()
   {
        $convertedFields = array();
        foreach ($this->_initializeFields as $name => $field) {
           if (preg_match('~^[0-9]+\.[0-9]+$~', $field)) {
              $convertedFields[$name] = str_replace('.', ',', $field);
           } elseif (preg_match('~[0-9]{4}-[0-9]{2}-[0-9]{2}~', $field)) {
              list($year, $month, $day) = explode('-', $field);
              $convertedFields[$name] = "{$day}.{$month}.{$year}";
           } else {
              $convertedFields[$name] = $field;
           }
        }

        return $convertedFields;
   }

   private function _getFields()
   {
      foreach ($this->_relationTables as $table => $fields) {
         $keys = array_keys($this->_widget->loadExtFields("fields/app/{$table}.php"));

         unset($keys[array_search('createuser', $keys)]);
         unset($keys[array_search('createtime', $keys)]);
         unset($keys[array_search('updateuser', $keys)]);
         unset($keys[array_search('updatetime', $keys)]);

         $this->_relationTables[$table] = $keys;
      }

      return $this;
   }

   private function _intersect()
   {
      $keys = array();

      eval('$keys = array_intersect($this->_relationTables["' . implode('"], $this->_relationTables["', array_keys($this->_relationTables)) . '"]);');

      $intersectedKeys = array();

      foreach ($keys as $key) {
         $intersectedKeys[$key] = $key;
      }

      foreach ($this->_relationTables as &$table) {
         $table = $intersectedKeys;
      }

      return $this;
   }


   private function _addExtFields()
   {
      foreach ($this->_relationTables as $table => &$tableFields) {
         if (array_key_exists($table, $this->_extFields) === true) {
            $extFields = $this->_extFields[$table];

            foreach ($extFields as $extField => $as) {
               $tableFields[$extField] = $as;
            }
         }
      }

      return $this;
   }


   private function _getData()
   {
      foreach ($this->_relationTables as $table => $tableFields) {
         $priority = $this->_relationOrder[$table]['priority'];

         $keys    = array_keys($tableFields);
         $values  = array_values($tableFields);

         $fields  = array();

         foreach ($keys as $index => $key) {
            $fields[] = "{$key} AS '{$values[$index]}'";
         }

         $impFields = implode(', ', $fields);

         $where = array();

         foreach ($this->_relations as $relation => $relationId) {
            $where[] = "{$relation} = '{$relationId}'";
         }

         $impWhere = implode(', ', $where);

         if (isset($this->_relationWhere[$table]) === true) {
            foreach ($this->_relationWhere[$table] as $field => $cond) {
               $cond = is_array($cond) === false ? array($cond) : $cond;
               $in   = implode("','", $cond);

               $impWhere .= " AND `{$field}` IN ('{$in}')";
            }
         }

         $query = "
            SELECT
               {$this->_relationOrder[$table]['order']},
               {$impFields}
            FROM `{$table}`
            WHERE
               {$impWhere}
            ORDER BY
               {$this->_relationOrder[$table]['order']} {$this->_relationOrder[$table]['sort']}
         ";

         $datasets = sql_query_array($this->_db, $query);

         foreach ($values as $value) {
            foreach ($datasets as $dataset) {
               if (strlen(trim($dataset[$value])) > 0) {
                  $date = $dataset[$this->_relationOrder[$table]['order']];

                  $this->_initializeFields[$value][$date][$priority] = $dataset[$value];
               }
            }
         }
      }

      foreach ($this->_initializeFields as $fieldName => $dates) {
          foreach ($dates as $date => $values) {
              $key = max(array_keys($values));
              $this->_initializeFields[$fieldName][$date] = $values[$key];
          }
      }

      return $this;
   }
}

?>