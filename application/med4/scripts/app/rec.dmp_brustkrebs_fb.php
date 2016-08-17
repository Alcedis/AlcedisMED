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

require_once( 'get_dmp_fd.php' );
require_once( 'feature/dmp/scripts/class.dmputils.php' );

$table      = 'dmp_brustkrebs_fb';
$form_id    = isset( $_REQUEST[ 'dmp_brustkrebs_fb_id' ] ) ? $_REQUEST[ 'dmp_brustkrebs_fb_id' ] : '';
$dmp_eb_id  = isset( $_REQUEST[ 'dmp_brustkrebs_eb_id' ] ) ? $_REQUEST[ 'dmp_brustkrebs_eb_id' ] : '';
$location   = get_url( 'page=view.erkrankung' );
$org_id     = $_SESSION[ 'sess_org_id' ];

if ( $permission->action( $action ) === true )
{
   $location = isset( $_REQUEST[ 'origin' ] ) ? get_url( $backPage ) : $location;
   require( $permission->getActionFilePath() );
}

// zusätzliche Config Werte laden
$smarty->config_load( 'settings/interfaces.conf' );
$config = $smarty->get_config_vars();

if ( strlen( $dmp_eb_id ) > 0 ) {
   $query = "
      SELECT
         d.melde_user_id

      FROM
         dmp_brustkrebs_eb d

      WHERE
         d.dmp_brustkrebs_eb_id=$dmp_eb_id
   ";
}
else {
   $query = "
      SELECT
         dp.melde_user_id

      FROM
         dmp_brustkrebs_fb dp

      WHERE
         dp.dmp_brustkrebs_fb_id=$form_id
   ";
}
$result = sql_query_array( $db, $query );

if ( !isset( $result[ 0 ][ 'melde_user_id' ] ) ) {
   // meldenden Arzt vorbelegen, sofern konfiguriert (nur im prä-INSERT-Fall)
   $default_melde_user_id = ( int )$config[ 'exp_dmp_default_melde_user_id' ];
   if ( $default_melde_user_id > 0 && strlen( $patient_id ) && !strlen( $form_id ) && !strlen( $action ) )
   {
      $_REQUEST[ 'melde_user_id' ] = $default_melde_user_id;
   }
}
else {
   $_REQUEST[ 'melde_user_id' ] = $result[ 0 ][ 'melde_user_id' ];
}

// KV-Werte vorbelegen (nur im prä-INSERT-Fall oder bei Klick auf "KV aktualisieren"
if ( strlen( $patient_id ) && ( $action == 'get_kv' || ( !strlen( $form_id ) && !strlen( $action ) ) ) ) {
   $result = reset( sql_query_array( $db, $query = "
      SELECT
         kv_iknr,
         kv_nr                AS versich_nr,
         kv_status            AS versich_status,
         kv_statusergaenzung  AS versich_statusergaenzung,
         kv_gueltig_bis       AS vk_gueltig_bis,
         kv_einlesedatum      AS kvk_einlesedatum
      FROM patient
      WHERE patient_id = '$patient_id'
   " ) );

   foreach( $result as $field => $value )
   {
      $_REQUEST[ $field ] = $value;
   }
}

// medizinische Daten vorbelegen (nur im prä-INSERT-Fall oder bei Klick auf "Daten aktualisieren"
if ( strlen( $patient_id ) && ( $action == 'get_dmp' || ( !strlen( $form_id ) && !strlen( $action ) ) ) ) {
   // im prä-INSERT-Fall oder bei nicht vorhandenem Doku-Datum die Daten zum aktuellen Datum holen
   $doku_datum_en = isset( $_REQUEST[ 'doku_datum' ] ) ? $_REQUEST[ 'doku_datum' ] : '';
   if( !strlen( $doku_datum_en ) )
   {
      $_REQUEST[ 'doku_datum' ] = date( 'd.m.Y' );
      $doku_datum_en = $_REQUEST[ 'doku_datum' ];
   }
   todate( $doku_datum_en, 'en' );

   // Daten holen
   require_once( 'scripts/app/get_dmp_fd.php' );
   $data = get_dmp_fd( $db, $patient_id, $erkrankung_id, $dmp_eb_id, $doku_datum_en );

   foreach( $data as $name => $value )
   {
      $_REQUEST[ $name ] = $value;
   }
}

// Reine Anzeige der Angabe aus der Erstdokumentation
if ( $dmp_eb_id == '' ) {
   $dmp_eb_id = dlookup( $db, "dmp_brustkrebs_fb", 'dmp_brustkrebs_eb_id', "dmp_brustkrebs_fb_id='$form_id'" );
}
$einschreibung_grund_bez = dlookup( $db, "dmp_brustkrebs_eb eb LEFT JOIN l_dmp_2013 d ON d.klasse='einschreibung' AND d.code=eb.einschreibung_grund", 'bez', "eb.dmp_brustkrebs_eb_id='$dmp_eb_id'" );
$fields[ 'einschreibung_grund' ] = array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'lookup', 'ext' => array( 'l_dmp_2013' => 'einschreibung' ) );
$fields[ 'dmp_brustkrebs_eb_id' ][ 'value' ][] = $form_id;

if ( ( $action != 'get_dmp' ) && ( $action != 'get_kv' ) ) {
   show_record( $smarty, $db, $fields, $table, $form_id, '', 'ext_warn' );
}
else {
   // Is nich toll, aber geht nich anders WEIL
   // die Daten TROTZ id NICHT aus der Datenbank kommen dürfen!!!
   $smarty->config_load( FILE_CONFIG_DEFAULT, 'validator' ); // laden des Config Files

   $config = $smarty->get_config_vars();                   // Config Variablen in variable $config
   $error = $smarty->get_template_vars( 'error' );         // gab es einen Error (Validierung, Datenbank)

   form2fields( $fields );

   // Validator Instanziieren und DB Handle setzen
   $valid = new validator( $smarty, $db, $fields );
   ext_warn( &$valid );

   $_warn_message = $valid->parse_block( 'warn' );

   // Warnmeldungen Anzeigen
   if ( strlen( $_warn_message ) ) {
      $smarty->assign( 'warn', array($_warn_message) );
   }
   else {
      unset( $_SESSION[ 'sess_warnung' ] );
   }
   todate( $fields,  'de' );
   tofloat( $fields, 'de' );
   totime( $fields, 'de' );

   $item = new itemgenerator( $smarty, $db, $fields, $config );
   $item->preselected = false;
   $item->generate_elements();
}

// bei Sonder-Action Werte nachbelegen
if ( $action == 'get_kv' || $action == 'get_dmp' ) {
   foreach( $fields as $name => $field ) {
      $fields[ $name ][ 'value' ][ 0 ] = isset( $_REQUEST[ $name ] ) ? $_REQUEST[ $name ] : '';
   }
}

// Hidden Inputs ausgeben
foreach( $fields as $name => $field ) {
   if ( isset( $field[ 'value' ][ 0 ] ) ) {
      $smarty->assign( $name, $field[ 'value' ][ 0 ] );
   }
}

$smarty->assign( 'einschreibung_grund_bez', isset( $einschreibung_grund_bez ) ? $einschreibung_grund_bez : '- - -' );

$button = get_buttons ( $table, $form_id, $statusLocked );
$smarty->assign( 'button',  $button );

///////////////////////////////////////////////////////////////////////////////

function ext_warn( $valid )
{

   require_once( 'feature/dmp/scripts/class.dmputils.php' );

   $dmp_protokoll = '';
   if ( isset( $_SESSION[ 'dmp_xml_protokoll' ] ) ) {
      $dmp_protokoll = $_SESSION[ 'dmp_xml_protokoll' ];
      unset( $_SESSION[ 'dmp_xml_protokoll' ] );
   }
   else if ( isset( $_REQUEST[ 'dmp_brustkrebs_fb_id' ] ) && ( strlen( $_REQUEST[ 'dmp_brustkrebs_fb_id' ] ) > 0 ) ) {
      $db = $valid->_db;
      $dmp_brustkrebs_fb_id = $valid->_fields[ 'dmp_brustkrebs_fb_id' ][ 'value' ][ 0 ];
      $dmp_protokoll = dlookup( $db, 'dmp_brustkrebs_fb', 'xml_protokoll', "dmp_brustkrebs_fb_id=$dmp_brustkrebs_fb_id" );
   }
   CDmpUtils::showDmpErrors( $valid->_smarty, $valid->_db, "fb", 0, $dmp_protokoll, $valid );
}

?>
