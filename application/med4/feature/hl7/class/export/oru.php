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

require_once 'base.php';

class hl7ExportOru extends hl7ExportBase
{
    private $_paths = array(
        'upload' => null,
        'obx'    => null
    );

    private $_oruEventCodes = array(
        'create'    => 'P',
        'update_wo' => 'F',
        'update_wi' => 'C',
        'delete'    => 'D'
    );

    private $_oruEvent   = null;

    private $_oruMessage = array();

    protected function _buildOBXMessage($segmentContent)
    {
        return $this
            ->_checkOruEvent()
            ->_buildPaths()
            ->_createOruMessage($segmentContent)
            ->_getOruMessage()
        ;
    }


    private function _checkOruEvent()
    {
        $id   = $this->getParam('id');
        $type = $this->getParam('type');

        switch ($this->getParam('event')) {
            case 'create':
            case 'delete':

                $this->_oruEvent = $this->getParam('event');

                break;

            case 'update':
                $therapyId = dlookup($this->_db, 'therapieplan', 'therapieplan_id', "konferenz_patient_id = '{$id}'");

                if (strlen($therapyId) > 0) {
                    $this->setParam('therapy', 1);
                }

                $where = "export_name = 'hl7' AND export_nr = '{$id}' AND parameters = '{$type}_update_1'";

                $logTherapyCount = dlookup($this->_db, 'export_log', 'COUNT(export_log_id)', $where);

                if (strlen($therapyId) > 0 || $logTherapyCount > 0) {

                    if ($logTherapyCount > 0) {
                        $this->_oruEvent = 'update_wi';
                    } else {
                        $this->_oruEvent = 'update_wo';
                    }
                } else {
                    $this->_oruEvent =  'create';
                }

            break;
        }

        return $this;
    }

    private function _createOruMessage($segmentContent)
    {
        if (is_dir($this->_paths['obx']) === true) {
            $elements = getDirContent($this->_paths['obx']);

            $segmentContent = explode('|', $segmentContent);

            foreach ($elements as $i => $element) {
                $segment        = $segmentContent;
                $segment[1]     = $i + 1;
                $segment[3]     = $this->_messageUniqueMessageId();
                $segment[4]     = substr($element, 5);
                $segment[5]     = $this->_parseOruMessage(file_get_contents($this->_paths['obx'] . $element));
                $segment[11]    = $this->_oruEventCodes[$this->_oruEvent];
                $segment[14]    = $this->_buffer['vars']['exportTime'];

                $this->_oruMessage[] = implode('|', $segment);
            }
        } else {
            $this->_skipProfile[] = $this->getParam('activeProfile');
        }

        return $this;
    }

    private function _parseOruMessage($message)
    {
        $message = str_replace($this->_lineBreak, '', $message);

        return $message;
    }


    private function _getOruMessage()
    {
        return implode($this->_lineBreak, $this->_oruMessage);
    }

    private function _buildPaths()
    {
        $this->_paths['upload'] = getUploadDir($this->_smarty, 'upload');

        $table  = $this->_documentTypes[$this->getParam('type')]['table'];
        $id     = $this->getParam('id');

        $this->_paths['obx'] = "{$this->_paths['upload']}doc/xhtml/{$table}_{$id}/hl7/";

        return $this;
    }

}

?>
