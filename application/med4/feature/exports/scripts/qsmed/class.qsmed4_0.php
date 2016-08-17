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

umask( 0002 );

// ZIP Library laden
require_once( DIR_LIB .'/zip/pclzip.lib.php');
require_once( getcwd() . '/feature/exports/scripts/class.medbaseexport.php' );
require_once(getcwd() . '/feature/export/history/class.historymanager.php');
require_once(getcwd() . '/feature/export/history/class.history.php');

class Cqsmed4_0 extends CMedBaseExport
{
    protected $_exportDatasets = array();

    protected $_exportFormBaseIds  = array(0);
    protected $_exportFormBrustIds = array(0);
    protected $_exportFormOpIds    = array(0);

    protected $_map = array();

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( '../feature/exports/configs/qsmed.conf', 'export_qsmed' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#GetVersion()
    */
   public function GetVersion()
   {
      return "versionBasedOnIntakeDate";
   }

   public function GetExportPath( $export_sub_dir, $login_name )
   {
       return "material/{$login_name}/{$export_sub_dir}/";
   }


   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#CreateExportFilter()
    */
   protected function CreateExportFilter( $session, $request )
   {
      $export_filter = array();

      $export_filter['user_id']     = isset($session['sess_user_id'])   ? $session['sess_user_id']    : null;
      $export_filter['login_name']  = isset($session['sess_loginname']) ? $session['sess_loginname']  : null;
      $export_filter['org_id']      = isset($session['sess_org_id'])    ? $session['sess_org_id']     : 0;
      $export_filter['format_date'] = '%Y%d%m';
      $export_filter['format_date_app'] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';

      $this->_loadExportSettings($export_filter, 'qsmed');

      $this->_export_path = $this->GetExportPath('qsmed', $export_filter[ 'login_name' ]);

      if (file_exists($this->_export_path) === true) {
          $this->DeleteDirectory($this->_export_path);
      }

      $this->_csv_dir = $this->_export_path . 'csv/';
      $this->_zip_dir = $this->_export_path . 'zip/';

      // Pfade anlegen
      $this->createPath($this->_csv_dir);
      $this->createPath($this->_zip_dir);

      // Formular Daten holen
      $export_filter['von'] = isset( $request[ 'sel_datum_von' ] ) ? todate( $request[ 'sel_datum_von' ], 'en' ) : '';
      $export_filter['bis'] = isset( $request[ 'sel_datum_bis' ] ) ? todate( $request[ 'sel_datum_bis' ], 'en' ) : '';

      return $export_filter;
   }

   public function Export( $session, $request )
   {
       $export_filter = $this->CreateExportFilter( $session, $request );
       $content = $this->CreateContent( $export_filter );
       if (count( $content ) > 0) {
           return $this->WriteContent($content, $export_filter);
       }
       return false;
   }


   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#WriteContent()
    */
   protected function WriteContent($content, $export_filter)
   {
      $files = array();

      foreach ($content as $section => $sectionVersions) {
          foreach ($sectionVersions as $sectionVersion => $sectionDatasets) {
              $files[] = $csvFile = $this->_csv_dir . "alcedis_med_v{$sectionVersion}_18_1_{$section}.csv";

              $fields = array();

              $keys = array_keys(reset($sectionDatasets));

              foreach ($keys as $key) {
                  $fields[] = array(
                      'Field' => $key,
                      'Type' => 'char'
                  );
              }

              $this->WriteCsvFile($csvFile, $fields, $sectionDatasets);
          }
      }

      $zipFile = $this->_zip_dir . 'exp_qsmed_' . date( 'YmdHis' ) . '.zip';

      $zip = new PclZip($zipFile);
      $zip_create = $zip->create($files, PCLZIP_OPT_REMOVE_ALL_PATH);

       // History erstellen
       $historyManager = CHistoryManager::getInstance();
       $historyManager->initialise($this->_db, $this->_smarty);
       $history = $historyManager->createHistory();
       $history->setExportLogId(0);
       $history->setExportName('qsmed');
       $history->setOrgId($export_filter['org_id']);
       $history->setUserId($export_filter['user_id']);
       $history->setDate(date('Ymd', time()));
       $history->addFilter('von', $export_filter['von']);
       $history->addFilter('bis', $export_filter['bis']);
       $history->setFiles(
           array(
               $zipFile
           )
       );
       $historyManager->insertHistory($history);

      // Template Variablen
      $this->_smarty->assign('filename', basename($zipFile));

      return true;
   }

   /**
    * (non-PHPdoc)
    * @see feature/exports/scripts/CMedBaseExport#ExtractData()
    */
   protected function ExtractData($export_filter)
   {
      $erkrankungIds = $this->_getErkrankungIds($export_filter['org_id']);

      // Wenn keine Daten verfügbar sind, hier raus springen
      if ($erkrankungIds === null) {
          return array();
      }

      $this->_getBaseDatasets($export_filter, $erkrankungIds);

      if (count($this->_exportDatasets) === 0) {
          return array();
      }

      return $this
          ->_getBrustDatasets()
          ->_getOpDatasets()
          ->_prepareDatasets()
      ;
   }

   /**
    * collect erkrankungIds by OrgId
    *
    * @param unknown_type $orgId
    * @return NULL
    */
   protected function _getErkrankungIds($orgId)
   {
       $query = "
           SELECT
               GROUP_CONCAT(DISTINCT e.erkrankung_id) as 'erkrankung_id'
           FROM patient p
               INNER JOIN erkrankung e ON p.patient_id = e.patient_id AND e.erkrankung = 'b'
           WHERE
               p.org_id = '{$orgId}'
           GROUP BY
               p.org_id
       ";

       $erkrankungIds = sql_query_array($this->_db, $query);

       return (count($erkrankungIds) > 0 ? reset(reset($erkrankungIds)) : null);
   }


   /**
    * brings the export data to 1 level
    *
    * @return array
    */
   protected function _prepareDatasets()
   {
       $datasets = array('base' => array(), 'brust' => array(), 'op' => array());

       if (count($this->_exportDatasets) == 0) {
           return $this->_exportDatasets;
       }

       foreach ($this->_exportDatasets as $exportDataset) {
           $version = $exportDataset['qsversion'];


           foreach ($exportDataset['formBrust'] as $exportBrustDataset) {
               foreach ($exportBrustDataset['formOp'] as $exportBrustOpDataset) {
                    $datasets['op'][$version][] = $exportBrustOpDataset;
               }

               unset($exportBrustDataset['formOp']);

               $datasets['brust'][$version][] = $exportBrustDataset;
           }

           unset(
              $exportDataset['formBrust'],
              $exportDataset['qsversion']
           );

           $datasets['base'][$version][] = $exportDataset;
       }

       return $datasets;
   }



   /**
    * retreive base datasets
    *
    * @param unknown_type $export_filter
    * @param unknown_type $erkrankungIds
    * @return Cqsmed2013_0
    */
   protected function _getBaseDatasets($export_filter, $erkrankungIds)
   {
       $query = "
          SELECT
              base.qs_18_1_b_id,
              base.idnrpat                                                         AS 'UID',
              '18/1'                                                               AS 'MODUL',
              CONCAT_WS(', ', p.nachname, p.vorname)                               AS 'NAME',
              o.ik_nr                                                              AS 'IKNRKH',
              '{$export_filter['standort']}'                                       AS 'STANDORT',
              '{$export_filter['bsnr']}'                                           AS 'BSNR',
              '{$export_filter['abtnr']}'                                          AS 'ABTNR',
              '{$export_filter['fachabt']}'                                        AS 'FACHABT',
              p.patient_nr                                                         AS 'IDNRPAT',
              DATE_FORMAT(p.geburtsdatum, '%d.%m.%Y')                              AS 'GEBDATUM',
              p.geschlecht                                                         AS 'GESCHLECHT',
              DATE_FORMAT(base.aufndatum, '%d.%m.%Y')                              AS 'AUFNDATUM',
              base.aufndiag_1                                                      AS 'AUFNDIAG_1',
              base.aufndiag_2                                                      AS 'AUFNDIAG_2',
              base.aufndiag_3                                                      AS 'AUFNDIAG_3',
              base.aufndiag_4                                                      AS 'AUFNDIAG_4',
              base.aufndiag_5                                                      AS 'AUFNDIAG_5',
              base.asa                                                             AS 'ASA',
              base.adjutherapieplanung                                             AS 'ADJUTHERAPIEPLANUNG',
              base.planbesprochen                                                  AS 'PLANBESPROCHEN',
              DATE_FORMAT(base.planbesprochendatum, '%d.%m.%Y')                    AS 'PLANBESPROCHENDATUM',
              base.meldungkrebsregister                                            AS 'MELDUNGKREBSREGISTER',
              DATE_FORMAT(base.entldatum, '%d.%m.%Y')                              AS 'ENTLDATUM',
              base.entldiag_1                                                      AS 'ENTLDIAG_1',
              base.entldiag_2                                                      AS 'ENTLDIAG_2',
              base.entldiag_3                                                      AS 'ENTLDIAG_3',
              base.entlgrund                                                       AS 'ENTLGRUND',
              base.sektion                                                         AS 'SEKTION',
              base.aufndatum                                                       AS 'raw_aufndatum',
              IF(YEAR(base.aufndatum) < 2013,
                (-1997 + YEAR(base.aufndatum)),
                YEAR(base.aufndatum)
              )                                                                    AS 'qsversion'
          FROM qs_18_1_b base
              ## Nur Datensaetze verarbeiten wo mindestens ein brust und ein dazugehoeriges op formular vorhanden sind
               INNER JOIN qs_18_1_brust br ON base.qs_18_1_b_id = br.qs_18_1_b_id AND br.freigabe IS NOT NULL
                  INNER JOIN qs_18_1_o op ON op.qs_18_1_brust_id = br.qs_18_1_brust_id AND op.freigabe IS NOT NULL

              INNER JOIN patient p ON p.patient_id = base.patient_id
                  INNER JOIN org o ON o.org_id = p.org_id
          WHERE
              base.erkrankung_id IN ({$erkrankungIds}) AND
              base.freigabe IS NOT NULL
          GROUP BY
              base.qs_18_1_b_id
          HAVING raw_aufndatum BETWEEN '{$export_filter['von']}' AND '{$export_filter['bis']}'
      ";

      foreach (sql_query_array($this->_db, $query) AS $dataset) {
          $key = $dataset['qs_18_1_b_id'];

          unset($dataset['qs_18_1_b_id']);
          unset($dataset['raw_aufndatum']);

          $dataset['formBrust'] = array();

          //Mappings
          $dataset['GESCHLECHT'] = $this->_map('geschlecht', $dataset['GESCHLECHT']);

          $this->_exportDatasets[$key] = $dataset;
      }

      $this->_exportFormBaseIds = array_keys($this->_exportDatasets);

      return $this;
   }


   /**
    * retreive brust datasets
    *
    * @return Cqsmed2013_0
    */
   protected function _getBrustDatasets()
   {
      $in = implode(',', $this->_exportFormBaseIds);

      $query = "
         SELECT
            br.qs_18_1_brust_id,
            br.qs_18_1_b_id,
            'MED_FROM_BASE'                                         AS 'UID',
            '18/1BRUST'                                             AS 'MODUL',
            'MED_FROM_BASE'                                         AS 'NAME',
            'MED_FROM_BASE'                                         AS 'ABTNR',
            'MED_FROM_BASE'                                         AS 'IDNRPAT',
            br.zuopseite                                            AS 'ZUOPSEITE',
            br.tastbarmammabefund                                   AS 'TASTBARMAMMABEFUND',
            br.praehistbefund                                       AS 'PRAEHISTBEFUND',
            DATE_FORMAT(br.ausganghistbefund, '%d.%m.%Y')           AS 'AUSGANGHISTBEFUND',
            br.praeoptumorth                                        AS 'PRAEOPTUMORTH',
            br.pokomplikatspez                                      AS 'POKOMPLIKATSPEZ',
            br.pokowundinfektion                                    AS 'POKOWUNDINFEKTION',
            br.nachblutung                                          AS 'NACHBLUTUNG',
            br.serom                                                AS 'SEROM',
            br.pokosonst                                            AS 'POKOSONST',
            br.posthistbefund                                       AS 'POSTHISTBEFUND',
            br.tnmptmamma                                           AS 'TNMPTMAMMA',
            br.tnmpnmamma                                           AS 'TNMPNMAMMA',
            br.tnmgmamma                                            AS 'TNMGMAMMA',
            br.anzahllypmphknoten                                   AS 'ANZAHLLYPMPHKNOTEN',
            br.rezeptorstatus                                       AS 'REZEPTORSTATUS',
            br.multizentrizitaet                                    AS 'MULTIZENTRIZITAET',
            br.gesamttumorgroesse                                   AS 'GESAMTTUMORGROESSE',
            br.angabensicherabstand                                 AS 'ANGABENSICHERABSTAND',
            br.sicherabstand                                        AS 'SICHERABSTAND',
            br.mnachstaging                                         AS 'MNACHSTAGING',
            br.arterkrank                                           AS 'ARTERKRANK',
            br.erstoffeingriff                                      AS 'ERSTOFFEINGRIFF',
            br.anlasstumordiag                                      AS 'ANLASSTUMORDIAG',
            br.anlasstumordiageigen                                 AS 'ANLASSTUMORDIAGEIGEN',
            br.anlasstumordiagfrueh                                 AS 'ANLASSTUMORDIAGFRUEH',
            br.mammographiescreening                                AS 'MAMMOGRAPHIESCREENING',
            br.anlasstumordiagsympt                                 AS 'ANLASSTUMORDIAGSYMPT',
            br.anlasstumordiagnachsorge                             AS 'ANLASSTUMORDIAGNACHSORGE',
            br.anlasstumordiagsonst                                 AS 'ANLASSTUMORDIAGSONST',
            br.praehistdiagsicherung                                AS 'PRAEHISTDIAGSICHERUNG',
            br.praeicdo3                                            AS 'PRAEICDO3',
            br.praethinterdisztherapieplan                          AS 'PRAETHINTERDISZTHERAPIEPLAN',
            DATE_FORMAT(br.datumtherapieplan, '%d.%m.%Y')           AS 'DATUMTHERAPIEPLAN',
            br.systchemoth                                          AS 'SYSTCHEMOTH',
            br.endokrinth                                           AS 'ENDOKRINTH',
            br.spezifantiktherapie                                  AS 'SPEZIFANTIKTHERAPIE',
            br.strahlenth                                           AS 'STRAHLENTH',
            br.sonstth                                              AS 'SONSTTH',
            br.posticdo3                                            AS 'POSTICDO3',
            br.optherapieende                                       AS 'OPTHERAPIEENDE',
            br.tumortherapieempf                                    AS 'TUMORTHERAPIEEMPF',
            br.anzahllypmphknotenunb                                AS 'ANZAHLLYPMPHKNOTENUNB',
            br.graddcis                                             AS 'GRADDCIS',
            br.her2neustatus                                        AS 'HER2NEUSTATUS',
            br.axilladissektion                                     AS 'AXILLADISSEKTION',
            br.axlkentfomark                                        AS 'AXLKENTFOMARK',
            br.slkbiopsie                                           AS 'SLKBIOPSIE',
            br.radionuklidmarkierung                                AS 'RADIONUKLIDMARKIERUNG',
            br.farbmarkierung                                       AS 'FARBMARKIERUNG',
            br.bet                                                  AS 'BET'
         FROM qs_18_1_brust br
            INNER JOIN qs_18_1_o op ON op.qs_18_1_brust_id = br.qs_18_1_brust_id AND op.freigabe IS NOT NULL
         WHERE
            br.qs_18_1_b_id IN ({$in}) AND
            br.freigabe IS NOT NULL
         GROUP BY br.qs_18_1_brust_id
      ";

      foreach (sql_query_array($this->_db, $query) as $dataset) {
         $key  = $dataset['qs_18_1_brust_id'];
         $bKey = $dataset['qs_18_1_b_id'];

         unset(
            $dataset['qs_18_1_b_id'],
            $dataset['qs_18_1_brust_id']
         );

         foreach ($dataset as $fieldName => $fieldValue) {
            if ($fieldValue === 'MED_FROM_BASE') {
                $dataset[$fieldName] = $this->_exportDatasets[$bKey][$fieldName];
            }
         }

         $this->_exportFormBrustIds[] = $key;

         //Mappings
         $dataset['ZUOPSEITE'] = $this->_map('zuopseite', $dataset['ZUOPSEITE']);

         //ProcID setzen
         $dataset['ProcID'] = count($this->_exportDatasets[$bKey]['formBrust']) + 1;
         $dataset['formOp'] = array();

         $this->_exportDatasets[$bKey]['formBrust'][$key] = $dataset;
      }

      return $this;
   }


   /**
    * retreive op datasets
    *
    * @return Cqsmed4_0
    */
   protected function _getOpDatasets()
   {
      $in = implode(',', $this->_exportFormBrustIds);

      $query = "
         SELECT
            qs_18_1_o_id,
            qs_18_1_brust_id,
            qs_18_1_b_id,
            'MED_FROM_BASE'                                      AS 'UID',
            '18/1O'                                              AS 'MODUL',
            'MED_FROM_BASE'                                      AS 'NAME',
            'MED_FROM_BASE'                                      AS 'ABTNR',
            'MED_FROM_BASE'                                      AS 'IDNRPAT',
            'MED_FROM_BRUST'                                     AS 'ZUOPSEITE',
            lfdnreingriff                                        AS 'LFDNREINGRIFF',
            diagoffbiopsie                                       AS 'DIAGOFFBIOPSIE',
            DATE_FORMAT(opdatum, '%d.%m.%Y')                     AS 'OPDATUM',
            CONCAT_WS(':', opschluessel_1, opschluessel_1_seite) AS 'OPSCHLUESSEL_1',
            CONCAT_WS(':', opschluessel_2, opschluessel_2_seite) AS 'OPSCHLUESSEL_2',
            CONCAT_WS(':', opschluessel_3, opschluessel_3_seite) AS 'OPSCHLUESSEL_3',
            CONCAT_WS(':', opschluessel_4, opschluessel_4_seite) AS 'OPSCHLUESSEL_4',
            CONCAT_WS(':', opschluessel_5, opschluessel_5_seite) AS 'OPSCHLUESSEL_5',
            CONCAT_WS(':', opschluessel_6, opschluessel_6_seite) AS 'OPSCHLUESSEL_6',
            antibioprph                                          AS 'ANTIBIOPRPH',
            praeopmarkierung                                     AS 'PRAEOPMARKIERUNG',
            praeopmammographiejl                                 AS 'PRAEOPMAMMOGRAPHIEJL',
            intraoppraeparatroentgen                             AS 'INTRAOPPRAEPARATROENTGEN',
            praeopsonographiejl                                  AS 'PRAEOPSONOGRAPHIEJL',
            intraoppraeparatsono                                 AS 'INTRAOPPRAEPARATSONO',
            praeopmrtjl                                          AS 'PRAEOPMRTJL',
            sentinellkeingriff                                   AS 'SENTINELLKEINGRIFF'
         FROM qs_18_1_o
         WHERE
            qs_18_1_brust_id IN ({$in}) AND
            freigabe IS NOT NULL
         ORDER BY
            qs_18_1_brust_id, lfdnreingriff
      ";

      foreach (sql_query_array($this->_db, $query) as $dataset) {
          $key      = $dataset['qs_18_1_o_id'];
          $bKey     = $dataset['qs_18_1_b_id'];
          $brustKey = $dataset['qs_18_1_brust_id'];

          unset(
             $dataset['qs_18_1_b_id'],
             $dataset['qs_18_1_o_id'],
             $dataset['qs_18_1_brust_id']
          );

          foreach ($dataset as $fieldName => $fieldValue) {
              if ($fieldValue === 'MED_FROM_BASE') {
                  $dataset[$fieldName] = $this->_exportDatasets[$bKey][$fieldName];
              } elseif ($fieldValue === 'MED_FROM_BRUST') {
                  $dataset[$fieldName] = $this->_exportDatasets[$bKey]['formBrust'][$brustKey][$fieldName];
              }
          }

          $this->_exportFormOpIds[] = $key;

          //ProcID setzen
          $dataset['ProcID'] = count($this->_exportDatasets[$bKey]['formBrust'][$brustKey]['formOp']) + 1;

          $this->_exportDatasets[$bKey]['formBrust'][$brustKey]['formOp'][$key] = $dataset;
      }

      return $this;
   }

   protected function _map($class = null, $val = null)
   {
       $value = NULL;

       if ($class !== null && $val !== null) {
           if (isset($this->_map[$class]) === false) {
               $results = sql_query_array($this->_db, "SELECT code_med, code_qsmed FROM l_exp_qsmed WHERE klasse = '{$class}'");

               foreach ($results as $result) {
                   $this->_map[$class][$result['code_med']] = $result['code_qsmed'];
               }
           }

           $value = $this->_map[$class][$val];
       }

       return $value;
   }
}

?>
