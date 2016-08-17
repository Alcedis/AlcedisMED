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

abstract class customReport
{
    /**
     * _params
     *
     * @access  protected
     * @var     array
     */
    protected $_params;


    /**
     * _db
     *
     * @access  protected
     * @var     resource
     */
    protected $_db;


    /**
     * _smarty
     *
     * @access  protected
     * @var     Smarty
     */
    protected $_smarty;


    /**
     * _renderer
     *
     * @access  protected
     * @var     mixed
     */
    protected $_renderer;


    /**
     * _configBuffer
     *
     * @access  protected
     * @var     array
     */
    protected $_configBuffer = array();


    /**
     * _data
     *
     * @access  protected
     * @var     array
     */
    protected $_data = array();


    /**
     * _title
     *
     * @access  protected
     * @var     string
     */
    protected $_title;


    /**
     * _subDir
     *
     * @access  protected
     * @var     string
     */
    protected $_subDir;


    /**
     * _type
     *
     * @access  protected
     * @var     string
     */
    protected $_type;


    /**
     * _debug
     *
     * @access  protected
     * @var     array
     */
    protected $_debug;


    /**
     * _template
     *
     * @access  protected
     * @var     string
     */
    protected $_template;


    /**
     * _lookupCache
     *
     * @access  protected
     * @var     array
     */
    protected $_lookupCache = array();


    /**
     * _cache
     *
     * @access  protected
     * @var     array
     */
    protected $_cache = array();


    /**
     * _preQuery
     *
     * @access  protected
     * @var     string
     */
    protected $_preQuery;


    /**
     * _filteredDiseases
     * (inherits disease ids for relevant patient diseases)
     *
     * @access  protected
     * @var     string
     */
    protected $_filteredDiseases;


    /**
     * _additionalQueryData
     *
     * @access  protected
     * @var     array
     */
    protected $_additionalQueryData = array();


    /**
     * _saveCell
     *
     * @access  protected
     * @var     array
     */
    protected $_saveCell = array();


    /**
     * _report
     *
     * @access  protected
     * @var     customReport[] TODO interface
     */
    protected $_reports = array();


    /**
     * @param mixed     $renderer
     * @param resource  $db
     * @param Smarty    $smarty
     * @param string    $subdir
     * @param string    $type
     * @param array     $params
     */
    public function __construct($renderer, $db, $smarty, $subdir, $type, $params = null)
    {
        $this->_db        = $db;
        $this->_params    = $params;
        $this->_smarty    = $smarty;
        $this->_type      = $type;
        $this->_renderer  = $renderer;
        $this->_subDir    = ($subdir !== null ? $subdir : '');

        $this->loadConfigs('default', true);

        $this->construct();
    }


    /**
     * construct
     *
     * @access  public
     * @return  void
     */
    public function construct()
    { }


    /**
     * setSubDir
     *
     * @access  public
     * @param   string  $subDir
     * @return  customReport
     */
    public function setSubDir($subDir)
    {
        $this->_subDir = $subDir;

        return $this;
    }


    /**
     * getSubDir
     *
     * @access  public
     * @return  string
     */
    public function getSubDir()
    {
        return $this->_subDir;
    }


    /**
     * getRenderer
     *
     * @access  public
     * @return  mixed // TODO interface
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }


    /**
     * setRenderer
     *
     * @access  public
     * @param   mixed $renderer // TODO interface
     * @return  customReport // TODO interface
     */
    public function setRenderer($renderer)
    {
        $this->_renderer = $renderer;

        return $this;
    }


    /**
     * getType
     *
     * @access  public
     * @return  string
     */
    public function getType()
    {
        return $this->_type;
    }


    /**
     * setType
     *
     * @access  public
     * @param   string  $type
     * @return  customReport // TODO interface
     */
    public function setType($type)
    {
        $this->_type = $type;

        return $this;
    }


    /**
     * setAdditionalQueryData
     *
     * @access  public
     * @param   array $data
     * @return  customReport // TODO interface
     */
    public function setAdditionalQueryData(array $data = array())
    {
        $this->_additionalQueryData = $data;

        return $this;
    }


    /**
     * getAdditionalQueryData
     *
     * @access  public
     * @return  array
     */
    public function getAdditionalQueryData()
    {
        return $this->_additionalQueryData;
    }


    /**
     * getReport
     *
     * @access  public
     * @param   string$name
     * @return  customReport
     * @throws  Exception
     */
    public function getReport($name)
    {
        $reports = $this->_reports;

        if (array_key_exists($name, $reports) === false) {
            throw new Exception("report {$name} was not initialized");
        }

        $report = $reports[$name];

        return $report;
    }


    /**
     * getCache
     *
     * @access  public
     * @param   string  $entry
     * @param   mixed   $default
     * @return  mixed
     */
    public function getCache($entry, $default = null)
    {
        $cache = $default;

        if (array_key_exists($entry, $this->_cache) === true) {
            $cache = $this->_cache[$entry];
        }

        return $cache;
    }


    /**
     * setCache
     *
     * @access  public
     * @param   string  $entry
     * @param   mixed   $value
     * @return  $this
     */
    public function setCache($entry, $value)
    {
        $this->_cache[$entry] = $value;

        return $this;
    }


    /**
     * getData
     *
     * @access  public
     * @return  array
     */
    public function getData()
    {
        return $this->_data;
    }


    /**
     * getDB
     *
     * @access  public
     * @return  resource
     */
    public function getDB()
    {
        return $this->_db;
    }


    /**
     * getSmarty
     *
     * @access  public
     * @return  Smarty
     */
    public function getSmarty()
    {
        return $this->_smarty;
    }


    /**
     * getFilteredDiseases
     *
     * @access  public
     * @return  string
     */
    public function getFilteredDiseases()
    {
        return $this->_filteredDiseases;
    }


    /**
     * addSaveCell
     *
     * @access  public
     * @param   string  $column
     * @param   string  $row
     * @param   string  $data
     * @return  customReport
     */
    public function addSaveCell($column, $row, $data = null)
    {
        if ($data !== null) {
            if (check_array_content($data) === true) {
                $this->_saveCell["{$column}{$row}"] = true;
            }
        } else {
            $this->_saveCell["{$column}{$row}"] = true;
        }

        return $this;
    }


    /**
     * clearCells
     *
     * @access  public
     * @param   array $matrix
     * @return  array
     */
    public function clearCells(array $matrix)
    {
        foreach ($matrix as $field => $data) {
            if (array_key_exists($field, $this->_saveCell) === false && $data == '0') {
                $matrix[$field] = '';
            }
        }

        return $matrix;
    }


    /**
     * _statusJoin
     * (build join condition for status joins)
     *
     * @access  protected
     * @param   string  $tableWithAlias
     * @param   bool $considerSide
     * @return  string
     */
    protected function _statusJoin($tableWithAlias, $considerSide = false)
    {
        list($table, $alias) = explode(' ', $tableWithAlias);

        $stmt = "LEFT JOIN $table $alias ON s.form = '$table' AND $alias.{$table}_id = s.form_id";

        if (true === $considerSide) {
            $stmt .= " AND $alias.diagnose_seite IN ('B', sit.diagnose_seite)";
        }

        return $stmt;
    }


    /**
     * _innerStatus
     * (build join condition for main status join)
     *
     * @access  protected
     * @return  string
     */
    protected function _innerStatus()
    {
        $query = "INNER JOIN status s ON
            (s.patient_id = sit.patient_id AND s.form IN ('nachsorge', 'abschluss', 'aufenthalt'))
            OR
            (s.erkrankung_id = sit.erkrankung_id AND
                ((s.form != 'studie' AND (s.form_date BETWEEN sit.start_date AND sit.end_date OR s.form_date IS NULL))
                OR
                (s.form = 'studie' AND s.report_param BETWEEN sit.start_date AND sit.end_date))
            )
        ";

        return $query;
    }


    /**
     * _notCountSelect
     *
     * @access  protected
     * @return  string
     */
    protected function _notCountSelect()
    {
        $query = "(SELECT IF(MAX(ts.nur_zweitmeinung) IS NOT NULL OR MAX(ts.nur_diagnosesicherung) IS NOT NULL " .
            "OR MAX(ts.kein_fall) IS NOT NULL, 1, NULL) FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id " .
            "AND ts.anlass = t.anlass) AS 'nicht_zaehlen'
        ";

        return $query;
    }


    /**
     * _sideSelect
     * (deprecated)
     *
     * @access  protected
     * @return  string
     */
    protected function _sideSelect()
    {
        $query = "(SELECT ts.diagnose_seite FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND " .
            "ts.anlass = t.anlass AND ts.diagnose IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad " .
            "ASC, ts.datum_beurteilung DESC LIMIT 1) AS diagnose_seite";

        return $query;
    }


    protected function _diseaseExtension($type, $date = null)
    {
      $return = '';

      switch ($type) {
         case 'joins':

            $return = "
               LEFT JOIN anamnese_erkrankung vorerk1  ON s.form = 'anamnese' AND vorerk1.anamnese_id = s.form_id AND LEFT(vorerk1.erkrankung, 1) = 'C'
               LEFT JOIN erkrankung sonstige_erk      ON sonstige_erk.patient_id = sit.patient_id AND sonstige_erk.erkrankung_id != sit.erkrankung_id AND sonstige_erk.erkrankung != 'd'
               LEFT JOIN status sonstige_erk_status   ON sonstige_erk_status.erkrankung_id = sonstige_erk.erkrankung_id AND sonstige_erk_status.form = 'histologie' AND sonstige_erk_status.form_date < '{$date}-12-31'

               LEFT JOIN erkrankung darm_erk ON darm_erk.patient_id = sit.patient_id AND darm_erk.erkrankung_id != sit.erkrankung_id AND darm_erk.erkrankung = 'd'
                  LEFT JOIN tumorstatus darm_erk_ts ON darm_erk_ts.erkrankung_id = darm_erk.erkrankung_id
                  LEFT JOIN status darm_erk_status ON darm_erk_status.erkrankung_id = darm_erk.erkrankung_id AND darm_erk_status.form IN ('histologie', 'eingriff') AND darm_erk_status.form_date < '{$date}-12-31'
            ";

            break;

        case 'fields':

           $return = "
            CONCAT_WS(
               ',',

               /* spätere Darm Erkrankung speziallfall wegen zusätzlichem eingriff check*/
               IF(

                  IFNULL(
                     IFNULL(
                        MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                        MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
                     ),
                     IF (
                        MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                        MIN(darm_erk_ts.datum_sicherung),
                        null
                     )
                  ) > MIN(h.datum),
                  IFNULL(
                     IFNULL(
                        MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                        MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
                     ),
                     IF (
                        MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                        MIN(darm_erk_ts.datum_sicherung),
                        null
                     )
                  ),
                  null
               ),

               /* spätere sonstige Erkrankung */
               IF(
                  MIN(sonstige_erk_status.form_date) > MIN(h.datum),
                  MIN(sonstige_erk_status.form_date),
                  NULL
               ),

               /* malignom */
               IF(
                  MAX(IF(n.datum <= '{$date}-12-31' AND n.malignom = '1', n.datum, NULL)) > MIN(h.datum),
                  MAX(IF(n.datum <= '{$date}-12-31' AND n.malignom = '1', n.datum, NULL)),
                  NULL
               )
            ) AS 'zweitmalignom',

            IF(
               /* vorhergehende Darmerkrankung */
               /* Für Primärfälle bei Darm: Eingriff mit Resektion des Primärtumors , wenn dies nicht dokumentiert, dann Datum der frühesten dokumentierten Histologie */

               IFNULL(
                  IFNULL(
                     MIN(IF(darm_erk_status.form = 'eingriff' AND LEFT(darm_erk_status.report_param, 1) = '1', darm_erk_status.form_date, NULL)),
                     MIN(IF(darm_erk_status.form = 'histologie', darm_erk_status.form_date, NULL))
                  ),
                  IF (
                     MIN(SUBSTRING(darm_erk_ts.anlass, 1, 1)) = 'r',
                     MIN(darm_erk_ts.datum_sicherung),
                     '9999-12-12'
                  )
               )

               < MIN(h.datum)

               OR

               /* vorhergehende sonstige Erkrankung */

               MIN(sonstige_erk_status.form_date) < MIN(h.datum)

               OR

               /* in der einer anamnese ist ein c dokumentiert */

               COUNT(vorerk1.anamnese_erkrankung_id) > 0,
               1,
               0
            ) AS 'vorerkrankung'
           ";

           break;
      }

        return $return;
    }


    /**
     * _buildHaving
     *
     * @access  protected
     * @param   string $field
     * @param   bool   $checkDateYear
     * @return  string
     */
    protected function _buildHaving($field = 'bezugsdatum', $checkDateYear = false)
    {
        $datum_von = $this->_getFromDate();
        $datum_bis = $this->_getUntilDate();

        if ($checkDateYear === true && $datum_von === null && $datum_bis === null && array_key_exists('jahr', $this->_params) === true && strlen($this->_params['jahr']) > 0) {
            $datum_von = "{$this->_params['jahr']}-01-01";
            $datum_bis = "{$this->_params['jahr']}-12-31";
        }

        return "1 " . ( $datum_von != NULL || $datum_bis != NULL ? "AND $field IS NOT NULL" : '' ) . "
             " . ( $datum_von != NULL ? "AND $field >= '$datum_von'" : '' ) . "
             " . ( $datum_bis != NULL ? "AND $field <= '$datum_bis'" : '' );
    }


    /**
     * writeXLS
     *
     * @access  public
     * @param   string  $headLabelsFrom
     * @return  void
     */
    public function writeXLS($headLabelsFrom = null)
    {
        $renderer = $this->_renderer;
        $data = $this->_data;

        $config = $this->loadConfigs($this->getParam('name'));

        $bold = $renderer->workbook->addFormat();
        $bold->setBold();
        $row = 0;
        $col = 0;


        if ($this->_title !== null) {
            $renderer->WriteText($row, 0, $this->_title, $bold);
            $row += 2;
        }

      $renderer->WriteText($row, 0, concat(array($config['lbl_xls_date_create'], date('d.m.Y H:i:s')),' '));
      $row += 2;

      if (array_key_exists('datum_von', $this->_params) === true) {
         $this->_params['datum_von'] = convertDate($this->_params['datum_von']);
      }

      if (array_key_exists('datum_bis', $this->_params) === true) {
         $this->_params['datum_bis'] = convertDate($this->_params['datum_bis']);
      }

      if ((isset($this->_params['datum_von']) && preg_match('~[0-9]{2}\.[0-9]{2}\.[1-2]{1}[0-9]{3}~', $this->_params['datum_von'])) ||
            (isset($this->_params['datum_bis']) && preg_match('~[0-9]{2}\.[0-9]{2}\.[1-2]{1}[0-9]{3}~', $this->_params['datum_bis']))) {

         $datumsbereich = array(
             $config['lbl_xls_date_range']
         );

         if (strlen($this->_params['datum_von'])) {
            $datumsbereich[] = $config['lbl_xls_date_start'];
            $datumsbereich[] = $this->_params['datum_von'];
         }

         if (strlen($this->_params['datum_bis'])) {
            $datumsbereich[] = $config['lbl_xls_date_end'];
            $datumsbereich[] = $this->_params['datum_bis'];
         }

         $renderer->WriteText($row, 0, implode(' ', $datumsbereich));
         $row += 2;
      }

      if (count($data) > 0) {
         $config = $headLabelsFrom === null ? $config : $this->loadConfigs($headLabelsFrom);

         // Spaltenüberschriften
         foreach (array_keys(reset($data)) as $head) {
            $renderer->worksheet->setColumn( $col, $col, 20 );
            $renderer->WriteText( $row, $col++, (array_key_exists($head, $config) === true ? $config[$head] : $head), $bold );
         }

         $row++;

         // Daten
         foreach( $data as $record )
         {
            $col = 0;

            foreach( $record as $value )
            {
               if( preg_match( "/^[0-2][0-9]{3}-[0-1][0-9]-[0-3][0-9]$/", $value ) )
                  $renderer->WriteDate( $row, $col++, $value );
               elseif( is_numeric( $value ) )
                  $renderer->WriteNumber( $row, $col++, $value );
               else
                  $renderer->WriteText( $row, $col++, $value );
            }
            $row++;
         }
      }
   }


    /**
     * _filterDisease
     * (generiert eine Komma-separierte Liste aller für die gegebene Erkrankung relevanten Ids
     * und cached dieses in der customReport)
     *
     * @access  protected
     * @param   bool $all
     * @return  string
     */
    protected function _filterDisease($all = false)
    {
        if ($this->_filteredDiseases === null) {
            $erk = $all === true ? 1 : "e.erkrankung = '{$this->_params['sub']}'";

            $this->_filteredDiseases = dlookup($this->_db,
                'erkrankung e INNER JOIN tumorstatus t ON t.erkrankung_id = e.erkrankung_id',
                "IFNULL(GROUP_CONCAT(DISTINCT e.erkrankung_id), 0)",
                $erk
            );
        }

        return $this->_filteredDiseases;
    }


    /**
     * getDiseaseFilter
     *
     * @access  public
     * @return  string
     */
    public function getDiseaseFilter()
    {
        return $this->_filteredDiseases;
    }


    /**
     * setDiseaseFilter
     *
     * @access  public
     * @param   string  $filter
     * @return  customReport
     */
    public function setDiseaseFilter($filter)
    {
        $this->_filteredDiseases = $filter;

        return $this;
    }


    /**
     * resetDiseaseFilter
     *
     * @access  public
     * @return  customReport
     */
    public function resetDiseaseFilter()
    {
        $this->_filteredDiseases = null;

        return $this;
    }



   public function loadConfigs($file, $noSub = false, $utf8 = false)
   {
      if (array_key_exists($file, $this->_configBuffer) === true) {
          $config = array_merge(
              $this->_configBuffer['default'],
              $this->_configBuffer[$file]
          );

          $this->_configBuffer[$file];
      } else {
          $path = "reports/config/" . ($noSub || !strlen($this->_subDir) ? '' : $this->_subDir . '/') . "{$file}.conf";

          $loadedConf   = array();
          $configBackup = $this->_smarty->get_config_vars();

          if (file_exists($path) === true) {
                $path = '../' . $path;

                $this->_smarty->clear_config();

                $this->_smarty->config_load($path);

                $loadedConf = $this->_smarty->get_config_vars();

                $this->_smarty->set_config($configBackup);
          }

          if ($file == 'default') {
              $config = $this->_configBuffer[$file] = array_merge(
                  $configBackup,
                  $loadedConf
              );
          } else {
              $config = $this->_configBuffer[$file] = $loadedConf;
          }

          if ($file !== 'default') {
              $config = array_merge(
                $this->_configBuffer['default'],
                $this->_configBuffer[$file]
            );
          }
      }

      if ($utf8 === true) {
         foreach ($config as &$string) {
            $string = utf8_encode($string);
         }
      }

      return $config;
   }


    /**
     * loadRessource
     * (load resource file *01)
     *
     * @access  protected
     * @param   string  $name
     * @param   array   $additionalContent
     * @return  array
     */
    protected function loadRessource($name, $additionalContent = array())
    {
        $additionalCondition = null;
        $additionalFields    = null;
        $additionalTsSelects = array();
        $additionalJoins     = null;

        // set additional condition
        if (array_key_exists('condition', $additionalContent) === true) {
            $additionalCondition = "AND " . $additionalContent['condition'];
        }

        // set additional fields
        if (array_key_exists('fields',  $additionalContent) === true) {
            $additionalFields = implode(',', is_array($additionalContent['fields']) === true ? $additionalContent['fields'] : array($additionalContent['fields'])) . ',';
        }

        // set additional selects
        if (array_key_exists('selects', $additionalContent) === true) {
            $additionalTsSelects = is_array($additionalContent['selects']) === true ? $additionalContent['selects'] : array($additionalContent['selects']);
        }

        // set additional joins
        if (array_key_exists('joins', $additionalContent) === true) {
            $additionalJoins = implode(' ', is_array($additionalContent['joins']) === true ? $additionalContent['joins'] : array($additionalContent['joins']));
        }

        $subDir = strlen($this->_subDir) ? $this->_subDir . '/' : '';
        $path = "reports/ressource/{$subDir}{$name}.php";

        if (file_exists($path) === true) {
            include $path;

            $className = 'report' . ucfirst($name);

            // new structure
            if (class_exists($className) === true) {
                /* @var customReport $report */
                $report = new $className(
                    $this->getRenderer(),
                    $this->getDB(),
                    $this->getSmarty(),
                    $this->getSubDir(),
                    $this->getType(),
                    $this->getParams()
                );

                // set possible pre defined disease filter
                $report->setDiseaseFilter($this->getDiseaseFilter());

                // set additional data for query building
                $report->setAdditionalQueryData(array(
                    'condition' => $additionalCondition,
                    'fields'    => $additionalFields,
                    'selects'   => $additionalTsSelects,
                    'joins'     => $additionalJoins
                ));

                // load report data
                $data = $report->getData();

                $this->_reports[$name] = $report;
            }
        } else {
            echo 'report doesn´t exist!';
        }

        return isset($data) ? $data : (isset($query) ? $query : NULL);
    }


    /**
     * getFirstValue
     *
     * @access  public
     * @return  bool
     */
    public function getFirstValue()
    {
        foreach (func_get_args() as $arg) {
            if (strlen($arg) > 0) {
                return $arg;
            }
        }

        return false;
    }


    /**
     * writePDF
     *
     * @access  public
     * @param   bool $save
     * @param   bool $letterArray
     * @return  void
     */
    public function writePDF($save = false, $letterArray = false)
    {
        $this->_renderer->setLetterType('pdf');
        $this->_renderer->setLetterTemplate($this->_template);
        $this->_renderer->setInsertArray($this->_data);

        $userDir = isset($_SESSION['sess_loginname']) ? $_SESSION['sess_loginname'] . '/' : '';

        if ($save) {
            $this->_renderer->setTmpDir("material/$userDir");
        }

        $config = $this->loadConfigs('default');

        $this->_renderer->setUrlToJOD($config['converter_url']);

        if ($letterArray === false) {
            $this->_renderer->generateLetter();
        } else {
            $this->_renderer->generateLetterArray();
        }
    }

   public function parseXLS($cellWrap = false)
   {
      $objWorksheet = $this->_renderer->getActiveSheet();

      $multiplier  = 12.8;
      $cellWraps   = array();

      foreach ($this->_data as $coord => $value) {
         $cell = $objWorksheet->getCell($coord);
         $cell->setValue($value);

         if ($cellWrap === true) {
            $row            = $cell->getRow();
            $column         = $cell->getColumn();
            $tmpCellWraps   = substr_count($value, "\n") + 1;

            $objWorksheet->getColumnDimension($column)->setAutoSize(true);

            $rowWraps = isset($cellWraps[$row]) === true && $cellWraps[$row] > $tmpCellWraps
                ? $cellWraps[$row]
                : $tmpCellWraps;

            $cellWraps[$row] = $rowWraps;

            $objWorksheet->getStyle($coord)->getAlignment()->setWrapText(true)->setVertical('top');

            $objWorksheet->getRowDimension($row)->setRowHeight($rowWraps * $multiplier);
         }
      }
   }

   /**
    * save join on l_basic for anlass
    */
   protected function _getAnlassCases($alias = 'sit')
   {
        $anlaesse = sql_query_array($this->_db, "SELECT code, bez FROM l_basic WHERE klasse='tumorstatus_anlass'");
        $return   = "CASE";

        foreach ($anlaesse as $anlass) {
            $return .= " WHEN $alias.anlass = '$anlass[code]' THEN '$anlass[bez]'";
        }

        return count($anlaesse) ? $return . ' END' : "''";
   }


   protected function _getNcState($type = null)
   {
       $state = null;

       switch ($type) {
           case 'oz01pz':

               $state = $this->getParam('dontUseNzPz') === true ? null : ' AND prostata_nz = 0';

               break;

           case 'p01':
           case 'p01_2':

               $state = $this->getParam('dontUseNzPz') === true ? null : ' AND nz = 0';

               break;
           default:

               $state = $this->getParam('dontUseNz') === true ? ' 1 ' : ' sit.nicht_zaehlen IS NULL';

               break;
       }

       return $state;
   }


    /**
     * _getPreQuery
     *
     * @access  protected
     * @param   string  $having
     * @param   array   $diseaseRelevantSelects
     * @param   array   $where
     * @return  string
     */
    protected function _getPreQuery($having = null, $diseaseRelevantSelects = array(), $where = array())
    {
        $diseases   = $this->_filterDisease();
        $all_org    = isset($this->_params['all_org']) && $this->_params['all_org'] == true ? true : false;
        $org_id     = isset($this->_params['org_id']) ? $this->_params['org_id'] : '';

        $where = implode(' AND ', array_merge(array(
            ($all_org === false ? "p.org_id = '{$org_id}'" : 1)
        ), $where));

        $diseaseRelevantSelects = is_array($diseaseRelevantSelects) === false ? array($diseaseRelevantSelects) : $diseaseRelevantSelects;
        $diseaseRelevantSelects = count($diseaseRelevantSelects) > 1
            ? implode(', ', $diseaseRelevantSelects) . ','
            : (count($diseaseRelevantSelects) == 1
               ? reset($diseaseRelevantSelects) . ','
               : ''
            )
        ;

        //conditions for nested selects (they're all the same, si!)
        $basicOrder       = 'ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1';
        $basicCondition   = "FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";

        $having = $having !== null ? 'HAVING ' . $having : '';

      $query = "
         SELECT
            e.erkrankung_id,
            e.erkrankung,
            e.zweiterkrankung,
            e.erkrankung_relevant,
            t.anlass,
            p.patient_id,
            p.nachname,
            p.vorname,
            p.patient_nr,
            p.geburtsdatum,
            p.geschlecht,
            t.tumorstatus_id,
            p.org_id,

            IF(x.first_date=MIN(IF(t2.anlass = t.anlass, t2.datum_sicherung, null)), '0000-00-00', MIN(t.datum_sicherung))                                       AS 'start_date',
            DATE_SUB(IFNULL(MIN(IF(t2.anlass != t.anlass AND t2.datum_sicherung > t.datum_sicherung, t2.datum_sicherung,null) ), '9999-12-31'), INTERVAL 1 DAY)  AS 'end_date',
            MAX(t.datum_sicherung)                                                                                                                               AS 'datum_sicherung',
            MIN(t.datum_sicherung)                                                                                                                               AS 'start_date_rezidiv',

            {$diseaseRelevantSelects}

            (SELECT ts.t            {$basicCondition} AND LEFT(ts.t, 1) = 'p' $basicOrder)            AS 'pt',
            (SELECT ts.n            {$basicCondition} AND LEFT(ts.n, 1) = 'p' $basicOrder)            AS 'pn',
            (SELECT ts.diagnose     {$basicCondition} AND ts.diagnose IS NOT NULL $basicOrder)        AS 'diagnose',
            (SELECT ts.morphologie  {$basicCondition} AND ts.morphologie IS NOT NULL $basicOrder)     AS 'morphologie',
            (SELECT ts.r            {$basicCondition} AND ts.r IS NOT NULL $basicOrder)               AS 'r'

         FROM patient p
             INNER JOIN erkrankung e         ON e.patient_id = p.patient_id AND e.erkrankung_id IN ({$diseases})
                 INNER JOIN tumorstatus t    ON t.erkrankung_id = e.erkrankung_id
                 LEFT JOIN tumorstatus t2    ON t2.erkrankung_id = t.erkrankung_id AND t2.diagnose_seite = t.diagnose_seite

                 INNER JOIN (
                     SELECT
                         erkrankung_id,
                         diagnose_seite,
                         MIN(datum_sicherung) AS first_date
                     FROM
                         tumorstatus
                     WHERE erkrankung_id IN ({$diseases})
                     GROUP BY
                       erkrankung_id,
                       diagnose_seite
                 ) x                          ON x.erkrankung_id = e.erkrankung_id AND x.diagnose_seite = t.diagnose_seite
         WHERE {$where}
         GROUP BY
             p.patient_id,
             e.erkrankung_id,
             t.anlass,
             t.diagnose_seite
         {$having}
         ORDER BY NULL
      ";

        return $this->_preQuery = $query;
    }


    /**
     * _getPreQuerySql
     *
     * @access  protected
     * @return  string
     */
    protected function _getPreQuerySql()
    {
        return $this->_preQuery;
    }


    /**
     * _getVonDate
     * (deprecated)
     *
     * @access  protected
     * @return  string
     */
    protected function _getVonDate()
    {
        return $this->_getFromDate();
    }


    /**
     * _getFromDate
     *
     * @access  protected
     * @return  string
     */
    protected function _getFromDate()
    {
        $date = $this->getParam('datum_von', null);
        $date = $this->_checkDate($date);

        // set new date
        $this->setParam('datum_von', $date);

        return $date;
    }


    /**
     * _getBisDate
     *
     * @access  protected
     * @return  string
     */
    protected function _getBisDate()
    {
        return $this->_getUntilDate();
    }


    /**
     * _getUntilDate
     *
     * @access  protected
     * @return  string
     */
    protected function _getUntilDate()
    {
        $date = $this->getParam('datum_bis', null);
        $date = $this->_checkDate($date);

        // set new date
        $this->setParam('datum_bis', $date);

        return $date;
    }


    /**
     * _checkDate
     *
     * @access  protected
     * @param   string $date
     * @return  string
     */
    protected function _checkDate($date)
    {
        if ($date !== null) {
            $enFormat = preg_match('~[1-2]{1}[0-9]{3}-[0-9]{2}-[0-9]{2}~', $date) === 1;

            // if date not already in english format
            if ($enFormat === false) {
                $date       = convertDate($date);
                $deFormat   = preg_match('~[0-9]{2}\.[0-9]{2}\.[1-2]{1}[0-9]{3}~', $date) === 1;
                $dateLength = strlen($date) === 10;

                // check german format and correct date length
                if ($deFormat === true && $dateLength === true) {
                    toDate($date, 'en');
                } else { // if de format for conversion does not match, return empty value
                    $date = null;
                }
            }
        }

        return $date;
    }


    /**
     * getParam
     *
     * @access  public
     * @param   string $param
     * @param   bool $else
     * @return  bool
     */
    public function getParam($param, $else = false)
    {
        return isset($this->_params[$param]) && strlen($this->_params[$param]) ? $this->_params[$param] : $else;
    }


    /**
     * setParam
     *
     * @access  public
     * @param   string  $param
     * @param   mixed   $value
     * @return  customReport // TODO interface
     */
    public function setParam($param, $value)
    {
        $this->_params[$param] = $value;

        return $this;
    }


    /**
     * getParams
     *
     * @access  public
     * @return  array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /**
     * getDebug
     *
     * @access  public
     * @return  array
     */
    public function getDebug()
    {
        return $this->_debug;
    }

   public function debug()
   {
      $args = func_get_args();
      $output = false;

      if (end($args) === true) {
         $output = true;
         array_pop($args);
      }

      foreach ($args as $arg) {
         if ($output === true) {
            ob_start();
                print_arr($arg);
                $arg = ob_get_contents();
            ob_end_clean();
         }

         $this->_debug[] = $arg;
      }

      return $this;
   }

   protected function _initPercentArray($array = array())
   {
      foreach ($array as $sectionName => $section) {
         $this->_values[$sectionName] = array(
            'count' => 0
         );

         foreach ($section as $secName => $secContent) {
            if (is_array($secContent) === true) {
               if (count($secContent) > 0) {
                  foreach ($secContent as $i => $c) {
                     if (is_int($i) === true) {
                        $this->_values[$sectionName][$secName][$c] = 0;
                     } else {
                        $this->_values[$sectionName][$secName][$i] = $c;
                     }
                  }
               } else {
                  $this->_values[$sectionName][$secName] = array();
               }
            } else {
                $this->_values[$sectionName][$secContent] = 0;
            }
         }
      }

      return $this;
   }


   protected function _bringInOrder($table, $klasse) {
      $data = sql_query_array($this->_db, "SELECT code, bez FROM $table WHERE klasse = '$klasse'");
      $order = array();
      foreach ($data as $argh) {
         $order[reset($argh)] = end($argh);
      }

      return $order;
   }


   protected function _translateLookup($value, $klasse, $table = 'l_basic')
   {
        $lookups = $this->_lookupCache[$klasse] = array_key_exists($klasse, $this->_lookupCache)
            ? $this->_lookupCache[$klasse]
            : $this->_bringInOrder($table, $klasse)
        ;

        return array_key_exists($value, $lookups) ? $lookups[$value] : $value;
   }

    /**
     * no one step anymore, take first findable
     *
     */
    protected function _rezidivOneStepCheck()
    {
       $s = "
       ts.erkrankung_id = t.erkrankung_id AND
       ts.datum_sicherung > t.datum_sicherung AND
       IF(
          LEFT(t.anlass, 1) = 'p',
          LEFT(ts.anlass, 1) = 'r',
          IF(
             LEFT(t.anlass,1) = 'r',
             SUBSTRING(ts.anlass , -2) > SUBSTRING(t.anlass , -2),
             ts.anlass = t.anlass
          )
       )";

       return $s;
    }



   protected function _createFpdi($pdfRessource, $fields )
   {
      $pdf = $this->_renderer->getFPDI();

      $count_page = $pdf->setSourceFile($pdfRessource);
      $last_page = 0;
      $last_counter = 0;

      foreach( $fields AS $fld ) {
         // Required
         $page      = $fld['page'];
         $x         = $fld['x'];
         $y         = $fld['y'];

         $html_encode = get_html_translation_table( HTML_ENTITIES );
         foreach( $html_encode AS $k => $v ) {
            $html_decode[ $v ] = $k;
         }
         $db_field = utf8_encode(strip_tags( strtr( $fld[ 'db_field' ], $html_decode )));

         // Optional
         $counter   = isset( $fld[ 'counter' ] )   ? $fld[ 'counter' ]             : 0;
         $chk_param = isset( $fld[ 'chk_param' ] ) ? $fld[ 'chk_param' ]           : '';
         $width     = isset( $fld[ 'width' ] )     ? $fld[ 'width' ]               : 100;
         $height    = isset( $fld[ 'height' ] )    ? $fld[ 'height' ]              : 10;
         $align     = isset( $fld[ 'align' ] )     ? strtoupper( $fld[ 'align' ] ) : 'L';
         $font      = isset( $fld[ 'font' ] )      ? $fld[ 'font' ]                : 'helvetica';
         $font_size = isset( $fld[ 'size' ] )      ? $fld[ 'size' ]                : 8;
         $function  = isset( $fld[ 'function' ] )  ? $fld[ 'function' ]            : '';
         $memo      = isset( $fld[ 'memo' ] )      ? $fld[ 'memo' ]                : '';
         $style     = isset( $fld[ 'style' ] )     ? $fld[ 'style' ]               : '';
         $border    = isset( $fld[ 'border' ] )    ? $fld[ 'border' ]              : 0;
         $arr_fill  = isset( $fld[ 'fill' ] )      ? $fld[ 'fill' ]                : false;
         $fill      = is_array( $arr_fill )        ? true                          : false;

         if ( strlen( $chk_param ) ) {
            $db_field = ( $db_field == $chk_param ) ? 'X' : '';
         }

         if ( $last_page != $page OR ( $last_counter != $counter AND $counter != 0 ) ) {
            $tplidx = $pdf->ImportPage( $page );
            $pdf->addPage();

            if ( isset( $rotation ) ) {
               $pdf->rotate( $rotation, 420, 410 );
            }

            $pdf->useTemplate( $tplidx );

            if ( isset( $rotation ) ) {
               $pdf->rotate( 0 );
            }
         }

         $pdf->SetFont( $font, $style, $font_size );

         // Funktion zum Ändern des Layouts oder ähnlichem.
         if ( strlen( $function ) ) {
            $function( $pdf );
         }

         if ( $memo === true ) {
            $memo = 'multi';
         }

         if ( $fill == true ) {
            $arr_fill['r'] = isset( $arr_fill['r'] ) ? $arr_fill['r'] : 255;
            $arr_fill['g'] = isset( $arr_fill['g'] ) ? $arr_fill['g'] : 255;
            $arr_fill['b'] = isset( $arr_fill['b'] ) ? $arr_fill['b'] : 255;

            $pdf->SetFillColor( $arr_fill['r'], $arr_fill['g'], $arr_fill['b'] );
         }

         switch( $memo ) {
            case 'multi':
               $pdf->SetXY( $x, $y );
               $pdf->MultiCell( $width, $height, $db_field, $border, $align, $fill );
               $pdf->SetXY( 0, 0 );
            break;

            case 'single':
               while( $pdf->GetStringWidth( $db_field ) >= $width )
                 $db_field = substr( $db_field, 0, -1 );

               $pdf->Text( $x, $y, $db_field );
            break;

            default:
               $pdf->Text( $x, $y, $db_field );
            break;
         }

         $last_page    = $page;
         $last_counter = $counter;

         if ( $fill == true ) {
            $pdf->SetFillColor( 255, 255, 255 );
         }
      }
   }


    /**
     * setTemplate
     *
     * @access  protected
     * @param   string  $file
     * @param   string $fileType
     * @return  customReport
     */
    protected function setTemplate($file, $fileType = 'odt')
    {
        $type   = $this->_type;
        $subDir = $this->_subDir;

        $this->_template = "reports/{$type}/{$subDir}/{$file}.{$fileType}";

        return $this;
    }
}

?>
