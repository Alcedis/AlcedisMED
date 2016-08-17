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

class Concobox_prostata_e_5_3_1_Model_Helper_Section_E extends Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{
    /**
     * render renderE1Datum
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Datum($patient, $records)
    {
        switch ($this->getFormName($records)) {
            case 'nachsorge':
                return $records['nachsorge']['datum'];
                break;
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                return $record['datum'];
                break;
            case 'abschluss':
                return strlen($records['todesdatum']) > 0 ?
                    $records['todesdatum'] :
                    $records['letzter_kontakt_datum']
                ;
                break;
        }

        return null;
    }


    /**
     * render renderE1Quelle
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Quelle($patient, $records)
    {
        if ($this->getFormName($records) === 'nachsorge' &&
            $records['nachsorge']['org_id'] == $this->getParameter('org_id')
        ) {
            return 'EZ-nach';
        }

        return 'X';
    }


    /**
     * render renderE1Vitalstatus
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Vitalstatus($patient, $records)
    {
        if ($this->getFormName($records) === 'abschluss') {
            if (in_array($records['tod_tumorassoziation'], array('totn', 'tott')) === true) {
                if ($records['tod_ursache'] === 'C61') {
                    return 'TBT';
                } elseif (str_starts_with($records['tod_ursache'], 'C')) {
                    return 'TAT';
                }
                return 'TT';
            }
            return strlen($records['todesdatum']) > 0 ? 'TU' : 'L';
        }

        return 'L';
    }


    /**
     * render renderE1Tumorstatus
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Tumorstatus($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'abschluss':
                $result = 'X';
                break;
            case 'tumorstatus':
                $result = 'P';
                break;
            case 'nachsorge':
                $map = array('CR' => 'VR', 'PR' => 'TR', 'SD' => 'NC', 'PD' => 'P');
                $result =  $this->map($records['nachsorge']['response_klinisch'], $map, 'X');
                break;
        }

        return $result;
    }


    /**
     * render renderE1PSAWert
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1PSAWert($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                $result = $record['psa'];
                break;
            case 'nachsorge':
                $result = $records['nachsorge']['psa_labor_wert']['wert'];
                break;
        }

        return $result;
    }


    /**
     * render renderE1DKGFragebogenEingereicht
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1DKGFragebogenEingereicht($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                if ($records['nachsorge']['fb_dkg'] === '1') {
                    $result = 'J';
                } elseif ($records['nachsorge']['fb_dkg'] === '0') {
                    $result = 'N';
            }
            break;
            case 'tumorstatus':
                foreach ($records['anamnese'] as $record) {
                    if ($record['fb_dkg'] === '1') {
                        $result = 'J';
                        break;
                    } elseif ($record['fb_dkg'] === '0') {
                        $result = 'N';
                    }
            }
            break;
        }

        return $result;
    }


    /**
     * render renderE1Kontinenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Kontinenz($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                $result = $records['nachsorge']['iciq_ui'];
                break;
            case 'tumorstatus':
                foreach ($records['anamnese'] as $record) {
                    if (strlen($record['iciq_ui']) > 0) {
                        $result = $record['iciq_ui'];
                        break;
                    }
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1Potenz
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Potenz($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                $result = $records['nachsorge']['iief5'];

                break;

            case 'tumorstatus':
                foreach ($records['anamnese'] as $record) {
                    if (strlen($record['iief5']) > 0) {
                        $result = $record['iief5'];
                        break;
                    }
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1Lebensqualitaet
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Lebensqualitaet($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                $result = $records['nachsorge']['lq_dkg'];
                break;
            case 'tumorstatus':
                foreach ($records['anamnese'] as $record) {
                    if (strlen($record['lq_dkg']) > 0) {
                        $result = $record['lq_dkg'];
                        break;
                    }
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1Gesundheitszustand
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Gesundheitszustand($patient, $records)
    {
        $result =  null;

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                $result = $records['nachsorge']['gz_dkg'];

                break;

            case 'tumorstatus':
                foreach ($records['anamnese'] as $record) {
                    if (strlen($record['gz_dkg']) > 0) {
                        $result = $record['gz_dkg'];
                        break;
                    }
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1DiagnoseLokalrezidiv
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1DiagnoseLokalrezidiv($patient, $records)
    {
        $result =  'N';

        switch ($this->getFormName($records)) {
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                if (in_array('1', array($record['rezidiv_lokal'], $record['rezidiv_lk'])) === true) {
                    $result = 'J';
                }
                break;
            case 'abschluss':
                if (in_array($records['abschluss_grund'], array('lost', 'nnach'))) {
                    $result = 'X';
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1DiagnoseBiochemischenRezidiv
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1DiagnoseBiochemischenRezidiv($patient, $records)
    {
        $result =  'N';

        switch ($this->getFormName($records)) {
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                if ($record['rezidiv_psa'] === '1') {
                    $result = 'J';
                }
                break;
            case 'abschluss':
                if (in_array($records['abschluss_grund'], array('lost', 'nnach'))) {
                    $result = 'X';
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1DiagnoseFernmetastasierung
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1DiagnoseFernmetastasierung($patient, $records)
    {
        $result =  'N';

        switch ($this->getFormName($records)) {
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                if ($record['rezidiv_metastasen'] === '1') {
                    switch ($record['quelle_metastasen']) {
                        case 'fmprim':
                            $result = 'J-FMBT';
                            break;
                        case 'fmanderer':
                            $result = 'J-FBAT';
                            break;
                        default:
                            $result = 'J-QFMU';
                            break;
                    }
                }
                break;
            case 'abschluss':
                if (in_array($records['abschluss_grund'], array('lost', 'nnach'))) {
                    $result = 'X';
                }
                break;
        }

        return $result;
    }


    /**
     * render renderE1Zweittumor
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderE1Zweittumor($patient, $records)
    {
        $result =  'N';

        switch ($this->getFormName($records)) {
            case 'nachsorge':
                if ($records['nachsorge']['malignom'] === '1') {
                    $result = 'J';
                }
                break;
            case 'tumorstatus':
                $record = $records['tumorstatus']->getFirst();
                if ($record['zweittumor'] === '1') {
                    $result = 'J';
                }
                break;
            case 'abschluss':
                if (in_array($records['abschluss_grund'], array('lost', 'nnach'))) {
                    $result = 'X';
                }
                break;
        }

        return $result;
    }
}
