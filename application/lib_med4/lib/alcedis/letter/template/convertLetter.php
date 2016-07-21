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

set_include_path( get_include_path() . PATH_SEPARATOR . DIR_LIB . '/pear' );

require_once 'HTTP/Request.php';

class ConvertLetter
{
    /**
     * URL to JOD converter
     *
     * @access protected
     * @var string
     */
    protected $_url = "";


    /**
     * constructor for convert.php
     *
     * @access public
     */
    public function __construct()
    {
      /*require_once('pear/PEAR.php');
        require_once('pear/Net/Socket.php');
        require_once('pear/Net/URL.php');
        require_once('pear/HTTP/Request.php');*/
    }


    /**
     * Sets the URL to JOP converter
     *
     * @access public
     * @param string $url
     */
    public function setUrlToJOD($url)
    {
        $this->_url = $url;
    }


    /**
     * Converts the data
     *
     * @access public
     * @param $inputData
     * @param string $inputType
     * @param string $outputType
     */
    public function convert($inputData, $inputType, $outputType)
    {
        $request = new HTTP_Request($this->_url);
        $request->setMethod("POST");
        $request->addHeader("Content-Type", $inputType);
        $request->addHeader("Accept", $outputType);
        $request->setBody($inputData);

        $pear = new PEAR;
        if (!$pear->isError($request->sendRequest())) {
            return $request->getResponseBody();
        } else {
            return false;
        }
    }
}
?>
