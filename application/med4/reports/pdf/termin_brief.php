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

$org     = end(sql_query_array($db, "SELECT * FROM org WHERE org_id='$org_id'"));

$patient = array();
$termin = array();

foreach ($export_id AS $each_export_id) {
   $patient_id = $each_export_id['patient_id'];
   $termin_id  = $each_export_id['termin_id'];

   list($patient[]) = sql_query_array($db,"
      SELECT
         *,
         titel AS titel_bez
      FROM patient
      WHERE patient_id = '$patient_id'
   ");


   list($termin[]) = sql_query_array($db, "
      SELECT
         t.*,
         l.bez AS art_bez
      FROM termin t
         LEFT JOIN l_basic l ON t.art=l.code   AND l.klasse='termin_art'
      WHERE t.termin_id = '$termin_id'
   ");
}

extract( $org, EXTR_PREFIX_ALL, 'org' );

$i       = 0;
$counter = 1;

if (count($termin) == 0) {
   $termin = array();
   $flds[] = array( 'page' => 1, 'counter' => $counter, 'x' =>  40, 'y' => 130, 'db_field' => utf8_encode($config['keine_termine']), 'memo' => true, 'width' => 450, 'height' => 10, 'size' =>  9 );
} else {
   foreach( $termin AS $each_termin)
   {
      $datum_de       = date('d.m.Y');
      $datum          = "Datum: {$datum_de}";

      $anrede         = $patient[$i]['geschlecht'] == 'm' ? 'Herr' : 'Frau';
      $termin_zeit    = strlen( $each_termin['uhrzeit'] ) ? ( 'um ' . $each_termin['uhrzeit'] . ' Uhr' ) : '';
      $termin_art_bez = strlen( $each_termin['art_bez'] ) ? ( '/ '  . $each_termin['art_bez']       ) : '';

      $datumBrief     = todate($each_termin['datum'], 'de');

      $betreff        = concat(array($config['termin_am'], $datumBrief), ' ');

      $patient_nachname = $patient[$i]['nachname'];
      $patient_vorname  = $patient[$i]['vorname'];
      $patient_titel_bez= $patient[$i]['titel_bez'];
      $patient_strasse  = $patient[$i]['strasse'];
      $patient_plz      = $patient[$i]['plz'];
      $patient_ort      = $patient[$i]['ort'];
      $patient_hausnummer = $patient[$i]['hausnr'];

      $absender = concat(array(
         $org_name,
         concat(array($org_strasse, $org_hausnr), ' '),
         concat(array($org_plz, $org_ort), ' '),
         $org_telefon
      ), ' ' . chr(149) . ' ');

      $empfaenger =
            "$anrede\n"
         .  trim("$patient_titel_bez $patient_vorname $patient_nachname")
         .  "\n$patient_strasse $patient_hausnummer\n"
         .  trim("$patient_plz $patient_ort");

      $brieftext  =
            "$betreff\n"
         .  "\n"
         .  "Sehr geehrte(r) $anrede $patient_nachname,\n"
         .  "\n"
         .  "Wir möchten Sie an Ihren nächsten Termin\n"
         .  "am " . concat(array($datumBrief,$termin_zeit,$termin_art_bez), ' ') . "\n"
         .  "erinnern.\n"
         .  "\n"
         .  "Mit freundlichen Grüßen\n"
         ;

      // Ausgabe
      $flds[] = array( 'page' => 1, 'counter' => $counter, 'x' =>  40, 'y' => 130, 'db_field' => utf8_encode($absender)   , 'memo' => true, 'width' => 450, 'height' => 10, 'size' =>  9 );
      $flds[] = array( 'page' => 1, 'counter' => $counter, 'x' =>  40, 'y' => 155, 'db_field' => utf8_encode($empfaenger) , 'memo' => true, 'width' => 300, 'height' => 15, 'size' => 14 );
      $flds[] = array( 'page' => 1, 'counter' => $counter, 'x' => 240, 'y' => 310, 'db_field' => utf8_encode($datum)      , 'memo' => true, 'width' => 300, 'height' => 15, 'size' => 14 , 'align' => 'R' );
      $flds[] = array( 'page' => 1, 'counter' => $counter, 'x' =>  40, 'y' => 400, 'db_field' => utf8_encode($brieftext)  , 'memo' => true, 'width' => 450, 'height' => 20, 'size' => 14 );

      $i++;
      $counter++;
   }
}

$pdf = new fpdi( 'p', 'pt', 'A4');
$pdf->SetAutoPageBreak( false );
$pdf->setPrintHeader(false);

$pdf->setSourceFile('reports/ressource/plain.pdf');

$last_page    = 0;
$last_counter = 0;

foreach( $flds AS $fld )
{
   // Required
   $page      = $fld['page'];
   $x         = $fld['x'];
   $y         = $fld['y'];

   $html_encode = get_html_translation_table(HTML_ENTITIES);
   foreach( $html_encode AS $k => $v )
      $html_decode[$v] = $k;
   $db_field = strip_tags( strtr( $fld['db_field'], $html_decode ) );

   // Optional
   $counter   = isset( $fld['counter'] )   ? $fld['counter']           : 0;
   $chk_param = isset( $fld['chk_param'] ) ? $fld['chk_param']         : '';
   $width     = isset( $fld['width'] )     ? $fld['width']             : 100;
   $height    = isset( $fld['height'] )    ? $fld['height']            : 10;
   $align     = isset( $fld['align'] )     ? strtoupper($fld['align']) : 'L';
   $font      = isset( $fld['font'] )      ? $fld['font']              : 'Helvetica';
   $font_size = isset( $fld['size'] )      ? $fld['size']              : 8;
   $function  = isset( $fld['function'] )  ? $fld['function']          : '';
   $memo      = isset( $fld['memo'] )      ? $fld['memo']              : '';
   $style     = isset( $fld['style'] )     ? $fld['style']             : '';
   $border    = isset( $fld['border'] )    ? $fld['border']            : 0;
   $arr_fill  = isset( $fld['fill'] )      ? $fld['fill']              : false;
   $fill      = is_array( $arr_fill )      ? true                      : false;

   if( strlen( $chk_param ) )
      $db_field = ( $db_field == $chk_param ) ? 'X' : '';

   if( $last_page != $page OR ( $last_counter != $counter AND $counter != 0 ) )
   {
      $tplidx = $pdf->ImportPage( $page );
      $pdf->addPage();

      if( isset( $rotation ) )
         $pdf->rotate( $rotation, 420, 410 );

      $pdf->useTemplate( $tplidx );

      if( isset( $rotation ) )
         $pdf->rotate( 0 );
   }


   $pdf->SetFont( $font, $style, $font_size );

   if($memo === true) {
      $memo = 'multi';
   }

   if( $fill == true )
   {
      $arr_fill['r'] = isset( $arr_fill['r'] ) ? $arr_fill['r'] : 255;
      $arr_fill['g'] = isset( $arr_fill['g'] ) ? $arr_fill['g'] : 255;
      $arr_fill['b'] = isset( $arr_fill['b'] ) ? $arr_fill['b'] : 255;

      $pdf->SetFillColor( $arr_fill['r'], $arr_fill['g'], $arr_fill['b'] );
   }

   switch( $memo )
   {
      case 'multi':
         $pdf->SetXY( $x, $y );
         $pdf->MultiCell( $width, $height, $db_field, $border, $align, $fill );
         $pdf->SetXY( 0, 0 );
      break;

      case 'single':
         while( $pdf->GetStringWidth( $db_field ) >= $width )
           $db_field = substr( $db_field, 0, -1 );

         $pdf->Text( $x, $y, $db_field );
      break;

      default:
         $pdf->Text( $x, $y, $db_field );
      break;
   }

   $last_page    = $page;
   $last_counter = $counter;

   if( $fill == true )
      $pdf->SetFillColor( 255, 255, 255 );
}

if (count($export_id) == 1) {
   $patientData = reset($patient);

   $fileName = strtolower(concat(array($patientData['nachname'], $patientData['vorname']), '_'));
} else {
   $fileName = 'patientenbriefe';
}


$pdf->Output("{$fileName}.pdf", 'D');

exit;

?>