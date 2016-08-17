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

class hl7ImportVorlageKrankenversicherung extends hl7ImportPatient
{
    protected function _importVorlageKrankenversicherung($cachePatient)
    {
        if ($this->_patientId !== null) {
            $dataset = array();

            foreach ($this->getFieldSettings('patient') as $field) {
                $dataset[$field['feld']] = $this->getFieldValue($field['med_feld']);
            }

            if ($this->_isFilled($dataset, 'kv_iknr') === true) {
                $kvIknr  = substr(str_pad($dataset['kv_iknr'], 9, '0', STR_PAD_LEFT), -9);
                $eKvIknr = $this->_escape($kvIknr);

                //Check 1 - Iknr in l_ktst vorhanden
                $lKtst = dlookup($this->_db, 'l_ktst', 'iknr', "iknr = '{$eKvIknr}'");

                if (strlen($lKtst) == 0) {

                   $kvDataset = array();

                   foreach ($this->getFieldSettings('vorlage_krankenversicherung') as $field) {
                      $kvDataset[$field['feld']] = $this->getFieldValue($field['med_feld']);
                   }

                   if (check_array_content($kvDataset) !== true) {
                      return;
                   }

                    if (array_key_exists('iknr', $kvDataset) === true && strlen($kvDataset['iknr'])) {
                        $kvDataset['iknr'] = substr(str_pad($kvDataset['iknr'], 9, '0', STR_PAD_LEFT), -9);
                    }

                   //Check 2 - Iknr in vorlage_krankenversicherung
                   $vorlageKrankenversicherung = sql_query_array($this->_db, "SELECT * FROM vorlage_krankenversicherung WHERE iknr = '{$eKvIknr}'");

                   $fields = $this->_smarty->widget->loadExtFields('fields/app/vorlage_krankenversicherung.php');

                   //Update
                   if (count($vorlageKrankenversicherung) > 0) {
                      $kvDataset = $this->mergeDataset(reset($vorlageKrankenversicherung), $kvDataset);

                      array2fields($kvDataset, $fields);

                      $where = "vorlage_krankenversicherung_id = '{$kvDataset['vorlage_krankenversicherung_id']}'";

                      execute_update($this->_smarty, $this->_db, $fields, 'vorlage_krankenversicherung', $where, 'update', '', true, -90);
                   } else {
                      array2fields($kvDataset, $fields);

                      execute_insert($this->_smarty, $this->_db, $fields, 'vorlage_krankenversicherung', 'insert', true, -90);
                   }

                   $this->_imported = true;
                }
            }
        }
    }
}


?>
