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

class reportContentKh07 extends reportExtensionKh
{
    /**
     * _uiccCols
     *
     * @access  protected
     * @var     array
     */
    protected $_uiccCols = array(
        'IVC' => 'I',
        'IVB' => 'H',
        'IVA' => 'G',
        'III' => 'F',
        'II'  => 'E',
        'I'   => 'D',
        '0'   => 'C'
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

        foreach ($this->_uiccCols as $col) {
            $data["{$col}14"] = 0;
            $data["{$col}15"] = 0;
            $data["{$col}17"] = 0;
            $data["{$col}18"] = 0;
        }

        return $data;
    }


    /**
     * generate
     *
     * @access  public
     * @param   $renderer
     * @return  void
     */
    public function generate(PHPExcel $renderer)
    {
        $config = $this->loadConfigs('kh07', false, true);

        $matrix = array();

        $kh01 = $this->loadRessource('kh01');

        if ($this->getParam('roh_daten') == 1) {

            $matrix['A1'] = $config['head_report'];
            $matrix['A3'] = $config['lbl_auswertungszeitraum'] .
                attach_label($config['lbl_von'], $this->getParam('datum_von')) .
                attach_label($config['lbl_bis'], $this->getParam('datum_bis'))
            ;

            $renderer->removeSheetByIndex(0);
            $renderer->createSheet(0);
            $renderer->setActiveSheetIndex(0);

            $start = 5;

            if (count($kh01) > 0) {
                $matrix["A{$start}"] = $config['nachname'];
                $matrix["B{$start}"] = $config['vorname'];
                $matrix["C{$start}"] = $config['geburtsdatum'];
                $matrix["D{$start}"] = $config['patient_nr'];
                $matrix["E{$start}"] = $config['primaerfall'];
                $matrix["F{$start}"] = $config['datumprimaer_op'];
                $matrix["G{$start}"] = $config['diagnose'];
                $matrix["H{$start}"] = $config['anlass'];
                $matrix["I{$start}"] = $config['uicc'];
            }

            foreach ($kh01 as $i => $record) {
                $matrix = $this->_getOutput($matrix, $start + $i + 1, $record);
            }
        } else {
            $matrix = $this->_initializeMatrix();

            $matrix['B6'] = $config['lbl_auswertungszeitraum'] .
                attach_label($config['lbl_von'], $this->getParam('datum_von')) .
                attach_label($config['lbl_bis'], $this->getParam('datum_bis'))
            ;

            $uiccCols = $this->_uiccCols;

            foreach ($kh01 as $record) {
                if ((int) $record['primaerfall'] !== 1 || $this->_checkUicc($record['uicc']) === false) {
                    continue;
                }

                $row = $this->_checkRow($record);

                if ($row !== null) {
                    $uicc = $record['uicc'];

                    $col = null;

                    foreach ($uiccCols as $uiccCode => $uiccCol) {
                        if (str_starts_with($uicc, $uiccCode) === true) {
                            $col = $uiccCol;
                            break;
                        }
                    }

                    if ($col !== null) {
                        $matrix["{$col}{$row}"] += 1;
                    }
                }
            }
        }

        $this->_data = $matrix;

        $this->parseXLS();
    }


    /**
     * _checkRow
     *
     * @access  protected
     * @param   array $record
     * @return  int
     */
    protected function _checkRow(array $record)
    {
        $row = null;

        switch (true) {
            case $this->ckhz_mundhoehle_op($record):  $row = 14; break;
            case $this->ckhz_mundhoehle_nop($record): $row = 15; break;
            case $this->ckhz_sonst_op($record):       $row = 17; break;
            case $this->ckhz_sonst_nop($record):      $row = 18; break;
        }

        return $row;
    }


    /**
     * _getOutput
     *
     * @access  protected
     * @param   array   $matrix
     * @param   int     $i
     * @param   array   $record
     * @return  array
     */
    protected function _getOutput($matrix, $i, $record)
    {
        $matrix["A{$i}"] = utf8_encode($record['nachname']);
        $matrix["B{$i}"] = utf8_encode($record['vorname']);
        $matrix["C{$i}"] = date('d.m.Y', strtotime($record['geburtsdatum']));
        $matrix["D{$i}"] = $record['patient_nr'];
        $matrix["E{$i}"] = $record['primaerfall'];

        $opDate = $record['datumprimaer_op'];

        if (strlen($opDate) > 0) {
            $matrix["F{$i}"] = date('d.m.Y', strtotime($opDate));
        }

        $matrix["G{$i}"] = $record['diagnose'];
        $matrix["H{$i}"] = utf8_encode($record['anlass_case']);
        $matrix["I{$i}"] = $record['uicc'];

        return $matrix;
    }
}
?>
