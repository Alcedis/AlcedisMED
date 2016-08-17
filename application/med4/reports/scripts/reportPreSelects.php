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

/**
 * Class reportPreSelects
 */
class reportPreSelects extends customReport
{
    /**
     * _preSelects
     *
     * @access  protected
     * @var     array
     */
    protected $_preSelects = array();


    /**
     * _preSelectResults
     *
     * @access  protected
     * @var     array
     */
    protected $_preSelectResults = array();



    public function __construct($renderer, $db, $smarty, $subdir, $type, $params = null){

        $this->_preSelectInit();

        parent::__construct($renderer, $db, $smarty, $subdir, $type, $params);
    }


    /**
     * Base preSelect Initialization
     */
    protected function _preSelectInit()
    {
        $this->_addPreSelect('zufrFragebogen', "
            SELECT
                vorlage_fragebogen_id AS 'id'
            FROM vorlage_fragebogen
            WHERE
                art = 'zufr'
        ");

        $this->_addPreSelect('trastuzumabImmun', "
            SELECT
                vt.vorlage_therapie_id AS 'id'
            FROM vorlage_therapie vt
                INNER JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = vt.vorlage_therapie_id AND
                                                             (vtw.wirkstoff = 'trastuzumab' OR vtw.wirkstoff = 'trastuzumab-emtansin')
            WHERE
                vt.art IN ('ist', 'ci', 'i')
            GROUP BY
                vt.vorlage_therapie_id
        ");

        $this->_addPreSelect('dacarbazinChemo', "
            SELECT
                vt.vorlage_therapie_id AS 'id'
            FROM vorlage_therapie vt
                INNER JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = vt.vorlage_therapie_id AND
                                                             vtw.wirkstoff = 'dacarbazin'
            WHERE
                vt.art IN ('cst', 'ci', 'c')
            GROUP BY
                vt.vorlage_therapie_id
        ");

        $this->_addPreSelect('brafIndikator', "
            SELECT
                vt.vorlage_therapie_id AS 'id'
            FROM vorlage_therapie vt
                INNER JOIN vorlage_therapie_wirkstoff vtw ON vtw.vorlage_therapie_id = vt.vorlage_therapie_id AND
                                                             vtw.wirkstoff IN ('sorafenib','vemurafenib')
            WHERE 1
            GROUP BY
                vt.vorlage_therapie_id
        ");


        return $this;
    }

    protected function _addPreSelect($name, $statement)
    {
        if (array_key_exists($name, $this->_preSelects) === true) {
            echo "preSelect '{$name}' already exists";
            exit;
        }

        $this->_preSelects[$name] = $statement;

        return $this;
    }

    protected function _getPreSelect($name)
    {
        if (array_key_exists($name, $this->_preSelects) === false) {
            echo "preSelect '{$name}' doesn´t exists";
            exit;
        }

        return $this->_firePreSelect($name, $this->_preSelects[$name]);
    }


    protected function _firePreSelect($name, $statement)
    {
        if (array_key_exists($name, $this->_preSelectResults) === false) {

            $result = array();

            foreach (sql_query_array($this->_db, $statement) as $dataset) {
                $result[] = $dataset['id'];
            }

            $this->_preSelectResults[$name] = (count($result) > 0 ? implode(',', $result) : '0');
        }

        return $this->_preSelectResults[$name];
    }
}

?>
