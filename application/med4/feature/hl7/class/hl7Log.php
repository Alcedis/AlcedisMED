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

class hl7Log extends hl7Parser
{
    /**
     *
     * @var type
     */
    protected $_logData = array();

    /**
     *
     * @var type
     */
    protected $_logType = null;

    /**
     *
     * @var type
     */
    protected $_logStatus = null;


    protected $_logFilter = array();

    public function setLogType($type)
    {
        $this->_logType = $type;

        return $this;
    }

    public function setLogStatus($status)
    {
        $this->_logStatus = $status;

        return $this;
    }

    public function setLogData($data)
    {
        foreach ($data as $key => $value) {
            $this->_logData[$key] = $value;
        }

        return $this;
    }


    /**
     * addLogFilter
     *
     * @access
     * @param $filter
     * @param $data
     * @return $this
     */
    public function addLogFilter($filter, $data)
    {
        $this->_logFilter[$filter] = $data;

        return $this;
    }

    public function writeLog()
    {
        //base data
        $data = array(
            'names' => array(
                'status',
                'filter'
            ),
            'values' => array(
                "'{$this->_logStatus}'",
                "'" . json_encode($this->_logFilter) . "'"
            )
        );

        foreach ($this->_logData as $name => $value) {
            $data['names'][] = $name;
            $data['values'][] = strlen($value) > 0
                ? "'" . $this->_escape($value). "'"
                : 'NULL'
            ;
        }

        $query = "INSERT INTO `hl7_log_{$this->_logType}` (`" . implode('`,`', $data['names']) . "`) VALUES (" . implode(',', $data['values']) . ")";

        mysql_query($query, $this->_db);

        return $this;
    }
}

?>
