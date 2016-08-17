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

class queryModifier
{
    /**
     * _cookie
     *
     * @access  protected
     * @var     cookie
     */
    protected $_cookie;


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _query
     *
     * @access  protected
     * @var     string
     */
    protected $_query;


    /**
     * _sql
     *
     * @access  protected
     * @var     string
     */
    protected $_sql;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;

    protected $_where = 1;

    protected $_baseWhere = 1;

    protected $_having = array();

    protected $_orderBy;

    protected $_orderType;

    protected $_groupBy = null;

    protected $_entriesPerPage = 25;

    protected $_currentPage = 1;

    protected $_datasetCount = 0;

    protected $_limit = null;

    protected $_noLimit = false;

    protected $_liveConditions = array();

    protected $_searchString = array();

    protected $_formFilter = array();

    protected $_searchFields = null;

    protected $_bflData = array();

    protected $_joins = array();

    protected $_table = '';

    protected $_cookieAdd = array();


    /**
     * @param $db
     * @param Smarty $smarty
     */
    public function __construct($db, Smarty $smarty)
    {
       $this->_smarty = $smarty;
       $this->_db     = $db;
    }


    /**
     * create queryModifier
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   Smarty $smarty
     * @return  queryModifier
     */
    public static function create($db, Smarty $smarty)
    {
        return new self($db, $smarty);
    }


    /**
     * buildSqlString
     *
     * @access  public
     * @return  string
     */
    public function buildSqlString()
    {
       $query = preg_replace('/SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $this->_query, 1);

       $this->_sql = concat(array(
          $query,
          $this->getJoins(),
          $this->getWhere(),
          $this->getGroupBy(),
          $this->getHaving(),
          $this->getOrderBy(),
          $this->getLimit()
       ), ' ');

       return $this->_sql;
    }


    /**
     * query
     *
     * @access  public
     * @return  int
     */
    public function query()
    {
        $sqlString = $this->getSql();

        $result = sql_query_array($this->_db, $sqlString);

        $this->_datasetCount = reset(reset(sql_query_array($this->_db, "SELECT FOUND_ROWS()")));

        return $result;
    }


    /**
     * injectStatement
     *
     * @static
     * @access  public
     * @param   string  $query
     * @param   string  $position
     * @param   string  $statement
     * @return  string
     */
    public static function injectStatement($query, $position, $statement)
    {
       return str_replace($position, $position . ' ' . $statement . ' ', $query);
    }



    //sonderfunktion: benutzung auf eigene gefahr!!!
    protected function _dateFormat($dates, $mod = false) {
        $formatDates = array();
         foreach ((is_array($dates) ? $dates : array($dates)) as $nr => $date) {
             $date       = str_replace('-', '', $date);
             $dateParts  = explode('.', $date);

             $formatDates[] = "$dateParts[2]-$dateParts[1]-$dateParts[0]";
         }

         return count($formatDates) == 1 ? $formatDates[0] : $formatDates;
    }


    /**
     * _checkDateSearch
     *
     * @access  protected
     * @param   string  $searchTerm
     * @return  bool|int|string
     */
    protected function _checkDateSearch($searchTerm)
    {
        $preg = array(
             preg_match('~^\d{2}\.\d{2}\.\d{4}$~', $searchTerm), //datum
             preg_match('~^\d{2}\.\d{2}\.\d{4}-$~', $searchTerm), //von datum
             preg_match('~^-\d{2}\.\d{2}\.\d{4}$~', $searchTerm), //bis datum
             preg_match('~^\d{2}\.\d{2}\.\d{4}-\d{2}\.\d{2}\.\d{4}$~', $searchTerm), //von-bis datum
             preg_match('~^\d{4}$~', $searchTerm), //jahr
             preg_match('~^\d{4}-$~', $searchTerm), //von jahr
             preg_match('~^-\d{4}$~', $searchTerm), //bis jahr
             preg_match('~^\d{4}-\d{4}$~', $searchTerm) //von-bis jahr
        );

         foreach ($preg as $case => $check) {
             if ($check) {
                 return $case;
             }
         }

         return false;
    }


    /**
     * buildSearchFields
     *
     * @access  public
     * @return  void
     */
    public function buildSearchFields()
    {
        $searchField = $this->_searchFields;
        $searchTerms = $this->_searchString;
        $formFilter  = $this->_formFilter;

        if (count($formFilter) > 0) {
            foreach ($searchField as $field => $info) {
                if ($info['type'] == 'filter') {

                     $or = array();

                     foreach ($formFilter as $filter) {

                         $or[] = "{$info['field']} LIKE '%|{$filter}|%'";
                     }

                     $this->_where .= ' AND (' . implode(' OR ', $or) . ')';
                }
            }
        }

        foreach ($searchField as $field => $info) {

             if (isset($searchTerms[$field]) === true ) {
                 $term = $searchTerms[$field];

                 switch ($info['type']) {
                     case 'string' :
                         $this->_addWhere($term, $info['field']);
                         break;

                     case 'lookup':

                        $this->_addWhere($term, $info['field']);
                        break;

                     case 'check' :

                        $this->_addWhere($term, $info['field'], false, true);
                        break;

                     case 'date' :
                         $term = str_replace(' ', null, $term);

                         if (preg_match('~.+-.+~', $term)) {
                             $parts = explode('-', $term);
                             $term = implode('-', array(convertDate($parts[0]), convertDate($parts[1])));
                         } else {
                             $term = convertDate($term);
                         }

                         $cases = $this->_checkDateSearch($term);

                         if ($cases !== false) {
                             switch($cases) {
                                 case '0' :
                                     $this->_addWhere($this->_dateFormat($term), $info['field']);
                                     break;
                                 case '1' :
                                     $this->_where .= " AND $info[field] >= '" . $this->_dateFormat($term) . "'";
                                     break;
                                 case '2' :
                                     $this->_where .= " AND $info[field] <= '" . $this->_dateFormat($term) . "'";
                                     break;
                                 case '3' :
                                     $dates = $this->_dateFormat(explode('-', $term), 1);
                                     $this->_where .= " AND $info[field] BETWEEN '$dates[0]' AND '$dates[1]'";
                                     break;
                                 case '4' :
                                     $this->_addWhere($term, "YEAR($info[field])");
                                     break;
                                 case '5' :
                                     $term = trim($term, ' -');
                                     $this->_where .= " AND YEAR($info[field]) >= '$term'";
                                     break;
                                 case '6' :
                                     $term = trim($term, ' -');
                                     $this->_where .= " AND YEAR($info[field]) <= '$term'";
                                     break;
                                 case '7' :
                                     $dates = explode('-', $term);
                                     $this->_where .= " AND YEAR($info[field]) BETWEEN '$dates[0]' AND '$dates[1]'";
                                     break;
                                 default :
                                     $this->_addWhere($term, $info['field'], true);
                                     break;
                                 }
                             } else {
                                 $this->_addWhere($term, $info['field'], true);
                             }
                         break;
                     case 'group' :
                         $this->_having[] = "$info[field] LIKE '%$term%'";
                         break;
                     default :
                         break;
                 }
             }
        }
    }


    /**
     * _addWhere
     *
     * @access  protected
     * @param   string  $term
     * @param   string  $field
     * @param   bool $dateCast
     * @param   bool $null
     * @return  void
     */
    protected function _addWhere($term, $field, $dateCast = false, $null = false)
    {
         $parts = explode(' ', trim($term));
         $sub = array();

         if ($dateCast) {
             $field = "CAST(DATE_FORMAT($field, '%d.%m.%Y') AS CHAR)";
         }

       if ($null == true) {

          $req = $field . ($term == 0 ? ' IS NULL' : ' = ' . $term);

       } else {
          foreach ($parts as $subterm) {
             $sub[] = "$field LIKE '%$subterm%'";
          }

          $req = implode(' AND ', $sub);
       }

       $this->_where .= " AND ($req)";
    }


    /**
     * getSql
     *
     * @access  public
     * @param   bool $fromQuery
     * @return  string
     */
    public function getSql($fromQuery=false)
    {
        $processData = $this->_cookie->getProcessData();

        if ($processData !== null) {
             if (isset($processData['formFilter']) === true) {
                $this->_formFilter = $processData['formFilter'];
             }

             if (isset($processData['searchString'])) {
                $this->_searchString = $processData['searchString'];
             }

             if ($processData['limit'] != $this->_entriesPerPage && $processData['limit'] !== null || $this->_limit !== null) {
                $this->_entriesPerPage = $this->_limit !== null ? $this->_limit : $processData['limit'];
             }

             if ($processData['orderBy'] != $this->_orderBy && $processData['orderBy'] !== null) {

                //Shice hack ......
                $orderBy = $processData['orderBy'] === 'createtime'
                   ? 'p.createtime'
                   : (isset($this->_searchFields[$processData['orderBy']]) === true
                      ? $this->_searchFields[$processData['orderBy']]['field']
                      : null
                   )
                ;

                if ($orderBy !== null) {

                    if ($processData['orderType'] != $this->_orderType && $processData['orderType'] !== null) {
                        $this->_orderType = $processData['orderType'];
                    }

                    $this->_orderBy = $orderBy;
                }
             }

             if ($processData['currentPage'] != $this->_currentPage && $processData['currentPage'] !== null) {
                $this->_currentPage = $processData['currentPage'];
             }

             $this->buildSearchFields();

             $this->_calculateLimit();
        }

        return $this->buildSqlString();
    }


    /**
     * _calculateLimit
     *
     * @access  protected
     * @return  queryModifier
     */
    protected function _calculateLimit()
    {
        if ($this->_currentPage == 1) {
            $this->setLimit($this->_entriesPerPage);
        } else {
            $beginn = ($this->_currentPage - 1) * $this->_entriesPerPage;

            $this->setLimit("$beginn, " . $this->_entriesPerPage);
        }

        return $this;
    }


    public function setHaving($having)
    {
       $this->_having[] = $having;

       return $this;
    }

    protected function getHaving()
    {
        return count($this->_having) ? 'HAVING ' . implode(' AND ', $this->_having) : '';
    }

    public function getDatasetCount()
    {
       return ($this->_datasetCount == 0 ? 1 : $this->_datasetCount);
    }

    public function getFullDatasetCount($query = null, $field = null)
    {
       $return = null;

       if ($query !== null) {
          $result = reset(sql_query_array($this->_db, $query));
          $return = $result['count'];
       } elseif ($field !== null) {
          $query = "
             SELECT
                COUNT(c.{$field}) AS 'count'
             FROM (
                {$this->_query}
                WHERE {$this->_baseWhere}
                GROUP BY {$field}
             ) c
          ";

          $result = reset(sql_query_array($this->_db, $query));

          $return = $result['count'];
       }

       return $return;
    }

    public function getWhere()
    {
       $where = $this->_where;

       //add liveCondition
       foreach ($this->_liveConditions as $type => $conditions) {
          foreach ($conditions as $condition) {
              preg_match_all('~\[([^\]]+)\]~', $condition, $findings);
              $noCondition = false;

              if (count($findings[0]) > 0) {
                 if (isset($this->_bflData[$findings[1][0]])) {
                     $condition = str_replace($findings[0][0], $this->_bflData[$findings[1][0]], $condition);
                 } else {
                    $noCondition = true;
                 }
              }

              if ($noCondition === false) {
                  if ($type == 'or') {
                     $where = "($where) OR $condition";
                  }
              }
          }
       }

       return "WHERE {$where}";
    }

    public function setTable($table)
    {
       $this->_table = $table;

       return $this;
    }

    public function setWhere($where)
    {
       $this->_where = strlen($where) == 0 ? '1' : $where;

       $this->_baseWhere = strlen($where) == 0 ? '1' : $where;

       return $this;
    }

    public function addLiveCondition($type, $condition)
    {
         $this->_liveConditions[strtolower($type)][] = $condition;

         return $this;
    }

    public function addBflData($key, $data)
    {
         $this->_bflData[$key] = $data;

         return $this;
    }


    /**
     * Now optimized with sql calc found rows
     *
     * @param     $query string
     * @return    queryModifier
     */
    public function setQuery($query)
    {
       $this->_query = $query;

       return $this;
    }

    public function setLimit($limit, $off = false)
    {
       if ($off === true) {
          $this->_limit = null;
          $this->_noLimit = true;
       } else {
           $this->_limit = $limit;
       }

       return $this;
    }


    public function getLimit()
    {
       if ($this->_noLimit === true) {
           $limit = '';
       } else {
           $limit = "LIMIT ".  ($this->_limit !== null ? $this->_limit : $this->_entriesPerPage);
       }

       return $limit;
    }


    public function setOrderBy($order, $orderAlias = null)
    {
       if ($orderAlias !== null) {
          $order = "{$order} {$orderAlias}";
       }

       $this->_orderBy = $order;

       return $this;
    }

    public function getOrderBy()
    {
       return concat(array("ORDER BY ". $this->_orderBy, $this->getOrderType()), ' ');
    }


    public function setOrderType($orderType)
    {
       $this->_orderType = $orderType;

       return $this;
    }

    public function getOrderType()
    {
       return strtoupper($this->_orderType);
    }

    public function addJoin($join)
    {
       $this->_joins[] = $join;

       return $this;
    }

    public function getJoins()
    {
       return implode(' ', $this->_joins);
    }


    public function setGroupBy($groupBy)
    {
       $this->_groupBy = $groupBy;

       return $this;
    }

    public function getGroupBy()
    {
       return (strlen($this->_groupBy) > 0 ? "GROUP BY {$this->_groupBy}" : null);
    }

    public function setCookie($cookie)
    {
       $this->_cookie = $cookie;

       return $this;
    }

    public function setCookieAdd($field)
    {
        $this->_cookieAdd[] = $field;

        return $this;
    }


    /**
     * setSearchFields
     *
     * @access  public
     * @param   array $searchFields
     * @return  queryModifier
     */
    public function setSearchFields($searchFields)
    {
       $this->_searchFields = $searchFields;

       $lookups = array();

       //Special smarty assign for lookups
       foreach ($searchFields as $field => $searchField) {
          if ($searchField['type'] == 'lookup') {
             if (isset($searchField['class']) == false && isset($searchField['content']) == false) {
                echo "no lookup-class for '{$field}' detected";
                exit;
             }

             if (isset($searchField['content']) === false) {
                 $where = isset($searchField['ignore'])
                     ? ' AND code NOT IN ("' . implode('","', $searchField['ignore']) .   '")'
                     : ''
                 ;

                 $class = sql_query_array($this->_db, "SELECT code, bez FROM l_basic WHERE klasse = '{$searchField['class']}' $where ORDER BY pos");

                 foreach ($class as $i => $element) {
                     $class[$i]['bez'] = escape($element['bez']);
                 }
             } else {
                 $class = array();

                 foreach ($searchField['content'] as $code => $bez) {
                     $class[] = array('code' => $code, 'bez' => $bez);
                 }
             }

             $lookups[$field] = json_encode(array(
                'val'       => $searchField['val'],
                'content'   => $class
             ));
          }
       }

       $this->_smarty->assign(
          'queryMod',
           array(
             'lookups' => $lookups
           )
       );

       return $this;
    }

}

?>
