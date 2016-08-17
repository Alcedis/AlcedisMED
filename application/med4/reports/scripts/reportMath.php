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

require_once 'reports/scripts/reportHelper.php';

/**
 * Class reportMath
 */
class reportMath extends reportHelper
{

    /**
     * SEP_ROWS
     *
     * @var string
     */
    const SEP_ROWS = "\x01";


    /**
     * SEP_COLS
     * @var string
     */
    const SEP_COLS = "\x02";


    /**
     * _selectMinByDate
     *
     * @access  protected
     * @param   string  $recordString
     * @param   bool    $reverse
     * @return  array
     */
    protected function _selectMinByDate($recordString, $reverse = false)
    {
        $entries = explode('|', $recordString);

        foreach ($entries as &$entry) {
            $entry = explode(',', $entry);
        }

        $order = array();

        foreach ($entries as $check) {
            if (count($check) === 2) {
                $order[$check[0]] = $check[1];
            }
        }

        if (count($order) === 0) {
            return null;
        }

        ksort($order);

        return $reverse ? end($order) : reset($order);
    }


    /**
     * _selectMaxByDate
     *
     * @access  protected
     * @param   string  $recordString
     * @return  array
     */
    protected function _selectMaxByDate($recordString)
    {
        return $this->_selectMinByDate($recordString, true);
    }


    /**
     * _kaplanMeier
     *
     * @access  protected
     * @param   array $dataArray
     * @return  array
     */
    protected function _kaplanMeier($dataArray)
    {
        if (
            (array_key_exists('range', $dataArray) === false || array_key_exists('event', $dataArray) === false) ||
            (is_array($dataArray['range']) === false || is_array($dataArray['event']) === false) ||
            count($dataArray['range']) === 0 || count($dataArray['range']) !== count($dataArray['event'])
        ){
            return array('x' => array(), 'y' => array());
        }

        // Init
        $xdata   = array();
        $ydata   = array();

        $s = kaplanMeierAlgorithm($dataArray, true);

        // Umwandlung in Daten für Diagramm-Ausgabe
        $previous_s = 1;

        foreach( $s as $t => $cur_s )
        {
          $xdata[] = $t;
          $xdata[] = $t;
          $ydata[] = $previous_s * 100;
          $ydata[] = $cur_s * 100;
          $previous_s = $cur_s;
        }

        return array('x' => $xdata, 'y' => $ydata);
    }


    /**
     * _getFigoSection
     *
     * @access  protected
     * @param   string  $string
     * @return  string
     */
    protected function _getFigoSection($string)
    {
        $value = null;

        if (strlen($string) > 0) {
            switch (true) {
                case substr($string, 0, 2) == 'IV':     $value = 'IV';  break;
                case substr($string, 0, 3) == 'III':    $value = 'III'; break;
                case substr($string, 0, 2) == 'II':     $value = 'II';  break;
                case substr($string, 0, 1) == 'I':      $value = 'I';   break;
                case substr($string, 0, 1) == '0':      $value = '0';   break;
            }
        }

        return $value;
    }


    /**
     * _getUiccSection
     *
     * @access  protected
     * @param   string  $string
     * @return  string
     */
    protected function _getUiccSection($string)
    {
        return $this->_getFigoSection($string);
    }


    /**
     * _pt
     *
     * @access  protected
     * @param   array $records
     * @return  array
     */
    protected function _pt(array $records)
    {
        $output = array();

        for ($i=0;$i<=1; $i++) {
            if (strlen($records[$i]['pt_hirarchy']) > 0) {
                $output[$records[$i]['pt_hirarchy']] = $records[$i]['pt_section'];
            }
        }

        krsort($output);

        return reset($output);
    }


    /**
     * _getLastDate
     *
     * @access  protected
     * @param   string $string
     * @return  string
     */
    protected function _getLastDate($string)
    {
        return $this->_getDate($string, 'DESC');
    }


    /**
     * _getDate
     *
     * @access  protected
     * @param   string  $string
     * @param   string  $sort
     * @param   string  $default
     * @return  string
     */
    protected function _getDate($string, $sort = 'ASC', $default = null)
    {
        $value = $default;

        if (strlen($string) > 0) {
            $dates = explode('|', $string);

            if ($sort == 'ASC') {
                asort($dates);
            } else {
                arsort($dates);
            }

            $value = reset($dates);
        }

        return $value;
    }


    /**
     * _distinct
     *
     * @access  protected
     * @param   string  $string
     * @param   string  $separator
     * @param   string  $addSeperator
     * @param   array   $exclude
     * @return  string
     */
    protected function _distinct($string, $separator = ' ', $addSeperator = ' ', $exclude = array(' **'))
    {
        $excluded    = false;
        $return      = $string;

        if (strlen($return) > 0) {
            if (strpos($string, $separator) !== false) {
                $exclude = array_unique($exclude);

                foreach ($exclude as $ex) {
                    if (strpos($string, $ex) !== false) {
                        $string      = str_replace($ex, md5($ex), $string);
                        $excluded    = true;
                    }
                }

                $return = implode($addSeperator, array_unique(explode($separator, $string)));

                if ($excluded === true) {
                    foreach ($exclude as $ex) {
                        $return = str_replace(md5($ex), $ex, $return);
                    }
                }
            }
        }

        return $return;
    }


    /**
     * _removeIdentifier
     *
     * @access  protected
     * @param   string  $string
     * @param   string  $identifier
     * @param   string  $separator
     * @return  string
     */
    protected function _removeIdentifier($string, $identifier='|', $separator = ', ')
    {
        $return = $string;

        if (strlen($return) > 0) {
            if (strpos($string, $identifier) !== false) {
                $explodedString = explode($separator, $string);

                foreach ($explodedString as $i => $parts) {
                    $explodedString[$i] = substr($parts, strpos($parts, $identifier) + strlen($identifier));
                }

                $return = implode($separator, $explodedString);
            }
        }

        return $return;
    }


    /**
     * _sortInitDataArray
     *
     * @access  protected
     * @param   array   $data
     * @return  array
     */
    protected function _sortInitDataArray($data)
    {
        $return = array();

        $srcKeys = array_keys($data);

        $sortedKeys = array();

        foreach ($srcKeys as $i => $srcKey) {
            $val = str_replace('_a', 0, $srcKey);
            $val = str_replace('_n', 1, $val);
            $val = str_replace('_z', 2, $val);
            $sortedKeys[$i] = str_replace('_', '.', substr($val, 3));
        }

        asort($sortedKeys);

        foreach ($sortedKeys as $i => $sortedKey) {
            $return[$srcKeys[$i]] = $data[$srcKeys[$i]];
        }

        return $return;
    }


    /**
     * recordStringToArray
     *
     * @access  public
     * @param   string $record
     * @param   array  $names
     * @param   string $order
     * @param   string $orderDirection
     * @return  array
     */
    public function recordStringToArray($record, array $names = array(), $order = null, $orderDirection = 'ASC')
    {
        $result = array();

        if (is_string($record) === true && strlen($record) > 0) {
            $rows = explode(self::SEP_ROWS, $record);

            foreach ($rows as $row_str) {
                $row = explode(self::SEP_COLS, $row_str);
                $i = 0;
                $row_data = array();

                foreach( $row as $col ) {
                    if (count($names) > 0 && isset($names[$i]) === true) {
                        $row_data[$names[$i]] = $col;
                    } else {
                        $row_data[] = $col;
                    }

                    $i++;
                }

                $result[] = $row_data;
            }
        }

        // order
        if ($order !== null && count($result) > 0) {
            $tmp = array();

            foreach ($result as $dataset) {
                $tmp[$dataset[$order]][] = $dataset;
            }

            if ($orderDirection === 'ASC') {
                ksort($tmp);
            } else {
                krsort($tmp);
            }

            $result = array();

            foreach ($tmp as $records) {
                foreach ($records as $record) {
                    $result[] = $record;
                }
            }
        }

        return $result;
    }
}

?>
