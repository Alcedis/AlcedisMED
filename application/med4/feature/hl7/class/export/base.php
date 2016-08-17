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

class hl7ExportBase
{
    /**
     * Line break character
     */
    protected $_lineBreak = null;

    protected $_params = array();

    protected $_db = null;

    protected $_smarty = null;

    protected $_patientData  = null;

    protected $_documentData = null;

    /**
     * skips profile export
     *
     * @var type
     */
    protected $_skipProfile = array();

    protected $_documentTypes = array(
        'kp' => array(
            'table' => 'konferenz_patient',
            'type'  => 'conferenceProtocoll'
        ),
        'br' => array(
            'table' => 'brief',
            'type'  => 'medicalReport'
        )
    );

    private $_profileMap = array(
        'a' => 0,
        'b' => 1,
        'c' => 2
    );

    protected $_profileMapping = array(
       'a' => 'mdm',
       'b' => 'mdm',
       'c' => 'oru'
    );

    private $_profileEvents = array(
        'a' => array(
            'create' => '02',
            'update' => '10',
            'delete' => '11'
        ),
        'b' => array(
            'create' => '01',
            'update' => '09',
            'delete' => '11'
        )
    );


    /**
     * message buffer
     * @var type
     */
    protected $_buffer = array(
        'message' => array(
            'raw'   => array(),
            'final' => ''
        ),
        'vars'    => array()
    );

    public function __construct($db, $smarty)
    {
        $this->_lineBreak = chr(13);

        $this->setParam('therapy', 0);

        $this->_db = $db;
        $this->_smarty = $smarty;
    }

    protected function _exportFromApp()
    {
        return appSettings::get('software_title');
    }

    protected function _exportFromAppFacility()
    {
        return appSettings::get('software_title') . appSettings::get('software_version');
    }

    protected function _exportToApp()
    {
        return $this->getParam('receiving_application');
    }

    protected function _exportToAppFacility()
    {
        return $this->getParam('receiving_facility');
    }

    protected function _exportTime()
    {
        return date('YmdHis');
    }

    protected function _exportType()
    {
        $event   = $this->getParam('event');
        $profile = $this->getParam('activeProfile');

        $type = null;

        if (array_key_exists($profile, $this->_profileEvents) === true) {
            $profileEvent = $this->_profileEvents[$profile][$event];

            $type = concat(array('MDM', "T{$profileEvent}", "MDM_T{$profileEvent}"), '^');
        } elseif ($profile === 'c') {
            $type = 'ORU^R01';
        }

        return $type;
    }

    protected function _messageUniqueMessageId()
    {
        if ($this->getParam('uniqueMessageId') === null) {
            $this->_exportUniqueMessageId();
        }

        return '^' . $this->getParam('uniqueMessageId');
    }

    protected function _exportUniqueMessageId()
    {
        $type = '0' . ($this->getParam('type') == 'kp' ? '0' : '1');
        $id   = $this->getParam('id');

        $activeProfile = $this->getParam('activeProfile');

        $uniqueId = concat(array(date('YmdHis'), $type, $id, $this->_profileMap[$activeProfile]), '');

        $this->setParam('uniqueMessageId', $uniqueId);

        return $this->getParam('uniqueMessageId');
    }

    protected function _exportVersion()
    {
        return 2.5;
    }

    protected function _patientNumber()
    {
        return $this->_getValue('patient_nr', 'patient');
    }

    protected function _patientName()
    {
        return concat(array($this->_getValue('nachname', 'patient'), $this->_getValue('vorname', 'patient')), '^');
    }

    protected function _patientSex()
    {
        $sex = $this->_getValue('geschlecht', 'patient');

        return ($sex == 'w'
            ? 'F'
            : ($sex == 'm'
                ? 'M'
                : 'U'
        ));
    }

    protected function _patientVisitNumber()
    {
        $visitNumber = null;

        if ($this->_patientData !== null) {
            $patientId = $this->_patientData['patient_id'];

            $date = date('Y-m-d' ,strtotime($this->_documentData['datenstand_datum']));

            //Erstmal nur der neuste, später dann Erkrankugnsabhängig
            $query = "
                SELECT
                    aufnahmenr,
                    aufnahmedatum
                FROM aufenthalt
                WHERE
                    patient_id = '{$patientId}' AND
                    aufnahmenr IS NOT NULL AND
                    aufnahmedatum IS NOT NULL
                HAVING
                    aufnahmedatum <= '{$date}'
                ORDER BY
                    aufnahmedatum DESC
            ";

            $result = sql_query_array($this->_db, $query);

            if (count($result) > 0) {
                $newest = reset($result);
                $visitNumber = $newest['aufnahmenr'];
            }
        }

        return $visitNumber;
    }

    protected function _documentType()
    {
        $type = $this->getParam('type');

        $customDocumentTypes = $this->getParam('documentType');

        if ($customDocumentTypes !== null && array_key_exists($type, $customDocumentTypes) === true) {
            $documentType = $customDocumentTypes[$type]['type'];

            switch (true) {
                case (array_key_exists('addDisease', $customDocumentTypes[$type]) === true):
                    $where =  "klasse = 'erkrankung' AND code = '{$this->_documentData['erkrankung_code']}'";

                    $documentType .= ' ' . dlookup($this->_db, 'l_basic', 'bez', $where);
                break;
            }
        } else {
            $documentType = $this->_documentTypes[$type]['type'];
        }

        return $documentType;
    }

    protected function _documentCreateTime()
    {
        return date( "YmdHi", strtotime($this->_getValue('createtime')));
    }

    protected function _documentUpdateTime()
    {
        return ($this->getParam('event') !== 'create' ? date('YmdHi') : null);
    }

    protected function _documentCreator()
    {
        $type = $this->getParam('type');

        $creator = null;

        if ($type !== null && array_key_exists($type, $this->_documentTypes) === true) {
            $table  = $this->_documentTypes[$type]['table'];
            $id     = $this->getParam('id');

            $userId = $this->_getValue('createuser');

            if (strlen($userId) > 0) {
                $dataset = sql_query_array($this->_db, "SELECT * FROM user WHERE user_id = '{$userId}'");

                if (count($dataset) == 1) {
                    $dataset = reset($dataset);

                    $creator = concat(array(
                        $dataset['user_id'],
                        $dataset['nachname'],
                        $dataset['vorname']
                    ), '^');
                }
            }
        }

        return $creator;
    }


    /**
     *
     * @return type
     */
    protected function _documentUniqueNumber()
    {
        $type       = md5($this->getParam('type'));
        $uniqueId   = md5($this->getParam('id'));

        return concat(array($type, $uniqueId), '');
    }

    /**
     *
     */
    protected function _documentUniqueFileName()
    {
        $uniqueId = uniqid();

        switch ($this->getParam('type')) {
            case 'kp':

                $type = 'conference_protocoll_';

                break;

            case 'br':

                $type = 'medical_report_';

                break;

            default:

                $type = 'document_';

                break;
        }

        $time       = date('Y_m_d__H_i___');
        $fileName   = concat(array($type, $time, $uniqueId, '.pdf'), '');

        do {
            $uniqueId = uniqid();

            $fileName = concat(array($type, $time, $uniqueId, '.pdf'), '');

        } while ($this->_checkDocumentFileName($fileName) === true);

        $this->setParam('documentFileName', $fileName);

        return $this->getParam('documentFileName');
    }


    /**
     * returns exportPath
     *
     * @access
     * @param $type
     * @return string
     */
    protected function _getExportPath($type)
    {
        $path  = $this->getParam('exportPath');
        $path .= (substr($path, '-1') !== '/' ? '/' : null) . "{$type}/";

        if (is_dir($path) === false) {
            mkdir($path, 0770, true);
        }

        return $path;
    }


    /**
     * checks if document alread exists in cache
     *
     * @access
     * @param $filename
     * @return bool
     */
    protected function _checkDocumentFileName($filename)
    {
        return file_exists($this->_getExportPath('doc') . $filename);
    }


    /**
     * base64 encode
     *
     */
    protected function _documentBase64()
    {
        $file = $this->getParam('file');

        return '^application/pdf^^Base64^' . base64_encode(file_get_contents($file));
    }

    protected function _observationEndDate()
    {
        //TODO
        //wenn protocoll abgeschlossen wurde


        return null;
    }


    /**
     * loads documentDataset
     *
     * @return \hl7ExportBase
     */
    protected function _loadDocumentData()
    {
        $type = $this->getParam('type');

        if ($this->_documentData === null && $type !== null && array_key_exists($type, $this->_documentTypes) === true) {
            $table  = $this->_documentTypes[$type]['table'];
            $this->_documentData = reset(sql_query_array($this->_db,
                "SELECT
                    t.*,
                    e.erkrankung as erkrankung_code
                FROM {$table} t
                    LEFT JOIN erkrankung e ON t.erkrankung_id = e.erkrankung_id
                WHERE
                    t.{$table}_id = '{$this->getParam('id')}'"
            ));

            $this->setParam('messengerId', ($this->_getValue('updateuser') === null ? $this->_getValue('createuser') : $this->_getValue('updateuser')));
        }

        return $this;
    }


    /**
     * loads patientDataset
     *
     * @return \hl7ExportBase
     */
    protected function _loadPatientData()
    {
        if ($this->_documentData !== null && $this->_patientData === null) {
            $this->_patientData = reset(sql_query_array($this->_db, "SELECT * FROM patient WHERE patient_id = '{$this->_documentData['patient_id']}'"));

            $this->setParam('orgId', $this->_getValue('org_id', 'patient'));
        }

        return $this;
    }

    private function _getValue($name, $dataType = 'document')
    {
        $dataType = "_{$dataType}Data";

        return (is_array($this->{$dataType}) === true && array_key_exists($name, $this->{$dataType}) === true ? $this->{$dataType}[$name] : null);
    }

    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;

        return $this;
    }

    /**
     *
     * @param type $profile
     * @return \hl7ExportBase
     */
    public function setProfile($profile)
    {
        $profile = is_array($profile) === false ? array($profile) : $profile;

        $this->setParam('profile', $profile);

        return $this;
    }

    public function getParam($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }
}

?>
