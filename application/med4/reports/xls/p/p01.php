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

class reportContentP01 extends reportExtensionP
{
    /**
     * generate
     *
     * @access  public
     * @return  void
     */
    public function generate()
    {
        $config = $this->loadConfigs('p01');

        $data = $this->loadRessource('p01');

        $this->convertReportData($data);

        $this->_title = $config['head_report'];
        $this->_data  = $data;

        $this->writeXLS();
    }

    /**
     * convertReportData
     *
     * @access  public
     * @param   array   $data
     * @return  array
     */
    public function convertReportData(array &$data)
    {
        foreach ($data as &$record) {

            $record['therapie_bezugsdatum'] = $this->_getReferenceDateTherapyLabel($record['therapie_bezugsdatum']);

            // remove fields for 01
            unset(
                $record['erkrankung_id'],
                $record['anlass_case'],
                $record['nz'],
                $record['g_original'],
                $record['revis_ops_eingriff'],
                $record['revis_ops_komplikation'],
                $record['leistungserbringer_raw'],
                $record['bezugsdatum_cpz_andere_behandlung_andere_behandlung'],
                $record['bezugsdatum_cpz_andere_behandlung_pall_strahlentherapie'],
                $record['bezugsdatum_cpz_andere_behandlung_therapie_systemisch'],
                $record['bezugsdatum_cpz_andere_behandlung_strahlentherapie'],
                $record['bezugsdatum_cpz_andere_behandlung_palliative_versorgung'],
                $record['def_perkutane_str_beginn'],
                $record['therapie_systemisch'],
                $record['strahlentherapie'],
                $record['str_permanent_seed_beginn'],
                $record['hdr_brachytherapie_beginn'],
                $record['aftercare_dates'],
                $record['bezug_rpe_kompl'],
                $record['beginn_sys'],
                $record['sit_start_date']
            );
        }
    }
}

?>
