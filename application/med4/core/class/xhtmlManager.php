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

class xhtmlManager
{
    protected $_smarty   = null;

    protected $_type     = null;

    protected $_formId   = null;

    protected $_file     = null;

    protected $_paths    = null;

    protected $_xhtml    = null;

    protected $_css      = null;

    protected $_stripImageTags = false;

    protected $_convertXhtml   = false;

    protected $_reconvertXhtml = false;


    public function __construct($smarty, $type, $formId, $file, $location = null)
    {
        $this->_smarty     = $smarty;
        $this->_type       = $type;
        $this->_formId     = $formId;
        $this->_file       = $file;
        $this->_location   = $location;
    }


    public static function create($smarty, $type, $formId, $file, $location = null) {
        return new self($smarty, $type, $formId, $file, $location);
    }


    /**
     * Set xhtml
     *
     */
    public function setXhtml($xhtml = null)
    {
        if ($xhtml !== null) {
            $this->_xhtml = $xhtml;

            if ($this->_reconvertXhtml === true) {
                $this->_reconvertXhtml();
            }
        }

        return $this;
    }


    /**
     * Saves xhtml from _xhtml to file
     *
     */
    public function saveXhtml()
    {
        if ($this->_xhtml !== null) {

            $filePath = $this->getXhtmlPath();
            $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getXhtmlPath();

            if ($filePath !== null) {
                file_put_contents ($filePath, $this->_xhtml);
            }
        }

        return $this;
    }


    /**
     * Saves css from xhtml to file
     *
     */
    public function saveCss()
    {
        if ($this->_css !== null) {
            $filePath = $this->getCssPath();
            $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getCssPath();

            if ($filePath !== null) {
                file_put_contents ($filePath, $this->_css);
            }
        }

        return $this;
    }


    /**
     * Special function for converting current xhtml to ckeditor compatibility
     *
     */
    public function convertXhtmlForCkEditor()
    {
        return $this->_reconvertXhtml(true);
    }


    /**
     * removes complete xml folder
     *
     */
    public function removeXmlDir()
    {
        $fileDir = $this->getXhtmlDir();
        $fileDir = $fileDir !== null ? $fileDir : $this->_buildXhtmlPath()->getXhtmlDir();

        if ($fileDir !== null) {
            deltree($fileDir);
        }

        return $this;
    }


    /**
     * unset the loaded xhtml
     *
     */
    public function unsetXhtml()
    {
        $this->_xhtml = null;

        return $this;
    }


    /**
     * return the xhtml of the selected file
     *
     * @param unknown_type $xhtml
     * @return getXhtmlFromFile
     */
    public function getXhtml()
    {
        if ($this->_xhtml === null) {
            $filePath = $this->getXhtmlPath();
            $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getXhtmlPath();

            if ($filePath !== null) {
                $this->_xhtml = file_get_contents($filePath);
            }

            if ($this->_stripImageTags === true) {
                $this->_stripImageTags();
            }

            if ($this->_convertXhtml === true) {
                $this->_convertXhtml();
            }
        }

        return $this->_xhtml;
    }


    /**
     * return the xhtml of the selected file
     *
     * @param unknown_type $xhtml
     * @return getXhtmlFromFile
     */
    public function getCss()
    {
        if ($this->_css === null) {
            $filePath = $this->getCssPath();
            $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getCssPath();

            if ($filePath !== null && file_exists($filePath) === true) {
                $this->_css = file_get_contents($filePath);
            }
        }

        return $this->_css;
    }


    /**
     * loads xhtml of the file to class
     */
    public function loadXhtml()
    {
        $filePath = $this->getXhtmlPath();
        $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getXhtmlPath();

        if ($filePath !== null) {
            $this->_xhtml = file_get_contents($filePath);
        }

        return $this;
    }


    /**
     * loads css of the xhtml to class
     *
     */
    public function loadCss()
    {
        $filePath = $this->getCssPath();
        $filePath = $filePath !== null ? $filePath : $this->_buildXhtmlPath()->getCssPath();

        if ($filePath !== null && file_exists($filePath) === true) {
            $this->_css = file_get_contents($filePath);
        }

        return $this;
    }


    /**
     * build path for the xml
     *
     */
    protected function _buildXhtmlPath()
    {
        $upload        = getUploadDir($this->_smarty, 'upload', false);
        $documentDir   = $upload['upload'] . $upload['config']['document_dir'];
        $fileDir       = $this->_type . '_' . $this->_formId . '/';
        $fileName      = count(explode('.', $this->_file)) > 1 ? $this->_file : $this->_file . '.html';
        $cssName       = count(explode('.', $this->_file)) > 1 ? $this->_file : $this->_file . '.css';

        $this->_paths['dir']  = array(
            'xhtml'  => $documentDir . $upload['config']['xhtml_dir'] . $fileDir
        );

        $this->_paths['file'] = array(
            'path'     => $this->_paths['dir']['xhtml'] . $this->_file . '/' . $fileName
        );

        $this->_paths['css'] = array(
            'path'     => $this->_paths['dir']['xhtml'] . $this->_file . '/' . $cssName
        );

        if (is_dir($this->_paths['dir']['xhtml']) == false && strlen($this->_formId) > 0) {
            mkdir ($this->_paths['dir']['xhtml'], 0777, true);
        }

        return $this;
    }


    /**
     * returns the path of the xhtml dir
     *
     */
    public function getXhtmlDir()
    {
        $return = null;

        if (isset($this->_paths['dir']['xhtml']) === true && strlen($this->_paths['dir']['xhtml']) > 0) {

            $xhtmlDir = $this->_paths['dir']['xhtml'] . $this->_file;

            if (is_dir($xhtmlDir) == true) {
                $return = $xhtmlDir;
            }
        }

        return $return;
    }


    /**
     * returns xhtml file path if file exists else null
     *
     */
    public function getXhtmlPath()
    {
        $return = null;

        if (isset($this->_paths['file']['path']) === true
            && strlen($this->_paths['file']['path']) > 0
            && file_exists($this->_paths['file']['path']) === true) {

            $return = $this->_paths['file']['path'];
        }

        return $return;
    }


    /**
     * returns xhtml file path if file exists else null
     *
     */
    public function getCssPath()
    {
        $return = null;

        if (isset($this->_paths['css']['path']) === true
            && strlen($this->_paths['css']['path']) > 0) {

            $return = $this->_paths['css']['path'];
        }

        return $return;
    }



    /**
     * sets xthml path if file exists
     *
     * @param string
     */
    public function setXhtmlPath($path)
    {
        if (file_exists($path) === true) {
            $this->_paths['file']['path'] = $path;
        }

        return $this;
    }


    /**
     * setConvertXhtml
     *
     * @param $boolean
     */
    public function setConvertXhtml($boolean = true)
    {
        $this->_convertXhtml = $boolean;

        return $this;
    }

    /**
     * setConvertXhtml
     *
     * @param $boolean
     */
    public function setReconvertXhtml($boolean = true)
    {
        $this->_reconvertXhtml = $boolean;

        return $this;
    }


    /**
     * convert xhtml for editor.... totally wrong place... but...OMG
     *
     */
    protected function _convertXhtml()
    {
        $xhtml = escape($this->_xhtml);

        $this->_xhtml = $xhtml;

        return $this;
    }


    /**
     * convert edtior xhtml back to valid xhtml .... holy...
     *
     */
    protected function _reconvertXhtml($firstConvert = false)
    {
        $xhtml = $this->_xhtml;

        if ($firstConvert === true) {
            $xhtmlParts = explode('</style>', $xhtml);

            //Part0 (Css)
            $css = preg_replace('/<style[^>]*>/', '', reset($xhtmlParts));

            //transparent background color create grey background bug in tcpdf
            $css = str_replace(array("background-color: transparent;"), '', $css);

            // add in alcCss defined css classes
            foreach (alcCss::getStyles() as $style) {
                if (strpos($css, $style) === false) {
                    $css .= $style . "\n";
                }
            }

            $this->_css = $css;

            //Part1 (xhtml)
            $xhtml = end($xhtmlParts);
        }

        //replace escaped tags
        $xhtml = str_ireplace(array('\"', "\'"), array('"', "'"), $xhtml);

        //make one string
        $xhtml = str_replace(array("\n\r", "\n", "\r"), '', $xhtml);

        //replace &nbsp;</p>
        $xhtml = str_replace('&nbsp;', ' ', $xhtml);

        // workaround for unwanted whitespace before custom style injection
        $xhtml = preg_replace('(\[\[nospace]]<)', '<', $xhtml);

        // keep wished whitespaces
        $xhtml = preg_replace('/> +([^<])/', '>' . "\x01" . '$1', $xhtml);

        // remove whitespaces
        $xhtml = preg_replace('~>\s+<~', '><', $xhtml);
        $xhtml = preg_replace('~>\s+~', '>', $xhtml);

        // restore wished whitespaces
        $xhtml = str_replace("\x01", ' ', $xhtml);

        //replace empty p with br p
        $xhtml = preg_replace('/<p class="([^"]*)"><\/p>/','<p class="$1"><br/></p>', $xhtml);

        if ($firstConvert === true) {
            $xhtml = UTF8ToEntities($xhtml);
        }

        $this->_xhtml = $xhtml;
        return $this;
    }


    /**
     * returnes alcedisCssStyles
     *
     * @access  public
     * @return  array
     */
    public function getCssStyles()
    {
        return $this->_alcedisCssStyles;
    }
}
?>
