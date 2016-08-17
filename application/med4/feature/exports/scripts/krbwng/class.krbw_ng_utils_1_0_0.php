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

class CKrbwUtils
{

   /**
    *
    * @param $value string
    * @return array
    */
   public static function CreateValueArray( $value )
   {
      $result = array();
      if ( $value == null ) {
         $result[ 'nullFlavor' ] = "NA";
      }
      else {
         $result[ 'value' ] = $value;
      }
      return $result;
   }

   /**
    *
    * @param $root string
    * @param $extension string
    * @return array
    */
   public static function CreateIdArray( $root, $extension )
   {
      $result = array();
      $result[ 'root' ] = CKrbwUtils::ReplaceHashOid( $root );
      $result[ 'extension' ] = CKrbwUtils::ReplaceHashCode( $extension );
      return $result;
   }

   /**
    *
    * @param $code string
    * @param $codeSystem string
    * @param $displayName string
    * @return array
    */
   public static function CreateCodeArray( $code, $codeSystem, $displayName )
   {
      $result = array();
      $result[ 'code' ] = CKrbwUtils::ReplaceHashCode( $code );
      $result[ 'codeSystem' ] = CKrbwUtils::ReplaceHashOid( $codeSystem );
      $result[ 'displayName' ] = $displayName;
      $result[ 'nullFlavor' ] = "";
      return $result;
   }

   /**
    *
    * @param string $vorname
    * @param string $nachname
    * @return array
    */
   public static function CreateNameArray( $titel, $namens_zusatz, $vorname, $nachname, $geburtsname, $fruehere_namen )
   {
      $result = array();
      $result[ 'prefix' ] = $titel;
      $result[ 'prefix:qualifier="VV"' ] = $namens_zusatz;
      $result[ 'given' ] = $vorname;
      $result[ 'family' ] = $nachname;
      $result[ 'family:qualifier="BR"' ] = $geburtsname;
      $result[ 'family:validityRange' ] = $fruehere_namen;
      return $result;
   }

   /**
    *
    * @param string $street_name
    * @param string $house_number
    * @param string $postal_code
    * @param string $city
    * @param string $post_box
    * @param string $country
    * @return array
    */
   public static function CreateAddressArray( $street_name, $house_number, $postal_code, $city, $post_box, $country )
   {
      $result = array();
      $result[ 'streetName' ] = $street_name;
      $result[ 'houseNumber' ] = $house_number;
      $result[ 'postalCode' ] = $postal_code;
      $result[ 'city' ] = $city;
      $result[ 'postBox' ] = $post_box;
      $result[ 'country' ] = $country;
      return $result;
   }

   /**
    *
    * @param $xsi_type string
    * @param $code string
    * @param $codeSystem string
    * @param $codeSystemName string
    * @param $displayName string
    * @return array
    */
   public static function CreateObservationValueArray( $xsi_type, $code, $codeSystem, $codeSystemName, $displayName )
   {
      $result = array();
      $result[ 'xsi_type' ] = $xsi_type;
      $result[ 'code' ] = $code;
      $result[ 'codeSystem' ] = $codeSystem;
      $result[ 'codeSystemName' ] = $codeSystemName;
      $result[ 'displayName' ] = $displayName;
      return $result;
   }

   /**
    *
    * @param $tel_use string
    * @param $tel_number string
    * @param $fax_use string
    * @param $fax_number string
    * @return array
    */
   public static function CreateTelefonArray( $tel_use, $tel_number, $fax_use, $fax_number )
   {
      $result = array();
      $result[ 'tel' ][ 'use' ] = $tel_use;
      $result[ 'tel' ][ 'value' ] = "tel:$tel_number";
      $result[ 'fax' ][ 'use' ] = $fax_use;
      $result[ 'fax' ][ 'value' ] = "fax:$fax_number";
      return $result;
   }

   public static function CreateEffectiveTimeArray( $value, $low, $high )
   {
      $result = array();
      $result[ 'value' ] = $value;
      $result[ 'low' ] = $low;
      $result[ 'high' ] = $high;
      return $result;
   }

   public static function ReplaceHashOid( $hash_oid )
   {
      $oid = $hash_oid;

      switch( $hash_oid ) {
         case "#Alcedis-OID-für-Systeminstanz-Abschluss" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.1";
            break;
         case "#Alcedis-OID-für-Systeminstanz-EKR" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.2";
            break;
         case "#Alcedis-OID-für-Systeminstanz-Erkrankung" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.3";
            break;
         case "#Alcedis-OID-für-Systeminstanz-Nachsorge" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.4";
            break;
         case "#Alcedis-OID-für-Systeminstanz-Patient" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.5";
            break;
         case "#Alcedis-OID-für-Systeminstanz-Therapie" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.6";
            break;
         case "#Alcedis-OID-für-Systeminstanz-Tumorstatus" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.1.7";
            break;
         case "#Alcedis-root-OID-für-Authors" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.2.1";
            break;
         case "#Alcedis-root-OID-für-Systeminstanzen" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.999.2.2";
            break;
         case "#KRBW-root-OID-für-Melder" :
            $oid = "1.2.276.0.76.3.1.131.1.4.3.997.1.1";
            break;
         case "#OID-für-Abschlussgrund" :
            $oid = "1.2.276.0.76.3.1.131.1.5.1";
            break;
         case "#OID-für-Abschlussgrund-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.1";
            break;
         case "#OID-für-Binet-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.2";
            break;
         case "#OID-für-Clark-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.3";
            break;
         case "#OID-für-Durie-Salmon-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.4";
            break;
         case "#OID-für-Estro-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.5";
            break;
         case "#OID-für-FAB-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.6";
            break;
         case "#OID-für-FIGO-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.7";
            break;
         case "#OID-für-Her2-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.8";
            break;
         case "#OID-für-Menopausenstatus-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.9";
            break;
         case "#OID-für-Mercury" :
            $oid = "1.2.276.0.76.3.1.131.1.5.1";
            break;
         case "#OID-für-Mercury-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.10";
            break;
         case "#OID-für-Prog-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.11";
            break;
         case "#OID-für-R" :
            $oid = "1.2.276.0.76.3.1.131.1.5.1";
            break;
         case "#OID-für-RAI-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.12";
            break;
         case "#OID-für-R-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.13";
            break;
         case "#OID-für-Studie" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.14";
            break;
         case "#OID-für-Therapieart-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.15";
            break;
         case "#OID-für-Tod-tumorbedingt" :
            $oid = "1.2.276.0.76.3.1.131.1.5.1";
            break;
         case "#OID-für-Tod-tumorbedingt-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.16";
            break;
         case "#OID-für-Tumorgeschehen" :
            $oid = "1.2.276.0.76.3.1.131.1.5.1";
            break;
         case "#OID-für-Tumorgeschehen-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.17";
            break;
         case "#OID-für-VALG-Werte" :
            $oid = "1.2.276.0.76.3.1.131.1.5.998.18";
            break;
      }
      return $oid;
   }

   public static function ReplaceHashCode( $hash_code )
   {
      $code = $hash_code;

      switch( $hash_code ) {
         case "#DKG-Code-für-KRBW-Dokument" :
            $code = "1.2.276.0.76.3.1.131.1.4.3.998.1.1";
            break;
         case "#Code-für-Abschlussgrund" :
            $code = "tmp_abschlussgrund";
            break;
         case "#Code-für-Mercury" :
            $code = "tmp_mercury";
            break;
         case "#Code-für-R" :
            $code = "tmp_r";
            break;
         case "#Code-für-Tod-tumorbedingt" :
            $code = "tmp_tod_tumorbedingt";
            break;
         case "#Code-für-Tumorgeschehen" :
            $code = "tmp_tumorgeschehen";
            break;
      }
      return $code;
   }

}

class CKrbwEntryrelationship
{

   protected $m_type_code;
   protected $m_observation;
   protected $m_procedure;

   public function __construct()
   {
      $this->m_type_code = "";
      $this->m_observation = null;
      $this->m_procedure = null;
   }

   public function SetTypeCode( $type_code )
   {
      $this->m_type_code = $type_code;
   }

   public function CreateObservation()
   {
      $this->m_observation = new CKrbwObservation;
   }

   public function GetObservation()
   {
      return $this->m_observation;
   }

   public function CreateProcedure()
   {
      $this->m_procedure = CKrbwProcedure;
   }

   public function GetProcedure()
   {
      return $this->m_procedure;
   }

   public function GetData()
   {
   	  $is_empty = true;

      $result = array();
      $result[ 'typeCode' ] = $this->m_type_code;
      if ( ( $this->m_observation != null ) &&
           ( $this->m_observation->GetData() != null ) ) {
         $result[ 'observation' ] = $this->m_observation->GetData();
         $is_empty = false;
      }
      else if ( ( $this->m_procedure != null ) &&
      			( $this->m_procedure->GetData() != null ) ) {
         $result[ 'procedure' ] = $this->m_procedure->GetData();
         $is_empty = false;
      }
      if ( $is_empty ) {
         return null;
      }
      return $result;
   }

}

class CKrbwQualifier
{

   protected $m_name;
   protected $m_value;

   public function __construct()
   {
      $this->m_name = array();
      $this->m_value = array();
   }

   public function SetName( $code, $code_system )
   {
      $this->m_name = array( 'code' => CKrbwUtils::ReplaceHashCode( $code ), 'codeSystem' => CKrbwUtils::ReplaceHashOid( $code_system ) );
   }

   public function SetValue( $code, $code_system )
   {
      $this->m_value = array( 'code' => CKrbwUtils::ReplaceHashCode( $code ), 'codeSystem' => CKrbwUtils::ReplaceHashOid( $code_system ) );
   }

   public function GetData()
   {
      $result = array();
      $result[ 'name' ] = $this->m_name;
      $result[ 'value' ] = $this->m_value;
      return $result;
   }

}

class CKrbwValue
{

   protected $m_xsi_type;
   protected $m_null_flavor;
   protected $m_code;
   protected $m_codeSystem;
   protected $m_codeSystemName;
   protected $m_displayName;
   protected $m_original_text;
   protected $m_value;
   protected $m_qualifiers;

   public function __construct()
   {
      $this->m_xsi_type = "CD";
      $this->m_code = "";
      $this->m_code_system = "";
      $this->m_code_system_name = "";
      $this->m_display_name = "";
      $this->m_original_text = "";
      $this->m_value = null;
      $this->m_qualifiers = array();
   }

   public function Clear()
   {
      $this->m_xsi_type = "";
      $this->m_code = "";
      $this->m_code_system = "";
      $this->m_code_system_name = "";
      $this->m_display_name = "";
      $this->m_original_text = "";
      $this->m_value = null;
      $this->m_qualifiers = array();
   }

   public function SetXsiType( $xsi_type )
   {
      $this->m_xsi_type = $xsi_type;
   }

   public function GetXsiType()
   {
      return $this->m_xsi_type;
   }

   public function SetNullFlavor()
   {
      $this->m_null_flavor = "UNK";
   }

   public function SetCode( $code )
   {
      $this->m_code = CKrbwUtils::ReplaceHashCode( $code );
   }

   public function SetCodeSystem( $code_system )
   {
      $this->m_code_system = CKrbwUtils::ReplaceHashOid( $code_system );
   }

   public function SetCodeSystemName( $code_system_name )
   {
      $this->m_code_system_name = $code_system_name;
   }

   public function SetDisplayName( $display_name )
   {
      $this->m_display_name = $display_name;
   }

   public function GetOriginalText()
   {
      return $this->m_original_text;
   }

   public function SetOriginalText( $original_text )
   {
      $this->m_original_text = $original_text;
   }

   public function SetValue( $value )
   {
      $this->m_value = $value;
   }

   public function AddQualifier( $name, $qualifier )
   {
      $this->m_qualifiers[ $name ] = $qualifier;
   }

   public function GetQualifier( $name )
   {
      if ( !isset( $this->m_qualifiers[ $name ] ) ) {
         return false;
      }
      return $this->m_qualifiers[ $name ];
   }

   public function GetData()
   {
      $result = array();
      $result[ 'xsi_type' ] = $this->m_xsi_type;
      $result[ 'nullFlavor' ] = $this->m_null_flavor;
      $result[ 'code' ] = $this->m_code;
      $result[ 'codeSystem' ] = $this->m_code_system;
      $result[ 'codeSystemName' ] = $this->m_code_system_name;
      $result[ 'displayName' ] = $this->m_display_name;
      $result[ 'originalText' ] = $this->m_original_text;
      if ( $this->m_value != null ) {
         $result[ 'value' ] = $this->m_value;
      }
      $result[ 'qualifiers' ] = array();
      foreach( $this->m_qualifiers as $name => $qualifier ) {
         $result[ 'qualifiers' ][] = $qualifier->GetData();
      }
      return $result;
   }

}

class CKrbwObservation
{

   protected $m_negation_ind;
   protected $m_template_id;
   protected $m_id;
   protected $m_code;
   protected $m_status_code;
   protected $m_effective_time;
   protected $m_value;
   protected $m_entryrelationships;

   public function __construct()
   {
      $this->m_negation_ind = "";
      $this->m_template_id = array();
      $this->m_id = array();
      $this->m_code = array();
      $this->m_status_code = array();
      $this->m_effective_time = CKrbwUtils::CreateEffectiveTimeArray( "", "", "" );
      $this->m_author_time = null;
      $this->m_value = new CKrbwValue;
      $this->m_entryrelationships = array();
   }

   public function SetNegationInd( $negation_ind )
   {
      $this->m_negation_ind = $negation_ind;
   }

   public function SetTemplateId( $root, $extension )
   {
      $this->m_template_id = CKrbwUtils::CreateIdArray( $root, $extension );
   }

   public function SetId( $root, $extension )
   {
      $this->m_id = CKrbwUtils::CreateIdArray( $root, $extension );
   }

   public function SetCode( $code, $codeSystem, $displayName )
   {
      $this->m_code = CKrbwUtils::CreateCodeArray( $code, $codeSystem, $displayName );
   }

   public function SetStatusCode( $code, $codeSystem, $displayName )
   {
      $this->m_status_code = CKrbwUtils::CreateCodeArray( $code, $codeSystem, $displayName );
   }

   public function SetEffectiveTime( $value, $low = "", $high = "" )
   {
      $this->m_effective_time = CKrbwUtils::CreateEffectiveTimeArray( $value, $low, $high );
   }

   public function SetAuthorTime( $value )
   {
      $this->m_author_time = $value;
   }

   public function GetValue()
   {
      return $this->m_value;
   }

   public function AddEntryrelationship( $name, $entryrelationship )
   {
      $this->m_entryrelationships[ $name ] = $entryrelationship;
   }

   public function GetEntryrelationship( $name )
   {
      if ( !isset( $this->m_entryrelationships[ $name ] ) ) {
         return false;
      }
      return $this->m_entryrelationships[ $name ];
   }

   public function GetData()
   {
      $result = array();
      $result[ 'negationInd' ] = $this->m_negation_ind;
      $result[ 'templateId' ] = $this->m_template_id;
      $result[ 'id' ] = $this->m_id;
      $result[ 'code' ] = $this->m_code;
      $result[ 'statusCode' ] = $this->m_status_code;
      $result[ 'effectiveTime' ] = $this->m_effective_time;
      $result[ 'author' ][ 'time' ][ 'value' ] = $this->m_author_time;
      $result[ 'value' ] = $this->m_value->GetData();
      $result[ 'entryrelationships' ] = array();
      foreach( $this->m_entryrelationships as $name => $entryrelationship ) {
         $result[ 'entryrelationships' ][] = $entryrelationship->GetData();
      }
      if ( ( count( $result[ 'code' ] ) > 0 ) &&
      	   ( strlen( $result[ 'code' ][ 'code' ] ) > 0 ) ) {
      	 if ( count( $result[ 'entryrelationships' ] ) > 0 ) {
      	 	if ( strlen( $result[ 'value' ][ 'code' ] ) == 0 ) {
      	 		$this->m_value->SetNullFlavor();
      	 		$result[ 'value' ] = $this->m_value->GetData();
      	 	}
      	 }
      	 else {
      	    // Fix für Ticket #3221
      	 	if ( ( strlen( $result[ 'value' ][ 'code' ] ) == 0 ) &&
      	 	     ( $this->m_value->GetXsiType() != "INT" ) &&
                 ( $this->m_value->GetXsiType() != "BL" ) ) {
      	 	   return null;
      	 	}
      	 }
      }
      else {
         return null;
      }
      return $result;
   }

   // Helper

   public function SetValueHelper( $key_oid, $key_code, $value_oid, $value_code )
   {
      $this->SetCode( $key_code, $key_oid, "" );
      $this->GetValue()->SetXsiType( "CD" );
      $this->GetValue()->SetValue( null );
      $this->GetValue()->SetCode( $value_code );
      $this->GetValue()->SetCodeSystem( $value_oid );
      $this->GetValue()->SetCodeSystemName( "" );
   }

   public function SetValueBooleanHelper( $key_oid, $key_code, $value )
   {
      $this->SetCode( $key_code, $key_oid, "" );
      $this->GetValue()->SetXsiType( "BL" );
      $this->GetValue()->SetValue( $value );
      $this->GetValue()->SetCode( "" );
      $this->GetValue()->SetCodeSystem( "" );
      $this->GetValue()->SetCodeSystemName( "" );
   }

   public function SetValueIntegerHelper( $key_oid, $key_code, $value )
   {
      $this->SetCode( $key_code, $key_oid, "" );
      $this->GetValue()->SetXsiType( "INT" );
      $this->GetValue()->SetValue( $value );
      $this->GetValue()->SetCode( "" );
      $this->GetValue()->SetCodeSystem( "" );
      $this->GetValue()->SetCodeSystemName( "" );
   }

   public function SetValueToNullFlavor( $key_oid, $key_code )
   {
      $this->SetCode( $key_code, $key_oid, "" );
      $this->GetValue()->SetXsiType( "CD" );
      $this->GetValue()->SetNullFlavor();
      $this->GetValue()->SetCode( "" );
      $this->GetValue()->SetCodeSystem( "" );
      $this->GetValue()->SetCodeSystemName( "" );
   }

}

class CKrbwProcedure extends CKrbwObservation
{

   public function SetValueHelper( $key_oid, $key_code, $value_oid, $value_code )
   {
      $this->SetCode( $value_code, $key_oid, "" );
   }

   public function GetData()
   {
      $result = array();
      $result[ 'negationInd' ] = $this->m_negation_ind;
      $result[ 'templateId' ] = $this->m_template_id;
      $result[ 'id' ] = $this->m_id;
      $result[ 'code' ] = $this->m_code;
      $result[ 'statusCode' ] = $this->m_status_code;
      $result[ 'effectiveTime' ] = $this->m_effective_time;
      $result[ 'author' ][ 'time' ][ 'value' ] = $this->m_author_time;
      $ot = $this->m_value->GetOriginalText();
      $this->m_value->Clear();
      if ( strlen( $ot ) > 0 ) {
         $this->m_value->SetOriginalText( $ot );
      }
      $result[ 'value' ] = $this->m_value->GetData();
      $result[ 'entryrelationships' ] = array();
      foreach( $this->m_entryrelationships as $name => $entryrelationship ) {
         $result[ 'entryrelationships' ][] = $entryrelationship->GetData();
      }
      if ( count( $result[ 'entryrelationships' ] ) > 0 ) {
      	 if ( strlen( $result[ 'code' ][ 'code' ] ) == 0 ) {
      	 	$result[ 'code' ][ 'nullFlavor' ] = "UNK";
      	 }
      }
      else {
      	 if ( !isset( $result[ 'code' ][ 'code' ] ) ||
      	      strlen( $result[ 'code' ][ 'code' ] ) == 0 ) {
      	    return null;
      	 }
      }
      return $result;
   }

}

class CKrbwEntry
{

   protected $m_type_code = "";
   protected $m_observation = null;
   protected $m_procedure = null;

   public function __construct()
   {
   }

   public function SetTypeCode( $type_code )
   {
      $this->m_type_code = $type_code;
   }

   public function CreateObservation()
   {
      $this->m_observation = new CKrbwObservation;
   }

   public function GetObservation()
   {
      return $this->m_observation;
   }

   public function CreateProcedure()
   {
      $this->m_procedure = new CKrbwProcedure;

   }

   public function GetProcedure()
   {
      return $this->m_procedure;
   }

   public function GetData()
   {
   	  $is_empty = true;
      $result = array();
      $result[ 'typeCode' ] = $this->m_type_code;
      if ( ( $this->m_observation != null ) &&
           ( $this->m_observation->GetData() != null ) ) {
         $result[ 'observation' ] = $this->m_observation->GetData();
         $is_empty = false;
      }
      if ( ( $this->m_procedure != null ) &&
      	   ( $this->m_procedure->GetData() != null ) ) {
         $result[ 'procedure' ] = $this->m_procedure->GetData();
         $is_empty = false;
      }
      if ( $is_empty ) {
         return array();
      }
      return $result;
   }

}

class CKrbwComponent
{

   protected $m_template_id;
   protected $m_id;
   protected $m_code;
   protected $m_title;
   protected $m_text;
   protected $m_entries;

   public function __construct()
   {
      $this->m_template_id = array();
      $this->m_id = array();
      $this->m_code = array();
      $this->m_title = "";
      $this->m_text = "";
      $this->m_entries = array();
   }

   public function SetTemplateId( $root, $extension )
   {
      $this->m_template_id = CKrbwUtils::CreateIdArray( $root, $extension );
   }

   public function SetId( $root, $extension )
   {
      $this->m_id = CKrbwUtils::CreateIdArray( $root, $extension );
   }

   public function SetCode( $code, $codeSystem, $displayName )
   {
      $this->m_code = CKrbwUtils::CreateCodeArray( $code, $codeSystem, $displayName );
   }

   public function SetTitle( $title )
   {
      $this->m_title = $title;
   }

   public function SetText( $text )
   {
      $this->m_text = $text;
   }

   public function AddEntry( $name, $entry )
   {
      $this->m_entries[ $name ] = $entry;
   }

   public function GetData()
   {
      $result = array();
      $result[ 'templateId' ] = $this->m_template_id;
      $result[ 'id' ] = $this->m_id;
      $result[ 'code' ] = $this->m_code;
      $result[ 'title' ] = $this->m_title;
      $result[ 'text' ] = $this->m_text;
      if ( count( $this->m_entries ) > 0 ) {
         foreach( $this->m_entries as $name => $e ) {
            $entry = $e->GetData();
            if ( count( $entry ) > 0 ) {
               $result[ 'entries' ][] = $entry;
            }
         }
         if ( !isset( $result[ 'entries' ] ) ) {
            return array();
         }
      }
      else {
         return array();
      }
      return $result;
   }

}

?>
