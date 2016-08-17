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

class reportContentGt05 extends reportExtensionGt
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array(
        'D','E','F','G','H','I','J','K','L','M','P','Q','R','V','X','Y','Z','AA','AB','AC','AF','AG'
    );


    /**
     * _figo_rows
     *
     * @access  protected
     * @var     array
     */
    protected $_figo_rows = array(
        'IA1'  => 'D',
        'IA2'  => 'E',
        'IB1'  => 'F',
        'IB2'  => 'G',
        'IIA'  => 'H',
        'IIB'  => 'I',
        'IIIA' => 'J',
        'IIIB' => 'K',
        'IVA'  => 'L',
        'IVB'  => 'M'
    );


    /**
     * _initalizeMatrix
     *
     * @access
     * @param   $auditjahr
     * @return  array
     */
    protected function _initalizeMatrix($auditjahr)
    {
        $years = range($auditjahr-6, $auditjahr-1);

        $data = array(
            'X8'  => $auditjahr,
            'AG8' => $auditjahr - 1
        );

        $this
            ->addSaveCell('AG', 8)
            ->addSaveCell('X', 8)
        ;

        foreach ($years as $k => $year) {
            $data['B' . (15 + $k)] = $year . ($k == count($years)-1 ? '*' : '');
        }

        foreach (range(15,20) as $row) {
            foreach($this->_cols as $col) {
                if ($col == 'Q' && $row == 20) {
                    break 2;
                } else {
                    $data[$col . $row] = 0;
                }
            }
        }

        $data['AF24'] = 0;
        $data['AG24'] = 0;

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
        $firstJahr  = $auditjahr - 6;

        $oas = array('all' => array('range' => array(), 'event' => array()));
        $dfs = array('all' => array('range' => array(), 'event' => array()));

        foreach (range($firstJahr, $bezugsjahr-1) as $j) {
            $oas[$j] = array('range' => array(), 'event' => array());
            $dfs[$j] = array('range' => array(), 'event' => array());
        }

        $matrix = $this->_initalizeMatrix($auditjahr);

        $additionalContent['condition'] = "
            sit.diagnose LIKE 'C53%'
            AND YEAR(bezugsdatum) BETWEEN {$firstJahr} AND {$bezugsjahr}"
        ;

        $additionalContent['selects'] = array(
            "
                (
                    SELECT      ts.rezidiv_lokal
                    FROM        tumorstatus ts
                    WHERE       ts.erkrankung_id = t.erkrankung_id
                    AND         ts.anlass LIKE 'r%'
                    AND         ts.datum_sicherung <= '{$bezugsjahr}-12-31'
                    AND         ts.rezidiv_lokal IS NOT NULL
                    ORDER BY    NULL
                    LIMIT       1
                ) AS    rez_lokal
            ",
            "
                (
                    SELECT      ts.rezidiv_lk
                    FROM        tumorstatus ts
                    WHERE       ts.erkrankung_id = t.erkrankung_id
                    AND         ts.anlass LIKE 'r%'
                    AND         ts.datum_sicherung <= '{$bezugsjahr}-12-31'
                    AND         ts.rezidiv_lk IS NOT NULL
                    ORDER BY    NULL
                    LIMIT       1
                )  AS rez_loko
            ",
            "
                (
                    SELECT      ts.rezidiv_metastasen
                    FROM        tumorstatus ts
                    WHERE       ts.erkrankung_id = t.erkrankung_id
                    AND         ts.anlass LIKE 'r%'
                    AND         ts.datum_sicherung <= '{$bezugsjahr}-12-31'
                    AND         ts.rezidiv_metastasen IS NOT NULL
                    ORDER BY    NULL
                    LIMIT       1
                )  AS rez_metast
            ",
            "
                (
                    SELECT      ts.datum_sicherung
                    FROM        tumorstatus ts
                    WHERE       ts.erkrankung_id = t.erkrankung_id
                    AND         ts.anlass LIKE 'r%'
                    AND         ts.datum_sicherung <= '{$bezugsjahr}-12-31'
                    ORDER BY    ts.datum_sicherung DESC
                    LIMIT       1
                ) AS lastRez
            ",
            "
                (
                    SELECT      ts.datum_sicherung
                    FROM        tumorstatus ts
                    WHERE       ts.erkrankung_id = t.erkrankung_id
                    AND         ts.anlass LIKE 'r%'
                    AND         ts.datum_sicherung <= '{$bezugsjahr}-12-31'
                    ORDER BY    ts.datum_sicherung ASC
                    LIMIT       1
                ) AS firstRez
            ",
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

            "
                IF(
                    sit.lastRez >= '{$bezugsjahr}-01-01',
                    1,
                    NULL
                )   AS 'lastRezInNachsorgejahr'
            ",
            "
                MAX(
                    IF(
                        n.datum <= '{$bezugsjahr}-12-31',
                        n.datum,
                        NULL
                    )
                ) AS 'lastNachsorge'
            ",
            "
                IF(
                    MAX(
                        IF(
                            n.datum <= '{$bezugsjahr}-12-31',
                            n.datum,
                            NULL
                        )
                    ) >= '{$bezugsjahr}-01-01',
                    1,
                    NULL
                ) AS 'lastNachsorgeInNachsorgejahr'
            ",
            "
                IF(
                    MAX(x.todesdatum) <= '{$bezugsjahr}-12-31',
                    MAX(x.todesdatum) ,
                    NULL
                ) AS 'todesdatum_less_nachsorgejahr'",

            $this->_diseaseExtension('fields', $bezugsjahr)
        );

        $datasets = $this->loadRessource('gt01_1', $additionalContent);

        foreach ($datasets as $dataset) {
            if ($dataset['primaerfall'] != 1 || in_array($dataset['morphologie'], array('8890/3', '8930/3', '8933/3')) === true) {
                continue;
            }

            if (strlen($dataset['zweitmalignom']) > 0) {
                $zweitmalignom = explode(',', $dataset['zweitmalignom']);

                rsort($zweitmalignom);

                $dataset['zweitmalignom'] = reset($zweitmalignom);
            }

            //Figo
            $figo = $dataset['figo_nach_neoadj_th'] == 1 ? $dataset['figo_prae'] : $dataset['figo'];

            if (str_starts_with($figo, 'IIA')) {
                $figo = 'IIA';
            }

            if (strlen($figo) == 0 || array_key_exists($figo, $this->_figo_rows) === false) {
                continue;
            }

            $currentJahr   = date('Y', strtotime($dataset['bezugsdatum']));
            $row           = $currentJahr - $bezugsjahr + 20;

            $matrix[$this->_figo_rows[$figo] . $row]++;

            if ($row == 20){
                continue;
            }

            //Pat., die in dieser Darstellung des Follow-Up nicht berücksichtigt werden dürfen: primär FIGO IV
            //diagnostizierte Pat. und Pat. mit vorausgegangenem Tumor (alle Entitäten)

            if (str_starts_with($figo, 'IV') === false && $dataset['vorerkrankung'] == '0') {
                if ($dataset['lastNachsorgeInNachsorgejahr'] == 1 ||
                    $dataset['lastRezInNachsorgejahr'] == 1 ||
                    strlen($dataset['todesdatum_less_nachsorgejahr']) > 0
                ) {
                    $matrix["Q{$row}"]++;

                    $caseRange = array(
                        'rez'   => strlen($dataset['lastRez']) > 0 ?
                            $dataset['lastRez'] :
                            false
                        ,
                        'zweit' => strlen($dataset['zweitmalignom']) > 0 ?
                            $dataset['zweitmalignom'] :
                            false
                        ,
                        'tod'   => strlen($dataset['todesdatum_less_nachsorgejahr']) > 0 ?
                            $dataset['todesdatum_less_nachsorgejahr'] :
                            false
                    );

                    $tmpDate = false;
                    $xCase = false;

                    foreach ($caseRange as $case => $checkDate) {
                        if ($tmpDate == false || $checkDate > $tmpDate) {
                            $tmpDate = $checkDate;
                            $xCase = $case;
                        }
                    }

                    switch($xCase) {
                        case 'rez' :
                            if (strlen($dataset['firstRez']) > 0) {
                                $matrix["X{$row}"] += $dataset['rez_lokal']  == '1' ? 1 : 0;
                                $matrix["Y{$row}"] += $dataset['rez_loko']   == '1' ? 1 : 0;
                                $matrix["Z{$row}"] += $dataset['rez_metast'] == '1' ? 1 : 0;

                                if ($dataset['rez_lokal'] == '1' ||
                                    $dataset['rez_loko'] == '1' ||
                                    $dataset['rez_metast'] == '1'
                                ) {
                                    $matrix["V{$row}"]++;
                                }
                            }

                        break;

                        case 'zweit' :
                            $matrix["AA{$row}"]++;
                            break;

                        case 'tod' :
                            if (strlen($dataset['todesdatum_less_nachsorgejahr'])) {
                          $matrix["AB{$row}"] += $dataset['tod_tumorbedingt'];
                          $matrix["AC{$row}"] += 1 - (int) $dataset['tod_tumorbedingt'];
                            }

                            break;
                    }
                } else {
                    $matrix["R{$row}"]++;
                }

                //Kaplan Meier Berechnung hier
                $dfsDauer = strtotime(
                    $this->getFirstValue(
                        $dataset['firstRez'],
                        $dataset['lastNachsorge'],
                        $dataset['todesdatum_less_nachsorgejahr'],
                        $dataset['bezugsdatum'])
                    )
                    - strtotime($dataset['bezugsdatum']
                );

                $first  = $dataset['lastRez'] > $dataset['lastNachsorge'] ?
                    $dataset['lastRez'] :
                    $dataset['lastNachsorge']
                ;
                $second = $dataset['lastRez'] > $dataset['lastNachsorge'] ?
                    $dataset['lastNachsorge'] :
                    $dataset['lastRez']
                ;

                $oasDauer = strtotime(
                    $this->getFirstValue(
                        $dataset['todesdatum_less_nachsorgejahr'],
                        $first,
                        $second,
                        $dataset['bezugsdatum']
                    )
                )
                - strtotime($dataset['bezugsdatum']);

                $dfsEvent = strlen($dataset['firstRez']) ? 1 : 0;
                $oasEvent = strlen($dataset['todesdatum_less_nachsorgejahr']) ? 1 : 0;

                $dfs[$currentJahr]['range'][]    = $dfsDauer;
                $dfs[$currentJahr]['event'][]    = $dfsEvent;

                $oas[$currentJahr]['range'][]    = $oasDauer;
                $oas[$currentJahr]['event'][]    = $oasEvent;
            }
        }

        foreach ($dfs as $jahr => $dfsData) {
            $row =
                $jahr != 'all' ? ($jahr - $bezugsjahr + 20) : '22';

            $this->addSaveCell('AF', $row, $dfsData);

            $oasValue = $this->_kaplanMeier($dfsData);
            $matrix["AF{$row}"] = end($oasValue['y'])/100;
        }

        foreach ($oas as $jahr => $oasData) {
            $row = $jahr != 'all' ? ($jahr - $bezugsjahr + 20) : '22';

            $this->addSaveCell('AG', $row, $oasData);

            $oasValue = $this->_kaplanMeier($oasData);
            $matrix["AG{$row}"] = end($oasValue['y'])/100;
        }

        $this->_data = $this->clearCells($matrix);

        $this->parseXLS();
    }
}
?>
