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

require_once 'register/state/default.php';
require_once 'register/map.php';
require_once 'register/patient.php';
require_once 'register/messenger.php';
require_once 'register/patient/collection.php';
require_once 'register/messenger/collection.php';
require_once 'register/export/serializer.php';

require_once 'register/helper.php';


/**
 * Class register
 * factory
 */
class register
{
    /**
     * _register
     *
     * @access  protected
     * @var     registerStateInterface
     */
    protected $_registerState;


    /**
     * create register
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   Smarty   $smarty
     * @param   string   $type
     * @param   array    $params
     * @return  register
     */
    public static function create($db, $smarty, $type, array $params = array())
    {
        return new self($db, $smarty, $type, $params);
    }


    /**
     * factory
     *
     * @param   resource    $db
     * @param   Smarty      $smarty
     * @param   string      $type
     * @param   array       $params
     * @throws  Exception
     */
    public function __construct($db, $smarty, $type, array $params = array())
    {
        require_once 'register/state/' . $type . '.php';

        $className = 'registerState' . ucfirst($type);

        HDatabase::LoadExportSettings($db, $params, 'kr_' . $type);

        /* @var registerStateInterface $registerState */
        $registerState = new $className($db, $smarty, $params);

        if (($registerState instanceof registerStateInterface) === false) {
            throw new Exception("loaded state '{$type}' is no valid type");
        }

        $this->_registerState = $registerState;
    }


    /**
     * getRegisterState
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function getRegisterState()
    {
        return $this->_registerState;
    }
}
