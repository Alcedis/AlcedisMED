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

class reportContentB07 extends reportExtensionB
{
    /**
     *
     *
     * @access
     * @param $renderer
     * @return void
     */
    public function init($renderer) {
        if ($this->_type == 'pdf') {
            $renderer->addPage();
        }
    }


    /**
     *
     *
     * @access
     * @return void
     */
    public function header()
    {
    }


    /**
     *
     *
     * @access
     * @param $renderer
     * @return void
     */
    public function generate($renderer)
    {
        $config = $this->_readConfig();
        $this->setSubDir('b');
        $this->setParam('sub', 'b');
        $this->_title = $config['head_report'];

        $additionalContent['selects'] = array(
            "(SELECT ts.t FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass AND ts.t IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS t",
        );

        $additionalContent['fields'] = array(
            "sit.patient_id AS 'patient_id'",
            "sit.t AS 't'",
            "sit.geschlecht AS 'geschlecht'",
            "IF(COUNT(
                DISTINCT IF(
                    tp.watchful_waiting = '1',
                    tp.therapieplan_id,
                    NULL
                )
            ), 1, NULL) AS watchful_waiting"
        );

        $bz01 = $this->loadRessource('b01', $additionalContent);

        if ($this->getParam('rohdatenx') == 'matrix') {
            $output = array();
            foreach ($bz01 as $row) {
                $output[] = $this->_getOutput($row);
            }
            $this->_data = $output;
        } else {
            $matrix = array();
            $patienten = array();
            $rawPatients = array();

            foreach ($bz01 as $i => $row) {
                $rawPatients[$i] = $this->_initRawPatient($row);
            }

            $sideCount = array(
                'w' => array(
                    'oneSided' => 0,
                    'bothSided' => 0
                ),
                'm' => array(
                    'oneSided' => 0,
                    'bothSided' => 0
                )
            );

            for ($y = 0; $y < 8; $y++) {
                for($x = 0; $x < 5; $x++) {
                    $matrix[$x][$y] = 0;
                }
            }

            unset($x,$y);

            foreach ($bz01 as $i => $row) {
                if ($this->IsTrue($row['primaerfall']) === true) {
                    if (in_array($row['seite'], array('L', 'R')) && in_array($row['geschlecht'], array('w', 'm'))) {

                        if (!isset($patienten[$row['patient_id']])) {
                            $patienten[$row['patient_id']] = array('c' => 0, 'g' => '');
                        }

                        $patienten[$row['patient_id']]['c']++;
                        $patienten[$row['patient_id']]['g'] = $row['geschlecht'];

                        // Primary case line
                        if ($this->_checkVarA($row, 'Tis') || $this->_checkVarB($row, 'Tis')) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 0, $rawPatients[$i], 'tis');
                        } else if ($this->_checkVarA($row, 'T1')  || $this->_checkVarB($row, 'T1')) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 1, $rawPatients[$i], 't1');
                        } else if ($this->_checkVarA($row, 'T2')  || $this->_checkVarB($row, 'T2')) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 2, $rawPatients[$i], 't2');
                        } else if ($this->_checkVarA($row, 'T3')  || $this->_checkVarB($row, 'T3')) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 3, $rawPatients[$i], 't3');
                        } else if ($this->_checkVarA($row, 'T4')  || $this->_checkVarB($row, 'T4')) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 4, $rawPatients[$i], 't4');
                        } else if ($this->_checkVarNA($row)  || $this->_checkVarNB($row)) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 5, $rawPatients[$i], 'nplus');
                        } else if ($this->_checkVarM1A($row)) {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 6, $rawPatients[$i], 'm1');
                        } else {
                            $matrix = $this->_checkAddPrimaryCase($matrix, $row, 7, $rawPatients[$i], 'nz');
                        }

                        // Primary case op lines
                        if ($this->_checkOpVarA($row, 'Tis') === true|| $this->_checkOpVarB($row, 'Tis') === true) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 0, $rawPatients[$i], 'tis');
                        } else if ($this->_checkOpVarA($row, 'T1') === true|| $this->_checkOpVarB($row, 'T1')) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 1, $rawPatients[$i], 't1');
                        } else if ($this->_checkOpVarA($row, 'T2')  || $this->_checkOpVarB($row, 'T2')) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 2, $rawPatients[$i], 't2');
                        } else if ($this->_checkOpVarA($row, 'T3')  || $this->_checkOpVarB($row, 'T3')) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 3, $rawPatients[$i], 't3');
                        } else if ($this->_checkOpVarA($row, 'T4')  || $this->_checkOpVarB($row, 'T4')) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 4, $rawPatients[$i], 't4');
                        } else if ($this->_checkOpVarNA($row)  || $this->_checkOpVarNB($row)) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 5, $rawPatients[$i], 'nplus');
                        } else if ($this->_checkOpVarM1A($row)  || $this->_checkOpVarM1B($row)) {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 6, $rawPatients[$i], 'm1');
                        } else {
                            $matrix = $this->_checkOpAndPrimaryCase($matrix, $row, 7, $rawPatients[$i], 'nz');
                        }

                        // Primary case ops with neoadjuvant lines
                        if ($this->_checkOpVarB($row, 'Tis') === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 0, $rawPatients[$i], 'tis');
                        } else if ($this->_checkOpVarB($row, 'T1') === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 1, $rawPatients[$i], 't1');
                        } else if ($this->_checkOpVarB($row, 'T2') === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 2, $rawPatients[$i], 't2');
                        } else if ($this->_checkOpVarB($row, 'T3') === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 3, $rawPatients[$i], 't3');
                        } else if ($this->_checkOpVarB($row, 'T4') === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 4, $rawPatients[$i], 't4');
                        } else if ($this->_checkOpVarNB($row) === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 5, $rawPatients[$i], 'nplus');
                        } else if ($this->_checkOpVarM1B($row) === true) {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 6, $rawPatients[$i], 'm1');
                        } else {
                            $matrix = $this->_checkOpNeoadjuvantAndPrimaryCase($matrix, $row, 7, $rawPatients[$i], 'nz');
                        }
                    }
                }
            }

            foreach ($patienten as $patientId => $patient) {
                // calc only when not raw data, else select first found patient and add check
                if ($this->getParam('rohdatenx') !== 'patient') {
                    if ($patient['c'] == 1) {
                        $sideCount[$patient['g']]['oneSided'] ++;
                    } else {
                        $sideCount[$patient['g']]['bothSided'] ++;
                    }
                } else {
                    // set sex and side flag for raw data
                    foreach ($rawPatients as $i => $rawPatient) {
                        if ($rawPatient['patient_id'] == $patientId) {
                            $ident1 = $patient['c'] == 1 ? 'single' : 'double';
                            $ident2 = $patient['g'] == 'm' ? 'male' : 'fem';

                            $rawPatients[$i][$ident1 . '_' . $ident2] = 1;
                            break;
                        }
                    }
                }
            }

            if ($this->getParam('rohdatenx') !== 'patient') {
                $data = array();

                $data[] = array(
                    ''                    => $config['primary_cases'],
                    $config['tis']        => $matrix[0][0],
                    $config['t1']         => $matrix[0][1],
                    $config['t2']         => $matrix[0][2],
                    $config['t3']         => $matrix[0][3],
                    $config['t4']         => $matrix[0][4],
                    $config['nplus']      => $matrix[0][5],
                    $config['m1']         => $matrix[0][6],
                    $config['unassigned'] => $matrix[0][7]
                );

                $data[] = array(); // Leer Zeile

                $data[] = array(
                    ''           => $config['bet'],
                    'tis'        => $matrix[2][0],
                    't1'         => $matrix[2][1],
                    't2'         => $matrix[2][2],
                    't3'         => $matrix[2][3],
                    't4'         => $matrix[2][4],
                    'nplus'      => $matrix[2][5],
                    'm1'         => $matrix[2][6],
                    'unassigned' => $matrix[2][7]
                );

                $data[] = array(
                    ''           => $config['mast'],
                    'tis'        => $matrix[3][0],
                    't1'         => $matrix[3][1],
                    't2'         => $matrix[3][2],
                    't3'         => $matrix[3][3],
                    't4'         => $matrix[3][4],
                    'nplus'      => $matrix[3][5],
                    'm1'         => $matrix[3][6],
                    'unassigned' => $matrix[3][7]
                );

                $data[] = array(
                    ''           => $config['neoadjuvant'],
                    'tis'        => $matrix[4][0],
                    't1'         => $matrix[4][1],
                    't2'         => $matrix[4][2],
                    't3'         => $matrix[4][3],
                    't4'         => $matrix[4][4],
                    'nplus'      => $matrix[4][5],
                    'm1'         => $matrix[4][6],
                    'unassigned' => $matrix[4][7]
                );

                $data[] = array(); // Leer Zeile
                $data[] = array(
                    ''  => '',
                    'w' => $config['weiblich'],
                    'm' => $config['maennlich']
                );
                $data[] = array(
                    ''  => $config['oneSided'],
                    'w' => $sideCount['w']['oneSided'],
                    'm' => $sideCount['m']['oneSided']
                );
                $data[] = array(
                    ''  => $config['bothSided'],
                    'w' => $sideCount['w']['bothSided'],
                    'm' => $sideCount['m']['bothSided']
                );
            } else {
                foreach ($rawPatients as $i => $patient) {
                    unset($rawPatients[$i]['patient_id']);
                }

                $data = $rawPatients;
            }

            $this->_data = $data;
        }

        $this->writeXLS();
    }


    /**
     * _checkAddPrimaryCase
     *
     * @access  protected
     * @param   array   $matrix
     * @param   array   $row
     * @param   int     $y
     * @param   array   $rawPatients
     * @param   string  $col
     * @return  array
     */
    protected function _checkAddPrimaryCase(array $matrix, array $row, $y, array &$rawPatients, $col)
    {
        $matrix[0][$y]++;
        $rawPatients['prim_' . $col] = 1;

        return $matrix;
    }


    /**
     * _checkOpAndPrimaryCase
     *
     * @access  protected
     * @param   array   $matrix
     * @param   array   $row
     * @param   int     $y
     * @param   array   $rawPatients
     * @param   string  $col
     * @return  array
     */
    protected function _checkOpAndPrimaryCase(array $matrix, array $row, $y, array &$rawPatients, $col)
    {
        if ($this->_hasPrimaryOp($row) === true) {
            $matrix[1][$y]++;

            // set prim case of
            $rawPatients['prim_' . $col] = 1;

            if (($this->_hasMastektomie($row) === true) ||
                ($this->_hasBet($row) === true)) {
                if ($this->_hasMastektomie($row) === true) {
                    $matrix[3][$y]++;
                    $rawPatients['mas_' . $col] = 1;
                } else if($this->_hasBet($row) === true) {
                    $matrix[2][$y]++;
                    $rawPatients['bet_' . $col] = 1;
                }
            }
        }

        return $matrix;
    }


    /**
     * _checkOpNeoadjuvantAndPrimaryCase
     *
     * @access  protected
     * @param   array   $matrix
     * @param   array   $row
     * @param   int     $y
     * @param   array   $rawPatients
     * @param   string  $col
     * @return  array
     */
    protected function _checkOpNeoadjuvantAndPrimaryCase(array $matrix, array $row, $y, array &$rawPatients, $col)
    {
        if ($this->_hasPrimaryOp($row) === true) {
            if (($this->_hasMastektomie($row) === true) ||
                ($this->_hasBet($row) === true)) {
                if ($this->_hasNeoadjuvantTherapy($row) === true) {
                    $matrix[4][$y]++;
                    $rawPatients['neo_' . $col] = 1;
                }
            }
        }

        return $matrix;
    }


    /**
     *
     *
     * @access
     * @param $dataset
     * @return array
     */
    private function _getOutput($dataset)
    {
        return array(
            'patient_nr'                => $dataset['patient_nr'],
            'nachname'                  => $dataset['nachname'],
            'vorname'                   => $dataset['vorname'],
            'geburtsdatum'              => $dataset['geburtsdatum'],
            'geschlecht'                => $dataset['geschlecht'],
            'primaerfall'               => $dataset['primaerfall'],
            'seite'                     => $dataset['seite'],
            'datumprimaer_op'           => $dataset['datumprimaer_op'],
            'durchgef_neoadj_therapie'  => $dataset['durchgef_neoadj_therapie'],
            'ct'                        => $dataset['ct'],
            'cn'                        => $dataset['cn'],
            'pt'                        => $dataset['pt'],
            'pn'                        => $dataset['pn'],
            'pn_sn'                     => $dataset['pn_sn'],
            'm'                         => $dataset['m'],
            'bet'                       => $dataset['bet'],
            'mastektomie'               => $dataset['mastektomie']
        );
    }


    /**
     * _getRawPatient
     *
     * @access  private
     * @param   array   $record
     * @return  array
     */
    protected function _initRawPatient(array $record)
    {
        return array(
            'patient_id'                => $record['patient_id'],
            'patient_nr'                => $record['patient_nr'],
            'nachname'                  => $record['nachname'],
            'vorname'                   => $record['vorname'],
            'geburtsdatum'              => $record['geburtsdatum'],
            'geschlecht'                => $record['geschlecht'],
            'primaerfall'               => $record['primaerfall'],
            'seite'                     => $record['seite'],
            'single_fem'                => 0,
            'double_fem'                => 0,
            'single_male'               => 0,
            'double_male'               => 0,
            'prim_tis'                  => 0,
            'prim_t1'                   => 0,
            'prim_t2'                   => 0,
            'prim_t3'                   => 0,
            'prim_t4'                   => 0,
            'prim_nplus'                => 0,
            'prim_m1'                   => 0,
            'prim_nz'                   => 0,
            'bet_tis'                   => 0,
            'bet_t1'                    => 0,
            'bet_t2'                    => 0,
            'bet_t3'                    => 0,
            'bet_t4'                    => 0,
            'bet_nplus'                 => 0,
            'bet_m1'                    => 0,
            'bet_nz'                    => 0,
            'mas_tis'                   => 0,
            'mas_t1'                    => 0,
            'mas_t2'                    => 0,
            'mas_t3'                    => 0,
            'mas_t4'                    => 0,
            'mas_nplus'                 => 0,
            'mas_m1'                    => 0,
            'mas_nz'                    => 0,
            'neo_tis'                   => 0,
            'neo_t1'                    => 0,
            'neo_t2'                    => 0,
            'neo_t3'                    => 0,
            'neo_t4'                    => 0,
            'neo_nplus'                 => 0,
            'neo_m1'                    => 0,
            'neo_nz'                    => 0
        );
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
     * @param $ptVal
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
     *
     *
     * @access
     * @param $row
     * @param $ptVal
     * @return bool
     */
    protected function _checkOpVarA($row, $ptVal)
    {
        return $this->_checkVarA($row, $ptVal);
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
     * @param $ctVal
     * @return bool
     */
    protected function _checkOpVarB($row, $ctVal)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNeoadjuvantTherapy($row) === true) &&
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
    protected function _checkOpVarNA($row)
    {
        return $this->_checkVarNA($row);
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
    protected function _checkOpVarNB($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNeoadjuvantTherapy($row) === true) &&
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
        if ($this->_hasM1($row) === true) {
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
    protected function _checkOpVarM1A($row)
    {
        return $this->_checkVarM1A($row);
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
    protected function _checkOpVarM1B($row)
    {
        if (($this->_hasPrimaryOp($row) === true) &&
            ($this->_hasNeoadjuvantTherapy($row) === true) &&
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
        if ((strlen($row['pn']) > 0) &&
            str_starts_with($row['pn'], "pN0")) {
            return true;
        }
        else if ((strlen($row['pn_sn']) > 0) &&
            str_starts_with($row['pn_sn'], "pN0")) {
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
    protected function _hasCn0($row)
    {
        if (str_starts_with($row['cn'], "cN0")) {
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
    protected function _hasBet($row)
    {
        if ($row['bet'] == '1') {
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
    protected function _hasMastektomie($row)
    {
        if ($row['mastektomie'] == '1') {
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
        if ((strlen($row['pn']) > 0) &&
            (str_starts_with($row['pn'], "pN1") ||
                str_starts_with($row['pn'], "pN2") ||
                str_starts_with($row['pn'], "pN3"))) {
            return true;
        }
        else if ((strlen($row['pn_sn']) > 0) &&
            (str_starts_with($row['pn_sn'], "pN1") ||
                str_starts_with($row['pn_sn'], "pN2") ||
                str_starts_with($row['pn_sn'], "pN3"))) {
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
    protected function _hasCnPlus($row)
    {
        if ((strlen($row['cn']) > 0) &&
            (str_starts_with($row['cn'], "cN1") ||
                str_starts_with($row['cn'], "cN2") ||
                str_starts_with($row['cn'], "cN3"))) {
            return true;
        }
        return false;
    }


    /**
     *
     *
     * @access
     * @return array
     */
    protected function _readConfig()
    {
        $config = array(
            'head_report'                => "Basisdaten Brust",
            'patient_nr'                 => "Patient Nr.",
            'primary_cases_mam'          => "Primärfälle Mammakarzinom",
            'primary_cases'              => "Primärfälle",
            'tis'                        => "Tis (= DCIS)",
            't1'                         => "T1",
            't2'                         => "T2",
            't3'                         => "T3",
            't4'                         => "T4",
            'nplus'                      => "N+",
            'm1'                         => "M1",
            'unassigned'                 => 'nicht zuzuordnen',
            'op_primary'                 => "Operierte Primärfälle",
            'bet'                        => "Mit BET",
            'mast'                       => "Mit Mastektomien",
            'neoadjuvant'                => "neoadjuvant vorbehandelte Primärfälle",
            'oneSided'                   => 'einseitig',
            'bothSided'                  => 'beidseitig (synchron)',
            'lbl_von'                    => ' von ',
            'prim_not_counted'           => ' Primärfällen die nicht gezählt werden konnten:',
            'primaerfall'                => 'Primärfall',
            'datumprimaer_op'            => "Datum Primär-OP",
            'durchgef_neoadj_therapie'   => "durchgef. neoadj. Therapie",
            'ct'                         => "cT",
            'pt'                         => "pT",
            'cn'                         => "cN",
            'pn_sn'                      => "pN(sn)",
            'bet'                        => "BET",
            'mastektomie'                => "Mastektomie",
            'seite'                      => "Seite",
            'nachname'                   => "Nachname",
            'vorname'                    => "Vorname",
            'geburtsdatum'               => "Geburtsdatum",
            'geschlecht'                 => "Geschlecht",
            'weiblich'                   => "Patientinnen (Frauen)",
            'maennlich'                  => "Patienten (Männer)"
        );

        return $config;
    }

}

?>
