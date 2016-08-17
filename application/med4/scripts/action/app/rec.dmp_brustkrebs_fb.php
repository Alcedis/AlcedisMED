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

$dmp_fd_obj = CDmpFactory::createObject( 'Fd', '2.22', $smarty, $db );

switch( $action )
{
   case 'insert' :
//  insert ab MED 4.0.28 nicht mehr möglich
//      initXml( $fields, $_REQUEST );
//      $no_errors = action_insert( $smarty, $db, $fields, $table, $action, '', '', '' );
//      if ( $no_errors && !is_null( $dmp_fd_obj ) ) {
//         $errors = $smarty->get_template_vars( 'error' );
//         if ( is_null( $errors ) || ( strlen( $errors ) > 0 ) ) {
//            $doku_datum_en = $_REQUEST[ 'doku_datum' ];
//            todate( $doku_datum_en, 'en' );
//            $dmp_db_id  = dlookup( $db, 'dmp_brustkrebs_fb', 'dmp_brustkrebs_fb_id', "dmp_brustkrebs_eb_id=" . $_REQUEST[ 'dmp_brustkrebs_eb_id' ] . " AND doku_datum='" . $doku_datum_en . "'" );
//            $dmp_fd_obj->generate( $dmp_db_id );
//            $_REQUEST['dmp_brustkrebs_fb_id'] = $dmp_db_id;
//            setXml( $dmp_fd_obj, $_REQUEST, $_SESSION );
//            $no_errors = action_update( $smarty, $db, $fields, $table, $dmp_db_id, 'update', '', '', 'ext_warn' );
//            if ( $no_errors ) {
//               action_cancel( $location );
//            }
//         }
//      }

      break;

   case 'update' :

      initXml( $fields, $_REQUEST );
      $no_errors = action_update( $smarty, $db, $fields, $table, $form_id, $action, '', '', '' );
      if ( $no_errors && !is_null( $dmp_fd_obj ) ) {
         $errors = $smarty->get_template_vars( 'error' );
         if ( is_null( $errors ) || ( strlen( $errors ) > 0 ) ) {
            $dmp_fd_obj->generate( $form_id );
            setXml( $dmp_fd_obj, $_REQUEST, $_SESSION );
            $_REQUEST['dmp_brustkrebs_fb_id'] = $form_id;
            $no_errors = action_update( $smarty, $db, $fields, $table, $form_id, 'update', '', '', 'ext_warn' );
            if ( $no_errors ) {
               action_cancel( $location );
            }
         }
      }

      break;

   case 'delete' :

      action_delete( $smarty, $db, $fields, $table, $form_id, $action, $location );

      break;

   case 'cancel' : action_cancel( $location );                                                   break;

}

function initXml( &$fields, &$request )
{
   $fields[ 'xml' ]           = array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '' );
   $fields[ 'xml_protokoll' ] = array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '' );
   $fields[ 'xml_status' ]    = array( 'req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '' );
   $request[ 'xml' ]            = "";
   $request[ 'xml_status' ]     = 1;
   $request[ 'xml_protokoll' ]  =
      "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>" .
      "<data>" .
      "  <parameter>" .
      "    <ERGEBNIS_TEXT>Fehlerhaft</ERGEBNIS_TEXT>" .
      "  </parameter>" .
      "  <record>" .
      "    <GRUPPE>Header</GRUPPE>" .
      "    <FEHLER_NR>Fehler</FEHLER_NR>" .
      "    <MELDUNG>XPM check ist fehlgeschlagen.</MELDUNG>" .
      "  </record>" .
      "</data>";
}

function setXml( $dmp_obj, &$request, &$session )
{
   $request[ 'xml' ] = $dmp_obj->getXml();
   $request[ 'xml_status' ] = $dmp_obj->getXmlStatus();
   $request[ 'xml_protokoll' ] = $dmp_obj->getXmlProtocol();
   $session[ 'dmp_xml_protokoll' ] = $dmp_obj->getXmlProtocol();
}

?>
