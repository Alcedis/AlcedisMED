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

class reportContentPa07 extends reportExtensionPa
{
    public function generate($renderer)
    {
        $config = $this->loadConfigs('pa07');

        $this->_title = $config['head_report'];

        $paz01_1 = $this->loadRessource('pa01_1');

        if ($this->getParam('roh_daten') == 1) {
            $output = array();

            foreach ($paz01_1 as $row) {
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

            $matrix[2][0] = 0;

            unset($x, $y);

            $primaryCases = 0;
            $notCounted    = array();

            foreach ($paz01_1 as $row) {
                if ($this->IsTrue($row['primaerfall'])) {

                    $primaryCases++;

                    $row['uicc'] = (int) $row['uicc_nach_neoadj_th'] == 1 ? $row['uicc_prae'] : $row['uicc'];

                    if (str_starts_with($row['uicc'], array('IA', 'IB', 'IIA', 'IIB', 'III', 'IV')) === true) {
                        $matrix = $this->_addToMatrix($matrix, 0, $row['uicc']);

                        if ($row['op_primaerfall'] == 1) {
                            $matrix = $this->_addToMatrix($matrix, 1, $row['uicc']);
                        }
                    } else {
                        $notCounted[] = $this->_getOutput($row);
                    }
                }
            }

            $matrix[2][0] = $this->_getAllPancreasResections();

            $data = array();

            $data[] = array(
                'primary_cases'         => $config['prim'],
                'ia'                    => $matrix[0][0],
                'ib'                    => $matrix[0][1],
                'iia'                   => $matrix[0][2],
                'iib'                   => $matrix[0][3],
                'iii'                   => $matrix[0][4],
                'iv'                    => $matrix[0][5],
            );

            $data[] = array(
                'primary_cases'         => $config['op_prim'],
                'ia'                    => $matrix[1][0],
                'ib'                    => $matrix[1][1],
                'iia'                   => $matrix[1][2],
                'iib'                   => $matrix[1][3],
                'iii'                   => $matrix[1][4],
                'iv'                    => $matrix[1][5],
            );

            $data[] = array('');
            $data[] = array('');

            $data[] = array(
                'primary_cases'         => $config['count'],
                'ia'                    => $matrix[2][0],
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
                    $config['op_primaerfall'],
                    $config['uicc'],
                    $config['uicc_prae'],
                    $config['uicc_nach_neoadj_th']
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
            'nachname'            => $dataset['nachname'],
            'vorname'             => $dataset['vorname'],
            'geburtsdatum'        => $dataset['geburtsdatum'],
            'patient_nr'          => $dataset['patient_nr'],
            'primaerfall'         => $dataset['primaerfall'],
            'diagnose'            => $dataset['diagnose'],
            'op_primaerfall'      => $dataset['op_primaerfall'],
            'uicc'                => $dataset['uicc'],
            'uicc_prae'           => $dataset['uicc_prae'],
            'uicc_nach_neoadj_th' => $dataset['uicc_nach_neoadj_th']
        );
    }

    private function _addToMatrix($matrix, $index, $uicc)
    {
        switch (true) {
            case (str_starts_with($uicc, 'IA')):    $matrix[$index][0]++; break;
            case (str_starts_with($uicc, 'IB')):    $matrix[$index][1]++; break;
            case (str_starts_with($uicc, 'IIA')):   $matrix[$index][2]++; break;
            case (str_starts_with($uicc, 'IIB')):   $matrix[$index][3]++; break;
            case (str_starts_with($uicc, 'III')):   $matrix[$index][4]++; break;
            case (str_starts_with($uicc, 'IV')):    $matrix[$index][5]++; break;
        }

        return $matrix;
    }


    /**
     * _getAllPancreasResections
     *
     * @access  protected
     * @return  int
     */
    protected function _getAllPancreasResections()
    {
        $org_id    = $this->getParam('org_id');

        $datum_von = $this->_getVonDate();
        $datum_bis = $this->_getBisDate();

        $datum_von = ( strlen( $datum_von ) > 0 ) ? $datum_von : '0000-00-00';
        $datum_bis = ( strlen( $datum_bis ) > 0 ) ? $datum_bis : '2199-12-31';

        $query = "
            SELECT
                ein.eingriff_id
            FROM patient p
                INNER JOIN erkrankung e     ON e.patient_id = p.patient_id
                INNER JOIN eingriff ein     ON ein.erkrankung_id=e.erkrankung_id
                                                    AND ein.datum BETWEEN '{$datum_von}' AND '{$datum_bis}'
                                                    AND ein.org_id = '{$org_id}'
                INNER JOIN eingriff_ops ops ON ops.eingriff_id = ein.eingriff_id AND (ops.prozedur LIKE '5-524%' OR ops.prozedur LIKE '5-525%')

            WHERE
                p.org_id = '{$org_id}'
            GROUP BY
                p.patient_id
        ";

        $result = sql_query_array($this->_db, $query);

        return ($result !== false ? count($result) : 0);
    }
}

?>
