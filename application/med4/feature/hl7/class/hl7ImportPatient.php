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

class hl7ImportPatient extends hl7Log
{
    protected $_patientId = null;

    protected $_patientBuffer = array();

    protected $_imported = false;

    protected function _importPatient($cachePatient)
    {
        $cacheId = $cachePatient['hl7_cache_id'];

        if (array_key_exists($cacheId, $this->_patientBuffer) === false) {
            //find patient in patient database
            $patient = $this->_identPatient($this->getSettings('patient_ident'), $cachePatient, 'patient', null, true);

            // if found, buffer it for all messages of this cachePatient
            if (check_array_content($patient) === true) {
                $this->_patientBuffer[$cacheId] = $patient;
            }
        } else {
            $patient = $this->_patientBuffer[$cacheId];
        }

        $this->_patientId = array_key_exists('patient_id', $patient) ? $patient['patient_id']: null;

        $dataset = array(
            'org_id' => $cachePatient['org_id']
        );

        foreach ($this->getFieldSettings('patient') as $field) {
            $dataset[$field['feld']] = $this->getFieldValue($field['med_feld']);
        }

        //IK Nummer 7 stellig
        if (array_key_exists('kv_iknr', $dataset) === true && strlen($dataset['kv_iknr'])) {
            $dataset['kv_iknr'] = substr(str_pad($dataset['kv_iknr'], 9, '0', STR_PAD_LEFT), -9);
        }

        if ($this->_patientId !== null) {
            $fields = $this->_smarty->widget->loadExtFields('fields/app/patient.php');

            $dataset = $this->mergeDataset($patient, $dataset);

            array2fields($dataset, $fields);

            execute_update($this->_smarty, $this->_db, $fields, 'patient', "patient_id = {$this->_patientId}", 'update', '', true, -90);

            $this->_imported = true;
         }


        //Insert
        if ($this->_patientId === null && $this->getParam('updateOnly') === false) {
            $fields = $this->_smarty->widget->loadExtFields('fields/app/patient.php');

            array2fields($dataset, $fields);

            execute_insert($this->_smarty, $this->_db, $fields, 'patient', 'insert', true, -90);

            $this->_patientId = $this->_identPatient($this->getSettings('patient_ident'), $cachePatient, 'patient');

            $this->_imported = true;
        }

        return $this;
    }


    public function getPatientId()
    {
        return $this->_patientId;
    }
}

?>
