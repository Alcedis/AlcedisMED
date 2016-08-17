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

switch( $action )
{
   case 'insert':

      $_REQUEST['org_id'] = dlookup($db, 'patient', 'org_id', "patient_id = {$patient_id}");

      action_insert( $smarty, $db, $fields, $table, $action, $location, 'ext_err');

      break;

   case 'update': action_update( $smarty, $db, $fields, $table, $form_id, $action, $location, 'ext_err');  break;
   case 'delete': action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location ); break;
   case 'cancel': action_cancel( $location );                                                   break;

   case 'print':

      $where = '';

      switch (end(array_keys($_REQUEST['action'][$action]))) {
         case 'multiple':

            $ident = reset($_REQUEST['action'][$action]);

            $erinnerung = is_array($ident) === true ? reset(array_keys($ident)) == 'erinnerung' : false;

            $datum_von = isset($_REQUEST['sel_datum_von']) === true && strlen($_REQUEST['sel_datum_von']) ? todate($_REQUEST['sel_datum_von'], 'en') : '1900-01-01';
            $datum_bis = isset($_REQUEST['sel_datum_bis']) === true && strlen($_REQUEST['sel_datum_bis']) ? todate($_REQUEST['sel_datum_bis'], 'en') : '2099-12-30';

            $where = "t.datum BETWEEN '{$datum_von}' AND '{$datum_bis}' AND t.org_id = {$org_id} AND e.erkrankung IN ('{$queryRechtErkrankung}') AND t.erledigt IS NULL";

            if ($erinnerung === true) {
               $where .= " AND t.erinnerung = 1";
            }

            break;

         case 'single':

            $ident = reset($_REQUEST['action'][$action]);

            $terminId = is_array($ident) === true ? reset(array_keys($ident)) : $form_id;

            if (strlen($terminId) > 0) {
               $where = "termin_id = {$terminId}";
            }

            break;
      }

      if (strlen($where) > 0) {
         $export_id = sql_query_array($db, "
            SELECT
               t.patient_id,
               t.termin_id
            FROM termin t
               LEFT JOIN erkrankung e ON t.patient_id = e.patient_id
            WHERE
               {$where}
            GROUP BY t.termin_id
         ");

         $smarty->config_load('app/termin.conf', 'brief');
         $config = $smarty->get_config_vars();

         require_once 'reports/pdf/termin_brief.php';
      }

      break;
}

?>