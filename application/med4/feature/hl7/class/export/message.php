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

require_once 'oru.php';

class hl7ExportMessage extends hl7ExportOru
{
    /**
     * Message construct
     *
     */
    protected $_message = array(
        'a' => array(
            'MSH'       => 'MSH||^~\&|||||||||||',
            'EVN'       => 'EVN|1||||||',
            'PID'       => 'PID|1||||||||',
            'PV1'       => 'PV1|1|U||||||||||||||||||',
            'TXA'       => 'TXA|||application/pdf||||||||||||||DO|',
            'OBX_MDM'   => 'OBX|1|ED|^Document Content|1|||||||F'
        ),
        'b' => array(
            'MSH'       => 'MSH||^~\&|||||||||||',
            'EVN'       => 'EVN|1||||||',
            'PID'       => 'PID|1||||||||',
            'PV1'       => 'PV1|1|U||||||||||||||||||',
            'TXA'       => 'TXA|||application/pdf||||||||||||||DO|',
        ),
        'c' => array(
            'MSH'       => 'MSH||^~\&|||||||||||',
            'EVN'       => 'EVN|1||||||',
            'PID'       => 'PID|1||||||||',
            'PV1'       => 'PV1|1|U||||||||||||||||||',
            'OBR'       => 'OBR|1||||||',
            'OBX_ORU'   => 'OBX||FT||||||||||||'
        )
    );

    /**
     * Segment specific methods
     *
     * @var type
     */
    protected $_segments = array(
        'MSH' => array(
            3  => 'exportFromApp',
            4  => 'exportFromAppFacility',
            5  => 'exportToApp',
            6  => 'exportToAppFacility',
            7  => 'exportTime',
            9  => 'exportType',
            10 => 'exportUniqueMessageId',
            12 => 'exportVersion'
        ),
        'EVN' => array(
            2 => 'exportTime',
            6 => 'exportTime'
        ),
        'PID' => array(
            2 => 'patientNumber',
            3 => 'patientNumber',
            4 => 'patientNumber',
            5 => 'patientName',
            8 => 'patientSex'
        ),
        'PV1' => array(
            19 => 'patientVisitNumber'
        ),
        'TXA' => array(
            2  => 'documentType',
            6  => 'documentCreateTime',
            7  => 'documentCreateTime',
            8  => 'documentUpdateTime',
            9  => 'documentCreator',
            12 => 'documentUniqueNumber',
            16 => 'documentUniqueFileName',
        ),
        'OBX_MDM' => array(
            5 => 'documentBase64'
        ),
        'OBR' => array(
            3 => 'documentUniqueNumber',
            7 => 'observationEndDate'
        ),
        'OBX_ORU' => 'buildOBXMessage'
    );

    /**
     * reset message buffer
     *
     * @return \hl7ExportMessage
     */
    protected function _reset() {
        $this->_buffer['message'] = array(
            'raw'   => array(),
            'final' => ''
        );

        unset(
            $this->_buffer['vars']['exportUniqueMessageId'],
            $this->_buffer['vars']['exportType']
        );

        return $this;
    }


    protected function _buildMessage()
    {
        $this
            ->_reset()
            ->_loadDocumentData()
            ->_loadPatientData()
        ;

        $messageConstruct = $this->_getMessageBody();

        if ($this->_patientData !== null) {
            foreach ($messageConstruct as $segment => $segmentContent) {
                $this->_buffer['message']['raw'][$segment] = $this->_buildSegment($segment, $segmentContent);
            }

            $this->_buffer['message']['final'] = implode($this->_lineBreak, $this->_buffer['message']['raw']);
        }

        return $this;
    }

    /**
     * returns profile message body
     *
     * @return type
     */
    private function _getMessageBody()
    {
        $profile = $this->getParam('activeProfile');

        $body = array();

        if (array_key_exists($profile, $this->_message) === true) {
            $body = $this->_message[$profile];
        }

        return $body;
    }



    protected function _getMessage()
    {
        if (array_key_exists('message', $this->_buffer) === false) {
            $this->_buildMessage();
        }

        return $this->_buffer['message']['final'];
    }


    private function _buildSegment($segment, $segmentContent)
    {
        if (array_key_exists($segment, $this->_segments) === true) {
            if (is_array($this->_segments[$segment]) === true) {
                $content = explode('|', $segmentContent);

                foreach ($content as $i => $value) {
                    if (array_key_exists($i, $this->_segments[$segment]) === true) {
                        $m = $this->_segments[$segment][$i];

                        // if var already exists in buffer
                        if (array_key_exists($m, $this->_buffer['vars']) === true) {
                            $content[$i] = $this->_buffer['vars'][$m];
                        } else if (method_exists($this, "_{$m}") === true) {
                            $content[$i] = $this->_buffer['vars'][$m] = $this->_escape($this->{"_{$m}"}());
                        }
                    }
                }

                //MSH field seperator remove
                if ($segment === 'MSH') {
                    unset($content[1]);
                }

                $segmentContent = implode('|', $content);
            } else {
                $method = $this->_segments[$segment];

                if (method_exists($this, "_{$method}") === true) {
                    $segmentContent = $this->_escape($this->{"_{$method}"}($segmentContent));
                }
            }
        }

        return $segmentContent;
    }


    private function _escape($value)
    {
        return $value;
    }

}

?>
