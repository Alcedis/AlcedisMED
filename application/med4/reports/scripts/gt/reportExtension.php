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

/**
 * Class reportExtensionGt
 */
class reportExtensionGt extends reportMath
{
    /**
     *
     * @var string
     */
    const SEPARATOR_ROWS = "\x01";


    /**
     *
     * @var string
     */
    const SEPARATOR_COLS = "\x02";


    /**
     * Convert data for gt04.1 report
     *
     * @param $data
     */
    protected function _convertGt041ReportData($data)
    {
        foreach ($data as &$dataset) {
            $addon = $dataset['addon'];
            $addon['datum_revisions_op_komplikation'] = $dataset['datum_revisions_op_komplikation'];

            unset(
                $dataset['patient_id'],
                $dataset['erkrankung_id'],
                $dataset['datum_revisions_op_komplikation'],
                $dataset['max_figo'],
                $dataset['anlass'],
                $dataset['start_date'],
                $dataset['end_date'],
                $dataset['h_beginn'],
                $dataset['041_ereignis'],
                $dataset['041_ende'],
                $dataset['042_ereignis'],
                $dataset['042_ende'],
                $dataset['043_ereignis'],
                $dataset['043_ende'],
                $dataset['044_ereignis'],
                $dataset['044_ende'],
                $dataset['045_ereignis'],
                $dataset['045_beginn'],
                $dataset['045_ende'],
                $dataset['addon']
            );

            $dataset = array_merge($dataset, $addon);
        }

        return $data;
    }


    /**
     * isPrimary
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function isPrimary($row)
    {
        return ((int) $row['primaerfall'] == 1);
    }


    /**
     * hasOperation
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function hasOperation($row)
    {
        return (strlen($row['datumprimaer_rezidiv_op']) > 0);
    }


    /**
     * hasNoOperation
     *
     * @access  public
     * @param   array  $row
     * @return  bool
     */
    public function hasNoOperation($row)
    {
        return ($this->hasOperation($row) === false);
    }


    /**
     * isStaging
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function isStaging($record)
    {
        return ($record['art_staging'] == '1');
    }


    /**
     * hasOpStaging
     *
     * @access  public
     * @param   array   $record
     * @return  bool
     */
    public function hasOpStaging($record)
    {
        return ($record['op_staging'] == '1');
    }


    /**
     * isDefinitive
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function isDefinitive($row)
    {
        return ($row['art_staging'] != '1');
    }


    /**
     * ['Diagnose' = C48* ODER 'Diagnose' = C56 ODER 'Diagnose' = C57.0]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca($row)
    {
        $diagnose = $row['diagnose'];

        return (str_starts_with($diagnose, 'C48') === true || $diagnose === 'C56' || $diagnose === 'C57.0');
    }


    /**
     * cgz_ovca UND Primärfall' = 1
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca_op_prim($row)
    {
        if ($this->cgz_ovca($row) === true && $this->isPrimary($row) === true) {
            return true;
        }

        return false;
    }


    /**
     * cgz_ovca_op_prim UND ['Staging-OP' = 1 ODER 'Datum Primär/Rezidiv-OP' = 1] UND 'R(lokal)' = 1 o. 2
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca_op_prim_stag($row)
    {
        if (
            $this->cgz_ovca_op_prim($row) === true
            && ($this->isStaging($row) === true || $this->hasOperation($row) === true)
            && in_array($row['r_lokal'], array(1, 2)) === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_ovca_op_prim UND 'Datum Primär-/Rezidiv-OP' = gefüllt
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca_op_prim_def($row)
    {
        if (
            $this->cgz_ovca_op_prim($row) === true
            && $this->hasOperation($row) === true
            && $this->hasOpStaging($row) === true
            && $row['r_lokal'] == '0'
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_ovca_op_prim UND 'Datum Primär-/Rezidiv-OP' = leer UND ['Staging-OP' ? 1 ODER ['Staging-OP' = 1 UND 'operatives Staging' = leer] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca_nop_prim($row)
    {
        if ($this->cgz_ovca_op_prim($row) === true &&
            $this->hasNoOperation($row) === true &&
            ($this->isStaging($row) === false || ($this->isStaging($row) === true && $this->hasOpStaging($row) === false))
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_ovca UND 'Primärfall' = 0
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ovca_nprim($row)
    {
        return ($this->cgz_ovca($row) === true && $this->isPrimary($row) === false);
    }


    /**
     * '['Diagnose' = C53* UND ['Morphologie' != 8890/3 || !=8930/3 || !=8933/3] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_zeca($row)
    {
        if (str_starts_with($row['diagnose'], 'C53') === true &&
            (in_array($row['morphologie'], array('8890/3', '8930/3', '8933/3')) === false)
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_zeca && UND 'Primärfall' = 1 UND 'Staging-OP' = 1 UND 'Datum Primär/Rezidiv-OP' = leer
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_zeca_op_prim_stag($row)
    {
        if ($this->cgz_zeca($row) === true &&
            $this->isPrimary($row) === true &&
            $this->isStaging($row) === true &&
            $this->hasNoOperation($row) === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_zeca UND 'Primärfall' = 1 UND 'Datum Primär-/Rezidiv-OP' = gefüllt
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_zeca_op_prim_def($row)
    {
        if ($this->cgz_zeca($row) === true &&
            $this->isPrimary($row) === true &&
            $this->hasOperation($row) === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_zeca UND 'Primärfall' = 1 UND 'Datum Primär-/Rezidiv-OP' = leer UND 'Staging-OP' != 1
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_zeca_nop_prim($row)
    {
        if ($this->cgz_zeca($row) === true &&
            $this->isPrimary($row) === true &&
            $this->hasNoOperation($row) === true &&
            $this->isStaging($row) === false
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_zeca && 'Primärfall' = 0
     *
     * @access
     * @param   $row
     * @return  bool
     */
    public function cgz_zeca_nprim($row)
    {
        if ($this->cgz_zeca($row) === true && $this->isPrimary($row) === false) {
            return true;
        }

        return false;
    }


    /**
     * ['Diagnose' = C53* UND ['Morphologie' = 8890/3 ODER 'Morphologie' = 8930/3 ODER 'Morphologie' = 8933/3]]
     * ||
     * 'Diagnose' = C54.0|C54.2|C54.3|C54.8|C54.9|C55|C57.1*|C57.2|C57.3|C57.4|C57.7|C57.8|C57.9|C58|C79.82
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_diag_sonst($row)
    {
        $diagnose = $row['diagnose'];

        $relevantDiagnosis = array(
            'C54.0',
            'C54.2',
            'C54.3',
            'C54.8',
            'C54.9',
            'C55',
            'C57.2',
            'C57.3',
            'C57.4',
            'C57.7',
            'C57.8',
            'C57.9',
            'C58',
            'C79.82'
        );

        if (str_starts_with($row['diagnose'], 'C53') === true &&
            (in_array($row['morphologie'], array('8890/3', '8930/3', '8933/3')) === true)
        ) {
            return true;
        }

        if (str_starts_with($diagnose, 'C57.1') === true || in_array($diagnose, $relevantDiagnosis)) {
            return true;
        }

        return false;
    }


    /**
     * [ 'Primärfall = 1' UND
    [ [ ['Diagnose' = C48* ODER C56 ODER C57.0] UND ['FIGO' = IA ODER IB ODER IC ODER IIA ODER IIB ODER IIIA ODER IIIB ODER IIIC ODER IV] ]
    ODER
    [ 'Diagnose' = C53* UND [ 'Morphologie' ? 8890/3 ODER 8930/3 ODER 8933/3] UND ['FIGO' = IA1 ODER IA2 ODER IB1 ODER IB2 ODER IIA* ODER IIB ODER IIIA ODER IIIB ODER IVA ODER IVB] ] ] ]

    ODER
    [ 'Primärfall' = 0 UND [ ['Diagnose' = C48* ODER C56 ODER C57.0] ODER [ 'Diagnose' = C53* UND ['Morphologie' ? 8890/3 ODER 8930/3 ODER 8933/3] ] ] ]

    ODER
    [ 'Diagnose' = D39.1 UND 'G' = GB ]
    ODER
    [ 'Diagnose' = C53* UND ['Morphologie' = 8890/3 ODER 8930/3 ODER 8933/3] ]
    ODER
    ['Diagnose' = C51* ODER C52 ODER C54* ODER C55 ODER C57* (ohne C57.0) ODER C58 ODER C79.82] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ges($row)
    {
        $condition = (
            ($this->isPrimary($row) === true && $this->cgz_ges_cond1($row) === true) ||
            ($this->isPrimary($row) === true && $this->cgz_ges_cond2($row) === true) ||
            ($this->isPrimary($row) === false && ($this->cgz_ovca($row) === true || $this->cgz_zeca($row) === true)) ||
            ($this->isBorderlineOvar($row) === true) ||
            ($this->cgz_ges_cond3($row) === true) ||
            ($this->cgz_ges_cond4($row) === true)
        );

        return $condition;
    }


    /**
     * cgz_ges_ext
     *
     *  ['Primärfall' = 1
    UND
    ['Staging-OP' = 1 UND 'Operatives Staging' = 1
    UND ['Diagnose' = C48* ODER C56 ODER C57.0]
    UND ['FIGO' = IA ODER IB ODER IC ODER IIA ODER IIB ODER IIIA ODER IIIB ODER IIIC ODER IV] ]

    ODER

    ['Staging-OP' = 1
    UND
    ['Diagnose' = C53* UND ['Morphologie' ? 8890/3 ODER 8930/3 ODER 8933/3] UND ['FIGO' = IA1 ODER IA2 ODER IB1 ODER IB2 ODER IIA* ODER IIB ODER IIIA ODER IIIB ODER IVA ODER IVB] ]
    ODER
    ['Diagnose' = D39.1 UND 'G' = GB ]
    ODER
    ['Diagnose' = C53* UND ['Morphologie' = 8890/3 ODER 8930/3 ODER 8933/3] ]
    ODER
    ['Diagnose' = C51* ODER C52 ODER C54* ODER C55 ODER C57* (ohne C57.0) ODER C58 ODER C79.82] ] ]
     *
     * @access  public
     * @param   array $row
     * @return  bool
     */
    public function cgz_ges_ext(array $row)
    {
        $condition = (
            ($this->isPrimary($row) === true && $this->isStaging($row) === true && $this->hasOpStaging($row) === true && $this->cgz_ges_cond1($row) === true) ||
            ($this->isStaging($row) === true && (
                ($this->cgz_ges_cond2($row) === true) ||
                 $this->isBorderlineOvar($row) === true ||
                 $this->cgz_ges_cond3($row) === true ||
                 $this->cgz_ges_cond4($row) === true
            ))
        );

        return $condition;
    }


    /**
     * [ ['Diagnose' = C48* ODER C56* ODER C57.0] UND
     * ['FIGO' = IA ODER IB ODER IC ODER IIA ODER IIB ODER IIIA ODER IIIB ODER IIIC ODER IV] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ges_cond1($row)
    {
        if ((str_starts_with($row['diagnose'], array('C48', 'C56')) === true || $row['diagnose'] === 'C57.0') &&
            $this->check_figo($row, array('IA', 'IB', 'IC', 'IIA', 'IIB', 'IIIA', 'IIIB', 'IIIC', 'IV')) === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * cgz_zeca & UND ['FIGO' = IA1 ODER IA2 ODER IB1 ODER IB2 ODER IIA ODER IIB ODER IIIA ODER IIIB ODER IVA ODER IVB]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ges_cond2($row)
    {
        if ($this->cgz_zeca($row) === true &&
            $this->check_figo($row, array('IA1', 'IA2', 'IB1', 'IB2', 'IIA*', 'IIB', 'IIIA', 'IIIB', 'IVA', 'IVB'))
        ) {
            return true;
        }

        return false;
    }


    /**
     * [ 'Diagnose' = C53* UND ['Morphologie' = 8890/3 ODER 8930/3 ODER 8933/3] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ges_cond3($row)
    {
        if (str_starts_with($row['diagnose'], 'C53') === true &&
            in_array($row['morphologie'], array('8890/3', '8930/3', '8933/3')) === true) {
            return true;
        }

        return false;
    }


    /**
     * ['Diagnose' = C51* ODER C52 ODER C54* ODER C55 ODER C57* (ohne C57.0) ODER C58 ODER C79.82] ]
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_ges_cond4($row)
    {
        if (in_array($row['diagnose'], array('C52', 'C55', 'C58', 'C79.82')) === true ||
            (str_starts_with($row['diagnose'], array('C51', 'C54', 'C57')) === true && $row['diagnose'] !== 'C57.0')) {
            return true;
        }

        return false;
    }


    /**
     * 'Primärfall' = 1 && cgz_ges
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_prim_ges($row)
    {
        if ($this->isPrimary($row) === true && $this->cgz_ges($row) === true) {
            return true;
        }

        return false;
    }


    /**
     * 'Datum Primär/Rezidiv-OP' = gefüllt && cgz_ges
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function cgz_op_ges($row)
    {
        if (($this->hasOperation($row) === true && $this->cgz_ges($row) === true) ||
            $this->cgz_ges_ext($row) === true
        ) {
            return true;
        }

        return false;
    }


    /**
     * check_figo
     *
     * @access  public
     * @param   array   $row
     * @param   array   $values
     * @return  bool
     */
    public function check_figo($row, $values)
    {
        $values = is_array($values) === false ? array($values) : $values;

        if ($row['figo_nach_neoadj_th'] !== null) {
            $figo = $row['figo_prae'];
        } else {
            $figo = $row['figo'];
        }

        $inArray = array();
        $startsWith = array();

        foreach ($values as $value) {
            if (str_ends_with($value, '*') === true) {
                $startsWith[] = substr($value, 0, -1);
            } else {
                $inArray[] = $value;
            }
        }

        return (in_array($figo, $inArray) || str_starts_with($figo, $startsWith));
    }


    /**
     * 'Diagnose' = D39.1 UND 'G' = GB
     *
     * @access  public
     * @param   array   $row
     * @return  bool
     */
    public function isBorderlineOvar($row)
    {
        return ($row['diagnose'] === 'D39.1' && $row['g'] === 'GB');
    }
}
?>
