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

switch( $action )
{
   case 'active':

      $location   = get_url("page=rec.vorlage_fragebogen&vorlage_fragebogen_id={$form_id}");

      if (isset($_REQUEST['inaktiv']) == false) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_fragebogen WHERE vorlage_fragebogen_id = '$form_id'"));
         unset($dataset['inaktiv']);

         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_fragebogen_id = '$form_id'", 'update');

         action_cancel($location);
      } else {
         action_cancel($location);
      }

      break;

   case 'inactive':

      $location   = get_url("page=rec.vorlage_fragebogen&vorlage_fragebogen_id={$form_id}");

      if (isset($_REQUEST['inaktiv']) == true) {
         $dataset = end(sql_query_array($db, "SELECT * FROM vorlage_fragebogen WHERE vorlage_fragebogen_id = '$form_id'"));
         $dataset['inaktiv'] = 1;

         $location   = get_url("page=rec.vorlage_fragebogen&vorlage_fragebogen_id={$form_id}");
         execute_update( $smarty, $db, dataArray2fields($dataset, $fields), $table, "vorlage_fragebogen_id = '$form_id'", 'update');

         action_cancel($location);
      } else {
         action_cancel($location);
      }

      break;

   case 'insert':
      $name = mysql_real_escape_string($_REQUEST['bez']);

      if(!isset($_REQUEST['bez']) || $_REQUEST['bez'] == ''){
         $errors = true;
         $err_msg[] = $config['msg_no_name'];
         $smarty->assign('name_exists',$config['msg_no_name']);
      }

      if( dlookup($db,'vorlage_fragebogen', "vorlage_fragebogen_id","bez = '$name'") != '' ){

         $errors = true;
         $err_msg[] = $config['msg_name_exists'];
         $smarty->assign('name_exists',$config['msg_name_exists']);

      }

      if( !isset( $_REQUEST['question'] ) ) {
         $errors = true;
         $err_msg[] = $config['msg_min_frage'];
      }else{
         foreach($_REQUEST['question'] as $question){
            foreach($question as $q_val){
               if($q_val == ''){
                  $errors = true;
                  if(!in_array($config['msg_inkorrekt'],$err_msg)){
                     $err_msg[] = $config['msg_inkorrekt'];
                  }
               }
            }
         }

         if( isset( $_REQUEST['question'] ) ) {
            $questions = array();
            $question_names = array();
            for($x = 0 ; $x < count($_REQUEST['question']['frage']) ; $x++){
               $tmp_array = array( 'frage'   => trim($_REQUEST['question']['frage'][$x]) ,
                                   'val_min' => trim($_REQUEST['question']['min'][$x]) ,
                                   'val_max' => trim($_REQUEST['question']['max'][$x]));

               if (in_array($tmp_array['frage'], $question_names) === true) {
                  $errors = true;
                  $err_msg[] = $config['msg_duplicate_q'];
               } else {
                  $question_names[] = $tmp_array['frage'];
               }

               if(($_REQUEST['question']['max'][$x] != '' && $_REQUEST['question']['min'][$x] != '') && $_REQUEST['question']['min'][$x] >= $_REQUEST['question']['max'][$x]){
                  $errors = true;
                  $err_msg[] = $config['msg_min'];
               }

               if(($_REQUEST['question']['max'][$x] != '' && $_REQUEST['question']['min'][$x] != '') && (!is_numeric($_REQUEST['question']['min'][$x]) || !is_numeric($_REQUEST['question']['max'][$x]))){
                  $errors = true;
                  $err_msg[] = $config['msg_min_max_int'];
               }

               array_push( $questions, $tmp_array );
            }
            $smarty->assign('fragen',$questions);
         }
      }

      if($errors == true){

         $smarty->assign('error', '<ul>'.implode(array_unique($err_msg)).'</ul>');

      }else{

         $freigabe = isset($_REQUEST['freigabe']) ? $_REQUEST['freigabe'] : 0;

         $cur_time = date('Y-m-d H:i:s',time());

         mysql_query("INSERT INTO vorlage_fragebogen (bez,art,bem,freigabe,createuser,createtime) ".
                     "VALUES ('".mysql_real_escape_string($_REQUEST['bez'])."','" . $_REQUEST['art'] . "', '".mysql_real_escape_string($_REQUEST['bem'])."','$freigabe','".$_SESSION['sess_user_id']."','$cur_time')");

         $bogen_id = dlookup($db,'vorlage_fragebogen', 'MAX(vorlage_fragebogen_id)','1=1');

         //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen (a_action,vorlage_fragebogen_id,frage,bem,freigabe,createuser,createtime) ".
         //            "VALUES ('insert','$bogen_id','".mysql_real_escape_string($_REQUEST['bez'])."','".mysql_real_escape_string($_REQUEST['bem'])."','$freigabe','".$_SESSION['sess_user_id']."','$cur_time')");

         foreach($questions as $question){

            mysql_query("INSERT INTO vorlage_fragebogen_frage (vorlage_fragebogen_id, frage, val_min, val_max, createuser, createtime) ".
                        "VALUES('$bogen_id','".mysql_real_escape_string($question['frage'])."','".$question['val_min']."','".$question['val_max']."','".$_SESSION['sess_user_id']."','$cur_time')");

            $frage_id = dlookup($db,'vorlage_fragebogen_frage', 'MAX(vorlage_fragebogen_frage_id)','1=1');

            //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen_frage (a_action, vorlage_fragebogen_frage_id, vorlage_fragebogen_id, frage, val_min, val_max, createuser, createtime) ".
            //            "VALUES('insert','$frage_id','$bogen_id','".mysql_real_escape_string($question['frage'])."','".$question['val_min']."','".$question['val_max']."','".$_SESSION['sess_user_id']."','$cur_time')");

         }

         action_cancel( $location );
      }

      break;

   case 'update':

     $cur_time = date('Y-m-d H:i:s',time());
     $upd_bogen = false;
     $freigabe = isset($_REQUEST['freigabe']) ? $_REQUEST['freigabe'] : 0;
     $questions = array();
     $art = $_REQUEST['art'];

     $form_desc = sql_query_array($db,"SELECT bez,art,bem,freigabe, createuser, createtime FROM vorlage_fragebogen WHERE vorlage_fragebogen_id = '$form_id' ORDER BY vorlage_fragebogen_id");

     if($form_desc[0]['bez'] != $_REQUEST['bez'] || $form_desc[0]['bem'] != $_REQUEST['bem'] || $form_desc[0]['freigabe'] != $freigabe || $form_desc[0]['art'] != $art){

         $name = dlookup($db,'vorlage_fragebogen', "bez", "vorlage_fragebogen_id = '$form_id'");

         if( $_REQUEST['bez'] == ''){
            $errors = true;
            $err_msg[] = $config['msg_no_name_2'];
            $smarty->assign('name_exists',$config['img_error']);
         }else if( dlookup($db,'vorlage_fragebogen', "vorlage_fragebogen_id","bez = '".mysql_real_escape_string($_REQUEST['bez'])."' AND vorlage_fragebogen_id <> '$form_id'") != '' ){
            $errors = true;
            $err_msg[] = $config['msg_name_exists'];
            $smarty->assign('name_exists',$config['img_error']);
         }else{
            $upd_bogen = true;

         }
      }

      if( !isset( $_REQUEST['question'] ) ) {

         $errors = true;
         $err_msg[] = $config['msg_min_frage'];

      }else{

         foreach($_REQUEST['question'] as $question){

            foreach($question as $q_val){

               if($q_val == ''){
                  $errors = true;
                  $err_msg[] = $config['msg_not_filled'];
               }
            }
         }
         $question_names = array();
         if( isset( $_REQUEST['question'] ) ) {

            for($x = 0 ; $x < count($_REQUEST['question']['frage']) ; $x++){
               $tmp_array = array( 'frage'   => trim($_REQUEST['question']['frage'][$x]) ,
                                   'val_min' => trim($_REQUEST['question']['min'][$x]) ,
                                   'val_max' => trim($_REQUEST['question']['max'][$x]));

               if (in_array($tmp_array['frage'], $question_names) === true) {
                  $errors = true;
                  $err_msg[] = $config['msg_duplicate_q'];
               } else {
                  $question_names[] = $tmp_array['frage'];
               }

               if($_REQUEST['question']['min'][$x] >= $_REQUEST['question']['max'][$x]){
                  $errors = true;
                  $err_msg[] = $config['msg_min'];
               }

               if( !is_numeric($_REQUEST['question']['min'][$x]) || !is_numeric($_REQUEST['question']['max'][$x])){
                  $errors = true;
                  $err_msg[] = $config['msg_min_max_int'];
               }

               array_push( $questions, $tmp_array );
            }
            $smarty->assign('fragen',$questions);
         }

      }

      if($errors == false){

          if( $upd_bogen == true ){

             mysql_query("UPDATE vorlage_fragebogen SET bez = '".mysql_real_escape_string($_REQUEST['bez'])."',art = '" . $_REQUEST['art'] . "', bem = '".mysql_real_escape_string($_REQUEST['bem'])."', freigabe = '$freigabe', updateuser = '".$_SESSION['sess_user_id']."', updatetime = '$cur_time' WHERE vorlage_fragebogen_id = '$form_id'");

             //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen (a_action,vorlage_fragebogen_id,frage,bem,freigabe,updateuser,updatetime) ".
             //                "VALUES ('update','$form_id','".mysql_real_escape_string($_REQUEST['bez'])."','".$_REQUEST['bem']."','$freigabe','".$_SESSION['sess_user_id']."','$cur_time')");

          }

          $fragen_id = sql_query_array($db,"SELECT vorlage_fragebogen_frage_id FROM vorlage_fragebogen_frage WHERE vorlage_fragebogen_id='$form_id'");

          foreach($fragen_id[0] as $frage){

             //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen_frage (a_action, vorlage_fragebogen_frage_id,vorlage_fragebogen_id,updateuser,updatetime) ".
             //            "VALUES('delete','$frage','$form_id','".$_SESSION['sess_user_id']."','$cur_time')");
          }

          mysql_query("DELETE FROM vorlage_fragebogen_frage WHERE vorlage_fragebogen_id = '$form_id'");

          foreach($questions as $question){

                mysql_query("INSERT INTO vorlage_fragebogen_frage (vorlage_fragebogen_id, frage, val_min, val_max, createuser, createtime) ".
                            "VALUES('$form_id','".mysql_real_escape_string($question['frage'])."','".$question['val_min']."','".$question['val_max']."','".$_SESSION['sess_user_id']."','$cur_time')");

                $frage_id = dlookup($db,'vorlage_fragebogen_frage', 'MAX(vorlage_fragebogen_frage_id)','1=1');

                //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen_frage (a_action, vorlage_fragebogen_frage_id, vorlage_fragebogen_id, frage, val_min, val_max, createuser, createtime) ".
                //            "VALUES('insert','$frage_id','$form_id','".mysql_real_escape_string($question['frage'])."','".$question['val_min']."','".$question['val_max']."','".$_SESSION['sess_user_id']."','$cur_time')");

          }

          action_cancel( $location );

      }else{
         $smarty->assign('error', '<ul>'.implode(array_unique($err_msg)).'</ul>');
      }

      break;

   case 'delete':

      $cur_time = date('Y-m-d H:i:s',time());

      mysql_query("DELETE FROM vorlage_fragebogen WHERE vorlage_fragebogen_id = '$form_id'");

      //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen (a_action, vorlage_fragebogen_id,updateuser,updatetime) ".
      //            "VALUES('delete','$form_id','".$_SESSION['sess_user_id']."','$cur_time')");

      $fragen_id = sql_query_array($db,"SELECT vorlage_fragebogen_frage_id FROM vorlage_fragebogen_frage WHERE vorlage_fragebogen_id='$form_id'");

      foreach($fragen_id[0] as $frage){
         //mysql_query("INSERT INTO $tbl_a_vorlage_fragebogen_frage (a_action, vorlage_fragebogen_frage_id,vorlage_fragebogen_id,updateuser,updatetime) ".
         //            "VALUES('delete','$frage','$form_id','".$_SESSION['sess_user_id']."','$cur_time')");
      }

      mysql_query("DELETE FROM vorlage_fragebogen_frage WHERE vorlage_fragebogen_id = '$form_id'");

      action_cancel( $location );

      break;

   case 'cancel': action_cancel( $location );   break;
}

?>