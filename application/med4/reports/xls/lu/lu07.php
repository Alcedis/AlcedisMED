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

class reportContentLu07 extends reportExtensionLu
{
    public function generate($renderer)
    {
        $config = $this->loadConfigs('lu07');

        $this->_title = $config['head_report'];

        $luz01_1 = $this->loadRessource('lu01_1');

        if ($this->getParam('roh_daten') == 1) {
            $output = array();

            foreach ($luz01_1 as $row) {
                $output[] = $this->_getOutput($row);
            }

            $this->_data = $output;

        } else {
            $matrix = array();

            for ($x = 0; $x < 2; $x++) {
                for ($y = 0; $y < 7; $y++) {
                    $matrix[$x][$y] = 0;
                }
            }

            unset($x, $y);

            $primaryCases = 0;
            $notCounted    = array();

            foreach ($luz01_1 as $row) {
                if ( ( $this->IsTrue($row['primaerfall'] ) ) &&
                     ( strlen( $row[ 'bezugsdatum' ] ) > 0 ) ) {

                    $primaryCases++;

                    if ('1' == $row['uicc_nach_neoadj_th']) {
                        $row['uicc'] = $row['uicc_praetherapeutisch'];
                    }

                    if ($this->_checkUiccValues($row['uicc'], array('IA', 'IB', 'IIA', 'IIB', 'IIIA*', 'IIIB', 'IV'))) {
                        if ((int) $row['lungenresektion_durchgefuehrt'] == 1) {
                            $matrix = $this->_addToMatrix($matrix, 0, $row['uicc']);
                        } else if ((int) $row['lungenresektion_durchgefuehrt'] == 0) {
                            $matrix = $this->_addToMatrix($matrix, 1, $row['uicc']);
                        } else {
                            $notCounted[] = $this->_getOutput($row);
                        }
                    } else {
                        $notCounted[] = $this->_getOutput($row);
                        continue;
                    }
                }
            }

            $data = array();

            $data[] = array(
                'primary_cases_bro'     => $config['op_prim'],
                'ia'                    => $matrix[0][0],
                'ib'                    => $matrix[0][1],
                'iia'                   => $matrix[0][2],
                'iib'                   => $matrix[0][3],
                'iiia'                  => $matrix[0][4],
                'iiib'                  => $matrix[0][5],
                'iv'                    => $matrix[0][6],
            );

            $data[] = array(
                'primary_cases_bro'     => $config['non_op_prim'],
                'ia'                    => $matrix[1][0],
                'ib'                    => $matrix[1][1],
                'iia'                   => $matrix[1][2],
                'iib'                   => $matrix[1][3],
                'iiia'                  => $matrix[1][4],
                'iiib'                  => $matrix[1][5],
                'iv'                    => $matrix[1][6],
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
                    $config['seite'],
                    $config['diagnose'],
                    $config['uicc'],
                    $config['uicc_praetherapeutisch'],
                    $config['lungenresektion_durchgefuehrt'],
                    $config['datum_primaer_op_oder_rezidiv_op']
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
            'nachname'                          => $dataset['nachname'],
            'vorname'                           => $dataset['vorname'],
            'geburtsdatum'                      => $dataset['geburtsdatum'],
            'patient_nr'                        => $dataset['patient_nr'],
            'primaerfall'                       => $dataset['primaerfall'],
            'seite'                             => $dataset['seite'],
            'diagnose'                          => $dataset['diagnose'],
            'uicc'                              => $dataset['uicc'],
            'uicc_praetherapeutisch'            => $dataset['uicc_praetherapeutisch'],
            'lungenresektion_durchgefuehrt'     => $dataset['lungenresektion_durchgefuehrt'],
            'datum_primaer_op_oder_rezidiv_op'  => $dataset['datum_primaer_op_oder_rezidiv_op']
        );
    }

    private function _addToMatrix($matrix, $index, $uicc)
    {
        switch (true) {
            case ($uicc == 'IA'):                  $matrix[$index][0]++; break;
            case ($uicc == 'IB'):                  $matrix[$index][1]++; break;
            case ($uicc == 'IIA'):                 $matrix[$index][2]++; break;
            case ($uicc == 'IIB'):                 $matrix[$index][3]++; break;
            case (str_starts_with($uicc, 'IIIA')): $matrix[$index][4]++; break;
            case ($uicc == 'IIIB'):                $matrix[$index][5]++; break;
            case ($uicc == 'IV'):                  $matrix[$index][6]++; break;
        }

        return $matrix;
    }


    /**
     *
     *
     * @access
     * @param $checkUicc
     * @param $uiccList
     * @return bool
     */
    protected function _checkUiccValues($checkUicc, $uiccList) {
        if (false === is_array($uiccList)) {
            $uiccList = explode(",", $uiccList);
        }
        foreach ($uiccList as $uicc) {
            if (false !== ($pos = strpos($uicc, "*"))) {
                if (true === str_starts_with($checkUicc, substr($uicc, 0, $pos))) {
                    return true;
                }
            }
            else {
                if ($checkUicc == $uicc) {
                    return true;
                }
            }
        }
        return false;
    }

}

?>
