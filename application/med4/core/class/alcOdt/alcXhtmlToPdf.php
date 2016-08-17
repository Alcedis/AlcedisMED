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

class alcXhtmlToPdf extends alcOdtConverter
{

   protected $_header            = false;

   protected $_footer            = false;

   protected $_headerRessources  = array();

   protected $_footerRessources  = array();

   protected $_content           = null;

   protected $_ext               = array();

   protected $_xhtmlRessources   = array();

   protected $_cssRessources     = array();

   protected $_pdfName           = null;

   protected $_status            = null;



   /**
    *
    * Enter description here ...
    * @var alcPdf
    */
   protected $_pdf               = null;


   public static function create($db, $smarty)
   {
      return new self($db, $smarty);
   }

   /**
    * creates a pdf file from xhtml ressources
    *
    */
   public function createPdf()
   {
      if (count($this->_paths) == 0) {
         $this->createPaths(false);
      }

      $this->_pdf = new alcPdf(PDF_PAGE_ORIENTATION, 'pt', PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $this
         ->_loadXhtmlRessources()
         ->_loadPdfExtension(array('header', 'footer'))
         ->_loadSettings()
         ->_create()
      ;

      require_once 'feature/hl7/class/export/export.php';

      hl7Export::call($this->_db, $this->_smarty, $this->_type, $this->getParam('id'));

      return $this;
   }



   /**
    * finds all convertible xhtml files in directory
    *
    */
   protected function _loadXhtmlRessources()
   {
      $path = isset($this->_paths['dir']['xhtml']) === true ? $this->_paths['dir']['xhtml'] : null;

      $this->_paths['file']['xhtml'] = array();

      //Check if path to xhtml dir exists
      if ($path !== null && is_dir($path) === true) {
         $folder = getDirContent($path);

         foreach ($folder as $folderName => $folderContent) {
            $folderPath    = $path . $folderName . '/';
            $folderContent = getDirContent($folderPath);

            foreach ($folderContent as $file) {
               $filePath = $folderPath . $file;

               if (is_file($filePath) == true) {
                  $fileInformation = explode('.', $file);
                  $type = null;

                  switch (end($fileInformation)) {
                     case 'html': $type = 'xhtml'; break;
                     case 'css' : $type = 'css';   break;
                  }

                  if ($type !== null) {
                     $tmp= array(
                        'type' => $folderName,
                        'path' => $filePath,
                        'name' => $file,
                     );

                     $this->_paths['file'][$type][] = $tmp;
                  }
               }
            }
         }
      }

      //load Xhtml Ressources
      if (array_key_exists('xhtml', $this->_paths['file']) === true) {
         foreach ($this->_paths['file']['xhtml'] as $xhtml) {
            $this->_xhtmlRessources[$xhtml['type']]['xhtml'] = file_get_contents($xhtml['path']);
         }
      }

      //load Css Ressources
      if (array_key_exists('css', $this->_paths['file']) === true) {
         foreach ($this->_paths['file']['css'] as $css) {
            $this->_xhtmlRessources[$css['type']]['css'] = '<style>' . file_get_contents($css['path']) . '</style>';
         }
      }

      return $this;
   }


    /**
     * _loadSettings
     *
     * @access  protected
     * @return  alcXhtmlToPdf
     */
   protected function _loadSettings()
   {
      $settingsPath = $this->_paths['dir']['xhtml'] . 'settings.php';

      if (is_file($settingsPath) === true) {
         $this->_paths['file']['settings'] = array(
            'path' => $this->_paths['dir']['xhtml'],
            'file' => $settingsPath
         );
      }

      return $this;
   }


    /**
     * _loadPdfExtension
     *
     * @access  protected
     * @param   array
     * @return  alcXhtmlToPdf
     */
   protected function _loadPdfExtension($extensions)
   {
      foreach ($extensions as $extension) {
         $extensionPath = $this->_paths['dir']['xhtml'] . "{$extension}/";

         if (is_dir($extensionPath) === true) {
            $this->{"_{$extension}"}          = true;
            $this->{"_{$extension}Ressources"}  = getDirContent($extensionPath);

            $this->_paths['file'][$extension] = array(
               'path' => $extensionPath,
               'file' => $extensionPath . "{$extension}.php"
            );
         }
      }

      return $this;
   }



   /**
    * render ressource
    */
   protected function _renderXhtml($ressourceName)
   {
      $css     = isset($this->_xhtmlRessources[$ressourceName]['css']) === true   ? $this->_xhtmlRessources[$ressourceName]['css']     : '';
      $xhtml   = isset($this->_xhtmlRessources[$ressourceName]['xhtml']) === true ? $this->_xhtmlRessources[$ressourceName]['xhtml']   : '';
      $merged  = $css . $xhtml;

      //Convert Images
      $xhtml   = $this->_convertXhtmlImagesPaths($ressourceName, $merged);

      //Convert Content
      $xhtml   = $this->_convertXhtmlRessource($xhtml);

      return $xhtml;
   }


   /**
    * converts ressource to pdf compatible html
    *
    */
   protected function _convertXhtmlRessource($xhtml)
   {
      //Convert relevant content
      $xhtml = str_ireplace(array(chr(135), '<p', '</p>', '<br/>'), array('', '<tblcontent', chr(135) . '</tblcontent>', ''), $xhtml);

       //$xhtml = str_replace('><', '>' . chr(13) . '<', $xhtml);

      //Add Cellpadding
      $xhtml = str_ireplace(array('<table'), array('<table cellpadding="3"'), $xhtml);

      //Escape
      preg_match_all('~(<tblcontent[^>]*>[^' . chr(135) . ']*' . chr(135) . '</tblcontent>)+~', $xhtml, $matches, PREG_OFFSET_CAPTURE);
      $tableContents = array_reverse(reset($matches));

      foreach ($tableContents as $tableContent) {
         $position               = end ($tableContent);
         $contentText            = reset($tableContent);
         $contentLength          = strlen($contentText);
         $contentTextWithoutCHR  = str_replace(chr(135), '', $contentText);
         $contentArray           = explode('</tblcontent>', $contentTextWithoutCHR);
         $inject                 = '<table>';
         array_pop($contentArray);

         foreach ($contentArray as $tc) {
            $inject .= '<tr><td>' . str_replace('tblcontent', 'span', $tc) . '</span></td></tr>';
         }

         $inject .= '</table>';
         $xhtml   = substr($xhtml, 0, $position) . $inject . substr($xhtml, $position + $contentLength);
      }

      $xhtml = str_replace('><', '>' . chr(13) . '<', $xhtml);

      return $xhtml;
   }

   /**
    * set name of header html
    *
    * @param $name
    */
   public function setContent($name)
   {
      $this->_content = $name;

      return $this;
   }


   /**
    * set extension html
    *
    * @param $name
    */
   public function setExtension($name)
   {
      if (in_array($name, $this->_ext) === false) {
         $this->_ext[] = $name;
      }

      return $this;
   }



   /**
    * create pdf file
    *
    *
    */
   protected function _create()
   {
//      require_once(DIR_LIB . '/tcpdf/config/lang/ger.php');
      require_once(DIR_LIB . '/tcpdf/tcpdf.php');

      //implement header if active
      if ($this->_header === true) {
         $this->_pdf->setAlcedisHeader($this->_paths['file']['header']['path']);
      }

      //implement footer if active
      if ($this->_footer === true) {
         $this->_pdf->setAlcedisFooter($this->_paths['file']['footer']['path']);
      }

      //DEFAULT SETTINGS
      $tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)));
      $this->_pdf->setHtmlVSpace($tagvs);

      $this->_pdf->SetCellPadding(0);

      $this->_pdf->setCellHeightRatio(2);
      $this->_pdf->setCellHeightRatio(1.4);

      $this->_pdf->SetPrintHeader(true);
      $this->_pdf->SetPrintFooter(true);

      $this->_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $this->_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

      $this->_pdf->SetFont('helvetica', '', 10);

      //set auto page breaks
      $this->_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

      //set image scale factor
      $this->_pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

      $this->_pdf->setPDFVersion('1.3');

      //CUSTOM SETTINGS
      if (array_key_exists('settings', $this->_paths['file']) === true) {
         include $this->_paths['file']['settings']['file'];
      }

      // add a page
      $this->_pdf->AddPage();

      //content
      if ($this->_content !== null && array_key_exists($this->_content, $this->_xhtmlRessources) === true) {
         $this->_pdf->writeHTML($this->_renderXhtml($this->_content), false, 0, false, false);
      }

      //extensions
      if (count($this->_ext) > 0) {
         foreach ($this->_ext as $extension) {
            if (array_key_exists($extension, $this->_xhtmlRessources) === true) {
               $this->_pdf->AddPage();

               $this->_pdf->writeHTML($this->_renderXhtml($extension), false, 0, false, false);
            }
         }
      }

      // reset pointer to the last page
      $this->_pdf->lastPage();

      $outputPath = $this->_paths['dir']['doc'] . $this->getPdfName();
      $this->_pdf->Output($outputPath, 'F');

      $this->_setStatus('ok');

      return $this;
   }

   /**
    * set pdf name
    *
    */
   public function setPdfName($name)
   {
      if (strlen($name) > 0) {
         $this->_pdfName = $name;
      }

      return $this;
   }


   /**
    * returns pdf name
    *
    */
   public function getPdfName()
   {
      $name = $this->_pdfName !== null ? $this->_pdfName : 'default';

      return $name . '.pdf';
   }

   /**
    * Adds complete directory path to images
    *
    * @param unknown_type $xhtml
    */
   protected function _convertXhtmlImagesPaths($name, $xhtml)
   {
      $imgMatch      = '/<img[^>]+>/i';
      $ImgSrcMatch   = "/\< *[img][^\>]*[src] *= *[\"\']{0,1}([^\"\'\ >]*)/i";

      preg_match_all($imgMatch, $xhtml, $imageTags);

      $imageTags = reset($imageTags);

      foreach ($imageTags as $image) {
         preg_match_all($ImgSrcMatch, $image, $imageSrcTag);

         $imageSrcTag = end($imageSrcTag);

         foreach ($imageSrcTag as $srcTag) {
            $replace = $this->_paths['dir']['xhtml'] . $name . '/' . $srcTag;
            $xhtml = str_ireplace($srcTag, $replace, $xhtml);
         }
      }

      return $xhtml;
   }


   /**
    * set status of the pdf conversion
    *
    */
   protected function _setStatus($status)
   {
      $this->_status = $status;

      return $this;
   }


   /**
    * returns the status of the pdf conversion
    *
    */
   public function getStatus()
   {
      return $this->_status;
   }
}

?>
