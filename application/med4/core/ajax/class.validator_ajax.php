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

class validator_ajax extends validator
{
    /**
     * _table
     * (comment)
     *
     * @access  public
     * @var     string
     */
    public $_table;


    /**
     * _pos
     *
     * @access  public
     * @var     array
     */
    public $_pos = array();


    /**
     * _posId
     * (comment)
     *
     * @access  public
     * @var     int
     */
    public $_posId;


    /**
     * @param Smarty    $smarty
     * @param ressource $db
     * @param array     $fields
     * @param string    $table
     * @param array     $posTables
     * @param int       $posId
     */
    public function __construct( $smarty, $db, &$fields, $table, $posTables, $posId)
    {
        $smarty->config_load( FILE_CONFIG_DEFAULT, 'validator');

        $this->_db        = $db;
        $this->_smarty    = $smarty;
        $this->_fields    = &$fields;
        $this->_msg       = $smarty->get_config_vars();
        $this->_table     = $table;
        $this->_pos       = $posTables;
        $this->_posId     = $posId;

        // Nachrichten Typen Registrieren
        $this->register_type('err' , 'err');
        $this->register_type('warn', 'warn');

        // JavaScript Nachrichten aktivieren
        $this->enable_js('err',    true);
        $this->enable_js('warn',   true);

        // JavaScript and Error Messages
        $err_range = $this->_msg['err_range'];

        $this->register_msg( 'err', 1  , $this->_msg['err_int']              , ''             , 'num'     );
        $this->register_msg( 'err', 2  , $this->_msg['err_float']            , ''             , 'num'     );
        $this->register_msg( 'err', 3  , $this->_msg['err_date_new']         , ''             , 'date'    );
        $this->register_msg( 'err', 4  , $this->_msg['err_time']             , ''             , 'error'   );
        $this->register_msg( 'err', 5  , $this->_msg['err_start_ende_date']  , ''             , 'date'    );
        $this->register_msg( 'err', 9  , $this->_msg['err_number_no_text']   , ''             , 'num'     );
        $this->register_msg( 'err', 10 , $this->_msg['err_invalid']          , ''             , 'error'   );
        $this->register_msg( 'err', 11 , $this->_msg['err_req']              , ''             , 'req'     );
        $this->register_msg( 'err', 12 , $this->_msg['err_plausibly']        , ''             , 'error'   );
        $this->register_msg( 'err', 13 , $this->_msg['err_delete_pos']       , ''             , 'error'   );
        $this->register_msg( 'err', 14 , $this->_msg['err_missing_forms']    , ''             , 'error'   );
        $this->register_msg( 'err', 15 , $this->_msg['err_plausibly']        , $err_range     , 'num'     );
        $this->register_msg( 'err', 17 , $this->_msg['err_fill_one']         , ''             , 'req'     );
        $this->register_msg( 'err', 18 , $this->_msg['err_fill_one_min']     , ''             , 'req'     );
        $this->register_msg( 'err', 100, $this->_msg['err_date_future']      , ''             , 'date'    );
        $this->register_msg( 'err', 101, $this->_msg['err_date_valid']       , ''             , 'date'    );
        $this->register_msg( 'err', 102, $this->_msg['err_date_year']        , ''             , 'date'    );

        // JavaScript Warn Messages
        $warn_range = $this->_msg['warn_range'];

        $this->register_msg('warn', 10, $this->_msg['warn_invalid']          , ''             , 'warning' );
        $this->register_msg('warn', 11, $this->_msg['warn_invalid']          , ''             , 'req'     );
        $this->register_msg('warn', 12, $this->_msg['warn_plausibly']        , $warn_range    , 'warning' );
        $this->register_msg('warn', 15, $this->_msg['warn_plausibly']        , ''             , 'warning' );
        $this->register_msg('warn', 17, $this->_msg['warn_fill_one']         , ''             , 'req'     );
        $this->register_msg('warn', 18, $this->_msg['warn_fill_one_min']     , ''             , 'req'     );

        // Compatibilitätsmodus
        $this->enable_multimessage('err',  true);
        $this->enable_multimessage('warn', true);
    }


    /**
     * validate_fields
     *
     * @access  public
     * @param   array $fields
     * @return  void
     */
    public function validate_fields($fields)
    {
        // initialisieren
        $arr_req      = array();
        $arr_int      = array();
        $arr_float    = array();
        $arr_date     = array();
        $arr_time     = array();
        $arr_min_max  = array();
        $arr_code_icd = array();
        $arr_code_o3  = array();
        $arr_code_nci = array();
        $arr_code_ops = array();

        if (is_array($fields) === false) {
            return;
        }

        while( list($key, $arr) = each($fields) )
        {
            switch($arr['type']) {
                case 'int':       $arr_int[]      = $key;    break;
                case 'float':     $arr_float[]    = $key;    break;
                case 'date':      $arr_date[]     = $key;    break;
                case 'time':      $arr_time[]     = $key;    break;
                case 'code_o3':     $arr_code_o3[] = $key;     break;
                case 'code_icd':  $arr_code_icd[] = $key;    break;
                case 'code_ops':  $arr_code_ops[] = $key;    break;
                case 'code_nci':  $arr_code_nci[] = $key;    break;
            }

            if ($arr['req'] == 1) {
                $arr_req[] = $key;
            }

            if (isset($arr['range']) === true) {
                $arr_min_max[] = $key;
            }
        }

        if( count($arr_req)      ) $this->fields_req($arr_req);
        if( count($arr_int)      ) $this->fields_int($arr_int);
        if( count($arr_float)    ) $this->fields_float($arr_float);
        if( count($arr_date)     ) $this->fields_date($arr_date);
        if( count($arr_time)     ) $this->fields_time($arr_time);
        if( count($arr_min_max)  ) $this->fields_in_range($arr_min_max);
        if( count($arr_code_icd) ) $this->fields_code_icd($arr_code_icd);
        if( count($arr_code_ops) ) $this->fields_code_ops($arr_code_ops);
        if( count($arr_code_o3)  )  $this->fields_code_o3($arr_code_o3);
        if( count($arr_code_nci) ) $this->fields_code_nci($arr_code_nci);

        //Ukey check für Pos formulare
        $this->fields_ukey($fields);
    }


   /******************************************************************************************************
      Requiered Feld prüfen
    ******************************************************************************************************/
   function fields_ukey($fields, $fullKeyCheck = false)
   {
      if ($this->_table !== null) {
         $ukeys = array();
         $table = $this->_table;

         if ($fullKeyCheck === true) {
            $query   = "SHOW COLUMNS FROM {$table} WHERE Field NOT IN ('{$table}_id','createuser', 'createtime', 'updateuser', 'updatetime')";
            $result  = sql_query_array($this->_db, $query);

            foreach ($result as $field) {
                $ukeys['all'][] = $field['Field'];
            }
         } else {
            $query   = "SHOW KEYS FROM $table WHERE KEY_NAME LIKE 'ukey%'";
            $result  = sql_query_array($this->_db, $query);

             //Ukeys check
             foreach ($result as $keys) {
                $ukeys[strtolower($keys['Key_name'])][] = $keys['Column_name'];
             }
         }

         $ukeyStack = array();

         //Init Stack
         foreach ($ukeys as $ukeyName => $ukeyContent) {
            $ukeyStack[$ukeyName] = array();
         }

         //Ukey Stack fill
         foreach ($this->_pos as $posId => $posTable)
         {
            if ($this->_posId !== null && (int) $posId === (int) $this->_posId){
               continue;
            }

            foreach ($ukeys as $ukeyName => $ukeyContent) {
               $ukeyValue = '';

               foreach ($ukeyContent as $ukey) {

                  $value = $posTable[$ukey];

                  if (strlen($value) == 0 && isset($fields[$ukey]['null']) === true) {
                     $value = $fields[$ukey]['null'];
                  }

                  $ukeyValue .= $value;
               }

               $ukeyStack[$ukeyName][] = trim($ukeyValue);
            }
         }

         //Fields erweitern IF NULL
         foreach ($fields as $key => $field) {
            if (array_key_exists('null', $field) && strlen(reset($field['value'])) == 0) {
               $fields[$key]['value'][0] = $field['null'];
            }
         }

         //Check current form
         foreach ($ukeys as $ukeyName => $ukeyContent) {
            $currentUkeyValue = '';
            $lastfield = '';

            foreach ($ukeyContent as $ukey) {
               $currentUkeyValue .= $fields[$ukey]['value'][0];
               $lastfield = $ukey;
            }

            if (in_array($currentUkeyValue, $ukeyStack[$ukeyName]) === true) {
               $felder = array();

               foreach ($ukeyContent as $ukey) {
                  $felder[] = $this->_msg[$ukey];
               }

               $err = count($felder) > 1 ? sprintf($this->_msg['msg_duplicate_multi'], implode(', ', $felder)) : sprintf($this->_msg['msg_duplicate_multi'], reset($felder));

               foreach ($ukeyContent as $ukey) {
                  $this->set_msg('err', 10, $ukey, $err);
               }
            }
         }
      }
   }


    /**
     * set_msg
     *
     * @access  public
     * @param   string          $var_type
     * @param   string          $type
     * @param   string|array    $field
     * @param   string          $msg
     * @return  void
     */
    public function set_msg($var_type, $type, $field, $msg='')
    {
        if (is_array($field) === false) {
            $field = array($field);
        }

        $varType = $this->getType($var_type);

        foreach ($field as $name) {
            if (strlen($msg) > 0) {
                if (in_array($msg, $varType->msg) === false) {
                    $varType->msg[$msg] = $msg;
                }

                $varType->field[$name][$type][] = $varType->msg[$msg];

            } else {
                if (in_array($type, $varType->pool) === false) {

                    $varType->pool[] = $type;
                }

                $varType->field[$name][$type][] = $varType->msg_pool[$type];
            }
        }
    }


    /**
     * parse_block
     *
     * @access  public
     * @param   string $var_type
     * @return  array
     */
    public function parse_block($var_type)
    {
        $arr_output = array();

        $varType = $this->getType($var_type);

        foreach ($varType->field AS $field => $messages) {
            foreach ($messages AS $errCode => $i18nMessages) {
                foreach ($i18nMessages as $msg) {
                    if (is_object($msg) === true) {
                        $msg = $msg->msg;
                    }

                    $arr_output[$field] = array(
                        'err_code' => $errCode,
                        'msg'      => escape($msg),
                        'type'     => $var_type
                    );
                }
            }
        }

        return $arr_output;
    }


    /**
     * fields_date
     *
     * @access  public
     * @param   array   $fields
     * @return  void
     */
    public function fields_date( $fields )
    {
        $year_min = 1900;
        $year_max = 2100;

        foreach ($fields AS $field) {
            $raw_date = trim( $this->_fields[$field]['value'][0] );

            if (!strlen($raw_date)) {
                continue;
            }


            $rasp_date    = str_replace( '.', '', $raw_date);

            /* Gate 1: Check numbers */
            $d = str_split( $rasp_date );
            foreach( $d as $char ){
               if( preg_match( '/[^0-9]/' , $char ) )
                  $this->set_msg( 'err', 3, $field , '' );
            continue;
            }

            /* Gate 2: String length */
            $date_length = in_array( strlen($rasp_date), array( 6 , 8 ) ) ? strlen( $rasp_date ) : false;
            if( !$date_length ){
               $this->set_msg( 'err', 3, $field, '' );
               continue;
            }

            /* Gate 3: Check TT, MM */
            $tt = substr( $rasp_date, 0, 2 );
            $mm = substr( $rasp_date, 2, 2 );
            $yy = substr( $rasp_date, 4 );

            if( strlen( $yy ) == 2 && $yy < 39 ){
               $yy = '20'.$yy;
            }elseif( strlen( $yy ) == 2){
               $yy = '19'.$yy;
            }

            if( !checkdate( $mm, $tt, $yy )){
               $this->set_msg( 'err', 101, $field , '' );
               continue;
            }

            /* Gate 4: Zwischen year_min und year_max */
        if ( ( $yy < $year_min )  OR ( $yy > $year_max ) ){
           $this->set_msg( 'err', 102, $field , '');
           continue;
        }

            /* Gate 5: Datum darf nicht in der Zukunft liegen */
            $range           = isset( $this->_fields[$field]['range'] ) ? $this->_fields[$field]['range'] : true;   /* Flag   */
            $today           = date( 'Y-m-d' );
            $orig_date       = format_date( ($tt.'.'.$mm.'.'.$yy), 'en' );

            if( $range && $orig_date > $today ){
               $this->set_msg( 'err', 100, $field, '' );
               continue;
            }


            $this->_fields[$field]['value'][0] = $tt.'.'.$mm.'.'.$yy;
        }
    }
}

?>
