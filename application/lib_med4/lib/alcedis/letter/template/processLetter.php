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

class ProcessLetter
{
    /**
     * Tag for variables in the template
     *
     * @access protected
     * @var string
     */
    protected $_tag = "##";


    /**
     * Type for table nodes
     *
     * @access const
     * @var string
     */
    const TABLE = "table:table";


    /**
     * Type for table nodes
     *
     * @access const
     * @var string
     */
    const TABLE_ROW = "table:table-row";


    /**
     * Type for frames
     *
     * @access const
     * @var string
     */
    const FRAME = "draw:frame";


    /**
     * Type for images
     *
     * @access const
     * @var string
     */
    const IMAGE = "draw:image";


    /**
     * Prefix for picturename to repeat the picture
     *
     * @access const
     * @var string
     */
    const REPEAT_PICTURE = "REPEAT-PICTURE";


    /**
     * Prefix for tablename to repeat the table
     *
     * @access const
     * @var string
     */
    const REPEAT_TABLE = "REPEAT-TABLE";


    /**
     * Seperator char
     *
     * @access protected
     * @var char
     */
    const SEPERATOR = "_";


    /**
     * Odt as array
     *
     * @access protected
     * @var array
     */
    protected $_odtAsArray = array();


    /**
     * Object of first node
     *
     * @access protected
     * @var object
     */
    protected $_firstNode = null;


    /**
     * Unique ID for the tmp folder
     *
     * @access protected
     * @var string
     */
    protected $_uniqueId = "";


    /**
     * Dir seperator for the OS
     *
     * @access protected
     * @var char
     */
    protected $_dirSeperator = "";


    /**
     * Folder for tmp dir
     *
     * @access protected
     * @var string
     */
    protected $_tmpDir = "";


    /**
     * Saves the inserts for the template
     *
     * @access protected
     * @var array
     */
    protected $_insertArray = array();


    /**
     * Name of the actual table in the template
     *
     * @access protected
     * @var string
     */
    protected $_tableName = "";


    /**
     * Array with the images to replace
     *
     * @access protected
     * @var string array
     */
    protected $_images = array();


    /**
     * Counts the repeats of a nested table
     *
     * @var int
     */
    protected $_repeats = 1;


    /**
     * Array with the counts of Rows to repeat
     *
     * @access protected
     * @var array
     */
    protected $_tableRepeatings = array();


    /**
     * Constructor for process.php
     *
     * @access public
     * @param string $uniqueId
     * @param char $dirSeperator
     * @param string $tmpDir
     */
    public function __construct($uniqueId = '') {
        $this->_uniqueId    = $uniqueId;
    }


    /**
     * Sets the first node
     *
     * @access public
     * @param $newFirstNode
     */
    public function setFirstNode($newFirstNode)
    {
        $this->_firstNode = $newFirstNode;

        return $this;
    }


    /**
     * Gets odt as an array
     *
     * @access public
     * @return array $this->_odtAsArray
     */
    public function getOdtAsArray()
    {
        return $this->_odtAsArray;
    }


    /**
     * Sets the insert array
     *
     * @access public
     * @param $newInsertArray
     * @return $this
     */
    public function setInsertArray ($newInsertArray)
    {
        $this->_insertArray = $newInsertArray;

        return $this;
    }


    /**
     * Gets the image array
     *
     * @access public
     * @return $this
     */
    public function getImageArray ()
    {
        return $this->_images;
    }


    /**
     * Starts the process method
     *
     * @access public
     * @return $this
     */
    public function convertIt()
    {
        $this->_process($this->_firstNode);
        return $this;
    }


    /**
     * Gets the name of the item
     *
     * @access protected
     * @param node $node
     * @return string $name
     */
    protected function _getMyNamedItem($node)
    {
        $attr = $node->attributes;
        $name = $attr->getNamedItem("name");

        if ($name === NULL) {
            return $node;
        }

        return $name;
    }


    /**
     * Gets the table name of the actual table in the template
     *
     * @access protected
     * @param node $node
     */
    protected function _getTableName($node)
    {
        if (is_numeric(substr($node->nodeValue,0,strpos($node->nodeValue, self::SEPERATOR)))) {
            $this->_tableName  = substr($node->nodeValue,
                                        strpos($node->nodeValue, self::SEPERATOR) + 1,
                                        strlen($node->nodeValue)
                                 );
        } else {
            $this->_tableName  = $node->nodeValue;
        }
    }


    /**
     * Counts the max number of rows
     *
     * @access protected
     * @return $maxRows
     */
    protected function _getMaxRows($node)
    {
        $maxRow  = substr($node->nodeValue,0,strpos($node->nodeValue, self::SEPERATOR));
        if (!is_numeric($maxRow) OR $maxRow > $this->_getNumberOfArrayEntries($node)) {
            $maxRow = $this->_getNumberOfArrayEntries($node);
        }

        return $maxRow;
    }


    /**
     * Counts the entries für this table in $this->_insertArray
     *
     * @access protected
     * @param node $node
     * @return int $anzahl/0
     */
    protected function _getNumberOfArrayEntries($node)
    {
        if (isset ($this->_insertArray[$this->_tableName])) {
            $anzahl = count($this->_insertArray[$this->_tableName]);
            return $anzahl;
        }
        return 0;
    }


    /**
     * Adds the image into an array to replace in template
     *
     * @access protected
     * @param node $node
     */
    protected function _addImage2Array($node)
    {
        $namedItem = $this->_getMyNamedItem($node);
        if ($namedItem) {
            $var        = $namedItem->nodeValue;
            $frameChild = $node->childNodes;
            foreach ($frameChild as $picture) {
                if ($picture->nodeName === self::IMAGE) {
                    $attr = $picture->attributes;
                    $name = $attr->getNamedItem("href");
                    $file = $name->nodeValue;
                    $this->_images[$var]=$file;
                }
            }
        }
    }


    /**
     * Starts the repeating process
     *
     * @access protected
     * @param node $nodes
     * @param int $maxRow
     * @param int $deep
     */
    protected function _process(&$nodes, $maxRow=0, $deep=0)
    {
        foreach ($nodes as $node) {
            if ($node->nodeType !== 1 AND $node->nodeType !== 3) {
                continue;
            }

            // Special treatment if the node is a table
            if ($node->nodeName === self::TABLE) {
                $namedItem      = $this->_getMyNamedItem($node);
                $this->_getTableName($namedItem);
                $maxRow         = $this->_getMaxRows($namedItem);
                $tableName      = $this->_tableName;
                $clearTableName = $this->_processClearTableName($tableName);

                if ($clearTableName != $tableName) {
                    $this->_processTable($node, $clearTableName);
                }
            }
            // Special treatment if the node is a draw:frame
            else if ($node->nodeName === self::FRAME) {
                $this->_processFrame($node);
            }
            // Special treatment if the node is a tablerow
            else if ($node->nodeName === self::TABLE_ROW && $firstChildChild = $node->firstChild->firstChild) {
                $this->_processRow($node, $maxRow);
            }
            // Special treatment if the node is "normal" text
            else if ($node->nodeType === 3) {
                $node->nodeValue = $this->_replaceTags($node->nodeValue, $this->_insertArray);
            }

            // Rekursion if the node has Children
            if($node->hasChildNodes())
            {
                $childs = $node->childNodes;
                $this->_process($childs,  $maxRow);
            }
        }
    }


    /**
     * Repeats the Rows of the Table in a Table
     *
     * @access protected
     * @param node $node
     */
    protected function _processTable(&$node, $clearTableName) {
        $sumElements    = count($this->_insertArray[$clearTableName]);
        $rowsPerRepeat  = $this->_processRowRepeat($node->childNodes, $sumElements);
        $parentNode     = $node->parentNode;
        $this->_processNested($parentNode->childNodes, $clearTableName, $rowsPerRepeat);
    }


    /**
     * Repeats the Pictures in a Table
     *
     * @access protected
     * @param node $node
     */
    protected function _processFrame(&$node)
    {
        $tableName      = $this->_tableName;
        $namedItem      = $this->_getMyNamedItem($node);
        $var            = $namedItem->nodeValue;
        $prefix         = substr($var, 0, 14);
        $pictureName    = substr($var, 15);

        if ($prefix === self::REPEAT_PICTURE) {
            if (isset($this->_insertArray[$pictureName]) === true &&
                (is_array($this->_insertArray[$pictureName]) || strlen($this->_insertArray[$pictureName]) > 0)) {

                $this->_addImage2Array($node);

                if (is_array($this->_insertArray[$pictureName]) === true) {
                   for ($i = 1; $i < count($this->_insertArray[$pictureName]); $i++) {
                       $parentNode = $node->parentNode;
                       //Search tablerow and count nodes until tablerow
                       while ($parentNode->nodeName != self::TABLE_ROW) {
                           $parentNode = $parentNode->parentNode;
                       }

                       //Clone node including children
                       $cloneNode = $parentNode->cloneNode(true);
                       $newParentNode = $parentNode->parentNode;
                       $newParentNode->insertBefore($cloneNode, $parentNode);

                       $this->_renamePicture($node, $i, $pictureName);
                       $this->_addImage2Array($node);
                   }
                }
            } else {
                $this->_addImage2Array($node);
            }
        } else {
            $this->_addImage2Array($node);
        }
    }


    /**
     * Repeats the variables in a Table
     *
     * @access protected
     * @param node $node
     */
    protected function _processRow(&$node, $maxRow) {
        $firstChildChild = $node->firstChild->firstChild;
        $replace         = false;
        $value           = substr($firstChildChild->nodeValue,0,2);

        if ($value === $this->_tag){
            $replace = true;
        }

        if ($replace && $maxRow > 0) {
            $rowNumber=0;
            while ($rowNumber < $maxRow-1) {
                //Clone row and insert before origin
                $cloneNode  = $node->cloneNode(true);
                $parentNode = $node->parentNode;
                $parentNode->insertBefore($cloneNode, $node);
                //Modify cloned rows -> replace tags
                $this->_editTableRow($cloneNode, $rowNumber);
                $rowNumber++;
            }
            //Modify origin row -> replace tags
            $this->_editTableRow($node, $rowNumber);
        }
    }


    /**
     * The lines are repeated as often as variables in the array
     *
     * @access protected
     * @param node $nodes
     * @param int $outerRepeats
     */
    protected function _processRowRepeat(&$nodes, $outerRepeats) {
        $startRow = 0;
        foreach ($nodes as $node) {
            if ($node->nodeName === self::TABLE_ROW) {
                if (strpos($node->nodeValue, $this->_tag) !== false) {
                    $row = 0;
                    for ($i = 0; $i < $outerRepeats - 1; $i++) {
                        foreach ($nodes as $innerNode) {
                            if ($innerNode->nodeName === self::TABLE_ROW) {
                                if ($row === $startRow) {
                                    $cloneNode  = $innerNode->cloneNode(true);
                                    $parentNode = $innerNode->parentNode;
                                    $parentNode->insertBefore($cloneNode, $node);
                                    $startRow++;
                                    $row++;
                                } else {
                                    $row++;
                                }
                            }
                        }
                        $row = 0;
                    }
                    $rowsPerRepeat = ($startRow - 1) / ($outerRepeats - 1);
                    return $rowsPerRepeat;
                } else {
                    $startRow++;
                }
            }
        }
    }


    /**
     * Starts the repeat process for the nested tables
     *
     * @param node $nodes
     * @param int $maxRow
     * @param int $deep
     */
    protected function _processNested(&$nodes, $outerTableName, $rowsPerRepeat, $outerPos = 0, $deep = 0) {
        foreach ($nodes as $node) {
            if ($node->nodeName === self::TABLE_ROW && $deep === 1) {
                if ($this->_repeats > $rowsPerRepeat) {
                    $this->_repeats = 1;
                    $outerPos++;
                }
            }

            //NodeName == table:table
            if ($node->nodeName === self::TABLE) {
                $this->_processNestedTable($node, $outerTableName, $outerPos, $deep);
            }

            // This Node is a TableRow from the InnerTable and the Value contains the ## Tag (Variables to replace)
            if ($node->nodeName === self::TABLE_ROW && $deep >= 3 && strpos($node->nodeValue, $this->_tag) !== false) {
                $this->_processNestedRow($node, $outerTableName, $outerPos);
            }

            //Replace "normal" text
            if ($node->nodeType === 3) {
                if ($deep === 4) {
                    $this->_repeats++;
                    $node->nodeValue = $this->_replaceTags(
                        $node->nodeValue,
                        $this->_insertArray[$outerTableName][$outerPos]);
                }
            }

            //Rekursion if node has Children
            if($node->hasChildNodes()){
                $this->_processNested(
                    $node->childNodes,
                    $outerTableName,
                    $rowsPerRepeat,
                    $outerPos,
                    $deep+1
                );
            }
        }
    }


    /**
     * Repeats the nested Table
     *
     * @access protected
     * @param node $node
     * @param string $outerTableName
     * @param int $outerPos
     * @param int $deep
     */
    protected function _processNestedTable(&$node, $outerTableName, $outerPos, $deep) {
        // This Node is an inner Table Node, getting the TableName from the Attributes
        if ($deep >= 3) {
            $innerTableName = "";
            if ($node->hasAttributes()){
                foreach ($node->attributes as $attribute){
                    if ($attribute->name === "name") {
                        $innerTableName = $attribute->value;
                    }
                }
            }
            $innerTableName = $this->_processClearTableName($innerTableName);
            $innerRepeatings = count($this->_insertArray[$outerTableName][$outerPos][$innerTableName]);
            $this->_tableRepeatings[$innerTableName] = $innerRepeatings;
        }
    }


    /**
     * Repeats the Row in a nestes Table
     *
     * @access protected
     * @param node $node
     * @param string $outerTableName
     * @param int $outerPos
     */
    protected function _processNestedRow(&$node, $outerTableName, $outerPos) {
        $innerTableName = "";
        // ParentNode = table Node from the InnerTable
        // Getting TableName of the InnerTable from the Attributes of the tableNode
        if ($node->parentNode->hasAttributes()){
            foreach ($node->parentNode->attributes as $attribute){
                if ($attribute->name === "name") {
                    $innerTableName = $attribute->value;
                }
            }
        }
        $innerTableName = $this->_processClearTableName($innerTableName);
        $rowNumber=0;

        // Clones the aktual Node (tableRow) for so many times how tableRepeatings - 1 and edits the TableRow
        while ($rowNumber < $this->_tableRepeatings[$innerTableName] - 1) {
            $cloneNode = $node->cloneNode(true);
            $parentNode = $node->parentNode;
            $parentNode->insertBefore($cloneNode, $node);

            $this->_editTableRow(
                $cloneNode,
                $rowNumber,
                $this->_insertArray[$outerTableName][$outerPos][$innerTableName]
            );

            $rowNumber++;
        }

        // Editing the origin TableRow
        $this->_editTableRow(
            $node,
            $rowNumber,
            $this->_insertArray[$outerTableName][$outerPos][$innerTableName]
        );
    }


    /**
     * Checks the given TableName and deletes the Prefix if available
     *
     * @access protected
     * @param string $tableName
     */
    protected function _processClearTableName($tableName) {
        $length = strlen($tableName);
        if ($length > 13) {
            $prefix = substr($tableName, 0, 12);
            if ($prefix === self::REPEAT_TABLE) {
                $tableName = substr($tableName, 13);
                return $tableName;
            }
        }
        return $tableName;
    }


    /**
     * Renames the picture in a given node to repeat the picture
     *
     * @access protected
     * @param node $node
     * @param int $index
     * @param string $pictureName
     */
    protected function _renamePicture($node, $index, $pictureName)
    {
        $namedItem = $this->_getMyNamedItem($node);

        if ($index != 0) {
            $namedItem->nodeValue = $pictureName . self::SEPERATOR . $index;
        }
        $frameChild = $node->childNodes;

        foreach ($frameChild as $picture) {
            if ($picture->nodeName === self::IMAGE) {
                $file       = $picture->attributes->getNamedItem("href")->nodeValue;
                $filename   = substr($file, 0, -4);
                $fileformat = substr($file, -4);
                $testchar   = substr($filename, -4, -3);
                if ($index != 0) {
                    $newIndex = sprintf("%'03d",$index);
                    if ($testchar === self::SEPERATOR) {
                        $filename = substr($filename, 0, -4);
                        $picture->attributes->getNamedItem("href")->nodeValue = $filename . self::SEPERATOR . $newIndex .
                                                                                $fileformat;
                    } else {
                        $picture->attributes->getNamedItem("href")->nodeValue = $filename . self::SEPERATOR . $newIndex .
                                                                                $fileformat;
                    }
                }
            }
        }
    }


    /**
     * Edit the node values if there is text in an table to repeat
     *
     * @access protected
     * @param node $node
     * @param int $rowNumber
     */
   protected function _editTableRow($node, $rowNumber, $insertArray = array())
   {
       if($node->hasChildNodes()) {
         $childs = $node->childNodes;
         foreach ($childs as $child)
         {
            $this->_editTableRow($child, $rowNumber, $insertArray);
            if ($child->nodeType === 3) {
               if (count($insertArray) === 0) {
                   $insertArray = $this->_insertArray[$this->_tableName];
               }
               $child->nodeValue = $this->_replaceTags(stripslashes($child->nodeValue), $insertArray[$rowNumber]);
            }
         }
      }
   }


    /**
     * Replaces the variable in the template with data from $insertArray
     *
     * @access protected
     * @param string $originalString - String to replace the variables
     * @param array $insertArray - Data to replace
     * @return string $originalString
     */
    protected function _replaceTags($originalString, $insertArray)
    {
        do {
            $erstesAufTag = strpos($originalString, $this->_tag);
            if ($erstesAufTag !== false) {
                $erstesZuTag = strpos($originalString, $this->_tag, ($erstesAufTag+2));
                if ($erstesZuTag === false) {
                    break;
                }
                $ersetzen = substr($originalString, $erstesAufTag, $erstesZuTag-$erstesAufTag+2);
                $suchen = substr($originalString, $erstesAufTag+2, ($erstesZuTag-$erstesAufTag)-2);
                $originalString = str_replace (
                                    $ersetzen,
                                    isset($insertArray[$suchen]) ? utf8_encode($insertArray[$suchen]) : "" ,
                                    $originalString);
            } else {
                break;
            }
        } while(true);

        return $originalString;
    }
}
?>
