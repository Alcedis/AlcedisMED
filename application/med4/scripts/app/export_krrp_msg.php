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

$export_id  = $_REQUEST[ 'export_id' ]  ? $_REQUEST[ 'export_id' ]  : '';
$patient_id = $_REQUEST[ 'patient_id' ] ? $_REQUEST[ 'patient_id' ] : '';

$query = "
   SELECT
     p.vorname, 
     p.nachname, 
     DATE_FORMAT( p.geburtsdatum, '$format_date' ) AS geburtsdatum

   FROM 
      patient p

   WHERE
      p.patient_id=$patient_id
";
$result = end( sql_query_array( $db, $query ) );
$bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . ")";

$query  = "SELECT * FROM exp_ekrrp_log WHERE export_id=$export_id AND patient_id=$patient_id;";
$result = end( sql_query_array( $db, $query ) );

$smarty->assign( 'result' , $result );
$smarty->assign( 'patient', $bez );
$smarty->display( 'app/export_krrp_msg.tpl' );

exit;

?>