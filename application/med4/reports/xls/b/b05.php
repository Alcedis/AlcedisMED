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

require_once("core/class/report/helper.reports.php");
require_once("reports/scripts/b/reportExtension.php");
require_once("reports/xls/b/b01.php");

class reportContentB05 extends reportContentB01
{

    const GENERATE_TYPE_PATIENT = "patient";
    const GENERATE_TYPE_MATRIX = "matrix";

    protected $_kassen_daten = array();

    public function init($renderer){
        if ($this->_type == 'pdf') {
            $renderer->addPage();
        }
    }

    //should be overwritten
    public function header()
    {
    }

    public function generate($renderer)
    {
        $separatorRow = HReports::SEPARATOR_ROWS;
        $separatorCol = HReports::SEPARATOR_COLS;
        $nachsorgeJahr = $this->getParam('nachsorgeJahr', intval(date('Y')) - 1);
        $startJahr = intval(date('Y')) - 11;
        $auditJahr = intval(date('Y'));
        $generateType = strtolower($this->getParam('rohdatenx', 'default'));
        $relevantSelectWhere =
            "ts.erkrankung_id = t.erkrankung_id AND " .
            "ts.diagnose_seite IN ('B', t.diagnose_seite) AND ts.anlass = t.anlass";
        $relevantSelectOrder =
            "ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1";
        $this->setSubDir('b');
        $this->setParam('sub', 'b');
        // Alle datensätze holen
        //$this->setParam('dontUseNz', true);
        $additionalContent['joins'] = array(
            "LEFT JOIN tumorstatus ts_rezidiv ON ts_rezidiv.erkrankung_id=sit.erkrankung_id
                                                 AND LEFT(ts_rezidiv.anlass, 1) = 'r'"
        );
        $additionalContent['selects'] = array(
            "(SELECT ts.t FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id
                                                    AND ts.anlass = t.anlass
                                                    AND ts.t IS NOT NULL
                                              ORDER BY
                                                  ts.datum_sicherung DESC,
                                                  ts.sicherungsgrad ASC,
                                                  ts.datum_beurteilung DESC LIMIT 1)                AS t"
        );
        $additionalContent['fields'] = array(
            "sit.patient_id                                          AS 'patient_id'",
            "sit.t                                                   AS 't'",
            "sit.anlass                                              AS 'anlass'", // Für Ticket #15369
            "IF(
                 LEFT(sit.cn,1) = 'c' AND RIGHT(sit.cn, 4) = '(sn)',
                 sit.cn,
                 NULL
             ) AS 'cn_sn'",
            "IF( MAX(n.nachsorge_id) IS NOT NULL,
                 GROUP_CONCAT( DISTINCT
                     IF( n.nachsorge_id IS NOT NULL,
                         CONCAT_WS('{$separatorCol}',
                             IFNULL(n.nachsorge_id, ''),
                             IFNULL(n.datum, ''),
                             IFNULL(n.response_klinisch, ''),
                             IFNULL(n.malignom, '')
                         ),
                         NULL
                     )
                     SEPARATOR '{$separatorRow}'
                 ),
                 NULL
             )                                                       AS nachsorgen",
            "IF( MAX(ts_rezidiv.tumorstatus_id) IS NOT NULL,
                 GROUP_CONCAT( DISTINCT
                     IF( ts_rezidiv.tumorstatus_id IS NOT NULL,
                         CONCAT_WS('{$separatorCol}',
                             IFNULL(ts_rezidiv.tumorstatus_id, ''),
                             IFNULL(ts_rezidiv.datum_sicherung, ''),
                             IFNULL(ts_rezidiv.rezidiv_lokal, ''),
                             IFNULL(ts_rezidiv.rezidiv_lk, ''),
                             IFNULL(ts_rezidiv.rezidiv_metastasen, ''),
                             IFNULL(ts_rezidiv.diagnose_seite, '')
                         ),
                         NULL
                     )
                     SEPARATOR '{$separatorRow}'
                 ),
                 NULL
             )                                                       AS rezidive",
            "IF( MAX(x.abschluss_id) IS NOT NULL,
                 GROUP_CONCAT( DISTINCT
                     IF( x.abschluss_id IS NOT NULL,
                         CONCAT_WS('{$separatorCol}',
                             IFNULL(x.abschluss_id, ''),
                             IFNULL(x.updatetime, x.createtime),
                             IFNULL(x.abschluss_grund, ''),
                             IFNULL(x.todesdatum, ''),
                             IFNULL(x.tod_tumorassoziation, '')
                         ),
                         NULL
                     )
                     SEPARATOR '{$separatorRow}'
                 ),
                 NULL
             )                                                       AS abschluesse"
        );
        $bz01 = $this->loadRessource('b01', $additionalContent);
        // Prüfen für Bilateral
        // Änderungen für Ticket #15369
        $oldErkrankungId = -1;
        $patientRows = array();
        $data = array();
        foreach ($bz01 as $row) {
            // Es muss ein Primärfall sein
            if ($this->IsTrue($row['primaerfall'])) {
                // Gibt es L und R pro Fall!
                // Dann Feld eine führen $bz01['bilateral'] = '1' oder '0'
                if (($oldErkrankungId != $row['erkrankungId']) &&
                    (count($patientRows) > 0)) {
                    if ((2 == count($patientRows)) &&
                        ($patientRows[0]['seite'] != $patientRows[1]['seite'])) {
                        $patientRows[0]['bilateral'] = true;
                        $patientRows[1]['bilateral'] = true;
                    }
                    $data = array_merge($data, $patientRows);
                    $patientRows = array();
                }
                $oldErkrankungId = $row['erkrankungId'];
                $row['bilateral'] = false;
                $patientRows[] = $row;
            }
        }
        if (count($patientRows) > 0) {
            if ((2 == count($patientRows)) &&
                ($patientRows[0]['seite'] != $patientRows[1]['seite'])) {
                $patientRows[0]['bilateral'] = true;
                $patientRows[1]['bilateral'] = true;
            }
            $data = array_merge($data, $patientRows);
        }
        // Hauptbearbeitung
        $matrix = $this->_generateDefaultMatrix($startJahr, $nachsorgeJahr, $generateType);
        foreach($data as $row) {
            //if ($this->IsTrue($row['primaerfall'])) { // Sind nur noch primaerfaelle hier!!!
            $row['erkrankungen'] = $this->_getErkrankungenNachPrimaererkrankung($row);
            // Alle Nachsorgen
            $nachsorgen = array();
            if (strlen($row['nachsorgen']) > 0) {
                $nachsorgen = HReports::RecordStringToArray(
                    $row['nachsorgen'],
                    array(
                        "nachsorge_id",
                        "datum",
                        "response_klinisch",
                        "malignom"
                    )
                );
            }
            $row['nachsorgen'] = $nachsorgen;
            // Alle Rezidive
            $rezidive = array();
            if (strlen($row['rezidive']) > 0) {
                $rezidive = HReports::RecordStringToArray(
                    $row['rezidive'],
                    array(
                        "tumorstatus_id",
                        "datum",
                        "rezidiv_lokal",
                        "rezidiv_lk",
                        "rezidiv_metastasen",
                        "seite"
                    )
                );
            }
            $row['rezidive'] = $rezidive;
            // Alle Abschlüsse
            $abschluesse = array();
            if (strlen($row['abschluesse']) > 0) {
                $abschluesse = HReports::RecordStringToArray(
                    $row['abschluesse'],
                    array(
                        "abschluss_id",
                        "datum",
                        "abschluss_grund",
                        "todesdatum",
                        "tod_tumorassoziation"
                    )
                );
            }
            $row['abschluesse'] = $abschluesse;
            $this->_generateMatrix($matrix, $row, $startJahr, $nachsorgeJahr, $generateType);
            //}
        }
        $this->_prepareMatrix($matrix, $generateType);
        if (self::GENERATE_TYPE_PATIENT == $generateType) {
            $this->_title = "Matrix - Ergebnisqualität Primärbehandlung (Auditjahr {$auditJahr}) [Patienten-Rohdaten]";
        }
        else if (self::GENERATE_TYPE_MATRIX == $generateType) {
            $this->_title = "Matrix - Ergebnisqualität Primärbehandlung (Auditjahr {$auditJahr}) [Matrix-Rohdaten]";
        }
        else {
            $this->_title = "Matrix - Ergebnisqualität Primärbehandlung (Auditjahr {$auditJahr})";
        }
        $this->_data = $matrix;
        $this->writeXLS();
    }


    /**
     *
     *
     * @access
     * @param $startJahr
     * @param $nachsorgeJahr
     * @param $generateType
     * @return void
     */
    protected function _generateDefaultMatrix($startJahr, $nachsorgeJahr, $generateType)
    {
        $data = array();

        for ($i = $startJahr; $i <= intval($nachsorgeJahr); $i++) {
            if (self::GENERATE_TYPE_PATIENT == $generateType) {
            }
            else if (self::GENERATE_TYPE_MATRIX == $generateType) {
                // TODO: ...
            }
            else {
                // Änderungen für Ticket #15354
                $data["" +$i] = array(
                    'A' => '',
                    'Jahr der Erstdiagnose' => $i,
                    'Primärfälle bei Männern und Frauen' => 0,
                    'Tis (=DCIS)' => 0,
                    'T1' => 0,
                    'T2' => 0,
                    'T3' => 0,
                    'T4' => 0,
                    'N+' => 0,
                    'M1' => 0,
                    'nicht zuzuordnen' => 0,
                    'L' => '',
                    'davon posttherapeutisch nicht tumorfrei' => 0,
                    'Grundgesamtheit Follow-Up' => 0,
                    'Follow-Up-Daten vom Klinischen Krebsregister' => 0,
                    'Follow-Up-Daten vom Zentrum' => 0,
                    'keine Rückmeldung' => 0,
                    'Follow-Up Quote in %' => 0.0,
                    'S' => '',
                    'kein Ergebnis pro Fall eingetreten' => 0,
                    '1 Ergebnis in Spalte V, W, X und Y (lebend)' => 0,
                    'Lokalrezidiv (lebend)' => 0,
                    'Lymphknotenrezidiv (lebend)' => 0,
                    'Fernmetastasen (lebend)' => 0,
                    'Diagnose Zweitmalignom im Verlauf (lebend)' => 0,
                    '1 Ergebnis in Spalte AA, AB, AC und AD (verstorben)' => 0,
                    'Lokalrezidiv (verstorben)' => 0,
                    'Lymphknotenrezidiv (verstorben)' => 0,
                    'Fernmetastasen (verstorben)' => 0,
                    'Diagnose Zweitmalignom im Verlauf (verstorben)' => 0,
                    'Verstorben ohne Ergebnis in Follow-Up / unbekannt' => 0
                );
            }
        }
        return $data;
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $row
     * @param $nachsorgeJahr
     * @param $generateType
     * @return void
     */
    protected function _generateMatrix(&$data, $row, $startJahr, $nachsorgeJahr, $generateType)
    {
        $erstdiagnoseJahr = date('Y', strtotime($row['bezugsdatum']));
        if (self::GENERATE_TYPE_PATIENT == $generateType) {
            if ((intval($erstdiagnoseJahr) >= intval($startJahr)) &&
                (intval($erstdiagnoseJahr) <= intval($nachsorgeJahr))) {
                $item = array();
                $item['Patientnr.'] = $row['patient_nr'];
                $item['Nachname'] = $row['nachname'];
                $item['Vorname'] = $row['vorname'];
                $item['Geburtsdatum'] = $row['geburtsdatum'];
                $item['Diagnose'] = $row['diagnose'];
                $item['Seite'] = $row['seite'];
                $item['Bilateral'] = $row['bilateral'];
                $item['Bezugsdatum'] = $row['bezugsdatum'];
                $item['Tis (=DCIS)'] = 0;
                $item['T1'] = 0;
                $item['T2'] = 0;
                $item['T3'] = 0;
                $item['T4'] = 0;
                $item['N+'] = 0;
                $item['M1'] = 0;
                $item['nicht zuzuordnen'] = 0;
                if ($this->_checkVarA($row, 'Tis') || $this->_checkVarB($row, 'Tis')) {
                    $item['Tis (=DCIS)'] = 1;
                } else if ($this->_checkVarA($row, 'T1')  || $this->_checkVarB($row, 'T1')) {
                    $item['T1'] = 1;
                } else if ($this->_checkVarA($row, 'T2')  || $this->_checkVarB($row, 'T2')) {
                    $item['T2'] = 1;
                } else if ($this->_checkVarA($row, 'T3')  || $this->_checkVarB($row, 'T3')) {
                    $item['T3'] = 1;
                } else if ($this->_checkVarA($row, 'T4')  || $this->_checkVarB($row, 'T4')) {
                    $item['T4'] = 1;
                } else if ($this->_checkVarNA($row)  || $this->_checkVarNB($row)) {
                    $item['N+'] = 1;
                } else if ($this->_checkVarM1A($row)  || $this->_checkVarM1B($row)) {
                    $item['M1'] = 1;
                } else {
                    $item['nicht zuzuordnen'] = 1;
                }
                $item['davon posttherapeutisch nicht tumorfrei'] = 0;
                if (('1' == $row['r']) || ('2' == $row['r'])) {
                    $item['davon posttherapeutisch nicht tumorfrei'] = 1;
                }
                $item['Follow-Up-Daten vom Zentrum'] = 0;
                if ($this->_checkColumnP($nachsorgeJahr, $row)) {
                    $item['Follow-Up-Daten vom Zentrum'] = 1;
                }
                // Lebenden
                $item['1 Ergebnis in Spalte V, W, X und Y (lebend)'] = 0;
                if ($this->_checkColumnU($nachsorgeJahr, $row)) {
                    $item['1 Ergebnis in Spalte V, W, X und Y (lebend)'] = 1;
                }
                $item['Lokalrezidiv (lebend)'] = 0;
                if ($this->_checkColumnV($nachsorgeJahr, $row)) {
                    $item['Lokalrezidiv (lebend)'] = 1;
                }
                $item['Lymphknotenrezidiv (lebend)'] = 0;
                if ($this->_checkColumnW($nachsorgeJahr, $row)) {
                    $item['Lymphknotenrezidiv (lebend)'] = 1;
                }
                $item['Fernmetastasen (lebend)'] = 0;
                if ($this->_checkColumnX($nachsorgeJahr, $row)) {
                    $item['Fernmetastasen (lebend)'] = 1;
                }
                $item['Diagnose Zweitmalignom im Verlauf (lebend)'] = 0;
                if ($this->_checkColumnY($nachsorgeJahr, $row)) {
                    $item['Diagnose Zweitmalignom im Verlauf (lebend)'] = 1;
                }
                // Verstorbene
                $item['1 Ergebnis in Spalte AA, AB, AC und AD (verstorben)'] = 0;
                if ($this->_checkColumnZ($nachsorgeJahr, $row)) {
                    $item['1 Ergebnis in Spalte AA, AB, AC und AD (verstorben)'] = 1;
                }
                $item['Lokalrezidiv (verstorben)'] = 0;
                if ($this->_checkColumnAA($nachsorgeJahr, $row)) {
                    $item['Lokalrezidiv (verstorben)'] = 1;
                }
                $item['Lymphknotenrezidiv (verstorben)'] = 0;
                if ($this->_checkColumnAB($nachsorgeJahr, $row)) {
                    $item['Lymphknotenrezidiv (verstorben)'] = 1;
                }
                $item['Fernmetastasen (verstorben)'] = 0;
                if ($this->_checkColumnAC($nachsorgeJahr, $row)) {
                    $item['Fernmetastasen (verstorben)'] = 1;
                }
                $item['Diagnose Zweitmalignom im Verlauf (verstorben)'] = 0;
                if ($this->_checkColumnAD($nachsorgeJahr, $row)) {
                    $item['Diagnose Zweitmalignom im Verlauf (verstorben)'] = 1;
                }
                $item['Verstorben ohne Ergebnis in Follow-Up / unbekannt'] = 0;
                if ($this->_checkColumnAE($nachsorgeJahr, $row)) {
                    $item['Verstorben ohne Ergebnis in Follow-Up / unbekannt'] = 1;
                }
                $data[] = $item;
            }
        }
        else if (self::GENERATE_TYPE_MATRIX == $generateType) {
            if ((intval($erstdiagnoseJahr) >= intval($startJahr)) &&
                (intval($erstdiagnoseJahr) <= intval($nachsorgeJahr))) {
                $item = array();
                $item['Patientnr.'] = $row['patient_nr'];
                $item['Nachname'] = $row['nachname'];
                $item['Vorname'] = $row['vorname'];
                $item['Geburtsdatum'] = $row['geburtsdatum'];
                $item['Diagnose'] = $row['diagnose'];
                $item['Seite'] = $row['seite'];
                $item['Bilateral'] = $row['bilateral'];
                $item['Bezugsjahr'] = $row['bezugsdatum'];
                $item['pT'] = $row['pt'];
                $item['pN'] = $row['pn'];
                $item['pN(sn)'] = $row['pn_sn'];
                $item['M'] = $row['m'];
                $item['cT'] = $row['ct'];
                $item['cN'] = $row['cn'];
                $item['R'] = $row['r'];
                $item['Datum Primär-OP'] = $row['datumprimaer_op'];
                $item['Durchgef. neoadj. Therapie'] = $row['durchgef_neoadj_therapie'];

                $item['Datum Nachsorge mit Response klinisch/radiologisch'] = '';
                $nachsorge = $this->_getNachsorgeWithValue($row['nachsorgen'], 'response_klinisch', -1, $nachsorgeJahr);
                if (count($nachsorge) > 0) {
                    $item['Datum Nachsorge mit Response klinisch/radiologisch'] = $nachsorge['datum'];
                }

                $item['Datum Rezidiv im gewählten Jahr'] = '';
                $rezidiv = $this->_getRezidivInYear($row['rezidive'], $nachsorgeJahr);
                if (count($rezidiv) > 0) {
                    $item['Datum Rezidiv im gewählten Jahr'] = $rezidiv['datum'];
                }

                $item['Todesdatum'] = '';
                $abschluss = $this->_getTodesdatum($row['abschluesse']);
                if (count($abschluss) > 0) {
                    $item['Todesdatum'] = $abschluss['todesdatum'];
                }

                $item['Todesdatum - Patient verstorben bis Ende gewähltes Jahr'] = '';
                $abschluss = $this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr);
                if (count($abschluss) > 0) {
                    $item['Todesdatum - Patient verstorben bis Ende gewähltes Jahr'] = $abschluss['todesdatum'];
                }

                $item['Hat Rezidive'] = (count($row['rezidive']) > 0) ? '1' : '';

                $item['Datum der Sicherung Rezidiv mit lokal'] = '';
                $rezidiv = $this->_getRezidivWithValue(
                    $row['rezidive'], 'rezidiv_lokal', '1', $row['bilateral'], $row['seite']
                );
                if (false !== $rezidiv) {
                    $item['Datum der Sicherung Rezidiv mit lokal'] = $rezidiv['datum'];
                }

                $item['Datum der Sicherung Rezidiv mit lokoregionären LK'] = '';
                $rezidiv = $this->_getRezidivWithValue(
                    $row['rezidive'], 'rezidiv_lk', '1', $row['bilateral'], $row['seite']
                );
                if (false !== $rezidiv) {
                    $item['Datum der Sicherung Rezidiv mit lokoregionären LK'] = $rezidiv['datum'];
                }

                $item['Datum der Sicherung Rezidiv mit Metastasen'] = '';
                $rezidiv = $this->_getRezidivWithValue(
                    $row['rezidive'], 'rezidiv_metastasen', '1', $row['bilateral'], $row['seite']
                );
                if (false !== $rezidiv) {
                    $item['Datum der Sicherung Rezidiv mit Metastasen'] = $rezidiv['datum'];
                }

                $item['Zweiterkrankung'] = '';
                $item['Datum der Sicherung Zweiterkrankung'] = '';
                $zweiterkrankung = $this->_getRelevantErkrankungen($row['erkrankungen'], $row['bezugsdatum']);
                if (count($zweiterkrankung) > 0) {
                    $item['Zweiterkrankung'] = $zweiterkrankung['erkrankung'];
                    $item['Datum der Sicherung Zweiterkrankung'] = $zweiterkrankung['diagnoseDatum'];
                }

                $item['Datum Nachsorge mit neues Malignom festgestellt'] = '';
                $nachsorge = $this->_getNachsorgeWithMalignomChecked($row['nachsorgen'], $nachsorgeJahr);
                if (count($nachsorge) > 0) {
                    $item['Datum Nachsorge mit neues Malignom festgestellt'] = $nachsorge['datum'];
                }

                $data[] = $item;
            }
        }
        else {
            if ((intval($erstdiagnoseJahr) >= intval($startJahr)) &&
                (intval($erstdiagnoseJahr) <= intval($nachsorgeJahr))) {
                if ($this->_checkVarA($row, 'Tis') || $this->_checkVarB($row, 'Tis')) {
                    $data[$erstdiagnoseJahr]['Tis (=DCIS)']++;
                } else if ($this->_checkVarA($row, 'T1')  || $this->_checkVarB($row, 'T1')) {
                    $data[$erstdiagnoseJahr]['T1']++;
                } else if ($this->_checkVarA($row, 'T2')  || $this->_checkVarB($row, 'T2')) {
                    $data[$erstdiagnoseJahr]['T2']++;
                } else if ($this->_checkVarA($row, 'T3')  || $this->_checkVarB($row, 'T3')) {
                    $data[$erstdiagnoseJahr]['T3']++;
                } else if ($this->_checkVarA($row, 'T4')  || $this->_checkVarB($row, 'T4')) {
                    $data[$erstdiagnoseJahr]['T4']++;
                } else if ($this->_checkVarNA($row)  || $this->_checkVarNB($row)) {
                    $data[$erstdiagnoseJahr]['N+']++;
                } else if ($this->_checkVarM1A($row)  || $this->_checkVarM1B($row)) {
                    $data[$erstdiagnoseJahr]['M1']++;
                } else {
                    $data[$erstdiagnoseJahr]['nicht zuzuordnen']++;
                }
                if (('1' == $row['r']) || ('2' == $row['r'])) {
                    $data[$erstdiagnoseJahr]['davon posttherapeutisch nicht tumorfrei']++;
                }
                if ($this->_checkColumnP($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Follow-Up-Daten vom Zentrum']++;
                }
                // Lebenden
                if ($this->_checkColumnU($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['1 Ergebnis in Spalte V, W, X und Y (lebend)']++;
                }
                if ($this->_checkColumnV($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Lokalrezidiv (lebend)']++;
                }
                if ($this->_checkColumnW($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Lymphknotenrezidiv (lebend)']++;
                }
                if ($this->_checkColumnX($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Fernmetastasen (lebend)']++;
                }
                if ($this->_checkColumnY($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Diagnose Zweitmalignom im Verlauf (lebend)']++;
                }
                // Verstorbene
                if ($this->_checkColumnZ($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['1 Ergebnis in Spalte AA, AB, AC und AD (verstorben)']++;
                }
                if ($this->_checkColumnAA($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Lokalrezidiv (verstorben)']++;
                }
                if ($this->_checkColumnAB($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Lymphknotenrezidiv (verstorben)']++;
                }
                if ($this->_checkColumnAC($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Fernmetastasen (verstorben)']++;
                }
                if ($this->_checkColumnAD($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Diagnose Zweitmalignom im Verlauf (verstorben)']++;
                }
                if ($this->_checkColumnAE($nachsorgeJahr, $row)) {
                    $data[$erstdiagnoseJahr]['Verstorben ohne Ergebnis in Follow-Up / unbekannt']++;
                }
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $data
     * @param $generateType
     * @return void
     */
    protected function _prepareMatrix(&$data, $generateType)
    {
        if (self::GENERATE_TYPE_PATIENT == $generateType) {
            // TODO: ...
        }
        else if (self::GENERATE_TYPE_MATRIX == $generateType) {
            // TODO: ...
        }
        else {
            foreach ($data as $year => $row) {
                $row['Primärfälle bei Männern und Frauen'] =
                    $row['Tis (=DCIS)'] +
                    $row['T1'] +
                    $row['T2'] +
                    $row['T3'] +
                    $row['T4'] +
                    $row['N+'] +
                    $row['M1'] +
                    $row['nicht zuzuordnen'];
                $row['Grundgesamtheit Follow-Up'] =
                    $row['Primärfälle bei Männern und Frauen'] -
                    $row['davon posttherapeutisch nicht tumorfrei'];
                $row['keine Rückmeldung'] =
                    $row['Grundgesamtheit Follow-Up'] -
                    $row['Follow-Up-Daten vom Klinischen Krebsregister'] -
                    $row['Follow-Up-Daten vom Zentrum'];
                $row['Follow-Up Quote in %'] = 0.0;
                if ($row['Grundgesamtheit Follow-Up'] > 0) {
                    $row['Follow-Up Quote in %'] = sprintf('%.2f', round(
                        (($row['Follow-Up-Daten vom Klinischen Krebsregister'] +
                                $row['Follow-Up-Daten vom Zentrum']) /
                            $row['Grundgesamtheit Follow-Up']) * 100.0, 2));
                }
                $row['kein Ergebnis pro Fall eingetreten'] =
                    ($row['Follow-Up-Daten vom Klinischen Krebsregister'] +
                        $row['Follow-Up-Daten vom Zentrum']) -
                    $row['1 Ergebnis in Spalte V, W, X und Y (lebend)'] -
                    $row['1 Ergebnis in Spalte AA, AB, AC und AD (verstorben)'] -
                    $row['Verstorben ohne Ergebnis in Follow-Up / unbekannt'];
                $data[$year] = $row;
            }
        }
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _hasResponseKlinischOrRezidivInYear($nachsorgeJahr, $row)
    {
        if (count($this->_getNachsorgeWithValue($row['nachsorgen'], 'response_klinisch', -1, $nachsorgeJahr)) > 0) {
            return true;
        }
        else if (count($this->_getRezidivInYear($row['rezidive'], $nachsorgeJahr)) > 0) {
            return true;
        }
        return false;
    }

    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkColumnP($nachsorgeJahr, $row)
    {
        // Anpassungen Ticket #15258
        if ($row['r'] != '1' && $row['r'] != '2') {
            if ($this->_hasResponseKlinischOrRezidivInYear($nachsorgeJahr, $row)) {
                return true;
            }
            else if (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) {
                return true;
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnU($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            $this->_isPatientAliveToYear($row['abschluesse'], $nachsorgeJahr) &&
            (($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lokal', '1', $row['bilateral'], $row['seite']) !== false) ||
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lk', '1', $row['bilateral'], $row['seite']) !== false) ||
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_metastasen', '1', $row['bilateral'], $row['seite']) !== false) ||
             $this->_hasZweiterkrankung($nachsorgeJahr, $row))
        ) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasRezidivWithSide($row) {
        if (count($row['rezidive']) > 0) {
            if ('1' != $row['bilateral']) {
                return true;
            }
            foreach ($row['rezidive'] as $rezidiv) {
                if ($rezidiv['seite'] == $row['seite']) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnV($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            $this->_isPatientAliveToYear($row['abschluesse'], $nachsorgeJahr) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lokal', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnW($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            $this->_isPatientAliveToYear($row['abschluesse'], $nachsorgeJahr) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lk', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnX($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            $this->_isPatientAliveToYear($row['abschluesse'], $nachsorgeJahr) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_metastasen', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _hasZweiterkrankung($nachsorgeJahr, $row)
    {
        if ((count($this->_getRelevantErkrankungen($row['erkrankungen'], $row['bezugsdatum'])) > 0) ||
            (count($this->_getNachsorgeWithMalignomChecked($row['nachsorgen'], $nachsorgeJahr)) > 0)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnY($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            $this->_isPatientAliveToYear($row['abschluesse'], $nachsorgeJahr) &&
            $this->_hasZweiterkrankung($nachsorgeJahr, $row)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnZ($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            (($this->_getRezidivWithValue(
                 $row['rezidive'], 'rezidiv_lokal', '1', $row['bilateral'], $row['seite']) !== false) ||
             ($this->_getRezidivWithValue(
                 $row['rezidive'], 'rezidiv_lk', '1', $row['bilateral'], $row['seite']) !== false) ||
             ($this->_getRezidivWithValue(
                 $row['rezidive'], 'rezidiv_metastasen', '1', $row['bilateral'], $row['seite']) !== false) ||
             $this->_hasZweiterkrankung($nachsorgeJahr, $row))
        ) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnAA($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lokal', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnAB($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_lk', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnAC($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            ($this->_getRezidivWithValue(
                $row['rezidive'], 'rezidiv_metastasen', '1', $row['bilateral'], $row['seite']) !== false)) {
            return true;
        }
        return false;
    }



    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnAD($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            $this->_hasZweiterkrankung($nachsorgeJahr, $row)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $nachsorgeJahr
     * @param $row
     * @return bool
     */
    protected function _checkColumnAE($nachsorgeJahr, $row)
    {
        if ($this->_checkColumnP($nachsorgeJahr, $row) &&
            (count($this->_isPatientDeadToYear($row['abschluesse'], $nachsorgeJahr)) > 0) &&
            !$this->_hasZweiterkrankung($nachsorgeJahr, $row) &&
            (count($row['rezidive']) == 0)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param      $nachsorgen
     * @param      $field
     * @param      $value
     * @param null $nachsorgeJahr
     * @return bool
     */
    protected function _getNachsorgeWithValue($nachsorgen, $field, $value = -1, $nachsorgeJahr = null)
    {
        if (is_array($nachsorgen)) {
            foreach ($nachsorgen as $nachsorge) {
                if (isset($nachsorge[$field])) {
                    if ((($value == -1) &&
                            (strlen($nachsorge[$field]) > 0)) ||
                        ($nachsorge[$field] == $value)) {
                        if (($nachsorgeJahr !== null) &&
                            (intval(date('Y', strtotime($nachsorge['datum']))) != intval($nachsorgeJahr)) ) {
                            continue;
                        }
                        return $nachsorge;
                    }
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $nachsorgen
     * @param $bezugsdatum
     * @return array
     */
    protected function _getNachsorgeWithMalignomChecked($nachsorgen, $bezugsdatum)
    {
        if (is_array($nachsorgen)) {
            foreach ($nachsorgen as $nachsorge) {
                if (($nachsorge['datum'] > $bezugsdatum) &&
                    ($nachsorge['malignom'] == '1')) {
                    return $nachsorge;
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $abschluesse
     * @return array
     */
    protected function _getTodesdatum($abschluesse)
    {
        if (is_array($abschluesse)) {
            foreach ($abschluesse as $abschluss) {
                if (('tot' == $abschluss['abschluss_grund']) &&
                    (strlen($abschluss['todesdatum'])) > 0) {
                    return $abschluss;
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $abschluesse
     * @param $nachsorgeJahr
     * @return bool
     */
    protected function _isPatientDeadToYear($abschluesse, $nachsorgeJahr)
    {
        if (is_array($abschluesse)) {
            foreach ($abschluesse as $abschluss) {
                if (('tot' == $abschluss['abschluss_grund']) &&
                    (strlen($abschluss['todesdatum']) > 0) &&
                    (intval(date('Y', strtotime($abschluss['todesdatum']))) <= intval($nachsorgeJahr))) {
                    return $abschluss;
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $abschluesse
     * @param $nachsorgeJahr
     * @return bool
     */
    protected function _isPatientAliveToYear($abschluesse, $nachsorgeJahr)
    {
        if (count($abschluesse) == 0) {
            return true;
        }
        if (is_array($abschluesse)) {
            foreach ($abschluesse as $abschluss) {
                if (('tot' != $abschluss['abschluss_grund']) ||
                    ((strlen($abschluss['todesdatum']) > 0) &&
                        (intval(date('Y', strtotime($abschluss['todesdatum']))) > intval($nachsorgeJahr)))) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $erkrankugen
     * @return array
     */
    protected function _getRelevantErkrankungen($erkrankugen, $bezugsdatum)
    {
        if (is_array($erkrankugen)) {
            foreach ($erkrankugen as $erkrankung) {
                // UND es muss einen Tumorstatus mit anlass == 'p' geben
                if ('1' !== $erkrankung['hasPrimaerTumorstatus']) {
                    continue;
                }
                // Es darf C44 geben nur nicht mit 809 und 811!!!
                // Ticket #15358
                if ((substr($erkrankung['diagnose'], 0, 3) === 'C44') &&
                    ((substr($erkrankung['morphologie'], 0, 3) === '809') ||
                        (substr($erkrankung['morphologie'], 0, 3) === '810') ||
                        (substr($erkrankung['morphologie'], 0, 3) === '811'))) {
                    continue;
                }
                if ($erkrankung['diagnoseDatum'] > $bezugsdatum) {
                    return $erkrankung;
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $rezidive
     * @param $field
     * @param $value
     * @return array|bool
     */
    protected function _getRezidivWithValue($rezidive, $field, $value, $isBilateral, $seite)
    {
        // hier muss noch auf Seite geprüft werden aber nur wenn es Bilateral ist!!! Ansonsten so wie es jetzt ist!!!
        // Ticket #15369
        if (is_array($rezidive)) {
            foreach ($rezidive as $rezidiv) {
                if (false === $isBilateral) {
                    if (isset($rezidiv[$field]) &&
                        ($rezidiv[$field] == $value)) {
                        return $rezidiv;
                    }
                }
                else {
                    if (isset($rezidiv[$field]) &&
                        ($rezidiv[$field] == $value) &&
                        ($rezidiv['seite'] === $seite)) {
                        return $rezidiv;
                    }
                }
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $rezidive
     * @param $nachsorgeJahr
     * @return bool
     */
    protected function _getRezidivInYear($rezidive, $nachsorgeJahr)
    {
        if (is_array($rezidive)) {
            foreach ($rezidive as $rezidiv) {
                if (intval(date('Y', strtotime($rezidiv['datum']))) == intval($nachsorgeJahr)) {
                    return $rezidiv;
                }
            }
        }
        return array();
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ptVal
     * @return bool
     */
    protected function _checkVarA($row, $ptVal)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNoNeoadjuvantTherapy($row) === true) &&
            (str_starts_with($row['pt'], "p{$ptVal}") === true) &&
            ($this->_hasN0($row, $ptVal) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @param $ctVal
     * @return bool
     */
    protected function _checkVarB($row, $ctVal)
    {
        if (((($this->_hasPrimaryOp($row) === true) && ($this->_hasNeoadjuvantTherapy($row) === true)) ||
                ($this->_hasNoPrimaryOp($row) === true) ||
                ($this->_hasPt0($row) === true)) &&
            (str_starts_with($row['ct'], "c{$ctVal}") === true) &&
            ($this->_hasCn0($row) === true) &&
            ($this->_hasCm0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarNA($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNoNeoadjuvantTherapy($row) === true) &&
            ($this->_hasPnPlus($row) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarNB($row)
    {
        if (((($this->_hasPrimaryOp($row) === true) && ($this->_hasNeoadjuvantTherapy($row) === true)) ||
                ($this->_hasNoPrimaryOp($row) === true) ||
                ($this->_hasPt0($row) === true)) &&
            ($this->_hasCnPlus($row) === true) &&
            ($this->_hasM0($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarM1A($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNoNeoadjuvantTherapy($row) === true) &&
            ($this->_hasM1($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _checkVarM1B($row)
    {
        if (((($this->_hasPrimaryOp($row) === true) && ($this->_hasNeoadjuvantTherapy($row) === true)) ||
                ($this->_hasNoPrimaryOp($row) === true) ||
                ($this->_hasPt0($row) === true)) &&
            ($this->_hasM1($row) === true)) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPrimaryOp($row)
    {
        return (strlen($row['datumprimaer_op']) > 0);
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasNoPrimaryOp($row)
    {
        return !$this->_hasPrimaryOp($row);
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasNeoadjuvantTherapy($row)
    {
        if ($row['durchgef_neoadj_therapie'] == '1') {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasNoNeoadjuvantTherapy($row)
    {
        return !$this->_hasNeoadjuvantTherapy($row);
    }

    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPt0($row)
    {
        return (str_starts_with($row['pt'], 'pT0'));
    }


    /**
     * pN0 or pN0(sn) check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPn0($row)
    {
        if (strlen($row['pn']) > 0) {
            if (str_starts_with($row['pn'], "pN0")) {
                return true;
            }
        }
        else if (strlen($row['pn_sn']) > 0) {
            if (str_starts_with($row['pn_sn'], "pN0")) {
                return true;
            }
        }
        return false;
    }


    /**
     * pN0 or pN0(sn) check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasN0($row, $ptVal)
    {
        if (str_starts_with($ptVal, "Tis")) {
            if (($this->_hasPn0($row) === true) ||
                ($this->_hasCn0($row) === true)) {
                return true;
            }
        }
        else {
            if ($this->_hasPn0($row) === true) {
                return true;
            }
        }
        return false;
    }


    /**
     * M0 check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCn0($row)
    {
        if ((strlen($row['cn']) > 0) &&
            str_starts_with($row['cn'], "cN0")) {
            return true;
        }
        /* TODO: ???
        else if ((strlen($row['cn_sn']) > 0) &&
            str_starts_with($row['cn_sn'], "cN0")) {
            return true;
        }*/
        return false;
    }


    /**
     * M0 check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCm0($row)
    {
        if (str_starts_with($row['m'], "cM0")) {
            return true;
        }
        return false;
    }


    /**
     * M0 check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasM0($row)
    {
        if (($this->_hasCm0($row) === true) ||
            str_starts_with($row['m'], "pM0")) {
            return true;
        }
        return false;
    }


    /**
     * M1 check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCm1($row)
    {
        if (str_starts_with($row['m'], "cM1")) {
            return true;
        }
        return false;
    }


    /**
     * M1 check
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasM1($row)
    {
        if (($this->_hasCm1($row) === true) ||
            str_starts_with($row['m'], "pM1")) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasPnPlus($row)
    {
        if (strlen($row['pn']) > 0) {
            if (str_starts_with($row['pn'], "pN1") ||
                str_starts_with($row['pn'], "pN2") ||
                str_starts_with($row['pn'], "pN3")) {
                return true;
            }
        }
        else if (strlen($row['pn_sn']) > 0) {
            if (str_starts_with($row['pn_sn'], "pN1") ||
                str_starts_with($row['pn_sn'], "pN2") ||
                str_starts_with($row['pn_sn'], "pN3")) {
                return true;
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return bool
     */
    protected function _hasCnPlus($row)
    {
        if (strlen($row['cn']) > 0) {
            if (str_starts_with($row['cn'], "cN1") ||
                str_starts_with($row['cn'], "cN2") ||
                str_starts_with($row['cn'], "cN3")) {
                return true;
            }
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @param $row
     * @return array
     */
    protected function _getErkrankungenNachPrimaererkrankung($row)
    {
        $data = array();
        $query = "
            SELECT DISTINCT
                e.erkrankung_id,
                e.erkrankung,
                MIN(ts.datum_sicherung) AS diagnoseDatum,
                '1'                     AS hasPrimaerTumorstatus,
                ts.diagnose,
                ts.morphologie

            FROM
                erkrankung e
                INNER JOIN tumorstatus ts ON ts.erkrankung_id=e.erkrankung_id
                                             AND ts.anlass = 'p'

            WHERE
                e.patient_id={$row['patient_id']}
                AND e.erkrankung_id != {$row['erkrankungId']}

            GROUP BY
                e.erkrankung_id,
                ts.datum_sicherung

            ORDER BY
                ts.datum_sicherung

        ";
        $result = sql_query_array($this->_db, $query);
        if ($result !== false) {
            foreach ($result as $item) {
                if ((strlen($row['bezugsdatum']) > 0) &&
                    ($item['diagnoseDatum'] > $row['bezugsdatum'])) {
                    $data[] = $item;
                }
            }
        }
        return $data;
    }

}






?>
