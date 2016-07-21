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

class Lookup
{
   /**
    * Datenbank Ressource-ID
    *
    * @access private
    * @var string $db
    */
   private $db;

   /**
    * Encoding der Rückgabewerte
    *
    * @access private
    * @var string $encoding
    */
   private $encoding;


   /**
    * Konstruktor
    *
    * @param string &$db
    * @return void
    */
   public function __construct(&$db)
   {
      $this->db = &$db;
      $this->encoding = false;
   }

   /**
    * Setzt UTF8-Kodierung für Rückgabewerte
    *
    * @access public
    * @param string $where
    * @param string $table
    * @param string $field
    * @return string
    */
   public function set_utf8encoding()
   {
      $this->encoding = 'utf8';
   }

   /**
    * Wrapper für dlookup
    *
    * @access public
    * @param string $table
    * @param string $field
    * @param string $where
    * @return string
    */
   public function get($table='', $field='', $where)
   {
      $value = dlookup($this->db, $table, $field, $where);

      if( $this->encoding == 'utf8') {
         $value = utf8_encode($value);
      }

      return $value;
   }

   /**
    * Macht einen lookup auf die Projekt Lookup-Tabelle
    *
    * @access public
    * @param string $class
    * @param string $code
    * @return string
    */
   public function get_l($class, $code, $priority_table='')
   {
      $table = @$_SESSION['db_tables']['tbl_l_' . $_SESSION['sess_modul']];
      $field = 'bez';
      // REVISIT dirty hack wg. TUK-Datenmigation
      $where = "(klasse='$class' OR klasse='{$class}_tuk') AND code='$code'";

      $retval = '';
      if(strlen($priority_table)) {
         $retval = $this->get($priority_table, $field, $where);
      }
      if(! strlen($retval)) {
         $retval = $this->get($table, $field, $where);
      }

      return $retval;
   }

   /**
    * Macht einen lookup auf die Therapie Vorlagen -Tabelle
    *
    * @access public
    * @param string $therapie_id
    * @return string
    */
   public function get_l_vorlagen($therapie_id)
   {
      $table = @$_SESSION['db_tables']['tbl_vorlagen_therapie'];
      $field = 'bez';
      $where = "therapie_id='$therapie_id'";

      $retval = '';
      $retval = $this->get($table, $field, $where);

      return $retval;
   }


   /**
    * Macht einen lookup auf die ICD10 Lookup-Tabelle
    *
    * @access public
    * @param string $code
    * @return string
    */
   public function get_l_icd10($code, $verbose=false, $priority_table='')
   {
      $table = $_SESSION['db_tables_lookup']['tbl_l_icd10'];
      $field = $verbose ? 'CONCAT_WS(" - ", code, description)' : 'description';
      $where = "code='$code'";

      $retval = '';
      if(strlen($priority_table)) {
         $retval = $this->get($priority_table, $field, $where);
      }
      if(! strlen($retval)) {
         $retval = $this->get($table, $field, $where);
      }

      return $retval;
   }

   /**
    * Macht einen lookup auf die ICD-O3 Lookup-Tabelle
    *
    * @access public
    * @param string $id
    * @return string
    */
   public function get_l_icdo3($id, $verbose=true, $priority_table='')
   {
      $table = $_SESSION['db_tables_lookup']['tbl_l_icdo3'];
      $field = $verbose ? 'CONCAT_WS(" - ", code, description)' : 'description';
      $where = "id='$id'";

      $retval = '';
      if(strlen($priority_table)) {
         $retval = $this->get($priority_table, $field, $where);
      }
      if(! strlen($retval)) {
         $retval = $this->get($table, $field, $where);
      }

      return $retval;
   }

   /**
    * Macht einen lookup auf die OPS301 Lookup-Tabelle
    *
    * @access public
    * @param string $code
    * @param string $verbose
    * @return string
    */
   public function get_l_ops301($code, $verbose=false, $priority_table='')
   {
      $table = $_SESSION['db_tables_lookup']['tbl_l_ops301'];
      $field = $verbose ? 'CONCAT_WS(" - ", code, description)' : 'description';
      $where = "code='$code'";

      $retval = '';
      if(strlen($priority_table)) {
         $retval = $this->get($priority_table, $field, $where);
      }
      if(! strlen($retval)) {
         $retval = $this->get($table, $field, $where);
      }

      return $retval;
   }
}


?>
