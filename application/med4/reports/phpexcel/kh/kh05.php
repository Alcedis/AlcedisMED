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

class reportContentkh05 extends reportExtensionKh
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array('D','E','F','G','I','J','K','L','N','O','P','Q');


    /**
     * _rows
     *
     * @access  protected
     * @var     array
     */
    protected $_rows = array(
        array('IV' => 'G', 'III' => 'F', 'II' => 'E', 'I' => 'D'),
        array('IV' => 'L', 'III' => 'K', 'II' => 'J', 'I' => 'I'),
        array('IV' => 'Q', 'III' => 'P', 'II' => 'O', 'I' => 'N'),
    );


    /**
     * _initalizeMatrix
     *
     * @access  protected
     * @param   int $firstYear
     * @return  array
     */
    protected function _initalizeMatrix($firstYear)
    {
        $data  = array();

        $config = $this->loadConfigs('kh05');

        $data['E6'] = str_replace(array('xx', 'yy'), array($firstYear + 4, $firstYear + 3), $config['lbl_head']);

        $data['D11'] = $config['lbl_ed'] . $firstYear;
        $data['I11'] = $config['lbl_ed'] . ($firstYear + 1);
        $data['N11'] = $config['lbl_ed'] . ($firstYear + 2);

        foreach (array(13,14,17,18) as $row) {
            foreach($this->_cols as $col) {
                $data[$col . $row] = 0;
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
        $nachsorgeJahr = $this->getParam('nachsorgeJahr', date('Y'));

        $firstYear = $nachsorgeJahr - 3;

        $cols = array();

        foreach (range($firstYear, $firstYear + 2) as $i => $cYear) {
            $cols[$cYear] = $this->_rows[$i];
        }

        $matrix = $this->_initalizeMatrix($firstYear);

        $additionalContent['condition'] = "LEFT(sit.diagnose, 3) IN ('C00','C01','C02','C03','C04','C05','C06') AND YEAR(bezugsdatum) BETWEEN {$firstYear} AND {$nachsorgeJahr} AND
            (rezInYear IS NOT NULL OR deathDate IS NOT NULL OR nachsorgeInYear > 0)
        ";

        $additionalContent['selects'] = array(
            "(SELECT 1 FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND YEAR(ts.datum_sicherung) = '{$nachsorgeJahr}' LIMIT 1) AS rezInYear",
            "(SELECT 1 FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass LIKE 'r%' AND ts.datum_sicherung <= '{$nachsorgeJahr}-12-31' LIMIT 1) AS rez",
        );

        $additionalContent['fields'] = array(
            'sit.rezInYear as rezInYear',
            'sit.rez as rez',
            "IF(MAX(x.todesdatum) <= '{$nachsorgeJahr}-12-31', MAX(x.todesdatum), NULL) AS 'deathDate'",
            "COUNT(DISTINCT
                IF(
                    YEAR(n.datum) = '{$nachsorgeJahr}',
                    n.nachsorge_id,
                    NULL
                )
            ) as nachsorgeInYear"
        );

        $records = $this->loadRessource('kh01', $additionalContent);

        foreach ($records as $i => $record) {
            if ((int) $record['primaerfall'] !== 1 || $this->_checkUicc($record['uicc']) === false) {
                continue;
            }

            $year = substr($record['bezugsdatum'], 0, 4);

            // Bezugsdatum muss im jahresbereich liegen
            if (array_key_exists($year, $cols) === true) {
                $uicc      = $record['uicc'];
                $uiccCols  = $cols[$year];
                $col       = null;

                foreach ($uiccCols as $uiccCode => $uiccCol) {
                    if (str_starts_with($uicc, $uiccCode) === true) {
                        $col = $uiccCol;
                        break;
                    }
                }

                if ($col !== null) {
                    $deathDate = $record['deathDate'];
                    $rez       = $record['rez'];

                    $matrix["{$col}14"] += 1;
                    $matrix["{$col}18"] += 1;

                    if ($rez === null) {
                        $matrix["{$col}13"] += 1;
                    }

                    if ($deathDate === null) {
                        $matrix["{$col}17"] += 1;
                    }
                }
            }
        }

        $this->_data = $matrix;

        $this->parseXLS();
    }
}

?>
