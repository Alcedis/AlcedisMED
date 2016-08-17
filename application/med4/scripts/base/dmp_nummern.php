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

// Fields aufbauen
$fields = array(
   'nr_von' => array( 'req' => 1, 'size' => '', 'maxlen' => 7, 'type' => 'int' ),
   'nr_bis' => array( 'req' => 1, 'size' => '', 'maxlen' => 7, 'type' => 'int' )
);

// Werte aus Formular in Fields legen
form2fields( $fields );

// Anzahl frei Nummern zeigen
$anz_frei   = dlookup($db, 'dmp_nummern', "COUNT(*)", "org_id = {$org_id} AND patient_id = 0");
$info       = sprintf($config['msg_freie_nummern'], $anz_frei);

$smarty
   ->assign('info', $info)
   ->assign('back_btn', 'page=extras')
;

// Formularfelder generieren
$item = new itemgenerator( $smarty, $db, $fields, $config );
$item->preselected = false;
$item->generate_elements();

// Wenn keine Aktion, dann Script nicht weiter ausführen
if ( !strlen( $action ) )
{
   return;
}

// Validierung starten
$validate = validate_dataset( $smarty, $db, $fields, 'ext_err', '' );
if( ! $validate  )
{
   $item->generate_elements();
   return;
}

// Aktion ausführen
$nummern = range( ( int )$fields[ 'nr_von' ][ 'value' ][ 0 ], ( int )$fields[ 'nr_bis' ][ 'value' ][ 0 ] );
$info    = '';

switch( $action )
{
   case 'delete':
      $nummern_liste = "'" . implode( "', '", $nummern ) . "'";
      $query = "DELETE FROM dmp_nummern WHERE org_id=$org_id AND nr IN ($nummern_liste) AND patient_id=0";
      @mysql_query( $query, $db );
      $info = $config[ 'msg_delete' ];
      break;

   case 'insert':
      foreach( $nummern as $nr )
      {
         $query = "INSERT INTO dmp_nummern (org_id, nr) VALUES ($org_id, $nr)";
         @mysql_query( $query, $db );
      }
      $info = $config[ 'msg_insert' ];
      break;
}

$anz_frei = dlookup( $db, 'dmp_nummern', "COUNT(*)", "org_id=$org_id AND patient_id=0" );
$smarty->assign( 'action_done', true );
$info .= ' ' . sprintf( $config[ 'msg_freie_nummern' ], $anz_frei );
$smarty->assign( 'info', $info );

////////////////////////////////////////////////////////////////////////////////////////////////////

function ext_err( $valid )
{
}

?>
