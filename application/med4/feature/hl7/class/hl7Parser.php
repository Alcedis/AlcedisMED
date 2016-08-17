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

class hl7Parser extends hl7Base
{
   /**
    * all loaded messages (array)
    */
   protected $_messages = array();

   /**
    * all loaded messages (raw-version)
    */
   protected $_raw_messages = array();

   /**
    * current message pointer
    */
   protected $_activeMsg = 0;


   protected $_msgControlIdPosition = 9;


   /**
    * Removes all old messages and creates a new message array
    *
    * @param unknown_type $msg
    */
   public function loadMsg($msg, $parse = false)
   {
      $this->_raw_messages = array();

      if ($parse === true) {
         $msg        = reset($this->parseMsg($msg, true));
         $messages   = array($msg['con']);
      } else {
         $messages   = array($msg);
      }

      $this->_messages = $messages;

      $this->_activeMsg = 0;

      return $this;
   }


   /**
    * parse raw Msg
    *
    * @param unknown_type $content
    * @param unknown_type $return
    */
   public function parseMsg($content, $return = false)
   {
     //basis explode auf chr(13) und |
     $lines = explode(chr(13), $content);

     foreach ($lines as $i => $line) {
        if (trim(strlen($line)) > 0) {
            $lines[$i] = explode('|', $line);
        } else {
            unset($lines[$i]);
        }
     }

     $messages = array();
     $message  = array();
     $firstRun = true;

     foreach ($lines as $line) {
        $type = $line[0];
        if ($type == 'MSH') {
           if ($firstRun) {
              $firstRun = false;
           } else {
              $messages[] = $message;
              $message = array();
           }
        }
        $message[] = $line;
     }

     if (count($message)> 0) {
        $messages[] = $message;
     }

     unset($message);

     $returnMessages = array();

     foreach ($messages as $index => &$message) {
        //save raw message
         if ($return === false) {
            $this->_saveRawMessage($message);
         } else {
            $returnMessages[$index]['raw'] = $this->_saveRawMessage($message, true);
         }

         //weiter trennzeichen aus Feld
         $customSeparator = str_split(isset($message[0][1]) ? $message[0][1] : '');
         $message[0][1] = '';

         foreach ($customSeparator as $customSep) {
            $message = $this->_deepSplit($message, $customSep);
         }

         $message[0][1] = implode($customSeparator);

         if ($return === true) {
            $returnMessages[$index]['con'] = $message;
         } else {
            $this->_messages[] = $message;
         }
      }

      return ($return === true ? $returnMessages : $this);
   }


   /**
    * get the first matching segment of the active message
    * @param $seg
    */
   public function getSegment($seg, $multi = false)
   {
      $return = array();
      foreach ($this->_messages[$this->_activeMsg] as $segments) {
         if ($segments[0] == $seg) {
            if ($multi) {
               $return[] = $segments;
            } else{
               return $segments;
            }
         }
      }

      return $return;
   }

   /**
    * get all messages
    */
   public function getMessages()
   {
        return $this->_messages;
   }

   /**
    * return message control id
    *
    * @return type
    */
   public function getMessageControlId()
   {
       $id = null;

       if ($this->hasSegment('MSH') === true) {
           $controlId = $this->findValue($this->getSegment('MSH'), array($this->_msgControlIdPosition));

           $id = strlen($controlId) > 0 ? $controlId : null;
       }

       return $id;
   }


   /**
    * get a specific field from the matching segment
    * of the current message
    *
    * @param $seg
    * @param $field
    */
   public function getFieldValue($fieldName, $replaceDelimiter = null)
   {
      $fieldValue       = null;
      $fieldSettings    = $this->getFieldSettings();
      $multipleDatasets = false;

      if (array_key_exists($fieldName, $fieldSettings) === true) {
         $fieldSetting        = $fieldSettings[$fieldName];
         $hl7FieldInformation = $this->splitHl7Src($fieldSetting['hl7']);

         if ($this->hasSegments($hl7FieldInformation['segment']) === true) {
            $segment = null;

            //Entscheide welches Segment genommen wird
            switch ($fieldSetting['multiple']) {
               case 'first': $segment = reset($this->getSegments($hl7FieldInformation['segment'])); break;
               case 'last': $segment = end($this->getSegments($hl7FieldInformation['segment'])); break;

               case 'filter':

                  $filterLocation = $fieldSetting['multiple_segment'];
                  $filter         = $fieldSetting['multiple_filter'];

                  foreach ($this->getSegments($hl7FieldInformation['segment']) as $possibleSegment) {
                     $filterResult = $this->findValue($possibleSegment, explode('.', $filterLocation));

                     if ($filterResult !== null) {
                        $regular = preg_match($filter, $filterResult);

                        if ($regular == 1) {
                           $segment = $possibleSegment;

                           break;
                        }
                     }
                  }
                  break;
            }

            if ($segment !== null) {
               $fieldValue = $this->findValue($segment, $hl7FieldInformation['position']);
            }

            if ($fieldSetting['multiple'] == 'all') {

               $multipleDatasets = true;
               $segments         = $this->getSegments($hl7FieldInformation['segment']);

               foreach ($segments as $segment) {
                  $fieldValue .= $fieldValue === null
                     ? $this->findValue($segment, $hl7FieldInformation['position'])
                     : $this->getDelimiter() . $this->findValue($segment, $hl7FieldInformation['position'])
                  ;
               }
            }

         } elseif ($this->hasSegment($hl7FieldInformation['segment']) === true) {
            $fieldValue = $this->findValue($this->getSegment($hl7FieldInformation['segment']), $hl7FieldInformation['position']);
         }

         if ($fieldValue !== null) {
            //value noch formatieren

            $fieldValues = explode($this->getDelimiter(), $fieldValue);
            $fieldValue = null;

            foreach ($fieldValues as $tmpFieldValue) {
               switch ($fieldSetting['feld_typ']) {
                  case 'string': $tmpFieldValue = (string) $tmpFieldValue;                break;
                  case 'int':    $tmpFieldValue = (int) $tmpFieldValue;                   break;
                  case 'date':   $tmpFieldValue = $this->toDate((string) $tmpFieldValue); break;
               }

               //Zeichen Nr
               if (isset($fieldSetting['hl7_bereich']) === true && strlen($fieldSetting['hl7_bereich']) > 0) {
                  $bereich = explode('-', $fieldSetting['hl7_bereich']);
                  $back    = $fieldSetting['hl7_back'];

                  if (count($bereich) > 1) {

                     $start = reset($bereich);
                     $end   = end($bereich);

                     $end           = ($end + 1) - $start;
                     $start         = $back == 1 ? -$start : $start - 1;

                     $tmpFieldValue = substr($tmpFieldValue, $start, $end);
                  } else {
                     $bereich       = reset($bereich);
                     $start         = $back == 1 ? -$bereich : $bereich - 1;
                     $tmpFieldValue = substr($tmpFieldValue, $start, $bereich);
                  }

                  if ($tmpFieldValue === false) {
                     $tmpFieldValue = null;
                  }
               }

               //Lookup
               if ($tmpFieldValue != null && isset($fieldSetting['ext']) === true && strlen($fieldSetting['ext']) > 0) {
                  $ext = json_decode($fieldSetting['ext'], true);

                  if (array_key_exists($tmpFieldValue, $ext) === true) {
                     $tmpFieldValue = $ext[$tmpFieldValue];
                  } else {
                     $tmpFieldValue = null;
                  }
               }

               //führende Nullen abhacken
               if ($fieldSetting['feld_trim_null'] == 1) {
                  $tmpFieldValue = preg_replace('~^0+~', '', $tmpFieldValue);
               }

               $fieldValue .= $fieldValue === null
                  ? $tmpFieldValue
                  : $this->getDelimiter() . $tmpFieldValue
               ;
            }
         }

         if ($fieldValue !== null && $replaceDelimiter !== null) {
            $delimiter = $replaceDelimiter === true ? '<br/>---<br/>' : $replaceDelimiter;

            $fieldValue = str_replace($this->getDelimiter(), $delimiter, $fieldValue);
         }
      }

      return trim($fieldValue);
   }


   private function findValue($array, $position)
   {
      $currentPosition = reset($position);
      $return   = null;

      if (isset($array[$currentPosition]) === true) {
         if (is_array($array[$currentPosition]) === true) {

            $newPosition = array_slice($position, 1);

            $return = $this->findValue($array[$currentPosition], $newPosition);
         } else {
            if ($currentPosition == false) {
               $return = $this->findFirst($array);
            } else {
               $return = $array[$currentPosition];
            }
         }
      }

      return $return;
   }


   /**
    * splits the hl7 field location from setting in readable text
    *
    * @param $hl7Src
    */
   private function splitHl7Src($hl7Src)
   {
      $split   = array();
      $src     = explode('.', $hl7Src);

      $split['segment'] = reset($src);
      $split['position'] = array_slice($src, 1);

      return $split;
   }


   /**
    * get all matching segments of the current message
    *
    * @param mixed $seg
    */
   public function getSegments($seg)
   {
       return $this->getSegment($seg, true);
   }


   /**
    * check if segment exists in current message
    *
    * @param $seg
    */
   public function hasSegment($seg)
   {
      return count($this->getSegments($seg)) > 0;
   }


   /**
    * check if more then one segment exists in current message
    *
    * @param $seg
    */
   public function hasSegments($seg)
   {
      return count($this->getSegments($seg)) > 1;
   }


   /**
    * change the active message
    *
    * @param mixed $msg
    */
   public function setActiveMsg($msg)
   {
      if (isset($this->_messages[$msg])) {
         $this->_activeMsg = $msg;
      }

      return $this;
   }


   /**
    * get all indexes of imported messages
    * most likely always 0,1,2,3...
    */
   public function getMessageKeys($msg = null)
   {
      $msg = $msg !== null ? $msg : $this->_messages;

      return array_keys($msg);
   }


   /**
    * get the raw string of the active message
    */
   public function getRawMessage()
   {
      return $this->_raw_messages[$this->_activeMsg];
   }


   /**
    * function for exploding the hl7 messages
    *
    * @param $lines
    * @param $splitter
    */
   private function _deepSplit(&$lines, $splitter)
   {
        foreach ($lines as &$line) {
           if (is_array($line)) {
              $line = $this->_deepSplit($line, $splitter);
           } else {
              $line = strpos($line, $splitter) !== false ? explode($splitter, $line) : $line;
           }
        }
        return $lines;
   }


   /**
    * save the message in raw-format
    * @param mixed $msgArray
    */
   private function _saveRawMessage($msgArray, $return = false)
   {
      foreach ($msgArray as &$msgPart) {
         $msgPart = implode('|', $msgPart);
      }

      if ($return === false) {
         $this->_raw_messages[] = implode(chr(13), $msgArray);
      } else {
         return implode(chr(13), $msgArray);
      }
   }


   /**
    * Schleife, um auch verschachtelte Array aufzulösen
    *
    * @param $array
    */
   public function findFirst($array) {
      while (is_array($array) == true) {
         $array = $array[0];
      }

      return trim($array);
   }

   /**
    * imports HL7 file
    *
    * @param $filePath
    */
   public function importFile($filePath = null, $parseAfter = false)
   {
      $return = null;

      if ($filePath !== null && file_exists($filePath) === true) {

         //chr(10) und chr(10).chr(13) durch chr(13) ersetzen!!!
         $content = str_replace(array(chr(10), chr(10).chr(13)), array(chr(13)), file_get_contents($filePath));

         if ($this->_looksLikeHL7File($content) === true){
            $return = $parseAfter === false ? $content : $this->parseMsg($content, true);
         }
      }

      return $return;
   }


   /**
    * cheap check if content of file looks like a hl7-message
    *
    * @param unknown_type $content
    */
   private function _looksLikeHL7File($content = null)
   {
      $return = false;

      if ($content !== null &&
          strpos($content, 'MSH') !== false && strpos($content, '|') !== false && strpos($content, chr(13)) !== false ){
         $return = true;
      }

      return $return;
   }
}

?>
