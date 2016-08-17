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
$ekr_id = $_REQUEST[ 'ekr_id' ] ? $_REQUEST[ 'ekr_id' ] : '';

$query = "
   SELECT
     p.vorname, 
     p.nachname, 
     DATE_FORMAT( p.geburtsdatum, '$format_date' ) AS geburtsdatum,
	 DATE_FORMAT( ekr.datum, '$format_date' )      AS datum

   FROM 
      ekr
      INNER JOIN erkrankung e ON e.erkrankung_id=ekr.erkrankung_id
      INNER jOIN patient p	  ON p.patient_id=e.patient_id

   WHERE
      ekr.ekr_id=$ekr_id
      
   GROUP BY
      ekr.ekr_id
";
$result = end( sql_query_array( $db, $query ) );
$bez = $result[ 'nachname' ] . ", " . $result[ 'vorname' ] . " (" . $result[ 'geburtsdatum' ] . "), Meldung vom " . $result[ 'datum' ];

$query  = "SELECT * FROM exp_gekid_log WHERE export_id=$export_id AND ekr_id=$ekr_id;";
$result = end( sql_query_array( $db, $query ) );

$smarty->assign( 'result' , $result );
$smarty->assign( 'ekr', $bez );
$smarty->display( 'app/export_gekid_msg.tpl' );

exit;

?>