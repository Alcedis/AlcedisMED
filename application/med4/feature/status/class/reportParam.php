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

class statusReportParam
{


    /**
     * _instance
     *
     * @access  private
     * @var     statusReportParam
     */
    private static $_instance;

    private $_db = null;

    private $_queries = array();

    private $_relations = array();


    /**
     * getInstance
     *
     * @static
     * @access  public
     * @return  statusReportParam
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    /**
     * setCall
     * set call function for table
     *
     * @param unknown_type $table
     * @param unknown_type $function
     */
    public static function setCall($table, $function)
    {
        $srp = self::getInstance();

        $srp->_queries[$table][] = $function;

        return $srp;
    }

    public static function setDb($db)
    {
        $srp = self::getInstance();

        $srp->_db = $db;

        return $srp;
    }

    public function getDB(){
        return $this->_db;
    }


    public static function fire($table, $formId = null, $getParentFormId = false)
    {
        $srp = self::getInstance();

        if (isset($srp->_queries[$table]) === true) {
            foreach ($srp->_queries[$table] as $query) {
                $replace = $formId !== null && strlen($formId) > 0 ? "AND s.form_id = '{$formId}'" : NULL;
                $query = str_replace('(ident)', $replace, $query);

                mysql_query($query, $srp->getDb());
            }
        } elseif (isset($srp->_relations[$table]) === true) {
            foreach ($srp->_relations[$table] as $relation) {
                $id = $getParentFormId === false
                    ? $formId
                    : dlookup($srp->getDb(), $table, "MAX({$relation}_id)", "{$table}_id = '{$formId}'")
                ;

                $srp->fire($relation, $id, $getParentFormId);
            }
        }

        return $srp;
    }

    public static function fireAll()
    {
        $srp = self::getInstance();

        foreach (array_keys($srp->_queries) as $table) {
            $srp->fire($table);
        }

        return $srp;
    }

    public static function setRelation($table, $relation)
    {
        $srp = self::getInstance();

        $srp->_relations[$table][] = $relation;

        return $srp;
    }

}

?>
