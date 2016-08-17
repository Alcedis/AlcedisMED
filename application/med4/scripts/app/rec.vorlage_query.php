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

require_once 'core/class/report/package.php';

$table    = 'vorlage_query';
$form_id  = isset( $_REQUEST['vorlage_query_id'] ) ? $_REQUEST['vorlage_query_id'] : '';
$location = get_url('page=list.vorlage_query');

$isAdmin  = ($rolle_code == 'admin');

//DLIST
if ($ajax === true) {
   if (isset($_REQUEST['show_dlist']) === true) {
      $vorlageQueryOrgFields = $widget->loadExtFields('fields/app/vorlage_query_org.php');

      $query = "
        SELECT
          vo.*
        FROM vorlage_query_org vo
            INNER JOIN org o ON vo.org_id = o.org_id
        WHERE vo.vorlage_query_id = '{$form_id}'
        GROUP BY vo.vorlage_query_org_id
        ORDER BY
            o.name
        ";

      echo create_json_string(load_pos_sess($db, 'vorlage_query_org', $query, 'org', $vorlageQueryOrgFields, $config));
      exit;
   }
}

$upload   = new upload($smarty);

$upload->setDestinations(array('package' => array('doc', 'tpl')));

$freigabe = false;
$inaktiv  = false;

if ($form_id) {
   $freigabe   = dlookup($db, 'vorlage_query', 'freigabe', "vorlage_query_id = '$form_id'") == 1 ? true : false;
   $inaktiv    = dlookup($db, 'vorlage_query', 'inaktiv',  "vorlage_query_id = '$form_id'") == 1 ? true : false;

   $inactivePermission  = $permission->action('inactive');
   $activePermission    = $permission->action('active');

   $statePermission = false;

   if ($inaktiv == true && $activePermission == true) {
      $statePermission = 'active';
   } elseif($freigabe == true && $inaktiv == false && $inactivePermission == true) {
      $statePermission = 'inactive';
   }

   $smarty
      ->assign('status_locked', $freigabe)
      ->assign('freigabe', $freigabe)
      ->assign('stateButton', $statePermission)
      ->assign('inaktiv', $inaktiv)
   ;
}


if ($permission->action($action) === true) {
   require($permission->getActionFilePath());
}

$button   = get_buttons ( $table, $form_id );

show_record( $smarty, $db, $fields, $table, $form_id);

$upload->setFields(array('package'));
$upload->assignVars($fields);

$confirmDial = $rolle_code === 'supervisor' ? false : true;

if ($freigabe == true) {
   $button = 'update.cancel';
} else {
   $button = get_buttons($table, $form_id, null, $confirmDial);
}

//Schritt 3
if ($inaktiv == true) {
   $button = 'cancel';
}

$smarty
   ->assign('isAdmin', $isAdmin)
   ->assign('button',  $button)
   ->assign('back_btn',  'page=list.vorlage_query')
;

function ext_err( $valid )
{
   $fields  = &$valid->_fields;
   $smarty  = &$valid->_smarty;
   $config  = $smarty->get_config_vars();
   $db      = $valid->_db;

   $isAdmin = (isset($_SESSION['sess_rolle_code']) === true && $_SESSION['sess_rolle_code'] == 'admin');

   // check if have sql, then it was not created by admin
   if ($isAdmin === false) {

       $error   = '';

       $sql = reset($fields['sqlstring']['value']);

       require("reports/xls/query.php");

        // SQL-Statement muss mit SELECT beginnen
        // und darf kein Semikolon enthalten
        // damit keine Datenmanipulationen möglich sind
        $sql = reportContentQuery::parseSql($fields['sqlstring']['value'][0]);

        if( strtoupper( substr( $sql, 0, 6 ) ) != 'SELECT' )
           $error   = $config['err_sql_select'];

        if( strpos( $sql, ';' ) !== false )
           $error   = $config['err_sql_semikolon'];

        // funktioniert das SQL?
        if( !strlen( $error) )
        {
           $query_result = @mysql_query( $sql, $db );
           if( $query_result === false )
              $error = $config['err_sql_syntax'] . '<br>' . mysql_error( $db );
        }

        //Backslash Fix
        if(strlen($error)){
           $valid->set_err( 12, 'sqlstring', false, $error );
        }

   } else {
        $upload = new upload($smarty);
        $uploadFields = array('package');

        $upload->setFields($uploadFields);
        $upload->setValidExtensions(array('zip'));
        $upload->setMandatory(array(1));
        $upload->upload2UserTmp($valid);
        $upload->assignVars($fields);

        $fields['package']['value'][0] = $upload->getFilename('package');

        // wenn kein Upload durchgeführt wurde ist hier schluss
        if ($upload->uploadPerformed() === false) {
           return;
        }
   }
}


?>