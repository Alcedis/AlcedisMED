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

require_once('helper/section/abstract.php');
require_once('helper/section/interface.php');

class Concobox_prostata_e_5_3_1_Model_Helper
{
    /**
     * _parameter
     *
     * @access  protected
     * @var     array
     */
    protected $_parameters = array();


    /**
     * _templates
     *
     * @access  protected
     * @var     array
     */
    protected $_templates = array();


    /**
     * helper stack
     *
     * @access  protected
     * @var     Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface[]
     */
    protected $_stack = array();


    /**
     * getHelper
     *
     * @access  public
     * @param   string  $section
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
     */
    public function getSectionHelper($section)
    {
        if (array_key_exists($section, $this->_stack) === false) {
            $fileName = strtolower($section);

            require_once("helper/section/{$fileName}.php");

            $name = 'Concobox_prostata_e_5_3_1_Model_Helper_Section_' . $section;

            /* @var Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface $helper */
            $this->_stack[$section] = $helper = new $name();

            $helper
                ->setParameters($this->getParameters())
                ->setTemplates($this->getTemplates())
            ;
        } else {
            $helper = $this->_stack[$section];
        }

        return $helper;
    }


    /**
     * setTemplates
     *
     * @access  public
     * @param   array   $templates
     * @return  Concobox_prostata_e_5_3_1_Model_Helper
     */
    public function setTemplates($templates)
    {
        $this->_templates = $templates;

        return $this;
    }


    /**
     * getTemplates
     *
     * @access  public
     * @return  array
     */
    public function getTemplates()
    {
        return $this->_templates;
    }


    /**
     * resetSectionHelperCache
     *
     * @access  public
     * @return  Concobox_prostata_e_5_3_1_Model_Helper
     */
    public function resetSectionHelperCache()
    {
        foreach ($this->_stack as $helper) {
            $helper->resetCache();
        }

        return $this;
    }


    /**
     * setParameters
     *
     * @access  public
     * @param   array   $parameters
     * @return  Concobox_prostata_e_5_3_1_Model_Helper
     */
    public function setParameters($parameters)
    {
        $this->_parameters = $parameters;

        return $this;
    }


    /**
     * getParameters
     *
     * @access  public
     * @return  array
     */
    public function getParameters()
    {
        return $this->_parameters;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $disease
     * @return bool
     */
    static public function getProstatectomy($disease)
    {
        foreach ($disease['eingriff'] as $eingriff) {
            foreach ($eingriff['eingriff_ops'] as $ops) {
                if (str_starts_with($ops['prozedur'], '5-604.') ||
                    str_starts_with($ops['prozedur'], '5-576.2') ||
                    str_starts_with($ops['prozedur'], '5-576.3') ||
                    str_starts_with($ops['prozedur'], '5-576.4') ||
                    str_starts_with($ops['prozedur'], '5-576.5')) {
                    return $eingriff;
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $records
     * @return array|bool
     */
    static public function getProstatectomyByPriority($records)
    {
        $results = array();
        $codeStartsWith = array(
            '5-604.12' => '1',
            '5-604.11' => '2',
            '5-604.1'  => '3',
            '5-604.02' => '4',
            '5-604.01' => '5',
            '5-604.0'  => '6',
            '5-604.32' => '7',
            '5-604.31' => '8',
            '5-604.3'  => '9',
            '5-604.22' => '10',
            '5-604.21' => '11',
            '5-604.2'  => '12',
            '5-604.52' => '13',
            '5-604.51' => '14',
            '5-604.5'  => '15',
            '5-604.42' => '16',
            '5-604.41' => '17',
            '5-604.4'  => '18',
            '5-604.x'  => '19',
            '5-604.y'  => '19',
            '5-576.20' => '20',
            '5-576.21' => '20',
            '5-576.2x' => '20',
            '5-576.30' => '20',
            '5-576.31' => '20',
            '5-576.3x' => '20',
            '5-576.40' => '20',
            '5-576.41' => '20',
            '5-576.4x' => '20',
            '5-576.50' => '20',
            '5-576.51' => '20',
            '5-576.5x' => '20'
        );

        foreach ($records['eingriff'] as $eingriff) {
            $tmp = array();
            foreach ($eingriff['eingriff_ops'] as $ops) {
                foreach ($codeStartsWith as $code => $prio) {
                    if ($code == $ops['prozedur']) {
                        $tmp[$prio] = $eingriff;
                    }
                }
            }
            if (count($tmp) > 0) {
                ksort($tmp);
                $keys = array_keys($tmp);
                $results[$keys[0]] = $tmp[$keys[0]];
            }
        }
        if (count($results) > 0) {
            ksort($results);
            return $results;
        }
        return false;
    }


    /**
     * @static
     * @access
     * @param      $eingriff
     * @param bool $artRezidiv
     * @return bool|string
     */
    static public function getOpsCodesByPriority($eingriff, $artRezidiv = false)
    {
        $results = array();
        $codeStartsWith = array(
            '5-604.12' => '1',
            '5-604.11' => '2',
            '5-604.1'  => '3',
            '5-604.02' => '4',
            '5-604.01' => '5',
            '5-604.0'  => '6',
            '5-604.32' => '7',
            '5-604.31' => '8',
            '5-604.3'  => '9',
            '5-604.22' => '10',
            '5-604.21' => '11',
            '5-604.2'  => '12',
            '5-604.52' => '13',
            '5-604.51' => '14',
            '5-604.5'  => '15',
            '5-604.42' => '16',
            '5-604.41' => '17',
            '5-604.4'  => '18',
            '5-604.x'  => '19',
            '5-604.y'  => '19',
            '5-576.20' => '20',
            '5-576.21' => '20',
            '5-576.2x' => '20',
            '5-576.30' => '20',
            '5-576.31' => '20',
            '5-576.3x' => '20',
            '5-576.40' => '20',
            '5-576.41' => '20',
            '5-576.4x' => '20',
            '5-576.50' => '20',
            '5-576.51' => '20',
            '5-576.5x' => '20'
        );

        foreach ($eingriff['eingriff_ops'] as $ops) {
            foreach ($codeStartsWith as $code => $prio) {
                if ($code == $ops['prozedur']) {
                    if (true === $artRezidiv && '1' != $eingriff['art_rezidiv']) {
                        continue;
                    }
                    $results[$prio] = $ops['prozedur'];
                }
            }
        }
        if (count($results) > 0) {
            ksort($results);
            return implode('/', $results);
        }
        return false;
    }

}

?>
