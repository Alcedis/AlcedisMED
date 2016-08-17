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

require_once 'message.php';

class hl7Export extends hl7ExportMessage
{
    private $_exportMessage = array();

    /**
     * Event types for profile
     * @var type
     */

    /**
     * create
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   Smarty $smarty
     * @return  hl7Export
     */
    public static function create($db, $smarty)
    {
        return new self($db, $smarty);
    }


    /**
     * call
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @param   Smarty      $smarty
     * @param   string      $type
     * @param   int         $id
     * @return  bool
     */
    public static function call($db, $smarty, $type, $id)
    {
        $return = false;

        if (appSettings::get('interfaces', null, 'hl7_e') === true && in_array($type, array('konferenz_patient', 'brief')) === true) {
            require_once 'feature/export/base/helper.database.php';

            $exportSettings = array(
                'org_id' => dlookup($db, "{$type} j INNER JOIN patient p ON j.patient_id = p.patient_id", 'org_id', "j.{$type}_id = '{$id}'")
            );

            $settingsExist = true;

            try {
                HDatabase::LoadExportSettings($db, $exportSettings, 'hl7');
            } catch (EExportException $e) {
                $settingsExist = false;
            }

            if ($settingsExist === true) {
                $upload     = getUploadDir($smarty, 'upload', false);
                $docDir     = $upload['config']['document_dir'];
                $xhtmlDir   = $upload['config']['xhtml_dir'];
                $file       = $upload['upload'] . $docDir . $docDir . "{$type}_{$id}" . '/protokoll.pdf';
                $srcFileDir = "{$upload['upload']}{$docDir}{$xhtmlDir}{$type}_{$id}/";

                $types   = array('konferenz_patient' => 'kp', 'brief' => 'br');

                $documentType = array_key_exists('documentType', $exportSettings) === true ? $exportSettings['documentType'] : null;
                $exportOnly   = array_key_exists('export_only_after_closure', $exportSettings) === true ? $exportSettings['export_only_after_closure'] : false;

                self::create($db, $smarty)
                    ->setProfile($exportSettings['profile'])
                    ->setParam('type', $types[$type])
                    ->setParam('id', $id)
                    ->setParam('documentType', $documentType)
                    ->setParam('exportPath',            $exportSettings['path'])
                    ->setParam('receiving_application', $exportSettings['receiving_application'])
                    ->setParam('receiving_facility',    $exportSettings['receiving_facility'])
                    ->setParam('export_only_after_closure', $exportOnly)
                    ->setParam('file', $file)
                    ->setParam('srcFileDir', $srcFileDir)
                    ->buildMessage()
                    ->export()
                ;

                return true;
            }
         }

        return $return;
    }


    /**
     * buildMessage
     *
     * @access  public
     * @param   string  $event
     * @return  hl7Export
     */
    public function buildMessage($event = null)
    {
        $this->_detectEvent($event);

        if ($this->_checkAfterClosureEvent($event) === false) {
            return $this;
        }

        $profiles = $this->getParam('profile');

        $profiles = is_array($profiles) == false
            ? ($profiles !== null ? array($profiles) : array())
            : $profiles
        ;

        foreach ($profiles as $profile) {
            $this->setParam('activeProfile', $profile);

            $this->_buildMessage();

            if (in_array($profile, $this->_skipProfile) === false) {
                $this->_exportMessage[$profile] = array(
                    'uniqueMessageId' => $this->getParam('uniqueMessageId'),
                    'type' => $this->_profileMapping[$profile],
                    'msg' => $this->_getMessage()
                );
            }
        }

        return $this;
    }


    /**
     * _checkAfterClosureEvent
     * (if settings active then export only after conference closure)
     *
     * @access  protected
     * @return  bool
     */
    protected function _checkAfterClosureEvent($event)
    {
        if ($this->getParam('export_only_after_closure') === true && $this->getParam('type') == 'kp') {
            $id      = $this->getParam('id');
            $type    = $this->getParam('type');

            // if export only after closure and form will be deleted, check if a minimum one export exists
            if ($event === 'delete') {
                $where   = "export_name = 'hl7' AND export_nr = '{$id}' AND LEFT(parameters, 2) = '{$type}'";
                $entries = dlookup($this->_db, 'export_log', 'COUNT(export_log_id)', $where);

                // if no exports exists
                if ($entries == 0) {
                    return false;
                }
            } else {
                $query = "
                    SELECT
                      k.konferenz_id
                    FROM konferenz_patient kp
                         INNER JOIN konferenz k ON kp.konferenz_id = k.konferenz_id AND k.final IS NOT NULL
                    WHERE kp.konferenz_patient_id = '{$id}'
                ";

                $result = sql_query_array($this->_db, $query);

                if (count($result) === 0) {
                    return false;
                }
            }
        }

        return true;
    }


    /**
     * export
     *
     * @access  public
     * @return  $this
     */
    public function export()
    {
        if (count($this->_exportMessage) > 0) {
            foreach ($this->_exportMessage as $profile => $msg) {
                if ($profile == 'b') {
                    $this->_copyDocument();
                }

                $this
                    ->_writeHl7File($msg)
                ;
            }

            $this->_writeExportLog();
        }

        return $this;
    }


    /**
     * Profile B
     *
     * Copy document to KIS readable directory
     *
     */
    private function _copyDocument()
    {
        copy($this->getParam('file'), $this->_getExportPath('doc') . $this->getParam('documentFileName'));

        return $this;
    }


    private function _writeHl7File($msg)
    {
        $fileName = "{$msg['uniqueMessageId']}_{$msg['type']}.hl7";

        $file = fopen($this->_getExportPath('msg') . $fileName , 'w');

        fwrite($file , $msg['msg']);
        fclose($file);

        return $this;
    }

    /**
     * writes event data to export_log
     *
     */
    private function _writeExportLog()
    {
        $data = array(
            'export_name' => 'hl7',
            'export_nr'   => $this->getParam('id'),
            'parameters'  => concat(array(
                $this->getParam('type'),
                $this->getParam('event'),
                $this->getParam('therapy')
            ), '_'),
            'org_id'      => $this->getParam('orgId'),
            'finished'    => 1,
            'melder_id'   => $this->getParam('messengerId'),
            'createtime'  => 'NOW()'
        );

        $sql = sqlArrayToInsertQuery($data, 'export_log');

        mysql_query($sql, $this->_db);

        return $this;
    }

    /**
     * detect protocol event
     */
    private function _detectEvent($event = null)
    {
        if ($event === null) {
            $id      = $this->getParam('id');
            $type    = $this->getParam('type');

            $where   = "export_name = 'hl7' AND export_nr = '{$id}' AND LEFT(parameters, 2) = '{$type}'";
            $entries = dlookup($this->_db, 'export_log', 'COUNT(export_log_id)', $where);

            $val = $entries == 0 ? 'create' : 'update';
        } else {
            $val = $event;
        }

        $this->setParam('event', $val);

        return $this;
    }
}

?>
