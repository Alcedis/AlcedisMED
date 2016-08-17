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

class reportContentH07 extends reportExtensionH
{

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
     * generate
     *
     * @access  public
     * @param   $renderer
     * @return  void
     */
    public function generate($renderer)
    {
        $config = $this->loadConfigs('h07');

        $this->_title = $config['head_report'];

        $hz01_1 = $this->loadRessource('h01_1');

        if ($this->getParam('roh_daten') == 1) {
            $output = array();

            foreach ($hz01_1 as $row) {
                $output[] = $this->_getOutput($row);
            }

            $this->_data = $output;

        } else {
            $matrix = array();

            for($y = 0; $y <= 12; $y++) {
                $matrix[0][$y] = 0;
            }

            $matrix[1][0] = 0;
            $matrix[2][0] = 0;

            unset($y);

            $primaryCases = 0;
            $notCounted    = array();

            foreach ($hz01_1 as $record) {
                if ($this->IsTrue($record['primaerfall']) === true) {
                    $primaryCases++;
                    $record['ajcc'] = strlen($record['ajcc']) ? $record['ajcc'] : $record['ajcc_prae'];
                    if ((int) $record['invasives_malignom'] === 1) {
                        $t = $record['t'];
                        $n = $record['n'];
                        $m = $record['m'];

                        if ((int) $record['uvea'] === 1 || (int) $record['konjunktiva'] === 1 || (int) $record['schleimhaut'] === 1) {
                            // Feld 12 - Invasive maligne Melanome und Uvea, Konjunktiva, Schleimhaut
                            $matrix[0][11]++;
                        } elseif (false === $this->_countAjcc($matrix, $record['ajcc'])) {
                            if (str_ends_with($t, 'X') === true && str_ends_with($n, $this->_nCodings) === true && str_ends_with($m, '0') === true) {
                                // Feld 10 - Invasive maligne Melanome und Tx, N+ ohne M1
                                $matrix[0][9]++;
                            } elseif (str_ends_with($t, 'X') === true && str_ends_with($n, array('X', 'X(sn)')) === true && str_ends_with($m, array('1', '1a', '1b', '1c')) === true) {
                                // Feld 11 - Invasive maligne Melanome und Tx, Nx, M1
                                $matrix[0][10]++;
                            } else {
                                // Feld 13 - Invasive Melanome und nicht zuzuordnen
                                $matrix[0][12]++;
                            }
                        }
                    } elseif ((int) $record['epithelialer_tumor'] === 1) {
                        $matrix[1][0]++;
                    } elseif ((int) $record['seltene_tumore'] === 1) {
                        $matrix[2][0]++;
                    } else {
                        $notCounted[] = $this->_getOutput($record);
                    }
                }
            }

            $data = array();

            $data[] = array(
                'primary_cases_skin'    => $config['inv_malig'],
                'ia'                    => $matrix[0][0],
                'ib'                    => $matrix[0][1],
                'iia'                   => $matrix[0][2],
                'iib'                   => $matrix[0][3],
                'iic'                   => $matrix[0][4],
                'iiia'                  => $matrix[0][5],
                'iiib'                  => $matrix[0][6],
                'iiic'                  => $matrix[0][7],
                'iv'                    => $matrix[0][8],
                'txnm0'                 => $matrix[0][9],
                'txnxm1'                => $matrix[0][10],
                'uvearkonschleim'       => $matrix[0][11],
                'nz'                    => $matrix[0][12]
            );

            $data[] = array('');
            $data[] = array('');

            $data[] = array(
                'primary_cases_skin'    => $config['epi'],
                'ia'                    => $matrix[1][0],
                'ib'                    => null,
                'iia'                   => null,
                'iib'                   => null,
                'iic'                   => null,
                'iiia'                  => null,
                'iiib'                  => null,
                'iiic'                  => null,
                'iv'                    => null,
                'txnm0'                 => null,
                'txnxm1'                => null,
                'uvearkonschleim'       => null,
                'nz'                    => null
            );

            $data[] = array(
                'primary_cases_skin'    => $config['kut'],
                'ia'                    => $matrix[2][0],
                'ib'                    => null,
                'iia'                   => null,
                'iib'                   => null,
                'iic'                   => null,
                'iiia'                  => null,
                'iiib'                  => null,
                'iiic'                  => null,
                'iv'                    => null,
                'txnm0'                 => null,
                'txnxm1'                => null,
                'uvearkonschleim'       => null,
                'nz'                    => null
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
                    $config['ajcc'],
                    $config['ajcc_prae'],
                    $config['t'],
                    $config['n'],
                    $config['m'],
                    $config['invasives_malignom'],
                    $config['uvea'],
                    $config['konjunktiva'],
                    $config['schleimhaut'],
                    $config['epithelialer_tumor'],
                    $config['seltene_tumore']
                );

                $notCounted = array_merge($notCountedHead, $notCounted);
            }

            $this->_data = array_merge($data, $notCounted);
        }

        $this->writeXLS();
    }


    /**
     * _getOutput
     *
     * @access  public
     * @param   array   $dataset
     * @return  array
     */
    private function _getOutput($dataset)
    {
        return array(
            'nachname'            => $dataset['nachname'],
            'vorname'            => $dataset['vorname'],
            'geburtsdatum'       => $dataset['geburtsdatum'],
            'patient_nr'         => $dataset['patient_nr'],
            'primaerfall'        => $dataset['primaerfall'],
            'diagnose'           => $dataset['diagnose'],
            'ajcc'               => $dataset['ajcc'],
            'ajcc_prae'          => $dataset['ajcc_prae'],
            't'                  => $dataset['t'],
            'n'                  => $dataset['n'],
            'm'                  => $dataset['m'],
            'invasives_malignom' => $dataset['invasives_malignom'],
            'uvea'               => $dataset['uvea'],
            'konjunktiva'        => $dataset['konjunktiva'],
            'schleimhaut'        => $dataset['schleimhaut'],
            'epithelialer_tumor' => $dataset['epithelialer_tumor'],
            'seltene_tumore'     => $dataset['seltene_tumore']
        );
    }


    /**
     * Check and count ajcc
     *
     * @access protected
     * @param $matrix
     * @param $ajcc
     * @return bool
     */
    protected function _countAjcc(&$matrix, $ajcc)
    {
        $i = null;
        switch (true) {
            case ($ajcc === 'IA'):   $i = 0; break;
            case ($ajcc === 'IB'):   $i = 1; break;
            case ($ajcc === 'IIA'):  $i = 2; break;
            case ($ajcc === 'IIB'):  $i = 3; break;
            case ($ajcc === 'IIC'):  $i = 4; break;
            case ($ajcc === 'IIIA'): $i = 5; break;
            case ($ajcc === 'IIIB'): $i = 6; break;
            case ($ajcc === 'IIIC'): $i = 7; break;
            case ($ajcc === 'IV'):   $i = 8; break;
        }
        if ($i !== null) {
            $matrix[0][$i]++;
            // Wurde gezählt
            return true;
        }
        // Nicht gezählt
        return false;
    }
}

?>
