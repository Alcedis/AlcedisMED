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

require_once('interface.exportserialiser.php' );
require_once('class.exportbaseobject.php' );
require_once('helper.filesystem.php' );

class CExportXmlSerialiser extends CExportBaseObject implements IExportSerialiser
{
    /**
     * m_internal_smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $m_internal_smarty;


    /**
     * m_xml_template_file
     *
     * @access  protected
     * @var     string
     */
    protected $m_xml_template_file = "";


    /**
     * m_xml_schema_file
     *
     * @access  protected
     * @var     string
     */
    protected $m_xml_schema_file = "";


    /**
     * m_export_record
     *
     * @access  protected
     * @var     RExport
     */
    protected $m_export_record;


    /**
     * create CExportXmlSerialiser
     *
     * @access  public
     * @param   string   $absolute_path
     * @param   string   $export_name
     * @param   Smarty   $smarty
     * @param   resource $db
     * @param   string   $error_function
     * @return     void
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        parent::create($absolute_path, $export_name, $smarty, $db, $error_function);

        $this->m_internal_smarty = new Smarty();
        $this->m_internal_smarty->template_dir = "feature/export/$export_name"; //$this->m_smarty->template_dir;
        $this->m_internal_smarty->compile_dir = $this->m_smarty->compile_dir;
        $this->m_internal_smarty->config_dir = $this->m_smarty->config_dir;
        $this->m_internal_smarty->cache_dir = $this->m_smarty->cache_dir;
        $this->m_internal_smarty->plugins_dir = $this->m_smarty->plugins_dir;
        $this->m_internal_smarty->force_compile = true;
        $this->m_internal_smarty->caching = 0;
        $this->m_internal_smarty->debugging = true;
        $this->m_internal_smarty->error_reporting = E_ALL & ~E_NOTICE & ~'E_WARN';
    }


    /**
     * setData
     *
     * @access  public
     * @param   RExport $export_record
     * @return  void
     */
    public function setData(&$export_record)
    {
        $this->m_export_record = $export_record;
    }


    /**
     * validate
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     */
    public function validate($parameters)
    { }


    /**
     * encrypt
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     */
    public function encrypt($parameters)
    { }


    /**
     * write
     *
     * @access  public
     * @param   array $parameters
     * @return  string
     */
    public function write($parameters)
    {
        $export_path = $this->GetExportPath($parameters['main_dir'], $parameters['login_name']);

        if (file_exists($export_path)) {
            HFileSystem::DeleteDirectory($export_path);
        }

        $xml_dir = $export_path . $parameters['xml_dir'];

        HFileSystem::CreatePath($xml_dir);

        return $xml_dir . $this->GetFilename();
    }


    /**
     * GetFilename
     *
     * @access  public
     * @return  string
     */
    public function GetFilename()
    {
        return "";
    }


    /**
     * xmlSchemaValidate
     *
     * @access  protected
     * @param   string  $xml_string
     * @param   string  $xml_schema
     * @return  string
     * @throws  EExportException
     */
    protected function xmlSchemaValidate($xml_string, $xml_schema)
    {
        if (mb_detect_encoding($xml_string, 'UTF-8', true) === false) {
            $xml_string = utf8_encode($xml_string);
        }

        if (!is_file($xml_schema)) {
            throw new EExportException('ERROR: XML-Schema file [' . $xml_schema . '] not found.');
        }

        libxml_use_internal_errors(true);

        $xml = new DOMDocument();
        $xml->loadXML($xml_string);

        $xml->schemaValidate($xml_schema);

        $errors = $this->xmlSchemaValidateErrors();

        return $errors;
    }

    /**
     * xmlFileSchemaValidate
     *
     * @access  protected
     * @param   string  $xml_file
     * @param   string  $xml_schema
     * @return  string
     * @throws  EExportException
     */
    protected function xmlFileSchemaValidate($xml_file, $xml_schema)
    {
        if (!is_file($xml_file)) {
            throw new EExportException('XML file [' . $xml_file . '] not found.');
        }
        if (!is_file($xml_schema)) {
            throw new EExportException('XML-Schema file [' . $xml_schema . '] not found.');
        }

        libxml_use_internal_errors(true);

        $xml = new DOMDocument();
        $xml->load($xml_file);

        $xml->schemaValidate($xml_schema);

        $errors = $this->xmlSchemaValidateErrors();

        return $errors;
    }


    /**
     * xmlSchemaValidateErrors
     *
     * @access  protected
     * @return  array
     */
    protected function xmlSchemaValidateErrors()
    {
        $libxml_errors = libxml_get_errors();
        $errors = array();

        foreach($libxml_errors AS $error) {
            if (($error->code == 9) ||     // Input is not proper UTF-8, indicate encoding
                 ($error->code == 100) ||   // xmlns: URI krbw is not absolute
                 ($error->code == 1872)) { // The document has no document element
                continue;
            }

            $line = "";

            switch($error->level) {
                case LIBXML_ERR_WARNING :
                    $line .= 'Warning [' . $error->code . ']: ';
                    break;
                case LIBXML_ERR_ERROR :
                    $line .= 'Error [' . $error->code . ']: ';
                    break;
                case LIBXML_ERR_FATAL:
                    $line .= 'Fatal Error [' . $error->code . ']: ';
                    break;
            }

            $line .= trim($error->message) . '.';
            $errors[] = $line;
        }

        libxml_clear_errors();

        return $errors;
    }


    /**
     * parseXmlForErrors
     *
     * @access  protected
     * @param   string  $xml
     * @return  array|bool
     */
    protected function parseXmlForErrors($xml)
    {
        $errors = array();
        $next_start_pos = 0;
        $next_end_pos = 0;

        while(false !== ($pos = strpos($xml, 'Undefined index:', $next_start_pos))) {
            $next_start_pos = strpos($xml, ':', $pos);
            $next_start_pos++;
            $next_end_pos = strpos($xml, 'in', $next_start_pos);
            $field = trim(substr($xml, $next_start_pos, $next_end_pos - $next_start_pos));
            $errors[] = "Fatal Error: Feld [$field] wird erwartet, wurde aber nicht in der Datenstruktur gefunden.";
            $next_start_pos = $next_end_pos;
        }

        if (count($errors) > 0) {
            return $errors;
        }

        return false;
    }


    /**
     * getExportPath
     *
     * @access  protected
     * @param   string  $export_sub_dir
     * @param   string  $login_name
     * @return  string
     */
    public function getExportPath($export_sub_dir, $login_name)
    {
        $tmp = getUploadDir($this->m_smarty, 'tmp', false);
        $path = $tmp[ 'tmp' ] . $export_sub_dir . $login_name . '/';

        return $path;
    }


    /**
     * replaceAllXmlEntities
     *
     * @access  protected
     * @param   array $data
     * @return  array
     */
    protected function replaceAllXmlEntities($data)
    {
        foreach($data as $key => $child) {
            if (is_array($child)) {
                $data[ $key ] = $this->ReplaceAllXmlEntities($child);
            }
            else {
                $data[ $key ] = $this->ReplaceXmlEntities($child);
            }
        }

        return $data;
    }


    /**
     * replaceXmlEntities
     *
     * @access  protected
     * @param   string  $str
     * @return  string
     */
    protected function replaceXmlEntities($str)
    {
        return str_replace(
            array("&",     "<",    ">",    '"',      "'"     ),
            array("&amp;", "&lt;", "&gt;", "&quot;", "&apos;"),
            $str
        );
    }


    /**
     * getInternalSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getInternalSmarty()
    {
        return $this->m_internal_smarty;
    }


    /**
     * getXmlTemplateFileName
     *
     * @access  public
     * @return  string
     */
    public function getXmlTemplateFileName()
    {
        return $this->m_xml_template_file;
    }


    /**
     * getXmlSchemaFileName
     *
     * @access  public
     * @return  string
     */
    public function getXmlSchemaFileName()
    {
        return $this->m_xml_schema_file;
    }
}
