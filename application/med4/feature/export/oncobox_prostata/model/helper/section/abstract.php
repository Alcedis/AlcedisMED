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

abstract class Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{
    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected $_cache = array();


    /**
     * _parameters
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
     * returns cache
     *
     * @access  public
     * @param   string  $method
     * @return  array
     */
    public function getCache($method)
    {
        return (array_key_exists($method, $this->_cache) === true ? $this->_cache[$method] : null);
    }


    /**
     * setTemplates
     *
     * @access  public
     * @param   array   $templates
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
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
     * getFromTemplate
     *
     * @access  public
     * @param   string  $name
     * @param   int $id
     * @return  array
     */
    public function getFromTemplate($name, $id)
    {
        $template = null;

        if (array_key_exists($name, $this->_templates) === true && array_key_exists($id,  $this->_templates[$name]) === true) {
            $template = $this->_templates[$name][$id];
        }

        return $template;
    }


    /**
     * sets cache
     *
     * @access  public
     * @param   string  $method
     * @param   string  $value
     * @return  void
     */
    public function setCache($method, $value)
    {
        $this->_cache[$method] = $value;
    }


    /**
     * resetCache
     *
     * @access  public
     * @return  Concobox_prostata_e_5_3_1_Model_Helper_Section_Interface
     */
    public function resetCache()
    {
        $this->_cache = array();

        return $this;
    }


    /**
     * return first filled value in forms
     *
     * @access  public
     * @param   array   $records
     * @param   string  $form
     * @param   string  $field
     * @return  string
     */
    public function getFirstFilled($records, $form, $field)
    {
        foreach ($records[$form] as $record) {
            if (strlen($record[$field]) > 0) {
                return $record[$field];
            }
        }

        return null;
    }


    /**
     * return first filled value in forms
     *
     * @access  public
     * @param   array   $records
     * @param   string  $form
     * @param   string  $field
     * @return  string
     */
    public function getLastFilled($records, $form, $field)
    {
        foreach ($records[$form]->reverse() as $record) {
            if (strlen($record[$field]) > 0) {
                return $record[$field];
            }
        }

        return null;
    }


    /**
     * returns field, when conditionalField has conditionalValue
     *
     * @access  public
     * @param   array  $records
     * @param   string $form
     * @param   string $field
     * @param   string $conditionField
     * @param   string $conditionValue
     * @return  string
     */
    public function getConditionalFirstFilled($records, $form, $field, $conditionField, $conditionValue = 'filled')
    {
        $conditionField = $conditionField === false ? $field : $conditionField;

        foreach ($records[$form] as $record) {
            switch (true) {
                case ($conditionValue === 'filled'):
                    if (strlen($record[$conditionField]) > 0) {
                        return $record[$field];
                    }

                    break;

                case (is_array($conditionValue)):
                    if (in_array($record[$conditionField], $conditionValue) === true) {
                        return $record[$field];
                    }

                    break;

                default :
                    if ($record[$conditionField] === $conditionValue) {
                        return $record[$field];
                    }

                    break;
            }
        }

        return null;
    }


    /**
     * returns array, when conditionalField has conditionalValue
     *
     * @access  public
     * @param   array  $records
     * @param   string $form
     * @param   string $field
     * @param   string $conditionField
     * @param   string $conditionValue
     * @return  array
     */
    public function getConditionalAllFilled($records, $form, $field, $conditionField, $conditionValue = 'filled')
    {
        $result = array();

        $conditionField = $conditionField === false ? $field : $conditionField;
        $key = 0;

        foreach ($records[$form] as $form) {

            if (array_key_exists('datum', $form)) {
                $key = $form['datum'];
            } else {
                $key++;
            }

            if ($conditionValue === 'filled') {
                if (strlen($form[$conditionField]) > 0) {
                    $result[$key] = $form[$field];
                }
            } elseif ($form[$conditionField] === $conditionValue) {
                $result[$key] = $form[$field];
            }
        }

        return $result;
    }


    /**
     * get field with content from tumorstate where t starts with p
     *
     * @access  public
     * @param   array   $records
     * @param   string  $field
     * @return  string
     */
    public function getFirstFilledFromTumorstateP($records, $field)
    {
        $result = null;

        foreach ($records['tumorstatus'] as $ts) {
            if (str_starts_with($ts['t'], 'p') === true) {
                $result = $ts[$field];
                break;
            }
        }

        return $result;
    }



    /**
     * ifEmpty
     *
     * @access  public
     * @param   string  $string
     * @param   string  $string2
     * @return  string
     */
    public function ifEmpty($string, $string2)
    {
        return (($string === null || strlen($string) === 0) ? $string2 : $string);
    }


    /**
     * setParameter
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
     * getParameter
     *
     * @access  public
     * @param   string  $param
     * @return  array
     */
    public function getParameter($param)
    {
        return (array_key_exists($param, $this->_parameters) === true ? $this->_parameters[$param] : null);
    }


    /**
     * map
     *
     * @access  public
     * @param   string  $value
     * @param   array   $map
     * @param   string  $default
     * @return  string
     */
    public function map($value, $map, $default = null)
    {
        return array_key_exists($value, $map) === true ? $map[$value] : $default;
    }


    /**
     * mapFinalStatus
     *
     * @access  public
     * @param   string  $end
     * @param   string  $reason
     * @return  string
     */
    public function mapFinalStatus($end, $reason)
    {
        $map = array('patw' => 'VF', 'hn'  => 'AN', 'nhn' => 'AN');

        if ($end === 'plan') {
            return 'E';
        } elseif (strlen($reason) > 0) {
            return $this->map($reason, $map) === null ? 'S' : $this->map($reason, $map);
        } elseif (strlen($end) === 0) {
            return 'E';
        }

        return null;
    }


    /**
     * renderStudyDate
     *
     * @access
     * @param $records
     * @param $section
     * @return null
     */
    public function renderStudyDate($records, $section)
    {
        foreach ($records['studie']->reverse() as $record) {
            $template = $this->getFromTemplate('studie', $record['vorlage_studie_id']);

            if ($template !== null && strlen($template['ethikvotum']) > 0) {
                $this->setCache($section . 'StudienTyp', $record);

                return (strlen($record['date']) > 0 ? $record['date'] : $record['beginn']);
            }
        }

        return null;
    }


    /**
     * renderStudytype
     *
     * @access
     * @param $records
     * @param $section
     * @return string
     */
    public function renderStudytype($records, $section)
    {
        $record = $this->getCache($section . 'StudienTyp');

        $type = 'X';

        if ($record !== null) {
            $template = $this->getFromTemplate('studie', $record['vorlage_studie_id']);

            $type = 'SX';

            if ($template !== null && strlen($template['studientyp']) > 0) {
                $type = $template['studientyp'] === 'inter' ? 'IS' : 'NIS';
            }
        } else {
            if ($this->getConditionalFirstFilled($records, 'therapieplan', 'studie', 'studie', '0') !== null) {
                $type = 'KS';
            }
        }

        return $type;
    }


    /**
     * renderPsychoOncology
     *
     * @access
     * @param $records
     * @return string
     */
    public function renderPsychoOncology($records)
    {
        foreach ($records['beratung'] as $record) {
            $check = $record['psychoonkologie'];
            if ($check === '1' && $record['psychoonkologie_dauer'] > 30) {
                return 'J';
            } else if ($check === '0' || ($check === '1' && $record['psychoonkologie_dauer'] <= 30)) {
                return 'N';
            }
        }
        return 'X';
    }


    /**
     * renderSocialService
     *
     * @access
     * @param $records
     * @return string
     */
    public function renderSocialService($records)
    {
        foreach ($records['beratung'] as $record) {
            $check = $record['sozialdienst'];
            if ($check === '1') {
                return 'J';
            } else if ($check === '0') {
                return 'N';
            }
        }
        return 'X';
    }



    /**
     * render renderMorbidityConference
     *
     * @access  public
     * @param   array   $records
     * @return  string
     */
    public function renderMorbidityConference($records)
    {
        $id = $this->getConditionalFirstFilled($records, 'konferenz_patient', 'konferenz_patient_id', 'art', 'morb');
        return ($id !== null ? 'J' : 'N');
    }


    /**
     * returns form name
     *
     * @access
     * @param   array   $records
     * @return  string
     */
    public function getFormName($records)
    {
        if ($records !== null) {
            switch (true) {
                case array_key_exists('nachsorge_erkrankung_id', $records):
                    return 'nachsorge';
                    break;
                case array_key_exists('tumorstatus', $records):
                    return 'tumorstatus';
                    break;
                case array_key_exists('abschluss_id', $records):
                    return 'abschluss';
                    break;
            }
        }

        return null;
    }


    /**
     *
     *
     * @static
     * @access  public
     * @param   $records
     * @return  array
     */
    static public function getPrimaryProstatectomy($records)
    {
        $result = null;

        foreach ($records['eingriff'] as $record) {
            if (strlen($record['art_primaertumor']) > 0) {
                foreach ($record['eingriff_ops'] as $ops) {
                    if (str_starts_with($ops['prozedur'], '5-604.') ||
                        str_starts_with($ops['prozedur'], '5-576.2') ||
                        str_starts_with($ops['prozedur'], '5-576.3') ||
                        str_starts_with($ops['prozedur'], '5-576.4') ||
                        str_starts_with($ops['prozedur'], '5-576.5')) {
                        $result = $record;
                    }
                }
            }
        }

        return $result;
    }
}
