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

require_once ('class.exportexception.php');

class HDatabase
{
    /**
     * LoadExportSettings
     * (loads export settings and throw error if not defined)
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @param   array       $base_settings
     * @param   string      $export_name
     * @return  void
     * @throws  EExportException
     */
    static public function LoadExportSettings($db, &$base_settings, $export_name)
    {
        $settings_found = false;
        $export_settings = json_decode(dlookup($db, 'settings_export', 'settings', "name = '{$export_name}'"), true);

        if (null != $export_settings) {
            foreach ($export_settings as $settings) {
                if ($settings[ 'org_id' ] == $base_settings[ 'org_id' ]) {
                    $base_settings = array_merge($base_settings, $settings);
                    $settings_found = true;
                    break;
                }
            }
        }

        if (false === $settings_found) {
            throw new EExportException("No settings defined for org_id ({$base_settings['org_id']})");
        }
    }


    /**
     * RecordStringToArray
     * (attention! empty values will be cast to null)
     *
     * @static
     * @access  public
     * @param   string  $record
     * @param   array   $names
     * @return  array
     */
    static public function recordStringToArray($record, $names = array())
    {
        $result = array();

        if (is_string($record) === true && strlen($record) > 0) {
            $rows = explode(HReports::SEPARATOR_ROWS, $record);

            foreach ($rows as $row_str) {
                $row = explode(HReports::SEPARATOR_COLS, $row_str);
                $i = 0;
                $row_data = array();

                foreach ($row as $col) {
                    $col = strlen($col) > 0 ? $col : null;

                    if ((count($names) > 0) && isset($names[$i]) === true) {
                        $row_data[$names[$i]] = $col;
                    } else {
                        $row_data[] = $col;
                    }

                    $i++;
                }

                $result[] = $row_data;
            }
        }

        return $result;
    }


    /**
     * createMap
     *
     * @static
     * @access  public
     * @param   array   $records
     * @return  array
     */
    public static function createMap(array $records = array())
    {
        $map = array();

        foreach ($records as $record) {
            $map[reset($record)] = $record;
        }

        return $map;
    }


    /**
     * GetPatientData
     *
     * @static
     * @access  public
     * @param   resource $db
     * @param   int $patient_id
     * @return  array
     */
    static public function GetPatientData($db, $patient_id)
    {
        $query = "SELECT * FROM patient WHERE patient_id = {$patient_id}";

        $result = end(sql_query_array($db, $query));

        if (false === $result) {
            return array();
        }

        return $result;
    }


    /**
     * GetErkrankungData
     *
     * @static
     * @access  public
     * @param   resource    $db
     * @param   int         $diseaseId
     * @return  array
     */
    static public function GetErkrankungData($db, $diseaseId)
    {
        $query = "
            SELECT
                e.*,
                erkrankung_bez.bez AS erkrankung_bez
            FROM
                erkrankung e
                LEFT JOIN l_basic erkrankung_bez ON erkrankung_bez.klasse='erkrankung' AND
                                                    erkrankung_bez.code=e.erkrankung
            WHERE
                e.erkrankung_id={$diseaseId}
        ";

        $result = end(sql_query_array($db, $query));

        if (false === $result) {
            return array();
        }

        return $result;
    }


    /**
     *
     * @param $db
     * @param $export_name
     * @return unknown_type
     */
    static public function ReadExportCodeTable( $db, $export_name )
    {
        $codes = array();

        if ( 0 == strlen( $export_name ) ) {
            throw new EExportException( "Export name $export_name not found." );
        }

        $table_export_name = "code_{$export_name}";

        if (HDatabase::ExportCodeCheck($table_export_name) === true) {
            $query = "
                SELECT
                    klasse,
                    code,
                    {$table_export_name} AS export_code

                FROM
                    l_basic

                WHERE
                   klasse IS NOT NULL
                   AND code IS NOT NULL
                    AND {$table_export_name} IS NOT NULL
            ";

            foreach (sql_query_array($db, $query) as $row) {
                $codes[ $row[ 'klasse' ] . "_" . $row[ 'code' ] ] = $row[ 'export_code' ];
            }
        }

        return $codes;
    }


    /**
     * ExportCodeCheck
     *
     * @static
     * @access  public
     * @param   string  $exportCode
     * @return  bool
     */
    public static function ExportCodeCheck($exportCode)
    {
        $result = mysql_query("SHOW COLUMNS FROM l_basic LIKE '{$exportCode}'");

        return (mysql_num_rows($result) ? true : false);
    }


    /**
     *
     * @param $db
     * @param $export_name
     * @return unknown_type
     */
    static public function ReadLBasicTable( $db )
    {
        $codes = array();
        $query = "
            SELECT
                klasse,
                code,
                bez

            FROM
                l_basic
        ";
        $result = sql_query_array( $db, $query );
        foreach ( $result as $row) {
            $codes[ $row[ 'klasse' ] . "_" . $row[ 'code' ] ] = $row[ 'bez' ];
        }
        return $codes;
    }


/**
     *
     * @param $db
     * @param $export_name
     * @return unknown_type
     */
    static public function readMedicationPatternTable( $db )
    {
        $medication_pattern = array();
        $query = "
            SELECT
                vorlage_therapie_id,
                wirkstoff
            FROM
                vorlage_therapie_wirkstoff
        ";
        $result = sql_query_array( $db, $query );

        $medication_pattern = array();

        foreach ($result as $r) {
            $medication_pattern[$r['vorlage_therapie_id']][] = $r['wirkstoff'];
        }

        return $medication_pattern;
    }
}
