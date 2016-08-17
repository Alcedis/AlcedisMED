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

class report
{
   protected $_db          = null;

   protected $_smarty      = null;

   protected $_type        = null;

   protected $_name        = null;

   protected $_subDir      = null;

   protected $_user        = null;

   /**
    *
    * Enter description here ...
    * @var reportExtension
    */
   protected $_report      = null;

   protected $_params      = null;

   protected $_letter      = false;

   protected $_renderer    = null;

   protected $_fileName    = null;

   protected $_packageDir  = null;


   public function __construct($db, $smarty, $params)
   {
      $this->_db     = $db;
      $this->_smarty = $smarty;
      $this->_params = $params;
   }

   public function setParam($param, $value)
   {
       $this->_params[$param] = $value;

       return $this;
   }

   public static function create($db, $smarty, $params)
   {
      return new self($db, $smarty, $params);
   }

    private function _buildReportPath($extension = false)
    {
        $path = null;

        $subdir = $this->_subDir !== null ? $this->_subDir . '/' : '';

        // means that a custom report must be loaded
        if ($this->_packageDir !== null) {
            $packageId = $this->_params['id'];

            if ($extension === false) {
                $path = "{$this->_packageDir}/{$packageId}/report.php";
            } else {
                $path = null;
            }
        } else {
            // normal report of system
            if ($extension === false) {
                $type = $this->_letter === true ? 'letter' : $this->_type;
                $name = $this->_name;

                $path = "reports/{$type}/{$subdir}{$name}.php";
            } else {
                $path = "reports/scripts/{$subdir}/reportExtension.php";
            }
        }

        return $path;
    }


    public function load()
    {
        $starttime  = explode(' ', microtime());
        $starttime  = reset($starttime) + end($starttime);

        $reportContent    = $this->_buildReportPath();
        $reportExtension  = $this->_buildReportPath(true);

        require_once 'reports/scripts/reportMath.php';

        if (is_file($reportExtension) === true) {
           require_once ($reportExtension);
        }

        require_once ($reportContent);

        $this->_renderer = $this->_initRenderer();

        if ($this->_renderer !== null) {

            $class = 'reportContent' . ucfirst($this->_name);

            $this->_report = new $class($this->_renderer, $this->_db, $this->_smarty, $this->_subDir, $this->_type, $this->_params);

            if (method_exists($this->_report, 'init') === true) {
                $this->_report->init($this->_renderer);
            }

            if (method_exists($this->_report, 'header') === true) {
                $this->_report->header($this->_renderer);
            } else {
                $this->_header();
            }

            if (method_exists($this->_report, 'generate') === true) {
                $this->_report->generate($this->_renderer);
            }

            // process only if no custom report
            if ($this->_packageDir === null) {
                //Berechnung der Skriptlaufzeit zur Anzeige im Report
                $endtime = explode(' ', microtime());
                $endtime = reset($endtime) + end($endtime);

                $time = round(($endtime - $starttime), 2);

                require_once ('core/class/report/time.php');

                $params = $this->_params;
                $params['report'] = $this->_name;

                reportTime::create($this->_db)
                    ->setParams($params)
                    ->setTime($time)
                    ->write()
                ;
            }
        }

      return $this;
   }


   /**
    *
    * @return type
    */
   public function getData()
   {
       return ($this->_report !== null ? $this->_report->getData() : array());
   }

  /**
    * sets package file path for custom report package
    *
    * @param type $path
    * @return report
    */
   public function setPackageDir($path)
   {
       if (is_dir($path) === true) {
           $this->_packageDir = $path;
       }

       return $this;
   }

   protected function _header()
   {
      $config = $this->_report->loadConfigs($this->_name, false, true);

      $reportHead = isset($config['head_report_pdf']) === true
         ? $config['head_report_pdf']
         : (isset($config['head_report']) === true
            ? $config['head_report']
            : null
      );

      if ($reportHead !== null) {

         //Filter abfragen
         $filter = null;
         if (isset($config['filter']) === true) {
            $filter = explode(',', $config['filter']);
         }

         switch ($this->_type) {
            case 'pdf':

               $this
                  ->_renderer
                  ->text
                  ->write($reportHead, 'h3', 25)
               ;

               if ($filter !== null) {

                  $textArray = array();

                  foreach ($filter as $filterVar) {

                     $filterText = '';

                     switch ($filterVar) {
                        case 'datum':
                           $date1 = strlen($this->_params['datum_von']) > 0 ? concat(array($config['lbl_pdf_date_start'], $this->_params['datum_von']), ' ')  : '';
                           $date2 = strlen($this->_params['datum_bis']) > 0 ? concat(array($config['lbl_pdf_date_end'], $this->_params['datum_bis']), ' ')  : '';

                           $filterText = strlen($date1) > 0 && strlen($date2) > 0
                              ? concat(array($date1, $date2), ' ')
                              : (strlen($date1) > 0
                                 ? $date1
                                 : (strlen($date2) > 0
                                    ? $date2
                                    : $config['lbl_pdf_date_empty']
                                 )
                              )
                           ;

                           break;
                        //TODO weitere ergänzen
                        case 'datumsbezug':

                            $filterText = dlookup($this->_db, 'l_basic', 'bez', "klasse = 'rpt_pz01_bezug' AND code = '{$this->_params['datumsbezug']}'");

                            break;
                     }

                     $textArray[] = concat(array(
                        (isset($config["lbl_filter_{$filterVar}"]) ? $config["lbl_filter_{$filterVar}"] : null),
                        $filterText
                     ), ': ');
                  }

                  $this
                    ->_renderer
                    ->text
                    ->write(concat($textArray, ', '), 'h4', 25)
                 ;
               }

            break;
         }
      }

      return $this;
   }



   protected function _initRenderer() {
      $renderer = null;

      switch ($this->_type) {
         case 'xls' :

            require_once(DIR_LIB . '/alcedis/excel/excelgen_pear.class.php');

            $renderer = new ExcelGenPear;

            break;

         case 'rtf' :

            require_once( DIR_LIB . '/alcedis/letter/letter.php' );

            $renderer = new Letter();

            $this->_smarty->config_load('../configs/settings/server.conf', 'jod');

            break;

         case 'phpexcel':

            require_once(DIR_LIB . '/phpexcel/PHPExcel.php');

            $renderer = PHPExcel_IOFactory::load($this->_getTemplate());

            break;

         case 'pdf':

            require_once('core/class/report/alcReportPdf.php');

            $renderer = alcReportPdf::create($this->_db, $this->_smarty, $this->_user, $this->_params);

            break;
      }

      return $renderer;
   }

   protected function _getTemplate()
   {
        $type = $this->_type;
        $sub  = strlen($this->_subDir) ? $this->_subDir . '/' : '';
        $name = $this->_name;

        return $template = "reports/$type/$sub$name.xls";
   }


   /**
    * live render from file
    *
    */
   public function render() {
      switch ($this->_type) {
         case 'xls' :

            $this->_renderer->SendFile();

            break;

         case 'rtf' :

            $this->_renderer->downloadLetter();

            break;

         case 'phpexcel':

            header('Content-Type: application/vnd.ms-excel');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($this->_renderer, 'Excel5');
            $objWriter->save('php://output');

            break;

         case 'pdf':

            $this->_renderer->output("{$this->_name}.pdf", "I");

            break;
      }

      exit;
   }

   public function getDebug()
   {
      return $this->_report->getDebug();
   }


   /**
    * writes file to storage
    *
    */
   public function saveFile()
   {
      $filePath = null;

      switch ($this->_type) {
         case 'rtf' :

            $filePath = $this->_renderer->letter2File();
            $this->_fileName = $this->_renderer->getFileName();

            break;

         case 'xls' :

            $filePath   = $this->_getUserTmpDir();
            $content    = $this->_renderer->SendFile($this->_name, true);

            $file = fopen($filePath, 'w');
            fwrite($file, $content);
            fclose($file);

            break;

         case 'phpexcel' :

            $filePath  = $this->_getUserTmpDir();
            $objWriter = PHPExcel_IOFactory::createWriter($this->_renderer, 'Excel5');

            $objWriter
               ->save($filePath)
            ;

            break;


         case 'pdf':
            $filePath = str_replace('index.php', null, $_SERVER['SCRIPT_FILENAME']) . $this->_getUserTmpDir();

            $this->_renderer->output($filePath, "F");

            break;

        }

      return $filePath;
   }


   private function _getUserTmpDir()
   {
        $userDir    = $this->_user !== null ? "{$this->_user}/" : '';
        $type       = $this->_type == 'phpexcel' ? 'xls' : $this->_type;
        $tmpDir     = "material/{$userDir}";
        $filename   = uniqid() . "_{$this->_name}.{$type}";

        $filepath   = $tmpDir . $filename;

        $this->_fileName = $filename;

        return $filepath;
   }

   public function getFileName()
   {
      return $this->_fileName;
   }


   public function setType($type)
   {
      $this->_type = $type;

      return $this;
   }


   public function setUser($user = null)
   {
      if ($user !== null) {
         $this->_user = $user;
      }

      return $this;
   }


   public function setSubDir($subdir)
   {
      $this->_subDir = $subdir;

      return $this;
   }


   public function setName($name)
   {
      $this->_name = $name;

      return $this;
   }
}

?>
