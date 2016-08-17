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

class concatPdf extends fpdi
{
   private $pdfSrcPath     = null;

   private $pdfOutputPath  = null;

   private $container      = array();


   public function __construct() {
      parent::__construct();
   }

   public static function create() {
      return new self();
   }

   public function output($type = 'D', $fileNname = null)
   {
      if (count($this->container) > 0 && $this->pdfOutputPath !== null) {
         $timestamp  = time();

         $outputfileName   = $fileNname === null ? uniqid() . '.pdf' : $fileNname;
         $outputFile       = $this->pdfOutputPath . $outputfileName;

         $pdf  = new FPDI();

         $pdf->setPrintHeader(false);
         $pdf->SetDisplayMode('fullpage');

         foreach ($this->container as $filePath) {
            $pageCount = $pdf->setSourceFile($filePath);

            for ($i = 1; $i <= $pageCount; $i++) {
               $tplidx = $pdf->importPage($i, '/MediaBox');
               $pdf->addPage();
               $pdf->useTemplate($tplidx);
            }
         }

         switch ($type) {
            case 'D':
               $pdf->Output($outputfileName, $type);
               exit;

               break;

            case 'F':
               $pdf->Output($outputFile, $type);

               break;
         }
      }

      return $this;
   }


   public function addPdf($fileName, $filePath = null, $index = null)
   {
      $pdfFile = $filePath === null ? $this->pdfSrcPath . $fileName : $filePath . $fileName;

      if (is_file($pdfFile) == true) {

         if ($index === null) {
            $this->container[] = $pdfFile;
         } else {

            if (array_key_exists($index, $this->container) === true) {

               $pre  = array_slice($this->container, 0, $index);
               $post = array_slice($this->container, $index);

               $this->container = array_merge_recursive($pre, array($pdfFile), $post);
            } else {
               $this->container[$index] = $pdfFile;
            }
         }
      }

      return $this;
   }


   public function setPdfSrcPath($path = null) {

      if ($path !== null) {

         if (is_dir($path) === false) {
            mkdir($path,0777, true);
         }

         $this->pdfSrcPath = $path;
      }

      return $this;
   }

   public function setPdfOutputPath($path =  null) {

      if ($path !== null) {

         if (is_dir($path) === false) {
            mkdir($path,0777, true);
         }

         $this->pdfOutputPath = $path;
      }

      return $this;
   }


}

?>
