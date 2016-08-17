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

require_once 'feature/dmp/scripts/class.dmpfactory.php';

if ( $permission->action( $action ) === true ) {
   require( $permission->getActionFilePath() );
}

$do_export = false;
$show_xkm = true;

$smarty->config_load( 'settings/interfaces.conf' );
$config  = $smarty->get_config_vars();

$smarty->config_load( 'app/export_dmp.conf', 'rec' );
$config = $smarty->get_config_vars();

$org_id = $_SESSION[ 'sess_org_id' ];

// Fields aufbauen
$query_melde_user = "
   (
      SELECT
         u.user_id,
         CONCAT_WS( ', ', u.nachname, u.vorname ) AS arzt

      FROM
         dmp_brustkrebs_eb d
         INNER JOIN patient p                     ON d.patient_id=p.patient_id
         INNER JOIN user u                        ON d.melde_user_id=u.user_id

      WHERE
         p.org_id='$org_id'

   ) UNION (
      SELECT
         u.user_id,
         CONCAT_WS( ', ', u.nachname, u.vorname ) AS arzt

      FROM
         dmp_brustkrebs_fb dp
         INNER JOIN patient p                     ON dp.patient_id=p.patient_id
         INNER JOIN user u                        ON dp.melde_user_id=u.user_id

      WHERE
         p.org_id='$org_id'

   )
   ORDER BY
      arzt
";
$fields = array(
   'sel_melde_user_id'  => array( 'req' => 1, 'size' => '', 'maxlen' => '11' , 'type' => 'query'   , 'ext' => $query_melde_user ),
   'sel_datum_von'      => array( 'req' => 1, 'size' => '', 'type' => 'date' ),
   'sel_datum_bis'      => array( 'req' => 1, 'size' => '', 'type' => 'date' ),
   'sel_empfaenger2'    => array( 'req' => 0, 'size' => '', 'type' => 'check')
);


// DMP-export alt darf nur noch daten vor dem 01.07.2013 exportieren !
if (array_key_exists('sel_datum_von', $_REQUEST) && $_REQUEST['sel_datum_von'] >= '01.07.2013') {
    $_REQUEST['sel_datum_von'] = '30.06.2013';
}

if (array_key_exists('sel_datum_bis', $_REQUEST) && $_REQUEST['sel_datum_bis'] >= '01.07.2013') {
    $_REQUEST['sel_datum_bis'] = '30.06.2013';
}

// Werte aus Formular in Fields legen
form2fields( $fields );

// Formularfelder generieren
$item = new itemgenerator( $smarty, $db, $fields, $config );
$item->preselected = false;
$item->generate_elements();

$smarty->assign( array(
	'export_done'        => $do_export,
	'back_btn'			 => 'page=extras'
) );

if ( ( $permission->action( "export" ) === true ) &&
     ( $permission->action( "download" ) === true ) ) {
   // Wenn keine Aktion, dann Script nicht weiter ausführen
   if ( ( $action != 'export' ) && ( $action != 'download' ) ) {
      return;
   }

   // Validierung Starten
   $validate = validate_dataset( $smarty, $db, $fields, 'ext_err', '' );
   if ( $validate == false ) {
      $item->generate_elements();
      return;
   }

   $dmp_export_obj = CDmpFactory::createObject( 'Export', '2.22', $smarty, $db );
   $dmp_export_obj->export( $_REQUEST, $_SESSION, $config );

   $do_export = true;

   // Anzeige im HTML
   $smarty->assign( array(
   	'export_done'        => $do_export,
   	'back_btn'			 => 'page=extras'
   ) );

}

////////////////////////////////////////////////////////////////////////////////////////////////////

function ext_err( $valid ) {
   $valid->start_end_date( array( 'sel_datum_von' ), array( 'sel_datum_bis' ) );
}

?>
