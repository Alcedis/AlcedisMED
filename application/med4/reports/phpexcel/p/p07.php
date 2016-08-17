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

class reportContentP07 extends reportExtensionP
{
    /**
     * _cols
     *
     * @access  protected
     * @var     array
     */
    protected $_cols = array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N');


    /**
     * _initializeMatrix
     *
     * @access  protected
     * @return  array
     */
    protected function _initializeMatrix()
    {
        $data = array();

        foreach ($this->_cols as $col) {
            $data["{$col}15"] = 0;
            $data["{$col}16"] = 0;
            $data["{$col}17"] = 0;
            $data["{$col}18"] = 0;
            $data["{$col}19"] = 0;
            $data["{$col}20"] = 0;
            $data["{$col}21"] = 0;

            if ($col !== 'D' && $col !== 'E') {
                $data["{$col}24"] = 0;
            }

            $data["{$col}27"] = 0;
            $data["{$col}28"] = 0;
            $data["{$col}29"] = 0;
            $data["{$col}31"] = 0;
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
        $config = $this->loadConfigs('p07', false, true);

        $year = isset($this->_params['jahr']) && strlen($this->_params['jahr']) ? $this->_params['jahr'] : date('Y');

        $matrix = array();

        $p01 = $this->loadRessource('p01');

        // output raw data if selected
        if ($this->getParam('roh_daten') == 1) {

            $matrix['A1'] = $config['head_report'];
            $matrix['A3'] = $config['lbl_filter_jahr'] . ': ' . $year;

            $renderer->removeSheetByIndex(0);
            $renderer->setActiveSheetIndex(0);

            $start = 6;

            foreach ($p01 as $i => $record) {
                $matrix = $this->_getOutput($matrix, $start++, $record);
            }
        } else { // output matrix

            $renderer->removeSheetByIndex(1);
            $renderer->setActiveSheetIndex(0);

            $matrix = $this->_initializeMatrix();

            $matrix['B6'] = $config['indexyear'] . $year;

            // data cache
            $cache = array(
                'primaryCases' => array()
            );

            // fill first block - check primary cases with correct reference year
            foreach ($p01 as $record) {
                if ($this->_isPrimary($record) === true && substr($record['bezugsdatum'], 0, 4) === $year) {
                    $row = $this->_checkRow($record);
                    $col = $this->_checkCol($record);

                    if ($row !== null && $col !== null) {
                        $matrix["{$col}{$row}"] += 1;

                        // prepare cache for row 31
                        if ($this->_cpz_ges($record) == '1') {
                            $cache['primaryCases'][] = $record['erkrankung_id'];
                        }

                        if ($col !== 'D' && $col !== 'E' &&
                            ($this->_has_active_surveillance($record) === true ||
                                $this->_has_watchful_waiting($record) === true)
                        ) {
                            $matrix["{$col}24"] += 1;
                        }
                    }
                }
            }

            // attention: filter relapse get also the youngest if more then one relapse in year exists.
            // so we must do it several for each conditions
            $newRelapses = $this->_filterRelapses($p01, $year, array('rezidiv', 'psa_rezidiv'));

            foreach ($newRelapses as $record) {
                $col = $this->_checkRelapseCol($record);

                if ($col !== null) {
                    $matrix["{$col}27"] += 1;
                }
            }

            // attention: filter relapse get also the youngest if more then one relapse in year exists.
            // so we must do it several for each conditions
            $newMetastasis = $this->_filterRelapses($p01, $year, array('metastasen'));

            foreach ($newMetastasis as $record) {
                $col = $this->_checkRelapseCol($record);

                if ($col !== null) {
                    $matrix["{$col}28"] += 1;
                }
            }

            // attention: filter relapse get also the youngest if more then one relapse in year exists.
            // so we must do it several for each conditions
            $newRelapsesMetastasis = $this->_filterRelapses($p01, $year, array('rezidiv', 'psa_rezidiv', 'metastasen'));

            foreach ($newRelapsesMetastasis as $record) {
                $col = $this->_checkRelapseCol($record);

                if ($col !== null) {
                    $matrix["{$col}29"] += 1;

                    // if patient is also a primary case in this year and was counted
                    if (in_array($record['erkrankung_id'], $cache['primaryCases']) === true) {
                        $matrix["{$col}31"] += 1;
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

            case $this->_cpz_niedriges_risiko($record)      : $row = 15; break;
            case $this->_cpz_mittleres_risiko($record)      : $row = 16; break;
            case $this->_cpz_hohes_risiko($record)          : $row = 17; break;
            case $this->_cpz_lokal_fortgeschritten($record) : $row = 18; break;
            case $this->_cpz_fortgeschritten($record)       : $row = 19; break;
            case $this->_cpz_metastasiert($record)          : $row = 20; break;
            default                                         : $row = 21; break;
        }

        return $row;
    }


    /**
     * _checkCol
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _checkCol(array $record)
    {
        $col = null;

        switch (true) {
            case $this->_is_bezug_as($record)                             : $col = 'D'; break;
            case $this->_is_bezug_ww($record)                             : $col = 'E'; break;
            case $this->_is_bezug_rpe($record)                            : $col = 'F'; break;
            case $this->_is_bezug_rze($record)                            : $col = 'G'; break;
            case $this->_is_bezug_rze_with_random($record)                : $col = 'H'; break;
            case $this->_is_bezug_perk($record)                           : $col = 'I'; break;
            case $this->_is_bezug_seed($record)                           : $col = 'J'; break;
            case $this->_is_bezug_hdr($record)                            : $col = 'K'; break;
            case $this->_is_bezug_cpz_andere_lokale_therapie($record)     : $col = 'L'; break;
            case $this->_is_bezug_cpz_ausschliesslich_systemisch($record) : $col = 'M'; break;
            case $this->_is_bezug_cpz_andere_behandlung($record)          : $col = 'N'; break;
        }

        return $col;
    }


    /**
     * _checkRelapseCol
     * Patient darf in diesem Block insgesamt nur einmal gezählt werden
     *
     * Falls mehrere Kriterien zutreffen bitte folgendermaßen in die einzelnen Spalten einteilen:
     * Prio 1) 'RPE' = 1
     * Prio 2) 'RZE' = 1 UND 'Zufallsbefund' = leer
     * Prio 3) 'RZE' = 1 UND 'Zufallsbefund' = 1
     * Prio 4) 'definitive perkutane Strahlentherapie' = 1
     * Prio 5) 'Str. permanent seed' = 1
     * Prio 6) 'HDR-Brachytherapie' =1
     * Prio 7) 'cpz_andere_lokale_therapie' = 1
     * Prio 8) 'cpz_ausschließlich_systemisch' = 1
     * Prio 9) 'cpz_andere_behandlung' = 1
     * Prio 10) 'Active Surveillance' = 1
     * Prio 11) 'Watchful Waiting' = 1
     *
     * @access  protected
     * @param   array $record
     * @return  string
     */
    protected function _checkRelapseCol(array $record)
    {
        $col = null;

        switch (true) {
            case $this->_has_rpe($record)                               : $col = 'F'; break;
            case $this->_has_rze_without_incidental_finding($record)    : $col = 'G'; break;
            case $this->_has_rze_with_incidental_finding($record)       : $col = 'H'; break;
            case $this->_has_perk($record)                              : $col = 'I'; break;
            case $this->_has_seed($record)                              : $col = 'J'; break;
            case $this->_has_hdr($record)                               : $col = 'K'; break;
            case $this->_has_cpz_andere_lokale_therapie($record)        : $col = 'L'; break;
            case $this->_has_cpz_ausschliesslich_systemisch($record)    : $col = 'M'; break;
            case $this->_has_cpz_andere_behandlung($record)             : $col = 'N'; break;
            case $this->_has_active_surveillance($record)               : $col = 'D'; break;
            case $this->_has_watchful_waiting($record)                  : $col = 'E'; break;
        }

        return $col;
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
        $matrix["A{$i}"]  = utf8_encode($record['nachname']);
        $matrix["B{$i}"]  = utf8_encode($record['vorname']);
        $matrix["C{$i}"]  = todate($record['geburtsdatum'], 'de');
        $matrix["D{$i}"]  = $record['patient_nr'];
        $matrix["E{$i}"]  = todate($record['bezugsdatum'], 'de');
        $matrix["F{$i}"]  = utf8_encode($this->_getReferenceDateTherapyLabel($record['therapie_bezugsdatum']));
        $matrix["G{$i}"]  = $record['primaerfall'];
        $matrix["H{$i}"]  = $record['zufallsbefund'];
        $matrix["I{$i}"]  = utf8_encode($record['anlass']);
        $matrix["J{$i}"]  = $record['gesamt_psa'];
        $matrix["K{$i}"]  = $record['rpe'];
        $matrix["L{$i}"]  = todate($record['bezug_rpe'], 'de');
        $matrix["M{$i}"]  = $record['rze'];
        $matrix["N{$i}"]  = todate($record['bezug_rze'], 'de');
        $matrix["O{$i}"]  = $record['ct'];
        $matrix["P{$i}"]  = $record['cn'];
        $matrix["Q{$i}"]  = $record['pt'];
        $matrix["R{$i}"]  = $record['pn'];
        $matrix["S{$i}"]  = $record['m'];
        $matrix["T{$i}"]  = $record['gleason_score'];
        $matrix["U{$i}"]  = $record['str_permanent_seed'];
        $matrix["V{$i}"]  = todate($record['bezug_seed'], 'de');
        $matrix["W{$i}"]  = $record['hdr_brachytherapie'];
        $matrix["X{$i}"]  = todate($record['bezug_hdr'], 'de');
        $matrix["Y{$i}"]  = $record['def_perkutane_strahlentherapie'];
        $matrix["Z{$i}"]  = todate($record['bezug_perk'], 'de');
        $matrix["AA{$i}"] = $record['pall_vers'];
        $matrix["AB{$i}"] = $record['active_surveillance'];
        $matrix["AC{$i}"] = todate($record['bezug_as'], 'de');
        $matrix["AD{$i}"] = $record['watchful_waiting'];
        $matrix["AE{$i}"] = todate($record['bezug_ww'], 'de');
        $matrix["AF{$i}"] = utf8_encode($record['sonstige_therapie_art']);
        $matrix["AG{$i}"] = $record['pall_vers'];
        $matrix["AH{$i}"] = todate($record['psa_rezidiv'], 'de');
        $matrix["AI{$i}"] = $record['rezidiv'];
        $matrix["AJ{$i}"] = todate($record['metastasen'], 'de');
        $matrix["AK{$i}"] = $record['cpz_niedriges_risiko'];
        $matrix["AL{$i}"] = $record['cpz_mittleres_risiko'];
        $matrix["AM{$i}"] = $record['cpz_hohes_risiko'];
        $matrix["AN{$i}"] = $record['cpz_lokal_fortgeschritten'];
        $matrix["AO{$i}"] = $record['cpz_fortgeschritten'];
        $matrix["AP{$i}"] = $record['cpz_metastasiert'];
        $matrix["AQ{$i}"] = $record['cpz_andere_lokale_therapie'];
        $matrix["AR{$i}"] = $record['cpz_ausschliesslich_systemisch'];
        $matrix["AS{$i}"] = $record['cpz_andere_behandlung'];
        $matrix["AT{$i}"] = $record['cpz_ges'];
        $matrix["AU{$i}"] = todate($record['bezugsdatum_cpz_andere_lokale_therapie'], 'de');
        $matrix["AV{$i}"] = todate($record['bezugsdatum_cpz_ausschliesslich_systemisch'], 'de');
        $matrix["AW{$i}"] = todate($record['bezugsdatum_cpz_andere_behandlung'], 'de');

        return $matrix;
    }
}

?>
