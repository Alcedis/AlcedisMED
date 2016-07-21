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

/** -------------------------------------------------------------------------------------------
 ** Array in gut lesbarer Ansicht ausgeben
 **/

function print_arr ($var, $arrayOfObjectsToHide=null, $fontSize=11)
{
    echo "<div style='text-align:left;'>";
    $text = print_r($var, true);

    if (is_array($arrayOfObjectsToHide)) {

        foreach ($arrayOfObjectsToHide as $objectName) {

            $searchPattern = '#('.$objectName.' Object\n(\s+)\().*?\n\2\)\n#s';
            $replace = "$1<span style=\"color: #FF9900;\">--&gt; HIDDEN - courtesy of wtf() &lt;--</span>)";
            $text = preg_replace($searchPattern, $replace, $text);
        }
    }

    // color code objects
    $text = preg_replace('#(\w+)(\s+Object\s+\()#s', '<span style="color: #079700;">$1</span>$2', $text);
    // color code object properties
    $text = preg_replace('#\[(\w+)\:(public|private|protected)\]#', '[<span style="color: #000099;">$1</span>:<span style="color: #009999;">$2</span>]', $text);

    echo '<pre style="font-size: '.$fontSize.'px; line-height: '.$fontSize.'px;">'.$text.'</pre>';
    echo "</div>";
}



/** -------------------------------------------------------------------------------------------
 ** Funktion holt Start Page aus Matrix Settings
 **/

function get_start_page( &$matrix_arr )
{
   if( !is_array( $matrix_arr ) )
      return;

   $rechte_string = '';
   $rolle_key     = 'zugriff';
   $separator     = '&';
   $start_page    = '';

   foreach( $matrix_arr AS $k => $recht )
   {
      if (isset($recht[$rolle_key]) === true) {
         if( strlen( $recht[$rolle_key] ) )
         {
            // Rechte f?r Rolle Detail sind gesetzt
            $recht_rolle_arr = explode( $separator, $recht[$rolle_key] );

            if( count( $recht_rolle_arr ) > 1 )
            {
               // Auch Start Page wurde gesetzt
               $rechte_string = $recht[$rolle_key];
            }
            else
            {
               // Start Page wurde nicht in Rechte Detail gesetzt
               if( count( explode( $separator, $recht['standard'] ) ) > 1 )
               {
                  // Rechte Standard sind gesetzt
                  $recht_standard_arr = explode( $separator, $recht['standard'] );

                  if( count( $recht_standard_arr ) > 1 )
                  {
                     // Startpage ist in Rechet Standard gesetzt also an Rechte Detail anh?ngen
                     $rechte_string = $recht_rolle_arr[0] . $separator . $recht_standard_arr[1];
                  }
               }
               else
               {
                  // Keine Startpage gesetzt, dann einfach Rechte setzen ohne Start Page
                  $rechte_string = strlen( $recht_rolle_arr[0] ) ? $recht_rolle_arr[0] : $recht['standard'];
               }
            }
         }
         else
         {
            // Keine Rechte Detail angelegt, also Standard nehmen
            $rechte_string   = $recht['standard'];
         }

         $matrix_arr[$k][$rolle_key] = $rechte_string;
         unset( $matrix_arr[$k]['standard'] );

         if( strpos( $rechte_string, $separator ) === false )
            continue;

         $exploded_rechte_string     = explode( $separator, $rechte_string );
         $start_page                 = ( isset( $exploded_rechte_string[1] ) ) ? trim( $exploded_rechte_string[1] ) : '' ;
         $matrix_arr[$k][$rolle_key] = $exploded_rechte_string[0];
      }

   }

   return $start_page;
}


/** -------------------------------------------------------------------------------------------
 ** Funktion, die anhand der Matrix Settings und anhand von POS Daten Buttons setzt
 **/

function get_buttons_pos( $db, $table, $id, $arr_pos )
{
   $modus     = get_buttons( $table, $id );
   $arr_modus = explode( '.', $modus );

   $count = 0;
   foreach( $arr_pos AS $table => $where )
      $count += dlookup( $db, $table, "COUNT(*)", $where );

   if( $count == 0 )
      return $modus;

   foreach( $arr_modus AS $key => $value )
   {
      if( $value == 'delete' )
         unset( $arr_modus[$key] );
   }

   return implode( '.', $arr_modus );
}


/** -------------------------------------------------------------------------------------------
 ** Funktion, die Ziel url z?ruckliefert
 **/

function get_url($url = 'page=login')
{
   return "index.php?{$url}";
}


/** -------------------------------------------------------------------------------------------
 ** Funktion, die die Section f?r die Config und den Typ der Ansicht (list oder rec) in einem
 ** Array zur?ckgibt
 **/

function get_arr_section($page)
{
   if (strpos($page, 'list') === 0 || strpos($page, 'rec') === 0 || strpos($page, 'view') === 0) {
      $arr = explode('.', $page);
      $arr_return['file'] = $arr[1];
      $arr_return['type'] = $arr[0];
   } else {
      $arr_return['file'] = $page;
      $arr_return['type'] = '';
   }

   return $arr_return;
}



/** -------------------------------------------------------------------------------------------
 ** Setzt in der Abschnittauswahl, entsprechend der Mitgegebenen Parameter,
 ** eine Checkbox oder macht einen count auf die Tabelle. Wird ben?tzt in Vorlagen und Abschnitt
 **/

function make_abschnitt( &$smarty, &$db, $abschnitte, $where = '1', $abschnitt_name='abschnitte' )
{
    foreach ($abschnitte as $key => $abschnitt) {
	$tmp_where = isset( $abschnitt['where'] ) ? $abschnitt['where'] : $where;

        $abschnitte[$key]['box'] = $abschnitt['type'] == 'count'
            ? dlookup($db, $abschnitt['table'], "COUNT(*)", $tmp_where)
            : $abschnitte[$key]['box']
        ;
   }

   $smarty->assign($abschnitt_name, $abschnitte);
}



/** -------------------------------------------------------------------------------------------
 ** Einfacher tausch von Variablen, nur zur besseren Code lesbarkeit
 **/
function swap( &$a, &$b )
{
	$temp	= $a;
	$a 	= $b;
	$b 	= $temp;
}


/** -------------------------------------------------------------------------------------------
 ** Datumsdifferenz
 **/

function date_diff_raw( $date1, $date2 )
{
	 $s = strtotime($date2) - strtotime($date1);
	 $h = intval($s/3600);
	 $s -= $h*3600;
	 $m = intval($s/60);
	 $s -= $m*60;
	 return array('h'=>$h,'m'=>$m,'s'=>$s);
}

/** -------------------------------------------------------------------------------------------
 ** Datumsdifferenz in Tagen
 **/

function date_diff_days( $date1, $date2 )
{
     return ceil((strtotime($date2) - strtotime($date1))/ 86400);
}


/** -------------------------------------------------------------------------------------------
 ** Bestimmt die Datumsdifferenz in Monaten
 ** Datumsformat kann Deutsch, Englisch oder Timestamp sein
 **/

function date_diff_month( $start, $end )
{
	$arr1 = explode(' ', $end);
	$arr2 = explode(' ', $start);
	if( strpos($arr1[0], '.') )
	{
		$tmp1 = explode('.',$arr1[0]);
		swap($tmp1[0], $tmp1[2]);
	}
	else
		$tmp1	= explode('-',$arr1[0]);

	if(!checkdate( (int) $tmp1[1], (int) $tmp1[2], (int) $tmp1[0]))
		return '';

	if( strpos($arr2[0], '.') )
	{
		$tmp2	= explode('.',$arr2[0]);
		swap($tmp2[0], $tmp2[2]);
	}
	else
		$tmp2	= explode('-',$arr2[0]);

	if( !checkdate( (int) $tmp2[1], (int) $tmp2[2], (int) $tmp2[0]) )
		return '';

	$tmp1[0] = $tmp1[0] - $tmp2[0];
	if( $tmp1[0]>0 )
		$tmp1[1]=$tmp1[1] + ($tmp1[0]*12);
	$tmp1[1] = $tmp1[1] - $tmp2[1];
	$tmp1[2] = $tmp1[2] - $tmp2[2];
	if( $tmp1[2]<0 )
		$tmp1[1]=$tmp1[1]-1;
	return $tmp1[1];
}


/** -------------------------------------------------------------------------------------------
 ** Bestimmt die Datumsdifferenz in Jahren
 ** Datumsformat kann Deutsch, Englisch oder Timestamp sein
 **/

function date_diff_years( $start, $end )
{
	$arr1 = explode(' ', $end);
	$arr2 = explode(' ', $start);
	if( strpos($arr1[0], '.') )
	{
		$tmp1	= explode('.',$arr1[0]);
		swap($tmp1[0], $tmp1[2]);
	}
	else
		$tmp1	= explode('-',$arr1[0]);

	if( !checkdate( (int) $tmp1[1], (int) $tmp1[2], (int) $tmp1[0]) )
		return '';

	if( strpos($arr2[0], '.') )
	{
		$tmp2	= explode('.',$arr2[0]);
		swap($tmp2[0], $tmp2[2]);
	}
	else
		$tmp2	= explode('-',$arr2[0]);

	if(!checkdate( (int) $tmp2[1], (int) $tmp2[2], (int) $tmp2[0]))
		return '';

	$tmp1[2] = $tmp1[2] - $tmp2[2];
	$tmp1[1] = $tmp1[1] - $tmp2[1];
	$tmp1[0] = $tmp1[0] - $tmp2[0];
	if($tmp1[2]<0)
		$tmp1[1]=$tmp1[1]-1;
	if($tmp1[1]<0)
		$tmp1[0]=$tmp1[0]-1;
	return $tmp1[0];
}

/** -------------------------------------------------------------------------------------------
 ** Bestimmt die Werktage, die zwischen zwei Datumsangaben liegen
 ** Erwartetes Datum: englisch oder timestamt
 **/


function getWorkdays($start,$end,$holidays = array()){

   $tage_gesamt = array();

   if(strpos($start,'-') !== false )
      $start = strtotime($start);

   if(strpos($end,'-') !== false )
      $end = strtotime($end);

   while( $start < $end ){
      $tage_gesamt[] = date('Y-m-d',$start);
      $start += 86400;
   }

   $workdays = 0;

   foreach( $tage_gesamt as $pwday){

      if( in_array(date('w',strtotime($pwday)), array(6,0)))
         continue;

      if( in_array(date('Y-m-d',strtotime($pwday)), $holidays) )
         continue;

      $workdays++;
   }

   return $workdays;
}


# DGR: 27.07.2006 - neue (?berarbeitete) Funktion (kann mit d.m.yyyy umgehen, braucht nich dd.mm.yyyy)
function todate( &$fields, $target )
{
   // wenn einzelnes Datum
   if( !is_array( $fields ) )
   {
      $fields = format_date( $fields, $target );
      return $fields;
   }

   // wenn komplette Fields
   foreach( $fields AS $name => $field )
   {
      if( $field['type'] != 'date' OR !isset( $field['value'] ) )
         continue;

      foreach( $field['value'] AS $key => $date )
         $fields[$name]['value'][$key] = format_date( $date, $target );
   }

   return;
}

function format_date( $date, $target )
{
   if( !strlen( $date ) )
      return;

   $target     = strtolower( $target );
	$arr_format = array( 'de' => array( 'from' => '-', 'to' => '.' ),
                        'en' => array( 'from' => '.', 'to' => '-' ),
                      );

	// Falls Date-Time dann Zeit und Datum trennen
   $arr_tmp    = explode( ' ', $date );
   $date       = isset( $arr_tmp[0] ) ? $arr_tmp[0] : '';
   $time       = isset( $arr_tmp[1] ) ? $arr_tmp[1] : '';

	if( !isset( $arr_format[$target] ) )
	   die( 'toDate: Wrong Locale:' . $target );

   $from = $arr_format[$target]['from'];
   $to   = $arr_format[$target]['to'];

   // Aus Datum ein Array machen( ?ber Seperator '.' oder '-' )
   $arr_date = explode( $from, $date );

   // Wenn falsches Datum, ab hier die Funktion verlassen
   if( count( $arr_date ) != 3 )
      return ( strlen( $time ) ) ? $date. ' ' . $time : $date;

   $date_new = $date;
   switch( $target )
   {
      case 'de':
         $DD       = ( strlen( $arr_date[2] ) == 1 ) ? '0'.$arr_date[2] : $arr_date[2];
         $MM       = ( strlen( $arr_date[1] ) == 1 ) ? '0'.$arr_date[1] : $arr_date[1];
         $YYYY     = $arr_date[0];

         $date_new = $DD . $to . $MM . $to . $YYYY;
      break;
      case 'en':
         $DD       = ( strlen( $arr_date[0] ) == 1 ) ? '0'.$arr_date[0] : $arr_date[0];
         $MM       = ( strlen( $arr_date[1] ) == 1 ) ? '0'.$arr_date[1] : $arr_date[1];
         $YYYY     = $arr_date[2];

         $date_new = $YYYY . $to . $MM . $to . $DD;
      break;
   }

   // Wenn Datum umgewandelt und g?ltig, dann Funktion verlassen
   if( checkdate( (int) $MM, (int) $DD, (int) $YYYY ) )
      $return = ( strlen( $time ) ) ? $date_new. ' ' . $time : $date_new;
   else
      $return = ( strlen( $time ) ) ? $date. ' ' . $time : $date;

   return $return;

}


/** -------------------------------------------------------------------------------------------
 ** Zeit Angaben umwandlung
 **/
function totime( &$fields, $target )
{
   if( !strlen( $target ) )
      return;

   $transform['en'] = array( '15' => '.25', '30' => '.5',  '45' => '.75' );
   $transform['de'] = array( '25' => ':15', '5'  => ':30', '75' => ':45' );

   if( !isset( $transform[$target] ) )
      return;

   if( !is_array( $fields ) )
   {
      $time = $fields;

      if( ( strpos( $time, ':' ) !== false ) AND $target == 'en' )
      {
         $arr_time    = explode( ':', $time );
         $transformed = isset( $transform[$target][$arr_time[1]] ) ? $transform[$target][$arr_time[1]] : '';
         $time        = $arr_time[0] . $transformed;
         if( $time[0] == 0 )
            $time[0] = '';
         $fields = trim( $time );
      }

      if( $target == 'de' )
      {
         $arr_time    = explode( '.', $time );
         $arr_time[0] = isset( $arr_time[0] ) ? trim( $arr_time[0] ) : '';
         $arr_time[1] = isset( $arr_time[1] ) ? trim( $arr_time[1] ) : '';
         $transform[$target][$arr_time[1]] = isset( $transform[$target][$arr_time[1]] ) ? $transform[$target][$arr_time[1]] : '';

         while( ( strlen( $arr_time[0] ) < 2 ) AND ( strlen( $arr_time[0] ) ) )
            $arr_time[0] = '0' . $arr_time[0];

         if( !strlen( $transform[$target][$arr_time[1]] ) AND strlen( $arr_time[0] ) )
            $transform[$target][$arr_time[1]] = ':00';

         $time = $arr_time[0] . $transform[$target][$arr_time[1]];
         $fields = trim( $time );
      }

      return;
   }

   foreach( $fields AS $name => $field )
   {
      $type   = $field['type'];
      $picker = isset( $field['ext']  ) ? $field['ext']  : false;

      if( $type != 'time' OR $picker != true OR !isset( $field['value'] ) )
         continue;

      $count = count( $field['value'] );
      for( $i = 0; $i < $count; $i++ )
      {
         $time = $field['value'][$i];

         if( ( strpos( $time, ':' ) !== false ) AND $target == 'en' )
         {
            $arr_time    = explode( ':', $time );
            $transformed = isset( $transform[$target][$arr_time[1]] ) ? $transform[$target][$arr_time[1]] : '';
            $time        = $arr_time[0] . $transformed;
            if( $time[0] == 0 )
               $time[0] = '';
            $fields[$name]['value'][$i] = trim( $time );
         }

         if( $target == 'de' )
         {
            $arr_time    = explode( '.', $time );
            $arr_time[0] = isset( $arr_time[0] ) ? trim( $arr_time[0] ) : '';
            $arr_time[1] = isset( $arr_time[1] ) ? trim( $arr_time[1] ) : '';
            $transform[$target][$arr_time[1]] = isset( $transform[$target][$arr_time[1]] ) ? $transform[$target][$arr_time[1]] : '';

            while( ( strlen( $arr_time[0] ) < 2 ) AND ( strlen( $arr_time[0] ) ) )
               $arr_time[0] = '0' . $arr_time[0];

            if( !strlen( $transform[$target][$arr_time[1]] ) AND strlen( $arr_time[0] ) )
               $transform[$target][$arr_time[1]] = ':00';

            $time = $arr_time[0] . $transform[$target][$arr_time[1]];
            $fields[$name]['value'][$i] = trim( $time );
         }
      }
   }
   return;
}

/** -------------------------------------------------------------------------------------------
 ** Zahlen umwandlung ( Namen ?nderen in convert_..... )
 **/
function tofloat( &$fields, $target )
{
	if( strtolower($target) == 'de' )
	{
		$source_seperator = '.';
		$target_seperator = ',';
	}
	elseif( strtolower($target)=='en' )
	{
		$source_seperator = ',';
		$target_seperator = '.';
	}
	else
		user_error( 'Wrong Locale, choose the Locale "de" or "en"',	E_USER_ERROR );

	if( is_array($fields) )
	{
		reset($fields);
		foreach( $fields as $name=>$field )
		{
         if ( ($field['type'] == 'float') AND (isset($field['value'])) )
	      {
	         $anzahl =  count($field['value']);
		      for( $i=0; $i<$anzahl; $i++ )
		   	   $fields[$name]['value'][$i] = str_replace($source_seperator, $target_seperator,$fields[$name]['value'][$i]);
		   }
		} //endforeach
	}
	else
		return str_replace($source_seperator, $target_seperator, $fields);
}


/** -------------------------------------------------------------------------------------------
 ** Diese Funktion generiert einen Benutzernamen (oder verl?ngert einen bestehenden)
 **/
function generate_loginname($value, $field='loginname', $length=7)
{
	$value_length   = strlen( $value );
	$signs          = '0123456789';
	$signs_length   = strlen( $signs )-1;
	mt_srand( ( double ) microtime() * 1000000);

	if( $value_length <= $length )
	{
		for( $i = $value_length; $i < $length; $i++ )
		{
			$pos    = mt_rand( 1, $signs_length );
			$value .= substr( $signs, $pos, 1 );
		}
	}
	return $value;
}


/** -------------------------------------------------------------------------------------------
 ** Diese Funktion generiert ein 6-8 Stelliges Passwort
 **/

function generate_password()
{
	$signs         = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$signs_length  = strlen( $signs )-1;
	$pwd_length    = mt_rand( 6, 8 );
	$value         = '';
	mt_srand( ( double ) microtime() * 1000000 );

	for( $i=1; $i <= $pwd_length; $i++ )
	{
		$pos    = mt_rand( 1, $signs_length );
		$value .= substr( $signs, $pos, 1 );
	}
	return $value;
}


/** -------------------------------------------------------------------------------------------
 ** Hilfsfunktion - Holt nur die Config Werte die in unter einer Section aufgef?hrt sind
 ** und wirft alle global definierten Werte aus dem Config Array heraus
 **/
                                // ACHTUNG Nicht &$smarty! In diesem Fall soll eine Kopie des Objekts mitrgegeben werden!
function get_config_section_only( $smarty, $file_config, $section_config)
{
   // File existiert, siehe ob section (block) auch vorhanden ist?
   $smarty->clear_config();
   $smarty->config_load( $file_config );	      // laden des Config Files
   $arr_global	= $smarty->get_config_vars();		// Globale Config Variablen in Array legen

   $smarty->config_load( $file_config, $section_config ); // Globale Werte und Section Werte in EIN Array legen
   $arr_global_and_section	= $smarty->get_config_vars();	 // Config Variablen in variable

   $arr_section_only = array_diff_assoc( $arr_global_and_section, $arr_global );

   return $arr_section_only;
}


/** -------------------------------------------------------------------------------------------
 ** Hier kann das aktuelle Alter aus einem Geburtsdatum errechnet werden
 **/

function calc_age( $birthdate='', $format='en' )
{
   if( !strlen($birthdate) )
      return;

   // Aktuelles Datum
   $cur_year  = (int)date('Y');
   $cur_month = (int)date('m');
   $cur_day   = (int)date('d');

   // Geburtsdatum
   if( $format == 'en' )
   {
      $arr_date    = explode( '-', $birthdate );
      $birth_year  = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $birth_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $birth_day   = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';
   }
   elseif( $format == 'de' )
   {
      $arr_date    = explode( '.', $birthdate );
      $birth_day   = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $birth_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $birth_year  = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';
   }
   else
       return "Parameter2: 'de' oder 'en'";

   // Pr?fen ob der Geburtstag dieses Jahr schon vorbei ist
   if( $cur_month > $birth_month OR ($birth_month == $cur_month AND $cur_day >= $birth_day) )
      $age = $cur_year - $birth_year;
   else // Geburtstag hat dieses Jahr noch nicht statt gefunden, deshalb Minus 1
      $age = $cur_year - $birth_year - 1;

   return $age;
}


/** -------------------------------------------------------------------------------------------
 ** Hier kann das Alter aus einem Geburtsdatum an einem bestimmten Zeitpunkt errechnet werden
 **/

function calc_age_on_event( $birthdate='', $eventdate='', $format='en' )
{
   if( !strlen($birthdate) OR !strlen($eventdate) )
      return;

   if( $format == 'en' )
   {
      $arr_date    = explode( '-', $birthdate );
      $birth_year  = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $birth_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $birth_day   = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';

      $arr_date    = explode( '-', $eventdate );
      $event_year  = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $event_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $event_day   = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';
   }
   elseif( $format == 'de' )
   {
      $arr_date    = explode( '.', $birthdate );
      $birth_day   = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $birth_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $birth_year  = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';

      $arr_date    = explode( '.', $eventdate );
      $event_day   = (int) isset( $arr_date[0] ) ? $arr_date[0] : '';
      $event_month = (int) isset( $arr_date[1] ) ? $arr_date[1] : '';
      $event_year  = (int) isset( $arr_date[2] ) ? $arr_date[2] : '';
   }

   // Pr?fen ob der Geburtstag bei Event schon vorbei ist
   if( $event_month > $birth_month OR ($birth_month == $event_month AND $event_day >= $birth_day) )
   {
      $age = $event_year - $birth_year;
   }
   // Geburtstag hat bei Event noch nicht statt gefunden, deshalb Minus 1
   else
   {
      $age = $event_year - $birth_year - 1;
   }

   return $age;
}


/* -------------------------------------------------------------------------------------------
 * Diese Funktion k?mmert sich um den File-Upload aus Formularen.
 * Es wird das http_upload-Objekt aus PEAR verwendet!
 *
 * @param     object    $smarty
 * @param     array     $fields
 * @param     string    $fieldname
 * @param     string    $valid_extensions (Semikolon getrennt)
 * @param     int       $max_filesize (in Byte)
 * @param     string    $name
 * @return    string    $message
 *
 * Beispiel:
 * function ext_err( $valid )
 * {
 *    $msg_feldname = upload_file( $valid->_smarty, &$valid->_fields, 'feldname', 'pdf;jpg;gif', 500000 );
 *    if($msg_feldname)
 *       $valid->set_msg('err', 10, array('feldname'), $msg_feldname);
 * }
 */

function upload_file( $smarty, &$fields, $fieldname, $req = 0, $valid_extensions='', $max_filesize=1000000, $name='real' )
{
   require_once( DIR_LIB . '/pear/http_upload.php' );

   // Einstellungen aus Config
   $smarty->config_load( FILE_CONFIG_DEFAULT, 'upload' );
   $smarty->config_load( FILE_CONFIG_SERVER, 'upload' );
   $config = $smarty->get_config_vars();

   // Wenn Feld gar kein input type="file", hier ende
   if( !isset($_FILES[$fieldname]) )
      return $config['no_file'];


   $avail_lang = array('de', 'en', 'fr', 'es', 'it');
   $lang = strlen($_SESSION['sess_sprache']) ? $_SESSION['sess_sprache'] : 'de';
   $lang = in_array($lang, $avail_lang) ? $lang : 'en';


   // Upload initialisieren
   $upload = new http_upload($lang);

   // Datei holen
   $file = $upload->getFiles($fieldname);

   // Wenn keine Datei mitgegeben, hier ende
   if( $file->isMissing() )
      return ($req == 1) ? $config['no_file'] : 0;

   // Wenn definiert, nur definierte Dateiendungen zulassen
   if( strlen($valid_extensions) )
   {
      $arr_valid_extensions = explode( ';', $valid_extensions );
      $file->setValidExtensions( $arr_valid_extensions , 'accept' );
   }

   // Fehler abfangen
   if( PEAR::isError($file) )
	  return $file->getMessage();
   elseif( $file->isError() OR !$file->isValid() )
      return $file->errorMsg();
   elseif( $file->getProp('size') >= $max_filesize )
       return $config['max_file_size'];

   // Dateiname setzen, wenn nicht explizit mitgegeben,
   // dann Original Dateiname bibehalten (Flag: real)
   $file->setName( $name );

   // Pfad des Upload Verzeichnises setzt sich zusammen aus
   // Basis-Upload-Verzeichnis + Name des Moduls
   $upload_base_dir = $config['upload_dir'];
   $modul           = $_SESSION['sess_modul'];
   $upload_dir      = $upload_base_dir . $modul . '/';
   // Falls nicht vorhanden, Unterverzeichnis f?r Modul erstellen
   if( !is_dir($upload_dir) )
   {
      umask( 0002 );
      $dir_created = mkdir($upload_dir);
      if( !$dir_created )
         return $config['dir_not_created'];
   }

   // Datei nach Upload-Verzeichnis verschieben
   $package = $file->moveTo($upload_dir);

   // Nochmal Fehler abfangen
   if( PEAR::isError($package) )
      return $package->getMessage();

   // Dateiname in Fields legen
   $fields[$fieldname]['value'][0] = $file->getProp('name');

   return 0;
}


/**
 * Berechnet den Body-Ma?-Index (BMI)
 *
 * @param     int       $gewicht
 * @param     int       $groesse
 * @param     bool      $silent
 * @return    string    $bmi
 */
function calc_bmi( $gewicht = 0, $groesse = 0, $silent=false )
{
	if( $gewicht < 1 OR $groesse < 1 )
		return $silent ? '' : '- - -';

   $groesse = $groesse / 100;
   $groesse = pow( $groesse, 2 );
	$bmi     = round( ( $gewicht / $groesse ), 1 );

   return str_replace( '.', ',', $bmi );
}


/**
 * Berechnet die K?rperoberfl?che (KOF)
 *
 * @param     int       $gewicht
 * @param     int       $groesse
 * @param     bool      $silent
 * @return    string    $kof
 */
function calc_kof( $gewicht = 0, $groesse = 0, $silent=false )
{
	if( $gewicht < 1 OR $groesse < 1 )
		return $silent ? '' : '- - -';

   $gewicht = pow( $gewicht, 0.425 );
   $groesse = pow( $groesse, 0.725 );
   $kof     = round( ( $groesse * $gewicht / 139.315), 2 );

   return str_replace( '.', ',', $kof );
}

/**
 * ## Kreatinin-Clearance Berechnung ##
 *
 * Formel:
 * ( (140 ? Alter) x K?rpergewicht (kg) x Fg ) / ( 72 x Serum-Kreatinin (mg/dl) )
 * Fg: M?nner=1, Frauen=0,85
 *
 * Umrechnung der Einheiten:
 * 1 mg/dl = 0,0884 mmol/l  -  Faktor: 11,31
 * 1 mg/dl = 88,4   ?mol/l  -  Faktor: 0,0113
 *
 *
 *
 * @param int $alter
 * @param float $gewicht
 * @param string $geschlecht
 * @param float $kreatinin
 * @param int $einheit
 * @return float / false
 */
function calc_kc($alter, $gewicht, $geschlecht, $kreatinin, $einheit)
{


   if( !strlen($gewicht) )
      return false;

   // Komma durch Punkt ersetzen
   tofloat($gewicht,'en');

   if($geschlecht =='M') $fg = 1;
   else $fg = 0.85;

   // Z?hler der Formel berechnen
   $zaehler = (140 - $alter) * $gewicht * $fg;

   // Komma durch Punkt ersetzen
   tofloat($kreatinin,'en');

   // Umrechnung des Wertes in 'mg/dl'
   switch($einheit)
   {
      // Wenn Einheit 'mmol/l'
      case '9':   $kreatinin = $kreatinin / 10;      break;
      // Wenn Einheit 'mmol/l'
      case '13':   $kreatinin = $kreatinin * 11.31;      break;
      // Wenn Einheit '?mol/l'
      case '14':   $kreatinin = $kreatinin * 0.0113;     break;
   }

   // Nenner der Formel berechnen
   $nenner = round(72 * $kreatinin, 2);

   // Wenn Nenner gr??er Null Ergebnis berechnen, ansonsten mit Meldung belegen
   if($nenner > 0)
   {
      // Ergebnis berechnen in dem Z?hler durch Nenner dividiert wird
      $ergebnis = round($zaehler / $nenner,  2);

      // Punkt f?r Anzeige wieder durch Punkt ersetzen
      return tofloat($ergebnis,'de');
   }
   else
      return false;

}

?>
