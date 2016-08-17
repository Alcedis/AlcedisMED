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
   case 'org':

       $dataset = reset(sql_query_array($db, "SELECT
           org_name AS 'name',
           org_namenszusatz AS 'namenszusatz',
           org_strasse AS 'strasse',
           org_hausnr AS 'hausnr',
           org_ort  AS 'ort',
           org_plz  AS 'plz',
           org_telefon AS 'telefon',
           org_telefax AS 'telefax',
           org_email AS 'email',
           org_bundesland AS 'bundesland',
           org_staat AS 'staat',
           org_website AS 'website'
       FROM user_reg
       WHERE
           user_reg_id = '{$form_id}'
       "));

       $orgFields = $widget->loadExtFieldsOnce('fields/base/org.php');

       $orgFields = dataArray2fields($dataset, $orgFields);

       $selectedOrg = dlookup($db, 'org', 'org_id', "name = '{$dataset['name']}' AND ort = '{$dataset['ort']}'");

       if (strlen($selectedOrg) == 0) {
           execute_insert($smarty, $db, $orgFields, 'org', 'insert', true);

           $selectedOrg = dlookup($db, 'org', 'MAX(org_id)', "name = '{$dataset['name']}'");
       }

      break;

   case 'approval':

       $userId = dlookup($db, 'user_reg', 'user_id', "user_reg_id = '{$form_id}'");

       $_REQUEST['user_id']     = $userId;
       $_REQUEST['registered']  = 1;

       $noError = action_update($smarty, $db, $fields, 'user_reg', $form_id, 'update', '', 'ext_err', '', true);

       if ($noError) {
           $orgId = reset($fields['org_id']['value']);

           $userFields = $widget->loadExtFieldsOnce('fields/base/user.php');

           $userDataset = reset(sql_query_array($db, "SELECT * FROM user WHERE user_id = '{$userId}'"));
           $userDataset['inaktiv'] = NULL;
           $userDataset['org'] = dlookup($db, 'org', 'name', "org_id = '{$orgId}'");

           $userFields = dataArray2fields($userDataset, $userFields);

           execute_update($smarty, $db, $userFields, 'user', "user_id = '{$userId}'", 'update', "", true);

           //insert auf recht
           $rechtDataset = array(
               'org_id' => $orgId,
               'user_id' => $userId,
               'rolle' => $regRole,
               'recht_global' => 1
           );

           alcEmail::create($db, $smarty)
               ->setTemplate('account_activated')
               ->setRecipient($userDataset)
               ->send()
           ;

           $rechtVorhanden = dlookup($db, 'recht','recht_id', "org_id = '{$rechtDataset['org_id']}' AND user_id = '{$rechtDataset['user_id']}' AND rolle = '{$rechtDataset['rolle']}'");

           if (strlen($rechtVorhanden) == 0) {
               $rechtFields = dataArray2fields($rechtDataset, $widget->loadExtFieldsOnce('fields/base/recht.php'));
               execute_insert($smarty, $db, $rechtFields, 'recht', 'insert', true);
           }

           action_cancel('index.php?page=list.user_reg');
      }

      break;

   case 'cancel':

         action_cancel($location);

         break;
}

?>