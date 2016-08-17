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

class Cdmp_2014_0_Observation
{

    /**
     * @access
     * @var string
     */
    protected $_parameter = '';


    /**
     * @access
     * @var array
     */
    protected $_ergebnisTexte = array();


    /**
     * @access
     * @var array
     */
    protected $_zeitpunkte = array();


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
     * @param $parameter
     * @return void
     */
    public function SetParameter($parameter)
    {
        $this->_parameter = $parameter;
    }


    /**
     *
     *
     * @access public
     * @param $ergebnisText
     * @return void
     */
    public function AddErgebnisText($ergebnisText)
    {
        $this->_ergebnisTexte[] = $ergebnisText;
    }


    /**
     *
     *
     * @access public
     * @param $zeitpunkt
     * @return void
     */
    public function AddZeitpunktDttm($zeitpunkt)
    {
        $this->_zeitpunkte[] = $zeitpunkt;
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
        $result['parameter'] = $this->_parameter;
        $result['ergebnistexte'] = $this->_ergebnisTexte;
        $result['zeitpunkte'] = $this->_zeitpunkte;
        return $result;
    }

}

?>
