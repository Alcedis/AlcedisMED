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

class pdf2Swf
{
    /**
     * _pdf_file
     *
     * @access  protected
     * @var     string
     */
    protected $_pdf_file;


    /**
     * _swf_file
     *
     * @access  protected
     * @var     string
     */
    protected $_swf_file;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;


    /**
     * _path
     *
     * @access  protected
     * @var     string
     */
    protected $_path;


    /**
     * @param Smarty    $smarty
     * @param string    $pdfFilePath
     * @param string    $swfFilePath
     */
    public function __construct($smarty, $pdfFilePath, $swfFilePath = null)
    {
        $this->_smarty   = $smarty;
        $this->_pdf_file = $pdfFilePath;
        $this->_swf_file = $swfFilePath;

        $this->_init();
    }


    /**
     * create pdf2Swf
     *
     * @static
     * @access  public
     * @param   Smarty  $smarty
     * @param   string  $pdfFilePath
     * @param   string  $swfFilePath
     * @return  pdf2Swf
     */
    public static function create($smarty, $pdfFilePath, $swfFilePath = null)
    {
        return new self($smarty, $pdfFilePath, $swfFilePath);
    }


    /**
     * _init
     *
     * @access  protected
     * @return  pdf2Swf
     */
    protected function _init()
    {
      $this->_smarty->config_load(FILE_CONFIG_SERVER,  'pdf2swf');

      $config = $this->_smarty->get_config_vars();

      $this->_path = $config['pdf2swf_url'];

      return $this;
    }


    /**
     * checkFile
     *
     * @access  protected
     * @return  string
     */
    protected function _checkFile()
    {
        return mime_content_type($this->_pdf_file);
    }


    /**
     * callConvert
     *
     * @access  public
     * @param   bool    $bitmapOnly
     * @return  string
     */
    public function callConvert($bitmapOnly = false)
    {
        if ($this->_checkFile() != 'application/pdf') {
            return;
        }

        $out=array();

        if (!file_exists($this->_path)){
           array_push($out,"Das Programm ".$this->_path." konnte nicht gefunden werden");
        }

        if (!file_exists($this->_pdf_file)){
           array_push($out,"Die Datei ".$this->_pdf_file." konnte nicht gefunden werden");
        }

        $params = null;
        $ending = null;

        if ($bitmapOnly === true) {
            $params = "-G -j100 -s zoom=150 -s bitmap ";
            $ending = '_bmp';
        }

        if ($this->_swf_file === null) {
            $this->_swf_file =  substr($this->_pdf_file, 0, -4) . $ending . '.swf';
        }

        exec($this->_path . ' ' .  "'{$this->_pdf_file}'" . " -t -T 9 -z {$params}-o " . "'{$this->_swf_file}'", $out, $value);

        return $this->_swf_file;
    }


    /**
     * deleteSwfFile
     *
     * @access  public
     * @return  pdf2Swf
     */
    public function deleteSwfFile() {
        if ($this->_swf_file !== null) {
            unlink($this->_swf_file);
        }

        return $this;
    }
}
