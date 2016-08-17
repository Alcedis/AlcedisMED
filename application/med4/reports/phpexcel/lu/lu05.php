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

class reportContentLu05 extends reportExtensionLu
{

    const CURRENT_VERSION = "E2.2";

    const MAX_DATA_ROWS = 6;

    const DATA_ROW_START_OFFSET = 13;
    const DATA_ROW_END_OFFSET = 18;

    protected $_cols = array(
        'D', 'E', 'F', 'G', 'H', 'I', 'J', // primary
        'M', 'N', 'O',                     // follow
        'T', 'U','V','W','X',              // events
        'Z', 'AA'                          // dfs, oas
    );


    /**
     * initialize data array
     *
     * @param $auditjahr
     * @param $version
     * @return array
     */
    protected function _initalizeMatrix($auditjahr, $version)
    {
        $years = range($auditjahr - self::MAX_DATA_ROWS, $auditjahr - 1);

        $data = array(
            'A6'  => utf8_encode("Anlage EB Version " . $version),
            'AA7' => $auditjahr,
            'M3'  => utf8_encode($this->_params['org_name']),
            'M4'  => utf8_encode($this->_params['org_ort'])
        );

        $this
            ->addSaveCell('M', 3)
            ->addSaveCell('M', 4)
            ->addSaveCell('A', 6)
            ->addSaveCell('AA', 7)
        ;

        foreach ($years as $k => $year) {
            $data['B' . (self::DATA_ROW_START_OFFSET + $k)] = $year;
        }

        foreach (range(self::DATA_ROW_START_OFFSET, self::DATA_ROW_END_OFFSET) as $row) {
            foreach($this->_cols as $col) {
                if ('L' == $col && $row == self::DATA_ROW_END_OFFSET) {
                    break 2;
                } else {
                    $data[$col . $row] = 0;
                }
            }
        }

        return $data;
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function generate()
    {
        $bezugsjahr = $this->getParam('jahr', date('Y'));

        $auditjahr = $bezugsjahr + 1;
        $firstJahr = $auditjahr - self::MAX_DATA_ROWS;

        $oas = array();
        $dfs = array();

        foreach (range($firstJahr, $bezugsjahr - 1) as $j) {
            $oas[$j] = array('range' => array(), 'event' => array());
            $dfs[$j] = array('range' => array(), 'event' => array());
        }

        $matrix = $this->_initalizeMatrix($auditjahr, self::CURRENT_VERSION);

        $additionalContent['condition'] = "sit.diagnose LIKE 'C34%' AND YEAR(bezugsdatum) BETWEEN {$firstJahr} AND {$bezugsjahr}";

        $additionalContent['selects'] = array(
            "(SELECT ts.rezidiv_metastasen   FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_metastasen IS NOT NULL  ORDER BY NULL LIMIT 1) AS rez_metast",
            "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' ORDER BY ts.datum_sicherung DESC LIMIT 1)                     AS lastRez",
            "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' ORDER BY ts.datum_sicherung ASC  LIMIT 1)                     AS firstRez",
        );

        $additionalContent['joins'] = array(
            $this->_diseaseExtension('joins', $bezugsjahr)
        );

        $additionalContent['fields'] = array(
            "sit.firstRez     AS 'firstRez'",
            "sit.lastRez      AS 'lastRez'",
            "sit.rez_metast   AS 'rez_metast'",

            "IF(sit.lastRez >= '{$bezugsjahr}-01-01', 1, NULL)                                              AS 'lastRezInNachsorgejahr'",
            "MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL))                                       AS 'lastNachsorge'",
            "IF(MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL)) >= '{$bezugsjahr}-01-01', 1, NULL) AS 'lastNachsorgeInNachsorgejahr'",
            "IF(MAX(x.todesdatum) <= '{$bezugsjahr}-12-31', MAX(x.todesdatum) , NULL)                       AS 'todesdatum_less_nachsorgejahr'",

            $this->_diseaseExtension('fields', $bezugsjahr)
        );

        $datasets = $this->loadRessource('lu01_1', $additionalContent);

        foreach ($datasets as $index => $dataset) {
            if ($dataset['primaerfall'] != 1) {
                continue;
            }

            $uicc = $dataset['uicc'];
            if ('1' == $dataset['uicc_nach_neoadj_th']) {
                $uicc = $dataset['uicc_praetherapeutisch'];
            }

            $currentJahr = date('Y', strtotime($dataset['bezugsdatum']));
            $row = $currentJahr - $bezugsjahr + self::DATA_ROW_END_OFFSET;

            $counted = false;

            switch (true) {
                case ($uicc == 'IA'):                $matrix["D$row"]++; $counted = true; break;
                case ($uicc == 'IB'):                $matrix["E$row"]++; $counted = true; break;
                case ($uicc == 'IIA'):               $matrix["F$row"]++; $counted = true; break;
                case ($uicc == 'IIB'):               $matrix["G$row"]++; $counted = true; break;
                case str_starts_with($uicc, 'IIIA'): $matrix["H$row"]++; $counted = true; break;
                case ($uicc == 'IIIB'):              $matrix["I$row"]++; $counted = true; break;
                case ($uicc == 'IV'):                $matrix["J$row"]++; $counted = true; break;
            }

            if ($row == self::DATA_ROW_END_OFFSET) {
                continue;
            }

            if ($counted === true && $dataset['vorerkrankung'] == '0') {
                if ($dataset['lastNachsorgeInNachsorgejahr'] == 1 || $dataset['lastRezInNachsorgejahr'] == 1 || strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
                    $matrix["N$row"]++;

                    $inWX = false;

                    if (strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
                        if ($dataset['tod_tumorbedingt'] == '1') {
                            $matrix["W$row"]++;
                            $inWX = true;
                        } else if ($dataset['tod_tumorbedingt'] == '0' || 0 == strlen($dataset['tod_tumorbedingt'])) {
                            $matrix["X$row"]++;
                            $inWX = true;
                        }
                    }

                    if ($inWX === false) {
                        if ((strlen($dataset['firstRez']) > 0 || strlen($dataset['lastRez']) > 0)) {
                            if ($dataset['r'] === '0') {
                                $matrix["T$row"]++;
                            } else if (in_array($dataset['r'], array('1', '2')) === true) {
                                $matrix["V$row"]++;
                            }
                        } else if (strlen($dataset['firstRez']) == 0 && strlen($dataset['lastRez']) == 0 && in_array($dataset['r'], array('1', '2'))) {
                            $matrix["U$row"]++;
                        }
                    }
                } else {
                    $matrix["O$row"]++;
                }

                $dfsDauer = strtotime($this->getFirstValue($dataset['firstRez'], $dataset['lastNachsorge'], $dataset['todesdatum_less_nachsorgejahr'], $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);

                $first = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastRez']       : $dataset['lastNachsorge'];
                $second = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastNachsorge'] : $dataset['lastRez'];

                $oasDauer = strtotime($this->getFirstValue($dataset['todesdatum_less_nachsorgejahr'], $first, $second, $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);
                $dfsEvent = strlen($dataset['firstRez']) ? 1 : 0;
                $oasEvent = strlen($dataset['todesdatum_less_nachsorgejahr']) ? 1 : 0;

                $dfs[$currentJahr]['range'][]    = $dfsDauer;
                $dfs[$currentJahr]['event'][]    = $dfsEvent;

                $oas[$currentJahr]['range'][]    = $oasDauer;
                $oas[$currentJahr]['event'][]    = $oasEvent;
            }
        }

        foreach ($dfs as $jahr => $dfsData) {
            $row = $jahr - $bezugsjahr + self::DATA_ROW_END_OFFSET;

            $this->addSaveCell('Z', $row, $dfsData);

            $oasValue = $this->_kaplanMeier($dfsData);
            $matrix["Z$row"] = end($oasValue['y'])/100;
        }

        foreach ($oas as $jahr => $oasData) {
            $row = $jahr - $bezugsjahr + self::DATA_ROW_END_OFFSET;

            $this->addSaveCell('AA', $row, $oasData);

            $oasValue = $this->_kaplanMeier($oasData);
            $matrix["AA$row"] = end($oasValue['y'])/100;
        }

        $this->_data = $this->clearCells($matrix);

        $this->parseXLS();
    }
}

?>
