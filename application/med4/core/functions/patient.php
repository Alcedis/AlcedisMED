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

function deletePatient($db, $smarty, $tables, $patientId)
{
   $dbTables      = relationManager::get();
   $patient       = dlookup($db, 'patient', "CONCAT_WS(', ', nachname, vorname)", "patient_id = '{$patientId}'");
   $config        = $smarty->get_config_vars();

   foreach ($tables as $table) {
      if (in_array($table, $dbTables) == true && $table != 'status') {
         $fields     = $smarty->widget->loadExtFields("fields/app/{$table}.php");

         $results    = sql_query_array($db, "SELECT * FROM {$table} WHERE patient_id = '{$patientId}'");

         $primaryKey = get_primaer_key($table);

         foreach ($results as $result) {
            $value      = $result[$primaryKey];
            $tmpFields  = $fields;

            array2fields($result, $tmpFields);

            action_delete($smarty, $db, $tmpFields, $table, $value, 'delete');
         }
      }
   }

   $_SESSION['sess_warn'][] = sprintf($config['patient_deleted'], $patient);
}

?>