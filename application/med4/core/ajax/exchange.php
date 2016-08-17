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

function load_template($db, $appOrder, $fileName, $config, $statusLock)
{
   $listDir      = 'dlist';
   $filePath     = null;
   $tmpl_array   = array();

   foreach ($appOrder as $app) {
      $folder = $app['folder'];

      $listFilePath   = "templates/$folder/$listDir/$fileName.php";

      if (file_exists($listFilePath) === true ) {

         $filePath = $listFilePath;
         break;
      }
   }

   //config required for dlist template
   if ($filePath !== null) {
      require_once($filePath);
   }

   echo create_json_string($tmpl_array);
}




function load_pos_sess ( $db, $table, $query, $postyp, $fields , $config, $vorlagen='', $list = array() )
{
   $index = 0;

   if(!array_key_exists('pos_table', $_SESSION)){
      $_SESSION['pos_table']           = array();
   }
   if(!array_key_exists($postyp, $_SESSION['pos_table'])){
      $_SESSION['pos_table'][$postyp]  = array();
   }

   //Nur für den Fall eines Updates, baue mir ein neues Array und legs mir dann da rein
   $update_check = get_primaer_key( $table );

   if(isset($_SESSION['pos_table'][$postyp])){
      foreach($_SESSION['pos_table'][$postyp] AS $i => $value){
         if($value[$update_check] == ''){
            $whole_array[$index]             = $value;
            $whole_array[$index]['sess_pos'] = $postyp.'_'.$index;
            ++$index;
         }
      }
   }

   //Löschen des vorhandenen Session Typ´s
   foreach($_SESSION['pos_table'][$postyp] AS $i => $content){
      unset($_SESSION['pos_table'][$postyp][$i]);
   }

   //zurück in die Session
   if(isset($whole_array)){
      foreach($whole_array AS $key => $value){
         $_SESSION['pos_table'][$postyp][$key] = $value;
      }
   }

   $available_posforms = strlen($query) > 0 ? sql_query_array($db, $query) : array();

   foreach($fields AS $feld => $wert){
      foreach($available_posforms AS $key => $value){
         if( $wert['type'] == 'date')
            todate(  $available_posforms[$key][$feld], 'de');
         if( $wert['type'] == 'float')
            tofloat( $available_posforms[$key][$feld], 'de');
         if( $wert['type'] == 'time')
            totime(  $available_posforms[$key][$feld], 'de');
      }
   }

   foreach( $available_posforms AS $key => $value ){
      $_SESSION['pos_table'][$postyp][$index]['sess_pos'] = $postyp.'_'.$index;

      foreach($value AS $pos_key => $pos_value){
         $_SESSION['pos_table'][$postyp][$index][$pos_key] = $pos_value;
      };

   ++$index;
   };

   $arr_ausgabe = create_legible_text($db, $postyp, $fields, $config, 0, $vorlagen, $list);

   if(count($arr_ausgabe) == 0)
      $arr_ausgabe['no_ajax_data'] = $config['no_ajax_dataset'];


   return $arr_ausgabe;
}


function insert_pos_sess ( $db, $typ, $action, $fields ,$config, $direct_insert = false)
{

   if($direct_insert == false){                                                                             #3#
      $pos_arr       = $_REQUEST;
      $missing_check = array_diff(array_keys($fields), array_keys($pos_arr)); //Checkboxen überprüfen

   }else{
      $pos_arr       = array();
      $missing_check = array();
      foreach($fields as $field => $field_content){
         if(!isset($field_content['value'])){
            $missing_check[] = $field;
         }else{
            $pos_arr[$field] = $field_content['value'][0];
         }
      }
   }

   //#1# Datumscheck
   foreach($fields as $fieldname => $field){
      if(isset($field['type']) && $field['type'] == 'date')
         $pos_arr[$fieldname] = isset($field['value'][0]) ? $field['value'][0] : $pos_arr[$fieldname];
   }

   foreach($missing_check AS $key => $zero){
      $pos_arr[$zero] = "";
   }

   foreach($pos_arr as $index => $value)
      $pos_arr[$index] = rawurldecode($value);




   $_SESSION['pos_table'][$typ][] = $pos_arr;
}


function insert_sess_db ( $smarty, $db, $fields, $table, $main_id, $postyp, $first_id2check, $sec_id2check, $skipStatus = true )
{
   //Von der SESSION alle filtern die keine ID haben
   if(isset($_SESSION['pos_table'])){
      if (array_key_exists($postyp, $_SESSION['pos_table']) === true) {
          foreach($_SESSION['pos_table'][$postyp] AS $sess_pos => $values){
             //Update, findet im Hauptformluar statt
             if(strlen($values[$first_id2check]) && strlen($values[$sec_id2check]) )   //Wenn eine eine ID vorhanden ist, also kein neu anlegen stattfindet
             continue;

             //Case insert - beides nicht vorhanden
             if(!strlen($values[$first_id2check]) && !strlen($values[$sec_id2check]) )
                   $values[$sec_id2check] = $main_id;                                   //ID vom Hauptformular =)

             if(!strlen($values[$first_id2check]) && strlen($values[$sec_id2check]) )
                   $values[$sec_id2check] = $main_id;

             intergate_request_into_fields($fields, $values);
             todate(  $fields, 'en' );
             tofloat( $fields, 'en' );
             totime(  $fields, 'en' );

             //Schreibe es in die Datenbank
             execute_insert( $smarty, $db, $fields, $table, 'insert', $skipStatus);
          }
      }
   }

   statusReportParam::fire($table, $main_id);
}


function edit_pos ( $smarty )
{
   $sess_typ  = substr($_REQUEST['sess_pos'], 0                                       , strpos($_REQUEST['sess_pos'], '_'));
   $sess_id   = substr($_REQUEST['sess_pos'], (strpos($_REQUEST['sess_pos'], '_') + 1), strlen($_REQUEST['sess_pos']));
   $_SESSION['pos_table'][$sess_typ][$sess_id]['sess_pos'] = $_REQUEST['sess_pos'];

   $smarty->assign('sess_pos', $_SESSION['pos_table'][$sess_typ][$sess_id]['sess_pos']);

   $arr_sess             = $_SESSION['pos_table'][$sess_typ][$sess_id];
   $arr_sess['sess_typ'] = $sess_typ;
   $arr_sess['sess_id']  = $sess_id;

   if(!isset($_REQUEST['action']))
      $_REQUEST = $arr_sess;

   return $arr_sess;
}


function delete_pos ( $smarty, $db, $fields, $table, $typ, $id, $action )
{
    $to_delete = $_SESSION['pos_table'][$typ][$id];
    $key       = get_primaer_key( $table );

    if  (strlen($to_delete[$key])){
        $value = $to_delete[$key];
        unset($_SESSION['pos_table'][$typ][$id]);
        action_delete( $smarty, $db, $fields, $table, $value, $action );
    } else {
        unset($_SESSION['pos_table'][$typ][$id]);
    }

    return $to_delete;
}


function update_pos_sess ( $fields, $postyp, $index )
{
   foreach( $fields AS $key => $value ){
      $_SESSION['pos_table'][$postyp][$index][$key] = $value['value'][0];
   }
}

function create_legible_text ( $db, $postyp, $fields, $config, $content_req = 0, $db_tables_lookup, $list )
{
   $sess_content  = $_SESSION['pos_table'][$postyp];
   $arr_ausgabe   = array();

   if(isset($db_tables_lookup) && is_array($db_tables_lookup))
      extract($db_tables_lookup);

   //Einzelne Blöcke innerhalb der Session (pos_lokal, pos_meta....)
   foreach( $sess_content AS $index => $value ){
      //Jeden Block einzeln durchlaufen und mit den Fields abgleichen ($key = name, $content = derzeitiger Inhalt !!!!!)
      $fix_methode = '';

      foreach($value AS $key => $content){

         if(array_key_exists($key, $fields)){
            if(is_array($fields[$key]['ext'])){
               $key_s   = array_keys($fields[$key]['ext']);
               $value_s = array_values($fields[$key]['ext']);
               $key_s   = $key_s[0];
               $value_s = $value_s[0];
               $bez     = '';

               switch( $fields[$key]['type'] )
               {
                  case 'lookup':
                     if(strlen($content)){

                        $value_s = get_dyn_lookup_class($value_s, $list, $postyp, $key, $value);

                        $bez = dlookup($db, $key_s, "bez", 'klasse = "'.$value_s.'" AND code = "'.$content.'"'  );
                     }
                  break;

                  case 'code_o3':
                  case 'code_ops':
                  case 'code_icd':
                     if(strlen($content)){
                        $bez = $content;
                     }
                  break;

                  default:
                     if(strlen($content))
                        $bez = $fields[$key]['ext'][$content];
                  break;
               }

               $arr_ausgabe[$index][$key] = $bez;
            }else{

               switch( $fields[$key]['type'] ){

                  case 'check':
                     switch($content){
                        case '1': $arr_ausgabe[$index][$key] = $config['ja'];    break;
                        default : $arr_ausgabe[$index][$key] = $config['nein'];  break;
                     };
                     break;

                  case 'query':

                     if(strlen($content)){

                        $query = $fields[$key]['ext'];
                        $result = sql_query_array($db, $query);

                        foreach( $result as $indi => $subresult )
                           foreach( $subresult as $i => $v )
                              if( $v == $content ){
                                 unset( $result[$indi][$i] );

                                 $arr_ausgabe[$index][$key] = implode(', ' , $result[$indi]);

                                 break 2;
                              }
                     }else{
                        $arr_ausgabe[$index][$key] = '';
                     }

                     break;

                  default:
                     if(!strlen($content))
                        $content = '';
                     $arr_ausgabe[$index][$key] = $content;
                     break;
               }
            };
         }elseif($content_req == 1){
            $arr_ausgabe[$index][$key] = $content;
         };
         $arr_ausgabe[$index]['sess_pos'] = $postyp.'_'.$index;
      }
   }

   return $arr_ausgabe;
}


function create_json_string( $a, $doubleTick = false)
{
   if (is_null($a)) return 'null';
   if ($a === false) return 'false';
   if ($a === true) return 'true';

   if (is_scalar($a)) {
       $a = addslashes($a);
       $a = str_replace("\n", '\n', $a);
       $a = str_replace("\r", '\r', $a);
       $a = preg_replace('{(</)(script)}i', "$1'+'$2", $a);

       $tick = $doubleTick === false ? "'" : '"';

       return $tick . escape($a, ENT_NOQUOTES) . $tick;
   }
   $isList = true;
   for ($i=0, reset($a); $i<count($a); $i++, next($a))
       if (key($a) !== $i) { $isList = false; break; }
   $result = array();
   if ($isList) {
       foreach ($a as $v) $result[] = create_json_string($v, $doubleTick);
       return '[ ' . join(', ', $result) . ' ]';
   } else {
       foreach ($a as $k=>$v)
           $result[] = create_json_string($k, $doubleTick) . ':' . create_json_string($v, $doubleTick);
       return '{' . join(',', $result) . '}';
   }
}


/** -------------------------------------------------------------------------------------------
 ** Speichert alle Werte aus dem Formular in Fields ($_REQUEST)
 **/
function intergate_request_into_fields ( &$fields , $values )
{
   // Attribut $fields['FELD']['value'] löschen, wenn vorhanden
	foreach( $fields AS $k => $f )
	{
	   $value = isset( $values[$k] ) ? $values[$k] : '';

      if( isset( $fields[$k]['value'] ) )
	 	   unset( $fields[$k]['value'] );

	   $fields[$k]["value"][] = trim( $value );
	}
}

function get_dyn_lookup_class($value, $list, $postyp, $field, $dataset)
{
   foreach($list as $key => $values) {
      if($values['name'] == $postyp && isset($values['dyn_lookup'][$field])) {

         $switch = $values['dyn_lookup'][$field];
         $ref    = $dataset[$switch['reference']];

         foreach($switch['cases'] as $class => $cases){
            if(in_array($ref, $cases) == true){
               return $class;
            }
         }
      }
   }

   return $value;
}

?>
