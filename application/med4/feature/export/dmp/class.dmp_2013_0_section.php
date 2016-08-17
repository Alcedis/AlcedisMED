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

require_once('class.dmp_2013_0_observation.php');

class Cdmp_2013_0_Section
{

    /**
     * @access protected
     * @var string
     */
    protected $_caption = "";


    /**
     * @access protected
     * @var array
     */
    protected $_observations = array();


    /**
     * @access public
     * @return void
     */
    public function __construct()
    {
    }


    /**
     *
     *
     * @access public
     * @param $caption
     * @return void
     */
    public function SetCaption($caption)
    {
        $this->_caption = $caption;
    }


    /**
     *
     *
     * @access public
     * @param $observation
     * @return void
     */
    public function AddObservation($observation)
    {
        $this->_observations[] = $observation;
    }


    /**
     *
     *
     * @access public
     * @return array
     */
    public function ToArray()
    {
        $result = array();
        $result['caption'] = $this->_caption;
        $result['observations'] = array();
        foreach ($this->_observations as $item) {
            $result['observations'][] = $item->ToArray();
        }
        return $result;
    }


    /**
     *
     *
     * @access public
     * @return bool
     */
    public function HasObservations()
    {
        if (count($this->_observations) > 0) {
            return true;
        }
        return false;
    }

}

?>
