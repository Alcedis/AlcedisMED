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

class widget
{
   protected $_fields            = array();

   /**
    * inherits all fields
    *
    * @var unknown_type
    */
   protected $_templateFields    = null;

   protected $_ghostFields       = array();

   protected $_selector          = null;

   protected $_db                = null;

   protected $_statusFields      = array();

   protected $_widgetLoadParam   = array();

   protected $_page              = '';

   protected $_view              = array();

   protected $_diseaseCodings    = array();


   public function __construct($db, $fields, $page, $selector, $widgetLoadParam = array())
   {
      $this->_selector        = $selector;
      $this->_db              = $db;
      $this->_page            = $page;
      $this->_widgetLoadParam = $widgetLoadParam;

      $this->setFields($fields, $selector);
      $this->_init();

      return $this;
   }

   /**
    * return fields of the form with all relevant parameter
    *
    * @param unknown_type $db
    * @param unknown_type $statusId
    */
   public static function getFieldsFromStatus($db, $status)
   {
       //given params, or given id for searching
       if (is_array($status) === true) {
          extract($status);
       } else {
           $query = "
               SELECT
                   s.patient_id,
                   e.erkrankung_id,
                   e.erkrankung,
                   s.form
               FROM `status` s
                   LEFT JOIN erkrankung e  ON e.erkrankung_id = IF(s.form = 'erkrankung', s.form_id, s.erkrankung_id)
               WHERE
               s.status_id = '{$status}'
           ";

           extract(reset(sql_query_array($db, $query)));
       }

      require 'core/initial/queries/dropdown.php';

      require "fields/app/{$form}.php";

      $widget = new self($db, $fields, $form, $erkrankung);

      featureService::getInstance()
          ->setParam('form', $form)
          ->setParam('disease', $erkrankung)
          ->callService($widget, 'getFields')
      ;

      $fields = $widget->getFields(false, false);

      return $fields;
   }


   protected function _init()
   {
      $this->_statusFields = $this->loadExtFields('fields/app/status.php');

      $page = $this->_page;

      return $this;
   }

   public function getWidgetLoadParam()
   {
      return $this->_widgetLoadParam;
   }


   /**
    * shows the field
    *
    * @param string $field
    * @param array $showIn
    */
   public function showFieldIn($fields, $showIn = array())
   {
      $showIn = $this->_forceArray($showIn);

      if (is_array($fields) === true) {
         foreach ($fields as $field) {
            $this->showFieldIn($field, $showIn);
         }
      } else {
         if (isset($this->_fields[$fields]) === false) {
            //echo "$field in fields nicht vorhanden!";
             //Wenn es eine posliste ist, die angezeigt werden soll
             if (strpos($fields, 'dlist_') !== false) {
                 $this->_view[$fields] = 'show';
             }
         } else {
             $this->_fields[$fields]['showIn'] = $showIn;
         }
      }

      return $this;
   }


   public function hideField($fields)
   {
      if (is_array($fields) === true) {
         foreach ($fields as $field) {
            $this->hideField($field);
         }
      } else {
         if (isset($this->_fields[$fields]) === false) {
             //Wenn es eine posliste ist, die versteckt werden soll
             if (strpos($fields, 'dlist_') !== false) {
                $this->_view[$fields] = 'hide';
             }
           //echo "$field in fields nicht vorhanden!";
         } else {
             $this->_fields[$fields]['forceHide'] = true;
         }
      }

      return $this;
   }


   /**
    * hides field in module $hideIn
    *
    * @param   string   $field
    * @param unknown_type $hideIn
    */
   public function hideFieldIn($fields, $hideIn)
   {
      $hideIn = $this->_forceArray($hideIn);

      if (is_array($fields) === true) {
         foreach ($fields as $field) {
            $this->hideFieldIn($field, $hideIn);
         }
      } else {
         if (isset($this->_fields[$fields]) === false) {
             //Wenn es eine posliste ist, die versteckt werden soll
             if (strpos($fields, 'dlist_') !== false) {
                $this->_view[$fields] = 'hide';
             }
           //echo "$field in fields nicht vorhanden!";
         } else {
            foreach ($hideIn as $selector) {
               if (in_array($selector, $this->_fields[$fields]['showIn']) === true) {
                  foreach ($this->_fields[$fields]['showIn'] as $index => $showIn) {
                     if ($showIn === $selector) {
                        unset($this->_fields[$fields]['showIn'][$index]);
                        break;
                     }
                  }
               }
            }
         }
      }

      return $this;
   }

   /**
    * getFields
    *
    * returns the fields which are in selector section or is hidden
    * @access  public
    * @return  array
    */
   public function getFields($template = false, $updateTemplateFields = true)
   {
      $fields = array();

      if ($template === true) {
         if ($this->_templateFields !== null) {
            $fields = $this->_templateFields;
         } else {
            $fields = $this->getFields();
         }
      } else {
         $page = $this->_page;

         //Step 2 - Load default widget configuration
         $defaultWidgetPath = "configs/widgets/{$page}.php";

         if (file_exists($defaultWidgetPath) === true){
            require ($defaultWidgetPath);
         }

         //Step 3 - Load custom widget configuration
         $customWidgetPath = "custom/widgets/{$page}.php";

         if (file_exists($customWidgetPath) === true){
            require ($customWidgetPath);
         }

         $fields = array();

         foreach ($this->_fields as $fieldName => $field) {
            if ($field['type'] === 'hidden') {
               $fields[$fieldName] = $field;
            } else {
               if (in_array($this->_selector, $field['showIn']) === true && array_key_exists('forceHide', $field) === false) {
                  $fields[$fieldName] = $field;
               } else if (isset($field['default']) === true) {
                  $this->_ghostFields[$page][$fieldName] = $field;
               }
            }
         }

         if ($updateTemplateFields === true) {
            $this->_templateFields = $fields;
         }
      }

      return $fields;
   }


   public function getView($field)
   {
      return (array_key_exists($field, $this->_view) === true ? $this->_view[$field] : 'show');
   }


   /**
    * getAllFields
    *
    * returns the fields which are in selector section or is hidden
    * @access  public
    * @return  array
    */
   public function getAllFields()
   {
      $fields = array();

      foreach ($this->_fields as $fieldName => $field) {
         $fields[$fieldName] = $field;
      }

      return $fields;
   }


   public function setAllFields($fields)
   {
       $this->_fields = $fields;

       return $this;
   }

   /**
    *
    *
    */

   public function resetAllInputFields()
   {
      foreach ($this->_show as $field) {

      }

      return $this;
   }


   /**
    *
    *
    * @param $fields
    * return widget
    */
   public function setFields($fields, $selector)
   {
      foreach ($fields as $index => $field) {
         if ($field['type'] !== 'hidden') {
            $fields[$index]['showIn'] = array($selector);
         }
      }

      $this->_fields = $fields;

      return $this;
   }

   public function unsetField($fieldName)
   {
      if (array_key_exists($fieldName, $this->_fields) === true) {
         unset($this->_fields[$fieldName]);
      }

      if (is_array($this->_templateFields) === true && array_key_exists($fieldName, $this->_templateFields) === true) {
         unset($this->_templateFields[$fieldName]);
      }

      return $this;
   }


   /**
    * loadFields
    * loads external field
    *
    * @access  public
    * @param   string   $url
    * @return  array
    */
   public function loadExtFields($url)
   {
      $db            = $this->_db;

      extract($this->_widgetLoadParam);

      include($url);

      return $fields;
   }


   /**
    * loadFields
    * loads external field
    *
    * @access  public
    * @param   string   $url
    * @return  array
    */
   public function loadExtFieldsOnce($url)
   {
      $db = $this->_db;

      extract($this->_widgetLoadParam);

      include_once $url;

      return $fields;
   }

   /**
    * forces given string to array
    *
    * @param   unknown_type   $value
    * @return  array
    */
   protected function _forceArray($value)
   {
      if (is_array($value) === false){
         $value = array((string) $value);
      }

      return $value;
   }


   public function getStatusFields()
   {
      return $this->_statusFields;
   }

   /**
    * getGhostFields
    *
    * @param $table
    * @return array
    */
   public function getGhostFields($table)
   {
      $ghostFields = array();

      if (isset($this->_ghostFields[$table]) == true) {
         $ghostFields = $this->_ghostFields[$table];
      }

      return $ghostFields;
   }


   public function setGhostFields($fields)
   {
      $this->_ghostFields = $fields;

      return $this;
   }
}

?>