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

class alcReportPdfAddonMatrix extends alcReportPdfAbstract implements alcReportPdfInterface
{
   private $_handle = null;

   /**
    *
    * Config Werte für Matren
    * @var unknown_type
    */
   protected $_matrixConf;

   public static $description       = 'matrixGetDescription';

   public static $configDescription = 'matrixConfigDiscription';

   public static $value             = 'matrixGetValue';

   public static $percent           = 'matrixGetPercent';

   public static $count             = 'matrixGetCount';

   public static $lookup            = 'matrixGetLookup';


   /**
    *
    * @see core/class/report/pdf/alcReportPdfInterface::init()
    */
   public function init(FPDI $fpdi)
   {
      $this->_fpdi = $fpdi;

      return $this;
   }

   /**
    *
    *
    * @param $caption
    * @param $params <-contains position, width height etc
    * array(
    *    'w' => ?,
    *    'h' => ?,
    *    'x' => ?,
    *    'y' => ?
    * )
    */
   public function create($caption = null, $fields = array(), $params = array())
   {
      $handle = rand().time().rand();

      $this->_handle = $handle;

      $this->_matrixConf[$handle] = array(
         'caption'   => ($caption !== null ? $this->_config["lbl_{$caption}"] : ''),
         'order'     => null,
         'fields'    => $fields,
         'rowCount'  => array(
            'head'   => ($caption !== null ? 1 : 0),
            'subhead'=> 0,
            'rows'   => 0,
            'count'  => 0
         )
      );

      $this->_calculateMatrixPosition($handle, $params);

      return $this;
   }


   protected function _createColumn($handle, $section, $params)
   {
      $column = array();

      $keywords = array(
         self::$description,
         self::$count,
         self::$percent,
         self::$value,
         self::$lookup,
         self::$configDescription
      );

      if (in_array($section, $keywords) === true && isset($this->_matrixConf[$handle]['fields']['val']) === false) {
         echo 'no default value fields defined!';
         exit;
      }

      switch ($section) {

         case self::$configDescription:

            $data = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data);
            }

            foreach ($data as $value) {
               $column[] = isset($this->_config["lbl_{$value}"]) ? $this->_config["lbl_{$value}"] : '-';
            }

            if (count($data) == 0) {
               $column[] = '-';
            }


            break;


         case self::$lookup:

            if (isset($params['lookup']) === false) {
               echo 'no lookup class defined';
               exit;
            }

            $data = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data => 1);
            }

            foreach (array_keys($data) as $key) {
               $tmpVal = dlookup($this->_db, $params['lookup']['table'], $params['lookup']['field'], "{$params['lookup']['src']} = '{$key}'");
               $column[] = strlen($tmpVal) > 0 ? iconv('Windows-1252', 'UTF-8//TRANSLIT', $tmpVal) : '-';
            }

            if (count($data) == 0) {
               $column[] = '-';
            }

            break;

         case self::$count:

            if (isset($this->_matrixConf[$handle]['fields']['count']) === false) {
               echo 'no count field for count display given';
               exit;
            }

            $count   = $this->_matrixConf[$handle]['fields']['count'];
            $data    = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data => 1);
            }

            foreach (array_keys($data) as $key) {
               $column[] = $count;
            }

            if (count($data) == 0) {
               $column[] = $count;
            }

            break;

         case self::$description:

            $data = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data => 1);
            }

            foreach (array_keys($data) as $key) {
               $column[] = isset($this->_config["lbl_{$key}"]) ? $this->_config["lbl_{$key}"] : '-';
            }

            if (count($data) == 0) {
               $column[] = '-';
            }

            break;

         case self::$value:

            $data = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data);
            }

            foreach ($data as $val) {
               $column[] = $val;
            }

            if (count($data) == 0) {
               $column[] = 0;
            }

            break;

         case self::$percent:

            if (isset($this->_matrixConf[$handle]['fields']['count']) === false) {
               echo 'no count field for percent calculation given';
               exit;
            }

            $count   = $this->_matrixConf[$handle]['fields']['count'];
            $data    = $this->_matrixConf[$handle]['fields']['val'];

            if (is_array($data) === false) {
               $data = array($data);
            }

            foreach ($data as $val) {
               $column[] = $count > 0 ? number_format(($val * 100 / $count), 1, ',', '') . '%' : '-';
            }

            if (count($data) == 0) {
               $column[] = '-';
            }

            break;

         default:

            $lookup = isset($params['lookup']) === true;

            if ($lookup === true && isset($params['lookup']) === false) {
               echo 'no lookup class defined';
               exit;
            }

            $data = $this->_matrixConf[$handle]['fields'][$section];

            if (is_array($data) === false) {
               $data = array($data);
            }

            foreach ($data as $val) {
               if ($lookup === true) {
                  $tmpVal  = dlookup($this->_db, $params['lookup']['table'], $params['lookup']['field'], "{$params['lookup']['src']} = '{$val}'");
                  $value = strlen($tmpVal) > 0 ? iconv('Windows-1252', 'UTF-8//TRANSLIT', $tmpVal) : '-';
               } else {
                  $value   = $val;
               }

               $column[] = $value;
            }

            if (count($data) == 0) {
               $column[] = '-';
            }

         break;
      }

      return $column;
   }


   /**
    *
    *
    * @param unknown_type $data
    * @param unknown_type $caption
    * @param unknown_type $width  PERCENT
    * @param unknown_type $params
    */

   public function addColumn($section, $width, $caption = null, $params = array())
   {
      $handle   = $this->_getHandle();

      $keywords = array(
         self::$description,
         self::$count,
         self::$percent,
         self::$value,
         self::$lookup,
         self::$configDescription
      );

      if (array_key_exists($section, $this->_matrixConf[$handle]['fields']) === false && in_array($section, $keywords) === false) {
         echo "section '{$section}' doesn´t exist in assigned data array";
         exit;
      }

      $matrixColumns = array_key_exists('col', $this->_matrixConf[$handle]) === true ? count($this->_matrixConf[$handle]["col"]) : 0;
      $currentColumn = $matrixColumns + 1;

      $this->_matrixConf[$handle]['col'][$currentColumn]['width'] = $width;

      $x = 0;
      for ($i = 1; $i < $currentColumn; $i++) {
         $x += $this->_matrixConf[$handle]['col'][$i]['width'];
      }

      $this->_matrixConf[$handle]['col'][$currentColumn]['begin_x'] = $x;

      $this->_matrixConf[$handle]['col'][$currentColumn]['bez'] = $caption !== null
         ? $this->_config["lbl_{$caption}"]
         : (array_key_exists('caption', $this->_matrixConf[$handle]['fields'][$section]) === true
            ? $this->_matrixConf[$handle]['fields'][$section]['caption']
            : ''
         )
      ;

      if (strlen($this->_matrixConf[$handle]['col'][$currentColumn]['bez']) > 0) {
         $this->_matrixConf[$handle]['rowCount']['subhead'] = 1;
      }

      $this->_matrixConf[$handle]['col'][$currentColumn]['row'] = $this->_createColumn($handle, $section, $params);

      $c = count($this->_matrixConf[$handle]['col'][$currentColumn]['row']);

      $this->_matrixConf[$handle]['rowCount']['rows'] = $c > $this->_matrixConf[$handle]['rowCount']['rows'] ? $c : $this->_matrixConf[$handle]['rowCount']['rows'];

      if (array_key_exists('order', $params) === true) {
          $this->_matrixConf[$handle]['order'][$currentColumn] = $params['order'];
      }

      //Auf Params abfragen
      if (is_array($params) === true && count($params) > 0) {
         $this->_matrixConf[$handle]['col'][$currentColumn]["params"]["align"] = array_key_exists('align', $params) === true ? $params['align'] : 'L';
      }

      return $this;
   }


   private function _drawHeadline($handle, $ghost = false)
   {
      $this->_matrixConf[$handle]["pos"]["y_tmp"] = $this->_matrixConf[$handle]["pos"]["y"];

      $caption = strlen($this->_matrixConf[$handle]["caption"]) > 0 ? 1 : 0;
      $subhead = $this->_matrixConf[$handle]['rowCount']['subhead'] == 1 ? 1 : 0;

      $this->_checkPageBreak($handle, $caption + $subhead);

      if ($caption === 1) {

          /*
         $fillColor = $ghost === false ? array(76, 109, 184)    : array(164,184,229);
         $textColor = $ghost === false ? array(255, 255, 255)   : array(2,2,2);
        */
         $fillColor = array(76, 109, 184);
         $textColor = array(255, 255, 255);

         $this->_fpdi->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
         $this->_fpdi->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
         $this->_fpdi->SetFont($this->_fontDefault, $this->_fontBold, $this->_fontSizeHeadline1);

         $this->_fpdi->SetXY($this->_matrixConf[$handle]["pos"]["x"], $this->_matrixConf[$handle]["pos"]["y_tmp"]);
         $this->_fpdi->MultiCell($this->_matrixConf[$handle]["width"], $this->_rowHeight, $this->_matrixConf[$handle]['caption'], 1, 'L', 1);

         $this->_matrixConf[$handle]["pos"]["y_tmp"] = $this->_fpdi->GetY();
      }

      $this->_matrixConf[$handle]["x_factor"] = $this->_matrixConf[$handle]["width"] / 100;

      if ($subhead === 1) {

          /*
         $fillColor = $ghost === false ? array(187, 199, 227)    : array(221,231,255);
         $textColor = $ghost === false ? array(0, 0, 0)          : array(2,2,2);
         */
         $fillColor = array(187, 199, 227);
         $textColor = array(0, 0, 0);

         foreach ($this->_matrixConf[$handle]["col"] AS $key => $col) {
            $this->_fpdi->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
            $this->_fpdi->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
            $this->_fpdi->SetFont($this->_fontDefault, $this->_fontBold, $this->_fontSizeHeadline2);

            $this->_fpdi->SetXY($this->_matrixConf[$handle]["pos"]["x"] + ($col["begin_x"] * $this->_matrixConf[$handle]["x_factor"]), $this->_matrixConf[$handle]["pos"]["y_tmp"]);
            $this->_fpdi->MultiCell($col["width"] * $this->_matrixConf[$handle]["x_factor"], $this->_rowHeight, $col["bez"], 1, 'C', 1, 0, '', '', true, 0, false, true, $this->_rowHeight, 'M');
         }

         $this->_matrixConf[$handle]["pos"]["y_tmp"] = $this->_matrixConf[$handle]["pos"]["y_tmp"] + $this->_rowHeight;
      }
   }


   private function _checkPageBreak($handle, $rows = 0, $drawHeader = false)
   {
      if ($rows > 0) {
         $currentY = $this->_matrixConf[$handle]["pos"]["y_tmp"];

         if ($currentY + ($rows * $this->_rowHeight) > ($this->_matrixConf[$handle]['height'] + (2 * $this->_rowHeight))) {

             if ($drawHeader === true) {
                $this->_fpdi->SetFont($this->_fontDefault, 'B', $this->_fontSizeNormal);
                $this->_fpdi->Text($this->_matrixConf[$handle]["pos"]["x"], $currentY, $this->_config['lbl_next_page']);
             }

             $this->_matrixConf[$handle]['pos']['y']        = $this->_pageMarginTop;
             $this->_matrixConf[$handle]["pos"]["y_tmp"]    = $this->_pageMarginTop;

             $this->_fpdi->AddPage($this->_matrixConf[$handle]['page']);

             if ($drawHeader === true) {
                 $this->_drawHeadline($handle, true);
             }
         }
      }

      return $this;
   }


   /**
    * order the final result
    *
    */
   private function _order($handle)
   {
      $orderBy = $this->_matrixConf[$handle]['order'];

      if ($orderBy !== null) {
          $orderCol     = key($orderBy);
          $orderType    = in_array($orderBy[$orderCol], array('ASC', 'DESC')) === true ? $orderBy[$orderCol] : 'ASC';
          $row          = $this->_matrixConf[$handle]['col'][$orderCol]['row'];

          if (count($row) > 0) {
              foreach ($row AS $i => $value) {
                  $row[$i] = strtolower($value);
              }

              if ($orderType == 'ASC') {
                 asort($row, SORT_STRING);
              } else {
                 arsort($row, SORT_STRING);
              }

              $newOrder = array_keys($row);

              //Now Sort all
              foreach ($this->_matrixConf[$handle]['col'] AS $i => $col) {
                  $row = $col['row'];

                  $tmpRow = array();

                  foreach ($newOrder as $key) {
                      $tmpRow[] = $row[$key];
                  }

                  $this->_matrixConf[$handle]['col'][$i]['row'] = $tmpRow;
              }
          }
      }

      return $this;
   }



   /**
    * draw matrix
    *
    * @param $show_summe
    */
   public function draw()
   {
      $handle = $this->_getHandle();

      if (array_key_exists('col', $this->_matrixConf[$handle]) === false || count($this->_matrixConf[$handle]['col']) == 0) {
         return;
      }

      $this
         ->_order($handle)
         ->_adjustColumns($handle)
         ->_drawHeadline($handle)
      ;

      $this->_fpdi->SetTextColor(0, 0, 0);
      $this->_fpdi->SetFont($this->_fontDefault, $this->_fontBold, $this->_fontSizeNormal);

      $rowCount = $this->_matrixConf[$handle]['rowCount']['rows'] - 1;

      for ($row=0; $row <= $rowCount; $row++) {
         $this->_checkPageBreak($handle, 1, true);

         $c = ($row % 2 ? 230 : 250);
         $this->_fpdi->SetFillColor($c,$c,$c);

         foreach ($this->_matrixConf[$handle]['col'] as $colI => $col) {

             $align = isset($col['params']['align']) ? $col['params']['align'] : 'L';

             $this->_fpdi->SetXY(
                $this->_matrixConf[$handle]["pos"]["x"] + ($col["begin_x"] * $this->_matrixConf[$handle]["x_factor"]),
                $this->_matrixConf[$handle]["pos"]["y_tmp"]
             );

             $value = $col['row'][$row];

             $this->_fpdi->MultiCell($col["width"] * $this->_matrixConf[$handle]["x_factor"], $this->_rowHeight, $value, 1, $align, 1, 0, '', '', true, 0, false, true, $this->_rowHeight, 'M');
         }

         $this->_matrixConf[$handle]["pos"]["y_tmp"] += ($this->_rowHeight);
      }

      $this->_fpdi->SetY($this->_matrixConf[$handle]["pos"]["y_tmp"]);

      return $this;
   }

   /**
    * returns the handle of the last inserted handle
    */
   private function _getHandle()
   {
      return $this->_handle;
   }

   /**
    *
    * adjust the column width of the matrix
    * @param $handle
    */
   private function _adjustColumns($handle)
   {
      $columnsWithoutWidth = array();
      $matrixWidth = $this->_matrixConf[$handle]['width'];

      foreach ($this->_matrixConf[$handle]['col'] as $index => $column) {
         if (strlen($column['width']) == 0) {
            $columnsWithoutWidth[] = $index;
         } else {
            $matrixWidth -= $column['width'];
         }
      }

      $columnsWithoutWidthCount = count($columnsWithoutWidth);

      if ($columnsWithoutWidthCount > 0) {
         $widthPerColumn = $matrixWidth / count($columnsWithoutWidth);

         foreach ($columnsWithoutWidth as $index) {
            $this->_matrixConf[$handle]['col'][$index]['width'] = $widthPerColumn;
         }
      }

      $this->_matrixConf[$handle]['rowCount']['count'] = $this->_matrixConf[$handle]['rowCount']['head'] + $this->_matrixConf[$handle]['rowCount']['subhead'] + $this->_matrixConf[$handle]['rowCount']['rows'];

      return $this;
   }


   /**
    * Calculates the position of the matrix
    *
    * @param $handle
    * @param $params
    */
   private function _calculateMatrixPosition($handle, $params)
   {
      //X Position
      $this->_matrixConf[$handle]["pos"]["x"] = (array_key_exists('x', $params) === true && $params['x'] !== null)
         ? $params['x']
         : $this->_pageMarginLeft
      ;

      //Y Position
      $this->_matrixConf[$handle]['pos']['y'] = (array_key_exists('y', $params) === true && $params['y'] !== null)
         ? $params['y']
         : $this->_pageMarginTop
      ;

      $orientation = method_exists($this->getFPDI(), 'getCurrentPageOrientation') === true
         ? $this->getFPDI()->getCurrentPageOrientation()
         : 'p'
      ;

      //Width
      $this->_matrixConf[$handle]["width"] = (array_key_exists('w', $params) === true && $params['w'] !== null)
         ? $params['w']
         : $this->_pageWidth[$orientation] - ($this->_pageMarginLeft + $this->_pageMarginRight);
      ;

      $this->_matrixConf[$handle]["height"] = $this->_pageHeight[$orientation] - ($this->_pageMarginTop + $this->_pageMarginBottom);

      $this->_matrixConf[$handle]['page'] = (array_key_exists('page', $params) === true && $params['page'] !== null)
         ? $params['page']
         : 'p'
      ;


      return $this;
   }
}

?>