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

class CGkrAddresses
{

    /**
     * @access
     * @var array
     */
    protected $_gkrAddresses = array();


    /**
     * @access
     * @var null
     */
    protected $_db = null;


    /**
     * @access
     * @var
     */
    private static $_instance;


    /**
     *
     */
    private function __construct()
    {
    }


    /**
     *
     *
     * @static
     * @access
     * @return mixed
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            $className = __CLASS__;
            self::$_instance = new $className;
        }
        return self::$_instance;
    }


    /**
     *
     *
     * @access
     * @param $db
     * @return void
     * @throws EExportException
     */
    public function init($db)
    {
        $this->_db = $db;
        $this->_gkrAddresses = $this->_readAllGkrAddresses();
        if (false === $this->_gkrAddresses) {
            throw new EExportException("ERROR: GKR-Addresses not read.");
        }
    }


    /**
     *
     *
     * @access
     * @return array|bool
     */
    protected function _readAllGkrAddresses()
    {
        $query = "
            SELECT
               *

            FROM
               l_exp_gkr_addresses
        ";
        $result = sql_query_array($this->_db, $query);
        if ((false !== $result) && (count($result) > 0)) {
            foreach ($result as &$row) {
                if (strlen($row['plz']) < 5) {
                    while (strlen($row['plz']) < 5) {
                        $row['plz'] = '0' . $row['plz'];
                    }
                }
            }
            return $result;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $plz
     * @param $ort
     * @return int
     */
    public function checkAddress($plz, $ort)
    {
        return 0; // FIX für Ticket #13438 - Immer korrekt zurück geben!!!
        /*
        $result = 2; // Nicht vorhanden
        foreach ($this->_gkrAddresses as $i => $address) {
            if (($address['plz'] == $plz) &&
                ($address['stadt'] == $ort)) {
                // Ist korrekt
                return 0;
            }
            else if (($address['plz'] == $plz) &&
                     ($address['stadt'] != $ort)) {
                // Plz okay, Ort nicht
                $result = 1;
            }
        }
        return $result;
        */
    }

}
