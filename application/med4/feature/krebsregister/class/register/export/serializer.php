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

require_once( 'feature/export/base/class.exportxmlserialiser.php' );
require_once( 'feature/export/history/class.historymanager.php' );
require_once( 'feature/export/history/class.history.php' );

define('XML_PARSE_BIG_LINES', 4194304);

/**
 * Class registerExportSerializer
 */
class registerExportSerializer extends CExportXmlSerialiser
{
    /**
     * m_xml_template_file
     *
     * @access  protected
     * @var     string
     */
    protected $m_xml_template_file = "kr_1_0_xml.tpl";


    /**
     * m_xml_schema_file
     *
     * @access  protected
     * @var     string
     */
    protected $m_xml_schema_file = "kr_1_0.xsd";


    /**
     * _type
     *
     * @access  protected
     * @var     string
     */
    protected $_type;


    /**
     * create CExportXmlSerializer
     *
     * @access  public
     * @param   string   $absolute_path
     * @param   string   $export_name
     * @param   Smarty   $smarty
     * @param   resource $db
     * @param   string   $error_function
     * @return  void
     */
    public function create($absolute_path, $export_name, $smarty, $db, $error_function = '')
    {
        parent::create($absolute_path, $export_name, $smarty, $db);

        $this->getInternalSmarty()->template_dir = $absolute_path;
    }


    /**
     * validateCollection
     *
     * @access  public
     * @param   array $parameters
     * @return  void
     * @throws  EKrExportException
     */
    public function validate($parameters)
    {
        $smarty = $this->getInternalSmarty();

        $settings = $parameters['settings'];

        /* @var registerPatientCollection $patients */
        $patients  = $parameters['patients'];

        /* @var registerMessengerCollection $messenger */
        $messenger = $parameters['messenger'];

        $schema = $this->getAbsolutePath() . $this->getXmlSchemaFileName();

        libxml_use_internal_errors(true);

        // check if schema file exists
        if (!is_file($schema)) {
            throw new EKrExportException('ERROR: XML-Schema file [' . $schema . '] not found.');
        }

        $templateFileName = $this->getXmlTemplateFileName();

        $messengerArray = $messenger->toArray();

        foreach ($patients->toArray(true, true, true) as $patient) {
            $messengerErrors = array();

            $data = array(
                'absender' => $settings,
                'patients' => [$patient],
                'melder'   => $messengerArray
            );

            // don't replace xml entities here, this will be done from xmlTag smarty plugin
            $smarty->assign('data', $data);

            $xml = utf8_encode($smarty->fetch($templateFileName));

            $errors = $this->_xmlSchemaValidate($xml, $schema);

            foreach ($errors as $error) {
                // this is the main path to error position
                $rootPath = $error['section']['rootPath'];

                // only process if parent nodeName is patient
                if ($rootPath['nodeName'] === 'patient') {
                    // find patient
                    $patient = $patients->getRegisterPatient($rootPath['attributes']['patient_id']);
                    $path    = $rootPath['child'];

                    // add error to all patient sections
                    if ($path['nodeName'] === 'patienten_stammdaten') {
                        foreach ($patient->getMessages() as $patientMessage) {
                            $patientSection = $patientMessage->getSection('patient');

                            $patientSection->addError($error);
                            $patientSection->setValid(self::buildValid($error['level'], $patientSection->getValid()));
                        }
                    } else {
                        // message section
                        $messageChild = $path['child'];
                        $messageIdent = substr($messageChild['attributes']['meldung_id'], 4);

                        $message = $patient->getMessage($messageIdent);
                        $section = $messageChild['child'];

                        // this is definitely a section
                        if (isset($section['child']) === true) {
                            $messageSection = $message->getSection($section['nodeName']);

                            $messageSection->addError($error);

                            $messageSection->setValid(self::buildValid($error['level'], $messageSection->getValid()));
                        } else { // this is directly under message
                            $messageSection = $message->getSection('message');

                            $messageSection->addError($error);
                            $messageSection->setValid(self::buildValid($error['level'], $messageSection->getValid()));
                        }
                    }
                } elseif ($rootPath['nodeName'] === 'melder') {
                    $error['messengerId'] = $rootPath['child']['attributes']['melder_id'];
                    $error['message'] .= ' (' . $error['section']['value'] . ')';

                    $messengerErrors[] = $error;
                }
            }

            // iterate over each error, find relevant patient
            foreach ($messengerErrors as $messengerError) {

                // mark all related messenger message with error
                foreach ($messenger->getMessagesForMessenger($messengerError['messengerId']) as $message) {
                    $messageSection = $message->getSection('message');

                    $messageSection->addError($messengerError);
                    $messageSection->setValid(self::buildValid($error['level'], $messageSection->getValid()));
                }
            }
        }
    }


    /**
     * write xml and returns history id for download
     *
     * @access  public
     * @param   array $parameters
     * @return  int
     */
    public function write($parameters)
    {
        $smarty = $this->getInternalSmarty();

        $settings = $parameters['settings'];

        $xml = $this->buildXml($parameters);

        $xmlFile = parent::write($settings);

        $smarty->assign('zip_url', $xmlFile);

        // write file
        file_put_contents($xmlFile, $xml);

        // create history
        $historyManager = CHistoryManager::getInstance();
        $historyManager->initialise($this->getDB(), $this->getSmarty());

        $history = $historyManager->createHistory();
        $history->setExportLogId($this->m_export_record->GetDbId());
        $history->setExportName($this->m_export_record->GetExportName());
        $history->setOrgId($settings['org_id']);
        $history->setUserId($settings['user_id']);
        $history->setDate(date('Ymd', time()));

        $history->setFiles(array($xmlFile));

        $historyManager->insertHistory($history);

        return $history->getExportHistoryId();
    }


    /**
     * buildXml
     *
     * @access  public
     * @param   array $parameters
     * @return  string
     */
    public function buildXml(array $parameters)
    {
        $smarty = $this->getInternalSmarty();

        $settings = $parameters['settings'];

        /* @var registerPatientCollection $patients */
        $patients = $parameters['patients'];

        /* @var registerMessengerCollection $messenger */
        $messenger = $parameters['messenger'];

        $data = array(
            'absender' => $settings,
            'patients' => $patients->toArray(true),
            'melder'   => $messenger->toArray()
        );

        $smarty->assign('data', $data);

        return utf8_encode($smarty->fetch($this->getXmlTemplateFileName()));
    }


    /**
     * _buildValid
     *
     * @static
     * @access  public
     * @param   int $error
     * @param   int $current
     * @return  int
     */
    public static function buildValid($error, $current)
    {
        $valid = $current;

        if ($current != 0) {
            if (str_contains($current, $error) === false) {
                $values = str_split($current);

                $values[] = $error;

                rsort($values);

                $valid = implode('', $values);
            }
        } else {
            $valid = $error;
        }

        return $valid;
    }


    /**
     * _findErrorSection
     * (recursive)
     *
     * @access  protected
     * @param   DOMNode $node
     * @param   int     $line
     * @return  array
     */
    protected function _findErrorSection($node, $line)
    {
        $section = null;

        // only want domElement in line
        if ($node instanceof DOMElement) {
            // if node element is in line
            if ($node->getLineNo() === $line) {
                return array(
                    'nodeName' => $node->nodeName,
                    'value'    => $node->nodeValue,
                    'rootPath' => $this->_buildRootPath($node)
                );
            }
        }

        if ($node->childNodes) {
            foreach ($node->childNodes as $child) {
                if ($child instanceof DOMElement) {
                    $section = $this->_findErrorSection($child, $line);

                    if ($section !== null) {
                        return $section;
                    }
                }
            }
        }

        return $section;
    }


    /**
     * _findErrorSection
     * (recursive)
     *
     * @access  protected
     * @param   DOMNode $node
     * @return  array
     */
    protected function _mapNodeLines($node, &$map)
    {
        // only want domElement in line
        if ($node instanceof DOMElement) {
            // if node element is in line
            $map[$node->getLineNo()] = array(
                'nodeName' => $node->nodeName,
                'value'    => $node->nodeValue,
                'rootPath' => $this->_buildRootPath($node)
            );
        }

        if ($node->childNodes) {
            foreach ($node->childNodes as $child) {
                if ($child instanceof DOMElement) {
                    $this->_mapNodeLines($child, $map);
                }
            }
        }
    }


    /**
     * _buildRootPath
     *
     * @access  protected
     * @param   DOMElement $node
     * @param   array      $steps
     * @return  array
     */
    protected function _buildRootPath($node, $steps = null)
    {
        $nodeName = strtolower($node->nodeName);

        /* @var DOMElement $node */
        if ($nodeName === 'patient') {
            $patientNode = $node->getElementsByTagName('Patienten_Stammdaten')->item(0);

            return array(
                'nodeName'   => $nodeName,
                'attributes' => $this->_getNodeAttributes($patientNode),
                'child'      => $steps
            );
        } else if ($nodeName === 'menge_melder') {
            $attributes = array();

            $messenger = $node->getElementsByTagName('Melder');

            if ($messenger->length > 0) {
                $attributes = $this->_getNodeAttributes($messenger->item(0));
            }

            return array(
                'nodeName'   => 'melder',
                'attributes' => $attributes,
                'child'      => $steps
            );
        } else {
            $nextStep = array(
                'nodeName'   => $nodeName,
                'attributes' => $this->_getNodeAttributes($node)
            );

            if ($steps !== null) {
                $nextStep['child'] = $steps;
            }

            if (($node->parentNode instanceof DOMDocument) === false) {
                return $this->_buildRootPath($node->parentNode, $nextStep);
            } else {
                return $nextStep;
            }
        }
    }


    /**
     * _getNodeAttributes
     *
     * @access  protected
     * @param   DOMNode $node
     * @return  array
     */
    protected function _getNodeAttributes($node)
    {
        $attributes = array();

        foreach ($node->attributes as $name => $attrNode) {
            $attributes[strtolower($name)] = $attrNode->value;
        }

        return $attributes;
    }


    /**
     * _findParentSection
     * (recursive)
     *
     * @access  protected
     * @param   DOMElement $node
     * @return  array
     */
    protected function _findParentSection($node)
    {
        if (in_array($node->nodeName, array_keys($this->_nodeSections)) === true) {
            return $this->_getNodeAttributes($node);
        } else {
            return $this->_findParentSection($node->parentNode);
        }
    }


    /**
     * _xmlSchemaValidate
     *
     * @access  protected
     * @param   string$xml_string
     * @param   string $xml_schema
     * @return  array
     * @throws  EKrExportException
     * @throws  Exception
     */
    protected function _xmlSchemaValidate($xml_string, $xml_schema)
    {
        if (mb_detect_encoding($xml_string, 'UTF-8', true) === false) {
            $xml_string = utf8_encode($xml_string);
        }

        static $xml;

        if ($xml === null) {
            $xml = new DOMDocument();
        }

        $result = $xml->loadXML($xml_string, XML_PARSE_BIG_LINES);

        if (false === $result) {
            throw new Exception('XML-File is broken');
        }

        $xml->schemaValidate($xml_schema);

        $errors = $this->_xmlSchemaValidateErrors();

        $errorMap = array();

        $this->_mapNodeLines($xml->firstChild, $errorMap);

        foreach ($errors as $i => $error) {
            $errors[$i]['section'] = $errorMap[$error['line']];
        }

        return $errors;
    }


    /**
     * xmlSchemaValidateErrors
     *
     * @access  protected
     * @return  array
     */
    protected function _xmlSchemaValidateErrors()
    {
        $libxml_errors = libxml_get_errors();
        $errors = array();

        foreach ($libxml_errors AS $error) {
            if (($error->code == 9) ||     // Input is not proper UTF-8, indicate encoding
                ($error->code == 100) ||   // xmlns: URI krbw is not absolute
                ($error->code == 1872)) { // The document has no document element
                continue;
            }

            $errors[] = array(
                'level' => $error->level,
                'code'  => $error->code,
                'message' => trim($error->message),
                'line' => $error->line
            );
        }

        libxml_clear_errors();

        return $errors;
    }


    /**
     * GetFilename
     *
     * @access  public
     * @return  string
     */
    public function getFilename()
    {
        return 'kr_' . $this->getType() . '_export_' . date( 'YmdHis' ) . '.xml';
    }


    /**
     * setType
     *
     * @access  public
     * @param   string  $type
     * @return  registerExportSerializer
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }


    /**
     * getType
     *
     * @access  public
     * @return  string
     */
    public function getType()
    {
        return $this->_type;
    }
}
