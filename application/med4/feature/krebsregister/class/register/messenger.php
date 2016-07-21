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
 * Class registerMessenger
 */
class registerMessenger
{
    /**
     * _hasData
     *
     * @access  protected
     * @var     bool
     */
    protected $_hasData = false;


    /**
     * _data
     *
     * @access  protected
     * @var     array
     */
    protected $_data = array(
        'id' => null
    );


    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected static $_cache = array(
        'fachabteilung' => null,
        'org' => array()
    );


    /**
     * _messages
     *
     * @access protected
     * @var    array
     */
    protected $_messages = array();


    /**
     * getId
     *
     * @access  protected
     * @return  int
     */
    public function getId()
    {
        return $this->_data['id'];
    }


    /**
     * create
     *
     * @static
     * @access  public
     * @param   int $id
     * @return  registerMessenger
     */
    public static function create($id)
    {
        return new self($id);
    }


    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->_data['id'] = $id;
    }


    /**
     * addMessage
     *
     * @access  public
     * @param   registerPatientMessage $message
     * @return  registerMessenger
     */
    public function addMessage(registerPatientMessage $message)
    {
        $this->_messages[] = $message;

        return $this;
    }


    /**
     * getMessages
     *
     * @access  public
     * @return  array
     */
    public function getMessages()
    {
        return $this->_messages;
    }


    /**
     * load
     *
     * @access  public
     * @param   resource $db
     * @param   array    $params
     * @return  registerMessenger
     */
    public function load($db, array $params)
    {
        $id    = $this->_data['id'];
        $orgId = $params['org_id'];
        $cache = self::$_cache;

        // build department cache
        if ($cache['fachabteilung'] === null) {
            $cache['fachabteilung'] = getLookup($db, 'fachabteilung');
        }

        // build org cache
        if (array_key_exists($orgId, $cache['org']) === false) {
            $orgQuery = "
                SELECT
                    ik_nr                              AS melder_iknr,
                    CONCAT_WS(' ', strasse, hausnr)    AS melder_anschrift,
                    plz                                AS melder_plz,
                    ort                                AS melder_ort
                FROM org
                WHERE org_id = '{$orgId}'
            ";

            $cache['org'][$orgId] = reset(sql_query_array($db, $orgQuery));
        }

        $query = "
            SELECT
                user_id                            AS id,
                lanr                               AS melder_lanr,
                bsnr                               AS melder_bsnr,
                fachabteilung                      AS melder_kh_abt_station_praxis,
                null                               AS meldende_stelle,
                CONCAT_WS(', ', nachname, vorname) AS melder_arztname,
                bank_name                          AS melder_bankname,
                bank_kontoinhaber                  AS melder_kontoinhaber,
                null                               AS melder_bic,
                null                               AS melder_iban
            FROM user
            WHERE user_id = '{$id}'
        ";

        $data = reset(sql_query_array($db, $query));

        // Für Ticket #28447
        // Wenn kein Melder im KR-Formular angegeben ist, wird das Array mit leeren Daten gefüllt.
        if (false === is_array($data) || 0 === count($data)) {
            $data = array(
                'id' => '',
                'melder_lanr' => '',
                'melder_bsnr' => '',
                'melder_kh_abt_station_praxis' => '',
                'meldende_stelle' => '',
                'melder_arztname' => '',
                'melder_bankname' => '',
                'melder_kontoinhaber' => '',
                'melder_bic' => '',
                'melder_iban' => ''
            );
        } else {
            $dep = $data['melder_kh_abt_station_praxis'];

            if (array_key_exists($dep, $cache['fachabteilung']) === true) {
                $data['melder_kh_abt_station_praxis'] = $cache['fachabteilung'][$dep];
            }
        }

        $data['meldende_stelle'] = $params['meldende_stelle'];

        $this->_data = array_merge(
            $data,
            $cache['org'][$orgId]
        );

        $this->_hasData = true;

        return $this;
    }


    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        return $this->_data;
    }


    /**
     * hasData
     *
     * @access  public
     * @return  bool
     */
    public function hasData()
    {
        return $this->_hasData;
    }
}
