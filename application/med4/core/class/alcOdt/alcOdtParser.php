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

class alcOdtParser extends alcOdtParserFunctions
{
   protected $_type        = '';

   protected $_smarty      = null;

   protected $_db          = null;

   protected $_paths       = array();

   protected $_zipContainment = array();

   protected $_iniSetted   = false;

   protected $_param       = array();

   protected $_config      = array();

   protected $_restriction = array();

   protected $_smartyVariablesAssigned = false;

   protected $_pdfExtensions = array('header', 'footer');

   /**
    * process flag
    *
    * @var unknown_type
    */
   protected $_breakParse = false;


   /**
    * only files in this array will be generated
    *
    * @var unknown_type
    */
   protected $_convertRestriction   = array();


   /**
    * ... too much special cases...
    */
   protected $_xmlOptimizeRestriction = array(
      'removeEmtpyTableRows' => array()
   );


    /**
     * constructor
     *
     * @param $db
     * @param Smarty    $smarty
     */
    public function __construct($db, $smarty)
    {
        parent::__construct();

        $this->_db     = $db;
        $this->_smarty = $smarty;

        //Parser Container
        $this->_delimiter['containerLeft']  = chr(134) . chr(135);
        $this->_delimiter['containerRight'] = chr(135) . chr(134);
    }


   public static function create($db, $smarty)
   {
      return new self($db, $smarty);
   }


   /**
    * parse the odt
    *
    */
   public function parse()
   {
      $this->unzipTemplate();

      if ($this->_breakParse === false) {
         $this
            ->parsePhp()
            ->parseOdt()
            ->parseHl7()
            ->_convertExtension()
            ->_convertSettings()
         ;
      }

      return $this;
   }


    /**
     * evalExtensionLine
     *
     * @access  private
     * @param   string  $line
     * @return  string
     */
    private function evalExtensionLine($line)
    {
        $return = null;
        $toEval = '$return = ' . end($line) . ';';

        eval($toEval);

        return str_replace("'", "\'", $return);
    }


   /**
    * Converts settings file
    */
   protected function _convertSettings()
   {
      if (in_array('settings.php', $this->_zipContainment) === true) {
         $settingsFilePath = $this->_paths['dir']['zipExtract'] . "settings.php";

         $phpContent = file($settingsFilePath);

         foreach ($phpContent as $i => $line) {
            $phpContent[$i] = preg_replace_callback(
               "~{{(.+?)}}~s",
               array( &$this, 'evalExtensionLine'),
               (string) $line);
         }

         $string = implode("", $phpContent);

         file_put_contents($settingsFilePath, $string);

         $this->_paths['file']['settings']= array(
            'fileName'  => "settings.php",
            'dir'       => $this->_paths['dir']['zipExtract'],
            'src'       => $settingsFilePath,
         );
      }

      return $this;
   }



   /**
    * Converts the Extension fill for later calling if exists blabla b
    *
    */
   protected function _convertExtension()
   {
      foreach ($this->_pdfExtensions as $extension) {
         if (array_key_exists($extension, $this->_zipContainment) === true) {

            $extensionContent = $this->_zipContainment[$extension];

            if (in_array("{$extension}.php", $extensionContent) === true) {
               //register for latter calling due converting odt to xml

               $extensionFilePath = $this->_paths['dir']['zipExtract'] . "{$extension}/{$extension}.php";

               $phpContent = file($extensionFilePath);

               foreach ($phpContent as $i => $line) {
                  $phpContent[$i] = preg_replace_callback(
                     "~{{(.+?)}}~s",
                     array( &$this, 'evalExtensionLine'),
                     (string) $line);
               }

               $string = implode("", $phpContent);

               file_put_contents($extensionFilePath, $string);

               $this->_paths['file'][$extension]= array(
                  'fileName'  => "{$extension}.php",
                  'dir'       => $this->_paths['dir']['zipExtract'] . "{$extension}/",
                  'src'       => $extensionFilePath,
               );
            }
         }
      }

      return $this;
   }


   /**
    * This function assigns the smarty variables of this class
    * It's an single function for better assign handling
    *
    * @param $smarty object
    */
   protected function assignSmartyVariables()
   {
      if ($this->_smartyVariablesAssigned === false) {
         $this->_odtSmarty->assign('config', $this->_to_utf8($this->_config));

         foreach ($this->_to_utf8($this->_assign) as $key => $value){
            $this->_odtSmarty->assign($key, $value);
         }

         //DEBUG
         // print_arr($this->_odtSmarty->get_template_vars());
         $this->_smartyVariablesAssigned = true;
      }

      return $this;
   }


   /**
    * replaces tags with xml style tags
    *
    * @access  protected
    * @param   string   $content
    * @return  string
    */
   protected function _applyStyle($content)
   {
      $styles = alcCss::getStyles();
      foreach ($styles as $tag => $style) {
          $pattern   = "~\[$tag](.*?)\[\/$tag]~";
          $styleName = substr($style, 1, strpos($style, ' ') - 1);

          preg_match_all($pattern, $content, $matches);

          foreach (array_unique($matches[1]) as $match) {
              // [[nospace]] need for workaround in xhtmlManager to remove unwanted whitespace2
              $replace = '[[nospace]]<text:span text:style-name="' . $styleName . '">' . $match . '</text:span>';
              $content = str_replace("[{$tag}]{$match}[/{$tag}]", $replace, $content);
          }
      }

      return $content;
   }

   /**
    * Optimize the xml
    *
    *
    * @param $content
    */
   protected function _optimizeXml($fileName, $content)
   {
      $content = preg_replace('/<table:table table:name="[\w]+" table/', '<table:table table:name="" table', $content);
      $content = preg_replace('/table:style-name="[\w]+">/', 'table:style-name="">', $content);

      //Replace wrap with [[break]]
      $reg = '~' . $this->_delimiter['containerLeft']. '[^' . $this->_delimiter['containerRight'] . ']*' . $this->_delimiter['containerRight'] . '~';
      preg_match_all($reg, $content, $matches);

      $blocks = $matches[0];
      $order  = array("\r\n", "\n", "\r");

      foreach ($blocks as $block) {
         $replaceBlock  = str_replace($order, '[[break]]', $block);
         $content       = str_replace($block, $replaceBlock, $content);
      }

      //This will replace [[break]] with P styles
      preg_match_all('~<text:p text:style-name="([^"]*)">[^<]*\[\[break\]\][^<]*</text:p>~', $content, $matches);

      $tags    = $matches[0];
      $classes = $matches[1];

      foreach ($tags as $k => $tag) {
         $class      = $classes[$k];
         $replaceTag = str_replace($this->_odtLineBreak, '</text:p><text:p text:style-name="' . $class . '">', $tag);
         $content    = str_replace($tag, $replaceTag, $content);
      }

      //This will replace [[break]] with P -> span styles
      preg_match_all('~<text:p text:style-name="([^"]*)"><text:span text:style-name="([^"]*)">[^<]*\[\[break\]\][^<]*</text:span></text:p>~', $content, $spanMatches);

      $tags    = $spanMatches[0];
      $pStyles = $spanMatches[1];
      $tStyles = $spanMatches[2];

      foreach ($tags as $k => $tag) {
         $pStyle = $pStyles[$k];
         $tStyle = $tStyles[$k];

         $replaceTag = str_replace($this->_odtLineBreak, '</text:span></text:p><text:p text:style-name="' . $pStyle . '"><text:span text:style-name="' . $tStyle . '">', $tag);
         $content    = str_replace($tag, $replaceTag, $content);
      }

      if (in_array($fileName, $this->_xmlOptimizeRestriction['removeEmtpyTableRows']) === false) {
         $content = $this->_removeEmptyTableRows($content);
      }

      return $content;
   }


   /**
    * Set filename to optimize restrictionm list
    * this file will not be optimized
    *
    * @param $fileName
    * @param $optimize
    */
   public function setXmlOptimizeRestriction($fileName, $optimize)
   {
      $this->_xmlOptimizeRestriction[$optimize][] = $fileName;

      return $this;
   }



   /**
    * Parse the given xml
    *
    */
   private function parseXml($fileName, $file)
   {
      $content = file_get_contents($file);

      $content = $this->_checkAlcFunctions($content);

      file_put_contents($file, $content);

      try {
         if ($this->_odtSmarty === null) {
            $odtSmarty = new Smarty;
            //$odtSmarty->error_reporting = E_ALL & ~E_NOTICE & ~'E_WARN';
            $odtSmarty->caching = false;
            $odtSmarty->force_compile  = true;
            $odtSmarty->left_delimiter = $this->_delimiter['left'];
            $odtSmarty->right_delimiter = $this->_delimiter['right'];
            $odtSmarty->compile_dir = 'templates/cache';

            $this->_odtSmarty = $odtSmarty;
         }

         //Assign smarty variables
         $this->assignSmartyVariables();

         $content = $this->_odtSmarty->fetch($file);
         $content = $this->_optimizeXml($fileName, $content);
         $content = $this->_applyStyle($content);

         //remove delimiter for latter content check
         $content = preg_replace('/'. $this->_delimiter['containerLeft'] . '/', '',  $content);
         $content = preg_replace('/'. $this->_delimiter['containerRight'] . '/', '', $content);
      } catch (Exception $e) {
         echo $e->getMessage();
         return $e->getMessage();
      }

      return $content;
   }


   /**
    * this function is very crzy...
    * Desc: only converts/parse the given file when section has data
    *
    */
   public function setRestriction($file, $section)
   {
      $this->_restriction[$file] = $section;

      return $this;
   }


   /**
    * Extracts the content.xml from ODT, parse it, and convert back to odt
    *
    */
   private function parseOdt()
   {
      foreach ($this->_zipContainment as $index => $file) {

         if (is_array($file) === true) {
            continue;
         }

         $fileInformation  = explode('.', $file);

         if (count($fileInformation) != 2) {
            continue;
         }

         $fileName = reset($fileInformation);
         $fileType = end($fileInformation);

         if ($fileType === 'odt') {

            //Restriction check
            if (array_key_exists($fileName, $this->_restriction) === true) {

               $parseDataCheck = $this->_restriction[$fileName];

               if (isset($this->_parseData[$parseDataCheck]) === false || count($this->_parseData[$parseDataCheck]) === 0) {
                  continue;
               }
            }

            //if odt file then register for latter xhtml calling due converting odt to xml
            $this->_paths['file']['xhtml'][] = array(
                'file'     => $this->_paths['dir']['xhtml'] . $fileName . '/' . $fileName . '.html',
                'fileName' => $fileName . '.html',
                'dir'      => $this->_paths['dir']['xhtml'] . $fileName . '/',
                'file'     => $fileName,
                'src'      => $this->_paths['dir']['zipExtract'] . $fileName . '.odt',
                'srcName'  =>  $fileName . '.odt'
            );

            if ($this->_iniSetted === false) {
               ini_set('pcre.backtrack_limit', -1);
               $this->_iniSetted = true;
            }

            $extractedXMLFile = $this->_paths['dir']['zipExtract'] . 'content.xml';
            $archive          = new PclZip($this->_paths['dir']['zipExtract'] . $file);

            if (is_file($extractedXMLFile) == true) {
               unlink ($extractedXMLFile);
            }

            $extract = $archive->extract(
               PCLZIP_OPT_BY_NAME, 'content.xml',
               PCLZIP_OPT_PATH, $this->_paths['dir']['zipExtract']
            );

            if ($extract == 0) {
               die("Error : ".$archive->errorInfo(true));
            } else {
               //convert content.xml and write back to odt
               $content = $this->parseXml($fileName, $extractedXMLFile);

                file_put_contents($extractedXMLFile, $content);

               $archive = new PclZip($this->_paths['dir']['zipExtract'] . $file);

               $delContentXml = $archive->delete(PCLZIP_OPT_BY_NAME, 'content.xml');
               if ($delContentXml == 0) {
                  die("Error : ".$archive->errorInfo(true));
               }

               $addContentXML = $archive->add($extractedXMLFile, PCLZIP_OPT_REMOVE_ALL_PATH);
               if ($addContentXML == 0) {
                  die("Error : ".$archive->errorInfo(true));
               }
            }
         }
      }

      return $this;
   }


   /**
    * parse the zip php
    *
    */
   private function parsePhp()
   {
      if (in_array('config.conf', $this->_zipContainment) == true) {

         $backup = $this->_smarty->get_config_vars();

         $this->_smarty->clear_config();
         $this->_smarty->config_load($this->_paths['dir']['zipExtract'] . 'config.conf');

         $smartyConfigVars = $this->_smarty->get_config_vars();

         $config = array();

         foreach ($smartyConfigVars as $key => $value) {
            $config[$key] = $value;
         }

         $this->_config = $config;

         $this->_smarty->set_config($backup);
      }

      if (in_array('parse.php', $this->_zipContainment) == true) {
         include($this->_paths['dir']['zipExtract'] . 'parse.php');
      }

      return $this;
   }


   private function parseHl7()
   {
       if (array_key_exists('hl7', $this->_zipContainment) === true) {
           $elements = $this->_zipContainment['hl7'];

           foreach ($elements as $element) {
               $filePath = $this->_paths['dir']['zipExtract'] . "hl7/{$element}";

               file_put_contents($filePath, $this->_odtSmarty->fetch($filePath));

               $this->_paths['file']['hl7'][] = array(
                  'fileName'  => $element,
                  'dir'       => $this->_paths['dir']['zipExtract'] . "hl7/",
                  'src'       => $filePath,
               );
           }
       }

       return $this;
   }


   /**
    * Unzips the Template File and put it in the xhtml dir of the report
    *
    */
   private function unzipTemplate()
   {
      if (isset($this->_paths['file']['zip']) === false) {
         //Old protocoll or zip not found
         $this->_breakParse = true;
      } else {
         require_once(DIR_LIB . '/zip/pclzip.lib.php' );

         //Erstelle einen Ordner mit unique Name um das Template (.zip File) dorthin zu schieben
         $uniqueTmpFolderName = uniqid('', true) . '/';
         $zipTmpFolder  = $this->_paths['dir']['tmp'] . $uniqueTmpFolderName;
         $zipTmpZipFile = $zipTmpFolder . $this->_paths['file']['zip']['name'];

         //Erstelle TMP Dir
         mkdir ($zipTmpFolder, 0777, true);

         //Kopiere Template(.zip) nach TMP
         copy($this->_paths['file']['zip']['file'], $zipTmpZipFile);

         //Extrahiere zip Inhalt
         $archive = new PclZip($zipTmpZipFile);

         $extractDir = $zipTmpFolder . 'zip/';
         $extract    = $archive->extract(PCLZIP_OPT_PATH, $extractDir);

         if ($extract == 0) {
            die("Error : ".$archive->errorInfo(true));
         }

         $zipExtractDir = $this->_paths['dir']['xhtml'] . 'zip/';

         $this->_paths['dir']['zipExtract'] = $zipExtractDir;

         //Ordner komplett löschen
         deltree($zipExtractDir);

         moveDir($extractDir, $zipExtractDir);

         $this->_zipContainment = getDirContent($zipExtractDir);

         //Lösche den Tmp Folder wieder
         deltree($zipTmpFolder, true);
      }

      return $this;
   }


   /**
    * register files for later calling
    *
    * @param unknown_type $type
    * @param unknown_type $path
    * @param unknown_type $name
    */
   public function registerFile($type, $path, $name)
   {
      //security call
      if (count($this->_paths) == 0) {
         $this->createPaths();
      }

      if (array_key_exists($path, $this->_paths['dir']) == true) {
         $possibleFileLocation = $this->_paths['dir'][$path] . $name;

         if (is_file($possibleFileLocation) == true) {
            $this->_paths['file'][$type] = array('file' => $possibleFileLocation, 'name' => $name);
         } else {
            //DEBUG
            //echo 'file doesn't exists';
            //exit;
         }
      } else {
         //DEBUG
         //echo 'path type doesn't exists';
         //exit;
      }

      return $this;
   }


   /**
    * Creates the path for the Converter
    *
    */
   public function createPaths($deltree = true)
   {
      $upload        = getUploadDir($this->_smarty, 'upload', false);
      $documentDir   = $upload['upload'] . $upload['config']['document_dir'];
      $fileDir       = $this->_type . '_' . $this->getParam('id') . '/';

      $this->_paths['dir']  = array(
         'tmp'    => getUploadDir($this->_smarty, 'tmp'),
         'tpl'    => $documentDir . $upload['config']['tpl_dir'],
         'xhtml'  => $documentDir . $upload['config']['xhtml_dir'] . $fileDir ,
         'doc'    => $documentDir . $upload['config']['document_dir'] . $fileDir
      );

      //Empty dirs
      if ($deltree === true && count($this->_convertRestriction) == 0) {
         deltree($this->_paths['dir']['doc']);
         deltree($this->_paths['dir']['xhtml']);
      }

      foreach ($this->_paths['dir'] as $dir) {
         if (is_dir($dir) === false) {
            mkdir($dir, 0777, true);
         }
      }

      return $this;
   }

   /**
    *
    * reset
    */
   protected function _resetOdtParser()
   {
      $this->_paths                    = array();
      $this->_config                   = array();
      $this->_zipContainment           = array();
      $this->_restriction              = array();
      $this->_breakParse               = false;
      $this->_parseData                = array();
      $this->_smartyVariablesAssigned  = false;
      $this->_assign                   = array();

      if ($this->_odtSmarty !== NULL) {
        $this->_odtSmarty->clear_config();
        $this->_odtSmarty->clear_all_assign();
     }

      $this->_xmlOptimizeRestriction = array(
         'removeEmtpyTableRows' => array()
      );

      return $this;
   }




   /**
    * Sets the type of the parser
    *
    *
    * @param unknown_type $type
    */
   public function setType($type)
   {
      if (strlen($type) > 0) {
         $this->_type = strtolower($type);
      }

      return $this;
   }


   /**
    * Set a new delimiter for smarty variables
    *
    * @param   $side       string
    * @param   $delimiter  string
    */
   public function setdelimiter($side = null, $delimiter = '')
   {
      if ($side !== null && array_key_exists($side, $this->_delimiter) == true) {
         $this->_delimiter[$side] = $delimiter;
      }

      return $this;
   }

   /**
    * Set Param for the converter
    * @return  alcXhtmlToPdf
    */
   public function setParam($name = '', $value = '') {
      if (strlen($name) > 0 && strlen($value) > 0) {
         $this->_param[$name] = $value;
      }

      return $this;
   }

   /**
    *
    * returns param
    * @param unknown_type $name
    */
   public function getParam($name) {
      return isset($this->_param[$name]) == true ? $this->_param[$name] : false;
   }


   /**
    * set file for convertRestriction
    *
    * @param $file
    */
   public function convertRestriction($file)
   {
      $this->_convertRestriction[] = $file;

      return $this;
   }



}

?>
