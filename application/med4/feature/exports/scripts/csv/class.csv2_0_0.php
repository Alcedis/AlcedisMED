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

require_once( getcwd() . '/feature/exports/scripts/class.medbaseexport.php' );


class CCsv2_0_0 extends CMedBaseExport
{
    /**
     * _orgId
     *
     * @access  protected
     * @var     int
     */
    protected $_orgId;


    /**
     * _disease
     *
     * @access  protected
     * @var     string
     */
    protected $_disease;


    /**
     * patients cache
     *
     * @access  protected
     * @var     array
     */
    protected $_patients;


    /**
     * diseaseIds cache
     *
     * @access  protected
     * @var     array
     */
    protected $_diseaseIds;


    /**
     * getRequiredTables
     *
     * @static
     * @access  public
     * @return  array
     */
    public static function getRequiredTables()
    {
        $tables = relationManager::get('all', array(
            '_',
            'report_time',
            'email',
            'exp_', 'export',
            'hl7_',
            'l_exp',
            'settings',
            'user_',
            'patho_item',
            'status',
            'recht',
            'history',
            'l_',
            'dmp_nummern',
            'dmp_counter',
            'dmp_version',
            'nachsorge_erkrankung' // special case
        ));

        $tables[] = 'l_basic';

        return $tables;
    }


   /**
    * @see feature/exports/scripts/CMedBaseExport#Init()
    */
   protected function Init()
   {
      $this->_smarty->config_load( 'settings/interfaces.conf' );
      $this->_smarty->config_load( 'app/export_csv.conf', 'export_csv' );
      $this->_smarty->config_load(FILE_CONFIG_APP);
      $this->_config = $this->_smarty->get_config_vars();
   }


   /**
    * @see feature/exports/scripts/CMedBaseExport#GetVersion()
    */
    public function GetVersion()
    {
        return "2.0.0";
    }


    /**
     * @see feature/exports/scripts/CMedBaseExport#Export()
     */
    public function Export($session, $request)
    {
        $export_filter = array();
        $export_filter[ 'org_id' ] = $session[ 'sess_org_id' ];
        $export_filter[ 'login_name' ] = isset( $session[ 'sess_loginname' ] ) ? $session[ 'sess_loginname' ] : '';
        $export_filter[ 'format_date' ] = isset( $session[ 'sess_format_date' ] ) ? $session[ 'sess_format_date' ] : 'd.%m.%Y';
        $export_filter[ 'newline' ] = "\r\n";
        $export_filter[ 'quote' ] = isset( $this->_config[ 'exp_csv_quote' ] ) ? $this->_config[ 'exp_csv_quote' ] : '"';
        $export_filter[ 'separator' ] = isset( $this->_config[ 'exp_csv_separator' ] ) ? $this->_config[ 'exp_csv_separator' ] : ';';
        $export_filter[ 'file_suffix' ] = isset( $this->_config[ 'exp_csv_file_suffix' ] ) ? $this->_config[ 'exp_csv_file_suffix' ] : '.csv';
        $export_filter[ 'sel_tabelle' ] = isset( $request[ 'sel_tabelle' ] )  ? $request[ 'sel_tabelle' ] : 'all';
        $export_filter[ 'sel_erkrankung' ] = isset( $request[ 'sel_erkrankung' ] )  ? $request[ 'sel_erkrankung' ] : 'all';

        $ext_dir = isset( $this->_config[ 'exp_csv_dir' ] ) ? $this->_config[ 'exp_csv_dir' ] : 'csv/';
        $this->_export_path = $this->GetExportPath( $ext_dir, $export_filter[ 'login_name' ] );

        if (file_exists($this->_export_path)) {
            $this->DeleteDirectory($this->_export_path);
        }

        $this->createPath($this->_export_path);

        $this
            ->setOrgId($export_filter['org_id'])
            ->setDisease($export_filter['sel_erkrankung'])
        ;

        if ($export_filter['sel_tabelle'] === 'all') {
            return $this->ExportAllCsv($this->_export_path, $export_filter);
        } else {
            return $this->ExportCsv($this->_export_path, $export_filter);
        }
    }


    /**
     * ExportCsv
     *
     * @access  protected
     * @param   string  $path
     * @param   array   $export_filter
     * @return  array
     */
    protected function ExportCsv($path, $export_filter)
    {
        $exports = array('valid' => array(), 'invalid' => array());

        $table = $export_filter['sel_tabelle'];

        $this->ExportTable($table, $path, $export_filter[ 'file_suffix' ], $exports);

        return $exports;
   }

   /**
    * ExportAllCsv
    *
    * @param    string  $path
    * @param    array   $export_filter
    * @return   array
    */
    protected function ExportAllCsv($path, $export_filter)
    {
        $exports = array('valid' => array(), 'invalid' => array());

        foreach (self::getRequiredTables() as $table) {
            $this->ExportTable($table, $path, $export_filter[ 'file_suffix' ], $exports);
        }

        return $exports;
    }


    /**
     * ExportTable
     *
     * @access  protected
     * @param   string  $table
     * @param   string  $path
     * @param   string  $file_suffix
     * @param   array   $exports
     * @return  void
     */
    protected function ExportTable($table, $path, $file_suffix, &$exports)
    {
        $hasPatientId = false;
        $hasDiseaseId = false;

        $fields   = sql_query_array($this->_db, "SHOW FIELDS FROM {$table}");
        $filename = $path . $table . $file_suffix;

        foreach($fields as $i => $field) {
            if ($field['Field'] === 'patient_id') {
                $hasPatientId = true;
            }

            if ($field['Field'] === 'erkrankung_id') {
                $hasDiseaseId = true;
            }
        }

        // remove loginname and pwd from user table
        if ($table === 'user') {
            foreach ($fields as $i => $field) {
                if (in_array($field['Field'], array('loginname', 'pwd')) === true) {
                    unset($fields[$i]);
                }
            }
        }

        // ggf. Felder für Patientendaten hinzufügen
        if ($hasPatientId) {
            $fields = array_merge(
                array(
                    array('Field' => 'nachname',     'Type' => 'varchar'),
                    array('Field' => 'vorname',      'Type' => 'varchar'),
                    array('Field' => 'geburtsdatum', 'Type' => 'date')
                ),
                $fields
            );
        }

        if (($hasPatientId === true && $hasDiseaseId === true) || $table === 'nachsorge') {
            $data = $this->ExportTableWithPatientAndDisease($table);
        } else if ($hasPatientId === true) {
            $data = $this->ExportTableWithPatient($table);
        } else {
            $data = $this->ExportOnlyTable($table);
        }

        if (!$this->WriteCsvFile($filename, $fields, $data)) {
            $exports['invalid'][] = array(
                "count" => count($data),
                "file"  => $table . $file_suffix,
                "url"   => $filename
            );
        } else {
            $exports['valid'][] = array(
                "count" => count($data),
                "file"  => $table . $file_suffix,
                "url"   => $filename
            );
        }
    }


    /**
     * getPatients
     * (from cache when not already loaded)
     *
     * @access  public
     * @param   bool $idsOnly
     * @return  array
     */
    public function getPatients($idsOnly = false)
    {
        $patients = $this->_patients;

        if ($patients === null) {
            $patients = array();
            $orgId    = $this->getOrgId();
            $disease  = $this->getDisease();

            // security check (sql injection)
            if (array_key_exists($disease, getLookup($this->_db, 'erkrankung')) === false) {
                $disease = '';
            }

            $query = "
                SELECT
                    p.patient_id,
                    p.nachname,
                    p.vorname,
                    p.geburtsdatum,
                    GROUP_CONCAT(DISTINCT e.erkrankung_id) as 'disease_ids'
                FROM
                    patient p
                INNER JOIN erkrankung e ON e.patient_id = p.patient_id
            ";

            if ($disease !== 'all') {
                $query .= " AND e.erkrankung = '{$disease}' ";
            }

            $query .= "
                WHERE
                    p.org_id = '{$orgId}'
                GROUP BY
                    p.patient_id
            ";

            foreach (sql_query_array($this->_db, $query) as $patient) {
                $patient['disease_ids'] = explode(',', $patient['disease_ids']);

                $patients[$patient['patient_id']] = $patient;
            }

            $this->_patients = $patients;
        }

        if ($idsOnly === true) {
            $patients = array_keys($patients);
        }

        return $patients;
    }


    /**
     * getPatientIds
     *
     * @access  public
     * @return  array
     */
    public function getPatientIds()
    {
        $patients = $this->getPatients();

        return array_keys($patients);
    }


    /**
     * getDisease IDs from patients
     *
     * @access  public
     * @return  array
     */
    public function getDiseaseIds()
    {
        $diseaseIds = $this->_diseaseIds;

        if ($diseaseIds === null) {
            $diseaseIds = array();

            foreach ($this->getPatients() as $patient) {
                $diseaseIds = array_merge(
                    $diseaseIds,
                    $patient['disease_ids']
                );
            }

            sort($diseaseIds);

            $this->_diseaseIds = $diseaseIds;
        }

        return $diseaseIds;
    }


    /**
     * ExportTableWithPatientAndDisease
     *
     * @access  protected
     * @param   string  $table
     * @return  array
     */
    protected function ExportTableWithPatientAndDisease($table)
    {
        $result     = array();
        $diseaseIds = $this->getDiseaseIds();

        if (count($diseaseIds) > 0) {
            $patients = $this->getPatients();

            if ($table === 'nachsorge') {
                $patientIds = $this->getPatientIds();

                $query = "
                    SELECT
                      n.*
                    FROM `{$table}` n
                        INNER JOIN nachsorge_erkrankung ne ON ne.nachsorge_id = n.nachsorge_id AND
                                                              ne.erkrankung_weitere_id IN (" . implode(',' , $diseaseIds) . ")
                    WHERE
                        n.patient_id IN (" . implode(',', $patientIds) .")
                    GROUP BY
                        n.nachsorge_id
                ";
            } else {
                $query = "SELECT * FROM `{$table}` WHERE erkrankung_id IN (" . implode(',', $diseaseIds) . ")";
            }

            foreach (sql_query_array($this->_db, $query) as $record) {
                $patientId = $record['patient_id'];

                $patient = array(
                    'nachname'     => null,
                    'vorname'      => null,
                    'geburtsdatum' => null
                );

                if (array_key_exists($patientId, $patients) === true) {
                    $patient['nachname']     = $patients[$patientId]['nachname'];
                    $patient['vorname']      = $patients[$patientId]['vorname'];
                    $patient['geburtsdatum'] = $patients[$patientId]['geburtsdatum'];
                }

                $result[] = array_merge(
                    array(
                        'nachname'     => $patient['nachname'],
                        'vorname'      => $patient['vorname'],
                        'geburtsdatum' => $patient['geburtsdatum']
                    ),
                    $record
                );
            }
        }

        return $result;
    }


    /**
     * ExportTableWithPatient
     *
     * @access  protected
     * @param   string  $table
     * @return  array
     */
    protected function ExportTableWithPatient($table)
    {
        $result = array();

        $patientIds = $this->getPatientIds();

        if (count($patientIds) > 0) {
            $patients = $this->getPatients();

            $query = "SELECT * FROM `{$table}` WHERE patient_id IN (" . implode(',', $patientIds) . ")";

            foreach (sql_query_array($this->_db, $query) as $record) {
                $patientId = $record['patient_id'];

                $patient = array(
                    'nachname'     => null,
                    'vorname'      => null,
                    'geburtsdatum' => null
                );

                if (array_key_exists($patientId, $patients) === true) {
                    $patient['nachname']     = $patients[$patientId]['nachname'];
                    $patient['vorname']      = $patients[$patientId]['vorname'];
                    $patient['geburtsdatum'] = $patients[$patientId]['geburtsdatum'];
                }

                $result[] = array_merge(
                    array(
                        'nachname'     => $patient['nachname'],
                        'vorname'      => $patient['vorname'],
                        'geburtsdatum' => $patient['geburtsdatum']
                    ),
                    $record
                );
            }
        }

        return $result;
    }


    /**
     * ExportOnlyTable
     *
     * @access  protected
     * @param   string  $table
     * @return  array
     */
    protected function ExportOnlyTable($table)
    {
        $query = "SELECT * FROM `{$table}`";

        return sql_query_array($this->_db, $query);
    }


    /**
     * setOrgId
     *
     * @access  public
     * @param   int $orgId
     * @return  CCsv2_0_0
     */
    public function setOrgId($orgId)
    {
        $this->_orgId = $orgId;

        return $this;
    }


    /**
     * getOrgId
     *
     * @access  public
     * @return  int
     */
    public function getOrgId()
    {
        return $this->_orgId;
    }


    /**
     * setDisease
     *
     * @access  public
     * @param   string  $disease
     * @return  CCsv2_0_0
     */
    public function setDisease($disease)
    {
        $this->_disease = $disease;

        return $this;
    }


    /**
     * getDisease
     *
     * @access  public
     * @return  string
     */
    public function getDisease()
    {
        return $this->_disease;
    }
}

?>
