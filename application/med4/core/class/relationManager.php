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

class relationManager
{
    /**
     * _instance
     *
     * @access  private
     * @var     relationManager
     */
    private static $_instance;


    /**
     * _db
     *
     * @access  private
     * @var     resource
     */
    private $_db;


    /**
     * _all tables in db
     *
     * @access  private
     * @var     array
     */
    private $_all = array();


    /**
     * patient tables
     *
     * @access  private
     * @var     array
     */
    private $_patient = array(
        'patient',
        'aufenthalt',
        'behandler',
        'abschluss',
        'erkrankung',
        'status',
        'lock',
        'foto_patient'
    );


    /**
     * erkrankung
     *
     * @access  private
     * @var     array
     */
    private $_erkrankung = array(
        'erkrankung',
        'anamnese',
        'untersuchung',
        'labor',
        'diagnose',
        'eingriff',
        'komplikation',
        'histologie',
        'zytologie',
        'tumorstatus',
        'konferenz_patient',
        'therapieplan',
        'therapieplan_abweichung',
        'therapie_systemisch',
        'therapie_systemisch_zyklus',
        'therapie_systemisch_zyklustag',
        'strahlentherapie',
        'sonstige_therapie',
        'nebenwirkung',
        'begleitmedikation',
        'studie',
        'beratung',
        'foto',
        'brief',
        'termin',
        'ekr',
        'fragebogen',
        'dmp_brustkrebs_eb',
        'dmp_brustkrebs_fb',
        'dmp_brustkrebs_ed_2013',
        'dmp_brustkrebs_ed_pnp_2013',
        'dmp_brustkrebs_fd_2013',
        'qs_18_1_b',
        'qs_18_1',
        'qs_18_1_brust',
        'qs_18_1_o',
        'nachsorge',
        'erkrankung_table',
        'erkrankung_tree',
        'lock',
        'zweitmeinung',
        'dokument'
    );


    /**
     * _dlist
     *
     * @access  private
     * @var     array
     */
    private $_dlist = array(
        'anamnese_erkrankung',
        'anamnese_familie',
        'eingriff_ops',
        'erkrankung_synchron',
        'nachsorge_erkrankung',
        'histologie_einzel',
        'labor_wert',
        'therapie_systemisch_zyklustag_wirkstoff',
        'tumorstatus_metastasen',
        'untersuchung_lokalisation',
        'zytologie_aberration',
        'brief_empfaenger',
        'fragebogen_frage',
        'abschluss_ursache'
    );




    /**
     * _picker
     *
     * @access  private
     * @var     array
     */
    private $_picker = array(
        'code_icd',
        'code_icd_gruppen',
        'code_icd_untergruppe',
        'code_icd_suche',
        'code_icd_vorauswahl',
        'code_ops',
        'code_ops_gruppen',
        'code_ops_untergruppe',
        'code_ops_suche',
        'code_ops_vorauswahl',
        'code_o3',
        'code_o3_gruppen',
        'code_o3_untergruppe',
        'code_o3_suche',
        'code_o3_vorauswahl',
        'code_nci',
        'code_nci_gruppe',
        'code_nci_suche',
        'code_ktst',
        'code_ktst_suche',
        'picker.arzt',
        'picker.user',
        'picker.vorlage',
        'picker.query',
        'picker.qs'
    );


    /**
     * _ext forms
     *
     * @access  private
     * @var     array
     */
    private $_ext = array(
        'login',
        'rollenauswahl',
        'extras',
        'vorlagen',
        'konferenz_archiv',
        'user_setup',
        'status',
        'delete',
        'confirm',
        'impressum',
        'export_dmp',
        'dmp_nummern',
        'dmp_nummern_2013',
        'dmp_popups',
        'dmp_2013_popups',
        'export_gekid',
        'export_wbc',
        'export_krbw',
        'export_ekrrp',
        'export_csv',
        'export_wdc',
        'export_gkr',
        'export_eusoma',
        'export_onkonet',
        'export_krhe',
        'export_qsmed',
        'export_krbw_ng',
        'export_onkeyline',
        'export_patho',
        'import_patho'
    );


    /**
     * _date fields in form
     *
     * @access  private
     * @var     array
     */
    private $_date = array(
        'datum' => array(
            'anamnese',
            'untersuchung',
            'labor',
            'diagnose',
            'eingriff',
            'komplikation',
            'histologie',
            'zytologie',
            'therapieplan',
            'therapieplan_abweichung',
            'beratung',
            'foto',
            'dokument',
            'brief',
            'termin',
            'ekr',
            'fragebogen',
            'nachsorge'
        ),
        'datum_sicherung' => array(
            'tumorstatus',
        ),
        'beginn' => array(
            'therapie_systemisch',
            'nebenwirkung',
            'strahlentherapie',
            'begleitmedikation',
            'studie'
        ),
        'doku_datum' => array(
            'dmp_brustkrebs_eb',
            'dmp_brustkrebs_ed_2013'
        )
    );


    /**
     * _init
     *
     * @access  private
     * @return     void
     */
    private function _init()
    {
        foreach (sql_query_array($this->_db, 'SHOW TABLES') as $tmpDbTable) {
            $this->_all[] = reset($tmpDbTable);
        }
    }


    /**
     * getInstance
     *
     * @access  private
     * @return  relationManager
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }


    /**
     * setDB
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @return  void
     */
    public static function setDB($db)
    {
        $rm = self::getInstance();

        $rm->_db = $db;
        $rm->_init();
    }


    /**
     * tableExists
     *
     * @static
     * @access  public
     * @param   string  $table
     * @return  bool
     */
    public static function tableExists($table)
    {
        $rm = self::getInstance();

        return in_array($table, $rm->_all);
    }


    /**
     * get tables
     * use filter for not wanted tables
     *
     * @static
     * @access  public
     * @param   string  $type
     * @param   array   $filter
     * @return  array
     */
    public static function get($type = 'all', $filter = array())
    {
        $relationManager = self::getInstance();

        $tables = array_unique($relationManager->{"_$type"});

        foreach ($filter as $f) {
            foreach ($tables as $i => $tableName) {
                if (str_starts_with($tableName, $f) === true) {
                    unset($tables[$i]);
                }
            }
        }

        sort($tables);

        return $tables;
    }


   /**
    * TODO
    */
   public static function getPatientMainForms($dbForms = false)
   {
       $rm = self::getInstance();

       $reduce = $dbForms == false
          ? array('erkrankung', 'qs_18_1', 'qs_18_1_brust', 'qs_18_1_o', 'erkrankung_table', 'erkrankung_tree', 'lock')
          : array('erkrankung_table', 'erkrankung_tree', 'lock', 'qs_18_1')
       ;

       $tmp = array(
           'patient' => arr_reduce(
               $rm->get('patient'),
               //Todo diese Formulare müssen sich selber ausschließen!!
               array('patient', 'erkrankung', 'status', 'lock', 'foto_patient')
           ),
           'erkrankung' => arr_reduce(
               $rm->get('erkrankung'),
               //Todo diese Formulare müssen sich selber ausschließen!!
               $reduce
           )
       );

       $return = array('patient' => array(), 'erkrankung' => array());

       foreach ($tmp as $section => $forms) {
           foreach ($forms as $form) {
               $return[$section][$form] = null;
           }
       }

       return $return;
   }

   public static function add($type, $tables)
   {
      $relationManager = self::getInstance();

      $tables = is_array($tables) === false ? array($tables) : $tables;

      foreach ($tables as $table) {
         $relationManager->{"_$type"}[] = $table;
      }

      return $relationManager;
   }


   public static function date($form = null)
   {
      $return = null;

      if ($form !== null && strlen($form) > 0) {
         $relationManager = self::getInstance();

         foreach ($relationManager->_date as $dateField => $forms) {
            if (in_array($form, $forms) === true) {

               $return = $dateField;
               break;
            }
         }
      }

      return $return;
   }

}

?>
