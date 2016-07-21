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
 * Class validatorType
 */
class validatorType
{
    /**
     * css_class
     *
     * @access  public
     * @var     string
     */
    public $css_class;


    /**
     * msg
     *
     * @access  public
     * @var     array
     */
    public $msg = array();


    /**
     * pool
     *
     * @access  public
     * @var     array
     */
    public $pool = array();


    /**
     * field
     *
     * @access  public
     * @var     array
     */
    public $field = array();


    /**
     * msg_pool
     *
     * @access  public
     * @var     validatorMessage[]
     */
    public $msg_pool = array();


    /**
     * multimessage
     *
     * @access  public
     * @var     bool
     */
    public $multimessage = false;


    /**
     * @param $cssClass
     */
    public function __construct($cssClass)
    {
        $this->css_class = $cssClass;
    }


    /**
     * hasTypeInMsgPool
     *
     * @access  public
     * @param   string $msg
     * @return  bool
     */
    public function hasTypeInMsgPool($msg)
    {
        return array_key_exists($msg, $this->msg_pool);
    }
}
