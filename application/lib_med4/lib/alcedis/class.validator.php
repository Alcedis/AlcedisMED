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

require_once 'validator/type.php';
require_once 'validator/msg.php';

class validator
{
    /**
     * _types
     *
     * @access  protected
     * @var     array
     */
    public $_types = array();


    /**
     * _db
     *
     * @access  protected
     * @var     ressource
     */
    public $_db;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    public $_smarty;


    /**
     * _fields
     *
     * @access  protected
     * @var     array
     */
    public $_fields = array();


    /**
     * _msg
     *
     * @access  protected
     * @var     array
     */
    public $_msg = array();


    /**
     * validator
     *
     * @access  public
     * @param   Smarty      $smarty
     * @param   ressource   $db
     * @param   array       $fields
     * @return  void
     */
    public function __construct($smarty, $db, &$fields)
    {
        $smarty->config_load(FILE_CONFIG_DEFAULT, 'validator');

        $this->_db     = $db;
        $this->_smarty = $smarty;
        $this->_fields = &$fields;
        $this->_msg    = $smarty->get_config_vars();

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
        $this->register_msg( 'err', 3  , $this->_msg['err_date']             , ''             , 'date'    );
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

        if (isset($this->_msg['err_wrong_captcha']) === true) {
            $this->register_msg( 'err', 19 , $this->_msg['err_wrong_captcha'], '', 'error');
        }

        // JavaScript Warn Messages
        $warn_range     = $this->_msg['warn_range'];
        $warn_plausibly = $this->_msg['warn_plausibly'];

        $this->register_msg('warn', 10, $this->_msg['warn_invalid']          , ''             , 'warning' );
        $this->register_msg('warn', 11, $this->_msg['warn_invalid']          , ''             , 'req'     );
        $this->register_msg('warn', 12, $this->_msg['warn_plausibly']        , $warn_range    , 'warning' );
        $this->register_msg('warn', 15, $this->_msg['warn_plausibly']        , ''             , 'warning' );
        $this->register_msg('warn', 17, $this->_msg['warn_fill_one']         , ''             , 'req'     );
        $this->register_msg('warn', 18, $this->_msg['warn_fill_one_min']     , ''             , 'req'     );

        // compatibility mode
        $this->enable_multimessage('err',  true);
        $this->enable_multimessage('warn', true);
    }


    /**
     * __get
     *
     * @access  public
     * @param   string $var
     * @return  validatorType
     */
    public function __get($var)
    {
        return $this->getType($var);
    }


    /**
     * setFields
     *
     * @access  public
     * @param   array   $fields
     * @return  validator
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;

        return $this;
    }


    /**
     * getValidationFields
     *
     * @access  public
     * @param   string  $type
     * @return  array
     */
    public function getValidationFields($type)
    {
        $return = null;

        if (isset($this->{$type}) === true) {
            if (count($this->{$type}->field) > 0) {
                $return = array_keys($this->{$type}->field);
            }
        }

        return $return;
    }


    /**
     * set_err
     *
     * @access  public
     * @param   string  $type
     * @param   string  $field
     * @param   null    $dummy
     * @param   string $msg
     * @return  validator
     */
    public function set_err($type, $field, $dummy, $msg='')
    {
        $this->set_msg('err', $type, $field, $msg);

        return $this;
    }

   /** -------------------------------------------------------------------------------------------
    **
    **/
    public function set_warn($type, $field, $dummy, $msg='')
    {
      $this->set_msg('warn', $type, $field, $msg);

      return $this;
   }

   /** -------------------------------------------------------------------------------------------
    ** Kombi Function ersetzt set_err, set_warn
    **/
    public function set_msg($var_type, $type, $field, $msg='')
    {
        // Wenn field kein Array ist dann zu einem machen
        if (is_array($field) === false) {
            $field = array($field);
        }

        $varType = $this->getType($var_type);

        // Jedes Feld in der Variable field in die Message Array's eintragen
        foreach ($field as $name) {
            if (array_key_exists($name, $this->_fields) === false) {
                continue;
            }


            // Wenn eine Message übergeben wurde
            if (strlen($msg) > 0) {
                if (in_array($msg, $varType->msg) === false) {
                    // Wenn Frei Text Meldung moch nicht in Array dann aufnehmen
                    $varType->msg[$msg] = $msg;
                }

                // und Zuordnung Feld => Type => Message Speichern
                $varType->field[$name][$type][] = &$varType->msg[$msg];
            } else {
                // Wenn keine Message übergeben wurde

                // wenn nicht in den Pool-Messages
                if (in_array($type, $varType->pool) === false) {
                    // Wenn wenn Standard Meldung moch nicht in Array dann aufnehmen
                    $varType->pool[] = $type;
                }

                // und Zuordnung Feld => Type => Message Speichern
                $varType->field[$name][$type][] = &$varType->msg_pool[$type];
            }
        }
    }

   /** -------------------------------------------------------------------------------------------
    ** Blöcke Parsen
    **/
    public function parse_block($var_type)
    {
        $output_msg = '';
        $varType = $this->getType($var_type);


        // erst die Std Messages in den Output eintragen
        foreach ($varType->pool as $index => $pool) {
            $output_msg.='<li>' . $varType->msg_pool[$pool]->msg . '</li>';
        }

        // dann die Frei Text Messages in den Output eintragen
        foreach ($varType->msg as $msg) {
            $output_msg .= '<li>' . $msg . '</li>';
        }

        if ($varType->enable_js == true) {
            $this->parse_js_msg($var_type);
        }

        return (strlen($output_msg)) ? '<ul>' . $output_msg . '</ul>' : '';
    }

    /******************************************************************************************************
      zu guter letzt die Script Messages erstellen
     ******************************************************************************************************/
    public function parse_js_msg($var_type)
    {
        $varType = $this->getType($var_type);

        reset($varType->field);

        while (list($field, $arr_script_msg) = each($varType->field)) {
            reset($arr_script_msg);

            $first    = false;
            $icon_msg = '';
            $anzahl   = count($arr_script_msg);

            // Message und Icon erstellen
            while ((list($type, $arr_msg) = each($arr_script_msg)) and !$first) {
                // wenn nur eine Message dann ist das Icon klar
                if ($anzahl == 1) {
                    // Zusammenbauen der Config-Variable
                    $icon       = isset($arr_msg[0]->icon) ? $arr_msg[0]->icon : '' ;
                    $lbl_key    = 'lbl_button_' . $icon;
                    $lbl_button = isset( $this->_msg[$lbl_key] ) ? $this->_msg[$lbl_key] : '';

                    // Existiert diese Variable?
                    if (!strlen($lbl_button)) {
                        // Zusammenbauen der Config-Variable
                        $lbl_key    = 'lbl_button_' . $varType->msg_pool[$type]->icon;
                        $lbl_button = isset( $this->_msg[$lbl_key] ) ? $this->_msg[$lbl_key] : '';
                    }

                    if ($var_type == 'warn') {
                        $lbl_button = $this->_msg['lbl_button_target'];
                    }

                    if ($lbl_key  == 'lbl_button_warning') {
                        $lbl_button = '';
                    }

                } else {
                    // wenn mehrere Messages dann nur das Multi Icon anzeigen
                    $lbl_button = '';

                    if ($varType->multimessage === false) {
                        $first = true;
                    }
                }

            list(, $arr_msg) = each($arr_msg);

            if ($var_type == 'err') {
               $icon_code = '<img src="./media/img/base/editdelete.png" alt="Error" /> ';
            }else if ($var_type == 'warn') {
               $icon_code = '<img src="./media/img/base/warn-small.png" alt="Warning" /> ';
            }else {
               $icon_code = '';
            }

            if(is_object($arr_msg))
               $icon_msg .= $icon_code . ( (isset($arr_msg->script)) ? $arr_msg->script : $arr_msg->msg );
            else
               $icon_msg .= $icon_code . $arr_msg;

            // Range sonderfall
            if( $type == 15 )
             {
               $range = isset($this->_fields[$field]['range']) ? $this->_fields[$field]['range'] : ( isset($this->_fields[$field]['warn_range']) ? $this->_fields[$field]['warn_range'] : '' );
               $icon_msg = str_replace('MIN', $range['min'], $icon_msg);
               $icon_msg = str_replace('MAX', $range['max'], $icon_msg);
            }
            $icon_msg .='<br/>';
         }


            // Zuweisung der richtigen CSS-Klasse
            switch( $var_type ) {
                case 'err'  : $button_type = 'button_err';  break;
                case 'warn' : $button_type = 'button_warn'; break;
            }

            $class = $button_type == 'button_warn' ? 'trigger-warn' : 'trigger-err';
            $color = $button_type == 'button_warn' ? 'border-warn' : 'border-err';

         // Ticks in JavaScript-Meldung escapen
            $icon_msg = str_replace("'", "\'", $icon_msg);

            $config_var = "src_$var_type";
            $popupImg = strlen($lbl_button) !== false ?
                 '<button tabindex="-1" type="button" class="trigger ' . $class . '"><!-- --></button>' :
                 '<img src="' . $this->_msg[$config_var] . '" class="trigger ' . $class . '"/>';

            $icon = "<div style='display:inline;' class='bubbleTrigger'>$popupImg<div class='bubbleInfo $color'><div style='max-width:325px;float:left;'>$icon_msg</div></div></div>";
// end

            if (is_object($this->_smarty) === true) {
                $this->_smarty->assign('err_'.$field, $icon);
            }
      }
   }


    /**
     * register_type
     *
     * @access  public
     * @param   string  $var_type
     * @param   string  $css_class
     * @return  validator
     */
    public function register_type($var_type, $css_class)
    {
        if (array_key_exists($var_type, $this->_types) === false) {
            $this->_types[$var_type] = new validatorType($css_class);
        }

        return $this;
    }


    /**
     * register_msg
     *
     * @access  public
     * @param   int     $var_type
     * @param   string  $type
     * @param   string  $msg
     * @param   string $script
     * @param   string $icon_name
     * @return  validator
     */
    public function register_msg($var_type, $type, $msg, $script='', $icon_name='')
    {
        $varType = $this->getType($var_type);

        if ($varType->hasTypeInMsgPool($type) === false) {
            $message = $varType->msg_pool[$type] = new validatorMessage;
        } else {
            $message = $varType->msg_pool[$type];
        }

        $message->msg = $msg;

        if (strlen($script)) {
            $message->script = $script;
        }

        $message->icon = $icon_name;

        return $this;
    }


    /**
     * getType
     *
     * @access  public
     * @param   string  $var_type
     * @return  validatorType
     */
    public function getType($var_type)
    {
        return $this->_types[$var_type];
    }


    /**
     * hasType
     *
     * @access  public
     * @param   string $type
     * @return  bool
     */
    public function hasType($type)
    {
        return array_key_exists($type, $this->_types);
    }


    /**
     * enable_js
     *
     * @access  public
     * @param   string  $var_type
     * @param   bool    $js
     * @return  validator
     */
    public function enable_js($var_type, $js)
    {
        $this->getType($var_type)->enable_js = $js;

        return $this;
    }


    /**
     * enable_multimessage
     * (Multiple Nachrichten in JS Box Anzeigen)
     *
     * @access  public
     * @param   string  $var_type
     * @param   bool    $multimessage
     * @return  validator
     */
    public function enable_multimessage($var_type, $multimessage)
    {
        $this->getType($var_type)->multimessage = $multimessage;

        return $this;
    }


    /**
     * validate_fields
     * (Stuffe 1 Prüfungen)
     *
     * @access  public
     * @param   array   $fields
     * @return  void
     */
    public function validate_fields($fields)
    {
        $arr_req      = array();
        $arr_email    = array();
        $arr_int      = array();
        $arr_float    = array();
        $arr_date     = array();
        $arr_captcha  = array();
        $arr_time     = array();
        $arr_min_max  = array();
        $arr_code_icd = array();
        $arr_code_o3  = array();
        $arr_code_nci = array();
        $arr_code_ops = array();

        if (is_array($fields) === false) {
            return;
        }

        while (list($key, $arr) = each($fields)) {
            switch($arr['type']) {
                case 'int':        $arr_int[]        = $key;       break;
                case 'email':      $arr_email[]     = $key;    break;
                case 'float':       $arr_float[]        = $key;       break;
                case 'date':       $arr_date[]        = $key;       break;
                case 'time':       $arr_time[]        = $key;       break;
                case 'code_icd':    $arr_code_icd[] = $key;       break;
                case 'code_o3':     $arr_code_o3[]  = $key;    break;
                case 'code_ops':    $arr_code_ops[] = $key;       break;
                case 'code_nci':    $arr_code_nci[] = $key;       break;
                case 'captcha':     $arr_captcha[]  = $key;    break;
            }

            if ($arr['req'] == 1) {
                $arr_req[] = $key;
            }

            if (isset($arr['range']) === true) {
                $arr_min_max[] = $key;
            }
       }


       if( count($arr_req)      )    $this->fields_req($arr_req);
       if( count($arr_email)  )    $this->fields_email($arr_email);
       if( count($arr_captcha)  )    $this->fields_captcha($arr_captcha);
       if( count($arr_int)      )    $this->fields_int($arr_int);
       if( count($arr_float)    )    $this->fields_float($arr_float);
       if( count($arr_date)     )    $this->fields_date($arr_date);
       if( count($arr_time)     )    $this->fields_time($arr_time);
       if( count($arr_min_max)  )    $this->fields_in_range($arr_min_max);
       if( count($arr_code_icd) )    $this->fields_code_icd($arr_code_icd);
       if( count($arr_code_ops) )    $this->fields_code_ops($arr_code_ops);
       if( count($arr_code_o3)  )  $this->fields_code_o3($arr_code_o3);
       if( count($arr_code_nci) )    $this->fields_code_nci($arr_code_nci);
   }

   /******************************************************************************************************
      Requiered Feld prüfen
    ******************************************************************************************************/
    public function fields_req( $fields, $msg = '', $message=true)
    {
       $return = true;
       reset($fields);

       foreach( $fields as $field )
        {
           $value = trim( $this->_fields[$field]['value'][0] );
           if( $value == '' )
            {
              if ($message === true) {
               $this->set_msg( 'err', 11, $field, $msg );
              }

            $return = false;
           }
       }

       return $return;
   }

   /******************************************************************************************************
    Captcha prüfen
   ******************************************************************************************************/
    public function fields_captcha( $fields, $msg = '', $message=true)
    {
          $return = true;
          reset($fields);

          foreach( $fields as $field )
           {
              $value = preg_replace("/[' ]/i","", trim($this->_fields[$field]['value'][0]));;

              if (strlen($value) > 0 && isset($_SESSION["captcha_{$field}"]) === true && $value !== $_SESSION["captcha_{$field}"]) {
                  if ($message === true) {
                    $this->set_msg('err', 19, $field, $msg);
                  }

               $return = false;
              }
          }

          return $return;
   }

   /******************************************************************************************************
    Funktion zum Pruefen ob die Email-Adresse korrekt ist
   ******************************************************************************************************/
    public function fields_email($fields, $msg = '', $message=true)
    {
       $return = true;
       reset($fields);

       foreach( $fields as $field )
        {
           $value = ( isset( $this->_fields[$field]['value'][0] ) ) ? $this->_fields[$field]['value'][0] : '';

           $reg = "^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@([_a-zA-Z0-9-]+\.)+([a-zA-Z{2,3}])+$";

           if (strlen($value) > 0 && !ereg($reg, $value ) )
            {
               if (strlen($msg) > 0) {
                   $this->set_msg('err', 10, $field, $msg);
               } else {
                   $this->set_msg('err', 12, $field, '');
               }

               $return = false;
           }
       }

        return $return;
   }



   /******************************************************************************************************
      Prüft ob Inhalt Integer Zahlen sind
    ******************************************************************************************************/
    public function fields_int( $fields )
    {
       $return  = true;
       $pattern = "/^(.*)[^0123456789](.*)+$/i";
       reset($fields);
       foreach( $fields as $field )
        {
           if( !isset($this->_fields[$field]["value"][0]) )
              continue;

            $value = trim( $this->_fields[$field]["value"][0] );
            if( !is_numeric($value) and !trim($value) == '' or preg_match($pattern, $value) )
             {
                if( stristr($value,",") or stristr($value,".") )
                 {
                    $this->set_msg( 'err', 1 ,$field, '' );
                    $return = false;
                }
                elseif( preg_match( $pattern, $value ) )
                 {
                    $this->set_msg( 'err', 9 ,$field, '' );
                    $return = false;
                }
            }
       }
       return $return;
   }

   /******************************************************************************************************
      Prüft ob Inhalt Float(Real) Zahlen sind
    ******************************************************************************************************/
    public function fields_float( $fields )
    {
       $return  = true;
          $pattern = "/^(.*)[^0123456789,](.*)+$/i";
      reset($fields);
        foreach( $fields as $field )
         {
           if( !isset($this->_fields[$field]["value"][0]) )
              continue;

            $value = trim( $this->_fields[$field]["value"][0] );
            if( !is_numeric($value) and !trim($value) == '' or $_pos = stristr( $value,"." ) )
             {
                $_pos = stristr( $value,"." );
                if( $_pos )
                 {
                    $this->set_msg( 'err', 2, $field, '' );
                    $return = false;
                }
                elseif( preg_match( $pattern, $value ) )
                 {
                    $this->set_msg( 'err', 9, $field, '' );
                    $return = false;
                }
            }
       }
       return $return;
   }

   /**
    * Datumscheck
    *
    */
    public function fields_date( $fields )
    {
      $year_min = 1900;
      $year_max = 9999;


      foreach( $fields AS $field ){
              $raw_date = trim( $this->_fields[$field]['value'][0] );
              if( !strlen( $raw_date ) )
                 continue;

            $err_date_new = isset( $this->_msg['err_date_new']  ) ? $this->_msg['err_date_new']  : '';
            $rasp_date    = str_replace( '.', '', $raw_date);

            /* Gate 1: Check numbers */
            $d = str_split( $rasp_date );
            foreach( $d as $char ){
               if( preg_match( '/[^0-9]/' , $char ) )
                  $this->set_msg( 'err', 3, $field , $err_date_new );
            continue;
            }

            /* Gate 2: String length */
            $date_length = in_array( strlen($rasp_date), array( 6 , 8 ) ) ? strlen( $rasp_date ) : false;
            if( !$date_length ){
               $this->set_msg( 'err', 3, $field, $err_date_new );
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

            if (is_numeric($mm) == false || is_numeric($tt) == false || is_numeric($yy) == false) {
               $err_date_valid = isset( $this->_msg['err_date_valid']  ) ? $this->_msg['err_date_valid']  : null;
               $this->set_msg( 'err', 3, $field , $err_date_valid);
               continue;
            }

            if ( !checkdate( $mm, $tt, $yy )){
               $err_date_valid = isset( $this->_msg['err_date_valid']  ) ? $this->_msg['err_date_valid']  : null;
               $this->set_msg( 'err', 3, $field , $err_date_valid);
               continue;
            }

            /* Gate 4: Zwischen year_min und year_max */
              if ( ( $yy < $year_min )  OR ( $yy > $year_max ) ){
                 $err_date_year = isset( $this->_msg['err_date_year']  ) ? $this->_msg['err_date_year']  : null;
                 $this->set_msg( 'err', 3, $field , $err_date_year );
                 continue;
              }

            /* Gate 5: Datum darf nicht in der Zukunft liegen */
            $range           = isset( $this->_fields[$field]['range'] ) ? $this->_fields[$field]['range'] : true;   /* Flag   */
            $err_date_future = isset( $this->_msg['err_date_future']  ) ? $this->_msg['err_date_future']  : null;   /* String */
            $today           = date( 'Y-m-d' );
            $orig_date       = format_date( ($tt.'.'.$mm.'.'.$yy), 'en' );

            $deactivateRangeCheck = (class_exists('appSettings') === true && appSettings::get('deactivate_range_check') === true);

            if ($range && $orig_date > $today && $deactivateRangeCheck === false){
               $this->set_msg( 'err', 3, $field, $err_date_future );
               continue;
            }

            /* Exit */
            $this->_fields[$field]['value'][0] = $tt.'.'.$mm.'.'.$yy;
          }
   }

   /******************************************************************************************************
       Zeitcheck
    ******************************************************************************************************/
    public function fields_time( $fields )
    {
      // Initial
      $separator = ':';
      $arr_format = array( 'h', 'm', 's' );

       // Wenn kein Array, dann zurück
       if( !is_array($fields) )
          return;

       foreach( $fields as $field )
        {
           $picker = isset( $this->_fields[$field]['ext'] ) ? $this->_fields[$field]['ext'] : false;
           if( $picker )
              continue;

           $time = trim( $this->_fields[$field]['value'][0] );

           // Wenn kein Strlen mitgegeben, dann zurück
           if( !strlen( $time ) )
              continue;

         //Wenn falsches Format
         if( !ereg( "^([0-9]{2})$separator([0-9]{2})($separator([0-9]{2}))?$", $time ) )
          {
            $this->set_msg( 'err', 4, $field, '' );
            continue;
         }

         $arr_time = explode( $separator, $time );

         foreach( $arr_time AS $key => $value )
          {
            $check = $this->checktime( $value, $arr_format[$key] );

            if( $check == false )
             {
               $this->set_msg( 'err', 4, $field, '' );
               break;
            }
         }
       }
   }

   /******************************************************************************************************
       Check der Felder vom Typ code_icd
    ******************************************************************************************************/
    public function fields_code_icd( $fields )
    {
       // Wenn kein Array, dann zurück
       if( !is_array($fields) )
          return;

     // $icd10_table   = $_SESSION['lookup']['l_icd10'];

       //Validierung aus inkompatibilaetsgruenden zu alten Versionen entfernt

      /*
       foreach( $fields as $field )
        {
         $value = $this->_fields[$field]['value'][0];
          if( !strlen($value) )
             continue;


          $query  = "SELECT count(*) as count FROM $icd10_table WHERE code LIKE '$value'";

          $result = sql_query_array( $this->_db, $query );
          $count  = $result[0]['count'];

          if( $count == 0 )
           {
             $this->set_msg( 'err', 4, $field, $this->_msg['err_code_icd_notfound'] );
            break;
          }
          elseif( $count > 1 )
           {
             $this->set_msg( 'err', 4, $field, $this->_msg['err_code_icd_multiple'] );
            break;
          }

          //Erweiterung: Nur wenn Selectable auf 1 steht
          if( dlookup( $this->_db, $icd10_table, 'selectable', "code = '$value'") == 0 ){
            $this->set_msg( 'err', 4, $field, $this->_msg['err_code_icd_notfound'] );
            break;
          }
       }
       */
   }

   /******************************************************************************************************
    Check der Felder vom Typ code_o3
    ******************************************************************************************************/
    public function fields_code_o3( $fields )
    {
    // Wenn kein Array, dann zurück
    if( !is_array($fields) )
       return;

    foreach( $fields as $field )
     {
         $value = $this->_fields[$field]['value'][0];
       if( !strlen($value) )
          continue;

       $query  = "SELECT count(*) as count FROM l_icdo3 WHERE code LIKE '$value' AND sub_level IN ('c', 'v')";
       $query2  = "SELECT count(*) as count FROM vorlage_icdo WHERE code LIKE '$value'";

       $result = sql_query_array( $this->_db, $query );
       $count  = $result[0]['count'];

       $result2 = sql_query_array( $this->_db, $query2 );
       $count2  = $result2[0]['count'];

       if( $count == 0 && $count2 == 0 )
        {
          $this->set_msg( 'err', 4, $field, $this->_msg['err_code_icd_notfound'] );
            break;
       }
       elseif( $count > 1 || $count2 > 1 )
        {
          $this->set_msg( 'err', 4, $field, $this->_msg['err_code_icd_multiple'] );
            break;
       }
    }
   }

   /******************************************************************************************************
      Check der Felder vom Typ code_ops
   ******************************************************************************************************/
    public function fields_code_ops( $fields )
    {
       // Wenn kein Array, dann zurück
       if( !is_array($fields) )
          return;

      /* Validierung entfernt wegen inkompatibilaet mit alten OPS Versionen
      $ops_table  = 'l_ops';

       foreach( $fields as $field )
        {
         $value = $this->_fields[$field]['value'][0];
          if( !strlen($value) )
             continue;

          $query  = "SELECT count(*) as count FROM $ops_table WHERE code LIKE '$value'";
          $result = sql_query_array( $this->_db, $query );
          $count  = $result[0]['count'];

          if( $count == 0 )
           {
             $this->set_msg( 'err', 4, $field, $this->_msg['err_code_ops_notfound'] );
            break;
          }

          //Erweiterung: Nur wenn Selectable auf 1 steht
          if( dlookup( $this->_db, $ops_table, 'selectable', "code = '$value'") == 0 ){
            $this->set_msg( 'err', 4, $field, $this->_msg['err_code_ops_notfound'] );
            break;
         }
       }
       */
   }

   /******************************************************************************************************
      Check der Felder vom Typ code_ops
   ******************************************************************************************************/
    public function fields_code_nci( $fields )
    {
       // Wenn kein Array, dann zurück
       if( !is_array($fields) )
          return;
   }

   /******************************************************************************************************
       Min.-Max. Werte Funktion
    ******************************************************************************************************/
    public function fields_in_range( $fields, $_msg = '', $warn = false )
    {
      $msg_type = ($warn) ? 'warn' : 'err';
       if( is_array($fields) )
        {
           reset($fields);
           foreach( $fields AS $field )
            {
               if (array_key_exists($field, $this->_fields) === false){
                   continue;
               }

               $value      = trim($this->_fields[$field]['value'][0]);
               $key_warn   = ( $warn ) ? 'warn_range' : 'range';

               $_msg_field = ( isset( $this->_fields[$field][$key_warn]['msg'] ) ) ? $this->_fields[$field][$key_warn]['msg'] : '';
               $_curRange  = ( isset( $this->_fields[$field][$key_warn] )        ) ? $this->_fields[$field][$key_warn]        : '';

               if( !is_array( $_curRange ) )
                continue;

               $min            = $_curRange['min'];
               $max            = $_curRange['max'];
               $value      = str_replace( ',', '.', $value );

               if( ( is_numeric($value) AND ( ($value < $min) OR ($value > $max) ) ) AND strlen($value) )
                {
                   if( !$_msg )
                       $this->set_msg( $msg_type, 15, $field, $_msg_field );
                   else
                       $this->set_msg( $msg_type, 10 ,$field, $_msg );
            }
           }
       }
   }

   /*************************************************************************************************************/
   /****** Ab hier ist der Code noch nicht überarbeitet, Stand 24.01.2005                               *********/
   /*************************************************************************************************************/

   /******************************************************************************************************
      Stuffe 2 Prüfungen

      Check Fields condition AND
    ******************************************************************************************************/

    public function condition($condition, $msg = '')
    {
      $fields   = $this->_fields;

      // Such und Ersetzungsmuster
      $suchmuster[0] = '/\$(\w*)/i';
      $ersetzung[0]  = "\$fields['$1']['value'][0]";

      $condition = preg_replace( $suchmuster, $ersetzung, $condition );
      $cond      = '$result =  ('.$condition.');';

      $init = strpos($condition, "['") + 2;
      $end  = strpos($condition, "']");
      $field = substr($condition,$init, $end-$init);

      //critical.. but do what we want...
      if (array_key_exists($field, $this->_fields) === false) {
         return false;
      }

      eval($cond);

      return $result;
   }


   // Option Fields kommt normal über die Klasse
    public function condition_and($condition, $fill_list, $msg = '', $warn = false )
    {
      // initialisierung
      $msg_type = ($warn) ? 'warn' : 'err';
      $fields   = $this->_fields;

         // Such und Ersetzungsmuster
         $suchmuster[0] = '/\$(\w*)/i';
         $ersetzung[0]  = "\$fields['$1']['value'][0]";

         // Bedindung aufbereiten
         $condition = preg_replace( $suchmuster, $ersetzung, $condition );
         $cond      = '$valid_erg =  ('.$condition.');';

         //ob start for error handling
      ob_start();

      //Do eval()
      eval($cond);

      //possible error message for later ...
      $error = ob_get_contents();

      // clear buffer
      ob_end_clean();

      if (strlen($error) > 0) {
          return false;
      }

      if (isset($fill_list[!$valid_erg]) && strlen($fill_list[!$valid_erg]))
       {
          $fill_list_cond = $fill_list[!$valid_erg];
          $part = trim( strtok( $fill_list_cond, ' && ' ) );
          do
           {
              $suchm = "/(\w*)\[(\d*)..(\d*)\/(.*)\]/i";
              $ersetz     = "$1#$2#$3#$4";
              $feld_parameter = preg_replace( $suchm, $ersetz, $part );
              $feld_parameter = explode( '#', $feld_parameter );
              if( count( $feld_parameter ) > 1 )
               {
                  for( $c = $feld_parameter[1]; $c <= $feld_parameter[2]; $c++ )
                     $this->set_field_err( $feld_parameter[0].sprintf($feld_parameter[3], $c), $msg_type, $msg );
            }
              else
                    $this->set_field_err( $feld_parameter[0], $msg_type, $msg );
          }
          while( $part = strtok(' && ') );
      }
   }

   /******************************************************************************************************
      Check Fields condition or
    ******************************************************************************************************/
    public function condition_or ( $condition, $fill_list, $fill_count = 1 ,$msg = '' ,$warn = false )
    {
      $msg_type    = ($warn) ? 'warn' : 'err';
      $fields      = $this->_fields;
      $count       = 0;
      $felder_voll = array();
      $felder_leer = array();

        // Such und Ersetzungsmuster
        $suchmuster[0] = '/\$(\w*)/i';
        $ersetzung[0]  = "array_key_exists('$1', \$fields) === true && \$fields['$1']['value'][0]";

        // Bedindung aufbereiten
        $condition = preg_replace( $suchmuster, $ersetzung, $condition );
        $cond      = '$valid_erg =  ('.$condition.');';


        //ob start for error handling
        ob_start();

        //Do eval()
        eval($cond);

        //possible error message for later ...
        $error = ob_get_contents();

        // clear buffer
        ob_end_clean();

        if (strlen($error) > 0) {
            return false;
        }

      if( isset($fill_list[!$valid_erg]) and strlen($fill_list[!$valid_erg]) )
       {
          $fill_list_cond = $fill_list[!$valid_erg];
          $part = trim( strtok( $fill_list_cond, ' || ' ) );
          do
           {
              $suchm   = "/(\w*)\[(\d*)..(\d*)\/(.*)\]/i";
              $ersetz    = "$1#$2#$3#$4";
              $feld_parameter = preg_replace( $suchm, $ersetz, $part );
              $feld_parameter = explode( '#', $feld_parameter );
              if( count( $feld_parameter ) > 1 )
                  for( $c = $feld_parameter[1]; $c <= $feld_parameter[2]; $c++ )
                   {
                      $name = $feld_parameter[0].sprintf( $feld_parameter[3], $c );
                      if( strlen( $fields[$name]['value'][0] ) )
                       {
                          $count++;
                          $felder_voll[] = $name;
                      }
                      else
                         $felder_leer[] = $name;
                  }
              else
               {
                  $name = $feld_parameter[0];

                  if (array_key_exists($name, $this->_fields) === true) {
                     if( strlen( $fields[$name]['value'][0] ) )
                   {
                     $count++;
                     $felder_voll[] = $name;
                  }
                  else
                     $felder_leer[] = $name;
                  }
              }
          }
          while( $part = strtok(' || ') );

          if( $fill_count < 0 )
           {
             $fill_count *=-1;
             if( $count < $fill_count )
                 foreach( $felder_leer as $name )
                     $this->set_msg( $msg_type, 17, $name, $msg );
             elseif( $count > $fill_count )
                 foreach( $felder_voll as $name )
                     $this->set_msg( $msg_type, 12, $name, $msg );
         }
         else
          {
             if( $count < $fill_count )
                 foreach( $felder_leer as $name )
                     $this->set_msg( $msg_type, 18, $name, $msg );
         }
      }
   }

   /******************************************************************************************************
      Hilfsfunktion für condition and und condition or
    ******************************************************************************************************/
     public function set_field_err( $feld, $msg_type, $msg )
     {
        if( substr($feld, 0, 1) == '!' ) {
            $feld = substr($feld, 1);

            if (array_key_exists($feld, $this->_fields) === false) {
                return false;
            }

            if (strlen($this->_fields[$feld]['value'][0]) > 0) {
                $this->set_msg( $msg_type, 12, $feld, $msg );
            }
        } else {
            if (array_key_exists($feld, $this->_fields) === false) {
                return false;
            }

            if (strlen($this->_fields[$feld]['value'][0]) == 0) {
                $this->set_msg( $msg_type, 11, $feld, $msg );
            }
        }
    }

   /******************************************************************************************************
    * Funktion zum Prüfen ob Startdatum nicht größer ist als Enddatum
    ******************************************************************************************************/
    public function start_end_date( $arr_start_fields, $arr_end_fields, $msg = '', $same_date=true )
    {
       if( !is_array( $arr_start_fields ) and !is_array( $arr_end_fields ) )
        {
          echo 'Fehler bei der Funktion start_end_date( Parameter 1: Array mit DB Feldnamen, Parameter 2: Array mit DB Feldnamen )';
         return;
      }

       reset( $arr_start_fields );
       reset( $arr_end_fields );

       foreach( $arr_start_fields as $start_field )
        {
           list( , $end_field ) = each( $arr_end_fields );

           if( !isset( $this->_fields[$start_field] ) )
              continue;

           $start_value    = ( isset( $this->_fields[$start_field]['value'][0] ) )  ? $this->_fields[$start_field]['value'][0]   : '';
           $end_value         = ( isset( $this->_fields[$end_field]['value'][0] ) )    ? $this->_fields[$end_field]['value'][0]     : '';

             if( empty($start_value) or empty($end_value) )
                continue;

         todate( $start_value, 'en' );
         todate( $end_value  , 'en' );

           if( $start_value > $end_value AND $same_date )
            {
               if( $msg == '' )
                   $this->set_msg( 'err', 5, array( $start_field, $end_field ), '' );
               else
                   $this->set_msg( 'err', 10, array( $start_field, $end_field ), $msg );
           }
           elseif( $start_value >= $end_value AND !$same_date )
            {
              if( $msg == '' )
                   $this->set_msg( 'err', 5, array( $start_field, $end_field ), '' );
               else
                   $this->set_msg( 'err', 10, array( $start_field, $end_field ), $msg );
           }
       }
   }

   /******************************************************************************************************
    ******************************************************************************************************/
    public function checktime( $time, $format = 'h' )
    {
      if( $format == 'h'  )
         $return = ( $time >= 0 and $time < 24 ) ? true : false;
      elseif( $format == 'm' or $format == 's' )
         $return = ( $time >= 0 and $time < 60 ) ? true : false;

       return $return;
   }

}

?>
