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

class reportContentPa05 extends reportExtensionPa
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array('D','E','F','G','H','I','L','M','N','R','S','T','U','V','W','Y', 'Z');


    /**
     * _initalizeMatrix
     *
     * @access  protected
     * @param   int $auditjahr
     * @return  array
     */
    protected function _initalizeMatrix($auditjahr)
    {
        $years = range($auditjahr - 6, $auditjahr - 1);

        $data = array(
            'Z8' => $auditjahr,
            'L3'  => utf8_encode($this->_params['org_name']),
            'L4'  => utf8_encode($this->_params['org_ort'])
        );

        $this
            ->addSaveCell('Z', 8)
            ->addSaveCell('L', 3)
            ->addSaveCell('L', 4)
        ;

        foreach ($years as $k => $year) {
            $data['B' . (14 + $k)] = $year;
        }

        foreach (range(14, 19) as $row) {
            foreach($this->_cols as $col) {
                if ($col == 'K' && $row == 19) {
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

        $auditjahr  = $bezugsjahr + 1;
        $firstJahr  = $bezugsjahr - 5;

        $oas = array();
        $dfs = array();

        foreach (range($firstJahr, $bezugsjahr - 1) as $j) {
            $oas[$j] = array('range' => array(), 'event' => array());
            $dfs[$j] = array('range' => array(), 'event' => array());
        }

        $matrix = $this->_initalizeMatrix($auditjahr);

        $additionalContent['condition'] = "YEAR(bezugsdatum) BETWEEN {$firstJahr} AND {$bezugsjahr}";

        $additionalContent['selects'] = array(
            "(SELECT ts.rezidiv_lokal        FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_lokal IS NOT NULL      ORDER BY NULL LIMIT 1)  AS rez_lokal",
            "(SELECT ts.rezidiv_lk           FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_lk IS NOT NULL         ORDER BY NULL LIMIT 1)  AS rez_loko",
            "(SELECT ts.rezidiv_metastasen   FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31' AND ts.rezidiv_metastasen IS NOT NULL ORDER BY NULL LIMIT 1)  AS rez_metast",
            "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31'                                       ORDER BY ts.datum_sicherung DESC LIMIT 1) AS lastRez",
            "(SELECT ts.datum_sicherung      FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$bezugsjahr}-12-31'                                       ORDER BY ts.datum_sicherung ASC  LIMIT 1) AS firstRez",
        );

        $additionalContent['joins'] = array(
            $this->_diseaseExtension('joins', $bezugsjahr)
        );

        $additionalContent['fields'] = array(
            "sit.firstRez     AS 'firstRez'",
            "sit.lastRez      AS 'lastRez'",
            "sit.rez_lokal    AS 'rez_lokal'",
            "sit.rez_loko     AS 'rez_loko'",
            "sit.rez_metast   AS 'rez_metast'",

         "IF(sit.lastRez >= '{$bezugsjahr}-01-01', 1, NULL)                                              AS 'lastRezInNachsorgejahr'",
         "MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL))                                       AS 'lastNachsorge'",
         "IF(MAX(IF(n.datum <= '{$bezugsjahr}-12-31', n.datum, NULL)) >= '{$bezugsjahr}-01-01', 1, NULL) AS 'lastNachsorgeInNachsorgejahr'",
         "IF(MAX(x.todesdatum) <= '{$bezugsjahr}-12-31', MAX(x.todesdatum) , NULL)                       AS 'todesdatum_less_nachsorgejahr'",

            $this->_diseaseExtension('fields', $bezugsjahr)
        );

        $datasets = $this->loadRessource('pa01_1', $additionalContent);

        foreach ($datasets as $i => $dataset) {
            if ($dataset['primaerfall'] != 1) {
                continue;
            }

            $currentJahr = date('Y', strtotime($dataset['bezugsdatum']));
            $row = $currentJahr - $bezugsjahr + 19;

            //Für die verwendete UICC-Angabe aus pa01 gilt:
            //wenn "UICC gilt nach neoadj. Therapie" = 1 wird der Wert aus "UICC prätherapeutisch" verwendet,
            //sonst wird der Wert aus "UICC" verwendet.
            $uicc    = $dataset['uicc_nach_neoadj_th'] == 1 ? $dataset['uicc_prae'] : $dataset['uicc'];

            $matrix["D$row"] += (int) (str_starts_with($uicc, 'IA'));
            $matrix["E$row"] += (int) (str_starts_with($uicc, 'IB'));
            $matrix["F$row"] += (int) (str_starts_with($uicc, 'IIA'));
            $matrix["G$row"] += (int) (str_starts_with($uicc, 'IIB'));
            $matrix["H$row"] += (int) (str_starts_with($uicc, 'III'));
            $matrix["I$row"] += (int) (str_starts_with($uicc, 'IV'));

            if ($row == 19) {
                continue;
            }

            //In $dataset['vorerkrankung'] ist eine mögliche dokumentierte C Diagnose der Anamnese schon enthalten!!
            if (str_starts_with($uicc, array('IA', 'IB', 'IIA', 'IIB', 'III')) === true && $dataset['vorerkrankung'] == '0') {
                //Zweitmalignom
                if (strlen($dataset['zweitmalignom']) > 0) {
                    $zweitmalignom = explode(',', $dataset['zweitmalignom']);

                    rsort($zweitmalignom);

                    $dataset['zweitmalignom'] = reset($zweitmalignom);
                }

                //Kaplan Meier Berechnung hier
                $first      = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastRez']       : $dataset['lastNachsorge'];
                $second     = $dataset['lastRez'] > $dataset['lastNachsorge'] ? $dataset['lastNachsorge'] : $dataset['lastRez'];

                $oasDauer = strtotime($this->getFirstValue($dataset['todesdatum_less_nachsorgejahr'], $first, $second, $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);
                $oasEvent = strlen($dataset['todesdatum_less_nachsorgejahr']) ? 1 : 0;

                if ($dataset['r'] == '0') {
                    $dfsDauer = strtotime($this->getFirstValue($dataset['firstRez'], $dataset['lastNachsorge'], $dataset['todesdatum_less_nachsorgejahr'], $dataset['bezugsdatum'])) - strtotime($dataset['bezugsdatum']);
                    $dfsEvent = strlen($dataset['firstRez']) ? 1 : 0;

                    $dfs[$currentJahr]['range'][] = $dfsDauer;
                    $dfs[$currentJahr]['event'][] = $dfsEvent;
                }

                $oas[$currentJahr]['range'][] = $oasDauer;
                $oas[$currentJahr]['event'][] = $oasEvent;

                if ($dataset['lastNachsorgeInNachsorgejahr'] == 1 || $dataset['lastRezInNachsorgejahr'] == 1 || strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
                    $matrix["M$row"]++;

                    //Tot
                    if (strlen($dataset['todesdatum_less_nachsorgejahr']) > 0) {
                        if ($dataset['tod_tumorbedingt'] == '1') {
                            $matrix["V$row"]++;
                        } else {
                            $matrix["W$row"]++;
                        }

                        continue;
                    }

                    //Diagnose Zweitmalignom im Verlauf [U]
                    //Nur Patienten, die in Spalte [L] gezählt werden und die nicht tot sind, werden berücksichtigt
                    //deshalb in Tot ein "continue"! weitere continues folgen unten
                    if (strlen($dataset['zweitmalignom']) > 0) {
                        $matrix["U$row"]++;
                        continue;
                    }

                    //Patienten mit Progress [T]
                    //Nur Patienten, die in Spalte [L] gezählt werden und nicht in Spalte [S] bis [V] enthalten sind, werden berücksichtigt
                    if (strlen($dataset['lastRez']) > 0 && ($dataset['r'] != '0' || in_array($dataset['response'], array('PR','SD','PD')) === true)) {
                        $matrix["T$row"]++;
                        continue;
                    }

                    //Patienten nicht tumorfrei [S]
                    //Nur Patienten, die in Spalte [L] gezählt werden und nicht in Spalte [R] bis [V] enthalten sind, werden berücksichtigt
                    if (in_array($dataset['r'], array('1', '2')) === true || in_array($dataset['response'], array('PR','SD','PD')) === true) {
                        $matrix["S$row"]++;
                    }

                    //Nur Patienten, die in Spalte [M] gezählt werden und nicht in Spalte [S] bis [W] enthalten sind, werden berücksichtigt
                    if (strlen($dataset['lastRez']) > 0 && ($dataset['r'] == '0' || in_array($dataset['response'], array('CR','NED')) === true)) {
                        $matrix["R$row"]++;
                        continue;
                    }
                } else {
                    $matrix["N$row"]++;
                }
            }
        }

        foreach ($dfs as $jahr => $dfsData) {
            $row = $jahr - $bezugsjahr + 19;

            $this->addSaveCell('Y', $row, $dfsData);

            $oasValue = $this->_kaplanMeier($dfsData);
            $matrix["Y$row"] = end($oasValue['y'])/100;
        }

        foreach ($oas as $jahr => $oasData) {
            $row = $jahr - $bezugsjahr + 19;

            $this->addSaveCell('Z', $row, $oasData);

            $oasValue = $this->_kaplanMeier($oasData);
            $matrix["Z$row"] = end($oasValue['y'])/100;
        }

        $this->_data = $this->clearCells($matrix);

        $this->parseXLS();
    }
}

?>
