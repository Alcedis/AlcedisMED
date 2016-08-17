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

class reportContentD07 extends reportExtensionD
{
    public function generate($renderer)
    {
        $config = $this->loadConfigs('d07');

        $this->_title = $config['head_report'];

        $additionalContent['selects'] = array(
            "(SELECT ts.tnm_praefix FROM tumorstatus ts WHERE ts.erkrankung_id = t.erkrankung_id AND ts.anlass = t.anlass AND ts.t IS NOT NULL ORDER BY ts.datum_sicherung DESC, ts.sicherungsgrad ASC, ts.datum_beurteilung DESC LIMIT 1) AS tnm_praefix",

        );

        $additionalContent['joins'] = array(
            "LEFT JOIN therapieplan tp ON s.form = 'therapieplan' AND tp.therapieplan_id  = s.form_id"
        );

        $additionalContent['fields'] = array(
            "sit.tnm_praefix",
            "sit.t",
            "IF(COUNT(
                DISTINCT IF(
                    tp.palliative_versorgung = '1',
                    tp.therapieplan_id,
                    NULL
                )
            ), 1, NULL) AS best_supportive_care",

            "GROUP_CONCAT(DISTINCT
                IF(
                    s.form = 'eingriff' AND LENGTH(SUBSTRING(s.report_param, 5)),
                    SUBSTRING(s.report_param, 5),
                    NULL
                ) SEPARATOR ' '
            ) AS 'possible_endoskopien'",

            "IF(
                COUNT(
                    DISTINCT IF(
                        th_sys.intention IN ('pal', 'pala', 'palna'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )
                ) OR
                COUNT(
                    DISTINCT IF(
                        th_str.intention IN  ('pal', 'pala', 'palna'),
                        th_str.strahlentherapie_id,
                        NULL
                    )
                ),
                1,
                NULL
            ) AS 'palliativ_therapie'",

            "IF(
                COUNT(
                    DISTINCT IF(
                        th_sys.therapie_systemisch_id IS NOT NULL AND
                        th_sys.intention IS NOT NULL AND
                        th_sys.intention NOT IN ('pal', 'pala', 'palna'),
                        th_sys.therapie_systemisch_id,
                        NULL
                    )
                ) OR
                COUNT(
                    DISTINCT IF(
                        th_str.strahlentherapie_id IS NOT NULL AND
                        th_str.intention IS NOT NULL AND
                        th_str.intention NOT IN ('pal', 'pala', 'palna'),
                        th_str.strahlentherapie_id,
                        NULL
                    )
                ),
                1,
                NULL
            ) AS 'r13'",

        );

        $dz01 = $this->loadRessource('d01', $additionalContent);


        if ($this->getParam('roh_daten') == 1) {
            $output = array();

            foreach ($dz01 as $row) {
                $output[] = $this->_getOutput($row);
            }

            $this->_data = $output;

        } else {
            $matrix = array();

            for ($y = 0; $y < 5; $y++) {
                for($x = 0; $x < 2; $x++) {
                    $matrix[$x][$y] = 0;
                }
            }

            unset($x,$y);

            $primaryCases  = 0;
            $notCounted    = array();

            foreach ($dz01 as $row) {
                if ($this->IsTrue($row['primaerfall'])) { //R10

                    $primaryCases++;

                    $counted = false;

                    for ($y = 0; $y < 5; $y++) {
                        for($x = 0; $x < 2; $x++) {
                            $funcName = "_validMatrix{$x}{$y}";
                            $val = (int) $this->{$funcName}($row);

                            if ($val === 1) {
                                $matrix[$x][$y]++;
                                $counted = true;
                            }
                        }
                    }

                    if ($counted === false) {
                        $notCounted[] = $this->_getOutput($row);
                    }
                }
            }

            $data = array();

            $data[] = array(
                'primary_cases'     => $config['colon'],
                'op_e'              => $matrix[0][0],
                'op_n'              => $matrix[0][1],
                'endo'              => $matrix[0][2],
                'nop_pall'          => $matrix[0][3],
                'nop_kura'          => $matrix[0][4]
            );

            $data[] = array(
                'primary_cases'     => $config['rectum'],
                'op_e'              => $matrix[1][0],
                'op_n'              => $matrix[1][1],
                'endo'              => $matrix[1][2],
                'nop_pall'          => $matrix[1][3],
                'nop_kura'          => $matrix[1][4]
            );

            if (count($notCounted) > 0) {
                $data[] = array('');
                $data[] = array('');
                $data[] = array(count($notCounted) . $config['lbl_von'] . $primaryCases . $config['prim_not_counted']);
                $data[] = array('');

                $notCountedHead[] = array(
                    $config['nachname'],
                    $config['vorname'],
                    $config['geburtsdatum'],
                    $config['patient_nr'],
                    $config['primaerfall'],
                    $config['diagnose'],
                    $config['zugeordnet_zu'],
                    $config['operativer_fall_kolon'],
                    $config['operativer_fall_rektum'],
                    $config['t'],
                    $config['tnm_praefix'],
                    $config['elek_primaer_oprezidiv_op'],
                    $config['datum_primaer_op_rezidiv_op'],
                    $config['possible_endoskopien'],
                    $config['best_supportive_care'],
                    $config['palliativ_therapie'],
                    $config['r13']
                );

                $notCounted = array_merge($notCountedHead, $notCounted);
            }

            $this->_data = array_merge($data, $notCounted);

        }

        $this->writeXLS();
    }

    private function _getOutput($dataset)
    {
        return array(
            'nachname'                      => $dataset['nachname'],
            'vorname'                       => $dataset['vorname'],
            'geburtsdatum'                  => $dataset['geburtsdatum'],
            'patient_nr'                    => $dataset['patient_nr'],
            'primaerfall'                   => $dataset['primaerfall'],
            'diagnose'                      => $dataset['diagnose'],
            'zugeordnet_zu'                 => $dataset['zugeordnet_zu'],
            'operativer_fall_kolon'         => $dataset['operativer_fall_kolon'],
            'operativer_fall_rektum'        => $dataset['operativer_fall_rektum'],
            't'                             => $dataset['t'],
            'tnm_praefix'                   => $dataset['tnm_praefix'],
            'elek_primaer_oprezidiv_op'     => $dataset['elek_primaer_oprezidiv_op'],
            'datum_primaer_op_rezidiv_op'   => $dataset['datum_primaer_op_rezidiv_op'],
            'possible_endoskopien'          => $dataset['possible_endoskopien'],
            'best_supportive_care'          => $dataset['best_supportive_care'],
            'palliativ_therapie'            => $dataset['palliativ_therapie'],
            'r13'                           => $dataset['r13']
        );
    }


    protected function _checkR2($row)
    {
        return ((int) $row['elek_primaer_oprezidiv_op'] == 1);
    }


    /**
     * _checkR3
     * elektive Primär-OP/Rezidiv-OP = 0
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR3($row)
    {
        return (strlen($row['elek_primaer_oprezidiv_op']) && (int) $row['elek_primaer_oprezidiv_op'] == 0);
    }


    /**
     * _checkR4
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR4($row)
    {
        return (strlen($row['datum_primaer_op_rezidiv_op']) > 0);
    }


    /**
     * _checkR5
     * OPS-Codes mit 5-452.* (außer 5-452.0 und 5-452.1) oder 5-482*
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR5($row)
    {
        if (strlen($row['possible_endoskopien']) > 0) {
            foreach (explode(' ', $row['possible_endoskopien']) as $opsCode) {
                $cond1 = (str_starts_with($opsCode, '5-452.') === true && str_ends_with($opsCode, array('.0', '.1')) === false);
                $cond2 = str_starts_with($opsCode, '5-482.');

                if ($cond1 === true || $cond2 === true) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function _checkR7($row)
    {
        return ((int) $row['best_supportive_care'] == 1 || (int) $row['palliativ_therapie'] == 1);
    }


    /**
     * _checkR8
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR8($row)
    {
        return ((int) $row['operativer_fall_kolon'] == 1);
    }


    /**
     * _checkR9
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR9($row)
    {
        return ((int) $row['operativer_fall_rektum'] == 1);
    }


    /**
     * _checkR11
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR11($row)
    {
        $diag = strlen($row['zugeordnet_zu']) > 0 ? $row['zugeordnet_zu'] : $row['diagnose'];

        return (str_starts_with($diag, array('D01.0', 'C18')) === true);
    }


    /**
     * _checkR12
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR12($row)
    {
        $diag = strlen($row['zugeordnet_zu']) > 0 ? $row['zugeordnet_zu'] : $row['diagnose'];

        return (str_starts_with($diag, array('C20', 'D01.1', 'D01.2')) === true);
    }


    /**
     * _checkR13
     *
     * @access  p
     * @param $row
     * @return  bool
     */
    protected function _checkR13($row)
    {
        return ((int) $row['r13'] === 1);
    }

    protected function _checkR14($row)
    {
        return (str_starts_with($row['tnm_praefix'], 'y') === true && str_ends_with($row['t'], 'T0') === true);
    }


    /**
     * Primärfall Rektum und Endoskopie
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR15($row)
    {
        if (strlen($row['possible_endoskopien']) > 0) {
            foreach (explode(' ', $row['possible_endoskopien']) as $opsCode) {
                $cond1 = str_starts_with($opsCode, '5-482.');
                $cond2 = str_starts_with($opsCode, '5-452.') === true && str_ends_with($opsCode, array('.0', '.1')) === false;

                if ($cond1 === true || $cond2 === true) {
                    return true;
                }
            }
        }

        return false;
    }


    /**
     * _checkR16
     *
     * @access  protected
     * @param   array $row
     * @return  bool
     */
    protected function _checkR16($row)
    {
        return (strlen($row['datum_primaer_op_rezidiv_op']) == 0);
    }


    /**
     * _checkR17
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR17($row) {
        return ($this->_validMatrix14($row) === false);
    }


    /**
     * _checkR18
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR18($row) {
        return ($this->_validMatrix04($row) === false);
    }


    /**
     * _checkR19
     *
     * Patient wurde nicht in der Spalte "Primärfall Kolon und elektive OP" oder "Primärfall Kolon Notfall-OP" gezählt
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR19($row)
    {
        return ($this->_validMatrix00($row) === false && $this->_validMatrix01($row) === false);
    }


    /**
     * _checkR20
     *
     * Patient wurde nicht in der Spalte "Primärfall Rektum und elektive OP" oder
     * "Primärfall Rektum Notfall-OP" gezählt
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _checkR20($row)
    {
        return ($this->_validMatrix10($row) === false && $this->_validMatrix11($row) === false);
    }


    /**
     * _validMatrix00
     * Primärfall Kolon und elektive OP
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix00($row)
    {
        return ($this->_checkR8($row) === true && $this->_checkR2($row) === true);
    }


    /**
     * _validMatrix01
     * Primärfall Kolon und Notfall-OP
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix01($row)
    {
        return ($this->_checkR8($row) === true && $this->_checkR3($row) === true);
    }


    /**
     * Feld 8
     * Primärfall Kolon und Endoskopie
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix02($row)
    {
        return (
            $this->_checkR4($row)  === true &&
            $this->_checkR11($row) === true &&
            $this->_checkR5($row)  === true &&
            $this->_checkR19($row) === true
        );
    }


    /**
     * Feld 9
     * Primärfall Kolon und nicht operativ palliativ
     *
     * @param type $row
     * @return type
     */
    protected function _validMatrix03($row)
    {
        return ($this->_checkR18($row) === true && $this->_checkR16($row) === true && $this->_checkR11($row) === true && $this->_checkR7($row) === true);
    }


    /**
     * Feld 10
     * Primärfall Kolon und nicht operativ kurativ
     *
     * @param type $row
     * @return type
     */
    protected function _validMatrix04($row)
    {
        return ($this->_checkR16($row) === true && $this->_checkR11($row) === true && $this->_checkR13($row) === true && $this->_checkR14($row) === true);
    }


    /**
     * Feld 11
     * Primärfall Rektum und elektive OP
     *
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix10($row)
    {
        return ($this->_checkR9($row) === true && $this->_checkR2($row) === true);
    }


    /**
     * Feld 12
     * Primärfall Rektum und Notfall-OP
     *
     * @param type $row
     * @return type
     */
    protected function _validMatrix11($row)
    {
        return ($this->_checkR9($row) === true && $this->_checkR3($row) === true);
    }


    /**
     * Feld 13
     * Primärfall Rektum und Endoskopie
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix12($row)
    {
        return (
            $this->_checkR4($row)  === true &&
            $this->_checkR12($row) === true &&
            $this->_checkR15($row) === true &&
            $this->_checkR20($row) === true
        );
    }


    /**
     * Feld 14
     * Primärfall Rektum und nicht operativ palliativ
     *
     * @param type $row
     * @return type
     */
    protected function _validMatrix13($row)
    {
        return ($this->_checkR12($row) === true && $this->_checkR16($row) === true && $this->_checkR7($row) === true && $this->_checkR17($row) === true);
    }


    /**
     * _validMatrix14
     * Primärfall Rektum und nicht operativ kurativ
     *
     * @access  protected
     * @param   array   $row
     * @return  bool
     */
    protected function _validMatrix14($row)
    {
        return ($this->_checkR12($row) === true && $this->_checkR16($row) === true && $this->_checkR13($row) === true && $this->_checkR14($row) === true);
    }
}

?>
