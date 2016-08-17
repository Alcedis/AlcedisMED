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

class jodConverter
{
    /**
     * URL to JOD converter
     *
     * @access protected
     * @var string
     */
    protected $_url = null;

    protected $_filePath = null;

    protected $_fileName = null;

    protected $_outputName = null;

    protected $_status = false;

    public static function create() {
        return new self();
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

        return $this;
    }

    public function setFile($path, $name)
    {
        $this->_filePath = $path;

        $this->_fileName = $name;

        return $this;
    }

    /**
     * Converts the data
     *
     * @access public
     * @param $inputData
     * @param string $inputType
     * @param string $outputType
     *
     */
    public function convert($inputType, $outputType)
    {
        $mimeType = mimeType::create();

        set_include_path(get_include_path() . PATH_SEPARATOR . DIR_LIB . '/pear');

        require_once 'HTTP/Request.php';

        $request = new HTTP_Request($this->_url);
        $request->setMethod("POST");
        $request->addHeader("Content-Type", $mimeType->get($inputType));
        $request->addHeader("Accept", $mimeType->get($outputType));

        $request->setBody(file_get_contents($this->_filePath . $this->_fileName));

        $pear = new PEAR;

        if (!$pear->isError($request->sendRequest())) {
            $convertedFile  = $request->getResponseBody();

            $this->_outputName = substr($this->_fileName, 0, 0 - strlen($inputType)) . $outputType;

            file_put_contents($this->_filePath . $this->_outputName, $convertedFile);

            $this->_status = true;
        }

        return $this;
    }

    /**
     *
     * @return NULL
     */
    public function getOutputName()
    {
        return $this->_outputName;
    }
}

?>