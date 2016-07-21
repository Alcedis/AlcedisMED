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

require_once(DIR_LIB . '/alcedis/captcha/captcha.php');

class itemgenerator
{
    protected $_params = array();

    var $smarty;
    var $db;
    var $fields                 = array();
    var $config                 = array();

    protected $_configBuffer    = array();

    var $types                  = array();
    var $preselected            = false;
    var $empty_dropdown_element = true;

    var $empty_code             = '';
    var $empty_bez              = '&nbsp;';
    var $field_value            = '_value';
    var $field_bez              = '_bez';

    var $caption_undefined      = '<font class="star">ITEMGEN: CAPTION UNDEFINED IN CONFIG</font>';


    /******************************************************************************************************
     ******************************************************************************************************/
    public function __construct(&$smarty, &$db, &$fields, &$config)
    {
        $this->smarty    = &$smarty;
        $this->db         = &$db;
        $this->config   = &$config;
        $this->fields   = $this->initializeFields($fields);
        $this->types    = $this->_getTypes();
    }


    /**
     * set Param
     *
     * @param type $name
     * @param type $value
     * @return \itemgenerator
     */
    public function setParam($name, $value)
    {
        $this->_params[$name] = $value;

        return $this;
    }

    /**
     * getParam
     *
     * @param type $name
     * @return type
     */
    public function getParam($name)
    {
        return (array_key_exists($name, $this->_params) === true ? $this->_params[$name] : null);
    }

    /**
     *
     * @param type $fields
     * @return type
     */
    private function initializeFields($fields)
    {
        foreach ($fields as $name => $field) {
            $fields[$name]['value'][0] = convertHtmlspecialchars($field['value'][0]);
        }

        return $fields;
    }

    /**
     * workaround for parsing lbls of checkbox fields
     *
     * @param type $parentForm
     * @param type $label
     */
    private function _parseLbl($label)
    {
        if (str_starts_with($label, 'lbl-') === true) {
            $parts    = explode('-', $label);
            $form     = $parts[1];
            $lblparts = array();

            for ($i = 2; $i < count($parts); $i++) {
                $lblparts[] = $parts[$i];
            }

            $lbl = implode('-', $lblparts);

            if (array_key_exists($form, $this->_configBuffer) === true) {
                $config = $this->_configBuffer[$form];
            } else {
                $backup = $this->smarty->get_config_vars();

                $this->smarty->clear_config();

                //TODO
                $this->smarty->config_load("app/{$form}.conf", 'rec');
                $config = $this->_configBuffer[$form] = $this->smarty->get_config_vars();

                $this->smarty->set_config($backup);
            }

            $label = $config[$lbl];
        }

        return $label;
    }

    /******************************************************************************************************
     ******************************************************************************************************/
    private function _getTypes( )
    {
        $types['string']        = array();
        $types['email']         = array();
        $types['password']      = array();
        $types['int']           = array();
        $types['float']         = array();
        $types['date']          = array();
        $types['check']         = array();
        $types['radio']         = array();
        $types['time']          = array();
        $types['textarea']      = array();
        $types['captcha']       = array();
        $types['hidden']        = array();
        $types['lookup']        = array();
        $types['query']         = array();
        $types['picker']        = array();
        $types['query_ext']     = array();
        $types['code_icd']      = array();
        $types['code_ops']      = array();
        $types['code_o3']       = array();
        $types['code_qs']       = array();
        $types['code_nci']      = array();
        $types['code_ktst']     = array();

        reset($this->fields);

        foreach ($this->fields AS $name => $field){
            $field['type'] = isset( $field['type'] ) ? $field['type'] : '';
            $field['ext']  = isset( $field['ext'] )  ? $field['ext']  : '';

            $types[strtolower($field['type'])][] = $name;
        }

        return $types;
    }


    /******************************************************************************************************
     ******************************************************************************************************/
    function generate_elements( )
    {
        // Captions / Feldbezeichnungen für Felder
        $this->_setLabels();

        //Verschiedene Input Typen setzen
        $this->set_string();
        $this->set_password();
        $this->set_int();
        $this->set_float();
        $this->set_date();
        $this->set_email();
        $this->set_captcha();
        $this->set_check();
        $this->set_textarea();
        $this->set_hidden();
        $this->set_radio();
        $this->set_time();
        $this->set_lookup();
        $this->set_picker();
        $this->set_query();
        $this->set_query_ext();
        $this->set_code_icd();
        $this->set_code_o3();
        $this->set_code_qs();
        $this->set_code_ops();
        $this->set_code_nci();
        $this->set_code_ktst();
    }


    protected function _setLabels()
    {
        $disease = $this->getParam('disease');

        $redStar   = '<span style="font-family:Verdana, Arial;color:red ">*</span>';
        $blueStar  = '<span style="font-family:Verdana, Arial;color:#9400d3">*</span>';
        $plus      = '<span style="font-family:Verdana, Arial;color:#0000ff"><sup>+</sup></span>';

        foreach ($this->fields as $name => $field) {
            $extLabel = null;
            $label    = array_key_exists($name, $this->config) ? $this->config[$name] : $this->caption_undefined;

            if ($disease !== null) {
                $extName = "{$name}_ext_{$disease}";
                $extLabel = array_key_exists($extName, $this->config) ? $this->config[$extName] : null;
            }

            $label = $extLabel !== null ? $extLabel : $label;

            if (array_key_exists('highlight', $field) === true) {
                $label = $this->_wrapLabel($label);
            }

            $req = array_key_exists('req', $field) === true ? $field['req'] : 0;

            switch ($req) {
               case 1 : $label .= ' ' . $redStar;    break;
               case 2 : $label .= ' ' . $plus;       break;
               case 3 : $label .= ' ' . $redStar;    break;
               case 4 : $label .= ' ' . $blueStar;   break;
            }

           $this->smarty->assign("_{$name}_lbl", $label);
        }
    }

    /**
     * wraps label with highlighting for feature
     *
     * @param type $label
     * @return string
     */
    private function _wrapLabel($label)
    {
        return "<span class='highlight-feature'>{$label}</span>";
    }

    /******************************************************************************************************
     ******************************************************************************************************/
   function set_string($ty = 'string')
   {
      foreach($this->types[$ty] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $maxlen    = strlen(trim($field['maxlen']))  ? trim($field['maxlen'])    : 0; // Soll hier sicher eine Maxlen von 0 gesetzt werden?
           $size         = trim($field['size']);
           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           //Wenn size nicht gesetzt, dann anhand der Maxlen Standardwerte setzten
           if( !strlen($size) )
           {
              if( $maxlen >= 50 )
                  $size = 50;
              elseif( $maxlen >= 20 AND $maxlen < 50)
                  $size = 20;
              elseif( $maxlen > 0 AND $maxlen < 20)
                  $size = 10;
              else
                  $size = 1; // wozu das gibt es das irgendwo?
          }

          $output = '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" value="' . $value . '" size="' . $size .
                     '" maxlength="' . $maxlen . '"/>&nbsp;' . $error;
          $this->smarty->assign('_' . $name, $output);

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


   function set_email()
   {
        $this->set_string('email');
   }

   /******************************************************************************************************
    ******************************************************************************************************/
   function set_captcha( )
   {
       foreach($this->types['captcha'] AS $name)
       {
           //Attribute des jetzigen Feldes in $field legen
           $field = &$this->fields[$name];
           $error = $this->smarty->get_template_vars("err_$name");

           $captcha = captcha::create()
               ->setLength(5)
               ->generate()
           ;

           $_SESSION["captcha_{$name}"] = $captcha->getText();

           $output = "<img src='data:image/png;base64,{$captcha->render()}' alt='' title=''/><br/>
                      <input type='text' name='{$name}' size='15'>{$error}";

           $this->smarty->assign('_' . $name, $output);
       }
   }

    /******************************************************************************************************
     ******************************************************************************************************/
   function set_password( )
   {
      foreach($this->types['password'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size']))   ? trim($field['size'])      : 30;
           $maxlen    = strlen(trim($field['maxlen'])) ? trim($field['maxlen'])    : 30;

           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           $output = '<input ' . $mark . ' type="password" class="input' . $this->getFeatures($name) . '" name="' . $name . '" value="' . $value . '" size="' . $size .
                     '" maxlength="' . $maxlen . '"/>&nbsp;' . $error;
          $this->smarty->assign('_' . $name, $output);

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


    /******************************************************************************************************
     ******************************************************************************************************/
   function set_int( )
   {
      foreach($this->types['int'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size']))    ? trim($field['size'])     : 6;
           $maxlen    = strlen(trim($field['maxlen']))  ? trim($field['maxlen'])   : 6;

           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           $output = '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" value="' . $value . '" size="' . $size .
                     '" maxlength="' . $maxlen . '"/>&nbsp;' . $error;
          $this->smarty->assign( '_' . $name, $output );

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


    /******************************************************************************************************
     ******************************************************************************************************/
   function set_float( )
   {
      foreach($this->types['float'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         $nameMod = isset($field['name_mod']) ? $field['name_mod'] : '';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size'])) ? trim($field['size'])     : 6;
           $maxlen    = strlen(trim($field['maxlen'])) ? trim($field['maxlen']) : 6;

           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           $output = '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . (strlen($nameMod) ? $nameMod : $name) . '" value="' . $value . '" size="' . $size .
                     '" maxlength="' . $maxlen . '"/>&nbsp;' . $error;
          $this->smarty->assign('_' . $name, $output);

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


   /******************************************************************************************************
     ******************************************************************************************************/
   function set_date( )
   {
      foreach($this->types['date'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0])    : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size'])) ? trim($field['size'])     : 10;
           $maxlen    = 10;

           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

         $output = '<span><input ' . $mark . ' type="text" class="datepicker input' . $this->getFeatures($name) . '" name="' . $name .
                     '" id="' . $name . '" value="' . $value . '" size="' . $size . '" maxlength="' . $maxlen . '"/>&nbsp;' . $error . '</span>';


          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
          $this->smarty->assign( '_' . $name . $this->field_bez, todate($value, 'de'));
      }
   }


   /******************************************************************************************************
     ***************************************************************************************************
    *
    */
   function set_time( )
   {
      foreach($this->types['time'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size'])) ? trim($field['size'])     : 6;
           $maxlen    = 5;

           $ext       = $field['ext'];
           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           $picker    = isset( $ext ) ? $ext : false;

           if( $picker )
           {
              $output  = '<table style="margin:-15px 0px -4px -3px;"><tr><td>'.
                         '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" id="' . $name . '" value="' . $value . '" size="' . $size .
                         '" maxlength="' . $maxlen . '" readonly="readonly"/>' .
                         '</td>' .
                         '&nbsp;<td><img style="cursor:pointer;"  class="code-img" src="' . DIR_EXT . '/lib/javascript/timepicker/button.gif" align="middle" onclick="showTimePicker(this, ' . $name . ')"/></td>';
            $output .= "<td>$error</td></tr></table>";
           }
           else
           {
              $output  = '<input ' . $mark . ' type="text" class="input" name="' . $name . '" value="' . $value . '" size="' . $size .
                         '" maxlength="' . $maxlen . '"/>';
            $output .= '&nbsp;' . $error;
         }

          $this->smarty->assign( '_' . $name, $output );

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }

   /******************************************************************************************************
     ******************************************************************************************************/
   function set_textarea( )
   {
      foreach($this->types['textarea'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field     = &$this->fields[$name];

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $size         = strlen(trim($field['size'])) ? trim($field['size'])     : 3;
           $mark         = $this->smarty->get_template_vars("mark_$name");
           $class     = (strlen($mark)) ? $mark : 'class="txtarea' . $this->getFeatures($name) . '"';
           $error     = $this->smarty->get_template_vars("err_$name");

           $output= '<textarea ' . $class . ' name="' . $name . '" rows="' . $size . '" cols="' . $size . '">' . $value . '</textarea>' . $error;

          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


   /******************************************************************************************************
     ******************************************************************************************************/
   function set_hidden( )
   {
      foreach($this->types['hidden'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];

         $nameMod = isset($field['name_mod']) ? $field['name_mod'] : '';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $output = '<input type="hidden" name="' . (strlen($nameMod) ? $nameMod : $name) . '" value="' . $value . '"/>';

          $this->smarty->assign( '_' . $name, $output );

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


    /******************************************************************************************************
     ******************************************************************************************************/
   function set_check( )
   {
      foreach($this->types['check'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field   = &$this->fields[$name];

         //Attribute des
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $checked = ( $value==1 ) ?  'checked="checked"' : '';
           $bez     = ( $value==1 ) ?  'Ja'      : '';

           $mark       = $this->smarty->get_template_vars("mark_$name");
           $error   = $this->smarty->get_template_vars("err_$name");

           $class    = strlen(trim($this->getFeatures($name))) > 0 ? "class='" . $this->getFeatures($name) . "'" : '';

         $output  = '<input ' . $mark . ' type="checkbox"' . $class . ' name="' . $name . '" value="1" ' . $checked . '/>&nbsp;' . $error;

          $this->smarty->assign( '_' . $name, $output );

          $this->smarty->assign( '_' . $name . $this->field_value, $value );
          $this->smarty->assign( '_' . $name . $this->field_bez,   $bez );
      }
   }


    /******************************************************************************************************
     ******************************************************************************************************/
   function set_radio( )
   {
      foreach($this->types['radio'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];

         //Attribute des Feldes setzen
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $bez   = '';
           $ext   = $field['ext'];
           $mark     = $this->smarty->get_template_vars("mark_$name");
           $error = $this->smarty->get_template_vars("err_$name");
         $count = count( $ext );

         if( (!is_array($ext)) OR ($count <= 0) )
         {
               echo "Warning: Fehlende Parameter bei Radiobutton Eingabe Element ( $name ) array not exist or count <= 0<br/>";
               continue;
            }

         if (count($ext) == 1 && strlen(key($ext)) > 2) {
            $table       = key($ext);
            $klasse      = pos($ext);

            // Radioelemente aus Radio Tabelle holen
              $query   = "SELECT code, bez FROM $table WHERE klasse='$klasse' ORDER BY pos";
               $result  = sql_query_array($this->db, $query);
            $count   = count( $result );
            $ext     = array();

               foreach ($result as $cur) {
               $ext[$cur['code']] = $cur['bez'];
               }
            }

            $output_concat = '';

         foreach( $ext AS $radio_value => $radio_bez )
         {
            $count--;
                $checked = ( $value==$radio_value AND strlen( $value ) ) ? 'checked="checked"' : '';

                if( strlen($checked) > 0)
                   $bez     = $radio_bez;

            $output = '<input ' . $mark . ' type="radio" class="chbkx' . $this->getFeatures($name) . '" name="' . $name . '" value="' . $radio_value . '" ' . $checked . '/>&nbsp;' . $radio_bez . (( $count==0 ) ? '&nbsp;' . $error : '');

             $this->smarty->assign('_' . $name . '_' . $radio_value, $output);

             $output_concat .= $output;
         }

         $this->smarty->assign( '_' . $name . $this->field_value  , $value );
         $this->smarty->assign( '_' . $name . $this->field_bez    , $bez );
         $this->smarty->assign( '_' . $name                       , $output_concat );
      }
   }

   private function getFeatures($fieldName) {

      $features = '';

      if (array_key_exists($fieldName, $this->fields) == true) {
         $field = $this->fields[$fieldName];

         if (array_key_exists('features', $field) === true) {
            foreach ($field['features'] as $feature) {
               $features .= ' i_' . $feature;
            }
         }
      }

      return $features;
   }



    /******************************************************************************************************
     ******************************************************************************************************/
    function set_lookup( )
   {
      foreach($this->types['lookup'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];

         $nameMod = isset($field['name_mod']) === true ? $field['name_mod'] : '';

         // Attribute des Feldes setzen
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }


           $ext   = $field['ext'];
           $mark     = $this->smarty->get_template_vars("mark_$name");
           $error = $this->smarty->get_template_vars("err_$name");
           $emptyField = isset($field['emptyField']) ? $field['emptyField'] : true;

         if( !is_array( $ext ) )
            {
               echo "Warning: Fehlende Parameter bei Lookup Eingabe Element ( $name ).";
               continue;
            }

         // Lookup Tabelle
            $table  = key($ext);
            // Lookup Klasse
            $klasse = pos($ext);

            // Wenn keine Lookuptabelle oder keine Lookupklasse dann abbrechen
            if( !strlen($table) OR !strlen($klasse) )
            {
               echo "Warning: Mangelhafte Informationen zu Lookup Eingabe Element ( $name ).";
               continue;
            }

            $lookup = $this->create_lookup($mark, (strlen($nameMod) ? $nameMod : $name), $table, $klasse, $value, $emptyField);

         $output = $lookup['html'] . $error;

         $this->smarty->assign( '_' . $name , $output );
         $this->smarty->assign( '_' . $name . $this->field_value, $value );
         $this->smarty->assign( '_' . $name . $this->field_bez,   $lookup['bez'] );
      }
   }


   function create_lookup($mark, $name, $table, $class, $value, $emptyField = true)
   {
      $bez   = '';

      //Dropdownelemente aus Lookup Tabelle holen

      $query   = "SELECT code, bez FROM $table WHERE klasse='$class' ORDER BY pos";

      $result  = sql_query_array($this->db, $query);

      $output  = '<select ' . $mark . ' class="input' . $this->getFeatures($name) . '" name="' . $name . '">';

      if ($this->empty_dropdown_element && $emptyField === true) {
            $output .= '<option value="' . $this->empty_code . '">' . $this->empty_bez . '</option>';
      }

      //Einträge der Klasse in das Dropdown legen
      foreach ($result AS $dataset)
      {
         if( $value == $dataset['code'] AND strlen($value)){
            $output .= '<option selected="selected" value="' . $dataset['code'] . '">' . $dataset['bez'] . '</option>';
            $bez     = $dataset['bez'];
         } else {
               $output .= '<option value="' . $dataset['code'] . '">' . $dataset['bez'] . '</option>';
            }
      }

      $output .= '</select>';

      return array('bez' => $bez, 'html' => $output);
   }


    /******************************************************************************************************
     ******************************************************************************************************/
   function set_query( )
   {
      foreach($this->types['query'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];

         // Attribute des Feldes setzen
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $bez         = '';
           $ext         = $field['ext'];
           $mark           = $this->smarty->get_template_vars("mark_$name");
           $error       = $this->smarty->get_template_vars("err_$name");
           $emptyField  = isset($field['emptyField']) ? $field['emptyField'] : true;
         $textLength  = isset($field['textLength']) ? $field['textLength'] : 70;

         if( !strlen( $ext ) )
            {
               echo "Warning: Leere Query bei Query Element";
               return false;
            }

         // Dropdownelemente aus Lookup Tabelle holen
            $output = '<select ' . $mark . ' class="input' . $this->getFeatures($name) . '" name="' . $name . '">';

            // Wenn leeres Dropdownelement erwünscht, dann setzen
           if($this->empty_dropdown_element && $emptyField === true)
              $output .= '<option value="' . $this->empty_code . '">' . $this->empty_bez . '</option>';

            $query  = $ext;

            //Query bearbeiten wenn Flag gesetzt //JCH
            $preselected = isset($field['preselect']) == true ? $field['preselect'] : false;
         if ($preselected !== false && strpos($query, 'WHERE') !== false && strlen($value) > 0) {
            $replace = "WHERE ({$preselected} = '{$value}') OR ";
            $query = str_replace('WHERE', $replace, $query);
         }

            $result = sql_query_array( $this->db, $query, MYSQL_NUM );

           // Einträge der Klasse in das Dropdown legen
           foreach ($result AS $dataset) {
              $count_elements = count($dataset);

              //selected
              if( $value == $dataset[0] AND strlen($value))
              {
                 $output .= '<option value="' . $dataset[0] . '" selected="selected">';

                 for ($nIndex = 1; $nIndex < $count_elements; $nIndex++){
                 $lbl = $this->_parseLbl($dataset[$nIndex]);

                 if (strlen($lbl) > 0) {
                     $output_arr[] = $lbl;
                 }
                 }

                 $string = implode(', ', $output_arr);
              $string = strlen($string) > $textLength ? substr($string, 0, $textLength) . '...' : $string;

                 $output .= $string. '</option>';

                 $bez     = implode(', ', $output_arr);
              } else {
                 //unselected
                 $output .= '<option value="' . $dataset[0] . '">';

                 for ($nIndex=1; $nIndex < $count_elements; $nIndex++) {
                    $lbl = $this->_parseLbl($dataset[$nIndex]);

                 if (strlen($lbl) > 0) {
                     $output_arr[] = $lbl;
                 }
                 }

                 $string = implode(', ', $output_arr);
                 $string = strlen($string) > $textLength ? substr($string, 0, $textLength) . '...' : $string;

                 $output .=  $string . '</option>';
              }
              unset($output_arr);
           }

         $output .= '</select>&nbsp;' . $error;
         $this->smarty->assign( '_' . $name , $output );

         $this->smarty->assign( '_' . $name . $this->field_value, $value );
         $this->smarty->assign( '_' . $name . $this->field_bez,   $bez );

      } // foreach
   }

    /******************************************************************************************************
     ******************************************************************************************************/
   function set_picker( )
   {
      foreach($this->types['picker'] AS $name)
      {
        //Attribute des jetzigen Feldes in $field legen
        $field = &$this->fields[$name];

         // Attribute des Feldes setzen
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $bez   = '';
           $ext   = $field['ext'];
           $size  = $field['size'];
           $plain = isset($ext['plain']) && $ext['plain'] === true ;
           $query = $ext['query'];
           $type  = $ext['type'];
           $table = isset($ext['table']) ? $ext['table'] : false;
           $multi = isset($ext['multi']) ? $ext['multi'] : false ;

           $mark  = $this->smarty->get_template_vars("mark_$name");
           $error = $this->smarty->get_template_vars("err_$name");

           $textLength = 80;

        if( !strlen( $query ) ) {
            echo "Warning: Leere Query bei Query Element";
            return false;
        }

        //build dropdown
        if ($plain === false) {

         // Dropdownelemente aus Lookup Tabelle holen
            $output = '<select style="width:' . $size . 'px;" ' . $mark . 'class="input' . $this->getFeatures($name) . '" name="' . $name . '">';


            // Wenn leeres Dropdownelement erwünscht, dann setzen
           if($this->empty_dropdown_element)
              $output .= '<option value="' . $this->empty_code . '">' . $this->empty_bez . '</option>';

         //Query bearbeiten wenn Flag gesetzt //JCH
         $preselected = isset($field['preselect']) == true ? $field['preselect'] : false;
         if ($preselected !== false && strpos($query, 'WHERE') !== false && strlen($value) > 0) {
            $replace = "WHERE ({$preselected} = '{$value}') OR ";
            $query = str_replace('WHERE', $replace, $query);
         }

            $result = sql_query_array( $this->db, $query, MYSQL_NUM );

               // Einträge der Klasse in das Dropdown legen
               foreach( $result AS $dataset)
               {
                  $count_elements = count($dataset);

                  if ( $value == $dataset[0] AND strlen($value)) {
                     //Index
                     $output .= '<option value="' . $dataset[0] . '" selected="selected">';

                     //Values
                     for ($nIndex = 1; $nIndex < $count_elements; $nIndex++) {
                         $output_arr[] = $dataset[$nIndex];
                     }

                     $outputString = implode(', ', $output_arr);

                     $outputString = strlen($outputString) > $textLength ? substr($outputString, 0, $textLength) . '...' : $outputString;

                     $bez = $outputString;

                  } else {
                    //Index
                    $output .= '<option value="' . $dataset[0] . '">';

                    for ($nIndex=1; $nIndex < $count_elements; $nIndex++) {
                           $output_arr[] = $dataset[$nIndex];
                       }

                       $outputString = implode(', ', $output_arr);

                       $outputString = strlen($outputString) > $textLength ? substr($outputString, 0, $textLength) . '...' : $outputString;
                  }

                  $output .=  $outputString . '</option>';

                  unset($output_arr);
               }

            $output .= '</select>&nbsp;';
        } else {
            $output = '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" size="' . $size . '" value="' . $value . '"/>&nbsp;';
        }

         if ($multi == true) $multi = ", true"; else $multi = ', false';
         if ($plain == true) $plain = ", true"; else $plain = ', false';

         //codepicker image setzten
         $output .= "<img alt='picker-$type' src='media/img/base/list-view.png' class='picker-img' onclick=\"openPicker('$type', '$name'{$multi}{$plain}, '$table');\"/>";

         $output .= $error;

         $this->smarty->assign( '_' . $name , $output );

         $this->smarty->assign( '_' . $name . $this->field_value, $value );
         $this->smarty->assign( '_' . $name . $this->field_bez,   $bez );

      } // foreach
   }

   /******************************************************************************************************
     ******************************************************************************************************/
   function set_query_ext( )
   {
      foreach($this->types['query_ext'] AS $name)
      {
         //Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];

         // Attribute des Feldes setzen
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';

           $bez   = '';
           $ext   = $field['ext'];
           $mark     = $this->smarty->get_template_vars("mark_$name");
           $error = $this->smarty->get_template_vars("err_$name");

         if( count( $ext ) <= 1 )
            {
               echo "Warning: Fehlende Parameter im Feldelement -> " . $name . " -> 'ext' Bereich";
               return false;
            }

         $typ        = $ext['typ'];
         $permit     = isset( $ext['permit']) ? $ext['permit'] : array();

         switch( $typ )
         {              // 0 = Studie, 1 = Therapie
            case 0:
               $tbl_l_vorlage             =  $_SESSION['db_tables']['tbl_vorlagen_studie'];
               $tbl_l_vorlage_indikation  =  $_SESSION['db_tables']['tbl_l_vorlagen_indikation'];
               $org_id                    =  $_SESSION['sess_org_id'];

               $query                     =  "SELECT studie.studie_id, studie.name FROM $tbl_l_vorlage studie, " .
                                             "$tbl_l_vorlage_indikation vorlagen_indikation WHERE " .
                                             "studie.org_id = '$org_id' AND studie.indikation=vorlagen_indikation.code AND (";

               if( count( $permit) == 0 ){
                  $query   = $query . ' 1 ';
               }else{
                  foreach( $permit as $index => $indikator )
                  {
                     if( $index != 0 )
                        $query = $query . ' OR ';

                     $query = $query . "vorlagen_indikation.grp='" . $indikator . "'";
                  }
               }
               $query   =  $query . ") AND ( studie.aktiv = 't' OR studie.studie_id = '$value') ORDER BY studie.name" ;
            break;
            case 1:
               //Wird später noch umgesetzt
               $tbl_l_vorlage    =  $_SESSION['db_tables']['tbl_vorlagen_therapie'];
            break;
         }
         $result  =  sql_query_array( $this->db, $query, MYSQL_NUM );

         // Dropdownelemente aus Lookup Tabelle holen
            $output = '<select ' . $mark . ' class="input' . $this->getFeatures($name) . '" name="' . $name . '">';

         //Leerer Eintrag
         $output .= '<option value="' . $this->empty_code . '">' . $this->empty_bez . '</option>';

           // Einträge der Klasse in das Dropdown legen
           foreach( $result AS $dataset)
           {
              $count_elements = count($dataset);
              if( $value == $dataset[0] AND strlen($value))
              {

                 $output .= '<option value="' . $dataset[0] . '" selected="selected">';
                 for($nIndex=1; $nIndex<$count_elements; $nIndex++)
                    $output_arr[] = $dataset[$nIndex];
                 $output .= implode(', ', $output_arr) . '</option>';

                 $bez     = implode(', ', $output_arr);
              }
              else
              {
                 $output .= '<option value="' . $dataset[0] . '">';
                 for($nIndex=1; $nIndex<$count_elements; $nIndex++)
                    $output_arr[] = $dataset[$nIndex];
                 $output .= implode(', ', $output_arr).'</option>';
              }
              unset($output_arr);
           }

         $output .= '</select>&nbsp;' . $error;
         $this->smarty->assign( '_' . $name , $output );

         $this->smarty->assign( '_' . $name . $this->field_value, $value );
         $this->smarty->assign( '_' . $name . $this->field_bez,   $bez );

      } // foreach
   }

   /******************************************************************************************************
    ******************************************************************************************************/
   function set_code_icd()
   {
      foreach( $this->types['code_icd'] AS $name )
      {
         // Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];
         $ext   = $field['ext'];
         $mark  = $this->smarty->get_template_vars("mark_$name");
         $error = $this->smarty->get_template_vars("err_$name");
         $showSide = isset($ext['showSide']) ? $ext['showSide'] : false;
         $showText = isset($ext['showText']) ? $ext['showText'] : true;

         $vorauswahl = '';
         if( isset($ext['vorauswahl']) )
            $vorauswahl = $ext['vorauswahl'];

         $page = 'code_icd';
         if( strlen($vorauswahl) )
            $page = 'code_icd_vorauswahl';

         $sub_form = '';
         if( isset($ext['sub_form']) )
            $sub_form = 'sub_form=true&amp;';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $vers = $this->fields[$name . '_version'];
           $vers_value = isset($vers['value'][0]) ? trim($vers['value'][0]) : '';

           $code_value = $value;

           //Beschreibung des Feldes
           if ($showText) {
               $description = $this->fields[$name . '_text'];

            $description_value = isset($description['value'][0]) ? trim($description['value'][0]) : '';
            $description_error = $this->smarty->get_template_vars("err_$name" . '_text');
           } else {
              $description_value = dlookup($this->db, 'l_icd10', 'description', "code = '$value'");
           }

         //Seite des Feldes

         if ($showSide == true) {
            $side          = $this->fields[$name . '_seite'];
            $side_error    = $this->smarty->get_template_vars("err_$name" . '_seite');
            $side_ext      = $side['ext'];
            $side_table    = key($side_ext);
            $side_class    = pos($side_ext);

            // Wenn keine Lookuptabelle oder keine Lookupklasse dann abbrechen
            if( !strlen($side_table) OR !strlen($side_table) )
            {
               echo "Warning: Mangelhafte Informationen zu Lookup Eingabe Element ( $name . '_seite' ).";
               continue;
            }

            $side_value = isset($side['value'][0]) ? trim($side['value'][0]) : '';
            $lookup = $this->create_lookup($mark, $name . '_seite', $side_table, $side_class, $side_value);
         }

         $output  = "<div style='vertical-align: bottom;'>";
            $output .= '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" size="5" value="' . $code_value . '"/>&nbsp;';

          $version = $this->config['lbl_version'] . " <input type='text' name='{$name}_version' size='5' value='{$vers_value}'/> ";
          $version .= "<input type='hidden' name='{$name}_default_version'  value='{$this->config['code_icd_version']}'/>";

            if( strlen($error) )
               $output .= $error . '&nbsp;';


            if ($showSide == true) {
               $output .= $lookup['html'] . $side_error . '&nbsp;';
            }

            $output .= $version;

            $output .= "<a onclick='openCodepicker(\"index.php?codepicker=true&amp;type=icd&amp;txtfield=$name&amp;" . $sub_form;
            $output .= "page=$page&amp;vorauswahl=$vorauswahl\", this)'>" . $this->config['img_code'] . "</a>&nbsp;";
            $output .= '<a onclick="resetCodepickerCode(\'' . $name . '\', this);">' . $this->config['img_code_delete'] . '</a>';


              if ($showText) {
                  $output .= '<br/><div style="margin-top:5px">';
                  $output .= '<textarea class="txtarea' . $this->getFeatures($name . '_text') . '" cols="2" rows="2"' . $mark . ' name="' . $name . '_text" >' . $description_value . '</textarea>' . $description_error . '</div>';
              }
           $output .= '</div>';



          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }

   /******************************************************************************************************
    ******************************************************************************************************/
   function set_code_nci()
   {
      foreach( $this->types['code_nci'] AS $name )
      {
         // Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];
         $mark  = $this->smarty->get_template_vars("mark_$name");
         $error = $this->smarty->get_template_vars("err_$name");
         $table = 'l_nci';

         $page = 'code_nci';

         $sub_form = '';
         if( isset($ext['sub_form']) )
            $sub_form = 'sub_form=true&amp;';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           $code_value = $value;

           //Beschreibung des Feldes
           $description = strlen($value) ? dlookup($this->db, $table, 'bez', "code='$value'") : '';

         $output  = '<div style="vertical-align: bottom;">';
            $output .= '<input ' . $mark . ' type="hidden" class="input' . $this->getFeatures($name) . '" name="' . $name . '" size="11" value="' . $code_value . '"/> ';
            $output .= '<a onclick="openCodepicker(\'index.php?codepicker=true&amp;type=nci&amp;txtfield=' . $name . '&amp;';
            $output .= 'page=' . $page . '&amp;' . $sub_form . '\', this)">' . $this->config['img_code'] . '</a>&nbsp;';
            $output .= '<a onclick="resetCodepickerCode(\'' . $name . '\', this);">' . $this->config['img_code_delete'] . '</a>';

            if( strlen($error) )
                 $output .= '&nbsp;' . $error;

              $output .= '<div style="margin-top:5px">';
              $output .= '<span ' . $mark . ' id="' . $name . '_text" ><strong>' . $description . '&nbsp;</strong></span></div>';

           $output .= '</div>';

          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }

   /******************************************************************************************************
    ******************************************************************************************************/

   function set_code_o3()
   {
      foreach( $this->types['code_o3'] AS $name )
      {
         // Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];
         $ext   = $field['ext'];

         $table = 'l_icdo3';
         $type  = isset($ext['type']) ? $ext['type'] : '';
         $mark  = $this->smarty->get_template_vars("mark_$name");
         $error = $this->smarty->get_template_vars("err_$name");
         $showSide = isset($ext['showSide']) ? $ext['showSide'] : false;

         $vorauswahl = '';
         if( isset($ext['vorauswahl']) )
            $vorauswahl = $ext['vorauswahl'];

         $r_exp = false;
         if( isset($ext['r_exp']) )
            $r_exp = true;

         $gruppen = false;
         if( isset($ext['gruppen']) )
            $gruppen = true;

         $spez_gruppen = false;
         if( isset($ext['spez_gruppen']) )
            $spez_gruppen = true;

         $sub_form = '';
         if( isset($ext['sub_form']) )
            $sub_form = 'sub_form=true&amp;';

         $code = '';
         $seite = false;
         $page = 'code_o3';
         if (strlen($type) && $type == 't'){
            $page = 'code_o3_gruppen';
            $seite = true;
            $code = '&amp;code=diag&amp;o3_type=t';
         }
         if ($showSide) {
            $seite = true;
         }
         if (strlen($type) && $type == 'm'){
            $code = '&amp;o3_type=m';
         }
         if( strlen($vorauswahl) )
            $page = 'code_o3_vorauswahl';

         //Seite des Feldes
         if ($seite) {
            $side          = $this->fields[$name . '_seite'];
            $side_error    = $this->smarty->get_template_vars("err_$name" . '_seite');
            $side_ext      = $side['ext'];
            $side_table    = key($side_ext);
            $side_class    = pos($side_ext);

            // Wenn keine Lookuptabelle oder keine Lookupklasse dann abbrechen
            if( !strlen($side_table) OR !strlen($side_table) )
            {
               echo "Warning: Mangelhafte Informationen zu Lookup Eingabe Element ( $name . '_seite' ).";
               continue;
            }

            $side_value = isset($side['value'][0]) ? trim($side['value'][0]) : '';
            $lookup = $this->create_lookup($mark, $name . '_seite', $side_table, $side_class, $side_value);
         }

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

          $vers = $this->fields[$name . '_version'];
          $vers_value = isset($vers['value'][0]) ? trim($vers['value'][0]) : '';

         $value_code = dlookup( $this->db, $table, 'code'       , "id='$value'" );
           $value_bez  = dlookup( $this->db, $table, 'description', "id='$value'" );
           $value_show = strlen($value_code) ? $value_code . ' - ' . $value_bez : '';

           //Beschreibung des Feldes
         $description = $this->fields[$name . '_text'];
         $description_value = isset($description['value'][0]) ? trim($description['value'][0]) : '';
         $description_error = $this->smarty->get_template_vars("err_$name" . '_text');

         $version = $this->config['lbl_version'] . " <input type='text' name='{$name}_version' size='5' value='{$vers_value}'/> ";
         $version .= "<input type='hidden' name='{$name}_default_version'  value='{$this->config['code_o3_version']}'/>";

            // Button erstellen
            $output  = "<div style='vertical-align: bottom;'>";
            $output .= '<input class="input' . $this->getFeatures($name) . '" size="5" ' . $mark . ' type="text" name="' . $name . '" value="' . $value . '"/>&nbsp;';
            if (strlen($error) > 0) {
               $output .= $error . '&nbsp;';
            }

            if ($seite) {
               $output .= $lookup['html'] . $side_error  . '&nbsp;';
            }

            $output .= $version;

            $output .= '<a onclick="openCodepicker(\'index.php?codepicker=true&amp;type=o3' . $code . '&amp;txtfield=' . $name . '&amp;' . $sub_form;
            $output .= 'page=' . $page . '&amp;r_exp=' . $r_exp . '&amp;gruppen=' . $gruppen . '&amp;vorauswahl=' . $vorauswahl . '\', this)">' . $this->config['img_code'] . "</a>&nbsp;";
            $output .= '<a onclick="resetCodepickerCode(\''. $name .'\', this);">' . $this->config['img_code_delete'] . "</a>";

            $output .= '<br/><div style="margin-top:5px">';
            $output .= '<textarea class="txtarea' . $this->getFeatures($name . '_text') . '" cols="3" rows="2"' . $mark . ' name="' . $name . '_text" >' . $description_value . '</textarea>' . $description_error . '</div>';
          $output .= '</div>';

          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }

   /******************************************************************************************************
    ******************************************************************************************************/
   function set_code_ops()
   {
      foreach( $this->types['code_ops'] AS $name )
      {
         // Attribute des jetzigen Feldes in $field legen
         $field = &$this->fields[$name];
         $ext   = $field['ext'];
         $table = 'l_ops';
         $mark  = $this->smarty->get_template_vars("mark_$name");
         $size  = strlen($field['size']) ? $field['size'] : 7;
         $showSide = isset($ext['showSide']) ? $ext['showSide'] : false;
         $showText = isset($ext['showText']) ? $ext['showText'] : true;

         $vorauswahl = '';
         if( isset($ext['vorauswahl']) )
            $vorauswahl = $ext['vorauswahl'];

         $show_inputfield = false;
         if( isset($ext['show_inputfield']) )
             $show_inputfield = $ext['show_inputfield'];

         $sub_form = '';
         if( isset($ext['sub_form']) )
            $sub_form = 'sub_form=true&amp;';

         $page = 'code_ops';
         if( strlen($vorauswahl) )
            $page = 'code_ops_vorauswahl';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           if ($showSide === true) {
              $side          = $this->fields[$name . '_seite'];
            $side_error    = $this->smarty->get_template_vars("err_$name" . '_seite');
            $side_ext      = $side['ext'];
            $side_table    = key($side_ext);
            $side_class    = pos($side_ext);

            // Wenn keine Lookuptabelle oder keine Lookupklasse dann abbrechen
            if( !strlen($side_table) OR !strlen($side_table) )
            {
               echo "Warning: Mangelhafte Informationen zu Lookup Eingabe Element ( $name . '_seite' ).";
               continue;
            }

            $side_value = isset($side['value'][0]) ? trim($side['value'][0]) : '';
            $lookup = $this->create_lookup($mark, $name . '_seite', $side_table, $side_class, $side_value);
           }

           $value_headline = strtok( $value, '.');
           $headline  = dlookup( $this->db, $table, 'description', "code='$value_headline'" );
           $value_bez = dlookup( $this->db, $table, 'description', "code='$value'" );

           $mark         = $this->smarty->get_template_vars("mark_$name");
           $error     = $this->smarty->get_template_vars("err_$name");

           //Beschreibung des Feldes
           if ($showText) {
             $description = $this->fields[$name . '_text'];
             $description_value = isset($description['value'][0]) ? trim($description['value'][0]) : '';
             $description_error = $this->smarty->get_template_vars("err_$name" . '_text');
           }

           $vers = $this->fields[$name . '_version'];
           $vers_value = isset($vers['value'][0]) ? trim($vers['value'][0]) : '';

           $version = $this->config['lbl_version'] . " <input type='text' name='{$name}_version' size='5' value='{$vers_value}'/> ";
        $version .= "<input type='hidden' name='{$name}_default_version'  value='{$this->config['code_ops_version']}'/>";

            // Button erstellen
            $output  = "<div style='vertical-align: bottom;'>";

            $output .= '<input ' . $mark . ' type="text" class="input' . $this->getFeatures($name) . '" name="' . $name . '" size="' . $size . '" value="' . $value . '"/>' . '&nbsp;';

            if (strlen($error) > 0) {
               $output .= $error . '&nbsp;';
            }

            if ($showSide === true) {
               $output .= $lookup['html'] . $side_error . '&nbsp;';
            }

            $output .= $version;


            $output .= "<a onclick='openCodepicker(\"index.php?codepicker=true&amp;type=ops&amp;txtfield=$name&amp;".$sub_form;
            $output .= "page=$page&amp;vorauswahl=$vorauswahl\", this)'>" . $this->config['img_code'] . "</a>&nbsp;";
            $output .= '<a onclick="resetCodepickerCode(\'' . $name . '\', this);">' . $this->config['img_code_delete'] . "</a>";


            if ($showText) {
                $output .= '<br/><div style="margin-top:5px">';
                $output .= '<textarea class="txtarea ' . $this->getFeatures($name . '_text') . '" cols="3" rows="2"' . $mark . ' name="' . $name . '_text" >' . $description_value . '</textarea>' . $description_error .  '</div>';
            }

          $output .= '</div>';

          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
      }
   }


   /******************************************************************************************************
    ******************************************************************************************************/
   function set_code_ktst()
   {
      foreach( $this->types['code_ktst'] AS $name )
      {
         // Attribute des jetzigen Feldes in $field legen
         $field  = &$this->fields[$name];
         $ext    = $field['ext'];
         $table  = 'l_ktst';
         $table2 = 'vorlage_krankenversicherung';
         $page   = 'code_ktst';
         $value_bez = '';

         //Attribute des Feldes
           $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';
           if( $this->preselected == true )
           {
              if( !strlen( $value ) )
                 $value = isset( $field['preselected'] ) ? trim( $field['preselected'] ) : $value;
           }

           if (strlen($value) > 0) {

              $vorlageKv = dlookup($this->db, $table2, 'name', "iknr='$value'");

              $value_bez = strlen($vorlageKv)
               ? $vorlageKv
               : dlookup($this->db, $table, 'name', "iknr='$value'");
         }

           $mark     = $this->smarty->get_template_vars("mark_$name");
           $error = $this->smarty->get_template_vars("err_$name");

         // Button erstellen
         $output  = "<div style='vertical-align: bottom;'>";
         $output .= "<a onclick=\"openCodepicker('index.php?codepicker=true&amp;txtfield=$name&amp;";
         $output .= "page=$page&amp;type=ktst', this)\">" . $this->config['img_code'] . "</a>&nbsp;";
         $output .= "<a onclick=\"resetCodepickerCode('". $name ."', this)\">" . $this->config['img_code_delete'] . "</a><br/>";
           $output .= '<input ' . $mark . ' type="hidden" class="input' . $this->getFeatures($name) . '" name="' . $name . '" value="' . $value . '"/>';
           $output .= '<span id="' . $name . '_text" style="font-weight:bold; font-size:10pt;"><!-- -->' . $value_bez . '</span>';
           if( strlen($error) )
              $output .= '&nbsp;' . $error;

         $output  .= "</div>";

          $this->smarty->assign( '_' . $name, $output );
          $this->smarty->assign( '_' . $name . $this->field_value, $value );
          $this->smarty->assign( '_' . $name . $this->field_bez,   $value_bez );
      }
   }

    function set_code_qs()
    {
        foreach ($this->types['code_qs'] AS $name) {
            // Attribute des jetzigen Feldes in $field legen
            $field = &$this->fields[$name];
            $ext   = $field['ext'];

            $table = reset(array_keys($ext));
            $class = reset($ext);

            $mark  = $this->smarty->get_template_vars("mark_$name");
            $error = $this->smarty->get_template_vars("err_$name");

            $size  = $field['size'];

            $bez   = '';
            $value = isset($field['value'][0]) ? trim($field['value'][0]) : '';

            if ($this->preselected == true) {
                if (!strlen($value)) {
                    $value = isset($field['preselected']) ? trim($field['preselected']) : $value;
                }
            }

            $params = $class;

            $lookup = $this->create_lookup($mark, $name, $table, $class, $value);

            $output = "<div style='margin-bottom: 7px'><img alt='picker-qs' src='media/img/base/list-view.png' class='picker-img' onclick=\"openPicker('qs', '$name', '','', '{$table}=>{$class}');\"/></div>";
            $output .= $lookup['html'];

            $output .= $error;

            $this->smarty->assign( '_' . $name , $output );
            $this->smarty->assign( '_' . $name . $this->field_value, $value );
            $this->smarty->assign( '_' . $name . $this->field_bez,   $lookup['bez'] );
        }
    }
}

?>
