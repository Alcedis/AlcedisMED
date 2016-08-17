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

class reportContentH05 extends reportExtensionH
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array('D','E','F','G','H','I','J','K','L','M','N','O','P','Q','S','T','W','X');


    /**
     * @access protected
     * @var array
     */
    protected $_nCodings = array(
        '1',  '1(sn)',
        '1a', '1a(sn)',
        '1b', '1b(sn)',
        '2',  '2(sn)',
        '2a', '2a(sn)',
        '2b', '2b(sn)',
        '2c', '2c(sn)',
        '3',  '3(sn)'
    );


    /**
     * _initializeMatrix
     *
     * @access  protected
     * @param   string  $bezugsjahr
     * @return  array
     */
    protected function _initializeMatrix($bezugsjahr)
    {
        $years = range($bezugsjahr - 5, $bezugsjahr);
        $data  = array();

        foreach ($years as $k => $year) {
            $data['C' . (14 + $k)] = $year;
        }

        foreach (range(14, 19) as $row) {
            foreach($this->_cols as $col) {
                if ($col === 'S' && $row == 19) {
                    break 2;
                } else {
                    $data[$col . $row] = 0;
                }
            }
        }

        return $data;
    }


    /**
     * generate
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $bezugsjahr = $this->getParam('jahr', date('Y'));

        $firstJahr = $bezugsjahr - 5;

        $oas = array();

        foreach (range($firstJahr, $bezugsjahr - 1) as $j) {
            $oas[$j] = array('range' => array(), 'event' => array());
        }

        $matrix = $this->_initializeMatrix($bezugsjahr);

        $additionalContent['condition'] = "sit.diagnose LIKE 'C43%' AND YEAR(bezugsdatum) BETWEEN {$firstJahr} AND {$bezugsjahr}";

        $additionalContent['selects'] = array(
            "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' ORDER BY ts.datum_sicherung DESC LIMIT 1) AS lastRez",
            "(SELECT ts.datum_sicherung FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' ORDER BY ts.datum_sicherung ASC LIMIT 1)  AS firstRez"
        );

        $additionalContent['joins'] = array(
            "LEFT JOIN nachsorge n ON s.form = 'nachsorge' AND LOCATE(CONCAT_WS('','-',sit.erkrankung_id,'-'), s.report_param) > 0 AND n.nachsorge_id = s.form_id"
        );

        $additionalContent['fields'] = array(
            "sit.firstRez     AS 'firstRez'",
            "sit.lastRez      AS 'lastRez'",
            "IF(sit.lastRez >= '{$bezugsjahr}-01-01', 1, NULL)                                                 AS 'lastRezInNachsorgejahr'",
            "MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL))                                          AS 'lastNachsorge'",
            "IF(MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL)) >= '{$bezugsjahr}-01-01', 1, NULL)    AS 'lastNachsorgeInNachsorgejahr'",
            "IF(MAX(x.todesdatum) <= '{$bezugsjahr}-12-31', MAX(x.todesdatum) , NULL)                          AS 'todesdatum_less_nachsorgejahr'"
        );

        $records = $this->loadRessource('h01_1', $additionalContent);

        foreach ($records as $record) {
            if ((int) $record['primaerfall'] !== 1) {
                continue;
            }
            $ajcc        = strlen($record['ajcc']) ? $record['ajcc'] : $record['ajcc_prae'];
            $currentJahr = date('Y', strtotime($record['bezugsdatum']));
            $row         = $currentJahr - $bezugsjahr + 19;

            if ((int) $record['invasives_malignom'] === 1) {
                $matrix["D{$row}"]++;
                $counted = false;

                if ((int) $record['uvea'] === 1 || (int) $record['konjunktiva'] === 1 || (int) $record['schleimhaut'] === 1) {
                    // Feld 12 - Invasive maligne Melanome und Uvea, Konjunktiva, Schleimhaut
                    $matrix["P{$row}"]++;
                    $counted = true;
                }

                if (false === $counted) {
                    switch (true) {
                        case $ajcc === 'IA':   $matrix["E{$row}"]++; $counted = true; break;
                        case $ajcc === 'IB':   $matrix["F{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIA':  $matrix["G{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIB':  $matrix["H{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIC':  $matrix["I{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIIA': $matrix["J{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIIB': $matrix["K{$row}"]++; $counted = true; break;
                        case $ajcc === 'IIIC': $matrix["L{$row}"]++; $counted = true; break;
                        case $ajcc === 'IV':   $matrix["M{$row}"]++; $counted = true; break;
                    }

                    $t = $record['t'];
                    $n = $record['n'];
                    $m = $record['m'];

                    if ($counted === false) {
                        if (str_ends_with($t, 'X') === true && str_ends_with($n, $this->_nCodings) === true && str_ends_with($m, '0') === true) {
                            // Feld 10 - Invasive maligne Melanome und Tx, N+ ohne M1
                            $matrix["N{$row}"]++;
                            $counted = true;
                        } elseif (str_ends_with($t, 'X') === true && str_ends_with($n, array('X', 'X(sn)')) === true && str_ends_with($m, array('1', '1a', '1b', '1c')) === true) {
                            // Feld 11 - Invasive maligne Melanome und Tx, Nx, M1
                            $matrix["O{$row}"]++;
                            $counted = true;
                        } else {
                            // Feld 13 - Invasive Melanome und nicht zuzuordnen
                            $matrix["Q{$row}"]++;
                            $counted = true;
                        }
                    }
                }
            }

            if ($row == 19) {
                continue;
            }

            if ($counted === true) {
                if ($record['lastNachsorgeInNachsorgejahr'] == '1' || $record['lastRezInNachsorgejahr'] == '1' || strlen($record['todesdatum_less_nachsorgejahr']) > 0) {
                    $matrix["S$row"]++;

                    if (strlen($record['todesdatum_less_nachsorgejahr']) > 0) {
                        $matrix["W$row"] += $record['tod_tumorbedingt'];
                        $matrix["X$row"] += (int) (strlen($record['tod_tumorbedingt']) == 0 || $record['tod_tumorbedingt'] == '0');
                    }
                } else {
                    $matrix["T$row"]++;
                }

                $first      = $record['lastRez'] > $record['lastNachsorge'] ? $record['lastRez']       : $record['lastNachsorge'];
                $second     = $record['lastRez'] > $record['lastNachsorge'] ? $record['lastNachsorge'] : $record['lastRez'];
                $oasDauer   = strtotime($this->getFirstValue($record['todesdatum_less_nachsorgejahr'], $first, $second, $record['bezugsdatum'])) - strtotime($record['bezugsdatum']);

                $oasEvent   = strlen($record['todesdatum_less_nachsorgejahr']) ? 1 : 0;

                $oas[$currentJahr]['range'][]    = $oasDauer;
                $oas[$currentJahr]['event'][]    = $oasEvent;
            }
        }

        foreach ($oas as $jahr => $oasData) {
            $row = $jahr - $bezugsjahr + 19;

            $this->addSaveCell('Z', $row, $oasData);

            $oasValue        = $this->_kaplanMeier($oasData);
            $matrix["Z$row"] = end($oasValue['y'])/100;
        }

        $this->_data = $this->clearCells($matrix);

        $this->parseXLS();
    }
}

?>
