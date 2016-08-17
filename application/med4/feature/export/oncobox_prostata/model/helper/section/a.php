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

class Concobox_prostata_e_5_3_1_Model_Helper_Section_A extends Concobox_prostata_e_5_3_1_Model_Helper_Section_Abstract
{

    /**
     * render renderAPatientID
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAPatientID($patient, $records)
    {
        return $patient['patient_nr'];
    }


    /**
     * render renderAGeschlecht
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAGeschlecht($patient, $records)
    {
        $gender = strtoupper($patient['geschlecht']);

        return $this->ifEmpty($gender, 'X');
    }


    /**
     * render renderAGeburtsJahr
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAGeburtsJahr($patient, $records)
    {
        return substr($patient['geburtsdatum'], 0, 4);
    }

    /**
     * render renderAGeburtsMonat
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAGeburtsMonat($patient, $records)
    {
        return substr($patient['geburtsdatum'], 5, 2);
    }


    /**
     * render renderAGeburtsTag
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAGeburtsTag($patient, $records)
    {
        return substr($patient['geburtsdatum'], 8, 2);
    }


    /**
     * render renderAOrgan
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAOrgan($patient, $records)
    {
        return 'PZ';
    }


    /**
     * render renderARegNr
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderARegNr($patient, $records)
    {
        return $this->getParameter('zentrum_id');
    }


    /**
     * render renderAHauptNebenStandort
     *
     * @access  public
     * @param   array   $patient
     * @param   array   $records
     * @return  string
     */
    public function renderAHauptNebenStandort($patient, $records)
    {
        return $this->getParameter('HauptNebenStandort');
    }
}
