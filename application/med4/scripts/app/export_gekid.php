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

// ZIP Library und GEKID-Functions laden
require_once( DIR_LIB .'/zip/pclzip.lib.php' );
require_once 'feature/gekid/scripts/class.gekidfactory.php';

if ( $permission->action( $action ) === true ) {
   require( $permission->getActionFilePath() );
}

$view_state = '';

// Config laden
$smarty->config_load( 'settings/interfaces.conf' );
$config  = $smarty->get_config_vars();
$smarty->config_load( 'app/export_gekid.conf', 'export_gekid' );
$config = $smarty->get_config_vars();

// Fields
$query_melde_user = "
   SELECT DISTINCT
      u.user_id,
      CONCAT_WS( ', ', u.nachname, u.vorname ) AS arzt

   FROM patient p      
      INNER JOIN ekr e  ON e.patient_id=p.patient_id
   	  INNER JOIN user u ON e.user_id=u.user_id  
      
   WHERE
      p.org_id='$org_id'

   ORDER BY
      arzt
";
$fields = array(
   'sel_melde_user_id' => array( 'req' => 1, 'size' => '', 'maxlen' => '11' , 'type' => 'query', 'ext' => $query_melde_user ),
   'sel_datum_von'     => array( 'req' => 1, 'size' => '', 'type' => 'date' ),
   'sel_datum_bis'     => array( 'req' => 1, 'size' => '', 'type' => 'date' )
);

// Werte aus Formular in Fields legen
form2fields( $fields );

$fields[ 'sel_datum_bis' ][ 'value' ][ 0 ] = date( 'd.m.Y' );

// Formularfelder generieren
$item = new itemgenerator( $smarty, $db, $fields, $config );
$item->preselected = false;
$item->generate_elements();

$smarty->assign( "back_btn", "page=extras" );

if ( ( $permission->action( "export" ) === true ) && 
     ( $permission->action( "download" ) === true ) ) {
        
   // Wenn keine Aktion, dann Script nicht weiter ausführen
   if ( ( $action != 'export' ) && ( $action != 'download' ) ) {
      return;
   }

   // Validierung starten
   $validate = validate_dataset( $smarty, $db, $fields, 'ext_err', '' );
   if ( !$validate ) {
      $item->generate_elements();
      return;
   }
   
   $gekid_export_obj = CGekidFactory::createObject( '', '1.06', $smarty, $db );
   $view_state = $gekid_export_obj->export( $_REQUEST, $_SESSION, $config );
   
   $smarty->assign( "view", $view_state );
}

function ext_err( $valid )
{
   $valid->start_end_date( array( 'sel_datum_von' ), array( 'sel_datum_bis' ) );
}

?>