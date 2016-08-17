<?php

/**
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

require_once('feature/export/dmp/helper.dmp2013.php');
require_once('feature/export/dmp_2014/helper.dmp2014.php');

class HelperDmp {
    /**
     *
     *
     * @static
     * @access public
     * @param $date
     * @return bool|string
     */
    static public function CheckDate($date)
    {
        $minDate = date('1900-01-01');
        $maxDate = date('2050-12-31');
        if (date($date) < $minDate) {
            return '1900-01-01';
        }
        elseif (date($date) > $maxDate) {
            return '2050-12-31';
        }
        return date('Y-m-d', strtotime($date));
    }


    /**
     *
     *
     * @static
     * @access
     * @param $db
     * @param $patientId
     * @return void
     */
    static public function getKvData($db, $patientId)
    {
        if (strtotime(date('d.m.Y')) >= strtotime('01.10.2014')) {
            $result = reset(sql_query_array($db, $query = "
                SELECT
                    kv_iknr,
                    kv_nr                                                  AS versich_nr,
                    kv_status                                              AS versich_status,
                    kv_statusergaenzung                                    AS versich_statusergaenzung,
                    kv_besondere_personengruppe,
                    kv_dmp_kennzeichnung,
                    DATE_FORMAT(kv_versicherungsschutz_beginn, '%d.%m.%Y') AS kv_versicherungsschutz_beginn,
                    DATE_FORMAT(kv_versicherungsschutz_ende, '%d.%m.%Y')   AS kv_versicherungsschutz_ende,
                    kv_abrechnungsbereich                                  AS kv_abrechnungsbereich,
                    DATE_FORMAT(kv_gueltig_bis, '%d.%m.%Y')                AS vk_gueltig_bis,
                    DATE_FORMAT(kv_einlesedatum, '%d.%m.%Y')                AS kvk_einlesedatum
                FROM patient
                WHERE
                    patient_id = '{$patientId}'
            "));
        } else {
            $result = reset(sql_query_array($db, $query = "
                SELECT
                    kv_iknr,
                    kv_nr                                    AS versich_nr,
                    kv_status                                AS versich_status,
                    kv_statusergaenzung                      AS versich_statusergaenzung,
                    kv_abrechnungsbereich                    AS kv_abrechnungsbereich,
                    DATE_FORMAT(kv_gueltig_bis, '%d.%m.%Y')  AS vk_gueltig_bis,
                  DATE_FORMAT(kv_einlesedatum, '%d.%m.%Y') AS kvk_einlesedatum
                FROM patient
                WHERE
                    patient_id = '{$patientId}'
            "));
        }

        foreach ($result as $field => $value) {
            $_REQUEST[$field] = $value;
        }
    }


    public static function updateDmpNr($db, $orgId, $dmpNrCurrent)
    {
        if (is_numeric($dmpNrCurrent) == true) {
            $query = "
                SELECT
                    *,
                    IF (dmp_nr_current = $dmpNrCurrent, true, false) AS isCurrent
                FROM dmp_nummern_2013
                WHERE
                    org_id = '{$orgId}' AND (
                        dmp_nr_current = {$dmpNrCurrent} OR
                        ({$dmpNrCurrent} BETWEEN dmp_nr_start AND dmp_nr_end)
                    )
                LIMIT 1
            ";

            $result = sql_query_array($db, $query);

            if (count($result) > 0){
                $record = reset($result);

                $current = $record['dmp_nr_current'];
                $pool    = explode('|', $record['pool']);

                if ($record['isCurrent'] == true) {
                    array_shift($pool);

                    if (count($pool) > 0) {
                        $current = reset($pool);
                    }
                } else {
                    unset($pool[array_search($dmpNrCurrent, $pool)]);
                }

                $count = count($pool);
                $pool  = implode('|', $pool);

                $query = "
                    UPDATE dmp_nummern_2013
                    SET
                        nr_count = {$count},
                        dmp_nr_current = '{$current}',
                        pool = '{$pool}'
                    WHERE
                        dmp_nummern_2013_id = '{$record['dmp_nummern_2013_id']}'
                ";

                mysql_query($query);
            }
        }
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $db
     * @param $orgId
     * @param $patientId
     * @param $formId
     * @return int
     */
    public static function getDefaultDetectorId($db, $orgId, $patientId, $formId)
    {
        $config['org_id'] = $orgId;
        HDatabase::LoadExportSettings($db, $config, 'dmp_2014');
        $defaultDetectorId = (int)$config['melder_user_id'];
        if ($defaultDetectorId > 0 && strlen($patientId) && !strlen($formId)) {
            return $defaultDetectorId;
        }
        return 0;
    }


    /**
     * Ersetzt bestimmte Zeichen aus XML in HTML
     *
     * @static
     * @access public
     * @param $result Text der ersetzt werden soll
     * @return array|mixed Ersetzter Text
     */
    static public function xmlToHtml($result)
    {
        foreach ($result as $i => $record) {
            foreach ($record as $name => $value) {
                // Dieses ersetzung erzeugt in der Datenbank das richtige!!! :)
                $result[$i][$name] = str_replace(
                    array('&', '<', '>'),
                    array('&amp;amp;', '&amp;lt;', '&amp;gt;'),
                    $value
                );
            }
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $str
     * @return mixed
     */
    static public function replaceXmlSpecialChars($str)
    {
        $result = str_replace(
            array('&amp;amp;', '&amp;lt;', '&amp;gt;'),
            array('&amp;', '&lt;', '&gt;'),
            $str
        );
        return $result;
    }


    /**
     * Ersetzt alle &amp;, &lt; und &gt; durch &, < und >
     *
     * @static
     * @access public
     * @param $data
     * @return array|mixed
     */
    static public function replaceAllXmlSpecialChars($data)
    {
        if (is_array($data)) {
            foreach ($data as $i => $record) {
                if (is_array($record)) {
                    $data[$i] = HelperDmp::replaceAllXmlSpecialChars($record);
                }
                else {
                    $data[$i] = HelperDmp::replaceXmlSpecialChars($record);
                }
            }
        }
        else {
            $data = HelperDmp::replaceXmlSpecialChars($data);
        }
        return $data;
    }


    /**
     *
     *
     * @static
     * @access
     * @param $parameters
     * @param $formId
     * @param $type
     * @param $smarty
     * @param $db
     * @param $documentationDate
     * @return mixed
     */
    static public function checkForm($parameters, $formId, $type, $smarty, $db, $documentationDate)
    {
        $checkDate = strtotime('2014-10-01');
        if (strtotime($documentationDate) < $checkDate) {
            return HDmp2013::checkForm($parameters, $formId, $type, $smarty, $db);
        }
        return HDmp2014::checkForm($parameters, $formId, $type, $smarty, $db);
    }


    /**
     *
     *
     * @static
     * @access
     * @param $xml
     * @param $filename
     * @param $exportPath
     * @param $type
     * @param $fileFilter
     * @param $deleteDirectory
     * @param $documentationDate
     * @return mixed
     */
    static public function checkXml(
        $xml, $filename, $exportPath, $type, $fileFilter, $deleteDirectory, $documentationDate)
    {
        $checkDate = strtotime('2014-10-01');
        if (strtotime($documentationDate) < $checkDate) {
            return HDmp2013::checkXml($xml, $filename, $exportPath, $type, $fileFilter, $deleteDirectory);
        }
        return HDmp2014::checkXml($xml, $filename, $exportPath, $type, $fileFilter, $deleteDirectory);
    }


    /**
     *
     *
     * @static
     * @access
     * @param $parameter
     * @param $zipDirPath
     * @param $zipFilename
     * @param $documentationDate
     * @return mixed
     */
    static public function cryptZip($parameter, $zipDirPath, $zipFilename, $documentationDate)
    {
        $checkDate = strtotime('2014-10-01');
        if (strtotime($documentationDate) < $checkDate) {
            return HDmp2013::cryptZip($parameter, $zipDirPath, $zipFilename);
        }
        return HDmp2014::cryptZip($parameter, $zipDirPath, $zipFilename);
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $fields
     * @param $request
     * @return void
     */
    static public function initXml(&$fields, &$request)
    {
        $fields['xml'] = array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '');
        $fields['xml_protokoll'] = array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '');
        $fields['xml_status'] = array('req' => 0, 'size' => '', 'maxlen' => '', 'type' => 'hidden', 'ext' => '');
        $request['xml'] = "";
        $request['xml_status'] = 0;
        $request['xml_protokoll'] =
            "<?xml version=\"1.0\" encoding=\"ISO-8859-15\"?>" .
            "<data>" .
            "    <parameter>" .
            "        <ABBRUCH>0</ABBRUCH>" .
            "        <FEHLER>0</FEHLER>" .
            "        <WARNUNGEN>0</WARNUNGEN>" .
            "        <ERGEBNIS_TEXT>Ok</ERGEBNIS_TEXT>" .
            "    </parameter>" .
            "</data>";
    }


    /**
     *
     *
     * @access
     * @param $dmpObj
     * @param $request
     * @param $session
     * @return void
     */
    static public function setXml($result, &$request, &$session)
    {
        $request['xml'] = $result['xml'];
        $request['xml_status'] = $result['xml_status'];
        $request['xml_protokoll'] = $result['xml_protocol'];
        $session['dmp_xml_protokoll'] = $result['xml_protocol'];
    }


    /**
     *
     *
     * @static
     * @access public
     * @param $protocol
     * @return array
     */
    static public function parseErrorsFromProtocol($protocol)
    {
        $errors = array();
        if (strlen($protocol) > 0) {
            $tmpXml = new SimpleXMLElement($protocol);
            foreach ($tmpXml->children() as $child) {
                if ($child->getName() == "record") {
                    $group = utf8_decode($child->GRUPPE);
                    $errorNo = utf8_decode($child->FEHLER_NR);
                    $msg = utf8_decode($child->MELDUNG);
                    $warning = '';
                    if ((false !== strpos($child->FEHLER_NR, "(W", 0)) ||
                        (false !== strpos($child->FEHLER_NR, "(I", 0))) {
                        $warning = '[warning] ';
                    }
                    $errors[] = "{$warning}[{$group}]: ({$errorNo}) {$msg}";
                }
            }
        }
        return $errors;
    }


    /**
     *
     *
     * @access
     * @param $errors
     * @return array
     */
    public static function removeJavaVersionWarning($errors) {
        // Ticket #12416
        $result = array();
        foreach ($errors as $error) {
            if (false === strpos($error, "System ist noch eine ältere Version")) {
                $result[] = $error;
            }
        }
        return $result;
    }


    /**
     *
     *
     * @static
     * @access public
     * @param      $db
     * @param      $type
     * @param      $dbid
     * @param null $dmpProtocol
     * @param null $valid
     * @return void
     */
    static public function showDmpErrors($db, $type, $dbid, $dmpProtocol = NULL, $valid = NULL)
    {
        if (is_null($dmpProtocol)) {
            $dmpProtocol = dlookup(
                $db,
                "dmp_brustkrebs_{$type}_2013", 'xml_protokoll', "dmp_brustkrebs_{$type}_2013_id={$dbid}"
            );
        }
        if (!is_null($dmpProtocol) &&
            strlen($dmpProtocol) > 0) {
            $errors = HelperDmp::parseErrorsFromProtocol($dmpProtocol);
            // Ticket #12416
            $errors = HelperDmp::removeJavaVersionWarning($errors);
            $warns = array();
            $i = 0;
            foreach ($errors as $error) {
                $i++;
                if (!is_null($valid)) {
                    $valid->set_warn(12, "dmp_brustkrebs_{$type}_2013_id", null, "{$i} {$error}");
                }
                else {
                    $warns[] = "<lu><li>{$i} {$error}</li></lu>";
                }
            }
            if (is_null($valid)) {
                $_SESSION['sess_warn'] = $warns;
            }
        }
    }


    /**
     *
     *
     * @static
     * @access
     * @param $statusCode
     * @return string
     */
    static public function getStatusText($statusCode) {
        switch ($statusCode) {
            case 3 :
                return 'Abbruch';
            case 2 :
                return 'Fehler';
            case 1 :
                return 'Warnung';
            default :
                return 'Ok';
        }
    }
}
