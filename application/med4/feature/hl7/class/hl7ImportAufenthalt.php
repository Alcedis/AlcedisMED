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

class hl7ImportAufenthalt extends hl7ImportVorlageKrankenversicherung
{
    protected function _importAufenthalt($cachePatient)
    {
        if ($this->_patientId !== null) {
            $dataset = array();

            foreach ($this->getFieldSettings('aufenthalt') as $field) {
                $dataset[$field['feld']] = $this->getFieldValue($field['med_feld']);
            }

            if (check_array_content($dataset) !== true) {
                return;
            }

            $aDataset = array();

            if ($this->_isFilled($dataset, 'aufnahmenr') === true) {
                $aufnNr = $this->_escape($dataset['aufnahmenr']);
                $aDataset = sql_query_array($this->_db, "SELECT * FROM aufenthalt WHERE patient_id = '{$this->_patientId}' AND aufnahmenr = '{$aufnNr}'");
            }

            if (count($aDataset) === 0 && $this->_isFilled($dataset, 'aufnahmedatum') === true) {
                $aufnDatum = $this->_escape($dataset['aufnahmedatum']);
                $aDataset = sql_query_array($this->_db, "SELECT * FROM aufenthalt WHERE patient_id = '{$this->_patientId}' AND aufnahmedatum = '{$aufnDatum}'");
            }

            $fields  = $this->_smarty->widget->loadExtFields('fields/app/aufenthalt.php');

            //Update
            if (count($aDataset) > 0) {
                $dataset = $this->mergeDataset(reset($aDataset), $dataset);

                array2fields($dataset, $fields);

                $where = "aufenthalt_id = '{$dataset['aufenthalt_id']}'";

                execute_update($this->_smarty, $this->_db, $fields, 'aufenthalt', $where, 'update', '', false, -90);
             } else {
                //Insert
                $dataset['patient_id'] = $this->_patientId;

                array2fields($dataset, $fields);

                execute_insert($this->_smarty, $this->_db, $fields, 'aufenthalt', 'insert', false, -90);
             }

             $this->_imported = true;
        }
    }
}

?>