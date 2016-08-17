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

class HReports
{

    static protected $g_l_basic_bez = array();

    /**
     *
     * @var unknown_type
     */
    const SEPARATOR_ROWS = "\x01";
    /**
     *
     * @var unknown_type
     */
    const SEPARATOR_COLS = "\x02";

    /**
     *
     * @param $record
     * @param $names
     * @return unknown_type
     */
    static public function RecordStringToArray( $record,
                                                $names = array() ) {
        $result = array();

        if ( is_string( $record ) ) {
            if ( strlen( $record ) > 0 ) {
                $rows = explode( HReports::SEPARATOR_ROWS, $record );
                foreach( $rows as $row_str ) {
                    $row = explode( HReports::SEPARATOR_COLS, $row_str );
                    $i = 0;
                    $row_data = array();
                    foreach( $row as $col ) {
                        if ( ( count( $names ) > 0 ) &&
                             ( isset( $names[ $i ] ) ) ) {
                            $row_data[ $names[ $i ] ] = $col;
                        }
                        else {
                            $row_data[] = $col;
                        }
                        $i++;
                    }
                    $result[] = $row_data;
                }
            }
        }
        return $result;
    }


    /**
     * order Records by given field
     *
     * @static
     * @access
     * @param        $records
     * @param        $field
     * @param string $orderType
     * @return array
     */
    public static function OrderRecordsByField($records, $field, $orderType = 'ASC')
    {
        $orderedRecords = array();
        $tmp = array();

        foreach ($records as $record) {
            $tmp[$record[$field]][] = $record;
        }

        if ($orderType === 'ASC') {
            ksort($tmp);
        } else {
            krsort($tmp);
        }

        foreach ($tmp as $records) {
            $orderedRecords = array_merge($orderedRecords, $records);
        }

        return $orderedRecords;
    }



    /**
     *
     * @param $array
     * @return unknown_type
     */
    static public function NatSortByKey( $array ) {
        if ( is_array( $array ) ) {
            $keys = array_keys( $array );
            if ( natsort( $keys ) ) {
                $result = array();
                foreach( $keys as $key ) {
                    $result[ $key ] = $array[ $key ];
                }
                return $result;
            }
        }
        return $array;
    }

    /**
     *
     * @param $value
     * @return unknown_type
     */
    static public function IsTrue( $value ) {
        if ( is_string( $value ) &&
             ( strlen( $value ) > 0 ) &&
             ( $value == '1' ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  $value == 1 ) {
            return true;
        }
        else if ( is_bool( $value ) &&
                  ( true == $value ) ) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param $value
     * @return unknown_type
     */
    static public function IsFalse( $value ) {
        if ( is_null( $value ) ) {
            return true;
        }
        else if ( is_string( $value ) &&
             ( ( strlen( $value ) == 0 ) ||
               ( $value == '0' ) ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  $value == 0 ) {
            return true;
        }
        else if ( is_bool( $value ) &&
                  ( false == $value ) ) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $value
     * @return bool
     */
    static public function IsEmpty( $value )
    {
        if ( is_null( $value ) ) {
            return true;
        }
        else if ( is_string( $value ) &&
                  ( strlen( $value ) == 0 ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  ( $value == 0 ) ) {
            return true;
        }
        else if ( is_bool( $value ) &&
                  ( false == $value ) ) {
            return true;
        }
        else if ( is_array( $value ) &&
                  ( count( $value ) == 0 ) ) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $value
     * @return bool
     */
    static public function IsNotEmpty( $value )
    {
        if ( is_null( $value ) ) {
            return false;
        }
        else if ( is_string( $value ) &&
                  ( strlen( $value ) > 0 ) ) {
            return true;
        }
        else if ( is_numeric( $value ) &&
                  ( $value != 0 ) ) {
            return true;
        }
        else if ( is_bool( $value ) &&
                  ( true == $value ) ) {
            return true;
        }
        else if ( is_array( $value ) &&
                  ( count( $value ) > 0 ) ) {
            return true;
        }
        return false;
    }


    /**
     *
     * @param $data
     * @param $index
     * @param $value
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMaxElementByDate( $data,
                                                $index = -1,
                                                $value = "",
                                                $date_index = 1 )
    {
        return HReports::GetMinElementByDate( $data,
                                              $index,
                                              $value,
                                              true,
                                              $date_index );
    }

    /**
     *
     * @param $data
     * @param $index
     * @param $value
     * @param $reverse
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMinElementByDate( $data,
                                                $index = -1,
                                                $value = "",
                                                $reverse = false,
                                                $date_index = 1 )
    {
        $rows = array();

        if ( is_string( $data ) ) {
            if ( strlen( $data ) == 0 ) {
                return false;
            }
            $rows = HReports::RecordStringToArray( $data );
        }
        else if ( is_array( $data ) ) {
            $rows = $data;
        }
        else {
            return false;
        }
        if ( count( $rows ) == 0 ) {
            return false;
        }
        if ( !is_array( $value ) ) {
            $value = array( $value );
        }
        $order = array();
        foreach( $rows as $row ) {
            $found = true;
            if ( $index != -1 ) {
                $arr_keys = array_keys( $row );
                if ( isset( $row[ $arr_keys[ $index ] ] ) ) {
                    $found = false;
                    foreach( $value as $v ) {
                        if ( null == $v ) {
                            if ( strlen( $row[ $arr_keys[ $index ] ] ) > 0 ) {
                                $found = true;
                                break;
                            }
                        }
                        else {
                            if ( $row[ $arr_keys[ $index ] ] == $v ) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
            }
            if ( true == $found ) {
                if ( isset( $row[ $date_index ] ) ) {
                    $time = $row[ $date_index ];
                }
                else {
                    $arr_keys = array_keys( $row );
                    $time = $row[ $arr_keys[ $date_index ] ];
                }
                $order[ strtotime( $time ) ] = $row;
            }
        }
        if ( count( $order ) === 0 ) {
            return false;
        }
        ksort( $order );
        return $reverse ? end( $order ) : reset( $order );
    }

    /**
     *
     * @param $record
     * @param $date
     * @param $index
     * @param $value
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMaxElementByDateBefor( $data,
                                                     $date,
                                                     $index = -1,
                                                     $value = "",
                                                     $date_index = 1 )
    {
        return HReports::GetMinElementByDateBefor( $data,
                                                   $date,
                                                   $index,
                                                   $value,
                                                   true,
                                                   $date_index );
    }

    /**
     *
     * @param $record
     * @param $date
     * @param $index
     * @param $value
     * @param $reverse
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMinElementByDateBefor( $data,
                                                     $date,
                                                     $index = -1,
                                                     $value = "",
                                                     $reverse = false,
                                                     $date_index = 1 )
    {
        $date_time = 100000000000000;
        $rows = array();

        if ( is_string( $data ) ) {
            if ( strlen( $data ) == 0 ) {
                return false;
            }
            $rows = HReports::RecordStringToArray( $data );
        }
        else if ( is_array( $data ) ) {
            $rows = $data;
        }
        else {
            return false;
        }
        if ( count( $rows ) == 0 ) {
            return false;
        }
        if ( strlen( $date ) > 0 ) {
            $date_time = strtotime( $date );
        }
        if ( !is_array( $value ) ) {
            $value = array( $value );
        }
        $order = array();
        foreach( $rows as $row ) {
            $found = true;
            if ( $index != -1 ) {
                $arr_keys = array_keys( $row );
                if ( isset( $row[ $arr_keys[ $index ] ] ) ) {
                    $found = false;
                    foreach( $value as $v ) {
                        if ( null == $v ) {
                            if ( strlen( $row[ $arr_keys[ $index ] ] ) > 0 ) {
                                $found = true;
                                break;
                            }
                        }
                        else {
                            if ( $row[ $arr_keys[ $index ] ] == $v ) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
            }
            if ( isset( $row[ $date_index ] ) ) {
                $time = $row[ $date_index ];
            }
            else {
                $arr_keys = array_keys( $row );
                $time = $row[ $arr_keys[ $date_index ] ];
            }
            if ( ( true == $found ) &&
                 ( strtotime( $time ) < $date_time ) ) {
                $order[ strtotime( $time ) ] = $row;
            }
        }
        if ( count( $order ) === 0 ) {
            return false;
        }
        ksort( $order );
        return $reverse ? end( $order ) : reset( $order );
    }

    /**
     *
     * @param $record
     * @param $date
     * @param $index
     * @param $value
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMaxElementByDateAfter( $record,
                                                     $date,
                                                     $index = -1,
                                                     $value = "",
                                                     $date_index = 1 )
    {
        return HReports::GetMinElementByDateAfter( $record,
                                                   $date,
                                                   $index,
                                                   $value,
                                                   true,
                                                   $date_index );
    }

    /**
     *
     * @param $record
     * @param $date
     * @param $index
     * @param $value
     * @param $reverse
     * @param $date_index
     * @return unknown_type
     */
    static public function GetMinElementByDateAfter( $data,
                                                     $date,
                                                     $index = -1,
                                                     $value = "",
                                                     $reverse = false,
                                                     $date_index = 1 )
    {
        $date_time = 0;
        $rows = array();

        if ( is_string( $data ) ) {
            if ( strlen( $data ) == 0 ) {
                return false;
            }
            $rows = HReports::RecordStringToArray( $data );
        }
        else if ( is_array( $data ) ) {
            $rows = $data;
        }
        else {
            return false;
        }
        if ( count( $rows ) == 0 ) {
            return false;
        }
        if ( strlen( $date ) > 0 ) {
            $date_time = strtotime( $date );
        }
        if ( !is_array( $value ) ) {
            $value = array( $value );
        }
        $order = array();
        foreach( $rows as $row ) {
            $found = true;
            if ( $index != -1 ) {
                $arr_keys = array_keys( $row );
                if ( isset( $row[ $arr_keys[ $index ] ] ) ) {
                    $found = false;
                    foreach( $value as $v ) {
                        if ( null == $v ) {
                            if ( strlen( $row[ $arr_keys[ $index ] ] ) > 0 ) {
                                $found = true;
                                break;
                            }
                        }
                        else {
                            if ( $row[ $arr_keys[ $index ] ] == $v ) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
            }
            if ( isset( $row[ $date_index ] ) ) {
                $time = $row[ $date_index ];
            }
            else {
                $arr_keys = array_keys( $row );
                $time = $row[ $arr_keys[ $date_index ] ];
            }
            if ( ( true == $found ) &&
                 ( strtotime( $time ) > $date_time ) ) {
                $order[ strtotime( $time ) ] = $row;
            }
        }
        if ( count( $order ) === 0 ) {
            return false;
        }
        ksort( $order );
        return $reverse ? end( $order ) : reset( $order );
    }

    /**
     *
     * @param $array_a
     * @param $array_b
     * @return unknown_type
     */
    static public function ArrayRecursiveDiff( $array_a, $array_b ) {
        $result = array();

        foreach( $array_a as $key => $value ) {
            if ( array_key_exists( $key, $array_b ) ) {
                if ( is_array( $value ) ) {
                    $rec_diff = HReports::ArrayRecursiveDiff( $value, $array_b[ $key ] );
                    if ( count( $rec_diff ) ) {
                        $result[ $key ] = $rec_diff;
                    }
                } else {
                    if ( $value != $array_b[ $key ] ) {
                        $result[ $key ] = $value;
                    }
                }
            } else {
                $result[ $key ] = $value;
            }
        }
        return $result;
    }

    /**
     *
     * @param $db
     * @param $erkrankung_id
     * @param $diagnose_seite
     * @param $start_date
     * @param $end_date
     * @param $newst
     * @return unknown_type
     */
    static public function GetDiagnoseWithText( $db,
                                                $erkrankung_id,
                                                $diagnose_seite,
                                                $start_date,
                                                $end_date,
                                                $newst = true ) {
        $data = array(
            'diagnose' => '',
            'text'     => ''
        );

        $query = "
            SELECT
                ts.diagnose       AS diagnose,
                ts.diagnose_text  AS text

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id=$erkrankung_id
                AND ts.diagnose_seite='$diagnose_seite'
                AND ts.datum_sicherung>='$start_date'
                AND ts.datum_sicherung<='$end_date'
                AND ts.diagnose IS NOT NULL
                AND ts.diagnose_text IS NOT NULL

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC
        ";
        $result = sql_query_array( $db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            if ( $newst ) {
                $data = reset( $result );
            }
            else {
                $data = end( $result );
            }
        }
        return $data;
    }


    /**
     *
     *
     * @static
     * @access
     * @param      $db
     * @param      $erkrankung_id
     * @param      $diagnose_seite
     * @param      $start_date
     * @param      $end_date
     * @param bool $newst
     * @return array|mixed
     */
    static public function GetLokalisationWithText( $db,
                                                    $erkrankung_id,
                                                    $diagnose_seite,
                                                    $start_date,
                                                    $end_date,
                                                    $newst = true ) {
        $data = array(
            'lokalisation' => '',
            'text'         => ''
        );

        $query = "
            SELECT
                ts.lokalisation        AS lokalisation,
                ts.lokalisation_text   AS text

            FROM
                tumorstatus ts

            WHERE
                ts.erkrankung_id=$erkrankung_id
                AND ts.diagnose_seite='$diagnose_seite'
                AND ts.datum_sicherung>='$start_date'
                AND ts.datum_sicherung<='$end_date'
                AND ts.lokalisation IS NOT NULL
                AND ts.lokalisation_text IS NOT NULL

            ORDER BY
                ts.datum_sicherung DESC,
                ts.sicherungsgrad ASC,
                ts.datum_beurteilung DESC
        ";
        $result = sql_query_array( $db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            if ( $newst ) {
                $data = reset( $result );
            }
            else {
                $data = end( $result );
            }
        }
        return $data;
    }

    /**
     *
     * @param $db
     * @param $erkrankung_id
     * @param $diagnose_seite
     * @param $start_date
     * @param $end_date
     * @param $newst
     * @return unknown_type
     */
    static public function GetMorphologieWithText( $db,
                                                   $erkrankung_id,
                                                   $diagnose_seite,
                                                   $start_date,
                                                   $end_date,
                                                   $table = "tumorstatus",
                                                   $newst = true ) {
        $data = array(
            'morphologie' => '',
            'text'        => ''
        );

        if ( "tumorstatus" == $table ) {
            $query = "
                SELECT
                    ts.morphologie       AS morphologie,
                    ts.morphologie_text  AS text

                FROM
                    tumorstatus ts

                WHERE
                    ts.erkrankung_id=$erkrankung_id
                    AND ts.diagnose_seite='$diagnose_seite'
                    AND ts.datum_sicherung>='$start_date'
                    AND ts.datum_sicherung<='$end_date'
                    AND ts.morphologie IS NOT NULL
                    AND ts.morphologie_text IS NOT NULL

                ORDER BY
                    ts.datum_sicherung DESC,
                    ts.sicherungsgrad ASC,
                    ts.datum_beurteilung DESC
            ";
        }
        else if ( "histologie" == $table ) {
            $query = "
                SELECT
                    h.morphologie       AS morphologie,
                    h.morphologie_text  AS text

                FROM
                    histologie h

                WHERE
                    h.erkrankung_id=$erkrankung_id
                    AND h.diagnose_seite='$diagnose_seite'
                    AND h.datum>='$start_date'
                    AND h.datum<='$end_date'
                    AND h.morphologie IS NOT NULL
                    AND h.morphologie_text IS NOT NULL

                ORDER BY
                    h.datum DESC
            ";
        }
        else {
            return false;
        }
        $result = sql_query_array( $db, $query );
        if ( ( false !== $result ) &&
             ( count( $result ) > 0 ) ) {
            if ( $newst ) {
                $data = reset( $result );
            }
            else {
                $data = end( $result );
            }
        }
        return $data;
    }


    /**
     *
     *
     * @static
     * @access
     * @param        $datum_von
     * @param string $datum_bis
     * @return float
     */
    static public function CalcDauer( $datum_von,
                                      $datum_bis = "NOW" )
    {
        $sdate = strtotime( $datum_von );
        $edate = strtotime( $datum_bis );
        $days = ceil( ( $edate - $sdate ) / 86400 );
        return $days;
    }


    /**
     *
     *
     * @static
     * @access
     * @param        $datum_von
     * @param string $datum_bis
     * @return float
     */
    static public function CalcMonths( $datum_von,
                                       $datum_bis = "NOW" )
    {
        $sdate = strtotime( $datum_von );
        $edate = strtotime( $datum_bis );
        $months = ceil( ( $edate - $sdate ) / 2592000 );
        return $months;
    }


    /**
     *
     *
     * @static
     * @access
     * @param      $data
     * @param      $index
     * @param null $value
     * @return void
     */
    static public function GetRecordsWithValueAt( $data,
                                                  $index,
                                                  $value = null )
    {
        $result = array();
        $rows = array();
        if ( is_string( $data ) ) {
            if ( strlen( $data ) == 0 ) {
                return false;
            }
            $rows = HReports::RecordStringToArray( $data );
        }
        else if ( is_array( $data ) ) {
            $rows = $data;
        }
        else {
            return false;
        }
        foreach( $rows as $row ) {
            $keys = array_keys( $row );
            if ( isset( $row[ $keys[ $index ] ] ) ) {
                if ( $value !== null ) {
                    if ( $row[ $keys[ $index ] ] == $value ) {
                        $result[] = $row;
                    }
                }
                else {
                    if ( strlen( $row[ $keys[ $index ] ] ) > 0 ) {
                        $result[] = $row;
                    }
                }
            }
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $diagnose
     * @return bool|mixed
     */
    static public function GetDiagnoseToLokalisation( $db,
                                                      $diagnose )
    {
        $query = "
            SELECT
                d_to_l.lokalisation_code AS lokalisation,
                d_to_l.lokalisation_text AS text

            FROM
                l_exp_diagnose_to_lokalisation d_to_l

            WHERE
                d_to_l.diagnose_code='{$diagnose}'

            LIMIT 0, 1
        ";
        $result = end( sql_query_array( $db, $query ) );
        if ( false !== $result ) {
            return $result;
        }
        return false;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $operation_id
     * @param $diagnose_seite
     * @return array
     */
    static public function GetOperationCodes( $db,
                                              $operation_id,
                                              $diagnose_seite )
    {
        $where = "";
        switch( $diagnose_seite ) {
            case 'B' :
                $where = "AND eo.prozedur_seite IN ( 'B', 'L', 'R' )";
                break;
            case 'L' :
                $where = "AND eo.prozedur_seite IN ( 'B', 'L' )";
                break;
            case 'R' :
                $where = "AND eo.prozedur_seite IN ( 'B', 'R' )";
                break;
        }
        $query = "
            SELECT
                eo.prozedur,
                eo.prozedur_seite,
                eo.prozedur_text

            FROM
                eingriff_ops eo

            WHERE
                eo.eingriff_id={$operation_id}
                {$where}
        ";
        $result = sql_query_array( $db, $query );
        if ( false === $result ) {
            return array();
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $operations
     * @return array
     */
    static public function CheckOperationsSide( $db,
                                                $operations )
    {
        $result = array();
        $ops_codes = array();
        if ( is_array( $operations ) ) {
            foreach( $operations as $op ) {
                $ops_codes = HReports::GetOperationCodes( $db,
                                                          $op[ 'eingriff_id' ],
                                                          $op[ 'diagnose_seite' ] );
                if ( count( $ops_codes ) > 0 ) {
                    $op[ 'ops_codes' ] = $ops_codes;
                    $result[] = $op;
                }
            }
        }
        else {
            $result = $operations;
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $db
     * @param $erkrankung_id
     * @param $diagnose_seite
     * @param $start_date
     * @param $end_date
     * @return array
     */
    static public function GetAllStudies( $db,
                                          $erkrankung_id,
                                          $start_date,
                                          $end_date )
    {
        $query = "
            SELECT
                *

            FROM
                studie s

            WHERE
                s.erkrankung_id = {$erkrankung_id}
                AND ( s.date IS NOT NULL OR s.beginn IS NOT NULL )
                AND ( ( s.date BETWEEN '{$start_date}' AND '{$end_date}' )
                      OR ( s.beginn BETWEEN '{$start_date}' AND '{$end_date}' ) )
        ";
        $result = sql_query_array( $db, $query );
        if ( false === $result ) {
            return array();
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $datum
     * @return int
     */
    static public function CalcAge($geburtsdatum)
    {
        $age = explode("-", $geburtsdatum);
        $alter = date("Y", time()) - $age[0];
        if (mktime(0, 0, 0, date("m", time()), date("d", time()), date("Y", time())) <
            mktime(0, 0, 0, $age[1], $age[2], date("Y", time()))) {
            $alter--;
        }
        return $alter;
    }


    /**
     * Bestimmt die Werktage, die zwischen zwei Datumsangaben liegen
     * Erwartetes Datum: englisch oder timestamt
     *
     * @static
     * @access      public
     * @param       $start
     * @param       $end
     * @param array $holidays
     * @return int
     */
    static public function CalcWorkdays($start, $end, $holidays = array())
    {
        $tageGesamt = array();
        if (strpos($start, '-') !== false) {
            $start = strtotime($start);
        }
        if (strpos($end, '-') !== false) {
            $end = strtotime($end);
        }
        while ($start < $end) {
            $tageGesamt[] = date('Y-m-d', $start);
            $start += 86400;
        }
        $workdays = 0;
        foreach ($tageGesamt as $pwday) {
            if (in_array(date('w', strtotime($pwday)), array(6, 0))) {
                continue;
            }
            if (in_array(date('Y-m-d', strtotime($pwday)), $holidays)) {
                continue;
            }
            $workdays++;
        }
        return $workdays;
    }


    /**
     *
     *
     * @static
     * @access
     * @return void
     */
    static public function UnitTest()
    {
        // Check for ArrayRecursiveDiff
        $a = array(
            array(
                "Hallo",
                100,
                ";",
								array(
								    "Wow"
								)
            ),
            array(
                "Welt!!!",
                array(
                    1 => "Geht!!!"
                )
            ),
            "test"
        );
        $b = array(
            array(
                "Hallo",
                100,
                ";",
								array(
								    "Wow"
								)
            ),
            array(
                "Welt!!!",
                array(
                    1 => "Geht!!!"
                )
            ),
            "test"
        );
        $c = array(
            array(
                "Hallo",
                100,
                ";",
								array(
								    "1" => "Wow"
								)
            ),
            array(
                "Welt!!!",
                array(
                    1 => "Geht!!!"
                )
            ),
            "test"
        );
        $erg = HReports::ArrayRecursiveDiff( $a, $b );
        if ( count( $erg ) == 0 ) {
            print( "OK: HReports::ArrayRecursiveDiff 1<br>" );
        }
        else {
            print( "Faild: HReports::ArrayRecursiveDiff 1<br>" );
        }
        $erg = HReports::ArrayRecursiveDiff( $a, $c );
        if ( count( $erg ) > 0 ) {
            print( "OK: HReports::ArrayRecursiveDiff 2<br>" );
        }
        else {
            print( "Faild: HReports::ArrayRecursiveDiff 2<br>" );
        }
        if ( $erg[ 0 ][ 3 ][ 0 ] === "Wow" ) {
            print( "OK: HReports::ArrayRecursiveDiff 3<br>" );
        }
        else {
            print( "Faild: HReports::ArrayRecursiveDiff 3<br>" );
        }
        // Check for HReports::RecordStringToArray
        $record_str = "52\x022013-04-10\x02\x02I\x02\x02Meldung KR 1 Bem...\x0154\x022013-05-20\x02\x02I\x02\x02Meldung Kr 2 Bem... lalala\x0155\x022013-07-29\x02\x02I\x02\x02Bem 3";
        $check_arr = array(
            array(
            	  "ekr_id"               => "52",
                "datum"                => "2013-04-10",
                "wandlung_diagnose"    => "",
                "meldebegruendung"     => "I",
                "export_for_onkeyline" => "",
                "bem"                  => "Meldung KR 1 Bem..."
            ),
            array(
                "ekr_id"               => "54",
                "datum"                => "2013-05-20",
                "wandlung_diagnose"    => "",
                "meldebegruendung"     => "I",
                "export_for_onkeyline" => "",
                "bem"                  => "Meldung Kr 2 Bem... lalala"
            ),
            array(
                "ekr_id"               => "55",
                "datum"                => "2013-07-29",
                "wandlung_diagnose"    => "",
                "meldebegruendung"     => "I",
                "export_for_onkeyline" => "",
                "bem"                  => "Bem 3"
            )
        );
        $a = array(
            "ekr_id"               => "54",
            "datum"                => "2013-05-20",
            "wandlung_diagnose"    => "",
            "meldebegruendung"     => "I",
            "export_for_onkeyline" => "",
            "bem"                  => "Meldung Kr 2 Bem... lalala"
        );
        $b = array(
            "ekr_id"               => "54",
            "datum"                => "2013-05-20",
            "wandlung_diagnose"    => "",
            "meldebegruendung"     => "I",
            "export_for_onkeyline" => "",
            "bem"                  => "Meldung Kr 2 Bem... lalala"
        );
        $arr = HReports::RecordStringToArray( $record_str,
                                              array(
                                              	  "ekr_id",
                                                  "datum",
                                                  "wandlung_diagnose",
                                                  "meldebegruendung",
                                                  "export_for_onkeyline",
                                                  "bem"
                                              ) );
        if ( count( HReports::ArrayRecursiveDiff( $check_arr, $arr ) ) == 0 ) {
            print( "OK: HReports::RecordStringToArray 1<br>" );
        }
        else {
            print( "Faild: HReports::RecordStringToArray 1<br>" );
        }
        $arr = HReports::RecordStringToArray( $a,
                                              array(
                                              	  "ekr_id",
                                                  "datum",
                                                  "wandlung_diagnose",
                                                  "meldebegruendung",
                                                  "export_for_onkeyline",
                                                  "bem"
                                              ) );
        if ( count( HReports::ArrayRecursiveDiff( $a, $b ) ) == 0 ) {
            print( "OK: HReports::RecordStringToArray 2<br>" );
        }
        else {
            print( "Faild: HReports::RecordStringToArray 2<br>" );
        }
        // Check for IsTrue
        if ( HReports::IsTrue( true ) ) {
            print( "OK: HReports::IsTrue 1<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 1<br>" );
        }
        if ( !HReports::IsTrue( false ) ) {
            print( "OK: HReports::IsTrue 2<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 2<br>" );
        }
        if ( HReports::IsTrue( 1 ) ) {
            print( "OK: HReports::IsTrue 3<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 3<br>" );
        }
        if ( !HReports::IsTrue( 0 ) ) {
            print( "OK: HReports::IsTrue 4<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 4<br>" );
        }
        if ( HReports::IsTrue( "1" ) ) {
            print( "OK: HReports::IsTrue 5<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 5<br>" );
        }
        if ( !HReports::IsTrue( "0" ) ) {
            print( "OK: HReports::IsTrue 6<br>" );
        }
        else {
            print( "Faild: HReports::IsTrue 6<br>" );
        }
        // Check for IsFalse
        if ( HReports::IsFalse( false ) ) {
            print( "OK: HReports::IsFalse 1<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 1<br>" );
        }
        if ( !HReports::IsFalse( true ) ) {
            print( "OK: HReports::IsFalse 2<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 2<br>" );
        }
        if ( HReports::IsFalse( 0 ) ) {
            print( "OK: HReports::IsFalse 3<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 3<br>" );
        }
        if ( !HReports::IsFalse( 1 ) ) {
            print( "OK: HReports::IsFalse 4<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 4<br>" );
        }
        if ( HReports::IsFalse( "0" ) ) {
            print( "OK: HReports::IsFalse 5<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 5<br>" );
        }
        if ( !HReports::IsFalse( "1" ) ) {
            print( "OK: HReports::IsFalse 6<br>" );
        }
        else {
            print( "Faild: HReports::IsFalse 6<br>" );
        }
        // Check for GetMaxElementByDate
        $test_array = array(
            array(
                'id'   => 100,
                'date' => '2013-04-12',
                'v1'   => 23,
                'v2'   => 'T'
            ),
            array(
                'id'   => 1,
                'date' => '2012-01-29',
                'v1'   => 10,
                'v2'   => 'H'
            ),
            array(
                'id'   => 30,
                'date' => '2013-06-21',
                'v1'   => 3,
                'v2'   => 'Z'
            ),
            array(
                'id'   => 42,
                'date' => '2011-12-18',
                'v1'   => 10,
                'v2'   => 'T'
            ),
            array(
                'id'   => 67,
                'date' => '2013-08-01',
                'v1'   => 230,
                'v2'   => 'v'
            )
        );
        $erg = HReports::GetMaxElementByDate( $test_array );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 4 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDate 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDate 1<br>" );
        }
        $erg = HReports::GetMaxElementByDate( $test_array );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 0 ], $erg ) ) > 0 ) {
            print( "OK: HReports::GetMaxElementByDate 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDate 2<br>" );
        }
        $erg = HReports::GetMaxElementByDate( "" );
        if ( false === $erg ) {
            print( "OK: HReports::GetMaxElementByDate 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDate 3<br>" );
        }
        $erg = HReports::GetMaxElementByDate( $test_array, 2, 10 );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 1 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDate 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDate 4<br>" );
        }
        // Check for GetMinElementByDate
        $erg = HReports::GetMinElementByDate( $test_array );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 3 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDate 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDate 1<br>" );
        }
        $erg = HReports::GetMinElementByDate( $test_array );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 0 ], $erg ) ) > 0 ) {
            print( "OK: HReports::GetMinElementByDate 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDate 2<br>" );
        }
        $erg = HReports::GetMinElementByDate( "" );
        if ( false === $erg ) {
            print( "OK: HReports::GetMinElementByDate 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDate 3<br>" );
        }
        $erg = HReports::GetMinElementByDate( $test_array, 2, 10 );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 3 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDate 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDate 4<br>" );
        }
        // Check for befor
        //HReports::GetMaxElementByDateBefor()
        $erg = HReports::GetMaxElementByDateBefor( "", '' );
        if ( false === $erg ) {
            print( "OK: HReports::GetMaxElementByDateBefor 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateBefor 1<br>" );
        }
        $erg = HReports::GetMaxElementByDateBefor( $test_array, '2012-01-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 3 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateBefor 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateBefor 2<br>" );
        }
        $erg = HReports::GetMaxElementByDateBefor( $test_array, '2013-06-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 0 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateBefor 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateBefor 3<br>" );
        }
        $erg = HReports::GetMaxElementByDateBefor( $test_array, '2013-06-01', 3, 'H' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 1 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateBefor 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateBefor 4<br>" );
        }
        //HReports::GetMinElementByDateBefor()
        $erg = HReports::GetMinElementByDateBefor( "", '' );
        if ( false === $erg ) {
            print( "OK: HReports::GetMinElementByDateBefor 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateBefor 1<br>" );
        }
        $erg = HReports::GetMinElementByDateBefor( $test_array, '2012-01-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 3 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateBefor 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateBefor 2<br>" );
        }
        $erg = HReports::GetMinElementByDateBefor( $test_array, '2013-06-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 3 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateBefor 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateBefor 3<br>" );
        }
        $erg = HReports::GetMaxElementByDateBefor( $test_array, '2013-06-01', 3, 'H' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 1 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateBefor 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateBefor 4<br>" );
        }
        // Check for after
        //HReports::GetMaxElementByDateAfter()
        $erg = HReports::GetMaxElementByDateAfter( "", '' );
        if ( false === $erg ) {
            print( "OK: HReports::GetMaxElementByDateAfter 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateAfter 1<br>" );
        }
        $erg = HReports::GetMaxElementByDateAfter( $test_array, '2012-01-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 4 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateAfter 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateAfter 2<br>" );
        }
        $erg = HReports::GetMaxElementByDateAfter( $test_array, '2013-06-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 4 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateAfter 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateAfter 3<br>" );
        }
        $erg = HReports::GetMaxElementByDateAfter( $test_array, '2013-06-01', 3, 'Z' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 2 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMaxElementByDateAfter 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMaxElementByDateAfter 4<br>" );
        }
        //HReports::GetMinElementByDateAfter()
        $erg = HReports::GetMinElementByDateAfter( "", '' );
        if ( false === $erg ) {
            print( "OK: HReports::GetMinElementByDateAfter 1<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateAfter 1<br>" );
        }
        $erg = HReports::GetMinElementByDateAfter( $test_array, '2012-01-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 1 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateAfter 2<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateAfter 2<br>" );
        }
        $erg = HReports::GetMinElementByDateAfter( $test_array, '2013-06-01' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 2 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateAfter 3<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateAfter 3<br>" );
        }
        $erg = HReports::GetMinElementByDateAfter( $test_array, '2013-06-01', 3, 'v' );
        if ( count( HReports::ArrayRecursiveDiff( $test_array[ 4 ], $erg ) ) == 0 ) {
            print( "OK: HReports::GetMinElementByDateAfter 4<br>" );
        }
        else
        {
            print( "Faild: HReports::GetMinElementByDateAfter 4<br>" );
        }
        exit;
    }

}

?>
