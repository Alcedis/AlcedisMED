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

class alcOdtParserFunctions
{
   protected $_assign            = array();

   protected $_parseData         = array();

   protected $_odtLineBreak      = '[[break]]';

   /**
    *
    * Enter description here ...
    * @var Smarty
    */
   protected $_odtSmarty         = null;

   protected $_odtSoftPageBreak  = '<text:soft-page-break/>';

   protected $_delimiter = array(
      'left'   => '{{',
      'right'  => '}}',
   );

   protected $_controlCharacter = array();

   /**
    * constructor
    *
    */
   public function __construct()
   {
      foreach (array_merge(array_diff(range(0, 31), array(9, 10, 13)), array(127)) AS $character) {
         $this->_controlCharacter[] = chr($character);
      }
   }


   protected function _checkAlcFunctions($content)
   {
      //Mark delimiter for latter content check
      $content = str_replace($this->_delimiter['left'],  $this->_delimiter['containerLeft'] . $this->_delimiter['left'],   $content);
      $content = str_replace($this->_delimiter['right'], $this->_delimiter['right'] . $this->_delimiter['containerRight'], $content);

      //Remove Soft Page Breakes
      $content = str_replace($this->_odtSoftPageBreak, '', $content);

      //Foreach
      preg_match_all('~\[\[foreach ([^\]]*)\]\]|\[\[/foreach\]\]~', $content, $foreaches, PREG_OFFSET_CAPTURE);

      if (count(reset($foreaches)) > 0) {
         $positions = array_reverse(reset($foreaches));
         $vars      = array_reverse(end($foreaches));

         for ($i=0; $i < (count($positions) / 2); $i++) {
            $beginnS       = end($positions[($i * 2) + 1]);
            $beginnSLength = strlen(reset($positions[($i * 2) + 1]));
            $endS          = end($positions[$i * 2]);
            $endSLength    = strlen(reset($positions[$i * 2]));
            $var           = reset($vars[($i * 2) + 1]);
            $iterations    = count($this->_smarty->get_template_vars($var));

            //Text extraction
            $beginn  = substr($content, 0, $beginnS);
            $extract = substr($content, $beginnS + $beginnSLength, $endS - ($beginnS + $beginnSLength));
            $end     = substr($content, $endS + $endSLength);
            $inject  = '';

            if ($iterations > 0) {
               for ($index=0; $index < $iterations; $index++) {
                  $inject .= str_ireplace($this->_delimiter['left'] . '$' . $var . '[i]', $this->_delimiter['left'] . '$' .$var. "[{$index}]", $extract);
               }
            }

            $content = $beginn . $inject . $end;
         }
      }

      return $content;
   }


   /**
    * Find first record with condition
    *
    * @param array $records
    * @param array $condition
    */
   public function findRecords($records, $condition = array(), $firstOnly = false)
   {
      $datasets = array();

      if (is_array($condition) === false && strlen($condition) > 0) {
         $condition = array($condition);
      }

      if (is_array($records) == true && count($records) > 0) {
         //if no condition, return first $from
         if (count($condition) == 0) {
            if ($firstOnly === true) {
               $datasets = reset($records);
            } else {
               $datasets = $records;
            }
         } else {
            foreach ($records as $record) {
               //Check conditions
               foreach ($condition as $cond) {
                  $bool = eval("return {$cond};");

                  if ($bool === false) {
                     continue 2;
                  }
               }

               if ($firstOnly === true) {
                  $datasets = $record;
                  break;
               } else {
                  $datasets[] = $record;
               }
            }
         }
      }


      return $datasets;
   }

   /**
    * Methodenaufruf für CHE
    *
    * @param $records
    * @param $condition
    * @param $firstOnly
    */
   public function findAllRecords($records, $condition = array(), $firstOnly = false)
   {
      return $this->findRecords($records, $condition, $firstOnly);
   }


   /**
    * Finds records with condition
    *
    * @param $records
    * @param $condition
    */
   public function findFirstRecord($records, $condition = array())
   {
      if (is_array($condition) === false && strlen($condition) > 0) {
         $condition = array($condition);
      }

      return $this->findRecords($records, $condition, true);
   }


   /**
    * Find last record from with condition
    *
    * @param $records
    * @param $condition
    */
   public function findLastRecord($records, $condition = array())
   {
      $dataset = array();

      if (is_array($condition) === false && strlen($condition) > 0) {
         $condition = array($condition);
      }

      if (is_array($records) == true && count($records) > 0) {
         $records = array_reverse($records, true);
         $dataset = $this->findRecords($records, $condition, true);
      }

      return $dataset;
   }


   /**
    * adds additional parsing Data to class
    *
    * @param $data   string
    * @param $data   array
    */
   public function addParseData($index, $data)
   {
      if (is_array($data) === true) {
         $this->_parseData[$index] = $data;
      }

      return $this;
   }


   /**
    * Converts all given data to UTF 8
    *
    * @param $in
    */
   protected function _to_utf8($in)
   {
      $out = array();

      if (is_array($in) === true) {
         foreach ($in as $key => $value) {
             $out[$this->_to_utf8($key)] = $this->_to_utf8($value);
         }
      } elseif (is_string($in) == true) {
         $optimizedString  = str_replace($this->_controlCharacter, '', $in);
         $string           = convertHtmlspecialchars(unescape($optimizedString));
         $out              = iconv('Windows-1252', 'UTF-8//TRANSLIT', $string);
      } else {
         $out = $in;
      }

      return $out;
   }


   /**
    * Assigns text to pool
    *
    * @param $name
    * @param $input
    */
   public function assignText($name, $input)
   {
      $this->_assign[$name] = $input;

      return $this;
   }

   /**
    * Assigns array to pool
    *
    */
   public function assignOdtArray($name, $input, $break = null)
   {
      $default = array('subhead' => '', 'content' => '');

      if (count($input) == 0) {
         $this->_assign[$name] = $default;
      } elseif (reset(array_keys($input)) == 'subhead') {
         $this->_assign[$name] = $this->_convertOdtArray($input);
      } else {
         $tmpArray = array('subhead' => array(), 'content' => array());

         foreach ($input as $index => $block) {
            foreach ($block as $section => $sectionData) {
               $tmpArray[$section] = $index > 0 ? array_merge($tmpArray[$section], array(''), $sectionData) : array_merge($tmpArray[$section], $sectionData);
            }
         }

         $this->_assign[$name] = $this->_convertOdtArray($tmpArray);
      }

      return $this;
   }


   /**
    * Converts the Text for ODT
    *
    * @param $input
    */
   protected function _convertOdtArray($input)
   {
      foreach ($input as $section => $sectionContent) {
         $input[$section] = $this->_addArrayBreaks($sectionContent);
      }

      return $input;
   }


   /**
    * Assigns one dimensional array
    *
    * @param $name
    * @param $input
    */
   public function assignArray($name, $input)
   {
      $return = '';

      if (count($input) > 0) {
         $return = $this->_addArrayBreaks($input);
      }

      return $return;
   }


   /**
    * Merges two or more arrays with seperator
    *
    * @param $arrays - array with arrays to merge
    * @param $seperator - string/array with seperator
    */
   public function arrayMergeWs($arrays, $seperator = array(' '))
   {
      if (!is_array($seperator)) {
         $seperator = array($seperator);
      }

      $return = array();

      if (is_array($arrays)) {
         foreach ($arrays as $array) {
            if (array_filter($array) != array()) {
               if (array_filter($return) != array()) {
                  $return = array_merge($return, $seperator);
               }

               $return = array_merge($return, array_filter($array));
            }
         }
      }

      return $return;
   }

   /**
    * transforms empty array content to compatible ODT breaks
    *
    * @param $input
    */
   protected function _addArrayBreaks($input)
   {
      $count = count($input);

      foreach ($input AS $index => $value) {
         if (($index + 1) < $count) {
            $input[$index] .= $this->_odtLineBreak;
         }
      }

      return implode('', $input);
   }


   public function getAllRecords($position = '', $data = null)
   {
      $records = array();

      $data = $data !== null ? $data : $this->_parseData;

      if (strlen(trim($position)) > 0) {
         $explode         = explode('.', $position);
         $currentPosition = reset($explode);

         if (count($explode) > 1 && array_key_exists($currentPosition, $data)) {
            //Position um 1 index kürzen
            array_shift($explode);

            $restPosition = implode('.', $explode);

            $records = $this->getAllRecords($restPosition, $data[$currentPosition]);
         } else if (count($explode) == 1 && array_key_exists($currentPosition, $data)) {
            $records = $data[$currentPosition];
         }
      }

      return $records;
   }

   /**
    *
    * Enter description here ...
    * @param $type
    * @param $data
    * @param $field
    */
   public function get($type, $data = array(), $field = '')
   {
      $return = '';

      if (is_array($data) === true && strlen($field) > 0 && array_key_exists($field, $data) && array_key_exists($type, $data[$field])) {
         $return = $data[$field][$type];
      }

      return $return;
   }


   /**
    * returns value of the field
    *
    * @param $data
    */
   public function getValue($data = array(), $field = '')
   {
      return $this->get('value', $data, $field);
   }


   /**
    * returns value of the field
    *
    * @param $data
    */
   public function getDescription($data = array(), $field = '')
   {
      return $this->get('bez', $data, $field);
   }

   /**
    * returns config lbl of the field
    *
    * @param $data
    */
   public function getConfig($data = array(), $field = '')
   {
      return $this->get('lbl', $data, $field);
   }

   protected function _removeEmptyTableRows($content)
   {
      //remove empty tables e.c rows
      $regExp = '/<[^<>]*>|[^<>]+/';

      $drawRegExp = '<(draw)|';
      $rowRegExp  = '/' . $drawRegExp . $this->_delimiter['containerLeft'] . '([^'. $this->_delimiter['containerLeft'] . $this->_delimiter['containerRight'] . ']*)' . $this->_delimiter['containerRight'] . '/';

      preg_match_all($regExp, $content, $tags);

      if (count($tags) > 0) {
         $tags       = reset($tags);

         $tmpTables  = array();
         $tables     = array();

         $index      = 0;
         foreach ($tags as $i => $tag) {
            $action = false;

            //find all tables
            if (strpos($tag, '<table:table ') !== false) {
               $tmpTables[$index]['start'] = $i;
               $tmpTables[$index]['layer'] = $index;
               $index++;
            } else if(strpos($tag, '</table:table>') !== false) {
               $index--;
               $tmpTables[$index]['end'] = $i;
               $action = true;
            }

            if ($action === true && $index === 0) {
               foreach ($tmpTables as $tmpTable) {
                  $tables[] = $tmpTable;
               }
               $tmpTables = array();
            }
         }

         //sort table with layer
         $tableLayer = array();
         foreach ($tables as $table) {
            $tableLayer[$table['layer']][] = $table;
         }

         $tableLayer = array_reverse($tableLayer);

         if (count($tableLayer) > 0) {
            $removeRows = array();

            //remove rows, later table
            foreach ($tableLayer as $layer) {
               foreach ($layer as $table) {

                  //copy the parts of the table to $extractedTable
                  $extractedTable = array();

                  foreach ($tags as $tagIndex => $tagRow) {
                     if ($tagIndex >= $table['start'] && $tagIndex <= $table['end']) {
                        $extractedTable[$tagIndex] = $tagRow;
                     }
                  }

                  //Search table-row position in extraced table section
                  $index      = 0;
                  $tableRows  = array();

                  $layerLevel = -1;
                  //Search only in the current layer level for rows
                  foreach ($extractedTable as $i => $tag) {
                     if (strpos($tag, '<table:table ') !== false) {
                        $layerLevel++;
                     }

                     if (strpos($tag, '</table:table>') !== false) {
                        $layerLevel--;
                     }

                     //Alle Tables markieren
                     if (strpos($tag, '<table:table-row') !== false && $layerLevel == 0) {
                        $tableRows[$index]['start'] = $i;
                     } else if(strpos($tag, '</table:table-row>') !== false && $layerLevel == 0) {
                        $tableRows[$index]['end'] = $i;
                        $index++;
                     }
                  }

                  $rowsEmpty = 0;

                  //find table rows whhich are empty
                  foreach ($tableRows as $row) {
                     $extractedRow = array();

                     //copy the parts of the row to $extractedRow
                     foreach ($extractedTable as $index => $content) {
                        if ($index >= $row['start'] && $index <= $row['end']) {
                           $extractedRow[$index] = $content;
                        }
                     }

                     //Merge Row array to string for preg match
                     $rowString = implode('', $extractedRow);
                     preg_match_all($rowRegExp, $rowString, $matches);

                     array_shift($matches);

                     //no assigned data from parser found e.c all content is completely empty
                     if (check_array_content($matches) !== true) {
                        $rowsEmpty++;

                        //remove rows
                        foreach ($extractedRow as $index => $content) {
                           unset($tags[$index]);
                        }
                     }
                  }

                  //if count of removed rows same like count of available rows then delete complete table
                  if (count($tableRows) == $rowsEmpty) {
                     foreach ($tags as $tagIndex => $tagRow) {
                        if ($tagIndex >= $table['start'] && $tagIndex <= $table['end']) {
                           unset($tags[$tagIndex]);
                        }
                     }
                  }
               }
            }
            //create new content with stripped tables
            $content = implode('', $tags);
         }
      }

      return $content;
   }


   public function getSmartyVar($var = '')
   {
      $layer = explode('.', $var);

      if (count($layer) > 1) {
         $templateVar = $this->_odtSmarty->get_template_vars(reset($layer));

         array_shift($layer);

         if (count($layer) > 0) {
            $s       = implode('.', $layer);
            $return  = $this->getAllRecords($s, $templateVar);
         } else {
            $return  = $templateVar;
         }

         return $return;
      } else {

         $find = strlen(reset($layer)) > 0 ? reset($layer) : null;

         return $this->_odtSmarty->get_template_vars($find);
      }
   }

}

?>
