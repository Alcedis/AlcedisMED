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

require_once 'feature/krebsregister/class/register/messenger.php';
require_once 'feature/krebsregister/class/register/query/default.php';
require_once('feature/krebsregister/class/register/export/record.krexport.php');
require_once('feature/krebsregister/class/register/export/record.krexportcase.php');
require_once('feature/krebsregister/class/register/export/record.krexportsection.php');
require_once 'interface.php';

/**
 * Class registerStateAbstract
 */
abstract class registerStateAbstract implements registerStateInterface
{
    /**
     * _isCached
     *
     * @access  protected
     * @var     bool
     */
    protected $_isCached = false;


    /**
     * _exportRecord
     *
     * @access  protected
     * @var     RKrExport
     */
    protected $_exportRecord;


    /**
     * _map
     *
     * @access  protected
     * @var     array
     */
    protected $_map = array();


    /**
     * _additionalClassificationFields
     *
     * @access  protected
     * @var     array
     */
    protected $_additionalClassificationFields = array();


    /**
     * _messengerCollection
     *
     * @access  protected
     * @var     registerMessengerCollection
     */
    protected $_messengerCollection;


    /**
     * _patientCollection
     *
     * @access  protected
     * @var     registerPatientCollection
     */
    protected $_patientCollection;


    /**
     * _patientFilter
     * (include only filter patients if not null)
     *
     * @access  protected
     * @var     array
     */
    protected $_patientIdFilter;


    /**
     * _query
     *
     * @access  protected
     * @var     registerQueryInterface
     */
    protected $_query;


    /**
     * _config
     *
     * @access  protected
     * @var     array
     */
    protected $_config = array();


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;


    /**
     * _params
     *
     * @access  protected
     * @var     array
     */
    protected $_params = array();


    /**
     * _serializer
     *
     * @access  protected
     * @var     registerExportSerializer
     */
    protected $_serializer;


    /**
     * _settings
     *
     * @access  protected
     * @var     array
     */
    protected $_settings = array();


    /**
     * _messageCache
     *
     * @access  protected
     * @var     array
     */
    protected $_messageCache = array();


    /**
     * @param resource  $db
     * @param Smarty    $smarty
     * @param array     $params
     */
    public function __construct($db, $smarty, array $params = array())
    {
        $type = $this->getType();

        $this->_db = $db;
        $this->_smarty = $smarty;
        $this->_params = $params;
        $this->_exportRecord = $exportRecord = new RKrExport;

        $orgId = $params['org_id'];

        $isCached = $this->_isCached = $exportRecord->read($db, 'kr_' . $type, $orgId, $orgId, 'not_finished');

        // prepare exportRecord if export is not already in cache
        if ($isCached === false) {
            $this->_initializeExportRecord();
        }

        registerMap::setDb($db);

        $this->_initialize($type);
    }


    /**
     * getType
     *
     * @access  public
     * @return  string
     */
    public function getType()
    {
        $type = substr(get_class($this), -2);

        return strtolower($type);
    }


    /**
     * _initialize
     *
     * @access  protected
     * @param   string  $type
     * @return  void
     */
    protected function _initialize($type)
    {
        $serializer = new registerExportSerializer;

        $serializer->setType($type);
        $serializer->create('feature/krebsregister/export/', 'kr_' . $type, $this->getSmarty(), $this->getDb());

        $serializer->setData($this->getExportRecord());

        $this->_serializer = $serializer;
        $this->_messengerCollection = new registerMessengerCollection;
    }


    /**
     * _initializeExportRecord
     *
     * @access  protected
     * @return  void
     */
    protected function _initializeExportRecord()
    {
        $params = $this->getParams();
        $exportRecord = $this->getExportRecord();

        $userId       = $params['user_id'];
        $orgId        = $params['org_id'];
        $exportName   = 'kr_' . $this->getType();

        $exportRecord->setExportName($exportName);
        $exportRecord->setOrgId($orgId);
        $exportRecord->setExportUniqueId($orgId);
        $exportRecord->setExportNr(RKrExport::getNextExportNr($this->getDb(), $exportName, $orgId));
        $exportRecord->setMelderId($userId);
        $exportRecord->setCreateUserId($userId);
        $exportRecord->setParameters($params);
    }


    /**
     * _loadConfig
     *
     * @access  protected
     * @param   string  $resource
     * @param   string  $section
     * @param   string  $path
     * @return  registerStateAbstract
     */
    protected function _loadConfig($resource, $section = null, $path = null)
    {
        $smarty = $this->getSmarty();

        $confBackup = $smarty->get_config_vars();

        $smarty->clear_config();

        $path = $path === null ? '../configs/app/' : $path;

        $smarty->config_load("{$path}{$resource}.conf", $section);

        $this->_config[$resource] = $smarty->get_config_vars();
        $smarty->clear_config();

        $smarty->set_config($confBackup);

        return $this;
    }


    /**
     * getPatients
     *
     * @access  public
     * @param   bool $export
     * @return  registerPatientCollection
     */
    public function getPatients($export = false)
    {
        $patients = $this->_patientCollection;

        if ($patients === null) {
            $patients = $this->_patientCollection = $this->_getPatients($export);
        }

        return $patients;
    }


    /**
     * loadArchive
     *
     * @access  public
     * @param   int          $exportLogId
     * @param   array|string $patientIds
     * @return  registerStateInterface
     */
    public function loadArchive($exportLogId, $patientIds = null)
    {
        // if $patientIds is filled, convert to array
        if ($patientIds !== null) {
            if (is_array($patientIds) === false) {
                $patientIds = array($patientIds);
            }
        }

        $cases = array();

        if ($patientIds !== null) {
            $patientIds = implode(',', $patientIds);

            $caseLogIds = sql_query_array($this->getDb(), "
                SELECT
                    export_case_log_id
                FROM export_case_log
                WHERE
                    export_log_id = '{$exportLogId}' AND
                    patient_id IN ({$patientIds})
            ");

            foreach ($caseLogIds as $record) {
                $case = new RKrExportCase;

                $case->read($this->getDb(), $record['export_case_log_id'], true);

                $cases[] = $case;
            }
        } else {} // TODO load complete export

        $registerPatientCollection = new registerPatientCollection;

        $this->_loadFromCache($registerPatientCollection, $cases, true);

        $this->_patientCollection = $registerPatientCollection;

        return $this;
    }


    /**
     * prepare register for initialy export patient with id
     *
     * @access  public
     * @param   int $patientId
     * @return  registerStateInterface
     */
    public function prepareInitialExport($patientId)
    {
        $this->refreshCache();

        // set pre query patient filter
        $this->getQuery()->setWhere(array("p.patient_id = '{$patientId}'"), true);

        // get patients without history for cache building
        $this->_getPatients(false, false);

        return $this;
    }


    /**
     * _getPatients
     *
     * @access  protected
     * @param   bool $export
     * @param   bool $withHistory
     * @return  registerPatientCollection
     */
    protected function _getPatients($export = false, $withHistory = true)
    {
        $collection = new registerPatientCollection;

        // load from cache if exists
        if ($this->isCached() === true) {
            $this->_loadFromCache($collection);
        } else { // load data from system
            $cases  = $this->getQuery()->execute();
            $params = $this->getParams();

            // map patient cases
            foreach ($cases as $case) {
                $patientId       = $case['patient_id'];
                $registerPatient = $collection->getRegisterPatient($patientId);

                // if register patient with patient id doesn't exists, create new one
                if ($registerPatient === null) {
                    $registerPatient = registerPatient::create($params);

                    // addRegisterPatient need min one valid case
                    $registerPatient->addCase($case);

                    $collection->addRegisterPatient($registerPatient);
                } else {
                    $registerPatient->addCase($case);
                }
            }

            // find primary cases for patients
            $this->_detectPrimaryCases($collection);

            // set filter for patient (state specific is valid or not)
            $this->_filterPatients($collection);

            // build messages
            $this->_buildMessages($collection, $withHistory);

            // validate xml against collection
            $this->_validateXML($collection);

            // create cache after patients was created
            $this->_writeCache($collection);
        }

        if ($withHistory === true) {
            // get all exported records from history and show too (marked as grey)
            $this->_loadFromHistory($collection);
        }

        $patientFilter = $this->getPatientIdFilter();

        // if export is true, load patients from log and create collection
        if ($export === true && $patientFilter !== null) {
            // if is for export and filtered patient ids exists (checkbox in list checked)
            $collection->removeExceptOf($patientFilter);
        }

        return $collection;
    }


    /**
     * _detectPrimaryCases
     *
     * @access  protected
     * @param   registerPatientCollection $patientCollection
     * @return  void
     */
    abstract protected function _detectPrimaryCases(registerPatientCollection $patientCollection);


    /**
     * _loadFromCache
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @param   array $cases
     * @param   bool  $raw
     * @return  void
     */
    protected function _loadFromCache(registerPatientCollection $collection, $cases = null, $raw = false)
    {
        // load cases from export record
        if ($cases === null) {
            $exportRecord = $this->getExportRecord();
            $cases  = $exportRecord->getCases();
        }

        $params = $this->getParams();

        foreach ($cases as $case) {
            $registerPatient = $collection->getRegisterPatient($case->getPatientId());

            $message = registerPatientMessage::create()
                ->setExportCase($case)
                ->setParams($this->getParams())
                ->checkConfiguration()
            ;

            $exportSection = $message->getSection('export')->getData();

            if ($raw === false && $exportSection['loadHistory'] === true) {
                $message->loadHistory($this->getDb());

                $messageBuilder = $this->getMessageBuilder($message->getType());

                $message->setIgnoreOnDiff($messageBuilder->getIgnoreOnDiff());

                // must called after fields ignore from message are added
                $message->checkDifference();
            }

            if ($registerPatient === null) {
                $registerPatient = registerPatient::create()
                    ->setData($case->getFirstSection('patient')->getData())
                    ->setValid(true)
                    ->setParams($params)
                ;

                $registerPatient->addMessage($message);

                $collection->addRegisterPatient($registerPatient);
            } else {
                $registerPatient->addMessage($message);
            }
        }
    }


    /**
     * getMessageBuilder
     *
     * @access  public
     * @param   string $type
     * @return  registerStateMessageInterface
     */
    public function getMessageBuilder($type)
    {
        $messageCache = $this->_messageCache;

        // build new message cache
        if (isset($messageCache[$type]) === false) {
            $messageName = 'registerStateMessage' . ucfirst($type);
            $messageCache[$type] = new $messageName($this->getDb());

            $this->_messageCache = $messageCache;
        }

        return $messageCache[$type];
    }


    /**
     * write patient collection to cache
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @return  registerStateInterface
     */
    protected function _writeCache(registerPatientCollection $collection)
    {
        $db           = $this->getDb();
        $exportRecord = $this->getExportRecord();

        /* @var registerPatient $patient */
        foreach ($collection as $patient) {

            // only if patient has changed
            if ($patient->hasChanged() === true) {

                // only add case to export if message has is new or has difference
                foreach ($patient->getMessages() as $patientMessage) {
                    if ($patientMessage->hasHistory() === false || $patientMessage->hasDifference() == true) {
                        $patientMessage->buildHash();
                        $exportRecord->addCase($patientMessage->getExportCase());
                    }
                }
            }
        }

        // better performance for writing many data
        // must only called once each write
        RKrExportSection::clearQueryStack();

        $exportRecord->write($db, true);

        RKrExportSection::flushQueryStack($db);

        return $this;
    }


    /**
     * _loadFromHistory
     * (load export datasets which wouldn't used in current export but they should be visible
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @return  void
     */
    protected function _loadFromHistory(registerPatientCollection $collection)
    {
        $exportRecord = $this->getExportRecord();
        $patientIds   = $collection->getIds(true, true);
        $type         = $this->getType();

        $exportNr = $exportRecord->getExportNr();
        $orgId    = $exportRecord->getOrgId();

        $query = "
            SELECT
                ecl.patient_id,
                MAX(ecl.export_case_log_id) as export_case_log_id
            FROM export_log el
                INNER JOIN export_case_log ecl ON ecl.export_log_id = el.export_log_id AND ecl.patient_id NOT IN ({$patientIds})
            WHERE
                el.export_name = 'kr_{$type}' AND
                el.org_id = '{$orgId}' AND
                el.export_unique_id = '{$orgId}' AND
                el.export_nr < '$exportNr' AND
                el.finished = 1
            GROUP BY
                ecl.patient_id
        ";

        $historyCases = sql_query_array($this->getDb(), $query);

        $cases = array();

        foreach ($historyCases as $historyCase) {
            $case = new RKrExportCase;

            $case->read($this->getDb(), $historyCase['export_case_log_id'], true);

            $cases[] = $case;
        }

        // load from cache if cases exists
        if (count($cases) > 0) {
            $this->_loadFromCache($collection, $cases);
        }
    }


    /**
     * _filterPatients
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @return  void
     */
    abstract protected function _filterPatients(registerPatientCollection $collection);


    /**
     * _buildMessages
     *
     * @access  protected
     * @param   registerPatientCollection $collection
     * @param   bool $withHistory
     * @return  void
     */
    abstract protected function _buildMessages(registerPatientCollection $collection, $withHistory = true);


    /**
     * _validateXML
     *
     * @access  protected
     * @param   registerPatientCollection $patientCollection
     * @return  void
     */
    protected function _validateXML(registerPatientCollection $patientCollection)
    {
        $serializer = $this->getSerializer();

        $params = $this->getParams();

        // load messenger data for xml validation
        $messenger = $this->getMessenger()->load($this->getDb(), $params);

        $serializer->validate(array(
            'settings'  => $params,
            'patients'  => $patientCollection,
            'messenger' => $messenger
        ));
    }


    /**
     * write xml on export
     *
     * @access  public
     * @return  int
     */
    public function writeXml()
    {
        $params = $this->getParams();

        $patients  = $this->getPatients(true);
        $messenger = $this->getMessenger();

        $patientIds = array();

        /* check patients */

        /* @var registerPatient $patient */
        foreach ($patients as $patient) {
            $processPatient = true;
            $patientId      = $patient->getId();

            // if nothing in patient has changed, remove him from stack
            if ($patient->hasChanged() === false) {
                $processPatient = false;

                // remove patient from stack
                $patients->remove($patientId);
            }

            if ($processPatient === true) {
                foreach ($patient->getMessages() as $message) {
                    // don't write this message to xml
                    if ($message->isExportable() === false) {
                        continue;
                    }

                    // if a message has errors, don't export whole patient
                    if ($message->hasErrors() === true) {
                        $patients->remove($patientId);

                        // go to next patient
                        continue 2;
                    }
                }

                $patientIds[] = $patientId;
            }
        }

        /* now all patients are filtered, so collect relevant messenger */
        foreach ($patients as $patient) {
            foreach ($patient->getMessages() as $message) {
                $messageData = $message->getSection('message')->getData();

                $messenger->add($messageData['meldender_arzt'], $message);
            }
        }

        // load messenger data for xml validation
        $messenger = $this->getMessenger()->load($this->getDb(), $params);

        $historyId = $this->getSerializer()
            ->write(array(
                'settings'  => $params,
                'patients'  => $patients,
                'messenger' => $messenger
            ))
        ;

        // if history id exists, mark export record as finished and remove all cases from patients which are not exported
        if ($historyId !== null) {
            $this->getExportRecord()
                ->removeCasesWithoutPatientId($this->getDb(), $patientIds)
                ->markAsFinished($this->getDb())
            ;
        }

        return $historyId;
    }


    /**
     * getXml
     *
     * @access  public
     * @return  string
     */
    public function getXml()
    {
        $patients  = $this->getPatients();
        $params    = $this->getParams();
        $messenger = $this->getMessenger();

        /* @var registerPatient $patient */
        foreach ($patients as $patient) {
            foreach ($patient->getMessages() as $message) {
                $messageData = $message->getSection('message')->getData();

                $messenger->add($messageData['meldender_arzt'], $message);
            }
        }

        $messenger->load($this->getDb(), $params);

        $xml = $this->getSerializer()
            ->buildXml(array(
                'settings'  => $params,
                'patients'  => $patients,
                'messenger' => $messenger
            ))
        ;

        return $xml;
    }


    /**
     * setPatientIdFilter
     *
     * @access  public
     * @param   array $patientIds
     * @return  registerStateInterface
     */
    public function setPatientIdFilter(array $patientIds = array())
    {
        $this->_patientIdFilter = $patientIds;

        return $this;
    }


    /**
     * getPatientIdFilter
     *
     * @access  public
     * @param   bool $concat
     * @return  array|string
     */
    public function getPatientIdFilter($concat = false)
    {
        $filter = $this->_patientIdFilter;

        if ($concat === true) {
            $filter = count($filter) > 0 ? implode(',', $filter) : '0';
        }

        return $filter;
    }


    /**
     * resetPatients
     *
     * @access  public
     * @return  registerStateInterface
     */
    public function resetPatients()
    {
        $this->_patientCollection = null;

        return $this;
    }


    /**
     * getDb
     *
     * @access  public
     * @return  resource
     */
    public function getDb()
    {
        return $this->_db;
    }


    /**
     * getSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getSmarty()
    {
        return $this->_smarty;
    }


    /**
     * getQuery
     *
     * @access  public
     * @return  registerQueryInterface
     */
    public function getQuery()
    {
        $query = $this->_query;

        // initialize query
        if ($query === null) {
            $type = $this->getType();

            require_once 'feature/krebsregister/class/register/query/'. $type . '.php';

            $queryName = 'registerQuery' . ucfirst($type);

            $query = $this->_query = new $queryName($this->getDb(), $this->getSmarty(), $this->getParams());
        }

        return $query;
    }


    /**
     * getConfig
     *
     * @access  public
     * @param   string  $var
     * @param   string  $section
     * @return  array|string
     */
    public function getConfig($var = null, $section = 'default')
    {
        $config = null;

        if ($var !== null) {
            if (array_key_exists($var, $this->_config[$section]) === true) {
                $config = $this->_config[$section][$var];
            }
        } else {
            $config = $this->_config[$section];
        }

        return $config;
    }



    /**
     * getMap
     *
     * @access  public
     * @param   string  $name
     * @return  array
     */
    public function getMap($name)
    {
        return (array_key_exists($name, $this->_map) === true ? $this->_map[$name] : array());
    }


    /**
     * addToMap
     *
     * @access  public
     * @param   string  $name
     * @param   array   $values
     * @return  registerStateInterface
     */
    public function addToMap($name, array $values = array())
    {
        if (array_key_exists($name, $this->_map) === true) {
            $this->_map[$name] = array_merge(
                $this->_map[$name],
                $values
            );
        } else {
            $this->_map[$name] = $values;
        }

        return $this;
    }


    /**
     * isCached
     *
     * @access  public
     * @return  bool
     */
    public function isCached()
    {
        return $this->_isCached;
    }


    /**
     * getExportRecord
     *
     * @access  public
     * @return  RKrExport
     */
    public function getExportRecord()
    {
        return $this->_exportRecord;
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
     * getAdditionalClassificationFields
     *
     * @access  public
     * @return  array
     */
    public function getAdditionalClassificationFields()
    {
        return $this->_additionalClassificationFields;
    }


    /**
     * _addAdditionalItems
     * (will be extended from state)
     *
     * @access  public
     * @param   registerPatientCase $patientCase
     * @return  array
     */
    public function addAdditionalItems(registerPatientCase $patientCase)
    {
        return array();
    }


    /**
     * refreshCache
     *
     * @access  public
     * @return  void
     */
    public function refreshCache()
    {
        $exportRecord = $this->getExportRecord();

        // only delete current export if really exists
        if ($exportRecord->getDbId() !== 0) {
            $exportRecord->delete($this->getDb());
        }

        $this->_initializeExportRecord();

        $this->resetPatients();

        $this->_isCached = false;
    }


    /**
     * getSerializer
     *
     * @access  public
     * @return  registerExportSerializer
     */
    public function getSerializer()
    {
        return $this->_serializer;
    }


    /**
     * getMessenger
     *
     * @access  public
     * @return  registerMessengerCollection
     */
    public function getMessenger()
    {
        return $this->_messengerCollection;
    }


    /**
     * map
     *
     * @access  public
     * @param   string  $mapping
     * @param   string  $value
     * @param   bool $default
     * @return  mixed
     */
    public function map($mapping, $value, $default = false)
    {
        $map = $value;
        $mapValues = $this->_map[$mapping];

        if (array_key_exists($value, $mapValues) === true) {
            $map = $mapValues[$value];
        } else if ($default !== false) {
            $map = $default;
        }

        return $map;
    }
}
