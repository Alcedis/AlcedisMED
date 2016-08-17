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

class hl7ImportBehandler extends hl7ImportAufenthalt
{
    protected function _importBehandler($cachePatient)
    {
        if ($this->_patientId !== null) {
            $dataset = array();

            foreach ($this->getFieldSettings('arzt') as $field) {
               $dataset[$field['feld']] = $this->getFieldValue($field['med_feld']);
            }

            if (check_array_content($dataset) !== true) {
               return false;
            }

            // required
            $req = array('nachname', 'vorname', 'plz');

            $where = array();

            foreach ($req as $r) {
                if ($this->_isFilled($dataset, $r) === false) {
                   return;
                }

                $where[] = "{$r} = '" . $this->_escape($dataset[$r]) . "'";
            }

            $arztId      = null;
            $aDataset = sql_query_array($this->_db, "SELECT * FROM user WHERE " . implode(' AND ', $where));

            $fields  = $this->_smarty->widget->loadExtFields('fields/base/user.php');

            //Update
            if (count($aDataset) > 0) {
                $aDataset = reset($aDataset);

                $arztId = $aDataset['user_id'];

                array2fields($this->mergeDataset($aDataset, $dataset), $fields);

                execute_update($this->_smarty, $this->_db, $fields, 'user', "user_id = '{$aDataset['user_id']}'", 'update', '', true, -90);

                $this->_imported = true;
             } else {
                $dataset['loginname'] = time() . $dataset['nachname'] . rand(0,100). $dataset['vorname'];

                $eLoginname = $this->_escape($dataset['loginname']);

                while (strlen(dlookup($this->_db, 'user', 'MAX(user_id)', "loginname = '{$eLoginname}'")) > 0) {
                    $dataset['loginname'] = time() . $dataset['nachname'] . rand(0,100). $dataset['vorname'];
                    $eLoginname = $this->_escape($dataset['loginname']);
                }

                $dataset['pwd']         = md5(1);
                $dataset['candidate']   = 1;

                array2fields($dataset, $fields);

                execute_insert($this->_smarty, $this->_db, $fields, 'user', 'insert', true, -90);

                $arztId = dlookup($this->_db, 'user', 'MAX(user_id)', "loginname = '{$eLoginname}'");

                $this->_imported = true;
             }

             if ($arztId !== null) {
                if (array_key_exists('funktion', $dataset) === true && strlen($dataset['funktion']) > 0) {
                    $bdataset = array(
                        'user_id'       => $arztId,
                        'patient_id'    => $this->_patientId,
                        'funktion'      => $dataset['funktion']
                    );

                    $condition = array();

                    foreach ($bdataset as $condKey => $condValue) {
                        $condition[] = "{$condKey} = '" . $this->_escape($condValue) . "'";
                    }

                    $behandlerId = dlookup($this->_db, 'behandler', 'behandler_id', implode(' AND ', $condition));

                    if (strlen($behandlerId) == 0) {
                        $fields  = $this->_smarty->widget->loadExtFields('fields/app/behandler.php');

                        array2fields($bdataset, $fields);

                        execute_insert($this->_smarty, $this->_smarty, $fields, 'behandler', 'insert', false, -90);

                        $this->_imported = true;
                     }
                }
            }
        }
    }
}

?>