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

require_once 'patient/message.php';
require_once 'patient/case.php';

/**
 * Class registerPatient
 */
class registerPatient
{
    /**
     * _data
     *
     * @access  protected
     * @var     array
     */
    protected $_data = array(
        'org_id',
        'patient_id',
        'patient_nr',
        'krankenversichertennr',
        'familienangehoerigennr',
        'krankenkassennr',
        'nachname',
        'titel',
        'namenszusatz',
        'vorname',
        'geburtsdatum',
        'geburtsname',
        'geschlecht',
        'strasse',
        'hausnummer',
        'land',
        'plz',
        'ort'
    );


    /**
     * _cases
     *
     * @access protected
     * @var    registerPatientCase[]
     */
    protected $_cases = array();


    /**
     * _messages
     *
     * @access  protected
     * @var     registerPatientMessage[]
     */
    protected $_messages = array();


    /**
     * _params
     *
     * @access  protected
     * @var     array
     */
    protected $_params = array();


    /**
     * _valid
     *
     * @access  protected
     * @var     bool
     */
    protected $_valid = false;


    /**
     * create registerPatient
     *
     * @static
     * @access  public
     * @param   array   $params
     * @return  registerPatient
     */
    public static function create(array $params = array())
    {
        return new self($params);
    }


    /**
     * @param array $params
     */
    public function __construct(array $params = array())
    {
        $this->setParams($params);
    }


    /**
     * _initialize patient
     *
     * @access  protected
     * @param   registerPatientCase $case
     * @return  void
     */
    protected function _initialize(registerPatientCase $case)
    {
        $patientFields = array();

        // doesn't implemented key check for faster performance
        foreach (array_flip($this->_data) as $field => $dummy) {
            $patientFields[$field] = $case->getData($field);
        }

        $this->_data = $patientFields;
    }


    /**
     * addCase
     *
     * @access  public
     * @param   array|registerPatientCase $case
     * @return  registerPatient
     */
    public function addCase($case)
    {
        // transform array case to registerPatientCase
        if (is_array($case) === true) {
            $case = registerPatientCase::create($case);
        }

        /* @var registerPatientCase $case */

        // initialize this patient if first case comes in
        if ($this->hasCases() === false) {
            $this->_initialize($case);
        }

        $this->_cases[$case->getIdent()] = $case;

        return $this;
    }


    /**
     * getCases
     *
     * @access  public
     * @return  registerPatientCase[]
     */
    public function getCases()
    {
        return $this->_cases;
    }


    /**
     * getPrimaryCases
     *
     * @access  public
     * @param   bool $onlyValid // means only valid for the selected state
     * @return  registerPatientCase[]
     */
    public function getPrimaryCases($onlyValid = true)
    {
        $cases = array();

        foreach ($this->_cases as $ident => $case) {

            // take only primary cases
            if ($case->isPrimaryCase() === true) {

                // if only valid primary cases are wanted but this one is not
                if ($onlyValid === true && $case->isValid() === false) {
                    continue;
                }

                $cases[$ident] = $case;
            }
        }

        return $cases;
    }


    /**
     * hasCases
     *
     * @access  public
     * @return  bool
     */
    public function hasCases()
    {
        return (count($this->_cases) > 0);
    }


    /**
     * hasMessages
     *
     * @access  public
     * @return  bool
     */
    public function hasMessages()
    {
        return (count($this->_messages) > 0);
    }


    /**
     * getId
     *
     * @access  public
     * @return  int
     */
    public function getId()
    {
        return $this->_data['patient_id'];
    }


    /**
     * getData
     *
     * @access  public
     * @param   string $field
     * @return  array
     */
    public function getData($field = null)
    {
        $data = null;

        if ($field !== null) {
            if (array_key_exists($field, $this->_data) === true) {
                $data = $this->_data[$field];
            }
        } else {
            $data = $this->_data;
        }

        return $data;
    }


    /**
     * setData
     *
     * @access  public
     * @param   array $data
     * @return  registerPatient
     */
    public function setData(array $data)
    {
        $this->_data = $data;

        return $this;
    }


    /**
     * addData
     *
     * @access  public
     * @param   string  $name
     * @param   mixed   $value
     * @return  registerPatient
     */
    public function addData($name, $value)
    {
        $this->_data[$name] = $value;

        return $this;
    }


    /**
     * isValid
     *
     * @access  public
     * @return  bool
     */
    public function isValid()
    {
        return $this->_valid;
    }


    /**
     * patient has min one valid case for export
     *
     * @access  public
     * @param   bool $bool
     * @return  registerPatient
     */
    public function setValid($bool = true)
    {
        $this->_valid = $bool;

        return $this;
    }


    /**
     * setParams
     *
     * @access  public
     * @param   array $params
     * @return  registerPatient
     */
    public function setParams(array $params = array())
    {
        $this->_params = $params;

        return $this;
    }


    /**
     * getParams
     *
     * @access  public
     * @return  array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * getParam
     *
     * @access  public
     * @param   string  $name
     * @return  string
     */
    public function getParam($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }


    /**
     * getMessages
     *
     * @access  public
     * @return  registerPatientMessage[]
     */
    public function getMessages()
    {
        return $this->_messages;
    }


    /**
     * getMessageIdents
     *
     * @access  public
     * @return  array
     */
    public function getMessageIdents()
    {
        return array_keys($this->_messages);
    }


    /**
     * removeMessage
     *
     * @access  public
     * @param   string $ident
     * @return  registerPatient
     */
    public function removeMessage($ident)
    {
        unset($this->_messages[$ident]);

        return $this;
    }


    /**
     * getMessageCount
     *
     * @access  public
     * @return  int
     */
    public function getMessageCount()
    {
        return count($this->_messages);
    }


    /**
     * get message with ident from patient
     *
     * @access  public
     * @param   string $ident
     * @return  registerPatientMessage
     */
    public function getMessage($ident)
    {
        $message = null;

        if (array_key_exists($ident, $this->_messages) === true) {
            $message = $this->_messages[$ident];
        }

        return $message;
    }


    /**
     * add message to patient
     *
     * @access  public
     * @param   registerPatientMessage $message
     * @return  registerPatient
     */
    public function addMessage(registerPatientMessage $message)
    {
        $this->_messages[$message->getIdent()] = $message;

        return $this;
    }


    /**
     * detect if patient has changed since last export
     *
     * @access  public
     * @return  bool
     */
    public function hasChanged()
    {
        $changed = false;

        foreach ($this->getMessages() as $message) {
            // don't check non exportable or validatable messages
            if ($message->isExportable() === false || $message->isValidatable() === false) {
                continue;
            }

            // if init (hasHistory === false) OR message is difference from last time
            if ($message->hasHistory() === false || $message->hasDifference() === true) {
                $changed = true;
                break;
            }
        }

        return $changed;
    }
}
