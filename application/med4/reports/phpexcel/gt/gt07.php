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

class reportContentGt07 extends reportExtensionGt
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array('C','D','E','F','G','H','I','J','K');


    /**
     * _ovcaFigo
     *
     * @access  protected
     * @var     array
     */
    protected $_ovcaFigo = array(
        'IA'    => 8,
        'IB'    => 9,
        'IC'    => 10,
        'IIA'   => 11,
        'IIB'   => 12,
        'IIIA'  => 13,
        'IIIB'  => 14,
        'IIIC'  => 15,
        'IV'    => 16
    );


    /**
     * _zecaFigo
     *
     * @access  protected
     * @var     array
     */
    protected $_zecaFigo = array(
        'IA1'  => 19,
        'IA2'  => 20,
        'IB1'  => 21,
        'IB2'  => 22,
        'IIA*' => 23,
        'IIB'  => 24,
        'IIIA' => 25,
        'IIIB' => 26,
        'IVA'  => 27,
        'IVB'  => 28
    );


    /**
     * _initializeMatrix
     *
     * @access  protected
     * @return  array
     */
    protected function _initializeMatrix()
    {
        $data = array();

        foreach (array('C', 'D', 'E', 'F', 'G') as $col) {
            for ($i = 8; $i <= 33; $i++) {
                if ($i !== 17 && $i !== 29
                    && (($col === 'E' || $col === 'F') && in_array($i, range(8, 18))) === false
                    && (($col === 'C' || $col === 'D') && in_array($i, range(19, 33))) === false
                ) {
                    $data["{$col}{$i}"] = 0;
                }
            }
        }

        foreach (array('H', 'I') as $col) {
            $data["{$col}8"] = 0;
            $data["{$col}18"] = 0;
            $data["{$col}19"] = 0;
            $data["{$col}30"] = 0;
            $data["{$col}31"] = 0;
            $data["{$col}32"] = 0;
            $data["{$col}33"] = 0;
        }

        return $data;
    }


    /**
     * generate
     *
     * @access  public
     * @param   PHPExcel $renderer
     * @return  void
     */
    public function generate(PHPExcel $renderer)
    {
        $config = $this->loadConfigs('gt07', false, true);

        $matrix['A1'] = $config['head_report'];
        $matrix['A3'] = $config['lbl_auswertungszeitraum'] .
            attach_label($config['lbl_von'], $this->getParam('datum_von')) .
            attach_label($config['lbl_bis'], $this->getParam('datum_bis'))
        ;

        $gt01 = $this->loadRessource('gt01_1');

        if ($this->getParam('roh_daten') == 1) {
            $matrix = $this->_unsetMatrix($matrix);

            $renderer->removeSheetByIndex(0);
            $renderer->createSheet(0);
            $renderer->setActiveSheetIndex(0);

            $start = 8;

            if (count($gt01) > 0) {
                $matrix["K3"] = $config['erstellungsdatum'];
                $matrix["M3"] = date('d.m.Y');
                $matrix["A7"] = $config['nachname'];
                $matrix["B7"] = $config['vorname'];
                $matrix["C7"] = $config['geburtsdatum'];
                $matrix["D7"] = $config['patient_nr'];
                $matrix["E7"] = $config['primaerfall'];
                $matrix["F7"] = $config['datumprimaer_rezidiv_op'];
                $matrix["G7"] = $config['diagnose'];
                $matrix["H7"] = $config['anlass'];
                $matrix["I7"] = $config['morphologie'];
                $matrix["J7"] = $config['art_staging'];
                $matrix["K7"] = $config['operatives_staging'];
                $matrix["L7"] = $config['figo'];
                $matrix["M7"] = $config['figo_prae'];
                $matrix["N7"] = $config['figo_post'];
                $matrix["O7"] = $config['g'];
            }

            foreach ($gt01 as $i => $row) {
                $matrix = $this->_getOutput($matrix, $start + $i, $row);
            }
        } else {
            $matrix = $this->_initializeMatrix();

            foreach ($gt01 as $row) {
                $found = false;

                $col = $this->_identifyColumn($row);

                if ($col !== false) {
                    $diagnose = $row['diagnose'];

                    switch (true) {
                        // borderline
                        case in_array($col, array('C', 'D')):
                            $matrix["{$col}18"]++;
                            $found = true;
                            break;

                        // endometrium
                        case $diagnose === 'C54.1':
                            $matrix["{$col}30"]++;
                            $found = true;
                            break;

                        // vulva
                        case str_starts_with($diagnose, 'C51'):
                            $matrix["{$col}31"]++;
                            $found = true;
                            break;

                        // vaginal
                        case $diagnose === 'C52':
                            $matrix["{$col}32"]++;
                            $found = true;
                            break;

                        // sonst
                        case $this->cgz_diag_sonst($row):
                            $matrix["{$col}33"]++;
                            $found = true;
                            break;
                    }
                }

                if ($found === false) {
                    foreach ($this->_ovcaFigo as $figoValue => $figoRow) {

                        if ($found === false) {
                            // attention: column order has been changed due performance reasons
                            switch (true) {
                                case $this->cgz_ovca_nprim($row) === true && $this->hasOperation($row) === true:
                                    $matrix["H{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_ovca_nprim($row) === true && $this->hasOperation($row) === false:
                                    $matrix["I{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_ovca_op_prim_stag($row) === true && $this->check_figo($row, $figoValue) === true :
                                    $matrix["C{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_ovca_op_prim_def($row) === true && $this->check_figo($row, $figoValue) === true :
                                    $matrix["D{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_ovca_nop_prim($row) === true && $this->check_figo($row, $figoValue) === true :
                                    $matrix["G{$figoRow}"]++;
                                    $found = true;
                                    break;

                            }
                        } else {
                            // performance
                            break;
                        }
                    }
                }

                if ($found === false) {
                    foreach ($this->_zecaFigo as $figoValue => $figoRow) {

                        if ($found === false) {
                            switch (true) {
                                case $this->cgz_zeca_nprim($row) === true && $this->hasOperation($row) === true:
                                    $matrix["H{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_zeca_nprim($row) === true && $this->hasOperation($row) === false:
                                    $matrix["I{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_zeca_op_prim_stag($row) === true && $this->check_figo($row, $figoValue) === true :
                                    $matrix["E{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_zeca_op_prim_def($row) === true && $this->check_figo($row, $figoValue) === true:
                                    $matrix["F{$figoRow}"]++;
                                    $found = true;
                                    break;
                                case $this->cgz_zeca_nop_prim($row) === true && $this->check_figo($row, $figoValue) === true :
                                    $matrix["G{$figoRow}"]++;
                                    $found = true;
                                    break;
                            }
                        } else {
                            // performance
                            break;
                        }
                    }
                }
            }
        }

        $this->_data = $matrix;

        $this->parseXLS();
    }


    /**
     * _identifyColumn
     *
     * @access  protected
     * @param   array   $record
     * @return  bool|string
     */
    protected function _identifyColumn($record)
    {
        $column = false;

        switch (true) {
            case ($this->isBorderlineOvar($record) === true && $this->isPrimary($record) === true):
                if (($this->hasOperation($record) === true || $this->isStaging($record) === true) && in_array($record['r_lokal'], array(1, 2)) === true) {
                    $column = 'C';
                } else if ($this->hasOperation($record) === true && $this->hasOpStaging($record) === true && $record['r_lokal'] == '0') {
                    $column = 'D';
                }

                break;

            case ($this->isPrimary($record) === true && $this->hasNoOperation($record) === true && $this->isStaging($record)):
                $column = 'E';
                break;

            case ($this->isPrimary($record) === true && $this->hasOperation($record) === true):
                $column = 'F';
                break;

            case ($this->isPrimary($record) === true && $this->hasNoOperation($record) === true):
                $column = 'G';
                break;

            case ($this->isPrimary($record) === false && $this->hasOperation($record) === true):
                $column = 'H';
                break;

            case ($this->isPrimary($record) === false && $this->hasNoOperation($record) === true):
                $column = 'I';
                break;
        }

        return $column;
    }


    /**
     * _getOutput
     *
     * @access  protected
     * @param   array   $matrix
     * @param   int     $i
     * @param   array   $dataset
     * @return  array
     */
    protected function _getOutput($matrix, $i, $dataset)
    {
        $matrix["A{$i}"] = utf8_encode($dataset['nachname']);
        $matrix["B{$i}"] = utf8_encode($dataset['vorname']);
        $matrix["C{$i}"] = date('d.m.Y', strtotime($dataset['geburtsdatum']));
        $matrix["D{$i}"] = $dataset['patient_nr'];
        $matrix["E{$i}"] = $dataset['primaerfall'];

        $opDate = $dataset['datumprimaer_rezidiv_op'];

        if (strlen($opDate) > 0) {
            $matrix["F{$i}"] = date('d.m.Y', strtotime($opDate));
        }

        $matrix["G{$i}"] = $dataset['diagnose'];
        $matrix["H{$i}"] = utf8_encode($dataset['anlass_case']);
        $matrix["I{$i}"] = $dataset['morphologie'];
        $matrix["J{$i}"] = $dataset['art_staging'];
        $matrix["K{$i}"] = $dataset['op_staging'];
        $matrix["L{$i}"] = $dataset['figo'];
        $matrix["M{$i}"] = $dataset['figo_prae'];
        $matrix["N{$i}"] = $dataset['figo_nach_neoadj_th'];
        $matrix["O{$i}"] = $dataset['g'];

        return $matrix;
    }


    /**
     * _unsetMatrix
     *
     * @access  protected
     * @param   array   $matrix
     * @return  array
     */
    protected function _unsetMatrix($matrix)
    {
        $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');

        foreach ($cols as $col) {
            for ($i = 6; $i <= 33; $i++) {
                $matrix["{$col}{$i}"] = '';
            }
        }

        return $matrix;
    }
}
?>
