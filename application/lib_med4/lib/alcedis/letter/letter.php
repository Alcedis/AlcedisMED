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

require_once(DIR_LIB . '/zip/pclzip.lib.php' );
require_once(DIR_LIB . '/alcedis/letter/template/startLetter.php');
require_once(DIR_LIB . '/alcedis/letter/template/convertLetter.php');

if (defined('PCLZIP_TEMPORARY_DIR') === false) {
    define('PCLZIP_TEMPORARY_DIR',  sys_get_temp_dir() . DIRECTORY_SEPARATOR);
}

class letter
{
     /**
     * Path to JOD converter service
     *
     * @access protected
     * @var url
     */
    protected $_convertService = 'http://localhost:8080/converter/service';


    /**
     * Tmp dir for pclzip lib
     *
     * @access protected
     * @var string
     */
    protected $_pclzipTemporaryDir = null;


    /**
     * Unique ID for the tmp folder
     *
     * @access protected
     * @var string
     */
    protected $_uniqueId = null;


    /**
     * dir lib
     *
     * @access protected
     */
    protected $_dirLib = null;


    /**
     * Saves the inserts for the template
     *
     * @access protected
     * @var array
     */
    protected $_insertArray = array();


    /**
     * Array with insertArray for the template - MED
     */
    protected $_insertArrayArray = array();


    /**
     * Saves the Letter Type e.g. 'pdf'
     *
     * @access protected
     * @var string
     */
    protected $_letterType = "pdf";


    /**
     * The outputfile to generate the letter
     *
     * @access protected
     * @var unknown_type
     */
    protected $_outputFile;


    /**
     * The template to generate the letter
     *
     * @access protected
     * @var object
     */
    protected $_letterTemplate;

   /**
    * Array for Templates to generate the letter. MED
    */
   protected $_letterTemplateArray;


    /**
     * Array with Images to replace
     *
     * @access protected
     * @var array
     */
    protected $_imageArray = array();


    /**
     * Array with Images to replace
     *
     * @access protected
     * @var array
     */
    protected $_replaceImageArray = array();


    /**
     * Array with Image-Array to replace - MED
     */
    protected $_replaceImageArrayArray = array();


    /**
     * Folder for tmp dir
     *
     * @access protected
     * @var string
     */
    const TMP_DIR = "letter";


    /**
     * Name of the content XML file
     *
     * @access protected
     * @var string
     */
    const CONTENT_XML = "content.xml";


    /**
     * Name of the style XML file
     *
     * @access protected
     * @var string
     */
    const STYLES_XML = "styles.xml";


    /**
     * Checked Checkbox
     * @var char
     */
    const CHECKBOX    = 'þ';


    /**
     * Unchecked Checkbox
     * @var char
     */
    const UNCHECKBOX = '¨';


    /**
     * Dir seperator for the OS
     *
     * @access protected
     * @var char
     */
    const DIR_SEPERATOR = "/";


    protected $_tmpDir = null;


    /**
     * Initializes some classvariables
     *
     * @access public
     */
    public function __construct( $unique = null)
    {
        //require_once('pclzip/pclzip.lib.php');
        if (isset($_SERVER['SERVER_NAME'])) {
            $this->_convertService = "http://".$_SERVER['SERVER_NAME'].":8080/converter/service";
        }

        $this->_pclzipTemporaryDir  = sys_get_temp_dir() . self::DIR_SEPERATOR;
        $this->_uniqueId            = $unique === null ? uniqid() : $unique;
    }


    /**
     * Factory
     *
     * @access public
     * @return letter
     */
    public static function create()
    {
        return new self;
    }


    /**
     * Add image to replace array
     *
     * @access public
     * @param array $newInsertArray
     */
    public function setInsertArray($newInsertArray)
    {
        if ($this->_insertArray === array()) {
            $this->_insertArray = $newInsertArray;
        } else {
            foreach ($newInsertArray as $newInsertKey => $newInsertValue) {
                $this->_insertArray[$newInsertKey] = $newInsertValue;
            }
        }

        return $this;
    }


   public function setInsertArrayArray($newInsertArray)
   {
      //Prüfen ob $newInsertArray ein "einfaches" oder "mehrfaches" Array ist
      $keys = array_keys($newInsertArray);
      $int  = false;

      foreach ($keys as $key) {
         $int = is_int($key)  ?  true  : false;
      }

      if (!$int) {
         $newInsertArray = array($newInsertArray);
      }

      //Array in Variable schreiben, ggf. mit vorhandenem Inhalt mergen
      if ($this->_insertArrayArray === array()) {
         $this->_insertArrayArray = $newInsertArray;
      } else {
         foreach ($newInsertArray as $newInsertKey => $newInsertValue) {
            $this->_insertArrayArray[$newInsertKey] = $newInsertValue;
         }
      }
   }


    /**
     * Chose LetterType for export
     *
     * @access public
     * @param string    $newLetterType
     */
    public function setLetterType($newLetterType)
    {
        $this->_letterType = $newLetterType;

        return $this;
    }


    /**
     * Chose LetterTemplate for export
     *
     * @access public
     * @param (.odt)File   $newLetterTemplate
     * @return $this
     */
    public function setLetterTemplate($newLetterTemplate)
    {
        if(is_file($newLetterTemplate))
            $this->_letterTemplate = $newLetterTemplate;
        else
            $this->_letterTemplate = null;

        return $this;
    }


   public function setLetterTemplateArray($newLetterTemplateArray)
   {
      //Prüfen ob einzelnes Template oder ein Array
      if (!is_array($newLetterTemplateArray)) {
         $newLetterTemplateArray = array($newLetterTemplateArray);
      }

      $this->_letterTemplateArray = $newLetterTemplateArray;
   }


    /**
     * Set URL to JOD converter service
     *
     * @access public
     * @param string    $url
     * @return $this
     */
    public function setUrlToJOD($url)
    {
        $this->_convertService = $url;

        return $this;
    }


    /**
     * Get the insertArray
     *
     * @access public
     * @return $this->_insertArray
     */
    public function getInsertArray()
    {
        return $this->_insertArray;
    }


    /**
     * Get the outputfile to generate the letter
     *
     * @access public
     * @return $this->_outputFile
     */
    public function getOutputFile()
    {
        return $this->_outputFile;
    }


    /**
     * Get the LetterType for export
     *
     * @access public
     */
    public function getLetterType()
    {
        return $this->_letterType;
    }


    /**
     * Get the LetterTemplate to export
     *
     * @access public
     */
    public function getLetterTemplate()
    {
        return $this->_letterTemplate;
    }


    /**
     * Get the URL to JOD converter Server
     *
     * @access public
     */
    public function getUrlToJOD()
    {
        return $this-$_convertService;
    }


    /**
     * Generates the letter content
     *
     * @access public
     * @return $this
     */
    public function generateLetter()
    {
        $this->_checkNeeds()
        // HTML Eintraege in Array ersetzen!
        // Sollte aber nicht vorkommen!!!!
        // Sobald das sicher gestellt ist, wieder rausnehmen!!
             ->_replaceHtmlInArray($this->_insertArray)
             ->_unzipContentXmlToTmpDir()
             ->_doConversion($this->_getContentXmlPath())
             ->_doConversion($this->_getStyleXmlPath())
             ->_replaceImages()
             ->_zipContentXmlToOdt()
             ->_outputFile()
             ->_cleanUp();
        return $this;
    }





   public function generateLetterArray()
   {
      if (count($this->_letterTemplateArray) !== count($this->_insertArrayArray)) {
         echo "Die Anzahl Elemente im Template-Array stimmt nicht mit der Anzahl Elemente im Insert-Array überein! Bitte prüfen!";
         exit;
      } else {
         $resultXml = '';

         foreach ($this->_letterTemplateArray as $nr => $letterTemplate) {
            // replaceImageArray leeren und neu füllen
            $this->_replaceImageArray  = array();

            /*if (array_key_exists($nr, $this->_replaceImageArrayArray) === true) {
               foreach ($this->_replaceImageArrayArray[$nr] as $file => $name) {
                  $this->setImage($file, $name);
               }
            }*/

            $this->setLetterTemplate($letterTemplate);
            $this->setInsertArray($this->_insertArrayArray[$nr]);

            $this->_checkNeeds()
               ->_replaceHtmlInArray($this->_insertArray)
               ->_unzipContentXmlToTmpDir($nr)
               ->_doConversionArray($this->_getContentXmlPath())
               ->_doConversionArray($this->_getStyleXmlPath())
               ->_replaceImages();
            $content = implode(" ",file($this->_getContentXmlPath()));

            if ($nr === 0) {
               $resultXml = $content;
            } else {
               $regExp  = '~<office:body><office:text>(.*)</office:text></office:body>~';
               $newContent  = array();
               preg_match($regExp, $content, $newContent);

               $regExp     = '~<office:body><office:text>(.*)(<text:sequence-decls>(.*)</text:sequence-decls>)?</office:text></office:body>~';
               $replace    = '<office:body><office:text>\1\2' . $newContent[1] . '</office:text></office:body>';
               $content    = preg_replace($regExp, $replace, $resultXml);

               $resultXml  = $content;
            }
         }

         file_put_contents($this->_getContentXmlPath(), $resultXml);

         $this->_zipContentXmlToOdt()
            ->_outputFile()
            ->_cleanUp();
         return $this;
      }
   }


    /**
     * Checks wheather all required variables are set
     *
     * @access protected
     */
    protected function _checkNeeds()
    {
        if ($this->_insertArray === false) {
            $this->_deleteTmp(2);
            //Exception
            echo "No insert Array specified";
        }
        if ($this->_letterType === false) {
            $this->_deleteTmp(2);
            //Exception
            echo "No Report export Type specified";
        }
        if ($this->_letterTemplate === false) {
            $this->_deleteTmp(2);
            //Exception
            echo "No Report Template specified";
        }
        if ($this->_convertService === false) {
            $this->_deleteTmp(2);
            //Exception
            echo "No URL to JODConverter specified";
        }

        return $this;
    }


    /**
     * Replaces the images from the template with those from the $this->_replaceImageArray
     *
     * @access protected
     * @return $this
     */
    protected function _replaceImages()
    {
        foreach ($this->_replaceImageArray as $name=>$picture) {
            if (isset($this->_imageArray[$name])) {
                $file = $this->_imageArray[$name];
            } else {
                continue;
            }

            $tmpDir = $this->_getTmpDir();
            if (file_exists($picture) === false) {
                $this->_deleteTmp(2);
                //Exception
                echo "file $picture... doesn't exist";
            }
            if (copy($picture, $tmpDir . basename($this->_imageArray[$name])) === false) {
                $this->_deleteTmp(2);
                //Exception
                echo "failed to copy $picture...";
            }

            $archiveFile = $this->_getFileFromString();
            $archive     = new PclZip($tmpDir . $archiveFile);
            if ($archive->add($tmpDir . basename($this->_imageArray[$name]),
                                  PCLZIP_OPT_ADD_PATH, "Pictures",
                                  PCLZIP_OPT_REMOVE_ALL_PATH) == 0) {
                $this->_deleteTmp(2);
                //Exception
                echo "$archive->errorInfo(true)";
            }
        }

        return $this;
    }


    /**
     * Adds image to $this->_replaceImageArray
     *
     * @param string(url)   $file
     * @param string        $name
     */
    public function setImage($file, $name)
    {
        if (is_array($file)) {
            $index = 0;

            foreach ($file as $single) {
                $this->insertArray[$name][$index] = $single;
                if ($index === 0) {
                    $nameTmp = 'REPEAT-PICTURE_' . $name;
                } else {
                    $nameTmp = $name . "_" . $index;
                }
                $this->_replaceImageArray[$nameTmp] = $single;
                $index++;
            }

        } else {
            $this->_replaceImageArray[$name] = $file;
        }

        return $this;
    }


   public function setImageArray($file)
   {
      if (is_array($file)) {
         $keys = array_keys($file);
         $int  = false;

         foreach ($keys as $key) {
            $key = is_int($key)  ?  true  :  false;
         }

         if ($key) {
            $this->_replaceImageArrayArray = $file;
         } else {
            echo "Der Aufbau des ImageArray entspricht nicht den Vorrausetzungen! Bitte prüfen!";
            exit;
         }

      } else {
         echo "Der Aufbau des ImageArray entspricht nicht den Vorrausetzungen! Bitte prüfen!";
         exit;
      }

   }


    /**
     * Replace all HTML tags in the template
     *
     * @access protected
     * @param array $htmlArray
     * @return $this
     */
    protected function _replaceHtmlInArray(&$entry = array())
    {
        foreach ($entry AS $key => &$value) {
            if (is_array($value)) {
                $this->_replaceHtmlInArray($value);
            } else {
                $value = unescape($value);
            }
        }
        $this->_insertArray = $entry;

        return $this;
    }


    /**
     * Gets the content from the XML
     *
     * @access protected
     * @return $contentXml
     */
    protected function _getContentXmlPath()
    {
        $contentXml = $this->_getTmpDir() . self::CONTENT_XML;

        return $contentXml;
    }


    /**
     * Gets the style from the XML
     *
     * @access protected
     * @return $contentXml
     * Enter description here ...
     */
    protected function _getStyleXmlPath()
    {
        $contentXml = $this->_getTmpDir() . self::STYLES_XML;

        return $contentXml;
    }


    /**
     * Seperates the name from the letterTemplate
     *
     * @access protected
     * @return $file
     */
    protected function _getFileFromString()
    {
        $file = substr($this->_letterTemplate,strrpos($this->_letterTemplate, self::DIR_SEPERATOR) + 1);

        return $file;
    }


    public function setTmpDir($tmpDir = null)
    {
        if ($tmpDir !== null) {
           $this->_tmpDir = $tmpDir;
        }

        return $this;
    }


    /**
     * Gets the temporary path for the actuall letter
     *
     * @access protected
     * @return $tmpDir
     */
    protected function _getTmpDir()
    {
        if ($this->_tmpDir !== null) {
           $tmpDir = $this->_tmpDir;
        } else {
           $tmpDir = sys_get_temp_dir() . self::DIR_SEPERATOR . self::TMP_DIR . self::DIR_SEPERATOR . $this->_uniqueId . self::DIR_SEPERATOR;
        }

        return $tmpDir;
    }


    /**
     * Extracts the content from the XML to the temporary dir
     *
     * @access protected
     * @return $this
     */
    protected function _unzipContentXmlToTmpDir ($nr = '')
    {
        $file       = $this->_getFileFromString();
        $tmpDir     = $this->_getTmpDir();
        $tmpRootDir = sys_get_temp_dir() . self::DIR_SEPERATOR . self::TMP_DIR . self::DIR_SEPERATOR;
        if(is_dir($tmpRootDir) === false) {
            mkdir ($tmpRootDir, 0775, true);
        } else {
            if (is_file($tmpDir . 'content.xml')) {
               unlink($tmpDir . 'content.xml');
            }
            chmod($tmpRootDir, 0775);
        }
        if(is_dir($tmpDir) === false) {
            mkdir ($tmpDir, 0775, true);
        }
        copy($this->_letterTemplate, $tmpDir . $file);

        $archive = new PclZip($tmpDir . $file);
        if ($archive->extract(PCLZIP_OPT_BY_NAME, self::CONTENT_XML, PCLZIP_OPT_PATH, $tmpDir) == 0) {
            $this->_deleteTmp(2);
            //Exception
            echo $archive->errorInfo(true);
        }
        if ($archive->extract(PCLZIP_OPT_BY_NAME, self::STYLES_XML, PCLZIP_OPT_PATH, $tmpDir) == 0) {
            $this->_deleteTmp(2);
            //Exception
            echo $archive->errorInfo(true);
        }

        return $this;
    }


    /**
     * Zips the content into the .odt file
     *
     * @access protected
     * @return $this
     */
    protected function _zipContentXmlToOdt()
    {
        $file    = $this->_getFileFromString();
        $tmpDir  = $this->_getTmpDir();
        $archive = new PclZip($tmpDir . $file);

        if ($archive->add($tmpDir . self::CONTENT_XML, PCLZIP_OPT_REMOVE_ALL_PATH) == 0) {
            $this->_deleteTmp(2);
            //Exception
            echo $archive->errorInfo(true);
        }
        if ($archive->add($tmpDir . self::STYLES_XML, PCLZIP_OPT_REMOVE_ALL_PATH) == 0) {
            $this->_deleteTmp(2);
            //Exception
            echo $archive->errorInfo(true);
        }

        return $this;
    }


    /**
     * Converts the templateFile into the output file
     *
     * @param xml $odtXmlFile
     * @access protected
     * @return $this
     */
    protected function _doConversion ($odtXmlFile)
    {
        $odtXml = new StartLetter($odtXmlFile, $this->_uniqueId);
        $odtXml->setInsertArray($this->_insertArray)
               ->getResult();
        $this->_imageArray = array_merge($this->_imageArray, $odtXml->getImageArray());
        $odtXml->save ($odtXmlFile);

        return $this;
    }


   protected function _doConversionArray($odtXmlFile)
   {
      $odtXml = new StartLetter($odtXmlFile, $this->_uniqueId);
      $odtXml->setInsertArray($this->_insertArray)
             ->getResult();
      $this->_imageArray = array_merge($this->_imageArray, $odtXml->getImageArray());
      $odtXml->save($odtXmlFile);

      return $this;
   }


    /**
     * Extracts the extension from the file
     *
     * @access protected
     * @return $file
     */
    protected function _getFilenameWithoutExtension()
    {
        $file = substr($this->_getFileFromString(),0,strrpos($this->_getFileFromString(),".")+1);

        return $file;
    }


    /**
     * Puts everything together and generates the outputData
     *
     * @access protected
     * @return $this
     */
    protected function _outputFile()
    {
        $documentConverter  = new ConvertLetter();
        $file               = $this->_getFileFromString();
        $tmpDir             = $this->_getTmpDir();
        $inputFile          = $tmpDir . $file;
        $inputType          = "application/vnd.oasis.opendocument.text";
        $unique             = $this->_uniqueId . '_';

        switch ($this->_letterType) {
            case 'pdf':
                $outputType = "application/pdf";
                break;

            case 'odt':
                $outputType = "application/vnd.oasis.opendocument.text";
                break;

            case 'rtf':
                $outputType = "text/rtf";
                break;

            case 'doc':
                $outputType = "application/msword";
                break;

            case 'txt':
                $outputType = "text/plain";
                break;

            case 'html':
                $outputType = "text/html";
                break;

            default:
                $outputType = "application/pdf";
                $this->setLetterType("pdf");
                break;
        }

        $this->_outputFile = $this->_getTmpDir() . $unique . $this->_getFilenameWithoutExtension() . $this->getLetterType();
        $documentConverter->setUrlToJOD($this->_convertService);
        $outputData        = $documentConverter->convert(file_get_contents($inputFile), $inputType, $outputType);
        if ($outputData) {
            file_put_contents($this->_outputFile, $outputData);
        } else {
            $this->_deleteTmp(2);
            //Exception
            echo "JODConverter Service on Host " . $this->_convertService . " unreacheable";
        }

        return $this;
    }


    /**
     * Deletes all non pdf files from tmp dir
     *
     * @access protected
     * @return $this
     */
    protected function _cleanUp()
    {
        $file        = $this->_getFileFromString();
        $tmpDir      = $this->_getTmpDir();
        $odtFile     = $tmpDir . $file;
        $contentFile = $tmpDir . self::DIR_SEPERATOR . self::CONTENT_XML;
        $styleFile   = $tmpDir . self::DIR_SEPERATOR . self::STYLES_XML;

        //Deletes all non PDFs in the dir
        $this->_deleteTmp(1, array($this->_letterType));
    }


    /**
     * 0 = delete all, 1 = all files, 1 + exception = all but files in exception format, 2 = folder (has to be empty!)
     *
     * @param int $mode
     * @param array $exception
     */
    protected function _deleteTmp($mode = 0, $exception = array())
    {
        /*$path = $this->_getTmpDir();
        switch ($mode) {
            case 0:
                $this->_deleteTmp(1);
                $this->_deleteTmp(2);
                break;
            case 1:
                $files = array_diff(scandir($path), array('.', '..'));
                foreach ($files AS $file) {
                    $position = strpos(strrev($file), ".");
                    $format   = substr($file, -$position);
                    if (!in_array($format, $exception)) {
                        if (!unlink($path . $file)) {
                            //Exception
                            echo "Error deleting file: $path$file";
                        }
                    }
                }
                break;
            case 2:
                if (is_dir($path)) {
                    $this->_deleteTmp(1);
                    if (rmdir($path) === false) {
                        //Exception
                        echo "Error deleting folder: $path";
                    }
                }
                break;
        }*/
    }


    /**
     * Starts the file download and deletes temporary file
     *
     * @access public
     * @param $file
     */
    public function downloadLetter($file = null)
    {
        $file = $file !== null ? $this->_getTmpDir() . $file . '.' . $this->_letterType : $this->_outputFile;
        $file_content = file_get_contents($file);
        header ('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
        header ('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
        header ('Pragma: public');
        header ('Cache-Control: must-revalidate, post-check=0,pre-check=0');
        if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE')) {
            header('Content-Type: application/force-download');
        } else {
            header('Content-Type: application/octet-stream');
        }
        header('Content-Length: ' . strlen($file_content));
        header('Content-disposition: attachment; filename=' . preg_replace('~^[^_]*_~', '', basename($file)));
        echo $file_content;
        $this->_deleteTmp(2);
    }


    /**
     * Returns the outputfile
     *
     * @access public
     * @return string $this->_outputFile
     */
    public function letter2File()
    {
        return $this->_outputFile;
    }


    /**
     * Returns the name of the outputfile
     *
     * @access public
     * @return string
     */
    public function getFileName()
    {
        return basename($this->_outputFile);
    }


    /**
     * Takes the content from the outputfile and deletes the outputfile
     *
     * @access public
     * @return string $file_content
     */
    public function getRawData()
    {
        $file_content = file_get_contents($this->_outputFile);
        if(is_file($this->_outputFile)) {
            unlink($this->_outputFile);
        }

        return $file_content;
    }
}
?>
